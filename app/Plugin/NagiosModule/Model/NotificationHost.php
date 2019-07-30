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

/**
 * Class NotificationHost
 * @deprecated
 */
class NotificationHost extends NagiosModuleAppModel {
    public $useTable = 'notifications';
    public $primaryKey = 'notification_id';
    public $tablePrefix = 'nagios_';


    //See http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf and search for "notifications Table"

    /**
     * @param HostNotificationConditions $HostNotificationConditions
     * @param array $paginatorConditions
     * @return array
     * @deprecated
     */
    public function getQuery(HostNotificationConditions $HostNotificationConditions, $paginatorConditions = []) {

        $fields = [
            'NotificationHost.object_id',
            'NotificationHost.notification_type',
            'NotificationHost.state',
            'NotificationHost.output',

            'Host.id',
            'Host.uuid',
            'Host.name',

            'Contactnotification.notification_id',
            'Contactnotification.contact_object_id',
            'Contactnotification.start_time',

            'Contact.id',
            'Contact.uuid',
            'Contact.name',

            'Command.id',
            'Command.uuid',
            'Command.name',

            'HostsToContainers.container_id',
        ];

        $query = [
            'recursive' => -1,
            'fields'    => $fields,
            'joins'     => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = NotificationHost.object_id',
                ],

                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Objects.name1 = Host.uuid',
                ],

                [
                    'table'      => 'nagios_contactnotifications',
                    'type'       => 'INNER',
                    'alias'      => 'Contactnotification',
                    'conditions' => 'NotificationHost.notification_id = Contactnotification.notification_id',
                ],

                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'ContactObject',
                    'conditions' => 'Contactnotification.contact_object_id = ContactObject.object_id',
                ],

                [
                    'table'      => 'contacts',
                    'type'       => 'INNER',
                    'alias'      => 'Contact',
                    'conditions' => 'ContactObject.name1 = Contact.uuid',
                ],

                [
                    'table'      => 'nagios_contactnotificationmethods',
                    'type'       => 'INNER',
                    'alias'      => 'Contactnotificationmethod',
                    'conditions' => 'Contactnotificationmethod.contactnotification_id = Contactnotification.contactnotification_id',
                ],

                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'CommandObject',
                    'conditions' => 'Contactnotificationmethod.command_object_id = CommandObject.object_id',
                ],

                [
                    'table'      => 'commands',
                    'type'       => 'INNER',
                    'alias'      => 'Command',
                    'conditions' => 'CommandObject.name1 = Command.uuid',
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

            'conditions' => [
                'NotificationHost.start_time >'      => date('Y-m-d H:i:s', $HostNotificationConditions->getFrom()),
                'NotificationHost.start_time <'      => date('Y-m-d H:i:s', $HostNotificationConditions->getTo()),
                'NotificationHost.notification_type' => 0
            ],

            'group' => [
                'Contactnotification.contactnotification_id'
            ],

            'order' => $HostNotificationConditions->getOrder()
        ];

        if ($HostNotificationConditions->getUseLimit()) {
            $query['limit'] = $HostNotificationConditions->getLimit();
        }

        if ($HostNotificationConditions->getHostUuid()) {
            $query['conditions']['Objects.name1'] = $HostNotificationConditions->getHostUuid();
        }

        if ($HostNotificationConditions->hasContainerIds()) {
            $query['conditions']['HostsToContainers.container_id'] = $HostNotificationConditions->getContainerIds();
        }

        if (!empty($HostNotificationConditions->getStates())) {
            $query['conditions']['state'] = $HostNotificationConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
