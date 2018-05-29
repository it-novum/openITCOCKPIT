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
use \itnovum\openITCOCKPIT\Core\HostControllerRequest;
use \itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\HosttemplateMerger;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\ModuleManager;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\HostPerfdataChecker;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\ScrollIndex;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use \itnovum\openITCOCKPIT\Monitoring\QueryHandler;
use itnovum\openITCOCKPIT\Core\HostSharingPermissions;

/**
 * @property Host $Host
 * @property Documentation $Documentation
 * @property Commandargument $Commandargument
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
 * @property Hostcommandargumentvalue $Hostcommandargumentvalue
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property DeletedHost $DeletedHost
 * @property DeletedService $DeletedService
 * @property Container $Container
 * @property Parenthost $Parenthost
 * @property Hosttemplate $Hosttemplate
 * @property Hostgroup $Hostgroup
 * @property Timeperiod $Timeperiod
 * @property DowntimeHost $DowntimeHost
 * @property BbcodeComponent $Bbcode
 */
class HostsController extends AppController {
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
        'Bbcode',
        'AdditionalLinks',
        'Flash'
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'CustomValidationErrors',
        'CustomVariables',
        'Bbcode',
        'Flash',
    ];
    public $uses = [
        'Host',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        MONITORING_OBJECTS,
        'Documentation',
        'Commandargument',
        'Hosttemplatecommandargumentvalue',
        'Hostcommandargumentvalue',
        'Contact',
        'Contactgroup',
        MONITORING_ACKNOWLEDGED_HOST,
        'DeletedHost',
        'DeletedService',
        'Container',
        'Parenthost',
        'Hosttemplate',
        'Hostgroup',
        'Timeperiod',
        'Servicetemplategroup',
        'Service',
        MONITORING_DOWNTIME_HOST
    ];

    public function index() {
        $this->layout = 'angularjs';
        $User = new User($this->Auth);

        $masterInstanceName = $this->Systemsetting->getMasterInstanceName();
        $SatelliteNames = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $SatelliteModel = $ModuleManager->loadModel('Satellite');
            $SatelliteNames = $SatelliteModel->find('list');
            $SatelliteNames[0] = $masterInstanceName;
        }

        if (!$this->isApiRequest()) {
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }


        $HostFilter = new HostFilter($this->request);

        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $User = new User($this->Auth);
        if ($HostControllerRequest->isRequestFromBrowser() === false) {
            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($this->MY_RIGHTS);
        }

        if ($HostControllerRequest->isRequestFromBrowser() === true) {
            $browserContainerIds = $HostControllerRequest->getBrowserContainerIdsByRequest();
            foreach ($browserContainerIds as $containerIdToCheck) {
                if (!in_array($containerIdToCheck, $this->MY_RIGHTS)) {
                    $this->render403();
                    return;
                }
            }

            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($browserContainerIds);

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
                $HostCondition->setContainerIds(array_merge($HostCondition->getContainerIds(), $recursiveContainerIds));
            }
        }

        //Default order
        $HostCondition->setOrder($HostControllerRequest->getOrder('Hoststatus.current_state', 'desc'));

        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Host->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if ($this->DbBackend->isCrateDb()) {
            $query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Host->getHostIndexQueryStatusengine3($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_hosts = $this->{$modelName}->find('all', $query);
            $this->set('all_hosts', $all_hosts);
            $this->set('_serialize', ['all_hosts']);
            return;
        } else {
            if($this->isScrollRequest()){
                $this->Paginator->settings['page'] = $HostFilter->getPage();
                $ScrollIndex = new ScrollIndex($this->Paginator, $this);
                $hosts = $this->{$modelName}->find('all', array_merge($this->Paginator->settings, $query));
                $ScrollIndex->determineHasNextPage($hosts);
                $ScrollIndex->scroll();
            }else{
                $this->Paginator->settings['page'] = $HostFilter->getPage();
                $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
                $hosts = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
            }
            //debug($this->Host->getDataSource()->getLog(false, false));
        }

        $all_hosts = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($hosts as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($host);
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($host['Hoststatus'], $UserTime);
            $PerfdataChecker = new HostPerfdataChecker($Host);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $SatelliteNames[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'       => $Host->toArray(),
                'Hoststatus' => $Hoststatus->toArray()
            ];
            $tmpRecord['Host']['has_graphs'] = $PerfdataChecker->hasRrdFolder();
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;

            $all_hosts[] = $tmpRecord;
        }

        $this->set('all_hosts', $all_hosts);

        $toJson = ['all_hosts', 'paging'];
        if($this->isScrollRequest()){
            $toJson = ['all_hosts', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function icon() {
        $this->layout = 'blank';
        //Only ship HTML Template
        return;
    }

    public function hostservicelist() {
        $this->layout = 'blank';
        //Only ship HTML Template
        return;
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }
        $host = $this->Host->findById($id);
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->wildcard();
        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $host = Hash::merge($host, $hoststatus);

        $this->set('host', $host);
        $this->set('_serialize', ['host']);
    }

    public function notMonitored() {
        $this->layout = 'angularjs';

        $masterInstanceName = $this->Systemsetting->getMasterInstanceName();
        $SatelliteNames = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $SatelliteModel = $ModuleManager->loadModel('Satellite');
            $SatelliteNames = $SatelliteModel->find('list');
            $SatelliteNames[0] = $masterInstanceName;
        }

        $User = new User($this->Auth);
        if (!$this->isApiRequest()) {
            //Only ship HTML template

            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        $HostFilter = new HostFilter($this->request);
        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $HostCondition->setIncludeDisabled(false);
        $HostCondition->setContainerIds($this->MY_RIGHTS);

        $HostCondition->setOrder($HostControllerRequest->getOrder('Host.name', 'asc'));


        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Host->getHostNotMonitoredQuery($HostCondition, $HostFilter->notMonitoredFilter());
            $modelName = 'Host';
        }

        if ($this->DbBackend->isCrateDb()) {
            $this->loadModel('CrateModule.CrateHost');
            $query = $this->CrateHost->getHostNotMonitoredQuery($HostCondition, $HostFilter->notMonitoredFilter());
            $this->CrateHost->alias = 'Host';
            $modelName = 'CrateHost';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Host->getHostNotMonitoredQuery($HostCondition, $HostFilter->notMonitoredFilter());
            $modelName = 'Host';
        }


        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $all_hosts = $this->{$modelName}->find('all', $query);
            $this->set('all_hosts', $all_hosts);
            $this->set('_serialize', ['all_hosts']);
            return;
        } else {
            $this->Paginator->settings['page'] = $HostFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $hosts = $this->Paginator->paginate($modelName, [], [key($this->Paginator->settings['order'])]);
        }

        $all_hosts = [];
        foreach ($hosts as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($host);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $SatelliteNames[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'       => $Host->toArray(),
                'Hoststatus' => [
                    'isInMonitoring' => false,
                    'currentState'   => -1
                ]
            ];
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_hosts[] = $tmpRecord;
        }


        $this->set('all_hosts', $all_hosts);
        $this->set('_serialize', ['all_hosts', 'paging']);

    }

    public function edit($id = null) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        $this->set('MY_WRITABLE_CONTAINERS', $this->getWriteContainers());
        $userId = $this->Auth->user('id');

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $_host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
            ],
            'fields'     => [
                'Host.container_id',
                'Container.*',
            ],
        ]);

        $containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $_host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }
        $host = $this->Host->prepareForView($id);
        $host_for_changelog = $host;
        $this->set('back_url', $this->referer());
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));
        $this->Frontend->setJson('dns_hostname_lookup_failed', __('Could not resolve hostname'));
        $this->Frontend->setJson('dns_ipaddress_lookup_failed', __('Could not reverse lookup your ip address'));
        $this->Frontend->setJson('hostname_placeholder', __('Will be auto detected if you enter a ip address'));
        $this->Frontend->setJson('address_placeholder', __('Will be auto detected if you enter a FQDN'));
        $this->Frontend->setJson('hostId', $id);

        // Checking if the user hit submit and a validation error happens, to refill input fields
        $Customvariable = [];
        $customFieldsToRefill = [
            'Host'    => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
                'priority',
                'active_checks_enabled',
            ],
            'Contact' => [
                'Contact',
            ],
            //	'Contactgroup' => [
            //		'Contactgroup'
            //	]
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
        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        $this->loadModel('Timeperiod');
        $this->loadModel('Command');
        $this->loadModel('Contact');
        $this->loadModel('Contactgroup');
        $this->loadModel('Container');
        $this->loadModel('Customvariable');
        $this->loadModel('Hosttemplate');
        $this->loadModel('Hostgroup');
        $this->loadModel('Commandargument');
        $this->loadModel('Hostcommandargumentvalue');

        // Data required for changelog
        $contacts = $this->Contact->find('list');
        $hosts = $this->Host->find('list');
        $contactgroups = $this->Contactgroup->findList();
        $timeperiods = $this->Timeperiod->find('list');
        $commands = $this->Command->hostCommands('list');
        $hosttemplates = $this->Hosttemplate->find('list');
        $hostgroups = $this->Hostgroup->findList([
            'recursive' => -1,
            'contain'   => [
                'Container',
            ],
        ], 'id');
        // End changelog

        // Data to refill form
        if ($this->request->is('post') || $this->request->is('put')) {
            $containerId = $this->request->data('Host.container_id');
        } else {
            $containerId = $host['Host']['container_id'];
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

        $_hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list', $host['Host']['host_type']);
        $_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        $_parenthosts = $this->Host->hostsByContainerIdExcludeHostId($containerIds, 'list', $id);
        $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');

        $this->set(compact(['_hosttemplates', '_hostgroups', '_parenthosts', '_timeperiods', '_contacts', '_contactgroups', 'id']));
        // End form refill

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        //Fehlende bzw. neu angelegte CommandArgummente ermitteln und anzeigen
        $commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $host['Host']['command_id'],
            ],
        ]);

        $contacts_for_changelog = [];
        foreach ($host['Contact'] as $contact_id) {
            $contacts_for_changelog[] = [
                'id'   => $contact_id,
                'name' => $contacts[$contact_id],
            ];
        }
        $contactgroups_for_changelog = [];
        foreach ($host['Contactgroup'] as $contactgroup_id) {
            if (isset($contactgroups[$contactgroup_id])) {
                $contactgroups_for_changelog[] = [
                    'id'   => $contactgroup_id,
                    'name' => $contactgroups[$contactgroup_id],
                ];
            }
        }
        $hostgroups_for_changelog = [];
        foreach ($host['Hostgroup'] as $hostgroup_id) {
            if (isset($hostgroups[$hostgroup_id])) {
                $hostgroups_for_changelog[] = [
                    'id'   => $hostgroup_id,
                    'name' => $hostgroups[$hostgroup_id],
                ];
            }
        }
        $parenthosts_for_changelog = [];
        foreach ($host['Parenthost'] as $parenthost_id) {
            $parenthosts_for_changelog[] = [
                'id'   => $parenthost_id,
                'name' => $hosts[$parenthost_id],
            ];
        }
        $host_for_changelog['Contact'] = $contacts_for_changelog;
        $host_for_changelog['Contactgroup'] = $contactgroups_for_changelog;
        $host_for_changelog['Hostgroup'] = $hostgroups_for_changelog;
        $host_for_changelog['Parenthost'] = $parenthosts_for_changelog;

        $masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];

        $ContactsInherited = $this->__inheritContactsAndContactgroups($host);
        $this->Frontend->setJson('ContactsInherited', $ContactsInherited);

        $this->set('back_url', $this->referer());

        //get sharing containers
        $sharingContainers = $this->getSharingContainers($host['Host']['container_id'], false);
        //get the already shared containers
        if (is_array($host['Container']) && !empty($host['Container'])) {
            $sharedContainers = array_diff($host['Container'], [$host['Host']['container_id']]);
        } else {
            $sharedContainers = [];
        }
        $this->set(compact([
            'host',
            '_host',
            'containers',
            'timeperiods',
            'commands',
            'contactgroups',
            'contacts',
            'userContainerId',
            'userValues',
            'Customvariable',
            'hosttemplates',
            'hosts',
            'hostgroups',
            'commandarguments',
            'masterInstance',
            'ContactsInherited',
            'sharedContainers',
            'sharingContainers',
        ]));
        if ($this->request->is('post') || $this->request->is('put')) {
            $ext_data_for_changelog = [
                'Contact'      => [
                    'Contact' => [],
                ],
                'Contactgroup' => [
                    'Contactgroup' => [],
                ],
                'Hostgroup'    => [],
                'Parenthost'   => [],
            ];
            if ($this->request->data('Host.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Host']['Contact'],
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
            if ($this->request->data('Host.Contactgroup')) {
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
                        'Contactgroup.id' => $this->request->data['Host']['Contactgroup'],
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
            if ($this->request->data('Host.Hostgroup')) {
                if ($hostgroupsForChangelog = $this->Hostgroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Hostgroup.id',
                    ],
                    'conditions' => [
                        'Hostgroup.id' => $this->request->data['Host']['Hostgroup'],
                    ],
                ])
                ) {
                    foreach ($hostgroupsForChangelog as $hostgroupData) {
                        $ext_data_for_changelog['Hostgroup'][] = [
                            'id'   => $hostgroupData['Hostgroup']['id'],
                            'name' => $hostgroupData['Container']['name'],
                        ];
                    }
                    unset($hostgroupsForChangelog);
                }
            }
            if ($this->request->data('Host.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Host']['notify_period_id'],
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
            if ($this->request->data('Host.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Host']['check_period_id'],
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
            if ($this->request->data('Host.hosttemplate_id')) {
                if ($hosttemplatesForChangelog = $this->Hosttemplate->find('list', [
                    'conditions' => [
                        'Hosttemplate.id' => $this->request->data['Host']['hosttemplate_id'],
                    ],
                ])
                ) {
                    foreach ($hosttemplatesForChangelog as $hosttemplateId => $hosttemplateName) {
                        $ext_data_for_changelog['Hosttemplate'] = [
                            'id'   => $hosttemplateId,
                            'name' => $hosttemplateName,
                        ];
                    }
                    unset($hosttemplatesForChangelog);
                }
            }
            if ($this->request->data('Host.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Host']['command_id'],
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
            if ($this->request->data('Host.Parenthost')) {
                if ($hostsForChangelog = $this->Host->find('list', [
                    'conditions' => [
                        'Host.id' => $this->request->data['Host']['Parenthost'],
                    ],
                ])
                ) {
                    foreach ($hostsForChangelog as $hostId => $hostName) {
                        $ext_data_for_changelog['Parenthost'][] = [
                            'id'   => $hostId,
                            'name' => $hostName,
                        ];
                    }
                    unset($hostsForChangelog);
                }
            }

            $this->Host->id = $id;
            $this->request->data['Contact']['Contact'] = $this->request->data('Host.Contact');
            $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data('Host.Contactgroup');
            $this->request->data['Parenthost']['Parenthost'] = $this->request->data['Host']['Parenthost'];
            $this->request->data['Hostgroup']['Hostgroup'] = (is_array($this->request->data['Host']['Hostgroup'])) ? $this->request->data['Host']['Hostgroup'] : [];
            $hosttemplate = [];
            if (isset($this->request->data['Host']['hosttemplate_id']) && $this->Hosttemplate->exists($this->request->data['Host']['hosttemplate_id'])) {
                $hosttemplate = $this->Hosttemplate->findById($this->request->data['Host']['hosttemplate_id']);
            }
            $data_to_save = $this->Host->prepareForSave($this->_diffWithTemplate($this->request->data, $hosttemplate),
                $this->request->data, 'edit');
            $data_to_save['Host']['own_customvariables'] = 0;
            //Add Customvariables data to $data_to_save
            $data_to_save['Customvariable'] = [];
            if (isset($this->request->data['Customvariable'])) {
                $customVariableDiffer = new CustomVariableDiffer($this->request->data['Customvariable'], $hosttemplate['Customvariable']);
                $customVariablesToSaveRepository = $customVariableDiffer->getCustomVariablesToSaveAsRepository();
                $data_to_save['Customvariable'] = $customVariablesToSaveRepository->getAllCustomVariablesAsArray();
                if (!empty($data_to_save)) {
                    $data_to_save['Host']['own_customvariables'] = 1;
                }
            }
            $this->Host->set($data_to_save);
            if ($this->Host->validates()) {
                //Delete old command argument values
                $this->Hostcommandargumentvalue->deleteAll([
                    'host_id' => $host['Host']['id'],
                ]);

                $this->Customvariable->deleteAll([
                    'object_id'     => $host['Host']['id'],
                    'objecttype_id' => OBJECT_HOST,
                ], false);

            }

            if (CakePlugin::loaded('MaximoModule')) {
                if (!empty($this->request->data['Maximoconfiguration'])) {
                    $data_to_save['Maximoconfiguration'] = $this->request->data['Maximoconfiguration'];
                }
            }

            if ($this->Host->saveAll($data_to_save)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_HOST,
                    $this->request->data('Host.container_id'),
                    $userId,
                    $this->request->data['Host']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $host_for_changelog
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/hosts/edit/%s">Host</a> modified successfully', $host['Host']['id']));
                $this->loadModel('Tenant');
                //$this->Tenant->hostCounter($this->request->data['Host']['container_id'], '+');
                $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);
            } else {
                $this->setFlash(__('Data could not be saved'), false);
            }
        }
    }


    public function sharing($id = null) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        $userId = $this->Auth->user('id');

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
            ],
        ]);
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);

        $sharingContainers = array_diff_key($containers, [$host['Host']['container_id'] => $host['Host']['container_id']]);
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container']['Container'][] = $this->request->data['Host']['container_id'];
            if ($this->Host->saveAll(Hash::merge($this->request->data, $host))) {
                if ($this->request->ext == 'json') {
                    $this->serializeId(); // REST API ID serialization
                } else {
                    $this->setFlash(__('Host modified successfully'));
                    $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
                    $this->redirect($redirect);
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                } else {
                    $this->setFlash(__('Data could not be saved'), false);
                }
            }
        }
        $this->set(compact(['host', 'containers', 'sharingContainers']));
    }

    public function edit_details($host_id = null) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        $this->set('back_url', $this->referer());
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list', 'id');
        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list', 'id');

        //get sharing containers
        $sharingContainers = $this->getSharingContainers(null, false);

        if ($this->request->is('post') || $this->request->is('put')) {
            foreach (func_get_args() as $host_id) {
                $this->Host->unbindModel([
                        'hasMany'             => ['Hostcommandargumentvalue', 'HostescalationHostMembership', 'HostdependencyHostMembership', 'Service', 'Customvariable'],
                        'hasAndBelongsToMany' => ['Parenthost', 'Hostgroup'],
                        'belongsTo'           => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand'],
                    ]
                );
                $data = ['Host' => []];
                $host = $this->Host->findById($host_id);
                if (!empty($host)) {
                    //Fill up required fields
                    $data['Host']['id'] = $host['Host']['id'];
                    $data['Host']['container_id'] = $host['Host']['container_id'];
                    $data['Host']['name'] = $host['Host']['name'];
                    $data['Host']['hosttemplate_id'] = $host['Host']['hosttemplate_id'];
                    $data['Host']['address'] = $host['Host']['address'];

                    $hostSharingPermissions = new HostSharingPermissions(
                        $host['Host']['container_id'],
                        $this->hasRootPrivileges,
                        Hash::extract($host['Container'], '{n}.id'),
                        $this->MY_RIGHTS
                    );
                    $allowSharing = $hostSharingPermissions->allowSharing();

                    if ($allowSharing) {
                        if ($this->request->data('Host.edit_sharing') == 1) {
                            if (!empty($this->request->data('Host.shared_container'))) {
                                if ($this->request->data('Host.keep_sharing') == 1) {
                                    $sharedContainer = Hash::extract($host, 'Container.{n}.id');
                                    $containers = array_merge($sharedContainer, $this->request->data('Host.shared_container'));
                                    $data['Container']['Container'] = $containers;
                                } else {
                                    $containers = array_merge([$host['Host']['container_id']], $this->request->data('Host.shared_container'));
                                    $data['Container']['Container'] = $containers;
                                }

                            }
                        }

                    }

                    if ($this->request->data('Host.edit_description') == 1) {
                        $data['Host']['description'] = $this->request->data('Host.description');
                    }

                    if ($this->request->data('Host.edit_contacts') == 1) {
                        $_contacts = [];
                        if ($this->request->data('Host.keep_contacts') == 1) {
                            if (!empty($host['Contact'])) {
                                //Merge exsting contacts with new contacts
                                $_contacts = Hash::extract($host['Contact'], '{n}.id');
                                $_contacts = Hash::merge($_contacts, $this->request->data('Host.Contact'));
                                $_contacts = array_unique($_contacts);
                            } else {
                                // There are no old contacts to overwirte, wo we take the current request data
                                $_contacts = $this->request->data('Host.Contact');
                            }
                        } else {
                            ////Overwrite all old contacts
                            $_contacts = $this->request->data('Host.Contact');
                        }
                        $data['Host']['Contact'] = $_contacts;
                        $data['Contact'] = [
                            'Contact' => $_contacts,
                        ];
                    }

                    if ($this->request->data('Host.edit_contactgroups') == 1) {
                        $_contactgroups = [];
                        if ($this->request->data('Host.keep_contactgroups') == 1) {
                            if (!empty($host['Contactgroup'])) {
                                //Merge existing contactgroups to new contact groups
                                $_contactgroups = Hash::extract($host['Contactgroup'], '{n}.id');
                                $_contactgroups = Hash::merge($_contactgroups, $this->request->data('Host.Contactgroup'));
                                $_contactgroups = array_unique($_contactgroups);
                            } else {
                                // There are no old contact groups to overwirte, wo we take the current request data
                                $_contactgroups = $this->request->data('Host.Contactgroup');
                            }
                        } else {
                            //Overwrite all old contact groups
                            $_contactgroups = $this->request->data('Host.Contactgroup');
                        }
                        $data['Host']['Contactgroup'] = $_contactgroups;
                        $data['Contactgroup'] = [
                            'Contactgroup' => $_contactgroups,
                        ];
                    }

                    if (!empty($data['Host']['Contact']) || !empty($data['Host']['Contactgroup'])) {
                        //Welcome to nagios 4 -.-
                        $data['Host']['own_contacts'] = 1;
                        $data['Host']['own_contactgroups'] = 1;
                    } else {
                        if (isset($_contacts) || isset($_contactgroups)) {
                            // Only if the user has submit a contact or a contact group, may be he want to delet all contacts.
                            $data['Host']['own_contacts'] = 0;
                            $data['Host']['own_contactgroups'] = 0;
                            $data['Host']['Contact'] = [];
                            $data['Host']['Contactgroup'] = [];
                        }
                    }

                    if ($this->request->data('Host.edit_url') == 1) {
                        $data['Host']['host_url'] = $this->request->data('Host.host_url');
                    }

                    if ($this->request->data('Host.edit_tags') == 1) {
                        $data['Host']['tags'] = $this->request->data('Host.tags');
                        if ($this->request->data('Host.keep_tags') == 1) {
                            if (!empty($host['Host']['tags'])) {
                                //Host has tags, lets merge this
                                $data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Host']['tags']), explode(',', $data['Host']['tags']))));
                            } else {
                                if (!empty($host['Hosttemplate']['tags'])) {
                                    //The host has no own tags, lets merge from hosttemplate
                                    $data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Hosttemplate']['tags']), explode(',', $data['Host']['tags']))));
                                }
                            }
                        }
                    }

                    if ($this->request->data('Host.edit_priority') == 1) {
                        $data['Host']['priority'] = $this->request->data('Host.priority');
                    }
                    $this->Host->save($data);
                    unset($data);
                }
            }
            $this->setFlash(__('Host modified successfully'));
            $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
            $this->redirect($redirect);

            return;
        }

        $this->set(compact(['contacts', 'contactgroups', 'sharingContainers']));
    }

    public function add() {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        //Empty variables, get field if Model::save() fails for refill
        $_hosttemplates = [];
        $_hostgroups = [];
        $_parenthosts = [];
        $_timeperiods = [];
        $_contacts = [];
        $_contactgroups = [];


        $userId = $this->Auth->user('id');
        $this->set('back_url', $this->referer());
        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));
        $this->Frontend->setJson('dns_hostname_lookup_failed', __('Could not resolve hostname'));
        $this->Frontend->setJson('dns_ipaddress_lookup_failed', __('Could not reverse lookup your ip address'));
        $this->Frontend->setJson('hostname_placeholder', __('Will be auto detected if you enter a ip address'));
        $this->Frontend->setJson('address_placeholder', __('Will be auto detected if you enter a FQDN'));


        // Checking if the user hit submit and a validation error happens, to refill input fields
        $Customvariable = [];
        $customFieldsToRefill = [
            'Host'    => [
                'notification_interval',
                'notify_on_recovery',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_flapping',
                'notify_on_downtime',
                'check_interval',
                'retry_interval',
                'flap_detection_enabled',
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
                'priority',
            ],
            'Contact' => [
                'Contact',
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

        //Fix that we dont lose any unsaved host macros, because of vaildation error
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        $this->loadModel('Timeperiod');

        $this->loadModel('Command');
        $this->loadModel('Contact');
        $this->loadModel('Contactgroup');
        $this->loadModel('Container');
        $this->loadModel('Customvariable');
        $this->loadModel('Hosttemplate');
        $this->loadModel('Hostgroup');

        $commands = $this->Command->hostCommands('list');

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        $masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];

        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            $ext_data_for_changelog = [];
            if ($this->request->data('Host.Contact')) {
                if ($contactsForChangelog = $this->Contact->find('list', [
                    'conditions' => [
                        'Contact.id' => $this->request->data['Host']['Contact'],
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
            if ($this->request->data('Host.Contactgroup')) {
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
                        'Contactgroup.id' => $this->request->data['Host']['Contactgroup'],
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
            if ($this->request->data('Host.Hostgroup')) {
                if ($hostgroupsForChangelog = $this->Hostgroup->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'fields'     => [
                        'Hostgroup.id',
                    ],
                    'conditions' => [
                        'Hostgroup.id' => $this->request->data['Host']['Hostgroup'],
                    ],
                ])
                ) {
                    foreach ($hostgroupsForChangelog as $hostgroupData) {
                        $ext_data_for_changelog['Hostgroup'][] = [
                            'id'   => $hostgroupData['Hostgroup']['id'],
                            'name' => $hostgroupData['Container']['name'],
                        ];
                    }
                    unset($hostgroupsForChangelog);
                }
            }
            if ($this->request->data('Host.notify_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Host']['notify_period_id'],
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
            if ($this->request->data('Host.check_period_id')) {
                if ($timeperiodsForChangelog = $this->Timeperiod->find('list', [
                    'conditions' => [
                        'Timeperiod.id' => $this->request->data['Host']['check_period_id'],
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
            if ($this->request->data('Host.hosttemplate_id')) {
                if ($hosttemplatesForChangelog = $this->Hosttemplate->find('list', [
                    'conditions' => [
                        'Hosttemplate.id' => $this->request->data['Host']['hosttemplate_id'],
                    ],
                ])
                ) {
                    foreach ($hosttemplatesForChangelog as $hosttemplateId => $hosttemplateName) {
                        $ext_data_for_changelog['Hosttemplate'] = [
                            'id'   => $hosttemplateId,
                            'name' => $hosttemplateName,
                        ];
                    }
                    unset($hosttemplatesForChangelog);
                }
            }
            if ($this->request->data('Host.command_id')) {
                if ($commandsForChangelog = $this->Command->find('list', [
                    'conditions' => [
                        'Command.id' => $this->request->data['Host']['command_id'],
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
            if ($this->request->data('Host.Parenthost')) {
                if ($hostsForChangelog = $this->Host->find('list', [
                    'conditions' => [
                        'Host.id' => $this->request->data['Host']['Parenthost'],
                    ],
                ])
                ) {
                    foreach ($hostsForChangelog as $hostId => $hostName) {
                        $ext_data_for_changelog['Parenthost'][] = [
                            'id'   => $hostId,
                            'name' => $hostName,
                        ];
                    }
                    unset($hostsForChangelog);
                }
            }

            if (isset($this->request->data['Host']['Contact'])) {
                $this->request->data['Contact']['Contact'] = $this->request->data['Host']['Contact'];
            } else {
                $this->request->data['Host']['Contact'] = [];
            }

            if (isset($this->request->data['Host']['Contactgroup'])) {
                $this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Host']['Contactgroup'];
            }

            if (!isset($this->request->data['Host']['Parenthost'])) {
                $this->request->data['Host']['Parenthost'] = [];
            }
            if (is_array($this->request->data['Host']['Parenthost'])) {
                $this->request->data['Parenthost']['Parenthost'] = $this->request->data['Host']['Parenthost'];
            } else {
                $this->request->data['Parenthost']['Parenthost'] = [];
            }

            if (isset($this->request->data['Host']['Hostgroup']) && is_array($this->request->data['Host']['Hostgroup'])) {
                $this->request->data['Hostgroup']['Hostgroup'] = $this->request->data['Host']['Hostgroup'];
            } else {
                $this->request->data['Hostgroup']['Hostgroup'] = [];
            }

            $hosttemplate = [];
            if (isset($this->request->data['Host']['hosttemplate_id']) &&
                $this->Hosttemplate->exists($this->request->data['Host']['hosttemplate_id'])
            ) {
                $hosttemplate = $this->Hosttemplate->findById($this->request->data['Host']['hosttemplate_id']);
            }
            App::uses('UUID', 'Lib');

            $data_to_save = $this->Host->prepareForSave(
                $this->_diffWithTemplate($this->request->data, $hosttemplate),
                $this->request->data,
                'add'
            );
            $data_to_save['Host']['own_customvariables'] = 0;
            //Add Customvariables data to $data_to_save
            $data_to_save['Customvariable'] = [];
            if (isset($this->request->data['Customvariable'])) {
                $customVariableDiffer = new CustomVariableDiffer($this->request->data['Customvariable'], $hosttemplate['Customvariable']);
                $customVariablesToSaveRepository = $customVariableDiffer->getCustomVariablesToSaveAsRepository();
                $data_to_save['Customvariable'] = $customVariablesToSaveRepository->getAllCustomVariablesAsArray();
                if (!empty($data_to_save)) {
                    $data_to_save['Host']['own_customvariables'] = 1;
                }
            }

            if (CakePlugin::loaded('MaximoModule')) {
                if (!empty($this->request->data['Maximoconfiguration'])) {
                    $data_to_save['Maximoconfiguration'] = $this->request->data['Maximoconfiguration'];
                }

            }
            if ($this->Host->saveAll($data_to_save)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Host->id,
                    OBJECT_HOST,
                    $this->request->data('Host.container_id'),
                    $userId,
                    $this->request->data['Host']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeId(); // REST API ID serialization
                } else {
                    $this->setFlash(__('<a href="/hosts/edit/%s">Host</a> created successfully', $this->Host->id));
                    $this->loadModel('Tenant');
                    //$this->Tenant->hostCounter($this->request->data['Host']['container_id'], '+');
                    $this->redirect(['action' => 'notMonitored']);
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                } else {
                    $this->setFlash(__('Data could not be saved'), false);
                }
                //Refil data that was loaded by ajax due to selected container id
                if ($this->Container->exists($this->request->data('Host.container_id'))) {
                    $container_id = $this->request->data('Host.container_id');

                    $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);
                    $_hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list');
                    $_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
                    //$_parenthosts = $this->Host->hostsByContainerId($containerIds, 'list');
                    $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
                    $_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
                    $_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
                }

                $this->setFlash(__('Data could not be saved'), false);
            }
        }
        $sharingContainers = [];
        //Refil ajax stuff if set or not
        $this->set(compact(['_hosttemplates', '_hostgroups', '_timeperiods', '_contacts', '_contactgroups', 'commands', 'containers', 'masterInstance', 'Customvariable', 'sharingContainers']));
    }

    public function getSharingContainers($containerId = null, $jsonOutput = true) {
        if ($jsonOutput) {
            $this->autoRender = false;
        }
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $sharingContainers = array_diff_key($containers, [$containerId => $containerId]);

        if ($jsonOutput) {
            echo json_encode($sharingContainers);
        } else {
            return $sharingContainers;
        }
    }

    public function disabled() {
        $this->layout = 'angularjs';

        $masterInstanceName = $this->Systemsetting->getMasterInstanceName();
        $SatelliteNames = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $SatelliteModel = $ModuleManager->loadModel('Satellite');
            $SatelliteNames = $SatelliteModel->find('list');
            $SatelliteNames[0] = $masterInstanceName;
        }

        if (!$this->isApiRequest()) {
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        $HostFilter = new HostFilter($this->request);
        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $HostCondition->setIncludeDisabled(true);
        $HostCondition->setContainerIds($this->MY_RIGHTS);

        $HostCondition->setOrder($HostControllerRequest->getOrder('Host.name', 'asc'));
        $query = $this->Host->getHostDisabledQuery($HostCondition, $HostFilter->disabledFilter());


        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            if (isset($query['limit'])) {
                unset($query['limit']);
            }
            $disabledHosts = $this->Host->find('all', $query);
            $this->set(compact(['disabledHosts']));
            $this->set('_serialize', ['disabledHosts']);
            return;
        } else {
            $this->Paginator->settings['page'] = $HostFilter->getPage();
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $hosts = $this->Paginator->paginate();
        }

        $all_hosts = [];
        foreach ($hosts as $host) {
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($host);
            $Hosttemplate = new \itnovum\openITCOCKPIT\Core\Views\Hosttemplate($host);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $SatelliteNames[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'         => $Host->toArray(),
                'Hosttemplate' => $Hosttemplate->toArray(),
                'Hoststatus'   => [
                    'isInMonitoring' => false,
                    'currentState'   => -1
                ]
            ];
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_hosts[] = $tmpRecord;
        }


        $this->set('all_hosts', $all_hosts);
        $this->set('_serialize', ['all_hosts', 'paging']);
    }

    public function deactivate($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Host->id = $id;
        if ($this->Host->saveField('disabled', 1)) {
            $this->Service->updateAll(['Service.disabled' => 1], ['Service.host_id' => $id]);
            $this->set('success', true);
            $this->set('message', __('Host successfully disabled'));
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while disabling host'));
        $this->set('_serialize', ['success', 'id', 'message']);
    }


    public function enable($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Host->id = $id;
        if ($this->Host->saveField('disabled', 0)) {
            $this->Service->updateAll(['Service.disabled' => 0], ['Service.host_id' => $id]);
            $this->set('success', true);
            $this->set('message', __('Host successfully enabled'));
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while enabling host'));
        $this->set('_serialize', ['success', 'id', 'message']);
    }

    public function delete($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $host = $this->Host->findById($id);
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        $modules = $this->Constants->defines['modules'];

        $usedBy = $this->Host->isUsedByModules($host, $modules);
        if (empty($usedBy['host']) && empty($usedBy['service'])) {
            //Not used by any module
            if ($this->Host->__delete($host, $this->Auth->user('id'))) {
                $this->set('success', true);
                $this->set('message', __('Host successfully deleted'));
                $this->set('_serialize', ['success']);
                return;
            }
        }

        //both types must be host, otherwise the serviceUsedBy site with the host id will be displayed wich results in an error
        $usedBy = Hash::merge(
            $this->getUsedByForFrontend($usedBy['host'], 'host'),
            $this->getUsedByForFrontend($usedBy['service'], 'host')
        );

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while deleting host'));
        $this->set('usedBy', $usedBy);
        $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
    }

    public function copy($id = null) {
        $userId = $this->Auth->user('id');
        $validationErrors = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            $validationError = false;
            $dataToSaveArray = [];
            $this->loadModel('Hosttemplate');
            App::uses('UUID', 'Lib');
            //We want to save/validate the data and save it
            foreach ($this->request->data['Host'] as $key => $host2copy) {
                if (!$this->Host->exists($host2copy)) {
                    continue;
                }
                $sourceHost = $this->Host->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Host.name',
                        'Host.hosttemplate_id',
                        'Host.container_id',
                        'Host.check_period_id',
                        'Host.notify_period_id',
                        'Host.description',
                        'Host.command_id',
                        'Host.check_interval',
                        'Host.retry_interval',
                        'Host.max_check_attempts',
                        'Host.notification_interval',
                        'Host.notifications_enabled',
                        'Host.notify_on_down',
                        'Host.notify_on_unreachable',
                        'Host.notify_on_recovery',
                        'Host.notify_on_flapping',
                        'Host.notify_on_downtime',
                        'Host.flap_detection_enabled',
                        'Host.flap_detection_on_up',
                        'Host.flap_detection_on_down',
                        'Host.flap_detection_on_unreachable',
                        'Host.process_performance_data',
                        'Host.freshness_checks_enabled',
                        'Host.freshness_threshold',
                        'Host.notes',
                        'Host.priority',
                        'Host.tags',
                        'Host.host_url',
                        'Host.host_type',
                        'Host.own_contacts',
                        'Host.own_contactgroups',
                        'Host.own_customvariables',
                        'Host.satellite_id',
                        'Host.disabled'
                    ],
                    'contain'    => [
                        'Parenthost'               => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'Container'                => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'CheckPeriod'              => [
                            'fields' => [
                                'CheckPeriod.id',
                                'CheckPeriod.name'
                            ]
                        ],
                        'NotifyPeriod'             => [
                            'fields' => [
                                'NotifyPeriod.id',
                                'NotifyPeriod.name'
                            ]
                        ],
                        'CheckCommand'             => [
                            'fields' => [
                                'CheckCommand.id',
                                'CheckCommand.name',
                            ]
                        ],
                        'Contact'                  => [
                            'fields' => [
                                'Contact.id',
                                'Contact.name'
                            ],
                        ],
                        'Contactgroup'             => [
                            'fields'    => [
                                'Contactgroup.id',
                            ],
                            'Container' => [
                                'fields' => [
                                    'Container.name'
                                ]
                            ]
                        ],
                        'Hostcommandargumentvalue' => [
                            'fields' => [
                                'commandargument_id',
                                'value',
                            ],
                        ],
                        'Customvariable'           => [
                            'fields' => [
                                'name',
                                'value',
                                'objecttype_id'
                            ],
                        ],
                        'Hostgroup'                => [
                            'fields'    => [
                                'Hostgroup.id',
                            ],
                            'Container' => [
                                'fields' => [
                                    'Container.name'
                                ]
                            ]
                        ],
                    ],
                    'conditions' => [
                        'Host.id' => $host2copy['source']
                    ],
                ]);

                $hosttemplate = $this->Hosttemplate->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Customvariable'                   => [
                            'fields' => [
                                'name',
                                'value',
                            ],
                        ],
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
                                'id',
                                'name',
                            ],
                        ],
                        'Contactgroup'                     => [
                            'fields'    => ['id'],
                            'Container' => [
                                'fields' => [
                                    'name',
                                ],
                            ],
                        ],
                        'Hostgroup'                        => [
                            'fields'    => ['id'],
                            'Container' => [
                                'fields' => [
                                    'name',
                                ],
                            ],
                        ],
                        'Hosttemplatecommandargumentvalue' => [
                            'fields' => [
                                'commandargument_id',
                                'value',
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Hosttemplate.id' => $sourceHost['Host']['hosttemplate_id']
                    ]
                ]);

                $sourceHost = Hash::remove($sourceHost, 'Host.id');
                $sourceHost = Hash::remove($sourceHost, '{s}.{n}.{s}.host_id');


                $contactIds = (!empty($sourceHost['Contact'])) ? Hash::extract($sourceHost['Contact'], '{n}.id') : [];
                $contactgroupIds = (!empty($sourceHost['Contactgroup'])) ? Hash::extract($sourceHost['Contactgroup'], '{n}.id') : [];
                $hostgroupIds = (!empty($sourceHost['Hostgroup'])) ? Hash::extract($sourceHost['Hostgroup'], '{n}.id') : [];
                $customVariables = (!empty($sourceHost['Customvariable']) && !is_null($sourceHost['Customvariable'])) ? Hash::remove($sourceHost['Customvariable'], '{n}.object_id') : [];
                $parentHostIds = (!empty($sourceHost['Parenthost'])) ? Hash::extract($sourceHost['Parenthost'], '{n}.id') : [];
                $containerIds = (!empty($sourceHost['Container'])) ? Hash::extract($sourceHost['Container'], '{n}.id') : [];
                $newHostData = [
                    'Host'                     => Hash::merge(
                        $sourceHost['Host'], [
                        'uuid'         => UUID::v4(),
                        'name'         => $host2copy['name'],
                        'description'  => $host2copy['description'],
                        'host_url'     => $host2copy['host_url'],
                        'address'      => $host2copy['address'],
                        'Contact'      => $contactIds,
                        'Contactgroup' => $contactgroupIds,
                        'Hostgroup'    => $hostgroupIds,
                    ]),
                    'Contact'                  => ['Contact' => $contactIds],
                    'Contactgroup'             => ['Contactgroup' => $contactgroupIds],
                    'Hostgroup'                => ['Hostgroup' => $hostgroupIds],
                    'Container'                => ['Container' => $containerIds],
                    'Customvariable'           => $customVariables,
                    'Hostcommandargumentvalue' => (!empty($sourceHost['Hostcommandargumentvalue'])) ? Hash::remove($sourceHost['Hostcommandargumentvalue'], '{n}.host_id') : [],
                    'Parenthost'               => ['Parenthost' => $parentHostIds]
                ];
                /* Data for Changelog Start*/
                $sourceHost['Customvariable'] = $customVariables;
                $hosttemplate['Customvariable'] = (!empty($sourceHost['Customvariable']) && !is_null($sourceHost['Customvariable'])) ? Hash::remove($sourceHost['Customvariable'], '{n}.object_id') : [];;


                if (!empty($sourceHost['Parenthost'])) {
                    $parenthosts = [];
                    foreach ($sourceHost['Parenthost'] as $parenthost) {
                        $parenthosts[] = [
                            'id'   => $parenthost['id'],
                            'name' => $parenthost['name']
                        ];
                    }
                    $sourceHost['Parenthost'] = $parenthosts;
                }
                if (!empty($sourceHost['Contactgroup'])) {
                    $contactgroups = [];
                    foreach ($sourceHost['Contactgroup'] as $contactgroup) {
                        $contactgroups[] = [
                            'id'   => $contactgroup['id'],
                            'name' => $contactgroup['Container']['name']
                        ];
                    }
                    $sourceHost['Contactgroup'] = $contactgroups;
                } else if (empty($sourceHost['Contactgroup']) && !empty($hosttemplate['Contactgroup'])) {
                    $contactgroups = [];
                    foreach ($hosttemplate['Contactgroup'] as $contactgroup) {
                        $contactgroups[] = [
                            'id'   => $contactgroup['id'],
                            'name' => $contactgroup['Container']['name']
                        ];
                    }
                    $hosttemplate['Contactgroup'] = $contactgroups;
                }

                if (!empty($sourceHost['Hostgroup'])) {
                    $hostgroups = [];
                    foreach ($sourceHost['Hostgroup'] as $hostgroup) {
                        $hostgroups[] = [
                            'id'   => $hostgroup['id'],
                            'name' => $hostgroup['Container']['name']
                        ];
                    }
                    $sourceHost['Hostgroup'] = $hostgroups;
                } else if (empty($sourceHost['Hostgroup']) && !empty($hosttemplate['Hostgroup'])) {
                    $hostgroups = [];
                    foreach ($hosttemplate['Hostgroup'] as $hostgroup) {
                        $hostgroups[] = [
                            'id'   => $hostgroup['id'],
                            'name' => $hostgroup['Container']['name']
                        ];
                    }
                    $hosttemplate['Hostgroup'] = $hostgroups;
                }
                /* Data for Changelog End*/
                $this->Host->set($newHostData);

                if ($this->Host->validates()) {
                    $dataToSaveArray[$host2copy['source']] = $newHostData;
                    $dataForChangeLog[$host2copy['source']] = [
                        'Host'         => $sourceHost,
                        'Hosttemplate' => $hosttemplate
                    ];
                } else {
                    $validationError = true;
                }
                if (!empty($this->Host->validationErrors)) {
                    $validationErrors['Host'][$key] = $this->Host->validationErrors;
                }
            }
            if ($validationError === false) {
                //All data is valid we can create the copy of the host
                $this->loadModel('Service');
                $this->loadModel('Servicetemplate');
                foreach ($dataToSaveArray as $sourceHostId => $data) {
                    $this->Host->create();
                    if ($this->Host->saveAll($data)) {
                        $hostDataAfterSave = $this->Host->dataForChangelogCopy($dataForChangeLog[$sourceHostId]['Host'], $dataForChangeLog[$sourceHostId]['Hosttemplate']);
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $this->params['action'],
                            $this->params['controller'],
                            $this->Host->id,
                            OBJECT_HOST,
                            $data['Host']['container_id'],
                            $userId,
                            $data['Host']['name'],
                            $hostDataAfterSave
                        );
                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }
                        $hostId = $this->Host->id;
                        $services = $this->Service->find('all', [
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
                                'Service.host_id'      => $sourceHostId,
                                'Service.service_type' => $this->Service->serviceTypes('copy'),
                            ],
                        ]);

                        //A Cache for servicetemplates to reduce the SQL querys
                        $servicetemplates = [];
                        foreach ($services as $service) {
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
                                'Service'                          => Hash::merge(
                                    $service['Service'], [
                                    'uuid'         => UUID::v4(),
                                    'host_id'      => $hostId,
                                    'Contact'      => $contactIds,
                                    'Contactgroup' => $contactgroupIds,
                                    'Servicegroup' => $servicegroupIds
                                ]),
                                'Contact'                          => ['Contact' => $contactIds],
                                'Contactgroup'                     => ['Contactgroup' => $contactgroupIds],
                                'Servicegroup'                     => ['Servicegroup' => $servicegroupIds],
                                'Customvariable'                   => $customVariables,
                                'Servicecommandargumentvalue'      => (!empty($service['Servicecommandargumentvalue'])) ? Hash::remove($service['Servicecommandargumentvalue'], '{n}.service_id') : [],
                                'Serviceeventcommandargumentvalue' => (!empty($service['Serviceeventcommandargumentvalue'])) ? Hash::remove($service['Serviceeventcommandargumentvalue'], '{n}.service_id') : [],
                            ];

                            /* Data for Changelog Start*/
                            $service['Host'] = ['id' => $hostId, 'name' => $data['Host']['name']];
                            $service['Customvariable'] = $customVariables;
                            $servicetemplate['Customvariable'] = (!empty($service['Customvariable']) && !is_null($service['Customvariable'])) ? Hash::remove($service['Customvariable'], '{n}.object_id') : [];;

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
                                    'services',
                                    $this->Service->id,
                                    OBJECT_SERVICE,
                                    $data['Host']['container_id'],
                                    $userId,
                                    $data['Host']['name'] . '/' . $serviceDataAfterSave['Service']['name'],
                                    $serviceDataAfterSave
                                );
                                if ($changelog_data) {
                                    CakeLog::write('log', serialize($changelog_data));
                                }
                            }
                        }
                    }
                }
                $this->setFlash(__('Host copied successfully'));
                $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);
            } else {
                if (isset($validationErrors['Host'])) {
                    $this->Host->validationErrors = $validationErrors['Host'];
                    $this->setFlash(__('Could not copy host/s'), false);
                    /*
                    For multiple "line" validation errors the array we gibe the view needs to look like this:
                    array(
                        (int) 0 => array(
                            'name' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            ),
                            'address' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            )
                        ),
                        (int) 1 => array(
                            'address' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            )
                        )
                    )
                    */
                }
            }
        }

        //We want to copy a host and display the view
        $hosts = [];
        foreach (func_get_args() as $host_id) {
            if ($this->Host->exists($host_id)) {
                $hosts[] = $this->Host->findById($host_id);
                //debug($host);
            }
        }
        $this->set(compact(['hosts']));
        $this->set('back_url', $this->referer());
    }


    public function browser($idOrUuid = null) {
        $this->layout = 'angularjs';

        $id = $idOrUuid;
        if (!is_numeric($idOrUuid)) {
            if (preg_match(UUID::regex(), $idOrUuid)) {
                $lookupHost = $this->Host->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Host.id'
                    ],
                    'conditions' => [
                        'Host.uuid' => $idOrUuid
                    ]
                ]);
                if (empty($lookupHost)) {
                    throw new NotFoundException(__('Invalid host'));
                }
                $this->redirect([
                    'controller' => 'hosts',
                    'action'     => 'browser',
                    $lookupHost['Host']['id']
                ]);
                return;
            }
        }
        unset($idOrUuid);

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $rawHost = $this->Host->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.container_id',
                'Host.host_type',
                'Host.host_url',
            ],
            'contain'    => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.host_url'
                    ]
                ]
            ],
            'conditions' => [
                'Host.id' => $id
            ]
        ]);

        if($rawHost['Host']['host_url'] === '' || $rawHost['Host']['host_url'] === null){
            $rawHost['Host']['host_url'] = $rawHost['Hosttemplate']['host_url'];
        }

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

            $grafanaDashboard = null;
            $GrafanaDashboardExists = false;
            $ModuleManager = new ModuleManager('GrafanaModule');
            if ($ModuleManager->moduleExists()) {
                $this->loadModel('GrafanaModule.GrafanaDashboard');
                $this->loadModel('GrafanaModule.GrafanaConfiguration');
                $grafanaConfiguration = $this->GrafanaConfiguration->find('first');
                if (!empty($grafanaConfiguration) && $this->GrafanaDashboard->existsForUuid($rawHost['Host']['uuid'])) {
                    $GrafanaDashboardExists = true;
                    $GrafanaConfiguration = \itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    $GrafanaConfiguration->setHostUuid($rawHost['Host']['uuid']);
                    $this->set('GrafanaConfiguration', $GrafanaConfiguration);
                }
            }
            $this->set('GrafanaDashboardExists', $GrafanaDashboardExists);

            $this->set('username', $User->getFullName());
            $this->set('host', $rawHost);
            $this->set('allowEdit', $allowEdit);
            $this->set('docuExists', $this->Documentation->existsForUuid($rawHost['Host']['uuid']));
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('masterInstanceName', $this->Systemsetting->getMasterInstanceName());
            //Only ship template
            return;
        }

        $hostQuery = $this->Host->getQueryForBrowser($id);
        $host = $this->Host->find('first', $hostQuery);


        $hosttemplateQuery = $this->Hosttemplate->getQueryForBrowser($host['Host']['hosttemplate_id']);
        $hosttemplate = $this->Hosttemplate->find('first', $hosttemplateQuery);


        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $HosttemplateMerger = new HosttemplateMerger($host, $hosttemplate);
        $mergedHost = [
            'Host'                        => $HosttemplateMerger->mergeHostWithTemplate(),
            'CheckPeriod'                 => $HosttemplateMerger->mergeCheckPeriod(),
            'NotifyPeriod'                => $HosttemplateMerger->mergeNotifyPeriod(),
            'CheckCommand'                => $HosttemplateMerger->mergeCheckCommand(),
            'Customvariable'              => $HosttemplateMerger->mergeCustomvariables(),
            'Hostcommandargumentvalue'    => $HosttemplateMerger->mergeCommandargumentsForReplace(),
            'Contactgroup'                => $HosttemplateMerger->mergeContactgroups(),
            'Contact'                     => $HosttemplateMerger->mergeContacts(),
            'areContactsFromHost'         => $HosttemplateMerger->areContactsFromHost(),
            'areContactsFromHosttemplate' => $HosttemplateMerger->areContactsFromHosttemplate(),
        ];

        $mergedHost['Host']['allowEdit'] = $allowEdit;
        $mergedHost['Host']['satelliteId'] = (int)$mergedHost['Host']['satellite_id'];
        $mergedHost['Host']['is_satellite_host'] = $mergedHost['Host']['satelliteId'] !== 0;
        $mergedHost['checkIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['check_interval']);
        $mergedHost['retryIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['retry_interval']);
        $mergedHost['notificationIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['notification_interval']);


        // Replace $HOSTNAME$
        $HostMacroReplacerCommandLine = new HostMacroReplacer($host);
        $hostCommandLine = $HostMacroReplacerCommandLine->replaceBasicMacros($mergedHost['CheckCommand']['command_line']);

        // Replace $_HOSTFOOBAR$
        $HostCustomMacroReplacerCommandLine = new CustomMacroReplacer($mergedHost['Customvariable'], OBJECT_HOST);
        $hostCommandLine = $HostCustomMacroReplacerCommandLine->replaceAllMacros($hostCommandLine);

        // Replace Command args $ARGx$
        $hostCommandLine = str_replace(
            array_keys($mergedHost['Hostcommandargumentvalue']),
            array_values($mergedHost['Hostcommandargumentvalue']),
            $hostCommandLine
        );

        $mergedHost['hostCommandLine'] = $hostCommandLine;

        //Check permissions for Contacts
        $contactsWithContainers = [];
        $writeContainers = $this->getWriteContainers();
        foreach ($mergedHost['Contact'] as $key => $contact) {
            $contactsWithContainers[$contact['id']] = [];
            foreach ($contact['Container'] as $container) {
                $contactsWithContainers[$contact['id']][] = $container['id'];
            }

            $mergedHost['Contact'][$key]['allowEdit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_contacts[$key]['allowEdit'] = false;
                if (!empty(array_intersect($contactsWithContainers[$contact['id']], $writeContainers))) {
                    $all_contacts[$key]['allowEdit'] = true;
                }
            }
        }

        //Check permissions for Contact groups
        foreach ($mergedHost['Contactgroup'] as $key => $contactgroup) {
            $mergedHost['Contactgroup'][$key]['allowEdit'] = $this->isWritableContainer($contactgroup['Container']['parent_id']);
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->wildcard();
        $HoststatusConditions = new HoststatusConditions($this->DbBackend);
        //$HoststatusConditions->hostsDownAndUnreachable();

        $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            //Empty host state for Hoststatus object
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus'], $UserTime);
        $hoststatus = $Hoststatus->toArrayForBrowser();
        $hoststatus['longOutputHtml'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($Hoststatus->getLongOutput(), true));


        $parenthosts = $host['Parenthost'];
        $ParentHoststatusFields = new HoststatusFields($this->DbBackend);
        $ParentHoststatusFields->currentState()->lastStateChange();
        $parentHostStatusRaw = $this->Hoststatus->byUuid(
            Hash::extract($host['Parenthost'], '{n}.uuid'),
            $ParentHoststatusFields,
            $HoststatusConditions
        );

        $parentHostStatus = [];
        foreach ($parentHostStatusRaw as $uuid => $parentHoststatus) {
            $ParentHoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($parentHoststatus['Hoststatus'], $UserTime);
            $parentHostStatus[$uuid] = $ParentHoststatus->toArrayForBrowser();
        }

        //Get Containers
        $mainContainer = $this->Tree->treePath($rawHost['Host']['container_id'], ['delimiter' => '/']);
        //Add shared containers
        $sharedContainers = [];
        foreach ($rawHost['Container'] as $container) {
            if (isset($container['id']) && $container['id'] != $rawHost['Host']['container_id']) {
                $sharedContainers[$container['id']] = $this->Tree->treePath($container['id'], ['delimiter' => '/']);
            }
        }

        $acknowledgement = [];
        if ($Hoststatus->isAcknowledged()) {
            $acknowledgement = $this->AcknowledgedHost->byHostUuid($host['Host']['uuid']);
            if(!empty($acknowledgement)) {
                $Acknowledgement = new AcknowledgementHost($acknowledgement['AcknowledgedHost'], $UserTime);
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
        if ($Hoststatus->isInDowntime()) {
            $downtime = $this->DowntimeHost->byHostUuid($host['Host']['uuid'], true);
            if(!empty($downtime)) {
                $Downtime = new \itnovum\openITCOCKPIT\Core\Views\Downtime($downtime['DowntimeHost'], $allowEdit, $UserTime);
                $downtime = $Downtime->toArray();
            }
        }

        $canSubmitExternalCommands = $this->hasPermission('externalcommands', 'hosts');

        $this->set('mergedHost', $mergedHost);
        $this->set('hoststatus', $hoststatus);
        $this->set('mainContainer', $mainContainer);
        $this->set('sharedContainers', $sharedContainers);
        $this->set('parenthosts', $parenthosts);
        $this->set('parentHostStatus', $parentHostStatus);
        $this->set('acknowledgement', $acknowledgement);
        $this->set('downtime', $downtime);
        $this->set('canSubmitExternalCommands', $canSubmitExternalCommands);
        $this->set('_serialize', [
            'mergedHost',
            'hoststatus',
            'mainContainer',
            'sharedContainers',
            'parenthosts',
            'parentHostStatus',
            'acknowledgement',
            'downtime',
            'canSubmitExternalCommands'
        ]);
    }

    /**
     * Converts BB code to HTML
     *
     * @param string $uuid The hosts UUID you want to get the long output
     * @param bool $parseBbcode If you want to convert BB Code to HTML
     * @param bool $nl2br If you want to replace \n with <br>
     *
     * @return string
     */
    public function longOutputByUuid($uuid = null, $parseBbcode = true, $nl2br = true) {
        $this->autoRender = false;
        $result = $this->Host->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.uuid'
            ],
            'conditions' => [
                'Host.uuid' => $uuid
            ]
        ]);
        if (!empty($result)) {
            $Hoststatusfields = new HoststatusFields($this->DbBackend);
            $Hoststatusfields->longOutput();
            $hoststatus = $this->Hoststatus->byUuid($result['Host']['uuid'], $Hoststatusfields);
            if (!empty($hoststatus)) {
                if ($parseBbcode === true) {
                    if ($nl2br === true) {
                        return $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($hoststatus['Hoststatus']['long_output'], $nl2br));
                    } else {
                        return $this->Bbcode->asHtml($hoststatus['Hoststatus']['long_output'], $nl2br);
                    }
                }

                return $hoststatus['Hoststatus']['long_output'];
            }
        }

        return '';
    }


    public function gethostbyname() {
        $this->autoRender = false;
        if ($this->request->is('ajax') && isset($this->request->data['hostname']) && $this->request->data['hostname'] != '') {
            $ip = gethostbyname($this->request->data['hostname']);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                echo $ip;

                return;
            }
        }
        echo '';
    }

    public function gethostbyaddr() {
        $this->autoRender = false;
        if ($this->request->is('ajax') && isset($this->request->data['address']) && filter_var($this->request->data['address'], FILTER_VALIDATE_IP)) {
            $fqdn = gethostbyaddr($this->request->data['address']);
            if (strlen($fqdn) > 0 && $fqdn != $this->request->data['address']) {
                echo $fqdn;

                return;
            }
        }
        echo '';
    }

    public function loadHosttemplate($hosttemplate_id = null) {
        $this->allowOnlyAjaxRequests();

        $this->loadModel('Hosttemplate');
        if (!$this->Hosttemplate->exists($hosttemplate_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $hosttemplate = $this->Hosttemplate->find(
            'first', [
                'conditions' => [
                    'Hosttemplate.id' => $hosttemplate_id,
                ],
                'contain'    => [
                    'Contactgroup' => 'Container',
                    'CheckCommand',
                    'Container',
                    'Customvariable',
                    'NotifyPeriod',
                    'Contact',
                    'Hosttemplatecommandargumentvalue',
                    'CheckPeriod',
                    'Hostgroup'    => 'Container'
                ],
            ]
        );

        $this->set(compact(['hosttemplate']));
        $this->set('_serialize', ['hosttemplate']);
    }

    public function addCustomMacro($counter) {
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_HOST);
        $this->set('counter', $counter);
    }

    public function loadTemplateMacros($hosttemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Hosttemplate');
        if (!$this->Hosttemplate->exists($hosttemplate_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        if ($this->Hosttemplate->exists($hosttemplate_id)) {
            $hosttemplate = $this->Hosttemplate->find('first', [
                'conditions' => [
                    'Hosttemplate.id' => $hosttemplate_id,
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
                    'Hosttemplate.id',
                ],
            ]);
        }
        $this->set('hosttemplate', $hosttemplate);
    }

    public function loadParametersByCommandId($command_id = null, $hosttemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        $test = [];
        $commandarguments = [];
        if ($command_id) {
            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
            //print_r($commandarguments);
            foreach ($commandarguments as $key => $commandargument) {
                if ($hosttemplate_id) {
                    $hosttemplate_command_argument_value = $this->Hosttemplatecommandargumentvalue->find('first', [
                        'conditions' => [
                            'Hosttemplatecommandargumentvalue.hosttemplate_id'    => $hosttemplate_id,
                            'Hosttemplatecommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id'],
                        ],
                        'fields'     => 'Hosttemplatecommandargumentvalue.value',
                    ]);
                    if (isset($hosttemplate_command_argument_value['Hosttemplatecommandargumentvalue']['value'])) {
                        $commandarguments[$key]['Hosttemplatecommandargumentvalue']['value'] = $hosttemplate_command_argument_value['Hosttemplatecommandargumentvalue']['value'];
                    }
                }
            }
        }

        $this->set(compact('commandarguments'));
    }

    public function loadArguments($command_id = null, $hosttemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Hosttemplate->exists($hosttemplate_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $commandarguments = [];

        $commandarguments = $this->Hosttemplatecommandargumentvalue->find('all', [
            'conditions' => [
                'Commandargument.command_id'                       => $command_id,
                'Hosttemplatecommandargumentvalue.hosttemplate_id' => $hosttemplate_id,
            ],
        ]);

        //Checking if the hosttemplade has own arguments defined
        if (empty($commandarguments)) {

            $commandarguments = $this->Commandargument->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Commandargument.command_id' => $command_id,
                ],
            ]);
        }

        $this->set('commandarguments', $commandarguments);
    }

    public function loadArgumentsAdd($command_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $commandarguments = [];
        $commandarguments = $this->Commandargument->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Commandargument.command_id' => $command_id,
            ],
        ]);

        $this->set('commandarguments', $commandarguments);
        $this->render('load_arguments');
    }

    public function loadHosttemplatesArguments($hosttemplate_id = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $this->loadModel('Hosttemplate');
        if (!$this->Hosttemplate->exists($hosttemplate_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $this->loadModel('Commandargument');
        $this->loadModel('Hosttemplatecommandargumentvalue');
        $commandarguments = $this->Hosttemplatecommandargumentvalue->find('all', [
            //	'recursive' => -1,
            'conditions' => [
                'hosttemplate_id' => $hosttemplate_id,
            ],
        ]);
        $commandarguments = Hash::remove($commandarguments, '{n}.Hosttemplatecommandargumentvalue.id');

        // Renaming Hosttemplatecommandargumentvalue to Hostcommandargumentvalue that we can render the view load_arguments with values
        $_commandarguments = [];
        foreach ($commandarguments as $commandargument) {
            $c = [];
            // Remove id of command argument value that if the user change them we dont overwrite the orginal data form host template in the database
            unset($commandargument['Hosttemplatecommandargumentvalue']['id']);
            $c['Hostcommandargumentvalue'] = $commandargument['Hosttemplatecommandargumentvalue'];
            $c['Commandargument'] = $commandargument['Commandargument'];
            $_commandarguments[] = $c;
        }
        $this->set('commandarguments', $_commandarguments);
        $this->render('load_arguments');
    }

    private function _diffWithTemplate($host, $hosttemplate) {
        $diff_array = [];
        //Host-/Hosttemplate fields
        $fields = [
            'description',
            'command_id',
            'check_interval',
            'retry_interval',
            'max_check_attempts',
            'notification_interval',
            'notify_on_down',
            'notify_on_unreachable',
            'notify_on_recovery',
            'notify_on_flapping',
            'notify_on_downtime',
            'flap_detection_enabled',
            'flap_detection_on_up',
            'flap_detection_on_down',
            'flap_detection_on_unreachable',
            'notes',
            'priority',
            'check_period_id',
            'notify_period_id',
            'tags',
            'active_checks_enabled',
            'host_url'
        ];
        $compare_array = [
            'Host'         => [
                ['Host.{(' . implode('|', array_values(Hash::merge($fields, ['uuid', 'name', 'description', 'address', 'satellite_id', 'host_type']))) . ')}', false],
                ['{^Contact$}.{^Contact$}.{n}', false],
                ['{^Contactgroup$}.{^Contactgroup$}.{n}', false],
                ['{^Hostgroup$}.{^Hostgroup$}.{n}', false],
                ['Hostcommandargumentvalue.{n}.{(commandargument_id|value|id)}', false],
            ],
            'Hosttemplate' => [
                ['Hosttemplate.{(' . implode('|', array_values($fields)) . ')}', false],
                ['{^Contact$}.{n}.id', true],
                ['{^Contactgroup$}.{n}.id', true],
                ['{^Hostgroup$}.{n}.id', true],
                ['Hosttemplatecommandargumentvalue.{n}.{(commandargument_id|value)}', false],
            ],
        ];
        $diff_array = [];
        foreach ($compare_array['Host'] as $key => $data) {
            $extractPath = $compare_array['Hosttemplate'][$key][0];
            if ($data[0] == 'Hostcommandargumentvalue.{n}.{(commandargument_id|value|id)}') {
                if (isset($host['Hostcommandargumentvalue'])) {
                    if (!empty(Hash::diff(Set::classicExtract($host, $data[0]), Set::classicExtract($hosttemplate, $compare_array['Hosttemplate'][$key][0])))) {
                        $diff_data = Set::classicExtract($host, $data[0]);
                        $diff_array['Hostcommandargumentvalue'] = $diff_data;
                    }
                }
            } else {
                //$Key for DiffArray with preg_replace ==>  from 'Customvariable.{n}.{(name|value)}'' to 'Customvariable'
                $possible_key = preg_replace('/(\{.*\})|(\.)/', '', $data[0]);
                $diff_data = $this->Host->getDiffAsArray($this->Host->prepareForCompare(Set::classicExtract($host, $data[0]), $data[1]),
                    $this->Host->prepareForCompare(Set::classicExtract($hosttemplate, $compare_array['Hosttemplate'][$key][0]),
                        $compare_array['Hosttemplate'][$key][1]));
                if (!empty($diff_data)) {
                    $diff_array = Hash::merge($diff_array, (!empty($possible_key)) ? [$possible_key => $diff_data] : $diff_data);
                }
            }
        }
        return $diff_array;
    }

    //This function return the controller name
    protected function controller() {
        return 'HostsController';
    }

    public function getHostByAjax($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        $host = $this->Host->findById($id);
        $this->set('host', $host);
        $this->set('_serialize', ['host']);
    }

    public function listToPdf() {
        $HostFilter = new HostFilter($this->request);

        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        if ($HostControllerRequest->isRequestFromBrowser() === false) {
            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($this->MY_RIGHTS);
        }

        $HostCondition->setOrder($HostControllerRequest->getOrder([
            'Host.name' => 'asc'
        ]));


        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Host->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if ($this->DbBackend->isCrateDb()) {
            $query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Host->getHostIndexQueryStatusengine3($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if (isset($query['limit'])) {
            unset($query['limit']);
        }
        $all_hosts = $this->{$modelName}->find('all', $query);

        $this->set('all_hosts', $all_hosts);

        $filename = 'Hosts_' . strtotime('now') . '.pdf';
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


    /*
     * $host is from prepareForView() but ther are no names in the service contact, only ids
     * $_host is from $this->Host->findById, because of contact names
     */
    protected function __inheritContactsAndContactgroups($host, $_host = []) {
        $diffExists = 0;
        if ($host['Host']['own_contacts'] == 0 && $host['Host']['own_contactgroups'] == 0) {
            $ContactsCombined = Hash::combine($host['Hosttemplate']['Contact'], '{n}.id', '{n}.id');
            $ContactgroupsCombined = Hash::combine($host['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.id');

            if (isset($this->request->data['Host']['Contact']) || isset($this->request->data['Host']['Contactgroup'])) {
                if (isset($this->request->data['Host']['Contact']) && is_array($this->request->data['Host']['Contact'])) {
                    $diffExists += sizeof(
                        array_merge(
                            array_diff($this->request->data['Host']['Contact'], $ContactsCombined),
                            array_diff($ContactsCombined, $this->request->data['Host']['Contact'])
                        )
                    );
                }
                if (isset($this->request->data['Host']['Contactgroup']) && is_array($this->request->data['Host']['Contactgroup'])) {
                    $diffExists += sizeof(
                        array_merge(
                            array_diff($this->request->data['Host']['Contactgroup'], $ContactgroupsCombined),
                            array_diff($ContactgroupsCombined, $this->request->data['Host']['Contactgroup'])
                        )
                    );
                }
            }
            if ($diffExists > 0) {
                return [
                    'inherit'      => false,
                    'source'       => 'Host',
                    'Contact'      => $this->request->data('Host.Contact'),
                    'Contactgroup' => $this->request->data('Host.Contactgroup'),
                ];

            }

            return [
                'inherit'      => true,
                'source'       => 'Hosttemplate',
                'Contact'      => Hash::combine($host['Hosttemplate']['Contact'], '{n}.id', '{n}.name'),
                'Contactgroup' => Hash::combine($host['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.Container.name'),
            ];
        }

        if (!empty($_host)) {
            return [
                'inherit'      => false,
                'source'       => 'Host',
                'Contact'      => Hash::combine($_host['Contact'], '{n}.id', '{n}.name'),
                'Contactgroup' => Hash::combine($_host['Contactgroup'], '{n}.id', '{n}.Container.name'),
            ];
        }

        $ContactsCombined = Hash::combine($host['Contact'], '{n}.id', '{n}.id');
        $ContactgroupsCombined = Hash::combine($host['Contactgroup'], '{n}.id', '{n}.id');

        if (isset($this->request->data['Host']['Contact']) || isset($this->request->data['Host']['Contactgroup'])) {
            if (isset($this->request->data['Host']['Contact']) && is_array($this->request->data['Host']['Contact'])) {
                $diffExists += sizeof(
                    array_merge(
                        array_diff($this->request->data['Host']['Contact'], $ContactsCombined),
                        array_diff($ContactsCombined, $this->request->data['Host']['Contact'])
                    )
                );
            }
            if (isset($this->request->data['Host']['Contactgroup']) && is_array($this->request->data['Host']['Contactgroup'])) {
                $diffExists += sizeof(
                    array_merge(
                        array_diff($this->request->data['Host']['Contactgroup'], $ContactgroupsCombined),
                        array_diff($ContactgroupsCombined, $this->request->data['Host']['Contactgroup'])
                    )
                );
            }
        }
        if ($diffExists > 0) {
            return [
                'inherit'      => false,
                'source'       => 'Host',
                'Contact'      => $this->request->data['Host']['Contact'],
                'Contactgroup' => $this->request->data['Host']['Contactgroup'],
            ];

        }

        return [
            'inherit'      => false,
            'source'       => 'Host',
            'Contact'      => Hash::combine($host['Contact'], '{n}.id', '{n}.name'),
            'Contactgroup' => Hash::combine($host['Contactgroup'], '{n}.id', '{n}.Container.name'),
        ];
    }

    public function ping() {
        //$this->allowOnlyAjaxRequests();
        $output = [];
        $address = $this->request->query('address');
        exec('ping ' . escapeshellarg($address) . ' -c 4 -W 5', $output);

        $this->set('output', $output);
        $this->set('_serialize', ['output']);
    }

    /**
     * Renders the ID of the host as JSON.
     *    Works if $this->request->data = array(
     *        'Host' => array(
     */
    public function addParentHosts() {
        $this->allowOnlyPostRequests();
        $data = $this->request->data;

        // CakePHP save/validation necessity
        if (!isset($data['Host']) || !is_array($data['Host'])) {
            $data['Host'] = [];
        }
        if (!isset($data['Parenthost']) || !is_array($data['Parenthost'])) {
            $data['Parenthost'] = [];
        }
        if (isset($data['Host']['Parenthost'])) {
            $data['Parenthost']['Parenthost'] = $data['Host']['Parenthost'];
        }
        if (isset($data['Parenthost']['Parenthost'])) {
            $data['Host']['Parenthost'] = $data['Parenthost']['Parenthost'];
        }

        if ($this->Host->save($data)) {
            $this->serializeId();
        } else {
            $this->serializeErrorMessage();
        }
    }


    public function loadElementsByContainerId($container_id = null, $host_id = 0) {
        $hosttemplate_type = GENERIC_HOST;
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Container->exists($container_id)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        if ($host_id != 0) {
            $host = $this->Host->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Host.id' => $host_id
                ]
            ]);
            if (!empty($host)) {
                $hosttemplate_type = $host['Host']['host_type'];
            }
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);

        $hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list', $hosttemplate_type);
        $hosttemplates = $this->Hosttemplate->chosenPlaceholder($hosttemplates);
        $hosttemplates = $this->Hosttemplate->makeItJavaScriptAble($hosttemplates);

        $hostgroups = $this->Host->makeItJavaScriptAble(
            $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id')
        );

        $parenthosts = $this->Host->hostsByContainerId($containerIds, 'list');
        if ($host_id != 0 && isset($parenthosts[$host_id])) {
            unset($parenthosts[$host_id]);
        }
        $parenthosts = $this->Host->makeItJavaScriptAble($parenthosts);

        $timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = $this->Host->makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contacts = $this->Host->makeItJavaScriptAble($contacts);

        $contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
        $contactgroups = $this->Host->makeItJavaScriptAble($contactgroups);

        $this->set(compact(['hosttemplates', 'hostgroups', 'parenthosts', 'timeperiods', 'checkperiods', 'contacts', 'contactgroups']));
        $this->set('_serialize', ['hosttemplates', 'hostgroups', 'parenthosts', 'timeperiods', 'checkperiods', 'contacts', 'contactgroups']);
    }

    //Only for ACLs
    public function checkcommand() {
        return null;
    }

    //Only for ACLs
    public function externalcommands() {
        return null;
    }

    public function allocateServiceTemplateGroup($host_id = 0) {

        //Form got submitted
        if (!empty($this->request->data)) {

            if (!$this->Servicetemplategroup->exists($this->request->data('Servicetemplategroup.id'))) {
                throw new NotFoundException(__('Invalid servicetemplategroup'));
            }

            $servicetemplateCache = [];
            //$this->loadModel('Host');
            if ($this->request->is('post') || $this->request->is('put')) {
                $userId = $this->Auth->user('id');
                //Checking if target host exists
                if ($this->Host->exists($host_id)) {
                    $this->loadModel('Service');
                    $this->loadModel('Servicetemplate');
                    $host = $this->Host->findById($host_id);
                    App::uses('UUID', 'Lib');
                    foreach ($this->request->data('Service.ServicesToAdd') as $servicetemplateIdToAdd) {
                        if (!isset($servicetemplateCache[$servicetemplateIdToAdd])) {
                            $servicetemplateCache[$servicetemplateIdToAdd] = $this->Servicetemplate->findById($servicetemplateIdToAdd);
                        }
                        $servicetemplate = $servicetemplateCache[$servicetemplateIdToAdd];
                        $service = [];
                        $service['Service']['uuid'] = UUID::v4();
                        $service['Service']['host_id'] = $host_id;
                        $service['Service']['servicetemplate_id'] = $servicetemplate['Servicetemplate']['id'];
                        $service['Host'] = $host;

                        $service['Service']['Contact'] = $servicetemplate['Contact'];
                        $service['Service']['Contactgroup'] = $servicetemplate['Contactgroup'];

                        $service['Contact']['Contact'] = [];
                        $service['Contactgroup']['Contactgroup'] = [];
                        $service['Servicegroup']['Servicegroup'] = [];

                        $service['Contact']['Contact'] = $service['Contact'];
                        $service['Contactgroup']['Contactgroup'] = $service['Contactgroup'];

                        $data_to_save = $this->Service->prepareForSave([], $service, 'add');
                        $this->Service->create();
                        if ($this->Service->saveAll($data_to_save)) {
                            $changelog_data = $this->Changelog->parseDataForChangelog(
                                'add',
                                'services',
                                $this->Service->id,
                                OBJECT_SERVICE,
                                $host['Host']['container_id'], // use host container_id for user permissions
                                $userId,
                                $host['Host']['name'] . '/' . $servicetemplate['Servicetemplate']['name'],
                                $service
                            );
                            if ($changelog_data) {
                                CakeLog::write('log', serialize($changelog_data));
                            }
                        }
                    }
                    $this->setFlash(__('Services created successfully'));
                    $this->redirect(['controller' => 'services', 'action' => 'serviceList', $host['Host']['id']]);
                } else {
                    $this->setFlash(__('Target host does not exist'), false);
                }
            }
        }
        $host = $this->Host->findById($host_id);
        $allServicetemplategroups = $this->Servicetemplategroup->find('all', [
            'fields' => ['Servicetemplategroup.id', 'Container.name'],
        ]);
        $serviceTemplateGroups = [];
        foreach ($allServicetemplategroups as $servicetemplategroup) {
            $serviceTemplateGroups[$servicetemplategroup['Servicetemplategroup']['id']] = $servicetemplategroup['Container']['name'];

        }

        $this->set('back_url', $this->referer());
        $this->set(compact([
            'host',
            'serviceTemplateGroups',
        ]));

    }

    public function getServiceTemplatesfromGroup($stg_id = 0) {
        if (!$this->Servicetemplategroup->exists($stg_id)) {
            throw new NotFoundException(__('Invalid Servicetemplategroup'));
        }
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        $hostid = $_REQUEST['host_id'];
        $host = $this->Host->findById($hostid);
        $servicetemplategroup = $this->Servicetemplategroup->findById($stg_id);
        $this->set(compact(['servicetemplategroup', 'host']));
        $this->set('_serialize', ['servicetemplategroup', 'host']);
    }

    public function ajaxList() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $HostFilter = new HostFilter($this->request);
        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($this->MY_RIGHTS);

        $hosts = $this->Host->makeItJavaScriptAble(
            $this->Host->getHostsForAngular($HostCondition, $selected)
        );

        $this->set(compact(['hosts']));
        $this->set('_serialize', ['hosts']);
    }

    public function loadHostsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');

        $HostFilter = new HostFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);

        $hosts = $this->Host->makeItJavaScriptAble(
            $this->Host->getHostsForAngular($HostCondition, $selected)
        );

        $this->set(compact(['hosts']));
        $this->set('_serialize', ['hosts']);
    }

    /**
     * @param bool $onlyHostsWithWritePermission
     */
    public function loadHostsByString($onlyHostsWithWritePermission = false) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $HostFilter = new HostFilter($this->request);


        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
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

        $hosts = $this->Host->makeItJavaScriptAble(
            $this->Host->getHostsForAngular($HostCondition, $selected)
        );

        $this->set(compact(['hosts']));
        $this->set('_serialize', ['hosts']);
    }


    public function loadParentHostsByString($containerId = 0, $host_id = 0) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $HostFilter = new HostFilter($this->request);

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerId);

        $hosts = $this->Host->makeItJavaScriptAble(
            $this->Host->getHostsForAngular($HostCondition, $selected)

        );

        if ($host_id != 0 && isset($hosts[$host_id])) {
            unset($hosts[$host_id]);
        }

        $this->set(compact(['hosts']));
        $this->set('_serialize', ['hosts']);
    }

    public function loadParentHostsById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }


        $parenthost = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id
            ],
            'contain'    => [
                'Parenthost'
            ]
        ]);

        $parenthost = Hash::extract($parenthost, 'Parenthost.{n}.id');

        $this->set(compact('parenthost'));
        $this->set('_serialize', ['parenthost']);
    }

    public function loadHostById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
                'Hosttemplate'
            ],
        ]);

        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        foreach ($host['Host'] as $key => $value) {
            if ($host['Host'][$key] === '' || $host['Host'][$key] === null) {
                if (isset($host['Hosttemplate'][$key])) {
                    $host['Host'][$key] = $host['Hosttemplate'][$key];
                }
            }
        }

        $host['Host']['is_satellite_host'] = (int)$host['Host']['satellite_id'] !== 0;
        $host['Host']['allow_edit'] = false;
        if ($this->hasRootPrivileges === true) {
            $host['Host']['allow_edit'] = true;
        } else {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
                $host['Host']['allow_edit'] = $ContainerPermissions->hasPermission();
            }
        }


        unset($host['Hosttemplate']);
        $this->set('host', $host);
        $this->set('_serialize', ['host']);
    }

    /**
     * @param string | null $uuid
     */
    public function hoststatus($uuid = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$uuid) {
            throw new NotFoundException(__('Invalid request parameter'));
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $hoststatus = $this->Hoststatus->byUuid($uuid, $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $this->set('hoststatus', $hoststatus);
        $this->set('_serialize', ['hoststatus']);
    }
}
