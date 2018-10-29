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

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

App::uses('PackageManager', 'Lib');

class SudoMessageInterface implements MessageComponentInterface {
    protected $clients;

    public function __construct($cakeThis) {
        $this->Cake = $cakeThis;
        $this->clients = new \SplObjectStorage;
        $this->process = null;
        $this->requestor = null;
        $this->returncode = null;
        //$this->tasks = [];

        $this->async = [];

        $this->lastExportCheck = time();
    }

    public function onOpen(ConnectionInterface $conn) {
        $uniqid = uniqid("", true);
        $this->clients->attach($conn);
        $conn->send(json_encode($this->merge([
            'payload' => 'Connection established',
            'uniqid'  => $uniqid,
            'type'    => 'connection',
        ])));
    }

    public function merge($msg = []) {
        $default = ['payload' => '', 'type' => '', 'task' => ''];

        return Hash::merge($default, $msg);
    }

    public function send($payload, $type = 'response', $task = '', $category = 'notification') {
        if ($this->requestor !== null) {
            if ($payload == false) {
                $payload = '';
            }
            foreach ($this->clients as $n => $client) {
                $client->send(json_encode($this->merge([
                    'payload'  => $payload,
                    'uniqid'   => $this->requestor,
                    'type'     => $type,
                    'task'     => $task,
                    'category' => $category,
                ])));
            }
        }
    }

    public function eventLoop() {
        $this->isExportRunning();


        if (empty($this->async)) {
            return;
        }

        foreach ($this->async as $id => $process) {
            if (!is_resource($process['process'])) {
                unset($this->async[$id]);

                return;
            }
            $line = fgets($process['pipes'][1], 1024);
            //$error = fgets($this->pipes[2], 1024);
            //echo $line;
            //echo $error;
            $this->send($line, 'response', $process['taskName']);

            $status = proc_get_status($process['process']);
            if ($status['running'] != 1 && $line == '') {
                fclose($process['pipes'][0]);
                fclose($process['pipes'][1]);
                fclose($process['pipes'][2]);

                // Es ist wichtig, dass Sie alle Pipes schließen bevor Sie
                // proc_close aufrufen, um Deadlocks zu vermeiden
                proc_close($process['process']);
                unset($this->async[$id]);
                $this->send('done', 'dispatcher');
            }
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg);
        //Reset MySQL connection to avoid "MySQL hase von away"
        if ($msg->key != $this->Cake->_systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY']) {
            return;
        }

        // Avoid "MySQL server has gone away"
        $this->Cake->Systemsetting->getDatasource()->reconnect();


        $this->requestor = $msg->uniqid;
        switch ($msg->task) {
            case 'package_install':
                $packageManager = new PackageManager($this, $msg->task);

                $url = $msg->data->url;
                $name = $msg->data->name;
                if ($packageManager->install($url, $name)) {
                    //echo 'Package ' . $name . ' was installed successfully.' . "\n";
                } else {
                    //echo 'Package ' . $name . ' couldn\'t get installed.' . "\n";
                }

                break;

            case 'package_delete': // Until 'package_uninstall' is fully implemented, uninstall and delete do both the same
            case 'package_uninstall':
                $packageManager = new PackageManager($this, $msg->task);

                $name = $msg->data->name;
                if ($packageManager->uninstall($name)) {
                    //echo 'Package ' . $name . ' was uninstalled successfully.' . "\n";
                } else {
                    //echo 'Package ' . $name . ' couldn\'t get uninstalled.' . "\n";
                }

                break;

            case '5238f8e57e72e81d44119a8ffc3f98ea':
                $this->exec(escapeshellcmd('apt-get purge -y openitcockpit-module-' . base64_decode($msg->data->name)) . ';/bin/echo -e "\n\nDone - Please run openitcockpit-update\n"', [
                    'task' => '5238f8e57e72e81d44119a8ffc3f98ea',
                ]);
                break;

            case 'd41d8cd98f00b204e9800998ecf8427e':
                $this->exec(escapeshellcmd('apt-get install -y openitcockpit-module-' . base64_decode($msg->data->name)) . ';/bin/echo -e "\n\nDone - Please check your User Roles for possible new settings.\n"', [
                    'task' => 'd41d8cd98f00b204e9800998ecf8427e',
                ]);
                break;

            case 'keepAlive':
                $this->send('Pong', 'keepAlive', 'keepAlive');
                break;

            case 'apt_get_update':
                //$this->exec('apt-get update');
                break;

            case 'execute_nagios_command':
                $this->execNagiosPlugin($msg->data);
                break;

            case 'rescheduleHost':
                $this->Cake->Externalcommand->rescheduleHost(['uuid' => $msg->data[0], 'type' => $msg->data[1], 'satellite_id' => $msg->data[2]]);
                break;

            case 'rescheduleHostWithQuery':
                $this->Cake->Externalcommand->rescheduleHostWithQuery(['uuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'rescheduleHostgroup':
                $this->Cake->Externalcommand->rescheduleHostgroup(['hostgroupUuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'commitPassiveResult':
                $this->Cake->Externalcommand->passiveTransferHostCheckresult(['uuid' => $msg->data[0], 'comment' => $msg->data[1], 'state' => $msg->data[2], 'forceHardstate' => $msg->data[3], 'repetitions' => $msg->data[4]]);
                break;

            case 'commitPassiveServiceResult':
                $this->Cake->Externalcommand->passiveTransferServiceCheckresult(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'comment' => $msg->data[2], 'state' => $msg->data[3], 'forceHardstate' => $msg->data[4], 'repetitions' => $msg->data[5]]);
                break;

            case 'enableOrDisableHostFlapdetection':
                $this->Cake->Externalcommand->enableOrDisableHostFlapdetection(['uuid' => $msg->data[0], 'condition' => $msg->data[1]]);
                break;

            case 'enableOrDisableServiceFlapdetection':
                $this->Cake->Externalcommand->enableOrDisableServiceFlapdetection(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'condition' => $msg->data[2]]);
                break;

            case 'nagiostats':
                exec($this->Cake->Nagiostat->command(), $ouput);
                $this->send($this->Cake->Nagiostat->mergeResult($ouput[0]));
                break;

            case 'rescheduleService':
                $this->Cake->Externalcommand->rescheduleService(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'satellite_id' => $msg->data[2]]);
                break;

            case 'rescheduleServiceWithQuery':
                $this->Cake->Externalcommand->rescheduleServiceWithQuery(['uuid' => $msg->data[0]]);
                break;

            case 'sendCustomHostNotification':
                $this->Cake->Externalcommand->sendCustomHostNotification(['hostUuid' => $msg->data[0], 'type' => $msg->data[1], 'author' => $msg->data[2], 'comment' => $msg->data[3]]);
                break;

            case 'sendCustomServiceNotification':
                $this->Cake->Externalcommand->sendCustomServiceNotification(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'type' => $msg->data[2], 'author' => $msg->data[3], 'comment' => $msg->data[4]]);
                break;

            case 'submitServicestateAck':
                $this->Cake->Externalcommand->setServiceAck(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'comment' => $msg->data[2], 'author' => $msg->data[3], 'sticky' => $msg->data[4]]);
                break;

            case 'submitServiceAckWithQuery':
                $this->Cake->Externalcommand->setServiceAckWithQuery(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'comment' => $msg->data[2], 'author' => $msg->data[3], 'sticky' => $msg->data[4]]);
                break;

