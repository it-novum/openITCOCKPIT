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

use CakePlugin;
use ClassRegistry;

class Grafana extends Widget {

    public $isDefault = false;
    public $icon = 'fa-area-chart';
    public $element = 'grafana';
    public $width = 4;
    public $height = 16;

    public function __construct(\Controller $controller, $QueryCache) {
        parent::__construct($controller, $QueryCache);
        $this->typeId = 16;
        $this->title = __('Grafana');
    }

    public function setData($widgetData){
        if (!CakePlugin::loaded('GrafanaModule')) {
            throw new NotFoundException(__('GrafanaModule not loaded'));
        }
        $this->GrafanaDashboard = ClassRegistry::init('GrafanaModule.GrafanaDashboard');

        $grafanaHostListForWidget = $this->GrafanaDashboard->find('all', [
            'fields'     => [
                'GrafanaDashboard.id',
                'GrafanaDashboard.host_id',
                'GrafanaDashboard.host_uuid',
                'Host.name'
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'Host.id = GrafanaDashboard.host_id',
                    ],
                ],
            ],
        ]);

        $grafanaDashboard = null;
        $GrafanaDashboardExists = false;

        if(!empty($widgetData['Widget']['host_id'])){
            $hostId = $widgetData['Widget']['host_id'];
            $this->GrafanaDashboard = ClassRegistry::init('GrafanaModule.GrafanaDashboard');
            $this->GrafanaConfiguration = ClassRegistry::init('GrafanaModule.GrafanaConfiguration');
            $this->Host = ClassRegistry::init('Host');
            $grafanaConfiguration = $this->GrafanaConfiguration->find('first');

            $host = $this->Host->find('first',[
                'recursive' => -1,
                'conditions' => [
                    'Host.id' => $hostId,
                ],
                'fields' => [
                    'Host.uuid',
                ]
            ]);
            if (!empty($grafanaConfiguration) && $this->GrafanaDashboard->existsForUuid($host['Host']['uuid'])) {
                $GrafanaDashboardExists = true;
                $GrafanaConfiguration = \itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                $GrafanaConfiguration->setHostUuid($host['Host']['uuid']);
                $this->Controller->set('GrafanaConfiguration', $GrafanaConfiguration);
            }
        }
        $this->Controller->set('GrafanaDashboardExists', $GrafanaDashboardExists);

        $this->Controller->viewVars['widgetGafana'][$widgetData['Widget']['id']] = [
            'Widget' => $widgetData,
        ];
        $this->Controller->set('grafanaHostListForWidget', $grafanaHostListForWidget);
    }

    public function refresh($widget) {
        $this->setData($widget);

        return [
            'element' => 'Dashboard'.DS.$this->element,
        ];
    }

}