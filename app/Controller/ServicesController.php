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

use itnovum\openITCOCKPIT\Core\CustomVariableDiffer;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
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
 */
class ServicesController extends AppController {
    public $layout = 'Admin.default';
    public $components = [
        'Paginator',
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
        MONITORING_ACKNOWLEDGED_SERVICE,
        MONITORING_OBJECTS,
        'DeletedService',
        'Rrd',
        'Container',
        'Documentation',
        'Systemsetting'
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Service.servicename' => ['label' => 'Servicename', 'searchType' => 'wildcard'],
                'Servicestatus.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
                'Service.keywords' => ['label' => 'Tag', 'searchType' => 'wildcardMulti', 'hidden' => true],
                'Servicestatus.current_state' => ['label' => 'Current state', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Servicestatus.ok',
                            'value' => 1,
                            'label' => 'Ok',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '1' => [
                            'name' => 'Servicestatus.warning',
                            'value' => 1,
                            'label' => 'Warning',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '2' => [
                            'name' => 'Servicestatus.critical',
                            'value' => 1,
                            'label' => 'Critical',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '3' => [
                            'name' => 'Servicestatus.unknown',
                            'value' => 1,
                            'label' => 'Unknown',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                    ],
                ],
                'Servicestatus.problem_has_been_acknowledged' => ['label' => 'Acknowledged', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Not Acknowledged',
                            'value' => 1,
                            'label' => 'Not Acknowledged',
                            'data' => 'Filter.Servicestatus.problem_has_been_acknowledged',
                        ],
                        '1' => [
                            'name' => 'Acknowledged',
                            'value' => 1,
                            'label' => 'Acknowledged',
                            'data' => 'Filter.Servicestatus.problem_has_been_acknowledged',
                        ],
                    ],
                ],
                'Servicestatus.scheduled_downtime_depth' => ['label' => 'In Downtime', 'type' => 'checkbox', 'searchType' => 'greater', 'options' =>
                    [
                        '0' => [
                            'name' => 'Downtime',
                            'value' => 1,
                            'label' => 'In Downtime',
                            'data' => 'Filter.Servicestatus.scheduled_downtime_depth',
                        ],
                    ],
                ],
                'Servicestatus.active_checks_enabled' => ['label' => 'Passive', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Passive',
                            'value' => 1,
                            'label' => 'Passive',
                            'data' => 'Filter.Servicestatus.active_checks_enabled',
                        ],
                    ],
                ],
            ],
        ],
        'listToPdf' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Service.servicename' => ['label' => 'Servicename', 'searchType' => 'wildcard'],
                'Servicestatus.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
                'Service.keywords' => ['label' => 'Tag', 'searchType' => 'wildcardMulti', 'hidden' => true],
                'Servicestatus.current_state' => ['label' => 'Current state', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Servicestatus.ok',
                            'value' => 1,
                            'label' => 'Ok',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '1' => [
                            'name' => 'Servicestatus.warning',
                            'value' => 1,
                            'label' => 'Warning',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '2' => [
                            'name' => 'Servicestatus.critical',
                            'value' => 1,
                            'label' => 'Critical',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                        '3' => [
                            'name' => 'Servicestatus.unknown',
                            'value' => 1,
                            'label' => 'Unknown',
                            'data' => 'Filter.Servicestatus.current_state',
                        ],
                    ],
                ],
                'Servicestatus.problem_has_been_acknowledged' => ['label' => 'Acknowledged', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Not Acknowledged',
                            'value' => 1,
                            'label' => 'Not Acknowledged',
                            'data' => 'Filter.Servicestatus.problem_has_been_acknowledged',
                        ],
                        '1' => [
                            'name' => 'Acknowledged',
                            'value' => 1,
                            'label' => 'Acknowledged',
                            'data' => 'Filter.Servicestatus.problem_has_been_acknowledged',
                        ],
                    ],
                ],
                'Servicestatus.scheduled_downtime_depth' => ['label' => 'In Downtime', 'type' => 'checkbox', 'searchType' => 'greater', 'options' =>
                    [
                        '0' => [
                            'name' => 'Downtime',
                            'value' => 1,
                            'label' => 'In Downtime',
                            'data' => 'Filter.Servicestatus.scheduled_downtime_depth',
                        ],
                    ],
                ],
                'Servicestatus.active_checks_enabled' => ['label' => 'Passive', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
                    [
                        '0' => [
                            'name' => 'Passive',
                            'value' => 1,
                            'label' => 'Passive',
                            'data' => 'Filter.Servicestatus.active_checks_enabled',
                        ],
                    ],
                ],
            ],
        ],
        'notMonitored' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Service.servicename' => ['label' => 'Servicename', 'searchType' => 'wildcard'],
                'Service.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
            ],
        ],
        'disabled' => [
            'fields' => [
                'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
                'Service.servicename' => ['label' => 'Servicename', 'searchType' => 'wildcard'],
                'Service.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
            ],
        ],
    ];

    public function index(){
        $ServiceControllerRequest = new ServiceControllerRequest($this->request);
        $ServiceConditions = new ServiceConditions();
        $User = new User($this->Auth);
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
            'Host.name' => 'asc',
            'Service.servicename' => 'asc'
        ]));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->Servicestatus->virtualFieldsForIndexAndServiceList();
            $query = $this->Servicestatus->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $modelName = 'Servicestatus';
        }

        if ($this->isApiRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_services = $this->{$modelName}->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_services = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
        }

        $this->set('all_services', $all_services);
        $this->set('_serialize', ['all_services']);
        $this->set('username', $User->getFullName());
        $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));

        //lookup host containers for edit permissions
        $hostContainers = [];
        if (!empty($all_services) && $this->hasRootPrivileges === false) {
            $hostIds = array_unique(Hash::extract($all_services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain' => [
                    'Container',
                ],
                'fields' => [
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
            $this->set('hostContainers', $hostContainers);
        }

    }

    public function view($id = null){
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

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid']);
        if (empty($servicestatus)) {
            $servicestatus = [
                'Servicestatus' => [],
            ];
        }
        $service = Hash::merge($service, $servicestatus);

        $this->set('service', $service);
        $this->set('_serialize', ['service']);
    }

    public function notMonitored(){
        $this->__unbindAssociations('Host');

        $this->Service->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';

        $conditions = ['Service.disabled' => 0, 'ServiceObject.name2 IS NULL'];
        $conditions = $this->ListFilter->buildConditions([], $conditions);

        if (isset($this->request->params['named']['BrowserContainerId'])) {
            //The user set a comntainer id in the URL, may be over browser
            $all_container_ids = Hash::merge(
                [$this->request->params['named']['BrowserContainerId']],
                Hash::extract(
                    $this->Container->children(
                        $this->request->params['named']['BrowserContainerId'],
                        false,
                        ['id', 'containertype_id']
                    ),
                    '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . ')$/].id'
                )
            );

            $_conditions = [
                'Host.disabled' => 0,
                'Host.container_id' => $all_container_ids,
            ];
            $conditions = Hash::merge($conditions, $_conditions);
        }

        $all_services = [];
        $query = [
            'contain' => ['Servicetemplate'],
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.description',
                'Service.active_checks_enabled',

                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicetemplate.active_checks_enabled',

                'ServiceObject.object_id',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.description',
                'Host.address',
            ],
            'order' => ['Host.name' => 'asc'],
            'joins' => [
                [
                    'table' => 'hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ], [
                    'table' => 'nagios_objects',
                    'type' => 'LEFT OUTER',
                    'alias' => 'ServiceObject',
                    'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2',
                ],
            ],
            'conditions' => $conditions,
        ];
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_services = $this->Service->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_services = $this->Paginator->paginate();
        }
        $hostContainers = [];
        if (!empty($all_services)) {
            $hostIds = array_unique(Hash::extract($all_services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain' => [
                    'Container',
                ],
                'fields' => [
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

        $hoststatus = $this->Hoststatus->byUuid(array_unique(Hash::extract($all_services, '{n}.Host.uuid')));

        // We want to display all services, that are not monitored (due to no export or whatever) so we can set an empty servicestatus array
        $servicestatus = [];

        $this->set(compact(['all_services', 'servicestatus', 'hoststatus', 'hostContainers']));
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_services']);
        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            if (isset($this->request->data['Filter']['ServiceStatus']['current_state'])) {
                $this->set('HostStatus.current_state', $this->request->data['Filter']['HostStatus']['current_state']);
            } else {
                $this->set('ServiceStatus.current_state', []);
            }
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function disabled(){
        $this->__unbindAssociations('Host');

        $this->Service->virtualFields['servicestatus'] = 'Servicestatus.current_state';
        $this->Service->virtualFields['last_hard_state_change'] = 'Servicestatus.last_hard_state_change';
        $this->Service->virtualFields['last_check'] = 'Servicestatus.last_check';
        $this->Service->virtualFields['next_check'] = 'Servicestatus.next_check';
        $this->Service->virtualFields['output'] = 'Servicestatus.output';
        $this->Service->virtualFields['hostname'] = 'Host.name';
        $this->Service->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';

        $conditions = ['Service.disabled' => 1];
        $conditions = $this->ListFilter->buildConditions([], $conditions);

        if (isset($this->request->params['named']['BrowserContainerId'])) {
            //The user set a comntainer id in the URL, may be over browser
            $all_container_ids = Hash::merge(
                [$this->request->params['named']['BrowserContainerId']],
                Hash::extract(
                    $this->Container->children(
                        $this->request->params['named']['BrowserContainerId'],
                        false,
                        ['id', 'containertype_id']
                    ),
                    '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . ')$/].id'
                )
            );

            $_conditions = [
                'Host.disabled' => 0,
                'Host.container_id' => $all_container_ids,
            ];
            $conditions = Hash::merge($conditions, $_conditions);
        }

        $all_services = [];
        $query = [
            'contain' => ['Servicetemplate'],
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.description',
                'Service.active_checks_enabled',

                'Servicetemplate.id',
                'Servicetemplate.uuid',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicetemplate.active_checks_enabled',

                'HostObject.object_id',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.description',
                'Host.address',

                'Hoststatus.current_state',
            ],
            'order' => ['Host.name' => 'asc'],
            'joins' => [
                [
                    'table' => 'hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' => 'Service.host_id = Host.id',
                ],
                [
                    'table' => 'nagios_objects',
                    'type' => 'LEFT OUTER',
                    'alias' => 'HostObject',
                    'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                ],
                [
                    'table' => 'nagios_hoststatus',
                    'type' => 'LEFT OUTER',
                    'alias' => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
                ],
                [
                    'table' => 'hosts_to_containers',
                    'alias' => 'HostsToContainers',
                    'type' => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'conditions' => $conditions,
            'group' => [
                'Service.id',
            ],
        ];
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_services = $this->Service->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_services = $this->Paginator->paginate();
        }
        $hostContainers = [];
        if (!empty($all_services)) {
            $hostIds = array_unique(Hash::extract($all_services, '{n}.Host.id'));
            $_hostContainers = $this->Host->find('all', [
                'contain' => [
                    'Container',
                ],
                'fields' => [
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

        // We want to display all disabled services so we can set an empty servicestatus array
        $servicestatus = [];

        $this->set(compact(['all_services', 'servicestatus', 'hostContainers']));
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_services']);
        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            if (isset($this->request->data['Filter']['ServiceStatus']['current_state'])) {
                $this->set('HostStatus.current_state', $this->request->data['Filter']['HostStatus']['current_state']);
            } else {
                $this->set('ServiceStatus.current_state', []);
            }
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function add(){
        $userId = $this->Auth->user('id');
        $Customvariable = [];
        $customFieldsToRefill = [
            'Service' => [
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
            'Contact' => [
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
        $hosts = $this->Host->find('list', [
            'conditions' => [
                'Host.host_type' => GENERIC_HOST,
                'Host.container_id' => $myRights
            ]
        ]);

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
            'hosts',
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
                    'contain' => [
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
                    'recursive' => -1,
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
                    $this->redirect(['action' => 'notMonitored']);
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

    public function edit($id = null){
        $userId = $this->Auth->user('id');
        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            throw new NotFoundException(__('invalid service'));
        }

        $__service = $this->Service->find('first', [
            'recursive' => -1,
            'conditions' => [
                'Service.id' => $id,
            ],
            'contain' => [
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
            'Service' => [
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
            'Contact' => [
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
            'recursive' => -1,
            'conditions' => [
                'Commandargument.command_id' => $service['Service']['command_id'],
            ],
        ]);
        $eventhandler_commandarguments = $this->Commandargument->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Commandargument.command_id' => $service['Service']['eventhandler_command_id'],
            ],
        ]);
        $contacts_for_changelog = [];
        foreach ($service['Contact'] as $contact_id) {
            if (isset($contacts[$contact_id])) {
                $contacts_for_changelog[] = [
                    'id' => $contact_id,
                    'name' => $contacts[$contact_id],
                ];
            }
        }
        $contactgroups_for_changelog = [];
        foreach ($service['Contactgroup'] as $contactgroup_id) {
            $contactgroups_for_changelog[] = [
                'id' => $contactgroup_id,
                'name' => $contactgroups[$contactgroup_id],
            ];
        }
        $servicegroups_for_changelog = [];
        foreach ($service['Servicegroup'] as $servicegroup_id) {
            if (isset($servicegroups[$servicegroup_id])) {
                $servicegroups_for_changelog[] = [
                    'id' => $servicegroup_id,
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
            'recursive' => -1,
            'contain' => [
                'Contact' => [
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
                    'fields' => [
                        'Contactgroup.id',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $service['Service']['id'],
            ],
            'fields' => [
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
                'Contact' => [],
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
                            'id' => $contactId,
                            'name' => $contactName,
                        ];
                    }
                    unset($contactsForChangelog);
                }
            }
            if ($this->request->data('Service.Contactgroup')) {
                if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields' => [
                        'Contactgroup.id',
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $this->request->data['Service']['Contactgroup'],
                    ],
                ])
                ) {
                    foreach ($contactgroupsForChangelog as $contactgroupData) {
                        $ext_data_for_changelog['Contactgroup'][] = [
                            'id' => $contactgroupData['Contactgroup']['id'],
                            'name' => $contactgroupData['Container']['name'],
                        ];
                    }
                    unset($contactgroupsForChangelog);
                }
            }
            if ($this->request->data('Service.Servicegroup')) {
                if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields' => [
                        'Servicegroup.id',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $this->request->data['Service']['Servicegroup'],
                    ],
                ])
                ) {
                    foreach ($servicegroupsForChangelog as $servicegroupData) {
                        $ext_data_for_changelog['Servicegroup'][] = [
                            'id' => $servicegroupData['Servicegroup']['id'],
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
                            'id' => $timeperiodId,
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
                            'id' => $timeperiodId,
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
                            'id' => $servicetemplateId,
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
                            'id' => $commandId,
                            'name' => $commandName,
                        ];
                    }
                    unset($commandsForChangelog);
                }
            }
            if ($this->request->data('Service.host_id')) {
                if ($hostsForChangelog = $this->Host->find('first', [
                    'recursive' => -1,
                    'fields' => [
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
                            'id' => $hostData['id'],
                            'name' => $hostData['name'],
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
                    'contain' => [
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
                    'recursive' => -1,
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
                    'object_id' => $service['Service']['id'],
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

    public function delete($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $service = $this->Service->findById($id);
        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $service['Host']['id'],
            ],
            'contain' => [
                'Container',
            ],
            'fields' => [
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

        if ($this->Service->__delete($service, $this->Auth->user('id'))) {
            //   $this->setFlash(__('Service deleted.'));
            $this->Flash->success('Service deleted', [
                'key' => 'positive',
            ]);
            $this->redirect(['action' => 'index']);
        }
        //$this->setFlash(__('Could not delete service'), false);

        $this->Flash->error('Could not delete service', [
            'key' => 'positive',
            'params' => [
                'usedBy' => $this->Service->usedBy,
            ]
        ]);
        $this->redirect(['action' => 'index']);
    }

    public function mass_delete($id = null){
        $msgCollect = [];
        foreach (func_get_args() as $service_id) {
            if ($this->Service->exists($service_id)) {
                $service = $this->Service->findById($service_id);
                $host = $this->Host->find('first', [
                    'conditions' => [
                        'Host.id' => $service['Host']['id'],
                    ],
                    'contain' => [
                        'Container',
                    ],
                    'fields' => [
                        'Host.id',
                        'Host.container_id',
                        'Container.*',
                    ],
                ]);
                $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
                $containerIdsToCheck[] = $host['Host']['container_id'];
                if ($this->allowedByContainerId($containerIdsToCheck)) {
                    if (!$this->Service->__delete($service, $this->Auth->user('id'))) {
                        $msgCollect[] = $this->Service->usedBy;
                    }
                }
            }
        }


        if (!empty($msgCollect)) {
            $messages = call_user_func_array('array_merge_recursive', $msgCollect);
            $this->Flash->error('Could not delete service', [
                'key' => 'positive',
                'params' => [
                    'usedBy' => $messages,
                ]
            ]);
            $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
        }

        $this->Flash->success('Service deleted', [
            'key' => 'positive',
        ]);
        $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
    }

    public function copy($id = null){
        $userId = $this->Auth->user('id');
        $servicesToCopy = [];
        $servicesCantCopy = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            //Checking if target host exists
            if ($this->Host->exists($this->request->data('Service.host_id'))) {
                $host = $this->Host->find('first', [
                    'recursive' => -1,
                    'conditions' => [
                        'Host.id' => $this->request->data('Service.host_id')
                    ],
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.container_id'
                    ]
                ]);
                foreach ($this->request->data['Service']['source'] as $sourceServiceId) {
                    $service = $this->Service->find('first', [
                        'recursive' => -1,
                        'fields' => [
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
                        ],
                        'contain' => [
                            'CheckPeriod' => [
                                'fields' => [
                                    'CheckPeriod.id',
                                    'CheckPeriod.name'
                                ]
                            ],
                            'NotifyPeriod' => [
                                'fields' => [
                                    'NotifyPeriod.id',
                                    'NotifyPeriod.name'
                                ]
                            ],
                            'CheckCommand' => [
                                'fields' => [
                                    'CheckCommand.id',
                                    'CheckCommand.name',
                                ]
                            ],
                            'Contact' => [
                                'fields' => [
                                    'Contact.id',
                                    'Contact.name'
                                ],
                            ],
                            'Contactgroup' => [
                                'fields' => [
                                    'Contactgroup.id',
                                ],
                                'Container' => [
                                    'fields' => [
                                        'Container.name'
                                    ]
                                ]
                            ],
                            'Servicecommandargumentvalue' => [
                                'fields' => [
                                    'commandargument_id', 'value',
                                ],
                            ],
                            'Serviceeventcommandargumentvalue' => [
                                'fields' => [
                                    'commandargument_id', 'value',
                                ],
                            ],
                            'Customvariable' => [
                                'fields' => [
                                    'name',
                                    'value',
                                    'objecttype_id'
                                ],
                            ],
                            'Servicegroup' => [
                                'fields' => [
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
                            'Service.id' => $sourceServiceId,
                            'Service.service_type' => $this->Service->serviceTypes('copy')
                        ],
                    ]);

                    if (isset($servicetemplates[$service['Service']['servicetemplate_id']])) {
                        $servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
                    } else {
                        $servicetemplates[$service['Service']['servicetemplate_id']] = $this->Servicetemplate->find('first', [
                                'recursive' => -1,
                                'fields' => [
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
                                'contain' => [
                                    'CheckPeriod' => [
                                        'fields' => [
                                            'CheckPeriod.id',
                                            'CheckPeriod.name'
                                        ]
                                    ],
                                    'NotifyPeriod' => [
                                        'fields' => [
                                            'NotifyPeriod.id',
                                            'NotifyPeriod.name'
                                        ]
                                    ],
                                    'CheckCommand' => [
                                        'fields' => [
                                            'CheckCommand.id',
                                            'CheckCommand.name',
                                        ]
                                    ],
                                    'Contact' => [
                                        'fields' => [
                                            'Contact.id',
                                            'Contact.name'
                                        ],
                                    ],
                                    'Contactgroup' => [
                                        'fields' => [
                                            'Contactgroup.id',
                                        ],
                                        'Container' => [
                                            'fields' => [
                                                'Container.name'
                                            ]
                                        ]
                                    ],
                                    'Servicegroup' => [
                                        'fields' => [
                                            'Servicegroup.id',
                                        ],
                                        'Container' => [
                                            'fields' => [
                                                'Container.name'
                                            ]
                                        ]
                                    ],
                                    'Servicetemplatecommandargumentvalue' => [
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
                                    'Customvariable' => [
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
                        'Service' => Hash::merge(
                            $service['Service'], [
                            'uuid' => UUID::v4(),
                            'host_id' => $host['Host']['id'],
                            'Contact' => $contactIds,
                            'Contactgroup' => $contactgroupIds,
                            'Servicegroup' => $servicegroupIds,
                            'Customvariable' => $customVariables,
                        ]),
                        'Contact' => ['Contact' => $contactIds],
                        'Contactgroup' => ['Contactgroup' => $contactgroupIds],
                        'Servicegroup' => ['Servicegroup' => $servicegroupIds],

                        'Customvariable' => $customVariables,
                        'Servicecommandargumentvalue' => (!empty($service['Servicecommandargumentvalue'])) ? Hash::remove($service['Servicecommandargumentvalue'], '{n}.service_id') : [],
                        'Serviceeventcommandargumentvalue' => (!empty($service['Serviceeventcommandargumentvalue'])) ? Hash::remove($service['Serviceeventcommandargumentvalue'], '{n}.service_id') : [],
                    ];

                    /* Data for Changelog Start*/
                    $service['Host'] = ['id' => $host['Host']['id'], 'name' => $host['Host']['name']];
                    if (!empty($service['Contactgroup'])) {
                        $contactgroups = [];
                        foreach ($service['Contactgroup'] as $contactgroup) {
                            $contactgroups[] = [
                                'id' => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $service['Contactgroup'] = $contactgroups;
                    } elseif (empty($service['Contactgroup']) && !empty($servicetemplate['Contactgroup'])) {
                        $contactgroups = [];
                        foreach ($servicetemplate['Contactgroup'] as $contactgroup) {
                            $contactgroups[] = [
                                'id' => $contactgroup['id'],
                                'name' => $contactgroup['Container']['name']
                            ];
                        }
                        $servicetemplate['Contactgroup'] = $contactgroups;
                    }

                    if (!empty($service['Servicegroup'])) {
                        $servicegroups = [];
                        foreach ($service['Servicegroup'] as $servicegroup) {
                            $servicegroups[] = [
                                'id' => $servicegroup['id'],
                                'name' => $servicegroup['Container']['name']
                            ];
                        }
                        $service['Servicegroup'] = $servicegroups;
                    } elseif (empty($service['Servicegroup']) && !empty($servicetemplate['Servicegroup'])) {
                        $servicegroups = [];
                        foreach ($servicetemplate['Servicegroup'] as $servicegroup) {
                            $servicegroups[] = [
                                'id' => $servicegroup['id'],
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

    public function deactivate($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->findById($id);
        if ($this->__disable($service)) {
            $this->setFlash(__('Service disabled'));
            $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
        }
        $this->setFlash(__('Could not disable service'), false);
        $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
    }

    public function mass_deactivate($id = null){
        foreach (func_get_args() as $service_id) {
            if ($this->Service->exists($service_id)) {
                $service = $this->Service->findById($service_id);
                $this->__disable($service);
            }
        }
        $this->setFlash(__('Services disabled'));
        $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
    }

    protected function __disable($service){
        $service['Service']['disabled'] = 1;

        return $this->Service->save($service);
    }

    public function enable($id = null){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $service = $this->Service->findById($id);
        $service['Service']['disabled'] = 0;
        if ($this->Service->save($service)) {
            $this->setFlash(__('Service enabled'));
            $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
        }
        $this->setFlash(__('Could not enable service'), false);
        $this->redirect(['action' => 'serviceList', $service['Service']['host_id']]);
    }

    public function loadContactsAndContactgroups($container_id = null){
        $this->allowOnlyAjaxRequests();

        $result = [
            'contacts' => [
                'contacts' => [],
                'sizeof' => 0,
            ],
            'contactgroups' => [
                'contactgroups' => [],
                'sizeof' => 0,
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

    public function loadParametersByCommandId($command_id = null, $servicetemplate_id = null){
        $this->allowOnlyAjaxRequests();

        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive' => -1,
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
                        'fields' => [
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

    public function loadNagParametersByCommandId($command_id = null, $servicetemplate_id = null){
        $this->allowOnlyAjaxRequests();

        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive' => -1,
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
                        'fields' => [
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

    public function loadArgumentsAdd($command_id = null){
        $this->allowOnlyAjaxRequests();
        $this->loadModel('Commandargument');

        $commandarguments = $this->Commandargument->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Commandargument.command_id' => $command_id,
            ],
        ]);

        $this->set('commandarguments', $commandarguments);
        $this->render('load_arguments');
    }

    public function loadServicetemplatesArguments($servicetemplate_id = null){
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

    public function loadTemplateData($servicetemplate_id = null){
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Servicetemplate');
        $servicetemplateData = $this->Servicetemplate->find('first', [
            'recursive' => -1,
            'conditions' => [
                'Servicetemplate.id' => $servicetemplate_id,
            ],
            'contain' => [
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

    public function addCustomMacro($counter){
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_SERVICE);
        $this->set('counter', $counter);
    }

    public function loadServices($host_id){
        /* $this->allowOnlyAjaxRequests(); */

        $this->loadModel('Host');
        $services = $this->Service->find('all');
        $this->set(compact(['services']));
        $this->set('_serialize', ['services']);
    }

    public function loadTemplateMacros($servicetemplate_id = null){
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
                'recursive' => -1,
                'contain' => [
                    'Customvariable' => [
                        'fields' => [
                            'Customvariable.name',
                            'Customvariable.value',
                            'Customvariable.objecttype_id',
                        ],
                    ],
                ],
                'fields' => [
                    'Servicetemplate.id',
                ],
            ]);
        }
        $this->set('servicetemplate', $servicetemplate);
    }

    function browser($id = null){
        $browseByUUID = false;
        $conditionsToFind = ['Service.id' => $id];
        if (preg_match('/\-/', $id)) {
            $browseByUUID = true;
            $conditionsToFind = ['Service.uuid' => $id];
        }

        $_service = $this->Service->find('first', [
            'conditions' => $conditionsToFind,
            'contain' => [
                'Contact' => [
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
                    'fields' => [
                        'Contactgroup.id',
                    ],
                ],
                'Servicecommandargumentvalue',
                'Host' => [
                    'Container',
                ],
                'NotifyPeriod',
                'CheckPeriod',

            ],
        ]);

        if (empty($_service)) {
            throw new NotFoundException(__('Service not found'));
        }
        if ($browseByUUID) {
            $id = $_service['Service']['id'];
        }

        $service = $this->Service->prepareForView($id);
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);
        //$_service = $this->Service->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($_service, 'Host.Container.{n}.HostsToContainer.container_id'), false)) {
            $this->render403();

            return;
        }

        $allowEdit = false;
        if ($this->allowedByContainerId(Hash::extract($_service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $allowEdit = true;
        }

        //Select command arguments, that we can replace them in in view
        $commandarguments = [];
        if ($_service['Servicecommandargumentvalue']) {
            //The service has own command argument values
            $_commandarguments = $this->Servicecommandargumentvalue->findAllByServiceId($_service['Service']['id']);
            $_commandarguments = Hash::sort($_commandarguments, '{n}.Commandargument.name', 'asc', 'natural');
            foreach ($_commandarguments as $commandargument) {
                $commandarguments[$commandargument['Commandargument']['name']] = $commandargument['Servicecommandargumentvalue']['value'];
            }
        } else {
            //The service command arguments are from the template
            $_commandarguments = $this->Servicetemplatecommandargumentvalue->findAllByServicetemplateId($service['Servicetemplate']['id']);
            $_commandarguments = Hash::sort($_commandarguments, '{n}.Commandargument.name', 'asc', 'natural');
            foreach ($_commandarguments as $commandargument) {
                $commandarguments[$commandargument['Commandargument']['name']] = $commandargument['Servicetemplatecommandargumentvalue']['value'];
            }
        }

        $ContactsInherited = $this->__inheritContactsAndContactgroups($service, $_service);

        $service['Host'] = $_service['Host'];
        $service['NotifyPeriod'] = $_service['NotifyPeriod'];
        $service['CheckPeriod'] = $_service['CheckPeriod'];
        unset($_service);

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid']);
        $hoststatus = $this->Hoststatus->byUuid($service['Host']['uuid']);

        if (isset($servicestatus['Servicestatus']) && $servicestatus['Servicestatus']['problem_has_been_acknowledged'] > 0) {
            $acknowledged = $this->AcknowledgedService->byUuid($service['Service']['uuid']);
            if (empty($acknowledged)) {
                $acknowledged = [];
            }
        }
        $ticketSystem = $this->Systemsetting->find('first', [
            'conditions' => ['key' => 'TICKET_SYSTEM.URL'],
        ]);
        $username = $this->Auth->user('full_name');
        $serviceAllValues = $this->Rrd->getPerfDataFiles($service['Host']['uuid'], $service['Service']['uuid']);
        $serviceValues = [];
        if (isset($serviceAllValues['xml_data']) && !empty($serviceAllValues['xml_data'])) {
            foreach ($serviceAllValues['xml_data'] as $serviceValueArr) {
                $serviceValues[$serviceValueArr['ds']] = $service['Host']['name'] . '/' . $service['Service']['name'] . '/' . $serviceValueArr['name'];
            }
        }
        $this->set(compact([
                'service',
                'servicestatus',
                'username',
                'acknowledged',
                'commandarguments',
                'ContactsInherited',
                'hoststatus',
                'allowEdit',
                'ticketSystem',
                'serviceValues',
                'docuExists'
            ])
        );
        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);
        $this->Frontend->setJson('hostUuid', $service['Host']['uuid']);
        $this->Frontend->setJson('serviceUuid', $service['Service']['uuid']);

        $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
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

    public function servicesByHostId($host_id = null){
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        $services = $this->Service->find('all', [
            'recursive' => -1,
            'contain' => [
                'Servicetemplate' => [
                    'fields' => ['Servicetemplate.name'],
                ],
                'Host' => [
                    'fields' => ['Host.name', 'Host.uuid'],
                ],
            ],
            'fields' => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceDescription',
            ],
            'order' => [
                'Service.name ASC', 'Servicetemplate.name ASC',
            ],
            'conditions' => [
                'Host.id' => $host_id,
            ],
        ]);

        $this->set('services', $services);
        $this->render('load_services');
    }

    public function serviceList($host_id){
        if (!$this->Host->exists($host_id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'fields' => [
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
            'contain' => [
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

        $disabledServices = $this->Service->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Service.host_id' => $host_id,
                'Service.disabled' => 1
            ],
            'contain' => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name'
                    ]
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ]
            ],
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name'
            ]
        ]);
        $deletedServices = $this->DeletedService->findAllByHostId($host_id);


        $allowEdit = false;
        if ($this->allowedByContainerId($containerIdsToCheck)) {
            $allowEdit = true;
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');


        $ServiceControllerRequest = new ServiceControllerRequest($this->request);
        $ServiceConditions = new ServiceConditions();
        $User = new User($this->Auth);
        $ServiceConditions->setIncludeDisabled(false);
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        $ServiceConditions->setHostId($host_id);

        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Service.servicename' => 'asc'
        ]));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->Servicestatus->virtualFieldsForIndexAndServiceList();
            $query = $this->Servicestatus->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $modelName = 'Servicestatus';
        }

        if ($this->isApiRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_services = $this->{$modelName}->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_services = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
        }


        $this->Frontend->setJson('hostUuid', $host['Host']['uuid']);

        $username = $this->Auth->user('full_name');


        $this->set(compact(['all_services', 'host', 'hosts', 'host_id', 'disabledServices', 'deletedServices', 'username', 'allowEdit']));
        $this->set('_serialize', ['all_services']);
    }

    public function grapherSwitch($id){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $service = $this->Service->findById($id);
        $commandUuid = $service['CheckCommand']['uuid'];
        if ($commandUuid == null || $commandUuid == '') {
            $servicetemplate = $this->Servicetemplate->findById($service['Service']['servicetemplate_id']);
            $commandUuid = $servicetemplate['CheckCommand']['uuid'];
        }

        if (file_exists(APP . 'GrapherTemplates' . DS . $commandUuid . '.php')) {
            return $this->redirect('/services/grapherTemplate/' . $service['Service']['id']);
        }

        return $this->redirect('/services/grapher/' . $service['Service']['id']);
    }

    public function grapher($id){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $this->Service->unbindModel([
            'hasAndBelongsToMany' => ['Servicegroup', 'Contact', 'Contactgroup'],
            'hasMany' => ['Servicecommandargumentvalue', 'ServiceEscalationServiceMembership', 'ServicedependencyServiceMembership', 'Customvariable'],
            'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand'],
        ]);

        $service = $this->Service->findById($id);
        $hostContainerId = $service['Host']['container_id'];
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);

        $services = $this->Service->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Service.host_id' => $service['Host']['id']
            ],
            'contain' => [
                'Servicetemplate'
            ],
            'fields' => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
            ]
        ]);

        $allowEdit = false;
        if ($this->allowedByContainerId($hostContainerId)) {
            $allowEdit = true;
        }

        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid']);
        $showThresholds = is_null($this->Session->read('service_thresholds_'.$id)) ? '1' : $this->Session->read('service_thresholds_'.$id);
        $this->set(compact(['service', 'servicestatus', 'allowEdit', 'services', 'docuExists', 'showThresholds']));
    }

    public function grapherTemplate($id){
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $this->Service->unbindModel([
            'hasAndBelongsToMany' => ['Servicegroup', 'Contact', 'Contactgroup'],
            'hasMany' => ['Servicecommandargumentvalue', 'ServiceEscalationServiceMembership', 'ServicedependencyServiceMembership', 'Customvariable'],
            'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand'],
        ]);
        $service = $this->Service->findById($id);
        $docuExists = $this->Documentation->existsForUuid($service['Service']['uuid']);
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid']);

        $commandUuid = $service['CheckCommand']['uuid'];
        if ($commandUuid == null || $commandUuid == '') {
            $servicetemplate = $this->Servicetemplate->findById($service['Service']['servicetemplate_id']);
            $commandUuid = $servicetemplate['CheckCommand']['uuid'];
        }

        $this->set(compact(['service', 'servicestatus', 'commandUuid', 'docuExists']));
    }

    public function grapherZoom($id, $ds, $newStart, $newEnd, $showThresholds) {
        if (!$this->Service->exists($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        $this->Session->write('service_thresholds_'.$id, $showThresholds);

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
                if($showThresholds !== '1') {
                    unset($rrd_structure_datasource['crit']);
                    unset($rrd_structure_datasource['warn']);
                }

                $imageUrl = $this->Rrd->createRrdGraph($rrd_structure_datasource, [
                    'host_uuid' => $service['Host']['uuid'],
                    'service_uuid' => $service['Service']['uuid'],
                    'path' => $rrd_path,
                    'start' => $newStart,
                    'end' => $newEnd,
                    'label' => $service['Host']['name'] . ' / ' . $service['Servicetemplate']['name'],
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

    public function grapherZoomTemplate($id, $ds, $newStart, $newEnd, $commandUuid){
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

    public function createGrapherErrorPng($error){
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
    function longOutputByUuid($uuid = null, $parseBbcode = true, $nl2br = true){
        $this->autoRender = false;
        $result = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.uuid' => $uuid
            ]
        ]);
        if (!empty($result)) {
            $servicestatus = $this->Servicestatus->byUuid($result['Service']['uuid'], [
                'fields' => [
                    'Servicestatus.long_output'
                ]
            ]);
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

    public function getSelectedServices($ids){
        $servicestatus = $this->Service->find('all', [
            'recursive' => -1,
            'fields' => [
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
                'Servicestatus.output',
            ],
            'conditions' => [
                'Service.id' => $ids,
            ],
            'joins' => [
                [
                    'table' => 'servicetemplates',
                    'type' => 'INNER',
                    'alias' => 'Servicetemplate',
                    'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
                ],
                [
                    'table' => 'nagios_objects',
                    'type' => 'INNER',
                    'alias' => 'Objects',
                    'conditions' => 'Objects.name2 = Service.uuid',
                ],
                [
                    'table' => 'nagios_servicestatus',
                    'type' => 'INNER',
                    'alias' => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = Objects.object_id',
                ],
            ],
        ]);

        $hostIds = Hash::extract($servicestatus, '{n}.Service.host_id');
        $hostIds = array_unique($hostIds);

        $hosts = $this->Objects->find('all', [
            'recursive' => -1,
            'conditions' => [
                'Host.disabled' => 0,
                'Host.container_id' => $this->MY_RIGHTS,
                'Host.id' => $hostIds,
            ],
            'fields' => [
                'Host.id',
                'Host.name',
                'Host.address',
                'Hoststatus.current_state',
                'Hoststatus.is_flapping',
            ],
            'joins' => [
                [
                    'table' => 'hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' => 'Objects.name1 = Host.uuid AND Objects.objecttype_id = 1',
                ],
                [
                    'table' => 'nagios_hoststatus',
                    'type' => 'INNER',
                    'alias' => 'Hoststatus',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ],
            ],
            'order' => [
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
            'list' => $result,
            'count' => $serviceCount,
        ];

        return $ret;
    }

    public function listToPdf(){
        $ServiceControllerRequest = new ServiceControllerRequest($this->request);
        $ServiceConditions = new ServiceConditions();
        if ($ServiceControllerRequest->isRequestFromBrowser() === false) {
            $ServiceConditions->setIncludeDisabled(false);
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        }

        //Default order
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Host.name' => 'asc',
            'Service.servicename' => 'asc'
        ]));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Service->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $this->Service->virtualFieldsForIndexAndServiceList();
            $modelName = 'Service';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->Servicestatus->virtualFieldsForIndexAndServiceList();
            $query = $this->Servicestatus->getServiceIndexQuery($ServiceConditions, $this->ListFilter->buildConditions());
            $modelName = 'Servicestatus';
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
            'engine' => 'CakePdf.WkHtmlToPdf',
            'margin' => [
                'bottom' => 15,
                'left' => 0,
                'right' => 0,
                'top' => 15,
            ],
            'encoding' => 'UTF-8',
            'download' => true,
            'binary' => $binary_path,
            'orientation' => 'portrait',
            'filename' => $filename,
            'no-pdf-compression' => '*',
            'image-dpi' => '900',
            'background' => true,
            'no-background' => false,
        ];
    }

    /**
     * $service is from prepareForView() but ther are no names in the service contact, only ids
     * $_service is from $this->Service->findById, because of contact names
     */
    protected function __inheritContactsAndContactgroups($service, $serviceContactsAndContactgroups){
        if (empty($serviceContactsAndContactgroups['Contact']) && empty($serviceContactsAndContactgroups['Contactgroup'])) {

            //Check servicetemplate for contacts
            if (!empty($service['Servicetemplate']['Contact']) || !empty($service['Servicetemplate']['Contactgroup'])) {
                return [
                    'inherit' => true,
                    'source' => 'Servicetemplate',
                    'Contact' => Hash::combine($service['Servicetemplate']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Servicetemplate']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }

            //Check host for contacts
            //debug($service['Host']);
            if (!empty($service['Host']['Contact']) || !empty($service['Host']['Contactgroup'])) {
                return [
                    'inherit' => true,
                    'source' => 'Host',
                    'Contact' => Hash::combine($service['Host']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Host']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }

            //Check hosttemplate for contacts
            if (!empty($service['Host']['Hosttemplate']['Contact']) || !empty($service['Host']['Hosttemplate']['Contactgroup'])) {
                return [
                    'inherit' => true,
                    'source' => 'Hosttemplate',
                    'Contact' => Hash::combine($service['Host']['Hosttemplate']['Contact'], '{n}.id', '{n}.name'),
                    'Contactgroup' => Hash::combine($service['Host']['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.Container.name'),
                ];
            }
        }

        return [
            'inherit' => false,
            'source' => 'Service',
            'Contact' => Hash::combine($serviceContactsAndContactgroups['Contact'], '{n}.id', '{n}.name'),
            'Contactgroup' => Hash::combine($serviceContactsAndContactgroups['Contactgroup'], '{n}.id', '{n}.Container.name'),
        ];
    }

    /**
     * @return array
     */
    protected function getChangelogDataForAdd(){
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
                        'id' => $contactId,
                        'name' => $contactName,
                    ];
                }
                unset($contactsForChangelog);
            }
        }
        if ($this->request->data('Service.Contactgroup')) {
            if ($contactgroupsForChangelog = $this->Contactgroup->find('all', [
                'recursive' => -1,
                'contain' => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                        ],
                    ],
                ],
                'fields' => [
                    'Contactgroup.id',
                ],
                'conditions' => [
                    'Contactgroup.id' => $this->request->data['Service']['Contactgroup'],
                ],
            ])
            ) {
                foreach ($contactgroupsForChangelog as $contactgroupData) {
                    $changelogData['Contactgroup'][] = [
                        'id' => $contactgroupData['Contactgroup']['id'],
                        'name' => $contactgroupData['Container']['name'],
                    ];
                }
                unset($contactgroupsForChangelog);
            }
        }
        if ($this->request->data('Service.Servicegroup')) {
            if ($servicegroupsForChangelog = $this->Servicegroup->find('all', [
                'recursive' => -1,
                'contain' => [
                    'Container' => [
                        'fields' => [
                            'Container.name',
                        ],
                    ],
                ],
                'fields' => [
                    'Servicegroup.id',
                ],
                'conditions' => [
                    'Servicegroup.id' => $this->request->data['Service']['Servicegroup'],
                ],
            ])
            ) {
                foreach ($servicegroupsForChangelog as $servicegroupData) {
                    $changelogData['Servicegroup'][] = [
                        'id' => $servicegroupData['Servicegroup']['id'],
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
                        'id' => $timeperiodId,
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
                        'id' => $timeperiodId,
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
                        'id' => $servicetemplateId,
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
                        'id' => $commandId,
                        'name' => $commandName,
                    ];
                }
                unset($commandsForChangelog);
            }
        }
        if ($this->request->data('Service.host_id')) {
            $hostsForChangelog = $this->Host->find('first', [
                'recursive' => -1,
                'fields' => [
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
                        'id' => $hostData['id'],
                        'name' => $hostData['name'],
                        'container_id' => $hostData['container_id'],
                    ];
                }
                unset($hostsForChangelog);
            }
        }

        return $changelogData;
    }

    //Acl
    public function checkcommand(){
        return null;
    }

    public function ajaxGetByTerm(){
        $this->autoRender = false;
        if ($this->request->is('ajax') && isset($this->request->data['term'])){
            if(strpos($this->request->data['term'], '/') === false){
                $conditions = [
                    'OR' => [
                        ['Host.name LIKE' => '%'.$this->request->data['term'].'%'],
                        ['Service.ServiceDescription LIKE' => '%'.$this->request->data['term'].'%'],
                    ]
                ];
            }else{
                $hostServiceArr = explode('/', $this->request->data['term'], 2);
                $conditions = [
                    ['Host.name LIKE' => '%'.$hostServiceArr[0].'%'],
                    ['Service.ServiceDescription LIKE' => '%'.$hostServiceArr[1].'%'],
                ];
            }

            $selectedArr = isset($this->request->data['selected']) && !empty($this->request->data['selected']) ? $this->request->data['selected'] : [];
            $userContainerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            $services = $this->Service->getAjaxServices($userContainerIds, $conditions, $selectedArr);
            $returnHtml = '';
            foreach($services as $hostName => $serviceArr){
                $returnHtml .= '<optgroup label="'.$hostName.'">';
                foreach($serviceArr as $serviceId => $serviceName){
                    $returnHtml .= '<option value="'.$serviceId.'" '.(in_array($serviceId, $selectedArr)?'selected':'').'>'.$serviceName.'</option>';
                }
                $returnHtml .= '</optgroup>';
            }
            return $returnHtml;
        }
    }
}
