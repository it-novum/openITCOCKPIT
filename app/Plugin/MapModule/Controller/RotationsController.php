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
use itnovum\openITCOCKPIT\Filter\RotationFilter;

class RotationsController extends MapModuleAppController {
    public $layout = 'angularjs';
    public $components = [
        'ListFilter.ListFilter',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Rotation.name' => [
                    'label'      => 'Name',
                    'searchType' => 'wildcard'
                ],
            ],
        ],
    ];

    public $uses = [
        'MapModule.Rotation',
        'MapModule.Map'
    ];


    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $RotationFilter = new RotationFilter($this->request);

        $query = [
            'conditions' => $RotationFilter->indexFilter(),
            'order'      => $RotationFilter->getOrderForPaginator('Rotation.name', 'asc'),
            'group'      => 'Rotation.id',
            'limit'      => $this->Paginator->settings['limit'],
            'contain'    => [
                'Map'       => [
                    'fields' => [
                        'Map.id'
                    ]
                ],
                'Container' => [
                    'fields' => [
                        'Container.id'
                    ]
                ]
            ],
            'joins'      => [
                [
                    'table'      => 'rotations_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'RotationsToContainers',
                    'conditions' => 'RotationsToContainers.rotation_id = Rotation.id',
                ],
            ],
        ];

        if (!$this->hasRootPrivileges) {
            $query['conditions']['RotationsToContainers.container_id'] = $this->MY_RIGHTS;
        }

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            unset($query['limit']);
            $all_rotations = $this->Rotation->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $this->Paginator->settings['page'] = $RotationFilter->getPage();
            $all_rotations = $this->Paginator->paginate();
        }

        foreach ($all_rotations as $key => $rotation) {
            $all_rotations[$key]['Rotation']['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $all_rotations[$key]['Rotation']['allowEdit'] = true;
                continue;
            }
            foreach ($rotation['Container'] as $cKey => $container) {
                if ($this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT) {
                    $all_rotations[$key]['Rotation']['allowEdit'] = true;
                    continue;
                }
            }
        }

        //build rotation link
        $link = '';
        foreach ($all_rotations as $key => $rotation) {
            foreach ($rotation['Map'] as $rKey => $map) {
                $link .= 'rotate[' . $rKey . ']:' . $map['id'] . '/';
            }
            $all_rotations[$key]['Rotation']['rotationLink'] = $link;
        }


        $this->set('all_rotations', $all_rotations);
        $this->set('_serialize', ['all_rotations', 'paging']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container'] = $this->request->data['Rotation']['container_id'];

            $this->request->data['Map'] = $this->request->data['Rotation']['Map'];

            if ($this->Rotation->save($this->request->data)) {
                if ($this->request->ext === 'json') {
                    if ($this->isAngularJsRequest()) {
                        $this->setFlash(__('<a href="/map_module/rotations/edit/%s">Rotation</a> successfully saved', $this->Rotation->id));
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

    public function loadMaps() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $MapFilter = new MapFilter($this->request);

        $query = [
            'recursive'  => -1,
            'conditions' => $MapFilter->indexFilter(),
            'fields'     => [
                'Map.id',
                'Map.name',
            ],
            'joins'      => [
                [
                    'table'      => 'maps_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'MapsToContainers',
                    'conditions' => 'MapsToContainers.map_id = Map.id',
                ],
            ],
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.id',
                    ],
                ],
            ],
            'group'      => 'Map.id'
        ];

        if (!$this->hasRootPrivileges) {
            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
        }

        $maps = $this->Map->find('all', $query);

        $maps = Hash::combine($maps, '{n}.Map.id', '{n}.Map.name');
        $maps = $this->Rotation->makeItJavaScriptAble($maps);

        $this->set('maps', $maps);
        $this->set('_serialize', ['maps']);
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
        $containers = $this->Rotation->makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->set('_serialize', ['containers']);
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Rotation->exists($id)) {
            throw new NotFoundException(__('Invalid Map rotation'));
        }

        $rotation = $this->Rotation->findById($id);

        $this->set('_serialize', ['rotation']);
        $this->set(compact('rotation'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Rotation']['id'] = $id;

            if (empty($this->request->data['Rotation']['interval'])) {
                $this->request->data['Rotation']['interval'] = 90;
            } else {
                if ($this->request->data['Rotation']['interval'] < 10) {
                    $this->request->data['Rotation']['interval'] = 10;
                }
            }

            $this->request->data['Map'] = $this->request->data['Rotation']['Map'];
            $this->request->data['Container'] = $this->request->data['Rotation']['container_id'];

            if ($this->Rotation->saveAll($this->request->data)) {
                if ($this->isJsonRequest()) {
                    $this->serializeId();
                    return;
                }
                $this->setFlash(__('<a href="/map_module/rotations/edit/%s">Rotation</a> successfully saved', $this->Rotation->id));

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
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Rotation->exists($id)) {
            throw new NotFoundException(__('Invalid Map rotation'));
        }

        $rotation = $this->Rotation->findById($id);
        $containerIdsToCheck = Hash::extract($rotation, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        if ($this->Rotation->delete($id, true)) {
            $this->set('message', __('Map rotation deleted successfully'));
            $this->set('_serialize', ['message']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('message', __('Could not delete map rotation'));
        $this->set('_serialize', ['message']);

    }
}
