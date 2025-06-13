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
        $lastMap = []; // this is the last map in the hierarchy, used to add items to it
        //echo chr(10) . " dessign higher map " . ' in ' . $hostAndData["hostId"] . ", " . $hostAndData['hostName'];
        $generatedMapsAndItems = [
            'maps'  => [],
            'items' => []
        ];

        // create maps
        foreach ($hostAndData['containerHierarchy'] as $containerKey => $container) {

            // check if container is already generated
            $containerName = $container['name'];
            $map = null;

            if (in_array($containerName, Hash::extract($this->allGeneratedMaps, '{n}.name'), true)) {
                //echo chr(10) . " map for container " . $containerName . ' already created, skip!!!';
                foreach ($this->allGeneratedMaps as $generatedMap) {
                    if ($containerName === $generatedMap['name']) {
                        $lastMap = $generatedMap;
                        if (count($hostAndData['containerHierarchy']) > 1) {
                            $higherMap = $generatedMap;
                        }
                        //echo chr(10) . " assign higher map";
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
            $lastMap = $map;

            //echo chr(10) . print_r($container, true) . "; containerKey " . $containerKey;

            // add map as mapsummaryitem to the previously generated map
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

        // create Host
        $newHostItem = $this->createNewMapSummaryItem($lastMap, $hostAndData['hostId'], 'host');

        if (array_key_exists("error", $newHostItem)) {
            return $newHostItem;
        }

        if ($newHostItem) {
            $this->generatedItems[] = $newHostItem;
            $generatedMapsAndItems['items'][] = $newHostItem;
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

        //get all items of the map to add the new item
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
         * calculate new x and y position for the new mapsummaryitem
         * by searching for the previous item and its position in the existing items
         * and calculate the position based on this item
         */
        $x = 0; // x position of the new item
        $y = 0; // y position of the new item
        $LINE_SIZE = 140; // size of one line in the map
        $ITEM_MIN_WIDTH = 200; // minimum width of an item
        $MAX_X = 1500; // maximum x position in the map
        $mapHasItems = false; // if map has only one item, calculate position based on this item
        $previousItem = [
            'type' => "",
            'id'   => 0,
        ];

        // searching for the previous item and its position in the existing items
        foreach (['Mapgadgets', 'Mapicons', 'Mapitems', 'Maplines', 'Maptexts', 'Mapsummaryitems'] as $itemType) {
            if (isset($mapToAddItemsWithItems[$itemType])) {
                $mapHasItems = true;
                foreach ($mapToAddItemsWithItems[$itemType] as $item) {

                    // check if mapsummaryitem is already on the map and break if so
                    if ($itemType === "Mapsummaryitems" && $item['object_id'] === $objectId && $item['type'] === $type) {
                        return [];
                    }

                    $itemX = ($itemType === 'Maplines') ? $item['endX'] : $item['x'];
                    $itemY = ($itemType === 'Maplines') ? $item['endY'] : $item['y'];

                    if ($itemY > $y || ($itemY === $y && $itemX > $x)) {
                        $x = $itemX;
                        $y = $itemY;
                        $previousItem = [
                            'type' => $item['type'],
                            'id'   => $item['object_id']
                        ];
                    }
                }
            }
        }

        // get name of the previous item to calculate the width
        $width = $this->calculateItemWidth($previousItem);

        // if y does not fit the line size (130px), add some space to the top
        if ($y > 0 && $y % $LINE_SIZE !== 0) {
            $y += ($y % $LINE_SIZE); // add some space to the top
        }

        if ($x > 0 || $mapHasItems) {
            if ($width < $ITEM_MIN_WIDTH) {
                $width = $ITEM_MIN_WIDTH;
            }
            $x += $width; // add some space to the right
        }
        // if item is too far to the right, move it to the next line
        if ($x > $MAX_X) {
            $x = 0; // reset x position to start
            $y += $LINE_SIZE; // add some space to the bottom
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

    private function calculateItemWidth(array $previousItem) {
        $width = 0;
        $namesById = [];
        if ($previousItem['type'] && $previousItem['id']) {
            if ($previousItem['type'] === 'map') {
                $namesById = Hash::combine($this->allGeneratedMaps, '{n}.id', '{n}.name');
            } else if ($previousItem['type'] === 'host') {
                $namesById = Hash::combine($this->hostsAndData, '{n}.hostId', '{n}.hostName');
            }

            if ($namesById && isset($namesById[$previousItem['id']])) {
                $width = strlen($namesById[$previousItem['id']]) * 7; // calculate width based on name length
            }
        }
        return $width;
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
