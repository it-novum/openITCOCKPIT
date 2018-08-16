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

class Mapgadget extends MapModuleAppModel {
    public $belongsTo = [
        'Map' => [
            'className' => 'MapModule.Map',
            'dependent' => true,
        ],
    ];

    public $validate = [
        'map_id'     => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'No Map selected',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'No Map selected',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'No Map selected',
                'required' => true,
            ],
        ],
        'object_id'  => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'No Service selected',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'No Map selected',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'No Map selected',
                'required' => true,
            ],
        ],
        'x'          => [
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
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field needs to be > 0',
                'required' => true,
            ],
        ],
        'y'          => [
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
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field needs to be > 0',
                'required' => true,
            ],
        ],
        'size_x'     => [
            'notBlank' => [
                'rule'     => ['isNullOrNumericGtZeroX'],
                'message'  => 'This field needs to be numeric or null',
                'required' => true,
            ],
        ],
        'size_y'     => [
            'notBlank' => [
                'rule'     => ['isNullOrNumericGtZeroY'],
                'message'  => 'This field needs to be numeric or null',
                'required' => true,
            ],
        ],
        'z_index'    => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ]
        ],
        'show_label' => [
            'numeric' => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ]
        ],
        'type'       => [
            'notBlank'       => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'valObjectTypes' => [
                'rule'    => ['valObjectType'],
                'message' => 'Unsupported object type',
            ],
        ],
        'gadget'     => [
            'notBlank'       => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'valObjectTypes' => [
                'rule'    => ['valGadgetTypes'],
                'message' => 'Unsupported Gatget type',
            ],
        ],
        'metric'     => [
            'valObjectTypes' => [
                'rule'    => ['valMetric'],
                'message' => 'Metric needs to be a string or null',
            ]
        ]
    ];

    public function valObjectType($data) {
        if (isset($data['type'])) {
            return $data['type'] === 'service';
        }
        return false;
    }

    public function valGadgetTypes($data) {
        if (isset($data['gadget'])) {
            return in_array($data['gadget'], ['Tacho', 'Cylinder', 'Text', 'TrafficLight', 'RRDGraph', 'Temperature'], true);
        }
        return false;
    }

    public function valMetric($data) {
        //print_r($this->data);
        //Uncomment this, if all "old" gadgets had set an metric
        /*if (isset($this->data['Mapgadget']['gadget']) && $this->data['Mapgadget']['gadget'] !== 'TrafficLight') {
            if (array_key_exists('metric', $this->data['Mapgadget'])) {
                if ($this->data['Mapgadget']['metric'] === null) {
                    return false;
                }
                return strlen($this->data['Mapgadget']['metric']) > 1;
            }
        }*/

        if (array_key_exists('metric', $data)) {
            if ($data['metric'] === null) {
                return true;
            }
            if ($data['metric'] !== '') {
                return true;
            }
        }
        return false;
    }

    public function isNullOrNumericGtZeroX($data) {
        if (array_key_exists('size_x', $data)) { //isset() can not handle null values
            if ($data['size_x'] === null) {
                //Default size
                return true;
            }

            if (is_numeric($data['size_x'])) {
                return true;
            }
        }
        return false;
    }

    public function isNullOrNumericGtZeroY($data) {
        if (array_key_exists('size_y', $data)) { //isset() can not handle null values
            if ($data['size_y'] === null) {
                //Default size
                return true;
            }

            if (is_numeric($data['size_y'])) {
                return true;
            }
        }
        return false;
    }
}

