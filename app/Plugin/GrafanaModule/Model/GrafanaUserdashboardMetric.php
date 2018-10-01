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

class GrafanaUserdashboardMetric extends GrafanaModuleAppModel {

    public $belongsTo = [
        'GrafanaUserdashboardPanel' => [
            'className'  => 'GrafanaModule.GrafanaUserdashboardPanel',
            'foreignKey' => 'panel_id'
        ],
        'Host'                      => [
            'className'  => 'Host',
            'foreignKey' => 'host_id'
        ],
        'Service'                   => [
            'className'  => 'Service',
            'foreignKey' => 'service_id'
        ],
    ];

    public $validate = [
        'panel_id'   => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'No userdashboard_id found in dataset',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'No userdashboard_id found in dataset',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'No userdashboard_id found in dataset',
                'required' => true,
            ],
        ],
        'metric'     => [
            'valObjectTypes' => [
                'rule'    => ['valMetric'],
                'message' => 'Metric needs to be a string or null',
            ],
            'notBlank'       => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'host_id'    => [
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
        'service_id' => [
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
    ];

    public function valMetric($data) {
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

}
