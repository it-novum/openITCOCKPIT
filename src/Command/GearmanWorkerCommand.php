<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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


declare(strict_types=1);

namespace App\Command;

use App\itnovum\openITCOCKPIT\Core\MonitoringEngine\SatelliteCopy;
use App\itnovum\openITCOCKPIT\Database\Backup;
use App\itnovum\openITCOCKPIT\Monitoring\Naemon\ExternalCommands;
use App\Model\Table\ExportsTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\MonitoringEngine\NagiosConfigDefaults;
use itnovum\openITCOCKPIT\Core\MonitoringEngine\NagiosConfigGenerator;
use itnovum\openITCOCKPIT\Core\System\Health\LsbRelease;
use Symfony\Component\Filesystem\Filesystem;

/**
 * GearmanWorker command.
 */
class GearmanWorkerCommand extends Command {

    /**
     * Determines if the current process is the parent process or a forked child process.
     *
     * @var bool
     */
    private $parentProcess = true;

    /**
     * All PIDs of the forked child processes
     *
     * @var array
     */
    private $childPids = [];

    /**
     * Call usleep if the process is idling around to save CPU time.
     * Otherwise the process will consume 100% CPU :)
     *
     * @var int
     */
    private $jobIdelCounter = 0;

    /**
     * @var string
     */
    private $naemonExternalCommandsFile;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        Configure::load('gearman');
        Configure::load('nagios');
        Configure::load('NagiosModule.config');
        $this->naemonExternalCommandsFile = Configure::read('NagiosModule.PREFIX') . Configure::read('NagiosModule.NAGIOS_CMD');

