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

/**
 * Class Automap
 * @deprecated
 */
class Automap extends AppModel {

    /*public $belongsTo = [
        'Container' => [
            'className' => 'Container',
            'foreignKey' => 'container_id',
        ]
    ];*/

    public $validate = [
        'name'          => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'container_id'  => [
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
                'message'  => 'Invalid container.',
                'required' => true,
            ],
        ],
        'host_regex'    => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isRegex'  => [
                'rule'     => ['isRegex', 'host_regex'],
                'message'  => 'Invalid regular expression',
                'required' => true,
            ],
        ],
        'service_regex' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isRegex'  => [
                'rule'     => ['isRegex', 'service_regex'],
                'message'  => 'Invalid regular expression',
                'required' => true,
            ],
        ],
        'show_ok'       => [
            'checkStateOptions' => [
                'rule'     => ['checkStateOptions'],
                'message'  => 'You need to select at least one option',
                'required' => false,
            ],
        ],
        'recursive'     => [
            'numeric' => [
                'rule'       => 'numeric',
                'message'    => 'This field needs to be numeric.',
                'allowEmpty' => true,
            ],
        ],

    ];

    /**
     * @param $data
     * @param $key
     * @return bool
     * @deprecated
     */
    public function isRegex($data, $key) {
        if (isset($data[$key])) {
            if (@preg_match('/' . $data[$key] . '/', null) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $data
     * @return bool
     * @deprecated
     */
    public function checkStateOptions($data) {
        $needles = ['show_acknowledged', 'show_downtime', 'show_unknown', 'show_critical', 'show_warning', 'show_ok'];
        foreach ($needles as $needle) {
            if (isset($this->data['Automap'][$needle]) && $this->data['Automap'][$needle] == 1) {
                return true;
            }
        }

        return false;
    }

}
