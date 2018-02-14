<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;

class DowntimeHost extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'host_downtimehistory';
    public $tablePrefix = 'statusengine_';

    /**
     * @param DowntimeHostConditions $Conditions
     * @param array $filterConditions
     * @return array
     */
    public function getQuery(DowntimeHostConditions $Conditions, $filterConditions = []) {
        $fields = [
            'DowntimeHost.author_name',
            'DowntimeHost.comment_data',
            'DowntimeHost.entry_time',
            'DowntimeHost.scheduled_start_time',
            'DowntimeHost.scheduled_end_time',
            'DowntimeHost.duration',
            'DowntimeHost.was_started',
            'DowntimeHost.internal_downtime_id',
            'DowntimeHost.was_cancelled',


            'Host.id',
            'Host.uuid',
            'Host.name',
            'Host.container_ids'
        ];

        $query = [
            'recursive' => -1,
            'fields' => $fields,
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' =>
                        'Host.uuid = DowntimeHost.hostname',
                ],
            ],

            'conditions' => [
                'DowntimeHost.scheduled_start_time >' => $Conditions->getFrom(),
                'DowntimeHost.scheduled_start_time <' => $Conditions->getTo()
            ],

            'order' => $Conditions->getOrder(),
            'limit' => $Conditions->getLimit(),
        ];

        if ($Conditions->hideExpired()) {
            $query['conditions']['DowntimeHost.scheduled_end_time >'] = time();
        }

        if ($Conditions->hasContainerIds()) {
            $query['array_difference'] = [
                'Host.container_ids' =>
                    $Conditions->getContainerIds(),
            ];
        }

        $query['conditions'] = Hash::merge($query['conditions'], $filterConditions);

        return $query;
    }

}

