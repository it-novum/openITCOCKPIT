<?php
// Copyright (C) <2020>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, version 3 of the License.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//    License agreement and license key will be shipped with the order
//    confirmation.

declare(strict_types=1);

namespace App\Controller;

use App\Form\AgentConfigurationForm;
use App\Model\Entity\Changelog;
use App\Model\Entity\Host;
use App\Model\Table\AgentchecksTable;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\AgentconnectorTable;
use App\Model\Table\AgenthostscacheTable;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Exception\GuzzleException;
use itnovum\openITCOCKPIT\Agent\AgentCertificateData;
use itnovum\openITCOCKPIT\Agent\AgentConfiguration;
use itnovum\openITCOCKPIT\Agent\AgentServicesToCreate;
use itnovum\openITCOCKPIT\Agent\HttpLoader;
use itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentconfigsFilter;
use itnovum\openITCOCKPIT\Filter\AgentconnectorAgentsFilter;
use itnovum\openITCOCKPIT\Filter\AgenthostscacheFilter;
use itnovum\openITCOCKPIT\Filter\HostFilter;

class AgentconnectorController extends AppController {

    //public $autoRender = false;

    /* TODO:
     *
     * Need a monthly cronjob to check if the CA will expire in 2 Month
     * It creates a new CA certificate (or maybe extend the existing)?
     * User can issue a new CA using the frontend.
     *
     * Things to do on creating a new CA certificate:
     *  - Create a second CA certificate
     *  - Use new CA for incoming certificate requests
     *  - Update certificate of all agents in pull mode with the old CA using updateCrt post request (in connectToAgent function)
     *  - Delete old CA
     *
     *
     * Push worker:
     *  - accept all requests with matching hostuuid and certificate checksum
     *  - return hint, that a new CA is available ('new_ca'), if certificate creation date (in database) is older than the creation date of the current CA
     *  - add 'ca_checksum' of this agent entity (AgentconnectorTable) to the hint to confirm it comes from the right CA-Server
     *
     */


    /**
     * @return \Cake\Http\Response
     * @deprecated
     */
    public function certificate() {
        if (!$this->isJsonRequest()) {
            throw new BadRequestException();
        }
        $this->autoRender = false;

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        if (!empty($this->request->getData('csr')) && !empty($this->request->getData('hostuuid'))) {   //is certificate request
            $hostuuid = $this->request->getData('hostuuid');

            if (!empty($this->request->getData('checksum'))) {  //maybe a request from an already known agent? (match checksum of old agent crt)
                if ($AgentconnectorTable->trustIsValid($this->request->getData('checksum'), $hostuuid)) {
                    $json = json_encode($AgentCertificateData->getAgentCsr($hostuuid, $this->request->getData('csr'), $AgentconnectorTable));
                    return $this->response->withType("application/json")->withStringBody($json);
                } else {    //untrusted threw frontend / maybe an imitator / unknown error?
                    return $this->response->withType("application/json")->withStringBody(json_encode(['unknown' => true]));
                }
            } else {    //not a request from an agent that has received a predecessor certificate
                if ($AgentconnectorTable->isTrustedFromUser($hostuuid)) {
                    if ($AgentconnectorTable->certificateNotYetGenerated($hostuuid)) {
                        $json = json_encode($AgentCertificateData->getAgentCsr($hostuuid, $this->request->getData('csr'), $AgentconnectorTable));
                        return $this->response->withType("application/json")->withStringBody($json);
                    }
                    return $this->response->withType("application/json")->withStringBody(json_encode(['checksum_missing' => true]));
                } else {    //definitely not a request from a known agent!
                    $hadErrors = false;
                    if (!$AgentconnectorTable->getByHostUuid($hostuuid)) {
                        $hadErrors = $AgentconnectorTable->addAgent($hostuuid, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null);
                    }

                    if ($hadErrors) {
                        return $this->response->withType("application/json")->withStringBody(json_encode(['error' => 'could not save data']));
                    } else {
                        //should return ['unknown' => true] if agent is unknown and needs to be confirmed by an user
                        //echo json_encode(['unknown' => true]);
                        return $this->response->withType("application/json")->withStringBody(json_encode(['unknown' => true]));
                    }
                }
            }
        }
    }

