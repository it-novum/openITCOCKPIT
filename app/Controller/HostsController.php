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
 * @property Host                              $Host
 * @property Documentation                     $Documentation
 * @property Commandargument                   $Commandargument
 * @property Hosttemplatecommandargumentvalue  $Hosttemplatecommandargumentvalue
 * @property Hostcommandargumentvalue          $Hostcommandargumentvalue
 * @property Contact                           $Contact
 * @property Contactgroup                      $Contactgroup
 * @property DeletedHost                       $DeletedHost
 * @property DeletedService                    $DeletedService
 * @property Container                         $Container
 * @property Parenthost                        $Parenthost
 * @property Hosttemplate                      $Hosttemplate
 * @property Hostgroup                         $Hostgroup
 * @property Timeperiod                        $Timeperiod
 */
class HostsController extends AppController{
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
		'CustomValidationErrors',
		'Bbcode',
		'AdditionalLinks',
	];
	public $helpers = [
		'ListFilter.ListFilter',
		'Status',
		'Monitoring',
		'CustomValidationErrors',
		'CustomVariables',
		'Bbcode',
	];
	public $uses = [
		'Host',
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		MONITORING_OBJECTS,
		'Documentation',
		'Commandargument',
		'Hosttemplatecommandargumentvalue',
		'Hostcommandargumentvalue',
		'Contact',
		'Contactgroup',
		MONITORING_ACKNOWLEDGED,
		'DeletedHost',
		'DeletedService',
		'Container',
		'Parenthost',
		'Hosttemplate',
		'Hostgroup',
		'Timeperiod',
		'Servicetemplategroup'
	];
	public $listFilters = [
		'index' => [
			'fields' => [
				'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
				'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
				'Hoststatus.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
				'Host.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
				'Hoststatus.current_state' => ['label' => 'Current state', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
					[
						'0' => [
							'name' => 'Hoststatus.up',
							'value' => 1,
							'label' => 'Up',
							'data' => 'Filter.Hoststatus.current_state',
						],
						'1' => [
							'name' => 'Hoststatus.down',
							'value' => 1,
							'label' => 'Down',
							'data' => 'Filter.Hoststatus.current_state',
						],
						'2' => [
							'name' => 'Hoststatus.unreachable',
							'value' => 1,
							'label' => 'Unreachable',
							'data' => 'Filter.Hoststatus.current_state',
						]
					]
				],
				'Hoststatus.problem_has_been_acknowledged' => ['label' => 'Acknowledged', 'type' => 'checkbox', 'searchType' => 'nix', 'options' =>
					[
						'1' => [
							'name' => 'Acknowledged',
							'value' => 1,
							'label' => 'Acknowledged',
							'data' => 'Filter.Hoststatus.problem_has_been_acknowledged',
						],
					]
				],
				'Hoststatus.scheduled_downtime_depth' => ['label' => 'In Downtime', 'type' => 'checkbox', 'searchType' => 'greater', 'options' =>
					[
						'0' => [
							'name' => 'Downtime',
							'value' => 0,
							'label' => 'In Downtime',
							'data' => 'Filter.Hoststatus.scheduled_downtime_depth',
						],
					]
				],
			],
		],
		'notMonitored' => [
			'fields' => [
				'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
				'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
				'Hoststatus.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
				'Host.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
			],
		],
		'disabled' => [
			'fields' => [
				'Host.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
				'Host.address' => ['label' => 'IP-Address', 'searchType' => 'wildcard'],
				'Host.tags' => ['label' => 'Tag', 'searchType' => 'wildcard', 'hidden' => true],
			],
		],
	];

	public function index(){
		$this->__unbindAssociations('Service');

		$conditions = [];
		if(!isset($this->request->params['named']['BrowserContainerId'])){
			$conditions = [
				'Host.disabled' => 0,
				'HostsToContainers.container_id' => $this->MY_RIGHTS,
			];
		}

		$conditions = $this->ListFilter->buildConditions([], $conditions);

		$childrenContainer = [];
		if(isset($this->request->params['named']['BrowserContainerId'])){
			if(!is_array($this->request->params['named']['BrowserContainerId'])){
				$this->request->params['named']['BrowserContainerId'] = [$this->request->params['named']['BrowserContainerId']];
			}
			foreach($this->request->params['named']['BrowserContainerId'] as $containerIdToCheck){
				if(!in_array($containerIdToCheck, $this->MY_RIGHTS)){
					$this->render403();
					return;
				}
			}
			$conditions = Hash::merge($conditions, [
				'Host.disabled' => 0,
				'HostsToContainers.container_id' => $this->request->params['named']['BrowserContainerId']
			]);
			/*if(is_array($this->request->params['named']['BrowserContainerId'])){
				$browserContainerIds = Hash::extract($this->request->params['named']['BrowserContainerId'], '{n}');
				foreach($browserContainerIds as $id){
					$childrenContainer[] = $this->Container->children($id, false, ['id', 'containertype_id']);
				}
				$childrenContainer = Hash::extract($childrenContainer, '{n}.{n}');
			}else{
				$browserContainerIds = [$this->request->params['named']['BrowserContainerId']];
				$childrenContainer = $this->Container->children(
					$this->request->params['named']['BrowserContainerId'],
					false,
					['id', 'containertype_id']
				);
			}
			//The user set a container id in the URL, may be over browser
			$all_container_ids = Hash::merge(
				$browserContainerIds, // array
				Hash::extract(
					$childrenContainer,
					'{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_DEVICEGROUP . ')$/].id'
				)
			);

			$all_container_ids = array_unique($all_container_ids);

			$_conditions = [
				'Host.disabled' => 0,
				'Host.container_id' => $all_container_ids
			];
			$conditions = Hash::merge($conditions, $_conditions);
				*/
		}

		$this->Host->virtualFields['hoststatus'] = 'Hoststatus.current_state';
		$this->Host->virtualFields['last_hard_state_change'] = 'Hoststatus.last_hard_state_change';
		$this->Host->virtualFields['last_check'] = 'Hoststatus.last_check';
		$this->Host->virtualFields['output'] = 'Hoststatus.output';
		
		$all_services = [];
		$query = [
			'conditions' => $conditions,
			'fields' => [
				'Host.id',
				'Host.uuid',
				'Host.name',
				'Host.description',
				'Host.active_checks_enabled',
				'Host.address',
				'Host.satellite_id',

				'Hoststatus.current_state',
				'Hoststatus.last_check',
				'Hoststatus.next_check',
				'Hoststatus.last_hard_state_change',
				'Hoststatus.output',
				'Hoststatus.scheduled_downtime_depth',
				'Hoststatus.active_checks_enabled',
				'Hoststatus.state_type',
				'Hoststatus.problem_has_been_acknowledged',
				'Hoststatus.is_flapping',


				'Hosttemplate.id',
				'Hosttemplate.uuid',
				'Hosttemplate.name',
				'Hosttemplate.description',
				'Hosttemplate.active_checks_enabled',


				'Hoststatus.current_state',
			],
			'order' => ['Host.name' => 'asc'],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'HostObject',
					'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
				], [
					'table' => 'nagios_hoststatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Hoststatus',
					'conditions' => 'Hoststatus.host_object_id = HostObject.object_id'
				], [
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				]
			],
			'group' => [
				'Host.id'
			],
		];

		$this->Host->unbindModel([
				'hasMany' => ['Hostcommandargumentvalue', 'HostescalationHostMembership', 'HostdependencyHostMembership', 'Service', 'Customvariable'],
				'hasAndBelongsToMany' => ['Contactgroup', 'Contact', 'Parenthost', 'Hostgroup'],
				'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand']
			]
		);
		if($this->isApiRequest()){
			$all_hosts = $this->Host->find('all', $query);
		}else{
			//$this->Paginator->settings = $query;
			$this->Paginator->settings = array_merge($this->Paginator->settings, $query);
			$all_hosts = $this->Paginator->paginate();
		}

		/*$hostCount = $this->Host->find('count', [
			'conditions' => ['Host.disabled' => 0]
		]);

		$disabledHostCount = $this->Host->find('count', [
			'conditions' => ['Host.disabled' => 1]
		]);

		$deletedHostCount = $this->DeletedHost->find('count');*/

		//$all_uuids = Hash::extract($all_hosts, '{n}.Host.uuid');
		//$hoststatus = $this->Hoststatus->byUuid($all_uuids);

		//distributed monitoring stuff
		$masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];
		$SatelliteModel = false;
		if(is_dir(APP . 'Plugin' . DS . 'DistributeModule')){
			$SatelliteModel = ClassRegistry::init('DistributeModule.Satellite', 'Model');
		}
		$SatelliteNames = [];
		if($SatelliteModel !== false){
			$SatelliteNames = $SatelliteModel->find('list');
		}

		$username = $this->Auth->user('full_name');

		$this->Frontend->setJson('websocket_url', 'wss://' . env('HTTP_HOST') . '/sudo_server');
		$key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
		$this->Frontend->setJson('akey', $key['Systemsetting']['value']);

		$this->set(compact(['all_hosts', 'hoststatus', 'masterInstance', 'SatelliteNames', 'username']));
		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', ['all_hosts']);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			if(isset($this->request->data['Filter']['HostStatus']['current_state'])){
				//$this->set('HostStatus.current_state', $this->request->data['Filter']['HostStatus']['current_state']);
			}else{
				$this->set('HostStatus.current_state', []);
			}
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}

	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();

		}
		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}
		$host = $this->Host->findById($id);
		$containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
		$containerIdsToCheck[] = $host['Host']['container_id'];
		if(!$this->allowedByContainerId($containerIdsToCheck)){
			$this->render403();
			return;
		}

		$_hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid']);
		if(isset($_hoststatus[$host['Host']['uuid']])){
			$hoststatus = $_hoststatus[$host['Host']['uuid']];
		}else{
			$hoststatus = [
				'Hoststatus' => [],
				'Objects' => [],
			];
		}
		$host = Hash::merge($host, $hoststatus);

		$this->set('host', $host);
		$this->set('_serialize', ['host']);
	}

	public function notMonitored(){
		$this->__unbindAssociations('Service');

		$conditions = [];
		if(!isset($this->request->params['named']['BrowserContainerId'])){
			$conditions = [
				'Host.disabled' => 0,
				'HostsToContainers.container_id' => $this->MY_RIGHTS,
				'HostObject.name1 IS NULL',
			];
		}

		$conditions = $this->ListFilter->buildConditions([], $conditions);
		if(isset($this->request->params['named']['BrowserContainerId'])){
			//The user set a comntainer id in the URL, may be over browser
			$all_container_ids = Hash::merge(
				[$this->request->params['named']['BrowserContainerId']],
				Hash::extract(
					$this->Container->children(
						$this->request->params['named']['BrowserContainerId'],
						false,
						['id', 'containertype_id']
					),
					'{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_DEVICEGROUP . ')$/].id'
				)
			);

			$_conditions = [
				'Host.disabled' => 0,
				'Host.container_id' => $all_container_ids
			];
			$conditions = Hash::merge($conditions, $_conditions);
		}

		$all_services = [];
		$query = [
			'conditions' => $conditions,
			'fields' => [
				'Host.id',
				'Host.uuid',
				'Host.name',
				'Host.description',
				'Host.active_checks_enabled',
				'Host.address',
				'Host.satellite_id',

				'Hosttemplate.id',
				'Hosttemplate.uuid',
				'Hosttemplate.name',
				'Hosttemplate.description',
				'Hosttemplate.active_checks_enabled',

			],
			'order' => ['Host.name' => 'asc'],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'LEFT OUTER',
					'alias' => 'HostObject',
					'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
				],
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					],
				]
			],
			'group' => [
				'Host.id'
			]
		];

		$this->Host->unbindModel([
				'hasMany' => ['Hostcommandargumentvalue', 'HostescalationHostMembership', 'HostdependencyHostMembership', 'Service', 'Customvariable'],
				'hasAndBelongsToMany' => ['Contactgroup', 'Contact', 'Parenthost', 'Hostgroup'],
				'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand']
			]
		);
		if($this->isApiRequest()){
			$all_hosts = $this->Host->find('all', $query);
		}else{
			$this->Paginator->settings = $query;
			$all_hosts = $this->Paginator->paginate();
		}

		$hoststatus = [];

		//distributed monitoring stuff
		$masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];
		$SatelliteModel = false;
		if(is_dir(APP . 'Plugin' . DS . 'DistributeModule')){
			$SatelliteModel = ClassRegistry::init('DistributeModule.Satellite', 'Model');
		}
		$SatelliteNames = [];
		if($SatelliteModel !== false){
			$SatelliteNames = $SatelliteModel->find('list');
		}

		$this->set(compact(['all_hosts', 'hoststatus', 'masterInstance', 'SatelliteNames']));
		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', ['all_hosts']);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			if(isset($this->request->data['Filter']['HostStatus']['current_state'])){
				//$this->set('HostStatus.current_state', $this->request->data['Filter']['HostStatus']['current_state']);
			}else{
				$this->set('HostStatus.current_state', []);
			}
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}

	public function edit($id = null){
		$this->set('MY_RIGHTS', $this->MY_RIGHTS);
		$userId = $this->Auth->user('id');

		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		$_host = $this->Host->find('first', [
			'conditions' => [
				'Host.id' => $id,
			],
			'contain' => [
				'Container'
			],
			'fields' => [
				'Host.container_id',
				'Container.*'
			]
		]);

		$containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
		$containerIdsToCheck[] = $_host['Host']['container_id'];
		if(!$this->allowedByContainerId($containerIdsToCheck)){
			$this->render403();
			return;
		}

		$host = $this->Host->prepareForView($id);


		$host_for_changelog = $host;

		$this->set('back_url', $this->referer());
		$this->Frontend->setJson('lang_minutes', __('minutes'));
		$this->Frontend->setJson('lang_seconds', __('seconds'));
		$this->Frontend->setJson('lang_and', __('and'));
		$this->Frontend->setJson('dns_hostname_lookup_failed', __('Could not resolve hostname'));
		$this->Frontend->setJson('dns_ipaddress_lookup_failed', __('Could not reverse lookup your ip address'));
		$this->Frontend->setJson('hostname_placeholder', __('Will be auto detected if you enter a ip address'));
		$this->Frontend->setJson('address_placeholder', __('Will be auto detected if you enter a FQDN'));
		$this->Frontend->setJson('hostId', $id);

		// Checking if the user hit submit and a validation error happents, to refill input fields
		$Customvariable = [];
		$customFieldsToRefill = [
			'Host' => [
				'notification_interval',
				'notify_on_recovery',
				'notify_on_down',
				'notify_on_unreachable',
				'notify_on_flapping',
				'notify_on_downtime',
				'check_interval',
				'retry_interval',
				'flap_detection_enabled',
				'flap_detection_on_up',
				'flap_detection_on_down',
				'flap_detection_on_unreachable',
				'priority',
				'active_checks_enabled'
			],
			'Contact' => [
				'Contact'
			],
			//	'Contactgroup' => [
			//		'Contactgroup'
			//	]
		];
		$this->CustomValidationErrors->checkForRefill($customFieldsToRefill);
		//Fix that we dont lose any unsaved host macros, because of vaildation error
		if(isset($this->request->data['Customvariable'])){
			$Customvariable = $this->request->data['Customvariable'];
			$this->Frontend->setJson('customVariablesCount', sizeof($Customvariable));
		}else{
			$this->Frontend->setJson('customVariablesCount', 0);
		}

		$this->loadModel('Timeperiod');
		$this->loadModel('Command');
		$this->loadModel('Contact');
		$this->loadModel('Contactgroup');
		$this->loadModel('Container');
		$this->loadModel('Customvariable');
		$this->loadModel('Hosttemplate');
		$this->loadModel('Hostgroup');
		$this->loadModel('Commandargument');
		$this->loadModel('Hostcommandargumentvalue');

		// Data required for changelog
		$contacts = $this->Contact->find('list');
		$hosts = $this->Host->find('list');
		$contactgroups = $this->Contactgroup->findList();
		$timeperiods = $this->Timeperiod->find('list');
		$commands = $this->Command->hostCommands('list');
		$hosttemplates = $this->Hosttemplate->find('list');
		$hostgroups = $this->Hostgroup->findList([
			'recursive' => -1,
			'contain' => [
				'Container'
			]
		], 'id');
		// End changelog

		// Data to refill form
		if($this->request->is('post') || $this->request->is('put')){
			$containerId = $this->request->data('Host.container_id');
		}else{
			$containerId = $host['Host']['container_id'];
		}

		$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerId);

		$_hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list');
		$_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
		$_parenthosts = $this->Host->hostsByContainerIdExcludeHostId($containerIds, 'list', $id);
		$_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
		$_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
		$_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
		$this->set(compact(['_hosttemplates', '_hostgroups', '_parenthosts', '_timeperiods', '_contacts', '_contactgroups']));
		// End form refill

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
		}

		//Fehlende bzw. neu angelegte CommandArgummente ermitteln und anzeigen
		$commandarguments = $this->Commandargument->find('all', [
			'recursive' => -1,
			'conditions' => [
				'Commandargument.command_id' => $host['Host']['command_id']
			]
		]);

		$contacts_for_changelog = [];
		foreach($host['Contact'] as $contact_id){
			$contacts_for_changelog[] = [
				'id' => $contact_id,
				'name' => $contacts[$contact_id]
			];
		}
		$contactgroups_for_changelog = [];
		foreach($host['Contactgroup'] as $contactgroup_id){
			if(isset($contactgroups[$contactgroup_id])){
				$contactgroups_for_changelog[] = [
					'id' => $contactgroup_id,
					'name' => $contactgroups[$contactgroup_id]
				];
			}
		}
		$hostgroups_for_changelog = [];
		foreach($host['Hostgroup'] as $hostgroup_id){
			if(isset($hostgroups[$hostgroup_id])){
				$hostgroups_for_changelog[] = [
					'id' => $hostgroup_id,
					'name' => $hostgroups[$hostgroup_id]
				];
			}
		}
		$parenthosts_for_changelog = [];
		foreach($host['Parenthost'] as $parenthost_id){
			$parenthosts_for_changelog[] = [
				'id' => $parenthost_id,
				'name' => $hosts[$parenthost_id]
			];
		}
		$host_for_changelog['Contact'] = $contacts_for_changelog;
		$host_for_changelog['Contactgroup'] = $contactgroups_for_changelog;
		$host_for_changelog['Hostgroup'] = $hostgroups_for_changelog;
		$host_for_changelog['Parenthost'] = $parenthosts_for_changelog;

		$masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];

		$ContactsInherited = $this->__inheritContactsAndContactgroups($host);
		$this->Frontend->setJson('ContactsInherited', $ContactsInherited);

		$this->set('back_url', $this->referer());
		$this->set(compact([
			'host',
			'containers',
			'timeperiods',
			'commands',
			'contactgroups',
			'contacts',
			'userContainerId',
			'userValues',
			'Customvariable',
			'hosttemplates',
			'hosts',
			'hostgroups',
			'commandarguments',
			'masterInstance',
			'ContactsInherited',
		]));
		if($this->request->is('post') || $this->request->is('put')){
			$ext_data_for_changelog = [
				'Contact' => [],
				'Contactgroup' => [],
				'Hostgroup' => [],
				'Parenthost' => []
			];
			if($this->request->data('Host.Contact')){
				if($contactsForChangelog = $this->Contact->find('list', [
					'conditions' => [
						'Contact.id' => $this->request->data['Host']['Contact']
					]
				])){
					foreach($contactsForChangelog as $contactId => $contactName){
						$ext_data_for_changelog['Contact'][] = [
							'id' => $contactId,
							'name' => $contactName
						];
					}
					unset($contactsForChangelog);
				}
			}
			if($this->request->data('Host.Contactgroup')){
				if($contactgroupsForChangelog = $this->Contactgroup->find('all', [
					'recursive' => -1,
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						]
					],
					'fields' => [
						'Contactgroup.id'
					],
					'conditions' => [
						'Contactgroup.id' => $this->request->data['Host']['Contactgroup']
					]
				])){
					foreach($contactgroupsForChangelog as $contactgroupData){
						$ext_data_for_changelog['Contactgroup'][] = [
							'id' => $contactgroupData['Contactgroup']['id'],
							'name' => $contactgroupData['Container']['name']
						];
					}
					unset($contactgroupsForChangelog);
				}
			}
			if($this->request->data('Host.Hostgroup')){
				if($hostgroupsForChangelog = $this->Hostgroup->find('all', [
					'recursive' => -1,
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						]
					],
					'fields' => [
						'Hostgroup.id'
					],
					'conditions' => [
						'Hostgroup.id' => $this->request->data['Host']['Hostgroup']
					]
				])){
					foreach($hostgroupsForChangelog as $hostgroupData){
						$ext_data_for_changelog['Hostgroup'][] = [
							'id' => $hostgroupData['Hostgroup']['id'],
							'name' => $hostgroupData['Container']['name']
						];
					}
					unset($hostgroupsForChangelog);
				}
			}
			if($this->request->data('Host.notify_period_id')){
				if($timeperiodsForChangelog = $this->Timeperiod->find('list', [
					'conditions' => [
						'Timeperiod.id' => $this->request->data['Host']['notify_period_id']
					]
				])){
					foreach($timeperiodsForChangelog as $timeperiodId => $timeperiodName){
						$ext_data_for_changelog['NotifyPeriod'] = [
							'id' => $timeperiodId,
							'name' => $timeperiodName
						];
					}
					unset($timeperiodsForChangelog);
				}
			}
			if($this->request->data('Host.check_period_id')){
				if($timeperiodsForChangelog = $this->Timeperiod->find('list', [
					'conditions' => [
						'Timeperiod.id' => $this->request->data['Host']['check_period_id']
					]
				])){
					foreach($timeperiodsForChangelog as $timeperiodId => $timeperiodName){
						$ext_data_for_changelog['CheckPeriod'] = [
							'id' => $timeperiodId,
							'name' => $timeperiodName
						];
					}
					unset($timeperiodsForChangelog);
				}
			}
			if($this->request->data('Host.hosttemplate_id')){
				if($hosttemplatesForChangelog = $this->Hosttemplate->find('list', [
					'conditions' => [
						'Hosttemplate.id' => $this->request->data['Host']['hosttemplate_id']
					]
				])){
					foreach($hosttemplatesForChangelog as $hosttemplateId => $hosttemplateName){
						$ext_data_for_changelog['Hosttemplate'] = [
							'id' => $hosttemplateId,
							'name' => $hosttemplateName
						];
					}
					unset($hosttemplatesForChangelog);
				}
			}
			if($this->request->data('Host.command_id')){
				if($commandsForChangelog = $this->Command->find('list', [
					'conditions' => [
						'Command.id' => $this->request->data['Host']['command_id']
					]
				])){
					foreach($commandsForChangelog as $commandId => $commandName){
						$ext_data_for_changelog['CheckCommand'] = [
							'id' => $commandId,
							'name' => $commandName
						];
					}
					unset($commandsForChangelog);
				}
			}
			if($this->request->data('Host.Parenthost')){
				if($hostsForChangelog = $this->Host->find('list', [
					'conditions' => [
						'Host.id' => $this->request->data['Host']['Parenthost']
					]
				])){
					foreach($hostsForChangelog as $hostId => $hostName){
						$ext_data_for_changelog['Parenthost'][] = [
							'id' => $hostId,
							'name' => $hostName
						];
					}
					unset($hostsForChangelog);
				}
			}

			$this->Host->id = $id;
			$this->request->data['Contact']['Contact'] = $this->request->data['Host']['Contact'];
			$this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Host']['Contactgroup'];
			$this->request->data['Parenthost']['Parenthost'] = $this->request->data['Host']['Parenthost'];
			$this->request->data['Hostgroup']['Hostgroup'] = (is_array($this->request->data['Host']['Hostgroup'])) ? $this->request->data['Host']['Hostgroup'] : [];
			$hosttemplate = [];
			if(isset($this->request->data['Host']['hosttemplate_id']) && $this->Hosttemplate->exists($this->request->data['Host']['hosttemplate_id'])){
				$hosttemplate = $this->Hosttemplate->findById($this->request->data['Host']['hosttemplate_id']);
			}
			$data_to_save = Hash::merge(
				$this->_diffWithTemplate($this->request->data, $hosttemplate),
				[
					'Host' => [
						'hosttemplate_id' => $this->request->data['Host']['hosttemplate_id'],
						'container_id' => $this->request->data['Host']['container_id']
					],
					'Container' => [
						'container_id' => $this->request->data['Host']['container_id']
					]
				]
			);
			$data_to_save = $this->Host->prepareForSave($this->_diffWithTemplate($this->request->data, $hosttemplate),
				$this->request->data, 'edit');

			//Delete Command argument values
			//Fetching all commandargument_id of the command arguments out of database:
			$commandargumentIdsOfDatabase = Hash::extract($host['Hostcommandargumentvalue'], '{n}.commandargument_id');

			//Fetching all commandargument_id out of $this->request-data
			$commandargumentIdsOfRequest = [];
			if(isset($this->request->data['Hostcommandargumentvalue'])){
				$commandargumentIdsOfRequest = Hash::extract($this->request->data['Hostcommandargumentvalue'], '{n}.commandargument_id');
			}
			// Checking if the user deleted this argument or changed the command and if we need to delete it out of the database
			foreach($commandargumentIdsOfDatabase as $commandargumentId){
				if(!in_array($commandargumentId, $commandargumentIdsOfRequest)){
					// Deleteing the parameter of the argument out of database (sorry ugly php 5.4+ syntax - check twice before modify)

					$this->Hostcommandargumentvalue->delete(
						$this->Hostcommandargumentvalue->find('first', [
							'conditions' => [
								'host_id' => $id,
								'commandargument_id' => $commandargumentId
							]
						])
						['Hostcommandargumentvalue']
					);
				}
			}

			if(!isset($data_to_save['Hostcommandargumentvalue']) || empty($data_to_save['Hostcommandargumentvalue'])){
				$this->Hostcommandargumentvalue->deleteAll(
					['Hostcommandargumentvalue.host_id' => $id]
				);
			}

			if($this->Host->saveAll($data_to_save)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$id,
					OBJECT_HOST,
					$this->request->data('Host.container_id'),
					$userId,
					$this->request->data['Host']['name'],
					array_merge($this->request->data, $ext_data_for_changelog),
					$host_for_changelog
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('<a href="/hosts/edit/%s">Host</a> modified successfully', $host['Host']['id']));
				$this->loadModel('Tenant');
				//$this->Tenant->hostCounter($this->request->data['Host']['container_id'], '+');
				$redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
				$this->redirect($redirect);
			}else{
				$this->setFlash(__('Data could not be saved'), false);
			}
		}
	}


	function sharing($id = null){
		$this->set('MY_RIGHTS', $this->MY_RIGHTS);
		$userId = $this->Auth->user('id');

		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		$host = $this->Host->find('first', [
			'conditions' => [
				'Host.id' => $id,
			],
			'contain' => [
				'Container'
			],
		]);
		$containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
		$containerIdsToCheck[] = $host['Host']['container_id'];
		if(!$this->allowedByContainerId($containerIdsToCheck)){
			$this->render403();
			return;
		}
		$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
		$sharingContainers = array_diff_key($containers, [$host['Host']['container_id'] => $host['Host']['container_id']]);
		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Container']['Container'][] = $this->request->data['Host']['container_id'];
			if($this->Host->saveAll(Hash::merge($this->request->data, $host))){
				if($this->request->ext == 'json'){
					$this->serializeId(); // REST API ID serialization
				}else{
					$this->setFlash(__('Host modified successfully'));
					$redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
					$this->redirect($redirect);
				}
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();
				}else{
					$this->setFlash(__('Data could not be saved'), false);
				}
			}
		}
		$this->set(compact(['host','containers', 'sharingContainers']));
	}

	function edit_details($host_id = null){
		$this->set('MY_RIGHTS', $this->MY_RIGHTS);
		$this->set('back_url', $this->referer());
		$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
		$contacts = $this->Contact->contactsByContainerId($containerIds, 'list', 'id');
		$contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list', 'id');

		if($this->request->is('post') || $this->request->is('put')){
			foreach(func_get_args() as $host_id){
				$this->Host->unbindModel([
						'hasMany' => ['Hostcommandargumentvalue', 'HostescalationHostMembership', 'HostdependencyHostMembership', 'Service', 'Customvariable'],
						'hasAndBelongsToMany' => ['Parenthost', 'Hostgroup'],
						'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand']
					]
				);
				$data = ['Host' => []];
				$host = $this->Host->findById($host_id);
				if(!empty($host)){
					//Fill up required fields
					$data['Host']['id'] = $host['Host']['id'];
					$data['Host']['name'] = $host['Host']['name'];
					$data['Host']['hosttemplate_id'] = $host['Host']['hosttemplate_id'];
					$data['Host']['address'] = $host['Host']['address'];

					if($this->request->data('Host.edit_description') == 1){
						$data['Host']['description'] = $this->request->data('Host.description');
					}

					if($this->request->data('Host.edit_contacts') == 1){
						$_contacts = [];
						if($this->request->data('Host.keep_contacts') == 1){
							if(!empty($host['Contact'])){
								//Merge exsting contacts with new contacts
								$_contacts = Hash::extract($host['Contact'], '{n}.id');
								$_contacts = Hash::merge($_contacts, $this->request->data('Host.Contact'));
								$_contacts = array_unique($_contacts);
							}else{
								// There are no old contacts to overwirte, wo we take the current request data
								$_contacts = $this->request->data('Host.Contact');
							}
						}else{
							////Overwrite all old contacts
							$_contacts = $this->request->data('Host.Contact');
						}
						$data['Host']['Contact'] = $_contacts;
						$data['Contact'] = [
							'Contact' => $_contacts
						];
					}

					if($this->request->data('Host.edit_contactgroups') == 1){
						$_contactgroups = [];
						if($this->request->data('Host.keep_contactgroups') == 1){
							if(!empty($host['Contactgroup'])){
								//Merge existing contactgroups to new contact groups
								$_contactgroups = Hash::extract($host['Contactgroup'], '{n}.id');
								$_contactgroups = Hash::merge($_contactgroups, $this->request->data('Host.Contactgroup'));
								$_contactgroups = array_unique($_contactgroups);
							}else{
								// There are no old contact groups to overwirte, wo we take the current request data
								$_contactgroups = $this->request->data('Host.Contactgroup');
							}
						}else{
							//Overwrite all old contact groups
							$_contactgroups = $this->request->data('Host.Contactgroup');
						}
						$data['Host']['Contactgroup'] = $_contactgroups;
						$data['Contactgroup'] = [
							'Contactgroup' => $_contactgroups
						];
					}

					if(!empty($data['Host']['Contact']) || !empty($data['Host']['Contactgroup'])){
						//Welcome to nagios 4 -.-
						$data['Host']['own_contacts'] = 1;
						$data['Host']['own_contactgroups'] = 1;
					}else{
						if(isset($_contacts) || isset($_contactgroups)){
							// Only if the user has submit a contact or a contact group, may be he want to delet all contacts.
							$data['Host']['own_contacts'] = 0;
							$data['Host']['own_contactgroups'] = 0;
							$data['Host']['Contact'] = [];
							$data['Host']['Contactgroup'] = [];
						}
					}

					if($this->request->data('Host.edit_url') == 1){
						$data['Host']['host_url'] = $this->request->data('Host.host_url');
					}

					if($this->request->data('Host.edit_tags') == 1){
						$data['Host']['tags'] = $this->request->data('Host.tags');
						if($this->request->data('Host.keep_tags') == 1){
							if(!empty($host['Host']['tags'])){
								//Host has tags, lets merge this
								$data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Host']['tags']), explode(',', $data['Host']['tags']))));
							}else{
								if(!empty($host['Hosttemplate']['tags'])){
									//The host has no own tags, lets merge from hosttemplate
									$data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Hosttemplate']['tags']), explode(',', $data['Host']['tags']))));
								}
							}
						}
					}
					$this->Host->save($data);
					unset($data);
				}
			}
			$this->setFlash(__('Host modified successfully'));
			$redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
			$this->redirect($redirect);

			return;
		}

		$this->set(compact(['contacts', 'contactgroups']));
	}

	public function add(){
		$this->set('MY_RIGHTS', $this->MY_RIGHTS);
		//Empty variables, get fild if Model::save() fails for refill
		$_hosttemplates = [];
		$_hostgroups = [];
		$_parenthosts = [];
		$_timeperiods = [];
		$_contacts = [];
		$_contactgroups = [];


		$userId = $this->Auth->user('id');
		$this->set('back_url', $this->referer());
		$this->Frontend->setJson('lang_minutes', __('minutes'));
		$this->Frontend->setJson('lang_seconds', __('seconds'));
		$this->Frontend->setJson('lang_and', __('and'));
		$this->Frontend->setJson('dns_hostname_lookup_failed', __('Could not resolve hostname'));
		$this->Frontend->setJson('dns_ipaddress_lookup_failed', __('Could not reverse lookup your ip address'));
		$this->Frontend->setJson('hostname_placeholder', __('Will be auto detected if you enter a ip address'));
		$this->Frontend->setJson('address_placeholder', __('Will be auto detected if you enter a FQDN'));


		// Checking if the user hit submit and a validation error happents, to refill input fields
		$Customvariable = [];
		$customFieldsToRefill = [
			'Host' => [
				'notification_interval',
				'notify_on_recovery',
				'notify_on_down',
				'notify_on_unreachable',
				'notify_on_flapping',
				'notify_on_downtime',
				'check_interval',
				'retry_interval',
				'flap_detection_enabled',
				'flap_detection_on_up',
				'flap_detection_on_down',
				'flap_detection_on_unreachable',
				'priority'
			],
			'Contact' => [
				'Contact'
			],
			'Contactgroup' => [
				'Contactgroup'
			]
		];
		$this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

		//Fix that we dont lose any unsaved host macros, because of vaildation error
		if(isset($this->request->data['Customvariable'])){
			$Customvariable = $this->request->data['Customvariable'];
			$this->Frontend->setJson('customVariablesCount', sizeof($Customvariable));
		}else{
			$this->Frontend->setJson('customVariablesCount', 0);
		}

		$this->loadModel('Timeperiod');

		$this->loadModel('Command');
		$this->loadModel('Contact');
		$this->loadModel('Contactgroup');
		$this->loadModel('Container');
		$this->loadModel('Customvariable');
		$this->loadModel('Hosttemplate');
		$this->loadModel('Hostgroup');

		$commands = $this->Command->hostCommands('list');

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
		}

		$masterInstance = $this->Systemsetting->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];

		$this->set('back_url', $this->referer());

		if($this->request->is('post') || $this->request->is('put')){

			$ext_data_for_changelog = [];
			if($this->request->data('Host.Contact')){
				if($contactsForChangelog = $this->Contact->find('list', [
					'conditions' => [
						'Contact.id' => $this->request->data['Host']['Contact']
					]
				])){
					foreach($contactsForChangelog as $contactId => $contactName){
						$ext_data_for_changelog['Contact'][] = [
							'id' => $contactId,
							'name' => $contactName
						];
					}
					unset($contactsForChangelog);
				}
			}
			if($this->request->data('Host.Contactgroup')){
				if($contactgroupsForChangelog = $this->Contactgroup->find('all', [
					'recursive' => -1,
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						]
					],
					'fields' => [
						'Contactgroup.id'
					],
					'conditions' => [
						'Contactgroup.id' => $this->request->data['Host']['Contactgroup']
					]
				])){
					foreach($contactgroupsForChangelog as $contactgroupData){
						$ext_data_for_changelog['Contactgroup'][] = [
							'id' => $contactgroupData['Contactgroup']['id'],
							'name' => $contactgroupData['Container']['name']
						];
					}
					unset($contactgroupsForChangelog);
				}
			}
			if($this->request->data('Host.Hostgroup')){
				if($hostgroupsForChangelog = $this->Hostgroup->find('all', [
					'recursive' => -1,
					'contain' => [
						'Container' => [
							'fields' => [
								'Container.name'
							]
						]
					],
					'fields' => [
						'Hostgroup.id'
					],
					'conditions' => [
						'Hostgroup.id' => $this->request->data['Host']['Hostgroup']
					]
				])){
					foreach($hostgroupsForChangelog as $hostgroupData){
						$ext_data_for_changelog['Hostgroup'][] = [
							'id' => $hostgroupData['Hostgroup']['id'],
							'name' => $hostgroupData['Container']['name']
						];
					}
					unset($hostgroupsForChangelog);
				}
			}
			if($this->request->data('Host.notify_period_id')){
				if($timeperiodsForChangelog = $this->Timeperiod->find('list', [
					'conditions' => [
						'Timeperiod.id' => $this->request->data['Host']['notify_period_id']
					]
				])){
					foreach($timeperiodsForChangelog as $timeperiodId => $timeperiodName){
						$ext_data_for_changelog['NotifyPeriod'] = [
							'id' => $timeperiodId,
							'name' => $timeperiodName
						];
					}
					unset($timeperiodsForChangelog);
				}
			}
			if($this->request->data('Host.check_period_id')){
				if($timeperiodsForChangelog = $this->Timeperiod->find('list', [
					'conditions' => [
						'Timeperiod.id' => $this->request->data['Host']['check_period_id']
					]
				])){
					foreach($timeperiodsForChangelog as $timeperiodId => $timeperiodName){
						$ext_data_for_changelog['CheckPeriod'] = [
							'id' => $timeperiodId,
							'name' => $timeperiodName
						];
					}
					unset($timeperiodsForChangelog);
				}
			}
			if($this->request->data('Host.hosttemplate_id')){
				if($hosttemplatesForChangelog = $this->Hosttemplate->find('list', [
					'conditions' => [
						'Hosttemplate.id' => $this->request->data['Host']['hosttemplate_id']
					]
				])){
					foreach($hosttemplatesForChangelog as $hosttemplateId => $hosttemplateName){
						$ext_data_for_changelog['Hosttemplate'] = [
							'id' => $hosttemplateId,
							'name' => $hosttemplateName
						];
					}
					unset($hosttemplatesForChangelog);
				}
			}
			if($this->request->data('Host.command_id')){
				if($commandsForChangelog = $this->Command->find('list', [
					'conditions' => [
						'Command.id' => $this->request->data['Host']['command_id']
					]
				])){
					foreach($commandsForChangelog as $commandId => $commandName){
						$ext_data_for_changelog['CheckCommand'] = [
							'id' => $commandId,
							'name' => $commandName
						];
					}
					unset($commandsForChangelog);
				}
			}
			if($this->request->data('Host.Parenthost')){
				if($hostsForChangelog = $this->Host->find('list', [
					'conditions' => [
						'Host.id' => $this->request->data['Host']['Parenthost']
					]
				])){
					foreach($hostsForChangelog as $hostId => $hostName){
						$ext_data_for_changelog['Parenthost'][] = [
							'id' => $hostId,
							'name' => $hostName
						];
					}
					unset($hostsForChangelog);
				}
			}

			if(isset($this->request->data['Host']['Contact'])){
				$this->request->data['Contact']['Contact'] = $this->request->data['Host']['Contact'];
			}else{
				$this->request->data['Host']['Contact'] = [];
			}

			if(isset($this->request->data['Host']['Contactgroup'])){
				$this->request->data['Contactgroup']['Contactgroup'] = $this->request->data['Host']['Contactgroup'];
			}

			if(!isset($this->request->data['Host']['Parenthost'])){
				$this->request->data['Host']['Parenthost'] = [];
			}
			if(is_array($this->request->data['Host']['Parenthost'])){
				$this->request->data['Parenthost']['Parenthost'] = $this->request->data['Host']['Parenthost'];
			}else{
				$this->request->data['Parenthost']['Parenthost'] = [];
			}

			if(isset($this->request->data['Host']['Hostgroup']) && is_array($this->request->data['Host']['Hostgroup'])){
				$this->request->data['Hostgroup']['Hostgroup'] = $this->request->data['Host']['Hostgroup'];
			}else{
				$this->request->data['Hostgroup']['Hostgroup'] = [];
			}

			$hosttemplate = [];
			if(isset($this->request->data['Host']['hosttemplate_id']) &&
				$this->Hosttemplate->exists($this->request->data['Host']['hosttemplate_id'])
			){
				$hosttemplate = $this->Hosttemplate->findById($this->request->data['Host']['hosttemplate_id']);
			}
			App::uses('UUID', 'Lib');

			$data_to_save = $this->Host->prepareForSave(
				$this->_diffWithTemplate($this->request->data, $hosttemplate),
				$this->request->data,
				'add'
			);
			if($this->Host->saveAll($data_to_save)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Host->id,
					OBJECT_HOST,
					$this->request->data('Host.container_id'),
					$userId,
					$this->request->data['Host']['name'],
					array_merge($this->request->data, $ext_data_for_changelog)
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				if($this->request->ext == 'json'){
					$this->serializeId(); // REST API ID serialization
				}else{
					$this->setFlash(__('<a href="/hosts/edit/%s">Host</a> created successfully', $this->Host->id));
					$this->loadModel('Tenant');
					//				$this->Tenant->hostCounter($this->request->data['Host']['container_id'], '+');
					$this->redirect(array('action' => 'notMonitored'));
				}
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();
				}else{
					$this->setFlash(__('Data could not be saved'), false);
				}

				//Refil data that was loaded by ajax due to selected container id
				if($this->Container->exists($this->request->data('Host.container_id'))){
					$container_id = $this->request->data('Host.container_id');

					$containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);
					$_hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list');
					$_hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id');
					$_parenthosts = $this->Host->hostsByContainerId($containerIds, 'list');
					$_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
					$_contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
					$_contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
				}

				$this->setFlash(__('Data could not be saved'), false);
			}
		}
		//Refil ajax stuff if set or not
		$this->set(compact(['_hosttemplates', '_hostgroups', '_parenthosts', '_timeperiods', '_contacts', '_contactgroups', 'commands','containers', 'masterInstance', 'Customvariable']));
	}

	public function disabled(){
		//$this->__unbindAssociations('Service');
		if(!isset($this->request->params['named']['BrowserContainerId'])){
			$conditions = [
				'Host.disabled' => 1,
				'HostsToContainers.container_id' => $this->MY_RIGHTS,
			];
		}
		$conditions = $this->ListFilter->buildConditions([], $conditions);
		$query = [
			'recurisve' => -1,
			'conditions' => [
				$conditions
			],
			'contain' => [
				'Hosttemplate',
				//'Container'
			],
			//'contain' => [],
			'fields' => [
				'Host.id',
				'Host.uuid',
				'Host.name',
				//'Host.description',
				//'Host.active_checks_enabled',
				'Host.address',
				'Host.satellite_id',
				'Hosttemplate.name',

			],
			'joins' => [
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				]
			],
			'order' => ['Host.name' => 'asc'],
			'group' => [
				'Host.id'
			]
		];

		if($this->isApiRequest()){
			$disabledHosts = $this->Host->find('all', $query);
		}else{
			$this->Paginator->settings = $query;
			$disabledHosts = $this->Paginator->paginate();
		}


		/*$activeHostCount = $this->Host->find('count', [
			'conditions' => ['Host.disabled' => 0]
		]);

		$disabledHostCount = $this->Host->find('count', [
			'conditions' => ['Host.disabled' => 1]
		]);

		$deletedHostCount = $this->DeletedHost->find('count');*/
		$this->set(compact(['disabledHosts']));
		$this->set('_serialize', ['disabledHosts']);

		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}

	public function deactivate($id = null, $return = false){
		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		$this->__unbindAssociations('Host');
		if($this->Host->updateAll(['Host.disabled' => 1], ['Host.id' => $id])){
			$this->loadModel('Service');
			$this->__unbindAssociations('Service');
			if($this->Service->updateAll(['Service.disabled' => 1], ['Service.host_id' => $id])){
				if($return === false){
					$this->setFlash(__('Host disabled'));
					$this->redirect(array('action' => 'index'));
				}
				return true;
			}else{
				if($return === false){
					$this->setFlash(__('Could not disable services from host'), false);
					$this->Host->updateAll(['Host.disabled' => 0], ['Host.id' => $id]);
					$this->redirect(array('action' => 'index'));
				}
				return false;
			}
		}

		if($return === false){
			$this->setFlash(__('Could not disable host'), false);
			$this->redirect(array('action' => 'index'));
		}
		return false;
	}

	public function mass_deactivate($id = null){
		$flash = '';
		foreach(func_get_args() as $host_id){
			$host = $this->Host->findById($host_id);
			if(!empty($host)){
				$this->__unbindAssociations('Host');
				if($this->Host->updateAll(['Host.disabled' => 1], ['Host.id' => $host['Host']['id']])){
					$this->loadModel('Service');
					$this->__unbindAssociations('Service');
					if($this->Service->updateAll(['Service.disabled' => 1], ['Service.host_id' => $host['Host']['id']])){
						$flash .= __('Host ' . h($host['Host']['name']) . ' disabled successfully<br />');
					}else{
						$flash .= __('Services of Host ' . h($host['Host']['name']) . ' could not disabled successfully<br />');
						$this->Host->updateAll(['Host.disabled' => 0], ['Host.id' => $host['Host']['id']]);
					}
				}
			}
		}
		$this->setFlash($flash);
		$this->redirect(array('action' => 'index'));
	}


	public function enable($id = null){
		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		$this->__unbindAssociations('Host');
		if($this->Host->updateAll(['Host.disabled' => 0], ['Host.id' => $id])){
			$this->loadModel('Service');
			$this->__unbindAssociations('Service');
			if($this->Service->updateAll(['Service.disabled' => 0], ['Service.host_id' => $id])){
				$this->setFlash(__('Host enabled'));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Could not enable services from host'), false);
				$this->Host->updateAll(['Host.disabled' => 0], ['Host.id' => $id]);
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->setFlash(__('Could not enable host'), false);
		$this->redirect(array('action' => 'index'));
	}

	public function delete($id = null){
		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}

		$host = $this->Host->findById($id);

		$containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
		$containerIdsToCheck[] = $host['Host']['container_id'];
		if(!$this->allowedByContainerId($containerIdsToCheck)){
			$this->render403();
			return;
		}

		if($this->Host->__delete($host, $this->Auth->user('id'))){
			$this->setFlash(__('Host deleted'));
			$this->redirect(array('action' => 'index'));
		}

		$this->setFlash(__('Could not delete host'), false);
		$this->redirect(array('action' => 'index'));

	}

	/*
	 * Delete one or more hosts
	 * Call: mass_delete(1,5,10,15);
	 * Or as HTML URL: /hosts/mass_delete/3/6/5/4/8/2/1/9
	 */
	public function mass_delete($id = null){
		$deleteAllowedValues = [];
		foreach(func_get_args() as $host_id){
			if($this->Host->exists($host_id)){
				$host = $this->Host->findById($host_id);

				$containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
				$containerIdsToCheck[] = $host['Host']['container_id'];
				if(!$this->allowedByContainerId($containerIdsToCheck)){
					$this->render403();
					return;
				}

				$deleteAllowedValues[] = $this->Host->__delete($host, $this->Auth->user('id'));
			}
		}

		//array contains at least one false
		if(in_array(false, $deleteAllowedValues)){
			if(count(array_unique($deleteAllowedValues)) === 1 && end($deleteAllowedValues) == false){
				//no host could be deleted
				$this->setFlash(__('Hosts could not be deleted'), false);
				$this->redirect(['action' => 'index']);
			}else{
				//at least one host couldnt be deleted
				$this->setFlash(__('Some of the Hosts could not be deleted'), false);
				$this->redirect(['action' => 'index']);
			}
		}
		$this->setFlash(__('Hosts deleted'));
		$this->redirect(array('action' => 'index'));
	}

	public function copy($id = null){
		$validationErrors = [];
		if($this->request->is('post') || $this->request->is('put')){
			$validationError = false;
			$dataToSaveArray = [];
			$this->loadModel('Hosttemplate');
			App::uses('UUID', 'Lib');
			//We want to save/validate the data and save it
			foreach($this->request->data['Host'] as $key => $host2copy){
				if(!$this->Host->exists($host2copy)){
					continue;
				}
				$sourceHost = $this->Host->prepareForView($host2copy['source']);
				$hosttemplate = $this->Hosttemplate->findById($sourceHost['Hosttemplate']['id']);
				unset($sourceHost['Host']['id']);
				$sourceHost['Host']['uuid'] = UUID::v4();
				$sourceHost['Host']['name'] = $host2copy['name'];
				$sourceHost['Host']['description'] = $host2copy['description'];
				$sourceHost['Host']['address'] = $host2copy['address'];
				$sourceHost['Host']['host_url'] = $host2copy['host_url'];

				$sourceHost['Host']['Contact'] = $sourceHost['Contact'];
				$sourceHost['Host']['Contactgroup'] = $sourceHost['Contactgroup'];
				$sourceHost['Host']['Parenthost'] = $sourceHost['Parenthost'];
				$sourceHost['Host']['Hostgroup'] = (is_array($sourceHost['Hostgroup'])) ? $sourceHost['Hostgroup'] : [];

				$sourceHost['Contact']['Contact'] = $sourceHost['Contact'];
				$sourceHost['Contactgroup']['Contactgroup'] = $sourceHost['Contactgroup'];
				$sourceHost['Parenthost']['Parenthost'] = $sourceHost['Parenthost'];
				$sourceHost['Hostgroup']['Hostgroup'] = (is_array($sourceHost['Hostgroup'])) ? $sourceHost['Hostgroup'] : [];

				//debug($sourceHost);
				$data_to_save = $this->Host->prepareForSave($this->_diffWithTemplate($sourceHost, $hosttemplate), $sourceHost, 'add');
				$this->Host->create();
				$this->Host->set($data_to_save);
				if($this->Host->validates()){
					$dataToSaveArray[$host2copy['source']] = $data_to_save;
				}else{
					$validationError = true;
				}

				if(!empty($this->Host->validationErrors)){
					$validationErrors['Host'][$key] = $this->Host->validationErrors;
				}
			}

			if($validationError === false){
				//All data is valid we can create the copy of the host
				$this->loadModel('Service');
				$this->loadModel('Servicetemplate');
				foreach($dataToSaveArray as $sourceHostId => $data){
					$this->Host->create();
					$this->Host->saveAll($data);
					$services = $this->Service->find('all', [
						'conditions' => [
							'Service.host_id' => $sourceHostId,
							'Service.service_type' => $this->Service->serviceTypes('copy')
						]
					]);

					//A Cache for servicetemplates to reduce the SQL querys
					$servicetemplates = [];
					foreach($services as $service){
						if(isset($servicetemplates[$service['Service']['servicetemplate_id']])){
							$servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
						}else{
							$servicetemplates[$service['Service']['servicetemplate_id']] = $this->Servicetemplate->findById($service['Service']['servicetemplate_id']);
							$servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
						}
						unset($service['Service']['id']);
						$service['Service']['uuid'] = UUID::v4();
						$service['Service']['host_id'] = $this->Host->id;
						$service['Host'] = $data;

						$service['Service']['Contact'] = $service['Contact'];
						$service['Service']['Contactgroup'] = $service['Contactgroup'];
						$service['Service']['Servicegroup'] = (is_array($service['Servicegroup'])) ? $service['Servicegroup'] : [];

						$service['Contact']['Contact'] = $service['Contact'];
						$service['Contactgroup']['Contactgroup'] = $service['Contactgroup'];
						$service['Servicegroup']['Servicegroup'] = (is_array($service['Servicegroup'])) ? $service['Servicegroup'] : [];

						$data_to_save = $this->Service->prepareForSave($this->Service->diffWithTemplate($service, $servicetemplate), $service, 'add');
						$this->Service->create();
						$this->Service->saveAll($data_to_save);
					}
				}
				$this->setFlash(__('Host copied successfully'));
				$redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
				$this->redirect($redirect);
			}else{
				if(isset($validationErrors['Host'])){
					$this->Host->validationErrors = $validationErrors['Host'];
					$this->setFlash(__('Could not copy host/s'), false);
					/*
					For multiple "line" validation errors the array we gibe the view needs to look like this
	:
					array(
						(int) 0 => array(
							'name' => array(
								(int) 0 => 'This field cannot be left blank.'
							),
							'address' => array(
								(int) 0 => 'This field cannot be left blank.'
							)
						),
						(int) 1 => array(
							'address' => array(
								(int) 0 => 'This field cannot be left blank.'
							)
						)
					)
					*/
				}
			}
		}

		//We want to copy a host and display the view
		$hosts = [];
		foreach(func_get_args() as $host_id){
			if($this->Host->exists($host_id)){
				$hosts[] = $this->Host->findById($host_id);
				//debug($host);
			}
		}
		$this->set(compact(['hosts']));
		$this->set('back_url', $this->referer());
		//$this->setFlash(__('Hosts deleted'));
		//$this->redirect(array('action' => 'index'));
	}

	function browser($id = null){
		if($this->Host->exists($id)){
			$this->__unbindAssociations('Service');
			// $uses is not the best choise at this point.
			// due to loadModel() we get the results we need
			$this->loadModel('Service');
			$host = $this->Host->prepareForView($id);
			$this->Service->unbindModel([
				'hasAndBelongsToMany' => ['Servicegroup', 'Contact', 'Contactgroup'],
				'hasMany' => ['Servicecommandargumentvalue', 'Serviceeventcommandargumentvalue', 'ServiceEscalationServiceMembership', 'ServicedependencyServiceMembership', 'Customvariable'],
				'belongsTo' => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand']
			]);
			$_host = $this->Host->find('first', [
				'conditions' => [
					'Host.id' => $id,
				],
				'contain' => [
					'Contactgroup' => [
						'Container'
					],
					'Contact',
					'Hostcommandargumentvalue',
					'Container'
				],
			]);


			$containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
			$containerIdsToCheck[] = $_host['Host']['container_id'];

			//Check if user is permitted to see this object
			if(!$this->allowedByContainerId($containerIdsToCheck, false)){
				$this->render403();
				return;
			}

			//Check if user is permitted to edit this object
			$allowEdit = false;
			if($this->allowedByContainerId($containerIdsToCheck)){
				$allowEdit = true;
			}

			$services = $this->Service->findAllByHostIdAndDisabled($id, 0);

			$commandarguments = [];
			if(!empty($_host['Hostcommandargumentvalue'])){
				//The service has own command argument values
				$_commandarguments = $this->Hostcommandargumentvalue->findAllByHostId($_host['Host']['id']);
				$_commandarguments = Hash::sort($_commandarguments, '{n}.Commandargument.name', 'asc', 'natural');
				foreach($_commandarguments as $commandargument){
					$commandarguments[$commandargument['Commandargument']['name']] = $commandargument['Hostcommandargumentvalue']['value'];
				}
			}else{
				//The service command arguments are from the template
				$_commandarguments = $this->Hosttemplatecommandargumentvalue->findAllByHosttemplateId($host['Hosttemplate']['id']);
				$_commandarguments = Hash::sort($_commandarguments, '{n}.Commandargument.name', 'asc', 'natural');
				foreach($_commandarguments as $commandargument){
					$commandarguments[$commandargument['Commandargument']['name']] = $commandargument['Hosttemplatecommandargumentvalue']['value'];
				}
			}

			$ContactsInherited = $this->__inheritContactsAndContactgroups($host, $_host);

			$parenthosts = [];
			if(!empty($host['Parenthost'])){
				$parenthosts = $this->Host->find('all', [
					'recursive' => -1,
					'conditions' => [
						'Host.id' => $host['Parenthost'],
					],
					'fields' => [
						'Host.id',
						'Host.uuid',
						'Host.name'
					]
				]);
				$hoststatus = $this->Hoststatus->byUuid(Hash::merge([$host['Host']['uuid']], Hash::extract($parenthosts, '{n}.Host.uuid')));
			}else{
				$hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid']);
			}

			$hostDocuExists = $this->Documentation->existsForHost($host['Host']['uuid']);


			$acknowledged = [];
			if(isset($hoststatus[$host['Host']['uuid']]['Hoststatus']) && $hoststatus[$host['Host']['uuid']]['Hoststatus']['problem_has_been_acknowledged'] > 0){
				$acknowledged = $this->Acknowledged->byHostUuid($host['Host']['uuid']);
			}
			if(isset($acknowledged[0]['Acknowledged']['comment_data'])) {
				$systemTicketLink = $this->Systemsetting->find('first', [
					'conditions' => ['key' => 'TICKET_SYSTEM.URL']
				]);
				$ticketSysLink = isset($systemTicketLink['Systemsetting']['value']) ? $systemTicketLink['Systemsetting']['value'] : null;
				$explodedAck = explode(';', $acknowledged[0]['Acknowledged']['comment_data']);
				$acknowledged[0]['Acknowledged']['otrs_link'] = is_null($ticketSysLink) ? __('No url was set in systemsettings') : (' <a target="_blank" href="' . $ticketSysLink . $explodedAck[sizeof($explodedAck) - 1] . '">' . __('OTRS Ticket') . ': ' . $explodedAck[sizeof($explodedAck) - 1] . '</a>');
				unset($explodedAck[sizeof($explodedAck) - 1]);
				$acknowledged[0]['Acknowledged']['comment_data'] = implode(';', $explodedAck);
			}
			$servicestatus = $this->Servicestatus->byUuid(Hash::extract($services, '{n}.Service.uuid'));
			$username = $this->Auth->user('full_name');
			$this->set(compact(['host', 'hoststatus', 'servicestatus', 'services', 'username', 'path', 'commandarguments', 'acknowledged', 'hostDocuExists', 'ContactsInherited', 'parenthosts', 'allowEdit']));
			$this->Frontend->setJson('websocket_url', 'wss://' . env('HTTP_HOST') . '/sudo_server');
			$this->Frontend->setJson('hostUuid', $host['Host']['uuid']);
			$this->loadModel('Systemsetting');
			$key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
			$this->Frontend->setJson('akey', $key['Systemsetting']['value']);
		}else{
			throw new NotFoundException(__('Host not found'));
		}
	}

	/**
	 * Converts BB code to HTML
	 * @param string $uuid        The hosts UUID you want to get the long output
	 * @param bool   $parseBbcode If you want to convert BB Code to HTML
	 * @param bool   $nl2br       If you want to replace \n with <br>
	 * @return string
	 */
	public function longOutputByUuid($uuid = null, $parseBbcode = true, $nl2br = true){
		$this->autoRender = false;
		$result = $this->Host->findByUuid($uuid);
		if(!empty($result)){
			$hoststatus = $this->Hoststatus->byUuid($result['Host']['uuid']);
			if(isset($hoststatus[$result['Host']['uuid']]['Hoststatus']['long_output'])){
				if($parseBbcode === true){
					if($nl2br === true){
						return $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($hoststatus[$result['Host']['uuid']]['Hoststatus']['long_output'], $nl2br));
					}else{
						return $this->Bbcode->asHtml($hoststatus[$result['Host']['uuid']]['Hoststatus']['long_output'], $nl2br);
					}
				}
				return $hoststatus[$result['Host']['uuid']]['Hoststatus']['long_output'];
			}
		}
		return '';
	}

	/* !!!!!
	 * NEVER EVER CALL THIS FUNCTION !!!!!!!!
	 */
	protected function resetAllUUID(){
		throw new BadRequestException('To call this function is a really bad idea, because all your UUIDs get lost and generated new. So this function is disabled by default!');
		return false;
		foreach($this->Host->find('all', ['fields' => ['uuid']]) as $host){
			debug($host);
			$host['Host']['uuid'] = $this->Host->createUUID();
			$this->Host->save($host);
		}
	}

	public function gethostbyname(){
		$this->autoRender = false;
		if($this->request->is('ajax') && isset($this->request->data['hostname']) && $this->request->data['hostname'] != ''){
			$ip = gethostbyname($this->request->data['hostname']);
			if(filter_var($ip, FILTER_VALIDATE_IP)){
				echo $ip;
				return;
			}
		}
		echo '';
	}

	public function gethostbyaddr(){
		$this->autoRender = false;
		if($this->request->is('ajax') && isset($this->request->data['address']) && filter_var($this->request->data['address'], FILTER_VALIDATE_IP)){
			$fqdn = gethostbyaddr($this->request->data['address']);
			if(strlen($fqdn) > 0 && $fqdn != $this->request->data['address']){
				echo $fqdn;
				return;
			}
		}
		echo '';
	}

	public function loadHosttemplate($hosttemplate_id = null){
		$this->allowOnlyAjaxRequests();

		$this->loadModel('Hosttemplate');
		if(!$this->Hosttemplate->exists($hosttemplate_id)){
			throw new NotFoundException(__('Invalid hosttemplate'));
		}

		$hosttemplate = $this->Hosttemplate->find(
			'first', [
				'conditions' => [
					'Hosttemplate.id' => $hosttemplate_id
				],
				'contain' => [
					'Contactgroup' => 'Container',
					'CheckCommand',
					'Container',
					'Customvariable',
					'NotifyPeriod',
					'Contact',
					'Hosttemplatecommandargumentvalue',
					'CheckPeriod',
				],
			]
		);

		$this->set(compact(['hosttemplate']));
		$this->set('_serialize', ['hosttemplate']);
	}

	public function addCustomMacro($counter){
		$this->allowOnlyAjaxRequests();

		$this->set('objecttype_id', OBJECT_HOST);
		$this->set('counter', $counter);
	}

	public function loadTemplateMacros($hosttemplate_id = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		$this->loadModel('Hosttemplate');
		if(!$this->Hosttemplate->exists($hosttemplate_id)){
			throw new NotFoundException(__('Invalid hosttemplate'));
		}

		if($this->Hosttemplate->exists($hosttemplate_id)){
			$hosttemplate = $this->Hosttemplate->findById($hosttemplate_id);
			// Remove ids of custom variables that if the user change them we dont overwrite the orginal custom variables form host template in the database
			$hosttemplate['Customvariable'] = Hash::remove($hosttemplate['Customvariable'], '{n}.id');
		}
		$this->set('hosttemplate', $hosttemplate);
	}

	public function loadParametersByCommandId($command_id = null, $hosttemplate_id = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		$test = [];
		$commandarguments = [];
		if($command_id){
			$commandarguments = $this->Commandargument->find('all', [
				'recursive' => -1,
				'conditions' => [
					'Commandargument.command_id' => $command_id
				]
			]);
			//print_r($commandarguments);
			foreach($commandarguments as $key => $commandargument){
				if($hosttemplate_id){
					$hosttemplate_command_argument_value = $this->Hosttemplatecommandargumentvalue->find('first', [
						'conditions' => [
							'Hosttemplatecommandargumentvalue.hosttemplate_id' => $hosttemplate_id,
							'Hosttemplatecommandargumentvalue.commandargument_id' => $commandargument['Commandargument']['id']
						],
						'fields' => 'Hosttemplatecommandargumentvalue.value'
					]);
					if(isset($hosttemplate_command_argument_value['Hosttemplatecommandargumentvalue']['value'])){
						$commandarguments[$key]['Hosttemplatecommandargumentvalue']['value'] = $hosttemplate_command_argument_value['Hosttemplatecommandargumentvalue']['value'];
					}
				}
			}
		}

		$this->set(compact('commandarguments'));
	}

	public function loadArguments($command_id = null, $hosttemplate_id = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		if(!$this->Hosttemplate->exists($hosttemplate_id)){
			throw new NotFoundException(__('Invalid hosttemplate'));
		}

		$commandarguments = [];

		$commandarguments = $this->Hosttemplatecommandargumentvalue->find('all', [
			'conditions' => [
				'Commandargument.command_id' => $command_id,
				'Hosttemplatecommandargumentvalue.hosttemplate_id' => $hosttemplate_id
			]
		]);

		//Checking if the hosttemplade has own arguments defined
		if(empty($commandarguments)){

			$commandarguments = $this->Commandargument->find('all', [
				'recursive' => -1,
				'conditions' => [
					'Commandargument.command_id' => $command_id,
				]
			]);
		}

		$this->set('commandarguments', $commandarguments);
	}

	public function loadArgumentsAdd($command_id = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		$commandarguments = [];
		$commandarguments = $this->Commandargument->find('all', [
			'recursive' => -1,
			'conditions' => [
				'Commandargument.command_id' => $command_id,
			]
		]);

		$this->set('commandarguments', $commandarguments);
		$this->render('load_arguments');
	}

	public function loadHosttemplatesArguments($hosttemplate_id = null){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		$this->loadModel('Hosttemplate');
		if(!$this->Hosttemplate->exists($hosttemplate_id)){
			throw new NotFoundException(__('Invalid hosttemplate'));
		}

		$this->loadModel('Commandargument');
		$this->loadModel('Hosttemplatecommandargumentvalue');
		$commandarguments = $this->Hosttemplatecommandargumentvalue->find('all', [
			//	'recursive' => -1,
			'conditions' => [
				'hosttemplate_id' => $hosttemplate_id
			]
		]);
		$commandarguments = Hash::remove($commandarguments, '{n}.Hosttemplatecommandargumentvalue.id');

		// Renaming Hosttemplatecommandargumentvalue to Hostcommandargumentvalue that we can render the view load_arguments with values
		$_commandarguments = [];
		foreach($commandarguments as $commandargument){
			$c = [];
			// Remove id of command argument value that if the user change them we dont overwrite the orginal data form host template in the database
			unset($commandargument['Hosttemplatecommandargumentvalue']['id']);
			$c['Hostcommandargumentvalue'] = $commandargument['Hosttemplatecommandargumentvalue'];
			$c['Commandargument'] = $commandargument['Commandargument'];
			$_commandarguments[] = $c;
		}
		$this->set('commandarguments', $_commandarguments);
		$this->render('load_arguments');
	}

	/*
	* Compare two arrays with each other
	* @param host Array
	* @param hosttemplate Array
	* @return $diff_array
	*
	* *************** Contact and contactgroups check ************
	debug(Set::classicExtract($host, '{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}'))); 	//Host
	debug(Set::classicExtract($hosttemplate, '{(Contact|Contactgroup)}.{n}.id'));					//Hosttemplate
	array(
		'Contact' => array(
			'Contact' => array(
				(int) 0 => '26'
			)
		),
		'Contactgroup' => array(
			'Contactgroup' => array(
				(int) 0 => '131',
				(int) 1 => '132'
			)
		)
	)
	*************** Single fields in hosttemplate and host *************
	debug(Set::classicExtract($host, 'Host.{('.implode('|', array_values(Hash::merge($fields,['name', 'description', 'address']))).')}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Hosttemplate.{('.implode('|', array_values($fields)).')}')));	//Hosttemplate

	**************** Command arguments check *************
	debug(Set::classicExtract($host, 'Hostcommandargumentvalue.{n}.{(commandargument_id|value)}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Hosttemplatecommandargumentvalue.{n}.{(commandargument_id|value)}')));	//Hostemplate

	**************** Custom variables check *************
	debug(Set::classicExtract($host, 'Customvariable.{n}.{(name|value)}'));	//Host
	debug(Set::classicExtract($hosttemplate, 'Customvariable.{n}.{(name|value)}'));	//Hosttemplate
	*/

	private function _diffWithTemplate($host, $hosttemplate){
		$diff_array = [];
		//Host-/Hosttemplate fields
		$fields = [
			'description',
			'command_id',
			'check_interval',
			'retry_interval',
			'max_check_attempts',
			'notification_interval',
			'notify_on_down',
			'notify_on_unreachable',
			'notify_on_recovery',
			'notify_on_flapping',
			'notify_on_downtime',
			'flap_detection_enabled',
			'flap_detection_on_up',
			'flap_detection_on_down',
			'flap_detection_on_unreachable',
			'notes',
			'priority',
			'check_period_id',
			'notify_period_id',
			'tags',
			'active_checks_enabled'
		];
		$compare_array = [
			'Host' => [
				['Host.{(' . implode('|', array_values(Hash::merge($fields, ['name', 'description', 'address', 'host_url', 'satellite_id', 'host_type']))) . ')}', false],
				['{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}', false],
				['Hostcommandargumentvalue.{n}.{(commandargument_id|value|id)}', false],
				['Customvariable.{n}.{(name|value)}', false]
			],
			'Hosttemplate' => [
				['Hosttemplate.{(' . implode('|', array_values($fields)) . ')}', false],
				['{(Contact|Contactgroup)}.{n}.id', true],
				['Hosttemplatecommandargumentvalue.{n}.{(commandargument_id|value)}', false],
				['Customvariable.{n}.{(name|value)}', false]
			]
		];
		$diff_array = [];
		foreach($compare_array['Host'] as $key => $data){
			$extractPath = $compare_array['Hosttemplate'][$key][0];
			if($data[0] == 'Hostcommandargumentvalue.{n}.{(commandargument_id|value|id)}'){
				if(isset($host['Hostcommandargumentvalue'])){
					if(!empty(Hash::diff(Set::classicExtract($host, $data[0]), Set::classicExtract($hosttemplate, $compare_array['Hosttemplate'][$key][0])))){
						$diff_data = Set::classicExtract($host, $data[0]);
						$diff_array['Hostcommandargumentvalue'] = $diff_data;
					}
				}
			}else{
				//$Key for DiffArray with preg_replace ==>  from 'Customvariable.{n}.{(name|value)}'' to 'Customvariable'
				$possible_key = preg_replace('/(\{.*\})|(\.)/', '', $data[0]);
				$diff_data = $this->Host->getDiffAsArray($this->Host->prepareForCompare(Set::classicExtract($host, $data[0]), $data[1]),
					$this->Host->prepareForCompare(Set::classicExtract($hosttemplate, $compare_array['Hosttemplate'][$key][0]),
						$compare_array['Hosttemplate'][$key][1]));
				if(!empty($diff_data)){
					$diff_array = Hash::merge($diff_array, (!empty($possible_key)) ? [$possible_key => $diff_data] : $diff_data);
				}
			}

		}
		return $diff_array;
	}

	//This function return the controller name
	protected function controller(){
		return 'HostsController';
	}

	public function getHostByAjax($id = null){
		if(!$this->Host->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}

		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		$host = $this->Host->findById($id);
		$this->set('host', $host);
		$this->set('_serialize', ['host']);
	}

	public function listToPdf(){
		$args = func_get_args();

		$conditions = [
			'Host.disabled' => 0,
			'Host.container_id' => $this->MY_RIGHTS,
		];

		if(is_array($args) && !empty($args)){
			if(end($args) == '.pdf' && (sizeof($args) > 1)){
				$host_ids = $args;
				end($host_ids);
				$last_key = key($host_ids);
				unset($host_ids[$last_key]);

				$_conditions = [
					'Host.id' => $host_ids,
				];
				$conditions = Hash::merge($conditions, $_conditions);
			}else{
				$host_ids = $args;

				$_conditions = [
					'Host.id' => $host_ids,
				];
				$conditions = Hash::merge($conditions, $_conditions);
			}
		}

		$hoststatus = $this->Objects->find('all', [
			'recursive' => -1,
			'conditions' => $conditions,
			'fields' => [
				'Host.name',
				'Hoststatus.current_state',
				'Hoststatus.last_check',
				'Hoststatus.is_flapping',
				'Hoststatus.next_check',
				'Hoststatus.last_state_change',
				'Hoststatus.problem_has_been_acknowledged',
				'Hoststatus.scheduled_downtime_depth',
			],
			'joins' => [
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Objects.name1 = Host.uuid AND Objects.objecttype_id = 1'
				],
				[
					'table' => 'nagios_hoststatus',
					'type' => 'INNER',
					'alias' => 'Hoststatus',
					'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
				],
			],
			'order' => [
				'Host.name ASC'
			]
		]);
