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
use ProgressBar\Manager;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ExportArchivAsJsonShell
 * @property NotificationHost $NotificationHost,
 * @property NotificationService $NotificationService
 * @property StatehistoryHost $StatehistoryHost
 * @property StatehistoryService $StatehistoryService
 * @property AcknowledgedHost $AcknowledgedHost
 * @property AcknowledgedService $AcknowledgedService
 * @property DowntimeHost $DowntimeHost,
 * @property DowntimeService $DowntimeService
 */
class ExportArchivAsJsonShell extends AppShell {

    /*
     * This shell will export notifications, state history records and acknowledgements
     * as a json file on the disk.
     * Call to export all data: oitc NagiosModule.export_archiv_as_json --path /tmp/foo
     * Call to export notifications and acknowledgements only: oitc NagiosModule.export_archiv_as_json --path /tmp/foo notifications acknowledgements
     */

    //We use "hardcoded" status models, to make this available
    //even if DbBackend is set to CrateDB
    public $uses = [
        'NagiosModule.NotificationHost',
        'NagiosModule.NotificationService',
        'NagiosModule.StatehistoryHost',
        'NagiosModule.StatehistoryService',
        'NagiosModule.AcknowledgedHost',
        'NagiosModule.AcknowledgedService',
        'NagiosModule.DowntimeHost',
        'NagiosModule.DowntimeService'
    ];

    /**
     * @var int
     */
    private $limit = 500;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     * Just for CrateDB schema. Not used yet
     */
    private $nodeName = 'openITCOCKPIT';

    public function main() {
        if (!isset($this->params['path'])) {
            throw new RuntimeException('Required option --path is missing!');
        }

        if (substr($this->params['path'], 0, 1) !== '/') {
            throw new RuntimeException('Path must begin with "/". Example: /mnt/tank/export/result');
        }
        //Remove trailing slash /tmp/foo/ => /tmp/foo
        $this->basePath = rtrim($this->params['path'], '/');

        $this->fs = new Filesystem();
        if (!is_dir($this->basePath)) {
            $this->fs->mkdir($this->basePath);
        }

        if (!empty($this->args)) {
            foreach ($this->args as $arg) {
                if ($arg === 'notifications') {
                    $this->exportHostNotifications();
                    $this->exportServiceNotifications();
                }
                if ($arg === 'statehistory') {
                    $this->exportHostStatehistory();
                    $this->exportServiceStatehistory();
                }
                if ($arg === 'acknowledgements') {
                    $this->exportHostAcknowledgements();
                    $this->exportServiceAcknowledgements();
                }
                if ($arg === 'downtimes') {
                    $this->exportHostDowntimes();
                    $this->exportServiceDowntimes();
                }
            }
            return;
        }

        //Default, export all
        $this->exportHostNotifications();
        $this->exportServiceNotifications();
        $this->exportHostStatehistory();
        $this->exportServiceStatehistory();
        $this->exportHostAcknowledgements();
        $this->exportServiceAcknowledgements();
        $this->exportHostDowntimes();
        $this->exportServiceDowntimes();
    }

    public function exportHostNotifications() {
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
            'Contactnotification.end_time',

            'Contact.id',
            'Contact.uuid',
            'Contact.name',

            'Command.id',
            'Command.uuid',
            'Command.name'
        ];

        $query = [
            'recursive'  => -1,
            'fields'     => $fields,
            'joins'      => [
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
                ]

            ],
            'conditions' => [
                'NotificationHost.notification_type' => 0
            ],

            'group' => [
                'Contactnotification.contactnotification_id'
            ],

