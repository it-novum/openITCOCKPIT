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
 * @property Service       $Service
 * @property Servicegroup  $Servicegroup
 * @property Host          $Host
 * @property TreeComponent $Tree
 */
class ServicegroupsController extends AppController{
	public $uses = [
		'Servicegroup',
		'Container',
		'Service',
		'User',
		MONITORING_OBJECTS,
		'Host',
	];
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
	];
	public $helpers = ['ListFilter.ListFilter'];

	public $listFilters = [
		'index' => [
			'fields' => [
				'Container.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
				'Servicegroup.description' => ['label' => 'Alias', 'searchType' => 'wildcard'],
			],
		]
	];

	public function index(){
		$conditions = [
			'Container.parent_id' => $this->MY_RIGHTS
		];
		if(isset($this->Paginator->settings['conditions'])){
			$conditions = Hash::merge($this->Paginator->settings['conditions'], $conditions);
		}
		$query = [
			'recursive' => -1,
			'joins'	=> [
				[
					'table' => 'containers',
					'alias' => 'Container',
					'type' => 'LEFT',
					'conditions' => [
						'Servicegroup.container_id = Container.id'
					]
				],
				[
					'table' => 'services_to_servicegroups',
					'alias' => 'servicesToServicegroups',
					'type' => 'LEFT',
					'conditions' => [
						'servicesToServicegroups.servicegroup_id = Servicegroup.id'
					]
				],
				[
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'LEFT',
					'conditions' => [
						'Service.id = servicesToServicegroups.service_id'
					]
				],
				[
					'table' => 'servicetemplates',
					'alias' => 'Servicetemplate',
					'type' => 'INNER',
					'conditions' => [
						'Servicetemplate.id = Service.servicetemplate_id'
					]
				],
				[
					'table' => 'hosts',
					'alias' => 'Host',
					'type' => 'INNER',
					'conditions' => [
						'Host.id = Service.host_id'
					]
				],
			],

			'fields' => [
				'Servicegroup.id',
				'Servicegroup.uuid',
				'Servicegroup.description',
				'Container.id',
				'Container.parent_id',
				'Container.name',
				'Service.id',
				'Service.name',
				'Host.id',
				'Host.name',
				'Servicetemplate.name'
			],
			'conditions' => $conditions,
		];

		$this->Paginator->settings['order'] = [
			'Container.name' => 'asc',
			'Host.name'	=> 'asc',
			'Service.name' => 'asc',
			'Servicetemplate.name' => 'asc'
		];
		if($this->isApiRequest()){
			$all_servicegroups = $this->Servicegroup->find('all', $query);
		}else{
			$query['limit'] = 150;
			$this->Paginator->settings = $query;
			$all_servicegroups = $this->Paginator->paginate();
			$all_servicegroups = Hash::merge([], Set::combine($all_servicegroups, '{n}.Servicegroup.id', '{n}.{(Servicegroup|Container)}'), Set::combine($all_servicegroups, '{n}.Service.id', '{n}.{(Service$|Servicetemplate|Host)}', '{n}.Servicegroup.id'));
		}
		
		$this->set('all_servicegroups', $all_servicegroups);

		//Aufruf für json oder xml view: /nagios_module/services.json oder /nagios_module/services.xml
		$this->set('_serialize', array('all_servicegroups'));
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}

	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();
		}
		if(!$this->Servicegroup->exists($id)){
			throw new NotFoundException(__('Invalid Servicegroup'));
		}

		$servicegroup = $this->Servicegroup->findById($id);
		if(!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}
		
		$this->set('servicegroup', $servicegroup);
		$this->set('_serialize', ['servicegroup']);
	}

	public function edit($id = null){
		$userId = $this->Auth->user('id');
		if(!$this->Servicegroup->exists($id)) {
			throw new NotFoundException(__('Invalid servicegroup'));
		}

		/*fixme for permissions*/
		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
		}

		$servicegroup = $this->Servicegroup->find('first', [
			'contain' => [
				'Service' => [
					'fields' => [
						'Service.id',
						'Service.servicetemplate_id',
						'Service.name',
					],
					'Servicetemplate' => [
						'fields' => [
							'Servicetemplate.id',
							'Servicetemplate.name'
						]
					],
					'Host' => [
						'fields' => [
							'Host.name'
						]
					]
				],
				'Container',
			],
			'conditions' => [
				'Servicegroup.id' => $id
			]
		]);

		if(!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}
		$services_for_changelog = [];
		foreach($servicegroup['Service'] as $service){
			$services_for_changelog[] = [
				'id' => $service['id'],
				'name' => $service['Host']['name'] . ' | ' . (($service['name']) ? $service['name'] : $service['Servicetemplate']['name'])
			];
			//$services_for_changelog[$service['id']] = $service['Host']['name'].' | '.(($service['name'])?$service['name']:$service['Servicetemplate']['name']);
		}

		$serviceIds = [ROOT_CONTAINER];
		if($this->request->is('post') == false && $this->request->is('put') == false){
			$serviceIds[] = $servicegroup['Container']['parent_id'];
		}else{
			$serviceIds[] = $this->request->data['Container']['parent_id'];
		}
		$serviceIds = $this->Tree->resolveChildrenOfContainerIds($serviceIds);
		array_unshift($serviceIds, ROOT_CONTAINER);
		$_services = $this->Service->servicesByHostContainerIds($serviceIds);

		//Fix that duplicate hostnames dont overwrite the array key!!
		foreach($_services as $service){
			$hostId = $service['Host']['id'];
			$hostName = $service['Host']['name'];
			$serviceId = $service['Service']['id'];
			$serviceDescription = $service[0]['ServiceDescription'];

			$services[$hostId][$hostName][$serviceId] = $hostName . '/' . $serviceDescription;
		}

		$servicegroup['Service'] = $services_for_changelog; //Services for changelog
		if($this->request->is('post') || $this->request->is('put')){
			$ext_data_for_changelog = [];

			if(isset($this->request->data['Servicegroup']['Service'])){
				$this->request->data['Service'] = $this->request->data['Servicegroup']['Service'];
			}
			if($this->request->data('Servicegroup.Service')){
				$serviceAsList = Hash::combine($_services, '{n}.Service.id', ['%s | %s', '{n}.Host.name','{n}.0.ServiceDescription']);
				foreach($this->request->data['Servicegroup']['Service'] as $service_id){
					$ext_data_for_changelog['Service'][] = [
						'id' => $service_id,
						'name' => $serviceAsList[$service_id]
					];
				}
			}

			if($this->Servicegroup->saveAll($this->request->data)) {
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$id,
					OBJECT_SERVICEGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data['Container']['name'],
					array_merge($this->request->data, $ext_data_for_changelog),
					$servicegroup
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				if($this->request->ext == 'json'){
					$this->serializeId();
					return;
				}
				$this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
				$this->redirect(['action' => 'index']);
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();
					return;
				}
				$this->setFlash(__('Servicegroup could not be saved'), false);
			}
		}
		if($this->request->is('post') == false && $this->request->is('put') == false){
			$servicegroup['Servicegroup']['Service'] = Hash::extract($servicegroup['Service'], '{n}.id', '{n}.name');
		}

		$this->request->data = Hash::merge($servicegroup, $this->request->data);
		$this->set(compact(['servicegroup', 'containers', 'services']));
		$this->set('_serialize', ['servicegroup', 'containers', 'services']);
	}

	public function add(){
		$userId = $this->Auth->user('id');
		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_SERVICEGROUP, [], $this->hasRootPrivileges);
		}

		$services = [];
		$this->Frontend->set('data_placeholder', __('Please choose a service'));
		$this->Frontend->set('data_placeholder_empty', __('No entries found'));
		if($this->request->is('post') || $this->request->is('put')){
			$_services = [];
			if($this->request->data['Container']['parent_id'] > 0){
				$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id'], $this->hasRootPrivileges);
				$_services = $this->Service->servicesByHostContainerIds($containerIds);
			}

			//Fix that duplicate hostnames dont overwrite the array key!!
			foreach($_services as $service){
				$services[$service['Host']['id']][$service['Host']['name']][$service['Service']['id']] = $service['Host']['name'].'/'.$service[0]['ServiceDescription'];
			}
			$ext_data_for_changelog = [];
			App::uses('UUID', 'Lib');
			$this->request->data['Servicegroup']['uuid'] = UUID::v4();
			$this->request->data['Container']['containertype_id'] = CT_SERVICEGROUP;
			if(isset($this->request->data['Servicegroup']['Service'])){
				$this->request->data['Service'] = $this->request->data['Servicegroup']['Service'];
			}
			if($this->request->data('Servicegroup.Service')){
				$serviceAsList = Hash::combine($_services, '{n}.Service.id', ['%s | %s', '{n}.Host.name','{n}.0.ServiceDescription']);
				foreach($this->request->data['Servicegroup']['Service'] as $service_id){
					$ext_data_for_changelog['Service'][] = [
						'id' => $service_id,
						'name' => $serviceAsList[$service_id],
					];
				}
			}

			$isJsonRequest = $this->request->ext === 'json';
			if($this->Servicegroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Servicegroup->id,
					OBJECT_SERVICEGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data('Container.name'),
					array_merge($this->request->data, $ext_data_for_changelog)
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				if($isJsonRequest){
					$this->serializeId();
					return;
				}else{
					$this->setFlash(__('<a href="/servicegroups/edit/%s">Servicegroup</a> successfully saved', $this->Servicegroup->id));
					$this->redirect(array('action' => 'index'));
				}
			}else{
				if($isJsonRequest){
					$this->serializeErrorMessage();
					return;
				}else{
					$this->setFlash(__('could not save data'), false);
				}
			}
		}
		$this->set(compact(['containers', 'services']));
	}

	public function loadServices($containerId = null){
		$this->allowOnlyAjaxRequests();

		$containerIds = $this->Tree->resolveChildrenOfContainerIds(
			$containerId, false,
			$this->Constants->containerProperties(OBJECT_HOST, CT_HOSTGROUP)
		);
		$services = $this->Host->servicesByContainerIds($containerIds, 'list', [
			'forOptiongroup' => true,
		]);
		$services = $this->Service->makeItJavaScriptAble($services);

		$data = ['services' => $services];
		$this->set($data);
		$this->set('_serialize', array_keys($data));
	}

	public function delete($id = null){
		$userId = $this->Auth->user('id');
		if (!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		if (!$this->Servicegroup->exists($id)){
			throw new NotFoundException(__('invalid_servicegroup'));
		}
		$container = $this->Servicegroup->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($container, 'Container.parent_id'))){
			$this->render403();
			return;
		}

		if($this->Container->delete($container['Servicegroup']['container_id'], true)){
			$changelog_data = $this->Changelog->parseDataForChangelog(
				$this->params['action'],
				$this->params['controller'],
				$id,
				OBJECT_SERVICEGROUP,
				$container['Container']['parent_id'],
				$userId,
				$container['Container']['name'],
				$container
			);
			if($changelog_data){
				CakeLog::write('log', serialize($changelog_data));
			}
			$this->setFlash(__('Servicegroup deleted'));
			$this->redirect(array('action' => 'index'));
		}

		$this->setFlash(__('could not delete servicegroup'), false);
		$this->redirect(['action' => 'index']);
	}

	public function mass_delete($id = null){
		$userId = $this->Auth->user('id');
		foreach(func_get_args() as $servicegroupId){
			if($this->Servicegroup->exists($servicegroupId)){
				$servicegroup = $this->Servicegroup->find('first', [
					'contain' => [
						'Container',
						'Service'
					],
					'conditions' => [
						'Servicegroup.id' => $servicegroupId
					]
				]);
				if($this->allowedByContainerId(Hash::extract($servicegroup, 'Container.parent_id'))){
					if($this->Container->delete($servicegroup['Servicegroup']['container_id'], true)){
						$changelog_data = $this->Changelog->parseDataForChangelog(
							$this->params['action'],
							$this->params['controller'],
							$id,
							OBJECT_SERVICEGROUP,
							$servicegroup['Container']['parent_id'],
							$userId,
							$servicegroup['Container']['name'],
							$servicegroup
						);
						if($changelog_data){
							CakeLog::write('log', serialize($changelog_data));
						}
					}
				}
			}
		}
		$this->setFlash(__('Servicegroups deleted'));
		$this->redirect(['action' => 'index']);
	}

	public function mass_add($id = null){
		if($this->request->is('post') || $this->request->is('put')){
			$targetServicegroup = $this->request->data('Servicegroup.id');
			if($this->Servicegroup->exists($targetServicegroup)){
				$servicegroup = $this->Servicegroup->findById($targetServicegroup);
				//Save old hosts from this hostgroup
				$servicegroupMembers = [];
				foreach($servicegroup['Service'] as $service){
					$servicegroupMembers[] = $service['id'];
				}
				foreach($this->request->data('Service.id') as $service_id){
					$servicegroupMembers[] = $service_id;
				}
				$servicegroup['Service'] = $servicegroupMembers;
				$servicegroup['Servicegroup']['Service'] = $servicegroupMembers;
				if($this->Servicegroup->saveAll($servicegroup)){
					$this->setFlash(_('Servicegroup appended successfully'));
					$this->redirect(['action' => 'index']);
				}else{
					$this->setFlash(_('Could not append Servicegroup'), false);
				}
			}else{
				$this->setFlash('Servicegroup not found', false);
			}
		}

		$servicesToAppend = [];
		foreach(func_get_args() as $service_id){
			$service = $this->Service->findById($service_id);
			$servicesToAppend[] = $service;
		}
		$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
		$servicegroups = $this->Servicegroup->servicegroupsByContainerId($containerIds, 'list');

		$this->set(compact(['servicesToAppend', 'servicegroups']));
		$this->set('back_url', $this->referer());
	}

	public function listToPdf(){
		$servicegroups = $this->Servicegroup->find('all', [
			'order' => [
				'Container.name' => 'ASC',
			],
			'conditions' => [
				'Container.parent_id' => $this->MY_RIGHTS
			]
		]);
		$servicegroupstatus = [];
		$servicegroupServicestatus = [];
		//iterate through each servicegroup
		foreach ($servicegroups as $key => $servicegroup) {
			//write Servicegroupt & container into the new array
			$servicegroupstatus[$key]['Servicegroup'] = $servicegroup['Servicegroup'];
			$servicegroupstatus[$key]['Container'] = $servicegroup['Container'];
			//initalize a new Host array
			$hosts = [];
			//iterate through each service from the servicegroup
			foreach ($servicegroup['Service'] as $k => $service) {
				//get the Host UUIDs
				$hostUuid = $this->Objects->find('all', [
					'recursive' => -1,
					'conditions' => [
						'name2' => $service['uuid'],
						'objecttype_id' => 2,
					],
					'fields' => [
						'Objects.name1'
					]
				]);
				//get the Host data for each host of the servicegroup
				$currentHostData = $this->Host->find('first', [
					'recursive' => -1,
					'conditions' => [
						'uuid' => $hostUuid[0]['Objects']['name1'],
					],
					'fields' => [
						'Host.uuid',
						'Host.name',
						'Host.address'
					],
					'order' => [
						'Host.name' => 'ASC'
					]
				]);
				//write the data into the Hosts array
				$hosts[] = $currentHostData;
			}
			//get the duplicate hosts out of the array
			$servicegroupstatus[$key]['Host'] = array_unique($hosts, SORT_REGULAR);

			//get the UUIDs of every service from the servicegroup
			$serviceUuids = Hash::extract($servicegroup,'Service.{n}.uuid');
			//iterate through each Host
			foreach($servicegroupstatus[$key]['Host'] as $hKey => $host){
				//get every Service from the current Host
				$hostServiceUuids = $this->Objects->find('all', [
					'recursive' => -1,
					'conditions' => [
						'name1' => $host['Host']['uuid'],
						'objecttype_id' => 2
					],
					'fields' => [
						'Objects.name2'
					]
				]);

				//extract the UUIDs from every service of the Host
				$hostServiceUuids = Hash::extract($hostServiceUuids, '{n}.Objects.name2');
				//iterate through the services from the Servicegroup
				foreach($serviceUuids as $serviceUuid){
					//if the serviceUUID from the servicegroup is in the array of the Host Services
					if(in_array($serviceUuid, $hostServiceUuids)){
						//get the Data of the Service
						$servicegroupServiceData = $this->Objects->find('all', [
							'recursive' => -1,
							'conditions' => [
								'name2' => $serviceUuid,
								'objecttype_id' => 2,
							],
							'fields' => [
								'Service.name',
								'Servicetemplate.name',
								'Servicestatus.*'
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
								'IF(Service.name IS NULL OR Service.name = "", Servicetemplate.name, Service.name)' => 'ASC'
							]
						]);
						//append the data to the Servicegroupstatus array
						$servicegroupstatus[$key]['Host'][$hKey]['Host']['Service'][] = $servicegroupServiceData;
					}
				}
			}
		}
		//counter
		$servicegroupCount = count($servicegroups);
		$hostCount = Hash::apply($servicegroupstatus, '{n}.Host.{n}', 'count');
		$serviceCount = Hash::apply($servicegroups, '{n}.Service.{n}', 'count');

		$this->set(compact('servicegroupstatus', 'servicegroupCount', 'hostCount', 'serviceCount'));

		$filename = 'Servicegroups_'.strtotime('now').'.pdf';
		$binary_path = '/usr/bin/wkhtmltopdf';
		if(file_exists('/usr/local/bin/wkhtmltopdf')){
			$binary_path = '/usr/local/bin/wkhtmltopdf';
		}
		$this->pdfConfig = [
			'engine' =>'CakePdf.WkHtmlToPdf',
			'margin' => [
				'bottom'=>15,
				'left'=>0,
				'right'=>0,
				'top'=>15
			],
			'encoding'=>'UTF-8',
			'download' =>true,
			'binary' => $binary_path,
			'orientation' => 'portrait',
			'filename' => $filename,
			'no-pdf-compression' => '*',
			'image-dpi'	=> '900',
			'background' => true,
			'no-background' => false,
		];
	}
}
