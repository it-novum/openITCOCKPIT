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

class NotificationHost extends CrateModuleAppModel {
    public $useDbConfig = 'Crate';
    public $useTable = 'host_notifications';
    public $tablePrefix = 'statusengine_';


    /**
     * @param HostNotificationConditions $HostNotificationConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(HostNotificationConditions $HostNotificationConditions, $paginatorConditions = []){

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
            'fields' => $fields,
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' =>
                        'Host.uuid = NotificationHost.hostname',
                ],
                [
                    'table' => 'openitcockpit_contacts',
                    'type' => 'INNER',
                    'alias' => 'Contact',
                    'conditions' =>
                        'Contact.uuid = NotificationHost.contact_name'
                ],
                [
                    'table' => 'openitcockpit_commands',
                    'type' => 'INNER',
                    'alias' => 'Command',
                    'conditions' =>
                        'Command.uuid = NotificationHost.command_name'
                ]
            ],

            'conditions' => [
                'NotificationHost.start_time >' => $HostNotificationConditions->getFrom(),
                'NotificationHost.start_time <' => $HostNotificationConditions->getTo()
            ],

            'order' => $HostNotificationConditions->getOrder(),
            'limit' => $HostNotificationConditions->getLimit(),
        ];

        if($HostNotificationConditions->getHostUuid()){
            $query['conditions']['NotificationHost.hostname'] = $HostNotificationConditions->getHostUuid();
        }

        if ($HostNotificationConditions->hasContainerIds()) {
            $query['array_difference'] = [
                'Host.container_ids' =>
                    $HostNotificationConditions->getContainerIds(),
            ];
        }

        if(!empty($HostNotificationConditions->getStates())){
            $query['conditions']['state'] = $HostNotificationConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
