<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\LocationsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\TenantsTable;
use App\Model\Table\UsersTable;
use Cake\Cache\Cache;
use Cake\Core\Plugin;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;


/**
 * Class ContainersController
 * @property Container $Container
 */
class ContainersController extends AppController {

    public function index() {
        return;
    }


    /**
     * @param null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$ContainersTable->existsById($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        $container = $ContainersTable->get($id);
        if (!$this->allowedByContainerId($container->get('id'))) {
            throw new ForbiddenException('403 Forbidden');
        }

        $this->set('container', $container);
        $this->viewBuilder()->setOption('serialize', ['container']);
    }


    public function add() {
        if ($this->request->is('GET')) {
            //Only ship HTML Template
            return;
        }

        if (!$this->request->is('post') && !$this->request->is('put')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->isJsonRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $container = $ContainersTable->newEntity($this->request->getData('Container'));

        if (!$this->isWritableContainer($container->get('parent_id'))) {
            $this->render403();
            return;
        }

        $ContainersTable->save($container);
        if ($container->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->serializeCake4ErrorMessage($container);
            return;
        }

        Cache::clear('permissions');
        $this->serializeCake4Id($container);
    }

    public function edit() {
        if (!$this->isAngularJsRequest()) {
            return;
        }
        if ($this->request->is('post')) {

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
            $containerId = (int)$this->request->getData('Container.id', 0);

            if (!$ContainersTable->existsById($containerId)) {
                throw new NotFoundException(__('Invalid container'));
            }
            $container = $ContainersTable->get($containerId);

            if (!$this->isWritableContainer($container->get('id'))) {
                $this->render403();
                return;
            }

            $container->setAccess('id', false);
            $container->setAccess('parent_id', false);
            $container = $ContainersTable->patchEntity($container, $this->request->getData('Container'));

            $ContainersTable->save($container);
            if ($container->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->serializeCake4ErrorMessage($container);
                return;
            }

            Cache::clear('permissions');
            $this->serializeCake4Id($container);
        }
    }

    /**
     * THIS IS ONLY FOR ContainersIndex Action !!!
     */
    public function loadContainersByContainerId($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        if(!$this->hasRootPrivileges && intval($id) === ROOT_CONTAINER){
            $this->render403();
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var  $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');
        /** @var  $LocationsTable LocationsTable */
        $LocationsTable = TableRegistry::getTableLocator()->get('Locations');
        /** @var  $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var  $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var  $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        /** @var  $ServicetemplategroupsTable ServicetemplategroupsTable */
        $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

        if (!$ContainersTable->existsById($id)) {
            throw new NotFoundException(__('Container not found'));
        }

        $parent = [$ContainersTable->get($id)->toArray()];

        $parent[0]['allowEdit'] = false;

        if (isset($this->MY_RIGHTS_LEVEL[$parent[0]['id']])) {
            if ((int)$this->MY_RIGHTS_LEVEL[$parent[0]['id']] === WRITE_RIGHT) {
                $parent[0]['allowEdit'] = true;
                $parent[0]['elements'] = ($parent[0]['rght'] - $parent[0]['lft']) / 2 - 0.5;;
            }
        }
        $containers = $ContainersTable->getChildren($id);
        foreach ($containers as $key => $container) {
            $containers[$key]['allowEdit'] = false;
            $containerId = $container['id'];
            if (isset($this->MY_RIGHTS_LEVEL[$containerId])) {
                if ((int)$this->MY_RIGHTS_LEVEL[$containerId] === WRITE_RIGHT) {
                    $containers[$key]['allowEdit'] = true;
                    switch ($containers[$key]['containertype_id']) {
                        case CT_GLOBAL:
                            $containers[$key]['linkedId'] = ROOT_CONTAINER;
                            $containers[$key]['elements'] = ($container['rght'] - $container['lft']) / 2 - 0.5;
                            break;
                        case CT_TENANT:
                            $tenantId = $TenantsTable->getTenantIdByContainerId($container['id']);
                            $containers[$key]['linkedId'] = $tenantId;
                            $containers[$key]['elements'] = ($container['rght'] - $container['lft']) / 2 - 0.5;
                            break;
                        case CT_LOCATION:
                            $locationId = $LocationsTable->getLocationIdByContainerId($container['id']);
                            $containers[$key]['linkedId'] = $locationId;
                            $containers[$key]['elements'] = ($container['rght'] - $container['lft']) / 2 - 0.5;
                            break;
                        case CT_NODE:
                            $containers[$key]['linkedId'] = $container['id'];
                            $containers[$key]['elements'] = ($container['rght'] - $container['lft']) / 2 - 0.5;
                            break;
                        case CT_CONTACTGROUP:
                            $contactGroup = $ContactgroupsTable->getContactgroupByContainerId($container['id']);
                            if (!empty($contactGroup)) {
                                $containers[$key]['linkedId'] = $contactGroup['id'];
                                $containers[$key]['contacts'] = sizeof($contactGroup['contacts']);
                            }
                            break;
                        case CT_HOSTGROUP:
                            $hostGroup = $HostgroupsTable->getHostgroupByContainerId($container['id']);
                            if (!empty($hostGroup)) {
                                $containers[$key]['linkedId'] = $hostGroup['id'];
                                $containers[$key]['hosts'] = sizeof($hostGroup['hosts']);
                                $containers[$key]['hosttemplates'] = sizeof($hostGroup['hosttemplates']);
                            }
                            break;
                        case CT_SERVICEGROUP:
                            $serviceGroup = $ServicegroupsTable->getServicegroupByContainerId($container['id']);
                            if (!empty($serviceGroup)) {
                                $containers[$key]['linkedId'] = $serviceGroup['id'];
                                $containers[$key]['services'] = sizeof($serviceGroup['services']);
                                $containers[$key]['servicetemplates'] = sizeof($serviceGroup['servicetemplates']);
                            }
                            break;
                        case CT_SERVICETEMPLATEGROUP:
                            $serviceTemplateGroup = $ServicetemplategroupsTable->getServicetemplategroupByContainerId($container['id']);
                            if (!empty($serviceTemplateGroup)) {
                                $containers[$key]['linkedId'] = $serviceTemplateGroup['id'];
                                $containers[$key]['servicetemplates'] = sizeof($serviceTemplateGroup['servicetemplates']);
                            }
                            break;
                    }
                }
            }
        }
        $hasChilds = true;
        if (empty($containers) && !empty($parent[0])) {
            $containers = $parent[0];
            $hasChilds = false;
        }

        //Add Container alias like in Cake2 for Hash::nest
        $cake2Containers = [];
        foreach ($containers as $container) {
            $cake2Containers[] = [
                'Container' => $container
            ];
        }

        $nest = Hash::nest($cake2Containers);

        $parent['children'] = ($hasChilds) ? $nest : [];

        //Gormat result like CakePHP2 for Frontend
        $result = [
            0 => [
                'Container' => $parent[0],
                'children'  => $parent['children']
            ]
        ];


        $this->set('nest', $result);
        $this->viewBuilder()->setOption('serialize', ['nest']);
    }

    /**
     * THIS IS ONLY FOR ContainersIndex Action !!!
     */
    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->find()
                ->where(['Containers.containertype_id IN' => [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]])
                ->disableHydration()
                ->toArray();
        } else {
            $containers = $ContainersTable->find()
                ->andWhere([
                    'Containers.containertype_id IN' => [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE],
                    'Containers.id IN '              => $this->MY_RIGHTS
                ])
                ->disableHydration()
                ->toArray();
        }

