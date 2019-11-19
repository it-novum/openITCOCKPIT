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

use itnovum\openITCOCKPIT\Core\ServiceNotificationConditions;

/**
 * Class NotificationService
 * @deprecated
 */
class NotificationService extends CrateModuleAppModel {
    public $useDbConfig = 'Crate';
    public $useTable = 'service_notifications';
    public $tablePrefix = 'statusengine_';


    /**
     * @param ServiceNotificationConditions $ServiceNotificationConditions
     * @param array $paginatorConditions
     * @return array
     * @deprecated
     */
    public function getQuery(ServiceNotificationConditions $ServiceNotificationConditions, $paginatorConditions = []) {

        $fields = [
            'NotificationService.start_time',
            'NotificationService.state',
            'NotificationService.output',

            'Host.id',
            'Host.uuid',
            'Host.name',

            'Service.id',
            'Service.uuid',
            'Service.name',

            'Contact.*',
            'Command.*'
        ];

        //todo CrateDB bug, check if LEFT join can be refactored with INNER join
        //https://github.com/crate/crate/issues/5747
        $query = [
            'recursive' => -1,
            'fields'    => $fields,
            'joins'     => [
                [
                    'table'      => 'openitcockpit_hosts',
                    'type'       => 'LEFT',
                    'alias'      => 'Host',
                    'conditions' =>
                        'Host.uuid = NotificationService.hostname',
                ],
                [
                    'table'      => 'openitcockpit_services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' =>
                        'Service.uuid = NotificationService.service_description',
                ],
                [
                    'table'      => 'openitcockpit_contacts',
                    'type'       => 'INNER',
                    'alias'      => 'Contact',
                    'conditions' =>
                        'Contact.uuid = NotificationService.contact_name'
                ],
                [
                    'table'      => 'openitcockpit_commands',
                    'type'       => 'INNER',
                    'alias'      => 'Command',
                    'conditions' =>
                        'Command.uuid = NotificationService.command_name'
                ]
            ],

            'conditions' => [
                'NotificationService.start_time >' => $ServiceNotificationConditions->getFrom(),
                'NotificationService.start_time <' => $ServiceNotificationConditions->getTo()
            ],

            'order' => $ServiceNotificationConditions->getOrder(),
        ];

        if ($ServiceNotificationConditions->getUseLimit()) {
            $query['limit'] = $ServiceNotificationConditions->getLimit();
        }

        if ($ServiceNotificationConditions->getServiceUuid()) {
            $query['conditions']['NotificationService.service_description'] = $ServiceNotificationConditions->getServiceUuid();
        }

        if ($ServiceNotificationConditions->hasContainerIds()) {
            $query['array_difference'] = [
                'Host.container_ids' =>
                    $ServiceNotificationConditions->getContainerIds(),
            ];
        }

        if (!empty($ServiceNotificationConditions->getStates())) {
            $query['conditions']['state'] = $ServiceNotificationConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
