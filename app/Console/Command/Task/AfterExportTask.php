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

        $monitoringSystemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
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

            //Creat SFTP Ressource
            if (!is_dir(Configure::read('nagios.export.satellite_path') . $satellite['Satellite']['id'])) {
                throw new Exception('No derectory in nagios.export.satellite_path was found!');
            }


            //Delete target on remote host
            $this->out('Delete old monitoring configuration', false);
            $result = $this->execOverSsh('rm -rf ' . $this->conf['REMOTE']['path'] . '/config', $satellite['Satellite']['address']);
            if ($result['returnCode'] > 0) {
                $this->out('<red>'.$result['output'].'</red>');
            } else {
                $this->out('<green>    ok</green>');
            }


            //Copy new files
            $this->out('Copy new configuration via rsync', false);

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
                throw new \Exception(sprintf('Failed executing "%s"', $commandTemplate));
            }
            $this->out('<green>    ok</green>');


            //Restart remote monitoring engine
            $this->out('Restart remote monitoring engine', false);
            $result = $this->execOverSsh($this->conf['SSH']['restart_command'], $satellite['Satellite']['address']);
            if ($result['returnCode'] != 0) {
                throw new \Exception(sprintf('Failed to restart monitoring engine'));
            }
            $this->out('<green>    ok</green>');

            //Execute remote commands - if any
            foreach ($this->conf['SSH']['remote_command'] as $remoteCommand) {
                $this->out('Execute external command ' . $remoteCommand, false);
                $result = $this->execOverSsh($remoteCommand, $satellite['Satellite']['address']);
                $this->out('<green>    ok</green>');
            }

            return true;
        } catch (Exception $ex) {
            error_log('Rsync failed for Satellite ' . $satellite['Satellite']['address'] . ': ' . $ex->getMessage() . "\n", 3, '/var/log/nginx/cake/error.log');
            $this->out('<red> ' . $ex->getMessage() . '</red>');
            return false;
        }
    }


    /**
     * @param $address
     * @return bool
     */
    private function checkPort(string $address): bool {
        if (!@fsockopen('tcp://' . $address, $this->conf['SSH']['port'], $errorNo, $errorStr, 35)) {
            $this->out('<red> ' . $errorStr . '</red>');
            return false;
        }

        $this->out('<green> ' . $errorStr . '</green>');
        return true;
    }

    /**
     * @param $command
     * @return array
     */
    private function execOverSsh($command, $address) {
        //Do not use PHP SSH2 anymore - it's crap...

        $output = [];
        exec(
            sprintf(
                'ssh -l %s -i %s -o StrictHostKeyChecking=no %s "%s"',
                escapeshellarg($this->conf['SSH']['username']),
                escapeshellarg($this->conf['SSH']['private_key']),
                escapeshellarg($address),
                $command
            ),
            $output,
            $returnCode
        );

        return [
            'output'     => implode("\n", $output),
            'returnCode' => $returnCode
        ];
    }

    public function beQuiet() {
        $this->params['quiet'] = true;
    }

}
