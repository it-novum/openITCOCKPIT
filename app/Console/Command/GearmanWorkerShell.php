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

class GearmanWorkerShell extends AppShell
{

    public $uses = [
        'Systemsetting',
        MONITORING_EXTERNALCOMMAND,
        'Export'
    ];
    public $tasks = [
        'NagiosExport',
        'DefaultNagiosConfig',
    ];

    public function main()
    {
        $this->stdout->styles('red', ['text' => 'red']);
        $this->stdout->styles('green', ['text' => 'green']);
        Configure::load('gearman');
        Configure::load('nagios');
        $this->Config = Configure::read('gearman');
        $this->parentProcess = true;
        $this->childPids = [];

        $this->parser = $this->getOptionParser();
        if (array_key_exists('probe', $this->params)) {
            $this->probe();
            return;
        }

        if (array_key_exists('status', $this->params)) {
            if ($this->status()) {
                $this->stdout->styles('green', ['text' => 'green']);
                $this->out('<green>oITC GearmanWorker is running</green>');
                exit(0);
            }

            $this->stdout->styles('red', ['text' => 'red']);
            $this->out('<red>oITC GearmanWorker Server not running</red>');
            exit(3);
        }

        if (array_key_exists('stop', $this->params)) {
            $this->stop();
            return;
        }

        try {
            $this->_systemsettings = $this->Systemsetting->findAsArray();
        } catch (Exception $e) {
            debug($e->getMessage());
            exit(3);
        }


        if (array_key_exists('start', $this->params)) {
            $this->start();
            return;
        }

        foreach (['restart', 'try-restart', 'reload', 'force-reload'] as $key) {
            if (array_key_exists($key, $this->params)) {
                $this->restart();
                return;
            }
        }

        if (array_key_exists('daemon', $this->params)) {
            $this->daemonizing();
        } else {
            $this->work();
        }
    }

