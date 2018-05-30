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
use itnovum\openITCOCKPIT\Core\HostControllerRequest;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\PerfdataChecker;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\ServicegroupFilter;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;


/**
 * @property Service $Service
 * @property Servicegroup $Servicegroup
 * @property Host $Host
 * @property Servicetemplate $Servicetemplate
 * @property TreeComponent $Tree
 */
class ServicegroupsController extends AppController {
    public $uses = [
        'Servicegroup',
        'Container',
        'Service',
        'Servicetemplate',
        'User',
        MONITORING_OBJECTS,
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Host',
    ];
    //public $layout = 'Admin.default';
    public $layout = 'angularjs';
    public $components = [
        'RequestHandler',
    ];
    public $helpers = [
        'Status'
    ];


    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $query = [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'conditions' => $ServicegroupFilter->indexFilter(),
            'order' => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'limit' => $this->Paginator->settings['limit']
        ];
        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $ServicegroupFilter->getPage();
            $servicegroups = $this->Paginator->paginate();
        }

        $all_servicegroups = [];

        foreach ($servicegroups as $servicegroup) {
            $servicegroup['Servicegroup']['allowEdit'] = $this->hasPermission('edit', 'servicegroups');;
            if ($this->hasRootPrivileges === false && $servicegroup['Servicegroup']['allowEdit'] === true) {
                $servicegroup['Servicegroup']['allowEdit'] = $this->allowedByContainerId($servicegroup['Container']['parent_id']);
            }

            $all_servicegroups[] = [
                'Servicegroup' => $servicegroup['Servicegroup'],
                'Container' => $servicegroup['Container']
            ];

        }
        $this->set(compact(['all_servicegroups']));
        $this->set('_serialize', ['all_servicegroups', 'paging']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid Servicegroup'));
        }

        $servicegroup = $this->Servicegroup->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        $this->set('servicegroup', $servicegroup);
        $this->set('_serialize', ['servicegroup']);
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid service group'));
        }

        $servicegroup = $this->Servicegroup->find('first', [
            'recursive' => -1,
            'contain' => [
                'Service' => [
                    'fields' => [
                        'Service.id',
                        'Service.name'
                    ],
                    'Host' => [
                        'fields' => [
                            'Host.id',
                            'Host.name'
                        ]
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name'
                        ]
                    ],
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Container'
            ],
            'conditions' => [
                'Servicegroup.id' => $id,
            ],
        ]);
        if (!$this->allowedByContainerId($servicegroup['Container']['parent_id'])) {
            $this->render403();
            return;
        }

        $ext_data_for_changelog = [];
        $containerId = $servicegroup['Container']['parent_id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Servicegroup']['id'] = $id;
            if ($this->request->data('Servicegroup.Service')) {
                foreach ($this->request->data['Servicegroup']['Service'] as $service_id) {
                    $service = $this->Service->find('first', [
                        'contain' => [
                            'Host.name',
                            'Servicetemplate.name'
                        ],
                        'fields' => [
                            'Service.id',
                            'Service.name',
                        ],
                        'conditions' => [
                            'Service.id' => $service_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Service'][] = [
                        'id' => $service_id,
                        'name' => sprintf(
                            '%s | %s',
                            $service['Host']['name'],
                            ($service['Service']['name']) ? $service['Service']['name'] : $service['Servicetemplate']['name']
                        )
                    ];
                }
            }
            if ($this->request->data('Servicegroup.Servicetemplate')) {
                foreach ($this->request->data['Servicegroup']['Servicetemplate'] as $servicetemplate_id) {
                    $servicetemplate = $this->Servicetemplate->find('first', [
                        'recursive' => -1,
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                        'conditions' => [
                            'Servicetemplate.id' => $servicetemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Servicetemplate'][] = [
                        'id' => $servicetemplate_id,
                        'name' => $servicetemplate['Servicetemplate']['name'],
                    ];
                }
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $userId = $this->Auth->user('id');
            $this->request->data['Service'] = (!empty($this->request->data('Servicegroup.Service'))) ? $this->request->data('Servicegroup.Service') : [];
            //Add container id (of the service group container itself) to the request data
            $this->request->data['Container']['id'] = $servicegroup['Servicegroup']['container_id'];
            $this->request->data['Servicetemplate'] = (!empty($this->request->data('Servicegroup.Servicetemplate'))) ? $this->request->data('Servicegroup.Servicetemplate') : [];
            if ($this->Servicegroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Servicegroup->id,
                    OBJECT_SERVICEGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data('Container.name'),
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $servicegroup
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
        }
        $this->set('servicegroup', $servicegroup);
        $this->set('_serialize', ['servicegroup']);
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
            if ($this->request->data('Servicegroup.Service')) {
                foreach ($this->request->data['Servicegroup']['Service'] as $service_id) {
                    $service = $this->Service->find('first', [
                        'contain' => [
                            'Host.name',
                            'Servicetemplate.name'
                        ],
                        'fields' => [
                            'Service.id',
                            'Service.name',
                        ],
                        'conditions' => [
                            'Service.id' => $service_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Service'][] = [
                        'id' => $service_id,
                        'name' => sprintf(
                            '%s | %s',
                            $service['Host']['name'],
                            ($service['Service']['name']) ? $service['Service']['name'] : $service['Servicetemplate']['name']
                        )
                    ];
                }
            }
            if ($this->request->data('Servicegroup.Servicetemplate')) {
                foreach ($this->request->data['Servicegroup']['Servicetemplate'] as $servicetemplate_id) {
                    $servicetemplate = $this->Servicetemplate->find('first', [
                        'recursive' => -1,
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                        'conditions' => [
                            'Servicetemplate.id' => $servicetemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Servicetemplate'][] = [
                        'id' => $servicetemplate_id,
                        'name' => $servicetemplate['Servicetemplate']['name'],
                    ];
                }
            }

            $this->request->data['Servicegroup']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_SERVICEGROUP;
            $this->request->data['Service'] = (!empty($this->request->data('Servicegroup.Service'))) ? $this->request->data('Servicegroup.Service') : [];
            $this->request->data['Servicetemplate'] = (!empty($this->request->data('Servicegroup.Servicetemplate'))) ? $this->request->data('Servicegroup.Servicetemplate') : [];


            if ($this->Servicegroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Servicegroup->id,
                    OBJECT_SERVICEGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data('Container.name'),
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
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
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
        }
        $containers = $this->Container->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function loadServices($containerId = null) {
        $this->allowOnlyAjaxRequests();

        $services = $this->Host->servicesByContainerIds([ROOT_CONTAINER, $containerId], 'list', [
            'forOptiongroup' => true,
        ]);
        $services = $this->Service->makeItJavaScriptAble($services);

        $data = ['services' => $services];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function loadServicegroupWithServicesById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('Invalid service group'));
        }

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $hostContainers = [];
        $hosts = [];
        $services = [];

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Service' => [
                    'fields' => [
                        'Service.id',
                        'Service.uuid',
                        'Service.name',
                        'Service.active_checks_enabled',
                        'Service.disabled',
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name',
                            'Servicetemplate.active_checks_enabled'
                        ]
                    ],
                    'Host' => [
                        'Container',
                        'fields' => [
                            'Host.id',
                            'Host.uuid',
                            'Host.name',
                            'Host.active_checks_enabled'
                        ]
                    ]
                ]
            ],
            'conditions' => [
                'Servicegroup.id' => $id
            ]
        ];

        $servicegroup = $this->Servicegroup->find('first', $query);
        $servicegroup['Servicegroup']['allowEdit'] = $this->hasPermission('edit', 'servicegroups');;
        if ($this->hasRootPrivileges === false && $servicegroup['Servicegroup']['allowEdit'] === true) {
            $servicegroup['Servicegroup']['allowEdit'] = $this->allowedByContainerId($servicegroup['Container']['parent_id']);
        }

        foreach ($servicegroup['Service'] as $service) {
            $hosts[$service['Host']['id']] = $service['Host']['uuid'];
            $services[$service['id']] = $service['uuid'];
            if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                $hostContainers[$service['Host']['id']] = Hash::extract($service['Host']['Container'], '{n}.id');
            }
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->output()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->notificationsEnabled();
        $hoststatus = $this->Hoststatus->byUuid($hosts, $HoststatusFields);
        $servicestatus = $this->Servicestatus->byUuid($services, $ServicestatusFields);

        if (!empty($services) && $this->hasRootPrivileges === false && $this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
            $hostContainers[$service['Host']['id']] = Hash::extract($service['Host']['Container'], '{n}.id');
        }
        $all_services = [];
        foreach ($servicegroup['Service'] as $key => $service) {
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

            $service['Hoststatus'] = (!empty($hoststatus[$service['Host']['uuid']])) ? $hoststatus[$service['Host']['uuid']]['Hoststatus'] : [];
            $service['Servicestatus'] = (!empty($servicestatus[$service['uuid']])) ? $servicestatus[$service['uuid']]['Servicestatus'] : [];

            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service([
                'Service' => $service,
                'Servicetemplate' => $service['Servicetemplate'],
                'Host' => $service['Host']
            ],
                null,
                $allowEdit
            );

            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service, $allowEdit);
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($service['Hoststatus'], $UserTime);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
            $PerfdataChecker = new PerfdataChecker($Host, $Service);

            $tmpRecord = [
                'Service' => $Service->toArray(),
                'Host' => $Host->toArray(),
                'Servicestatus' => $Servicestatus->toArray(),
                'Hoststatus' => $Hoststatus->toArray()
            ];
            $tmpRecord['Service']['has_graph'] = $PerfdataChecker->hasRrdFile();
            $all_services[] = $tmpRecord;
        }

        $serviceStatusForServicegroup = Hash::apply(
            $all_services,
            '{n}.Servicestatus[isInMonitoring=true].currentState',
            'array_count_values'
        );
        //refill missing service states
        $statusOverview = array_replace(
            [0 => 0, 1 => 0, 2 => 0, 3 => 0],
            $serviceStatusForServicegroup
        );
        $statusOverview = array_combine([
            __('ok'),
            __('warning'),
            __('critical'),
            __('unknown')], $statusOverview
        );

        # get a list of sort columns and their data to pass to array_multisort
        $sortAllServices = [];
        foreach ($all_services as $k => $v) {
            $sortAllServices['Host']['hostname'][$k] = $v['Host']['hostname'];
            $sortAllServices['Service']['servicename'][$k] = $v['Service']['servicename'];
        }

        # sort by host name asc and service name asc
        if (!empty($all_services)) {
            array_multisort($sortAllServices['Host']['hostname'], SORT_ASC, $sortAllServices['Service']['servicename'], SORT_ASC, $all_services);
        }

        $selectedServiceGroup = [
            'Servicegroup' => $servicegroup['Servicegroup'],
            'Container' => $servicegroup['Container'],
            'Services' => $all_services,
            'StatusSummary' => $statusOverview
        ];

        $servicegroup = $selectedServiceGroup;

        $this->set(compact(['servicegroup']));
        $this->set('_serialize', ['servicegroup']);
    }

    public function loadServicetemplates($containerId = null) {
        $this->allowOnlyAjaxRequests();

        $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId([ROOT_CONTAINER, $containerId], 'list');
        $servicetemplates = $this->Servicetemplate->makeItJavaScriptAble($servicetemplates);

        $data = ['servicetemplates' => $servicetemplates];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function delete($id = null) {
        $userId = $this->Auth->user('id');
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicegroup->exists($id)) {
            throw new NotFoundException(__('invalid_servicegroup'));
        }
        $container = $this->Servicegroup->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->Container->delete($container['Servicegroup']['container_id'], true)) {
            Cache::clear(false, 'permissions');
            $changelog_data = $this->Changelog->parseDataForChangelog(
                $this->params['action'],
                $this->params['controller'],
                $id,
                OBJECT_SERVICEGROUP,
                $container['Container']['parent_id'],
                $userId,
                $container['Container']['name'],
                $container
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }
            $this->setFlash(__('Servicegroup deleted'));
            $this->redirect(['action' => 'index']);
        }

        $this->setFlash(__('could not delete servicegroup'), false);
        $this->redirect(['action' => 'index']);
    }

    public function mass_delete($id = null) {
        $userId = $this->Auth->user('id');
        foreach (func_get_args() as $servicegroupId) {
            if ($this->Servicegroup->exists($servicegroupId)) {
                $servicegroup = $this->Servicegroup->find('first', [
                    'contain' => [
                        'Container',
                        'Service',
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $servicegroupId,
                    ],
                ]);
                if ($this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))) {
                    if ($this->Container->delete($servicegroup['Servicegroup']['container_id'], true)) {
                        $changelog_data = $this->Changelog->parseDataForChangelog(
                            $this->params['action'],
                            $this->params['controller'],
                            $id,
                            OBJECT_SERVICEGROUP,
                            $servicegroup['Container']['parent_id'],
                            $userId,
                            $servicegroup['Container']['name'],
                            $servicegroup
                        );
                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }
                    }
                }
            }
        }
        Cache::clear(false, 'permissions');
        $this->setFlash(__('Servicegroups deleted'));
        $this->redirect(['action' => 'index']);
    }

    public function mass_add($id = null) {
        if ($this->request->is('post') || $this->request->is('put')) {
            $targetServicegroup = $this->request->data('Servicegroup.id');
            if ($this->Servicegroup->exists($targetServicegroup)) {
                $servicegroup = $this->Servicegroup->findById($targetServicegroup);
                //Save old services from this service group
                $servicegroupMembers = [];
                foreach ($servicegroup['Service'] as $service) {
                    $servicegroupMembers[] = $service['id'];
                }
                foreach ($this->request->data('Service.id') as $service_id) {
                    $servicegroupMembers[] = $service_id;
                }
                $servicegroup['Service'] = $servicegroupMembers;
                $servicegroup['Servicegroup']['Service'] = $servicegroupMembers;
                if ($this->Servicegroup->saveAll($servicegroup)) {
                    Cache::clear(false, 'permissions');
                    $this->setFlash(_('Servicegroup appended successfully'));
                    $this->redirect(['action' => 'index']);
                } else {
                    $this->setFlash(_('Could not append Servicegroup'), false);
                }
            } else {
                $this->setFlash('Servicegroup not found', false);
            }
        }

        $servicesToAppend = [];
        foreach (func_get_args() as $service_id) {
            $service = $this->Service->findById($service_id);
            $servicesToAppend[] = $service;
        }
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');

        $this->set(compact(['servicesToAppend', 'servicegroups']));
        $this->set('back_url', $this->referer());
    }

    public function listToPdf() {
        $this->layout = 'Admin.default';

        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Service' => [
                    'fields' => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid'
                    ],
                    'Host' => [
                        'fields' => [
                            'Host.id',
                            'Host.name'
                        ],
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ],
                    ]
                ],
                'Servicetemplate'

            ],
            'order' => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $ServicegroupFilter->indexFilter(),
        ];


        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }
        $servicegroups = $this->Servicegroup->find('all', $query);
        $servicegroupCount = count($servicegroups);
        $serviceUuids = Hash::extract($servicegroups, '{n}.Service.{n}.uuid');
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields
            ->currentState()
            ->lastStateChange()
            ->lastCheck()
            ->nextCheck()
            ->output()
            ->problemHasBeenAcknowledged()
            ->acknowledgementType()
            ->scheduledDowntimeDepth()
            ->notificationsEnabled();
        $servicegroupstatus = $this->Servicestatus->byUuids(array_unique($serviceUuids), $ServicestatusFields);
        $hostsArray = [];
        $serviceCount = 0;

        foreach ($servicegroups as $servicegroup) {
            foreach ($servicegroup['Service'] as $service) {
                $serviceCount++;
                $hostsArray[$service['Host']['id']] = $service['Host']['name'];
            }
        }
        $hostCount = sizeof($hostsArray);
        $this->set(compact('servicegroups', 'servicegroupstatus', 'servicegroupCount', 'hostCount', 'serviceCount'));

        $filename = 'Servicegroups_' . strtotime('now') . '.pdf';
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

    public function loadServicegroupsByContainerId() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $containerId = $this->request->query('containerId');
        $selected = $this->request->query('selected');
        $ServicegroupFilter = new ServicegroupFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'order' => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $ServicegroupFilter->indexFilter(),
            'limit' => $this->Paginator->settings['limit']
        ];

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $ServicegroupFilter->getPage();
            $servicegroups = $this->Paginator->paginate();
        }

        $servicegroups = $this->Servicegroup->makeItJavaScriptAble(
            Hash::combine(
                $servicegroups,
                '{n}.Servicegroup.id',
                '{n}.Container.name'
            )
        );

        $this->set(compact(['servicegroups']));
        $this->set('_serialize', ['servicegroups']);
    }

    public function extended() {
        $this->layout = 'angularjs';
        $User = new User($this->Auth);

        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            return;
        }

        $ServicegroupFilter = new ServicegroupFilter($this->request);
        $query = [
            'recursive' => -1,
            'contain' => [
                'Container',
            ],
            'conditions' => $ServicegroupFilter->indexFilter(),
            'order' => $ServicegroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'limit' => $this->Paginator->settings['limit']
        ];
        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $servicegroups = $this->Servicegroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $ServicegroupFilter->getPage();
            $servicegroups = $this->Paginator->paginate();
        }

        $this->set('servicegroups', $servicegroups);
        $this->set('_serialize', ['servicegroups', 'username']);
    }
}
