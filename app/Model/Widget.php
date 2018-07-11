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


class Widget extends AppModel {

    /*
    public $belongsTo = ['DashboardTab', 'Service'];
    public $hasOne = [
        'WidgetTacho'               => [
            'dependent' => true,
        ],
        'WidgetServiceStatusList'   => [
            'dependent' => true,
        ],
        'WidgetHostStatusList'      => [
            'dependent' => true,
        ],
        'WidgetHostDowntimeList'    => [
            'dependent' => true,
        ],
        'WidgetServiceDowntimeList' => [
            'dependent' => true,
        ],
        'WidgetNotice'              => [
            'dependent' => true,
        ],
        'WidgetPiechart'            => [
            'dependent' => true,
        ],
        //'WidgetGraphgenerator' => [
        //	'dependent' => true
        //],
    ];
    */

    public $validate = [
        'dashboard_tab_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'The value should be greate than zero.',
                'required' => true,
            ],
        ],
        'row'              => [
            'numeric' => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
            ],
        ],
        'col'              => [
            'numeric' => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
            ],
        ],
        'height'           => [
            'numeric' => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
            ],
        ],
        'width'            => [
            'numeric' => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
            ],
        ],
    ];

    public function getAvailableWidgets() {

        //Default Widgets
        $widgets = [
            [
                'type_id'   => 1,
                'title'     => __('Welcome'),
                'icon'      => 'fa-comment',
                'directive' => 'welcome-widget' //AngularJS directive
            ],
            [
                'type_id'   => 2,
                'title'     => __('Parent outages'),
                'icon'      => 'fa-exchange',
                'directive' => 'parent-outages-widget'
            ],

            [
                'type_id'   => 3,
                'title'     => __('Hosts pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-widget'
            ],
            [
                'type_id'   => 7,
                'title'     => __('Hosts pie chart 180'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-180-widget'
            ],
            [
                'type_id'   => 4,
                'title'     => __('Services pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'services-piechart-widget'
            ],
            [
                'type_id'   => 5,
                'title'     => __('Hosts in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'hosts-downtime-widget'
            ],
            [
                'type_id'   => 6,
                'title'     => __('Services in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'services-downtime-widget'
            ],
            [
                'type_id'   => 9,
                'title'     => __('Host status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'host-status-widget'
            ],
            [
                'type_id'   => 10,
                'title'     => __('Service status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'service-status-widget'
            ],
            [
                'type_id'   => 11,
                'title'     => __('Traffic light'),
                'icon'      => 'fa-road',
                'directive' => 'trafficlight-widget'
            ],
            [
                'type_id'   => 12,
                'title'     => __('Tachometer'),
                'icon'      => 'fa-dashboard',
                'directive' => 'tachometer-widget'
            ],
            [
                'type_id'   => 13,
                'title'     => __('Notice'),
                'icon'      => 'fa-pencil-square-o',
                'directive' => 'notice-widget'
            ],
            [
                'type_id'   => 15,
                'title'     => __('Graphgenerator'),
                'icon'      => 'fa-area-chart',
                'directive' => 'graphgenerator-widget'
            ]
        ];

        return $widgets;
    }

    public function copySharedWidgets($sourceTab, $targetTab, $userId) {
        $sourceWidgets = $this->find('all', [
            'conditions' => [
                'Widget.dashboard_tab_id' => $sourceTab['DashboardTab']['id'],
            ],
        ]);
        $error = false;
        foreach ($sourceWidgets as $sourceWidget) {
            if (isset($sourceWidget['Service'])) {
                unset($sourceWidget['Service']);
            }
            if (isset($sourceWidget['Host'])) {
                unset($sourceWidget['Host']);
            }

            $sourceWidget = Hash::remove($sourceWidget, '{s}.id');
            $sourceWidget = Hash::remove($sourceWidget, '{s}.widget_id');

            $sourceWidget['DashboardTab'] = [
                'id'      => $targetTab['DashboardTab']['id'],
                'name'    => $sourceTab['DashboardTab']['name'],
                'user_id' => $userId,
            ];
            $sourceWidget['Widget']['dashboard_tab_id'] = $targetTab['DashboardTab']['id'];
            //Remove all mnull keys and unused Models
            $sourceWidget = array_filter($sourceWidget, function ($valuesAsArray) {
                if (is_array($valuesAsArray)) {
                    foreach ($valuesAsArray as $value) {
                        if ($value !== null && $value !== '') {
                            return true;
                        }
                    }
                } else {
                    if ($valuesAsArray !== null && $valuesAsArray !== '') {
                        return true;
                    }
                }

                return false;
            });
            if (!$this->saveAll($sourceWidget)) {
                debug($this->validationErrors);
                $error = true;
            } else {
                $error = false;
            }
        }

        return $error;
    }
}