    public function _welcome()
    {
        //Disable CakePHP welcome messages
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'daemon' => ['short' => 'd', 'help' => __d('oitc_console', 'Starts GearmanWorker in forground mode and fork worker processes')],
            'start' => ['help' => __d('oitc_console', 'Start GearmanWorker in daemon mode and and fork the process in the background the daemon')],
            'stop' => ['short' => 'k', 'help' => __d('oitc_console', 'Stops the daemon')],
            'restart' => ['help' => __d('oitc_console', 'Restart the daemon')],
            'try-restart' => ['help' => __d('oitc_console', 'Restart the daemon')],
            'reload' => ['help' => __d('oitc_console', 'Restart the daemon')],
            'force-reload' => ['help' => __d('oitc_console', 'Restart the daemon')],
            'status' => ['short' => 's', 'help' => __d('oitc_console', 'Resturn the status of the daemon')],
            'probe' => ['short' => 'p', 'help' => __d('oitc_console', 'Pacemaker likes this, but we dont know why :) (it\'s recommended to use with -q e.g.: gearman_worker -p -q)')],
        ]);
        return $parser;
    }

    public function start()
    {
        if ($this->status()) {
            $this->out("<info>Notice: oITC GearmanWorker is allready running!</info>");
            exit(0);
        }

        $this->out("<info>Starting oITC GearmanWorker...</info>", false);
        $pid = pcntl_fork();
        if (!$pid) {
            //Forked in background
            $this->daemonizing();
            exit(255);
        }
        $this->out("<green>   OK</green>");
        exit(0);
    }

    public function daemonizing()
    {
        if (file_exists(Configure::read('gearman.pidfile'))) {
            $this->out('Pid file ' . Configure::read('gearman.pidfile') . ' allready exists');
            exit(3);
        }

        $pidfile = fopen(Configure::read('gearman.pidfile'), 'w+');
        fwrite($pidfile, getmypid());
        fclose($pidfile);

        declare(ticks = 100);
        pcntl_signal(SIGTERM, [$this, 'sig_handler']);
        pcntl_signal(SIGINT, [$this, 'sig_handler']);

        for ($i = 1; $i < Configure::read('gearman.worker'); $i++) {
            $pid = pcntl_fork();
            if (!$pid) {
                //I am a child process
                $this->parentProcess = false;
                $this->work();
            }
            $this->childPids[] = $pid;
            unset($pid);
        }

        pcntl_signal(SIGCHLD, [$this, 'sig_handler']);
        $this->work();
    }

    public function work()
    {
        $worker = new GearmanWorker();
        $worker->addServer($this->Config['address'], $this->Config['port']);

        $worker->addFunction('oitc_gearman', [$this, 'runTask']);
        $worker->addOptions(GEARMAN_WORKER_NON_BLOCKING);

        $this->jobIdelCounter = 0;

        while (true) {
            pcntl_signal_dispatch();

            if ($this->jobIdelCounter < 10) {
                $this->jobIdelCounter++;
            }

            $worker->work();

            if ($this->jobIdelCounter == 10) {
                usleep(250000);
            }

        }
    }

    public function runTask($job)
    {
        $this->jobIdelCounter = 0;

        $payload = $job->workload();
        if ($this->Config['encryption'] === true) {
            $payload = Security::cipher($payload, $this->Config['password']);
        }

        $payload = @unserialize($payload);

        if (!is_array($payload)) {
            return serialize(['error' => 'Corrupt data']);
        }

        $return = [];


        // Avoid "MySQL server has gone away"
        $this->Systemsetting->getDatasource()->reconnect();

        switch ($payload['task']) {
            case 'CheckMKSNMP':
                $_task = new TaskCollection($this);
                $MkNagiosExportTask = $_task->load('MkModule.MkModuleNagiosExport');

                //Generate check_mk config file, to run SNMP scan
                $MkNagiosExportTask->init();
                if ($payload['snmp_version'] < 3) {
                    //SNMP V1 and V2
                    $MkNagiosExportTask->createConfigFiles($payload['hostuuid'], [
                        'for_snmp_scan' => true,
                        'host_address' => $payload['host_address'],
                        'snmp_version' => $payload['snmp_version'],
                        'snmp_community' => $payload['snmp_community']
                    ]);
                } else {
                    //SNMP V3
                    $MkNagiosExportTask->createConfigFiles($payload['hostuuid'], [
                        'for_snmp_scan' => true,
                        'host_address' => $payload['host_address'],
                        'snmp_version' => $payload['snmp_version'],
                        'v3' => [
                            'security_level' => $payload['v3']['security_level'],
                            'hash_algorithm' => $payload['v3']['hash_algorithm'],
                            'username' => $payload['v3']['username'],
                            'password' => $payload['v3']['password'],
                            'encryption_algorithm' => $payload['v3']['encryption_algorithm'],
                            'encryption_password' => $payload['v3']['encryption_password']
                        ]
                    ]);
                }

                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -II -v ' . escapeshellarg($payload['hostuuid']), $output, $returncode);
                $output = null;
                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -D ' . escapeshellarg($payload['hostuuid']), $output, $returncode);
                $return = $output;
                break;

            case 'CheckMKListChecks':
                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -L', $output);
                $return = $output;
                break;

            case 'CheckMkDiscovery':

                //Generate .mk config to run -II and -D
                $_task = new TaskCollection($this);
                $MkNagiosExportTask = $_task->load('MkModule.MkModuleNagiosExport');
                $MkNagiosExportTask->init();
                $MkNagiosExportTask->createConfigFiles($payload['hostUuid'], [
                    'for_snmp_scan' => true, //Hacky but works -.-
                    'host_address' => $payload['hostaddress']
                ]);

                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -II -v ' . escapeshellarg($payload['hostUuid']), $output, $returncode);
                $output = null;
                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -D ' . escapeshellarg($payload['hostUuid']), $output, $returncode);
                $return = $output;
                unset($output);
                break;

            case 'CheckMKListChecks':
                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -L', $output, $returncode);
                $return = $output;
                unset($output);
                break;

            case 'CheckMKProcesses':
                exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -d ' . escapeshellarg($payload['hostaddress']), $output);
                $return = $output;
                unset($output);
                break;

            case 'create_apt_config':
                $file = fopen('/etc/apt/sources.list.d/openitcockpit.list', 'w+');
                fwrite($file, 'deb https://secret:' . $payload['key'] . '@apt.open-itcockpit.com trusty  main' . PHP_EOL);
                //fwrite($file, 'deb http://secret:'.$payload['key'].'@apt.open-itcockpit.com nightly  main'.PHP_EOL);
                fclose($file);
                unset($payload);
                exec('apt-get update');
                break;

            case 'deleteServiceConfiguration':
                $file = Configure::read('nagios.export.path') . Configure::read('nagios.export.services') . $payload['serviceUuid'] . Configure::read('nagios.export.suffix');
                if (file_exists($file)) {
                    unlink($file);
                }
                break;

            case 'createHostDowntime':
                $this->Externalcommand->setHostDowntime($payload);
                break;

            case 'createHostgroupDowntime':
                $this->Externalcommand->setHostgroupDowntime($payload);
                break;

            case 'createServiceDowntime':
                $this->Externalcommand->setServiceDowntime($payload);
                break;

            //Called by NagiosModule/CmdController/submit
            case 'cmd_external_command':
                $this->Externalcommand->runCmdCommand($payload);
                break;

            case 'export_start_export':
                //Start the export over a gearman worker to avoid max_execution_time issues
                $this->launchExport($payload['backup']);
                break;

            case 'export_delete_old_configuration':
                $this->NagiosExport->deleteAllConfigfiles();
                $return = ['task' => $payload['task']];
                break;

            case 'export_create_default_config':
                $this->DefaultNagiosConfig->execute();
                $return = ['task' => $payload['task']];
                break;
            case 'export_hosttemplates':
                $this->NagiosExport->exportHosttemplates();
                $return = ['task' => $payload['task']];
                break;
            case 'export_hosts':
                $this->NagiosExport->exportHosts();
                $return = ['task' => $payload['task']];
                break;
            case 'export_commands':
                $this->NagiosExport->exportCommands();
                $return = ['task' => $payload['task']];
                break;
            case 'export_contacts':
                $this->NagiosExport->exportContacts();
                $return = ['task' => $payload['task']];
                break;
            case 'export_contactgroups':
                $this->NagiosExport->exportContactgroups();
                $return = ['task' => $payload['task']];
                break;
            case 'export_timeperiods':
                $this->NagiosExport->exportTimeperiods();
                $return = ['task' => $payload['task']];
                break;
            case 'export_hostgroups':
                $this->NagiosExport->exportHostgroups();
                $return = ['task' => $payload['task']];
                break;
            case 'export_hostescalations':
                $this->NagiosExport->exportHostescalations();
                $return = ['task' => $payload['task']];
                break;
                $this->NagiosExport->exportMacros();
                $return = ['task' => $payload['task']];
            case 'export_servicetemplates':
                $this->NagiosExport->exportServicetemplates();
                $return = ['task' => $payload['task']];
                break;
            case 'export_services':
                $this->NagiosExport->exportServices();
                $return = ['task' => $payload['task']];
                break;
            case 'export_serviceescalations':
                $this->NagiosExport->exportServiceescalations();
                $return = ['task' => $payload['task']];
                break;
            case 'export_servicegroups':
                $this->NagiosExport->exportServicegroups();
                $return = ['task' => $payload['task']];
                break;
            case 'export_hostdependencies':
                $this->NagiosExport->exportHostdependencies();
                $return = ['task' => $payload['task']];
                break;
            case 'export_servicedependencies':
                $this->NagiosExport->exportServicedependencies();
                $return = ['task' => $payload['task']];
                break;
            case 'export_userdefinedmacros':
                $this->NagiosExport->exportMacros();
                $return = ['task' => $payload['task']];
                break;

            case 'export_verify_config':
                $command = $this->NagiosExport->returnVerifyCommand();
                exec($command, $output, $returncode);
                $return = [
                    'output' => $output,
                    'returncode' => $returncode
                ];
                break;

            case 'export_sync_sat_config':
                //This task is part of a plugin, so we need to load it dynamicly
                $_task = new TaskCollection($this);
                $AfterExportTask = $_task->load('AfterExport');
                $AfterExportTask->beQuiet();
                $AfterExportTask->init();
                $AfterExportTask->copy($payload['Satellite']);
                $return = ['task' => $payload['task']];
                break;

            case 'make_sql_backup':
                $this->NagiosExport->makeSQLBackup(Configure::read('nagios.export.backupTarget') . '/');
                break;

            case 'restore_sql_backup':
                $this->NagiosExport->restoreSQLBackup($payload['path']);
                break;
        }

        return serialize($return);
    }

    public function launchExport($createBackup = 1)
    {
        //We do the export in on of our workers, to avoid max_execution_time errors
        $this->NagiosExport->init();
        $successfully = true;
        $this->Export->deleteAll(true);
        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'export_started',
                'text' => __('Started refresh of monitoring configuration')
            ]
        ];
        $result = $this->Export->save($data);

        if ($createBackup == 1) {
            $this->Export->create();
            $data = [
                'Export' => [
                    'task' => 'export_create_backup',
                    'text' => __('Create Backup of old configuration')
                ]
            ];
            $result = $this->Export->save($data);

            App::uses('Folder', 'Utility');
            $folder1 = new Folder(Configure::read('nagios.export.backupSource'));

            $backupTarget = Configure::read('nagios.export.backupTarget') . '/' . date('d-m-Y_H-i-s');

            if (!is_dir(Configure::read('nagios.export.backupTarget'))) {
                mkdir(Configure::read('nagios.export.backupTarget'));
            }

            if (!is_dir($backupTarget)) {
                mkdir($backupTarget);
            }
            $folder1->copy($backupTarget);

            //Hier muss ddie neue Backupfunktion rein
            $this->NagiosExport->makeSQLBackup(Configure::read('nagios.export.backupTarget') . '/');

            $this->Export->saveField('finished', 1);
            $this->Export->saveField('successfully', 1);
        }

        $gearmanClient = new GearmanClient();
        $gearmanClient->addServer($this->Config['address'], $this->Config['port']);
        //This callback gets called, for any finished export task (like hosttemplates, services etc...)
        $gearmanClient->setCompleteCallback([$this, 'exportCallback']);

        //Delete old configuration
        //Delete old configuration
        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'export_delete_old_configuration',
                'text' => __('Delete old configuration')
            ]
        ];
        $response = $this->Export->save($data);
        $gearmanClient->do("oitc_gearman", Security::cipher(serialize(['task' => 'export_delete_old_configuration']), $this->Config['password']));
        $this->Export->saveField('finished', 1);
        $this->Export->saveField('successfully', 1);

        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'before_export_external_tasks',
                'text' => __('Execute pre export tasks')
            ]
        ];
        $response = $this->Export->save($data);
        $this->NagiosExport->beforeExportExternalTasks();
        $this->Export->id = $response['Export']['id'];
        $this->Export->saveField('finished', 1);
        $this->Export->saveField('successfully', 1);

        //Define all tasks, we can do parallel
        $tasks = [
            'export_create_default_config' => [
                'text' => __('Create default configuration'),
            ],
            'export_hosttemplates' => [
                'text' => __('Create hosttemplate configuration'),
            ],
            'export_hosts' => [
                'text' => __('Create host configuration'),
            ],
            'export_commands' => [
                'text' => __('Create command configuration'),
            ],
            'export_contacts' => [
                'text' => __('Create contact configuration'),
            ],
            'export_contactgroups' => [
                'text' => __('Create contact group configuration'),
            ],
            'export_timeperiods' => [
                'text' => __('Create timeperiod configuration'),
            ],
            'export_hostgroups' => [
                'text' => __('Create host group configuration'),
            ],
            'export_hostescalations' => [
                'text' => __('Create host escalation configuration'),
            ],
            'export_servicetemplates' => [
                'text' => __('Create servicetemplate configuration'),
            ],
            'export_services' => [
                'text' => __('Create service configuration'),
            ],
            'export_serviceescalations' => [
                'text' => __('Create service escalation configuration'),
            ],
            'export_servicegroups' => [
                'text' => __('Create service group configuration'),
            ],
            'export_hostdependencies' => [
                'text' => __('Create host dependency configuration'),
            ],
            'export_servicedependencies' => [
                'text' => __('Create service dependency configuration'),
            ],
            'export_userdefinedmacros' => [
                'text' => __('Export user defined macros'),
            ],
        ];

        foreach ($tasks as $taskName => $task) {
            $gearmanClient->addTask("oitc_gearman", Security::cipher(serialize(['task' => $taskName]), $this->Config['password']));
            $this->Export->create();
            $data = [
                'Export' => [
                    'task' => $taskName,
                    'text' => $task['text']
                ]
            ];
            $this->Export->save($data);
        }
        $gearmanClient->runTasks();

        //runTasks() may be block for a long time
        //Reset MySQL Connection to avoid MySQL hase gone away
        $this->Systemsetting->getDatasource()->reconnect();

        //Export done
        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'after_export_external_tasks',
                'text' => __('Execute post export tasks')
            ]
        ];
        $response = $this->Export->save($data);
        $this->NagiosExport->afterExportExternalTasks();
        $this->Export->saveField('finished', 1);
        $this->Export->saveField('successfully', 1);

        //Verify new configuration
        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'export_verify_new_configuration',
                'text' => __('Verifying new configuration')
            ]
        ];
        $this->Export->save($data);
        $command = $this->NagiosExport->returnVerifyCommand();
        $output = null;
        exec($command, $output, $returncode);
        $this->Export->saveField('finished', 1);
        if ($returncode === 0) {
            //New configuration is valid :-)
            //Reloading monitoring system
            $this->Export->saveField('successfully', 1);
            $this->Export->create();
            $data = [
                'Export' => [
                    'task' => 'export_reload_monitoring',
                    'text' => __('Reloading monitoring engine')
                ]
            ];
            $this->Export->save($data);
            $command = $this->NagiosExport->returnReloadCommand();
            $output = null;
            exec($command, $output, $returncode);
            $this->Export->saveField('finished', 1);
            if ($returncode == 0) {
                $this->Export->saveField('finished', 1);
                $this->Export->saveField('successfully', 1);

                //Run After Export command
                $this->Export->create();
                $data = [
                    'Export' => [
                        'task' => 'export_after_export_command',
                        'text' => __('Execute after export command')
                    ]
                ];
                $result = $this->Export->save($data);
                $command = $this->NagiosExport->returnAfterExportCommand();
                $output = null;
                exec($command, $output, $returncode);
                //The exec() may be block for a while
                //Reset MySQL Connection to avoid MySQL hase gone away
                $this->Systemsetting->getDatasource()->reconnect();
                $this->Export->id = $result['Export']['id'];
                $this->Export->saveField('finished', 1);
                if ($returncode == 0) {
                    $this->Export->saveField('successfully', 1);
                    $this->distributedMonitoringAfterExportCommand();
                } else {
                    $successfully = false;
                    $this->Export->saveField('successfully', 0);
                }
            } else {
                $successfully = false;
                $this->Export->saveField('successfully', 0);
            }
        } else {
            //Error with new configuration :-(
            $successfully = false;
            $this->Export->saveField('successfully', 0);
        }

        //Export done
        $this->Export->create();
        $data = [
            'Export' => [
                'task' => 'export_finished',
                'text' => __('Refresh finished'),
                'finished' => 1,
                'successfully' => $successfully
            ]
        ];
        $this->Export->save($data);
        $exportStarted = $this->Export->findByTask('export_started');
        $exportStarted['Export']['finished'] = 1;
        $exportStarted['Export']['successfully'] = $successfully;
        $this->Export->save($exportStarted);
    }

    public function exportCallback($task)
    {
        $result = unserialize($task->data());
        if ($result['task'] !== 'export_sync_sat_config') {
            $exportRecord = $this->Export->findByTask($result['task']);
            if (!empty($exportRecord)) {
                $exportRecord['Export']['finished'] = 1;
                $exportRecord['Export']['successfully'] = 1;
                $this->Export->save($exportRecord);
            }
        }
    }

    public function distributedMonitoringAfterExportCommand()
    {
        //Loading distributed Monitoring support, if plugin is loaded/installed
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        if (in_array('DistributeModule', $modulePlugins)) {
            //DistributeModule is loaded and installed...
            $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
            $satellites = $this->Satellite->find('all');

            $gearmanClient = new GearmanClient();
            $gearmanClient->addServer($this->Config['address'], $this->Config['port']);
            //This callback gets called, for any finished export task (like hosttemplates, services etc...)
            $gearmanClient->setCompleteCallback([$this, 'exportCallback']);

            $this->Export->create();
            $data = [
                'Export' => [
                    'task' => 'export_sync_sat_config',
                    'text' => __('Copy new monitoring configuration to satellite systems')
                ]
            ];
            $result = $this->Export->save($data);
            foreach ($satellites as $satellite) {
                $gearmanClient->addTask("oitc_gearman", Security::cipher(serialize(['task' => $data['Export']['task'], 'Satellite' => $satellite]), $this->Config['password']));
            }
            $gearmanClient->runTasks();
            // Avoid "MySQL server has gone away"
            $this->Systemsetting->getDatasource()->reconnect();

            $this->Export->id = $result['Export']['id'];
            $this->Export->saveField('finished', 1);
            $this->Export->saveField('successfully', 1);
        }
    }

    /**
     * The signal handler is called by the linux kernel or pcntl_signal_dispatch and will handel singals^^
     * Delete oitc.cmd and exit on SIGTERM and SIGINT
     *
     * @param    int $signo , the signal catched by pcntl_signal_dispatch()
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since    3.0.1
     *
     */
    public function sig_handler($signo)
    {
        switch ($signo) {
            case SIGTERM:
            case SIGINT:
                if ($this->parentProcess) {
                    foreach ($this->childPids as $pid) {
                        posix_kill($pid, SIGTERM);
                    }

                    foreach ($this->childPids as $pid) {
                        pcntl_waitpid($pid, $status);
                    }

                    if (file_exists(Configure::read('gearman.pidfile'))) {
                        unlink(Configure::read('gearman.pidfile'));
                    }
                }
                exit(0);
                break;

            case SIGCHLD:
                ///Get the dead child pid and clean up the zombie process
                if ($this->parentProcess) {
                    $dead_child_pid = pcntl_wait($status, WNOHANG);
                    $pids = [];
                    foreach ($this->childPids as $pid) {
                        if ($pid != $dead_child_pid) {
                            $pids[] = $pid;
                        }
                    }
                    $this->childPids = $pids;
                    unset($pids);
                }
                break;

            default:
                //$this->out('Warning: Signal not supported yet!');
                break;
        }
    }

    /*
    * Pacemaker likes this function, we dont know why :)
    */
    public function probe()
    {
        echo "restart\n";
        exit(0);
    }

    public function status()
    {
        foreach ($this->_getPid() as $pid) {
            exec('ps -eaf |grep ' . escapeshellarg($pid) . ' |grep -v grep', $output);
            foreach ($output as $line) {
                if (preg_match('#.*app/Console/cake.php -working .*/app gearman_worker (-d|--daemon|--start)#', $line)) {
                    return true;
                }
            }
        }
        // The file should not exist at this point, if it exists the GearmanWorker crashed and we need to cleanup
        if (file_exists(Configure::read('gearman.pidfile'))) {
            unlink(Configure::read('gearman.pidfile'));
        }
        return false;
    }

    public function restart()
    {
        if ($this->stop(false)) {
            sleep(1);
            $this->start();
        }
    }

    public function stop($exit = true)
    {
        if (!$this->status()) {
            $this->out("<info>Notice: oITC GearmanWorker isn't running!</info>");
            if ($exit) {
                exit(0);
            }
            return true;
        }

        foreach ($this->_getPid() as $mypid) {
            posix_kill($mypid, SIGTERM);
            $this->out("<info>Waiting for oITC GearmanWorker to exit...</info>", false);
            pcntl_waitpid($mypid, $status);
            $this->out("<green>   OK</green>");
        }
        if ($exit) {
            exit(0);
        }
        return true;
    }

    private function _getPid()
    {
        $return = [];
        if (file_exists(Configure::read('gearman.pidfile'))) {
            $pids = file(Configure::read('gearman.pidfile'));
            if (sizeof($pids) > 1) {
                $this->out('<warning>More than one pid in my pid file!</warning>');
            }
            foreach ($pids as $pid) {
                $return[] = trim($pid);
            }
        }
        return $return;
    }
}