            'limit' => $this->limit
        ];


        $hostNotificationsCount = $this->NotificationHost->find('count', $query);
        $numberOfSelects = ceil($hostNotificationsCount / $this->limit);

        if ($hostNotificationsCount == 0) {
            return;
        }

        $this->out('Exporting host notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'host_notifications.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->NotificationHost->find('all', $query) as $record) {
                $data = [
                    'ack_author'   => '',
                    'ack_data'     => '',
                    'command_args' => '',
                    'command_name' => $record['Command']['uuid'],
                    'contact_name' => $record['Contact']['uuid'],
                    'end_time'     => (int)strtotime($record['Contactnotification']['end_time']),
                    'hostname'     => $record['Host']['uuid'],
                    'output'       => $record['NotificationHost']['output'],
                    'reason_type'  => (int)$record['NotificationHost']['notification_type'],
                    'start_time'   => (int)strtotime($record['Contactnotification']['start_time']),
                    'state'        => (int)$record['NotificationHost']['state']
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_host_notifications');
    }

    public function exportServiceNotifications() {
        $fields = [
            'NotificationService.object_id',
            'NotificationService.notification_type',
            'NotificationService.state',
            'NotificationService.output',

            'Host.id',
            'Host.uuid',
            'Host.name',

            'Service.id',
            'Service.uuid',

            'Contactnotification.notification_id',
            'Contactnotification.contact_object_id',
            'Contactnotification.start_time',
            'Contactnotification.end_time',

            'Contact.id',
            'Contact.uuid',
            'Contact.name',

            'Command.id',
            'Command.uuid',
            'Command.name',
        ];

        $query = [
            'recursive' => -1,
            'fields'    => $fields,
            'joins'     => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = NotificationService.object_id',
                ],

                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Objects.name1 = Host.uuid',
                ],

                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => 'Objects.name2 = Service.uuid',
                ],

                [
                    'table'      => 'nagios_contactnotifications',
                    'type'       => 'INNER',
                    'alias'      => 'Contactnotification',
                    'conditions' => 'NotificationService.notification_id = Contactnotification.notification_id',
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
                'NotificationService.notification_type' => 1,
                'NotificationService.contacts_notified > 0'
            ],

            'group' => [
                'Contactnotification.contactnotification_id'
            ],
            'limit' => $this->limit
        ];


        $serviceNotificationsCount = $this->NotificationService->find('count', $query);
        $numberOfSelects = ceil($serviceNotificationsCount / $this->limit);

        if ($serviceNotificationsCount == 0) {
            return;
        }

        $this->out('Exporting service notifications');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'service_notifications.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->NotificationService->find('all', $query) as $record) {
                $data = [
                    'ack_author'          => '',
                    'ack_data'            => '',
                    'command_args'        => '',
                    'command_name'        => $record['Command']['uuid'],
                    'contact_name'        => $record['Contact']['uuid'],
                    'end_time'            => (int)strtotime($record['Contactnotification']['end_time']),
                    'hostname'            => $record['Host']['uuid'],
                    'output'              => $record['NotificationService']['output'],
                    'reason_type'         => (int)$record['NotificationService']['notification_type'],
                    'start_time'          => (int)strtotime($record['Contactnotification']['start_time']),
                    'service_description' => $record['Service']['uuid'],
                    'state'               => (int)$record['NotificationService']['state']
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_service_notifications');
    }

    public function exportHostStatehistory() {
        $query = [
            'limit' => $this->limit
        ];

        $hostStatehistoryCount = $this->StatehistoryHost->find('count', $query);
        $numberOfSelects = ceil($hostStatehistoryCount / $this->limit);

        if ($hostStatehistoryCount == 0) {
            return;
        }

        $this->out('Exporting host state history records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'host_statehistory.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->StatehistoryHost->find('all', $query) as $record) {
                $data = [
                    'current_check_attempt' => (int)$record['StatehistoryHost']['current_check_attempt'],
                    'hostname'              => $record['Objects']['name1'],
                    'is_hardstate'          => (bool)$record['StatehistoryHost']['state_type'],
                    'last_hard_state'       => (int)$record['StatehistoryHost']['last_hard_state'],
                    'last_state'            => $record['StatehistoryHost']['last_state'],
                    'long_output'           => $record['StatehistoryHost']['long_output'],
                    'max_check_attempts'    => (int)$record['StatehistoryHost']['max_check_attempts'],
                    'output'                => $record['StatehistoryHost']['output'],
                    'state'                 => (int)$record['StatehistoryHost']['state'],
                    'state_change'          => (bool)$record['StatehistoryHost']['state_change'],
                    'state_time'            => (int)strtotime($record['StatehistoryHost']['state_time'])
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_host_statehistory');
    }

    public function exportServiceStatehistory() {
        $query = [
            'limit' => $this->limit
        ];

        $serviceStatehistoryCount = $this->StatehistoryService->find('count', $query);
        $numberOfSelects = ceil($serviceStatehistoryCount / $this->limit);

        if ($serviceStatehistoryCount == 0) {
            return;
        }

        $this->out('Exporting service state history records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'service_statehistory.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->StatehistoryService->find('all', $query) as $record) {
                $data = [
                    'current_check_attempt' => (int)$record['StatehistoryService']['current_check_attempt'],
                    'hostname'              => $record['Objects']['name1'],
                    'is_hardstate'          => (bool)$record['StatehistoryService']['state_type'],
                    'last_hard_state'       => (int)$record['StatehistoryService']['last_hard_state'],
                    'last_state'            => $record['StatehistoryService']['last_state'],
                    'long_output'           => $record['StatehistoryService']['long_output'],
                    'max_check_attempts'    => (int)$record['StatehistoryService']['max_check_attempts'],
                    'output'                => $record['StatehistoryService']['output'],
                    'service_description'   => $record['Objects']['name2'],
                    'state'                 => (int)$record['StatehistoryService']['state'],
                    'state_change'          => (bool)$record['StatehistoryService']['state_change'],
                    'state_time'            => (int)strtotime($record['StatehistoryService']['state_time'])
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_service_statehistory');
    }

    public function exportHostAcknowledgements() {
        $query = [
            'conditions' => [
                'Objects.objecttype_id' => 1,
            ],
            'limit'      => $this->limit
        ];

        $hostAcknowledgementsCount = $this->AcknowledgedHost->find('count', $query);
        $numberOfSelects = ceil($hostAcknowledgementsCount / $this->limit);

        if ($hostAcknowledgementsCount == 0) {
            return;
        }

        $this->out('Exporting host acknowledgement records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'host_acknowledgements.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->AcknowledgedHost->find('all', $query) as $record) {
                $data = [
                    'acknowledgement_type' => (int)$record['AcknowledgedHost']['acknowledgement_type'],
                    'author_name'          => $record['AcknowledgedHost']['author_name'],
                    'comment_data'         => $record['AcknowledgedHost']['comment_data'],
                    'entry_time'           => (int)strtotime($record['AcknowledgedHost']['entry_time']),
                    'hostname'             => $record['Objects']['name1'],
                    'is_sticky'            => (bool)$record['AcknowledgedHost']['is_sticky'],
                    'notify_contacts'      => (bool)$record['AcknowledgedHost']['notify_contacts'],
                    'persistent_comment'   => (bool)$record['AcknowledgedHost']['persistent_comment'],
                    'state'                => (int)$record['AcknowledgedHost']['state'],
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_host_acknowledgements');
    }

    public function exportServiceAcknowledgements() {
        $query = [
            'conditions' => [
                'Objects.objecttype_id' => 2,
            ],
            'limit'      => $this->limit
        ];

        $serviceAcknowledgementsCount = $this->AcknowledgedService->find('count', $query);
        $numberOfSelects = ceil($serviceAcknowledgementsCount / $this->limit);

        if ($serviceAcknowledgementsCount == 0) {
            return;
        }

        $this->out('Exporting service acknowledgement records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'service_acknowledgements.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->AcknowledgedService->find('all', $query) as $record) {
                $data = [
                    'acknowledgement_type' => (int)$record['AcknowledgedService']['acknowledgement_type'],
                    'author_name'          => $record['AcknowledgedService']['author_name'],
                    'comment_data'         => $record['AcknowledgedService']['comment_data'],
                    'entry_time'           => (int)strtotime($record['AcknowledgedService']['entry_time']),
                    'hostname'             => $record['Objects']['name1'],
                    'is_sticky'            => (bool)$record['AcknowledgedService']['is_sticky'],
                    'notify_contacts'      => (bool)$record['AcknowledgedService']['notify_contacts'],
                    'persistent_comment'   => (bool)$record['AcknowledgedService']['persistent_comment'],
                    'service_description'  => $record['Objects']['name2'],
                    'state'                => (int)$record['AcknowledgedService']['state'],
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_service_acknowledgements');
    }

    public function exportHostDowntimes() {
        $query = [
            'recursive' => -1,
            'fields'    => [
                'DowntimeHost.*',

                'Host.id',
                'Host.uuid',
            ],
            'joins'     => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = DowntimeHost.object_id AND DowntimeHost.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.uuid = Objects.name1',
                ]
            ],
            'group'     => 'DowntimeHost.downtimehistory_id',
            'limit'     => $this->limit
        ];


        $hostDowntimesCount = $this->DowntimeHost->find('count', $query);
        $numberOfSelects = ceil($hostDowntimesCount / $this->limit);

        if ($hostDowntimesCount == 0) {
            return;
        }

        $this->out('Exporting host downtime records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'host_downtimes.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->DowntimeHost->find('all', $query) as $record) {
                $data = [
                    'actual_end_time'      => (int)strtotime($record['DowntimeHost']['actual_end_time']),
                    'actual_start_time'    => (int)strtotime($record['DowntimeHost']['actual_start_time']),
                    'author_name'          => $record['DowntimeHost']['author_name'],
                    'comment_data'         => $record['DowntimeHost']['comment_data'],
                    'duration'             => (int)$record['DowntimeHost']['duration'],
                    'entry_time'           => (int)strtotime($record['DowntimeHost']['entry_time']),
                    'hostname'             => $record['Host']['uuid'],
                    'internal_downtime_id' => (int)$record['DowntimeHost']['internal_downtime_id'],
                    'is_fixed'             => (int)$record['DowntimeHost']['is_fixed'],
                    'node_name'            => $this->nodeName,
                    'scheduled_end_time'   => (int)strtotime($record['DowntimeHost']['scheduled_end_time']),
                    'scheduled_start_time' => (int)strtotime($record['DowntimeHost']['scheduled_start_time']),
                    'triggered_by_id'      => (int)$record['DowntimeHost']['triggered_by_id'],
                    'was_cancelled'        => (bool)$record['DowntimeHost']['was_cancelled'],
                    'was_started'          => (bool)$record['DowntimeHost']['was_started'],
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_host_downtimehistory');
    }

    public function exportServiceDowntimes() {
        $query = [
            'recursive' => -1,
            'fields'    => [
                'DowntimeService.*',
                'Host.id',
                'Host.uuid',
                'Service.id',
                'Service.uuid'
            ],
            'joins'     => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = DowntimeService.object_id AND DowntimeService.downtime_type = 1' //Downtime.downtime_type = 1 Service downtime
                ],
                [
                    'table'      => 'services',
                    'type'       => 'INNER',
                    'alias'      => 'Service',
                    'conditions' => 'Service.uuid = Objects.name2',
                ], [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Host.id = Service.host_id',
                ],
            ],
            'group'     => 'DowntimeService.downtimehistory_id',
            'limit'     => $this->limit
        ];


        $serviceDowntimesCount = $this->DowntimeService->find('count', $query);
        $numberOfSelects = ceil($serviceDowntimesCount / $this->limit);

        if ($serviceDowntimesCount == 0) {
            return;
        }

        $this->out('Exporting service downtime records');
        $ProgressBar = new Manager(0, $numberOfSelects);
        $fileName = $this->basePath . DS . 'service_downtimes.json';
        $file = fopen($fileName, 'w+');
        for ($i = 0; $i < $numberOfSelects; $i++) {
            $query['limit'] = $this->limit;
            $query['offset'] = $this->limit * $i;

            foreach ($this->DowntimeService->find('all', $query) as $record) {
                $data = [
                    'actual_end_time'      => (int)strtotime($record['DowntimeService']['actual_end_time']),
                    'actual_start_time'    => (int)strtotime($record['DowntimeService']['actual_start_time']),
                    'author_name'          => $record['DowntimeService']['author_name'],
                    'comment_data'         => $record['DowntimeService']['comment_data'],
                    'duration'             => (int)$record['DowntimeService']['duration'],
                    'entry_time'           => (int)strtotime($record['DowntimeService']['entry_time']),
                    'hostname'             => $record['Host']['uuid'],
                    'internal_downtime_id' => (int)$record['DowntimeService']['internal_downtime_id'],
                    'is_fixed'             => (int)$record['DowntimeService']['is_fixed'],
                    'node_name'            => $this->nodeName,
                    'scheduled_end_time'   => (int)strtotime($record['DowntimeService']['scheduled_end_time']),
                    'scheduled_start_time' => (int)strtotime($record['DowntimeService']['scheduled_start_time']),
                    'service_description'  => $record['Service']['uuid'],
                    'triggered_by_id'      => (int)$record['DowntimeService']['triggered_by_id'],
                    'was_cancelled'        => (bool)$record['DowntimeService']['was_cancelled'],
                    'was_started'          => (bool)$record['DowntimeService']['was_started'],
                ];
                fwrite($file, json_encode($data) . PHP_EOL);
            }
            $ProgressBar->update(($i + 1));
        }
        fclose($file);
        $this->out('');
        $this->printCrashMsg($fileName, 'statusengine_service_downtimehistory');
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'path' => ['short' => 'p', 'help' => 'Path where the exporter will create the json files to. Make sure you have enough disk space!'],
        ]);
        $parser->addArguments([
            'notifications'    => ['help' => 'Will export host and service notificatiosn as json'],
            'statehistory'     => ['help' => 'Will export host and service statehistory records as json'],
            'acknowledgements' => ['help' => 'Will export host and service acknowledgements as json'],
            'downtimes'        => ['help' => 'Will export host and service downtimes as json'],
        ]);
        return $parser;
    }

    public function printCrashMsg($fileName, $tableName = '<table name>') {
        $this->out(sprintf('File <info>%s</info> exported.', $fileName));
        $this->out('You can import the file to CrateDB via "crash"');
        $this->out(sprintf("cr> COPY %s FROM '%s' WITH (bulk_size = 2000);", $tableName, $fileName));
        $this->out();
    }

    public function _welcome() {
        $this->out('');
        $this->out('<info>Export archive tables from NDOUtils based database schemas to a json file</info>');
        $this->hr();
        $this->out('');
    }

}