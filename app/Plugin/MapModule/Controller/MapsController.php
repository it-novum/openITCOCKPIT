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

use itnovum\openITCOCKPIT\Filter\MapFilter;

class MapsController extends MapModuleAppController {

    public $layout = 'angularjs';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors'
    ];

    public $helpers = [
        'CustomValidationErrors',
        'ListFilter.ListFilter'
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Map.name'  => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Map.title' => ['label' => 'Title', 'searchType' => 'wildcard'],
            ],
        ]
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $MapFilter = new MapFilter($this->request);

        $query = [
            'conditions' => $MapFilter->indexFilter(),
            'fields'     => [
                'Map.*',
            ],
            'joins'      => [
                [
                    'table'      => 'maps_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'MapsToContainers',
                    'conditions' => 'MapsToContainers.map_id = Map.id',
                ],
            ],
            'order'      => $MapFilter->getOrderForPaginator('Map.name', 'asc'),
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.id',
                    ],
                ],
            ],
            'group'      => 'Map.id',
            'limit'      => $this->Paginator->settings['limit']
        ];

        if (!$this->hasRootPrivileges) {
            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
        }


        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $all_maps = $this->Map->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $MapFilter->getPage();
            $all_maps = $this->Paginator->paginate();
        }

        foreach ($all_maps as $key => $all_map) {
            $all_maps[$key]['Map']['allowEdit'] = false;
            $all_maps[$key]['Map']['allowCopy'] = false;
            if ($this->hasRootPrivileges == true) {
                $all_maps[$key]['Map']['allowEdit'] = true;
                $all_maps[$key]['Map']['allowCopy'] = true;
                continue;
            }
            foreach ($all_map['Container'] as $cKey => $container) {
                if ($this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT) {
                    $all_maps[$key]['Map']['allowEdit'] = true;
                    $all_maps[$key]['Map']['allowCopy'] = true;
                    continue;
                }
            }
        }
        $this->set('all_maps', $all_maps);
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_maps', 'paging']);
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

        $map = $this->Map->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Map.id' => $id
            ],
            'contain'    => [
                'Container' => [
                    'fields' => ['id', 'name'],
                ]
            ]
        ]);

        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        $this->set('_serialize', ['map']);
        $this->set(compact('map'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Map']['id'] = $id;

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
                $this->setFlash(__('<a href="/map_module/maps/edit/%s">Map</a> successfully saved', $this->Map->id));

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
            $this->set('message', __('Map deleted successfully'));
            $this->set('_serialize', ['message']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('message', __('Could not delete Map'));
        $this->set('_serialize', ['message']);
    }


    public function copy($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $sourceMap = $this->Map->find('first', [
            //'recursive' => -1,
            'conditions' => [
                'Map.id' => $id
            ],
        ]);


        $this->set('sourceMap', $sourceMap);
        $this->set('_serialize', ['sourceMap']);

        if ($this->request->is('post') || $this->request->is('put')) {

            $newMapData = $this->request->data;

            if (empty($newMapData['Map']['refresh_interval'])) {
                $newMapData['Map']['refresh_interval'] = 90000;
            } else {
                if ($newMapData['Map']['refresh_interval'] < 10) {
                    $newMapData['Map']['refresh_interval'] = 10;
                }
                $newMapData['Map']['refresh_interval'] = ((int)$newMapData['Map']['refresh_interval'] * 1000);
            }

            $sourceMapContainer = Hash::extract($sourceMap['Container'], '{n}.id');
            $newMap = [];
            $newMap['Map'] = [
                'name'             => $newMapData['Map']['name'],
                'title'            => $newMapData['Map']['title'],
                'background'       => $sourceMap['Map']['background'],
                'refresh_interval' => $newMapData['Map']['refresh_interval'],
                'container_id'     => $sourceMapContainer
            ];
            $newMap['Container'] = $sourceMapContainer;

            $this->Map->create();
            $newMapId = $this->Map->id;
            $newMapElements = $this->Map->transformForCopy($sourceMap, $newMapId);
            $newMap = array_merge($newMap, $newMapElements);

            if ($this->Map->saveAll($newMap)) {
                if ($this->request->ext === 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/map_module/maps/edit/%s">Map</a> successfully copied', $this->Map->id));
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
}