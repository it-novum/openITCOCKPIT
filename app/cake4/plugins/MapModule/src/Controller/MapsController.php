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

namespace MapModule\Controller;

use App\Model\Table\ContainersTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use MapModule\Model\Entity\Map;
use MapModule\Model\Table\MapsTable;

/**
 * Class MapsController
 * @package MapModule\Controller
 */
class MapsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $MapFilter = new MapFilter($this->request);

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $MapFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $all_maps = $MapsTable->getMapsIndex($MapFilter, $PaginateOMat, $MY_RIGHTS);

        $maps = [];
        foreach ($all_maps as $key => $all_map) {
            /** @var Map $all_map */
            $map = $all_map->toArray();
            $map['allowEdit'] = false;
            $map['allowCopy'] = false;
            if ($this->hasRootPrivileges === true) {
                $map['allowEdit'] = true;
                $map['allowCopy'] = true;
                $maps[] = $map;
                continue;
            }

            foreach ($all_map->getContainerIds() as $containerId) {
                if ($this->MY_RIGHTS_LEVEL[$containerId] == WRITE_RIGHT) {
                    $map['allowEdit'] = true;
                    $map['allowCopy'] = true;
                    continue;
                }
            }

            $maps[] = $map;
        }
        $this->set('all_maps', $maps);
        $this->viewBuilder()->setOption('serialize', ['all_maps']);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $data = $this->request->getData();
        if (($this->request->is('post') || $this->request->is('put')) && isset($data['Map'])) {
            //$data['Container'] = $data['Map']['container_id'];

            if (empty($data['Map']['refresh_interval'])) {
                $data['Map']['refresh_interval'] = 90000;
            } else {
                if ($data['Map']['refresh_interval'] < 5) {
                    $data['Map']['refresh_interval'] = 5;
                }

                $data['Map']['refresh_interval'] = ((int)$data['Map']['refresh_interval'] * 1000);
            }

            /** @var MapsTable $MapsTable */
            $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

            $map = $MapsTable->newEmptyEntity();
            $map = $MapsTable->patchEntity($map, $data['Map']);

            /** old code: $this->Map->saveAll($data) */
            $MapsTable->save($map);
            if ($map->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $map->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($map); // REST API ID serialization
                    return;
                }
            }
            $this->set('map', $map);
            $this->viewBuilder()->setOption('serialize', ['map']);
        }
    }

    public function edit($id = null) {
        if (!$this->isApiRequest() && $id === null) {
            //Only ship HTML template for angular
            return;
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid map'));
        }

        $map = $MapsTable->getMapForEdit($id);
        if (!$this->allowedByContainerId($map['Map']['containers']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get')) {
            $this->viewBuilder()->setOption('serialize', ['map']);
            $this->set('map', $map);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData('Map', []);
            if (empty($data['refresh_interval'])) {
                $data['refresh_interval'] = 90000;
            } else {
                if ($data['refresh_interval'] < 5) {
                    $data['refresh_interval'] = 5;
                }

                $data['refresh_interval'] = ((int)$data['refresh_interval'] * 1000);
            }

            $map = $MapsTable->get($id);
            $map->setAccess('id', false);
            $map = $MapsTable->patchEntity($map, $data);

            $MapsTable->save($map);
            if ($map->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $map->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($map); // REST API ID serialization
                    return;
                }
            }
            $this->set('map', $map);
            $this->viewBuilder()->setOption('serialize', ['map']);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Map'));
        }

        /** @var Map $map */
        $map = $MapsTable->find()
            ->contain(['Containers'])
            ->where([
                'Maps.id' => $id
            ])
            ->first();

        if (!$this->allowedByContainerId($map->getContainerIds())) {
            $this->render403();
            return;
        }

        if ($MapsTable->delete($map)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }


    /**
     * @param null $id
     * @deprecated
     * @todo refactor with cake4
     */
    public function copy($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        if ($this->request->is('get')) {
            $map = $this->Map->find('first', [
                //'recursive' => -1,
                'conditions' => [
                    'Map.id' => $id
                ],
            ]);
            if (empty($map)) {
                throw new NotFoundException();
            }
            $this->set('map', $map);
            $this->viewBuilder()->setOption('serialize', ['map']);
            return;
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $map = $this->Map->find('first', [
                //'recursive' => -1,
                'conditions' => [
                    'Map.id' => $id
                ],
            ]);

            if (empty($map)) {
                throw new NotFoundException();
            }

            //Try to save new map
            $newMap = [
                'Map'       => $this->request->data('Map'),
                'Container' => Hash::extract($map, 'Container.{n}.id')
            ];
            $newMap['Map']['background'] = $map['Map']['background'];
            $newMap['Map']['refresh_interval'] = $newMap['Map']['refresh_interval'] * 1000; // in milliseconds

            $this->Map->create();
            if ($this->Map->save($newMap)) {
                $newMapId = $this->Map->id;
                $newMap['Map']['id'] = $newMapId;
                $modelsToIgnore = [
                    'Map',
                    'Container',
                    'Rotation'
                ];
                //Add objects to new map
                foreach ($map as $modelName => $records) {
                    if (!in_array($modelName, $modelsToIgnore, true)) {
                        foreach ($records as $record) {
                            unset($record['id']);
                            $record['map_id'] = $newMapId;
                            $newMap[$modelName][] = $record;
                        }

                    }
                }
                if ($this->Map->saveAll($newMap)) {
                    $this->set('success', true);
                    $this->viewBuilder()->setOption('serialize', ['success']);
                    return;
                }
            }

            $this->serializeErrorMessageFromModel('Map');

        }

    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, CT_TENANT, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), CT_TENANT, [], $this->hasRootPrivileges);
        }
        $containers = Api::makeItJavaScriptAble($containers);


        $this->set('containers', $containers);
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }
}
