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

class NotificationService extends Statusengine3ModuleAppModel {
    public $useTable = 'service_notifications';
    public $tablePrefix = 'statusengine_';


    /**
     * @param ServiceNotificationConditions $ServiceNotificationConditions
     * @param array $paginatorConditions
     * @return array
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

        $query = [
            'recursive' => -1,
            'fields'    => $fields,
            'joins'     => [
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' =>
                        'Service.uuid = NotificationService.service_description'
                ],
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => 'Service.servicetemplate_id = Servicetemplate.id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' =>
                        'Host.id = Service.host_id',
                ],
                [
                    'table'      => 'contacts',
                    'type'       => 'INNER',
                    'alias'      => 'Contact',
                    'conditions' =>
                        'Contact.uuid = NotificationService.contact_name'
                ],
                [
                    'table'      => 'commands',
                    'type'       => 'INNER',
                    'alias'      => 'Command',
                    'conditions' =>
                        'Command.uuid = NotificationService.command_name'
                ],
            ],

            'order' => $ServiceNotificationConditions->getOrder(),
            'limit' => $ServiceNotificationConditions->getLimit(),
        ];

        if (empty($ServiceNotificationConditions->getServiceUuid())) {
            //Get all services
            $db = $this->getDataSource();
            $conditionsSubQuery = [];
            if ($ServiceNotificationConditions->hasContainerIds()) {
                $conditionsSubQuery['HostsToContainers.container_id'] = $ServiceNotificationConditions->getContainerIds();
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
            $subQuery = 'NotificationService.hostname IN (' . $subQuery . ') ';
            $subQueryExpression = $db->expression($subQuery);
            $query['conditions'][] = $subQueryExpression->value;
        }

        $query['conditions']['NotificationService.start_time >'] = $ServiceNotificationConditions->getFrom();
        $query['conditions']['NotificationService.start_time <'] = $ServiceNotificationConditions->getTo();

        if ($ServiceNotificationConditions->getServiceUuid()) {
            //Get list for one service
            $query['conditions']['NotificationService.service_description'] = $ServiceNotificationConditions->getServiceUuid();
        }


        if (!empty($ServiceNotificationConditions->getStates())) {
            $query['conditions']['state'] = $ServiceNotificationConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        $this->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';

        return $query;
    }

}
