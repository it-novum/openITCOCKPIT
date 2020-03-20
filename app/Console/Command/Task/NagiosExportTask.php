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

/**
 * Class NagiosExportTask
 * @property Hosttemplate $Hosttemplate
 * @property Timeperiod $Timeperiod
 * @property Command $Command
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Customvariable $Customvariable
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
 * @property Servicetemplatecommandargumentvalue $Servicetemplatecommandargumentvalue
 * @property Hostcommandargumentvalue $Hostcommandargumentvalue
 * @property Servicecommandargumentvalue $Servicecommandargumentvalue
 * @property Commandargument $Commandargument
 * @property Hostgroup $Hostgroup
 * @property Hostescalation $Hostescalation
 * @property Host $Host
 * @property Macro $Macro
 * @property Servicetemplate $Servicetemplate
 * @property Service $Service
 * @property Systemsetting $Systemsetting
 * @property Serviceescalation $Serviceescalation
 * @property Servicegroup $Servicegroup
 * @property Hostdependency $Hostdependency
 * @property Servicedependency $Servicedependency
 * @property DeletedService $DeletedService
 * @property DeletedHost $DeletedHost
 * @property Serviceeventcommandargumentvalue $Serviceeventcommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 * @property Satellite $Satellite
 */
class NagiosExportTask extends AppShell {
    /**
     * This code gets executed by the sudo_server!
     * @var array
     */
    public $uses = [
        'Hosttemplate',
        'Timeperiod',
        'Command',
        'Contact',
        'Contactgroup',
        'Container',
        'Customvariable',
        'Hosttemplatecommandargumentvalue',
        'Servicetemplatecommandargumentvalue',
        'Hostcommandargumentvalue',
        'Servicecommandargumentvalue',
        'Commandargument',
        'Hostgroup',
        'Hostescalation',
        'Host',
        'Macro',
        'Servicetemplate',
        'Service',
        'Systemsetting',
        'Serviceescalation',
        'Servicegroup',
        'Hostdependency',
        'Servicedependency',
        'DeletedService',
        'DeletedHost',
        'Serviceeventcommandargumentvalue',
        'Servicetemplateeventcommandargumentvalue',
        'Calendar'
    ];

    /**
     * NagiosExportTask constructor.
     */
    public function __construct() {
        parent::__construct();
        //Loading components
        $this->init();
    }

