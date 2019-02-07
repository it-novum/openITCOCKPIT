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
                'on'       => 'update'
            ],
            'numeric'  => [
                'rule'     => 'numeric',
                'message'  => 'This field needs a numeric value.',
                'required' => true,
                'on'       => 'update'
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'The value should be greate than zero.',
                'required' => true,
                'on'       => 'update'
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

    /**
     * @param array $ACL_PERMISSIONS
     * @return array
     */
    public function getAvailableWidgets($ACL_PERMISSIONS = []) {
        //Default Widgets static dashboards - no permissions required
        $widgets = [
            [
                'type_id'   => 1,
                'title'     => __('Welcome'),
                'icon'      => 'fa-comment',
                'directive' => 'welcome-widget', //AngularJS directive,
                'width'     => 6,
                'height'    => 7,
                'default'   => [
                    'row' => 0,
                    'col' => 0
                ]
            ],
            [
                'type_id'   => 2,
                'title'     => __('Parent outages'),
                'icon'      => 'fa-exchange',
                'directive' => 'parent-outages-widget',
                'width'     => 6,
                'height'    => 7,
                'default'   => [
                    'row' => 0,
                    'col' => 6
                ]
            ],

            [
                'type_id'   => 3,
                'title'     => __('Hosts pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-widget',
                'width'     => 6,
                'height'    => 11,
                'default'   => [
                    'row' => 7,
                    'col' => 0
                ]
            ],
            [
                'type_id'   => 7,
                'title'     => __('Hosts pie chart 180'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'hosts-piechart-180-widget',
                'width'     => 6,
                'height'    => 11
            ],
            [
                'type_id'   => 4,
                'title'     => __('Services pie chart'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'services-piechart-widget',
                'width'     => 6,
                'height'    => 11,
                'default'   => [
                    'row' => 7,
                    'col' => 6
                ]
            ],
            [
                'type_id'   => 8,
                'title'     => __('Services pie chart 180'),
                'icon'      => 'fa-pie-chart',
                'directive' => 'services-piechart180-widget',
                'width'     => 6,
                'height'    => 11
            ],
            [
                'type_id'   => 11,
                'title'     => __('Traffic light'),
                'icon'      => 'fa-road',
                'directive' => 'trafficlight-widget',
                'width'     => 3,
                'height'    => 14
            ],
            [
                'type_id'   => 12,
                'title'     => __('Tachometer'),
                'icon'      => 'fa-dashboard',
                'directive' => 'tachometer-widget',
                'width'     => 3,
                'height'    => 14
            ],
            [
                'type_id'   => 13,
                'title'     => __('Notice'),
                'icon'      => 'fa-pencil-square-o',
                'directive' => 'notice-widget',
                'width'     => 6,
                'height'    => 13
            ],
            /*
            [
                'type_id'   => 15,
                'title'     => __('Graphgenerator'),
                'icon'      => 'fa-area-chart',
                'directive' => 'graphgenerator-widget',
                'width'     => 6,
                'height'    => 7
            ]
            */
        ];

        //Depands on user rights
        if (isset($ACL_PERMISSIONS['downtimes']['host'])) {
            $widgets[] = [
                'type_id'   => 5,
                'title'     => __('Hosts in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'hosts-downtime-widget',
                'width'     => 12,
                'height'    => 15,
                'default'   => [
                    'row' => 18,
                    'col' => 0
                ]
            ];
        }

        if (isset($ACL_PERMISSIONS['downtimes']['service'])) {
            $widgets[] = [
                'type_id'   => 6,
                'title'     => __('Services in downtime'),
                'icon'      => 'fa-power-off',
                'directive' => 'services-downtime-widget',
                'width'     => 12,
                'height'    => 15,
                'default'   => [
                    'row' => 32,
                    'col' => 0
                ]
            ];
        }

        if (isset($ACL_PERMISSIONS['hosts']['index'])) {
            $widgets[] = [
                'type_id'   => 9,
                'title'     => __('Host status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'hosts-status-widget',
                'width'     => 12,
                'height'    => 16
            ];
            $widgets[] = [
                'type_id'   => 16,
                'title'     => __('Host status overview'),
                'icon'      => 'fa-info-circle',
                'directive' => 'host-status-overview-widget',
                'width'     => 3,
                'height'    => 15
            ];
        }

        if (isset($ACL_PERMISSIONS['services']['index'])) {
            $widgets[] = [
                'type_id'   => 10,
                'title'     => __('Service status list'),
                'icon'      => 'fa-list-alt',
                'directive' => 'services-status-widget',
                'width'     => 12,
                'height'    => 16
            ];
            $widgets[] = [
                'type_id'   => 17,
                'title'     => __('Service status overview'),
                'icon'      => 'fa-info-circle',
                'directive' => 'service-status-overview-widget',
                'width'     => 3,
                'height'    => 15
            ];
        }

        //Load Plugin configuration files
        $loadedModules = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        foreach ($loadedModules as $loadedModule) {
            $file = OLD_APP . 'Plugin' . DS . $loadedModule . DS . 'Lib' . DS . 'Widgets.php';
            if (file_exists($file)) {
                require_once $file;
                $dynamicNamespaceWithClassName = sprintf('itnovum\openITCOCKPIT\%s\Widgets\Widgets', $loadedModule);
                $ModuleWidgets = new $dynamicNamespaceWithClassName($ACL_PERMISSIONS);
                foreach ($ModuleWidgets->getAvailableWidgets() as $moduleWidget) {
                    $widgets[] = $moduleWidget;
                }
            }
        }

        return $widgets;
    }

    /**
     * @param int $typeId
     * @param array $ACL_PERMISSIONS
     * @return bool
     */
    public function isWidgetAvailable($typeId, $ACL_PERMISSIONS = []) {
        $typeId = (int)$typeId;
        foreach ($this->getAvailableWidgets($ACL_PERMISSIONS) as $widget) {
            if ($widget['type_id'] === $typeId) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $typeId
     * @param array $ACL_PERMISSIONS
     * @return array
     */
    public function getWidgetByTypeId($typeId, $ACL_PERMISSIONS = []) {
        $typeId = (int)$typeId;
        foreach ($this->getAvailableWidgets($ACL_PERMISSIONS) as $widget) {
            if ($widget['type_id'] === $typeId) {
                return $widget;
            }
        }
        return [];
    }

    /**
     * @param $ACL_PERMISSIONS
     * @return array
     */
    public function getDefaultWidgets($ACL_PERMISSIONS) {
        $widgets = [];
        foreach ($this->getAvailableWidgets($ACL_PERMISSIONS) as $widget) {
            if (isset($widget['default'])) {
                $widget['row'] = $widget['default']['row'];
                $widget['col'] = $widget['default']['col'];
                $widget['color'] = 'jarviswidget-color-blueDark';
                unset($widget['default']);
                $widgets[] = $widget;
            }
        }

        return $widgets;
    }

}
