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

use App\Lib\Constants;
use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Model\Table\CommandargumentsTable;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\DeletedServicesTable;
use App\Model\Table\DocumentationsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicecommandargumentvaluesTable;
use App\Model\Table\ServiceeventcommandargumentvaluesTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AcknowledgedServiceConditions;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\CustomMacroReplacer;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\HosttemplateMerger;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\Merger\ServiceMergerForView;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ServiceMacroReplacer;
use itnovum\openITCOCKPIT\Core\ServiceNotificationConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ServicetemplateMerger;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;
use itnovum\openITCOCKPIT\Core\Timeline\AcknowledgementSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\DowntimeSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\Groups;
use itnovum\openITCOCKPIT\Core\Timeline\NotificationSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\StatehistorySerializer;
use itnovum\openITCOCKPIT\Core\Timeline\TimeRangeSerializer;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementService;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\PerfdataChecker;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;
use Statusengine\PerfdataParser;

/**
 * @property Container $Container
 * @property Service $Service
 * @property Host $Host
 * @property Servicetemplate $Servicetemplate
 * @property Servicegroup $Servicegroup
 * @property Timeperiod $Timeperiod
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Customvariable $Customvariable
 * @property Servicecommandargumentvalue $Servicecommandargumentvalue
 * @property Serviceeventcommandargumentvalue $Serviceeventcommandargumentvalue
 * @property Servicetemplatecommandargumentvalue $Servicetemplatecommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 * @property DeletedService $DeletedService
 * @property AcknowledgedService $AcknowledgedService
 * @property DowntimeService $DowntimeService
 * @property BbcodeComponent $Bbcode
 * @property Command $Command
 * @property DbBackend $DbBackend
 * @property AppPaginatorComponent $Paginator
 */
class ServicesController extends AppController {

