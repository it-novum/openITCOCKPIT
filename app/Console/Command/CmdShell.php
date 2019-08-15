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

use Cake\ORM\TableRegistry;

class CmdShell extends AppShell {

    public $uses = [
        'Host',
        'Service',
        'Servicetemplates',
        'Systemsetting',
    ];

    public $serviceCache = [];
    public $monitoringCmdCache = [];
    public $uuidRegex = null;
    public $cmdPipe = null;

    public $logLevel = 0;
    public $logfile = null;

    /**
     * Main function get called by the AppShell of cake and start the programm
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function main() {
        App::uses('UUID', 'Lib');
        $this->uuidRegex = \itnovum\openITCOCKPIT\Core\UUID::regex();
        define('LOGLEVEL_INFO', 1);
        define('LOGLEVEL_WARNING', 2);
        define('LOGLEVEL_FATAL', 4);
        define('LOGLEVEL_DEBUG', 8);

        Configure::load('cmddaemon');

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();

        $this->logLevel = Configure::read('loglevel');

        $this->createPipe();
        $this->buildServiceCache();
        $this->daemonize();

        /*$this->processCommand("[1426072654] ACKNOWLEDGE_HOST_PROBLEM;host1;2;1;1;Some One;Some Acknowledgement Comment\n");
        $this->processCommand("[1426072654] RESTART_PROGRAM\n");
        $this->processCommand("[1426072654] ACKNOWLEDGE_SVC_PROBLEM;host1;service1;2;1;1;Some One;Some Acknowledgement Comment\n");
        $this->processCommand("[1426072654] DEL_HOST_DOWNTIME;1\n");
        $this->processCommand("[".time()."] SCHEDULE_FORCED_SVC_CHECK;host1;Random;".time()."\n");
        $this->processCommand("[".time()."] SCHEDULE_FORCED_SVC_CHECK;da6defe4-3a44-4df2-8195-25de55ad9379;Random;".time()."\n");
        $this->processCommand("[".time()."] SCHEDULE_FORCED_SVC_CHECK;da6defe4-3a44-4df2-8195-25de55ad9379;c6466a47-8b67-49d9-8e6a-897dfa720351;".time()."\n");
        $this->processCommand("[1426148428] SCHEDULE_FORCED_SVC_CHECK;bdb0558c-7c64-49ca-8f26-4ccd88a8c887;tenplate_args;1426148428\n"); */
    }

    /**
     * Create an cache with all service names as an array.
     * Array key is Host-UUID + Service Name as md5 hash
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function buildServiceCache() {
        $this->_log('Info: Build up service cache', LOGLEVEL_INFO);
        $services = $this->Service->find('all', [
            'fields'  => [
                'Service.id',
                'Service.uuid',
                'Service.name',
            ],
            'contain' => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name',
                    ],
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                    ],
                ],
            ],
        ]);

        foreach ($services as $service) {
            $this->appendCache($service);
        }
        $this->_log('Info: Finished service cache', LOGLEVEL_INFO);
    }

    /**
     * Add an given $service to the serviceCache array
     *
     * @param    array $service you get from a cake $this->Service->find()
     *
     * @return    string the UUID of the service you want to add to the cache
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function appendCache($service) {
        $serviceName = $service['Service']['name'];
        if ($serviceName == null || $serviceName == '') {
            $serviceName = $service['Servicetemplate']['name'];
        }

        $hostAndServiceMd5 = md5($service['Host']['uuid'] . $serviceName);

        if (!isset($this->serviceCache[$hostAndServiceMd5])) {
            $this->serviceCache[$hostAndServiceMd5] = $service['Service']['uuid'];
        } else {
            $this->_log('Warning: Duplicate service ' . $serviceName . ' on host ' . $service['Host']['name'] . ' (' . $service['Host']['uuid'] . ')', LOGLEVEL_WARNING);
        }

        return $service['Service']['uuid'];
    }

    /**
     * Search the cache for a service by Host-UUID and the service description as string
     * If a service is not found in the cache, a SQL query is fired up to lookup the UUID of the service
     * and store the result in the cache
     *
     * @param    string $hostUuid the UUID of the host
     * @param    string $serviceDescription the service description (for humans not a uuid e.g. Lan-Ping)
     *
     * @return    string/bool return service uuid if found in cache, or false if no result is found
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function searchCache($hostUuid, $serviceDescription) {
        $hostAndServiceMd5 = md5($hostUuid . $serviceDescription);
        if (isset($this->serviceCache[$hostAndServiceMd5])) {
            return $this->serviceCache[$hostAndServiceMd5];
        }

        //Reconnect datasource to avoid "MySQL has gone away"
        $this->Systemsetting->getDatasource()->reconnect();

        $result = $this->Service->find('first', [
            'conditions' => [
                'Host.uuid'                                                    => $hostUuid,
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name)' => $serviceDescription,
            ],
            'contain'    => [
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                    ],
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name',
                    ],
                ],
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
            ],
        ]);

        if (empty($result)) {
            return false;
        }

        return $this->appendCache($result);
    }


    /**
     * Is a wrapper function you pass the external nagios command to
     * The command gets parsed, service description will be replaced and
     * the function give the external command to nagios.cmd
     *
     * @param    string $commandAsString the external command read von oitc.cmd
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function processCommand($commandAsString) {
        //Parse the external command read from oitc.cmd
        $commandAsArray = $this->parseCommand($commandAsString);
        if ($commandAsArray) {
            //Lookup human service_description to service uuid
            $NagCommandAsString = $this->buildCmdCommand($commandAsArray);
            $this->_log($NagCommandAsString, LOGLEVEL_DEBUG);
            //Pass the command to nagios.cmd
            $this->writeToMonitoringCmd($NagCommandAsString);
        } else {
            $this->_log('External command failed! Parser error', LOGLEVEL_FATAL);
        }
    }

    /**
     * Parse the external nagios command and return it as an array
     * array(
     *    'timestamp' => '[123456789]',
     *    'command'   => 'ACKNOWLEDGE_SVC_PROBLEM',
     *    'params'    => array(
     *        0 => 'host1',
     *        1 => 'service1',
     *        n => 'argn'
     *   )
     * )
     *
     * @param    string $command the external command you want to parse
     *
     * @return    array external command parsed as array
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function parseCommand($command = '') {
        $command = trim($command);

        //Get timestamp part
        $command = explode(' ', $command, 2);
        if (sizeof($command) !== 2) {
            $this->_log('Warning: External command parse error', LOGLEVEL_WARNING);

            return false;
        }

        $timestamp = $command[0];
        $command = explode(';', $command[1]);

        $externalCommand = $command[0];
        unset($command[0]);
        $params = $command;

        return [
            'timestamp' => $timestamp,
            'command'   => $externalCommand,
            'params'    => $params,
        ];

    }

    /**
     * Is a wrapper function you pass the external nagios command to
     * The command gets parsed, service description will be replaced and
     * the function give the external command to nagios.cmd
     *
     * @param    string $msg the message you want to log
     * @param    int $logLevel the log level of the message
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function _log($msg, $logLevel) {
        if ($this->logLevel & $logLevel) {
            $this->openLogfile();
            fwrite($this->logfile, $msg . PHP_EOL);
        }
    }


    /**
     * open the logfile of the daemon and save the resource to $this->logfile
     * @return    resource
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function openLogfile() {
        if (!is_resource($this->logfile)) {
            $this->logfile = fopen(Configure::read('logfile'), 'a+');
        }

        return $this->logfile;
    }

    /**
     * close the logfile
     * @return    bool
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function closeLogfile() {
        if (is_resource($this->logfile)) {
            fclose($this->logfile);

            return true;
        }

        return false;
    }

    /**
     * This function check wich command we got from the oitc.cmd. If it is a service command
     * the function check if ther is a human service_description in it and try to replace this
     * with the service uuid from the serviceCache.
     * All other commands get passed to nagios.cmd, the function will not touch them.
     *
     * @param    array $commandAsString retuned from $this->parseCommand()
     *
     * @return    string a external command wich can be piped to the nagios.cmd
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function buildCmdCommand($commandAsArray) {
        //We only catch service commands, to replace the service description with a uuid
        //All other commands gets passed to monitorings *.cmd file

        //We use a switch because may be we need some exceptions in future or so

        switch ($commandAsArray['command']) {
            case 'ACKNOWLEDGE_SVC_PROBLEM':
            case 'ADD_SVC_COMMENT':
            case 'CHANGE_CUSTOM_SVC_VAR':
            case 'CHANGE_MAX_SVC_CHECK_ATTEMPTS':
            case 'CHANGE_NORMAL_SVC_CHECK_INTERVAL':
            case 'CHANGE_RETRY_SVC_CHECK_INTERVAL':
            case 'CHANGE_SVC_CHECK_COMMAND':
            case 'CHANGE_SVC_CHECK_TIMEPERIOD':
            case 'CHANGE_SVC_EVENT_HANDLER':
            case 'CHANGE_SVC_MODATTR':
            case 'CHANGE_SVC_NOTIFICATION_TIMEPERIOD':
            case 'DEL_ALL_SVC_COMMENTS':
            case 'DELAY_SVC_NOTIFICATION':
            case 'DISABLE_PASSIVE_SVC_CHECKS':
            case 'DISABLE_SVC_CHECK':
            case 'DISABLE_SVC_EVENT_HANDLER':
            case 'DISABLE_SVC_FLAP_DETECTION':
            case 'DISABLE_SVC_NOTIFICATIONS':
            case 'ENABLE_PASSIVE_SVC_CHECKS':
            case 'ENABLE_SVC_CHECK':
            case 'ENABLE_SVC_EVENT_HANDLER':
            case 'ENABLE_SVC_FLAP_DETECTION':
            case 'ENABLE_SVC_NOTIFICATIONS':
            case 'PROCESS_SERVICE_CHECK_RESULT':
            case 'REMOVE_SVC_ACKNOWLEDGEMENT':
            case 'SCHEDULE_FORCED_SVC_CHECK':
            case 'SCHEDULE_SVC_CHECK':
            case 'SCHEDULE_SVC_DOWNTIME':
            case 'SEND_CUSTOM_SVC_NOTIFICATION':
            case 'SET_SVC_NOTIFICATION_NUMBER':
            case 'START_OBSESSING_OVER_SVC':
            case 'STOP_OBSESSING_OVER_SVC':

                /* Average external command syntax
                 * <hostname (uuid)>;<service_description (human name)>;<params>
                 */
                //We may be have a uuid, lets check this with preg_match
                if (strlen($commandAsArray['params'][2]) == 36) {
                    if (preg_match($this->uuidRegex, $commandAsArray['params'][2])) {
                        //Yes, we have a uuid, so we can submit the command without touch it
                        return $this->_buildNagCommand($commandAsArray);
                    }
                }

                // The user passed a service description, so we need to check, if we can find the uuid for this service
                $this->_log('Service description: ' . $commandAsArray['params'][2], LOGLEVEL_DEBUG);
                $serviceUuid = $this->searchCache($commandAsArray['params'][1], $commandAsArray['params'][2]);
                if ($serviceUuid) {
                    //Yes, we found a service uuid
                    $commandAsArray['params'][2] = $serviceUuid;

                    return $this->_buildNagCommand($commandAsArray);
                } else {
                    //could not find service uuid
                    $this->_log('Could not find service for given service description and host uuid', LOGLEVEL_FATAL);

                    return false;
                }

                break;

            default:
                return $this->_buildNagCommand($commandAsArray);
                break;
        }
    }

    /**
     * Build up a nagios command in the syntax for nagios.cmd
     *
     * @param    array $commandAsArray the external command you want to convert in a nagios command
     *
     * @return    string return the external command ready for nagios.cmd
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    private function _buildNagCommand($commandAsArray) {
        if (!empty($commandAsArray['params'])) {

            foreach ($commandAsArray['params'] as $key => $value) {
                $commandAsArray['params'][$key] = $this->fixUtf8($value);
            }

            return $commandAsArray['timestamp'] . ' ' . $commandAsArray['command'] . ';' . implode(';', $commandAsArray['params']) . "\n";
        }

        return $commandAsArray['timestamp'] . ' ' . $commandAsArray['command'] . "\n";
    }

    /**
     * Create the oitc.cmd named pipe file and store the resource in $this->cmdPipe
     * If a oitc.cmd exits the file will be deleted
     * @return    bool
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function createPipe() {
        $this->closePipe();

        $fileName = Configure::read('pipe');
        umask(0);
        if (!posix_mkfifo($fileName, Configure::read('mode'))) {
            $this->_log('FATAL ERROR: Could not create named pipe file!', LOGLEVEL_FATAL);
            exit(1);
        }

        chown($fileName, $this->_systemsettings['MONITORING']['MONITORING.USER']);
        chgrp($fileName, $this->_systemsettings['WEBSERVER']['WEBSERVER.GROUP']);

        $this->cmdPipe = fopen($fileName, 'r+');
        if ($this->cmdPipe) {
            return true;
        }

        return false;
    }

    /**
     * Close the named pipe file and close the file handler
     * @return    bool
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function closePipe() {
        if (is_resource($this->cmdPipe)) {
            fclose($this->cmdPipe);
        }

        if (file_exists(Configure::read('pipe'))) {
            if (unlink(Configure::read('pipe'))) {
                return true;
            }
        }

        if (!file_exists(Configure::read('pipe'))) {
            return true;
        }

        return false;
    }

    /**
     * Create a daemon, wich reads from the socket, process data and pass it to nagios.cmd
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function daemonize() {
        $sleep = Configure::read('sleep');
        declare(ticks=1);

        pcntl_signal(SIGTERM, [$this, 'sig_handler']);
        pcntl_signal(SIGINT, [$this, 'sig_handler']);

        stream_set_blocking($this->cmdPipe, false); //move over while?!

        $this->_log('Info: Finished daemonizing... [My PID = ' . getmypid() . ']', LOGLEVEL_INFO);

        while (true) {
            pcntl_signal_dispatch();
            $commands = [];
            while (true) {
                pcntl_signal_dispatch();

                //Read as long as you receive a text.
                //If fgets return false, we leave the while
                $read = fgets($this->cmdPipe);
                if ($read) {
                    $commands[] = $read;
                } else {
                    //gets returned false, exit while
                    break;
                }

            }
            foreach ($commands as $command) {
                $this->processCommand($command);
            }

            //Our daemon sleep, to save CPU time
            usleep($sleep);
        }
    }

    /**
     * The signal handler is called by the linux kernel or pcntl_signal_dispatch and will handel singals^^
     * Delete oitc.cmd and exit on SIGTERM and SIGINT
     *
     * @param    int $signo , the signal catched by pcntl_signal_dispatch()
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function sig_handler($signo) {
        $this->_log('Info: Received signal: ' . $signo, LOGLEVEL_INFO);
        switch ($signo) {
            case SIGTERM:
            case SIGINT:
                if (!$this->closePipe()) {
                    $this->_log('Warning: Could not delete my named pipe file!', LOGLEVEL_WARNING);
                }
                $this->_log('Astalavista baby...' . PHP_EOL, LOGLEVEL_INFO);
                $this->closeLogfile();
                exit(0);
                break;

            default:
                $this->_log('Warning: Signal not supported yet!', LOGLEVEL_WARNING);
                break;
        }
    }

    /**
     * Write a given command to nagios.cmd
     * If the cmd file not exists, commands gets cached in
     * $this->monitoringCmdCache array and passed to nagios.cmd
     * if its back
     *
     * @param    string $$commandAsString nagios external command
     *
     * @return    void
     * @author    Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since     3.0.1
     */
    public function writeToMonitoringCmd($commandAsString = '') {
        if (file_exists($this->_systemsettings['MONITORING']['MONITORING.CMD'])) {
            $cmd = fopen($this->_systemsettings['MONITORING']['MONITORING.CMD'], 'a+');
            fwrite($cmd, $commandAsString);
            if (!empty($this->monitoringCmdCache)) {
                //Process cache, becasue nagios.cmd was not there for a while...
                foreach ($this->monitoringCmdCache as $command) {
                    fwrite($cmd, $command);
                }
                $this->monitoringCmdCache = [];
            }
            fclose($cmd);
        } else {
            $this->monitoringCmdCache[] = $commandAsString;
        }
    }

    public function fixUtf8($str) {
        if (mb_detect_encoding($str) !== 'UTF-8') {
            return utf8_encode($str);
        }
        return $str;
    }

    /*
     * This are all known nagios external commands (11.03.2015)
     * I used TextMate (OSX) to create this switch in less than 60 secs...
     */
    /*public function buildCmdCommand($command = '', $params = []){
        switch(){
            case 'ACKNOWLEDGE_HOST_PROBLEM':
            break;
            case 'ACKNOWLEDGE_SVC_PROBLEM':
            break;
            case 'ADD_HOST_COMMENT':
            break;
            case 'ADD_SVC_COMMENT':
            break;
            case 'CHANGE_CONTACT_HOST_NOTIFICATION_TIMEPERIOD':
            break;
            case 'CHANGE_CONTACT_MODATTR':
            break;
            case 'CHANGE_CONTACT_MODHATTR':
            break;
            case 'CHANGE_CONTACT_MODSATTR':
            break;
            case 'CHANGE_CONTACT_SVC_NOTIFICATION_TIMEPERIOD':
            break;
            case 'CHANGE_CUSTOM_CONTACT_VAR':
            break;
            case 'CHANGE_CUSTOM_HOST_VAR':
            break;
            case 'CHANGE_CUSTOM_SVC_VAR':
            break;
            case 'CHANGE_GLOBAL_HOST_EVENT_HANDLER':
            break;
            case 'CHANGE_GLOBAL_SVC_EVENT_HANDLER':
            break;
            case 'CHANGE_HOST_CHECK_COMMAND':
            break;
            case 'CHANGE_HOST_CHECK_TIMEPERIOD':
            break;
            case 'CHANGE_HOST_CHECK_TIMEPERIOD':
            break;
            case 'CHANGE_HOST_EVENT_HANDLER':
            break;
            case 'CHANGE_HOST_MODATTR':
            break;
            case 'CHANGE_MAX_HOST_CHECK_ATTEMPTS':
            break;
            case 'CHANGE_MAX_SVC_CHECK_ATTEMPTS':
            break;
            case 'CHANGE_NORMAL_HOST_CHECK_INTERVAL':
            break;
            case 'CHANGE_NORMAL_SVC_CHECK_INTERVAL':
            break;
            case 'CHANGE_RETRY_HOST_CHECK_INTERVAL':
            break;
            case 'CHANGE_RETRY_SVC_CHECK_INTERVAL':
            break;
            case 'CHANGE_SVC_CHECK_COMMAND':
            break;
            case 'CHANGE_SVC_CHECK_TIMEPERIOD':
            break;
            case 'CHANGE_SVC_EVENT_HANDLER':
            break;
            case 'CHANGE_SVC_MODATTR':
            break;
            case 'CHANGE_SVC_NOTIFICATION_TIMEPERIOD':
            break;
            case 'DELAY_HOST_NOTIFICATION':
            break;
            case 'DELAY_SVC_NOTIFICATION':
            break;
            case 'DEL_ALL_HOST_COMMENTS':
            break;
            case 'DEL_ALL_SVC_COMMENTS':
            break;
            case 'DEL_HOST_COMMENT':
            break;
            case 'DEL_HOST_DOWNTIME':
            break;
            case 'DEL_SVC_COMMENT':
            break;
            case 'DEL_SVC_DOWNTIME':
            break;
            case 'DISABLE_ALL_NOTIFICATIONS_BEYOND_HOST':
            break;
            case 'DISABLE_CONTACTGROUP_HOST_NOTIFICATIONS':
            break;
            case 'DISABLE_CONTACTGROUP_SVC_NOTIFICATIONS':
            break;
            case 'DISABLE_CONTACT_HOST_NOTIFICATIONS':
            break;
            case 'DISABLE_CONTACT_SVC_NOTIFICATIONS':
            break;
            case 'DISABLE_EVENT_HANDLERS':
            break;
            case 'DISABLE_FAILURE_PREDICTION':
            break;
            case 'DISABLE_FLAP_DETECTION':
            break;
            case 'DISABLE_HOSTGROUP_HOST_CHECKS':
            break;
            case 'DISABLE_HOSTGROUP_HOST_NOTIFICATIONS':
            break;
            case 'DISABLE_HOSTGROUP_PASSIVE_HOST_CHECKS':
            break;
            case 'DISABLE_HOSTGROUP_PASSIVE_SVC_CHECKS':
            break;
            case 'DISABLE_HOSTGROUP_SVC_CHECKS':
            break;
            case 'DISABLE_HOSTGROUP_SVC_NOTIFICATIONS':
            break;
            case 'DISABLE_HOST_AND_CHILD_NOTIFICATIONS':
            break;
            case 'DISABLE_HOST_CHECK':
            break;
            case 'DISABLE_HOST_EVENT_HANDLER':
            break;
            case 'DISABLE_HOST_FLAP_DETECTION':
            break;
            case 'DISABLE_HOST_FRESHNESS_CHECKS':
            break;
            case 'DISABLE_HOST_NOTIFICATIONS':
            break;
            case 'DISABLE_HOST_SVC_CHECKS':
            break;
            case 'DISABLE_HOST_SVC_NOTIFICATIONS':
            break;
            case 'DISABLE_NOTIFICATIONS':
            break;
            case 'DISABLE_PASSIVE_HOST_CHECKS':
            break;
            case 'DISABLE_PASSIVE_SVC_CHECKS':
            break;
            case 'DISABLE_PERFORMANCE_DATA':
            break;
            case 'DISABLE_SERVICEGROUP_HOST_CHECKS':
            break;
            case 'DISABLE_SERVICEGROUP_HOST_NOTIFICATIONS':
            break;
            case 'DISABLE_SERVICEGROUP_PASSIVE_HOST_CHECKS':
            break;
            case 'DISABLE_SERVICEGROUP_PASSIVE_SVC_CHECKS':
            break;
            case 'DISABLE_SERVICEGROUP_SVC_CHECKS':
            break;
            case 'DISABLE_SERVICEGROUP_SVC_NOTIFICATIONS':
            break;
            case 'DISABLE_SERVICE_FLAP_DETECTION':
            break;
            case 'DISABLE_SERVICE_FRESHNESS_CHECKS':
            break;
            case 'DISABLE_SVC_CHECK':
            break;
            case 'DISABLE_SVC_EVENT_HANDLER':
            break;
            case 'DISABLE_SVC_FLAP_DETECTION':
            break;
            case 'DISABLE_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_ALL_NOTIFICATIONS_BEYOND_HOST':
            break;
            case 'ENABLE_CONTACTGROUP_HOST_NOTIFICATIONS':
            break;
            case 'ENABLE_CONTACTGROUP_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_CONTACT_HOST_NOTIFICATIONS':
            break;
            case 'ENABLE_CONTACT_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_EVENT_HANDLERS':
            break;
            case 'ENABLE_FAILURE_PREDICTION':
            break;
            case 'ENABLE_FLAP_DETECTION':
            break;
            case 'ENABLE_HOSTGROUP_HOST_CHECKS':
            break;
            case 'ENABLE_HOSTGROUP_HOST_NOTIFICATIONS':
            break;
            case 'ENABLE_HOSTGROUP_PASSIVE_HOST_CHECKS':
            break;
            case 'ENABLE_HOSTGROUP_PASSIVE_SVC_CHECKS':
            break;
            case 'ENABLE_HOSTGROUP_SVC_CHECKS':
            break;
            case 'ENABLE_HOSTGROUP_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_HOST_AND_CHILD_NOTIFICATIONS':
            break;
            case 'ENABLE_HOST_CHECK':
            break;
            case 'ENABLE_HOST_EVENT_HANDLER':
            break;
            case 'ENABLE_HOST_FLAP_DETECTION':
            break;
            case 'ENABLE_HOST_FRESHNESS_CHECKS':
            break;
            case 'ENABLE_HOST_NOTIFICATIONS':
            break;
            case 'ENABLE_HOST_SVC_CHECKS':
            break;
            case 'ENABLE_HOST_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_NOTIFICATIONS':
            break;
            case 'ENABLE_PASSIVE_HOST_CHECKS':
            break;
            case 'ENABLE_PASSIVE_SVC_CHECKS':
            break;
            case 'ENABLE_PERFORMANCE_DATA':
            break;
            case 'ENABLE_SERVICEGROUP_HOST_CHECKS':
            break;
            case 'ENABLE_SERVICEGROUP_HOST_NOTIFICATIONS':
            break;
            case 'ENABLE_SERVICEGROUP_PASSIVE_HOST_CHECKS':
            break;
            case 'ENABLE_SERVICEGROUP_PASSIVE_SVC_CHECKS':
            break;
            case 'ENABLE_SERVICEGROUP_SVC_CHECKS':
            break;
            case 'ENABLE_SERVICEGROUP_SVC_NOTIFICATIONS':
            break;
            case 'ENABLE_SERVICE_FRESHNESS_CHECKS':
            break;
            case 'ENABLE_SVC_CHECK':
            break;
            case 'ENABLE_SVC_EVENT_HANDLER':
            break;
            case 'ENABLE_SVC_FLAP_DETECTION':
            break;
            case 'ENABLE_SVC_NOTIFICATIONS':
            break;
            case 'PROCESS_FILE':
            break;
            case 'PROCESS_HOST_CHECK_RESULT':
            break;
            case 'PROCESS_SERVICE_CHECK_RESULT':
            break;
            case 'READ_STATE_INFORMATION':
            break;
            case 'REMOVE_HOST_ACKNOWLEDGEMENT':
            break;
            case 'REMOVE_SVC_ACKNOWLEDGEMENT':
            break;
            case 'RESTART_PROGRAM':
            break;
            case 'SAVE_STATE_INFORMATION':
            break;
            case 'SCHEDULE_AND_PROPAGATE_HOST_DOWNTIME':
            break;
            case 'SCHEDULE_AND_PROPAGATE_TRIGGERED_HOST_DOWNTIME':
            break;
            case 'SCHEDULE_FORCED_HOST_CHECK':
            break;
            case 'SCHEDULE_FORCED_HOST_SVC_CHECKS':
            break;
            case 'SCHEDULE_FORCED_SVC_CHECK':
            break;
            case 'SCHEDULE_HOSTGROUP_HOST_DOWNTIME':
            break;
            case 'SCHEDULE_HOSTGROUP_SVC_DOWNTIME':
            break;
            case 'SCHEDULE_HOST_CHECK':
            break;
            case 'SCHEDULE_HOST_DOWNTIME':
            break;
            case 'SCHEDULE_HOST_SVC_CHECKS':
            break;
            case 'SCHEDULE_HOST_SVC_DOWNTIME':
            break;
            case 'SCHEDULE_SERVICEGROUP_HOST_DOWNTIME':
            break;
            case 'SCHEDULE_SERVICEGROUP_SVC_DOWNTIME':
            break;
            case 'SCHEDULE_SVC_CHECK':
            break;
            case 'SCHEDULE_SVC_DOWNTIME':
            break;
            case 'SEND_CUSTOM_HOST_NOTIFICATION':
            break;
            case 'SEND_CUSTOM_SVC_NOTIFICATION':
            break;
            case 'SET_HOST_NOTIFICATION_NUMBER':
            break;
            case 'SET_SVC_NOTIFICATION_NUMBER':
            break;
            case 'SHUTDOWN_PROGRAM':
            break;
            case 'START_ACCEPTING_PASSIVE_HOST_CHECKS':
            break;
            case 'START_ACCEPTING_PASSIVE_SVC_CHECKS':
            break;
            case 'START_EXECUTING_HOST_CHECKS':
            break;
            case 'START_EXECUTING_SVC_CHECKS':
            break;
            case 'START_OBSESSING_OVER_HOST':
            break;
            case 'START_OBSESSING_OVER_HOST_CHECKS':
            break;
            case 'START_OBSESSING_OVER_SVC':
            break;
            case 'START_OBSESSING_OVER_SVC_CHECKS':
            break;
            case 'STOP_ACCEPTING_PASSIVE_HOST_CHECKS':
            break;
            case 'STOP_ACCEPTING_PASSIVE_SVC_CHECKS':
            break;
            case 'STOP_EXECUTING_HOST_CHECKS':
            break;
            case 'STOP_EXECUTING_SVC_CHECKS':
            break;
            case 'STOP_OBSESSING_OVER_HOST':
            break;
            case 'STOP_OBSESSING_OVER_HOST_CHECKS':
            break;
            case 'STOP_OBSESSING_OVER_SVC':
            break;
            case 'STOP_OBSESSING_OVER_SVC_CHECKS':
            break;
        }
    }*/

}
