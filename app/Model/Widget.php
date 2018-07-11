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
                'directive' => 'welcome-widget', //AngularJS directive,
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 2,
                'title'     => __('Parent outages'),
                'icon'      => 'fa-exchange',
                'directive' => 'parent-outages-widget',
                'width'     => 6,
                'height'    => 7
            ],

            [
                'type_id'   => 3,
                'title'     => __('Hosts pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-widget',
                'width'     => 6,
                'height'    => 9
            ],
            [
                'type_id'   => 7,
                'title'     => __('Hosts pie chart 180'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-180-widget',
                'width'     => 6,
                'height'    => 8
            ],
            [
                'type_id'   => 4,
                'title'     => __('Services pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'services-piechart-widget',
                'width'     => 6,
                'height'    => 9
            ],
            [
                'type_id'   => 8,
                'title'     => __('Services pie chart 180'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'services-piechart180-widget',
                'width'     => 6,
                'height'    => 8
            ],
            [
                'type_id'   => 5,
                'title'     => __('Hosts in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'hosts-downtime-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 6,
                'title'     => __('Services in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'services-downtime-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 9,
                'title'     => __('Host status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'host-status-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 10,
                'title'     => __('Service status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'service-status-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 11,
                'title'     => __('Traffic light'),
                'icon'      => 'fa-road',
                'directive' => 'trafficlight-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 12,
                'title'     => __('Tachometer'),
                'icon'      => 'fa-dashboard',
                'directive' => 'tachometer-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 13,
                'title'     => __('Notice'),
                'icon'      => 'fa-pencil-square-o',
                'directive' => 'notice-widget',
                'width'     => 6,
                'height'    => 7
            ],
            [
                'type_id'   => 15,
                'title'     => __('Graphgenerator'),
                'icon'      => 'fa-area-chart',
                'directive' => 'graphgenerator-widget',
                'width'     => 6,
                'height'    => 7
            ]
        ];

        return $widgets;
    }

    /**
     * @param $typeId
     * @return bool
     */
    public function isWidgetAvailable($typeId) {
        $typeId = (int)$typeId;
        foreach ($this->getAvailableWidgets() as $widget) {
            if ($widget['type_id'] === $typeId) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $typeId
     * @return array
     */
    public function getWidgetByTypeId($typeId) {
        $typeId = (int)$typeId;
        foreach ($this->getAvailableWidgets() as $widget) {
            if ($widget['type_id'] === $typeId) {
                return $widget;
            }
        }
        return [];
    }

}