    /**
     * SudoServer normally runs 24/7 so we need to refresh
     * class variables for each export, may be some thing changed in
     * Systemsettings, Satelliteconfig, or in on of the config files
     */
    public function init() {
        App::uses('Component', 'Controller');
        App::uses('ConstantsComponent', 'Controller/Component');
        $this->Constants = new ConstantsComponent();

        Configure::load('nagios');
        Configure::load('rrd');
        $this->conf = Configure::read('nagios.export');
        $this->_systemsettings = $this->Systemsetting->findAsArray();
        $this->FRESHNESS_THRESHOLD_ADDITION = (int)$this->_systemsettings['MONITORING']['MONITORING.FRESHNESS_THRESHOLD_ADDITION'];

        //Loading external tasks
        $this->__loadExternTasks();

        //Loading distributed Monitoring support, if plugin is loaded
        $this->dm = false;
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        if (in_array('DistributeModule', $modulePlugins)) {
            $this->dm = true;
            $this->dmConfig = [];

            //Loading external Model
            $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
            $this->Satellites = $this->Satellite->find('all');

            //Create default config folder for sat systems
            if (!is_dir($this->conf['satellite_path'])) {
                mkdir($this->conf['satellite_path']);
            }

            //Create rollout folder
            if (!is_dir($this->conf['rollout'])) {
                mkdir($this->conf['rollout']);
            }

            foreach ($this->Satellites as $satellite) {
                if (!is_dir($this->conf['satellite_path'] . $satellite['Satellite']['id'])) {
                    mkdir($this->conf['satellite_path'] . $satellite['Satellite']['id']);
                }

                if (!is_dir($this->conf['satellite_path'] . $satellite['Satellite']['id'] . DS . $this->conf['config'])) {
                    mkdir($this->conf['satellite_path'] . $satellite['Satellite']['id'] . DS . $this->conf['config']);
                }
            }

        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportCommands($uuid = null) {
        if ($uuid !== null) {
            $commands = [];
            $commands[] = $this->Command->findByUuid($uuid);
        } else {
            $commands = $this->Command->find('all', [
                'recursive' => -1,
                'contain'   => [],
                'fields'    => [
                    'id',
                    'uuid',
                    'command_line',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['commands'])) {
            mkdir($this->conf['path'] . $this->conf['commands']);
        }

        if ($this->conf['minified'] == true) {
            $file = new File($this->conf['path'] . $this->conf['commands'] . 'commands_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($commands as $command) {
            if (!empty($command['Command']['command_line'])) {

                if ($this->conf['minified'] == false) {
                    $file = new File($this->conf['path'] . $this->conf['commands'] . $command['Command']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }


                $content .= $this->addContent('define command{', 0);
                $content .= $this->addContent('command_name', 1, $command['Command']['uuid']);
                $content .= $this->addContent('command_line', 1, $command['Command']['command_line']);
                $content .= $this->addContent('}', 0);

                if ($this->conf['minified'] == false) {
                    $file->write($content);
                    $file->close();
                }
            }
        }

        if ($this->conf['minified'] == true) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportContacts($uuid = null) {
        if ($uuid !== null) {
            $contacts = [];
            $contacts[] = $this->Contact->findByUuid($uuid);
        } else {
            $contacts = $this->Contact->find('all', [
                'recursive' => -1,
                'contain'   => [
                    'HostTimeperiod',
                    'ServiceTimeperiod',
                    'HostCommands',
                    'ServiceCommands',
                    'Customvariable',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['contacts'])) {
            mkdir($this->conf['path'] . $this->conf['contacts']);
        }


        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['contacts'] . 'contacts_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($contacts as $contact) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['contacts'] . $contact['Contact']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define contact{', 0);
            $content .= $this->addContent('contact_name', 1, $contact['Contact']['uuid']);
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($contact['Contact']['description']));
            $content .= $this->addContent('host_notifications_enabled', 1, $contact['Contact']['host_notifications_enabled']);
            $content .= $this->addContent('service_notifications_enabled', 1, $contact['Contact']['service_notifications_enabled']);
            $content .= $this->addContent('host_notification_period', 1, $contact['HostTimeperiod']['uuid']);
            $content .= $this->addContent('service_notification_period', 1, $contact['ServiceTimeperiod']['uuid']);
            $content .= $this->addContent('host_notification_commands', 1, implode(',', Hash::extract($contact['HostCommands'], '{n}.uuid')));
            $content .= $this->addContent('service_notification_commands', 1, implode(',', Hash::extract($contact['ServiceCommands'], '{n}.uuid')));
            $content .= $this->addContent('host_notification_options', 1, $this->contactHostNotificationOptions($contact['Contact']));
            $content .= $this->addContent('service_notification_options', 1, $this->contactServiceNotificationOptions($contact['Contact']));
            $content .= $this->addContent('email', 1, $contact['Contact']['email']);
            if (!empty($contact['Contact']['phone'])) {
                $content .= $this->addContent('pager', 1, $contact['Contact']['phone']);
            }

            if (!empty($contact['Customvariable'])) {
                $content .= $this->nl();
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($contact['Customvariable'] as $customvariable) {
                    $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                }
            }

            if (!empty($contact['Contact']['user_id'])) {
                $content .= $this->addContent('_OITCUSERID', 1, $contact['Contact']['user_id']);
            }

            $content .= $this->addContent('}', 0);
            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportContactgroups($uuid = null) {
        if ($uuid !== null) {
            $contactgroups = [];
            $contactgroups[] = $this->Contactgroup->findByUuid($uuid);
        } else {
            $contactgroups = $this->Contactgroup->find('all', [
                'recursive' => -1,
                'contain'   => [
                    'Contact' => [
                        'fields' => [
                            'Contact.id',
                            'Contact.uuid',
                        ],
                    ],
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['contactgroups'])) {
            mkdir($this->conf['path'] . $this->conf['contactgroups']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['contactgroups'] . 'contactgroups_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }


        foreach ($contactgroups as $contactgroup) {
            if (!empty($contactgroup['Contact'])) {
                if (!$this->conf['minified']) {
                    $file = new File($this->conf['path'] . $this->conf['contactgroups'] . $contactgroup['Contactgroup']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }

                $content .= $this->addContent('define contactgroup{', 0);
                $content .= $this->addContent('contactgroup_name', 1, $contactgroup['Contactgroup']['uuid']);
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                    $contactgroup['Contactgroup']['description']
                ));
                $content .= $this->addContent('members', 1, implode(',', Hash::extract($contactgroup['Contact'], '{n}.uuid')));
                $content .= $this->addContent('}', 0);
                if (!$this->conf['minified']) {
                    $file->write($content);
                    $file->close();
                }
            }
        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHosttemplates($uuid = null) {
        if ($uuid !== null) {
            $hosttemplates = [];
            $hosttemplates[] = $this->Hosttemplate->findByUuid($uuid);
        } else {
            $hosttemplates = $this->Hosttemplate->find('all', [
                'recursive' => -1,
                'contain'   => [
                    'CheckPeriod',
                    'NotifyPeriod',
                    'CheckCommand',
                    'Customvariable',
                    'Hosttemplatecommandargumentvalue' => [
                        'Commandargument',
                    ],
                    'Contactgroup',
                    'Contact',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['hosttemplates'])) {
            mkdir($this->conf['path'] . $this->conf['hosttemplates']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['hosttemplates'] . 'hosttemplates_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($hosttemplates as $hosttemplate) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hosttemplates'] . $hosttemplate['Hosttemplate']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $commandarguments = [];
            if (!empty($hosttemplate['Hosttemplatecommandargumentvalue'])) {
                //Select command arguments + command, because we have arguments!
                $commandarguments = Hash::sort($hosttemplate['Hosttemplatecommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
            }

            $content .= $this->addContent('define host{', 0);
            $content .= $this->addContent('register', 1, 0);
            $content .= $this->addContent('use', 1, '8147201e91c4dcf7c016ba2ddeac3fd7e72edacc');
            $content .= $this->addContent('host_name', 1, $hosttemplate['Hosttemplate']['uuid']);
            $content .= $this->addContent('name', 1, $hosttemplate['Hosttemplate']['uuid']);
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $hosttemplate['Hosttemplate']['name']
            ));
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                $hosttemplate['Hosttemplate']['description']
            ));

            $content .= $this->nl();
            $content .= $this->addContent(';Check settings:', 1);
            if (isset($commandarguments) && !empty($commandarguments)) {
                $content .= $this->addContent('check_command', 1, $hosttemplate['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                $content .= $this->addContent('check_command', 1, $hosttemplate['CheckCommand']['uuid']);
            }

            if (isset($commandarguments)) {
                unset($commandarguments);
            }

            $content .= $this->addContent('initial_state', 1, $this->_systemsettings['MONITORING']['MONITORING.HOST.INITSTATE']);
            $content .= $this->addContent('check_period', 1, $hosttemplate['CheckPeriod']['uuid']);
            $content .= $this->addContent('check_interval', 1, $hosttemplate['Hosttemplate']['check_interval']);
            $content .= $this->addContent('retry_interval', 1, $hosttemplate['Hosttemplate']['retry_interval']);
            $content .= $this->addContent('max_check_attempts', 1, $hosttemplate['Hosttemplate']['max_check_attempts']);
            $content .= $this->addContent('active_checks_enabled', 1, $hosttemplate['Hosttemplate']['active_checks_enabled']);
            $content .= $this->addContent('passive_checks_enabled', 1, 1);


            $content .= $this->nl();
            $content .= $this->addContent(';Notification settings:', 1);
            $content .= $this->addContent('notifications_enabled', 1, 1);

            if (!empty($hosttemplate['Contact'])) {
                $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($hosttemplate['Contact'], '{n}.uuid')));
            }


            if (!empty($hosttemplate['Contactgroup'])) {
                $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($hosttemplate['Contactgroup'], '{n}.uuid')));
            }

            $content .= $this->addContent('notification_interval', 1, $hosttemplate['Hosttemplate']['notification_interval']);
            $content .= $this->addContent('notification_period', 1, $hosttemplate['NotifyPeriod']['uuid']);

            $hostNotificationString = $this->hostNotificationString($hosttemplate['Hosttemplate']);
            if (!empty($hostNotificationString)) {
                $content .= $this->addContent('notification_options', 1, $hostNotificationString);
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $hosttemplate['Hosttemplate']['flap_detection_enabled']);
            if ($hosttemplate['Hosttemplate']['flap_detection_enabled'] == 1) {
                $content .= $this->addContent('flap_detection_options', 1, $this->hostFlapdetectionString($hosttemplate['Hosttemplate']));
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Everything else:', 1);
            if (isset($hosttemplate['Hosttemplate']['process_performance_data'])) {
                $content .= $this->addContent('process_perf_data', 1, $hosttemplate['Hosttemplate']['process_performance_data']);
            }
            if (!empty($hosttemplate['Hosttemplate']['notes']))
                $content .= $this->addContent('notes', 1, $hosttemplate['Hosttemplate']['notes']);


            if (!empty($hosttemplate['Customvariable'])) {
                $content .= $this->nl();
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($hosttemplate['Customvariable'] as $customvariable) {
                    $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                }
            }
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }

        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     * @param array $options with 'start' and 'end' as keys and numeric values
     */
    public function exportHosts($uuid = null, $options = []) {
        if ($uuid !== null) {
            $hosts = [];
            $hosts[] = $this->Host->find('first', [
                'conditions' => [
                    'Host.disabled' => 0,
                    'Host.uuid'     => $uuid,
                ],
            ]);
        } else {
            if (isset($options['limit']) && isset($options['offset'])) {
                //Export an set of hosts to speed up thins
                $hosts = $this->Host->find('all', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Host.disabled' => 0,
                    ],
                    'contain'    => [
                        'Hosttemplate'             => [
                            'fields'    => [
                                'Hosttemplate.id',
                                'Hosttemplate.uuid',
                                'Hosttemplate.check_interval',
                            ],
                            'Hostgroup' => [
                                'fields' => [
                                    'Hostgroup.uuid',
                                ],
                            ],
                        ],
                        'Hostcommandargumentvalue' => [
                            'Commandargument',
                        ],
                        'Customvariable',
                        'Contactgroup',
                        'Contact',
                        'Parenthost',
                        'Hostgroup',
                        'CheckPeriod',
                        'NotifyPeriod',
                        'CheckCommand',
                    ],
                    'limit'      => $options['limit'],
                    'offset'     => $options['offset'],
                ]);
            } else {
                //Multiple queries are faster than one big query
                $hostCount = $this->Host->find('count');
                $chunk = 200;
                $queryCount = ceil($hostCount / $chunk);

                //Export all hosts
                $hosts = [];
                for ($i = 0; $i < $queryCount; $i++) {
                    $_hosts = $this->Host->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Host.disabled' => 0,
                        ],
                        'contain'    => [
                            'Hosttemplate'             => [
                                'fields'    => [
                                    'Hosttemplate.id',
                                    'Hosttemplate.uuid',
                                    'Hosttemplate.check_interval',
                                ],
                                'Hostgroup' => [
                                    'fields' => [
                                        'Hostgroup.uuid',
                                    ],
                                ],
                            ],
                            'Hostcommandargumentvalue' => [
                                'Commandargument',
                            ],
                            'Customvariable',
                            'Contactgroup',
                            'Contact',
                            'Parenthost',
                            'Hostgroup',
                            'CheckPeriod',
                            'NotifyPeriod',
                            'CheckCommand',
                        ],
                        'limit'      => $chunk,
                        'offset'     => $chunk * $i,
                    ]);
                    foreach ($_hosts as $_host) {
                        $hosts[] = $_host;
                    }
                    unset($_hosts);
                }
            }
        }

        if (!is_dir($this->conf['path'] . $this->conf['hosts'])) {
            mkdir($this->conf['path'] . $this->conf['hosts']);
        }

        if ($this->conf['minified']) {
            $fileName = $this->conf['path'] . $this->conf['hosts'] . 'hosts_minified' . $this->conf['suffix'];
            if (isset($options['limit']) && isset($options['offset'])) {
                $fileName = $this->conf['path'] . $this->conf['hosts'] . 'hosts_minified_' . $options['limit'] . '_' . $options['offset'] . $this->conf['suffix'];
            }
            $file = new File($fileName);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }


        foreach ($hosts as $host) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hosts'] . $host['Host']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $commandarguments = [];
            if (!empty($host['Hostcommandargumentvalue'])) {
                //Select command arguments + command, because we have arguments!
                $commandarguments = Hash::sort($host['Hostcommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
            }

            $content .= $this->addContent('define host{', 0);
            $content .= $this->addContent('use', 1, $host['Hosttemplate']['uuid']);
            $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host['Host']['name']));
            $content .= $this->addContent('address', 1, $host['Host']['address']);


            if ($host['Host']['description'] !== null && $host['Host']['description'] !== '')
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host['Host']['description']));

            //Check if the host hase parent hosts and if they are active!
            if (!empty($host['Parenthost'])) {
                $parenthosts = [];
                foreach ($host['Parenthost'] as $parenthost) {
                    if ($parenthost['disabled'] == 0) {
                        //Only write enable/active hosts to nagios config
                        $parenthosts[] = $parenthost['uuid'];
                    }
                }
                if (!empty($parenthosts)) {
                    $content .= $this->addContent('parents', 1, implode(',', $parenthosts));
                }
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Check settings:', 1);
            if ($host['Host']['satellite_id'] == 0) {
                if (isset($commandarguments) && !empty($commandarguments)) {
                    if ($host['CheckCommand']['uuid'] !== null && $host['CheckCommand']['uuid'] !== '') {
                        //The host has its own check_command and own command args
                        $content .= $this->addContent('check_command', 1, $host['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                    } else {
                        //The host only has its own command args, but the same command as the hosttemplate
                        //This is not supported by nagios, so we need to select the command and create the
                        //config with the right comman
                        $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                        if (!empty($command_id)) {
                            $command_id = array_pop($command_id);
                            $command = $this->Command->find('first', [
                                'recurisve'  => -1,
                                'conditions' => [
                                    'Command.id' => $command_id,
                                ],
                                'fields'     => ['Command.uuid'],
                            ]);
                            $content .= $this->addContent('check_command', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                            unset($command);
                        }
                    }
                } else {
                    if ($host['CheckCommand']['uuid'] !== null && $host['CheckCommand']['uuid'] !== '') {
                        $content .= $this->addContent('check_command', 1, $host['CheckCommand']['uuid']);
                    }
                }
            } else {
                $content .= $this->addContent('check_command', 1, '2106cf0bf26a82af262c4078e6d9f94eded84d2a');
            }

            if ($host['CheckPeriod']['uuid'] !== null && $host['CheckPeriod']['uuid'] !== '')
                $content .= $this->addContent('check_period', 1, $host['CheckPeriod']['uuid']);

            if ($host['Host']['check_interval'] !== null && $host['Host']['check_interval'] !== '')
                $content .= $this->addContent('check_interval', 1, $host['Host']['check_interval']);

            if ($host['Host']['retry_interval'] !== null && $host['Host']['retry_interval'] !== '')
                $content .= $this->addContent('retry_interval', 1, $host['Host']['retry_interval']);

            if ($host['Host']['max_check_attempts'] !== null && $host['Host']['max_check_attempts'] !== '')
                $content .= $this->addContent('max_check_attempts', 1, $host['Host']['max_check_attempts']);

            if ($host['Host']['satellite_id'] > 0) {
                $content .= $this->addContent('active_checks_enabled', 1, 0);

            } else {
                if ($host['Host']['active_checks_enabled'] !== null && $host['Host']['active_checks_enabled'] !== '') {
                    $content .= $this->addContent('active_checks_enabled', 1, $host['Host']['active_checks_enabled']);
                }
            }


            $checkInterrval = null;
            if ($host['Host']['check_interval'] !== null && $host['Host']['check_interval'] !== '') {
                $checkInterrval = $host['Host']['check_interval'];
            } else {
                $checkInterrval = $host['Hosttemplate']['check_interval'];
            }

            if (isset($host['Host']['freshness_checks_enabled']) && isset($host['Host']['freshness_threshold'])) {
                if ((int)$host['Host']['freshness_checks_enabled'] > 0 || $host['Host']['satellite_id'] > 0) {

                    if ($host['Host']['satellite_id'] > 0) {
                        //Services gets checked by a SAT-System
                        $content .= $this->addContent('check_freshness', 1, 1);

                        $content .= $this->addContent('freshness_threshold', 1, (int)$host['Host']['freshness_threshold'] + $checkInterrval + $this->FRESHNESS_THRESHOLD_ADDITION);
                    } else {
                        //Passive service on the master system
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $content .= $this->addContent('freshness_threshold', 1, (int)$host['Host']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
                    }
                }
            } else {
                /*
                 * NOTICE:
                 * At the moment the host has no freshness_checks_enabled and freshness_threshold field.
                 * This will be available in one of the next versions...
                 *
                 * So this is a little workaround!!!
                 * We only add the freshness for hosts on SAT-Systems! Normal hosts cant have this option at the moment!
                 */
                if ($host['Host']['satellite_id'] > 0) {
                    $content .= $this->addContent('check_freshness', 1, 1);
                    if ($checkInterrval == 0) {
                        $checkInterrval = 300;
                    }
                    $content .= $this->addContent('freshness_threshold', 1, $checkInterrval + $this->FRESHNESS_THRESHOLD_ADDITION);
                }
            }


            if ($host['Host']['passive_checks_enabled'] !== null && $host['Host']['passive_checks_enabled'] !== '')
                $content .= $this->addContent('passive_checks_enabled', 1, $host['Host']['passive_checks_enabled']);


            $content .= $this->nl();
            $content .= $this->addContent(';Notification settings:', 1);

            if ($host['Host']['notifications_enabled'] !== null && $host['Host']['notifications_enabled'] !== '')
                $content .= $this->addContent('notifications_enabled', 1, $host['Host']['notifications_enabled']);

            if (!empty($host['Contact']))
                $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($host['Contact'], '{n}.uuid')));

            if (!empty($host['Contactgroup'])) {
                $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($host['Contactgroup'], '{n}.uuid')));
            }

            if ($host['Host']['notification_interval'] !== null && $host['Host']['notification_interval'] !== '')
                $content .= $this->addContent('notification_interval', 1, $host['Host']['notification_interval']);

            if ($host['NotifyPeriod']['uuid'] !== null && $host['NotifyPeriod']['uuid'] !== '')
                $content .= $this->addContent('notification_period', 1, $host['NotifyPeriod']['uuid']);

            if (
                ($host['Host']['notify_on_down'] === '1' || $host['Host']['notify_on_down'] === '0') ||
                ($host['Host']['notify_on_unreachable'] === '1' || $host['Host']['notify_on_unreachable'] === '0') ||
                ($host['Host']['notify_on_recovery'] === '1' || $host['Host']['notify_on_recovery'] === '0') ||
                ($host['Host']['notify_on_flapping'] === '1' || $host['Host']['notify_on_flapping'] === '0') ||
                ($host['Host']['notify_on_downtime'] === '1' || $host['Host']['notify_on_downtime'] === '0')
            ) {
                $content .= $this->addContent('notification_options', 1, $this->hostNotificationString($host['Host']));
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);

            if ($host['Host']['flap_detection_enabled'] === '1' || $host['Host']['flap_detection_enabled'] === '0')
                $content .= $this->addContent('flap_detection_enabled', 1, $host['Host']['flap_detection_enabled']);

            if (
                ($host['Host']['flap_detection_on_up'] === '1' || $host['Host']['flap_detection_on_up'] === '0') ||
                ($host['Host']['flap_detection_on_down'] === '1' || $host['Host']['flap_detection_on_down'] === '0') ||
                ($host['Host']['flap_detection_on_unreachable'] === '1' || $host['Host']['flap_detection_on_unreachable'] === '0')
            ) {
                if ($host['Host']['flap_detection_enabled'] === '1') {
                    $content .= $this->addContent('flap_detection_options', 1, $this->hostFlapdetectionString($host['Host']));
                }
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Everything else:', 1);

            if (isset($host['Host']['process_performance_data'])) {
                if ($host['Host']['process_performance_data'] == 1 || $host['Host']['process_performance_data'] == 0)
                    $content .= $this->addContent('process_perf_data', 1, $host['Host']['process_performance_data']);
            }
            if (!empty($host['Host']['notes'])) {
                if ($host['Host']['notes'] !== null && $host['Host']['notes'] !== '')
                    $content .= $this->addContent('notes', 1, $host['Host']['notes']);
            }


            if (!empty($host['Customvariable'])) {
                $content .= $this->nl();
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($host['Customvariable'] as $customvariable) {
                    $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                }
            }

            if (!empty($host['Hostgroup'])) {
                $content .= $this->addContent('hostgroups', 1, implode(',', Hash::extract($host['Hostgroup'], '{n}.uuid')));
            } else if (empty($host['Hostgroup']) && !empty($host['Hosttemplate']['Hostgroup'])) {
                $content .= $this->addContent('hostgroups', 1, implode(',', Hash::extract($host['Hosttemplate']['Hostgroup'], '{n}.uuid')));
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }


            if ($this->dm === true && $host['Host']['satellite_id'] > 0) {
                //Generate config file for sat nagios
                $this->exportSatHost($host, $host['Host']['satellite_id'], $commandarguments);

                /*
                 * May be not all hosts in hostgroup 'foo' are available on SAT system 'bar', so we create an array
                 * with all hosts from system 'bar' in hostgroup 'foo' for each SAT system
                 */
                if (!empty($host['Hostgroup'])) {
                    foreach ($host['Hostgroup'] as $hostgroup) {
                        $this->dmConfig[$host['Host']['satellite_id']]['Hostgroup'][$hostgroup['uuid']][] = $host['Host']['uuid'];
                    }
                }
                if (empty($host['Hostgroup']) && !empty($host['Hosttemplate']['Hostgroup'])) {
                    foreach ($host['Hosttemplate']['Hostgroup'] as $hostgroup) {
                        $this->dmConfig[$host['Host']['satellite_id']]['Hostgroup'][$hostgroup['uuid']][] = $host['Host']['uuid'];
                    }
                }
            }

            if (isset($commandarguments)) {
                unset($commandarguments);
            }

        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            $this->exportSatHostgroups();
        }

        $this->deleteHostPerfdata();
    }

    /**
     * @param $host
     * @param $satelliteId
     * @param $commandarguments
     */
    public function exportSatHost($host, $satelliteId, $commandarguments) {
        if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'])) {
            mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts']);
        }

        if (!$this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'] . $host['Host']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
        } else {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'] . 'hosts_minified' . $this->conf['suffix']);
            $content = '';
        }

        if (!$file->exists()) {
            $file->create();
        }

        $content .= $this->addContent('define host{', 0);
        $content .= $this->addContent('use', 1, $host['Hosttemplate']['uuid']);
        $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);
        $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host['Host']['name']));
        $content .= $this->addContent('address', 1, $host['Host']['address']);


        if ($host['Host']['description'] !== null && $host['Host']['description'] !== '')
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host['Host']['description']));

        if (!empty($host['Parenthost'])) {

            $parenthosts = [];
            //Only wirte the parent hosts to monitoring config, that exists on this satellite
            foreach ($host['Parenthost'] as $parenthost) {
                if ($parenthost['satellite_id'] == $host['Host']['satellite_id']) {
                    if ($parenthost['disabled'] == 0) {
                        //Only write active hosts to monitoring configuration
                        $parenthosts[] = $parenthost['uuid'];
                    }
                }
            }

            if (!empty($parenthosts)) {
                $content .= $this->addContent('parents', 1, implode(',', $parenthosts));
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Check settings:', 1);
        if (isset($commandarguments) && !empty($commandarguments)) {
            if ($host['CheckCommand']['uuid'] !== null && $host['CheckCommand']['uuid'] !== '') {
                //The host has its own check_command and own command args
                $content .= $this->addContent('check_command', 1, $host['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                //The host only has its own command args, but the same command as the hosttemplate
                //This is not supported by nagios, so we need to select the command and create the
                //config with the right comman
                $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                if (!empty($command_id)) {
                    $command_id = array_pop($command_id);
                    $command = $this->Command->find('first', [
                        'recurisve'  => -1,
                        'conditions' => [
                            'Command.id' => $command_id,
                        ],
                        'fields'     => ['Command.uuid'],
                    ]);
                    $content .= $this->addContent('check_command', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                    unset($command);
                }
            }
        } else {
            if ($host['CheckCommand']['uuid'] !== null && $host['CheckCommand']['uuid'] !== '')
                $content .= $this->addContent('check_command', 1, $host['CheckCommand']['uuid']);
        }

        if ($host['CheckPeriod']['uuid'] !== null && $host['CheckPeriod']['uuid'] !== '')
            $content .= $this->addContent('check_period', 1, $host['CheckPeriod']['uuid']);

        if ($host['Host']['check_interval'] !== null && $host['Host']['check_interval'] !== '')
            $content .= $this->addContent('check_interval', 1, $host['Host']['check_interval']);

        if ($host['Host']['retry_interval'] !== null && $host['Host']['retry_interval'] !== '')
            $content .= $this->addContent('retry_interval', 1, $host['Host']['retry_interval']);

        if ($host['Host']['max_check_attempts'] !== null && $host['Host']['max_check_attempts'] !== '')
            $content .= $this->addContent('max_check_attempts', 1, $host['Host']['max_check_attempts']);

        if ($host['Host']['active_checks_enabled'] !== null && $host['Host']['active_checks_enabled'] !== '')
            $content .= $this->addContent('active_checks_enabled', 1, $host['Host']['active_checks_enabled']);

        if ($host['Host']['passive_checks_enabled'] !== null && $host['Host']['passive_checks_enabled'] !== '')
            $content .= $this->addContent('passive_checks_enabled', 1, $host['Host']['passive_checks_enabled']);

        $content .= $this->nl();
        $content .= $this->addContent(';Notification settings:', 1);

        if ($host['Host']['notifications_enabled'] !== null && $host['Host']['notifications_enabled'] !== '')
            $content .= $this->addContent('notifications_enabled', 1, $host['Host']['notifications_enabled']);

        if (!empty($host['Contact']))
            $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($host['Contact'], '{n}.uuid')));

        if (!empty($host['Contactgroup']))
            $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($host['Contactgroup'], '{n}.uuid')));

        if ($host['Host']['notification_interval'] !== null && $host['Host']['notification_interval'] !== '')
            $content .= $this->addContent('notification_interval', 1, $host['Host']['notification_interval']);

        if ($host['NotifyPeriod']['uuid'] !== null && $host['NotifyPeriod']['uuid'] !== '')
            $content .= $this->addContent('notification_period', 1, $host['NotifyPeriod']['uuid']);

        $hostNotificationString = $this->hostNotificationString($host['Host']);
        if (!empty($hostNotificationString)) {
            $content .= $this->addContent('notification_options', 1, $hostNotificationString);
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Flap detection settings:', 1);

        if ($host['Host']['flap_detection_enabled'] === '1' || $host['Host']['flap_detection_enabled'] === '0')
            $content .= $this->addContent('flap_detection_enabled', 1, $host['Host']['flap_detection_enabled']);

        if (
            ($host['Host']['flap_detection_on_up'] === '1' || $host['Host']['flap_detection_on_up'] === '0') ||
            ($host['Host']['flap_detection_on_down'] === '1' || $host['Host']['flap_detection_on_down'] === '0') ||
            ($host['Host']['flap_detection_on_unreachable'] === '1' || $host['Host']['flap_detection_on_unreachable'] === '0')
        ) {
            if ($host['Host']['flap_detection_enabled'] === '1') {
                $content .= $this->addContent('flap_detection_options', 1, $this->hostFlapdetectionString($host['Host']));
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Everything else:', 1);

        if (isset($host['Host']['process_performance_data'])) {
            if ($host['Host']['process_performance_data'] == 1 || $host['Host']['process_performance_data'] == 0)
                $content .= $this->addContent('process_perf_data', 1, $host['Host']['process_performance_data']);
        }
        if (!empty($host['Host']['notes'])) {
            if ($host['Host']['notes'] !== null && $host['Host']['notes'] !== '')
                $content .= $this->addContent('notes', 1, $host['Host']['notes']);
        }


        if (!empty($host['Customvariable'])) {
            $content .= $this->nl();
            $content .= $this->addContent(';Custom  variables:', 1);
            foreach ($host['Customvariable'] as $customvariable) {
                $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
            }
        }
        $content .= $this->addContent('}', 0);

        if (!$this->conf['minified']) {
            $file->write($content);
        } else {
            $file->append($content);
        }
        $file->close();
    }

    /**
     * @param null|string $uuid
     */
    public function exportServicetemplates($uuid = null) {
        if ($uuid !== null) {
            $_servicetemplates = [];
            $_servicetemplates[] = $this->Servicetemplate->findByUuid($uuid);
        } else {
            $_servicetemplates = $this->Servicetemplate->find('all', [
                'recursive' => -1,
                'contain'   => [
                    'CheckPeriod',
                    'NotifyPeriod',
                    'CheckCommand',
                    'EventhandlerCommand',
                    'Customvariable',
                    'Servicetemplatecommandargumentvalue'      => [
                        'Commandargument',
                    ],
                    'Servicetemplateeventcommandargumentvalue' => [
                        'Commandargument',
                    ],
                    'Contact',
                    'Contactgroup',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['servicetemplates'])) {
            mkdir($this->conf['path'] . $this->conf['servicetemplates']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['servicetemplates'] . 'servicetemplates_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($_servicetemplates as $servicetemplates) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['servicetemplates'] . $servicetemplates['Servicetemplate']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $commandarguments = [];
            if (!empty($servicetemplates['Servicetemplatecommandargumentvalue'])) {
                //Select command arguments + command, because we have arguments!
                $commandarguments = Hash::sort($servicetemplates['Servicetemplatecommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
            }

            $content .= $this->addContent('define service{', 0);
            $content .= $this->addContent('register', 1, 0);
            $content .= $this->addContent('use', 1, '689bfdd01af8a21c4a4706c5117849c2fc2c3f38');
            $content .= $this->addContent('name', 1, $servicetemplates['Servicetemplate']['uuid']);
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $servicetemplates['Servicetemplate']['name']
            ));
            $content .= $this->addContent('service_description', 1, $servicetemplates['Servicetemplate']['uuid']);

            $content .= $this->nl();
            $content .= $this->addContent(';Check settings:', 1);
            if (isset($commandarguments) && !empty($commandarguments)) {
                $content .= $this->addContent('check_command', 1, $servicetemplates['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                $content .= $this->addContent('check_command', 1, $servicetemplates['CheckCommand']['uuid']);
            }

            if (isset($commandarguments)) {
                unset($commandarguments);
            }

            $content .= $this->addContent('initial_state', 1, $this->_systemsettings['MONITORING']['MONITORING.SERVICE.INITSTATE']);
            $content .= $this->addContent('check_period', 1, $servicetemplates['CheckPeriod']['uuid']);
            $content .= $this->addContent('check_interval', 1, $servicetemplates['Servicetemplate']['check_interval']);
            $content .= $this->addContent('retry_interval', 1, $servicetemplates['Servicetemplate']['retry_interval']);
            $content .= $this->addContent('max_check_attempts', 1, $servicetemplates['Servicetemplate']['max_check_attempts']);
            $content .= $this->addContent('active_checks_enabled', 1, $servicetemplates['Servicetemplate']['active_checks_enabled']);
            $content .= $this->addContent('passive_checks_enabled', 1, 1);

            if ($servicetemplates['Servicetemplate']['freshness_checks_enabled'] > 0) {
                $content .= $this->addContent('check_freshness', 1, 1);

                if ((int)$servicetemplates['Servicetemplate']['freshness_threshold'] > 0) {
                    $content .= $this->addContent('freshness_threshold', 1, (int)$servicetemplates['Servicetemplate']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
                }
            }


            $content .= $this->nl();
            $content .= $this->addContent(';Notification settings:', 1);
            $content .= $this->addContent('notifications_enabled', 1, 1);

            $contacts = Hash::extract($servicetemplates['Contact'], '{n}.uuid');
            if (!empty($contacts))
                $content .= $this->addContent('contacts', 1, implode(',', $contacts));

            $contactgroups = Hash::extract($servicetemplates['Contactgroup'], '{n}.uuid');
            if (!empty($contactgroups))
                $content .= $this->addContent('contact_groups', 1, implode(',', $contactgroups));
            $content .= $this->addContent('notification_interval', 1, $servicetemplates['Servicetemplate']['notification_interval']);
            if ($servicetemplates['NotifyPeriod']['uuid'] !== null && $servicetemplates['NotifyPeriod']['uuid'] !== '') {
                $content .= $this->addContent('notification_period', 1, $servicetemplates['NotifyPeriod']['uuid']);
            }

            $serviceNotificationString = $this->serviceNotificationString($servicetemplates['Servicetemplate']);
            if (!empty($serviceNotificationString)) {
                $content .= $this->addContent('notification_options', 1, $serviceNotificationString);
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $servicetemplates['Servicetemplate']['flap_detection_enabled']);
            if ($servicetemplates['Servicetemplate']['flap_detection_enabled'] == 1) {
                $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($servicetemplates['Servicetemplate']));
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Everything else:', 1);
            if (isset($servicetemplates['Servicetemplate']['process_performance_data'])) {
                $content .= $this->addContent('process_perf_data', 1, $servicetemplates['Servicetemplate']['process_performance_data']);
            }

            if (isset($servicetemplates['Servicetemplate']['is_volatile'])) {
                $content .= $this->addContent('is_volatile', 1, (int)$servicetemplates['Servicetemplate']['is_volatile']);
            }

            if (!empty($servicetemplates['Servicetemplate']['notes']))
                $content .= $this->addContent('notes', 1, $servicetemplates['Servicetemplate']['notes']);

            //Export event handlers to template
            $eventarguments = [];
            if (isset($servicetemplates['EventhandlerCommand']['id']) && $servicetemplates['EventhandlerCommand']['id'] !== null) {
                $content .= $this->addContent(';Event handler:', 1);
                if (!empty($servicetemplates['Servicetemplateeventcommandargumentvalue'])) {
                    //Select command arguments + command, because we have arguments!
                    $eventarguments = Hash::sort($servicetemplates['Servicetemplateeventcommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                if (isset($eventarguments) && !empty($eventarguments)) {
                    $content .= $this->addContent('event_handler', 1, $servicetemplates['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventarguments, '{n}.Commandargument.human_name')));
                } else {
                    $content .= $this->addContent('event_handler', 1, $servicetemplates['EventhandlerCommand']['uuid']);
                }

                if (isset($eventarguments)) {
                    unset($eventarguments);
                }
            }

            if (!empty($servicetemplates['Customvariable'])) {
                $content .= $this->nl();
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($servicetemplates['Customvariable'] as $customvariable) {
                    $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                }
            }
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

    }

    /**
     * @param null|string $uuid
     * @param array $options with keys limit and offset
     */
    public function exportServices($uuid = null, $options = []) {
        if (!is_dir($this->conf['path'] . $this->conf['services'])) {
            mkdir($this->conf['path'] . $this->conf['services']);
        }

        if ($this->conf['minified']) {
            $fileName = $this->conf['path'] . $this->conf['services'] . 'services_minified' . $this->conf['suffix'];
            if (isset($options['limit']) && isset($options['offset'])) {
                $fileName = $this->conf['path'] . $this->conf['services'] . 'services_minified_' . $options['limit'] . '_' . $options['offset'] . $this->conf['suffix'];
            }
            $file = new File($fileName);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $hosts = $this->Host->find('all', [
            'contain'    => [],
            'recursive'  => -1,
            'conditions' => [
                'Host.disabled' => 0,
            ],
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.satellite_id',
            ],
        ]);

        foreach ($hosts as $host) {
            $services = $this->Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Service.disabled' => 0,
                    'Service.host_id'  => $host['Host']['id'],
                ],
                'contain'    => [
                    'Servicetemplate'                  => [
                        'fields'       => [
                            'Servicetemplate.id',
                            'Servicetemplate.uuid',
                            'Servicetemplate.name',
                            'Servicetemplate.check_interval',
                            'Servicetemplate.eventhandler_command_id',
                        ],
                        'Servicegroup' => [
                            'fields' => [
                                'Servicegroup.uuid',
                            ],
                        ],
                    ],
                    'CheckPeriod',
                    'CheckCommand',
                    'NotifyPeriod',
                    'Servicecommandargumentvalue'      => [
                        'Commandargument',
                    ],
                    'Serviceeventcommandargumentvalue' => [
                        'Commandargument',
                    ],
                    'EventhandlerCommand',
                    'Customvariable',
                    'Servicegroup',
                    'Contactgroup',
                    'Contact',
                ],
            ]);
            foreach ($services as $service) {
                if (!$this->conf['minified']) {
                    $file = new File($this->conf['path'] . $this->conf['services'] . $service['Service']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }


                $commandarguments = [];
                if (!empty($service['Servicecommandargumentvalue'])) {
                    $commandarguments = Hash::sort($service['Servicecommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                $content .= $this->addContent('define service{', 0);
                $content .= $this->addContent('use', 1, $service['Servicetemplate']['uuid']);
                $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);

                $content .= $this->addContent('name', 1, $service['Service']['uuid']);
                if ($service['Service']['name'] !== null && $service['Service']['name'] !== '') {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $service['Service']['name'])
                    );
                } else {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $service['Servicetemplate']['name'])
                    );
                }

                $content .= $this->addContent('service_description', 1, $service['Service']['uuid']);

                $content .= $this->nl();
                $content .= $this->addContent(';Check settings:', 1);

                $eventcommandarguments = [];
                if (!empty($service['Serviceeventcommandargumentvalue'])) {
                    $eventcommandarguments = Hash::sort($service['Serviceeventcommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                if ($host['Host']['satellite_id'] == 0) {
                    if (isset($commandarguments) && !empty($commandarguments)) {
                        if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '') {
                            //The host has its own check_command and own command args
                            $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                        } else {
                            //The services only has its own command args, but the same command as the servicetemplate
                            //This is not supported by nagios, so we need to select the command and create the
                            //config with the right command uuid
                            $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                            if (!empty($command_id)) {
                                $command_id = array_pop($command_id);
                                $command = $this->Command->find('first', [
                                    'recurisve'  => -1,
                                    'conditions' => [
                                        'Command.id' => $command_id,
                                    ],
                                    'fields'     => ['Command.uuid'],
                                ]);
                                $content .= $this->addContent('check_command', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                                unset($command);
                            }
                        }
                    } else {
                        if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '')
                            $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid']);
                    }


                    // Only export event handlers if the services is on the master system. for SAT event handlers see $this->exportSatService()
                    if (isset($eventcommandarguments) && !empty($eventcommandarguments)) {
                        if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '') {
                            //The service has its own event_handler and own event handler args
                            $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                        } else {
                            //The services only has its own event handler args, but the same event handler command as the servicetemplate
                            //This is not supported by nagios, so we need to select the event handler command and create the
                            //config with the right command uuid and pass the arguments of the service
                            $command_id = Hash::extract($eventcommandarguments, '{n}.Commandargument.command_id');
                            if (!empty($command_id)) {
                                $command_id = array_pop($command_id);
                                $command = $this->Command->find('first', [
                                    'recurisve'  => -1,
                                    'conditions' => [
                                        'Command.id' => $command_id,
                                    ],
                                    'fields'     => ['Command.uuid'],
                                ]);
                                $content .= $this->addContent('event_handler', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                                unset($command);
                            }
                        }
                    } else {
                        //Own event_handler without any handler args
                        if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '')
                            $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid']);
                    }


                } else {
                    $content .= $this->addContent('check_command', 1, '2106cf0bf26a82af262c4078e6d9f94eded84d2a');
                }


                if ($service['Service']['check_period_id'] !== null && $service['Service']['check_period_id'] !== '')
                    $content .= $this->addContent('check_period', 1, $service['CheckPeriod']['uuid']);

                if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '')
                    $content .= $this->addContent('check_interval', 1, $service['Service']['check_interval']);

                if ($service['Service']['retry_interval'] !== null && $service['Service']['retry_interval'] !== '')
                    $content .= $this->addContent('retry_interval', 1, $service['Service']['retry_interval']);

                if ($service['Service']['max_check_attempts'] !== null && $service['Service']['max_check_attempts'] !== '')
                    $content .= $this->addContent('max_check_attempts', 1, $service['Service']['max_check_attempts']);


                if ($host['Host']['satellite_id'] > 0) {
                    $content .= $this->addContent('active_checks_enabled', 1, 0);
                    $content .= $this->addContent('passive_checks_enabled', 1, 1);
                } else {
                    if ($service['Service']['active_checks_enabled'] !== null && $service['Service']['active_checks_enabled'] !== '') {
                        $content .= $this->addContent('active_checks_enabled', 1, (int)$service['Service']['active_checks_enabled']);
                    }

                    if ($service['Service']['passive_checks_enabled'] !== null && $service['Service']['passive_checks_enabled'] !== '') {
                        $content .= $this->addContent('passive_checks_enabled', 1, (int)$service['Service']['passive_checks_enabled']);
                    }
                }

                if ((int)$service['Service']['freshness_checks_enabled'] > 0 || $host['Host']['satellite_id'] > 0) {

                    if ($host['Host']['satellite_id'] > 0) {
                        //Services gets checked by a SAT-System
                        $content .= $this->addContent('check_freshness', 1, 1);

                        $checkInterrval = null;
                        if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '') {
                            $checkInterrval = $service['Service']['check_interval'];
                        } else {
                            $checkInterrval = $service['Servicetemplate']['check_interval'];
                        }

                        $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $checkInterrval + $this->FRESHNESS_THRESHOLD_ADDITION);
                    } else {
                        //Passive service on the master system
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
                    }
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Notification settings:', 1);
                if ($service['Service']['notifications_enabled'] !== null && $service['Service']['notifications_enabled'] !== '')
                    $content .= $this->addContent('notifications_enabled', 1, $service['Service']['notifications_enabled']);

                if (!empty($service['Contact']))
                    $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($service['Contact'], '{n}.uuid')));

                if (!empty($service['Contactgroup']))
                    $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($service['Contactgroup'], '{n}.uuid')));

                if ($service['Service']['notification_interval'] !== null && $service['Service']['notification_interval'] !== '')
                    $content .= $this->addContent('notification_interval', 1, $service['Service']['notification_interval']);

                if ($service['NotifyPeriod']['uuid'] !== null && $service['NotifyPeriod']['uuid'] !== '')
                    $content .= $this->addContent('notification_period', 1, $service['NotifyPeriod']['uuid']);

                if (
                    ($service['Service']['notify_on_warning'] === '1' || $service['Service']['notify_on_warning'] === '0') ||
                    ($service['Service']['notify_on_unknown'] === '1' || $service['Service']['notify_on_unknown'] === '0') ||
                    ($service['Service']['notify_on_critical'] === '1' || $service['Service']['notify_on_critical'] === '0') ||
                    ($service['Service']['notify_on_recovery'] === '1' || $service['Service']['notify_on_recovery'] === '0') ||
                    ($service['Service']['notify_on_flapping'] === '1' || $service['Service']['notify_on_flapping'] === '0') ||
                    ($service['Service']['notify_on_downtime'] === '1' || $service['Service']['notify_on_downtime'] === '0')
                ) {
                    $content .= $this->addContent('notification_options', 1, $this->serviceNotificationString($service['Service']));
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Flap detection settings:', 1);
                if ($service['Service']['flap_detection_enabled'] === '0' || $service['Service']['flap_detection_enabled'] === '1')
                    $content .= $this->addContent('flap_detection_enabled', 1, $service['Service']['flap_detection_enabled']);

                if (
                    ($service['Service']['flap_detection_on_ok'] === '1' || $service['Service']['flap_detection_on_ok'] === '0') ||
                    ($service['Service']['flap_detection_on_warning'] === '1' || $service['Service']['flap_detection_on_warning'] === '0') ||
                    ($service['Service']['flap_detection_on_unknown'] === '1' || $service['Service']['flap_detection_on_unknown'] === '0') ||
                    ($service['Service']['flap_detection_on_critical'] === '1' || $service['Service']['flap_detection_on_critical'] === '0')
                ) {
                    if ($service['Service']['flap_detection_enabled'] === '1') {
                        $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($service['Service']));
                    }
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Everything else:', 1);
                if (isset($service['Service']['process_performance_data'])) {
                    if ($service['Service']['process_performance_data'] == 1 || $service['Service']['process_performance_data'] == 0)
                        $content .= $this->addContent('process_perf_data', 1, $service['Service']['process_performance_data']);
                }
                if (isset($service['Service']['is_volatile'])) {
                    if ($service['Service']['is_volatile'] !== null && $service['Service']['is_volatile'] !== '')
                        $content .= $this->addContent('is_volatile', 1, (int)$service['Service']['is_volatile']);
                }
                if (!empty($service['Service']['notes']))
                    $content .= $this->addContent('notes', 1, $service['Service']['notes']);


                if (!empty($service['Customvariable'])) {
                    $content .= $this->nl();
                    $content .= $this->addContent(';Custom  variables:', 1);
                    foreach ($service['Customvariable'] as $customvariable) {
                        $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                    }
                }

                if (!empty($service['Servicegroup'])) {
                    $content .= $this->addContent('servicegroups', 1, implode(',', Hash::extract($service['Servicegroup'], '{n}.uuid')));
                } else if (empty($service['Servicegroup']) && !empty($service['Servicetemplate']['Servicegroup'])) {
                    $content .= $this->addContent('servicegroups', 1, implode(',', Hash::extract($service['Servicetemplate']['Servicegroup'], '{n}.uuid')));
                }

                $content .= $this->addContent('}', 0);

                if (!$this->conf['minified']) {
                    $file->write($content);
                    $file->close();
                }


                if ($this->dm === true && $host['Host']['satellite_id'] > 0) {

                    $this->exportSatService($service, $host, $commandarguments, $eventcommandarguments);

                    /*
                     * May be not all services in servicegroup 'foo' are available on SAT system 'bar', so we create an array
                     * with all services from system 'bar' in servicegroup 'foo' for each SAT system
                     */
                    if (!empty($service['Servicegroup'])) {
                        foreach ($service['Servicegroup'] as $servicegroup) {
                            $this->dmConfig[$host['Host']['satellite_id']]['Servicegroup'][$servicegroup['uuid']][] = $host['Host']['uuid'] . ',' . $service['Service']['uuid'];
                        }
                    }
                    if (empty($service['Servicegroup']) && !empty($service['Servicetemplate']['Servicegroup'])) {
                        foreach ($service['Servicetemplate']['Servicegroup'] as $servicegroup) {
                            $this->dmConfig[$host['Host']['satellite_id']]['Servicegroup'][$servicegroup['uuid']][] = $host['Host']['uuid'] . ',' . $service['Service']['uuid'];
                        }
                    }
                }

                if (isset($commandarguments)) {
                    unset($commandarguments);
                }

                if (isset($eventcommandarguments)) {
                    unset($eventcommandarguments);
                }
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            $this->exportSatServicegroups();
        }

        $this->deleteServicePerfdata();
    }

    /**
     * @param $service
     * @param $host
     * @param $commandarguments
     * @param $eventcommandarguments
     */
    public function exportSatService($service, $host, $commandarguments, $eventcommandarguments) {
        $satelliteId = $host['Host']['satellite_id'];
        if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'])) {
            mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services']);
        }

        if (!$this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . $service['Service']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();

        } else {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . 'services_minified' . $this->conf['suffix']);
            $content = '';
        }

        if (!$file->exists()) {
            $file->create();
        }

        $content .= $this->addContent('define service{', 0);
        $content .= $this->addContent('use', 1, $service['Servicetemplate']['uuid']);
        $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);

        $content .= $this->addContent('name', 1, $service['Service']['uuid']);
        if ($service['Service']['name'] !== null && $service['Service']['name'] !== '') {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $service['Service']['name']
            ));
        } else {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $service['Servicetemplate']['name']
            ));
        }

        $content .= $this->addContent('service_description', 1, $service['Service']['uuid']);

        $content .= $this->nl();
        $content .= $this->addContent(';Check settings:', 1);

        if (isset($commandarguments) && !empty($commandarguments)) {
            if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '') {
                //The host has its own check_command and own command args
                $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                //The services only has its own command args, but the same command as the servicetemplate
                //This is not supported by nagios, so we need to select the command and create the
                //config with the right comman
                $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                if (!empty($command_id)) {
                    $command_id = array_pop($command_id);
                    $command = $this->Command->find('first', [
                        'recurisve'  => -1,
                        'conditions' => [
                            'Command.id' => $command_id,
                        ],
                        'fields'     => ['Command.uuid'],
                    ]);
                    $content .= $this->addContent('check_command', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                    unset($command);
                }
            }
        } else {
            if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '')
                $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid']);
        }

        // Export event handler to SAT-System
        if (isset($eventcommandarguments) && !empty($eventcommandarguments)) {
            if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '') {
                //The service has its own event_handler and own event handler args
                $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
            } else {
                //The services only has its own event handler args, but the same event handler command as the servicetemplate
                //This is not supported by nagios, so we need to select the event handler command and create the
                //config with the right command uuid and pass the arguments of the service
                $command_id = Hash::extract($eventcommandarguments, '{n}.Commandargument.command_id');
                if (!empty($command_id)) {
                    $command_id = array_pop($command_id);
                    $command = $this->Command->find('first', [
                        'recurisve'  => -1,
                        'conditions' => [
                            'Command.id' => $command_id,
                        ],
                        'fields'     => ['Command.uuid'],
                    ]);
                    $content .= $this->addContent('event_handler', 1, $command['Command']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                    unset($command);
                }
            }
        } else {
            //Own event_handler without any handler args
            if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '')
                $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid']);
        }


        if ($service['Service']['check_period_id'] !== null && $service['Service']['check_period_id'] !== '')
            $content .= $this->addContent('check_period', 1, $service['CheckPeriod']['uuid']);

        if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '')
            $content .= $this->addContent('check_interval', 1, $service['Service']['check_interval']);

