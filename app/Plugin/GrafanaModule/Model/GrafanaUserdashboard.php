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


class GrafanaUserdashboard extends GrafanaModuleAppModel {
    public $belongsTo = [
        'Container'            => [
            'className'  => 'Container',
            'foreignKey' => 'container_id',
        ],
        'GrafanaConfiguration' => [
            'className'  => 'GrafanaModule.GrafanaConfiguration',
            'foreignKey' => 'configuration_id'
        ],
    ];

    public $hasMany = [
        'GrafanaUserdashboardPanel' => [
            'className'  => 'GrafanaModule.GrafanaUserdashboardPanel',
            'dependent'  => true,
            'foreignKey' => 'userdashboard_id',
        ],
    ];

    public $validate = [
        'container_id'     => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field needs to be numeric',
                'required' => true,
            ],
        ],
        'configuration_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'This field needs to be numeric',
                'required' => true,
            ],
        ],
        'name'             => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'This user dashboard name has already been taken.',
                'required' => true,
            ],
        ],
    ];

    /**
     * @param $id
     * @return array
     */
    public function getQuery($id) {
        return [
            'conditions' => [
                'GrafanaUserdashboard.id' => $id
            ],
            'contain'    => [
                'GrafanaUserdashboardPanel' => [
                    'GrafanaUserdashboardMetric' => [
                        'Host'    => [
                            'fields' => [
                                'Host.id',
                                'Host.name',
                                'Host.uuid'
                            ]
                        ],
                        'Service' => [
                            'fields'          => [
                                'Service.id',
                                'Service.name',
                                'Service.uuid'
                            ],
                            'Servicetemplate' => [
                                'fields' => [
                                    'Servicetemplate.name'
                                ]
                            ]
                        ]
                    ],
                    'order'                      => [
                        'GrafanaUserdashboardPanel.row' => 'ASC'
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array $findResult
     * @return array
     */
    public function extractRowsWithPanelsAndMetricsFromFindResult($findResult) {
        $rowsWithPanelsAndMetrics = [];
        foreach ($findResult['GrafanaUserdashboardPanel'] as $k => $panel) {
            $rowsWithPanelsAndMetrics[$panel['row']][$k] = [
                'id'               => $panel['id'],
                'userdashboard_id' => $panel['userdashboard_id'],
                'row'              => $panel['row'],
                'unit'             => $panel['unit'],
                'title'             => $panel['title'],
                'metrics'          => []
            ];
            foreach ($panel['GrafanaUserdashboardMetric'] as $metric) {
                $metric['Servicetemplate'] = [];
                if (isset($metric['Service']['Servicetemplate'])) {
                    $metric['Servicetemplate'] = $metric['Service']['Servicetemplate'];
                }
                $host = new \itnovum\openITCOCKPIT\Core\Views\Host($metric);
                $service = new \itnovum\openITCOCKPIT\Core\Views\Service($metric);
                $metric['Host'] = $host->toArray();
                $metric['Service'] = $service->toArray();
                $rowsWithPanelsAndMetrics[$panel['row']][$k]['metrics'][] = $metric;
            };
        }
        return $rowsWithPanelsAndMetrics;
    }

}
