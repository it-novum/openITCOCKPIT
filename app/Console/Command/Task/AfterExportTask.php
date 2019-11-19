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

class AfterExportTask extends AppShell {

    public $uses = [
        'DistributeModule.Satellite',
        'Systemsetting'
    ];

    public function init() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);

        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        Configure::load('after_export');
        $this->conf = Configure::read('after_export');
        Configure::load('nagios');
    }

    public function execute() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $monitoringSystemsettings = $Systemsettings->findAsArraySection('MONITORING');
        if ($monitoringSystemsettings['MONITORING']['MONITORING.SINGLE_INSTANCE_SYNC'] == 1) {
            $satellites = $this->Satellite->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Satellite.sync_instance' => 1,
                ]
            ]);
        } else {
            $satellites = $this->Satellite->find('all', [
                'recursive' => -1,
            ]);
        }
        foreach ($satellites as $satellite) {
            $this->copy($satellite);
            $this->hr();
        }
    }

    public function copy($satellite) {
        try {
            if (!$this->checkPort($satellite['Satellite']['address'])) {
                throw new Exception('Checking port failed!');
            }

            $this->out('Connect to ' . $satellite['Satellite']['name'] . ' (' . $satellite['Satellite']['address'] . ')', false);
            $sshConnection = ssh2_connect($satellite['Satellite']['address'], $this->conf['SSH']['port']);
            $loggedIn = ssh2_auth_pubkey_file(
                $sshConnection,
                $this->conf['SSH']['username'],
                $this->conf['SSH']['public_key'],
                $this->conf['SSH']['private_key']
            );
            if ($loggedIn !== true) {
                throw new Exception('Login failed!');
            }
            $this->out('<green> ok</green>');

            //Creat SFTP Ressource
            if (!is_dir(Configure::read('nagios.export.satellite_path') . $satellite['Satellite']['id'])) {
                throw new Exception('No derectory in nagios.export.satellite_path was found!');
            }
            $folder = new Folder(Configure::read('nagios.export.satellite_path') . $satellite['Satellite']['id']);

            //Delete target on remote host
            $this->out('Delete old monitoring configuration', false);
            $result = $this->execOverSsh($sshConnection, '/bin/bash -c \'rm -rf ' . $this->conf['REMOTE']['path'] . '/config\'');
            $this->out('<green> ok</green>');

            //Copy new files
            $this->out('Copy new configuration', false);
            if ($this->conf['SSH']['use_rsync'] === false) {
                $this->out(' using PHP', false);
                $sftp = ssh2_sftp($sshConnection);
                @$folder->copy([
                    'to'        => 'ssh2.sftp://' . $sftp . $this->conf['REMOTE']['path'],
                    'from'      => Configure::read('nagios.export.satellite_path') . $satellite['Satellite']['id'],
                    //'mode' => 0644,
                    'recursive' => true,
                ]);
                $this->out('<green> ok</green>');
            } else {
                $this->out(' using rsync', false);
                $commandArgs = [
                    $this->conf['SSH']['private_key'],
                    Configure::read('nagios.export.satellite_path') . $satellite['Satellite']['id'],
                    $this->conf['SSH']['username'],
                    $satellite['Satellite']['address'],
                    $this->conf['REMOTE']['path'],
                ];
                $commandTemplate = "rsync -e 'ssh -C -ax -i %s -o StrictHostKeyChecking=no' -avm --timeout=10 --delete %s/* %s@%s:%s";
                $command = vsprintf($commandTemplate, $commandArgs);
                exec($command, $output, $returnCode);
                if ($returnCode != 0) {
                    throw new Exception(sprintf('Failed executing "%s"', $commandTemplate));
                }
                $this->out('<green> ok</green>');
            }


            //Restart remote monitoring engine
            $this->out('Restart remote monitoring engine', false);
            $result = $this->execOverSsh($sshConnection, "/bin/bash -c '" . $this->conf['SSH']['restart_command'] . "'");
            $this->out('<green> ok</green>');

            //Execute remote commands - if any
            foreach ($this->conf['SSH']['remote_command'] as $remoteCommand) {
                $this->out('Execute external command ' . $remoteCommand, false);
                $result = $this->execOverSsh($sshConnection, $remoteCommand);
                $this->out('<green> ok</green>');
            }

            return true;
        } catch (Exception $ex) {
            error_log('Rsync failed for Satellite ' . $satellite['Satellite']['address'] . ': ' . $ex->getMessage() . "\n", 3, '/var/log/nginx/cake/error.log');
            $this->out('<red> ' . $ex->getMessage() . '</red>');
            return false;
        }
    }


    public function checkPort($address) {
        $this->out('Check remote system for open port ' . $this->conf['SSH']['port'], false);
        if (!@fsockopen('tcp://' . $address, $this->conf['SSH']['port'], $errorNo, $errorStr, 35)) {
            $this->out('<red> ' . $errorStr . '</red>');

            return false;
        }
        $this->out('<green> ' . $errorStr . '</green>');

        return true;
    }

    public function execOverSsh($sshConnection, $command) {
        $stream = ssh2_exec($sshConnection, $command);
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);
        $stdout = stream_get_contents($stream);
        $stderr = stream_get_contents($errorStream);
        fclose($stream);
        fclose($errorStream);

        return [
            'stdout' => $stdout,
            'stderr' => $stderr,
        ];
    }

    public function beQuiet() {
        $this->params['quiet'] = true;
    }

}
