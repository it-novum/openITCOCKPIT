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


/**
 * @property Servicetemplategroup $Servicetemplategroup
 * @property Servicetemplate      $Servicetemplate
 * @property Container            $Container
 * @property Host                 $Host
 * @property Hostgroup            $Hostgroup
 */
class ServicetemplategroupsController extends AppController
{
    public $layout = 'Admin.default';

    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
    ];
    public $helpers = ['ListFilter.ListFilter'];

    public $uses = [
        'Servicetemplategroup',
        'Servicetemplate',
        'Container',
        'Changelog',
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Container.name' => ['label' => 'Servicetemplategroup name', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {
        $options = [
            'order'      => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS,
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        $this->Servicetemplategroup->unbindModel([
            'hasAndBelongsToMany' => ['Servicetemplate'],
        ]);
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_servicetemplategroups = $this->Servicetemplategroup->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_servicetemplategroups = $this->Paginator->paginate();
        }
        $this->set(compact('all_servicetemplategroups'));
        $this->set('_serialize', ['all_servicetemplategroups']);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Servicetemplategroup->exists($id)) {
            throw new NotFoundException(__('Invalid Servicetemplategroup'));
        }

        $servicetemplategroup = $this->Servicetemplategroup->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($servicetemplategroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        $this->set('servicetemplategroup', $servicetemplategroup);
        $this->set('_serialize', ['servicetemplategroup']);
    }

    public function add()
    {
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        }

        $servicetemplates = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('UUID', 'Lib');
            $this->request->data['Servicetemplategroup']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_SERVICETEMPLATEGROUP;
            $this->request->data['Servicetemplate'] = $this->request->data['Servicetemplategroup']['Servicetemplate'];

            if ($this->Servicetemplategroup->saveAll($this->request->data)) {
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                }
                $this->setFlash(__('<a href="/servicetemplategroups/edit/%s">Servicetemplategroup</a> successfully saved', $this->Servicetemplategroup->id));
                $this->redirect(['action' => 'index']);
            }
            if ($this->request->ext == 'json') {
                $this->serializeErrorMessage();

                return;
            }
            $this->setFlash(__('Servicetemplategroup could not be saved'), false);
            if (isset($this->request->data['Container']['parent_id'])) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
                $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');
            }
        }

        $this->set(compact(['containers', 'servicetemplates']));
    }

    public function edit($id = null)
    {
        if (!$this->Servicetemplategroup->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplategroup'));
        }

        $servicetemplategroup = $this->Servicetemplategroup->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($servicetemplategroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Servicetemplate'] = $this->request->data['Servicetemplategroup']['Servicetemplate'];
            $this->request->data['Container']['id'] = $this->request->data['Servicetemplategroup']['container_id'];
            if ($this->Servicetemplategroup->saveAll($this->request->data)) {
                $this->setFlash(__('<a href="/servicetemplategroups/edit/%s">Servicetemplategroup</a> successfully saved', $this->Servicetemplategroup->id));
                $this->redirect(['action' => 'index']);
            }
            if (isset($this->request->data['Container']['parent_id'])) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
                $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');
                $selectedServicetemplates = $this->request->data['Servicetemplate'];
            }
            $this->setFlash(__('Servicetemplategroup could not be saved'), false);
        } else {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($servicetemplategroup['Container']['parent_id']);
            $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');
            $selectedServicetemplates = Hash::extract($servicetemplategroup['Servicetemplate'], '{n}.id');
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICETEMPLATEGROUP, [], $this->hasRootPrivileges);
        }
        $this->request->data['Servicetemplate'] = $selectedServicetemplates;
        $this->request->data = Hash::merge($servicetemplategroup, $this->request->data);

        $this->set(compact(['containers', 'servicetemplates', 'servicetemplategroup']));
    }

    public function allocateToHost($id = null)
    {
        if (!$this->Servicetemplategroup->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplategroup'));
        }

        $servicetemplateCache = [];
        $this->loadModel('Host');
        if ($this->request->is('post') || $this->request->is('put')) {
            $userId = $this->Auth->user('id');
            //Checking if target host exists
            if ($this->Host->exists($this->request->data('Service.host_id'))) {
                $this->loadModel('Service');
                $this->loadModel('Servicetemplate');
                $host = $this->Host->findById($this->request->data('Service.host_id'));
                App::uses('UUID', 'Lib');
                foreach ($this->request->data('Service.ServicesToAdd') as $servicetemplateIdToAdd) {
                    if (!isset($servicetemplateCache[$servicetemplateIdToAdd])) {
                        $servicetemplateCache[$servicetemplateIdToAdd] = $this->Servicetemplate->findById($servicetemplateIdToAdd);
                    }
                    $servicetemplate = $servicetemplateCache[$servicetemplateIdToAdd];
                    $service = [];
                    $service['Service']['uuid'] = UUID::v4();
                    $service['Service']['host_id'] = $host['Host']['id'];
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
                            $host['Host']['name'].'/'.$servicetemplate['Servicetemplate']['name'],
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

        $servicetemplategroup = $this->Servicetemplategroup->findById($id);
        if ($this->hasRootPrivileges === true) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            $hosts = $this->Host->hostsByContainerId($containerIds, 'list');
        } else {
            $hosts = $this->Host->hostsByContainerId($this->getWriteContainers(), 'list');
        }
        $this->set(compact(['servicetemplategroup', 'hosts']));
        $this->set('back_url', $this->referer());
    }

    public function allocateToHostgroup($id = null)
    {
        $this->loadModel('Hostgroup');
        if ($this->request->is('post') || $this->request->is('put')) {
            $userId = $this->Auth->user('id');
            $this->loadModel('Host');
            $this->loadModel('Service');
            $this->loadModel('Servicetemplate');
            App::uses('UUID', 'Lib');
            $servicetemplateCache = [];
            if ($this->Hostgroup->exists($this->request->data('Hostgroup.id'))) {
                foreach ($this->request->data('Host') as $host_id => $servicesToAdd) {
                    if ($this->Host->exists($host_id)) {
                        $host = $this->Host->findById($host_id);
                        if (isset($host['Service'])) {
                            unset($host['Service']);
                        }
                        foreach ($servicesToAdd['ServicesToAdd'] as $servicetemplate_id) {
                            if (!isset($servicetemplateCache[$servicetemplate_id])) {
                                $servicetemplateCache[$servicetemplate_id] = $this->Servicetemplate->findById($servicetemplate_id);
                            }
                            $servicetemplate = $servicetemplateCache[$servicetemplate_id];
                            $service = [];
                            $service['Service']['uuid'] = UUID::v4();
                            $service['Service']['host_id'] = $host['Host']['id'];
                            $service['Service']['servicetemplate_id'] = $servicetemplate['Servicetemplate']['id'];
                            //$service['Host'] = $host['Host'];

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
                                    $host['Host']['name'].'/'.$servicetemplate['Servicetemplate']['name'],
                                    $service
                                );
                                if ($changelog_data) {
                                    CakeLog::write('log', serialize($changelog_data));
                                }
                            }
                        }
                    }
                }
                $this->setFlash(__('Services successfully created'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Invalide hostgroup'), false);
            }
        }

        $servicetemplategroup = $this->Servicetemplategroup->findById($id);
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);

        if ($this->hasRootPrivileges === true) {
            $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
        } else {
            $hostgroups = $this->Hostgroup->hostgroupsByContainerId($this->getWriteContainers(), 'list', 'id');
        }

        $this->Frontend->setJson('servicetemplategroup_id', $servicetemplategroup['Servicetemplategroup']['id']);
        $this->Frontend->setJson('service_exists', __('Service already exist on selected host. Tick the box to create duplicate.'));
        $this->Frontend->setJson('service_disabled', __('Service already exist on selected host but is disabled. Tick the box to create duplicate.'));
        $this->set(compact(['hostgroups', 'servicetemplategroup']));
        $this->set('back_url', $this->referer());
    }

    public function allocateToMatchingHostgroup($servicetemplategroup_id)
    {
        if (!$this->Servicetemplategroup->exists($servicetemplategroup_id)) {
            throw new NotFoundException(__('Invalid hostgroup'));
        }

        $userId = $this->Auth->user('id');
        $servicetemplategroup = $this->Servicetemplategroup->find('first', [
            'contain'    => [
                'Container',
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                    ],
                ],
            ],
            'conditions' => [
                'Servicetemplategroup.id' => $servicetemplategroup_id,
            ],
        ]);

        //Get all hostgroups to user is allowed to see
        $this->loadModel('Hostgroup');
        $this->loadModel('Host');
        $this->loadModel('Service');
        $query = [
            'contain'    => [
                'Container',
                'Host' => [
                    'fields' => [
                        'Host.id',
                    ],
                ],
            ],
            'order'      => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS,
                'Container.name'      => $servicetemplategroup['Container']['name'],
            ],
            'fields'     => [
                'Hostgroup.id',
                'Hostgroup.container_id',
                'Container.id',
                'Container.name',
                'Container.parent_id',
            ],
        ];
        $hostgroup = $this->Hostgroup->find('first', $query);
        if (empty($hostgroup)) {
            $this->setFlash(__('Could not found any hostgroup matching to %s', $servicetemplategroup['Container']['name']), false);
            $this->redirect(['action' => 'index']);
        }

        $servicetemplateCache = [];

        foreach ($hostgroup['Host'] as $_host) {
            $host = $this->Host->find('first', [
                'contain'    => [
                    'Service' => [
                        'fields' => [
                            'Service.servicetemplate_id',
                        ],
                    ],
                ],
                'conditions' => [
                    'Host.id'       => $_host['id'],
                    'Host.disabled' => 0,
                ],
                'fields'     => [
                    'Host.id',
                    'Host.uuid',
                    'Host.name',
                    'Host.container_id',
                ],
            ]);

            if (!empty($host)) {
                $existingServiceTemplates = Hash::extract($host, 'Service.{n}.servicetemplate_id');
                foreach ($servicetemplategroup['Servicetemplate'] as $_servicetemplate) {
                    //Check if we need to create this service
                    if (in_array($_servicetemplate['id'], $existingServiceTemplates)) {
                        continue;
                    }

                    if (!isset($servicetemplateCache[$_servicetemplate['id']])) {
                        $servicetemplateCache[$_servicetemplate['id']] = $this->Servicetemplate->findById($_servicetemplate['id']);
                    }
                    $servicetemplate = $servicetemplateCache[$_servicetemplate['id']];

                    $service = [];
                    $service['Service']['uuid'] = UUID::v4();
                    $service['Service']['host_id'] = $host['Host']['id'];
                    $service['Service']['servicetemplate_id'] = $servicetemplate['Servicetemplate']['id'];
                    //$service['Host'] = $host['Host'];

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
                            $host['Host']['name'].'/'.$servicetemplate['Servicetemplate']['name'],
                            $service
                        );
                        if ($changelog_data) {
                            CakeLog::write('log', serialize($changelog_data));
                        }
                    }
                }
            }
        }
        $this->setFlash(__('Services successfully created'));
        $this->redirect(['action' => 'index']);
    }

    public function getHostsByHostgroupByAjax($id_hostgroup, $servicetemplategroup_id)
    {
        $this->loadModel('Hostgroup');
        $this->loadModel('Host');
        $excludeHostIds = [];
        if (!$this->Hostgroup->exists($id_hostgroup)) {
            throw new NotFoundException(__('Invalid hostgroup'));
        }

        if (!$this->Servicetemplategroup->exists($servicetemplategroup_id)) {
            throw new NotFoundException(__('Invalid servicetemplategroup'));
        }

        $servicetemplategroup = $this->Servicetemplategroup->findById($servicetemplategroup_id);
        $servicetemplategroup['Servicetemplate'] = Hash::sort($servicetemplategroup['Servicetemplate'], '{n}.name', 'asc');
        $hostgroup = $this->Hostgroup->findById($id_hostgroup);
        $hosts = [];
        foreach ($hostgroup['Host'] as $host) {
            //Find host + Services
            $hosts[] = $this->Host->find('first',[
                'recursive' => -1,
                'contain' => [
                    'Service' => [
                        'Servicetemplate'
                    ]
                ],
                'conditions' => [
                    'Host.id' => $host['id']
                ]
            ]);
            $excludeHostIds[] = $host['id'];
        }
        $hostTemlateIds = Hash::extract($hostgroup, 'Hosttemplate.{n}.id');
        if(!empty($hostTemlateIds)){
            $hostsByHosttemplateIds = $this->Host->find('all', [
                'recursive' => -1,
                'contain' => [
                    'Service' => [
                        'Servicetemplate'
                    ],
                    'Hostgroup',
                    'Hosttemplate' => [
                        'Hostgroup'
                    ]
                ],
                'conditions' => [
                    'Host.hosttemplate_id' => $hostTemlateIds,
                    'NOT' => [
                        'Host.id' => $excludeHostIds
                    ]
                ]
            ]);
            foreach($hostsByHosttemplateIds as $host){
                if(empty($host['Hostgroup']) && !empty($host['Hosttemplate']['Hostgroup'])){
                    $hosts[] = $host;
                }
            }
        }
        $this->set(compact(['servicetemplategroup', 'hosts']));
        $this->set('_serialize', ['servicetemplategroup', 'hosts']);
    }

    public function delete($id = null)
    {
        if (!$this->Servicetemplategroup->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplategroup'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $servicetemplategroup = $this->Servicetemplategroup->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($servicetemplategroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        $this->Servicetemplategroup->id = $id;
        if ($this->Servicetemplategroup->delete()) {
            $this->setFlash(__('Servicetemplategroup deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete servicetemplategroup'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadServicetemplatesByContainerId($containerId = null)
    {
        $this->allowOnlyAjaxRequests();
        if (!$this->Container->exists($containerId)) {
            throw new NotFoundException(__('Invalid hosttemplate'));
        }

        $containerId = $this->Tree->resolveChildrenOfContainerIds($containerId);
        $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerId, 'list');
        $servicetemplates = $this->Servicetemplategroup->makeItJavaScriptAble($servicetemplates);

        $this->set(compact(['servicetemplates']));
        $this->set('_serialize', ['servicetemplates']);
    }
}