        $this->daemonizing($io);
    }

    public function daemonizing(ConsoleIo $io) {
        $gearmanConfig = Configure::read('gearman');

        $pidfile = $gearmanConfig['pidfile'];
        if (file_exists($pidfile)) {
            $pid = trim(file_get_contents($gearmanConfig['pidfile']));
            if (is_numeric($pid)) {
                //We got a pid. Is this a gearman_worker or is this a pid from an died process?
                exec('ps -eaf | grep ' . escapeshellarg($pid) . '| grep gearman_worker |grep -v grep', $output);

                if (!empty($output)) {
                    Log::error(sprintf('GearmanWorker: Pidfile "%s" allready exists.', $pidfile));
                    exit(3);
                }
            }

            //This is not a gearman_worker process
            Log::info(sprintf('GearmanWorker: Deleted orphaned Pidfile "%s". Did the gearman_worker died?', $pidfile));
            unlink($pidfile);

        }

        $pidfd = fopen($pidfile, 'w+');
        fwrite($pidfd, (string)getmypid());
        fclose($pidfd);

        declare(ticks=100);
        pcntl_signal(SIGTERM, [$this, 'sig_handler']);
        pcntl_signal(SIGINT, [$this, 'sig_handler']);

        //Disconnect from DB before forking
        /** @var SystemsettingsTable $SystemsettingsTable */
        //$SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        //$connection = $SystemsettingsTable->getConnection();
        //$connection->disconnect();

        //$driver = $connection->getDriver();
        //$driver->disconnect();

        //TableRegistry::getTableLocator()->destroy('Systemsettings');

        for ($i = 1; $i < $gearmanConfig['worker']; $i++) {
            $io->info(__('GearmanWorker: Fork new worker child'));
            $pid = pcntl_fork();
            if (!$pid) {
                //I am a child process
                $this->parentProcess = false;

                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');


                $this->loop();
            }
            $this->childPids[] = $pid;
            unset($pid);
        }

        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        pcntl_signal(SIGCHLD, [$this, 'sig_handler']);
        $this->loop();
    }

    /**
     * Loops until receives SIGTERM or SIGINT
     */
    public function loop() {
        $gearmanConfig = Configure::read('gearman');

        $worker = new \GearmanWorker();
        $worker->addServer($gearmanConfig['address'], $gearmanConfig['port']);

        $worker->addFunction('oitc_gearman', [$this, 'runJob']);
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

    /**
     * @param \GearmanJob $job
     * @return string
     * @throws \Exception
     */
    public function runJob($job) {
        $this->jobIdelCounter = 0;

        $payload = $job->workload();

        $payload = @unserialize($payload);

        if (!is_array($payload)) {
            Log::error('GearmanWorker received corrupted data: ' . (string)$payload);
            return serialize(['error' => 'Corrupt data']);
        }

        $return = [];

        // Avoid "MySQL server has gone away"
        /** @var SystemsettingsTable $SystemsettingsTable */
        TableRegistry::getTableLocator()->destroy('Systemsettings');
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        //$connection = $SystemsettingsTable->getConnection();
        //$connection->disconnect();
        //$connection->connect();


        switch ($payload['task']) {
            //@todo implement me
            /*
            case 'CheckMKSNMP':
                $_task = new TaskCollection($this);
                $MkNagiosExportTask = $_task->load('MkModule.MkModuleNagiosExport');

                //Generate check_mk config file, to run SNMP scan
                $MkNagiosExportTask->init();
                if ($payload['snmp_version'] < 3) {
                    //SNMP V1 and V2
                    $MkNagiosExportTask->createConfigFiles($payload['hostuuid'], [
                        'for_snmp_scan'  => true,
                        'host_address'   => $payload['host_address'],
                        'snmp_version'   => $payload['snmp_version'],
                        'snmp_community' => $payload['snmp_community'],
                    ], false);
                } else {
                    //SNMP V3
                    $MkNagiosExportTask->createConfigFiles($payload['hostuuid'], [
                        'for_snmp_scan' => true,
                        'host_address'  => $payload['host_address'],
                        'snmp_version'  => $payload['snmp_version'],
                        'v3'            => [
                            'security_level'       => $payload['v3']['security_level'],
                            'hash_algorithm'       => $payload['v3']['hash_algorithm'],
                            'username'             => $payload['v3']['username'],
                            'password'             => $payload['v3']['password'],
                            'encryption_algorithm' => $payload['v3']['encryption_algorithm'],
                            'encryption_password'  => $payload['v3']['encryption_password'],
                        ],
                    ], false);
                }

                if ($payload['satellite_id'] !== '0' && is_dir(OLD_APP . 'Plugin' . DS . 'DistributeModule')) {
                    $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
                    $satellite = $this->Satellite->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Satellite.id' => $payload['satellite_id']
                        ]
                    ]);
                    if (empty($satellite)) {
                        break;
                    }

                    try {
                        $options = [
                            'allow_redirects' => true,
                            'verify'          => false,
                            'stream_context'  => [
                                'ssl' => [
                                    'allow_self_signed' => true,
                                    'verify'            => false
                                ]
                            ]
                        ];
                        $Client = new Client($options);
                        $response = $Client->request('GET', sprintf(
                                'https://%s/nagios/discover/dump-host/%s',
                                $satellite['Satellite']['address'],
                                $payload['hostUuid'])
                        );

                        $output = json_decode($response->getBody()->getContents(), true);
                    } catch (\Exception $e) {
                        $output = [];
                        error_log($e->getMessage());
                    }
                } else {
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -II -v ' . escapeshellarg($payload['hostuuid']), $output, $returncode);
                    $output = null;
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -D ' . escapeshellarg($payload['hostuuid']), $output, $returncode);
                    $this->deleteMkAutochecks();
                    exec(sprintf(
                        'chown %s:%s %s -R',
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.USER']),
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.GROUP']),
                        escapeshellarg($this->_systemsettings['CHECK_MK']['CHECK_MK.VAR'])
                    ));
                }

                $return = $output;
                break;

            case 'CheckMKListChecks':
                if ($payload['satellite_id'] !== '0' && is_dir(OLD_APP . 'Plugin' . DS . 'DistributeModule')) {
                    $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
                    $satellite = $this->Satellite->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Satellite.id' => $payload['satellite_id']
                        ]
                    ]);
                    if (empty($satellite)) {
                        break;
                    }

                    try {
                        $options = [
                            'allow_redirects' => true,
                            'verify'          => false,
                            'stream_context'  => [
                                'ssl' => [
                                    'allow_self_signed' => true,
                                    'verify'            => false
                                ]
                            ]
                        ];
                        $Client = new Client($options);
                        $response = $Client->request('GET', sprintf(
                            'https://%s/nagios/discover/check-types',
                            $satellite['Satellite']['address']
                        ));

                        $output = json_decode($response->getBody()->getContents(), true);
                    } catch (\Exception $e) {
                        $output = [];
                        error_log($e->getMessage());
                    }

                } else {
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -L', $output);
                    exec(sprintf(
                        'chown %s:%s %s -R',
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.USER']),
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.GROUP']),
                        escapeshellarg($this->_systemsettings['CHECK_MK']['CHECK_MK.VAR'])
                    ));
                }
                $return = $output;
                unset($output);
                break;

            case 'CheckMkDiscovery':
                //Generate .mk config to run -II and -D
                $_task = new TaskCollection($this);
                $MkNagiosExportTask = $_task->load('MkModule.MkModuleNagiosExport');
                $MkNagiosExportTask->init();
                $MkNagiosExportTask->createConfigFiles($payload['hostUuid'], [
                    'for_snmp_scan' => true, //Hacky but works -.-
                    'host_address'  => $payload['hostaddress'],
                ], false);

                if ($payload['satellite_id'] !== '0' && is_dir(OLD_APP . 'Plugin' . DS . 'DistributeModule')) {
                    $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
                    $satellite = $this->Satellite->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Satellite.id' => $payload['satellite_id']
                        ]
                    ]);
                    if (empty($satellite)) {
                        break;
                    }

                    try {
                        $options = [
                            'allow_redirects' => true,
                            'verify'          => false,
                            'stream_context'  => [
                                'ssl' => [
                                    'allow_self_signed' => true,
                                    'verify'            => false
                                ]
                            ]
                        ];
                        $Client = new Client($options);
                        $response = $Client->request('GET', sprintf(
                                'https://%s/nagios/discover/dump-host/%s',
                                $satellite['Satellite']['address'],
                                $payload['hostUuid'])
                        );

                        $output = json_decode($response->getBody()->getContents(), true);
                    } catch (\Exception $e) {
                        $output = [];
                        error_log($e->getMessage());
                    }

                } else {
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -II -v ' . escapeshellarg($payload['hostUuid']), $output, $returncode);
                    $output = null;
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -D ' . escapeshellarg($payload['hostUuid']), $output, $returncode);
                    $this->deleteMkAutochecks();
                    exec(sprintf(
                        'chown %s:%s %s -R',
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.USER']),
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.GROUP']),
                        escapeshellarg($this->_systemsettings['CHECK_MK']['CHECK_MK.VAR'])
                    ));
                }

                $return = $output;
                unset($output);
                break;

            case 'CheckMKProcesses':
                if ($payload['satellite_id'] !== '0' && is_dir(OLD_APP . 'Plugin' . DS . 'DistributeModule')) {
                    $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
                    $satellite = $this->Satellite->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Satellite.id' => $payload['satellite_id']
                        ]
                    ]);
                    if (empty($satellite)) {
                        break;
                    }

                    try {
                        $options = [
                            'allow_redirects' => true,
                            'verify'          => false,
                            'stream_context'  => [
                                'ssl' => [
                                    'allow_self_signed' => true,
                                    'verify'            => false
                                ]
                            ]
                        ];
                        $Client = new Client($options);
                        $response = $Client->request('GET', sprintf(
                                'https://%s/nagios/discover/raw-info/%s',
                                $satellite['Satellite']['address'],
                                $payload['hostUuid'])
                        );

                        $output = json_decode($response->getBody()->getContents(), true);
                    } catch (\Exception $e) {
                        $output = [];
                        error_log($e->getMessage());
                    }

                } else {
                    exec($this->_systemsettings['CHECK_MK']['CHECK_MK.BIN'] . ' -d ' . escapeshellarg($payload['hostUuid']), $output);
                    exec(sprintf(
                        'chown %s:%s %s -R',
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.USER']),
                        escapeshellarg($this->_systemsettings['MONITORING']['MONITORING.GROUP']),
                        escapeshellarg($this->_systemsettings['CHECK_MK']['CHECK_MK.VAR'])
                    ));
                }

                $return = $output;
                unset($output);
                break;
            */
            case 'create_apt_config':
                $LsbRelease = new LsbRelease();
                $repo = '';
                $usesAuthConfig = false;
                switch ($LsbRelease->getCodename()) {
                    case 'xenial':
                        $repo = 'packages.openitcockpit.io/openitcockpit/xenial/nightly xenial main';
                        break;
                    case 'bionic':
                        $repo = 'packages.openitcockpit.io/openitcockpit/bionic/nightly bionic main';
                        $usesAuthConfig = true;
                        break;
                    case 'buster':
                        $repo = 'packages.openitcockpit.io/openitcockpit/buster/nightly buster main';
                        break;
                    case 'stretch':
                        $repo = 'packages.openitcockpit.io/openitcockpit/stretch/nightly stretch main';
                        break;
                }
                $file = fopen('/etc/apt/sources.list.d/openitcockpit.list', 'w+');
                if ($usesAuthConfig) {

                    if (!is_dir('/etc/apt/auth.conf.d')) {
                        mkdir('/etc/apt/auth.conf.d');
                    }

                    //bionic and newer
                    fwrite($file, 'deb https://' . $repo . '  main' . PHP_EOL);

                    $authFile = fopen('/etc/apt/auth.conf.d/openitcockpit.conf', 'w+');
                    fwrite($authFile, 'machine packages.openitcockpit.io login secret password ' . $payload['key'] . PHP_EOL);
                    fclose($authFile);

                } else {
                    // trusty / xenial
                    fwrite($file, 'deb https://secret:' . $payload['key'] . '@' . $repo . '  main' . PHP_EOL);
                }
                fclose($file);
                unset($payload);
                exec('apt-get update');
                break;

            case 'createHostDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->setHostDowntime($payload);
                break;

            case 'createHostgroupDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->setHostgroupDowntime($payload);
                break;

            case 'createServiceDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->setServiceDowntime($payload);
                break;

            case 'createContainerDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->setContainerDowntime($payload);
                break;

            case 'deleteHostDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->deleteHostDowntime($payload['internal_downtime_id']);
                break;

            case 'deleteServiceDowntime':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->deleteServiceDowntime($payload['internal_downtime_id']);
                break;

            //Called by NagiosModule/CmdController/submit
            case 'cmd_external_command':
                $ExternalCommands = new ExternalCommands($this->naemonExternalCommandsFile);
                $ExternalCommands->runCmdCommand($payload);
                break;

            case 'export_start_export':
                //Start the export over a gearman worker to avoid max_execution_time issues
                $this->launchExport($payload['backup']);
                break;

            case 'export_delete_old_configuration':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->deleteAllConfigfiles();
                $return = ['task' => $payload['task']];
                break;

            case 'export_create_default_config':
                $NagiosConfigDefaults = new NagiosConfigDefaults();
                $NagiosConfigDefaults->execute();
                $return = ['task' => $payload['task']];
                break;

            case 'export_hosttemplates':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportHosttemplates();
                $return = ['task' => $payload['task']];
                break;

            case 'export_hosts':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportHosts();
                $return = ['task' => $payload['task']];
                break;

            case 'export_commands':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportCommands();
                $return = ['task' => $payload['task']];
                break;

            case 'export_contacts':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportContacts();
                $return = ['task' => $payload['task']];
                break;

            case 'export_contactgroups':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportContactgroups();
                $return = ['task' => $payload['task']];
                break;

            case 'export_timeperiods':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportTimeperiods();
                $return = ['task' => $payload['task']];
                break;

            case 'export_hostgroups':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportHostgroups();
                $return = ['task' => $payload['task']];
                break;

            case 'export_hostescalations':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportHostescalations();
                $return = ['task' => $payload['task']];
                break;

            case 'export_servicetemplates':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportServicetemplates();
                $return = ['task' => $payload['task']];
                break;
            case 'export_services':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportServices();
                $return = ['task' => $payload['task']];
                break;

            case 'export_serviceescalations':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportServiceescalations();
                $return = ['task' => $payload['task']];
                break;
            case 'export_servicegroups':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportServicegroups();
                $return = ['task' => $payload['task']];
                break;

            case 'export_hostdependencies':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportHostdependencies();
                $return = ['task' => $payload['task']];
                break;

            case 'export_servicedependencies':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportServicedependencies();
                $return = ['task' => $payload['task']];
                break;

            case 'export_userdefinedmacros':
                $NagiosConfigGenerator = new NagiosConfigGenerator();
                $NagiosConfigGenerator->exportMacros();
                $return = ['task' => $payload['task']];
                break;

            case 'export_verify_config':

                /** @var SystemsettingsTable $SystemsettingsTable */
                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
                $systemsettings = $SystemsettingsTable->findAsArray();

                $naemonBin = Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin');
                $naemonCfg = Configure::read('nagios.nagios_cfg');

                $cmd = sprintf(
                    'sudo -u %s %s -v %s',
                    escapeshellarg($systemsettings['MONITORING']['MONITORING.USER']),
                    $naemonBin,
                    $naemonCfg
                );

                exec($cmd, $output, $returncode);
                $return = [
                    'output'     => $output,
                    'returncode' => $returncode,
                ];
                break;

            case 'export_sync_sat_config':
                $NagiosConfigGenerator = new NagiosConfigGenerator();

                /** @var ExportsTable $ExportsTable */
                $ExportsTable = TableRegistry::getTableLocator()->get('Exports');
                $entity = $ExportsTable->find()
                    ->where([
                        'task' => 'export_sync_sat_config_' . $payload['Satellite']['id']
                    ])
                    ->first();
                if (!empty($entity)) {
                    $entity->set('finished', 1);

                    $SatelliteCopy = new SatelliteCopy($payload['Satellite']);
                    $copyResult = $SatelliteCopy->copy();
                    $entity->set('successfully', (int)$copyResult);

                    $ExportsTable->save($entity);
                }
                unset($entity);
                $return = ['task' => $payload['task']];
                break;

            case 'idoit_sync':
                //@todo implement me
                //$this->Synchronisation->runImport($payload['isCron'], $payload['authUser']);
                break;

            case 'make_sql_backup':
                $filename = Configure::read('nagios.export.backupTarget') . '/' . $payload['filename'];
                if (file_exists($filename)) {
                    $return = [
                        'output'     => [
                            'File already exists!'
                        ],
                        'returncode' => 1,
                    ];
                    break;
                }

                $MysqlBackup = new Backup();
                $return = $MysqlBackup->createMysqlDump($filename);
                exec('touch /opt/openitc/nagios/backup/finishBackup.txt.sql');
                break;

            case 'restore_sql_backup':
                $filename = $payload['path'];
                if (!file_exists($filename)) {
                    $return = [
                        'output'     => [
                            'File does not exists!'
                        ],
                        'returncode' => 1,
                    ];
                    break;
                }

                $MysqlBackup = new Backup();
                $return = $MysqlBackup->restoreMysqlDump($filename);
                exec('touch /opt/openitc/nagios/backup/finishRestore.txt.sql');
                break;

            case 'delete_sql_backup':
                $backup_files = [];
                $files = scandir("/opt/openitc/nagios/backup/");
                foreach ($files as $file) {
                    if (strstr($file, ".sql")) {
                        $backup_files["/opt/openitc/nagios/backup/" . $file] = $file;
                    }
                }

                $fileToDelete = $payload['path'];

                $return = ['success' => false];
                if (isset($backup_files[$fileToDelete]) && is_file($fileToDelete)) {
                    $return = ['success' => unlink($fileToDelete)];
                }

                break;

            case 'check_background_processes':
                /** @var SystemsettingsTable $SystemsettingsTable */
                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
                $systemsetting = $SystemsettingsTable->findAsArray();
                $errorRedirect = ' 2> /dev/null';

                $state = [
                    'isNagiosRunning'           => false,
                    'isNdoRunning'              => false,
                    'isStatusengineRunning'     => false,
                    'isNpcdRunning'             => false,
                    'isOitcCmdRunning'          => false,
                    'isSudoServerRunning'       => false,
                    'isPhpNstaRunning'          => false,
                    'isGearmanWorkerRunning'    => true,
                    'isPushNotificationRunning' => false,
                    'isNodeJsServerRunning'     => false
                ];

                exec($systemsetting['MONITORING']['MONITORING.STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isNagiosRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.NDO_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isNdoRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.STATUSENIGNE_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isStatusengineRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.NPCD_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isNpcdRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.OITC_CMD_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isOitcCmdRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.SUDO_SERVER_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isSudoServerRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.PHPNSTA_STATUS'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isPhpNstaRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.PUSH_NOTIFICATION'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isPushNotificationRunning'] = true;
                }

                exec($systemsetting['INIT']['INIT.NODEJS_SERVER'] . $errorRedirect, $output, $returncode);
                if ($returncode == 0) {
                    $state['isNodeJsServerRunning'] = true;
                }

                $return = $state;
                break;

            case 'NmapDiscovery':
                // @todo implement me
                /*
                $nmapScan = ClassRegistry::init('DiscoveryModule.NmapScan');
                if (!empty($payload['scanId'])) {
                    $nmapScan->id = $payload['scanId'];
                }

                $discoveryResult = null;
                if (!empty($payload['path']) && !empty($payload['filename']) && !empty($payload['address']) && isset($payload['bitmask'])) {
                    if ($payload['singleHost']) {
                        exec("nmap --unprivileged -oX " . escapeshellarg($payload['path'] . $payload['filename']) . " " . escapeshellarg($payload['address']), $output, $returncode);
                    } else {
                        $CIDRAddress = $payload['address'] . '/' . $payload['bitmask'];
                        if (!empty($payload['hostsWithoutServices'])) {
                            exec("nmap --unprivileged -sn -oX " . escapeshellarg($payload['path'] . $payload['filename']) . " " . escapeshellarg($CIDRAddress), $output, $returncode);
                        } else {
                            exec("nmap --unprivileged -oX " . escapeshellarg($payload['path'] . $payload['filename']) . " " . escapeshellarg($CIDRAddress), $output, $returncode);
                        }

                    }

                }

                $this->Systemsetting->getDatasource()->reconnect();
                $nmapScan->saveField('finished', 1);

                if ($returncode == 0) {
                    $nmapScan->saveField('successful', 1);
                }

                $return = true;
                */
                break;
        }

        return serialize($return);
    }

    /**
     * @param int $createBackup
     * @throws \Exception
     */
    public function launchExport($createBackup = 1) {
        $NagiosConfigGenerator = new NagiosConfigGenerator();
        $successfully = 1;
        /** @var ExportsTable $ExportsTable */
        $ExportsTable = TableRegistry::getTableLocator()->get('Exports');
        $ExportsTable->deleteAll([]);

        $ExportsTable->save($ExportsTable->newEntity([
            'task' => 'export_started',
            'text' => __('Started refresh of monitoring configuration'),
        ]));


        if ($createBackup == 1) {
            $entity = $ExportsTable->newEntity([
                'task' => 'export_create_backup',
                'text' => __('Create Backup of current configuration')
            ]);
            $ExportsTable->save($entity);


            $backupSrc = new Folder(Configure::read('nagios.export.backupSource'));

            $backupTarget = Configure::read('nagios.export.backupTarget') . '/' . date('d-m-Y_H-i-s');

            if (!is_dir(Configure::read('nagios.export.backupTarget'))) {
                mkdir(Configure::read('nagios.export.backupTarget'));
            }

            if (!is_dir($backupTarget)) {
                mkdir($backupTarget);
            }
            $backupSrc->copy($backupTarget);

            $filename = "export_oitc_bkp_" . date("Y-m-d_His") . ".sql";
            $MysqlBackup = new Backup();
            $MysqlBackup->createMysqlDump(
                Configure::read('nagios.export.backupTarget') . '/' . $filename
            );

            $entity->set('finished', 1);
            $entity->set('successfully', 1);
            $ExportsTable->save($entity);
            unset($entity);
        }

        $gearmanConfig = Configure::read('gearman');
        $gearmanClient = new \GearmanClient();
        $gearmanClient->addServer($gearmanConfig['address'], $gearmanConfig['port']);
        //This callback gets called, for any finished export task (like hosttemplates, services etc...)
        $gearmanClient->setCompleteCallback([$this, 'exportCallback']);

        //Delete old configuration
        $entity = $ExportsTable->newEntity([
            'task' => 'export_delete_old_configuration',
            'text' => __('Delete old configuration')
        ]);
        $ExportsTable->save($entity);
        $gearmanClient->doNormal("oitc_gearman", serialize(['task' => 'export_delete_old_configuration']));
        $entity->set('finished', 1);
        $entity->set('successfully', 1);
        $ExportsTable->save($entity);
        unset($entity);

        $entity = $ExportsTable->newEntity([
            'task' => 'before_export_external_tasks',
            'text' => __('Execute pre export tasks')
        ]);
        $ExportsTable->save($entity);
        $NagiosConfigGenerator->beforeExportExternalTasks();
        $entity->set('finished', 1);
        $entity->set('successfully', 1);
        $ExportsTable->save($entity);
        unset($entity);

        //Define all tasks, we can do in parallel
        $tasks = [
            'export_create_default_config' => [
                'text' => __('Create default configuration'),
            ],
            'export_hosttemplates'         => [
                'text' => __('Create hosttemplate configuration'),
            ],
            'export_hosts'                 => [
                'text' => __('Create host configuration'),
            ],
            'export_commands'              => [
                'text' => __('Create command configuration'),
            ],
            'export_contacts'              => [
                'text' => __('Create contact configuration'),
            ],
            'export_contactgroups'         => [
                'text' => __('Create contact group configuration'),
            ],
            'export_timeperiods'           => [
                'text' => __('Create timeperiod configuration'),
            ],
            'export_hostgroups'            => [
                'text' => __('Create host group configuration'),
            ],
            'export_hostescalations'       => [
                'text' => __('Create host escalation configuration'),
            ],
            'export_servicetemplates'      => [
                'text' => __('Create servicetemplate configuration'),
            ],
            'export_services'              => [
                'text' => __('Create service configuration'),
            ],
            'export_serviceescalations'    => [
                'text' => __('Create service escalation configuration'),
            ],
            'export_servicegroups'         => [
                'text' => __('Create service group configuration'),
            ],
            'export_hostdependencies'      => [
                'text' => __('Create host dependency configuration'),
            ],
            'export_servicedependencies'   => [
                'text' => __('Create service dependency configuration'),
            ],
            'export_userdefinedmacros'     => [
                'text' => __('Export user defined macros'),
            ],
        ];

        foreach ($tasks as $taskName => $task) {
            if (isset($task['options'])) {
                //Task with special options
                $gearmanClient->addTask("oitc_gearman", serialize(['task' => $taskName, 'options' => $task['options']]));
            } else {
                //Normal task
                $gearmanClient->addTask("oitc_gearman", serialize(['task' => $taskName]));
            }
            $entity = $ExportsTable->newEntity([
                'task' => $taskName,
                'text' => $task['text']
            ]);
            $ExportsTable->save($entity);
            unset($entity);
        }

        //Run all tasks
        $gearmanClient->runTasks(); //Blocks until all tasks are done

        //runTasks() may be block for a long time
        // Avoid "MySQL server has gone away"
        //$connection = $SystemsettingsTable->getConnection();
        //$connection->disconnect();
        //$connection->connect();

        //Export done
        $entity = $ExportsTable->newEntity([
            'task' => 'after_export_external_tasks',
            'text' => __('Execute post export tasks')
        ]);
        $ExportsTable->save($entity);
        $NagiosConfigGenerator->afterExportExternalTasks();
        $entity->set('finished', 1);
        $entity->set('successfully', 1);
        $ExportsTable->save($entity);
        unset($entity);

        //Verify new configuration
        $verifyEntity = $ExportsTable->newEntity([
            'task' => 'export_verify_new_configuration',
            'text' => __('Verifying new configuration')
        ]);
        $ExportsTable->save($verifyEntity);
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        $naemonBin = Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin');
        $naemonCfg = Configure::read('nagios.nagios_cfg');
        $cmd = sprintf(
            'sudo -u %s %s -v %s',
            escapeshellarg($systemsettings['MONITORING']['MONITORING.USER']),
            $naemonBin,
            $naemonCfg
        );
        $output = null;
        exec($cmd, $output, $returncode);
        $verifyEntity->set('finished', 1);
        if ($returncode === 0) {
            //New configuration is valid :-)

            $verifyEntity->set('successfully', 1);
            $ExportsTable->save($verifyEntity);

            //Reloading the monitoring system

            //Check if Naemon/Nagios is running.
            //If Nagios is running, we reload the config, if not we need to restart
            $entity = $ExportsTable->newEntity([
                'task' => 'is_monitoring_engine_running',
                'text' => __('Check if monitoring engine is running')
            ]);
            $ExportsTable->save($entity);
            exec($systemsettings['MONITORING']['MONITORING.STATUS'], $statusOutput, $statusRc);
            $entity->set('finished', 1);
            $entity->set('successfully', 1);
            $ExportsTable->save($entity);
            unset($entity);

            $isMonitoringRunning = false;
            if ($statusRc === 0) {
                //Nagios/Naemon is running (reload)
                $entity = $ExportsTable->newEntity([
                    'task' => 'export_reload_monitoring',
                    'text' => __('Reloading monitoring engine')
                ]);
                $ExportsTable->save($entity);
                exec($systemsettings['MONITORING']['MONITORING.RELOAD'], $reloadOutput, $reloadRc);
                $entity->set('finished', 1);
                $entity->set('successfully', 0);
                if ($reloadRc === 0) {
                    $entity->set('successfully', 1);
                    $isMonitoringRunning = true;
                }
                $ExportsTable->save($entity);
                unset($entity);
            } else {
                //Nagios/Naemon is stopped (restart)
                $entity = $ExportsTable->newEntity([
                    'task' => 'export_restart_monitoring',
                    'text' => __('Restarting monitoring engine')
                ]);
                $ExportsTable->save($entity);
                exec($systemsettings['MONITORING']['MONITORING.RESTART'], $restartOutput, $restartRc);
                $entity->set('finished', 1);
                $entity->set('successfully', 0);
                if ($restartRc === 0) {
                    $entity->set('successfully', 1);
                    $isMonitoringRunning = true;
                }
                $ExportsTable->save($entity);
                unset($entity);
            }


            if ($isMonitoringRunning) {
                //Run After Export command
                $entity = $ExportsTable->newEntity([
                    'task' => 'export_after_export_command',
                    'text' => __('Execute after export command')
                ]);
                $output = null;
                exec($systemsettings['MONITORING']['MONITORING.AFTER_EXPORT'], $output, $returncode);

                // Avoid "MySQL server has gone away"
                $connection = $SystemsettingsTable->getConnection();
                $connection->disconnect();
                $connection->connect();

                $entity->set('finished', 1);
                $entity->set('successfully', 0);
                if ($returncode == 0) {
                    $entity->set('successfully', 1);
                    $this->distributedMonitoringAfterExportCommand();
                } else {
                    $successfully = 0;
                }

                $ExportsTable->save($entity);
                unset($entity);

            } else {
                $successfully = 0;
            }
        } else {
            //Error with new configuration :-(
            $successfully = 0;
            $verifyEntity->set('successfully', 0);
            $ExportsTable->save($verifyEntity);
        }

        //Export, reload/restart and after export done
        $entity = $ExportsTable->newEntity([
            'task'         => 'export_finished',
            'text'         => __('Refresh of configuration finished'),
            'finished'     => 1,
            'successfully' => $successfully
        ]);
        $ExportsTable->save($entity);
        unset($entity);

        //Mark export as finished
        $entity = $ExportsTable->find()
            ->where([
                'task' => 'export_started'
            ])
            ->first();

        if ($entity) {
            $entity->set('finished', 1);
            $entity->set('successfully', $successfully);
            $ExportsTable->save($entity);
        }

        if ($successfully) {
            if (Plugin::isLoaded('DistributeModule')) {
                /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
                $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
                $SatellitesTable->disableAllInstanceConfigSyncs();
            }
        }

    }

    /**
     * Save status to database
     * @param \GearmanTask $task
     */
    public function exportCallback($task) {
        $result = unserialize($task->data());
        if (!isset($result['task'])) {
            Log::error('Export result has no "task" key ' . serialize($result));
            return;
        }

        if ($result['task'] !== 'export_sync_sat_config') {
            /** @var ExportsTable $ExportsTable */
            $ExportsTable = TableRegistry::getTableLocator()->get('Exports');

            // Avoid "MySQL server has gone away"
            $connection = $ExportsTable->getConnection();
            $connection->disconnect();
            $connection->connect();

            $entity = $ExportsTable->find()
                ->where([
                    'task' => $result['task']
                ])
                ->first();

            if ($entity) {
                $entity->set('finished', 1);
                $entity->set('successfully', 1);
                $ExportsTable->save($entity);
            }
        }
    }

    public function distributedMonitoringAfterExportCommand() {
        if (Plugin::isLoaded('DistributeModule')) {
            //DistributeModule is installed and loaded
            /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

            $syncType = $SystemsettingsTable->getSystemsettingByKey('MONITORING.SINGLE_INSTANCE_SYNC');
            if ($syncType->get('value') === '1') {
                // Only synchronize new config to marked satellites
                $query = $SatellitesTable->find()
                    ->where([
                        'Satellites.sync_instance' => 1
                    ])
                    ->all();
            } else {
                // Synchronize new config to ALL satellites
                $query = $SatellitesTable->find()
                    ->all();
            }

            $satellites = $query->toArray();

            $gearmanConfig = Configure::read('gearman');

            $gearmanClient = new \GearmanClient();
            $gearmanClient->addServer($gearmanConfig['address'], $gearmanConfig['port']);
            // This callback gets called, for any finished export task (like hosts, hosttemplates, services etc...)
            // to save success status to the database.
            $gearmanClient->setCompleteCallback([$this, 'exportCallback']);

            if (!empty($satellites)) {
                /** @var ExportsTable $ExportsTable */
                $ExportsTable = TableRegistry::getTableLocator()->get('Exports');

                foreach ($satellites as $satellite) {
                    /** @var \DistributeModule\Model\Entity\Satellite $satellite */

                    $entity = $ExportsTable->newEntity([
                        'task' => 'export_sync_sat_config_' . $satellite->get('id'),
                        'text' => __('Copy new monitoring configuration for Satellite [' . $satellite->get('id') . '] ' . $satellite->get('name'))
                    ]);
                    $ExportsTable->save($entity);

                    $gearmanClient->addTask("oitc_gearman", serialize([
                        'task'      => 'export_sync_sat_config',
                        'Satellite' => $satellite->toArray()
                    ]));
                }
                $gearmanClient->runTasks();
            }
            // Avoid "MySQL server has gone away"
            $connection = $SystemsettingsTable->getConnection();
            $connection->disconnect();
            $connection->connect();

        }
    }

    /**
     * The signal handler is called by the linux kernel or pcntl_signal_dispatch and will handel singals - obviously
     * Kill forked child processes, delete pidfile and exit on SIGTERM and SIGINT
     *
     * @param int $signo , the signal caught by pcntl_signal_dispatch()
     *
     */
    public function sig_handler($signo) {
        switch ($signo) {
            case SIGTERM:
            case SIGINT:
                if ($this->parentProcess) {
                    // I'm the parent process
                    // Killing all my child processes :'(
                    foreach ($this->childPids as $pid) {
                        posix_kill($pid, SIGTERM);
                    }

                    foreach ($this->childPids as $pid) {
                        pcntl_waitpid($pid, $status);
                    }

                    $gearmanConfig = Configure::read('gearman');
                    $pidfile = $gearmanConfig['pidfile'];
                    if (file_exists($pidfile)) {
                        unlink($pidfile);
                    }
                }
                exit(0);
                break;

            case SIGCHLD:
                // Child died
                // Get the dead child pid and clean up the zombie process
                if ($this->parentProcess) {
                    $deadChildPid = pcntl_wait($status, WNOHANG);
                    $pids = [];
                    foreach ($this->childPids as $pid) {
                        if ($pid != $deadChildPid) {
                            $pids[] = $pid;
                        }
                    }
                    $this->childPids = $pids;
                    unset($pids);
                }
                break;
        }
    }

    /**
     * @return bool
     */
    private function deleteMkAutochecks() {
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        $autochecksPath = $systemsettings['CHECK_MK']['CHECK_MK.VAR'] . 'autochecks';
        if (!is_dir($autochecksPath)) {
            return false;
        }
        $files = [];
        foreach (new \DirectoryIterator($autochecksPath) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $files[] = $fileInfo->getRealPath();
            }
        }
        if (empty($files)) {
            return true;
        }

        $Filesystem = new Filesystem();
        $Filesystem->remove($files);

        return true;
    }
}
