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

use itnovum\openITCOCKPIT\Core\CustomMacroReplacer;
use itnovum\openITCOCKPIT\Core\CustomVariableDiffer;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\HosttemplateMerger;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ServiceMacroReplacer;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ServicetemplateMerger;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementService;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\PerfdataChecker;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\ScrollIndex;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;

/**
 * @property Container $Container
 * @property Service $Service
 * @property Host $Host
 * @property Servicetemplate $Servicetemplate
 * @property Servicegroup $Servicegroup
 * @property Command $Command
 * @property Commandargument $Commandargument
 * @property Timeperiod $Timeperiod
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Customvariable $Customvariable
 * @property Servicecommandargumentvalue $Servicecommandargumentvalue
 * @property Serviceeventcommandargumentvalue $Serviceeventcommandargumentvalue
 * @property Servicetemplatecommandargumentvalue $Servicetemplatecommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 * @property DeletedService $DeletedService
 * @property Rrd $Rrd
 * @property AcknowledgedService $AcknowledgedService
 * @property DowntimeService $DowntimeService
 * @property BbcodeComponent $Bbcode
 * @property DbBackend $DbBackend
 */
class ServicesController extends AppController {
    public $layout = 'Admin.default';
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
        'Grapher',
    ];
    public $uses = [
        'Service',
        'Host',
        'Servicetemplate',
        'Servicegroup',
        'Command',
        'Commandargument',
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
        'Rrd',
        'Container',
        'Documentation',
        'Systemsetting',
        MONITORING_DOWNTIME_HOST,
        MONITORING_DOWNTIME_SERVICE
    ];

    public function index() {
        $this->layout = 'angularjs';
        $User = new User($this->Auth);

        if (!$this->isApiRequest()) {
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            //Only ship HTML template
            return;
        }

        $ServiceFilter = new ServiceFilter($this->request);
        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions();
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
                $containerIds = Hash::extract($this->Container->children($containerIdToResolve[0], false, ['Container.id']), '{n}.Container.id');
                $recursiveContainerIds = [];
                foreach ($containerIds as $containerId) {
                    if (in_array($containerId, $this->MY_RIGHTS)) {
                        $recursiveContainerIds[] = $containerId;
                    }
                }
                $ServiceConditions->setContainerIds(array_merge($ServiceConditions->getContainerIds(), $recursiveContainerIds));
            }
        }
        //Default order


        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Host.name'           => 'asc',
            'Service.servicename' => 'asc'
        ]));
        //$ServiceConditions->setOrder($ServiceControllerRequest->getOrder('Servicestatus.current_state', 'desc'));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceIndexQuery($ServiceConditions, $ServiceFilter->indexFilter());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->Servicestatus->virtualFieldsForIndexAndServiceList();
            $query = $this->Servicestatus->getServiceIndexQuery($ServiceConditions, $ServiceFilter->indexFilter());
            $modelName = 'Servicestatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Service->getServiceIndexQueryStatusengine3($ServiceConditions, $ServiceFilter->indexFilter());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_services = $this->{$modelName}->find('all', $query);
            $this->set('all_services', $all_services);
            $this->set('_serialize', ['all_services']);
            return;
        } else {
            if($this->isScrollRequest()){
                $this->Paginator->settings['page'] = $ServiceFilter->getPage();
                $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
                $ScrollIndex = new ScrollIndex($this->Paginator, $this);
                $services = $this->{$modelName}->find('all', array_merge($this->Paginator->settings, $query));
                $ScrollIndex->determineHasNextPage($services);
                $ScrollIndex->scroll();
            }else{
                $this->Paginator->settings['page'] = $ServiceFilter->getPage();
                $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
                $services = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
            }
            //debug($this->Service->getDataSource()->getLog(false, false));
        }

        $hostContainers = [];
        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostIds = array_unique(Hash::extract($services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        $all_services = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($services as $service) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$service['Host']['id']])) {
                    $containerIds = $hostContainers[$service['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($service['Hoststatus'], $UserTime);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
            $PerfdataChecker = new PerfdataChecker($Host, $Service);

            $tmpRecord = [
                'Service'       => $Service->toArray(),
                'Host'          => $Host->toArray(),
                'Servicestatus' => $Servicestatus->toArray(),
                'Hoststatus'    => $Hoststatus->toArray()
            ];
            $tmpRecord['Service']['has_graph'] = $PerfdataChecker->hasRrdFile();
            $all_services[] = $tmpRecord;
        }

        $this->set('all_services', $all_services);
        $toJson = ['all_services', 'paging'];
        if($this->isScrollRequest()){
            $toJson = ['all_services', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $service = $this->Service->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'), false)) {
            $this->render403();

            return;
        }

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->wildcard();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        if (empty($servicestatus)) {
            $servicestatus = [
                'Servicestatus' => [],
            ];
        }
        $service = Hash::merge($service, $servicestatus);

        $this->set('service', $service);
        $this->set('_serialize', ['service']);
    }

    public function notMonitored() {
        $this->layout = 'angularjs';
        $User = new User($this->Auth);

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions();
        $ServiceConditions->setIncludeDisabled(false);
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);


        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder('Host.name', 'asc'));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceNotMonitoredQuery($ServiceConditions, $ServiceFilter->notMonitoredFilter());
            $this->Service->virtualFieldsForNotMonitored();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->loadModel('CrateModule.CrateService');
            $this->CrateService->virtualFieldsForServicesNotMonitored();
            $query = $this->CrateService->getServiceNotMonitoredQuery($ServiceConditions, $ServiceFilter->indexFilter());
            $modelName = 'CrateService';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Service->getServiceNotMonitoredQueryStatusengine3($ServiceConditions, $ServiceFilter->notMonitoredFilter());
            $this->Service->virtualFieldsForNotMonitored();
            $modelName = 'Service';
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            if ($this->DbBackend->isCrateDb()) {
                $all_services = $this->{$modelName}->find('all', $query);
                foreach ($all_services as $key => $record) {
                    //Rename key from CrateService to Service
                    $all_services[$key]['Service'] = $record['CrateService'];
                    unset($all_services[$key]['CrateService']);
                }
            } else {
                $all_services = $this->{$modelName}->find('all', $query);
            }
            $this->set('all_services', $all_services);
            $this->set('_serialize', ['all_services']);
            return;
        } else {
            $this->Paginator->settings['page'] = $ServiceFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $services = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
            //debug($this->Service->getDataSource()->getLog(false, false));
        }

        $hostContainers = [];
        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostIds = array_unique(Hash::extract($services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $hoststatusCache = $this->Hoststatus->byUuid(array_unique(Hash::extract($services, '{n}.Host.uuid')), $HoststatusFields);


        $all_services = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($services as $service) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$service['Host']['id']])) {
                    $containerIds = $hostContainers[$service['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
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
        $this->set('_serialize', ['all_services', 'paging']);

    }

    public function disabled() {
        $this->layout = 'angularjs';

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions();
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);


        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder('Host.name', 'asc'));

        $query = $this->Service->getServiceDisabledQuery($ServiceConditions, $ServiceFilter->disabledFilter());
        $this->Service->virtualFieldsForDisabled();
        $modelName = 'Service';


        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_services = $this->{$modelName}->find('all', $query);
            $this->set('all_services', $all_services);
            $this->set('_serialize', ['all_services']);
            return;
        } else {
            $this->Paginator->settings['page'] = $ServiceFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $services = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
            //debug($this->Service->getDataSource()->getLog(false, false));
        }

        $hostContainers = [];
        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostIds = array_unique(Hash::extract($services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain'    => [
                    'Container',
                ],
                'fields'     => [
                    'Host.id',
                    'Container.*',
                ],
                'conditions' => [
                    'Host.id' => $hostIds,
                ],
            ]);
            foreach ($_hostContainers as $host) {
                $hostContainers[$host['Host']['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $hoststatusCache = $this->Hoststatus->byUuid(array_unique(Hash::extract($services, '{n}.Host.uuid')), $HoststatusFields);


        $all_services = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($services as $service) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$service['Host']['id']])) {
                    $containerIds = $hostContainers[$service['Host']['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
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
        $this->set('_serialize', ['all_services', 'paging']);
    }

    public function deleted() {
        $this->layout = 'angularjs';

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions();


        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder('DeletedService.name', 'asc'));

        $query = $this->Service->getServiceDeletedQuery($ServiceConditions, $ServiceFilter->deletedFilter());

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_services = $this->DeletedService->find('all', $query);
            $this->set('all_services', $all_services);
            $this->set('_serialize', ['all_services']);
            return;
        } else {
            $this->Paginator->settings['page'] = $ServiceFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $services = $this->Paginator->paginate('DeletedService', [], [key($this->Paginator->settings['order'])]);
            //debug($this->Service->getDataSource()->getLog(false, false));
        }

        $all_services = [];
        foreach ($services as $deletedService) {
            $DeletedService = new \itnovum\openITCOCKPIT\Core\Views\DeletedService($deletedService, $UserTime);
            $all_services[] = [
                'DeletedService' => $DeletedService->toArray()
            ];
        }

        $this->set('all_services', $all_services);
        $this->set('_serialize', ['all_services', 'paging']);
    }

    public function add() {
        $userId = $this->Auth->user('id');
        $Customvariable = [];
        $customFieldsToRefill = [
            'Service'      => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_warning',
                'notify_on_unknown',
                'notify_on_critical',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_ok',
                'flap_detection_on_warning',
                'flap_detection_on_unknown',
                'flap_detection_on_critical',
                'priority',
                'active_checks_enabled',
                'process_performance_data',
            ],
            'Contact'      => [
                'Contact',
            ],
            'Contactgroup' => [
                'Contactgroup',
            ],
        ];

        if (CakePlugin::loaded('MaximoModule')) {
            $customFieldsToRefill['Maximoconfiguration'] = [
                'type',
                'impact_level',
                'urgency_level',
                'maximo_ownergroup_id',
                'maximo_service_id'
            ];
        }

        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        //Check if a host was selected before adding new service (host service list)
        $hostId = null;
        if (!empty($this->request->params['pass'])) {
            $hostId = $this->request->params['pass'][0];
        }
        $this->Frontend->setJson('hostId', $hostId);

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        $this->loadModel('Customvariable');

        $userContainerId = $this->Auth->user('container_id');
        $myContainerId = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $myRights = $myContainerId;
        if (!$this->hasRootPrivileges && ($rootKey = array_search(ROOT_CONTAINER, $myRights)) !== false) {
            unset($myRights[$rootKey]);
        }


        $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($myContainerId, 'list');
        $timeperiods = $this->Timeperiod->find('list');
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list', 'id');
        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list', 'id');
        $commands = $this->Command->serviceCommands('list');
        $eventhandlers = $this->Command->eventhandlerCommands('list');
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');

        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));

        $this->set(compact([
            'hostId',
            'servicetemplates',
            'servicegroups',
            'timeperiods',
            'contacts',
            'contactgroups',
            'commands',
            'eventhandlers',
            'Customvariable',
        ]));

        if ($this->request->is('post') || $this->request->is('put')) {
            $ext_data_for_changelog = $this->getChangelogDataForAdd();

            $this->Service->set($this->request->data);
            if (isset($this->request->data['Service']['Contact'])) {
                $this->request->data['Contact']['Contact'] = $this->request->data['Service']['Contact'];
            }
            if (isset($this->request->data['Service']['Contactgroup'])) {
                $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Service']['Contactgroup'];
            }
            if (isset($this->request->data['Service']['Servicegroup']) &&
                is_array($this->request->data['Service']['Servicegroup'])
            ) {
                $this->request->data['Servicegroup']['Servicegroup'] = $this->request->data['Service']['Servicegroup'];
            } else {
                $this->request->data['Servicegroup']['Servicegroup'] = [];
            }
            $servicetemplate = [];
            if (isset($this->request->data['Service']['servicetemplate_id']) &&
                $this->Servicetemplate->exists($this->request->data['Service']['servicetemplate_id'])
            ) {
                $servicetemplate = $this->Servicetemplate->find('first', [
                    'contain'    => [
                        'Container',
                        'CheckPeriod',
                        'NotifyPeriod',
                        'CheckCommand',
                        'EventhandlerCommand',
                        'Customvariable',
                        'Servicetemplatecommandargumentvalue',
                        'Servicetemplateeventcommandargumentvalue',
                        'Contactgroup',
                        'Contact',
                        'Servicetemplategroup',
                        'Servicegroup'
                    ],
                    'recursive'  => -1,
                    'conditions' => [
                        'Servicetemplate.id' => $this->request->data['Service']['servicetemplate_id'],
                    ],
                ]);
            }
            $dataToSave = $this->Service->prepareForSave(
                $this->Service->diffWithTemplate($this->request->data, $servicetemplate),
                $this->request->data,
                'add'
            );

            $dataToSave['Service']['own_customvariables'] = 0;
            //Add Customvariables data to $dataToSave
            $dataToSave['Customvariable'] = [];
            if (isset($this->request->data['Customvariable'])) {
                $customVariableDiffer = new CustomVariableDiffer($this->request->data['Customvariable'], $servicetemplate['Customvariable']);
                $customVariablesToSaveRepository = $customVariableDiffer->getCustomVariablesToSaveAsRepository();
                $dataToSave['Customvariable'] = $customVariablesToSaveRepository->getAllCustomVariablesAsArray();
                if (!empty($dataToSave)) {
                    $dataToSave['Service']['own_customvariables'] = 1;
                }
            }

            $isJsonRequest = $this->request->ext === 'json';

            if (CakePlugin::loaded('MaximoModule')) {
                if (!empty($this->request->data['Maximoconfiguration'])) {
                    $dataToSave['Maximoconfiguration'] = $this->request->data['Maximoconfiguration'];
                }
            }

            if ($this->Service->saveAll($dataToSave)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Service->id,
                    OBJECT_SERVICE,
                    $ext_data_for_changelog['Host']['container_id'], // use host container_id for user permissions
                    $userId,
                    $ext_data_for_changelog['Host']['name'] . '/' . $this->request->data['Service']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($isJsonRequest) {
                    $this->serializeId();
                } else {
                    $this->setFlash(__('<a href="/services/edit/%s">Service</a> created successfully', $this->Service->id));
                    $redirect = $this->Service->redirect($this->request->params, ['action' => 'notMonitored']);
                    $this->redirect($redirect);
                }
            } else {
                if ($isJsonRequest) {
                    $this->serializeErrorMessage();
                } else {
                    $this->setFlash(__('Data could not be saved'), false);
                }
            }
        }
    }

    public function edit($id = null) {
        $userId = $this->Auth->user('id');
        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            throw new NotFoundException(__('invalid service'));
        }

        $__service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $id,
            ],
            'contain'    => [
                'Host' => [
                    'Container',
                ],

            ],
        ]);
        if (!$this->allowedByContainerId(Hash::extract($__service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();

            return;
        }
        unset($__service);

        $Customvariable = [];
        $customFieldsToRefill = [
            'Service'      => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_warning',
                'notify_on_unknown',
                'notify_on_critical',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_ok',
                'flap_detection_on_warning',
                'flap_detection_on_unknown',
                'flap_detection_on_critical',
                'priority',
                'active_checks_enabled',
                'process_performance_data',
            ],
            'Contact'      => [
                'Contact',
            ],
            'Contactgroup' => [
                'Contactgroup',
            ],
            'Servicegroup' => [
                'Servicegroup',
            ],
        ];

        if (CakePlugin::loaded('MaximoModule')) {
            $customFieldsToRefill['Maximoconfiguration'] = [
                'type',
                'impact_level',
                'urgency_level',
                'maximo_ownergroup_id',
                'maximo_service_id'
            ];
        }

        $service = $this->Service->prepareForView($id);
        $service_for_changelog = $service;
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        $userContainerId = $this->Auth->user('container_id');
        $hosts = $this->Host->find('list');
        $myContainerId = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($myContainerId, 'list', $service['Service']['service_type']);
        $timeperiods = $this->Timeperiod->find('list');
        //container_id = 1 => ROOT
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list', 'id');
        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list', 'id');
        $commands = $this->Command->serviceCommands('list');
        $eventhandlers = $this->Command->eventhandlerCommands('list');
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list', 'id');
        //Fehlende bzw. neu angelegte CommandArgummente ermitteln und anzeigen
        $commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $service['Service']['command_id'],
            ],
        ]);
        $eventhandler_commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $service['Service']['eventhandler_command_id'],
            ],
        ]);
        $contacts_for_changelog = [];
        foreach ($service['Contact'] as $contact_id) {
            if (isset($contacts[$contact_id])) {
                $contacts_for_changelog[] = [
                    'id'   => $contact_id,
                    'name' => $contacts[$contact_id],
                ];
            }
        }
        $contactgroups_for_changelog = [];
        foreach ($service['Contactgroup'] as $contactgroup_id) {
            $contactgroups_for_changelog[] = [
                'id'   => $contactgroup_id,
                'name' => $contactgroups[$contactgroup_id],
            ];
        }
        $servicegroups_for_changelog = [];
        foreach ($service['Servicegroup'] as $servicegroup_id) {
            if (isset($servicegroups[$servicegroup_id])) {
                $servicegroups_for_changelog[] = [
                    'id'   => $servicegroup_id,
                    'name' => $servicegroups[$servicegroup_id],
                ];
            }
        }
        $service_for_changelog['Contact'] = $contacts_for_changelog;
        $service_for_changelog['Contactgroup'] = $contactgroups_for_changelog;
        $service_for_changelog['Servicegroup'] = $servicegroups_for_changelog;

        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));

        $serviceContactsAndContactgroups = $this->Service->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Contact'      => [
                    'fields' => [
                        'Contact.id',
                        'Contact.name',
                    ],
                ],
                'Contactgroup' => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                        ],
                    ],
                    'fields'    => [
                        'Contactgroup.id',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $service['Service']['id'],
            ],
            'fields'     => [
                'Service.id',
            ],
        ]);
        $ContactsInherited = $this->__inheritContactsAndContactgroups($service, $serviceContactsAndContactgroups);
        $this->Frontend->setJson('ContactsInherited', $ContactsInherited);

        $this->set(compact(
            'hosts',
            'service',
            'servicetemplates',
            'servicegroups',
            'timeperiods',
            'contacts',
            'contactgroups',
            'commands',
            'eventhandlers',
            'Customvariable',
            'commandarguments',
            'ContactsInherited',
            'eventhandler_commandarguments',
            'id'
        ));

        if ($this->request->is('post') || $this->request->is('put')) {
            $ext_data_for_changelog = [
                'Contact'      => [],
                'Contactgroup' => [],
                'Servicegroup' => [],
            ];
            if ($this->request->data('Service.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Service']['Contact'],
                    ],
                ])
                ) {
                    foreach ($contactsForChangelog as $contactId => $contactName) {
                        $ext_data_for_changelog['Contact'][] = [
                            'id'   => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Service.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Service']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id'   => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Service.Servicegroup')) {
                if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Servicegroup.id',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $this->request->data['Service']['Servicegroup'],
                    ],
                ])
                ) {
                    foreach ($servicegroupsForChangelog as $servicegroupData) {
                        $ext_data_for_changelog['Servicegroup'][] = [
                            'id'   => $servicegroupData['Servicegroup']['id'],
                            'name' => $servicegroupData['Container']['name'],
                        ];
                    }
                    unset($servicegroupsForChangelog);
                }
            }
            if ($this->request->data('Service.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Service']['notify_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['NotifyPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Service.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Service']['check_period_id'],
                    ],
                ])
                ) {
                    foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                        $ext_data_for_changelog['CheckPeriod'] = [
                            'id'   => $timeperiodId,
                            'name' => $timeperiodName,
                        ];
                    }
                    unset($timeperiodsForChangelog);
                }
            }
            if ($this->request->data('Service.servicetemplate_id')) {
                if ($servicetemplatesForChangelog = $this->Servicetemplate->find('list', [
                    'conditions' => [
                        'Servicetemplate.id' => $this->request->data['Service']['servicetemplate_id'],
                    ],
                ])
                ) {
                    foreach ($servicetemplatesForChangelog as $servicetemplateId => $servicetemplateName) {
                        $ext_data_for_changelog['Servicetemplate'] = [
                            'id'   => $servicetemplateId,
                            'name' => $servicetemplateName,
                        ];
                    }
                    unset($servicetemplatesForChangelog);
                }
            }
            if ($this->request->data('Service.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Service']['command_id'],
                    ],
                ])
                ) {
                    foreach ($commandsForChangelog as $commandId => $commandName) {
                        $ext_data_for_changelog['CheckCommand'] = [
                            'id'   => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }
            if ($this->request->data('Service.host_id')) {
                if ($hostsForChangelog = $this->Host->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'id',
                        'name',
                        'container_id',
                    ],
                    'conditions' => [
                        'Host.id' => $this->request->data['Service']['host_id'],
                    ],
                ])
                ) {
                    foreach ($hostsForChangelog as $hostData) {
                        $ext_data_for_changelog['Host'] = [
                            'id'           => $hostData['id'],
                            'name'         => $hostData['name'],
                            'container_id' => $hostData['container_id'],
                        ];
                    }
                    unset($hostsForChangelog);
                }
            }

            $this->Service->set($this->request->data);
            $this->request->data['Contact']['Contact'] = $this->request->data('Service.Contact');
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data('Service.Contactgroup');
            $this->request->data['Servicegroup']['Servicegroup'] = (is_array($this->request->data['Service']['Servicegroup'])) ? $this->request->data['Service']['Servicegroup'] : [];

            $servicetemplate = [];
            if (isset($this->request->data['Service']['servicetemplate_id']) &&
                $this->Servicetemplate->exists($this->request->data['Service']['servicetemplate_id'])
            ) {
                $servicetemplate = $this->Servicetemplate->find('first', [
                    'contain'    => [
                        'Container',
                        'CheckPeriod',
                        'NotifyPeriod',
                        'CheckCommand',
                        'EventhandlerCommand',
                        'Customvariable',
                        'Servicetemplatecommandargumentvalue',
                        'Servicetemplateeventcommandargumentvalue',
                        'Contactgroup',
                        'Contact',
                        'Servicetemplategroup',
                    ],
                    'recursive'  => -1,
                    'conditions' => [
                        'Servicetemplate.id' => $this->request->data['Service']['servicetemplate_id'],
                    ],
                ]);
            }
            $data_to_save = $this->Service->prepareForSave($this->Service->diffWithTemplate($this->request->data, $servicetemplate), $this->request->data, 'edit');

            $data_to_save['Service']['own_customvariables'] = 0;
            //Add Customvariables data to $data_to_save
            $data_to_save['Customvariable'] = [];
            if (isset($this->request->data['Customvariable'])) {
                $customVariableDiffer = new CustomVariableDiffer($this->request->data['Customvariable'], $servicetemplate['Customvariable']);
                $customVariablesToSaveRepository = $customVariableDiffer->getCustomVariablesToSaveAsRepository();
                $data_to_save['Customvariable'] = $customVariablesToSaveRepository->getAllCustomVariablesAsArray();
                if (!empty($data_to_save)) {
                    $data_to_save['Service']['own_customvariables'] = 1;
                }
            }

            $this->Service->set($data_to_save);
            if ($this->Service->validates()) {
                //Delete old command argument values
                $this->Servicecommandargumentvalue->deleteAll([
                    'service_id' => $service['Service']['id'],
                ]);

                //Delete old event handler command argument values
                $this->Serviceeventcommandargumentvalue->deleteAll([
                    'service_id' => $service['Service']['id'],
                ]);

                $this->Customvariable->deleteAll([
                    'object_id'     => $service['Service']['id'],
                    'objecttype_id' => OBJECT_SERVICE,
                ], false);
            }

            if (CakePlugin::loaded('MaximoModule')) {
                if (!empty($this->request->data['Maximoconfiguration'])) {
                    $data_to_save['Maximoconfiguration'] = $this->request->data['Maximoconfiguration'];
                }
            }

            if ($this->Service->saveAll($data_to_save)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_SERVICE,
                    $ext_data_for_changelog['Host']['container_id'], // use host container_id for user permissions
                    $userId,
                    $ext_data_for_changelog['Host']['name'] . '/' . $this->request->data['Service']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $service_for_changelog
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/services/edit/%s">Service</a> modified successfully.', $this->Service->id));
                $this->loadModel('Tenant');
                $redirect = $this->Service->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);
            } else {
                $this->setFlash(__('Data could not be saved'), false);
            }
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->findById($id);
        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $service['Host']['id'],
            ],
            'contain'    => [
                'Container',
            ],
            'fields'     => [
                'Host.id',
                'Host.container_id',
                'Container.*',
            ],
        ]);
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        $modules = $this->Constants->defines['modules'];

        $usedBy = $this->Service->isUsedByModules($service, $modules);
        if (empty($usedBy)) {
            //Not used by any module
            if ($this->Service->__delete($service, $this->Auth->user('id'))) {
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


    public function copy($id = null) {
        if ($id === null && $this->request->is('get')) {
            $this->redirect([
                'controller' => 'services',
                'action'     => 'index'
            ]);
        }

        $userId = $this->Auth->user('id');
        $servicesToCopy = [];
        $servicesCantCopy = [];
        $servicetemplates = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            //Checking if target host exists
            if ($this->Host->exists($this->request->data('Service.host_id'))) {
                $host = $this->Host->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Host.id' => $this->request->data('Service.host_id')
                    ],
                    'fields'     => [
                        'Host.id',
                        'Host.name',
                        'Host.container_id'
                    ]
                ]);
                foreach ($this->request->data['Service']['source'] as $sourceServiceId) {
                    $service = $this->Service->find('first', [
                        'recursive'  => -1,
                        'fields'     => [
                            'Service.name',
                            'Service.servicetemplate_id',
                            'Service.check_period_id',
                            'Service.notify_period_id',
                            'Service.description',
                            'Service.command_id',
                            'Service.eventhandler_command_id',
                            'Service.check_interval',
                            'Service.retry_interval',
                            'Service.max_check_attempts',
                            'Service.notification_interval',
                            'Service.notifications_enabled',
                            'Service.notify_on_warning',
                            'Service.notify_on_unknown',
                            'Service.notify_on_critical',
                            'Service.notify_on_recovery',
                            'Service.notify_on_flapping',
                            'Service.notify_on_downtime',
                            'Service.flap_detection_enabled',
                            'Service.flap_detection_on_ok',
                            'Service.flap_detection_on_warning',
                            'Service.flap_detection_on_unknown',
                            'Service.flap_detection_on_critical',
                            'Service.process_performance_data',
                            'Service.freshness_checks_enabled',
                            'Service.freshness_threshold',
                            'Service.notes',
                            'Service.priority',
                            'Service.tags',
                            'Service.service_url',
                            'Service.is_volatile',
                            'Service.service_type',
                            'Service.own_contacts',
                            'Service.own_contactgroups',
                            'Service.own_customvariables',
                            'Service.disabled'
                        ],
                        'contain'    => [
                            'CheckPeriod'                      => [
                                'fields' => [
                                    'CheckPeriod.id',
                                    'CheckPeriod.name'
                                ]
                            ],
                            'NotifyPeriod'                     => [
                                'fields' => [
                                    'NotifyPeriod.id',
                                    'NotifyPeriod.name'
                                ]
                            ],
                            'CheckCommand'                     => [
                                'fields' => [
                                    'CheckCommand.id',
                                    'CheckCommand.name',
                                ]
                            ],
                            'Contact'                          => [
                                'fields' => [
                                    'Contact.id',
                                    'Contact.name'
                                ],
                            ],
                            'Contactgroup'                     => [
                                'fields'    => [
                                    'Contactgroup.id',
                                ],
                                'Container' => [
                                    'fields' => [
                                        'Container.name'
                                    ]
                                ]
                            ],
                            'Servicecommandargumentvalue'      => [
                                'fields' => [
                                    'commandargument_id', 'value',
                                ],
                            ],
                            'Serviceeventcommandargumentvalue' => [
                                'fields' => [
                                    'commandargument_id', 'value',
                                ],
                            ],
                            'Customvariable'                   => [
                                'fields' => [
                                    'name',
                                    'value',
                                    'objecttype_id'
                                ],
                            ],
                            'Servicegroup'                     => [
                                'fields'    => [
                                    'Servicegroup.id',
                                ],
                                'Container' => [
                                    'fields' => [
                                        'Container.name'
                                    ]
                                ]
                            ],
                        ],
                        'conditions' => [
                            'Service.id'           => $sourceServiceId,
                            'Service.service_type' => $this->Service->serviceTypes('copy')
                        ],
                    ]);

                    if (isset($servicetemplates[$service['Service']['servicetemplate_id']])) {
                        $servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
                    } else {
                        $servicetemplates[$service['Service']['servicetemplate_id']] = $this->Servicetemplate->find('first', [
                                'recursive'  => -1,
                                'fields'     => [
                                    'Servicetemplate.template_name',
                                    'Servicetemplate.name',
                                    'Servicetemplate.check_period_id',
                                    'Servicetemplate.notify_period_id',
                                    'Servicetemplate.description',
                                    'Servicetemplate.command_id',
                                    'Servicetemplate.eventhandler_command_id',
                                    'Servicetemplate.check_interval',
                                    'Servicetemplate.retry_interval',
                                    'Servicetemplate.max_check_attempts',
                                    'Servicetemplate.notification_interval',
                                    'Servicetemplate.notifications_enabled',
                                    'Servicetemplate.notify_on_warning',
                                    'Servicetemplate.notify_on_unknown',
                                    'Servicetemplate.notify_on_critical',
                                    'Servicetemplate.notify_on_recovery',
                                    'Servicetemplate.notify_on_flapping',
                                    'Servicetemplate.notify_on_downtime',
                                    'Servicetemplate.flap_detection_enabled',
                                    'Servicetemplate.flap_detection_on_ok',
                                    'Servicetemplate.flap_detection_on_warning',
                                    'Servicetemplate.flap_detection_on_unknown',
                                    'Servicetemplate.flap_detection_on_critical',
                                    'Servicetemplate.process_performance_data',
                                    'Servicetemplate.freshness_checks_enabled',
                                    'Servicetemplate.freshness_threshold',
                                    'Servicetemplate.notes',
                                    'Servicetemplate.priority',
                                    'Servicetemplate.tags',
                                    'Servicetemplate.service_url',
                                    'Servicetemplate.is_volatile',
                                    'Servicetemplate.check_freshness',
                                ],
                                'contain'    => [
                                    'CheckPeriod'                              => [
                                        'fields' => [
                                            'CheckPeriod.id',
                                            'CheckPeriod.name'
                                        ]
                                    ],
                                    'NotifyPeriod'                             => [
                                        'fields' => [
                                            'NotifyPeriod.id',
                                            'NotifyPeriod.name'
                                        ]
                                    ],
                                    'CheckCommand'                             => [
                                        'fields' => [
                                            'CheckCommand.id',
                                            'CheckCommand.name',
                                        ]
                                    ],
                                    'Contact'                                  => [
                                        'fields' => [
                                            'Contact.id',
                                            'Contact.name'
                                        ],
                                    ],
                                    'Contactgroup'                             => [
                                        'fields'    => [
                                            'Contactgroup.id',
                                        ],
                                        'Container' => [
                                            'fields' => [
                                                'Container.name'
                                            ]
                                        ]
                                    ],
                                    'Servicegroup'                             => [
                                        'fields'    => [
                                            'Servicegroup.id',
                                        ],
                                        'Container' => [
                                            'fields' => [
                                                'Container.name'
                                            ]
                                        ]
                                    ],
                                    'Servicetemplatecommandargumentvalue'      => [
                                        'fields' => [
                                            'id',
                                            'commandargument_id',
                                            'value',
                                        ],
                                    ],
                                    'Servicetemplateeventcommandargumentvalue' => [
                                        'fields' => [
                                            'id',
                                            'commandargument_id',
                                            'value',
                                        ],
                                    ],
                                    'Customvariable'                           => [
                                        'fields' => [
                                            'name', 'value',
                                        ],
                                    ],
                                ],
                                'conditions' => [
                                    'Servicetemplate.id' => $service['Service']['servicetemplate_id']
                                ]
                            ]
                        );
                        $servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
                    }
                    $service = Hash::remove($service, 'Service.id');
                    $service = Hash::remove($service, '{s}.{n}.{s}.service_id');
                    $contactIds = (!empty($service['Contact'])) ? Hash::extract($service['Contact'], '{n}.id') : [];
                    $contactgroupIds = (!empty($service['Contactgroup'])) ? Hash::extract($service['Contactgroup'], '{n}.id') : [];
                    $servicegroupIds = (!empty($service['Servicegroup'])) ? Hash::extract($service['Servicegroup'], '{n}.id') : [];
                    $customVariables = (!empty($service['Customvariable'])) ? Hash::remove($service['Customvariable'], '{n}.object_id') : [];
                    $newServiceData = [
                        'Service'      => Hash::merge(
                            $service['Service'], [
                            'uuid'           => UUID::v4(),
                            'host_id'        => $host['Host']['id'],
                            'Contact'        => $contactIds,
                            'Contactgroup'   => $contactgroupIds,
                            'Servicegroup'   => $servicegroupIds,
                            'Customvariable' => $customVariables,
                        ]),
                        'Contact'      => ['Contact' => $contactIds],
                        'Contactgroup' => ['Contactgroup' => $contactgroupIds],
                        'Servicegroup' => ['Servicegroup' => $servicegroupIds],

                        'Customvariable'                   => $customVariables,
                        'Servicecommandargumentvalue'      => (!empty($service['Servicecommandargumentvalue'])) ? Hash::remove($service['Servicecommandargumentvalue'], '{n}.service_id') : [],
                        'Serviceeventcommandargumentvalue' => (!empty($service['Serviceeventcommandargumentvalue'])) ? Hash::remove($service['Serviceeventcommandargumentvalue'], '{n}.service_id') : [],
                    ];

                    /* Data for Changelog Start*/
                    $service['Host'] = ['id' => $host['Host']['id'], 'name' => $host['Host']['name']];
                    if (!empty($service['Contactgroup'])) {
                        $contactgroups = [];
                        foreach ($service['Contactgroup'] as $contactgroup) {
                            $contactgroups[] = [
                                'id'   => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $service['Contactgroup'] = $contactgroups;
                    } else if (empty($service['Contactgroup']) && !empty($servicetemplate['Contactgroup'])) {
                        $contactgroups = [];
                        foreach ($servicetemplate['Contactgroup'] as $contactgroup) {
                            $contactgroups[] = [
                                'id'   => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $servicetemplate['Contactgroup'] = $contactgroups;
                    }

                    if (!empty($service['Servicegroup'])) {
                        $servicegroups = [];
                        foreach ($service['Servicegroup'] as $servicegroup) {
                            $servicegroups[] = [
                                'id'   => $servicegroup['id'],
                                'name' => $servicegroup['Container']['name']
                            ];
                        }
                        $service['Servicegroup'] = $servicegroups;
                    } else if (empty($service['Servicegroup']) && !empty($servicetemplate['Servicegroup'])) {
                        $servicegroups = [];
                        foreach ($servicetemplate['Servicegroup'] as $servicegroup) {
                            $servicegroups[] = [
                                'id'   => $servicegroup['id'],
                                'name' => $servicegroup['Container']['name']
                            ];
                        }
                        $servicetemplate['Servicegroup'] = $servicegroups;
                    }
                    /* Data for Changelog End*/
                    $this->Service->create();
                    if ($this->Service->saveAll($newServiceData)) {
                        $serviceDataAfterSave = $this->Service->dataForChangelogCopy($service, $servicetemplate);
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $this->params['action'],
                            $this->params['controller'],
                            $this->Service->id,
                            OBJECT_SERVICE,
                            $host['Host']['container_id'],
                            $userId,
                            $host['Host']['name'] . '/' . $serviceDataAfterSave['Service']['name'],
                            $serviceDataAfterSave
                        );
                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }
                    }
                }
                $this->setFlash(__('Copied successfully'));
                $this->redirect(['action' => 'serviceList', $host['Host']['id']]);
            } else {
                $this->setFlash(__('Target host does not exist'), false);
            }
        }

        $sourceHost = null;
        foreach (func_get_args() as $service_id) {
            if ($this->Service->exists($service_id)) {
                $service = $this->Service->findById($service_id);
                if ($sourceHost === null) {
                    $sourceHost['Host'] = $service['Host'];
                }
                if (in_array($service['Service']['service_type'], $this->Service->serviceTypes('copy'))) {
                    $servicesToCopy[] = $service;
                } else {
                    if ($service['Service']['name'] == null || $service['Service']['name'] == '') {
                        $servicesCantCopy[] = $service['Servicetemplate']['name'];
                    } else {
                        $servicesCantCopy[] = $service['Service']['name'];
                    }
                }
            }

            //Find hosts to copy on this host.
            if (!empty($servicesToCopy)) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
                $hosts = $this->Host->hostsByContainerId($containerIds, 'list', ['Host.host_type' => GENERIC_HOST]);
            }
        }

        $this->set(compact(['hosts', 'servicesToCopy', 'servicesCantCopy', 'sourceHost']));
        $this->set('back_url', $this->referer());
    }

    public function deactivate($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $this->Service->id = $id;
        if ($this->Service->saveField('disabled', 1)) {
            $this->set('success', true);
            $this->set('message', __('Service successfully disabled'));
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while disabling service'));
        $this->set('_serialize', ['success', 'id', 'message']);
    }

    public function enable($id = null) {
        if (!$this->request->is('post')) {
            //throw new MethodNotAllowedException();
        }

        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $id
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.disabled'
                    ]
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.host_id',
            ]
        ]);

        if ($service['Host']['disabled'] == 1) {
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Could not enable service, because associated host is also disabled.'));
            $this->set('_serialize', ['success', 'id', 'message']);
            return;
        }

        $this->Service->id = $id;
        if ($this->Service->saveField('disabled', 0)) {
            $this->set('success', true);
            $this->set('message', __('Service successfully enabled'));
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while enabling service'));
        $this->set('_serialize', ['success', 'id', 'message']);
    }

    public function loadContactsAndContactgroups($container_id = null) {
        $this->allowOnlyAjaxRequests();

        $result = [
            'contacts'      => [
                'contacts' => [],
                'sizeof'   => 0,
            ],
            'contactgroups' => [
                'contactgroups' => [],
                'sizeof'        => 0,
            ],
        ];
        //container_id = 1 => ROOT
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);
        $result['contacts']['contacts'] = $this->Contact->contactsByContainerId($containerIds, 'list');
        $result['contacts']['sizeof'] = sizeof($result['contacts']['contacts']);
        //container_id = 1 => ROOT
        $result['contactgroups']['contactgroups'] = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $result['contactgroups']['sizeof'] = sizeof($result['contactgroups']['contactgroups']);

        $this->set(compact(['result']));
        $this->set('_serialize', ['result']);

    }

    public function loadParametersByCommandId($command_id = null, $servicetemplate_id = null) {
        $this->allowOnlyAjaxRequests();

        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
            foreach ($commandarguments as $key => $commandargument) {
                if ($servicetemplate_id) {
                    $servicemteplate_command_argument_value = $this->Servicetemplatecommandargumentvalue->find('first', [
                        'conditions' => [
                            'Servicetemplatecommandargumentvalue.servicetemplate_id' => $servicetemplate_id,
                            'Servicetemplatecommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id'],
                        ],
                        'fields'     => [
                            'Servicetemplatecommandargumentvalue.value',
                            'Servicetemplatecommandargumentvalue.id',
                        ],
                    ]);
                    if (isset($servicemteplate_command_argument_value['Servicetemplatecommandargumentvalue']['value'])) {
                        $commandarguments[$key]['Servicetemplatecommandargumentvalue']['value'] =
                            $servicemteplate_command_argument_value['Servicetemplatecommandargumentvalue']['value'];
                    }
                    if (isset($servicemteplate_command_argument_value['Servicetemplatecommandargumentvalue']['id'])) {
                        $commandarguments[$key]['Servicetemplatecommandargumentvalue']['id'] =
                            $servicemteplate_command_argument_value['Servicetemplatecommandargumentvalue']['id'];
                    }
                }
            }
        }

        $this->set(compact('commandarguments'));
    }

    public function loadNagParametersByCommandId($command_id = null, $servicetemplate_id = null) {
        $this->allowOnlyAjaxRequests();

        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
            foreach ($commandarguments as $key => $commandargument) {
                if ($servicetemplate_id) {
                    $servicemteplate_command_argument_value = $this->Servicetemplateeventcommandargumentvalue->find('first', [
                        'conditions' => [
                            'Servicetemplateeventcommandargumentvalue.servicetemplate_id' => $servicetemplate_id,
                            'Servicetemplateeventcommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id'],
                        ],
                        'fields'     => [
                            'Servicetemplateeventcommandargumentvalue.value',
                            'Servicetemplateeventcommandargumentvalue.id',
                        ],
                    ]);
                    if (isset($servicemteplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['value'])) {
                        $commandarguments[$key]['Servicetemplateeventcommandargumentvalue']['value'] =
                            $servicemteplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['value'];
                    }
                    if (isset($servicemteplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['id'])) {
                        $commandarguments[$key]['Servicetemplateeventcommandargumentvalue']['id'] =
                            $servicemteplate_command_argument_value['Servicetemplateeventcommandargumentvalue']['id'];
                    }
                }
            }
        }

        $this->set(compact('commandarguments'));
    }

    public function loadArgumentsAdd($command_id = null) {
        $this->allowOnlyAjaxRequests();
        $this->loadModel('Commandargument');

        $commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $command_id,
            ],
        ]);

        $this->set('commandarguments', $commandarguments);
        $this->render('load_arguments');
    }

    public function loadServicetemplatesArguments($servicetemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Servicetemplate');
        if (!$this->Servicetemplate->exists($servicetemplate_id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        $this->loadModel('Commandargument');
        $this->loadModel('Servicetemplatecommandargumentvalue');
        $commandarguments = $this->Servicetemplatecommandargumentvalue->find('all', [
            'conditions' => [
                'servicetemplate_id' => $servicetemplate_id,
            ],
        ]);

        // Renaming Servicetemplatecommandargumentvalue to Servicecommandargumentvalue that we can render the view load_arguments with values
        $_commandarguments = [];
        foreach ($commandarguments as $commandargument) {
            $c = [];
            // Remove id of command argument value that if the user change them we dont overwrite the orginal data form host template in the database
            unset($commandargument['Servicetemplatecommandargumentvalue']['id']);
            $c['Servicecommandargumentvalue'] = $commandargument['Servicetemplatecommandargumentvalue'];
            $c['Commandargument'] = $commandargument['Commandargument'];
            $_commandarguments[] = $c;
        }
        $this->set('commandarguments', $_commandarguments);
        $this->render('load_arguments');
    }

    public function loadTemplateData($servicetemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Servicetemplate');
        $servicetemplateData = $this->Servicetemplate->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Servicetemplate.id' => $servicetemplate_id,
            ],
            'contain'    => [
                'Contactgroup' => [
                    'Container' => ['fields' => 'name'],
                ],
                'Servicegroup' => [
                    'Container' => ['fields' => 'name'],
                ],
            ],
        ]);
        $servicetemplate = Hash::merge($this->Servicetemplate->findById($servicetemplate_id), $servicetemplateData);

        $this->set(compact(['servicetemplate']));
        $this->set('_serialize', ['servicetemplate']);
    }

    public function addCustomMacro($counter) {
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_SERVICE);
        $this->set('counter', $counter);
    }

    public function loadServices($host_id) {
        /* $this->allowOnlyAjaxRequests(); */

        $this->loadModel('Host');
        $services = $this->Service->find('all');
        $this->set(compact(['services']));
        $this->set('_serialize', ['services']);
    }

    public function loadTemplateMacros($servicetemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Servicetemplate');
        if (!$this->Servicetemplate->exists($servicetemplate_id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        if ($this->Servicetemplate->exists($servicetemplate_id)) {
            $servicetemplate = $this->Servicetemplate->find('first', [
                'conditions' => [
                    'Servicetemplate.id' => $servicetemplate_id,
                ],
                'recursive'  => -1,
                'contain'    => [
                    'Customvariable' => [
                        'fields' => [
                            'Customvariable.name',
                            'Customvariable.value',
                            'Customvariable.objecttype_id',
                        ],
                    ],
                ],
                'fields'     => [
                    'Servicetemplate.id',
                ],
            ]);
        }
        $this->set('servicetemplate', $servicetemplate);
    }


    public function browser($idOrUuid = null) {
        $this->layout = 'angularjs';

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

        $rawHost = $this->Host->find('first', $this->Host->getQueryForServiceBrowser($rawService['Service']['host_id']));

        $PerfdataChecker = new PerfdataChecker(
            new \itnovum\openITCOCKPIT\Core\Views\Host($rawHost),
            new \itnovum\openITCOCKPIT\Core\Views\Service($rawService)
        );

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

            $this->set('username', $User->getFullName());
            $this->set('host', $rawHost);
            $this->set('service', $rawService);
            $this->set('allowEdit', $allowEdit);
            $this->set('docuExists', $this->Documentation->existsForUuid($rawService['Service']['uuid']));
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
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
        $mergedService['Service']['has_graph'] = $PerfdataChecker->hasRrdFile();
        $mergedService['checkIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['check_interval']);
        $mergedService['retryIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['retry_interval']);
        $mergedService['notificationIntervalHuman'] = $UserTime->secondsInHumanShort($mergedService['Service']['notification_interval']);


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


        $hoststatus = $this->Hoststatus->byUuid($rawHost['Host']['uuid'], $HoststatusFields);
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

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        if (empty($servicestatus)) {
            //Empty host state for Servicestatus object
            $servicestatus = [
                'Servicestatus' => []
            ];
        }
        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus'], $UserTime);
        $servicestatus = $Servicestatus->toArrayForBrowser();
        $servicestatus['longOutputHtml'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($Servicestatus->getLongOutput(), true));


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


        $canSubmitExternalCommands = $this->hasPermission('externalcommands', 'hosts');

        $this->set('mergedService', $mergedService);
        $this->set('host', $rawHost);
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
            'contacts',
            'contactgroups',
            'acknowledgement',
            'hostAcknowledgement',
            'downtime',
            'hostDowntime',
            'canSubmitExternalCommands'
        ]);

        /*
        // Wos is desch?
        $serviceAllValues = $this->Rrd->getPerfDataFiles($service['Host']['uuid'], $service['Service']['uuid']);
        $serviceValues = [];
        if (isset($serviceAllValues['xml_data']) && !empty($serviceAllValues['xml_data'])) {
            foreach ($serviceAllValues['xml_data'] as $serviceValueArr) {
                $serviceValues[$serviceValueArr['ds']] = $service['Host']['name'] . '/' . $service['Service']['name'] . '/' . $serviceValueArr['name'];
            }
        }
        */
    }

    /*
	* Compare two arrays with each other
	* @host Array
	* @hosttemplate Array
	* @return $diff_array
	*
	* *************** Contact and contactgroups check ************
	debug(Set::classicExtract($host, '{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}'))); 	//Host
	debug(Set::classicExtract($hosttemplate, '{(Contact|Contactgroup)}.{n}.id'));					//Hosttemplate
	array(
		'Contact' => array(
			'Contact' => array(
				(int) 0 => '26'
			)
		),
		'Contactgroup' => array(
			'Contactgroup' => array(
				(int) 0 => '131',
				(int) 1 => '132'
			)
		)
	)
	*************** Single fields in hosttemplate and host *************
	debug(Set::classicExtract($host, 'Host.{('.implode('|', array_values(Hash::merge($fields,['name', 'description', 'address']))).')}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Hosttemplate.{('.implode('|', array_values($fields)).')}')));	//Hosttemplate

	**************** Command arguments check *************
	debug(Set::classicExtract($host, 'Hostcommandargumentvalue.{n}.{(commandargument_id|value)}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Hosttemplatecommandargumentvalue.{n}.{(commandargument_id|value)}')));	//Hostemplate

	**************** Custom variables check *************
	debug(Set::classicExtract($host, 'Customvariable.{n}.{(name|value)}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Customvariable.{n}.{(name|value)}'));	//Hosttemplate
	*/

    public function servicesByHostId($host_id = null) {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        $services = $this->Service->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => ['Servicetemplate.name'],
                ],
                'Host'            => [
                    'fields' => ['Host.name', 'Host.uuid'],
                ],
            ],
            'fields'     => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceDescription',
            ],
            'order'      => [
                'Service.name ASC', 'Servicetemplate.name ASC',
            ],
            'conditions' => [
                'Host.id' => $host_id,
            ],
        ]);

        $this->set('services', $services);
        $this->render('load_services');
    }

    public function serviceList($host_id) {
        $this->layout = 'angularjs';
        $User = new User($this->Auth);

        if (!$this->Host->exists($host_id)) {
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
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            //Only ship HTML template
            return;
        }
    }

    public function grapherSwitch($id) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $id
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields'       => [
                        'Servicetemplate.id',
                        'Servicetemplate.command_id'
                    ],
                    'CheckCommand' => [
                        'fields' => [
                            'CheckCommand.id',
                            'CheckCommand.uuid'
                        ]
                    ]
                ],
                'CheckCommand'    => [
                    'fields' => [
                        'CheckCommand.id',
                        'CheckCommand.uuid'
                    ]
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.command_id'
            ]
        ]);

        $commandUuid = $service['CheckCommand']['uuid'];
        if ($commandUuid === null || $commandUuid === '') {
            $commandUuid = $service['Servicetemplate']['CheckCommand']['uuid'];
        }

        if (file_exists(APP . 'GrapherTemplates' . DS . $commandUuid . '.php')) {
            return $this->redirect('/services/grapherTemplate/' . $service['Service']['id']);
        }

        return $this->redirect('/services/grapher/' . $service['Service']['id']);
    }

    public function grapher($id) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $id

            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address',
                        'Host.container_id'
                    ],
                    'Container'
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Service.service_type',
                'Service.service_url'
            ]
        ]);


        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'), false)) {
            $this->render403();
            return;
        }

        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        $services = $this->Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Service.host_id'  => $service['Host']['id'],
                'Service.disabled' => 0
            ],
            'contain'    => [
                'Servicetemplate'
            ],
            'fields'     => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
            ]
        ]);


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->wildcard();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);
        $showThresholds = is_null($this->Session->read('service_thresholds_' . $id)) ? '1' : $this->Session->read('service_thresholds_' . $id);
        $this->set(compact(['service', 'servicestatus', 'allowEdit', 'services', 'docuExists', 'showThresholds']));
    }

    public function grapherTemplate($id) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $id
            ],
            'contain'    => [
                'Servicetemplate' => [
                    'fields'       => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                        'Servicetemplate.command_id'
                    ],
                    'CheckCommand' => [
                        'fields' => [
                            'CheckCommand.id',
                            'CheckCommand.uuid'
                        ]
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.address',
                        'Host.container_id'
                    ],
                    'Container'
                ],
                'CheckCommand'    => [
                    'fields' => [
                        'CheckCommand.id',
                        'CheckCommand.uuid'
                    ]
                ]
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.host_id',
                'Service.service_type',
                'Service.service_url',
                'Service.command_id'
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'), false)) {
            $this->render403();
            return;
        }

        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        $services = $this->Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Service.host_id'  => $service['Host']['id'],
                'Service.disabled' => 0
            ],
            'contain'    => [
                'Servicetemplate'
            ],
            'fields'     => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
            ]
        ]);

        $commandUuid = $service['CheckCommand']['uuid'];
        if ($commandUuid === null || $commandUuid === '') {
            $commandUuid = $service['Servicetemplate']['CheckCommand']['uuid'];
        }

        $ServicestatusField = new ServicestatusFields($this->DbBackend);
        $ServicestatusField->wildcard();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusField);
        $this->set(compact(['service', 'servicestatus', 'allowEdit', 'services', 'docuExists', 'commandUuid']));

    }

    public function grapherZoom($id, $ds, $newStart, $newEnd, $showThresholds) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $this->Session->write('service_thresholds_' . $id, $showThresholds);

        //Avoid RRD errors
        if ($newStart > $newEnd) {
            $_newEnd = $newEnd;
            $newEnd = $newStart;
            $newStart = $_newEnd;
        }

        $service = $this->Service->findById($id);
        $this->set(compact(['service', 'ds', 'newStart', 'newEnd']));
        $this->layout = false;
        $this->render = false;
        header('Content-Type: image/png');


        $rrd_path = Configure::read('rrd.path');

        $File = new File($rrd_path . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.xml', false);
        if (!$File->exists()) {
            $errorImage = $this->createGrapherErrorPng('No such file or directory');
            imagepng($errorImage);
            imagedestroy($errorImage);
            return;
        }

        $rrd_structure_datasources = $this->Rrd->getPerfDataStructure($rrd_path . $service['Host']['uuid'] . DS . $service['Service']['uuid'] . '.xml');
        foreach ($rrd_structure_datasources as $rrd_structure_datasource):
            if ($rrd_structure_datasource['ds'] == $ds):
                if ($showThresholds !== '1') {
                    unset($rrd_structure_datasource['crit']);
                    unset($rrd_structure_datasource['warn']);
                }

                $imageUrl = $this->Rrd->createRrdGraph($rrd_structure_datasource, [
                    'host_uuid'    => $service['Host']['uuid'],
                    'service_uuid' => $service['Service']['uuid'],
                    'path'         => $rrd_path,
                    'start'        => $newStart,
                    'end'          => $newEnd,
                    'label'        => $service['Host']['name'] . ' / ' . $service['Servicetemplate']['name'],
                ], [], true);
                if (!isset($imageUrl['diskPath'])) {
                    //The image is broken, i gues we have an RRD error here, so we render the RRD return text into an image and send it to the browser.
                    $errorImage = $this->createGrapherErrorPng($imageUrl);
                    imagepng($errorImage);
                    imagedestroy($errorImage);

                    return;
                }

                $image = imagecreatefrompng($imageUrl['diskPath']);

                imagepng($image);
                imagedestroy($image);
            endif;
        endforeach;
    }

    public function grapherZoomTemplate($id, $ds, $newStart, $newEnd, $commandUuid) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        //Avoid RRD errors
        if ($newStart > $newEnd) {
            $_newEnd = $newEnd;
            $newEnd = $newStart;
            $newStart = $_newEnd;
        }

        $service = $this->Service->findById($id);
        $this->set(compact(['service', 'ds', 'newStart', 'newEnd']));
        $this->layout = false;
        $this->render = false;
        header('Content-Type: image/png');
        $rrd_path = Configure::read('rrd.path');

        //Loading template
        $templateSettings = [];
        require_once APP . 'GrapherTemplates' . DS . $commandUuid . '.php';

        foreach ($templateSettings as $key => $templateSetting):
            if ($key == $ds):
                $rrdOptions = [
                    '--slope-mode',
                    '--start', $newStart,
                    '--end', $newEnd,
                    '--width', 850,
                    '--color', 'BACK#FFFFFF',
                    '--border', 1,
                    '--imgformat', 'PNG',
                ];

                //Merging template settings to our default settings
                $rrdOptions = Hash::merge($rrdOptions, $templateSetting);

                $imageUrl = $this->Rrd->createRrdGraphFromTemplate($rrdOptions);

                if (!isset($imageUrl['diskPath'])) {
                    //The image is broken, i gues we have an RRD error here, so we render the RRD return text into an image and send it to the browser.
                    $errorImage = $this->createGrapherErrorPng($imageUrl);
                    imagepng($errorImage);
                    imagedestroy($errorImage);

                    return;
                }

                $image = imagecreatefrompng($imageUrl['diskPath']);

                imagepng($image);
                imagedestroy($image);
            endif;
        endforeach;
    }

    public function createGrapherErrorPng($error) {
        $img = imagecreatetruecolor(947, 173);
        imagesavealpha($img, true);
        $background = imagecolorallocatealpha($img, 255, 110, 110, 0);
        $textColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $background);

        imagestring($img, 5, 5, 5, 'Error:', $textColor);
        imagestring($img, 5, 5, 25, $error, $textColor);

        return $img;
    }

    /**
     * Converts BB code to HTML
     *
     * @param string $uuid The services UUID you want to get the long output
     * @param bool $parseBbcode If you want to convert BB Code to HTML
     * @param bool $nl2br If you want to replace \n with <br>
     *
     * @return string
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

    public function getSelectedServices($ids) {
        $servicestatus = $this->Service->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.name',
                'Service.host_id',
                'Servicetemplate.name',
                'Servicestatus.current_state',
                'Servicestatus.is_flapping',
                'Servicestatus.next_check',
                'Servicestatus.last_check',
                'Servicestatus.last_state_change',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.no_downtime_depth',
                'Servicestatus.output',
            ],
            'conditions' => [
                'Service.id' => $ids,
            ],
            'joins'      => [
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                ],
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.name2 = Service.uuid',
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'INNER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = Objects.object_id',
                ],
            ],
        ]);

        $hostIds = Hash::extract($servicestatus, '{n}.Service.host_id');
        $hostIds = array_unique($hostIds);

        $hosts = $this->Objects->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Host.disabled'     => 0,
                'Host.container_id' => $this->MY_RIGHTS,
                'Host.id'           => $hostIds,
            ],
            'fields'     => [
                'Host.id',
                'Host.name',
                'Host.address',
                'Hoststatus.current_state',
                'Hoststatus.is_flapping',
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Objects.name1 = Host.uuid AND Objects.objecttype_id = 1',
                ],
                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ],
            ],
            'order'      => [
                'Host.name',
            ],
        ]);
        $hosts = Hash::combine($hosts, '{n}.Host.id', '{n}');
        $servicestatus = Hash::combine($servicestatus, '{n}.Service.id', '{n}', '{n}.Service.host_id');

        $result = [];
        $serviceCount = 0;
        foreach ($hosts as $currentHostId => $host) {
            $hosts[$currentHostId]['ServiceData'] = $servicestatus[$currentHostId];
            $result[$currentHostId] = $hosts[$currentHostId];
            $serviceCount += sizeof($servicestatus[$currentHostId]);
        }
        $ret = [
            'list'  => $result,
            'count' => $serviceCount,
        ];

        return $ret;
    }

    public function listToPdf() {
        $ServiceFilter = new ServiceFilter($this->request);
        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions();
        if ($ServiceControllerRequest->isRequestFromBrowser() === false) {
            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        }

        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Host.name'           => 'asc',
            'Service.servicename' => 'asc'
        ]));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceIndexQuery($ServiceConditions, $ServiceFilter->indexFilter());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->Servicestatus->virtualFieldsForIndexAndServiceList();
            $query = $this->Servicestatus->getServiceIndexQuery($ServiceConditions, $ServiceFilter->indexFilter());
            $modelName = 'Servicestatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Service->getServiceIndexQueryStatusengine3($ServiceConditions, $ServiceFilter->indexFilter());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }


        $query = array_merge($this->Paginator->settings, $query);
        if (isset($query['limit'])) {
            unset($query['limit']);
        }
        $all_services = $this->{$modelName}->find('all', $query);


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
     * $service is from prepareForView() but ther are no names in the service contact, only ids
     * $_service is from $this->Service->findById, because of contact names
     */
    protected function __inheritContactsAndContactgroups($service, $serviceContactsAndContactgroups) {
        if (empty($serviceContactsAndContactgroups['Contact']) && empty($serviceContactsAndContactgroups['Contactgroup'])) {

            //Check servicetemplate for contacts
            if (!empty($service['Servicetemplate']['Contact']) || !empty($service['Servicetemplate']['Contactgroup'])) {
                return [
                    'inherit'      => true,
                    'source'       => 'Servicetemplate',
                    'Contact'      => Hash::combine($service['Servicetemplate']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Servicetemplate']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }

            //Check host for contacts
            //debug($service['Host']);
            if (!empty($service['Host']['Contact']) || !empty($service['Host']['Contactgroup'])) {
                return [
                    'inherit'      => true,
                    'source'       => 'Host',
                    'Contact'      => Hash::combine($service['Host']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Host']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }

            //Check hosttemplate for contacts
            if (!empty($service['Host']['Hosttemplate']['Contact']) || !empty($service['Host']['Hosttemplate']['Contactgroup'])) {
                return [
                    'inherit'      => true,
                    'source'       => 'Hosttemplate',
                    'Contact'      => Hash::combine($service['Host']['Hosttemplate']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Host']['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }
        }

        return [
            'inherit'      => false,
            'source'       => 'Service',
            'Contact'      => Hash::combine($serviceContactsAndContactgroups['Contact'], '{n}.id', '{n}.name'),
            'Contactgroup' => Hash::combine($serviceContactsAndContactgroups['Contactgroup'], '{n}.id', '{n}.Container.name'),
        ];
    }

    /**
     * @return array
     */
    protected function getChangelogDataForAdd() {
        $changelogData = [];
        if ($this->request->data('Service.Contact')) {
            if ($contactsForChangelog = $this->Contact->find('list', [
                'conditions' => [
                    'Contact.id' => $this->request->data['Service']['Contact'],
                ],
            ])
            ) {
                foreach ($contactsForChangelog as $contactId => $contactName) {
                    $changelogData['Contact'][] = [
                        'id'   => $contactId,
                        'name' => $contactName,
                    ];
                }
                unset($contactsForChangelog);
            }
        }
        if ($this->request->data('Service.Contactgroup')) {
            if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                'recursive'  => -1,
                'contain'    => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                        ],
                    ],
                ],
                'fields'     => [
                    'Contactgroup.id',
                ],
                'conditions' => [
                    'Contactgroup.id' => $this->request->data['Service']['Contactgroup'],
                ],
            ])
            ) {
                foreach ($contactgroupsForChangelog as $contactgroupData) {
                    $changelogData['Contactgroup'][] = [
                        'id'   => $contactgroupData['Contactgroup']['id'],
                        'name' => $contactgroupData['Container']['name'],
                    ];
                }
                unset($contactgroupsForChangelog);
            }
        }
        if ($this->request->data('Service.Servicegroup')) {
            if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                'recursive'  => -1,
                'contain'    => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                        ],
                    ],
                ],
                'fields'     => [
                    'Servicegroup.id',
                ],
                'conditions' => [
                    'Servicegroup.id' => $this->request->data['Service']['Servicegroup'],
                ],
            ])
            ) {
                foreach ($servicegroupsForChangelog as $servicegroupData) {
                    $changelogData['Servicegroup'][] = [
                        'id'   => $servicegroupData['Servicegroup']['id'],
                        'name' => $servicegroupData['Container']['name'],
                    ];
                }
                unset($servicegroupsForChangelog);
            }
        }
        if ($this->request->data('Service.notify_period_id')) {
            if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                'conditions' => [
                    'Timeperiod.id' => $this->request->data['Service']['notify_period_id'],
                ],
            ])
            ) {
                foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                    $changelogData['NotifyPeriod'] = [
                        'id'   => $timeperiodId,
                        'name' => $timeperiodName,
                    ];
                }
                unset($timeperiodsForChangelog);
            }
        }
        if ($this->request->data('Service.check_period_id')) {
            if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                'conditions' => [
                    'Timeperiod.id' => $this->request->data['Service']['check_period_id'],
                ],
            ])
            ) {
                foreach ($timeperiodsForChangelog as $timeperiodId => $timeperiodName) {
                    $changelogData['CheckPeriod'] = [
                        'id'   => $timeperiodId,
                        'name' => $timeperiodName,
                    ];
                }
                unset($timeperiodsForChangelog);
            }
        }
        if ($this->request->data('Service.servicetemplate_id')) {
            if ($servicetemplatesForChangelog = $this->Servicetemplate->find('list', [
                'conditions' => [
                    'Servicetemplate.id' => $this->request->data['Service']['servicetemplate_id'],
                ],
            ])
            ) {
                foreach ($servicetemplatesForChangelog as $servicetemplateId => $servicetemplateName) {
                    $changelogData['Servicetemplate'] = [
                        'id'   => $servicetemplateId,
                        'name' => $servicetemplateName,
                    ];
                }
                unset($servicetemplatesForChangelog);
            }
        }
        if ($this->request->data('Service.command_id')) {
            if ($commandsForChangelog = $this->Command->find('list', [
                'conditions' => [
                    'Command.id' => $this->request->data['Service']['command_id'],
                ],
            ])
            ) {
                foreach ($commandsForChangelog as $commandId => $commandName) {
                    $changelogData['CheckCommand'] = [
                        'id'   => $commandId,
                        'name' => $commandName,
                    ];
                }
                unset($commandsForChangelog);
            }
        }
        if ($this->request->data('Service.host_id')) {
            $hostsForChangelog = $this->Host->find('first', [
                'recursive'  => -1,
                'fields'     => [
                    'id',
                    'name',
                    'container_id',
                ],
                'conditions' => [
                    'Host.id' => $this->request->data['Service']['host_id'],
                ],
            ]);
            if (!empty($hostsForChangelog)) {
                foreach ($hostsForChangelog as $hostData) {
                    $changelogData['Host'] = [
                        'id'           => $hostData['id'],
                        'name'         => $hostData['name'],
                        'container_id' => $hostData['container_id'],
                    ];
                }
                unset($hostsForChangelog);
            }
        }

        return $changelogData;
    }

    //Acl
    public function checkcommand() {
        return null;
    }

    //Only for ACLs
    public function externalcommands() {
        return null;
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

    public function details() {
        $this->layout = 'blank';
        //Only ship HTML Template

        $User = new User($this->Auth);
        $this->set('username', $User->getFullName());
        return;
    }

    public function loadServicesByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $this->Service->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServiceFilter = new ServiceFilter($this->request);
        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setContainerIds($containerIds);
        $ServiceCondition->includeDisabled(true);

        $services = $this->Service->makeItJavaScriptAble(
            $this->Service->getServicesForAngular($ServiceCondition, $selected)
        );

        $this->set(compact(['services']));
        $this->set('_serialize', ['services']);
    }

    public function loadServicesByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $this->Service->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
        $selected = $this->request->query('selected');
        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setContainerIds($this->MY_RIGHTS);
        $ServiceCondition->includeDisabled();

        $services = $this->Service->makeItJavaScriptAble(
            $this->Service->getServicesForAngular($ServiceCondition, $selected)
        );


        $this->set(compact(['services']));
        $this->set('_serialize', ['services']);
    }
}
