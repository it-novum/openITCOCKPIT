<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

namespace MapModule\Controller;

use App\Model\Table\ContainersTable;
use App\Model\Table\HostsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MapgeneratorFilter;
use itnovum\openITCOCKPIT\Maps\MapForAngular;
use MapModule\Model\Table\MapgeneratorsTable;
use MapModule\Model\Table\MapsTable;
use MapModule\Model\Table\MapsummaryitemsTable;

class MapgeneratorsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var MapgeneratorsTable $MapgeneratorsTable */
        $MapgeneratorsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgenerators');

        $MapgeneratorFilter = new MapgeneratorFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $MapgeneratorFilter->getPage());

        $limit = $PaginateOMat->getHandler()->getLimit();
        $Paginator = null;
        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            $limit = null;
        } else {
            $Paginator = $PaginateOMat;
        }

        $all_mapgenerators = $MapgeneratorsTable->getAll(
            $MapgeneratorFilter->indexFilter(),
            $MapgeneratorFilter->getOrderForPaginator('Mapgenerators.name', 'asc'),
            $limit,
            $Paginator,
            $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);
        foreach ($all_mapgenerators as $key => $mapgenerator) {
            $all_mapgenerators[$key]['allowEdit'] = false;
            if ($this->hasRootPrivileges == true) {
                $all_mapgenerators[$key]['allowEdit'] = true;
                continue;
            }
            foreach ($mapgenerator['containers'] as $cKey => $container) {
                if (array_key_exists($container['id'], $this->MY_RIGHTS_LEVEL) && $this->MY_RIGHTS_LEVEL[$container['id']] == WRITE_RIGHT) {
                    $all_mapgenerators[$key]['allowEdit'] = true;
                    break;
                }
            }
        }

        $this->set('all_mapgenerators', $all_mapgenerators);
        $this->viewBuilder()->setOption('serialize', ['all_mapgenerators', 'paging']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $data['Mapgenerator']['containers']['_ids'] = $data['Mapgenerator']['container_id'];
            $data['Mapgenerator']['maps']['_ids'] = $data['Mapgenerator']['Map'];

            /** @var MapgeneratorsTable $MapgeneratorsTable */
            $MapgeneratorsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgenerators');

            $mapgeneratorsEntity = $MapgeneratorsTable->newEntity($data['Mapgenerator']);
            $MapgeneratorsTable->save($mapgeneratorsEntity);
            if (!$mapgeneratorsEntity->hasErrors()) {
                $this->serializeCake4Id($mapgeneratorsEntity);
                return;
            } else {
                $this->serializeCake4ErrorMessage($mapgeneratorsEntity);
                return;
            }
        }
    }

    /**
     * @throws Exception
     */
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

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var MapgeneratorsTable $MapgeneratorsTable */
        $MapgeneratorsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgenerators');

        if (!$MapgeneratorsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Map generator'));
        }

        $mapgenerator = $MapgeneratorsTable->get($id, [
            'contain' => [
                'Maps',
                'Containers'
            ]
        ]);

        $containerIds = Hash::extract($mapgenerator, 'containers.{n}.id');

        if (!$this->allowedByContainerId($containerIds)) {
            $this->render403();
            return;
        }

        if ($this->hasRootPrivileges === false) {
            if (empty(array_intersect($containerIds, $this->getWriteContainers()))) {
                $this->render403();
            }
        }

        $this->viewBuilder()->setOption('serialize', ['mapgenerator']);
        $this->set(compact('mapgenerator'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $data['Mapgenerator']['id'] = $id;
            $data['Mapgenerator']['containers']['_ids'] = $data['Mapgenerator']['container_id'];

            $data['Rotation']['maps']['_ids'] = $data['Rotation']['Map'];


            $mapgeneratorEntity = $mapgenerator;
            $mapgeneratorEntity = $MapgeneratorsTable->patchEntity($mapgeneratorEntity, $data['Mapgenerator']);
            $MapgeneratorsTable->save($mapgeneratorEntity);
            if (!$mapgeneratorEntity->hasErrors()) {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($mapgeneratorEntity);
                }
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4ErrorMessage($mapgeneratorEntity);
                }
            }
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var MapgeneratorsTable $MapgeneratorsTable */
        $MapgeneratorsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgenerators');

        if (!$MapgeneratorsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Map generator'));
        }

        $rotation = $MapgeneratorsTable->get($id, [
            'contain' => [
                'Maps',
                'Containers'
            ]
        ]);
        $containerIdsToCheck = Hash::extract($rotation, 'containers.{n}.id');
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        if ($MapgeneratorsTable->delete($rotation)) {
            $this->set('message', __('Map generator deleted successfully'));
            $this->viewBuilder()->setOption('serialize', ['message']);
            return;
        }

        $this->response->withStatus(400);
        $this->set('message', __('Could not delete map generator'));
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    public function generate() {
        /*if (!$this->isApiRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }*/

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $data = $this->request->getData();

        if (empty($data['Mapgenerator']['refresh_interval'])) {
            $data['Mapgenerator']['refresh_interval'] = 90000;
        } else {
            if ($data['Mapgenerator']['refresh_interval'] < 5) {
                $data['Mapgenerator']['refresh_interval'] = 5;
            }

            $data['Mapgenerator']['refresh_interval'] = ((int)$data['Mapgenerator']['refresh_interval'] * 1000);
        }

        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }

        $hosts = $HostsTable->getHostsForMapgenerator($MY_RIGHTS);

        if (empty($hosts)) {
            $errors = [
                'hosts' => __('No hosts found for map generator')
            ];
            $this->set('error', $errors);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $containersAndHosts = $ContainersTable->getContainersForMapgeneratorByContainerStructure($hosts, $MY_RIGHTS);

        $generatedMaps = [];

        // get already generated maps
        if (!empty($data['Mapgenerator']['maps']['_ids'])) {

            $generatedMaps = $MapsTable->getMapsByIds($data['Mapgenerator']['maps']['_ids']);

        }

        // generate maps
        foreach ($containersAndHosts as $containerAndHostKey => $containerAndHost) {

            $higherMap = null; // this is the map that is generated for the container that is higher in the hierarchy

            // container for map is the mandant (first container in the list)
            $containerIdForNewMap = $containerAndHost['containerHierarchy'][0]['id'];

            foreach ($containerAndHost['containerHierarchy'] as $containerKey => $container) {

                // check if container is already generated
                $containerName = $container['name'];
                $map = null;

                // if map is already generated continue
                if (in_array($containerName, Hash::extract($generatedMaps, '{n}.name'), true)) {
                    continue;
                }

                // create new map for this container
                $mapData = [
                    'containers'       => [
                        '_ids' => [$containerIdForNewMap]
                    ],
                    'name'             => $containerName,
                    'title'            => $containerName,
                    'refresh_interval' => $data['Mapgenerator']['refresh_interval'],
                ];

                $map = $MapsTable->newEmptyEntity();
                $map = $MapsTable->patchEntity($map, $mapData);
                $generatedMaps[] = $map;

                $MapsTable->save($map);
                if ($map->hasErrors()) {
                    $this->response = $this->response->withStatus(400);
                    $this->set('error', $map->getErrors());
                    $this->viewBuilder()->setOption('serialize', ['error']);
                    return;
                }

                // add maps as mapsummaryitems to the previously generated map
                if ($higherMap && $containerKey > 0) {

                    //get item that is furthest to the right
                    $higherMapWithItems = $MapsTable->get($higherMap["id"], [
                        'contain' => [
                            'Containers',
                            'Mapgadgets',
                            'Mapicons',
                            'Mapitems',
                            'Maplines',
                            'Maptexts',
                            'Mapsummaryitems'
                        ]
                    ])->toArray();

                    $MapForAngular = new MapForAngular($higherMapWithItems);
                    $higherMapWithItems = $MapForAngular->toArray();

                    /**
                     * calculate new x and y position for the new mapsummaryitems
                     * by searching for the highest x and y position of the existing items
                     */
                    $x = 0;
                    $y = 0;
                    foreach ($higherMapWithItems['Mapgadgets'] as $mapgdagetKey => $mapgadget) {
                        if ($mapgadget['x'] >= $x && $mapgadget['y'] >= $y) {
                            $x = $mapgadget['x'];
                            $y = $mapgadget['y'];
                        }
                    }
                    foreach ($higherMapWithItems['Mapicons'] as $mapicon) {
                        if ($mapicon['x'] >= $x && $mapicon['y'] >= $y) {
                            $x = $mapicon['x'];
                            $y = $mapicon['y'];
                        }
                    }
                    foreach ($higherMapWithItems['Mapitems'] as $mapitem) {
                        if ($mapitem['x'] >= $x && $mapitem['y'] >= $y) {
                            $x = $mapitem['x'];
                            $y = $mapitem['y'];
                        }
                    }
                    foreach ($higherMapWithItems['Maplines'] as $mapline) {
                        if ($mapline['endX'] >= $x && $mapline['endY'] >= $y) {
                            $x = $mapline['endX'];
                            $y = $mapline['endY'];
                        }
                    }
                    foreach ($higherMapWithItems['Maptexts'] as $maptext) {
                        if ($maptext['x'] >= $x && $maptext['y'] >= $y) {
                            $x = $maptext['x'];
                            $y = $maptext['y'];
                        }
                    }
                    foreach ($higherMapWithItems['Mapsummaryitems'] as $mapsummaryitem) {
                        if ($mapsummaryitem['x'] >= $x && $mapsummaryitem['y'] >= $y) {
                            $x = $mapsummaryitem['x'];
                            $y = $mapsummaryitem['y'];
                        }
                    }

                    if ($x > 0) {
                        $x += 200; // add some space to the right
                    }
                    if ($x > 1500) {
                        $x = 0;
                        $y += 120; // add some space to the bottom
                    }

                    /** @var MapsummaryitemsTable $MapsummaryitemsTable */
                    $MapsummaryitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapsummaryitems');

                    $mapsummaryitemEntity = $MapsummaryitemsTable->newEmptyEntity();

                    // add map item to the map
                    $mapsummaryitem['Mapsummaryitem'] = [
                        "z_index"         => "0",
                        "x"               => $x,
                        "y"               => $y,
                        "size_x"          => 0,
                        "size_y"          => 0,
                        "show_label"      => 1,
                        "label_possition" => 2,
                        "type"            => "map",
                        "object_id"       => $map["id"],
                        "map_id"          => $higherMap["id"]
                    ];
                    $mapsummaryitemEntity = $MapsummaryitemsTable->patchEntity($mapsummaryitemEntity, $mapsummaryitem['Mapsummaryitem']);
                    $MapsummaryitemsTable->save($mapsummaryitemEntity);

                    if ($mapsummaryitemEntity->hasErrors()) {
                        $this->response = $this->response->withStatus(400);
                        $this->set('error', $mapsummaryitemEntity->getErrors());
                        $this->viewBuilder()->setOption('serialize', ['error']);
                        return;
                    }
                }

                if (count($containerAndHost['containerHierarchy']) > 1) {
                    $higherMap = $map;
                }

            }


        }

        // TODO: save new geneated maps in MapgeneratorsTable


        return $generatedMaps;
    }

}
