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


class Map extends MapModuleAppModel {

    public $hasAndBelongsToMany = [
        'Container' => [
            'className' => 'Container',
            'joinTable' => 'maps_to_containers',
            'dependent' => true,
        ],
        'Rotation'  => [
            'className' => 'MapModule.Rotation',
            'joinTable' => 'maps_to_rotations',
            'unique'    => true,
        ],
    ];

    public $hasMany = [
        'Mapitem'   => [
            'className' => 'MapModule.Mapitem',
            'dependent' => true,
        ],
        'Mapline'   => [
            'className' => 'MapModule.Mapline',
            'dependent' => true,
        ],
        'Mapgadget' => [
            'className' => 'MapModule.Mapgadget',
            'dependent' => true,
        ],
        'Mapicon'   => [
            'className' => 'MapModule.Mapicon',
            'dependent' => true,
        ],
        'Maptext'   => [
            'className' => 'MapModule.Maptext',
            'dependent' => true,
        ],
    ];


    public $validate = [
        'name'         => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'title'        => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'container_id' => [
            'rule'    => ['multiple', ['min' => 1]],
            'message' => 'Please select one or more containers',
        ],
    ];

    /*
        Custom validation rule for map field
    */
    public function atLeastOne($data) {
        return !empty($this->data[$this->name]['container_id']);
    }


    public function transformForCopy($data, $newMapId){
        $newMap = [];
        foreach ($data as $elementType => $sourceMap){
            if($elementType == 'Container' || $elementType == 'Map' || $elementType == 'Rotation'){
                continue;
            }

            foreach ($sourceMap as $key => $elementData){
                //remove useless stuff
                unset($sourceMap[$key]['id']);
                unset($sourceMap[$key]['created']);
                unset($sourceMap[$key]['modified']);
                //set new map id for every element
                $sourceMap[$key]['map_id'] = $newMapId;
            }

            switch($elementType){
                case 'Mapitem':
                    $newMap['Mapitem'] = $sourceMap;
                    break;
                case 'Mapline':
                    $newMap['Mapline'] = $sourceMap;
                    break;

                case 'Mapgadget':
                    $newMap['Mapgadget'] = $sourceMap;
                    break;

                case 'Mapicon':
                    $newMap['Mapicon'] = $sourceMap;
                    break;

                case 'Maptext':
                    $newMap['Maptext'] = $sourceMap;
                    break;
            }
        }

        return $newMap;

    }

}