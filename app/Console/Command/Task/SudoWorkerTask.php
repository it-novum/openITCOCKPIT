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

class SudoWorkerTask extends AppShell{
	
	public $uses = ['Systemsetting'];
	
	public $tasks = ['NagiosExport', 'DefaultNagiosConfig'];
	
	public function work(){
		$this->init();
		while(true){
			$this->readSocket();
			usleep(100000);
		}
		
	}
	
	public function init(){
		$this->_systemsettings = $this->Systemsetting->findAsArray();
		$this->socket = $this->createSocket();
		$this->parrentSocket = $this->createSocket();
		$this->bindSocket();
		$this->requestor = null;
		$this->process = null;
		$this->pipes = null;
			
	}
	
	public function readSocket($len = 4096){
		$buf = "";
		socket_recv($this->socket, $buf, $len, MSG_DONTWAIT);
		if($buf !== null){
			$data = json_decode($buf);
			$this->runSocketCommand($data);
		}
	}
	
	public function runSocketCommand($data){
		$this->requestor = $data->requestor;
		
		//Reset MySQL connection to avoid "MySQL hase von away"
		$this->Systemsetting->getDatasource()->reconnect();
		
		switch($data->task){
			case 'runCompleteExport':
				$this->runCompleteExport($data->parameters[0]);
				break;
				
				
			case 'exit':
				$this->stop();
				break;
		}
	}
	
	public function createSocket(){
		return socket_create(AF_UNIX, SOCK_DGRAM, 0);
	}
	
	public function stop(){
		$this->deleteSocket();
		exit(0);
	}
	