        $paths = [];
        foreach ($containers as $container) {
            $paths[$container['id']] = '/' . $ContainersTable->treePath($container['id'], '/');
        }
        natcasesort($paths);
        if(!$this->hasRootPrivileges){
            unset($paths[ROOT_CONTAINER]);
        }

        $containers = Api::makeItJavaScriptAble($paths);

        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    /**
     * Randers the selectbox with all the nodes and path of the tenant
     * ### Options
     * Please check at ContainersTable->easyPath()
     *
     * @param int $id of the tenant
     * @param array $options Array of options and HTML attributes.
     *
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function byTenantForSelect($id = null, $options = []) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $this->set('paths', $ContainersTable->easyPath($ContainersTable->resolveChildrenOfContainerIds($id), OBJECT_NODE));
        $this->viewBuilder()->setOption('serialize', ['paths']);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
        throw new \RuntimeException('Not implemented');

        $userId = $this->Auth->user('id');

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$ContainersTable->existsById($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        /*
         * cake4 query
         *
            $rootContainer = $ContainersTable->find()
                ->where([
                    'Containers.id' => $id,
                ])
                ->first();

            $childElements = $ContainersTable->find()
                ->where([
                    'AND' => [
                        ['(Containers.lft BETWEEN :lft1 AND :rght1)'],
                        ['(Containers.rght BETWEEN :lft1 AND :rght1)'],
                        'Containers.containertype_id IN' => [
                            CT_LOCATION,
                            CT_NODE,
                            CT_HOSTGROUP,
                            CT_SERVICEGROUP,
                            CT_CONTACTGROUP,
                        ]
                    ]
                ])
                ->bind(':lft1', $rootContainer->get('lft'), 'integer')
                ->bind(':rght1', $rootContainer->get('rght'), 'integer')
                ->disableHydration()
                ->all();
            debug($childElements->toArray());
         */

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
                            'conditions' => [
                                'Host.container_id' => $containerIds
                            ]
                        ]);
                        $hostIds = Hash::extract($hostsToDelete, '{n}.Host.id');
                        $allowDelete = $this->Container->__allowDelete($hostIds);
                        $allowDeleteRoot = $allowDelete;

                        //Check users to delete
                        $usersToDelete = $UsersTable->getUsersToDeleteByContainerIds($containerIds);
                        if ($allowDelete) {
                            foreach ($usersToDelete as $user) {
                                /** @var User $user */
                                $UsersTable->delete($user);
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
                        foreach ($hostgroupsToDelete as $containerId) {
                            $this->Container->__delete($containerId);
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
                        foreach ($servicegroupsToDelete as $containerId) {
                            $this->Container->__delete($containerId);
                        }
                        break;
                    case CT_CONTACTGROUP:
                        //Check contact groups to delete

                        /** @var $ContactgroupsTable ContactgroupsTable */
                        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
                        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerIdsForContainerDelete($containerIds);
                        foreach ($contactgroups as $contactgroup) {
                            $this->Container->__delete($contactgroup['container']['id']);
                        }
                        break;
                }
            }
        }
        Cache::clear('permissions');
        if ($allowDeleteRoot) {
            if ($this->Container->__delete($id)) {
                Cache::clear('permissions');
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            } else {
                $this->response = $this->response->withStatus(500);
                $this->set('success', false);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }
        }
        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function loadContainersForAngular() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $onlyWithWritePermissions = $this->request->getQuery('onlyWritePermissions') === 'true';

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers =
                $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        if ($onlyWithWritePermissions === true) {
            foreach ($containers as $containerId => $containerName) {
                if (!isset($this->MY_RIGHTS_LEVEL[$containerId]) || $this->MY_RIGHTS_LEVEL[$containerId] !== WRITE_RIGHT) {
                    unset($containers[$containerId]);
                }
            }
        }

        $containers = Api::makeItJavaScriptAble($containers);
        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function showDetails($id = null) {
        if (!$this->isApiRequest() && $id === null) {
            //Only ship HTML template for angular
            return;
        }
        if (!$this->allowedByContainerId($id)) {
            $this->render403();
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$ContainersTable->existsById($id)) {
            throw new NotFoundException(__('Invalid container'));
        }

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $container = $ContainersTable->getContainerById($id, $MY_RIGHTS);
        $subContainers = $ContainersTable->getContainerWithAllChildren($id);
//debug($container);
//debug($subContainers);
//die();
        $containerMap = $ContainersTable->getContainerMap($container, $subContainers);
//debug($containerMap);die();
//return;


        $this->set('containerMap', $containerMap);
        $this->viewBuilder()->setOption('serialize', ['containerMap']);


        return;

        if (Plugin::isLoaded('DistributeModule')) {
            $SatelliteModel = $ModuleManager->loadModel('Satellite');
            $satellites = $SatelliteModel->find('all', [
                'recursive'  => -1,
                'fields'     => [
                    'Satellite.id',
                    'Satellite.name'
                ],
                'conditions' => [
                    'Satellite.container_id' => $id
                ],
                'order'      => [
                    'Satellite.name' => 'asc'
                ]
            ]);
            if (!empty($satellites)) {
                $containerDetails['Satellite'] = $satellites;
            }
        }
        $this->set(compact(['containerDetails']));
        $this->viewBuilder()->setOption('serialize', ['containerDetails']);

    }
}
