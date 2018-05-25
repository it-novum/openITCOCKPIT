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

use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\CumulatedValue;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;

/**
 * @property Hostgroup $Hostgroup
 * @property Container $Container
 * @property Host $Host
 * @property Hosttemplate $Hosttemplate
 * @property User $User
 */
class HostgroupsController extends AppController {

    public $uses = [
        'Hostgroup',
        'Container',
        'Host',
        'Hosttemplate',
        'Service',
        'User',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        MONITORING_OBJECTS,
    ];
    public $layout = 'angularjs';
    public $components = [
        'RequestHandler',
    ];
    public $helpers = [
        'Status',
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $HostgroupFilter = new HostgroupFilter($this->request);

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
            ],
            'order' => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $HostgroupFilter->indexFilter(),
            'limit' => $this->Paginator->settings['limit']
        ];
        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $hostgroups = $this->Hostgroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $HostgroupFilter->getPage();
            $hostgroups = $this->Paginator->paginate();
        }

        $all_hostgroups = [];
        foreach ($hostgroups as $hostgroup) {
            $hostgroup['Hostgroup']['allowEdit'] = $this->hasPermission('edit', 'hostgroups');;
            if ($this->hasRootPrivileges === false && $hostgroup['Hostgroup']['allowEdit'] === true) {
                $hostgroup['Hostgroup']['allowEdit'] = $this->allowedByContainerId($hostgroup['Container']['parent_id']);
            }

            $all_hostgroups[] = [
                'Hostgroup' => $hostgroup['Hostgroup'],
                'Container' => $hostgroup['Container'],
            ];

        }


        $this->set('all_hostgroups', $all_hostgroups);

        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_hostgroups', 'paging']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Hostgroup->exists($id)) {
            throw new NotFoundException(__('Invalid Hostgroup'));
        }

        $hostgroup = $this->Hostgroup->findById($id);
        if (!$this->allowedByContainerId($hostgroup['Container']['parent_id'])) {
            $this->render403();
            return;
        }

        $this->set('hostgroup', $hostgroup);
        $this->set('_serialize', ['hostgroup']);
    }

    public function extended() {
        if (!$this->isApiRequest()) {
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $this->set('username', $User->getFullName());
        }
    }


    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Hostgroup->exists($id)) {
            throw new NotFoundException(__('Invalid hostgroup'));
        }

        $hostgroup = $this->Hostgroup->find('first', [
            'recursive' => -1,
            'contain' => [
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name'
                    ]
                ],
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.name'
                    ]
                ],
                'Container'
            ],
            'conditions' => [
                'Hostgroup.id' => $id,
            ],
        ]);
        if (!$this->allowedByContainerId($hostgroup['Container']['parent_id'])) {
            $this->render403();
            return;
        }

        $ext_data_for_changelog = [];
        $containerId = $hostgroup['Container']['parent_id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Hostgroup']['id'] = $id;
            if ($this->request->data('Hostgroup.Host')) {
                foreach ($this->request->data['Hostgroup']['Host'] as $host_id) {
                    $host = $this->Host->find('first', [
                        'contain' => [],
                        'fields' => [
                            'Host.id',
                            'Host.name',
                        ],
                        'conditions' => [
                            'Host.id' => $host_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id' => $host_id,
                        'name' => $host['Host']['name'],
                    ];
                }
            }
            if ($this->request->data('Hostgroup.Hosttemplate')) {
                foreach ($this->request->data['Hostgroup']['Hosttemplate'] as $hosttemplate_id) {
                    $hosttemplate = $this->Hosttemplate->find('first', [
                        'contain' => [],
                        'fields' => [
                            'Hosttemplate.id',
                            'Hosttemplate.name',
                        ],
                        'conditions' => [
                            'Hosttemplate.id' => $hosttemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Hosttemplate'][] = [
                        'id' => $hosttemplate_id,
                        'name' => $hosttemplate['Hosttemplate']['name'],
                    ];
                }
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $userId = $this->Auth->user('id');
            $this->request->data['Host'] = (!empty($this->request->data('Hostgroup.Host'))) ? $this->request->data('Hostgroup.Host') : [];
            //Add container id (of the hostgroup container itself) to the request data
            $this->request->data['Container']['id'] = $hostgroup['Hostgroup']['container_id'];
            $this->request->data['Hosttemplate'] = (!empty($this->request->data('Hostgroup.Hosttemplate'))) ? $this->request->data('Hostgroup.Hosttemplate') : [];
            if ($this->Hostgroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Hostgroup->id,
                    OBJECT_HOSTGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data['Container']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $hostgroup
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/hostgroups/edit/%s">Hostgroup</a> successfully saved', $this->Hostgroup->id));
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
        }

        $this->set('hostgroup', $hostgroup);
        $this->set('_serialize', ['hostgroup']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $userId = $this->Auth->user('id');
            $ext_data_for_changelog = [];
            App::uses('UUID', 'Lib');
            if ($this->request->data('Hostgroup.Host')) {
                foreach ($this->request->data['Hostgroup']['Host'] as $host_id) {
                    $host = $this->Host->find('first', [
                        'contain' => [],
                        'fields' => [
                            'Host.id',
                            'Host.name',
                        ],
                        'conditions' => [
                            'Host.id' => $host_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id' => $host_id,
                        'name' => $host['Host']['name'],
                    ];
                }
            }
            if ($this->request->data('Hostgroup.Hosttemplate')) {
                foreach ($this->request->data['Hostgroup']['Hosttemplate'] as $hosttemplate_id) {
                    $hosttemplate = $this->Hosttemplate->find('first', [
                        'contain' => [],
                        'fields' => [
                            'Hosttemplate.id',
                            'Hosttemplate.name',
                        ],
                        'conditions' => [
                            'Hosttemplate.id' => $hosttemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Hosttemplate'][] = [
                        'id' => $hosttemplate_id,
                        'name' => $hosttemplate['Hosttemplate']['name'],
                    ];
                }
            }

            $this->request->data['Hostgroup']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_HOSTGROUP;
            $this->request->data['Host'] = (!empty($this->request->data('Hostgroup.Host'))) ? $this->request->data('Hostgroup.Host') : [];
            $this->request->data['Hosttemplate'] = (!empty($this->request->data('Hostgroup.Hosttemplate'))) ? $this->request->data('Hostgroup.Hosttemplate') : [];


            if ($this->Hostgroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Hostgroup->id,
                    OBJECT_HOSTGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data['Container']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/hostgroups/edit/%s">Hostgroup</a> successfully saved', $this->Hostgroup->id));
                    }
                    $this->serializeId();
                    return;
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
                $this->setFlash(__('Could not save data'), false);
            }
        }
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
        }
        $containers = $this->Container->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function loadHosts() {
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

    public function loadHostgroupWithHostsById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Hostgroup->exists($id)) {
            throw new NotFoundException(__('Invalid host group'));
        }

        $HostFilter = new HostFilter($this->request);
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $hostContainers = [];
        $hosts = [];

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Host' => [
                    'Container',
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.active_checks_enabled',
                        'Host.disabled',
                        'Host.satellite_id'
                    ],
                    'conditions'=> [
                        'Host.disabled' => 0
                    ],
                    'Service' => [
                        'fields' => [
                            'Service.uuid'
                        ]
                    ]

                ]
            ],
            'conditions' => [
                'Hostgroup.id' => $id
            ]
        ];

        $hostFilterConditions = $HostFilter->indexFilter();
        if (!empty($hostFilterConditions)) {
            foreach ($hostFilterConditions as $field => $condition) {
                $query['contain']['Host']['conditions'][$field] = $condition;
            }
        }

        $hostgroup = $this->Hostgroup->find('first', $query);
        $hostgroup['Hostgroup']['allowEdit'] = $this->hasPermission('edit', 'hostgroups');;
        if ($this->hasRootPrivileges === false && $hostgroup['Hostgroup']['allowEdit'] === true) {
            $hostgroup['Hostgroup']['allowEdit'] = $this->allowedByContainerId($hostgroup['Container']['parent_id']);
        }
        foreach ($hostgroup['Host'] as $host) {
            $hosts[$host['id']] = $host['uuid'];
            if (!empty($hosts) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts')) {
                $hostContainers[$host['id']] = Hash::extract($host['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->notificationsEnabled();

        $hoststatus = $this->Hoststatus->byUuid($hosts, $HoststatusFields);
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState();


        $all_hosts = [];
        foreach ($hostgroup['Host'] as $key => $host) {
            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $containerIds = [];
                if (isset($hostContainers[$host['id']])) {
                    $containerIds = $hostContainers[$host['id']];
                }
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIds);
                $allowEdit = $ContainerPermissions->hasPermission();
            }
            $serviceUuids = Hash::extract($host, 'Service.{n}.uuid');
            $servicestatus = $this->Servicestatus->byUuid($serviceUuids, $ServicestatusFields);
            $serviceStateSummary = $this->Service->getServiceStateSummary($servicestatus, false);

            $CumulatedValue = new CumulatedValue($serviceStateSummary['state']);
            $serviceStateSummary['cumulatedState'] = $CumulatedValue->getKeyFromCumulatedValue();
            $serviceStateSummary['state'] = array_combine([
                __('ok'),
                __('warning'),
                __('critical'),
                __('unknown')
            ], $serviceStateSummary['state']
            );
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host(['Host' => $host], $allowEdit);
            $host['Hoststatus'] = (!empty($hoststatus[$host['uuid']])) ? $hoststatus[$host['uuid']]['Hoststatus'] : [];
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($host['Hoststatus'], $UserTime);

            $tmpRecord = [
                'Host' => $Host->toArray(),
                'Hoststatus' => $Hoststatus->toArray(),
                'ServicestatusSummary' => $serviceStateSummary
            ];
            $all_hosts[] = $tmpRecord;
        }

        $hostStatusForHostgroup = Hash::apply(
            $all_hosts,
            '{n}.Hoststatus[isInMonitoring=true].currentState',
            'array_count_values'
        );
        //refill missing hosts states
        $statusOverview = array_replace(
            [0 => 0, 1 => 0, 2 => 0],
            $hostStatusForHostgroup
        );
        $statusOverview = array_combine([
            __('up'),
            __('down'),
            __('unreachable')
        ], $statusOverview
        );

        # get a list of sort columns and their data to pass to array_multisort
        $sortAllServices = [];
        foreach ($all_hosts as $k => $v) {
            $sortAllServices['Host']['hostname'][$k] = $v['Host']['hostname'];
        }

        # sort by host name asc and service name asc
        if (!empty($all_services)) {
            array_multisort($sortAllServices['Host']['hostname'], SORT_ASC, $all_hosts);
        }

        $selectedHostGroup = [
            'Hostgroup' => $hostgroup['Hostgroup'],
            'Container' => $hostgroup['Container'],
            'Hosts' => $all_hosts,
            'StatusSummary' => $statusOverview
        ];

        $hostgroup = $selectedHostGroup;

        $this->set(compact(['hostgroup']));
        $this->set('_serialize', ['hostgroup']);
    }


    public function loadHosttemplates($containerId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $HosttemplateFilter = new HosttemplateFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $hosttemplates = $this->Hosttemplate->makeItJavaScriptAble(
            $this->Hosttemplate->getHosttemplatesForAngular($containerIds, $HosttemplateFilter, $selected)
        );

        $this->set(compact(['hosttemplates']));
        $this->set('_serialize', ['hosttemplates']);
    }

    public function loadHostgroupsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');

        $HostgroupFilter = new HostgroupFilter($this->request);

        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());
        $HostgroupCondition->setContainerIds($this->MY_RIGHTS);

        $hostgroups = $this->Hostgroup->makeItJavaScriptAble(
            $this->Hostgroup->getHostgroupsForAngular($HostgroupCondition, $selected)
        );

        $this->set(compact(['hostgroups']));
        $this->set('_serialize', ['hostgroups']);
    }

    public function loadHosgroupsByContainerId() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $HostgroupFilter = new HostgroupFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'order' => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $HostgroupFilter->indexFilter(),
            'limit' => $this->Paginator->settings['limit']
        ];

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $hostgroups = $this->Hostgroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $HostgroupFilter->getPage();
            $hostgroups = $this->Paginator->paginate();
        }

        $hostgroups = $this->Hostgroup->makeItJavaScriptAble(
            Hash::combine(
                $hostgroups,
                '{n}.Hostgroup.id',
                '{n}.Container.name'
            )
        );

        $this->set(compact(['hostgroups']));
        $this->set('_serialize', ['hostgroups']);
    }

    public function delete($id = null) {
        $userId = $this->Auth->user('id');
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Hostgroup->exists($id)) {
            throw new NotFoundException(__('Invalid hostgroup'));
        }

        $container = $this->Hostgroup->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.parent_id'))) {
            $this->render403();
            return;
        }
        if ($this->Container->delete($container['Hostgroup']['container_id'], true)) {
            Cache::clear(false, 'permissions');
            $changelog_data = $this->Changelog->parseDataForChangelog(
                $this->params['action'],
                $this->params['controller'],
                $id,
                OBJECT_HOSTGROUP,
                $container['Container']['parent_id'],
                $userId,
                $container['Container']['name'],
                $container
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }

            $this->setFlash(__('Host group deleted successfully'));
            $this->set('message', __('Host group deleted successfully'));
            $this->set('_serialize', ['message']);
            return;
        }
        $this->response->statusCode(400);
        $this->set('message', __('Could not delete host group'));
        $this->set('_serialize', ['message']);
    }

    public function mass_add($id = null) {
        $this->layout = 'Admin.default';
        if ($this->request->is('post') || $this->request->is('put')) {

            $userId = $this->Auth->user('id');

            if (isset($this->request->data['Hostgroup']['create']) && $this->request->data['Hostgroup']['create'] == 1) {
                if (isset($this->request->data['Hostgroup']['id'])) {
                    unset($this->request->data['Hostgroup']['id']);
                }

                $ext_data_for_changelog = [];
                App::uses('UUID', 'Lib');

                $this->request->data['Hostgroup']['uuid'] = UUID::v4();
                $this->request->data['Container']['containertype_id'] = CT_HOSTGROUP;

                //Required for validation
                foreach ($this->request->data('Host.id') as $host_id) {
                    $hostgroupMembers[] = $host_id;
                }
                $this->request->data['Host'] = $hostgroupMembers;
                $this->request->data['Hostgroup']['Host'] = $hostgroupMembers;


                $ext_data_for_changelog = [];
                foreach ($hostgroupMembers as $hostId) {
                    $host = $this->Host->find('first', [
                        'recursive' => -1,
                        'conditions' => [
                            'Host.id' => $hostId,
                        ],
                        'fields' => [
                            'Host.id',
                            'Host.name',
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id' => $hostId,
                        'name' => $host['Host']['name'],
                    ];
                }

                if ($this->Hostgroup->saveAll($this->request->data)) {
                    Cache::clear(false, 'permissions');
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        'add',
                        'hostgroups',
                        $this->Hostgroup->id,
                        OBJECT_HOSTGROUP,
                        $this->request->data('Container.parent_id'),
                        $userId,
                        $this->request->data['Container']['name'],
                        array_merge($this->request->data, $ext_data_for_changelog)
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }

                    $this->setFlash(_('Hostgroup appended successfully'));
                    $this->redirect(['action' => 'index']);
                } else {
                    $this->setFlash(__('Could not save data'), false);
                }
            } else {
                $targetHostgroup = $this->request->data('Hostgroup.id');
                if ($this->Hostgroup->exists($targetHostgroup)) {
                    $hostgroup = $this->Hostgroup->findById($targetHostgroup);
                    //Save old hosts from this hostgroup
                    $hostgroupMembers = [];
                    foreach ($hostgroup['Host'] as $host) {
                        $hostgroupMembers[] = $host['id'];
                    }
                    foreach ($this->request->data('Host.id') as $host_id) {
                        $hostgroupMembers[] = $host_id;
                    }
                    $hostgroup['Host'] = $hostgroupMembers;
                    $hostgroup['Hostgroup']['Host'] = $hostgroupMembers;
                    if ($this->Hostgroup->saveAll($hostgroup)) {
                        Cache::clear(false, 'permissions');
                        $this->setFlash(_('Hostgroup appended successfully'));
                        $this->redirect(['action' => 'index']);
                    } else {
                        $this->setFlash(__('Could not append hostgroup'), false);
                    }
                } else {
                    $this->setFlash(__('Hostgroup not found'), false);
                }
            }
        }

        $hostsToAppend = [];
        foreach (func_get_args() as $host_id) {
            $host = $this->Host->findById($host_id);
            $hostsToAppend[] = $host;
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        } else {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->getWriteContainers());
            $containers = $this->Tree->easyPath($containerIds, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
            $hostgroups = $this->Hostgroup->hostgroupsByContainerId($this->getWriteContainers(), 'list', 'id');
        }
        $userContainerId = (isset($this->request->data['Container']['parent_id'])) ? $this->request->data['Container']['parent_id'] : $this->Auth->user('container_id');

        $this->set([
            'hostsToAppend' => $hostsToAppend,
            'hostgroups' => $hostgroups,
            'containers' => $containers,
            'user_container_id' => $userContainerId,
        ]);
        $this->set('back_url', $this->referer());
    }

    public function listToPdf() {
        $this->layout = 'Admin.default';

        $HostgroupFilter = new HostgroupFilter($this->request);
        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid'
                    ],
                ]
            ],
            'order' => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $HostgroupFilter->indexFilter(),
        ];
        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }

        $hostgroups = $this->Hostgroup->find('all', $query);


        $hostgroupHostCount = 0;
        foreach ($hostgroups as $hgKey => $hostgroup) {
            $hostgroupHostUuids = Hash::extract($hostgroup, 'Host.{n}.uuid');
            $hostgroupHostCount += count($hostgroupHostUuids);
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->wildcard();
            $hoststatusOfHostgroup = $this->Hoststatus->byUuids($hostgroupHostUuids, $HoststatusFields);
            $hostgroups[$hgKey]['all_hoststatus'] = $hoststatusOfHostgroup;
        }

        $hostgroupCount = count($hostgroups);
        $this->set(compact('hostgroups', 'hostgroupCount', 'hostgroupHostCount'));

        $filename = 'Hostgroups_' . strtotime('now') . '.pdf';
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
}
