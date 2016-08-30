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
 * @property Hostgroup $Hostgroup
 * @property Container $Container
 * @property Host      $Host
 * @property User      $User
 */
class HostgroupsController extends AppController{

	public $uses = [
		'Hostgroup',
		'Container',
		'Host',
		'User',
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		MONITORING_OBJECTS,
	];
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
	];
	public $helpers = [
		'ListFilter.ListFilter',
		'Status',
	];

	public $listFilters = [
		'index' => [
			'fields' => [
				'Container.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
				'Hostgroup.description' => ['label' => 'Alias', 'searchType' => 'wildcard'],
			],
		]
	];

	public function index(){
		if(!isset($this->Paginator->settings['conditions'])){
			$this->Paginator->settings['conditions'] = array();
		}
		$this->Paginator->settings['order'] = array('Container.name' => 'asc');

		$options = [
			'order' => [
				'Container.name' => 'asc'
			],
			'conditions' => [
				'Container.parent_id' => $this->MY_RIGHTS
			],
		];
		$query = Hash::merge($this->Paginator->settings, $options);

		if($this->isApiRequest()){
			unset($query['limit']);
			$all_hostgroups = $this->Hostgroup->find('all', $query);
		}else{
			$this->Paginator->settings = array_merge($this->Paginator->settings, $query);
			$all_hostgroups = $this->Paginator->paginate();
		}


		$this->set('all_hostgroups', $all_hostgroups);

		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', array('all_hostgroups'));
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}

	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();
		}
		if(!$this->Hostgroup->exists($id)){
			throw new NotFoundException(__('Invalid Hostgroup'));
		}

		$hostgroup = $this->Hostgroup->findById($id);
		if(!$this->allowedByContainerId($hostgroup['Container']['parent_id'])){
			$this->render403();
			return;
		}

		$this->set('hostgroup', $hostgroup);
		$this->set('_serialize', ['hostgroup']);
	}

	public function extended($hostgroup_id = null){
		if(!isset($this->Paginator->settings['conditions'])){
			$this->Paginator->settings['conditions'] = array();
		}
		$this->Frontend->setJson('websocket_url', 'wss://'.env('HTTP_HOST').'/sudo_server');
		$this->loadModel('Systemsetting');
		$key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
		$this->Frontend->setJson('akey', $key['Systemsetting']['value']);

		$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
		$hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
		$this->set('hostgroups', $hostgroups);
		$hostgroup = [];
		if($hostgroup_id === null){
			//Select first hostgroup out of find result
			$hostgroup_id = key($hostgroups);
		}
		if($hostgroup_id !== null){
			if(!$this->Hostgroup->exists($hostgroup_id)){
				throw new NotFoundException(__('Invalid hostgroup'));
			}

			$settings = [];
			$settings['recursive'] = -1;
			$settings['contain'] = [
				'Container' => [
					'fields' => [
						'Container.id',
						'Container.parent_id',
						'Container.name'
					]
				],
				'Host' => [
					'fields' => [
						'Host.id', 'Host.uuid', 'Host.name'
					],
					'order' => [
						'Host.name'		=> 'asc'
					],
					'Service' => [
						'fields' => [
							'Service.id', 'Service.uuid', 'Service.name'
						],
						'Servicetemplate' => [
							'fields' => [
								'Servicetemplate.name'
							],
						]
					]
				],
			];

			$settings['order'] = [
				'Container.name'=> 'asc'
			];
			$settings['conditions'] = [
				'Container.id' => $containerIds
			];
			if($hostgroup_id !== null){
				$settings['conditions'] = [
					'Hostgroup.id' => $hostgroup_id
				];
			}

			//$this->Paginator->settings['contain'] =['Host' => 'Service'];
			$hostgroup = $this->Hostgroup->find('first', $settings);
			if(!$this->allowedByContainerId(Hash::extract($hostgroup, 'Container.parent_id'))){
				$this->render403();
				return;
			}

			$hoststatus = $this->Hoststatus->find('all', [
				'fields' => [
					'Objects.name1',
					'Hoststatus.current_state',
					'Hoststatus.status_update_time',
					'Hoststatus.last_check',
					'Hoststatus.next_check',
					'Hoststatus.is_flapping',
					'Hoststatus.active_checks_enabled',
					'Hoststatus.problem_has_been_acknowledged',
					'Hoststatus.scheduled_downtime_depth',
					'Hoststatus.last_hard_state_change'
				],
				'conditions' => [
					'Objects.name1' => Hash::extract($hostgroup, 'Host.{n}.uuid')
				]
			]);

			$servicestatus = $this->Servicestatus->find('all', [
				'fields' => [
					'Objects.name2',
					'Servicestatus.current_state',
					'Servicestatus.status_update_time',
					'Servicestatus.last_check',
					'Servicestatus.next_check',
					'Servicestatus.is_flapping',
					'Servicestatus.active_checks_enabled',
					'Servicestatus.process_performance_data',
					'Servicestatus.problem_has_been_acknowledged',
					'Servicestatus.scheduled_downtime_depth',
					'Servicestatus.last_hard_state_change'
				],
				'conditions' => [
					'Objects.name2' => Hash::extract($hostgroup, 'Host.{n}.Service.{n}.uuid')
				]
			]);

			$this->Frontend->setJson('hostgroupUuid', $hostgroup['Hostgroup']['uuid']);

			$hoststatus_by_uuid = Hash::combine($hoststatus, '{n}.Objects.name1', '{n}.Hoststatus');
			$servicestatus_by_uuid = Hash::combine($servicestatus, '{n}.Objects.name2', '{n}.Servicestatus');

			//add host- and service status to host/service object
			foreach($hostgroup['Host'] as $host_key => $host_data){
				$hostgroup['Host'][$host_key]['Hoststatus'] = [];
				if(array_key_exists($host_data['uuid'], $hoststatus_by_uuid)){
					$hostgroup['Host'][$host_key]['Hoststatus']= $hoststatus_by_uuid[$host_data['uuid']];
				}
				foreach($host_data['Service'] as $service_key => $service_data){
					$hostgroup['Host'][$host_key]['Service'][$service_key]['Servicestatus'] = [];
					if(array_key_exists($service_data['uuid'], $servicestatus_by_uuid)){
						$hostgroup['Host'][$host_key]['Service'][$service_key]['Servicestatus']= $servicestatus_by_uuid[$service_data['uuid']];
					}
				}
			}

			$username = $this->Auth->user('full_name');

			$this->set(compact(['hostgroup', 'username', 'hostgroup_id']));
			//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
			$this->set('_serialize', array('hostgroup'));

		}

	}

	public function edit($id = null){
		if(!$this->Hostgroup->exists($id)){
			throw new NotFoundException(__('Invalid hostgroup'));
		}
		$userId = $this->Auth->user('id');
		$hostgroup = $this->Hostgroup->find('first', [
			'conditions' => [
				'Hostgroup.id' => $id
			]
		]);

		if(!$this->allowedByContainerId($hostgroup['Container']['parent_id'])){
			$this->render403();
			return;
		}

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
		}

		$ext_data_for_changelog = [];
		$containerId = $hostgroup['Container']['parent_id'];
		if($this->request->is('post') || $this->request->is('put')){
			$containerId = $this->request->data('Container.parent_id');
		}
		$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

		$hosts = $this->Host->hostsByContainerId($containerIds, 'list');
		if($this->request->data('Hostgroup.Host')){
			foreach($this->request->data['Hostgroup']['Host'] as $host_id){
				$host = $this->Host->find('first', [
					'contain' => [],
					'fields' => [
						'Host.id',
						'Host.name'
					],
					'conditions' => [
						'Host.id' => $host_id
					]
				]);
				$ext_data_for_changelog['Host'][] = [
					'id' => $host_id,
					'name' => $host['Host']['name']
				];
			}
		}

		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Host'] = (!empty($this->request->data('Hostgroup.Host')))?$this->request->data('Hostgroup.Host'):[];
			//Add container id (of the hostgroup container itself) to the request data
			$this->request->data['Container']['id'] = $hostgroup['Hostgroup']['container_id'];
			if($this->Hostgroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Hostgroup->id,
					OBJECT_HOSTGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data['Container']['name'],
					array_merge($this->request->data, $ext_data_for_changelog),
					$hostgroup
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('<a href="/hostgroups/edit/%s">Hostgroup</a> successfully saved', $this->Hostgroup->id));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('Could not save data'), false);
			}
		}else{
			$hostgroup['Host'] = Hash::extract($hostgroup['Host'], '{n}.id');
		}

		$this->request->data = Hash::merge($hostgroup, $this->request->data);
		$this->set(compact(['hostgroup', 'hosts', 'containers']));
	}

	public function add(){
		$userId = $this->Auth->user('id');

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
		}


		$this->Frontend->set('data_placeholder', __('Please choose a host'));
		$this->Frontend->set('data_placeholder_empty', __('No entries found'));

		if($this->request->is('post') || $this->request->is('put')){
			$ext_data_for_changelog = [];
			App::uses('UUID', 'Lib');
			if($this->request->data('Hostgroup.Host')){
				foreach($this->request->data['Hostgroup']['Host'] as $host_id){
					$host = $this->Host->find('first', [
						'contain' => [],
						'fields' => [
							'Host.id',
							'Host.name'
						],
						'conditions' => [
							'Host.id' => $host_id
						]
					]);
					$ext_data_for_changelog['Host'][] = [
						'id' => $host_id,
						'name' => $host['Host']['name']
					];
				}
			}

			$this->request->data['Hostgroup']['uuid'] = UUID::v4();
			$this->request->data['Container']['containertype_id'] = CT_HOSTGROUP;
			$this->request->data['Host'] = (!empty($this->request->data('Hostgroup.Host')))?$this->request->data('Hostgroup.Host'):[];


			if($this->Hostgroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Hostgroup->id,
					OBJECT_HOSTGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data['Container']['name'],
					array_merge($this->request->data, $ext_data_for_changelog)
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				if($this->request->ext == 'json'){
					$this->serializeId();
					return;
				}

				$this->setFlash(__('<a href="/hostgroups/edit/%s">Hostgroup</a> successfully saved', $this->Hostgroup->id));
				$this->redirect(['action' => 'index']);
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();
					return;
				}
				$this->setFlash(__('Could not save data'), false);
			}
		}

		$hosts = [];
		if($this->request->is('post') || $this->request->is('put')){
			$containerId = $this->request->data('Container.parent_id');
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);
			$hosts = $this->Host->hostsByContainerId($containerIds, 'list');
		}

		$this->set(compact(['containers', 'hosts']));
		$this->set('_serialize', ['containers', 'hosts']);
	}

	public function loadHosts($containerId = null){
		$this->allowOnlyAjaxRequests();
		$hosts = $this->Host->hostsByContainerId([ROOT_CONTAINER ,$containerId], 'list');
		$hosts = $this->Host->makeItJavaScriptAble($hosts);
		$this->set(compact(['hosts']));
		$this->set('_serialize', ['hosts']);
	}

	public function delete($id = null){
		$userId = $this->Auth->user('id');
		if (!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}

		if (!$this->Hostgroup->exists($id)){
			throw new NotFoundException(__('Invalid hostgroup'));
		}

		$container = $this->Hostgroup->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($container, 'Container.parent_id'))){
			$this->render403();
			return;
		}
		if($this->Container->delete($container['Hostgroup']['container_id'], true)){
			$changelog_data = $this->Changelog->parseDataForChangelog(
				$this->params['action'],
				$this->params['controller'],
				$id,
				OBJECT_HOSTGROUP,
				$container['Container']['parent_id'],
				$userId,
				$container['Container']['name'],
				$container
			);
			if($changelog_data){
				CakeLog::write('log', serialize($changelog_data));
			}
			$this->setFlash(__('Hostgroup deleted'));
			$this->redirect(array('action' => 'index'));
		}

		$this->setFlash(__('Could not delete hostgroup'), false);
		$this->redirect(array('action' => 'index'));

	}

	public function mass_add($id = null){
		if($this->request->is('post') || $this->request->is('put')){

			$userId = $this->Auth->user('id');

			if(isset($this->request->data['Hostgroup']['create']) && $this->request->data['Hostgroup']['create'] == 1){
				if(isset($this->request->data['Hostgroup']['id'])){
					unset($this->request->data['Hostgroup']['id']);
				}

				$ext_data_for_changelog = [];
				App::uses('UUID', 'Lib');

				$this->request->data['Hostgroup']['uuid'] = UUID::v4();
				$this->request->data['Container']['containertype_id'] = CT_HOSTGROUP;

				//Required for validation
				foreach($this->request->data('Host.id') as $host_id){
					$hostgroupMembers[] = $host_id;
				}
				$this->request->data['Host'] = $hostgroupMembers;
				$this->request->data['Hostgroup']['Host'] = $hostgroupMembers;


				$ext_data_for_changelog = [];
				foreach($hostgroupMembers as $hostId){
					$host = $this->Host->find('first', [
						'recursive' => -1,
						'conditions' => [
							'Host.id' => $hostId
						],
						'fields' => [
							'Host.id',
							'Host.name'
						]
					]);
					$ext_data_for_changelog['Host'][] = [
						'id' => $hostId,
						'name' => $host['Host']['name']
					];
				}

				if($this->Hostgroup->saveAll($this->request->data)){
					$changelog_data = $this->Changelog->parseDataForChangelog(
						'add',
						'hostgroups',
						$this->Hostgroup->id,
						OBJECT_HOSTGROUP,
						$this->request->data('Container.parent_id'),
						$userId,
						$this->request->data['Container']['name'],
						array_merge($this->request->data, $ext_data_for_changelog)
					);
					if($changelog_data){
						CakeLog::write('log', serialize($changelog_data));
					}

					$this->setFlash(_('Hostgroup appended successfully'));
					$this->redirect(array('action' => 'index'));
				}else{
					$this->setFlash(__('Could not save data'), false);
				}
			}else{
				$targetHostgroup = $this->request->data('Hostgroup.id');
				if($this->Hostgroup->exists($targetHostgroup)){
					$hostgroup = $this->Hostgroup->findById($targetHostgroup);
					//Save old hosts from this hostgroup
					$hostgroupMembers = [];
					foreach($hostgroup['Host'] as $host){
						$hostgroupMembers[] = $host['id'];
					}
					foreach($this->request->data('Host.id') as $host_id){
						$hostgroupMembers[] = $host_id;
					}
					$hostgroup['Host'] = $hostgroupMembers;
					$hostgroup['Hostgroup']['Host'] = $hostgroupMembers;
					if($this->Hostgroup->saveAll($hostgroup)){
						$this->setFlash(_('Hostgroup appended successfully'));
						$this->redirect(array('action' => 'index'));
					}else{
						$this->setFlash(__('Could not append hostgroup'), false);
					}
				}else{
					$this->setFlash(__('Hostgroup not found'), false);
				}
			}
		}

		$hostsToAppend = [];
		foreach(func_get_args() as $host_id){
			$host = $this->Host->findById($host_id);
			$hostsToAppend[] = $host;
		}

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
			$hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
		}else{
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->getWriteContainers());
			$containers = $this->Tree->easyPath($containerIds, OBJECT_HOSTGROUP, [], $this->hasRootPrivileges);
			$hostgroups = $this->Hostgroup->hostgroupsByContainerId($this->getWriteContainers(), 'list', 'id');
		}
		$userContainerId = (isset($this->request->data['Container']['parent_id']))?$this->request->data['Container']['parent_id']:$this->Auth->user('container_id');

		$this->set([
			'hostsToAppend' => $hostsToAppend,
			'hostgroups' => $hostgroups,
			'containers' => $containers,
			'user_container_id' => $userContainerId,
		]);
		$this->set('back_url', $this->referer());
	}

	public function mass_delete($id = null){
		$userId = $this->Auth->user('id');
		foreach(func_get_args() as $hostgroupId){
			if($this->Hostgroup->exists($hostgroupId)){
				$hostgroup = $this->Hostgroup->find('first', [
					'contain' => [
						'Container',
						'Host'
					],
					'conditions' => [
						'Hostgroup.id' => $hostgroupId
					]
				]);
				if($this->allowedByContainerId(Hash::extract($hostgroup, 'Container.parent_id'))){
					if($this->Container->delete($hostgroup['Hostgroup']['container_id'], true)){
						$changelog_data = $this->Changelog->parseDataForChangelog(
							$this->params['action'],
							$this->params['controller'],
							$hostgroupId,
							OBJECT_HOSTGROUP,
							$hostgroup['Container']['parent_id'],
							$userId,
							$hostgroup['Container']['name'],
							$hostgroup
						);
						if($changelog_data){
							CakeLog::write('log', serialize($changelog_data));
						}
					}
				}
			}
		}
		$this->setFlash(__('Hostgroups deleted'));
		$this->redirect(['action' => 'index']);
	}

	public function listToPdf(){
		$args = func_get_args();
		$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
		$conditions = [
			'Container.parent_id' => $containerIds
		];

		if(is_array($args) && !empty($args)){
			if(end($args) == '.pdf' && (sizeof($args) > 1)){
				$hostgroup_ids = $args;
				end($hostgroup_ids);
				$last_key = key($hostgroup_ids);
				unset($hostgroup_ids[$last_key]);

				$_conditions = [
					'Hostgroup.id' => $hostgroup_ids,
				];
				$conditions = Hash::merge($conditions, $_conditions);
			}else{
				$hostgroup_ids = $args;

				$_conditions = [
					'Hostgroup.id' => $hostgroup_ids,
				];
				$conditions = Hash::merge($conditions, $_conditions);
			}
		}

		$hostgroups = $this->Hostgroup->find('all', [
			'conditions' => $conditions,
			'fields' => [
				'Hostgroup.description',
				'Container.name',
			],
			'contain' => [
				'Host' => [
					'fields' => [
						'Host.name',
						'Host.uuid'
					]
				],
				'Container',
			]
		]);

		foreach ($hostgroups as $hgKey => $hostgroup) {
			$hostgroupHostUuids = Hash::extract($hostgroup, 'Host.{n}.uuid');

			foreach ($hostgroupHostUuids as $hKey => $hostgroupHostUuid) {
				$hoststatus = $this->Objects->find('all',[
					'recursive' => -1,
					'conditions' => [
						'name1' => $hostgroupHostUuid,
						'objecttype_id' => 1
					],
					'fields' => [
						'Hoststatus.current_state',
						'Hoststatus.is_flapping',
						'Hoststatus.problem_has_been_acknowledged',
						'Hoststatus.scheduled_downtime_depth',
						'Hoststatus.last_state_change',
						'Hoststatus.last_check',
						'Hoststatus.next_check',
					],
					'joins' => [
						[
							'table' => 'nagios_hoststatus',
							'type' => 'LEFT OUTER',
							'alias' => 'Hoststatus',
							'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
						]
					]
				]);
				$hostgroups[$hgKey]['Host'][$hKey]['Hoststatus'] = $hoststatus;
			}
		}
		$hostgroupCount = Hash::apply($hostgroups,'{n}.Hostgroup','count');
		$hostgroupHostCount = Hash::apply($hostgroups,'{n}.Host.{n}','count');
		$this->set(compact('hostgroups', 'hostgroupCount', 'hostgroupHostCount'));

		$filename = 'Hostgroups_'.strtotime('now').'.pdf';
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
