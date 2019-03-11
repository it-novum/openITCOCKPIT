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

use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ProxiesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Comparison\HostComparison;
use itnovum\openITCOCKPIT\Core\Comparison\HostComparisonForSave;

class TestingShell extends AppShell {
    /*
     * This is a test and debuging shell for development purposes
     */
    public $uses = [
        'Systemsetting',
        MONITORING_CORECONFIG_MODEL,
        'Host',
        'Servicetemplate',
        'Hosttemplate',
        'Service',
        'Hostgroup',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Servicetemplateeventcommandargumentvalue',
        'Serviceeventcommandargumentvalue',
        'Command',
        'Contact',
        'Contactgroup',
        'Servicegroup',
        'Timeperiod',
        'Macro',
        'Hostescalation',
        'Hostcommandargumentvalue',
        'Servicecommandargumentvalue',
        'Aro',
        'Aco',
        'Calendar',
        'Container',
        'User',
        'Browser'
    ];

    public function main() {
        //debug($this->Aro->find('all'));
        //debug($this->Aco->find('all', ['recursive' => -1]));

        //Load CakePHP4 Models
        /** @var $Proxy ProxiesTable */
        //$Proxy = TableRegistry::getTableLocator()->get('Proxies');
        //print_r($Proxy->getSettings());

        /*
         * Lof of space for your experimental code :)
         */

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        //debug($HosttemplatesTable->getHosttemplateForDiff(27));

        $HostComparison = new HostComparisonForSave([
            'Host' => [
                'name' => '',
                'description' => 'max and all',
                'hosttemplate_id' => (int) 27,
                'address' => '',
                'command_id' => (int) 40,
                'eventhandler_command_id' => (int) 0,
                'check_interval' => (int) 1,
                'retry_interval' => (int) 14400,
                'max_check_attempts' => (int) 10,
                'first_notification_delay' => (int) 0,
                'notification_interval' => (int) 14400,
                'notify_on_down' => (int) 1,
                'notify_on_unreachable' => (int) 1,
                'notify_on_recovery' => (int) 1,
                'notify_on_flapping' => (int) 1,
                'notify_on_downtime' => (int) 1,
                'flap_detection_enabled' => (int) 1,
                'flap_detection_on_up' => (int) 1,
                'flap_detection_on_down' => (int) 1,
                'flap_detection_on_unreachable' => (int) 1,
                'low_flap_threshold' => (int) 0,
                'high_flap_threshold' => (int) 0,
                'process_performance_data' => (int) 0,
                'freshness_checks_enabled' => (int) 0,
                'freshness_threshold' => (int) 0,
                'passive_checks_enabled' => (int) 1,
                'event_handler_enabled' => (int) 0,
                'active_checks_enabled' => (int) 1,
                'retain_status_information' => (int) 0,
                'retain_nonstatus_information' => (int) 0,
                'notifications_enabled' => (int) 0,
                'notes' => 'max',
                'priority' => (int) 5,
                'check_period_id' => (int) 1,
                'notify_period_id' => (int) 1,
                'tags' => 'all,max,4',
                'container_id' => (int) 1,
                'host_url' => 'allalll',
                'satellite_id' => (int) 0,


                'contacts'                  => [
                    '_ids' => [
                        1, 2, 3
                    ]
                ],
                'contactgroups'             => [
                    '_ids' => [
                        1, 3, 2
                    ]
                ],
                'hostgroups' => [
                    '_ids' => [
                        (int) 0 => (int) 1,
                        (int) 1 => (int) 2,
                        (int) 2 => (int) 4,
                        (int) 3 => (int) 5,
                        (int) 4 => (int) 6
                    ]
                ],
                'customvariables'           => [
                    (int)0 => [
                        'objecttype_id' => (int)512,
                        'name'          => 'OPEN',
                        'value'         => 'your'
                    ],
                    (int)1 => [
                        'objecttype_id' => (int)512,
                        'name'          => 'EYES',
                        'value'         => 'sssss'
                    ]
                ],
                'hostcommandargumentvalues' => [
                    (int)0 => [
                        'commandargument_id' => (int)3,
                        'value'              => 'all',
                        'commandargument'    => [
                            'id'         => (int)3,
                            'command_id' => (int)4,
                            'name'       => '$ARG1$',
                            'human_name' => 'Warning',
                            'created'    => '2015-01-05T15:21:32+01:00',
                            'modified'   => '2015-01-05T15:21:32+01:00'
                        ]
                    ],
                    (int)1 => [
                        'commandargument_id' => (int)4,
                        'value'              => 'max',
                        'commandargument'    => [
                            'id'         => (int)4,
                            'command_id' => (int)4,
                            'name'       => '$ARG2$',
                            'human_name' => 'Critical',
                            'created'    => '2015-01-05T15:21:32+01:00',
                            'modified'   => '2015-01-05T15:21:32+01:00'
                        ]
                    ]
                ]
            ]
        ],
            [
                'Hosttemplate' => [

                    'name' => '',
                    'description' => 'max and all',
                    'hosttemplate_id' => (int) 27,
                    'address' => '',
                    'command_id' => (int) 4,
                    'eventhandler_command_id' => (int) 0,
                    'check_interval' => (int) 14400,
                    'retry_interval' => (int) 14400,
                    'max_check_attempts' => (int) 10,
                    'first_notification_delay' => (int) 0,
                    'notification_interval' => (int) 14400,
                    'notify_on_down' => (int) 1,
                    'notify_on_unreachable' => (int) 1,
                    'notify_on_recovery' => (int) 1,
                    'notify_on_flapping' => (int) 1,
                    'notify_on_downtime' => (int) 1,
                    'flap_detection_enabled' => (int) 1,
                    'flap_detection_on_up' => (int) 1,
                    'flap_detection_on_down' => (int) 1,
                    'flap_detection_on_unreachable' => (int) 1,
                    'low_flap_threshold' => (int) 0,
                    'high_flap_threshold' => (int) 0,
                    'process_performance_data' => (int) 0,
                    'freshness_checks_enabled' => (int) 0,
                    'freshness_threshold' => (int) 0,
                    'passive_checks_enabled' => (int) 1,
                    'event_handler_enabled' => (int) 0,
                    'active_checks_enabled' => (int) 1,
                    'retain_status_information' => (int) 0,
                    'retain_nonstatus_information' => (int) 0,
                    'notifications_enabled' => (int) 0,
                    'notes' => 'max',
                    'priority' => (int) 5,
                    'check_period_id' => (int) 1,
                    'notify_period_id' => (int) 1,
                    'tags' => 'all,max,4',
                    'container_id' => (int) 1,
                    'host_url' => 'allalll',
                    'satellite_id' => (int) 0,

                    'contacts'                          => [
                        '_ids' => [
                            1, 2, 3
                        ]
                    ],
                    'contactgroups'                     => [
                        '_ids' => [
                            1, 2, 3
                        ]
                    ],
                    'customvariables'                   => [
                        (int)0 => [
                            'objecttype_id' => (int)512,
                            'name'          => 'OPEN',
                            'value'         => 'your'
                        ],
                        (int)1 => [
                            'objecttype_id' => (int)512,
                            'name'          => 'EYES',
                            'value'         => 'sssss'
                        ]
                    ],
                    'hosttemplatecommandargumentvalues' => [
                        (int)0 => [
                            'commandargument_id' => (int)3,
                            'hosttemplate_id'    => (int)27,
                            'value'              => 'all',
                            'commandargument'    => [
                                'id'         => (int)3,
                                'command_id' => (int)4,
                                'name'       => '$ARG1$',
                                'human_name' => 'Warning',
                            ]
                        ],
                        (int)1 => [
                            'commandargument_id' => (int)4,
                            'hosttemplate_id'    => (int)27,
                            'value'              => 'max',
                            'commandargument'    => [
                                'id'         => (int)4,
                                'command_id' => (int)4,
                                'name'       => '$ARG2$',
                                'human_name' => 'Critical'
                            ]
                        ]
                    ],
                    'hostgroups' => [
                        '_ids' => [
                            (int) 0 => (int) 1,
                            (int) 1 => (int) 2,
                            (int) 2 => (int) 4,
                            (int) 3 => (int) 5,
                            (int) 4 => (int) 6
                        ]
                    ],
                ]
            ]);

        debug($HostComparison->getDataForSaveForAllFields());

        debug($HostgroupsTable->getHostgroupsAsList([1,2,4]));

        debug($HostsTable->getHostsAsList([1,2,3,4, 500]));
        debug($HosttemplatesTable->getHosttemplatesAsList(1));
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type'     => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }

}
