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
		'Dashboard',
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
		//Dashboard is allays allowed
		if($this->Auth->loggedIn() === true){
			$this->Auth->allow();
		}
		parent::beforeFilter();
		if($this->Auth->loggedIn() === true){
			$this->DashboardHandler = new Dashboard\DashboardHandler($this);
		}
	}
	
	public function index($tabId = null){
		$userId = $this->Auth->user('id');
		$tab = [];
		if($tabId !== null && is_numeric($tabId)){
			$tab = $this->DashboardTab->find('first', [
				'conditions' => [
					'user_id' => $this->Auth->user('id'),
					'id' => $tabId,
				],
			]);
		}
		//No tab given, select first tab of the user
		if(empty($tab)){
			$tab = $this->DashboardTab->find('first', [
				'conditions' => [
					'user_id' => $this->Auth->user('id')
				],
				'order' => [
					'position' => 'ASC'
				]
			]);
		}
		if(empty($tab)){
			//No tab found. Create one
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
		
		//Find all tabs of the user, to create tab bar
		$tabs = $this->DashboardTab->find('all', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'user_id' => $this->Auth->user('id')
			],
			'order' => [
				'position' => 'ASC'
			]
		]);
		
		$allWidgets = $this->DashboardHandler->getAllWidgets();
		
		$preparedWidgets = $this->DashboardHandler->prepareForRender($tab);
		
		$this->Frontend->setJson('lang', ['newTitle' => __('New title')]);
		$this->Frontend->setJson('tabId', $tabId);
		$this->set(compact([
			'tab',
			'tabs',
			'allWidgets',
			'preparedWidgets'
		]));
	}
	
	public function restoreDefault($tabId = null){
		$tab = $this->DashboardTab->find('first', [
			'conditions' => [
				'user_id' => $this->Auth->user('id'),
				'id' => $tabId,
			],
		]);
		if(empty($tab) || $tab['DashboardTab']['id'] == null){
			throw new NotFoundException(__('Invalid tab'));
		}
		if($this->Widget->deleteAll(['Widget.dashboard_tab_id' => $tab['DashboardTab']['id']])){
			$defaultWidgets = $this->DashboardHandler->getDefaultDashboards($tabId);
			$this->Widget->saveAll($defaultWidgets);
		}
		$this->redirect(['action' => 'index', $tabId]);
	}
	
	public function updateTitle(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['widgetId']) && isset($this->request->data['title'])){
			$widgetId = $this->request->data['widgetId'];
			$title = $this->request->data['title'];
			$userId = $this->Auth->user('id');
			if($this->Widget->exists($widgetId)){
				$widget = $this->Widget->findById($widgetId);
				if($widget['DashboardTab']['user_id'] == $userId){
					$widget['Widget']['title'] = $title;
					$this->Widget->save($widget);
				}
			}
		}
	}
	
	public function updateColor(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['widgetId']) && isset($this->request->data['color'])){
			$widgetId = $this->request->data['widgetId'];
			$color = $this->request->data['color'];
			$userId = $this->Auth->user('id');
			if($this->Widget->exists($widgetId)){
				$widget = $this->Widget->findById($widgetId);
				if($widget['DashboardTab']['user_id'] == $userId){
					$widget['Widget']['color'] = str_replace('bg-', 'jarviswidget-', $color);
					$this->Widget->save($widget);
				}
			}
		}
	}
	
	public function updatePosition(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['tabId']) && isset($this->request->data[0])){
			$userId = $this->Auth->user('id');
			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [
					'Widget'
				],
				'conditions' => [
					'id' => $this->request->data['tabId'],
					'user_id' => $userId
				]
			]);
			if(!empty($tab)){
				$widgetIds = Hash::extract($tab['Widget'], '{n}.id');
				$data = [];
				foreach($this->request->data as $widget){
					if(is_array($widget) && isset($widget['id'])){
						if(in_array($widget['id'], $widgetIds)){
							$data[] = [
								'id' => $widget['id'],
								'row' => $widget['row'],
								'col' => $widget['col'],
								'width' => $widget['width'],
								'height' => $widget['height']
							];
						}
					}
				}
				if(!empty($data)){
					$this->Widget->saveAll($data);
				}
			}
		}
	}

	public function deleteWidget(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['widgetId'])){
			$widgetId = $this->request->data['widgetId'];
			$userId = $this->Auth->user('id');
			if($this->Widget->exists($widgetId)){
				$widget = $this->Widget->find('first', [
					'contain' => [
						'DashboardTab'
					],
					'conditions' => [
						'Widget.id' => $widgetId,
					]
				]);
				if($widget['DashboardTab']['user_id'] == $userId){
					$this->Widget->delete($widget['Widget']['id']);
				}
			}
		}
	}

}
