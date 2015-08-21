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

class AfterExportShell extends AppShell{

	public function main(){
		
		$this->stdout->styles('green', ['text' => 'green']);
		$this->stdout->styles('blue',  ['text' => 'blue']);
		$this->stdout->styles('red',   ['text' => 'red']);
		
		App::uses('Folder', 'Utility');
		
		$this->connection = null;
		
		$modulePlugins = array_filter(CakePlugin::loaded(), function($value){
			return strpos($value, 'Module') !== false;
		});
		if(in_array('DistributeModule', $modulePlugins)){
			Configure::load('after_export');
			$this->conf = Configure::read('after_export');
			Configure::load('nagios');
			
			
			if($this->checkForExtension()){
				$this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
				$this->Satellites = $this->Satellite->find('all');
				
				//$this->copy();
				$this->dirtyCopy();
			}
		}else{
			//Checkuing for distributed Monitoring support (If DistributeModule is loaded)
			$this->out('<red>Distributed Monitoring Module is not loaded on your system :-(</red>');
			exit(0);
		}
	}
	
	public function checkForExtension(){
		if(!function_exists("ssh2_connect")){
			$this->out('<error>Fatal Error:</error>', false);
			$this->out(' Call to undefined function ssh2_connect - try apt-get install libssh2-php');
			return false;
		}
		return true;
	}
	
	public function checkPort($address){
		if(!@fsockopen('tcp://'.$address, $this->conf['SSH']['port'], $errorNo, $errorStr, 5)){
			$this->out('<error>SSH port check error for '.$address.':</error>', false);
			$this->out(' '.$errorStr);
			return false;
		}
		return true;
	}
	
	public function connect($address){
		$this->connection = ssh2_connect($address, $this->conf['SSH']['port']);
		if(is_resource($this->connection)){
			return true;
		}
		return false;
	}
	
	public function auth(){
		return ssh2_auth_pubkey_file($this->connection, $this->conf['SSH']['username'], $this->conf['SSH']['public_key'], $this->conf['SSH']['private_key']);
	}
	
	public function copy(){
		foreach($this->Satellites as $satellite){
			if($this->checkPort($satellite['Satellite']['address'])){
				if($this->connect($satellite['Satellite']['address'])){
					if($this->auth($satellite['Satellite']['address'])){
						if(is_dir(Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id'])){
							$satFolderContent = scandir(Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id']);
							foreach($satFolderContent as $name){
								if($name == '.' || $name == '..'){
									continue;
								}
								if(is_dir($name)){
									//do cool stuff
								}else{
									//Copy the file to remote system
									$fileName = Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id'].DS.$name;
									$this->out('Copy file '.$fileName.' to '.$this->conf['REMOTE']['path'].$name);
									//debug($this->conf['REMOTE']['path'].$name);
									/*if(!ssh2_scp_send($this->connection, $fileName, $this->conf['REMOTE']['path'].$name)){
										$this->out('<error>Failed to copy file </error>', false);
										$this->out(' '.$fileName);
									}*/
								}
							}
						}
					}
				}
			}
		}
	}
	
	public function dirtyCopy(){
		foreach($this->Satellites as $satellite){
			$this->out('Copy files to <blue>'.$satellite['Satellite']['name'].'</blue> ('.$satellite['Satellite']['address'].')', false);
			if($this->checkPort($satellite['Satellite']['address'])){
				$output = null;
				exec("rsync -e 'ssh -ax -i ".$this->conf['SSH']['private_key']."' -avzm --timeout=10 --delete  ".Configure::read('nagios.export.satellite_path').$satellite['Satellite']['id']."/* ".$this->conf['SSH']['username']."@".$satellite['Satellite']['address'].":".$this->conf['REMOTE']['path'], $output, $returnCode);
				if($returnCode > 0){
					$this->out('<red>   Error while copy data</red>');
					debug($output);
				}else{
					$this->out('<green>   ...ok</green>');
					$this->out('Fire up nagios restart', false);
					$output = null;
					exec('ssh -i '.$this->conf['SSH']['private_key'].' '.$this->conf['SSH']['username']."@".$satellite['Satellite']['address'].' "service nagios restart"', $output, $returnCode);
					if($returnCode > 0){
						$this->out('<red>   Error while restart nagios</red>');
						debug($output);
					}else{
						$this->out('<green>   ...ok</green>');
					}
					if(!empty($this->conf['SSH']['remote_command'])){
						$this->out('Execute custom remote command', false);
						foreach($this->conf['SSH']['remote_command'] as $remoteCommand){
							$output = null;
							exec('ssh -i '.$this->conf['SSH']['private_key'].' '.$this->conf['SSH']['username']."@".$satellite['Satellite']['address'].' \''.$remoteCommand.'\'', $output, $returnCode);
							if($returnCode > 0){
								$this->out('<red>   Error while executing remote command</red>');
								debug($output);
							}else{
								$this->out('<green>   ...ok</green>');
							}
						}
					}
					
				}
			}
			$this->hr();
		}
	}
}