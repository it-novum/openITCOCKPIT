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

use itnovum\openITCOCKPIT\Core\HostNotificationConditions;

class NotificationHost extends Statusengine3ModuleAppModel {
    public $useTable = 'host_notifications';
    public $tablePrefix = 'statusengine_';


    /**
     * @param HostNotificationConditions $HostNotificationConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(HostNotificationConditions $HostNotificationConditions, $paginatorConditions = []) {

        $fields = [
            'NotificationHost.start_time',
            'NotificationHost.state',
            'NotificationHost.output',

            'Host.id',
            'Host.uuid',
            'Host.name',
            'Contact.*',
            'Command.*',
        ];

        $query = [
            'recursive' => -1,
            'fields'    => $fields,
            'joins'     => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' =>
                        'Host.uuid = NotificationHost.hostname',
                ],
                [
                    'table'      => 'contacts',
                    'type'       => 'INNER',
                    'alias'      => 'Contact',
                    'conditions' =>
                        'Contact.uuid = NotificationHost.contact_name'
                ],
                [
                    'table'      => 'commands',
                    'type'       => 'INNER',
                    'alias'      => 'Command',
                    'conditions' =>
                        'Command.uuid = NotificationHost.command_name'
                ],
            ],

            'order' => $HostNotificationConditions->getOrder(),
            'limit' => $HostNotificationConditions->getLimit(),
        ];

        if(empty($HostNotificationConditions->getHostUuid())){
            //Get all hosts
            $db = $this->getDataSource();
            $conditionsSubQuery = [];
            if ($HostNotificationConditions->hasContainerIds()) {
                $conditionsSubQuery['HostsToContainers.container_id'] = $HostNotificationConditions->getContainerIds();
            }
            $subQuery = $db->buildStatement([
                'fields'     => ['Host.uuid'],
                'table'      => 'hosts',
                'alias'      => 'Host',
                'limit'      => null,
                'offset'     => null,
                'joins'      => [
                    [
                        'table'      => 'hosts_to_containers',
                        'alias'      => 'HostsToContainers',
                        'type'       => 'INNER',
                        'conditions' => [
                            'HostsToContainers.host_id = Host.id',
                        ],
                    ]
                ],
                'conditions' => $conditionsSubQuery,
                'order'      => null,
                'group'      => null
            ], $this);
            $subQuery = 'NotificationHost.hostname IN (' . $subQuery . ') ';
            $subQueryExpression = $db->expression($subQuery);
            $query['conditions'][] = $subQueryExpression->value;
        }


        $query['conditions']['NotificationHost.start_time >'] = $HostNotificationConditions->getFrom();
        $query['conditions']['NotificationHost.start_time <'] = $HostNotificationConditions->getTo();

        if ($HostNotificationConditions->getHostUuid()) {
            //Get the list just for one host
            $query['conditions']['NotificationHost.hostname'] = $HostNotificationConditions->getHostUuid();
        }

        if (!empty($HostNotificationConditions->getStates())) {
            $query['conditions']['state'] = $HostNotificationConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
