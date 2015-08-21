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

class CmdController extends AppController{
	
	public $layout = 'Admin.default';
	public $uses = ['Systemsetting'];
	public $components = ['GearmanClient'];
	
	/*
	 * Example:
	 * https://$HOSTADDRESS$nagios_module/cmd/submit/api_key:$SECRET$/command:ACKNOWLEDGE_HOST_PROBLEM/hostUuid:d7306457-d4e3-4dee-a79f-38ffa6cc321e/author:foobar/comment:this is a test/sticky:0/
	 */
	
	function index(){
		$commands = $this->__externalCommands();
		$this->set(compact(['commands']));
	}
	
	public function submit(){
		$this->autoRender = false;
		//debug($this->request->params['named']);
		$commands = $this->__externalCommands();
		
		if(!isset($this->request->params['named']['api_key'])){
			throw new NotFoundException(__('API key is missing!'));
		}
		
		if(!isset($this->request->params['named']['command'])){
			throw new NotFoundException(__('Command is missing!'));
		}
		
		if(!isset($commands[$this->request->params['named']['command']])){
			throw new NotFoundException(__('Given command is not supported yet!'));
		}
		
		$systemsettings = $this->Systemsetting->findAsArray();
		
		if($this->request->params['named']['api_key'] != $systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY']){
			throw new ForbiddenException(__('API key mismatch!'));
		}
		
		//Mergeing given parameters with default parameters
		$externalCommand = $this->request->params['named']['command'];
		unset($this->request->params['named']['command']);
		unset($this->request->params['named']['api_key']);
		//Hash::merge retunrs us the parameters in the right direction witch is great <3
		$parameters = Hash::merge($commands[$externalCommand], $this->request->params['named']);
		
		//Command is now ready to submit to sudo_server
		$this->GearmanClient->sendBackground('cmd_external_command', ['command' => $externalCommand, 'parameters' => $parameters]);
		
	}
	
	protected function __externalCommands(){
		return [
			'ACKNOWLEDGE_HOST_PROBLEM' => ['hostUuid' => null, 'sticky' => 0, 'notify' => 1, 'persistent' => 1, 'author' => null, 'comment' => null],
			'ACKNOWLEDGE_SVC_PROBLEM' => ['hostUuid' => null, 'serviceUuid' => null, 'sticky' => 0, 'notify' => 1, 'persistent' => 1, 'author' => null, 'comment' => null],
			'DISABLE_FLAP_DETECTION' => [],
			'DISABLE_HOST_CHECK' => ['hostUuid' => null],
			'DISABLE_NOTIFICATIONS' => [],
			'DISABLE_PERFORMANCE_DATA' => [],
			'DISABLE_SERVICE_FRESHNESS_CHECKS' => [],
			'DISABLE_SVC_CHECK' => ['hostUuid' => null, 'serviceUuid' => null],
			'DISABLE_SVC_FLAP_DETECTION' => ['hostUuid' => null, 'serviceUuid' => null],
			'DISABLE_SVC_NOTIFICATIONS' => ['hostUuid' => null, 'serviceUuid' => null],
			'ENABLE_FLAP_DETECTION' => [],
			'ENABLE_HOST_CHECK' => [],
			'ENABLE_NOTIFICATIONS' => [],
			'ENABLE_PERFORMANCE_DATA' => [],
			'ENABLE_SERVICE_FRESHNESS_CHECKS' => [],
			'ENABLE_SVC_CHECK' => ['hostUuid' => null, 'serviceUuid' => null],
			'ENABLE_SVC_FLAP_DETECTION' => ['hostUuid' => null, 'serviceUuid' => null],
			'ENABLE_SVC_NOTIFICATIONS' => ['hostUuid' => null, 'serviceUuid' => null],
			'PROCESS_HOST_CHECK_RESULT' => ['hostUuid' => null, 'status_code' => null, 'plugin_output' => null],
			'PROCESS_SERVICE_CHECK_RESULT' => ['hostUuid' => null, 'serviceUuid' => null, 'status_code' => null, 'plugin_output' => null],
			'REMOVE_HOST_ACKNOWLEDGEMENT' => ['hostUuid' => null],
			'REMOVE_SVC_ACKNOWLEDGEMENT' => ['hostUuid' => null, 'serviceUuid' => null],
			'RESTART_PROGRAM' => [],
			'SCHEDULE_AND_PROPAGATE_HOST_DOWNTIME' => ['hostUuid' => null, 'start_time' => null, 'end_time' => null, 'fixed' => 1, 'trigger_id' => 0, 'duration' => null, 'author' => null, 'comment' => null],
			'SCHEDULE_AND_PROPAGATE_TRIGGERED_HOST_DOWNTIME' => ['hostUuid' => null, 'start_time' => null, 'end_time' => null, 'fixed' => 1, 'trigger_id' => 0, 'duration' => null, 'author' => null, 'comment' => null],
			'SCHEDULE_FORCED_HOST_CHECK' => ['hostUuid' => null, 'check_time' => null],
			'SCHEDULE_FORCED_HOST_SVC_CHECKS' => ['hostUuid' => null, 'check_time' => null],
			'SCHEDULE_FORCED_SVC_CHECK' => ['hostUuid' => null, 'serviceUuid' => null, 'check_time' => null],
			'SCHEDULE_SVC_DOWNTIME' => ['hostUuid' => null, 'serviceUuid' => null, 'start_time' => null, 'end_time' => null, 'fixed' => 1, 'trigger_id' => 0, 'duration' => null, 'author' => null, 'comment' => null],
			'SEND_CUSTOM_HOST_NOTIFICATION' => ['hostUuid' => null, 'options' => 0, 'author' => null, 'comment' => null],
			'SEND_CUSTOM_SVC_NOTIFICATION' => ['hostUuid' => null, 'serviceUuid' => null, 'options' => 0, 'author' => null, 'comment' => null],
		];
	}
}