        if ($service['Service']['retry_interval'] !== null && $service['Service']['retry_interval'] !== '')
            $content .= $this->addContent('retry_interval', 1, $service['Service']['retry_interval']);

        if ($service['Service']['max_check_attempts'] !== null && $service['Service']['max_check_attempts'] !== '')
            $content .= $this->addContent('max_check_attempts', 1, $service['Service']['max_check_attempts']);

        if ($service['Service']['active_checks_enabled'] !== null && $service['Service']['active_checks_enabled'] !== '')
            $content .= $this->addContent('active_checks_enabled', 1, $service['Service']['active_checks_enabled']);

        if ($service['Service']['passive_checks_enabled'] !== null && $service['Service']['passive_checks_enabled'] !== '')
            $content .= $this->addContent('passive_checks_enabled', 1, 1);


        if ($service['Service']['freshness_checks_enabled'] > 0) {
            $content .= $this->addContent('check_freshness', 1, 1);

            if ($service['Service']['freshness_threshold'] !== null && $service['Service']['freshness_threshold'] !== '') {
                $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Notification settings:', 1);
        if ($service['Service']['notifications_enabled'] !== null && $service['Service']['notifications_enabled'] !== '')
            $content .= $this->addContent('notifications_enabled', 1, $service['Service']['notifications_enabled']);

        if (!empty($service['Contact']))
            $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($service['Contact'], '{n}.uuid')));

