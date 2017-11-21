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

class MapsController extends MapModuleAppController {

    public $layout = 'angularjs';
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors'
    ];

    public $helpers = [
        'CustomValidationErrors',
        'ListFilter.ListFilter'
    ];

    //public $uses = ['Tenant'];

    public $listFilters = ['index' => [
        'fields' => [
            'Map.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
            'Map.title' => ['label' => 'Title', 'searchType' => 'wildcard'],
            //'Tenant.name' => array('label' => 'Contact', 'searchType' => 'wildcard'),
        ],
    ]];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }
        $query = [
            'conditions' => ['MapsToContainers.container_id' => $this->MY_RIGHTS],
            'fields' => [
                'Map.*',
            ],
            'joins' => [
                [
                    'table' => 'maps_to_containers',
                    'type' => 'INNER',
                    'alias' => 'MapsToContainers',
                    'conditions' => 'MapsToContainers.map_id = Map.id',
                ],
            ],
            'order' => [
                'Map.name' => 'asc',
            ],
            'contain' => [
                'Container' => [
                    'fields' => [
                        'Container.id',
                    ],
                ],
            ],
            'group' => 'Map.id',
        ];



        if (!isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = [];
        }
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_maps = $this->Map->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings, $query);
            $all_maps = $this->Paginator->paginate();
        }

        foreach($all_maps as $key => $all_map){
            $all_maps[$key]['Map']['allowEdit'] = false;
            if($this->hasRootPrivileges == true){
                $all_maps[$key]['Map']['allowEdit'] = true;
                continue;
            }
            foreach ($all_map['Container'] as $cKey => $container){
                if($this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT){
                    $all_maps[$key]['Map']['allowEdit'] = true;
                    continue;
                }
            }
        }
        $this->set('all_maps', $all_maps);
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_maps']);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container'] = $this->request->data['Map']['container_id'];

            if (empty($this->request->data['Map']['refresh_interval'])) {
                $this->request->data['Map']['refresh_interval'] = 90000;
            } else {
                if ($this->request->data['Map']['refresh_interval'] < 10) {
                    $this->request->data['Map']['refresh_interval'] = 10;
                }

                $this->request->data['Map']['refresh_interval'] = ((int)$this->request->data['Map']['refresh_interval'] * 1000);
            }

            if ($this->Map->saveAll($this->request->data)) {
                if ($this->request->ext === 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/map_module/maps/edit/%s">Map</a> successfully saved', $this->Map->id));
                    }
                    $this->serializeId();
                    return;
                }
            } else {
                if ($this->request->ext === 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
                $this->setFlash(__('could not save data'), false);
            }
        }
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = $this->Container->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Map->exists($id)) {
            throw new NotFoundException(__('Invalid map'));
        }
        $this->loadModel('Tenant');
        $this->Map->recursive = -1;
        $this->Map->autoFields = false;


        $this->Map->contain([
            'Container' => [
                'fields' => ['id', 'name'],
            ],
        ]);

        $map = $this->Map->findById($id);

        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');

        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }

        //'Tenant.description' <-> Container.name
        $tenants = Set::combine($this->Tenant->find('all', [

                'fields' => [
                    'Container.id',
                    'Container.name',
                ],
                'order' => 'Container.name ASC',
            ]
        ),
            '{n}.Container.id', '{n}.Container.name'
        );
        $container = $this->Tree->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges, []);
        $this->set(compact('map', 'container'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container'] = $this->request->data['Map']['container_id'];

            if (empty($this->request->data['Map']['refresh_interval'])) {
                $this->request->data['Map']['refresh_interval'] = 90000;
            } else {
                if ($this->request->data['Map']['refresh_interval'] < 10) {
                    $this->request->data['Map']['refresh_interval'] = 10;
                }

                $this->request->data['Map']['refresh_interval'] = ((int)$this->request->data['Map']['refresh_interval'] * 1000);
            }

            if ($this->Map->saveAll($this->request->data)) {
                if ($this->isJsonRequest()) {
                    $this->serializeId();

                    return;
                }
                $this->setFlash(__('Map properties successfully saved'));
                $this->redirect(['action' => 'index']);

            } else {
                if ($this->request->ext === 'json') {
                    $this->serializeErrorMessage();

                    return;
                }
                $this->setFlash(__('could not save data'), false);
            }
        }
    }

    public function delete($id = null) {
        if (!$this->Map->exists($id)) {
            throw new NotFoundException(__('Invalid Map'));
        }

        $map = $this->Map->findById($id);
        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Map->delete($id, true)) {
            $this->setFlash(__('Map deleted'));
            $this->redirect(['action' => 'index']);
        }

        $this->setFlash(__('Could not delete map'), false);
        $this->redirect(['action' => 'index']);
    }

    public function mass_delete() {
        $userId = $this->Auth->user('id');
        foreach (func_get_args() as $mapId) {
            if (!$this->Map->exists($mapId)) {
                throw new NotFoundException(__('Invalid Map'));
            }
            $map = $this->Map->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Map.id' => $mapId,
                ],
                'fields' => [
                    'Map.name',
                ],
                'contain' => [
                    'Container' => [
                        'fields' => [
                            'Container.id',
                            'Container.parent_id',
                        ],
                    ],
                ],
            ]);

            $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
            if (!$this->allowedByContainerId($containerIdsToCheck)) {
                $this->render403();

                return;
            }

            if ($this->Map->delete($map['Map']['id'], true)) {
                /*	$changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $ids,
                        OBJECT_SERVICEGROUP,
                        $servicegroup['Container']['parent_id'],
                        $userId,
                        $map['Map']['name'],
                        $map
                    );
                    if($changelog_data){
                        CakeLog::write('log', serialize($changelog_data));
                    }
                    */
            }
        }
        $this->setFlash(__('Maps deleted'));
        $this->redirect(['action' => 'index']);
    }
}