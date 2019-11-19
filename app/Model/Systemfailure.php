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
 * Class Systemfailure
 * @deprecated
 */
class Systemfailure extends AppModel {
    public $belongsTo = [
        'User' => [
            'dependent'  => false,
            'foreignKey' => 'user_id',
            'className'  => 'User',
        ]
    ];


    public $validate = [
        'comment'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'from_date' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'date'     => [
                'rule'    => ['date', 'dmy'],
                'message' => 'Please enter a valid date',
            ],
        ],
        'from_time' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'     => [
                'rule'    => 'time',
                'message' => 'Please enter a valid time',
            ],
        ],
        'to_date'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'date'     => [
                'rule'    => ['date', 'dmy'],
                'message' => 'Please enter a valid date',
            ],
        ],
        'to_time'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'time'     => [
                'rule'    => 'time',
                'message' => 'Please enter a valid time',
            ],
        ],
        'user_id'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'notBlank' => [
                'rule'     => 'numeric',
                'message'  => 'Please enter a number',
                'required' => true,
            ],
        ],
    ];
}