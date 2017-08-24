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

use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\Views\Downtime;

class DowntimeService extends NagiosModuleAppModel {
    public $useTable = 'downtimehistory';
    public $primaryKey = 'downtimehistory_id';
    public $tablePrefix = 'nagios_';
    public $recursive = 2;
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'object_id',
        ],
    ];

    //See http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf and search for "downtimehistory Table"
    public $downtime_type = '1,2';

    public function getQuery(DowntimeHostConditions $Conditions, $paginatorConditions) {
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'DowntimeHost.author_name',
                'DowntimeHost.comment_data',
                'DowntimeHost.entry_time',
                'DowntimeHost.scheduled_start_time',
                'DowntimeHost.scheduled_end_time',
                'DowntimeHost.duration',
                'DowntimeHost.was_started',
                'DowntimeHost.internal_downtime_id',
                'DowntimeHost.downtimehistory_id',
                'DowntimeHost.was_cancelled',

                'Host.id',
                'Host.uuid',
                'Host.name',

                'HostsToContainers.container_id',

            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = DowntimeHost.object_id AND DowntimeHost.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
                ],

                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.uuid = Objects.name1',
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'group'      => 'DowntimeHost.downtimehistory_id',
            'order'      => $Conditions->getOrder(),
            'limit'      => $Conditions->getLimit(),
            'conditions' => [
                'DowntimeHost.scheduled_start_time >' => date('Y-m-d H:i:s', $Conditions->getFrom()),
                'DowntimeHost.scheduled_start_time <' => date('Y-m-d H:i:s', $Conditions->getTo())
            ]
        ];

        if ($Conditions->hasContainerIds()) {
            $query['conditions']['HostsToContainers.container_id'] = $Conditions->getContainerIds();
        }

        if ($Conditions->hideExpired()) {
            $query['conditions']['DowntimeHost.scheduled_end_time >'] = date('Y-m-d H:i:s', time());
        }


        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

    public function getServiceDowntimesByHostAndDowntime($hostId, Downtime $Downtime) {
        $query = [
            'conditions' => [
                'DowntimeService.downtime_type'        => 1,
                'Service.host_id'               => $hostId,
                'DowntimeService.scheduled_start_time' => date('Y-m-d H:i:s',$Downtime->getScheduledStartTime()),
                'DowntimeService.scheduled_end_time'   => date('Y-m-d H:i:s', $Downtime->getScheduledEndTime())
            ],
            'fields'     => [
                'DowntimeService.internal_downtime_id',
            ],
            'joins'      => [
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => 'Service.uuid = Objects.name2',
                ]
            ]
        ];
        return $this->find('all', $query);
    }


}
