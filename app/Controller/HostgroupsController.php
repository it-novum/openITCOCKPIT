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
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\HosttemplateFilter;
use itnovum\openITCOCKPIT\HostgroupsController\HostsExtendedLoader;
use itnovum\openITCOCKPIT\HostgroupsController\ServicesExtendedLoader;
use itnovum\openITCOCKPIT\HostgroupsController\CumulatedServicestatusCollection;

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
        'Paginator',
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
            'recursive'  => -1,
            'contain'    => [
                'Container',
            ],
            'order'      => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $HostgroupFilter->indexFilter(),
            'limit'      => $this->Paginator->settings['limit']
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
                'Hostgroup'    => $hostgroup['Hostgroup'],
                'Container'    => $hostgroup['Container'],
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

    public function extended($hostgroupId = null) {
        $this->layout = 'Admin.default';
        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);

        $options = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Host'         => [
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
                ]
            ],
            'order'      => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS,
            ],
        ];

        $resultHostgroups = $this->Hostgroup->find('all', $options);
        $hostgroups = $this->getHostgroupNames($resultHostgroups);
        $hosttemplates = $this->getHosttemplates($resultHostgroups);
        $hostsInHostgroup = $this->getHostsInHostgroup($resultHostgroups);

        $this->set('hostgroups', $hostgroups);
        $hostgroup = [];
        if ($hostgroupId === null) {
            //Select first hostgroup out of find result
            $hostgroupId = key($hostgroups);
        }
        if ($hostgroupId !== null) {
            if (!$this->Hostgroup->exists($hostgroupId)) {
                throw new NotFoundException(__('Invalid hostgroup'));
            }

            $hostgroup = $this->Hostgroup->find('first', [
                'contain'    => [
                    'Container',
                ],
                'conditions' => [
                    'Hostgroup.id' => $hostgroupId,
                ],
            ]);


            $this->Frontend->setJson('hostgroupUuid', $hostgroup['Hostgroup']['uuid']);
            $this->Frontend->setJson('hostgroupId', $hostgroup['Hostgroup']['id']);
            $this->Frontend->setJson('renderTableMessage', __('Render data in browser'));

            $HostsExtendedLoader = new HostsExtendedLoader($this->Hostgroup, $containerIds, $hostgroup['Hostgroup']['id'], $hosttemplates[$hostgroup['Hostgroup']['id']], $hostsInHostgroup[$hostgroup['Hostgroup']['id']]);
            $hosts = $HostsExtendedLoader->loadHostsWithStatus();

            $ServicesExtendedLoader = new ServicesExtendedLoader($this->Hostgroup, $containerIds, $hostgroup['Hostgroup']['id']);
            $ServicestatusCollection = new CumulatedServicestatusCollection(
                $ServicesExtendedLoader->loadServicesCumulated()
            );

            $this->set('hosts', $hosts);
            $this->set('ServicestatusCollection', $ServicestatusCollection);

            $username = $this->Auth->user('full_name');
            $this->set(compact(['hostgroup', 'username', 'hostgroupId']));
            //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
            $this->set('_serialize', ['hostgroup']);

        }

    }

    private function getHostgroupNames($hostgroups) {
        $hostgroupnames = [];
        foreach ($hostgroups as $hostgroup) {
            $hostgroupnames[$hostgroup['Hostgroup']['id']] = $hostgroup['Container']['name'];
        }
        return $hostgroupnames;
    }

    private function getHosttemplates($hostgroups) {
        $hosttemplates = [];

        foreach ($hostgroups as $hostgroup) {
            $templateIds = "";
            foreach ($hostgroup['Hosttemplate'] as $template) {
                if ($templateIds !== "") {
                    $templateIds = $templateIds . ", " . $template['id'];
                } else {
                    $templateIds = $template['id'];
                }
            }
            $hosttemplates[$hostgroup['Hostgroup']['id']] = $templateIds;
        }
        return $hosttemplates;
    }

    private function getHostsInHostgroup($hostgroups) {
        $hostsInHostgroup = [];

        foreach ($hostgroups as $hostgroup) {
            $hostIds = "";
            foreach ($hostgroup['Host'] as $host) {
                if ($hostIds !== "") {
                    $hostIds = $hostIds . ", " . $host['id'];
                } else {
                    $hostIds = $host['id'];
                }
            }
            $hostsInHostgroup[$hostgroup['Hostgroup']['id']] = $hostIds;
        }
        return $hostsInHostgroup;
    }

    public function loadServicesByHostId($hostId = null, $hostgroupId) {
        if (!is_numeric($hostId) || $hostId < 1) {
            throw new NotFoundException('Invalide host id');
        }

        if (!$this->Host->exists($hostId)) {
            throw new NotFoundException('Host not found!');
        }

        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $hostId,
            ],
            'contain'    => [
                'Container',
            ],
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
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

        $ServicesExtendedLoader = new ServicesExtendedLoader($this->Hostgroup, [], null);
        $ServicesExtendedLoader->setServiceModel($this->Service);
        $services = $ServicesExtendedLoader->loadServicesWithStatusByHostId($hostId);

        $hostgroup = $this->Hostgroup->find('first', [
            'conditions' => [
                'Hostgroup.id' => $hostgroupId,
            ],
            'contain'    => [],
            'fields'     => [
                'Hostgroup.id',
                'Hostgroup.uuid',
            ],
        ]);
        $this->set(compact(['host', ['hostgroup', 'services']]));


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
            'recursive'  => -1,
            'contain'    => [
                'Host'         => [
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
                        'contain'    => [],
                        'fields'     => [
                            'Host.id',
                            'Host.name',
                        ],
                        'conditions' => [
                            'Host.id' => $host_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id'   => $host_id,
                        'name' => $host['Host']['name'],
                    ];
                }
            }
            if ($this->request->data('Hostgroup.Hosttemplate')) {
                foreach ($this->request->data['Hostgroup']['Hosttemplate'] as $hosttemplate_id) {
                    $hosttemplate = $this->Hosttemplate->find('first', [
                        'contain'    => [],
                        'fields'     => [
                            'Hosttemplate.id',
                            'Hosttemplate.name',
                        ],
                        'conditions' => [
                            'Hosttemplate.id' => $hosttemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Hosttemplate'][] = [
                        'id'   => $hosttemplate_id,
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
                        'contain'    => [],
                        'fields'     => [
                            'Host.id',
                            'Host.name',
                        ],
                        'conditions' => [
                            'Host.id' => $host_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id'   => $host_id,
                        'name' => $host['Host']['name'],
                    ];
                }
            }
            if ($this->request->data('Hostgroup.Hosttemplate')) {
                foreach ($this->request->data['Hostgroup']['Hosttemplate'] as $hosttemplate_id) {
                    $hosttemplate = $this->Hosttemplate->find('first', [
                        'contain'    => [],
                        'fields'     => [
                            'Hosttemplate.id',
                            'Hosttemplate.name',
                        ],
                        'conditions' => [
                            'Hosttemplate.id' => $hosttemplate_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Hosttemplate'][] = [
                        'id'   => $hosttemplate_id,
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
            'recursive'  => -1,
            'contain'    => [
                'Container'
            ],
            'order'      => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
            'conditions' => $HostgroupFilter->indexFilter(),
            'limit'      => $this->Paginator->settings['limit']
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
                        'recursive'  => -1,
                        'conditions' => [
                            'Host.id' => $hostId,
                        ],
                        'fields'     => [
                            'Host.id',
                            'Host.name',
                        ],
                    ]);
                    $ext_data_for_changelog['Host'][] = [
                        'id'   => $hostId,
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
            'hostsToAppend'     => $hostsToAppend,
            'hostgroups'        => $hostgroups,
            'containers'        => $containers,
            'user_container_id' => $userContainerId,
        ]);
        $this->set('back_url', $this->referer());
    }

    public function listToPdf() {
        $this->layout = 'Admin.default';

        $HostgroupFilter = new HostgroupFilter($this->request);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid'
                    ],
                ]
            ],
            'order'      => $HostgroupFilter->getOrderForPaginator('Container.name', 'asc'),
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
}