            case 'submitHoststateAck':
                $this->Cake->Externalcommand->setHostAck(['hostUuid' => $msg->data[0], 'comment' => $msg->data[1], 'author' => $msg->data[2], 'sticky' => $msg->data[3], 'type' => $msg->data[4]]);
                break;

            case 'submitHostAckWithQuery':
                $this->Cake->Externalcommand->setHostAckWithQuery(['hostUuid' => $msg->data[0], 'comment' => $msg->data[1], 'author' => $msg->data[2], 'sticky' => $msg->data[3], 'type' => $msg->data[4]]);
                break;

            case 'submitHostgroupAck':
                $this->Cake->Externalcommand->setHostgroupAck(['hostgroupUuid' => $msg->data[0], 'comment' => $msg->data[1], 'author' => $msg->data[2], 'sticky' => $msg->data[3], 'type' => $msg->data[4]]);
                break;

            case 'submitServiceDowntime':
                $this->Cake->Externalcommand->setServiceDowntime(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1], 'start' => strtotime($msg->data[2]), 'end' => strtotime($msg->data[3]), 'comment' => $msg->data[4], 'author' => $msg->data[5]]);
                break;

            case 'submitHostDowntime':
                $this->Cake->Externalcommand->setHostDowntime(['hostUuid' => $msg->data[0], 'start' => strtotime($msg->data[1]), 'end' => strtotime($msg->data[2]), 'comment' => $msg->data[3], 'author' => $msg->data[4], 'downtimetype' => $msg->data[5]]);
                break;

            case 'submitHostgroupDowntime':
                $this->Cake->Externalcommand->setHostgroupDowntime(['hostgroupUuid' => $msg->data[0], 'start' => strtotime($msg->data[1]), 'end' => strtotime($msg->data[2]), 'comment' => $msg->data[3], 'author' => $msg->data[4], 'downtimetype' => $msg->data[5]]);
                break;

            case 'submitDisableHostNotifications':
                $this->Cake->Externalcommand->disableHostNotifications(['uuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'submitEnableHostNotifications':
                $this->Cake->Externalcommand->enableHostNotifications(['uuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'submitDisableHostgroupNotifications':
                $this->Cake->Externalcommand->disableHostgroupNotifications(['hostgroupUuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'submitEnableHostgroupNotifications':
                $this->Cake->Externalcommand->enableHostgroupNotifications(['hostgroupUuid' => $msg->data[0], 'type' => $msg->data[1]]);
                break;

            case 'submitDisableServiceNotifications':
                $this->Cake->Externalcommand->disableServiceNotifications(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1]]);
                break;

            case 'submitEnableServiceNotifications':
                $this->Cake->Externalcommand->enableServiceNotifications(['hostUuid' => $msg->data[0], 'serviceUuid' => $msg->data[1]]);
                break;

            case 'submitDeleteHostDowntime':
                $this->Cake->Externalcommand->deleteHostDowntime($msg->data[0]);
                if (isset($msg->data[1])) { // deleting service downtimes too
                    $servicesArr = explode(',', $msg->data[1]);
                    foreach ($servicesArr as $serviceDowntimeId) {
                        if ($serviceDowntimeId === '0' || empty($serviceDowntimeId)) continue;
                        $this->Cake->Externalcommand->deleteServiceDowntime($serviceDowntimeId);
                    }
                }
                break;

            case 'submitDeleteServiceDowntime':
                $this->Cake->Externalcommand->deleteServiceDowntime($msg->data[0]);
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
        $this->clients->detach($conn);
    }

    public function execNagiosPlugin($command) {

        $folder = new Folder(Configure::read('nagios.basepath') . Configure::read('nagios.libexec'));
        $plugins = $folder->find();
        $plugins[] = 'ls';
        $plugins[] = 'ls -la';

        if (strpos($command, ';') || strpos($command, '&&') || strpos($command, '$') || strpos($command, '|') || strpos($command, '`')) {
            $this->send("\e[0;34mWARNING: This command contain illegal characters, to run this command is only allowed from real CLI!\e[0m\n");

            return false;
        }

        if (strpos($command, './') === 0) {
            //Parse ./ away
            $_command = explode('./', $command);
            //remove spaces to get raw command name
            $_command = explode(' ', $_command[1], 2);
            if (!isset($_command[0]) || !in_array($_command[0], $plugins)) {
                $this->send("\e[0;31mERROR: Forbidden command!\e[0m\n");

                return false;
            }
        } else {
            $_command = explode(' ', $command, 2);
            if (!isset($_command[0]) || !in_array($_command[0], $plugins)) {
                $this->send("\e[0;31mERROR: Forbidden command!\e[0m\n");

                return false;
            }
        }

        $this->exec(escapeshellcmd("su " . Configure::read('nagios.user') . " -c '" . $command . "'"), [
            'cwd' => Configure::read('nagios.basepath') . Configure::read('nagios.libexec'),
        ]);
    }

    public function exec($command, $options = []) {
        // Exec normaly workd async wich is bad if we try to run to commands or tow users run a command
        $_options = [
            'cwd' => '/tmp/',
            'env' => [
                'LANG'     => 'C',
                'LANGUAGE' => 'en_US.UTF-8',
                'LC_ALL'   => 'C',
                'PATH'     => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
                'no_proxy' => 'localhost,127.0.0.0/8,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,jenkins.oitc.itn',
            ],
        ];
        $options = Hash::merge($_options, $options);

        $descriptorspec = [
            0 => ["pipe", "r"],  // STDIN ist eine Pipe, von der das Child liest
            1 => ["pipe", "w"],  // STDOUT ist eine Pipe, in die das Child schreibt
            2 => ["pipe", "r"],  // STDERR ist eine Datei, in die geschrieben wird
        ];

        $uniqid = uniqid();
        $this->async[$uniqid] = [
            'process'  => proc_open($command, $descriptorspec, $pipes, $options['cwd'], $options['env']),
            'pipes'    => $pipes,
            'taskName' => isset($options['task']) ? $options['task'] : '',
        ];

        //$this->process =
        //$this->pipes = $pipes;
        //$this->taskName = isset($options['task']) ? $options['task'] : '';
    }

    private function isExportRunning() {
        if ((time() - $this->lastExportCheck) > 3) {
            $exportRunning = true;
            $result = $this->Cake->Export->findByTask('export_started');
            if (empty($result)) {
                $exportRunning = false;
            } else {
                if ($result['Export']['finished'] == 1) {
                    $exportRunning = false;
                }
            }
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type'    => 'dispatcher',
                    'running' => $exportRunning,
                ]));
            }
            $this->lastExportCheck = time();
        }
    }


    public function fileHeader($file) {
        if (is_resource($file)) {
            fwrite($file,
                "    #########################################################################
    #    DO NOT EDIT THIS FILE BY HAND -- YOUR CHANGES WILL BE OVERWRITTEN  #
    #                                                                       #
    #                   File generated by openITCOCKPIT                     #
    #                                                                       #
    #                        Created: " . date('d.m.Y H:i') . "                      #
    #########################################################################\n\n\n");
        }
    }
}
