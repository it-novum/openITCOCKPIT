<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
use App\Model\Table\AgentchecksTable;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Agent\AgentResponseToServicetemplateMapper;
use itnovum\openITCOCKPIT\Agent\HttpLoader;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
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

        /** @var \App\Model\Entity\Host $host */
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
            $this->set('_serialize', ['host', 'config']);
            return;
        }

        if ($this->request->is('post')) {
            //Save agent configuration
            $entity = $AgentconfigsTable->getConfigOrEmptyEntity($hostId);
            $entity = $AgentconfigsTable->patchEntity($entity, $this->request->data('Agentconfig'));

            $entity->set('host_id', $hostId);

            $AgentconfigsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $entity->getErrors());
                $this->set('success', false);
                $this->set('_serialize', ['error', 'success']);
                return;
            } else {
                $this->set('success', true);
                $this->set('_serialize', ['success']);
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

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        if (!$this->allowedByContainerId($host->getContainerIds(), true)) {
            $this->render403();
            return;
        }

        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');
        $config = $AgentconfigsTable->getConfigByHostId($hostId, true);

        if ($this->request->is('get')) {

            $runDiscovery = $this->request->query('runDiscovery') === 'true';

            if ($runDiscovery === false) {
                $this->set('host', $host);
                $this->set('config', $config);
                $this->set('_serialize', ['host', 'config']);
                return;
            }

            try {
                $HttpLoader = new HttpLoader($config, $host->get('address'));
                $response = $HttpLoader->queryAgent();

                if ($response['error'] !== null) {
                    $this->response->statusCode(400);
                    $this->set('error', $response['error']);
                    $this->set('success', false);
                    $this->set('_serialize', ['error', 'success']);
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
                $this->set('_serialize', ['mapping']);
                return;
            } catch (\Exception $e) {
                $this->response->statusCode(400);
                $this->set('error', $e->getMessage());
                $this->set('success', false);
                $this->set('_serialize', ['error', 'success']);
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
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AgentchecksFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $agentchecks = $AgentchecksTable->getAgentchecksIndex($AgentchecksFilter, $PaginateOMat, $MY_RIGHTS);


        $all_agentchecks = [];
        foreach ($agentchecks as $index => $agentcheck) {
            /** @var \App\Model\Entity\Agentcheck $agentcheck */
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
        $this->set('_serialize', $toJson);

    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $AgentchecksTable AgentchecksTable */
            $AgentchecksTable = TableRegistry::getTableLocator()->get('Agentchecks');
            $agentcheck = $AgentchecksTable->newEntity();
            $agentcheck = $AgentchecksTable->patchEntity($agentcheck, $this->request->data('Agentcheck'));

            $AgentchecksTable->save($agentcheck);
            if ($agentcheck->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $agentcheck->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($agentcheck); // REST API ID serialization
                    return;
                }
            }
            $this->set('agentcheck', $agentcheck);
            $this->set('_serialize', ['agentcheck']);
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
            $agentcheck = $AgentchecksTable->patchEntity($agentcheck, $this->request->data('Agentcheck'));

            $AgentchecksTable->save($agentcheck);
            if ($agentcheck->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $agentcheck->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($agentcheck); // REST API ID serialization
                    return;
                }
            }
        }
        $this->set('agentcheck', $agentcheck);
        $this->set('_serialize', ['agentcheck']);
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
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function loadServicetemplates() {
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId($this->MY_RIGHTS, 'list', OITC_AGENT_SERVICE);
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $this->set('servicetemplates', $servicetemplates);
        $this->set('_serialize', ['servicetemplates']);
    }

    public function createService() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            $servicetemplateId = $this->request->data('Service.servicetemplate_id');
            if ($servicetemplateId === null) {
                throw new BadRequestException('Service.servicetemplate_id needs to set.');
            }

            $hostId = $this->request->data('Service.host_id');
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
            $this->request->data['Host'] = [
                [
                    'id'   => $host->get('id'),
                    'name' => $host->get('name')
                ]
            ];

            $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId);


            $servicename = $this->request->data('Service.name');
            if ($servicename === null || $servicename === '') {
                $servicename = $servicetemplate['Servicetemplate']['name'];
            }

            $ServiceComparisonForSave = new ServiceComparisonForSave(
                $this->request->data,
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
                $this->response->statusCode(400);
                $this->set('error', $service->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new User($this->Auth);

                $extDataForChangelog = $ServicesTable->resolveDataForChangelog($this->request->data);
                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'add',
                    'services',
                    $service->get('id'),
                    OBJECT_SERVICE,
                    $host->get('container_id'),
                    $User->getId(),
                    $host->get('name') . '/' . $servicename,
                    array_merge($this->request->data, $extDataForChangelog)
                );

                if ($changelog_data) {
                    $ChangelogsTable->write($changelog_data);
                }


                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($service); // REST API ID serialization
                    return;
                }
            }
            $this->set('service', $service);
            $this->set('_serialize', ['$service']);
        }
    }

}
