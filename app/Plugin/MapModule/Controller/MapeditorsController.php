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
 * @property Mapeditor Mapeditor
 * @property Mapitem Mapitem
 * @property Mapline Mapline
 * @property Mapgadget Mapgadget
 * @property Mapicon Mapicon
 * @property Maptext Maptext
 * @property Host Host
 * @property Hostgroup Hostgroup
 * @property Service Service
 * @property Servicegroup Servicegroup
 * @property Background Background
 * @property Map Map
 */
class MapeditorsController extends MapModuleAppController {
	public $layout = 'Admin.default';
	public $uses = [
		'MapModule.Mapeditor',
		'MapModule.Mapitem',
		'MapModule.Mapline',
		'MapModule.Mapgadget',
		'MapModule.Mapicon',
		'MapModule.Maptext',
		'Host',
		'Hostgroup',
		'Service',
		'Servicegroup',
		'MapModule.Background',
		'MapModule.Map',
		MONITORING_OBJECTS,
		MONITORING_HOSTSTATUS,
	];
	public $helpers = [
		'MapModule.Mapstatus',
		'Perfdata',
	];

	public function index(){
		$this->__checkForGD();
	}

	protected function __checkForGD(){
		if(!extension_loaded('gd') || !function_exists('gd_info')){
			throw new InternalErrorException(__('php5-gd not installed'));
		}
	}

	public function edit($id=null){
		$this->__checkForGD();
		if(!$this->Map->exists($id)){
			throw new NotFoundException(__('Invalid map'));
		}

		$map = $this->Map->find('first', [
			'conditions' => [
				'Map.id' => $id,
			],
			'contain' => [
				'Mapitem',
				'Mapline',
				'Mapgadget',
				'Mapicon',
				'Maptext'
			]
		]);
		$maps = $this->Map->find('all');
		$maps = Hash::remove($maps, '{n}.Tenant');

		$mapList = Hash::combine($maps, '{n}.Map.id', '{n}.Map.name');

		if($this->request->is('post') || $this->request->is('put')){
			$request = $this->Mapeditor->prepareForSave($this->request->data);

			//@TODO deleteAll is not the good way to refresh the map items
			//-> pass the itemId and lineId as hidden field!
			//Delete old map items
			$this->Mapitem->deleteAll(['Mapitem.map_id' => $map['Map']['id']]);
			$this->Mapline->deleteAll(['Mapline.map_id' => $map['Map']['id']]);
			$this->Mapgadget->deleteAll(['Mapgadget.map_id' => $map['Map']['id']]);
			$this->Mapicon->deleteAll(['Mapicon.map_id' => $map['Map']['id']]);
			$this->Maptext->deleteAll(['Maptext.map_id' => $map['Map']['id']]);
			if($this->Map->saveAll($request)){
				if($this->request->ext === 'json'){
					$this->serializeId();
					return;
				}
				$this->setFlash(__('Map modified successfully'));
				$this->redirect(['plugin' => 'map_module', 'controller' => 'maps', 'action' => 'index']);
			}else{
				if($this->request->ext === 'json'){
					$this->serializeErrorMessage();
					return;
				}
				$this->setFlash(__('Data could not be saved'), false);
			}
		}

		$this->Frontend->setJson('lang_minutes', __('minutes'));
		$this->Frontend->setJson('lang_seconds', __('seconds'));
		$this->Frontend->setJson('lang_and', __('and'));
		$this->Frontend->setJson('map_lines', Hash::Extract($map, 'Mapline.{n}'));
		$this->Frontend->setJson('map_gadgets', Hash::Extract($map, 'Mapgadget.{n}'));

		$hosts = $this->Host->hostsByContainerId($this->MY_RIGHTS, 'list');
		$services = $this->Service->servicesByHostContainerIds($this->MY_RIGHTS, 'list');
		$hostgroup = $this->Hostgroup->hostgroupsByContainerId($this->MY_RIGHTS, 'list', 'id');
		$servicegroup = $this->Servicegroup->servicegroupsByContainerId($this->MY_RIGHTS, 'list');

		$backgroundThumbs = $this->Background->findBackgrounds();
		$iconSets = $this->Background->findIconsets();
		$icons = $this->Background->findIcons();
		$this->set(compact(['map', 'maps', 'mapList', 'servicegroup', 'hostgroup', 'hosts', 'services','backgroundThumbs', 'iconSets', 'icons']));
	}