    /**
     * @deprecated
     */
    public function agents() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $AgentconnectorAgentsFilter = new AgentconnectorAgentsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AgentconnectorAgentsFilter->getPage());

        $unTrustedAgents = $AgentconnectorTable->getAgentsIndex($AgentconnectorAgentsFilter, $PaginateOMat);

        foreach ($unTrustedAgents as $key => $agentconnector) {
            if (isset($agentconnector['Agentconnector']) && isset($agentconnector['Agentconnector']['hostuuid'])) {
                try {
                    $host = $HostsTable->getHostByUuid($agentconnector['Agentconnector']['hostuuid']);

                    $unTrustedAgents[$key]['Host'] = [
                        'id'   => $host->get('id'),
                        'name' => $host->get('name')
                    ];

                } catch (RecordNotFoundException $e) {
                    //No host for this agent configuration - delete it
                    $entity = $AgentconnectorTable->get($agentconnector['Agentconnector']['id']);
                    $AgentconnectorTable->delete($entity);

                    unset($unTrustedAgents[$key]);
                }

            }
        }

        $this->set('unTrustedAgents', $unTrustedAgents);
        $toJson = ['unTrustedAgents', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['unTrustedAgents', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @deprecated
     */
    public function untrustedAgents() {
        return;
    }

    /**
     * @param null $action
     * @param null $id
     * @deprecated
     */
    public function pullConfigurations($action = null, $id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AgentconfigsTable $AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        if ($this->request->is('post') && $action !== null && $id !== null) {
            if ($AgentconfigsTable->existsById($id)) {
                if ($action == 'edit') {
                    $Agentconfig = $AgentconfigsTable->get($id);
                    $Agentconfig->setAccess('id', false);
                    $Agentconfig->setAccess('push_noticed', false);
                    $Agentconfig = $AgentconfigsTable->patchEntity($Agentconfig, $this->request->getData('Agentconfig'));
                    $AgentconfigsTable->save($Agentconfig);
                    if (!$Agentconfig->hasErrors()) {
                        $this->set('success', true);
                        $this->viewBuilder()->setOption('serialize', ['success']);
                        return;
                    } else {
                        $this->set('errors', $Agentconfig->getErrors());
                        $this->viewBuilder()->setOption('serialize', ['errors']);
                        return;
                    }
                } else if ($action == 'delete') {
                    $Agentconfig = $AgentconfigsTable->get($id);
                    if ($AgentconfigsTable->delete($Agentconfig)) {
                        $this->set('success', true);
                        $this->viewBuilder()->setOption('serialize', ['success']);
                        return;
                    }
                }
            }
            $this->set('success', false);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $AgentconfigsFilter = new AgentconfigsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AgentconfigsFilter->getPage());

        $pullConfigurations = $AgentconfigsTable->getForList($AgentconfigsFilter, $PaginateOMat);

        $this->set('pullConfigurations', $pullConfigurations);
        $toJson = ['pullConfigurations', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['pullConfigurations', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function pushCache($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AgenthostscacheTable $AgenthostscacheTable */
        $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');

        if ($this->request->is('post') && $id !== null) {
            if ($AgenthostscacheTable->existsById($id)) {
                $Agenthostscache = $AgenthostscacheTable->get($id);
                if ($AgenthostscacheTable->delete($Agenthostscache)) {
                    $this->set('success', true);
                    $this->viewBuilder()->setOption('serialize', ['success']);
                    return;
                }
            }
            $this->set('success', false);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $AgenthostscacheFilter = new AgenthostscacheFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AgenthostscacheFilter->getPage());
        $pushCache = $AgenthostscacheTable->getForList($AgenthostscacheFilter, $PaginateOMat);

        $this->set('pushCache', $pushCache);
        $toJson = ['pushCache', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['pushCache', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @param $agenthostscacheId
     * @deprecated
     */
    public function downloadPushedCheckdata($agenthostscacheId) {
        /** @var AgenthostscacheTable $AgenthostscacheTable */
        $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');

        if (!$AgenthostscacheTable->existsById($agenthostscacheId)) {
            new NotFoundException();
        }

        $Agenthostscache = $AgenthostscacheTable->get($agenthostscacheId);
        $checkdata = $Agenthostscache->get('checkdata');
        header("Content-type: application/json");
        header("Content-Disposition: attachment; filename=openITCOCKPIT-Agent-Push-Checkdata_" . $agenthostscacheId . ".json");
        echo $checkdata;
        die();
    }

    /**
     * @throws MissingParameterExceptions
     * @deprecated
     */
    public function changetrust() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        if (empty($this->request->getData('id'))) {
            throw new MissingParameterExceptions('Agent id is missing!');
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');

        $id = intval($this->request->getData('id'));
        $trust = boolval($this->request->getData('trust'));

        $agent = $AgentconnectorTable->get($id);
        $agent = $AgentconnectorTable->patchEntity($agent, ['trusted' => $trust]);
        $AgentconnectorTable->save($agent);
        if ($agent->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $agent->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
    }

    /**
     * @param null $id
     * @throws MissingParameterExceptions
     * @deprecated
     */
    public function delete($id = null) {
        if (!$this->isJsonRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($id === null) {
            throw new MissingParameterExceptions('Agent id is missing!');
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');

        if (!$AgentconnectorTable->existsById($id)) {
            throw new NotFoundException(__('Invalid agent'));
        }
        $AgentconnectorTable->delete($AgentconnectorTable->get($id));
    }

    /**
     * @deprecated
     */
    public function updateCheckdata() {
        if (!$this->isJsonRequest()) {
            throw new BadRequestException();
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
        /** @var AgentconfigsTable $AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        $receivedChecks = 0;

        if (!empty($this->request->getData('checkdata')) && !empty($this->request->getData('hostuuid'))) {
            $AgentconfigsTable->updatePushNoticedForHostIfConfigExists($this->request->getData('hostuuid'), true);

            if ($AgentconnectorTable->isTrustedFromUserAndSaveAgentconnectorIfMissing($this->request->getData())) {
                if (!$AgentconnectorTable->certificateNotYetGenerated($this->request->getData('hostuuid')) && !empty($this->request->getData('checksum'))) {  //should have a certificate!
                    if ($AgentconnectorTable->trustIsValid($this->request->getData('checksum'), $this->request->getData('hostuuid'))) {
                        $receivedChecks = $this->processUpdateCheckdata($this->request->getData('hostuuid'), $this->request->getData('checkdata', '{}'));
                        //if new ca certificate was generated, echo new_ca with old ca checksum
                    } else {    //trusted, but certificate is not valid! do not process checkdata!
                        //maybe frontend hint, that the agent certificate has changed (and if it should be trusted)
                    }
                } else {    //does not have a certificate or autossl option was disabled after creation
                    $receivedChecks = $this->processUpdateCheckdata($this->request->getData('hostuuid'), $this->request->getData('checkdata', '{}'), false);
                }
            } else {
                //Agent is not trusted yet - only sache data to cache
                /** @var AgenthostscacheTable $AgenthostscacheTable */
                $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');
                $AgenthostscacheTable->saveCacheData($this->request->getData('hostuuid'), $this->request->getData('checkdata', '{}'));
            }
        }

        $this->set('receivedChecks', $receivedChecks);
        $this->viewBuilder()->setOption('serialize', ['receivedChecks']);
    }

    /**
     * @param $hostuuid
     * @param $checkdata
     * @param bool $passDataToNagios
     * @return int
     * @deprecated
     */
    private function processUpdateCheckdata($hostuuid, $checkdata, $passDataToNagios = true) {
        /** @var AgenthostscacheTable $AgenthostscacheTable */
        $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');
        $AgenthostscacheTable->saveCacheData($hostuuid, $checkdata);

        require_once "/opt/openitc/receiver/vendor/autoload.php";


        $CheckConfig = new \itnovum\openITCOCKPIT\Checks\Receiver\CheckConfig('/opt/openitc/receiver/etc/production.json');
        $config = $CheckConfig->getConfigByHostName($this->request->getData('hostuuid'));

        $GearmanClient = new Gearman();
        $receivedChecks = 0;

        if (isset($config['checks']) && is_array($config['checks']) && isset($config['mode']) && $config['mode'] === 'push' && $passDataToNagios === true) {
            foreach ($config['checks'] as $pluginConfig) {
                $pluginName = $pluginConfig['plugin'];

                $pluginClassName = sprintf('itnovum\openITCOCKPIT\Checks\Receiver\Plugins\%s', $pluginName);
                if (!class_exists($pluginClassName)) {
                    //Unknown Plugin
                    continue;
                }

                /** @var  $Plugin \itnovum\openITCOCKPIT\Checks\Receiver\Plugins\PluginInterface */
                $Plugin = new $pluginClassName($pluginConfig, json_decode($checkdata, true));

                $pluginOutput = $Plugin->getOutput();
                if (strlen($Plugin->getPerfdataSerialized()) > 0) {
                    $pluginOutput .= '|' . $Plugin->getPerfdataSerialized();
                }

                $GearmanClient->sendBackground('cmd_external_command', [
                    'command'     => 'PROCESS_SERVICE_CHECK_RESULT',
                    'parameters'  => [
                        'hostUuid'      => $config['uuid'],
                        'serviceUuid'   => $pluginConfig['uuid'],
                        'status_code'   => $Plugin->getStatuscode(),
                        'plugin_output' => $pluginOutput,
                        'long_output'   => ''
                    ],
                    'satelliteId' => 0 // Agent check results are always Master system!,
                ]);

                $receivedChecks++;
            }
        }

        return $receivedChecks;
    }

    /**
     * @param null $uuid
     * @throws MissingParameterExceptions
     * @deprecated
     */
    public function sendNewAgentConfig($uuid = null) {
        if (!$this->isJsonRequest()) {
            return;
        }

        if (empty($this->request->getData('config'))) {
            throw new MissingParameterExceptions('config is missing!');
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var AgentconfigsTable $AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        $hostId = $HostsTable->getHostIdByUuid($uuid);
        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        $this->set('success', 'false');
        if ($AgentconfigsTable->existsByHostId($hostId)) {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);

            $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
            $response = $HttpLoader->updateAgentConfig($this->request->getData('config'));
            $this->set('success', $response['success']);

            if ($response['success'] === true) {
                $agentconfigEntity = $AgentconfigsTable->getConfigOrEmptyEntity($hostId);

                $agentconfig['modified'] = FrozenTime::now();
                $agentconfig['basic_auth'] = 0;
                $agentconfig['username'] = '';
                $agentconfig['password'] = '';
                if ($this->request->getData('config')['auth'] !== '') {
                    $agentconfig['basic_auth'] = 1;
                    $agentconfig['username'] = explode(':', $this->request->getData('config')['auth'])[0];
                    $agentconfig['password'] = explode(':', $this->request->getData('config')['auth'])[1];
                }
                $agentconfig['port'] = $this->request->getData('config')['port'];
                //$agentconfig['use_https'] = intval($this->request->getData('config')['try-autossl'] === 'true' || $this->request->getData('config')['try-autossl'] === true);
                $agentconfigEntity = $AgentconfigsTable->patchEntity($agentconfigEntity, $agentconfig);
                $AgentconfigsTable->save($agentconfigEntity);
            }
        }
        $this->viewBuilder()->setOption('serialize', ['success']);
    }



    /**
     * @param null $uuid
     * @throws MissingParameterExceptions
     * @deprecated
     */
    public function getServicesToCreateByHostUuid($uuid = null) {
        if (!$this->isJsonRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($uuid === null) {
            throw new MissingParameterExceptions('Host uuid is missing!');
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');
        /** @var AgenthostscacheTable $AgenthostscacheTable */
        $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');

        $hostId = $HostsTable->getHostIdByUuid($uuid);
        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        $services = $ServicesTable->getServicesByHostIdForAgent($hostId, OITC_AGENT_SERVICE, false);
        $services = $services->toArray();

        $this->set('servicesToCreate', '');
        $this->set('config', '');
        $this->set('system', 'windows');
        $this->set('mode', '');
        $this->set('error', '');

        if ($AgenthostscacheTable->existsByHostuuid($uuid)) {
            $Agenthostscache = $AgenthostscacheTable->getByHostUuid($uuid);

            if ($Agenthostscache->checkdata !== null && $Agenthostscache->checkdata !== '') {
                $agentJsonOutput = json_decode($Agenthostscache->checkdata, true);
                $this->set('mode', 'push');
            }
        }

        if ($AgentconfigsTable->existsByHostId($hostId)) {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);

            $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
            try {
                $response = $HttpLoader->queryAgent(true);

                if (isset($response['response']) && !empty($response['response'])) {
                    $agentJsonOutput = $response['response'];
                }
                if (isset($response['config']) && $response['config'] !== '' && !empty($response['config'])) {
                    $this->set('config', $response['config']);
                }
                $this->set('mode', 'pull');
            } catch (\Exception | GuzzleException $e) {
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'SSL routines:ssl3_get_record:wrong version number') !== false) {
                    $HttpLoader->updateAgentProtocol(!boolval($agentconfig['use_https']));
                }
                $this->set('mode', 'could-be-pull');
                $this->set('error', $errorMessage);
            }
        }

        if (isset($agentJsonOutput) && !empty($agentJsonOutput)) {
            if (isset($agentJsonOutput['agent']) && isset($agentJsonOutput['agent']['system']) && trim($agentJsonOutput['agent']['system']) != '') {
                $this->set('system', strtolower(trim($agentJsonOutput['agent']['system'])));
            }
            $AgentServicesToCreate = new AgentServicesToCreate($agentJsonOutput, $AgentchecksTable->getAgentchecksForMapping(), $hostId, $services);

            $this->set('servicesToCreate', $AgentServicesToCreate->getServicesForFrontend());
        }
        $this->viewBuilder()->setOption('serialize', ['servicesToCreate', 'mode', 'config', 'system', 'error']);
    }

    /**
     * @throws MissingParameterExceptions
     * @deprecated
     */
    public function createServices() {
        if (!$this->isJsonRequest()) {
            return;
        }

        if (empty($this->request->getData('hostId'))) {
            throw new MissingParameterExceptions('hostId parameter is missing!');
        }

        $serviceConfigs = $this->request->getData('serviceConfigs');
        $hostId = $this->request->getData('hostId');

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');
        /** @var AgenthostscacheTable $AgenthostscacheTable */
        $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->get($hostId);
        $hostuuid = $HostsTable->getHostUuidById($hostId);
        $services = $ServicesTable->getServicesByHostIdForAgent($hostId, OITC_AGENT_SERVICE, false);
        $services = $services->toArray();

        if ($AgenthostscacheTable->existsByHostuuid($hostuuid)) {
            $Agenthostscache = $AgenthostscacheTable->getByHostUuid($hostuuid);

            if ($Agenthostscache->checkdata !== null && $Agenthostscache->checkdata !== '') {
                $agentJsonOutput = json_decode($Agenthostscache->checkdata, true);
            }
        }

        if ($AgentconfigsTable->existsByHostId($hostId)) {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);

            try {
                $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
                $response = $HttpLoader->queryAgent(false);

                if (isset($response['response']) && !empty($response['response'])) {
                    $agentJsonOutput = $response['response'];
                    $AgentconfigsTable->updatePushNoticedForHostIfConfigExists($hostuuid, false);
                }
            } catch (\Exception | GuzzleException $e) {
                throw new \Exception('Could not connect to agent to fetch current check data! - ' . $e->getMessage());
            }
        }

        if (isset($agentJsonOutput) && !empty($agentJsonOutput)) {
            $AgentServicesToCreate = new AgentServicesToCreate($agentJsonOutput, $AgentchecksTable->getAgentchecksForMapping(), $hostId, $services);
            $servicesToCreate = $AgentServicesToCreate->getServicesToCreate();
        }

        foreach ($serviceConfigs as $serviceConfig) {
            foreach ($servicesToCreate as $receiverPlugin) {
                foreach ($receiverPlugin as $service) {
                    if ($service['name'] === $serviceConfig['name'] && $service['servicetemplate_id'] === $serviceConfig['servicetemplate_id']) {
                        $this->createRealService($service, $host);
                        continue 3;
                    }
                }
            }
        }

        if ($this->request->getData('tryAutosslInPullMode') === true || $this->request->getData('tryAutosslInPullMode') === 'true') {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);
            $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
            $response = $HttpLoader->sendCertificateToAgent();
        }

        if (isset($this->serviceCreationErrors) && is_array($this->serviceCreationErrors) && !empty($this->serviceCreationErrors)) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $this->serviceCreationErrors);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('success', 'true');
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    /**
     * @param $serviceInput
     * @param Host $host
     * @deprecated
     */
    private function createRealService($serviceInput, Host $host) {
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $request = ['Service' => $serviceInput];
        $request['Host'] = [
            [
                'id'   => $host->get('id'),
                'name' => $host->get('name')
            ]
        ];

        $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($request['Service']['servicetemplate_id']);


        $servicename = $request['Service']['name'];
        if ($servicename === null || $servicename === '') {
            $servicename = $servicetemplate['Servicetemplate']['name'];
        }

        $ServiceComparisonForSave = new ServiceComparisonForSave(
            $request,
            $servicetemplate,
            $HostsTable->getContactsAndContactgroupsById($host->get('id')),
            $HosttemplatesTable->getContactsAndContactgroupsById($host->get('hosttemplate_id'))
        );
        $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();
        $serviceData['uuid'] = UUID::v4();
        $serviceData['service_type'] = OITC_AGENT_SERVICE;

        //Add required fields for validation
        $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
        $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
        $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
        $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
        $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

        $service = $ServicesTable->newEntity($serviceData);

        $ServicesTable->save($service);
        if ($service->hasErrors()) {
            if (!isset($this->serviceCreationErrors)) {
                $this->serviceCreationErrors = [];
            }
            $this->serviceCreationErrors[] = $service->getErrors();
        } else {
            //No errors
            $User = new User($this->getUser());

            $extDataForChangelog = $ServicesTable->resolveDataForChangelog($request);
            /** @var  ChangelogsTable $ChangelogsTable */
            $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

            $changelog_data = $ChangelogsTable->parseDataForChangelog(
                'add',
                'services',
                $service->get('id'),
                OBJECT_SERVICE,
                $host->get('container_id'),
                $User->getId(),
                $host->get('name') . '/' . $servicename,
                array_merge($request, $extDataForChangelog)
            );

            if ($changelog_data) {
                /** @var Changelog $changelogEntry */
                $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                $ChangelogsTable->save($changelogEntry);
            }
        }
    }

    /****************************
     *      Wizard METHODS      *
     ****************************/

    public function wizard() {
        if (!$this->isAngularJsRequest()) {
            return;
        }
    }

    public function config() {
        if (!$this->isApiRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($this->request->is('get')) {
            $hostId = $this->request->getQuery('hostId', 0);

            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException();
            }

            $host = $HostsTable->get($hostId);

            //todo check that config exists

            $AgentConfiguration = new AgentConfiguration();
            $config = $AgentConfiguration->unmarshal('{}');

            $this->set('config', $config);
            $this->set('host', $host);
            $this->viewBuilder()->setOption('serialize', ['config', 'host']);
        }

        if ($this->request->is('post')) {
            // Validate and save agent configuration
            $AgentConfigurationForm = new AgentConfigurationForm();
            $dataWithDatatypes = $this->request->getData(null, []);

            //Remote data type keys for validation (string, int, bool etc)
            $data = [];
            foreach ($dataWithDatatypes as $datatype => $fields) {
                foreach ($fields as $fieldName => $fieldValue) {
                    $data[$fieldName] = $fieldValue;
                }
            }
            $AgentConfigurationForm->execute($data);

            if (!empty($AgentConfigurationForm->getErrors())) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $AgentConfigurationForm->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

        }
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @param bool $onlyHostsWithWritePermission
     */
    public function loadHostsByString($onlyHostsWithWritePermission = false) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);

        $where = $HostFilter->ajaxFilter();
        $where['Hosts.host_type'] = GENERIC_HOST;


        $HostCondition = new HostConditions($where);
        $HostCondition->setIncludeDisabled(false);
        $HostCondition->setContainerIds($this->MY_RIGHTS);
        if ($onlyHostsWithWritePermission) {
            $writeContainers = [];
            foreach ($this->MY_RIGHTS_LEVEL as $containerId => $rightLevel) {
                $rightLevel = (int)$rightLevel;
                if ($rightLevel === WRITE_RIGHT) {
                    $writeContainers[$containerId] = $rightLevel;
                }
            }
            $HostCondition->setContainerIds(array_keys($writeContainers));
        }

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    public function loadAgentConfigByHostId() {
        $agentConfig = null;
        $this->set('agentConfig', $agentConfig);
        $this->viewBuilder()->setOption('serialize', ['agentConfig']);
    }


    /************************************
     *    AGENT API METHODS FOR PUSH    *
     ************************************/


}
