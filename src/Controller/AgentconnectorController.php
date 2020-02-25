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

use App\Model\Entity\Changelog;
use App\Model\Entity\Host;
use App\Model\Table\AgentchecksTable;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\AgentconnectorTable;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Exception\GuzzleException;
use itnovum\openITCOCKPIT\Agent\AgentCertificateData;
use itnovum\openITCOCKPIT\Agent\AgentServicesToCreate;
use itnovum\openITCOCKPIT\Agent\HttpLoader;
use itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentconnectorAgentsFilter;

class AgentconnectorController extends AppController {

    private $hostsCacheFolder = '/opt/openitc/agent/hostscache/';
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


    public function certificate() {
        if (!$this->isJsonRequest()) {
            return;
        }
        $this->autoRender = false;

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        if (!empty($this->request->getData('csr')) && !empty($this->request->getData('hostuuid'))) {   //is certificate request
            if (!empty($this->request->getData('checksum'))) {  //maybe a request from an already known agent? (match checksum of old agent crt)
                if ($AgentconnectorTable->trustIsValid($this->request->getData('checksum'), $this->request->getData('hostuuid'))) {
                    $json = json_encode($AgentCertificateData->getAgentCsr($this->request->getData('hostuuid'), $this->request->getData('csr'), $AgentconnectorTable));
                    return $this->response->withType("application/json")->withStringBody($json);
                } else {    //untrusted threw frontend / maybe an imitator / unknown error?
                    echo json_encode(['unknown' => true]);
                }
            } else {    //not a request from an agent that has received a predecessor certificate
                if ($AgentconnectorTable->isTrustedFromUser($this->request->getData('hostuuid'))) {
                    if ($AgentconnectorTable->certificateNotYetGenerated($this->request->getData('hostuuid'))) {
                        $json = json_encode($AgentCertificateData->getAgentCsr($this->request->getData('hostuuid'), $this->request->getData('csr'), $AgentconnectorTable));
                        return $this->response->withType("application/json")->withStringBody($json);
                    }
                    echo json_encode(['checksum_missing' => true]);
                } else {    //definitely not a request from a known agent!
                    if (!$AgentconnectorTable->getByHostUuid($this->request->getData('hostuuid'))) {
                        $AgentConnectionEntity = $AgentconnectorTable->newEntity([
                            'hostuuid'             => $this->request->getData('hostuuid'),
                            'checksum'             => null,
                            'ca_checksum'          => null,
                            'generation_date'      => null,
                            'remote_addr'          => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
                            'http_x_forwarded_for' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null,
                            'trusted'              => 0
                        ]);
                        $AgentconnectorTable->save($AgentConnectionEntity);
                        if ($AgentConnectionEntity->hasErrors()) {
                            $this->set('error', 'could not save data');
                            $this->viewBuilder()->setOption('serialize', ['error']);
                            return;
                        }
                    }

                    //should return this if agent is unknown and needs to be confirmed by an user
                    echo json_encode(['unknown' => true]);
                }
            }
        }
    }

    public function agents() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');

