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


class DowntimeHosts extends WidgetBase
{
    protected $iconname = 'power-off';
    protected $createName = 'Create downtime';
    protected $createLink = '/systemdowntimes/addHostdowntime';
    protected $bodyStyles = 'height:167px;overflow:auto;';
    protected $viewName = 'Dashboard/widget_downtime_hosts';

    public function compileTemplateData()
    {
        $hostsInDowntime = $this->Hoststatus->find('all', [
            'recursive'  => -1,
            'fields'     => [
                'Hoststatus.host_object_id',
                'Objects.name1',
                'Host.name',
                'Host.id',
                'Hoststatus.scheduled_downtime_depth',
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.uuid = Objects.name1',
                ],
            ],
            'conditions' => [
                'Hoststatus.scheduled_downtime_depth >' => 0,
            ],
        ]);

        $templateVariables = [
            'hostsInDowntime' => $hostsInDowntime,
        ];

        $this->setTemplateVariables($templateVariables);
    }
}
