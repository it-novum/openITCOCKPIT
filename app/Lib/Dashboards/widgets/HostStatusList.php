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
class HostStatusList extends Widget
{
    public $isDefault = false;
    public $icon = 'fa-list-alt';
    public $element = 'host_status_list';
    public $width = 10;
    public $height = 21;
    public $hasInitialConfig = true;

    public $initialConfig = [
        'WidgetHostStatusList' => [
            'animation'          => 'fadeInUp',
            'animation_interval' => 10,
            'show_up'            => 0,
            'show_down'          => 1,
            'show_unreachable'   => 1,
            'show_acknowledged'  => 0,
            'show_downtime'      => 0,
        ],
    ];

    public function __construct(\Controller $controller, $QueryCache)
    {
        parent::__construct($controller, $QueryCache);
        $this->typeId = 9;
        $this->title = __('Hosts status list');
    }

    public function setData($widgetData)
    {
        //debug($widgetData);
        $widget = $this->Controller->Widget->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'WidgetHostStatusList',
            ],
            'conditions' => [
                'Widget.id' => $widgetData['Widget']['id'],
            ],
        ]);

        $conditions = [
            'Host.disabled' => 0,
        ];

        $mapWidgetSettingsToHoststatus = [
            'show_up'          => 0,
            'show_down'        => 1,
            'show_unreachable' => 2,
        ];

        $_stateTypes = [];
        foreach ($mapWidgetSettingsToHoststatus as $stateName => $state) {
            if ($widget['WidgetHostStatusList'][$stateName]) {
                $_stateTypes[] = $state;
            }
        }
        //Only add the condition, if it make any sens. If the user select all state types, we don't need a condition
        if (sizeof($_stateTypes) < 3) {
            $conditions['Hoststatus.current_state'] = $_stateTypes;
        }

        if ($widget['WidgetHostStatusList']['show_acknowledged'] == false) {
            $conditions['Hoststatus.problem_has_been_acknowledged'] = 0;
        }

        if ($widget['WidgetHostStatusList']['show_downtime'] == false) {
            $conditions['Hoststatus.scheduled_downtime_depth'] = 0;
        }

        if ($this->Controller->hasRootPrivileges === false) {
            $conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
        }

        $fields = [
            'Host.id',
            'Host.uuid',
            'Host.name',
            'Hoststatus.current_state',
            'Hoststatus.last_state_change',
            'Hoststatus.problem_has_been_acknowledged',
            'Hoststatus.scheduled_downtime_depth',
            'Hoststatus.is_flapping',
        ];
        $query = $this->QueryCache->_hostBaseQuery($fields, $conditions);
        $hosts = $this->Controller->Host->find('all', $query);
        $this->Controller->viewVars['widgetHoststatusList'][$widget['Widget']['id']] = [
            'Hosts'  => $hosts,
            'Widget' => $widget,
        ];
    }

    public function refresh($widget)
    {
        $this->setData($widget);

        return [
            'element' => 'Dashboard'.DS.'host_status_list_table',
        ];
    }

}
