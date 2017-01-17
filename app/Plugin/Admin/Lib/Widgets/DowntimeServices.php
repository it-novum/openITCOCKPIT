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


class DowntimeServices extends WidgetBase
{
    protected $iconname = 'power-off';
    protected $createName = 'Create downtime';
    protected $createLink = '/systemdowntimes/addServicedowntime';
    protected $bodyStyles = 'height:167px;overflow:auto;';
    protected $viewName = 'Dashboard/widget_downtime_services';

    public function compileTemplateData()
    {
        $servicesInDowntime = $this->Servicestatus->find('all', [
            'recursive'     => -1,
            'fields'        => [
                'Servicestatus.service_object_id',
                'Objects.name2',
                'Service.name',
                'Service.id',
                'Service.servicetemplate_id',
                'Servicestatus.scheduled_downtime_depth',
                'Servicetemplate.id',
                'Servicetemplate.name',
            ], 'joins'      => [[
                'table'      => 'nagios_objects',
                'type'       => 'INNER',
                'alias'      => 'Objects',
                'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
            ], [
                'table'      => 'services',
                'type'       => 'INNER',
                'alias'      => 'Service',
                'conditions' => 'Service.uuid = Objects.name2',
            ], [
                'table'      => 'servicetemplates',
                'type'       => 'INNER',
                'alias'      => 'Servicetemplate',
                'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
            ],
            ], 'conditions' => [
                'Servicestatus.scheduled_downtime_depth >' => 0,
            ],
        ]);


        $templateVariables = [
            'servicesInDowntime' => $servicesInDowntime,
        ];

        $this->setTemplateVariables($templateVariables);
    }

}