        $AgentconnectorAgentsFilter = new AgentconnectorAgentsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AgentconnectorAgentsFilter->getPage());

        $agents = $AgentconnectorTable->getAgentsIndex($AgentconnectorAgentsFilter, $PaginateOMat);

        $this->set('agents', $agents);
        $toJson = ['agents', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['agents', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @throws MissingParameterExceptions
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

    public function updateCheckdata() {
        if (!$this->isJsonRequest()) {
            return;
        }
        $this->autoRender = false;

        //require_once '/opt/openitcockpit-receiver/vendor/autoload.php';

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        if (!empty($this->request->getData('checkdata')) && !empty($this->request->getData('hostuuid'))) {
            if ($AgentconnectorTable->isTrustedFromUser($this->request->getData('hostuuid'))) {
                $this->processUpdateCheckdata($this->request->getData('hostuuid'), $this->request->getData('checkdata'));
                if (!$AgentconnectorTable->certificateNotYetGenerated($this->request->getData('hostuuid')) && !empty($this->request->getData('checksum'))) {  //should have a certificate!
                    if ($AgentconnectorTable->trustIsValid($this->request->getData('checksum'), $this->request->getData('hostuuid'))) {

                        //if new ca ws generated, echo new_ca with old ca checksum
                    }
                } else {    //does not have a certificate or autossl option was disabled after creation
                    //$this->processUpdateCheckdata($this->request->getData('hostuuid'), $this->request->getData('checkdata'));
                }
            } else {
                $this->processUpdateCheckdata($this->request->getData('hostuuid'), $this->request->getData('checkdata'));
            }
        }
    }

    private function processUpdateCheckdata($hostuuid, $checkdata) {
        //FileDebugger::dump(json_decode($checkdata, true)['agent']);

        //if no host check config (poller host config) is available, store data to process later
        file_put_contents('/opt/openitc/agent/hostscache/' . $hostuuid, $checkdata);
    }

    public function sendNewAgentConfig($uuid = null) {
        if (!$this->isJsonRequest()) {
            return;
        }

        if (empty($this->request->getData('config'))) {
            throw new MissingParameterExceptions('config is missing!');
        }
        //debug($this->request->getData('config'));die();


        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
        /** @var $AgentconfigsTable AgentconfigsTable */
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

            $agentconfigEntity = $AgentconfigsTable->getConfigOrEmptyEntity($hostId);
            $agentconfigEntity = $AgentconfigsTable->patchEntity($agentconfigEntity, [
                'port'      => $this->request->getData('config')['port'],
                'use_https' => $this->request->getData('config')['try-autossl'],
            ]);
            $AgentconfigsTable->save($agentconfigEntity);
        }
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function config() {
        if (!$this->isAngularJsRequest()) {
            return;
        }

        /** @var AgentconnectorTable $AgentconnectorTable */
        $AgentconnectorTable = TableRegistry::getTableLocator()->get('Agentconnector');
    }

    public function getLatestCheckDataByHostUuid($uuid = null) {
        if (!$this->isJsonRequest()) {
            //Only ship HTML Template
            return;
        }

        if ($uuid === null) {
            throw new MissingParameterExceptions('Host uuid is missing!');
        }

        $this->set('checkdata', '');
        if (is_readable($this->hostsCacheFolder . $uuid)) {
            $fileContents = trim(file_get_contents($this->hostsCacheFolder . $uuid));

            if ($fileContents !== '') {
                $contentArray = json_decode($fileContents, true);
                if ($contentArray['processes']) {
                    foreach ($contentArray['processes'] as $key => $val) {
                        if (!empty($contentArray['processes'][$key]['cmdline'])) {
                            $contentArray['processes'][$key]['cmdline'] = implode(' ', $contentArray['processes'][$key]['cmdline']);
                        }
                    }
                }

                $this->set('checkdata', $contentArray);
            }
        }
        $this->viewBuilder()->setOption('serialize', ['checkdata']);
    }

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

        $hostId = $HostsTable->getHostIdByUuid($uuid);
        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        $services = $ServicesTable->getServicesByHostIdForAgent($hostId, OITC_AGENT_SERVICE, false);
        $services = $services->toArray();

        $this->set('servicesToCreate', '');
        $this->set('config', '');
        $this->set('mode', '');

        if ($AgentconfigsTable->existsByHostId($hostId)) {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);

            try {
                $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
                $response = $HttpLoader->queryAgent(true);

                if (isset($response['response']) && !empty($response['response'])) {
                    $agentJsonOutput = $response['response'];
                }
                if (isset($response['config']) && $response['config'] !== '' && !empty($response['config'])) {
                    $this->set('config', $response['config']);
                }
                $this->set('mode', 'pull');
            } catch (\Exception | GuzzleException $e) {

            }
        }

        if (is_readable($this->hostsCacheFolder . $uuid)) {
            $fileContents = trim(file_get_contents($this->hostsCacheFolder . $uuid));
            $contentArray = json_decode($fileContents, true);
            $agentJsonOutput = $contentArray;
            $this->set('mode', 'push');
        }

        if (isset($agentJsonOutput) && !empty($agentJsonOutput)) {
            $AgentServicesToCreate = new AgentServicesToCreate($agentJsonOutput, $AgentchecksTable->getAgentchecksForMapping(), $hostId, $services);

            $this->set('servicesToCreate', $AgentServicesToCreate->getServicesForFrontend());
        }
        $this->viewBuilder()->setOption('serialize', ['servicesToCreate', 'mode', 'config']);
    }

    public function createServices() {
        if (!$this->isJsonRequest()) {
            return;
        }

        if (empty($this->request->getData('serviceConfigs'))) {
            throw new MissingParameterExceptions('serviceConfigs is missing!');
        }
        if (empty($this->request->getData('hostId'))) {
            throw new MissingParameterExceptions('hostId is missing!');
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

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->get($hostId);
        $hostuuid = $HostsTable->getHostUuidById($hostId);
        $services = $ServicesTable->getServicesByHostIdForAgent($hostId, OITC_AGENT_SERVICE, false);
        $services = $services->toArray();

        if ($AgentconfigsTable->existsByHostId($hostId)) {
            $agentconfig = $AgentconfigsTable->getConfigByHostId($hostId, true);

            try {
                $HttpLoader = new HttpLoader($agentconfig, $host->get('address'));
                $response = $HttpLoader->queryAgent(false);

                if (isset($response['response']) && !empty($response['response'])) {
                    $agentJsonOutput = $response['response'];
                }
            } catch (\Exception | GuzzleException $e) {

            }
        }

        if (is_readable($this->hostsCacheFolder . $hostuuid)) {
            $fileContents = trim(file_get_contents($this->hostsCacheFolder . $hostuuid));
            $contentArray = json_decode($fileContents, true);
            $agentJsonOutput = $contentArray;
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

        if (isset($this->serviceCreationErrors) && !empty($this->serviceCreationErrors)) {
            $this->serviceCreationErrors = [];
            $this->response = $this->response->withStatus(400);
            $this->set('error', $this->serviceCreationErrors);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('success', 'true');
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

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
}
