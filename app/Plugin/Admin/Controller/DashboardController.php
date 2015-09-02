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

class DashboardController extends AdminAppController{
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
		//Dashboard is allays allowed
		if($this->Auth->loggedIn() === true){
			$this->Auth->allow();
		}
		parent::beforeFilter();
	}

	public function index($tabId = 0){
		if(in_array('MapModule', CakePlugin::loaded())){
			array_push($this->uses,'MapModule.Map');
			array_push($this->uses,'MapModule.Mapitem');
			array_push($this->uses,'MapModule.Mapline');
			array_push($this->uses,'MapModule.Mapgadget');
			array_push($this->uses,'MapModule.Maptext');
			array_push($this->uses,'MapModule.Background');
			$MapModule = true;
		}else{
			$MapModule = false;
		}
		$this->Frontend->setJson('MapModule', $MapModule);
		$this->set(compact('MapModule'));

		$userId = $this->Auth->user('id');
		$tabs = $this->DashboardTab->find('all', [
			'fields' => [
				'DashboardTab.id',
				'DashboardTab.name',
				'DashboardTab.position',
				'DashboardTab.shared',
				'DashboardTab.source_tab_id',
			],
			'order' => ['DashboardTab.position'],
			'conditions' => ['DashboardTab.user_id' => $userId],
			'recursive' => -1,
		]);

		$sharedTabs = $this->DashboardTab->find('all', [
			'fields' => [
				'DashboardTab.id',
				'DashboardTab.name',
			],
			'order' => ['DashboardTab.position'],
			'conditions' => [
				'DashboardTab.shared' => 1
			],
			'recursive' => -1,
		]);

		$sharedTabsForSelect = [];
		foreach($sharedTabs as $sharedTab){
			$sharedTabsForSelect[$sharedTab['DashboardTab']['id']] = $sharedTab['DashboardTab']['name'];
		}

		$legitTab = Hash::extract($tabs, '{n}.DashboardTab[id='.$tabId.']');
		if(!empty($legitTab) || $tabId == 0){


			if($tabId == 0){
				if(count($tabs) == 0){
					$newEmptyTab = [
						'DashboardTab' => [
							'dashboard_id' => 0,
							'name' => __('Default'),
							'position' => 0,
							'shared' => 0,
							'user_id' => $userId,
						]
					];
					$this->DashboardTab->saveAll($newEmptyTab);
					$newEmptyTab['DashboardTab']['id'] = $this->DashboardTab->id;
					$tabs = [$newEmptyTab];
				}

				$tabId = $tabs[0]['DashboardTab']['id'];
			}

			if(!$this->DashboardTab->hasAny(['id' => $tabId])){
				$this->redirect(['action' => 'index']);
			}

			$sharedTabId = $this->DashboardTab->find('first', [
				'fields' => [
					'DashboardTab.source_tab_id',
					'DashboardTab.check_for_updates',
				],
				'conditions' => [
					'DashboardTab.user_id' => $userId,
					'DashboardTab.id' => $tabId
				],
				'recursive' => -1,
			]);

			if(!empty($sharedTabId)){
				$checkForUpdates = intval($sharedTabId['DashboardTab']['check_for_updates']);
				$sharedTabId = intval($sharedTabId['DashboardTab']['source_tab_id']);

				$sourceHasChanged = 0;
				$manualUpdate = 0;
				$alwaysUpdate = 0;
				if($sharedTabId != 0 && $checkForUpdates === 1){
					//Check if Widgets of Sourcetab have changed
					$lastModified = $this->DashboardTab->find('first', [
						'fields' => [
							'DashboardTab.modified',
						],
						'conditions' => [
							'DashboardTab.id' => $sharedTabId
						],
						'recursive' => -1,
					]);

					if(!empty($lastModified)){
						$targetTab = $this->DashboardTab->find('first', [
							'fields' => [
								'DashboardTab.source_last_modified',
							],
							'conditions' => [
								'DashboardTab.id' => $tabId
							],
							'recursive' => -1,
						]);

						$sourceModified = strtotime($lastModified['DashboardTab']['modified']);
						$sourceLastModified = strtotime($targetTab['DashboardTab']['source_last_modified']);

						if($sourceModified > $sourceLastModified){
							$sourceHasChanged = 1;
							$manualUpdate = 1;
						}
					}
				}
				if($checkForUpdates === 0){
					$lastModified = $this->DashboardTab->find('first', [
						'fields' => [
							'DashboardTab.modified',
						],
						'conditions' => [
							'DashboardTab.id' => $sharedTabId
						],
						'recursive' => -1,
					]);

					if(!empty($lastModified)){
						$targetTab = $this->DashboardTab->find('first', [
							'fields' => [
								'DashboardTab.source_last_modified',
							],
							'conditions' => [
								'DashboardTab.id' => $tabId
							],
							'recursive' => -1,
						]);

						$sourceModified = strtotime($lastModified['DashboardTab']['modified']);
						$sourceLastModified = strtotime($targetTab['DashboardTab']['source_last_modified']);

						if($sourceModified > $sourceLastModified){
							$manualUpdate = 1;
						}
					}
				}
				if($sharedTabId != 0 && $checkForUpdates === 2){
					//Check if Widgets of Sourcetab have changed
					$lastModified = $this->DashboardTab->find('first', [
						'fields' => [
							'DashboardTab.modified',
						],
						'conditions' => [
							'DashboardTab.id' => $sharedTabId
						],
						'recursive' => -1,
					]);

					if(!empty($lastModified)){
						$targetTab = $this->DashboardTab->find('first', [
							'fields' => [
								'DashboardTab.source_last_modified',
							],
							'conditions' => [
								'DashboardTab.id' => $tabId
							],
							'recursive' => -1,
						]);

						$sourceModified = strtotime($lastModified['DashboardTab']['modified']);
						$sourceLastModified = strtotime($targetTab['DashboardTab']['source_last_modified']);

						if($sourceModified > $sourceLastModified){
							$sourceHasChanged = 1;
							$alwaysUpdate = 1;
						}
					}
				}

			}

			$widgets = $this->Widget->find('all', [
				'conditions' => [
					'Widget.dashboard_tab_id' => $tabId,
				],
				'contain' => [
					'WidgetTacho',
					'WidgetServiceStatusList',
					'WidgetHostStatusList',
					'WidgetBrowser',
					'WidgetNotice',
					'WidgetGraphgenerator',
				]
			]);
		}else{
			$tabId = $tabs[0]['DashboardTab']['id'];
			$widgets = $this->Widget->find('all', [
				'conditions' => [
					'Widget.dashboard_tab_id' => $tabId,
				],
				'contain' => [
					'WidgetTacho',
					'WidgetServiceStatusList',
					'WidgetHostStatusList',
					'WidgetBrowser',
					'WidgetNotice',
					'WidgetGraphgenerator',
				]
			]);
			$sourceHasChanged = 0;
			$manualUpdate = 0;
			$alwaysUpdate = 0;
		}

		$barColors = $this->getBarColors();

		$tabRotationInterval = $this->User->find('first', [
			'fields' => [
				'User.dashboard_tab_rotation',
			],
			'conditions' => ['User.id' => $userId],
		]);

		$this->set(compact('tabs'));
		$this->set(compact('sharedTabsForSelect'));
		$this->set(compact('tabId'));
		$this->set(compact('widgets'));
		$this->set(compact('barColors'));
		$this->set(compact('tabRotationInterval'));

		$this->WidgetCollection->setWidgetDataToView($widgets,$MapModule);

		$this->Frontend->setJson('tabId', $tabId);
		$this->Frontend->setJson('tabRotationInterval', $tabRotationInterval['User']['dashboard_tab_rotation']);
		$this->Frontend->setJson('tabs', $tabs);
		$this->Frontend->setJson('sourceHasChanged', $sourceHasChanged);
		$this->Frontend->setJson('manualUpdate', $manualUpdate);
		$this->Frontend->setJson('alwaysUpdate', $alwaysUpdate);
	}

	public function saveWidgetPositionsAndSizes(){
		$this->autoRender = false;
		foreach($this->request->data as $widget){
			$this->Widget->save($widget);
		}
		$this->refreshTab($this->request->data[0]['id']);
	}

	/**
	 * Loads a widget configuration by id.
	 *
	 * @param int $widgetId
	 */
	public function loadWidgetConfiguration($widgetId){
		$this->set('data', $this->Widget->find('first', [
			'contain' => [
				'Service' => [
					'fields' => [
						'id',
						'uuid',
						'name',
						'check_interval',
					],
					'Servicetemplate' => [
						'fields' => [
							'name',
							'check_interval'
						]
					],
					'Host' => [
						'fields' => [
							'Host.name'
						]
					]
				],
				'WidgetTacho' => [
				],
				'WidgetServiceStatusList' => [
				],
				'WidgetHostStatusList' => [
				],
				'WidgetBrowser' => [
				],
				'WidgetNotice' => [
				],
				'WidgetGraphgenerator' => [
				],
			],
			'conditions' => [
				'Widget.id' => $widgetId,
			],
		]));
		$this->set('_serialize', ['data']);
	}

	public function saveWidget(){
		$this->autoRender = false;
		$this->allowOnlyAjaxRequests();
		debug($this->request->data);
		$success = $this->Widget->saveAll($this->request->data);

		if(!$success){
			debug($this->Widget->validationErrors);
		}
		if($this->request->data['Widget']['id'] !== null){
			$this->refreshTab($this->request->data['Widget']['id']);
		}else{
			$this->refreshTab($this->request->data['id']);
		}
		//debug($this->request->data['Widget']['id']);

	}

	public function deleteWidget($widgetId){
		$this->autoRender = false;
		if($this->Widget->exists($widgetId)){
			$this->refreshTab($widgetId);
			$this->Widget->delete($widgetId);
		}
	}

	public function deleteAllWidgetsFromTab($tabId){
		$this->autoRender = false;
		$checkbox = $this->request->data;
		$widgetsToDelete = $this->Widget->find('all', [
			'conditions' => [
				'Widget.dashboard_tab_id' => $tabId,
			]
		]);

		foreach($widgetsToDelete as $widgetToDelete){
			if($this->Widget->exists($widgetToDelete['Widget']['id'])){
				$this->Widget->delete($widgetToDelete['Widget']['id']);
			}
		}

		$activeTab = $this->DashboardTab->find('first', [
			'fields' => [
				'DashboardTab.name',
				'DashboardTab.source_tab_id',
			],
			'conditions' => [
				'DashboardTab.id' => $tabId
			],
			'recursive' => -1,
		]);

		$this->cloneWidgets($activeTab['DashboardTab']['source_tab_id'], $tabId);

		$lastModified = $this->DashboardTab->find('first', [
			'fields' => [
				'DashboardTab.modified',
			],
			'conditions' => [
				'DashboardTab.id' => $activeTab['DashboardTab']['source_tab_id']
			],
			'recursive' => -1,
		]);

		$data = [
			'DashboardTab' => [
				'id' => $tabId,
				'name' => $activeTab['DashboardTab']['name'],
				'source_last_modified' => $lastModified['DashboardTab']['modified']
			]
		];

		$result = $this->DashboardTab->save($data);
		return json_encode($checkbox);
	}

	public function addWidget($typeId, $tabId){
		$this->Widget->create();
		$data = [
			'Widget' => [
				'dashboard_tab_id' => $tabId,
				'type_id' => $typeId,
				'service_id' => null,
				'host_id' => null,
				'map_id' => null,
				'width' => __($this->WidgetCollection->getDefaultWidth($typeId)),
				'height' => __($this->WidgetCollection->getDefaultHeight($typeId)),
				'title' => __($this->WidgetCollection->getDefaultTitle($typeId))
			]
		];
		$result = $this->Widget->save($data);

		$this->refreshTab($result['Widget']['id']);

		$barColors = $this->getBarColors();

		$this->set(compact('result'));
		$this->set(compact('barColors'));
		$this->WidgetCollection->setWidgetDataToView([$result]);
	}

	public function addDefaultWidgets($tabId){
		$data = [
				0 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 8,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 0,
						'col' => 0,
						'width' => __($this->WidgetCollection->getDefaultWidth(8)),
						'height' => __($this->WidgetCollection->getDefaultHeight(8)),
						'title' => __($this->WidgetCollection->getDefaultTitle(8))
					]
				],
				1 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 3,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 5,
						'col' => 0,
						'width' => __($this->WidgetCollection->getDefaultWidth(3)),
						'height' => __($this->WidgetCollection->getDefaultHeight(3)),
						'title' => __($this->WidgetCollection->getDefaultTitle(3))
					]
				],
				2 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 2,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 0,
						'col' => 11,
						'width' => __($this->WidgetCollection->getDefaultWidth(2)),
						'height' => __($this->WidgetCollection->getDefaultHeight(2)),
						'title' => __($this->WidgetCollection->getDefaultTitle(2))
					]
				],
				3 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 5,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 5,
						'col' => 11,
						'width' => __($this->WidgetCollection->getDefaultWidth(5)),
						'height' => __($this->WidgetCollection->getDefaultHeight(5)),
						'title' => __($this->WidgetCollection->getDefaultTitle(5))
					]
				],
				4 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 1,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 0,
						'col' => 24,
						'width' => __($this->WidgetCollection->getDefaultWidth(1)),
						'height' => __($this->WidgetCollection->getDefaultHeight(1)),
						'title' => __($this->WidgetCollection->getDefaultTitle(1))
					]
				],
				5 => [
					'Widget' => [
						'dashboard_tab_id' => $tabId,
						'type_id' => 4,
						'service_id' => null,
						'host_id' => null,
						'map_id' => null,
						'row' => 5,
						'col' => 24,
						'width' => __($this->WidgetCollection->getDefaultWidth(4)),
						'height' => __($this->WidgetCollection->getDefaultHeight(4)),
						'title' => __($this->WidgetCollection->getDefaultTitle(4))
					]
				]
			];
		$return = [];
		foreach($data as $key => $w){
			$this->Widget->create();
			$result[$key] = $this->Widget->save($w);
		}
		$this->refreshTab($result[0]['Widget']['id']);

		$barColors = $this->getBarColors();

		$this->set(compact('barColors'));
		foreach($result as $widget){
			$this->set(compact('widget'));
			$this->WidgetCollection->setWidgetDataToView([$widget]);
		}
	}

	public function getBarColors(){
		$barColors = [
			'green' => [
				'title' => 'Green Grass',
				'color' => '#356e35',
			],
			'greenDark' => [
				'title' => 'Dark Green',
				'color' => '#496949',
			],
			'greenLight' => [
				'title' => 'Light Green',
				'color' => '#71843f',
			],
			'purple' => [
				'title' => 'Purple',
				'color' => '#6e587a',
			],
			'magenta' => [
				'title' => 'Magenta',
				'color' => '#6e3671',
			],
			'pink' => [
				'title' => 'Pink',
				'color' => '#ac5287',
			],
			'pinkDark' => [
				'title' => 'Fade Pink',
				'color' => '#a8829f',
			],
			'blueLight' => [
				'title' => 'Light Blue',
				'color' => '#92a2a8',
			],
			'teal' => [
				'title' => 'Teal',
				'color' => '#568a89',
			],
			'blue' => [
				'title' => 'Ocean Blue',
				'color' => '#57889C',
			],
			'blueDark' => [
				'title' => 'Night Sky',
				'color' => '#4c4f53',
			],
			'darken' => [
				'title' => 'Night',
				'color' => '#404040',
			],
			'yellow' => [
				'title' => 'Day Light',
				'color' => '#b09b5b',
			],
			'orange' => [
				'title' => 'Orange',
				'color' => '#c79121',
			],
			'orangeDark' => [
				'title' => 'Dark Orange',
				'color' => '#a57225',
			],
			'red' => [
				'title' => 'Red Rose',
				'color' => '#a90329',
			],
			'redLight' => [
				'title' => 'Light Red',
				'color' => '#a65858',
			],
			'white' => [
				'title' => 'Purity',
				'color' => '#FFF',
			],
		];
		return $barColors;
	}

	public function addTab($title,$position){
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->DashboardTab->create();
		$data = [
			'DashboardTab' => [
				'user_id' => $userId,
				'position' => $position,
				'name' => $title
			]
		];
		$result = $this->DashboardTab->save($data);
		return json_encode($result);
	}

	public function addSharedTab($title,$position,$sourceTabId){
		$this->autoRender = false;
		$userId = $this->Auth->user('id');

		$sourceTabModified = $this->DashboardTab->find('first', [
			'fields' => [
				'DashboardTab.modified',
			],
			'conditions' => [
				'DashboardTab.id' => $sourceTabId
			],
			'recursive' => -1,
		]);

		$this->DashboardTab->create();
		$data = [
			'DashboardTab' => [
				'user_id' => $userId,
				'position' => $position,
				'name' => $title,
				'source_tab_id' => $sourceTabId,
				'check_for_updates' => 1,
				'source_last_modified' => $sourceTabModified['DashboardTab']['modified']
			]
		];
		$result = $this->DashboardTab->save($data);
		$this->cloneWidgets($sourceTabId, $result['DashboardTab']['id']);
		return json_encode($result);
	}

	public function cloneWidgets($sourceTabId, $targetTabId){
		$this->autoRender = false;
		$widgetsToClone = $this->Widget->find('all', [
			'conditions' => [
				'Widget.dashboard_tab_id' => $sourceTabId,
			],
			'contain' => [
				'WidgetTacho',
				'WidgetServiceStatusList',
				'WidgetHostStatusList',
				'WidgetBrowser',
				'WidgetNotice',
				'WidgetGraphgenerator',
			]
		]);

		foreach($widgetsToClone as $widgetToClone){
			$this->Widget->create();
			$data = [
				'Widget' => [
					'dashboard_tab_id' => $targetTabId,
					'type_id' => $widgetToClone['Widget']['type_id'],
					'service_id' => $widgetToClone['Widget']['service_id'],
					'host_id' => $widgetToClone['Widget']['host_id'],
					'map_id' => $widgetToClone['Widget']['map_id'],
					'row' => $widgetToClone['Widget']['row'],
					'col' => $widgetToClone['Widget']['col'],
					'width' => $widgetToClone['Widget']['width'],
					'height' => $widgetToClone['Widget']['height'],
					'title' => $widgetToClone['Widget']['title'],
					'color' => $widgetToClone['Widget']['color']
				]
			];

			$success = $this->Widget->save($data);
			if(!$success){
				debug($this->Widget->validationErrors);
			}

			if($widgetToClone['WidgetTacho']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetTacho' => [
						'widget_id' => $success['Widget']['id'],
						'min' => $widgetToClone['WidgetTacho']['min'],
						'max' => $widgetToClone['WidgetTacho']['max'],
						'warn' => $widgetToClone['WidgetTacho']['warn'],
						'crit' => $widgetToClone['WidgetTacho']['crit'],
						'data_source' => $widgetToClone['WidgetTacho']['data_source']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}

			if($widgetToClone['WidgetServiceStatusList']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetServiceStatusList' => [
						'widget_id' => $success['Widget']['id'],
						'scroll_direction' => $widgetToClone['WidgetServiceStatusList']['scroll_direction'],
						'services_per_page' => $widgetToClone['WidgetServiceStatusList']['services_per_page'],
						'refresh_interval' => $widgetToClone['WidgetServiceStatusList']['refresh_interval'],
						'animation_interval' => $widgetToClone['WidgetServiceStatusList']['animation_interval'],
						'show_ok' => $widgetToClone['WidgetServiceStatusList']['show_ok'],
						'show_warning' => $widgetToClone['WidgetServiceStatusList']['show_warning'],
						'show_critical' => $widgetToClone['WidgetServiceStatusList']['show_critical'],
						'show_unknown' => $widgetToClone['WidgetServiceStatusList']['show_unknown'],
						'show_acknowledged' => $widgetToClone['WidgetServiceStatusList']['show_acknowledged'],
						'show_downtime' => $widgetToClone['WidgetServiceStatusList']['show_downtime']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}

			if($widgetToClone['WidgetHostStatusList']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetHostStatusList' => [
						'widget_id' => $success['Widget']['id'],
						'scroll_direction' => $widgetToClone['WidgetHostStatusList']['scroll_direction'],
						'hosts_per_page' => $widgetToClone['WidgetHostStatusList']['hosts_per_page'],
						'refresh_interval' => $widgetToClone['WidgetHostStatusList']['refresh_interval'],
						'animation_interval' => $widgetToClone['WidgetHostStatusList']['animation_interval'],
						'show_up' => $widgetToClone['WidgetHostStatusList']['show_up'],
						'show_down' => $widgetToClone['WidgetHostStatusList']['show_down'],
						'show_unreachable' => $widgetToClone['WidgetHostStatusList']['show_unreachable'],
						'show_acknowledged' => $widgetToClone['WidgetHostStatusList']['show_acknowledged'],
						'show_downtime' => $widgetToClone['WidgetHostStatusList']['show_downtime']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}

			if($widgetToClone['WidgetBrowser']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetBrowser' => [
						'widget_id' => $success['Widget']['id'],
						'url' => $widgetToClone['WidgetBrowser']['url']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}

			if($widgetToClone['WidgetNotice']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetNotice' => [
						'widget_id' => $success['Widget']['id'],
						'note' => $widgetToClone['WidgetNotice']['note']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}

			if($widgetToClone['WidgetGraphgenerator']['id'] !== null){
				$data = [
					'Widget' => [
						'id' => $success['Widget']['id']
					],
					'WidgetGraphgenerator' => [
						'widget_id' => $success['Widget']['id'],
						'data_sources' => $widgetToClone['WidgetGraphgenerator']['data_sources'],
						'time' => $widgetToClone['WidgetGraphgenerator']['time']
					]
				];
				$success = $this->Widget->saveAll($data);
				if(!$success){
					debug($this->Widget->validationErrors);
				}
			}
		}
	}

	public function renameTab($tabId, $title){
		$this->autoRender = false;
		$data = [
			'DashboardTab' => [
				'id' => $tabId,
				'name' => $title
			]
		];
		$this->DashboardTab->save($data);
	}

	public function refreshTab($widgetId){
		$tabId = $this->Widget->find('first', [
			'fields' => [
				'Widget.dashboard_tab_id',
			],
			'conditions' => [
				'Widget.id' => $widgetId
			],
			'recursive' => -1,
		]);

		$tabName = $this->DashboardTab->find('first', [
			'fields' => [
				'DashboardTab.name',
			],
			'conditions' => [
				'DashboardTab.id' => $tabId['Widget']['dashboard_tab_id']
			],
			'recursive' => -1,
		]);

		$data = [
			'DashboardTab' => [
				'id' => $tabId['Widget']['dashboard_tab_id'],
				'name' => $tabName['DashboardTab']['name']
			]
		];
		$this->DashboardTab->save($data);
	}

	public function setTabRefresh($tabId, $alwaysRefresh = 0){
		$this->autoRender = false;
		$tabName = $this->DashboardTab->find('first', [
			'fields' => [
				'DashboardTab.name',
			],
			'conditions' => [
				'DashboardTab.id' => $tabId
			],
			'recursive' => -1,
		]);

		$data = [
			'DashboardTab' => [
				'id' => $tabId,
				'name' => $tabName['DashboardTab']['name'],
				'check_for_updates' => $alwaysRefresh
			]
		];
		if($this->DashboardTab->save($data)){
			debug('Alles tutti');
		}else{
			debug($this->validationErrors);
		}
	}

	public function shareTab($tabId, $name, $share){
		$this->autoRender = false;
		$data = [
			'DashboardTab' => [
				'id' => $tabId,
				'name' => $name,
				'shared' => $share
			]
		];
		$result = $this->DashboardTab->save($data);
	}

	public function deleteTab($tabId){
		$this->autoRender = false;
		if($this->DashboardTab->exists($tabId)){
			$this->DashboardTab->delete($tabId);
		}
	}

	public function saveTabRotationTime($interval){
		$this->autoRender = false;
		$user = $this->User->findById($this->Auth->user('id'));
		$this->User->id = $this->Auth->user('id');
		if($this->User->saveField('dashboard_tab_rotation', $interval)){
			$sessionUser = $this->Session->read('Auth');
			$merged = Hash::merge($sessionUser, ['dashboard_tab_rotation' => intval($interval)]);
			$this->Session->write('Auth', $merged);
		}
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function getServiceCurrentState($id){
		$this->allowOnlyAjaxRequests();
		$this->autoRender = false;
		$serviceCurrentState = $this->Objects->find('first', [
			'recursive' => -1,
			'conditions' => [
				'Service.id' => $id
			],
			'fields' => [
				'Servicestatus.current_state',
				'Servicestatus.is_flapping',
				'Service.check_interval',
				'Servicetemplate.check_interval',
			],
			'joins' => [
				[
					'table' => 'services',
					'alias' => 'Service',
					'conditions' => [
						'Objects.name2 = Service.uuid',
					]
				],
				[
					'table' => 'servicetemplates',
					'type' => 'INNER',
					'alias' => 'Servicetemplate',
					'conditions' => [
						'Servicetemplate.id = Service.servicetemplate_id',
					]
				],
				[
					'table' => 'nagios_servicestatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Servicestatus',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				]
			],
			'order' => [
				'Servicestatus.current_state DESC'
			]
		]);
		return json_encode($serviceCurrentState);
	}

	public function getServicePerfData($id){
		$this->allowOnlyAjaxRequests();
		$this->autoRender = false;
		$servicePerfData = $this->Objects->find('first', [
			'recursive' => -1,
			'conditions' => [
				'Service.id' => $id
			],
			'fields' => [
				'Servicestatus.perfdata',
			],
			'joins' => [
				[
					'table' => 'services',
					'alias' => 'Service',
					'conditions' => [
						'Objects.name2 = Service.uuid',
					]
				],
				[
					'table' => 'servicetemplates',
					'type' => 'INNER',
					'alias' => 'Servicetemplate',
					'conditions' => [
						'Servicetemplate.id = Service.servicetemplate_id',
					]
				],
				[
					'table' => 'nagios_servicestatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Servicestatus',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				]
			],
			'order' => [
				'Servicestatus.current_state DESC'
			]
		]);
		//debug($servicePerfData);
		if($servicePerfData['Servicestatus']['perfdata'] !== null){
			$servicePerfData = $this->Rrd->parsePerfData($servicePerfData['Servicestatus']['perfdata']);
			$subservice_for_select = [];
			foreach($servicePerfData as $key => $subservice){
				$subservice_for_select[] = $key;
			}
		}else{
			$servicePerfData = false;
		}
		return json_encode($servicePerfData);
	}

	public function statusListServices($showOK='',$showWarning='',$showCrit='',$showUnknown='',$showAcknowledged='',$showDowntime=''){

		$statesArray = [];
		$extraConditions = [];

		if($showOK === 'true'){
			$statesArray[] = '0';
		}
		if($showWarning === 'true'){
			$statesArray[] = '1';
		}
		if($showCrit === 'true'){
			$statesArray[] = '2';
		}
		if($showUnknown === 'true'){
			$statesArray[] = '3';
		}

		if($showAcknowledged === 'true'){
			$extraConditions[] = '1';
		}else{
			$extraConditions[] = '0';
		}
		if($showDowntime === 'true'){
			$extraConditions[] = '0';
			$extraConditionsString = implode(' AND Servicestatus.scheduled_downtime_depth > ',$extraConditions);
		}else{
			$extraConditions[] = '0';
			$extraConditionsString = implode(' AND Servicestatus.scheduled_downtime_depth = ',$extraConditions);
		}
		//debug($extraConditions);
		$statesToSelect = implode(' OR current_state = ',$statesArray);

		if($statesToSelect === ''){
			$statesToSelect = '4';
		}

		$servicestatus = $this->Servicestatus->find('all', [
			'recursive' => -1,
			'fields' => [
				'Host.name',
				'Host.id',
				'Service.uuid',
				'Service.name',
				'Service.id',
				'Servicetemplate.name',
				'Servicestatus.current_state',
				'Servicestatus.status_update_time',
				'Servicestatus.is_flapping',
				'Servicestatus.active_checks_enabled',
				'Servicestatus.last_hard_state_change',
				'Servicestatus.problem_has_been_acknowledged',
				'Servicestatus.scheduled_downtime_depth'
			],
			'conditions' => [
				'AND' => [
					'Servicestatus.current_state = '.$statesToSelect,
					'Servicestatus.problem_has_been_acknowledged = '.$extraConditionsString,
				],
			],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'Objects',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				],
				[
					'table' => 'services',
					'type' => 'INNER',
					'alias' => 'Service',
					'conditions' => 'Service.uuid = Objects.name2'
				],
				[
					'table' => 'servicetemplates',
					'type' => 'INNER',
					'alias' => 'Servicetemplate',
					'conditions' => 'Servicetemplate.id = Service.servicetemplate_id'
				],
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Host.id = Service.host_id'
				],
			]
		]);
		$this->set(compact('servicestatus'));
		return json_encode($servicestatus);
	}

	public function statusListHosts($showUp='',$showDown='',$showUnreachable='',$showAcknowledged='',$showDowntime=''){
		$statesArray = [];
		$extraConditions = [];

		if($showUp === 'true'){
			$statesArray[] = '0';
		}
		if($showDown === 'true'){
			$statesArray[] = '1';
		}
		if($showUnreachable === 'true'){
			$statesArray[] = '2';
		}

		if($showAcknowledged === 'true'){
			$extraConditions[] = '1';
		}else{
			$extraConditions[] = '0';
		}
		if($showDowntime === 'true'){
			$extraConditions[] = '0';
			$extraConditionsString = implode(' AND Hoststatus.scheduled_downtime_depth > ',$extraConditions);
		}else{
			$extraConditions[] = '0';
			$extraConditionsString = implode(' AND Hoststatus.scheduled_downtime_depth = ',$extraConditions);
		}

		$statesToSelect = implode(' OR current_state = ',$statesArray);

		if($statesToSelect === ''){
			$statesToSelect = '4';
		}

		$hoststatus = $this->Hoststatus->find('all', [
			'recursive' => -1,
			'fields' => [
				'Host.name',
				'Host.id',
				'Host.uuid',
				'Hoststatus.current_state',
				'Hoststatus.status_update_time',
				'Hoststatus.is_flapping',
				'Hoststatus.active_checks_enabled',
				'Hoststatus.problem_has_been_acknowledged',
				'Hoststatus.last_hard_state_change',
				'Hoststatus.scheduled_downtime_depth'
			],
			'conditions' => [
				'AND' => [
					'Hoststatus.current_state = '.$statesToSelect,
					'Hoststatus.problem_has_been_acknowledged = '.$extraConditionsString,
				],
			],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'Objects',
					'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
				],
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Host.uuid = Objects.name1'
				],
			]
		]);
		$this->set(compact('hoststatus'));
		return json_encode($hoststatus);
	}

	public function browser($widgetId){
		$this->autoRender = false;
	}

	public function getAllRelatedInfoForService($serviceId){
		$this->autoRender = false;
		$service = $this->Objects->find('first', [
			'recursive' => -1,
			'fields' => [
				'Service.uuid',
				'Service.name',
				'Service.check_interval',
				'Servicetemplate.name',
				'Servicetemplate.check_interval',
				'Host.name',
				'Host.uuid',
				'Servicestatus.perfdata',
			],
			'conditions' => [
				'Service.id' => $serviceId
			],
			'joins' => [
				[
					'table' => 'services',
					'alias' => 'Service',
					'conditions' => [
						'Objects.name2 = Service.uuid',
					]
				],
				[
					'table' => 'hosts',
					'alias' => 'Host',
					'conditions' => [
						'Objects.name1 = Host.uuid',
					]
				], [
					'table' => 'servicetemplates',
					'type' => 'INNER',
					'alias' => 'Servicetemplate',
					'conditions' => [
						'Servicetemplate.id = Service.servicetemplate_id',
					]
				], [
					'table' => 'nagios_servicestatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Servicestatus',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				]
			],
		]);

		if($service['Servicestatus']['perfdata'] !== null){
			$service['Servicestatus']['perfdata'] = $this->Rrd->parsePerfData($service['Servicestatus']['perfdata']);
		}else{
			$service = false;
		}
		return json_encode($service);
	}

	public function fetchGraphData(){
		$this->allowOnlyAjaxRequests();
		$result = [];
		$this->set('rrd_data', $result);
		$this->set('_serialize', ['rrd_data']);

		// Validate input.
		$host_and_service_uuids = $this->request->data('host_and_service_uuids');
		if(!$host_and_service_uuids || !is_array($host_and_service_uuids) || count($host_and_service_uuids) == 0){
			return;
		}
		foreach($host_and_service_uuids as $host_uuid => $service_uuids){
			if(!UUID::is_valid($host_uuid)){
				return;
			}

			foreach($service_uuids as $service_uuid){
				if(!UUID::is_valid($service_uuid)){
					return;
				}
			}
		}

		$service_uuid_amount = 0;
		foreach($host_and_service_uuids as $service_uuids){
			$service_uuid_amount += count($service_uuids);
		}
		$limit = (int) (self::MAX_RESPONSE_GRAPH_POINTS / $service_uuid_amount);

		$options = [
			'start' => time() - 2 * 3600,
			'end' => time(),
		];
		if(is_numeric($this->request->data('start')) && is_numeric($this->request->data('end'))){
			// A unix timestamp is expected here for 'start' and 'end'.
			$options = [
				'start' => $this->request->data('start'),
				'end' => $this->request->data('end'),
			];
		}

		foreach($host_and_service_uuids as $host_uuid => $service_uuids){
			foreach($service_uuids as $service_uuid){
				$rrd_data = $this->Rrd->getPerfDataFiles($host_uuid, $service_uuid, $options);
				$data_sources_count = count($rrd_data['data']);
				$tmp_limit = $limit / $data_sources_count;
				foreach($rrd_data['data'] as $key => $value_array){
					// Limit the returned data to prevent client performance issues.
					$rrd_data['data'][$key] = $this->reduceData($rrd_data['data'][$key], $tmp_limit, self::REDUCE_METHOD_AVERAGE);
				}

				// Add hostname
				$additional_information['hostname'] = $this->Host->findByUuid($host_uuid)['Host']['name'];
				// Add servicename
				$service = $this->Service->findByUuid($service_uuid);
				$service_name = $service['Service']['name'] != '' ?
					$service['Service']['name'] : $service['Servicetemplate']['name'];
				$additional_information['servicename'] = $service_name;

				$result[$host_uuid][$service_uuid] = array_merge($rrd_data, $additional_information);
			}
		}

		$this->set('rrd_data', $result);
	}

	public function servicestatus($arr){
		$state = [
			0 => [
				'human_state' => __('Ok'),
				'image' => 'up.png',
			],
			1 => [
				'human_state' => __('Warning'),
				'image' => 'down.png',
			],
			2 => [
				'human_state' => __('Critical'),
				'image' => 'critical.png',
			],
			3 => [
				'human_state' => __('Unreachable'),
				'image' => 'unreachable.png',
			]
		];
		return [
			'state' => $arr['state'],
			'is_flapping' => $arr['is_flapping'],
			'human_state' => $state[$arr['state']]['human_state'],
			'image' => $state[$arr['state']]['image'],
			'perfdata' => $arr['perfdata']
		];
	}

	public function hoststatus($arr){
		$state = [
			0 => [
				'human_state' => __('Up'),
				'image' => 'up.png',
			],
			1 => [
				'human_state' => __('Down'),
				'image' => 'down.png',
			],
			2 => [
				'human_state' => __('Unreachable'),
				'image' => 'unreachable.png',
			]
		];
		return [
			'state' => $arr['state'],
			'is_flapping' => $arr['is_flapping'],
			'human_state' => $state[$arr['state']]['human_state'],
			'image' => $state[$arr['state']]['image'],
			'perfdata' => $arr['perfdata']
		];
	}

	public function servicegroupstatus($arr){

	}

	public function hostgroupstatus($arr){

	}

	public function parsePerfData($perfdata_string){
		$perfdata = array();
		$perf_data_structure = array('label','current_value','unit', 'warning', 'critical', 'min', 'max');
		$i = 0;
		foreach(explode(" ", $perfdata_string) as $data_set){
			foreach(explode(';', $data_set) as $value){
				if(preg_match('/=/', $value)){
					$s = preg_split('/=/', $value);
					if(isset($s[0])){
						$perfdata[$i][]  = $s[0];
						$number = '';
						$unit = '';
						foreach(str_split($s[1]) as $char ){
							if( $char == '.' || $char == ',' || ($char >= '0' && $char <= '9') ){
									$number .= $char;
							}
							else{
									$unit .= $char;
							}
						}
						$perfdata[$i][] = str_replace(',', '.', $number);
						$perfdata[$i][] = $unit;
						continue;
					}
				}
				if(isset($s[0])){
					$perfdata[$i][] = $value;
				}
			}
			if(isset($s[0])){
					$perfdata[$i] = array_combine($perf_data_structure, array_merge($perfdata[$i], (( sizeof($perf_data_structure)-sizeof($perfdata[$i]) )>0) ? array_fill(sizeof($perfdata[$i]),(sizeof($perf_data_structure)-sizeof($perfdata[$i])),''):array()));
					unset($s);
			}
			$i++;
		}
		return($perfdata);
	}

	public function parseMarkdown(){
		$this->autoRender = false;
		$stringToParse = $this->request->data('notice');

		require_once APP . 'Vendor' . DS . 'parsedown' . DS . 'Parsedown.php';
		require_once APP . 'Vendor' . DS . 'parsedown' . DS . 'ParsedownExtra.php';

		$parsedown = new ParsedownExtra();
		$parsedMarkdown = $parsedown->text($stringToParse);

		return $parsedMarkdown;
	}

	public function getAllServiceWithCurrentState($containerIds){


		if(!is_array($containerIds)){
			$containerIds = [$containerIds];
		}

		$results = $this->Host->find('all', [
			'recursive' => '-1',
			'fields' => [
				'Host.id',
				'Host.name',
				'Service.name',
				'Service.id',
				'Service.uuid',
				'Servicetemplate.name',
				'Servicestatus.current_state',
				'Servicestatus.perfdata',
			],
			'joins' => [
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'INNER',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				],
				[
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'LEFT',
					'conditions' => [
						'Service.host_id = Host.id',
					]
				],
				[
					'table' => 'servicetemplates',
					'alias' => 'Servicetemplate',
					'type' => 'INNER',
					'conditions' => [
						'Servicetemplate.id = Service.servicetemplate_id',
					]
				],
				[
					'table' => 'nagios_objects',
					'alias' => 'Objects',
					'type' => 'INNER',
					'conditions' => [
						'Objects.name2 = Service.uuid',
					]
				],
				[
					'table' => 'nagios_servicestatus',
					'alias' => 'Servicestatus',
					'type' => 'INNER',
					'conditions' => [
						'Servicestatus.service_object_id = Objects.object_id',
					]
				],

			],
			'conditions' => [
				'HostsToContainers.container_id' => $containerIds,
				'Host.disabled' => 0
			]
		]);


		$return = [];
		foreach($results as $result){

			if($result['Service']['name'] == null || $result['Service']['name'] == ''){
				$result['Service']['name'] = $result['Servicetemplate']['name'];
			}
			$return[$result['Service']['id']] = $result['Host']['name'] ." | ". $result['Service']['name'];
		}
		return $return;
	}

	public function saveTabOrder(){
		$this->autoRender = false;
		//debug($this->request->data);
		foreach($this->request->data as $tab){
			$data = [
				'DashboardTab' => [
					'id' => $tab['id'],
					'name' => $tab['name'],
					'position' =>$tab['position']
				]
			];
			$this->DashboardTab->save($data);
		}
	}

}