	public function bindSocket(){
		if(!is_dir($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'])){
			mkdir($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET']);
		}
		
		$this->setFolderPermissions();
		
		$this->deleteSocket();
		
		socket_bind($this->socket, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME']);
		if(file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'])){
			$this->setFilePermissions();
			return true;
		}else{
			return false;
		}
	}
	
	function deleteSocket(){
		if(file_exists($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'])){
			unlink($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME']);
		}
	}
	
	public function setFolderPermissions(){
		chown($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['WEBSERVER']['WEBSERVER.USER']);
		chgrp($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['MONITORING']['MONITORING.GROUP']);
		chmod($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'], $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.FOLDERPERMISSIONS']);
	}
	
	public function setFilePermissions(){
		chown($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'], $this->_systemsettings['WEBSERVER']['WEBSERVER.USER']);
		chgrp($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'], $this->_systemsettings['MONITORING']['MONITORING.GROUP']);
		chmod($this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKET_NAME'], $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.WORKERSOCKETPERMISSIONS']);
	}
	
	
	public function runCompleteExport($createBackup = false){
		$this->NagiosExport->init();
		
		if($createBackup == true){
			$this->send('responseFromFork', [
				'payload' => __('Create Backup of old configuration'),
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]);
			App::uses('Folder', 'Utility');
			$folder1 = new Folder(Configure::read('nagios.export.backupSource'));
			
			$backupTarget = Configure::read('nagios.export.backupTarget').'/'.date('d-m-Y_H-i-s');
			
			if(!is_dir(Configure::read('nagios.export.backupTarget'))){
				mkdir(Configure::read('nagios.export.backupTarget'));
			}
			
			if(!is_dir($backupTarget)){
				mkdir($backupTarget);
			}
			$folder1->copy($backupTarget);
		}
		
		$this->NagiosExport->beforeExportExternalTasks();
		
		$this->send('responseFromFork', [
			'payload' => __('Delete old configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->deleteAllConfigfiles();
		
		$this->send('responseFromFork', [
			'payload' => __('Create default configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->DefaultNagiosConfig->execute();
		
		$this->send('responseFromFork', [
			'payload' => __('Create hosttemplate configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportHosttemplates();
		
		$this->send('responseFromFork', [
			'payload' => __('Create host configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportHosts();
		
		$this->send('responseFromFork', [
			'payload' => __('Create command configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportCommands();
		
		$this->send('responseFromFork', [
			'payload' => __('Create contact configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportContacts();
		
		$this->send('responseFromFork', [
			'payload' => __('Create contact group configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportContactgroups();
		
		$this->send('responseFromFork', [
			'payload' => __('Create timeperiod configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportTimeperiods();
		
		$this->send('responseFromFork', [
			'payload' => __('Create host group configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportHostgroups();
		
		$this->send('responseFromFork', [
			'payload' => __('Create host escalation configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportHostescalations();
		
		$this->send('responseFromFork', [
			'payload' => __('Create macro configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportMacros();
		
		$this->send('responseFromFork', [
			'payload' => __('Create servicetemplate configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportServicetemplates();
		
		$this->send('responseFromFork', [
			'payload' => __('Create service configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportServices();
		
		$this->send('responseFromFork', [
			'payload' => __('Create service escalation configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportServiceescalations();
		
		$this->send('responseFromFork', [
			'payload' => __('Create service group configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportServicegroups();
		
		$this->send('responseFromFork', [
			'payload' => __('Create host dependency configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportHostdependencies();
		

		$this->send('responseFromFork', [
			'payload' => __('Create service dependency configuration'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->exportServicedependencies();
		
		$this->send('responseFromFork', [
			'payload' => __('Execute module export tasks'),
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$this->NagiosExport->afterExportExternalTasks();
		
		$this->send('responseFromFork', [
			'payload' => __('Done'),
			'type' => 'event',
			'task' => 'runCompleteExport',
			'category' => 'done'
		]);
		
		//Verifying new configuration
		$this->send('responseFromFork', [
			'payload' => '<strong>'.__('Verifying new configuration').'</strong>',
			'type' => 'response',
			'task' => 'runCompleteExport',
			'category' => 'notification'
		]);
		$return = $this->exec($this->NagiosExport->returnVerifyCommand(), [
			'success' => [
				'payload' => __('Done'),
				'type' => 'event',
				'task' => 'runCompleteExport',
				'category' => 'done'
			],
			'error' => [
				'payload' => __('Error: new configuration is not valid'),
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]
		]);
		
		if($return === 0){
			//New configuration is valid :-)
			//Reloading monitoring system
			$this->send('responseFromFork', [
				'payload' => '<strong>'.__('Reloading monitoring engine').'</strong>',
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]);
			$this->exec($this->NagiosExport->returnReloadCommand());
			
			$this->send('responseFromFork', [
				'payload' => '<strong>'.__('Execute after export command').'</strong>',
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]);
			$return = $this->exec($this->NagiosExport->returnAfterExportCommand(),[
				'success' => [
					'payload' => __('Done'),
					'type' => 'event',
					'task' => 'runCompleteExport',
					'category' => 'done'
				],
				'error' => [
					'payload' => __('Error while executing after export command, please try yourself'),
					'type' => 'response',
					'task' => 'runCompleteExport',
					'category' => 'notification'
				]
			]);
		}
		

		
	}
	
	
	public function send($task = 'ping', $payload = []){
		$data = [
			'task' => $task,
			//'sourceTask' => $task,
			'payload' => $payload,
			'key' => $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY'],
			'requestor' => $this->requestor,
		];
		$data = json_encode($data);
		if(!socket_sendto($this->parrentSocket, $data, strlen($data), 0, $this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME'])){
			$this->out(__('Could not connect to UNIX socket ').$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET'].$this->_systemsettings['SUDO_SERVER']['SUDO_SERVER.SOCKET_NAME']);
		}
	}
	
	public function exec($command, $options = []){
		$_options = [
			'cwd' => '/tmp/',
			'env' => [
				'LANG' => 'C',
				'LANGUAGE' => 'en_US.UTF-8',
				'LC_ALL' => 'C',
				'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'
			],
			'success' => [
				'payload' => __('Done'),
				'type' => 'event',
				'task' => 'runCompleteExport',
				'category' => 'done'
			],
			'error' => [
				'payload' => __('Error on execute'),
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]
		];
		$options = Hash::merge($_options, $options);
		
		
		$descriptorspec = [
			0 => ["pipe", "r"],  // STDIN ist eine Pipe, von der das Child liest
			1 => ["pipe", "w"],  // STDOUT ist eine Pipe, in die das Child schreibt
			2 => ["pipe", "r"],  // STDERR ist eine Datei, in die geschrieben wird
		];
		
		$this->process = proc_open($command, $descriptorspec, $pipes, $options['cwd'], $options['env']);
		$this->pipes = $pipes;
		
		while(true){
			//avoid infinite loop
			if(!is_resource($this->process)){
				break;
			}
			
			$status = proc_get_status($this->process);
			/*
			 * NOTICE:
			 * The exit code returned by the process (which is only meaningful if running is FALSE).
			 * Only first call of this function return real value, next calls return -1. 
			 * Source: http://php.net/manual/en/function.proc-get-status.php
			 */
			if($status['running'] === false && $status['exitcode'] !== -1){
				$exitcode = $status['exitcode'];
			}

			$line = fgets($this->pipes[1], 1024);
			$this->send('responseFromFork', [
				'payload' => $line,
				'type' => 'response',
				'task' => 'runCompleteExport',
				'category' => 'notification'
			]);
			
			if($status['running'] == false && $line == ''){
				fclose($this->pipes[0]);
				fclose($this->pipes[1]);
				fclose($this->pipes[2]);
				
				if($exitcode == 0){
					$this->send('responseFromFork', $options['success']);
				}else{
					$this->send('responseFromFork', $options['error']);
				}
				
				// Es ist wichtig, dass Sie alle Pipes schließen bevor Sie
				// proc_close aufrufen, um Deadlocks zu vermeiden
				proc_close($this->process);
				return $exitcode;
			}
		}
		return false;
	}
	
}