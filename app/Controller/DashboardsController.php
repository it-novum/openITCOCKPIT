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

class DashboardsController extends AppController{
	public $layout = 'Admin.default';
	public $helpers = [
		'PieChart',
		'Status',
		'Monitoring',
		'Admin.Widget',
		'Bbcode',
	];
	public $components = [
		'Admin.WidgetCollection',
		'Bbcode',
	];
	public $uses = [
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		MONITORING_PARENTHOST,
		'Host',
		'DashboardTab',
		'Widget',
		'Service',
		MONITORING_OBJECTS,
		'Rrd',
		'User',
		'Servicegroup',
		'Hostgroup',
	];

	public function beforeFilter(){
		require_once APP . 'Lib' . DS . 'Dashboards' . DS . 'DashboardHandler.php';
		$this->DashboardHandler = new Dashboard\DashboardHandler();
		//Dashboard is allays allowed
		if($this->Auth->loggedIn() === true){
			$this->Auth->allow();
		}
		parent::beforeFilter();
	}
	
	public function index($tabId = null){
		$userId = $this->Auth->user('id');
		$tab = $this->DashboardTab->find('first', [
			'conditions' => [
				'user_id' => $this->Auth->user('id')
			],
			'order' => [
				'position' => 'ASC'
			]
		]);
		if(empty($tab)){
			$result = $this->DashboardTab->createNewTab($userId, 1);
			if($result){
				$tabId = $result['DashboardTab']['id'];
				//Fill new tab with default dashboards
				$this->Widget->create();
				$defaultWidgets = $this->DashboardHandler->getDefaultDashboards($tabId);
				$this->Widget->saveAll($defaultWidgets);
				//normalize data for controller workflow
				$tab = $this->DashboardTab->findById($tabId);
			}
		}else{
			$tabId = $tab['DashboardTab']['id'];
		}
		//debug($tab);
	}
	
	public function restoreDefault($tabId = null){
		if(!$this->DashboardTab->exists($tabId)){
			throw new NotFoundException(__('Invalid tab'));
		}
	}
}
