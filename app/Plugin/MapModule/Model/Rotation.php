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

class Rotation extends MapModuleAppModel
{
    public $hasAndBelongsToMany = [
        'Container' => [
            'className' => 'Container',
            'joinTable' => 'rotations_to_containers',
            'dependent' => true,
        ],
        'Map' => [
            'className' => 'MapModule.Map',
            'joinTable' => 'maps_to_rotations',
            'unique'    => true,
        ],
    ];

    var $validate = [
        'container_id' => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one Container.',
                'required' => true,
                'on'       => 'create',
            ],
        ],
        'name'     => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'Map'      => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one map',
                'required' => true,
            ],
        ],
        'interval' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 10],
                'message'  => 'Please enter a number > 10.',
                'required' => true,
            ],
        ],
    ];


    /*
    Custom validation rule for map field
    */
    public function atLeastOne($data)
    {
        return !empty($this->data[$this->name]['Map']);
    }
}
