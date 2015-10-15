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

class ExportsController extends AppController{
	public $layout = 'Admin.default';
	
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
		'AdditionalLinks',
		'GearmanClient',
	];
	public $helpers = [
		'ListFilter.ListFilter',
	];
	
	public function beforeFilter(){
		//Dashboard is allays allowed
		if($this->Auth->loggedIn() === true){
			$this->Auth->allow();
		}
		parent::beforeFilter();
	}
	
	public function index(){
		App::uses('UUID', 'Lib');
		Configure::load('gearman');
		$this->Config = Configure::read('gearman');
	
		if($this->request->is('post') || $this->request->is('put')){
			debug($this->request->data);
			$this->Session->write('export', null);
			$this->Session->write('export.export_started', true);
			
			$this->GearmanClient->client->setCompleteCallback([$this, 'complete']);
			$this->GearmanClient->client->addTask("oitc_gearman", Security::cipher(serialize(['task' => 'export', 'playload' => 'hello world']), $this->Config['password']));
			$this->GearmanClient->client->runTasks();
		}
		
		$this->loadModel('Systemsetting');
		$key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
		$this->Frontend->setJson('akey', $key['Systemsetting']['value']);
		$this->Frontend->setJson('websocket_url', 'wss://'.env('HTTP_HOST').'/sudo_server');
		$this->Frontend->setJson('uuidRegEx', UUID::JSregex());
	}
	
	public function broadcast(){
		$this->allowOnlyAjaxRequests();
		$_exportRecords = $this->Export->find('all');
		
		$exportRecords = [];
		foreach($_exportRecords as $exportRecord){
			$exportRecords[$exportRecord['Export']['id']] = [
				'task' => $exportRecord['Export']['task'],
				'text' => $exportRecord['Export']['text'],
				'finished' => $exportRecord['Export']['finished'],
			];
		}
		
		$this->set('exportRecords', $exportRecords);
		$this->set('_serialize', ['exportRecords']);
	}
	
	public function launchExport(){
		$this->allowOnlyAjaxRequests();
		session_write_close();
		Configure::load('gearman');
		$this->Config = Configure::read('gearman');
		
		$this->autoRender = false;
		$this->Export->deleteAll(true);
		$this->Export->create();
		$data = [
			'Export' => [
				'task' => 'export_started',
				'text' => __('Export started')
			]
		];
		$this->Export->save($data);
		
		$this->GearmanClient->client->setCompleteCallback([$this, 'complete']);
		
		//Prepare for export
		$this->GearmanClient->client->do("oitc_gearman", Security::cipher(serialize(['task' => 'export_delete_old_configuration']), $this->Config['password']));
		
		//Delete old configuration
		$this->Export->create();
		$data = [
			'Export' => [
				'task' => 'export_delete_old_configuration',
				'text' => __('Delete old configuration')
			]
		];
		$this->Export->save($data);
		
		$tasks = [
			'export_create_default_config' => [
				'text' => __('Create default configuration'),
			],
			'export_hosttemplates' => [
				'text' => __('Create hosttemplate configuration'),
			],
			'export_hosts' => [
				'text' => __('Create host configuration'),
			],
			'export_commands' => [
				'text' => __('Create command configuration'),
			],
			'export_contacts' => [
				'text' => __('Create contact configuration'),
			],
			'export_contactgroups' => [
				'text' => __('Create contact group configuration'),
			],
			'export_timeperiods' => [
				'text' => __('Create timeperiod configuration'),
			],
			'export_hostgroups' => [
				'text' => __('Create host group configuration'),
			],
			'export_hostescalations' => [
				'text' => __('Create host escalation configuration'),
			],
			'export_servicetemplates' => [
				'text' => __('Create servicetemplate configuration'),
			],
			'export_services' => [
				'text' => __('Create service configuration'),
			],
			'export_serviceescalations' => [
				'text' => __('Create service escalation configuration'),
			],
			'export_servicegroups' => [
				'text' => __('Create service group configuration'),
			],
			'export_hostdependencies' => [
				'text' => __('Create host dependency configuration'),
			],
			'export_servicedependencies' => [
				'text' => __('Create service dependency configuration'),
			],
			'export_userdefinedmacros' => [
				'text' => __('Export user defined macros'),
			],
		];
			
		foreach($tasks as $taskName => $task){
			$this->GearmanClient->client->addTask("oitc_gearman", Security::cipher(serialize(['task' => $taskName]), $this->Config['password']));
			$this->Export->create();
			$data = [
				'Export' => [
					'task' => $taskName,
					'text' => $task['text']
				]
			];
			$this->Export->save($data);
		}
		$this->GearmanClient->client->runTasks();
	}
	
	public function complete($task){
		$result = unserialize($task->data());
		$exportRecord = $this->Export->findByTask($result['task']);
		if(!empty($exportRecord)){
			$exportRecord['Export']['finished'] = 1;
			$this->Export->save($exportRecord);
		}
	}
	
}
