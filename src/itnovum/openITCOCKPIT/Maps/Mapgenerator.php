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

namespace App\itnovum\openITCOCKPIT\Maps;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Maps\MapForAngular;
use MapModule\Model\Table\MapsTable;
use MapModule\Model\Table\MapsummaryitemsTable;

class Mapgenerator {

    /**
     * @var array
     */
    private $mapgeneratorData;

    /**
     * @var array
     */
    private $hostsAndData;

    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $allGeneratedMaps;

    /**
     * @var array
     */
    private $generatedItems;

    /**
     * @var array
     */
    private $newGeneratedMaps;


    /**
     * Mapgenerator constructor.
     * @param array $hostsAndData
     * @param int $type
     */
    public function __construct(array $mapgeneratorData, array $hostsAndData, array $generatedMaps = [], int $type = 1) {
        $this->mapgeneratorData = $mapgeneratorData;
        $this->hostsAndData = $hostsAndData;
        $this->type = $type;
        $this->allGeneratedMaps = $generatedMaps;
        $this->generatedItems = [];
    }

    public function generate() {

        foreach ($this->hostsAndData as $hostsAndDataKey => $hostAndData) {

            if ($this->type === 1) {
                // container for map is the mandant (first container in the list)
                $containerIdForNewMap = $hostAndData['containerHierarchy'][0]['id'];

                $generatedMapsAndItemsByHost = $this->generateByContainerHierarchy($hostAndData, $containerIdForNewMap);
                if (array_key_exists("error", $generatedMapsAndItemsByHost)) {
                    return $generatedMapsAndItemsByHost;
                }

            }

        }

        $generatedMapsAndItems = $this->convertToArray();

        return $generatedMapsAndItems;
    }

    private function generateByContainerHierarchy(array $hostAndData, int $containerIdForNewMap) {

        $higherMap = []; // this is the map that is generated for the container that is higher in the hierarchy
        //echo chr(10) . " dessign higher map " . ' in ' . $hostAndData["hostId"] . ", " . $hostAndData['hostName'];
        $generatedMapsAndItems = [
            'maps'  => [],
            'items' => []
        ];

        foreach ($hostAndData['containerHierarchy'] as $containerKey => $container) {

            // check if container is already generated
            $containerName = $container['name'];
            $map = null;

            if (in_array($containerName, Hash::extract($this->allGeneratedMaps, '{n}.name'), true)) {
                //echo chr(10) . " map for container " . $containerName . ' already created, skip!!!';
                if (count($hostAndData['containerHierarchy']) > 1) {
                    foreach ($this->allGeneratedMaps as $generatedMap) {
                        if ($containerName === $generatedMap['name']) {
                            $higherMap = $generatedMap;
                            //echo chr(10) . " assign higher map";
                        }
                    }
                }
                continue;
            }

            // create new map for this container
            $map = $this->createNewMap($containerName, $this->mapgeneratorData['Mapgenerator']['refresh_interval'], $containerIdForNewMap);

            if (array_key_exists("error", $map)) {
                return $map;
            }

            $this->allGeneratedMaps[] = $map;
            $this->newGeneratedMaps[] = $map;
            $generatedMapsAndItems['maps'][] = $map;

            //echo chr(10) . print_r($container, true) . "; containerKey " . $containerKey;

            // add map as mapsummaryitems to the previously generated map
            if ($higherMap && $containerKey > 0) {

                $mapsummaryitem = $this->createNewMapSummaryItem($higherMap, $map["id"], 'map');
                //echo chr(10) . " add map  as item " . $mapsummaryitem['id'] . " to higher map " . $higherMap['name'];

                if (array_key_exists("error", $mapsummaryitem)) {
                    return $mapsummaryitem;
                }

                $this->generatedItems[] = $mapsummaryitem;
                $generatedMapsAndItems['items'][] = $mapsummaryitem;
            }

            if (count($hostAndData['containerHierarchy']) > 1) {
                $higherMap = $map;
                //echo chr(10) . " assign higher map";
            }

        }

        return $generatedMapsAndItems;
    }

