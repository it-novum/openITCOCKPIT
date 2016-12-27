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


App::uses('Widget', 'Admin.Lib');


class TrafficLight extends WidgetBase
{
    protected $iconnameBootstrap = 'road';
    protected $bodyClasses = 'traffic-light';
    protected $viewName = 'Dashboard/widget_trafficlight';

    public function compileTemplateData()
    {
        $stateArrayService = [];
        for ($i = 0; $i < 4; $i++) {
            $stateArrayService[$i] = $this->Servicestatus->find('count', [
                'conditions' => [
                    'current_state' => $i,
                ],
            ]);
        }

        $all_services = $this->Objects->find('all', [
            'recursive' => -1,
            'fields'    => [
                'Objects.*',
                'Servicetemplate.name',
                'Servicetemplate.description',
                'Servicestatus.*',
                'Service.name',
                'Service.description',
                'Service.uuid',
                'Service.id',
                'Host.name',
            ],
            'joins'     => [
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'conditions' => [
                        'Objects.name2 = Service.uuid',
                    ],
                ], [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ], [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ],
            'order'     => [
                'Servicestatus.current_state DESC',
            ],
        ]);

        $serviceIdsForSelect = [];
        foreach ($all_services as $service) {
            $name = $service['Service']['name'] ? $service['Service']['name'] : $service['Servicetemplate']['name'];
            $serviceIdsForSelect[$service['Service']['id']] = $service['Host']['name'].' | '.$name;
        }

        $templateVariables = [
            'state_array_service'    => $stateArrayService,
            'service_ids_for_select' => $serviceIdsForSelect,
        ];

        $trafficLightWidgetData = [
            'ids' => [
                'traffic_light' => '0',
            ],
        ];

        $this->setTemplateVariables($templateVariables);
        $this->setFrontedJson('trafficLightWidgetData', $trafficLightWidgetData);
    }
}
