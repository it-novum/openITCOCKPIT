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
		'Bbcode',
		'Dashboard',
	];
	public $components = [
		'Bbcode',
	];
	public $uses = [
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		MONITORING_PARENTHOST,
		'Host',
		'DashboardTab',
		'Widget',
		'WidgetHostStatusList',
		'WidgetServiceStatusList',
		'Service',
		MONITORING_OBJECTS,
		'Rrd',
		'User',
		'Servicegroup',
		'Hostgroup',
		'WidgetTacho',
		'WidgetNotice',
	];

	const UPDATE_DISABLED = 0;
	const CHECK_FOR_UPDATES = 1;
	const AUTO_UPDATE = 2;

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
				],
			]);
		}
		if(empty($tab)){
			//No tab found. Create one
			$result = $this->DashboardTab->createNewTab($userId);
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

		//Find shared tabs
		$this->DashboardTab->bindModel([
			'belongsTo' => [
				'User'
			]
		]);
		$_sharedTabs = $this->DashboardTab->find('all', [
			'recursive' => -1,
			'contain' => [
				'User' => [
					'fields' => [
						'User.id',
						'User.usergroup_id',
						'User.firstname',
						'User.lastname'
					]
				]
			],
			'fields' => [
				'DashboardTab.id',
				'DashboardTab.name',
			],
			'conditions' => [
				'shared' => 1
			],
			'order' => [
				'User.id' => 'ASC'
			]
		]);
		$sharedTabs = [];
		foreach($_sharedTabs as $sharedTab){
			$sharedTabs[$sharedTab['DashboardTab']['id']] = $sharedTab['User']['firstname'].' '.$sharedTab['User']['lastname'].DS.$sharedTab['DashboardTab']['name'];
		}

		//Was this tab created from a shared tab?
		$updateAvailable = false;
		if($tab['DashboardTab']['source_tab_id'] > 0){
			if($this->DashboardTab->exists($tab['DashboardTab']['source_tab_id'])){
				//Does the source tab exists?
				$sourceTab = $this->DashboardTab->find('first', [
					'recursive' => -1,
					'contain' => [],
					'conditions' => [
						'DashboardTab.id' => $tab['DashboardTab']['source_tab_id'],
						'DashboardTab.shared' => 1,
						'DashboardTab.modified >' => $tab['DashboardTab']['modified']
					]
				]);
				if(!empty($sourceTab)){
					//Source tab was modified, show update notice or run auto update
					if($tab['DashboardTab']['check_for_updates'] == self::CHECK_FOR_UPDATES){
						//Display update available message
						$updateAvailable = true;
					}

					if($tab['DashboardTab']['check_for_updates'] == self::AUTO_UPDATE){
						//Delete old widgets
						foreach($tab['Widget'] as $widget){
							$this->Widget->delete($widget['id']);
						}
						$error = $this->Widget->copySharedWidgets($sourceTab, $tab, $userId);
						if($error === false){
							$this->setFlash(__('Tab automatically updated'));
							$this->redirect([
								'action' => 'index',
								$tab['DashboardTab']['id']
							]);
						}else{
							$this->setFlash(__('Automatically tab failed'), false);
							$this->redirect(['action' => 'index']);
						}
					}
				}
			}else{
				//Source tab not found, reset association
				$tab['DashboardTab']['source_tab_id'] = null;
				$tab['DashboardTab']['check_for_updates'] = self::UPDATE_DISABLED;
				$this->DashboardTab->id = $tab['DashboardTab']['id'];
				$this->DashboardTab->saveField('source_tab_id', $tab['DashboardTab']['source_tab_id']);
				$this->DashboardTab->saveField('check_for_updates', $tab['DashboardTab']['check_for_updates']);
			}
		}

		//Get tab rotate interval
		$user = $this->User->find('first', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'User.id' => $this->Auth->user('id')
			],
			'fields' => [
				'dashboard_tab_rotation'
			]
		]);
		$tabRotateInterval = $user['User']['dashboard_tab_rotation'];

		$this->Frontend->setJson('updateAvailable', $updateAvailable);
		$this->Frontend->setJson('tabRotationInterval', $tabRotateInterval);
		$this->Frontend->setJson('lang_minutes', __('minutes'));
		$this->Frontend->setJson('lang_seconds', __('seconds'));
		$this->Frontend->setJson('lang_and', __('and'));
		$this->Frontend->setJson('lang_disabled', __('disabled'));
		$this->set(compact([
			'tab',
			'tabs',
			'allWidgets',
			'preparedWidgets',
			'sharedTabs',
			'updateAvailable',
			'tabRotateInterval'
		]));
	}

	//Will redirect the user to the next tab
	public function next($currentTabId){
		$userId = $this->Auth->user('id');
		$tabs = $this->DashboardTab->find('all', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'user_id' => $this->Auth->user('id')
			],
			'order' => [
				'position' => 'ASC'
			],
			'fields' => [
				'DashboardTab.id',
				'DashboardTab.position',
			]
		]);
		$tabs = Hash::extract($tabs, '{n}.DashboardTab.id');
		$nextTabId = $tabs[0];

		$currentKey = array_search($currentTabId, $tabs);
		$nextKey = $currentKey + 1;
		if(isset($tabs[$nextKey])){
			$nextTabId = $tabs[$nextKey];
		}
		$this->redirect([
			'action' => 'index',
			$nextTabId
		]);
	}

	public function add(){
		$widget = [];
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['typeId']) && isset($this->request->data['tabId'])){
			$typeId = $this->request->data['typeId'];
			$tabId = $this->request->data['tabId'];
			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [],
				'conditions' => [
					'user_id' => $this->Auth->user('id'),
					'id' => $tabId,
				],
			]);
			//Check if the tab exists and is owned by the user
			if(!empty($tab)){
				$_widget = $this->DashboardHandler->getWidgetByTypeId($typeId, $tabId);
				$this->Widget->create();
				if($this->Widget->saveAll($_widget)){
					$resultForRender = $this->Widget->find('first', [
						'conditions' => [
							'Widget.id' => $this->Widget->id
						],
						'recursive' => -1,
						'contain' => [],
					]);
					//prepareForRender requires multidimensional Widget array
					$resultForRender = [
						'Widget' => [
							$resultForRender['Widget']
						]
					];
					$widget = $this->DashboardHandler->prepareForRender($resultForRender);
					$this->DashboardTab->id = $tabId;
					$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
				}
			}
		}
		//Set the widget or an empty array
		$this->set('widget', $widget);
	}

	public function createTab(){
		if($this->request->is('post') || $this->request->is('put')){
			if(isset($this->request->data['dashboard']['name'])){
				$tabName = $this->request->data['dashboard']['name'];
				$userId = $this->Auth->user('id');
				if(mb_strlen($tabName) > 0){
					$result = $this->DashboardTab->createNewTab($userId, [
						'name' => $tabName
					]);
					if(isset($result['DashboardTab']['id'])){
						$this->redirect([
							'action' => 'index',
							$result['DashboardTab']['id']
						]);
					}
				}
			}
		}
		$this->redirect([
			'action' => 'index'
		]);
	}

	public function createTabFromSharing(){
		$sourceTabId = $this->request->data('dashboard.source_tab');
		$sourceTab = $this->DashboardTab->find('first', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'id' => $sourceTabId,
				'shared' => 1
			],
		]);
		if(empty($sourceTab)){
			throw new NotFoundException(__('Invalid tab'));
		}
		$userId = $this->Auth->user('id');
		$newTab = $this->DashboardTab->createNewTab($userId, [
			'name' => $sourceTab['DashboardTab']['name'],
			'source_tab_id' => $sourceTab['DashboardTab']['id'],
			'check_for_updates' => 1
		]);

		$error = $this->Widget->copySharedWidgets($sourceTab, $newTab, $userId);

		if($error === false){
			$this->setFlash(__('Tab copied successfully'));
			$this->redirect([
				'action' => 'index',
				$newTab['DashboardTab']['id']
			]);
		}

		$this->setFlash(__('Could not use shared tab'), false);
		$this->redirect(['action' => 'index']);
	}

	public function updateSharedTab(){
		if($this->request->is('post') || $this->request->is('put')){
			$tabId = $this->request->data('dashboard.tabId');
			$askAgain = $this->request->data('dashboard.ask_again');
			$userId = $this->Auth->user('id');

			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [
					'Widget'
				],
				'conditions' => [
					'id' => $tabId,
					'user_id' => $userId
				]
			]);
			if(!empty($tab)){
				//Delete old widgets
				foreach($tab['Widget'] as $widget){
					$this->Widget->delete($widget['id']);
				}

				if($this->DashboardTab->exists($tab['DashboardTab']['source_tab_id'])){
					$sourceTab = $this->DashboardTab->find('first', [
						'recursive' => -1,
						'contain' => [],
						'conditions' => [
							'id' => $tab['DashboardTab']['source_tab_id'],
							'shared' => 1
						],
					]);
					$error = $this->Widget->copySharedWidgets($sourceTab, $tab, $userId);

					$this->DashboardTab->id = $tab['DashboardTab']['id'];
					if($askAgain == 1){
						$this->DashboardTab->saveField('check_for_updates', self::AUTO_UPDATE);
					}
					$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));

					if($error === false){
						$this->setFlash(__('Tab updated successfully'));
						$this->redirect([
							'action' => 'index',
							$tab['DashboardTab']['id']
						]);
					}
					$this->setFlash(__('Could not update tab'), false);
					$this->redirect(['action' => 'index']);
				}
			}
		}
	}

	public function disableUpdate(){
		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		$this->autoRender = false;
		if(isset($this->request->data['tabId'])){
			$tabId = $this->request->data['tabId'];
			$userId = $this->Auth->user('id');
			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [],
				'conditions' => [
					'id' => $tabId,
					'user_id' => $userId
				]
			]);
			if(!empty($tab)){
				$this->DashboardTab->id = $tab['DashboardTab']['id'];
				$this->DashboardTab->saveField('source_tab_id', null);
				$this->DashboardTab->saveField('check_for_updates', self::UPDATE_DISABLED);
			}
		}
	}

	public function renameTab(){
		if($this->request->is('post') || $this->request->is('put')){
			if(isset($this->request->data['dashboard']['name']) && isset($this->request->data['dashboard']['id'])){
				$tabName = $this->request->data['dashboard']['name'];
				$tabId = $this->request->data['dashboard']['id'];
				$userId = $this->Auth->user('id');
				if(mb_strlen($tabName) > 0){
					$result = $this->DashboardTab->find('first', [
						'recursive' => -1,
						'contain' => [],
						'conditions' => [
							'id' => $tabId,
							'user_id' => $userId
						]
					]);
					if(!empty($result)){
						$this->DashboardTab->id = $tabId;
						if($this->DashboardTab->saveField('name', $tabName)){
							$this->redirect([
								'action' => 'index',
								$tabId
							]);
						}
					}
				}
			}
		}
		$this->setFlash(__('Could not rename tab'), false);
		$this->redirect([
			'action' => 'index',
			$tabId
		]);
	}

	public function deleteTab($tabId = null){
		if(!$this->DashboardTab->exists($tabId)){
			throw new NotFoundException(__('Invalid tab'));
		}

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}

		$tab = $this->DashboardTab->findById($tabId);
		$userId = $this->Auth->user('id');
		if($tab['DashboardTab']['user_id'] == $userId){
			$this->DashboardTab->id = $tab['DashboardTab']['id'];
			if($this->DashboardTab->delete()){
				$this->setFlash(__('Tab deleted'));
				$this->redirect(['action' => 'index']);
			}
		}

		$this->setFlash(__('Could not delete tab'), false);
		$this->redirect(['action' => 'index']);
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
			$this->DashboardTab->id = $tabId;
			$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
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
					$this->DashboardTab->id = $tab['DashboardTab']['id'];
					$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
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
					$this->DashboardTab->id = $widget['DashboardTab']['id'];
					$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
				}
			}
		}
	}

	public function updateTabPosition(){
		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		$this->autoRender = false;
		$tabIdsOrdered = $this->request->data('tabIdsOrdered');
		if(is_array($tabIdsOrdered) && !empty($tabIdsOrdered)){
			$userId = $this->Auth->user('id');
			$position = 1;
			foreach($tabIdsOrdered as $tabId){
				$tab = $this->DashboardTab->find('first', [
					'recursive' => -1,
					'contain' => [],
					'conditions' => [
						'id' => $tabId,
						'user_id' => $userId
					],
					'fields' => [
						'id',
						'user_id'
					]
				]);
				if(!empty($tab)){
					$this->DashboardTab->id = $tabId;
					$this->DashboardTab->saveField('position', $position);
					$position++;
				}
			}
		}
	}

	public function saveTabRotationInterval(){
		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		$this->autoRender = false;
		if(isset($this->request->data['value'])){
			if(is_numeric($this->request->data['value'])){
				$userId = $this->Auth->user('id');
				$user = $this->User->find('first', [
					'recursive' => -1,
					'contain' => [],
					'conditions' => [
						'User.id' => $userId
					],
					'fields' => [
						'User.id',
						'User.dashboard_tab_rotation'
					]
				]);
				$this->User->id = $user['User']['id'];
				$this->User->saveField('dashboard_tab_rotation', $this->request->data['value']);
			}
		}
	}

	public function startSharing($tabId){
		$userId = $this->Auth->user('id');
		$tab = $this->DashboardTab->find('first', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'id' => $tabId,
				'user_id' => $userId
			],
			'fields' => [
				'id',
				'user_id'
			]
		]);
		if(empty($tab)){
			throw new NotFoundException(__('Invalid tab'));
		}

		$this->DashboardTab->id = $tabId;
		$this->DashboardTab->saveField('shared', 1);
		$this->redirect([
			'action' => 'index',
			$tabId
		]);
	}

	public function stopSharing($tabId){
		$userId = $this->Auth->user('id');
		$tab = $this->DashboardTab->find('first', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'id' => $tabId,
				'user_id' => $userId
			],
			'fields' => [
				'id',
				'user_id'
			]
		]);
		if(empty($tab)){
			throw new NotFoundException(__('Invalid tab'));
		}

		$this->DashboardTab->id = $tabId;
		$this->DashboardTab->saveField('shared', 0);
		$this->redirect([
			'action' => 'index',
			$tabId
		]);
	}

	public function refresh(){
		$widget = [];
		$element = 'Dashboard'.DS.'404.ctp';
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
				if($widget['DashboardTab']['user_id'] != $userId){
					$widgetId = [];
				}else{
					$result = $this->DashboardHandler->refresh($widget);
					$element = $result['element'];
				}
			}
		}

		//Set the widget or an empty array
		$this->set('widget', $widget);
		$this->set('element', $element);
	}

	public function saveStatuslistSettings(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['widgetId']) && isset($this->request->data['settings']) && isset($this->request->data['widgetTypeId'])){
			$widgetId = $this->request->data['widgetId'];
			$settings = $this->request->data['settings'];
			$widgetTypeId = $this->request->data['widgetTypeId'];

			if($widgetTypeId == 9 || $widgetTypeId == 10){
				if($widgetTypeId == 9){
					$contain = 'WidgetHostStatusList';
				}

				if($widgetTypeId == 10){
					$contain = 'WidgetServiceStatusList';
				}
				if($this->Widget->exists($widgetId)){
					$userId = $this->Auth->user('id');
					$widget = $this->Widget->find('first', [
						'contain' => [
							$contain,
							'DashboardTab'
						],
						'conditions' => [
							'Widget.id' => $widgetId,
						]
					]);
					if($widget['DashboardTab']['user_id'] == $userId){
						foreach($settings as $dbField => $value){
							if($value !== '' && $value !== null && isset($widget[$contain][$dbField])){
								$widget[$contain][$dbField] = $value;
							}
						}
						$this->Widget->saveAll($widget);
						$this->DashboardTab->id = $widget['DashboardTab']['id'];
						$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
					}
				}
			}
		}
	}

	public function saveTrafficLightService(){
		$this->autoRender = false;
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(isset($this->request->data['widgetId']) && isset($this->request->data['serviceId'])){
			$widgetId = $this->request->data['widgetId'];
			$serviceId = (int)$this->request->data['serviceId'];
			$userId = $this->Auth->user('id');
			if($this->Widget->exists($widgetId)){
				$widget = $this->Widget->findById($widgetId);
				if($widget['DashboardTab']['user_id'] == $userId){
					$widget['Widget']['service_id'] = $serviceId;
					$this->Widget->save($widget);
					$this->DashboardTab->id = $widget['DashboardTab']['id'];
					$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));
				}
			}
		}
	}

	public function getTachoPerfdata(){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		$perfdata = [];
		if(isset($this->request->data['widgetId']) && isset($this->request->data['serviceId'])){
			$widgetId = $this->request->data['widgetId'];
			$serviceId = (int)$this->request->data['serviceId'];
			$userId = $this->Auth->user('id');
			if($this->Widget->exists($widgetId)){
				$widget = $this->Widget->findById($widgetId);
				if($widget['DashboardTab']['user_id'] == $userId){
					$widget['Widget']['service_id'] = $serviceId;
					//$this->Widget->save($widget);
					//$this->DashboardTab->id = $widget['DashboardTab']['id'];
					//$this->DashboardTab->saveField('modified', date('Y-m-d H:i:s'));

					$service = $this->Service->find('first', [
						'recursive' => -1,
						'contain' => [],
						'conditions' => [
							'Service.id' => $serviceId
						],
						'joins' => [
							[
								'table' => 'nagios_objects',
								'type' => 'INNER',
								'alias' => 'ServiceObject',
								'conditions' => 'Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2'
							],
							[
								'table' => 'nagios_servicestatus',
								'type' => 'LEFT OUTER',
								'alias' => 'Servicestatus',
								'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id'
							],
						],
						'fields' => [
							'Service.id',
							'Service.uuid',
							'Servicestatus.current_state',
							'Servicestatus.perfdata',
						]
					]);



					if(isset($service['Servicestatus']['perfdata'])){
						$perfdata = [];
						$_perfdata = $this->Rrd->parsePerfData($service['Servicestatus']['perfdata']);
						$keys = ['current', 'unit', 'warn', 'crit', 'min', 'max'];
						foreach($_perfdata as $dsName => $data){
							foreach($keys as $key){
								if(isset($data[$key])){
									if($data[$key] == '' && $key !== 'unit'){
										$data[$key] = 0;
									}
									$perfdata[$dsName][$key] = $data[$key];
								}else{
									$perfdata[$dsName][$key] = 0;
								}
							}
						}
					}
				}
			}
		}
		$this->set('perfdata', $perfdata);
		$this->set('_serialize', ['perfdata']);
	}

	public function saveTachoConfig(){
		if($this->request->is('post') || $this->request->is('put')){
			$tachoConfig = $this->request->data['dashboard'];
			$widgetTachoId = null;
			if(isset($tachoConfig['widgetTachoId'])){
				$widgetTachoId = $tachoConfig['widgetTachoId'];
			}
			$requiredKeys = [
				'ds',
				'min',
				'max',
				'warn',
				'crit',
				'tabId',
				'widgetId',
				'serviceId'
			];
			foreach($requiredKeys as $key){
				if(!isset($tachoConfig[$key]) || $tachoConfig[$key] == ''){
					$this->setFlash(__('One or more parameters are missing'), false);
					return $this->redirect(['action' => 'index']);
				}
			}

			$userId = $this->Auth->user('id');
			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [],
				'conditions' => [
					'id' => $tachoConfig['tabId'],
					'user_id' => $userId
				],
			]);
			if(empty($tab) && !$this->Widget->exists($tachoConfig['widgetId'])){
				$this->setFlash(__('Given tab not found in database'), false);
				return $this->redirect(['action' => 'index']);
			}

			$data = [
				'WidgetTacho' => [
					'widget_id' => $tachoConfig['widgetId'],
					'min' => $tachoConfig['min'],
					'max' => $tachoConfig['max'],
					'warn' => $tachoConfig['warn'],
					'crit' => $tachoConfig['crit'],
					'data_source' => $tachoConfig['ds'],
				]
			];
			if($widgetTachoId !== null){
				$data['WidgetTacho']['id'] = $widgetTachoId;
			}
			if($this->WidgetTacho->save($data)){
				$this->Widget->id = $data['WidgetTacho']['widget_id'];
				$this->Widget->saveField('service_id', $tachoConfig['serviceId']);
			}
			return $this->redirect(['action' => 'index', $tachoConfig['tabId']]);
		}

		return $this->redirect(['action' => 'index']);
	}

	public function saveNotice(){
		if($this->request->is('post') || $this->request->is('put')){
			$noticeConfig = $this->request->data['dashboard'];

			$note = Purifier::clean($noticeConfig['noticeText'], 'StandardConfig');
			$note = htmlspecialchars($note);

			if(isset($noticeConfig['WidgetNoticeId'])){
				$widgetNoticeId = $noticeConfig['WidgetNoticeId'];
			}
			$userId = $this->Auth->user('id');
			$tab = $this->DashboardTab->find('first', [
				'recursive' => -1,
				'contain' => [],
				'conditions' => [
					'id' => $noticeConfig['tabId'],
					'user_id' => $userId
				],
			]);
			if(empty($tab) && !$this->Widget->exists($noticeConfig['widgetId'])){
				$this->setFlash(__('Given tab not found in database'), false);
				return $this->redirect(['action' => 'index']);
			}

			$data = [
				'WidgetNotice' => [
					'widget_id' => $noticeConfig['widgetId'],
					'note' => $note,
				]
			];
			if($widgetNoticeId !== null){
				$data['WidgetNotice']['id'] = $widgetNoticeId;
			}
			if($this->WidgetNotice->save($data)){
				$this->Widget->id = $data['WidgetNotice']['widget_id'];
			}
			/*if(){
				$this->Widget->id = $data['WidgetNotice']['widget_id'];
			}*/
			return $this->redirect(['action' => 'index', $noticeConfig['tabId']]);
		}

		return $this->redirect(['action' => 'index']);
	}
}
