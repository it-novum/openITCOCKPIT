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
 * @property Systemsetting Systemsetting
 */
class AdministratorsController extends AppController{
	public $layout = 'Admin.default';

	function index(){
	}

	function debug(){
		$this->loadModel('Systemsetting');
		$this->loadModel('Cronjob');
		$this->loadModel('Register');
		$this->set('systemsetting', $this->Systemsetting->findAsArray());

		//Check if load cronjob exists
		if(!$this->Cronjob->checkForCronjob('CpuLoad', 'Core')){
			//Cron does not exists, so we create it
			$this->Cronjob->add('CpuLoad', 'Core', 15);
		}

		$license = $this->Register->find('first');
		$isEnterprise = false;
		if(!empty($license)){
			$isEnterprise = true;
		}

		$load = null;

		if(file_exists(TMP.'loadavg')){
			$this->Frontend->setJson('renderGraph', true);
			$load = file(TMP.'loadavg');
			if(sizeof($load) >= 3){
				$graphData = [
					1  => [],
					5  => [],
					15 => []
				];
				foreach($load as $line){
					$line = explode(' ', $line);
					$graphData[1][($line[0] * 1000)] = $line[1];
					$graphData[5][($line[0] * 1000)] = $line[2];
					$graphData[15][($line[0] * 1000)] = $line[3];
				}
				$this->Frontend->setJson('graphData', $graphData);
			}else{
				if(file_exists('/proc/loadavg')){
					$load = file('/proc/loadavg');
					$load = explode(' ', $load[0]);
					$this->Frontend->setJson('renderGraph', false);
				}
			}
		}else{
			if(file_exists('/proc/loadavg')){
				$load = file('/proc/loadavg');
				$load = explode(' ', $load[0]);
				$this->Frontend->setJson('renderGraph', false);
			}
		}



		exec('LANG=C df -h', $output, $returncode);
		$disks = [];
		if($returncode == 0){
			$ignore = ['none', 'udev', 'Filesystem'];
			foreach($output as $line){
				$value = preg_split('/\s+/', $line);
				if(!in_array($value[0], $ignore) && $value[5] != '/run'){
					$disks[] = [
						'disk' => $value[0],
						'size' => $value[1],
						'used' => $value[2],
						'avail' => $value[3],
						'use%' => str_replace('%', '', $value[4]),
						'mountpoint' => $value[5],
					];
				}
			}
		}

		$output = null;
		exec('LANG=C free -m', $output, $returncode);
		$memory = [];
		if($returncode == 0){
			foreach($output as $line){
				$value = preg_split('/\s+/', $line);
				if($value[0] == 'Mem:'){
					$memory['Memory'] = [
						'total' => $value[1],
						'free' => $value[3],
						'buffers' => $value[5],
						'cached' => $value[6],
					];
				}

				if($value[0] == '-/+'){
					$memory['Memory']['used'] = $value[2];
				}

				if($value[0] == 'Swap:'){
					$memory['Swap'] = [
						'total' => $value[1],
						'used' => $value[2],
						'free' => $value[3],
					];
				}
			}
		}

		$is_nagios_running = false;
		$is_db_running = false;
		$is_npcd_running = false;
		$is_mysql_running = false;
		$is_phpNSTA_running = false;
		$is_statusengine = false;
		$is_statusengine_perfdata = false;
		$is_gearmand_running = false;
		$gearmanStatus = [];

		Configure::load('nagios');
		exec(Configure::read('nagios.nagios_status'), $output, $returncode);
		if($returncode == 0){
			$is_nagios_running = true;
		}

		$output = null;
		//exec(Configure::read('nagios.ndo_status'), $output, $returncode);
		exec('ps -eaf |grep ndo2db | grep -v grep', $output, $returncode);
		if(sizeof($output) > 0){
			$is_db_running = true;
		}

		if(!$is_db_running){
			exec('ps -eaf |grep statusengine | grep -v grep', $output, $returncode);
			if(sizeof($output) > 0){
				$is_db_running = true;
				$is_statusengine = true;
			}
		}

		$output = null;
		//exec(Configure::read('nagios.npcd_status'), $output, $returncode);
		exec('ps -eaf |grep npcd | grep -v grep', $output, $returncode);
		if(sizeof($output) > 0){
			$is_npcd_running = true;
		}

		if(!$is_npcd_running){
			$statusengineConfig = '/opt/statusengine/cakephp/app/Config/Statusengine.php';
			if(file_exists($statusengineConfig)){
				require_once $statusengineConfig;
				if(isset($config['process_perfdata'])){
					if($config['process_perfdata'] === true && $is_statusengine === true){
						$is_npcd_running = true;
						$is_statusengine_perfdata = true;
					}
				}
			}
		}

		$output = null;
		exec('ps -eaf |grep gearmand | grep -v grep', $output, $returncode);
		if(sizeof($output) > 0){
			$is_gearmand_running = true;
			$output = null;
			exec('gearadmin --status', $output);
			//Parse output
			$trash = array_pop($output);
			foreach($output as $line){
				$queueDetails = explode("\t", $line);
				$gearmanStatus[$queueDetails[0]] = [
					'jobs' => $queueDetails[1],
					'running' => $queueDetails[2],
					'worker' => $queueDetails[3]
				];
			}
		}

		/*exec(Configure::read('nagios.mysql_status'), $output, $returncode);
		if($returncode == 0){
			$is_mysql_running = true;
		}*/

		exec(Configure::read('nagios.phpnsta_status'), $output, $returncode);
		if($returncode == 0){
			$is_phpNSTA_running = true;
		}

		//Get Monitoring engine + version
		$output = null;
		exec(Configure::read('nagios.basepath').Configure::read('nagios.bin').Configure::read('nagios.nagios_bin').' --version | head -n 2', $output);
		$monitoring_engine = $output[1];

		App::uses('CakeEmail', 'Network/Email');
		$Email = new ItcMail();
		$Email->config('default');
		$mailConfig = $Email->getConfig();

		$recipientAddress = $this->Auth->user('email');

		$this->set(compact([
			'disks',
			'memory',
			'load',
			'is_npcd_running',
			'is_db_running',
			'is_nagios_running',
			'is_mysql_running',
			'is_phpNSTA_running',
			'isEnterprise',
			'is_statusengine',
			'is_statusengine_perfdata',
			'monitoring_engine',
			'mailConfig',
			'is_gearmand_running',
			'gearmanStatus',
			'recipientAddress'
		]));
	}

	public function testMail(){
		$this->loadModel('Systemsetting');
		$recipientAddress = $this->Auth->user('email');
		$_systemsettings = $this->Systemsetting->findAsArray();

		$Email = new CakeEmail();
		$Email->config('default');
		$Email->from([$_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
		$Email->to($recipientAddress);
		$Email->subject(__('System test mail'));

		$Email->emailFormat('both');
		$Email->template('template-testmail', 'template-testmail');

		$Email->send();
		$this->setFlash(__('Test mail send to: %s', $recipientAddress));
		return $this->redirect(['action' => 'debug']);
	}
}

App::uses('CakeEmail', 'Network/Email');
class ItcMail extends CakeEmail{

	public function __construct($config = null){
		parent::__construct($config);
	}

	public function getConfig($removePassword = true){
		if($removePassword === true){
			$config = $this->_config;
			unset($config['password']);
			return $config;
		}
		return $this->_config;
	}
}
