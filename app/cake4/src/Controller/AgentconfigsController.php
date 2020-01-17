<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Agentcheck;
use App\Model\Entity\Host;
use App\Model\Table\AgentchecksTable;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use itnovum\openITCOCKPIT\Agent\AgentResponseToServicetemplateMapper;
use itnovum\openITCOCKPIT\Agent\HttpLoader;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentchecksFilter;

/**
 * Class AgentchecksController
 * @property AppPaginatorComponent $Paginator
 */
class AgentconfigsController extends AppController {
    public $layout = 'blank';

    /**
     * @param int|null $hostId
     */
    public function config($hostId = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        if (!$this->allowedByContainerId($host->getContainerIds(), true)) {
            $this->render403();
            return;
        }

        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        if ($this->request->is('get')) {
            $config = $AgentconfigsTable->getConfigByHostId($hostId, true);
            $this->set('host', $host);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['host', 'config']);
            return;
        }

        if ($this->request->is('post')) {
            //Save agent configuration
            $entity = $AgentconfigsTable->getConfigOrEmptyEntity($hostId);
            $entity = $AgentconfigsTable->patchEntity($entity, $this->request->getData('Agentconfig'));

            $entity->set('host_id', $hostId);

            $AgentconfigsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->set('success', false);
                $this->viewBuilder()->setOption('serialize', ['error', 'success']);
                return;
            } else {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }

        }
    }

    /**
     * @param int|null $hostId
     */
    public function scan($hostId = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        if (!$this->allowedByContainerId($host->getContainerIds(), true)) {
            $this->render403();
            return;
        }

        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');
        $config = $AgentconfigsTable->getConfigByHostId($hostId, true);

        if ($this->request->is('get')) {

            $runDiscovery = $this->request->getQuery('runDiscovery') === 'true';

            if ($runDiscovery === false) {
                $this->set('host', $host);
                $this->set('config', $config);
                $this->viewBuilder()->setOption('serialize', ['host', 'config']);
                return;
            }

            try {
                $HttpLoader = new HttpLoader($config, $host->get('address'));
                $response = $HttpLoader->queryAgent();

                if ($response['error'] !== null) {
                    $this->response = $this->response->withStatus(400);
                    $this->set('error', $response['error']);
                    $this->set('success', false);
                    $this->viewBuilder()->setOption('serialize', ['error', 'success']);
                    return;
                }

                $agentResponse = $response['response'];

                /** @var $AgentchecksTable AgentchecksTable */
                $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
                $agentchecks = $AgentchecksTable->getAgentchecksForMapping();

                $AgentResponseToServicetemplateMapper = new AgentResponseToServicetemplateMapper(
                    $agentResponse,
                    $agentchecks
                );

                $mapping = $AgentResponseToServicetemplateMapper->getMapping();

                $this->set('mapping', $mapping);
                $this->viewBuilder()->setOption('serialize', ['mapping']);
                return;
            } catch (Exception $e) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $e->getMessage());
                $this->set('success', false);
                $this->viewBuilder()->setOption('serialize', ['error', 'success']);
            }
        }
    }

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');

        $AgentchecksFilter = new AgentchecksFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $AgentchecksFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $agentchecks = $AgentchecksTable->getAgentchecksIndex($AgentchecksFilter, $PaginateOMat, $MY_RIGHTS);


        $all_agentchecks = [];
        foreach ($agentchecks as $index => $agentcheck) {
            /** @var Agentcheck $agentcheck */
            $all_agentchecks[$index] = $agentcheck->toArray();
            $all_agentchecks[$index]['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_agentchecks[$index]['allow_edit'] = $this->isWritableContainer($agentcheck->get('servicetemplate')->get('container_id'));
            }
        }


        $this->set('all_agentchecks', $all_agentchecks);
        $toJson = ['all_agentchecks', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_agentchecks', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);

    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $AgentchecksTable AgentchecksTable */
            $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
            $agentcheck = $AgentchecksTable->newEmptyEntity();
            $agentcheck = $AgentchecksTable->patchEntity($agentcheck, $this->request->getData('Agentcheck'));

            $AgentchecksTable->save($agentcheck);
            if ($agentcheck->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $agentcheck->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($agentcheck); // REST API ID serialization
                    return;
                }
            }
            $this->set('agentcheck', $agentcheck);
            $this->viewBuilder()->setOption('serialize', ['agentcheck']);
        }
    }

    /**
     * @param int|null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');

        if (!$AgentchecksTable->existsById($id)) {
            throw new NotFoundException(__('Agentcheck not found'));
        }

        $agentcheck = $AgentchecksTable->getAgentcheckById($id);

        $allowEdit = $this->isWritableContainer($agentcheck->get('servicetemplate')->get('container_id'));
        if (!$allowEdit) {
            $this->render403();
            return;
        }

        if ($this->request->is('post')) {
            $agentcheck = $AgentchecksTable->patchEntity($agentcheck, $this->request->getData('Agentcheck'));

            $AgentchecksTable->save($agentcheck);
            if ($agentcheck->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $agentcheck->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($agentcheck); // REST API ID serialization
                    return;
                }
            }
        }
        $this->set('agentcheck', $agentcheck);
        $this->viewBuilder()->setOption('serialize', ['agentcheck']);
    }

    public function delete($id) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $AgentchecksTable AgentchecksTable */
        $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');

        if (!$AgentchecksTable->existsById($id)) {
            throw new NotFoundException(__('Agentcheck not found'));
        }

        $agentcheck = $AgentchecksTable->getAgentcheckById($id);

        $allowEdit = $this->isWritableContainer($agentcheck->get('servicetemplate')->get('container_id'));
        if (!$allowEdit) {
            $this->render403();
            return;
        }

        if ($AgentchecksTable->delete($agentcheck)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function loadServicetemplates() {
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId($this->MY_RIGHTS, 'list', OITC_AGENT_SERVICE);
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $this->set('servicetemplates', $servicetemplates);
        $this->viewBuilder()->setOption('serialize', ['servicetemplates']);
    }

    public function createService() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            $servicetemplateId = $this->request->getData('Service.servicetemplate_id');
            if ($servicetemplateId === null) {
                throw new BadRequestException('Service.servicetemplate_id needs to set.');
            }

            $hostId = $this->request->getData('Service.host_id');
            if ($hostId === null) {
                throw new BadRequestException('Service.host_id needs to set.');
            }

            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $ServicetemplatesTable ServicetemplatesTable */
            $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');
            /** @var $ChangelogsTable ChangelogsTable */
            $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

            if (!$ServicetemplatesTable->existsById($servicetemplateId)) {
                throw new NotFoundException(__('Invalid service template'));
            }

            $host = $HostsTable->get($hostId);
            $request = $this->request->getData();
            $request['Host'] = [
                [
                    'id'   => $host->get('id'),
                    'name' => $host->get('name')
                ]
            ];

            $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId);


            $servicename = $this->request->getData('Service.name');
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
                $this->response = $this->response->withStatus(400);
                $this->set('error', $service->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new User($this->getUser());

                $extDataForChangelog = $ServicesTable->resolveDataForChangelog($request);
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
                    $ChangelogsTable->write($changelog_data);
                }


                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($service); // REST API ID serialization
                    return;
                }
            }
            $this->set('service', $service);
            $this->viewBuilder()->setOption('serialize', ['$service']);
        }
    }

}