        if (!empty($service['Contactgroup']))
            $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($service['Contactgroup'], '{n}.uuid')));

        if ($service['Service']['notification_interval'] !== null && $service['Service']['notification_interval'] !== '')
            $content .= $this->addContent('notification_interval', 1, $service['Service']['notification_interval']);

        if ($service['NotifyPeriod']['uuid'] !== null && $service['NotifyPeriod']['uuid'] !== '')
            $content .= $this->addContent('notification_period', 1, $service['NotifyPeriod']['uuid']);

        if (
            ($service['Service']['notify_on_warning'] === '1' || $service['Service']['notify_on_warning'] === '0') ||
            ($service['Service']['notify_on_unknown'] === '1' || $service['Service']['notify_on_unknown'] === '0') ||
            ($service['Service']['notify_on_critical'] === '1' || $service['Service']['notify_on_critical'] === '0') ||
            ($service['Service']['notify_on_recovery'] === '1' || $service['Service']['notify_on_recovery'] === '0') ||
            ($service['Service']['notify_on_flapping'] === '1' || $service['Service']['notify_on_flapping'] === '0') ||
            ($service['Service']['notify_on_downtime'] === '1' || $service['Service']['notify_on_downtime'] === '0')
        ) {
            $content .= $this->addContent('notification_options', 1, $this->serviceNotificationString($service['Service']));
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Flap detection settings:', 1);
        if ($service['Service']['flap_detection_enabled'] === '0' || $service['Service']['flap_detection_enabled'] === '1')
            $content .= $this->addContent('flap_detection_enabled', 1, $service['Service']['flap_detection_enabled']);

        if (
            ($service['Service']['flap_detection_on_ok'] === '1' || $service['Service']['flap_detection_on_ok'] === '0') ||
            ($service['Service']['flap_detection_on_warning'] === '1' || $service['Service']['flap_detection_on_warning'] === '0') ||
            ($service['Service']['flap_detection_on_unknown'] === '1' || $service['Service']['flap_detection_on_unknown'] === '0') ||
            ($service['Service']['flap_detection_on_critical'] === '1' || $service['Service']['flap_detection_on_critical'] === '0')
        ) {
            if ($service['Service']['flap_detection_enabled'] === '1') {
                $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($service['Service']));
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Everything else:', 1);
        if (isset($service['Service']['process_performance_data'])) {
            if ($service['Service']['process_performance_data'] == 1 || $service['Service']['process_performance_data'] == 0)
                $content .= $this->addContent('process_perf_data', 1, $service['Service']['process_performance_data']);
        }
        if (isset($service['Service']['is_volatile'])) {
            if ($service['Service']['is_volatile'] !== null && $service['Service']['is_volatile'] !== '')
                $content .= $this->addContent('is_volatile', 1, (int)$service['Service']['is_volatile']);
        }
        if (!empty($service['Service']['notes']))
            $content .= $this->addContent('notes', 1, $service['Service']['notes']);


        if (!empty($service['Customvariable'])) {
            $content .= $this->nl();
            $content .= $this->addContent(';Custom  variables:', 1);
            foreach ($service['Customvariable'] as $customvariable) {
                $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
            }
        }
        $content .= $this->addContent('}', 0);


        if (!$this->conf['minified']) {
            $file->write($content);
        } else {
            $file->append($content);
        }
        $file->close();

    }


    /**
     * @param null|string $uid
     */
    public function exportHostgroups($uuid = null) {
        if ($uuid !== null) {
            $hostgroups = [];
            $hostgroups[] = $this->Hostgroup->findByUuid($uuid);
        } else {
            $hostgroups = $this->Hostgroup->find('all', [
                'recursive' => -1,
                'fields'    => [
                    'Hostgroup.id',
                    'Hostgroup.uuid',
                    'Hostgroup.description',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['hostgroups'])) {
            mkdir($this->conf['path'] . $this->conf['hostgroups']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['hostgroups'] . 'hostgroups_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($hostgroups as $hostgroup) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hostgroups'] . $hostgroup['Hostgroup']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define hostgroup{', 0);
            $content .= $this->addContent('hostgroup_name', 1, $hostgroup['Hostgroup']['uuid']);
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($hostgroup['Hostgroup']['description']));
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServicegroups($uuid = null) {
        if ($uuid !== null) {
            $servicegroups = [];
            $servicegroups[] = $this->Servicegroup->findByUuid($uuid);
        } else {
            $servicegroups = $this->Servicegroup->find('all', [
                'recursive' => -1,
                'fields'    => [
                    'Servicegroup.id',
                    'Servicegroup.uuid',
                    'Servicegroup.description',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['servicegroups'])) {
            mkdir($this->conf['path'] . $this->conf['servicegroups']);
        }

        foreach ($servicegroups as $servicegroup) {
            $file = new File($this->conf['path'] . $this->conf['servicegroups'] . $servicegroup['Servicegroup']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $content .= $this->addContent('define servicegroup{', 0);
            $content .= $this->addContent('servicegroup_name', 1, $servicegroup['Servicegroup']['uuid']);
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                $servicegroup['Servicegroup']['description']
            ));

            $content .= $this->addContent('}', 0);
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHostescalations($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'HostescalationHostMembership'      => [
                    'Host' => [
                        'conditions' => [
                            'Host.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid'
                        ],
                    ],
                ],
                'HostescalationHostgroupMembership' => [
                    'Hostgroup' => [
                        'fields' => [
                            'Hostgroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                        => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $hostescalations = [];
            $query['conditions'] = [
                'Hostescalation.uuid' => $uuid
            ];
            $hostescalations[] = $this->Hostescalation->find('first', $query);
        } else {
            $hostescalations = $this->Hostescalation->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['hostescalations'])) {
            mkdir($this->conf['path'] . $this->conf['hostescalations']);
        }

        foreach ($hostescalations as $hostescalation) {
            if (!empty($hostescalation['HostescalationHostMembership']) || !empty($hostescalation['HostescalationHostgroupMembership'])) {
                $includedHosts = Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=0].Host.uuid');
                $exludedHosts = Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=1].Host.uuid');

                // Prefix the hosts with an !
                $_exludedHosts = [];
                foreach ($exludedHosts as $extHost) {
                    $_exludedHosts[] = '!' . $extHost;
                }

                $hosts = Hash::merge($includedHosts, $_exludedHosts);

                $includedHostgroups = Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=0].Hostgroup.uuid');
                $exludedHostgroups = Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=1].Hostgroup.uuid');

                // Prefix the hosts with an !
                $_exludedHostgroups = [];
                foreach ($exludedHostgroups as $extHostgroup) {
                    $_exludedHostgroups[] = '!' . $extHostgroup;
                }
                $hostgroups = Hash::merge($includedHostgroups, $_exludedHostgroups);

                if (!empty($includedHosts) || !empty($includedHostgroups)) {
                    $file = new File($this->conf['path'] . $this->conf['hostescalations'] . $hostescalation['Hostescalation']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }

                    $content .= $this->addContent('define hostescalation{', 0);
                    if (!empty($hosts)) {
                        $content .= $this->addContent('host_name', 1, implode(',', $hosts));
                    }

                    if (!empty($hostgroups)) {
                        $content .= $this->addContent('hostgroup_name', 1, implode(',', $hostgroups));
                    }
                    if (!empty($hostescalation['Contact'])) {
                        $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($hostescalation['Contact'], '{n}.uuid')));
                    }
                    if (!empty($hostescalation['Contactgroup'])) {
                        $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($hostescalation['Contactgroup'], '{n}.uuid')));
                    }
                    $content .= $this->addContent('first_notification', 1, $hostescalation['Hostescalation']['first_notification']);
                    $content .= $this->addContent('last_notification', 1, $hostescalation['Hostescalation']['last_notification']);
                    $content .= $this->addContent('notification_interval', 1, (int)$hostescalation['Hostescalation']['notification_interval'] * 60);
                    $content .= $this->addContent('escalation_period', 1, $hostescalation['Timeperiod']['uuid']);
                    $hostEscalationString = $this->hostEscalationString($hostescalation['Hostescalation']);
                    if (!empty($hostEscalationString)) {
                        $content .= $this->addContent('escalation_options', 1, $hostEscalationString);
                    }

                    $content .= $this->addContent('}', 0);
                    $file->write($content);
                    $file->close();
                } else {
                    //This hostescalation is broken!
                    $this->Hostescalation->delete($hostescalation['Hostescalation']['id']);
                }
            } else {
                //This hostescalation is broken!
                $this->Hostescalation->delete($hostescalation['Hostescalation']['id']);
            }
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServiceescalations($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'ServiceescalationServiceMembership'      => [
                    'Service' => [
                        'conditions' => [
                            'Service.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid',
                        ],
                        'Host'       => [
                            'fields' => [
                                'uuid',
                            ],
                        ],
                    ],
                ],
                'ServiceescalationServicegroupMembership' => [
                    'Servicegroup' => [
                        'fields' => [
                            'Servicegroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                              => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $serviceescalations = [];
            $query['conditions'] = [
                'Serviceescalation.uuid' => $uuid
            ];
            $serviceescalations[] = $this->Serviceescalation->find('first', $query);
        } else {
            $serviceescalations = $this->Serviceescalation->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['serviceescalations'])) {
            mkdir($this->conf['path'] . $this->conf['serviceescalations']);
        }

        foreach ($serviceescalations as $serviceescalation) {
            $servicesForCfg = [];
            if (empty($serviceescalation['ServiceescalationServiceMembership'])) {
                $this->Serviceescalation->delete($serviceescalation['Serviceescalation']['id']);
                continue;
            }
            foreach ($serviceescalation['ServiceescalationServiceMembership'] as $serviceEscalationService) {
                if ($serviceEscalationService['excluded'] == 0) {
                    $servicesForCfg[$serviceEscalationService['Service']['Host']['uuid']][] = $serviceEscalationService['Service']['uuid'];
                } else {
                    $servicesForCfg[$serviceEscalationService['Service']['Host']['uuid']][] = '!' . $serviceEscalationService['Service']['uuid'];
                }
            }
            if (empty($servicesForCfg)) {
                //This service escalation is broken!
                $this->Serviceescalation->delete($serviceescalation['Serviceescalation']['id']);
                continue;
            }

            $servicegroupsForCfg = [];
            foreach ($serviceescalation['ServiceescalationServicegroupMembership'] as $serviceEscalationServicegroup) {
                if ($serviceEscalationServicegroup['excluded'] == 0) {
                    $servicegroupsForCfg[] = $serviceEscalationServicegroup['Servicegroup']['uuid'];
                } else {
                    $servicegroupsForCfg[] = '!' . $serviceEscalationServicegroup['Servicegroup']['uuid'];
                }
            }

            if (!empty($servicesForCfg)) {
                $file = new File($this->conf['path'] . $this->conf['serviceescalations'] . $serviceescalation['Serviceescalation']['uuid'] . $this->conf['suffix']);
                if (!$file->exists()) {
                    $file->create();
                }
                $content = $this->fileHeader();
                foreach ($servicesForCfg as $hostUuid => $serviceUuids) {
                    $content .= $this->addContent('define serviceescalation{', 0);
                    $content .= $this->addContent('host_name', 1, $hostUuid);
                    $content .= $this->addContent('service_description', 1, implode(',', $serviceUuids));
                    if (!empty($servicegroupsForCfg)) {
                        $content .= $this->addContent('servicegroup_name', 1, implode(',', $servicegroupsForCfg));
                    }
                    if (!empty($serviceescalation['Contact'])) {
                        $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($serviceescalation['Contact'], '{n}.uuid')));
                    }
                    if (!empty($serviceescalation['Contactgroup'])) {
                        $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($serviceescalation['Contactgroup'], '{n}.uuid')));
                    }
                    $content .= $this->addContent('first_notification', 1, $serviceescalation['Serviceescalation']['first_notification']);
                    $content .= $this->addContent('last_notification', 1, $serviceescalation['Serviceescalation']['last_notification']);
                    $content .= $this->addContent('notification_interval', 1, (int)$serviceescalation['Serviceescalation']['notification_interval'] * 60);
                    $content .= $this->addContent('escalation_period', 1, $serviceescalation['Timeperiod']['uuid']);
                    $serviceEscalationString = $this->serviceEscalationString($serviceescalation['Serviceescalation']);
                    if (!empty($serviceEscalationString)) {
                        $content .= $this->addContent('escalation_options', 1, $serviceEscalationString);
                    }
                    $content .= $this->addContent('}', 0);
                }
                $file->write($content);
                $file->close();
            }
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportTimeperiods($uuid = null) {
        if ($uuid !== null) {
            $timeperiods = [];
            $timeperiods[] = $this->Timeperiod->findByUuid($uuid);
        } else {
            $timeperiods = $this->Timeperiod->find('all');
        }

        if (!is_dir($this->conf['path'] . $this->conf['timeperiods'])) {
            mkdir($this->conf['path'] . $this->conf['timeperiods']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['timeperiods'] . 'timeperiods_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $date = new DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = strtolower($date->format('l'));
            $date->modify('+1 day');
        }

        foreach ($timeperiods as $timeperiod) {
            $timeranges = [];
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['timeperiods'] . $timeperiod['Timeperiod']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define timeperiod{', 0);
            $content .= $this->addContent('timeperiod_name', 1, $timeperiod['Timeperiod']['uuid']);
            if (strlen($timeperiod['Timeperiod']['description']) > 0) {
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($timeperiod['Timeperiod']['description']));
            } else {
                //Naemon 1.0.0 fix
                $content .= $this->addContent('alias', 1, $timeperiod['Timeperiod']['uuid']);
            }

            foreach ($timeperiod['Timerange'] as $timerange) {
                $timeranges[$timerange['day']][] = $timerange['start'] . '-' . $timerange['end'];
            }

            foreach ($timeranges as $weekday => $timesArray) {
                asort($timesArray);
                $content .= $this->addContent($weekdays[$weekday], 1, implode(',', $timesArray));
            }

            //Merge Calendar records to timeperiod
            if ($timeperiod['Timeperiod']['calendar_id'] > 0) {
                $calendar = $this->Calendar->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Calendar.id' => $timeperiod['Timeperiod']['calendar_id']
                    ],
                    'contain'    => ['CalendarHoliday']
                ]);
                foreach ($calendar['CalendarHoliday'] as $holiday) {
                    $timestamp = strtotime(sprintf('%s 00:00', $holiday['date']));

                    $calendarDay = sprintf('%s 00:00-24:00; %s',
                        strtolower(date('F j', $timestamp)),
                        $holiday['name']
                    );
                    $content .= $this->addContent($calendarDay, 1);
                }
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            foreach ($this->Satellites as $satelite) {
                $this->exportSatTimeperiods($timeperiods, $satelite);
            }
        }
    }

    /**
     * @param $timeperiods
     * @param $satelite
     */
    public function exportSatTimeperiods($timeperiods, $satelite) {
        if (!is_dir($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'])) {
            mkdir($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'] . 'timeperiods_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $date = new DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = strtolower($date->format('l'));
            $date->modify('+1 day');
        }

        foreach ($timeperiods as $timeperiod) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'] . $timeperiod['Timeperiod']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define timeperiod{', 0);
            $content .= $this->addContent('timeperiod_name', 1, $timeperiod['Timeperiod']['uuid']);
            if (strlen($timeperiod['Timeperiod']['description']) > 0) {
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($timeperiod['Timeperiod']['description']));
            } else {
                //Naemon 1.0.0 fix
                $content .= $this->addContent('alias', 1, $timeperiod['Timeperiod']['uuid']);
            }
            $timeRanges = [];
            foreach ($timeperiod['Timerange'] as $timeRange) {
                if (empty($satelite['Satellite']['timezone']) || ($timeRange['start'] == '00:00' && $timeRange['end'] == '24:00')) {
                    $timeRanges[$weekdays[$timeRange['day']]][] = $timeRange['start'] . '-' . $timeRange['end'];
                } else {
                    $remoteTimeZone = new DateTimeZone($satelite['Satellite']['timezone']);
                    $start = new DateTime($weekdays[$timeRange['day']] . ' ' . $timeRange['start']);
                    $start = $start->setTimezone($remoteTimeZone);
                    $end = new DateTime($weekdays[$timeRange['day']] . ' ' . (($timeRange['end'] == '24:00') ? '23:59' : $timeRange['end']));
                    $end = $end->setTimezone($remoteTimeZone);
                    if ($timeRange['end'] == '24:00') {
                        $end = $end->add(new DateInterval('PT1M'));
                    }
                    if ($start->format('l') == $end->format('l')) {
                        $timeRanges[strtolower($start->format('l'))][] = $start->format('H:i') . '-' . $end->format('H:i');
                    } else {
                        $timeRanges[strtolower($start->format('l'))][] = $start->format('H:i') . '-24:00';
                        if ($end->format('H:i') != '00:00') {
                            $timeRanges[strtolower($end->format('l'))][] = '00:00-' . $end->format('H:i');
                        }
                    }
                }
            }
            foreach ($timeRanges as $day => $timeRange) {
                asort($timeRange);
                $content .= $this->addContent($day, 1, implode(',', $timeRange));
            }

            //Merge Calendar records to timeperiod
            if ($timeperiod['Timeperiod']['calendar_id'] > 0) {
                $calendar = $this->Calendar->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Calendar.id' => $timeperiod['Timeperiod']['calendar_id']
                    ],
                    'contain'    => ['CalendarHoliday']
                ]);
                foreach ($calendar['CalendarHoliday'] as $holiday) {
                    $timestamp = strtotime(sprintf('%s 00:00', $holiday['date']));

                    $calendarDay = sprintf('%s 00:00-24:00; %s',
                        strtolower(date('F j', $timestamp)),
                        $holiday['name']
                    );
                    $content .= $this->addContent($calendarDay, 1);
                }
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHostdependencies($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'HostdependencyHostMembership'      => [
                    'Host' => [
                        'fields'     => [
                            'Host.id',
                            'Host.uuid',
                            'Host.disabled'

                        ],
                        'conditions' => [
                            'Host.disabled' => 0
                        ]
                    ]
                ],
                'HostdependencyHostgroupMembership' => [
                    'Hostgroup' => [
                        'fields' => [
                            'Hostgroup.id',
                            'Hostgroup.uuid'

                        ]
                    ]
                ],
                'Timeperiod'                        => [
                    'fields' => [
                        'Timeperiod.id',
                        'Timeperiod.uuid',
                    ]
                ]
            ],

        ];
        if ($uuid !== null) {
            $hostdependencies = [];
            $query['conditions'] = [
                'Hostdependency.uuid' => $uuid
            ];
            $hostdependencies[] = $this->Hostdependency->find('first', $query);
        } else {
            $hostdependencies = $this->Hostdependency->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['hostdependencies'])) {
            mkdir($this->conf['path'] . $this->conf['hostdependencies']);
        }

        foreach ($hostdependencies as $hostdependency) {
            $file = new File($this->conf['path'] . $this->conf['hostdependencies'] . $hostdependency['Hostdependency']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $hosts = Hash::extract($hostdependency, 'HostdependencyHostMembership.{n}[dependent=0].Host.uuid');
            $dependentHosts = Hash::extract($hostdependency, 'HostdependencyHostMembership.{n}[dependent=1].Host.uuid');

            $hostGroups = Hash::extract($hostdependency, 'HostdependencyHostgroupMembership.{n}[dependent=0].Hostgroup.uuid');
            $dependentHostGroups = Hash::extract($hostdependency, 'HostdependencyHostgroupMembership.{n}[dependent=1].Hostgroup.uuid');


            if (!empty($hosts) && !empty($dependentHosts)) {
                $content .= $this->addContent('define hostdependency{', 0);

                $content .= $this->addContent('host_name', 1, implode(',', $hosts));
                $content .= $this->addContent('dependent_host_name', 1, implode(',', $dependentHosts));

                if (!empty($hostGroups)) {
                    $content .= $this->addContent('hostgroup_name', 1, implode(',', $hostGroups));
                }

                if (!empty($dependentHostGroups)) {
                    $content .= $this->addContent('dependent_hostgroup_name', 1, implode(',', $dependentHostGroups));
                }
            }

            $content .= $this->addContent('inherits_parent', 1, $hostdependency['Hostdependency']['inherits_parent']);


            $hostDependencyExecutionString = $this->hostDependencyExecutionString($hostdependency['Hostdependency']);
            if (!empty($hostDependencyExecutionString)) {
                $content .= $this->addContent('execution_failure_criteria', 1, $hostDependencyExecutionString);
            }

            $hostDependencyNotificationString = $this->hostDependencyNotificationString($hostdependency['Hostdependency']);
            if (!empty($hostDependencyNotificationString)) {
                $content .= $this->addContent('notification_failure_criteria', 1, $hostDependencyNotificationString);
            }

            if ($hostdependency['Timeperiod']['uuid'] !== null && $hostdependency['Timeperiod']['uuid'] !== '') {
                $content .= $this->addContent('dependency_period', 1, $hostdependency['Timeperiod']['uuid']);
            }

            $content .= $this->addContent('}', 0);

            //Check if the host dependency is valid
            if (empty($hosts) || empty($dependentHosts)) {
                //This host dependency is broken, ther are no hosts in it!
                $this->Hostdependency->delete($hostdependency['Hostdependency']['id']);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServicedependencies($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'ServicedependencyServiceMembership'      => [
                    'Service' => [
                        'conditions' => [
                            'Service.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid',
                        ],
                        'Host'       => [
                            'fields'     => [
                                'uuid',
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ],
                        ],
                    ],
                ],
                'ServicedependencyServicegroupMembership' => [
                    'Servicegroup' => [
                        'fields' => [
                            'Servicegroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                              => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $servicedependencies = [];
            $query['conditions'] = [
                'Servicedependency.uuid' => $uuid
            ];
            $servicedependencies[] = $this->Servicedependency->find('first', $query);
        } else {
            $servicedependencies = $this->Servicedependency->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['servicedependencies'])) {
            mkdir($this->conf['path'] . $this->conf['servicedependencies']);
        }
        foreach ($servicedependencies as $servicedependency) {
            $file = new File($this->conf['path'] . $this->conf['servicedependencies'] . $servicedependency['Servicedependency']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $masterServices = array_filter(Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=0].Service'));
            $dependentServices = array_filter(Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=1].Service'));

            $masterServicegroups = Hash::extract($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=0].Servicegroup.uuid');
            $dependentServicegroups = Hash::extract($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=1].Servicegroup.uuid');


            if (!empty($masterServicegroups)) {
                $masterServicegroups = implode(',', Hash::extract($masterServicegroups, '{n}.Servicegroup.uuid'));
            }
            if (!empty($dependentServicegroups)) {
                $dependentServicegroups = implode(',', Hash::extract($dependentServicegroups, '{n}.Servicegroup.uuid'));
            }

            $hasMasterServicesAndDependentService = (!empty($masterServices) && !empty($dependentServices));
            if ($hasMasterServicesAndDependentService === true) {
                foreach ($masterServices as $masterService) {
                    foreach ($dependentServices as $dependentService) {
                        $content .= $this->addContent('define servicedependency{', 0);
                        $content .= $this->addContent('host_name', 1, $masterService['Host']['uuid']);
                        $content .= $this->addContent('service_description', 1, $masterService['uuid']);
                        $content .= $this->addContent('dependent_host_name', 1, $dependentService['Host']['uuid']);
                        $content .= $this->addContent('dependent_service_description', 1, $dependentService['uuid']);

                        if (!empty($masterServicegroups)) {
                            $content .= $this->addContent('servicegroup_name', 1, $masterServicegroups);
                        }

                        if (!empty($dependentServicegroups)) {
                            $content .= $this->addContent('dependent_servicegroup_name', 1, $dependentServicegroups);
                        }

                        $content .= $this->addContent('inherits_parent', 1, $servicedependency['Servicedependency']['inherits_parent']);
                        $serviceDependencyExecutionString = $this->serviceDependencyExecutionString($servicedependency['Servicedependency']);
                        if (!empty($serviceDependencyExecutionString)) {
                            $content .= $this->addContent('execution_failure_criteria', 1, $serviceDependencyExecutionString);
                        }
                        $serviceDependencyNotificationString = $this->serviceDependencyNotificationString($servicedependency['Servicedependency']);
                        if (!empty($serviceDependencyNotificationString)) {
                            $content .= $this->addContent('notification_failure_criteria', 1, $serviceDependencyNotificationString);
                        }
                        if ($servicedependency['Timeperiod']['uuid'] !== null && $servicedependency['Timeperiod']['uuid'] !== '') {
                            $content .= $this->addContent('dependency_period', 1, $servicedependency['Timeperiod']['uuid']);
                        }

                        $content .= $this->addContent('}', 0);
                        $content .= $this->nl();
                    }
                }
            } else {
                //This service dependency is broken, ther are no services in it
                $this->Servicedependency->delete($servicedependency['Servicedependency']['id']);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }

            $file->write($content);
            $file->close();
        }
    }

    public function exportMacros() {
        $file = new File($this->conf['path'] . $this->conf['macros'] . $this->conf['suffix']);
        $content = $this->hashFileHeader();
        if (!$file->exists()) {
            $file->create();
        }

        $macros = $this->Macro->find('all', [
            'fields' => [
                'name',
                'value',
            ],
        ]);

        foreach ($macros as $macro) {
            $content .= $this->addContent($macro['Macro']['name'] . '=' . $macro['Macro']['value'], 0);
        }

        $file->write($content);
        $file->close();
    }

    /**
     * This function load export tasks from external modules
     */
    protected function __loadExternTasks() {
        $this->externalTasks = [];
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            if (file_exists(APP . 'Plugin/' . $pluginName . '/Console/Command/Task/' . $pluginName . 'NagiosExportTask.php')) {
                $this->externalTasks[$pluginName] = $pluginName . 'NagiosExport';
            }
        }
    }

    public function exportExternalTasks() {
        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->export();
        }
    }

    public function beforeExportExternalTasks() {
        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->beforeExport();
        }
    }

    public function afterExportExternalTasks() {
        //Restart oitc CMD to wipe old cached information
        exec('service oitc_cmd restart');

        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->afterExport();
        }
    }

    public function exportSatHostgroups() {
        if (!empty($this->dmConfig) && is_array($this->dmConfig)) {
            foreach ($this->dmConfig as $sat_id => $data) {
                //Create hostgroups configuration
                if (isset($data['Hostgroup']) && !empty($data['Hostgroup'])) {
                    if (!is_dir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['hostgroups'])) {
                        mkdir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['hostgroups']);
                    }
                    foreach ($data['Hostgroup'] as $hostgroupUuid => $members) {
                        $file = new File($this->conf['satellite_path'] . $sat_id . DS . $this->conf['hostgroups'] . $hostgroupUuid . $this->conf['suffix']);

                        $content = $this->fileHeader();
                        if (!$file->exists()) {
                            $file->create();
                        }

                        $content .= $this->addContent('define hostgroup{', 0);
                        $content .= $this->addContent('hostgroup_name', 1, $hostgroupUuid);
                        $content .= $this->addContent('alias', 1, $hostgroupUuid);
                        $content .= $this->addContent('members', 1, implode(',', $members));
                        $content .= $this->addContent('}', 0);

                        $file->write($content);
                        $file->close();
                    }
                }
            }
        }
    }

    public function exportSatServicegroups() {
        if (!empty($this->dmConfig) && is_array($this->dmConfig)) {
            foreach ($this->dmConfig as $sat_id => $data) {
                //Create service groups
                if (isset($data['Servicegroup']) && !empty($data['Servicegroup'])) {
                    if (!is_dir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups'])) {
                        mkdir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups']);
                    }
                    foreach ($data['Servicegroup'] as $servicegroupUuid => $members) {
                        $file = new File($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups'] . $servicegroupUuid . $this->conf['suffix']);
                        $content = $this->fileHeader();
                        if (!$file->exists()) {
                            $file->create();
                        }

                        $content .= $this->addContent('define servicegroup{', 0);
                        $content .= $this->addContent('servicegroup_name', 1, $servicegroupUuid);
                        $content .= $this->addContent('alias', 1, $servicegroupUuid);

                        $content .= $this->addContent('members', 1, implode(',', $members));
                        $content .= $this->addContent('}', 0);
                        $file->write($content);
                        $file->close();
                    }
                }
            }
        }
    }

    /**
     * @param array $hostOrHosttemplate
     *
     * @return string
     */
    public function hostNotificationString($hostOrHosttemplate = []) {
        $fields = ['notify_on_down' => 'd', 'notify_on_unreachable' => 'u', 'notify_on_recovery' => 'r', 'notify_on_flapping' => 'f', 'notify_on_downtime' => 's'];

        return $this->_implode($hostOrHosttemplate, $fields);
    }

    /**
     * @param array $serviceOrServicetemplate
     *
     * @return string
     */
    public function serviceNotificationString($serviceOrServicetemplate = []) {
        $fields = ['notify_on_warning' => 'w', 'notify_on_unknown' => 'u', 'notify_on_critical' => 'c', 'notify_on_recovery' => 'r', 'notify_on_flapping' => 'f', 'notify_on_downtime' => 's'];

        return $this->_implode($serviceOrServicetemplate, $fields);
    }

    /**
     * @param array $hostOrHosttemplate
     *
     * @return string
     */
    public function hostFlapdetectionString($hostOrHosttemplate = []) {
        $fields = ['flap_detection_on_up' => 'o', 'flap_detection_on_down' => 'd', 'flap_detection_on_unreachable' => 'u'];

        return $this->_implode($hostOrHosttemplate, $fields);
    }

    /**
     * @param array $serviceOrServicetemplate
     *
     * @return string
     */
    public function serviceFlapdetectionString($serviceOrServicetemplate = []) {
        $fields = ['flap_detection_on_ok' => 'o', 'flap_detection_on_warning' => 'w', 'flap_detection_on_unknown' => 'u', 'flap_detection_on_critical' => 'c'];

        return $this->_implode($serviceOrServicetemplate, $fields);
    }

    /**
     * @param array $contact
     *
     * @return string
     */
    public function contactHostNotificationOptions($contact = []) {
        $fields = ['notify_host_recovery' => 'r', 'notify_host_down' => 'd', 'notify_host_unreachable' => 'u', 'notify_host_flapping' => 'f', 'notify_host_downtime' => 's'];

        return $this->_implode($contact, $fields);
    }

    /**
     * @param array $contact
     *
     * @return string
     */
    public function contactServiceNotificationOptions($contact = []) {
        $fields = ['notify_service_downtime' => 's', 'notify_service_flapping' => 'f', 'notify_service_critical' => 'c', 'notify_service_unknown' => 'u', 'notify_service_warning' => 'w', 'notify_service_recovery' => 'r'];

        return $this->_implode($contact, $fields);
    }

    /**
     * @param array $hostescalation
     *
     * @return string
     */
    public function hostEscalationString($hostescalation = []) {
        $fields = ['escalate_on_recovery' => 'r', 'escalate_on_down' => 'd', 'escalate_on_unreachable' => 'u'];

        return $this->_implode($hostescalation, $fields);
    }

    /**
     * @param array $hostescalation
     *
     * @return string
     */
    public function serviceEscalationString($hostescalation = []) {
        $fields = ['escalate_on_recovery' => 'r', 'escalate_on_warning' => 'w', 'escalate_on_unknown' => 'u', 'escalate_on_critical' => 'c'];

        return $this->_implode($hostescalation, $fields);
    }

    /**
     * @param array $hostdependency
     *
     * @return string
     */
    public function hostDependencyExecutionString($hostdependency = []) {
        $fields = ['execution_fail_on_up' => 'o', 'execution_fail_on_down' => 'd', 'execution_fail_on_unreachable' => 'u', 'execution_fail_on_pending' => 'p', 'execution_none' => 'n'];

        return $this->_implode($hostdependency, $fields);
    }

    /**
     * @param array $hostdependency
     *
     * @return string
     */
    public function hostDependencyNotificationString($hostdependency = []) {
        $fields = ['notification_fail_on_up' => 'o', 'notification_fail_on_down' => 'd', 'notification_fail_on_unreachable' => 'u', 'notification_fail_on_pending' => 'p', 'notification_none' => 'n'];

        return $this->_implode($hostdependency, $fields);
    }

    /**
     * @param array $servicedependeny
     *
     * @return string
     */
    public function serviceDependencyExecutionString($servicedependeny = []) {
        $fields = ['execution_fail_on_ok' => 'o', 'execution_fail_on_warning' => 'w', 'execution_fail_on_unknown' => 'u', 'execution_fail_on_critical' => 'c', 'execution_fail_on_pending' => 'p', 'execution_none' => 'n'];

        return $this->_implode($servicedependeny, $fields);
    }

    /**
     * @param array $servicedependeny
     *
     * @return string
     */
    public function serviceDependencyNotificationString($servicedependeny = []) {
        $fields = ['notification_fail_on_ok' => 'o', 'notification_fail_on_warning' => 'w', 'notification_fail_on_unknown' => 'u', 'notification_fail_on_critical' => 'c', 'notification_fail_on_pending' => 'p', 'notification_none' => 'n'];

        return $this->_implode($servicedependeny, $fields);
    }

    /**
     * @param $object
     * @param $fields
     *
     * @return string
     */
    private function _implode($object, $fields) {
        $nagios = [];
        foreach ($fields as $field => $nagios_value) {
            if (isset($object[$field]) && $object[$field] == 1) {
                $nagios[] = $nagios_value;
            }
        }

        return implode(',', $nagios);
    }

    public function verify() {
        $this->out("<info>" . __d('oitc_console', 'verifying configuration files, please standby...') . "</info>");
        exec('sudo -u ' . $this->_systemsettings['MONITORING']['MONITORING.USER'] . ' ' . Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin') . ' ' . Configure::read('nagios.verify') . ' ' . Configure::read('nagios.nagios_cfg'), $out);
        foreach ($out as $line) {
            echo $line . PHP_EOL;
        }
    }

    /**
     * @return string
     */
    public function returnVerifyCommand() {
        return 'sudo -u ' . $this->_systemsettings['MONITORING']['MONITORING.USER'] . ' ' . Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin') . ' ' . Configure::read('nagios.verify') . ' ' . Configure::read('nagios.nagios_cfg');
    }

    /**
     * @return mixed
     */
    public function returnReloadCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.RELOAD'];
    }

    /**
     * @return mixed
     */
    public function returnAfterExportCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.AFTER_EXPORT'];
    }

    /**
     * @deprecated
     */
    public function restart() {
    }

    public function deleteHostPerfdata() {
        App::uses('Folder', 'Utility');
        $deletedHosts = $this->DeletedHost->findAllByDeletedPerfdata(0);
        foreach ($deletedHosts as $deletedHost) {
            if (is_dir(Configure::read('rrd.path') . $deletedHost['DeletedHost']['uuid'])) {
                $folder = new Folder(Configure::read('rrd.path') . $deletedHost['DeletedHost']['uuid']);
                $folder->delete();
                unset($folder);
            }

            $deletedHost['DeletedHost']['deleted_perfdata'] = 1;
            $this->DeletedHost->save($deletedHost);
        }
    }

    public function deleteServicePerfdata() {
        $deletedServices = $this->DeletedService->findAllByDeletedPerfdata(0);
        foreach ($deletedServices as $deletedService) {
            //Check if perfdata files still exists and if we need to delete them
            foreach (Configure::read('rrd.allowedExtensions') as $extension) {
                if (file_exists(Configure::read('rrd.path') . $deletedService['DeletedService']['host_uuid'] . '/' . $deletedService['DeletedService']['uuid'] . '.' . $extension)) {
                    unlink(Configure::read('rrd.path') . $deletedService['DeletedService']['host_uuid'] . '/' . $deletedService['DeletedService']['uuid'] . '.' . $extension);
                }
            }

            $deletedService['DeletedService']['deleted_perfdata'] = 1;
            $this->DeletedService->save($deletedService);
        }
    }

    public function deleteAllConfigfiles() {
        App::uses('Folder', 'Utility');
        $result = scandir($this->conf['path'] . DS . 'config');
        foreach ($result as $filename) {
            if (!in_array($filename, ['.', '..'])) {
                if (is_dir($this->conf['path'] . DS . 'config' . DS . $filename)) {
                    $folder = new Folder($this->conf['path'] . DS . 'config' . DS . $filename);
                    $folder->delete();
                    unset($folder);
                }
            }
        }
    }

    /**
     * @param null|resource $file
     *
     * @return string
     */
    public function fileHeader($file = null) {
        $header = ";#########################################################################
;#    DO NOT EDIT THIS FILE BY HAND -- YOUR CHANGES WILL BE OVERWRITTEN  #
;#                                                                       #
;#                   File generated by openITCOCKPIT                     #
;#                                                                       #
;#                        Created: " . date('d.m.Y H:i') . "                      #
;#########################################################################

;Weblinks:
;http://nagios.sourceforge.net/docs/nagioscore/4/en/objectdefinitions.html
;http://nagios.sourceforge.net/docs/nagioscore/4/en/objecttricks.html
\n";
        if (is_resource($file)) {
            fwrite($file, $header);
        } else {
            return $header;
        }
    }

    /**
     * @param null|resource $file
     *
     * @return string
     */
    public function hashFileHeader($file = null) {
        $header = "#########################################################################
#    DO NOT EDIT THIS FILE BY HAND -- YOUR CHANGES WILL BE OVERWRITTEN  #
#                                                                       #
#                   File generated by openITCOCKPIT                     #
#                                                                       #
#                        Created: " . date('d.m.Y H:i') . "                      #
#########################################################################
#
#Weblink:
# http://nagios.sourceforge.net/docs/nagioscore/4/en/configmain.html#resource_file
# http://nagios.sourceforge.net/docs/nagioscore/4/en/macrolist.html#user
";
        if (is_resource($file)) {
            fwrite($file, $header);
        } else {
            return $header;
        }
    }

    /**
     * @param             $string
     * @param int $deep
     * @param null|string $value
     * @param bool $newline
     *
     * @return string
     */
    public function addContent($string, $deep = 1, $value = null, $newline = true) {
        $c = "";
        $i = 0;
        while ($i < $deep) {
            $c .= '    ';
            $i++;
        }
        $c .= $string;

        if ($value !== null) {
            while ((strlen($c) < 40)) {
                $c .= ' ';
            }
        }

        if ($value !== null) {
            $c .= $value;
        }

        if ($newline === true) {
            $c .= PHP_EOL;
        }

        return $c;
    }

    /**
     * @param $pathForBackup
     *
     * @return array
     */
    public function makeSQLBackup($pathForBackup) {
        $connection = ConnectionManager::sourceList();
        $connectionList = ConnectionManager::enumConnectionObjects();
        $usedConnectionDetails = $connectionList[$connection[0]];
        $dbc_dbname = $usedConnectionDetails["database"];
        $output = [];
        $returncode = 0;
        exec("mysqldump --defaults-extra-file=/etc/mysql/debian.cnf --databases $dbc_dbname --flush-privileges --single-transaction --triggers --routines --events --hex-blob --ignore-table=$dbc_dbname.nagios_acknowledgements --ignore-table=$dbc_dbname.nagios_commands --ignore-table=$dbc_dbname.nagios_commenthistory --ignore-table=$dbc_dbname.nagios_configfiles --ignore-table=$dbc_dbname.nagios_configfilevariables --ignore-table=$dbc_dbname.nagios_conninfo --ignore-table=$dbc_dbname.nagios_contact_addresses --ignore-table=$dbc_dbname.nagios_contact_notificationcommands --ignore-table=$dbc_dbname.nagios_contactgroup_members --ignore-table=$dbc_dbname.nagios_contactgroups --ignore-table=$dbc_dbname.nagios_contactnotificationmethods --ignore-table=$dbc_dbname.nagios_contactnotifications --ignore-table=$dbc_dbname.nagios_contacts --ignore-table=$dbc_dbname.nagios_contactstatus --ignore-table=$dbc_dbname.nagios_customvariables --ignore-table=$dbc_dbname.nagios_customvariablestatus --ignore-table=$dbc_dbname.nagios_dbversion --ignore-table=$dbc_dbname.nagios_downtimehistory --ignore-table=$dbc_dbname.nagios_eventhandlers --ignore-table=$dbc_dbname.nagios_externalcommands --ignore-table=$dbc_dbname.nagios_flappinghistory --ignore-table=$dbc_dbname.nagios_host_contactgroups --ignore-table=$dbc_dbname.nagios_host_contacts --ignore-table=$dbc_dbname.nagios_host_parenthosts --ignore-table=$dbc_dbname.nagios_hostchecks --ignore-table=$dbc_dbname.nagios_hostdependencies --ignore-table=$dbc_dbname.nagios_hostescalation_contactgroups --ignore-table=$dbc_dbname.nagios_hostescalation_contacts --ignore-table=$dbc_dbname.nagios_hostescalations --ignore-table=$dbc_dbname.nagios_hostgroup_members --ignore-table=$dbc_dbname.nagios_hostgroups --ignore-table=$dbc_dbname.nagios_hosts --ignore-table=$dbc_dbname.nagios_hoststatus --ignore-table=$dbc_dbname.nagios_instances --ignore-table=$dbc_dbname.nagios_logentries --ignore-table=$dbc_dbname.nagios_notifications --ignore-table=$dbc_dbname.nagios_processevents --ignore-table=$dbc_dbname.nagios_programstatus --ignore-table=$dbc_dbname.nagios_runtimevariables --ignore-table=$dbc_dbname.nagios_scheduleddowntime --ignore-table=$dbc_dbname.nagios_service_contactgroups --ignore-table=$dbc_dbname.nagios_service_contacts --ignore-table=$dbc_dbname.nagios_service_parentservices --ignore-table=$dbc_dbname.nagios_servicechecks --ignore-table=$dbc_dbname.nagios_servicedependencies --ignore-table=$dbc_dbname.nagios_serviceescalation_contactgroups --ignore-table=$dbc_dbname.nagios_serviceescalation_contacts --ignore-table=$dbc_dbname.nagios_serviceescalations --ignore-table=$dbc_dbname.nagios_servicegroup_members --ignore-table=$dbc_dbname.nagios_servicegroups --ignore-table=$dbc_dbname.nagios_services --ignore-table=$dbc_dbname.nagios_servicestatus --ignore-table=$dbc_dbname.nagios_statehistory --ignore-table=$dbc_dbname.nagios_systemcommands --ignore-table=$dbc_dbname.nagios_timedeventqueue --ignore-table=$dbc_dbname.nagios_timedevents --ignore-table=$dbc_dbname.nagios_timeperiod_timeranges --ignore-table=$dbc_dbname.nagios_timeperiods > " . $pathForBackup, $output, $returncode);
        $return = [
            'output'     => $output,
            'returncode' => $returncode,
        ];

        return $return;
    }

    /**
     * @param $dumpFile
     *
     * @return array
     */
    public function restoreSQLBackup($dumpFile) {
        $connection = ConnectionManager::sourceList();
        $connectionList = ConnectionManager::enumConnectionObjects();
        $usedConnectionDetails = $connectionList[$connection[0]];
        $dbc_dbname = $usedConnectionDetails["database"];
        $host = $usedConnectionDetails["host"];
        $pwd = $usedConnectionDetails["password"];
        $user = $usedConnectionDetails["login"];
        $output = [];
        $returncode = 0;
        exec("mysql --host=$host --user=$user --password=$pwd -v $dbc_dbname < $dumpFile", $output, $returncode);
        exec("mysql --host=$host --user=$user --password=$pwd -e 'truncate openitcockpit.exports'");
        $return = [
            'output'     => $output,
            'returncode' => $returncode,
        ];

        return $return;
    }

    public function escapeLastBackslash($str = '') {
        if (mb_substr($str, -1) === '\\') {
            $str = sprintf('%s\\', $str); //Add a \ to the end of the string - because last char is a \
        }
        return $str;
    }
}
