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

class GrafanaUserdashboardPanel extends GrafanaModuleAppModel {

    public $belongsTo = [
        'GrafanaUserdashboard' => [
            'className'  => 'GrafanaModule.GrafanaUserdashboard',
            'foreignKey' => 'userdashboard_id'
        ]
    ];

    public $hasMany = [
        'GrafanaUserdashboardMetric' => [
            'className'  => 'GrafanaModule.GrafanaUserdashboardMetric',
            'dependent'  => true,
            'foreignKey' => 'panel_id',
        ],
    ];

    public $validate = [
        'userdashboard_id' => [
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
        'row'              => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'No row found in dataset',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'No row found in dataset',
            ],
        ]
    ];

    /**
     * @param $dashboardId
     * @return int
     */
    public function getNextRow($dashboardId){
        $result = $this->find('first', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'GrafanaUserdashboardPanel.userdashboard_id' => $dashboardId,
            ],
            'order'      => [
                'GrafanaUserdashboardPanel.row' => 'DESC',
            ],
        ]);
        if (!empty($result)) {
            return (int)$result['GrafanaUserdashboardPanel']['row'] + 1;
        }

        return 0;
    }

}
