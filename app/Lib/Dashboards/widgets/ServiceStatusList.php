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

namespace Dashboard\Widget;
class ServiceStatusList extends Widget
{
    public $isDefault = false;
    public $icon = 'fa-list-alt';
    public $element = 'service_status_list';
    public $width = 10;
    public $height = 21;
    public $hasInitialConfig = true;

    public $initialConfig = [
        'WidgetServiceStatusList' => [
            'animation'          => 'fadeInUp',
            'animation_interval' => 10,
            'show_ok'            => 0,
            'show_warning'       => 1,
            'show_critical'      => 1,
            'show_unknown'       => 1,
            'show_acknowledged'  => 0,
            'show_downtime'      => 0,
        ],
    ];

    public function __construct(\Controller $controller, $QueryCache)
    {
        parent::__construct($controller, $QueryCache);
        $this->typeId = 10;
        $this->title = __('Service status list');
    }

    public function setData($widgetData)
    {
        //debug($widgetData);
        $widget = $this->Controller->Widget->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'WidgetServiceStatusList',
            ],
            'conditions' => [
                'Widget.id' => $widgetData['Widget']['id'],
            ],
        ]);

        $conditions = [
            'Host.disabled'    => 0,
            'Service.disabled' => 0,
        ];

        $mapWidgetSettingsToServicestatus = [
            'show_ok'       => 0,
            'show_warning'  => 1,
            'show_critical' => 2,
            'show_unknown'  => 3,
        ];

        $_stateTypes = [];
        foreach ($mapWidgetSettingsToServicestatus as $stateName => $state) {
            if ($widget['WidgetServiceStatusList'][$stateName]) {
                $_stateTypes[] = $state;
            }
        }
        //Only add the condition, if it make any sens. If the user select all state types, we don't need a condition
        if (sizeof($_stateTypes) < 4) {
            $conditions['Servicestatus.current_state'] = $_stateTypes;
        }

        if ($widget['WidgetServiceStatusList']['show_acknowledged'] == false) {
            $conditions['Servicestatus.problem_has_been_acknowledged'] = 0;
        }

        if ($widget['WidgetServiceStatusList']['show_downtime'] == false) {
            $conditions['Servicestatus.scheduled_downtime_depth'] = 0;
        }

        if ($this->Controller->hasRootPrivileges === false) {
            $conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
        }

        $fields = [
            'Host.id',
            'Host.uuid',
            'Host.name',
            'Service.id',
            'Service.uuid',
            'Service.name',
            'Servicetemplate.name',
            'Servicestatus.current_state',
            'Servicestatus.last_hard_state_change',
            'Servicestatus.problem_has_been_acknowledged',
            'Servicestatus.scheduled_downtime_depth',
            'Servicestatus.is_flapping',
        ];

        $joins = [
            [
                'table'      => 'servicetemplates',
                'type'       => 'INNER',
                'alias'      => 'Servicetemplate',
                'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
            ],
            [
                'table'      => 'hosts',
                'type'       => 'INNER',
                'alias'      => 'Host',
                'conditions' => 'Host.id = Service.host_id',
            ],
            [
                'table'      => 'nagios_objects',
                'type'       => 'INNER',
                'alias'      => 'HostObject',
                'conditions' => 'HostObject.name1 = Host.uuid AND HostObject.objecttype_id = 1',
            ],
        ];

        $order = [
            'Servicestatus.current_state' => 'desc',
            'Servicestatus.last_hard_state_change' => 'desc',
        ];

        $query = $this->QueryCache->_serviceBaseQuery($fields, $conditions, $joins, $order);

        $services = $this->Controller->Service->find('all', $query);
        $this->Controller->viewVars['WidgetServiceStatusList'][$widget['Widget']['id']] = [
            'Services' => $services,
            'Widget'   => $widget,
        ];
    }

    public function refresh($widget)
    {
        $this->setData($widget);

        return [
            'element' => 'Dashboard'.DS.'service_status_list_table',
        ];
    }

}