	public function view($id = null){
		$rotate = null;
		if(isset($this->request->params['named']['rotate'])){
			$isFirst = true;
			$rotation = [];
			foreach($this->request->params['named']['rotate'] as $rotation_map_id){
				if($isFirst === true){
					$id = $rotation_map_id;
					$isFirst = false;
				}else{
					$rotation[] = $rotation_map_id;
				}
			}

			//Add the current map id as the last element in rotation array, to rotate
			$rotation[] = $id;
			$this->Frontend->setJson('rotation_ids', $rotation);
			$this->Frontend->setJson('interval', $this->request->params['named']['interval']);

		}else{
			$this->Frontend->setJson('interval', 0);
		}

		if(!$this->Map->exists($id)){
			throw new NotFoundException(__('Invalid map'));
		}

		$isFullscreen = false;
		if(isset($this->request->params['named']['fullscreen'])){
			$this->layout = 'Admin.fullscreen';
			$isFullscreen = true;
			$this->Frontend->setJson('is_fullscren', true);
		}

		$map = $this->Map->findById($id);
		$_map = Hash::extract($map, 'Map');

		$map_items = $this->Mapitem->find('all', [
			//'recursive' => -1,
			'joins' => [
				[
					'table' => 'hosts',
					'alias' => 'Host',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Host.id = Mapitem.object_id',
								'Mapitem.type' => 'host'
							]
						]
					],
				],
				[
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Service.id = Mapitem.object_id',
								'Mapitem.type' => 'service'
							]
						]
					],
				],
				[
					'table' => 'hostgroups',
					'alias' => 'Hostgroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Hostgroup.id = Mapitem.object_id',
								'Mapitem.type' => 'hostgroup'
							]
						]
					],
				],
				[
					'table' => 'servicegroups',
					'alias' => 'Servicegroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Servicegroup.id = Mapitem.object_id',
								'Mapitem.type' => 'servicegroup'
							]
						]
					],
				],
					[
					'table' => 'maps',
					'alias' => 'SubMap',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'SubMap.id = Mapitem.object_id',
								'Mapitem.type' => 'map'
							]
						]
					],
				],
			],
			'conditions' => [
				'Mapitem.map_id' => $id,
			],
			'fields' => [
				'Mapitem.*','Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*', 'SubMap.*'
			]
		]);

		$map_lines = $this->Mapline->find('all', [
			'joins' => [
				[
					'table' => 'hosts',
					'alias' => 'Host',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Host.id = Mapline.object_id',
								'Mapline.type' => 'host'
							]
						]
					],
				],
				[
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Service.id = Mapline.object_id',
								'Mapline.type' => 'service'
							]
						]
					],
				],
				[
					'table' => 'hostgroups',
					'alias' => 'Hostgroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Hostgroup.id = Mapline.object_id',
								'Mapline.type' => 'hostgroup'
							]
						]
					],
				],
				[
					'table' => 'servicegroups',
					'alias' => 'Servicegroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Servicegroup.id = Mapline.object_id',
								'Mapline.type' => 'servicegroup'
							]
						]
					],
				],
			],
			'conditions' => [
				'Mapline.map_id' => $id,
			],
			'fields' => [
				'Mapline.*','Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*'
			]
		]);

		$map_gadgets = $this->Mapgadget->find('all', [
			'joins' => [
				[
					'table' => 'hosts',
					'alias' => 'Host',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Host.id = Mapgadget.object_id',
								'Mapgadget.type' => 'host'
							]
						]
					],
				],
				[
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Service.id = Mapgadget.object_id',
								'Mapgadget.type' => 'service'
							]
						]
					],
				],
				[
					'table' => 'hostgroups',
					'alias' => 'Hostgroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Hostgroup.id = Mapgadget.object_id',
								'Mapgadget.type' => 'hostgroup'
							]
						]
					],
				],
				[
					'table' => 'servicegroups',
					'alias' => 'Servicegroup',
					'type' => 'LEFT OUTER',
					'conditions' => [
						[
							'AND' => [
								'Servicegroup.id = Mapgadget.object_id',
								'Mapgadget.type' => 'servicegroup'
							]
						]
					],
				],
			],
			'conditions' => [
				'Mapgadget.map_id' => $id,
			],
			'fields' => [
				'Mapgadget.*','Host.*', 'Hostgroup.*', 'Service.*', 'Servicegroup.*'
			]
		]);

		$map_texts = $this->Maptext->find('all',[
			'conditions' => [
				'map_id' => $id
			]
		]);

		//keep the null values out
		$map_items = Hash::filter($map_items);
		$map_lines = Hash::filter($map_lines);
		$map_gadgets = Hash::filter($map_gadgets);

		$hostUuids = Hash::extract($map_items, '{n}.Host.uuid');
		$serviceUuids = Hash::extract($map_items, '{n}.Service.uuid');
		$hostgroupUuids = Hash::extract($map_items, '{n}.Hostgroup.uuid');
		$servicegroupUuids = Hash::extract($map_items, '{n}.Servicegroup.uuid');
		$mapIds = Hash::extract($map_items, '{n}.SubMap.id');

		$hostLineUuids = Hash::extract($map_lines, '{n}.Host.uuid');
		$serviceLineUuids = Hash::extract($map_lines, '{n}.Service.uuid');
		$hostgroupLineUuids = Hash::extract($map_lines, '{n}.Hostgroup.uuid');
		$servicegroupLineUuids = Hash::extract($map_lines, '{n}.Servicegroup.uuid');

		$hostGadgetUuids = Hash::extract($map_gadgets, '{n}.Host.uuid');
		$serviceGadgetUuids = Hash::extract($map_gadgets, '{n}.Service.uuid');
		$hostgroupGadgetUuids = Hash::extract($map_gadgets, '{n}.Hostgroup.uuid');
		$servicegroupGadgetUuids = Hash::extract($map_gadgets, '{n}.Servicegroup.uuid');

		//merge the LineUuids and the item uuids
		$hostUuids = Hash::merge($hostUuids, $hostLineUuids, $hostGadgetUuids);
		$serviceUuids = Hash::merge($serviceUuids, $serviceLineUuids, $serviceGadgetUuids);
		$hostgroupUuids = Hash::merge($hostgroupUuids, $hostgroupLineUuids, $hostgroupGadgetUuids);
		$servicegroupUuids = Hash::merge($servicegroupUuids, $servicegroupLineUuids, $servicegroupGadgetUuids);

		$this->__unbindAssociations('Objects');


		//just the Hosts
		if(count($hostUuids) > 0){
			$hoststatus = $this->Objects->find('all', [
				'conditions' => [
					'name1' => $hostUuids,
					'objecttype_id' => 1
				],
				'fields' => [
					'Objects.*',
					'Hoststatus.*'
				],
				'joins' => [
					[
						'table' => 'nagios_hoststatus',
						'type' => 'LEFT OUTER',
						'alias' => 'Hoststatus',
						'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
					],
				]
			]);

			$currentHostUuids = Hash::extract($hoststatus,'{n}.Objects.name1');

			foreach($currentHostUuids as $key => $currentHostUuid){
				$hostServiceStatus = $this->Objects->find('all',[
					'recursive' => -1,
					'conditions' => [
						'name1' => $currentHostUuid,
						'objecttype_id' => 2
					],
					'fields' => [
						'Objects.*',
						'Servicetemplate.name',
						'Servicetemplate.description',
						'Servicestatus.*',
						'Service.name',
						'Service.description',
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
					]
				]);
				$hoststatus[$key]['Hoststatus']['Servicestatus'] = $hostServiceStatus;
			}
			//$mapstatus['hoststatus'] = $hoststatus;
		}

		//just the hostgroups
		if(count($hostgroupUuids) > 0){
			$hostgroups = [];
			foreach ($hostgroupUuids as $k => $hostgroupUuid) {
				$hostgroup = $this->Hostgroup->find('first',[
					'recursive' => -1,
					'conditions' => [
						'uuid' => $hostgroupUuid,
					],
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						],
						'Host' =>[
							'fields' => [
								'Host.name',
								'Host.uuid',
								'Host.description',
								'Host.address'
							]
						]
					],
					'fields' => [
						'Hostgroup.*'
					]
				]);
				$hostgroups[$k] = $hostgroup;
				$currentHostgroupHostUuids = Hash::extract($hostgroup, 'Host.{n}.uuid');
				$hostgroupHoststatus = [];
				$hostgroupServicestatus = [];

				foreach ($currentHostgroupHostUuids as $key => $currentHostgroupHostUuid) {
					$hostgroupHoststatus = $this->Objects->find('first', [
						'conditions' => [
							'name1' => $currentHostgroupHostUuid,
							'objecttype_id' => 1
						],
						'fields' => [
							'Objects.*',
							'Hoststatus.current_state',
						],
						'joins' => [
							[
								'table' => 'nagios_hoststatus',
								'type' => 'LEFT OUTER',
								'alias' => 'Hoststatus',
								'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
							],
						]
					]);

					$hostgroupServicestatus = $this->Objects->find('all', [
						'recursive' => -1,
						'conditions' => [
							'name1' => $currentHostgroupHostUuid,
							'objecttype_id' => 2
						],
						'fields' => [
							'Objects.*',
							'Servicetemplate.name',
							'Servicetemplate.description',
							'Servicestatus.current_state',
							'Service.name',
							'Service.description',
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
						]
					]);
					$hostgroups[$k]['Host'][$key]['Hoststatus'] = $hostgroupHoststatus;
					$hostgroups[$k]['Host'][$key]['Servicestatus'] = $hostgroupServicestatus;

				}
			}
			//$mapstatus['hostgroupstatus'] = $hostgroups;
		}

		//just the Servicegroups
		if(count($servicegroupUuids) > 0){
			$servicegroups = [];
			foreach ($servicegroupUuids as $k => $servicegroupUuid) {
				$servicegroup = $this->Servicegroup->find('first',[
					'recursive' => -1,
					'conditions' => [
						'uuid' => $servicegroupUuid,
					],
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						],
						'Service' => [
							'fields' => [
								'Service.*'
							],
						],
					],
				]);
				$servicegroups[$k] = $servicegroup;

				$currentServicegroupServiceUuids = Hash::extract($servicegroup, 'Service.{n}.uuid');

				foreach ($currentServicegroupServiceUuids as $key => $currentServicegroupServiceUuid) {
					$servicestate = $this->Objects->find('first', [
						'recursive' => -1,
						'conditions' => [
							'name2' => $currentServicegroupServiceUuid,
							'objecttype_id' => 2
						],
						'fields' => [
							'Objects.*',
							'Servicetemplate.name',
							'Servicetemplate.description',
							'Servicestatus.*',
							'Service.name',
							'Service.description',
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
						]
					]);

					$servicegroups[$k]['Servicegroup']['Servicestatus'][$key] = $servicestate;
				}

			}
			//$mapstatus['servicegroupstatus'] = $servicegroups;
		}

		//just the Services
		if(count($serviceUuids) > 0){
			$this->loadModel('Service');
			$servicestatus = $this->Objects->find('all', [
				'recursive' => -1,
				'conditions' => [
					'name2' => $serviceUuids,
					'objecttype_id' => 2
				],
				'fields' => [
					'Objects.*',
					'Servicetemplate.name',
					'Servicetemplate.description',
					'Servicestatus.*',
					'Service.name',
					'Service.description',
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
				]
			]);
			//$mapstatus['servicestatus'] = $servicestatus;
		}

		//insert the Host UUID into the servicegadgets (eg. for RRDs)
		foreach ($serviceGadgetUuids as $key => $serviceGadgetUuid) {
			$map_gadgets[$key]['Service']['host_uuid'] = $this->hostUuidFromServiceUuid($serviceGadgetUuid)[0];
		}

		$backgroundThumbs = $this->Background->findBackgrounds();
		$iconSets = $this->Background->findIconsets();
		$icons = $this->Background->findIcons();
		if(!empty($map_lines)){
			$this->Frontend->setJson('map_lines', Hash::Extract($map_lines, '{n}.Mapline'));
		}

		if(!empty($map_gadgets)){
			$this->Frontend->setJson('map_gadgets', Hash::Extract($map_gadgets, '{n}.Mapgadget'));
		}
		
		foreach ($mapIds as $id) {
			$mapstatus[$id] = $this->Mapeditor->mapStatus($id);
		}
		$this->set(compact(['map', 'map_items', 'map_lines', 'map_gadgets', 'map_texts', 'backgroundThumbs', 'iconSets', 'mapstatus', 'hoststatus', 'servicestatus', 'hostgroups', 'servicegroups', 'isFullscreen', 'icons']));
	}

	public function hostUuidFromServiceUuid($serviceUuid = null){

		$hostUuid = $this->Service->find('first',[
			'conditions' => [
				'Service.uuid' => $serviceUuid
			],
			'fields' => [
				'Service.uuid'
			],
			'contain' => [
				'Host' => [
					'fields' => [
						'Host.uuid'
					]
				],
			]
		]);

		$hostUuid = Hash::extract($hostUuid, 'Host.uuid');

/*
		$hostUuid = $this->Objects->find('first',[
			'recursive' => -1,
			'conditions' => [
				'name2' => $serviceUuid,
			],
			'fields' => [
				'Objects.name1',
			]
		]);
		$hostUuid = Hash::extract($hostUuid, 'Objects.name1');*/
		return $hostUuid;
	}

	public function fullscreen($id = null){
		$this->layout = '';
		$this->view($id);
		$this->render('view');
	}

	public function popoverHostStatus($uuid = null){
		$hoststatusFields = [
			'Host.name',
			'Host.description',
			'Host.address',
			'Hoststatus.output',
			'Hoststatus.long_output',
			'Hoststatus.last_check',
			'Hoststatus.next_check',
			'Hoststatus.last_state_change',
			'Hoststatus.problem_has_been_acknowledged',
			'Hoststatus.scheduled_downtime_depth',
			'Hoststatus.is_flapping',
			'Hoststatus.current_check_attempt',
			'Hoststatus.max_check_attempts'
		];
		$hoststatus = $this->Mapeditor->getHoststatusByUuid($uuid, $hoststatusFields);

		$servicestatusFields = [
			'Objects.name2',
			'Servicestatus.problem_has_been_acknowledged',
			'Servicestatus.scheduled_downtime_depth',
			'Servicestatus.is_flapping',
			'Servicestatus.perfdata',
			'Servicestatus.output',
			'Service.name', // may obsolete .. just mapstatushelper is using that 
			'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that 
			'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
			'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
		];
		$servicestatus = $this->Mapeditor->getServicestatusByHostUuid($uuid, $servicestatusFields);
		$this->set(compact(['uuid', 'hoststatus', 'servicestatus']));
	}

	public function popoverServicegroupStatus($uuid = null){
		$fields = [];
		$servicegroups = $this->Mapeditor->getServicegroupstatusByUuid($uuid, $fields);
		$this->set(compact(['uuid', 'servicegroups']));
	}

	public function popoverHostgroupStatus($uuid = null){
		$hostFields = [
			'Hoststatus.current_state',
			'Hoststatus.problem_has_been_acknowledged',
			'Hoststatus.scheduled_downtime_depth',
			'Hoststatus.is_flapping',
		];
		$serviceFields = [
			'Servicestatus.current_state',
			//'Servicestatus.'
		];
		$hostgroups = $this->Mapeditor->getHostgroupstatusByUuid($uuid, $hostFields, $serviceFields);
		$this->set(compact(['hostgroups']));
	}


	public function popoverServiceStatus($uuid = null){
		$fields = [
			'Host.name',
			'Objects.name2',
			'Service.name', // may obsolete .. just mapstatushelper is using that 
			'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that 
			'Servicestatus.problem_has_been_acknowledged',
			'Servicestatus.scheduled_downtime_depth',
			'Servicestatus.is_flapping',
			'Servicestatus.perfdata',
			'Servicestatus.output',
			'Servicestatus.long_output',
			'Servicestatus.current_check_attempt',
			'Servicestatus.max_check_attempts',
			'Servicestatus.last_check',
			'Servicestatus.next_check',
			'Servicestatus.last_state_change',
			'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
			'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
		];

		$servicestatus = $this->Mapeditor->getServicestatusByUuid($uuid, $fields);
		$this->set(compact('uuid','servicestatus'));
	}

	public function popoverMapStatus($id){
		$mapstatus = $this->Mapeditor->mapStatus($id);
		$mapinfo = $this->Map->find('first',[
			'recursive' => -1,
			'conditions' => [
				'Map.id' => $id
			],
			'fields' => [
				'Map.id',
				'Map.name',
				'Map.title'
			]
		]);
		$mapstatus[$id] = $mapstatus;
		$this->set(compact('mapstatus', 'mapinfo'));
	}


	public function servicesForWizard($hostId = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(!$this->Host->exists($hostId)){
			throw new NotFoundException(__('Invalid service'));
		}
		$services = $this->Service->find('all', [
			'recursive' => -1,
			'contain' => [
				'Servicetemplate' => [
					'fields' => ['Servicetemplate.name']
				],
				'Host' => [
					'fields' => ['Host.name', 'Host.uuid']
				]
			],
			'fields' => [
				'Service.id',
				'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceDescription'
			],
			'order' => [
				'Service.name ASC', 'Servicetemplate.name ASC'
			],
			'conditions' => [
				'Host.id' => $hostId
			]
		]);
		$this->set(compact(['services']));
	}

	public function hostFromService($serviceId = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		if(!$this->Service->exists($serviceId)){
			throw new NotFoundException(__('Invalid service'));
		}
		$service = $this->Service->find('first', [
			'recursive' => -1,
			'conditions' => [
				'Service.id' => $serviceId,
			],
			'contain' => [
				'Host'
			],
			'fields' => [
				'Host.id'
			]
		]);
		$this->set(compact('service'));
	}
}