//debug($hoststatus);
		$hostCount = count($hoststatus);

		$this->set(compact('hoststatus', 'hostCount'));
		$filename = 'Hosts_' . strtotime('now') . '.pdf';
		$binary_path = '/usr/bin/wkhtmltopdf';
		if(file_exists('/usr/local/bin/wkhtmltopdf')){
			$binary_path = '/usr/local/bin/wkhtmltopdf';
		}
		$this->pdfConfig = [
			'engine' => 'CakePdf.WkHtmlToPdf',
			'margin' => [
				'bottom' => 15,
				'left' => 0,
				'right' => 0,
				'top' => 15
			],
			'encoding' => 'UTF-8',
			'download' => true,
			'binary' => $binary_path,
			'orientation' => 'portrait',
			'filename' => $filename,
			'no-pdf-compression' => '*',
			'image-dpi' => '900',
			'background' => true,
			'no-background' => false,
		];
	}


	/*
	 * $host is from prepareForView() but ther are no names in the service contact, only ids
	 * $_host is from $this->Host->findById, because of contact names
	 */
	protected function __inheritContactsAndContactgroups($host, $_host = []){
		$diffExists = 0;
		if($host['Host']['own_contacts'] == 0 && $host['Host']['own_contactgroups'] == 0){
			$ContactsCombined = Hash::combine($host['Hosttemplate']['Contact'], '{n}.id', '{n}.id');
			$ContactgroupsCombined = Hash::combine($host['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.id');

			if(isset($this->request->data['Host']['Contact']) || isset($this->request->data['Host']['Contactgroup'])){
				if(isset($this->request->data['Host']['Contact']) && is_array($this->request->data['Host']['Contact'])){
					$diffExists += sizeof(
						array_merge(
							array_diff($this->request->data['Host']['Contact'], $ContactsCombined),
							array_diff($ContactsCombined, $this->request->data['Host']['Contact'])
						)
					);
				}
				if(isset($this->request->data['Host']['Contactgroup']) && is_array($this->request->data['Host']['Contactgroup'])){
					$diffExists += sizeof(
						array_merge(
							array_diff($this->request->data['Host']['Contactgroup'], $ContactgroupsCombined),
							array_diff($ContactgroupsCombined,$this->request->data['Host']['Contactgroup'])
						)
					);
				}
			}
			if($diffExists > 0){
				return [
					'inherit' => false,
					'source' => 'Host',
					'Contact' => $this->request->data['Host']['Contact'],
					'Contactgroup' => $this->request->data['Host']['Contactgroup']
				];

			}
			return [
				'inherit' => true,
				'source' => 'Hosttemplate',
				'Contact' => Hash::combine($host['Hosttemplate']['Contact'], '{n}.id', '{n}.name'),
				'Contactgroup' => Hash::combine($host['Hosttemplate']['Contactgroup'], '{n}.id', '{n}.Container.name')
			];
		}

		if(!empty($_host)){
			return [
				'inherit' => false,
				'source' => 'Host',
				'Contact' => Hash::combine($_host['Contact'], '{n}.id', '{n}.name'),
				'Contactgroup' => Hash::combine($_host['Contactgroup'], '{n}.id', '{n}.Container.name')
			];
		}

		$ContactsCombined = Hash::combine($host['Contact'], '{n}.id', '{n}.id');
		$ContactgroupsCombined = Hash::combine($host['Contactgroup'], '{n}.id', '{n}.id');

		if(isset($this->request->data['Host']['Contact']) || isset($this->request->data['Host']['Contactgroup'])){
			if(isset($this->request->data['Host']['Contact']) && is_array($this->request->data['Host']['Contact'])){
				$diffExists += sizeof(
					array_merge(
						array_diff($this->request->data['Host']['Contact'], $ContactsCombined),
						array_diff($ContactsCombined, $this->request->data['Host']['Contact'])
					)
				);
			}
			if(isset($this->request->data['Host']['Contactgroup']) && is_array($this->request->data['Host']['Contactgroup'])){
				$diffExists += sizeof(
					array_merge(
						array_diff($this->request->data['Host']['Contactgroup'], $ContactgroupsCombined),
						array_diff($ContactgroupsCombined,$this->request->data['Host']['Contactgroup'])
					)
				);
			}
		}
		if($diffExists > 0){
			return [
				'inherit' => false,
				'source' => 'Host',
				'Contact' => $this->request->data['Host']['Contact'],
				'Contactgroup' => $this->request->data['Host']['Contactgroup']
			];

		}

		return [
			'inherit' => false,
			'source' => 'Host',
			'Contact' => Hash::combine($host['Contact'], '{n}.id', '{n}.name'),
			'Contactgroup' => Hash::combine($host['Contactgroup'], '{n}.id', '{n}.Container.name')
		];
	}

	public function ping(){
		$this->allowOnlyAjaxRequests();
		$output = [];
		exec('ping ' . escapeshellarg($this->getNamedParameter('address', '')) . ' -c 4 -W 5', $output);

		$this->set('output', $output);
		$this->set('_serialize', array('output'));
	}

	/**
	 * Renders the ID of the host as JSON.
	 *
	 * 	Works if $this->request->data = array(
	 * 		'Host' => array(
	 *
	 */
	public function addParentHosts(){
		$this->allowOnlyPostRequests();
		$data = $this->request->data;

		// CakePHP save/validation necessity
		if(!isset($data['Host']) || !is_array($data['Host'])){
			$data['Host'] = [];
		}
		if(!isset($data['Parenthost']) || !is_array($data['Parenthost'])){
			$data['Parenthost'] = [];
		}
		if(isset($data['Host']['Parenthost'])){
			$data['Parenthost']['Parenthost'] = $data['Host']['Parenthost'];
		}
		if(isset($data['Parenthost']['Parenthost'])){
			$data['Host']['Parenthost'] = $data['Parenthost']['Parenthost'];
		}

		if($this->Host->save($data)){
			$this->serializeId();
		}else{
			$this->serializeErrorMessage();
		}
	}


	public function loadElementsByContainerId($container_id = null, $host_id = 0){
		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}

		if(!$this->Container->exists($container_id)){
			throw new NotFoundException(__('Invalid hosttemplate'));
		}

		$containerIds = $this->Tree->resolveChildrenOfContainerIds($container_id);

		$hosttemplates = $this->Hosttemplate->hosttemplatesByContainerId($containerIds, 'list');
		$hosttemplates = $this->Hosttemplate->chosenPlaceholder($hosttemplates);
		$hosttemplates = $this->Hosttemplate->makeItJavaScriptAble($hosttemplates);

		$hostgroups = $this->Host->makeItJavaScriptAble(
			$this->Hostgroup->hostgroupsByContainerId($containerIds, 'list', 'id')
		);

		$parenthosts = $this->Host->hostsByContainerId($containerIds, 'list');
		if($host_id != 0 && isset($parenthosts[$host_id])){
			unset($parenthosts[$host_id]);
		}
		$parenthosts = $this->Host->makeItJavaScriptAble($parenthosts);

		$timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
		$timeperiods = $this->Host->makeItJavaScriptAble($timeperiods);
		$checkperiods = $timeperiods;

		$contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
		$contacts = $this->Host->makeItJavaScriptAble($contacts);

		$contactgroups = $this->Contactgroup->contactgroupsByContainerId($containerIds, 'list');
		$contactgroups = $this->Host->makeItJavaScriptAble($contactgroups);

		$this->set(compact(['hosttemplates', 'hostgroups', 'parenthosts', 'timeperiods', 'checkperiods', 'contacts', 'contactgroups']));
		$this->set('_serialize', ['hosttemplates', 'hostgroups', 'parenthosts', 'timeperiods', 'checkperiods', 'contacts', 'contactgroups']);
	}

	//Acl
	public function checkcommand(){
		return null;
	}

	public function allocateServiceTemplateGroup($host_id = 0){

		//Form got submitted
		if(!empty($this->request->data)){
			debug($this->request->data('Servicetemplategroup.id'));
			debug($host_id);

			if(!$this->Servicetemplategroup->exists($this->request->data('Servicetemplategroup.id'))) {
				throw new NotFoundException(__('Invalid servicetemplategroup'));
			}

			$servicetemplateCache = [];
			//$this->loadModel('Host');
			if($this->request->is('post') || $this->request->is('put')){
				$userId = $this->Auth->user('id');
				//Checking if target host exists
				if($this->Host->exists($host_id)){
					$this->loadModel('Service');
					$this->loadModel('Servicetemplate');
					$host = $this->Host->findById($host_id);
					App::uses('UUID', 'Lib');
					foreach($this->request->data('Service.ServicesToAdd') as $servicetemplateIdToAdd){
						if(!isset($servicetemplateCache[$servicetemplateIdToAdd])){
							$servicetemplateCache[$servicetemplateIdToAdd] = $this->Servicetemplate->findById($servicetemplateIdToAdd);
						}
						$servicetemplate = $servicetemplateCache[$servicetemplateIdToAdd];
						$service = [];
						$service['Service']['uuid'] = UUID::v4();
						$service['Service']['host_id'] = $host_id;
						$service['Service']['servicetemplate_id'] = $servicetemplate['Servicetemplate']['id'];
						$service['Host'] = $host;

						$service['Service']['Contact'] = $servicetemplate['Contact'];
						$service['Service']['Contactgroup'] = $servicetemplate['Contactgroup'];

						$service['Contact']['Contact'] = [];
						$service['Contactgroup']['Contactgroup'] = [];
						$service['Servicegroup']['Servicegroup'] = [];

						$service['Contact']['Contact'] = $service['Contact'];
						$service['Contactgroup']['Contactgroup'] = $service['Contactgroup'];

						$data_to_save = $this->Service->prepareForSave([], $service, 'add');
						$this->Service->create();
						if($this->Service->saveAll($data_to_save)){
							$changelog_data = $this->Changelog->parseDataForChangelog(
								'add',
								'services',
								$this->Service->id,
								OBJECT_SERVICE,
								$host['Host']['container_id'], // use host container_id for user permissions
								$userId,
								$host['Host']['name'] . '/' . $servicetemplate['Servicetemplate']['name'],
								$service
							);
							if($changelog_data){
								CakeLog::write('log', serialize($changelog_data));
							}
						}
					}
					$this->setFlash(__('Services created successfully'));
					$this->redirect(['controller' => 'services', 'action' => 'serviceList', $host['Host']['id']]);
				}else{
					$this->setFlash(__('Target host does not exist'), false);
				}
			}
		}
		//=====
		$host = $this->Host->findById($host_id);
		$serviceTemplateGroups = $this->Servicetemplategroup->find('list',[
				'fields' => ['Servicetemplategroup.description']
			]);

		$this->set('back_url', $this->referer());
		$this->set(compact([
			'host',
			'serviceTemplateGroups',
		]));

	}

	public function getServiceTemplatesfromGroup($stg_id = 0){
		if(!$this->Servicetemplategroup->exists($stg_id)){
			throw new NotFoundException(__('Invalid Servicetemplategroup'));
		}

		if(!$this->request->is('ajax')){
			throw new MethodNotAllowedException();
		}
		$hostid = $_REQUEST['host_id'];
		$host = $this->Host->findById($hostid);
		$servicetemplategroup = $this->Servicetemplategroup->findById($stg_id);
		$this->set(compact(['servicetemplategroup','host']));
		$this->set('_serialize', ['servicetemplategroup','host']);
	}
}
