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
 * Class ContainersController
 * @property Container $Container
 */
class ContainersController extends AppController
{
    public $layout = 'Admin.default';
    //public $components = array('Paginator', 'ListFilter.ListFilter','RequestHandler');
    public $helpers = ['Nest'];

    public function index()
    {
        if ($this->isApiRequest()) {
            $all_container = $this->Container->find('all', [
                'recursive' => -1,
            ]);
            $this->set('all_container', $all_container);
            $this->set('_serialize', ['all_container']);
            $this->render();
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container']['containertype_id'] = CT_NODE;
            if ($this->Container->save($this->request->data)) {
                $this->setFlash(__('new node created successfully'));
            } else {
                $this->setFlash(__('error while saving data'), false);
            }
        }
        $all_containers = $this->Paginator->paginate();
        $this->set(compact(['all_containers']));
        $this->set('_serialize', ['all_containers']);

        $tenants = $this->Container->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'containertype_id' => CT_TENANT,
            ],
            'order'      => ['name ASC'],
            'fields'     => ['id', 'containertype_id', 'name', 'parent_id'],
        ]);
        $tenants_select = [];
        foreach ($tenants as $tenant) {
            $tenants_select[$tenant['Container']['id']] = $tenant['Container']['name'];
        }
        $tenants = $tenants_select;
        unset($tenants_select);
        $this->set(compact(['tenants']));

        $selected_tenant = null;
        if (isset($this->request->data['Container']['selected_tenant'])) {
            $selected_tenant = $this->request->data['Container']['selected_tenant'];
        }
        $this->set('validationError', (!empty($this->Container->validationErrors) ? true : false));
        $this->set('selected_tenant', $selected_tenant);
    }

    public function nest()
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $all_container = $this->Container->find('all', [
            'recursive' => -1,
        ]);
        $all_container = Hash::nest($all_container);
        $this->set('all_container', $all_container);
        $this->set('_serialize', ['all_container']);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Container->exists($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        $container = $this->Container->findById($id);
        if (!$this->allowedByContainerId($container['Container']['id'])) {
            throw new ForbiddenException('404 Forbidden');
        }

        $this->set('container', $container);
        $this->set('_serialize', ['container']);
    }

    protected function tree($id = 0)
    {
        debug($this->Container->generateTreeList());
    }

    public function add()
    {
        if (!$this->request->is('post') && !$this->request->is('put') && $this->request->ext == 'json') {
            return;
        }
        if ($this->request->ext == 'json') {
            if ($this->Container->saveAll($this->request->data)) {
                $this->serializeId();

                return;
            }
            $this->serializeErrorMessage();
        } else {
            if ($this->Container->save($this->request->data)) {
                $this->setFlash(__('new node created successfully'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('error while saving data'), false);
                $this->redirect(['action' => 'index']);
            }
        }
    }

    /**
     * recovers the container tree if left and/or right is missing or broken
     * Wrapper public function of CakePHP´s TreeBehavior::recover
     *
     * @param string $mode
     * @param        array $$missingParentAction
     *
     * @link  http://book.cakephp.org/2.0/en/core-libraries/behaviors/tree.html#TreeBehavior::recover
     * @since 3.0
     */
    protected function recover($mode = 'parent', $missingParentAction = null)
    {
        $this->Container->recover($mode, $missingParentAction);
    }

    /**
     * Is called by AJAX to rander the nest list in Nodes
     *
     * @param int $id the id of the tenant
     *
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function byTenant($id = null)
    {
        $this->allowOnlyAjaxRequests();
        if (!$this->Container->hasAny()) {
            throw new NotFoundException(__('tenant.notfound'));
        }
        $parent = $this->Container->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'id' => $id,
            ],
        ]);
        $nest = Hash::nest($this->Container->children($id, false, null, 'name'));
        $parent[0]['children'] = $nest;
        $this->set('nest', $parent);
    }

    /**
     * Randers the selectbox with all the nodes and path of the tenant
     * ### Options
     * Please check at Tree->easyPath()
     *
     * @param int   $id      of the tenant
     * @param array $options Array of options and HTML attributes.
     *
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function byTenantForSelect($id = null, $options = [])
    {
        $this->allowOnlyAjaxRequests();

        $this->set('paths', $this->Tree->easyPath($this->Tree->resolveChildrenOfContainerIds($id), OBJECT_NODE));
    }

    public function delete($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->Container->exists($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        $rootContainer = $this->Container->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Container.id' => $id,
            ],
        ]);
        $childElements = $this->Container->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'AND' => [
                    'Container.lft BETWEEN ? AND ?'  => [$rootContainer['Container']['lft'], $rootContainer['Container']['rght']],
                    'Container.rght BETWEEN ? AND ?' => [$rootContainer['Container']['lft'], $rootContainer['Container']['rght']],
                    'Container.containertype_id'     => [
                        CT_LOCATION,
                        CT_NODE,
                        CT_HOSTGROUP,
                        CT_SERVICEGROUP,
                        CT_CONTACTGROUP,
                    ],
                ],
            ],
        ]);
        $allowDeleteRoot = true;
        $childContainers = Hash::combine($childElements, '{n}.Container.id', '{n}.Container.name', '{n}.Container.containertype_id');
        if (is_array($childContainers) && !empty($childContainers)) {
            foreach ($childContainers as $containerTypeId => $containers) {
                $containerIds = array_keys($containers);
                switch ($containerTypeId) {
                    case CT_NODE:
                        //Check hosts to delete
                        $Host = ClassRegistry::init('Host');
                        $hostsToDelete = $Host->find('all', [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'hosts_to_containers',
                                    'alias'      => 'HostsToContainers',
                                    'type'       => 'LEFT',
                                    'conditions' => [
                                        'HostsToContainers.host_id = Host.id',
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'HostsToContainers.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'Host.id',
                                'Host.name',
                                'Host.uuid',
                                'Host.hosttemplate_id',
                                'Host.description',
                                'Host.container_id',
                            ],
                        ]);
                        $hostIds = Hash::extract($hostsToDelete, '{n}.Host.id');
                        $allowDelete = $this->Container->__allowDelete($hostIds);
                        $allowDeleteRoot = $allowDelete;

                        //Check users to delete
                        $User = ClassRegistry::init('User');
                        $usersToDelete = $this->User->find('all', [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'users_to_containers',
                                    'type'       => 'LEFT',
                                    'alias'      => 'UsersToContainer',
                                    'conditions' => 'UsersToContainer.user_id = User.id',
                                ],
                            ],
                            'conditions' => [
                                'UsersToContainer.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'User.id',
                            ],
                        ]);
                        if ($allowDelete) {
                            foreach ($usersToDelete as $user) {
                                $User->__delete($user, $userId);
                            }
                        }

                        //Check satellites to delete
                        if (in_array('DistributeModule', $modulePlugins)) {
                            $Satellite = ClassRegistry::init('DistributeModule.Satellite');
                            $satellitesToDelete = $Satellite->find('all', [
                                'recursive'  => -1,
                                'joins'      => [
                                    [
                                        'table'      => 'containers',
                                        'alias'      => 'Container',
                                        'type'       => 'INNER',
                                        'conditions' => [
                                            'Container.id = Satellite.container_id',
                                        ],
                                    ],
                                ],
                                'conditions' => [
                                    'Satellite.container_id' => $containerIds,
                                ],

                                'fields' => [
                                    'Satellite.id',
                                    'Container.id',
                                ],
                            ]);
                            if ($allowDelete) {
                                foreach ($satellitesToDelete as $satellite) {
                                    $Satellite->__delete($satellite, $userId);
                                }
                            }
                        }

                        if ($allowDelete) {
                            foreach ($hostsToDelete as $host) {
                                $Host->__delete($host, $userId);
                            }
                        }
                        break;
                    case CT_LOCATION:
                        //Check locations to delete
                        $Location = ClassRegistry::init('Location');
                        $locationsToDelete = $Location->find('all', [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'containers',
                                    'alias'      => 'Container',
                                    'type'       => 'INNER',
                                    'conditions' => [
                                        'Container.id = Location.container_id',
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'Location.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'Location.id',
                                'Container.id',
                            ],
                        ]);
                        foreach ($locationsToDelete as $location) {
                            $Location->__delete($location, $userId);
                        }
                        break;
                    case CT_HOSTGROUP:
                        //Check host groups to delete
                        $Hostgroup = ClassRegistry::init('Hostgroup');
                        $hostgroupsToDelete = $Hostgroup->find('all', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.id',
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'Hostgroup.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'Hostgroup.id',
                            ],
                        ]);
                        foreach ($hostgroupsToDelete as $container) {
                            $this->Container->__delete($container, $userId);
                        }
                        break;
                    case CT_SERVICEGROUP:
                        //Check service groups to delete
                        $Servicegroup = ClassRegistry::init('Servicegroup');
                        $servicegroupsToDelete = $Servicegroup->find('all', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.id',
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'Servicegroup.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'Servicegroup.id',
                            ],
                        ]);
                        foreach ($servicegroupsToDelete as $container) {
                            $this->Container->__delete($container, $userId);
                        }
                        break;
                    case CT_CONTACTGROUP:
                        //Check contact groups to delete
                        $Contactgroup = ClassRegistry::init('Contactgroup');
                        $contactgroupsToDelete = $Contactgroup->find('all', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.id',
                                    ],
                                ],
                            ],
                            'conditions' => [
                                'Contactgroup.container_id' => $containerIds,
                            ],
                            'fields'     => [
                                'Contactgroup.id',
                            ],
                        ]);
                        foreach ($contactgroupsToDelete as $container) {
                            $this->Container->__delete($container, $userId);
                        }
                        break;
                }
            }
        }
        if ($allowDeleteRoot) {
            if ($this->Container->__delete($rootContainer, $userId)) {
                $this->setFlash(__('Container deleted'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not delete container'), false);
                $this->redirect(['action' => 'index']);
            }
        }
        $this->setFlash(__('Could not delete container'), false);
        $this->redirect(['action' => 'index']);

    }
}