    private function createNewMap(string $name, int $refreshInterval, int $containerId) {

        $map = [];

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $mapData = [
            'containers'       => [
                '_ids' => [$containerId]
            ],
            'name'             => $name,
            'title'            => $name,
            'refresh_interval' => $refreshInterval,
        ];

        $map = $MapsTable->newEmptyEntity();
        $map = $MapsTable->patchEntity($map, $mapData);

        $MapsTable->save($map);
        if ($map->hasErrors()) {
            return [
                'error' => $map->getErrors()
            ];
        }

        return $map->toArray();

    }

    private function createNewMapSummaryItem(array $mapToAddItems, int $objectId, string $type) {

        $mapsummaryitemEntity = [];

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        //get item that is furthest to the right
        $mapToAddItemsWithItems = $MapsTable->get($mapToAddItems["id"], [
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

        $MapForAngular = new MapForAngular($mapToAddItemsWithItems);
        $mapToAddItemsWithItems = $MapForAngular->toArray();

        /**
         * calculate new x and y position for the new mapsummaryitems
         * by searching for the highest x and y position of the existing items
         */
        $x = 0;
        $y = 0;
        $mapHasItems = false; // if map has only one item, calculate position based on this item
        if (isset($mapToAddItemsWithItems['Mapgadgets'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Mapgadgets'] as $mapgdagetKey => $mapgadget) {
                if ($mapgadget['x'] >= $x && $mapgadget['y'] >= $y) {
                    $x = $mapgadget['x'];
                    $y = $mapgadget['y'];
                }
            }
        }
        if (isset($mapToAddItemsWithItems['Mapicons'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Mapicons'] as $mapicon) {
                if ($mapicon['x'] >= $x && $mapicon['y'] >= $y) {
                    $x = $mapicon['x'];
                    $y = $mapicon['y'];
                }
            }
        }
        if (isset($mapToAddItemsWithItems['Mapitems'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Mapitems'] as $mapitem) {
                if ($mapitem['x'] >= $x && $mapitem['y'] >= $y) {
                    $x = $mapitem['x'];
                    $y = $mapitem['y'];
                }
            }
        }
        if (isset($mapToAddItemsWithItems['Maplines'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Maplines'] as $mapline) {
                if ($mapline['endX'] >= $x && $mapline['endY'] >= $y) {
                    $x = $mapline['endX'];
                    $y = $mapline['endY'];
                }
            }
        }
        if (isset($mapToAddItemsWithItems['Maptexts'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Maptexts'] as $maptext) {
                if ($maptext['x'] >= $x && $maptext['y'] >= $y) {
                    $x = $maptext['x'];
                    $y = $maptext['y'];
                }
            }
        }
        if (isset($mapToAddItemsWithItems['Mapsummaryitems'])) {
            $mapHasItems = true;
            foreach ($mapToAddItemsWithItems['Mapsummaryitems'] as $mapsummaryitem) {
                if ($mapsummaryitem['x'] >= $x && $mapsummaryitem['y'] >= $y) {
                    $x = $mapsummaryitem['x'];
                    $y = $mapsummaryitem['y'];
                }
            }
        }

        if ($x > 0 || $mapHasItems) {
            $x += 200; // add some space to the right
        }
        // if item is too far to the right, move it to the next line
        if ($x > 1500) {
            $x = 0;
            $y += 130; // add some space to the bottom
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
            "type"            => $type,
            "object_id"       => $objectId,
            "map_id"          => $mapToAddItems["id"]
        ];
        $mapsummaryitemEntity = $MapsummaryitemsTable->patchEntity($mapsummaryitemEntity, $mapsummaryitem['Mapsummaryitem']);
        $MapsummaryitemsTable->save($mapsummaryitemEntity);

        if ($mapsummaryitemEntity->hasErrors()) {
            return [
                'error' => $mapsummaryitemEntity->getErrors()
            ];
        }

        return $mapsummaryitemEntity->toArray();

    }

    private function convertToArray() {

        $generatedMapsAndItems = [
            'maps'  => [],
            'items' => []
        ];

        $mapsById = Hash::combine($this->allGeneratedMaps, '{n}.id', '{n}.name');

        foreach ($this->newGeneratedMaps as $map) {
            $generatedMapsAndItems['maps'][] = $map;
        }

        foreach ($this->generatedItems as $item) {

            $item["map"] = [
                'id'   => $item['map_id'],
                'name' => $mapsById[$item['map_id']]
            ];

            $generatedMapsAndItems['items'][] = $item;
        }

        return $generatedMapsAndItems;

    }

}
