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
 * Class Timerange
 * @deprecated
 */
class Timerange extends AppModel {
    public $tablePrefix = 'timeperiod_';

    public $validate = [
        'day'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'start' => [
            'notBlank'       => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'           => [
                'rule'     => ['custom', '/^(([0-2][0-9]):([0-5][0-9]))$/'],
                'message'  => 'Please enter a valid time (HH:MM).',
                'required' => true,
            ],
            'startBeforeEnd' => [
                'rule'    => ['startBeforeEnd', 'end'],
                'message' => 'The start time must be before the end time.',
            ],
        ],
        'end'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'     => [
                'rule'     => ['custom', '/^(([0-2][0-9]):([0-5][0-9])|(24:00))$/'],
                'message'  => 'Please enter a valid time (HH:MM).',
                'required' => true,
            ],
        ],
    ];

    public $belongsTo = [
        'Timeperiod' => [
            'className' => 'Timeperiod',
            'dependent' => true,
        ],
    ];

    /**
     * @param array $field
     * @param null $compare_field
     * @return bool
     * @deprecated
     */
    public function startBeforeEnd($field = [], $compare_field = null) {
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];
            if ($v1 > $v2) {
                return false;
            } else {
                continue;
            }
        }

        return true;
    }
}