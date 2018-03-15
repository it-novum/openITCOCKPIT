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

use phpseclib\Crypt\RSA;
use phpseclib\Net\SFTP;
use phpseclib\Net\SSH2;
use Symfony\Component\Finder\Finder;

class AfterExportTask extends AppShell
{

    public $uses = [
        'DistributeModule.Satellite',
        'Systemsetting'
    ];

    public function init()
    {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);

        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        Configure::load('after_export');
        $this->conf = Configure::read('after_export');
        Configure::load('nagios');
    }

    public function execute()
    {

        $monitoringSystemsettings = $this->Systemsetting->findAsArraySection('MONITORING');
        if($monitoringSystemsettings['MONITORING']['MONITORING.SINGLE_INSTANCE_SYNC'] == 1){
            $satellites = $this->Satellite->find('all', [
                'recursive' => -1,
                'conditions' => [
                    'Satellite.sync_instance' => 1,
                ]
            ]);
        }else{
            $satellites = $this->Satellite->find('all', [
                'recursive' => -1,
            ]);
        }
        foreach ($satellites as $satellite) {
            $this->copy($satellite);
            $this->hr();
        }
    }

    public function copy($satellite)
    {
        try{
            if (!$this->checkPort($satellite['Satellite']['address'])) {
                throw new Exception('Checking port failed!');
            }

            $this->out('Connect to '.$satellite['Satellite']['name'].' ('.$satellite['Satellite']['address'].')', false);

            $key = new RSA();
            $key->loadKey(file_get_contents($this->conf['SSH']['private_key']));

            $ssh = new SSH2($satellite['Satellite']['address'], $this->conf['SSH']['port']);

            if(!$ssh->login($this->conf['SSH']['username'], $key)){
                throw new Exception('Login failed!');
            }

            $this->out('<green> ok</green>');

            //Create SFTP Ressource
            if (!is_dir(Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id'])) {
                throw new Exception('No derectory in nagios.export.satellite_path was found!');
            }
            $folder = new Folder(Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id']);

            //Delete target on remote host
            $this->out('Delete old monitoring configuration', false);
            $result = $this->execOverSsh($ssh, '/bin/bash -c \'rm -rf '.$this->conf['REMOTE']['path'].'/config\'');


            $this->out('<green> ok</green>');

            //Copy new files
            $this->out('Copy new configuration', false);
            if ($this->conf['SSH']['use_rsync'] === false) {
                $this->out(' using PHP', false);


                $sftpKey = new RSA();
                $sftpKey->loadKey(file_get_contents($this->conf['SSH']['private_key']));

                $sftp = new SFTP($satellite['Satellite']['address']);

                if(!$sftp->login($this->conf['SSH']['username'], $sftpKey)){
                    throw new Exception('Login failed!');
                }

                $finder = new Finder();
                $files = $finder->files()->in(Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id']);
                foreach($files as $file){
                    //debug($file->getRealPath()); //'/opt/openitc/nagios/satellites/1/config/hosttemplates/hosttemplates_minified.cfg'
                  //  debug($file->getRelativePath()); //'config/hosttemplates'
                  //  debug($file->getRelativePathname()); //'config/hosttemplates/hosttemplates_minified.cfg'

                    $to = $this->conf['REMOTE']['path'].$file->getRelativePathname();
                    $toDir = $this->conf['REMOTE']['path'].$file->getRelativePath();
                    $from = $file->getRealPath();
                    $sftp->mkdir($toDir, -1, true);
                    $sftp->chdir($to);
                    $sftp->put($to, $from, SFTP::SOURCE_LOCAL_FILE);
                }

                $this->out('<green> ok</green>');
            } else {
                $this->out(' using rsync', false);
                $commandArgs = [
                    $this->conf['SSH']['private_key'],
                    Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id'],
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
            $result = $this->execOverSsh($ssh, "/bin/bash -c '".$this->conf['SSH']['restart_command']."'");
            $this->out('<green> ok</green>');

            //Execute remote commands - if any
            foreach ($this->conf['SSH']['remote_command'] as $remoteCommand) {
                $this->out('Execute external command '.$remoteCommand, false);
                $result = $this->execOverSsh($ssh, $remoteCommand);
                $this->out('<green> ok</green>');
            }

            return true;
        }catch (Exception $ex){
            error_log('Rsync failed for Satellite '.$satellite['Satellite']['address'].': '.$ex->getMessage()."\n", 3, '/var/log/nginx/cake/error.log');
            $this->out('<red> '.$ex->getMessage().'</red>');
            return false;
        }
    }


    public function checkPort($address)
    {
        $this->out('Check remote system for open port '.$this->conf['SSH']['port'], false);
        if (!@fsockopen('tcp://'.$address, $this->conf['SSH']['port'], $errorNo, $errorStr, 35)) {
            $this->out('<red> '.$errorStr.'</red>');

            return false;
        }
        $this->out('<green> '.$errorStr.'</green>');

        return true;
    }

    public function execOverSsh($sshConnection, $command)
    {
        $sshConnection->enableQuietMode();
        $stdout = $sshConnection->exec($command);
        $stderr = $sshConnection->getStdError();

        return [
            'stdout' => $stdout,
            'stderr' => $stderr,
        ];
    }

    public function beQuiet()
    {
        $this->params['quiet'] = true;
    }

}