    public $layout = 'blank';

    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
        'Bbcode',
        'GearmanClient',
        'Flash'
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'CustomValidationErrors',
        'CustomVariables',
        'Bbcode',
    ];
    public $uses = [
        'Service',
        'Host',
        'Servicetemplate',
        'Servicegroup',
        'Command',
        'Timeperiod',
        'Contact',
        'Contactgroup',
        'Container',
        'Customvariable',
        'Servicecommandargumentvalue',
        'Serviceeventcommandargumentvalue',
        'Servicetemplatecommandargumentvalue',
        'Servicetemplateeventcommandargumentvalue',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        MONITORING_ACKNOWLEDGED_HOST,
        MONITORING_ACKNOWLEDGED_SERVICE,
        MONITORING_OBJECTS,
        'DeletedService',
        'Container',
        'Documentation',
        'Systemsetting',
        MONITORING_DOWNTIME_HOST,
        MONITORING_DOWNTIME_SERVICE,
        MONITORING_STATEHISTORY_HOST,
        MONITORING_STATEHISTORY_SERVICE,
        'DateRange',
        MONITORING_NOTIFICATION_SERVICE
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $ServiceFilter = new ServiceFilter($this->request);
        $User = new User($this->Auth);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions(
            $ServiceFilter->indexFilter()
        );
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);

        if ($ServiceControllerRequest->isRequestFromBrowser() === false) {
            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        }

        if ($ServiceControllerRequest->isRequestFromBrowser() === true) {
            $browserContainerIds = $ServiceControllerRequest->getBrowserContainerIdsByRequest();
            foreach ($browserContainerIds as $containerIdToCheck) {
                if (!in_array($containerIdToCheck, $this->MY_RIGHTS)) {
                    $this->render403();
                    return;
                }
            }

            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($browserContainerIds);

            if ($User->isRecursiveBrowserEnabled()) {
                //get recursive container ids
                $containerIdToResolve = $browserContainerIds;
                $children = $ContainersTable->getChildren($containerIdToResolve[0]);
                $containerIds = \Cake\Utility\Hash::extract($children, '{n}.id');
                $recursiveContainerIds = [];
                foreach ($containerIds as $containerId) {
                    if (in_array($containerId, $this->MY_RIGHTS)) {
                        $recursiveContainerIds[] = $containerId;
                    }
                }
                $ServiceConditions->setContainerIds(array_merge($ServiceConditions->getContainerIds(), $recursiveContainerIds));
            }
        }

        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Hosts.name'  => 'asc',
            'servicename' => 'asc'
        ]));


        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceFilter->getPage());

        if ($this->DbBackend->isNdoUtils()) {
            $services = $ServicesTable->getServiceIndex($ServiceConditions, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        $hostContainers = [];
        if ($this->hasRootPrivileges === false) {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                foreach ($services as $index => $service) {
                    $hostId = $service['_matchingData']['Hosts']['id'];
                    if (!isset($hostContainers[$hostId])) {
                        $hostContainers[$hostId] = $HostsTable->getHostContainerIdsByHostId($hostId);
                    }

                    $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $hostContainers[$hostId]);
                    $services[$index]['allow_edit'] = $ContainerPermissions->hasPermission();
                }
            }
        } else {
            //Root user
            foreach ($services as $index => $service) {
                $services[$index]['allow_edit'] = $this->hasRootPrivileges;
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isFlapping()
            ->lastHardStateChange();
        $hoststatusCache = $this->Hoststatus->byUuid(
            array_unique(\Cake\Utility\Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
            $HoststatusFields
        );


        $all_services = [];
        $UserTime = $User->getUserTime();
        foreach ($services as $service) {
            $allowEdit = $service['allow_edit'];
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['_matchingData']['Hosts'], $allowEdit);
            if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
            } else {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([], $UserTime);
            }
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
            $PerfdataChecker = new PerfdataChecker($Host, $Service, $this->PerfdataBackend, $Servicestatus);

            $tmpRecord = [
                'Service'       => $Service->toArray(),
                'Host'          => $Host->toArray(),
                'Hoststatus'    => $Hoststatus->toArray(),
                'Servicestatus' => $Servicestatus->toArray()
            ];
            $tmpRecord['Service']['has_graph'] = $PerfdataChecker->hasPerfdata();
            $all_services[] = $tmpRecord;
        }

        $this->set('all_services', $all_services);
        $toJson = ['all_services', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_services', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param int|null $id
     * @throws MissingDbBackendException
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$ServicesTable->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        /** @var \App\Model\Entity\Service $service */
        $service = $ServicesTable->getServiceById($id);

        if (!$this->allowedByContainerId($service->get('host')->getContainerIds())) {
            $this->render403();
            return;
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->wildcard();

        if ($this->DbBackend->isNdoUtils()) {
            /** @var $ServicestatusTable ServicestatusTableInterface */
            $ServicestatusTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Servicestatus');
            $servicestatus = $ServicestatusTable->byUuid($service->get('uuid'), $ServicestatusFields);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if (empty($servicestatus)) {
            $servicestatus = [
                'Servicestatus' => []
            ];
        }
        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($servicestatus['Servicestatus']);

        $this->set('service', $service);
        $this->set('servicestatus', $Servicestatus->toArray());
        $this->set('_serialize', ['service', 'servicestatus']);
    }

    public function byUuid($uuid) {
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        try {
            $service = $ServicesTable->getServiceByUuid($uuid);

            if (!$this->allowedByContainerId($service->get('host')->getContainerIds())) {
                $this->render403();
                return;
            }
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('Service not found');
        }

        $this->set('service', $service);
        $this->set('_serialize', ['service']);
    }

    public function notMonitored() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions(
            $ServiceFilter->disabledFilter()
        );
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder('Hosts.name', 'asc'));

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceFilter->getPage());

        if ($this->DbBackend->isNdoUtils()) {
            $services = $ServicesTable->getServiceNotMonitored($ServiceConditions, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        $hostContainers = [];
        if ($this->hasRootPrivileges === false) {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                foreach ($services as $index => $service) {
                    $hostId = $service['_matchingData']['Hosts']['id'];
                    if (!isset($hostContainers[$hostId])) {
                        $hostContainers[$hostId] = $HostsTable->getHostContainerIdsByHostId(1);
                    }

                    $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $hostContainers[$hostId]);
                    $services[$index]['allow_edit'] = $ContainerPermissions->hasPermission();
                }
            }
        } else {
            //Root user
            foreach ($services as $index => $service) {
                $services[$index]['allow_edit'] = $this->hasRootPrivileges;
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $hoststatusCache = $this->Hoststatus->byUuid(
            array_unique(\Cake\Utility\Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
            $HoststatusFields
        );


        $all_services = [];
        $User = new User($this->Auth);
        $UserTime = $User->getUserTime();
        foreach ($services as $service) {
            $allowEdit = $service['allow_edit'];
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['_matchingData']['Hosts'], $allowEdit);
            if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
            } else {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([], $UserTime);
            }
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);

            $tmpRecord = [
                'Service'    => $Service->toArray(),
                'Host'       => $Host->toArray(),
                'Hoststatus' => $Hoststatus->toArray()
            ];
            $all_services[] = $tmpRecord;
        }

        $this->set('all_services', $all_services);
        $toJson = ['all_services', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_services', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function disabled() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }


        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions(
            $ServiceFilter->notMonitoredFilter()
        );
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder('Hosts.name', 'asc'));

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceFilter->getPage());

        $services = $ServicesTable->getServicesForDisabled($ServiceConditions, $PaginateOMat);

        $hostContainers = [];
        if ($this->hasRootPrivileges === false) {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                foreach ($services as $index => $service) {
                    $hostId = $service['_matchingData']['Hosts']['id'];
                    if (!isset($hostContainers[$hostId])) {
                        $hostContainers[$hostId] = $HostsTable->getHostContainerIdsByHostId(1);
                    }

                    $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $hostContainers[$hostId]);
                    $services[$index]['allow_edit'] = $ContainerPermissions->hasPermission();
                }
            }
        } else {
            //Root user
            foreach ($services as $index => $service) {
                $services[$index]['allow_edit'] = $this->hasRootPrivileges;
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $hoststatusCache = $this->Hoststatus->byUuid(
            array_unique(\Cake\Utility\Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
            $HoststatusFields
        );


        $all_services = [];
        $User = new User($this->Auth);
        $UserTime = $User->getUserTime();
        foreach ($services as $service) {
            $allowEdit = $service['allow_edit'];
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['_matchingData']['Hosts'], $allowEdit);
            if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
            } else {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([], $UserTime);
            }
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);

            $tmpRecord = [
                'Service'    => $Service->toArray(),
                'Host'       => $Host->toArray(),
                'Hoststatus' => $Hoststatus->toArray()
            ];
            $all_services[] = $tmpRecord;
        }

        $this->set('all_services', $all_services);
        $toJson = ['all_services', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_services', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function add() {
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
                $changelog_data = $this->Changelog->parseDataForChangelog(
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
                    CakeLog::write('log', serialize($changelog_data));
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

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        if (!$ServicesTable->existsById($id)) {
            throw new NotFoundException(__('Service not found'));
        }

        $service = $ServicesTable->getServiceForEdit($id);
        $serviceForChangelog = $service;

        $host = $HostsTable->getHostForServiceEdit($service['Service']['host_id']);

        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return service information
            $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($service['Service']['servicetemplate_id']);

            $hostContactsAndContactgroups = $HostsTable->getContactsAndContactgroupsById($host['Host']['id']);
            $hosttemplateContactsAndContactgroups = $HosttemplatesTable->getContactsAndContactgroupsById($host['Host']['hosttemplate_id']);

            $ServiceMergerForView = new ServiceMergerForView(
                $service,
                $servicetemplate,
                $hostContactsAndContactgroups,
                $hosttemplateContactsAndContactgroups
            );
            $mergedService = $ServiceMergerForView->getDataForView();

            $this->set('service', $mergedService);
            $this->set('host', $host);
            $this->set('servicetemplate', $servicetemplate);
            $this->set('hostContactsAndContactgroups', $hostContactsAndContactgroups);
            $this->set('hosttemplateContactsAndContactgroups', $hosttemplateContactsAndContactgroups);
            $this->set('areContactsInheritedFromHosttemplate', $ServiceMergerForView->areContactsInheritedFromHosttemplate());
            $this->set('areContactsInheritedFromHost', $ServiceMergerForView->areContactsInheritedFromHost());
            $this->set('areContactsInheritedFromServicetemplate', $ServiceMergerForView->areContactsInheritedFromServicetemplate());


            $this->set('_serialize', [
                'service',
                'host',
                'servicetemplate',
                'hostContactsAndContactgroups',
                'hosttemplateContactsAndContactgroups',
                'areContactsInheritedFromHosttemplate',
                'areContactsInheritedFromHost',
                'areContactsInheritedFromServicetemplate'
            ]);
            return;
        }


        if ($this->request->is('post')) {
            $servicetemplateId = $this->request->data('Service.servicetemplate_id');
            if ($servicetemplateId === null) {
                throw new Exception('Service.servicetemplate_id needs to set.');
            }

            if (!$ServicetemplatesTable->existsById($servicetemplateId)) {
                throw new NotFoundException(__('Invalid service template'));
            }
            $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($servicetemplateId);

            $servicename = $this->request->data('Service.name');
            if ($servicename === null || $servicename === '') {
                $servicename = $servicetemplate['Servicetemplate']['name'];
            }

            $ServiceComparisonForSave = new ServiceComparisonForSave(
                $this->request->data,
                $servicetemplate,
                $HostsTable->getContactsAndContactgroupsById($host['Host']['id']),
                $HosttemplatesTable->getContactsAndContactgroupsById($host['Host']['hosttemplate_id'])
            );
            $dataForSave = $ServiceComparisonForSave->getDataForSaveForAllFields();

            //Add required fields for validation
            $dataForSave['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
            $dataForSave['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
            $dataForSave['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
            $dataForSave['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
            $dataForSave['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

            //Update contact data
            $serviceEntity = $ServicesTable->get($id);
            $serviceEntity->setAccess('uuid', false);
            $serviceEntity->setAccess('id', false);
            $serviceEntity->setAccess('host_id', false);

            $serviceEntity = $ServicesTable->patchEntity($serviceEntity, $dataForSave);
            $ServicesTable->save($serviceEntity);

            $this->request->data['Host'] = [
                [
                    'id'   => $host['Host']['id'],
                    'name' => $host['Host']['name'],
                ]
            ];

            if ($serviceEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $serviceEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new User($this->Auth);

                $extDataForChangelog = $ServicesTable->resolveDataForChangelog($this->request->data);

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'services',
                    $serviceEntity->get('id'),
                    OBJECT_SERVICE,
                    $host['Host']['container_id'],
                    $User->getId(),
                    $host['Host']['name'] . '/' . $servicename,
                    array_merge($ServicesTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ServicesTable->resolveDataForChangelog($serviceForChangelog), $serviceForChangelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($serviceEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('service', $serviceEntity);
            $this->set('_serialize', ['service']);
        }
    }

    public function deleted() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var $DeletedServicesTable DeletedServicesTable */
        $DeletedServicesTable = TableRegistry::getTableLocator()->get('DeletedServices');
        $ServiceFilter = new ServiceFilter($this->request);

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceFilter->getPage());
        $result = $DeletedServicesTable->getDeletedServicesIndex($ServiceFilter, $PaginateOMat);

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $all_services = [];
        foreach ($result as $deletedService) {
            $DeletedService = new \itnovum\openITCOCKPIT\Core\Views\DeletedService($deletedService, $UserTime);
            $all_services[] = [
                'DeletedService' => $DeletedService->toArray()
            ];
        }

        $this->set('all_services', $all_services);
        $toJson = ['all_services', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_services', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$ServicesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $ServicesTable->find()
            ->contain([
                'ServiceescalationsServiceMemberships',
                'ServicedependenciesServiceMemberships'
            ])->where([
                'Services.id' => $id
            ])->first();

        $host = $HostsTable->getHostForServiceEdit($service->get('host_id'));
        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        $Constants = new Constants();
        $moduleConstants = $Constants->getModuleConstants();

        $usedBy = $service->isUsedByModules($service, $moduleConstants);
        $User = new User($this->Auth);
        if (empty($usedBy)) {
            //Not used by any module
            if ($ServicesTable->__delete($service, $User)) {

                /** @var $DocumentationsTable DocumentationsTable */
                $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

                $DocumentationsTable->deleteDocumentationByUuid($service->get('uuid'));

                $this->set('success', true);
                $this->set('message', __('Service successfully deleted'));
                $this->set('_serialize', ['success']);
                return;
            }
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while deleting service'));
        $this->set('usedBy', $this->getUsedByForFrontend($usedBy, 'service'));
        $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
    }


    public function copy() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->request->is('get')) {
            $hostId = $this->request->query('hostId');
            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException('Invalid host');
            }

            $containerId = $HostsTable->getHostPrimaryContainerIdByHostId($hostId);
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

            $services = $ServicesTable->getServicesForCopy(func_get_args(), $containerIds);

            /** @var $CommandsTable CommandsTable */
            $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
            $commands = $CommandsTable->getCommandByTypeAsList(CHECK_COMMAND);
            $eventhandlerCommands = $CommandsTable->getCommandByTypeAsList(EVENTHANDLER_COMMAND);

            $this->set('services', $services);
            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('eventhandlerCommands', Api::makeItJavaScriptAble($eventhandlerCommands));
            $this->set('_serialize', ['services', 'commands', 'eventhandlerCommands']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $Cache = new KeyValueStore();
            $ServicetemplateCache = new KeyValueStore();
            $ServicetemplateEditCache = new KeyValueStore();

            $postData = $this->request->data('data');
            $hostId = $this->request->data('hostId');

            if (!$HostsTable->existsById($hostId)) {
                throw new NotFoundException('Invalid host');
            }

            $host = $HostsTable->getHostForServiceEdit($hostId);
            $hostContactsAndContactgroups = $HostsTable->getContactsAndContactgroupsById($host['Host']['id']);
            $hosttemplateContactsAndContactgroups = $HosttemplatesTable->getContactsAndContactgroupsById($host['Host']['hosttemplate_id']);

            $User = new User($this->Auth);

            foreach ($postData as $index => $serviceData) {
                if (!isset($serviceData['Service']['id'])) {
                    //Create/clone service
                    $sourceServiceId = $serviceData['Source']['id'];
                    if (!$Cache->has($sourceServiceId)) {
                        $sourceService = $ServicesTable->getServiceForEdit($sourceServiceId);

                        if (!$ServicetemplateCache->has($sourceService['Service']['servicetemplate_id'])) {
                            $sourceServiceServicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($sourceService['Service']['servicetemplate_id']);
                            $ServicetemplateCache->set($sourceService['Service']['servicetemplate_id'], $sourceServiceServicetemplate);
                        }

                        $sourceServiceServicetemplate = $ServicetemplateCache->get($sourceService['Service']['servicetemplate_id']);

                        $ServiceMergerForView = new ServiceMergerForView(
                            $sourceService,
                            $sourceServiceServicetemplate
                        );

                        $sourceService = $ServiceMergerForView->getDataForView();

                        //This service is not used - because it does not exists yet
                        $sourceService['Service']['usage_flag'] = 0;

                        unset($sourceService['Service']['id'], $sourceService['Service']['uuid'], $sourceService['Service']['host_id']);

                        foreach ($sourceService['Service']['servicecommandargumentvalues'] as $i => $servicecommandargumentvalues) {
                            unset($sourceService['Service']['servicecommandargumentvalues'][$i]['id']);
                            if (isset($sourceService['Service']['servicecommandargumentvalues'][$i]['service_id'])) {
                                unset($sourceService['Service']['servicecommandargumentvalues'][$i]['service_id']);
                            }

                            if (isset($sourceService['Service']['servicecommandargumentvalues'][$i]['servicetemplate_id'])) {
                                unset($sourceService['Service']['servicecommandargumentvalues'][$i]['servicetemplate_id']);
                            }
                        }

                        $Cache->set($sourceServiceId, $sourceService);
                    }

                    $sourceService = $Cache->get($sourceServiceId);

                    $newServiceData = $sourceService;
                    $newServiceData['Service']['host_id'] = $hostId;
                    $newServiceData['Service']['name'] = $serviceData['Service']['name'];
                    $newServiceData['Service']['description'] = $serviceData['Service']['description'];
                    $newServiceData['Service']['command_id'] = $serviceData['Service']['command_id'];
                    if (!empty($serviceData['Service']['servicecommandargumentvalues'])) {
                        $newServiceData['Service']['servicecommandargumentvalues'] = $serviceData['Service']['servicecommandargumentvalues'];
                    }
                }

                $action = 'copy';
                if (isset($serviceData['Service']['id'])) {
                    //Update existing service
                    //This happens, if a user copy multiple services, and one run into an validation error
                    //All services without validation errors got already saved to the database
                    $newServiceData = $ServicesTable->getServiceForEdit($serviceData['Service']['id']);
                    $serviceForChangelog = $newServiceData;

                    $newServiceData['Service']['name'] = $serviceData['Service']['name'];
                    $newServiceData['Service']['description'] = $serviceData['Service']['description'];
                    $newServiceData['Service']['command_id'] = $serviceData['Service']['command_id'];
                    if (!empty($serviceData['Service']['servicecommandargumentvalues'])) {
                        $newServiceData['Service']['servicecommandargumentvalues'] = $serviceData['Service']['servicecommandargumentvalues'];
                    }

                    $action = 'edit';
                }

                $servicename = $newServiceData['Service']['name'];
                // Replace service template values with zero
                if (!$ServicetemplateEditCache->has($newServiceData['Service']['servicetemplate_id'])) {
                    $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($newServiceData['Service']['servicetemplate_id']);
                    $ServicetemplateEditCache->set($newServiceData['Service']['servicetemplate_id'], $servicetemplate);
                }
                $servicetemplate = $ServicetemplateEditCache->get($newServiceData['Service']['servicetemplate_id']);

                $ServiceComparisonForSave = new ServiceComparisonForSave(
                    $newServiceData,
                    $servicetemplate,
                    $hostContactsAndContactgroups,
                    $hosttemplateContactsAndContactgroups
                );
                $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();

                //Add required fields for validation
                $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
                $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
                $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
                $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
                $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];

                //Container permissions check for contacts, contact groups and time periods
                if (!empty($serviceData['contacts']['_ids'])) {
                    // Host can use this contacts
                    // Contacts are different than in the service template
                    // Check if the contacts can be used by the host
                    $serviceData['contacts']['_ids'] = $ContactsTable->removeContactsWhichAreNotInContainer(
                        $serviceData['contacts']['_ids'],
                        $host['Host']['container_id']
                    );
                }

                if (!empty($serviceData['contactgroups']['_ids'])) {
                    // Host can use this contactgroups
                    // Contactgroups are different than in the service template
                    // Check if the contactgroups can be used by the host
                    $serviceData['contactgroups']['_ids'] = $ContactgroupsTable->removeContactgroupsWhichAreNotInContainer(
                        $serviceData['contactgroups']['_ids'],
                        $host['Host']['container_id']
                    );
                }

                if ($serviceData['check_period_id'] !== null) {
                    // Host can use this template
                    // Timeperiod is different than in the service template
                    // Check if this time period can be used by the host
                    $serviceData['check_period_id'] = $TimeperiodsTable->checkTimeperiodIdForContainerPermissions(
                        $serviceData['check_period_id'],
                        $host['Host']['container_id'],
                        $host['Host']['check_period_id']
                    );
                }

                if ($serviceData['notify_period_id'] !== null) {
                    // Host can use this template
                    // Timeperiod is different than in the service template
                    // Check if this time period can be used by the host
                    $serviceData['notify_period_id'] = $TimeperiodsTable->checkTimeperiodIdForContainerPermissions(
                        $serviceData['notify_period_id'],
                        $host['Host']['container_id'],
                        $host['Host']['notify_period_id']
                    );
                }

                if ($action === 'copy') {
                    $serviceData['uuid'] = UUID::v4();

                    $newServiceEntity = $ServicesTable->newEntity($serviceData);
                } else {
                    $newServiceEntity = $ServicesTable->get($newServiceData['Service']['id']);
                    $newServiceEntity->setAccess('uuid', false);
                    $newServiceEntity->setAccess('id', false);
                    $newServiceEntity->setAccess('host_id', false);
                    $newServiceEntity = $ServicesTable->patchEntity($newServiceEntity, $serviceData);
                }

                $ServicesTable->save($newServiceEntity);

                $postData[$index]['Error'] = [];
                if ($newServiceEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newServiceEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Service']['id'] = $newServiceEntity->get('id');

                    if ($action === 'copy') {
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $action,
                            'services',
                            $postData[$index]['Service']['id'],
                            OBJECT_SERVICE,
                            $host['Host']['container_id'],
                            $User->getId(),
                            $host['Host']['name'] . '/' . $servicename,
                            $serviceData
                        );
                    } else {
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $action,
                            'services',
                            $postData[$index]['Service']['id'],
                            OBJECT_SERVICE,
                            $host['Host']['container_id'],
                            $User->getId(),
                            $host['Host']['name'] . '/' . $servicename,
                            array_merge($ServicesTable->resolveDataForChangelog($serviceData), $serviceData),
                            array_merge($ServicesTable->resolveDataForChangelog($serviceForChangelog), $serviceForChangelog)
                        );
                    }

                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }
            }
        }

        if ($hasErrors) {
            $this->response->statusCode(400);
        }
        $this->set('result', $postData);
        $this->set('_serialize', ['result']);
    }

    /**
     * @param int|null $id
     */
    public function deactivate($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$ServicesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $ServicesTable->get($id);
        $host = $HostsTable->getHostForServiceEdit($service->get('host_id'));
        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        $service->set('disabled', 1);
        $ServicesTable->save($service);

        if ($service->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('message', __('Issue while disabling service'));
            $this->set('error', $service->getErrors());
            $this->set('_serialize', ['error', 'success', 'message']);
            return;
        }

        $this->set('success', true);
        $this->set('id', $id);
        $this->set('message', __('Service successfully disabled'));
        $this->set('_serialize', ['success', 'message', 'id']);
    }

    /**
     * @param int|null $id
     */
    public function enable($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$ServicesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $ServicesTable->get($id);
        $host = $HostsTable->getHostForServiceEdit($service->get('host_id'));
        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        if ($host['Host']['disabled'] === 1) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Could not enable service, because associated host is also disabled.'));
            $this->set('_serialize', ['success', 'id', 'message']);
            return;
        }

        $service->set('disabled', 0);
        $ServicesTable->save($service);

        if ($service->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('message', __('Issue while enabling service'));
            $this->set('error', $service->getErrors());
            $this->set('_serialize', ['error', 'success', 'message']);
            return;
        }

        $this->set('success', true);
        $this->set('id', $id);
        $this->set('message', __('Service successfully enabled'));
        $this->set('_serialize', ['success', 'message', 'id']);
    }

    /**
     * @param int|string|null $idOrUuid
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     * @deprecated
     */
    public function browser($id = null) {
        $this->layout = 'blank';

        if (!$this->isAngularJsRequest() && $idOrUuid === null) {
            //Only ship template
            return;
        }

        $id = $idOrUuid;
        if (!is_numeric($idOrUuid)) {
            if (preg_match(UUID::regex(), $idOrUuid)) {
                $lookupService = $this->Service->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Service.id'
                    ],
                    'conditions' => [
                        'Service.uuid' => $idOrUuid
                    ]
                ]);
                if (empty($lookupService)) {
                    throw new NotFoundException(__('Service not found'));
                }
                $this->redirect([
                    'controller' => 'services',
                    'action'     => 'browser',
                    $lookupService['Service']['id']
                ]);
                return;
            }
        }
        unset($idOrUuid);

        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Service not found'));
        }

        /** @var $DocumentationsTable DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

        $rawService = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name',
                        'Servicetemplate.service_url'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address'
                    ]
                ]
            ],
            'conditions' => [
                'Service.id' => $id
            ]
        ]);

        if ($rawService['Service']['service_url'] === '' || $rawService['Service']['service_url'] === null) {
            $rawService['Service']['service_url'] = $rawService['Servicetemplate']['service_url'];
        }

        $ServiceMacroReplacer = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($rawService);
        $rawService['Service']['service_url_replaced'] = $rawService['Service']['service_url'];
        if ($rawService['Service']['service_url'] !== '' && $rawService['Service']['service_url'] !== null) {
            $rawService['Service']['service_url_replaced'] = $ServiceMacroReplacer->replaceBasicMacros($rawService['Service']['service_url']);
        }

        $rawHost = $this->Host->find('first', $this->Host->getQueryForServiceBrowser($rawService['Service']['host_id']));
        $host = new \itnovum\openITCOCKPIT\Core\Views\Host($rawHost);
        $rawHost['Host']['is_satellite_host'] = $host->isSatelliteHost();

        $containerIdsToCheck = Hash::extract($rawHost, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $rawHost['Host']['container_id'];

        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        if ($this->hasRootPrivileges) {
            $allowEdit = true;
        } else {
            $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
            $allowEdit = $ContainerPermissions->hasPermission();
        }

        if (!$this->isAngularJsRequest()) {
            $User = new User($this->Auth);

            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('host', $rawHost);
            $this->set('service', $rawService);
            $this->set('allowEdit', $allowEdit);
            $this->set('docuExists', $DocumentationsTable->existsByUuid($rawService['Service']['uuid']));
            //Only ship template
            return;
        }


        $serviceQuery = $this->Service->getQueryForBrowser($id);
        $service = $this->Service->find('first', $serviceQuery);


        $servicetemplateQuery = $this->Servicetemplate->getQueryForBrowser($service['Service']['servicetemplate_id']);
        $servicetemplate = $this->Servicetemplate->find('first', $servicetemplateQuery);


        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        $hosttemplate = [
            'Hosttemplate' => [
                'id' => $rawHost['Hosttemplate']['id']
            ],
            'Contact'      => $rawHost['Hosttemplate']['Contact'],
            'Contactgroup' => $rawHost['Hosttemplate']['Contactgroup']
        ];
        $HosttemplateMerger = new HosttemplateMerger($rawHost, $hosttemplate);


        $ServicetemplateMerger = new ServicetemplateMerger($service, $servicetemplate);
        //Use host/hosttemplate contacts and contactgroups as default
        $contacts = $HosttemplateMerger->mergeContacts();
        $contactgroups = $HosttemplateMerger->mergeContactgroups();

        $ServicetemplateMerger->mergeContacts();
        $ServicetemplateMerger->mergeContactgroups();

        $mergedService = [
            'Service'                     => $ServicetemplateMerger->mergeServiceWithTemplate(),
            'CheckPeriod'                 => $ServicetemplateMerger->mergeCheckPeriod(),
            'NotifyPeriod'                => $ServicetemplateMerger->mergeNotifyPeriod(),
            'CheckCommand'                => $ServicetemplateMerger->mergeCheckCommand(),
            'Customvariable'              => $ServicetemplateMerger->mergeCustomvariables(),
            'Servicecommandargumentvalue' => $ServicetemplateMerger->mergeCommandargumentsForReplace()
        ];

        if ($ServicetemplateMerger->areContactsFromService() || $ServicetemplateMerger->areContactsFromServicetemplate()) {
            //Overwrite contacts from service/servicetemplate
            $contacts = $ServicetemplateMerger->mergeContacts();
            $contactgroups = $ServicetemplateMerger->mergeContactgroups();
            $mergedService['areContactsFromHost'] = false;
            $mergedService['areContactsFromHosttemplate'] = false;
            $mergedService['areContactsFromService'] = $ServicetemplateMerger->areContactsFromService();
            $mergedService['areContactsFromServicetemplate'] = $ServicetemplateMerger->areContactsFromServicetemplate();
        } else {
            $mergedService['areContactsFromHost'] = $HosttemplateMerger->areContactsFromHost();
            $mergedService['areContactsFromHosttemplate'] = $HosttemplateMerger->areContactsFromHosttemplate();
            $mergedService['areContactsFromService'] = false;
            $mergedService['areContactsFromServicetemplate'] = false;
        }


        $mergedService['Service']['allowEdit'] = $allowEdit;
        $mergedService['checkIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['check_interval']);
        $mergedService['retryIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['retry_interval']);
        $mergedService['notificationIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['notification_interval']);

        $ServiceMacroReplacer = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($mergedService);
        $mergedService['Service']['service_url_replaced'] = $mergedService['Service']['service_url'];
        if ($mergedService['Service']['service_url'] !== '' && $mergedService['Service']['service_url'] !== null) {
            $mergedService['Service']['service_url_replaced'] = $ServiceMacroReplacer->replaceBasicMacros($mergedService['Service']['service_url']);
        }

        // Replace $HOSTNAME$
        $ServiceMacroReplacerCommandLine = new HostMacroReplacer($rawHost);
        $serviceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($mergedService['CheckCommand']['command_line']);

        // Replace $_SERVICEFOOBAR$
        $ServiceCustomMacroReplacer = new CustomMacroReplacer($mergedService['Customvariable'], OBJECT_SERVICE);
        $serviceCommandLine = $ServiceCustomMacroReplacer->replaceAllMacros($serviceCommandLine);

        // Replace $SERVICEDESCRIPTION$
        $ServiceMacroReplacerCommandLine = new ServiceMacroReplacer($mergedService);
        $serviceCommandLine = $ServiceMacroReplacerCommandLine->replaceBasicMacros($serviceCommandLine);

        // Replace Command args $ARGx$
        $serviceCommandLine = str_replace(
            array_keys($mergedService['Servicecommandargumentvalue']),
            array_values($mergedService['Servicecommandargumentvalue']),
            $serviceCommandLine
        );
        $mergedService['serviceCommandLine'] = $serviceCommandLine;


        //Check permissions for Contacts
        $contactsWithContainers = [];
        $writeContainers = $this->getWriteContainers();
        foreach ($contacts as $key => $contact) {
            $contactsWithContainers[$contact['id']] = [];
            foreach ($contact['Container'] as $container) {
                $contactsWithContainers[$contact['id']][] = $container['id'];
            }

            $contacts[$key]['allowEdit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_contacts[$key]['allowEdit'] = false;
                if (!empty(array_intersect($contactsWithContainers[$contact['id']], $writeContainers))) {
                    $all_contacts[$key]['allowEdit'] = true;
                }
            }
        }

        //Check permissions for Contact groups
        foreach ($contactgroups as $key => $contactgroup) {
            $contactgroups[$key]['allowEdit'] = $this->isWritableContainer($contactgroup['Container']['parent_id']);
        }

        //Get host and service status
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth()
            ->lastStateChange();


        /** @var $HoststatusTable HoststatusTableInterface */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        /** @var $ServicestatusTable ServicestatusTableInterface */
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        $hoststatus = $HoststatusTable->byUuid($rawHost['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            //Empty host state for Hoststatus object
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus'], $UserTime);
        $hoststatus = $Hoststatus->toArrayForBrowser();


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->wildcard();

        $servicestatus = $ServicestatusTable->byUuid($service['Service']['uuid'], $ServicestatusFields);
        if (empty($servicestatus)) {
            //Empty host state for Servicestatus object
            $servicestatus = [
                'Servicestatus' => []
            ];
        }
        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus'], $UserTime);
        $servicestatus = $Servicestatus->toArrayForBrowser();
        $servicestatus['longOutputHtml'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($Servicestatus->getLongOutput(), true));

        $PerfdataChecker = new PerfdataChecker(
            new \itnovum\openITCOCKPIT\Core\Views\Host($rawHost),
            new \itnovum\openITCOCKPIT\Core\Views\Service($rawService),
            $this->PerfdataBackend,
            $Servicestatus
        );
        $mergedService['Service']['has_graph'] = $PerfdataChecker->hasPerfdata();
        $PerfdataParser = new PerfdataParser($Servicestatus->getPerfdata());
        $mergedService['Perfdata'] = $PerfdataParser->parse();


        //Check for acknowledgements and downtimes
        $acknowledgement = [];
        if ($Servicestatus->isAcknowledged()) {
            $acknowledgement = $this->AcknowledgedService->byServiceUuid($service['Service']['uuid']);
            if (!empty($acknowledgement)) {
                $Acknowledgement = new AcknowledgementService($acknowledgement['AcknowledgedService'], $UserTime);
                $acknowledgement = $Acknowledgement->toArray();

                $ticketSystem = $this->Systemsetting->find('first', [
                    'conditions' => ['key' => 'TICKET_SYSTEM.URL'],
                ]);

                $ticketDetails = [];
                if (!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $Acknowledgement->getCommentData(), $ticketDetails)) {
                    $commentDataHtml = $Acknowledgement->getCommentData();
                    if (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) {
                        $commentDataHtml = sprintf(
                            '<a href="%s%s" target="_blank">%s %s</a>',
                            $ticketSystem['Systemsetting']['value'],
                            $ticketDetails[3],
                            $ticketDetails[1],
                            $ticketDetails[2]
                        );
                    }
                } else {
                    $commentDataHtml = $this->Bbcode->asHtml($Acknowledgement->getCommentData(), true);
                }

                $acknowledgement['commentDataHtml'] = $commentDataHtml;
            }
        }

        $downtime = [];
        if ($Servicestatus->isInDowntime()) {
            $downtime = $this->DowntimeService->byServiceUuid($service['Service']['uuid'], true);
            if (!empty($downtime)) {
                $Downtime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeService'], $allowEdit, $UserTime);
                $downtime = $Downtime->toArray();
            }
        }

        //Get Host Ack and Donwtime
        $hostDowntime = [];
        if ($Hoststatus->isInDowntime()) {
            $hostDowntime = $this->DowntimeHost->byHostUuid($rawHost['Host']['uuid'], true);
            if (!empty($hostDowntime)) {
                $DowntimeHost = new \itnovum\openITCOCKPIT\Core\Views\Downtime($hostDowntime['DowntimeHost'], $allowEdit, $UserTime);
                $hostDowntime = $DowntimeHost->toArray();
            }
        }

        $hostAcknowledgement = [];
        if ($Hoststatus->isAcknowledged()) {
            $hostAcknowledgement = $this->AcknowledgedHost->byHostUuid($rawHost['Host']['uuid']);
            if (!empty($hostAcknowledgement)) {
                $AcknowledgementHost = new AcknowledgementHost($hostAcknowledgement['AcknowledgedHost'], $UserTime);
                $hostAcknowledgement = $AcknowledgementHost->toArray();

                $ticketSystem = $this->Systemsetting->find('first', [
                    'conditions' => ['key' => 'TICKET_SYSTEM.URL'],
                ]);

                $ticketDetails = [];
                if (!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $AcknowledgementHost->getCommentData(), $ticketDetails)) {
                    $commentDataHtml = $AcknowledgementHost->getCommentData();
                    if (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) {
                        $commentDataHtml = sprintf(
                            '<a href="%s%s" target="_blank">%s %s</a>',
                            $ticketSystem['Systemsetting']['value'],
                            $ticketDetails[3],
                            $ticketDetails[1],
                            $ticketDetails[2]
                        );
                    }
                } else {
                    $commentDataHtml = $this->Bbcode->asHtml($AcknowledgementHost->getCommentData(), true);
                }

                $hostAcknowledgement['commentDataHtml'] = $commentDataHtml;
            }
        }

        $docuExists = $DocumentationsTable->existsByUuid($mergedService['Service']['uuid']);

        $canSubmitExternalCommands = $this->hasPermission('externalcommands', 'hosts');

        $this->set('mergedService', $mergedService);
        $this->set('host', $rawHost);
        $this->set('docuExists', $docuExists);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('hoststatus', $hoststatus);
        $this->set('servicestatus', $servicestatus);
        $this->set('acknowledgement', $acknowledgement);
        $this->set('downtime', $downtime);
        $this->set('hostDowntime', $hostDowntime);
        $this->set('hostAcknowledgement', $hostAcknowledgement);
        $this->set('canSubmitExternalCommands', $canSubmitExternalCommands);
        $this->set('_serialize', [
            'mergedService',
            'host',
            'hoststatus',
            'servicestatus',
            'docuExists',
            'contacts',
            'contactgroups',
            'acknowledgement',
            'hostAcknowledgement',
            'downtime',
            'hostDowntime',
            'canSubmitExternalCommands'
        ]);
    }

    /**
     * @param int|null $host_id
     * @deprecated
     */
    public function serviceList($host_id = null) {
        $this->layout = 'blank';
        $User = new User($this->Auth);

        if (!$this->isApiRequest() && $host_id === null) {
            /** @var $Systemsettings SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            //Only ship HTML template
            return;
        }


        if (!$this->Host->exists($host_id) && $host_id !== null) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.host_url',
                'Host.container_id',
            ],
            'conditions' => [
                'Host.id' => $host_id,
            ],
            'contain'    => [
                'Container',
            ],
        ]);

        //Check if user is permitted to see this object
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        if (!$this->isApiRequest()) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            //Only ship HTML template
            return;
        }
    }

    /**
     * Converts BB code to HTML
     *
     * @param string $uuid The services UUID you want to get the long output
     * @param bool $parseBbcode If you want to convert BB Code to HTML
     * @param bool $nl2br If you want to replace \n with <br>
     *
     * @return string
     * @deprecated
     */
    function longOutputByUuid($uuid = null, $parseBbcode = true, $nl2br = true) {
        $this->autoRender = false;
        $result = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.uuid' => $uuid
            ]
        ]);
        if (!empty($result)) {
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->longOutput();
            $servicestatus = $this->Servicestatus->byUuid($result['Service']['uuid'], $ServicestatusFields);
            if (isset($servicestatus['Servicestatus']['long_output'])) {
                if ($parseBbcode === true) {
                    if ($nl2br === true) {
                        return $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($servicestatus['Servicestatus']['long_output'], $nl2br));
                    } else {
                        return $this->Bbcode->asHtml($servicestatus['Servicestatus']['long_output'], $nl2br);
                    }
                }

                return $servicestatus['Servicestatus']['long_output'];
            }
        }

        return '';
    }


    public function listToPdf() {
        $this->layout = 'Admin.default';

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $ServiceFilter = new ServiceFilter($this->request);
        $User = new User($this->Auth);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions(
            $ServiceFilter->indexFilter()
        );
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);

        if ($ServiceControllerRequest->isRequestFromBrowser() === false) {
            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        }

        if ($ServiceControllerRequest->isRequestFromBrowser() === true) {
            $browserContainerIds = $ServiceControllerRequest->getBrowserContainerIdsByRequest();
            foreach ($browserContainerIds as $containerIdToCheck) {
                if (!in_array($containerIdToCheck, $this->MY_RIGHTS)) {
                    $this->render403();
                    return;
                }
            }

            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($browserContainerIds);

            if ($User->isRecursiveBrowserEnabled()) {
                //get recursive container ids
                $containerIdToResolve = $browserContainerIds;
                $children = $ContainersTable->getChildren($containerIdToResolve[0]);
                $containerIds = \Cake\Utility\Hash::extract($children, '{n}.id');
                $recursiveContainerIds = [];
                foreach ($containerIds as $containerId) {
                    if (in_array($containerId, $this->MY_RIGHTS)) {
                        $recursiveContainerIds[] = $containerId;
                    }
                }
                $ServiceConditions->setContainerIds(array_merge($ServiceConditions->getContainerIds(), $recursiveContainerIds));
            }
        }

        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Hosts.name'  => 'asc',
            'servicename' => 'asc'
        ]));


        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServiceFilter->getPage());

        if ($this->DbBackend->isNdoUtils()) {
            $services = $ServicesTable->getServiceIndex($ServiceConditions, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields
            ->currentState()
            ->isFlapping()
            ->lastHardStateChange();
        $hoststatusCache = $this->Hoststatus->byUuid(
            array_unique(\Cake\Utility\Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
            $HoststatusFields
        );


        $all_services = [];
        $UserTime = $User->getUserTime();
        foreach ($services as $service) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['_matchingData']['Hosts']);
            if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
            } else {
                $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([], $UserTime);
            }
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);

            $tmpRecord = [
                'Service'       => $Service,
                'Host'          => $Host,
                'Hoststatus'    => $Hoststatus,
                'Servicestatus' => $Servicestatus
            ];
            $all_services[] = $tmpRecord;
        }

        $this->set('all_services', $all_services);

        $filename = 'Services_' . strtotime('now') . '.pdf';
        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => $filename,
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }

    /**
     * For ACL only
     */
    public function checkcommand() {
        return;
    }

    /**
     * For ACL only
     */
    public function externalcommands() {
        return;
    }

    public function icon() {
        $this->layout = 'blank';
        //Only ship HTML Template
        return;
    }

    public function servicecumulatedstatusicon() {
        $this->layout = 'blank';
        //Only ship HTML Template
        return;
    }

    /**
     * @deprecated
     * Refactor javascript directive serviceStatusDetails as soon as self::browser() got refactord
     */
    public function details() {
        //Only ship template for auto maps modal

        $this->layout = 'blank';
        //Only ship HTML Template

        $User = new User($this->Auth);
        $this->set('username', $User->getFullName());
        return;
    }

    /**
     * @deprecated
     */
    public function loadServicesByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $this->Service->virtualFields['servicename'] = 'CONCAT(Host.name,"/",IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name))';
        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServiceFilter = new ServiceFilter($this->request);
        $containerIds = [ROOT_CONTAINER, $containerId];

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setContainerIds($containerIds);
        $ServiceCondition->setIncludeDisabled(false);

        $services = Api::makeItJavaScriptAble(
            $this->Service->getServicesForAngular($ServiceCondition, $selected)
        );

        $this->set(compact(['services']));
        $this->set('_serialize', ['services']);
    }


    /**
     * @deprecated
     */
    public function loadServicesByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $this->Service->virtualFields['servicename'] = 'CONCAT(Host.name,"/",IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name))';
        $selected = $this->request->query('selected');
        $includeDisabled = $this->request->query('includeDisabled') === 'true';

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setIncludeDisabled($includeDisabled);
        $ServiceCondition->setContainerIds($this->MY_RIGHTS);
        $ServiceCondition->includeDisabled();

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngular($ServiceCondition, $selected)
        );


        $this->set('services', $services);
        $this->set('_serialize', ['services']);
    }

    /**
     * @param int|null $id
     * @deprecated
     */
    public function timeline($id = null) {
        session_write_close();
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->find('first', [
            'conditions' => [
                'Service.id' => $id,
            ],
            'contain'    => [
                'Host'            => [
                    'Container'
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.check_period_id',
                        'Servicetemplate.notify_period_id'
                    ]
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Host.uuid',
                'Service.check_period_id',
                'Service.notify_period_id'
            ]
        ]);

        $containerIdsToCheck = Hash::extract($service['Host'], 'Container.{n}.HostsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $timeperiodId = ($service['Service']['check_period_id']) ? $service['Service']['check_period_id'] : $service['Servicetemplate']['check_period_id'];
        //$notifyPeriodId = ($service['Service']['notify_period_id']) ? $service['Service']['notify_period_id'] : $service['Servicetemplate']['notify_period_id'];

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $checkTimePeriod = $TimeperiodsTable->getTimeperiodWithTimerangesById($timeperiodId);


        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        $Groups = new Groups();
        $this->set('groups', $Groups->serialize(false));

        $start = $this->request->query('start');
        $end = $this->request->query('end');


        if (!is_numeric($start) || $start < 0) {
            $start = time() - 2 * 24 * 3600;
        }


        if (!is_numeric($end) || $end < 0) {
            $end = time();
        }

        /*************  TIME RANGES *************/
        $timeRanges = $this->DateRange->createDateRanges(
            date('d-m-Y H:i:s', $start),
            date('d-m-Y H:i:s', $end),
            $checkTimePeriod['Timeperiod']['timeperiod_timeranges']
        );

        $TimeRangeSerializer = new TimeRangeSerializer($timeRanges, $UserTime);
        $this->set('timeranges', $TimeRangeSerializer->serialize());
        unset($TimeRangeSerializer, $timeRanges);

        $hostUuid = $service['Host']['uuid'];
        $serviceUuid = $service['Service']['uuid'];

        /*************  HOST STATEHISTORY *************/
        //Process conditions
        $Conditions = new StatehistoryHostConditions();
        $Conditions->setOrder(['StatehistoryHost.state_time' => 'asc']);

        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);
        $Conditions->setUseLimit(false);

        //Query state history records for hosts
        $query = $this->StatehistoryHost->getQuery($Conditions);
        $statehistories = $this->StatehistoryHost->find('all', $query);
        $statehistoryRecords = [];

        //Host has no state history record for selected time range
        //Get last available state history record for this host
        $query = $this->StatehistoryHost->getLastRecord($Conditions);
        $record = $this->StatehistoryHost->find('first', $query);
        if (!empty($record)) {
            $record['StatehistoryHost']['state_time'] = $start;
            $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($record['StatehistoryHost']);
            $statehistoryRecords[] = $StatehistoryHost;
        }

        if (empty($statehistories) && empty($record)) {
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState()
                ->isHardstate()
                ->lastStateChange()
                ->lastHardStateChange();

            $hoststatus = $this->Hoststatus->byUuid($hostUuid, $HoststatusFields);
            if (!empty($hoststatus)) {
                $record['StatehistoryHost']['state_time'] = $hoststatus['Hoststatus']['last_state_change'];
                $record['StatehistoryHost']['state'] = $hoststatus['Hoststatus']['current_state'];
                $record['StatehistoryHost']['state_type'] = ($hoststatus['Hoststatus']['state_type']) ? true : false;
                $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($record['StatehistoryHost']);
                $statehistoryRecords[] = $StatehistoryHost;
            }
        }


        foreach ($statehistories as $statehistory) {
            $StatehistoryHost = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryHost($statehistory['StatehistoryHost']);
            $statehistoryRecords[] = $StatehistoryHost;
        }


        $StatehistorySerializer = new StatehistorySerializer($statehistoryRecords, $UserTime, $end, 'host');
        $this->set('statehistory', $StatehistorySerializer->serialize());
        unset($StatehistorySerializer, $statehistoryRecords);

        /*************  SERVICE STATEHISTORY *************/
        //Process conditions
        $StatehistoryServiceConditions = new StatehistoryServiceConditions();
        $StatehistoryServiceConditions->setOrder(['StatehistoryService.state_time' => 'asc']);
        $StatehistoryServiceConditions->setFrom($start);
        $StatehistoryServiceConditions->setTo($end);
        $StatehistoryServiceConditions->setServiceUuid($serviceUuid);
        $StatehistoryServiceConditions->setUseLimit(false);
        //Query state history records for service
        $query = $this->StatehistoryService->getQuery($StatehistoryServiceConditions);
        $statehistoriesService = $this->StatehistoryService->find('all', $query);
        $statehistoryServiceRecords = [];

        //Service has no state history record for selected time range
        //Get last available state history record for this host
        $query = $this->StatehistoryService->getLastRecord($StatehistoryServiceConditions);
        $record = $this->StatehistoryService->find('first', $query);
        if (!empty($record)) {
            $record['StatehistoryService']['state_time'] = $start;
            $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($record['StatehistoryService']);
            $statehistoryServiceRecords[] = $StatehistoryService;
        }

        if (empty($statehistoriesService) && empty($record)) {
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->currentState()
                ->isHardstate()
                ->lastStateChange()
                ->lastHardStateChange();

            $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
            if (!empty($servicestatus)) {
                $record['StatehistoryService']['state_time'] = $servicestatus['Servicestatus']['last_state_change'];
                $record['StatehistoryService']['state'] = $servicestatus['Servicestatus']['current_state'];
                $record['StatehistoryService']['state_type'] = $servicestatus['Servicestatus']['state_type'];
                $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($record['StatehistoryService']);
                $statehistoryServiceRecords[] = $StatehistoryService;
            }
        }

        foreach ($statehistoriesService as $statehistoryService) {
            $StatehistoryService = new \itnovum\openITCOCKPIT\Core\Views\StatehistoryService($statehistoryService['StatehistoryService']);
            $statehistoryServiceRecords[] = $StatehistoryService;
        }


        $StatehistorySerializer = new StatehistorySerializer($statehistoryServiceRecords, $UserTime, $end, 'service');
        $this->set('servicestatehistory', $StatehistorySerializer->serialize());
        unset($StatehistorySerializer, $statehistoryServiceRecords);

        /*************  SERVICE DOWNTIMES *************/
        //Query downtime records for hosts
        $DowntimeServiceConditions = new DowntimeServiceConditions();
        $DowntimeServiceConditions->setOrder(['DowntimeService.scheduled_start_time' => 'asc']);
        $DowntimeServiceConditions->setFrom($start);
        $DowntimeServiceConditions->setTo($end);
        $DowntimeServiceConditions->setServiceUuid($serviceUuid);
        $DowntimeServiceConditions->setIncludeCancelledDowntimes(true);


        $query = $this->DowntimeService->getQueryForReporting($DowntimeServiceConditions);
        $downtimes = $this->DowntimeService->find('all', $query);
        $downtimeRecords = [];
        foreach ($downtimes as $downtime) {
            $downtimeRecords[] = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeService']);
        }

        $DowntimeSerializer = new DowntimeSerializer($downtimeRecords, $UserTime);
        $this->set('downtimes', $DowntimeSerializer->serialize());
        unset($DowntimeSerializer, $downtimeRecords);

        /*************  SERVICE NOTIFICATIONS *************/
        $Conditions = new ServiceNotificationConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setServiceUuid($serviceUuid);
        $query = $this->NotificationService->getQuery($Conditions, []);

        $notificationRecords = [];
        foreach ($this->NotificationService->find('all', $query) as $notification) {
            $notificationRecords[] = [
                'NotificationService' => new \itnovum\openITCOCKPIT\Core\Views\NotificationService($notification),
                'Command'             => new \itnovum\openITCOCKPIT\Core\Views\Command($notification['Command']),
                'Contact'             => new \itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contact'])
            ];
        }

        $NotificationSerializer = new NotificationSerializer($notificationRecords, $UserTime, 'service');
        $this->set('notifications', $NotificationSerializer->serialize());
        unset($NotificationSerializer, $notificationRecords);

        /*************  SERVICE ACKNOWLEDGEMENTS *************/
        //Process conditions
        $Conditions = new AcknowledgedServiceConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setServiceUuid($serviceUuid);

        $acknowledgementRecords = [];
        $query = $this->AcknowledgedService->getQuery($Conditions, []);
        foreach ($this->AcknowledgedService->find('all', $query) as $acknowledgement) {
            $acknowledgementRecords[] = new AcknowledgementService($acknowledgement['AcknowledgedService']);
        }

        $AcknowledgementSerializer = new AcknowledgementSerializer($acknowledgementRecords, $UserTime);
        $this->set('acknowledgements', $AcknowledgementSerializer->serialize());

        $this->set('start', $start);
        $this->set('end', $end);
        $this->set('_serialize', [
            'start',
            'end',
            'groups',
            'statehistory',
            'servicestatehistory',
            'downtimes',
            'notifications',
            'acknowledgements',
            'timeranges'
        ]);
    }

    /**
     * @param int $id
     * @deprecated
     */
    public function serviceBrowserMenu($id) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Service->exists($id)) {
            throw new NotFoundException();
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name',
                        'Servicetemplate.service_url'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address'
                    ]
                ]
            ],
            'conditions' => [
                'Service.id' => $id
            ]
        ]);

        if ($service['Service']['service_url'] === '' || $service['Service']['service_url'] === null) {
            $service['Service']['service_url'] = $service['Servicetemplate']['service_url'];
        }

        if ($service['Service']['name'] === '' || $service['Service']['name'] === null) {
            $service['Service']['name'] = $service['Servicetemplate']['name'];
        }

        $rawHost = $this->Host->find('first', $this->Host->getQueryForServiceBrowser($service['Service']['host_id']));
        $host = new \itnovum\openITCOCKPIT\Core\Views\Host($rawHost);
        $rawHost['Host']['is_satellite_host'] = $host->isSatelliteHost();

        $containerIdsToCheck = Hash::extract($rawHost, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $rawHost['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        /** @var $DocumentationsTable DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');
        $docuExists = $DocumentationsTable->existsForUuid($service['Service']['uuid']);

        //Get meta data and push to front end
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState()->isFlapping();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        if (!isset($servicestatus['Servicestatus'])) {
            $servicestatus['Servicestatus'] = [];
        }
        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);

        $ServiceMacroReplacer = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($service);
        $service['Service']['service_url_replaced'] = $service['Service']['service_url'];
        if ($service['Service']['service_url'] !== '' && $service['Service']['service_url'] !== null) {
            $service['Service']['service_url_replaced'] = $ServiceMacroReplacer->replaceBasicMacros($service['Service']['service_url']);
        }

        if ($this->hasRootPrivileges) {
            $allowEdit = true;
        } else {
            $ContainerPermissions = new \itnovum\openITCOCKPIT\Core\Views\ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
            $allowEdit = $ContainerPermissions->hasPermission();
        }
        $service['Service']['allowEdit'] = $allowEdit;

        $this->set('service', $service);
        $this->set('servicestatus', $Servicestatus->toArray());
        $this->set('docuExists', $docuExists);
        $this->set('_serialize', ['service', 'servicestatus', 'docuExists']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/


    /**
     * @param int $hostId
     * @param int $serviceId
     * @throws Exception
     */
    public function loadElementsByHostId($hostId, $serviceId = 0) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $hostId = (int)$hostId;
        $serviceId = (int)$serviceId;

        $servicetemplateType = GENERIC_SERVICE;

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $containerId = $HostsTable->getHostPrimaryContainerIdByHostId($hostId);

        if ($serviceId != 0) {
            try {
                $service = $ServicesTable->get($serviceId);
                $servicetemplateType = $service->get('service_type');
            } catch (RecordNotFoundException $e) {
                //Ignore error
            }
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);


        $servicetemplates = $ServicetemplatesTable->getServicetemplatesByContainerId($containerIds, 'list', $servicetemplateType);
        $servicetemplates = Api::makeItJavaScriptAble($servicetemplates);

        $servicegroups = $ServicegroupsTable->getServicegroupsByContainerId($containerIds, 'list', 'id');
        $servicegroups = Api::makeItJavaScriptAble($servicegroups);

        $timeperiods = $TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);


        $this->set('servicetemplates', $servicetemplates);
        $this->set('servicegroups', $servicegroups);
        $this->set('timeperiods', $timeperiods);
        $this->set('checkperiods', $checkperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);

        $this->set('_serialize', [
            'servicetemplates',
            'servicegroups',
            'timeperiods',
            'checkperiods',
            'contacts',
            'contactgroups'
        ]);
    }

    /**
     * @param int $servicetemplateId
     * @param int|null $hostId
     */
    public function loadServicetemplate($servicetemplateId, $hostId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        if (!$ServicetemplatesTable->existsById($servicetemplateId)) {
            throw new NotFoundException(__('Invalid service template'));
        }

        $servicetemplate = $ServicetemplatesTable->getServicetemplateForEdit($servicetemplateId);
        $toJson = ['servicetemplate'];


        if ($hostId !== null) {
            //We are in /services/add

            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            if ($HostsTable->existsById($hostId)) {
                $host = $HostsTable->get($hostId);

                //We are in services/add
                //There is no service jet, so no contacts
                $serviceContacts = [
                    'contacts'      => ['_ids' => []],
                    'contactgroups' => ['_ids' => []]
                ];

                $servicetemplateContactsAndContactgroups = $ServicetemplatesTable->getContactsAndContactgroupsById($servicetemplateId);
                $hostContactsAndContactgroups = $HostsTable->getContactsAndContactgroupsById($hostId);
                $hosttemplateContactsAndContactgroups = $HosttemplatesTable->getContactsAndContactgroupsById($host->get('hosttemplate_id'));

                $ServiceMergerForView = new ServiceMergerForView(
                    ['Service' => $serviceContacts],
                    ['Servicetemplate' => $servicetemplateContactsAndContactgroups],
                    $hostContactsAndContactgroups,
                    $hosttemplateContactsAndContactgroups
                );

                $contactsAndContactgroups = $ServiceMergerForView->getDataForContactsAndContactgroups();

                $this->set('contactsAndContactgroups', $contactsAndContactgroups);
                $this->set('hostContactsAndContactgroups', $hostContactsAndContactgroups);
                $this->set('hosttemplateContactsAndContactgroups', $hosttemplateContactsAndContactgroups);
                $this->set('servicetemplateContactsAndContactgroups', $servicetemplateContactsAndContactgroups);
                $this->set('areContactsInheritedFromHosttemplate', $ServiceMergerForView->areContactsInheritedFromHosttemplate());
                $this->set('areContactsInheritedFromHost', $ServiceMergerForView->areContactsInheritedFromHost());
                $this->set('areContactsInheritedFromServicetemplate', $ServiceMergerForView->areContactsInheritedFromServicetemplate());
                $toJson[] = 'contactsAndContactgroups';
                $toJson[] = 'hostContactsAndContactgroups';
                $toJson[] = 'hosttemplateContactsAndContactgroups';
                $toJson[] = 'servicetemplateContactsAndContactgroups';
                $toJson[] = 'areContactsInheritedFromHosttemplate';
                $toJson[] = 'areContactsInheritedFromHost';
                $toJson[] = 'areContactsInheritedFromServicetemplate';
            }
        }


        $this->set('servicetemplate', $servicetemplate);
        $this->set('_serialize', $toJson);
    }

    public function loadCommands() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        $commands = $CommandsTable->getCommandByTypeAsList(CHECK_COMMAND);

        $eventhandlerCommands = [
            0 => __('None')
        ];

        //Use foreach because of array_merge remove the keys and adding None after getCommandByTypeAsList()
        //will display "None" as the last element in the select box
        foreach ($CommandsTable->getCommandByTypeAsList(EVENTHANDLER_COMMAND) as $eventhandlerCommndId => $eventhandlerCommandName) {
            $eventhandlerCommands[$eventhandlerCommndId] = $eventhandlerCommandName;
        }

        $this->set('commands', Api::makeItJavaScriptAble($commands));
        $this->set('eventhandlerCommands', Api::makeItJavaScriptAble($eventhandlerCommands));
        $this->set('_serialize', ['commands', 'eventhandlerCommands']);
    }

    /**
     * @param int|null $commandId
     * @param int|null $serviceId
     */
    public function loadCommandArguments($commandId = null, $serviceId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');

        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $servicecommandargumentvalues = [];

        if ($serviceId != null) {
            //User passed an serviceId, so we are in a non add mode!
            //Check if the service has defined command arguments

            /** @var $ServicecommandargumentvaluesTable ServicecommandargumentvaluesTable */
            $ServicecommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Servicecommandargumentvalues');

            $serviceCommandArgumentValues = $ServicecommandargumentvaluesTable->getByServiceIdAndCommandId($serviceId, $commandId);

            foreach ($serviceCommandArgumentValues as $serviceCommandArgumentValue) {
                $servicecommandargumentvalues[] = [
                    'commandargument_id' => $serviceCommandArgumentValue['commandargument_id'],
                    'service_id'         => $serviceCommandArgumentValue['service_id'],
                    'value'              => $serviceCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $serviceCommandArgumentValue['commandargument']['name'],
                        'human_name' => $serviceCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $serviceCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        if (empty($servicecommandargumentvalues)) {
            //Service has no command arguments defined
            //Or we are in services/add ?

            //Load command arguments of the check command
            foreach ($CommandargumentsTable->getByCommandId($commandId) as $commandargument) {
                $servicecommandargumentvalues[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        };

        $this->set('servicecommandargumentvalues', $servicecommandargumentvalues);
        $this->set('_serialize', ['servicecommandargumentvalues']);
    }

    /**
     * @param int|null $commandId
     * @param int|null $serviceId
     */
    public function loadEventhandlerCommandArguments($commandId = null, $serviceId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');


        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $serviceeventhandlercommandargumentvalues = [];

        if ($serviceId != null) {
            //User passed an serviceId, so we are in a non add mode!
            //Check if the service has defined command arguments for the event handler

            /** @var $ServiceeventcommandargumentvaluesTable ServiceeventcommandargumentvaluesTable */
            $ServiceeventcommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Serviceeventcommandargumentvalues');

            $serviceEventhandlerCommandArgumentValues = $ServiceeventcommandargumentvaluesTable->getByServiceIdAndCommandId($serviceId, $commandId);

            foreach ($serviceEventhandlerCommandArgumentValues as $serviceEventhandlerCommandArgumentValue) {
                $serviceeventhandlercommandargumentvalues[] = [
                    'commandargument_id' => $serviceEventhandlerCommandArgumentValue['commandargument_id'],
                    'service_id'         => $serviceEventhandlerCommandArgumentValue['service_id'],
                    'value'              => $serviceEventhandlerCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $serviceEventhandlerCommandArgumentValue['commandargument']['name'],
                        'human_name' => $serviceEventhandlerCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $serviceEventhandlerCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        if (empty($serviceeventhandlercommandargumentvalues)) {
            //Service has no command arguments defined
            //Or we are in services/add ?

            //Load event handler command arguments of the check command
            foreach ($CommandargumentsTable->getByCommandId($commandId) as $commandargument) {
                $serviceeventhandlercommandargumentvalues[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        };

        $this->set('serviceeventhandlercommandargumentvalues', $serviceeventhandlercommandargumentvalues);
        $this->set('_serialize', ['serviceeventhandlercommandargumentvalues']);
    }

    public function loadServicesByStringCake4() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');
        $containerId = $this->request->query('containerId');

        if (!$this->allowedByContainerId($containerId, false)) {
            $this->render403();
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $ServicesFilter = new ServiceFilter($this->request);

        $ServiceConditions = new ServiceConditions($ServicesFilter->indexFilter());
        $ServiceConditions->setContainerIds($containerIds);

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngularCake4($ServiceConditions, $selected)
        );

        $this->set('services', $services);
        $this->set('_serialize', ['services']);
    }

    public function loadServicesByContainerIdCake4() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');
        $containerId = $this->request->query('containerId');

        $ServiceFilter = new ServiceFilter($this->request);
        $containerIds = [ROOT_CONTAINER, $containerId];

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setContainerIds($containerIds);
        $ServiceCondition->setIncludeDisabled(false);


        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngularCake4($ServiceCondition, $selected)
        );

        $this->set('services', $services);
        $this->set('_serialize', ['services']);
    }
}
