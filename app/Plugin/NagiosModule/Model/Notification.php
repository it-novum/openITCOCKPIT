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

class Notification extends NagiosModuleAppModel
{
    //public $useDbConfig = 'default';
    public $useTable = 'notifications';
    public $primaryKey = 'notification_id';
    public $tablePrefix = 'nagios_';

    //See http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf and search for "notifications Table"
    public $notification_type = [0, 1];


    public function listSettings($requestData)
    {

        //$host_state_types = [
        //	'recovery' => 0,
        //	'down' => 1,
        //	'unreachable' => 2
        //];
        //
        //$service_state_types = [
        //	'recovery' => 0,
        //	'warning' => 1,
        //	'critical' => 2,
        //	'unknown' => 3,
        //];

        $return = [
            'conditions'   => [],
            'paginator'    => [],
            'Listsettings' => [],
        ];


        if (isset($requestData['Listsettings']['view'])) {
            switch ($requestData['Listsettings']['view']) {
                case 'hostOnly':
                    $return['notifiction_type'] = 0;
                    $return['Listsettings']['view'] = 'hostOnly';
                    break;

                case 'serviceOnly':
                    $return['notifiction_type'] = 1;
                    $return['Listsettings']['view'] = 'serviceOnly';
                    break;

                default:
                    $return['notifiction_type'] = 0;
                    $return['Listsettings']['view'] = 'hostOnly';
                    break;
            }
        }

        //if(isset($requestData['Listsettings']['state_types'])){
        //	if(isset($requestData['Listsettings']['state_types']['Host'])){
        //		foreach($requestData['Listsettings']['state_types']['Host'] as $state_name => $value){
        //			if($value == 1 && isset($host_state_types[$state_name])){
        //				$return['conditions']['Notification.state'][] = $host_state_types[$state_name];
        //				$return['Listsettings']['state_types']['Host'][$state_name] = 1;
        //			}
        //		}
        //	}
        //	
        //	if(isset($requestData['Listsettings']['state_types']['Service'])){
        //		foreach($requestData['Listsettings']['state_types']['Service'] as $state_name => $value){
        //			if($value == 1 && isset($service_state_types[$state_name])){
        //				$return['conditions']['Notification.state'][] = $service_state_types[$state_name];
        //				$return['Listsettings']['state_types']['Service'][$state_name] = 1;
        //			}
        //		}
        //	}
        //
        //$return['conditions']['Notification.state'] = array_unique($return['conditions']['Notification.state']);
        //
        //}


        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['from'])) {
            $time = strtotime($requestData['Listsettings']['from']);
            if ($time == false || !is_numeric($time)) {
                $time = strtotime('3 days ago');
            }

            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['from'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
            $return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
        }

        if (isset($requestData['Listsettings']['to'])) {
            $time = strtotime($requestData['Listsettings']['to']);
            if ($time == false || !is_numeric($time)) {
                $time = time() + (60 * 5); //Avoid missing entries in result
            }

            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['to'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
            $return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
        }

        //debug($requestData);
        //debug($return);
        return $return;

    }

    public function serviceListSettings($requestData, $hostUuid, $serviceUuid)
    {

        $return = [
            'conditions'   => [
                'Objects.name1'         => $hostUuid,
                'Objects.name2'         => $serviceUuid,
                'Objects.objecttype_id' => 2,
            ],
            'paginator'    => [],
            'Listsettings' => [],
        ];


        $return['notifiction_type'] = 1;


        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['from'])) {
            $time = strtotime($requestData['Listsettings']['from']);
            if ($time == false || !is_numeric($time)) {
                $time = strtotime('3 days ago');
            }

            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['from'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
            $return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
        }

        if (isset($requestData['Listsettings']['to'])) {
            $time = strtotime($requestData['Listsettings']['to']);
            if ($time == false || !is_numeric($time)) {
                $time = time() + (60 * 5); //Avoid missing entries in result
            }

            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['to'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
            $return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
        }

        return $return;

    }

    public function hostListSettings($requestData, $hostUuid)
    {

        $return = [
            'conditions'   => [
                'Objects.name1'         => $hostUuid,
                'Objects.objecttype_id' => 1,
            ],
            'paginator'    => [],
            'Listsettings' => [],
        ];


        $return['notifiction_type'] = 0;


        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['from'])) {
            $time = strtotime($requestData['Listsettings']['from']);
            if ($time == false || !is_numeric($time)) {
                $time = strtotime('3 days ago');
            }

            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['from'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
            $return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
        }

        if (isset($requestData['Listsettings']['to'])) {
            $time = strtotime($requestData['Listsettings']['to']);
            if ($time == false || !is_numeric($time)) {
                $time = time() + (60 * 5); //Add 5 minutes to avoid missing entries in result
            }

            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['to'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Contactnotification.start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
            $return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
        }

        return $return;

    }

    public function paginatorSettings($notifiction_type = 0, $order, $conditions = [], $join = null, $MY_RIGHTS = [])
    {
        $_conditions = [
            'Notification.notification_type'   => $notifiction_type,
            'Notification.contacts_notified >' => 0,
            'HostsToContainers.container_id'   => $MY_RIGHTS,
        ];
        $conditions = Hash::merge($_conditions, $conditions);

        if ($notifiction_type == 0) {
            //Host notifications
            $fields = [
                'Notification.object_id',
                'Notification.notification_type',
                'Notification.state',
                'Notification.output',

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

        } else {
            //Service notifications
            $fields = [
                'Notification.object_id',
                'Notification.notification_type',
                'Notification.state',
                'Notification.output',

                'Host.id',
                'Host.uuid',
                'Host.name',

                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.servicetemplate_id',

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
        }

        return [
            'recursive'  => -1,
            'fields'     => Hash::merge($fields, $join['fields']),
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = Notification.object_id',
                ],

                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Objects.name1 = Host.uuid',
                ],

                [
                    'table'      => 'services',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Service',
                    'conditions' => 'Objects.name2 = Service.uuid',
                ],

                [
                    'table'      => 'nagios_contactnotifications',
                    'type'       => 'INNER',
                    'alias'      => 'Contactnotification',
                    'conditions' => 'Notification.notification_id = Contactnotification.notification_id',
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

                $join['join'],

            ],
            'order'      => $order,
            //'group'      => 'Notification.notification_id',
            'conditions' => $conditions,
            'findType'   => 'all',

        ];
    }

}