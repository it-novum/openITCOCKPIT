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

class DowntimeHost extends NagiosModuleAppModel {
    public $useTable = 'downtimehistory';
    public $primaryKey = 'downtimehistory_id';
    public $tablePrefix = 'nagios_';
    public $recursive = 2;



    /**
     * @param DowntimeHostConditions $Conditions
     * @param array $filterConditions
     * @return array
     */
    public function getQuery(DowntimeHostConditions $Conditions, $filterConditions = []) {
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
        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        if($Conditions->isRunning()){
            $query['conditions']['DowntimeHost.scheduled_end_time >'] = date('Y-m-d H:i:s', time());
            $query['conditions']['DowntimeHost.was_started'] = 1;
            $query['conditions']['DowntimeHost.was_cancelled'] = 0;
        }

        return $query;
    }

    /**
     * @param DowntimeHostConditions $Conditions
     * @return array
     */
    public function getQueryForReporting(DowntimeHostConditions $Conditions) {
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'DowntimeHost.author_name',
                'DowntimeHost.comment_data',
                'DowntimeHost.scheduled_start_time',
                'DowntimeHost.scheduled_end_time',
                'DowntimeHost.duration',
                'DowntimeHost.was_started',
                'DowntimeHost.was_cancelled',
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = DowntimeHost.object_id AND DowntimeHost.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
                ],
            ],
            'order'      => $Conditions->getOrder(),
            'conditions' => [
                'OR' => [
                    '"' . date('Y-m-d H:i:s', $Conditions->getFrom()) . '"
                                        BETWEEN DowntimeHost.scheduled_start_time
                                        AND DowntimeHost.scheduled_end_time',
                    '"' . date('Y-m-d H:i:s', $Conditions->getTo()) . '"
                                        BETWEEN DowntimeHost.scheduled_start_time
                                        AND DowntimeHost.scheduled_end_time',
                    'DowntimeHost.scheduled_start_time BETWEEN "' . date('Y-m-d H:i:s', $Conditions->getFrom()) . '"
                                        AND "' . date('Y-m-d H:i:s', $Conditions->getTo()) . '"',
                ],
                'DowntimeHost.was_cancelled' => 0

            ]
        ];

        if ($Conditions->hasHostUuids()) {
            $query['conditions']['Objects.name1'] = $Conditions->getHostUuids();
        }

        return $query;
    }

    /**
     * @param int $internalDowntimeId
     * @return array
     */
    public function getHostUuidWithDowntimeByInternalDowntimeId($internalDowntimeId) {
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'DowntimeHost.*',
                'Objects.name1',

            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = DowntimeHost.object_id AND DowntimeHost.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
                ]
            ],
            'conditions' => [
                'DowntimeHost.internal_downtime_id' => $internalDowntimeId
            ]
        ];

        $result = $this->find('first', $query);
        if(empty($result)){
            return [];
        }

        return [
            'DowntimeHost' => $result['DowntimeHost'],
            'Host' => [
                'uuid' => $result['Objects']['name1']
            ]
        ];

    }

    /**
     * @param string $uuid
     * @return array|null
     */
    public function byHostUuid($uuid = null)
    {
        if ($uuid !== null) {
            $downtime = $this->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Objects.name1'         => $uuid,
                    'Objects.objecttype_id' => 1,
                ],
                'order'      => [
                    'DowntimeHost.entry_time' => 'DESC',
                ],
                'joins'      => [
                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'Objects',
                        'conditions' => 'Objects.object_id = DowntimeHost.object_id AND DowntimeHost.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
                    ]
                ]
            ]);

            return $downtime;

        }

        return [];
    }

}
