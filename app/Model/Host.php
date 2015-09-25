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

App::uses('ValidationCollection', 'Lib');


/**
 * @property ParentHost $ParentHost
 */
class Host extends AppModel{
	public $hasAndBelongsToMany = [
		'Container' => [
			'className' => 'Container',
			'joinTable' => 'hosts_to_containers',
			'foreignKey' => 'host_id',
			'associationForeignKey' => 'container_id',
		],
		'Contactgroup' => [
			'className' => 'Contactgroup',
			'joinTable' => 'contactgroups_to_hosts',
			'foreignKey' => 'host_id',
			'associationForeignKey' => 'contactgroup_id',
			'unique' => true,
			'dependent' => true,
		],
		'Contact' => [
			'className' => 'Contact',
			'joinTable' => 'contacts_to_hosts',
			'foreignKey' => 'host_id',
			'associationForeignKey' => 'contact_id',
			'unique' => true,
			'dependent' => true,
		],
		'Parenthost' => [
			'className' => 'Host',
			'joinTable' => 'hosts_to_parenthosts',
			'foreignKey' => 'host_id',
			'associationForeignKey' => 'parenthost_id',
			'unique' => true,
			'dependent' => true,
		],
		'Hostgroup' => [
			'className' => 'Hostgroup',
			'joinTable' => 'hosts_to_hostgroups',
			'foreignKey' => 'host_id',
			'associationForeignKey' => 'hostgroup_id',
		],
	];

	public $hasMany = [
		'Hostcommandargumentvalue',
		'HostescalationHostMembership' => [
			'className' => 'HostescalationHostMembership',
			'foreignKey' => 'host_id',
			'dependent' => true,
		],
		'HostdependencyHostMembership' => [
			'className' => 'HostdependencyHostMembership',
			'foreignKey' => 'host_id',
			'dependent' => true,
		],
		'Service' => [
			'className' => 'Service',
			'foreignKey' => 'host_id',
			'dependent' => true,
		],
		'Customvariable' => [
			'className' => 'Customvariable',
			'foreignKey' => 'object_id',
			'conditions' => [
				'objecttype_id' => OBJECT_HOST,
			],
			'dependent' => true,
		]
	];

	public $belongsTo = [
		'Hosttemplate',
		'Container' => [
			'className' => 'Container',
			'foreignKey' => 'container_id'
		],
		'CheckPeriod' => [
			'className' => 'Timeperiod',
			'foreignKey' => 'check_period_id',
		],
		'NotifyPeriod' => [
			'className' => 'Timeperiod',
			'foreignKey' => 'notify_period_id',
		],
		'CheckCommand' => [
			'className' => 'Command',
			'foreignKey' => 'command_id',
		],
	];

	public $validate = [
		'name' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true,
			],
			/*'isUnique' => [
				'rule' => 'isUnique',
				'message' => 'This host name has already been taken.'
			],*/
		],
		'container_id' => [
			'notEmpty' => [
				'rule'    => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs to be numeric.'
			],
			'notZero' => [
				'rule'    => ['comparison', '>', 0],
				'message' => 'Invalid container.',
				'required' => true,
			],
		],
		'hosttemplate_id' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true,
			],
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs to be numeric.'
			],
			'notZero' => [
				'rule'    => ['comparison', '>', 0],
				'message' => 'Invalid host template.',
				'required' => true,
			],
		],
		'address' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true,
			]
		],
		/*
		'Contact' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one contact or contact group.',
				'required' => true
			]
		],
		'Contactgroup' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one contact or contact group',
				'required' => true
			]
		],
		*/
		'command_id' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs to be numeric.',
				'allowEmpty' => true,
				'required' => false,
			],
			'notZero' => [
				'rule' => ['comparison', '>', 0],
				'message' => 'This field cannot be left blank.',
				'allowEmpty' => true,
				'required' => false,
			],
		],
		'max_check_attempts' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'allowEmpty' => true,
				'required' => false,
			],
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs to be numeric.',
				'allowEmpty' => true,
				'required' => false,
			],

		],
		/*
				'priority' => [
					'notEmpty' => [
						'rule'    => 'notEmpty',
						'message' => 'This field cannot be left blank.',
						'required' => true
					],
					'range' => [
						'rule' => ['range', 0, 6],
						'message' => 'This value must be between 1 and 5'
					],
					'numeric' => [
						'rule' => 'numeric',
						'message' => 'This value needs to be numeric'
					]
				],
		*/
	];

	/**
	 * Returns an array with hosts, the user is allowd to see by container_id
	 *
	 * @param    array  $containerIds Container IDs of container ids the user is allowd to see
	 * @param    string $type          cake's find types
	 * @return array
	 */
	public function hostsByContainerId($containerIds = [], $type = 'all', $conditions = [], $index = 'id', $useLevel = false){
		if(!is_array($containerIds)){
			$containerIds = [$containerIds];
		}
		$containerIds = array_unique($containerIds);

		if($useLevel === false){
			$_conditions = [
				'HostsToContainers.container_id' => $containerIds,
				'Host.disabled' => 0
			];
		}else{
			$_conditions = [
				'HostsToContainers.container_id' => $containerIds,
				'Host.disabled' => 0
			];
		}

		$conditions = Hash::merge($_conditions, $conditions);

		if($index == 'id'){
			return $this->find($type, [
				'recursive' => -1,
				'joins' => array(
					array('table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => array(
						'HostsToContainers.host_id = Host.id',
						)
					)
				),
				'conditions' => $conditions,
				'order' => [
					'Host.name' => 'ASC'
				]
			]);
		}

		$hosts = $this->find('all', [
			'recursive' => -1,
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
			'conditions' => $conditions,
			'order' => [
				'Host.name' => 'ASC'
			],
			'fields' => [
				'Host.name',
				'Host.'.$index
			]
		]);

		$result = [];
		foreach($hosts as $host){
			$result[$host['Host'][$index]] = $host['Host']['name'];
		}
		return $result;
	}

	/**
	 * same as $this->hostsByContainerId but remove the host with id given in $id
	 *
	 * @param array     $container_ids
	 * @param    string $type cake's find types
	 * @param    int    $id   of a host you want to remove from result
	 * @return array
	 */
	public function hostsByContainerIdExcludeHostId($container_ids = [], $type = 'all', $id){
		return $this->find($type, [
			'recursive' => -1,
			'joins' => [
				['table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				]
			],
			'conditions' => [
				'HostsToContainers.container_id' => $container_ids,
				'Host.disabled' => 0,
				'NOT' => ['Host.id' => $id]
			],
			'order' => [
				'Host.name' => 'ASC'
			]
		]);
	}

	public function getDiffAsArray($host_values = [], $hosttemplate_values = []){
		$host_values = ($host_values === null) ? [] : $host_values;
		$hosttemplate_values = ($hosttemplate_values === null) ? [] : $hosttemplate_values;
		return Hash::diff($host_values, $hosttemplate_values);
	}

	public function prepareForCompare($prepare_array = [], $prepare = false){
		//if prepare_for_compare => false, nothing to do $prepare_array[0] => 'Template.{n}, $prepare_array[1] => true/false'
		if(!$prepare){
			return $prepare_array;
		}
		$new_array = [];
		if(is_array($prepare_array)){
			foreach($prepare_array as $key => $data){
				$new_array[$key][$key] = $data;
			}
		}
		return $new_array;
	}

	public function prepareForSave($diff_array = [], $requestData = [], $save_mode = 'add'){
		//Check differences for notification settings
		if(!empty(Set::classicExtract($diff_array, 'Host.{(notify_on_).*}'))){
			//Overwrite all notification settings if at least one option has been changed
			$diff_array = Hash::merge($diff_array, ['Host' => Set::classicExtract($requestData, 'Host.{(notify_on_).*}')]);
		}
		//Check differences for flap detection settings
		if(!empty(Set::classicExtract($diff_array, 'Host.{(flap_detection_on_).*}'))){
			//Overwrite all flap detection settings if at least one option has been changed

			$diff_array = Hash::merge($diff_array, ['Host' => Set::classicExtract($requestData, 'Host.{(flap_detection_on_).*}')]);
		}
		//Set default for contact/contactgroup settings
		$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0', 'own_contactgroups' => '0', 'own_customvariables' => '0']]);
		if($save_mode === 'edit'){
			$tmp_keys = array_diff_key($requestData['Host'], $diff_array['Host']);
		}

		//Because of nagios 4 inheritance
		//See https://github.com/naemon/naemon-core/pull/92
		$breakInherit = false;
		if(isset($diff_array['Contact']) && empty($diff_array['Contactgroup']['Contactgroup'])){
			if(empty($requestData['Contact']['Contact'])){
				$diff_array['Contact']['Contact'] = [];
			}else{
				$diff_array['Contact']['Contact'] = $requestData['Contact']['Contact'];
			}
			if(empty($requestData['Contactgroup']['Contactgroup'])){
				$diff_array['Contactgroup']['Contactgroup'] = [];
			}else{
				$diff_array['Contactgroup']['Contactgroup'] = $requestData['Contactgroup']['Contactgroup'];
			}
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
			$breakInherit = true;
		}

		//Because of nagios 4 inheritance
		//See https://github.com/naemon/naemon-core/pull/92
		if(empty($diff_array['Contact']['Contact']) && isset($diff_array['Contactgroup'])){
			$diff_array['Contact']['Contact'] = empty($requestData['Contact']['Contact']) ? [] : $requestData['Contact']['Contact'];
			$diff_array['Contactgroup']['Contactgroup'] = empty($requestData['Contactgroup']['Contactgroup']) ? [] : $requestData['Contactgroup']['Contactgroup'];
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
			$breakInherit = true;
		}

		//Because of nagios 4 inheritance
		//See https://github.com/naemon/naemon-core/pull/92
		if(empty($diff_array['Contact']['Contact']) && empty($diff_array['Contactgroup']['Contactgroup'])){
			$diff_array['Contact']['Contact'] = [];
			$diff_array['Contactgroup']['Contactgroup'] = [];
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '0']]);
			$breakInherit = true;
		}
		//Because of nagios 4 inheritance
		//See https://github.com/naemon/naemon-core/pull/92

		/*
		if(isset($diff_array['Contact']['Contact']) || ((isset($diff_array['Contact']['Contact']) && $diff_array['Contact']['Contact'] == null)) && !isset($diff_array['Contactgroup']['Contactgroup'])){
			$diff_array['Contact']['Contact'] = ($request_data['Contact']['Contact'] == '')?[]:$request_data['Contact']['Contact'];
			$diff_array['Contactgroup']['Contactgroup'] = ($request_data['Contactgroup']['Contactgroup'] == '')?[]:$request_data['Contactgroup']['Contactgroup'];
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
			$breakInherit = true;
		}

		//Because of nagios 4 inheritance
		if(isset($diff_array['Contactgroup']['Contactgroup']) || ((isset($diff_array['Contactgroup']['Contactgroup']) && $diff_array['Contactgroup']['Contactgroup'] == null)) && !isset($diff_array['Contact']['Contact'])){
		//if(!isset($diff_array['Contact']['Contact']) && (isset($diff_array['Contactgroup']['Contactgroup']) || $diff_array['Contactgroup']['Contactgroup'] == null)){
			$diff_array['Contact']['Contact'] = ($request_data['Contact']['Contact'] == '')?[]:$request_data['Contact']['Contact'];
			$diff_array['Contactgroup']['Contactgroup'] = ($request_data['Contactgroup']['Contactgroup'] == '')?[]:$request_data['Contactgroup']['Contactgroup'];
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '1']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '1']]);
			$breakInherit = true;
		}
		//debug($breakInherit);
		//debug($request_data);debug($diff_array);die('test');
		*/
		if(!$breakInherit){
			//Check differences for contacts and contactgroups
			foreach(Set::classicExtract($diff_array, '{(Contact|Contactgroup)}.{(Contact|Contactgroup)}.{n}') as $key => $value){
				//overwrite default setting for: own_contact/own_contactgroups => 1 if contact/contactgroup array exists
				$diff_array = Hash::merge($diff_array, ['Host' => ['own_' . strtolower(Inflector::pluralize($key)) => '1']]);
				if($diff_array[$key][$key] === null){
					//Remove empty contacts or contactgroups from array
					$diff_array[$key][$key] = [];
					//$diff_array = Hash::remove($diff_array, $key);
				}
			}
		}
		if($save_mode === 'edit'){
			$diff_array = Hash::merge($diff_array, ['Host' => array_fill_keys(array_keys($tmp_keys), null)]);
		}
		//Check differences for custom variables
		if(!empty(Set::classicExtract($diff_array, 'Customvariable.{n}'))){
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_customvariables' => '1']]);
			$diff_array = Hash::merge($diff_array, ['Customvariable' => Set::classicExtract($requestData, 'Customvariable.{n}')]);
		}

		$hostTemplateId = 0;
		if(isset($requestData['Host']['hosttemplate_id'])){
			$hostTemplateId = $requestData['Host']['hosttemplate_id'];
		}

		$containerId = 0;
		if(isset($requestData['Host']['container_id'])){
			$containerId = $requestData['Host']['container_id'];
		}

		if(empty($requestData['Host']['Contactgroup'])){
			$requestData['Host']['Contactgroup'] = [];
		}
		if(empty($requestData['Host']['Contact'])){
			$requestData['Host']['Contactgroup'] = [];
		}

		$diff_array = Hash::merge($diff_array, [
			'Host' => [
				'hosttemplate_id' => $hostTemplateId,
				'container_id' => $containerId,
				/* Set Contact/Contactgroup for custom validation rule*/
				'Contact' => $requestData['Host']['Contact'],
				'Contactgroup' => $requestData['Host']['Contactgroup'],
				'Parenthost' => $requestData['Parenthost']['Parenthost'],
				'Hostgroup' => $requestData['Hostgroup']['Hostgroup']
			],
			'Container' => [
				'container_id' => $containerId,
			],
			'Parenthost' => [
				'Parenthost' => $requestData['Parenthost']['Parenthost']
			],
			'Hostgroup' => [
				'Hostgroup' => $requestData['Hostgroup']['Hostgroup']
			]
		]);
		if(empty($diff_array['Hostcommandargumentvalue'])){
			$diff_array = Hash::merge($diff_array, [
					'Hostcommandargumentvalue' => [],
				]
			);
		}
		if($save_mode === 'add'){
			$diff_array = Hash::merge($diff_array, [
				'Host' => [
					'uuid' => UUID::v4()
				]
			]);
		}elseif($save_mode === 'edit'){
			$diff_array = Hash::merge($diff_array, [
				'Host' => [
					'id' => $requestData['Host']['id']
				]
			]);
		}
		if(empty($requestData['Hostcommandargumentvalue'])){
			$diff_array = Hash::remove($diff_array, 'Hostcommandargumentvalue');
		}

		//Because of nagios 4 inheritance
		//See https://github.com/naemon/naemon-core/pull/92
		if(empty($diff_array['Host']['Contact']) && empty($diff_array['Host']['Contactgroup'])){
			$diff_array['Contact']['Contact'] = [];
			$diff_array['Contactgroup']['Contactgroup'] = [];
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contacts' => '0']]);
			$diff_array = Hash::merge($diff_array, ['Host' => ['own_contactgroups' => '0']]);
		}
		return $diff_array;
	}

	/*
	Custom validation rule for contact and/or contactgroup fields
	*/
	public function atLeastOne($data){
		return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
	}

	public function prepareForView($id = null){
		if(!$this->exists($id)){
			throw new NotFoundException(__('Invalid host'));
		}
		$host = $this->find('all', [
			'conditions' => [
				'Host.id' => $id
			],
			'contain' => [
				'CheckPeriod',
				'NotifyPeriod',
				'CheckCommand',
				'Hosttemplate' => [
					'Contact' => [
						'fields' => [
							'id', 'name'
						]
					],
					'Contactgroup' => [
						'fields' => ['id'],
						'Container' => [
							'fields' => [
								'name'
							]
						]
					],
					'CheckCommand',
					'CheckPeriod',
					'NotifyPeriod',
					'Customvariable' => [
						'fields' => [
							'id', 'name', 'value'
						]
					],
					'Hosttemplatecommandargumentvalue' => [
						'fields' => [
							'commandargument_id', 'value'
						],
						'Commandargument' => [
							'fields' => ['human_name']
						]
					]
				],
				'Contact' => [
					'fields' => [
						'id', 'name'
					]
				],
				'Contactgroup' => [
					'fields' => ['id'],
					'Container' => [
						'fields' => [
							'name'
						]
					]
				],
				'Customvariable' => [
					'fields' => [
						'id', 'name', 'value'
					]
				],
				'Hostcommandargumentvalue' => [
					'fields' => [
						'id', 'commandargument_id', 'value'
					],
					'Commandargument' => [
						'fields' => [
							'id', 'human_name'
						]
					]
				],
				'Parenthost' => [
					'fields' => [
						'id', 'name'
					]
				],
				'Hostgroup' => [
					'fields' => [
						'id'
					],
					'Container' => [
						'fields' => ['name']
					]
				]
			],
			'recursive' => -1
		]);
		$host = $host[0];
		if(empty($host['Host']['hosttemplate_id']) || $host['Host']['hosttemplate_id'] == 0){
			return $host;
		}
		$host = [
			'Host' => Hash::merge(Hash::filter($host['Host'], ['Host', 'filterNullValues']), Set::classicExtract($host['Hosttemplate'], '{(' . implode('|', array_keys(Hash::diff($host['Host'], Hash::filter($host['Host'], ['Host', 'filterNullValues'])))) . ')}')),
			'Contact' => Hash::extract((($host['Host']['own_contacts']) ? $host['Contact'] : $host['Hosttemplate']['Contact']), '{n}.id'),
			'Contactgroup' => Hash::extract((($host['Host']['own_contactgroups']) ? $host['Contactgroup'] : $host['Hosttemplate']['Contactgroup']), '{n}.id'),
			'Parenthost' => Hash::extract($host['Parenthost'], '{n}.id'),
			'Customvariable' => ($host['Host']['own_customvariables']) ? $host['Customvariable'] : $host['Hosttemplate']['Customvariable'],
			'Hostcommandargumentvalue' => (!empty($host['Hostcommandargumentvalue'])) ? $host['Hostcommandargumentvalue'] : $host['Hosttemplate']['Hosttemplatecommandargumentvalue'],
			'Hosttemplate' => $host['Hosttemplate'],
			'Hostgroup' => Hash::combine($host['Hostgroup'], '{n}.id', '{n}.id'),
			'CheckCommand' => (!is_null($host['Host']['command_id'])) ? $host['CheckCommand'] : $host['Hosttemplate']['CheckCommand'],
			'CheckPeriod' => (!is_null($host['Host']['check_period_id'])) ? $host['CheckPeriod'] : $host['Hosttemplate']['CheckPeriod'],
			'NotifyPeriod' => (!is_null($host['Host']['notify_period_id'])) ? $host['NotifyPeriod'] : $host['Hosttemplate']['NotifyPeriod'],
		];
		return $host;
	}

	/**
	 * Callback function for filtering.
	 *
	 * @param array $var Array to filter.
	 * @return boolean
	 */
	public static function filterNullValues($var){
		if($var != null || $var === '0' || $var === '' || $var === []){
			return true;
		}
		return false;
	}

	public function hostHasServiceByServicetemplateId($host_id, $servicetemplateId = null){
		if($this->exists($host_id)){
			$host = $this->find('first', [
				'recursive' => -1,
				'conditions' => ['Host.id' => $host_id],
				'contain' => [
					'Service' => [
						'Servicetemplate' => [
							'fields' => ['id', 'name', 'uuid'],
						],
					]
				],
			]);

			foreach($host['Service'] as $service){
				if(isset($service['Servicetemplate']['id'])){
					if($service['Servicetemplate']['id'] == $servicetemplateId){
						return true;
					}
				}
			}
		}
		return false;
	}

	public function redirect($params = [], $default = []){
		$redirect = [];

		if(isset($params['named']['_controller'])){
			$redirect['controller'] = $params['named']['_controller'];
		}

		if(isset($params['named']['_action'])){
			$redirect['action'] = $params['named']['_action'];
		}

		if(!empty($default)){
			$redirect = Hash::merge($default, $redirect);
		}

		return $redirect;
	}

	/**
	 * Delete old records (custom variables) from database
	 *
	 * ### Options
	 *
	 * - See cake-api beforeSave function
	 *
	 * @param    array $options with the options
	 * @return    true
	 */
	public function beforeSave($options = []){
		if(isset($this->data['Customvariable']) && isset($this->data['Host']['id'])){
			$customvariablesToDelete = $this->Customvariable->find('all', [
				'conditions' => [
					'Customvariable.object_id' => $this->data['Host']['id'],
					'Customvariable.objecttype_id' => OBJECT_HOST,
					'NOT' => [
						'Customvariable.id' => Hash::extract($this->data['Customvariable'], '{n}.id')
					]
				]
			]);
			//Delete all custom variables that are remove by the user:
			foreach($customvariablesToDelete as $customvariableToDelete){
				$this->Customvariable->delete($customvariableToDelete['Customvariable']['id']);
			}
		}
		return true;
	}


	public function beforeValidate($options = array()){
		$params = Router::getParams();
		if(empty($params['action'])){
			return parent::beforeValidate($options);
		}
		$action = $params['action'];

		if($action == 'addParentHosts'){
			$this->validate = [
				'id' => ValidationCollection::getIdRule(),
				'Parenthost' => [
					'multiple' => [
						'rule' => ['multiple', ['min' => 1]],
						'message' => 'You need to select at least one parent host.',
						'required' => true,
					],
				]
			];
		}

		return parent::beforeValidate($options);
	}

	/**
	 * @param int[]  $containerIds May be empty if the option `hasRootPrivileges` is true.
	 * @param string $type
	 * @param array  $options
	 * @return int[]
	 */
	public function servicesByContainerIds($containerIds, $type = 'all', $options = []){
		$_options = [
			'prefixHostname' => true,
			'delimiter' => '/',
			'forOptiongroup' => false,
			'hasRootPrivileges' => false,
		];
		$options = Hash::merge($_options, $options);

		if(!is_array($containerIds)){
			$containerIds = [$containerIds];
		}

		switch($type){
			case 'all':
				$results = $this->find('all', [
					'contain' => [
						'Service' => [
							'conditions' => [
								'Service.disabled' => 0,
							],
							'Servicetemplate' => [
								'fields' => [
									'Servicetemplate.id',
									'Servicetemplate.name',
								],
							],
						],
					],
					'fields' => [
						'Host.id',
						'Host.name',
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
					'conditions' => [
						'HostsToContainers.container_id' => $containerIds,
						'Host.disabled' => 0
					]
				]);

				$return = [];
				foreach($results as $result){
					foreach($result['Service'] as $service){
						$service['hostname'] = $result['Host']['name'];

						if(empty($service['name'])){
							$service['name'] = $service['Servicetemplate']['name'];
						}

						unset($service['Servicetemplate']);
						$return[] = $service;
					}
				}

				return $return;
				break;

			case 'list':
				$results = $this->find('all', [
					'contain' => [
						'Service' => [
							'conditions' => [
								'Service.disabled' => 0
							],
							'fields' => [
								'Service.id',
								'Service.uuid',
								'Service.name',
								'Service.servicetemplate_id'
							],
							'Servicetemplate' => [
								'fields' => [
									'Servicetemplate.id',
									'Servicetemplate.name'
								]
							]
						]
					],
					'fields' => [
						'Host.id',
						'Host.name',
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
					'conditions' => [
						'HostsToContainers.container_id' => $containerIds,
						'Host.disabled' => 0
					]
				]);

				$return = [];
				foreach($results as $result){
					foreach($result['Service'] as $service){
						if(empty($service['name'])){
							$service['name'] = $service['Servicetemplate']['name'];
						}
						if($options['forOptiongroup'] === false){
							if($options['prefixHostname']){
								$return[$service['id']] = $result['Host']['name'] . $options['delimiter'] . $service['name'];
							}else{
								$return[$service['id']] = $service['name'];
							}
						}else{
							$hostId = $result['Host']['id'];
							$hostName = $result['Host']['name'];
							$serviceName = $service['name'];

							if($options['prefixHostname']){
								$return[$hostId][$hostName][$service['id']] = $hostName . $options['delimiter'] . $serviceName;
							}else{
								$return[$hostId][$hostName][$service['id']] = $serviceName;
							}
						}

					}
				}
				return $return;
				break;
		}

		return [];
	}

	public function __delete($host, $userId){
		if(empty($host)){
			return false;
		}

		$id = $host['Host']['id'];
		$this->id = $id;
		$Changelog = ClassRegistry::init('Changelog');

		//Load the Service Model to delete Graphgenerator configurations
		$Service = ClassRegistry::init('Service');
		$serviceIds = array_keys($Service->find('list', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'Service.host_id' => $id
			]
		]));

		$GraphgenTmplConf = ClassRegistry::init('GraphgenTmplConf');
		$graphgenTmplConfs = $GraphgenTmplConf->find('all', [
			'conditions' => [
				'GraphgenTmplConf.service_id' => $serviceIds
			]
		]);
		if($this->delete()){
			//Delete was successfully - delete Graphgenerator configurations
			foreach($graphgenTmplConfs as $graphgenTmplConf){
				$GraphgenTmplConf->delete($graphgenTmplConf['GraphgenTmplConf']['id']);
			}

			$changelog_data = $Changelog->parseDataForChangelog(
				'delete',
				'hosts',
				$id,
				OBJECT_HOST,
				$host['Host']['container_id'],
				$userId,
				$host['Host']['name'],
				$host
			);
			if($changelog_data){
				CakeLog::write('log', serialize($changelog_data));
			}


			//Add host to deleted objects table
			$DeletedHost = ClassRegistry::init('DeletedHost');
			$DeletedService = ClassRegistry::init('DeletedService');
			$DeletedHost->create();
			$data = [
				'DeletedHost' => [
					'host_id' => $host['Host']['id'],
					'uuid' => $host['Host']['uuid'],
					'hosttemplate_id' => $host['Host']['hosttemplate_id'],
					'name' => $host['Host']['name'],
					'description' => $host['Host']['description'],
					'deleted_perfdata' => 0,
				]
			];
			if($DeletedHost->save($data)){
				// The host is history now, so we can delete all deleted services of this host, we dont need this data anymore
				$DeletedService->deleteAll([
					'DeletedService.host_id' => $id
				]);
			}


			/*
			 * Check if the host was part of an hostgroup, hostescalation or hostdependency
			 * If yes, cake delete the records by it self, but may be we have an empty hostescalation or hostgroup now.
			 * Nagios don't relay like this so we need to check this and delete the hostescalation/hostgroup or host dependency if empty
			 */
			$this->_cleanupHostEscalationDependency($host);

			$Documentation = ClassRegistry::init('Documentation');
			//Delete the Documentation of the Host
			$documentation = $Documentation->findByUuid($host['Host']['uuid']);
			if(isset($documentation['Documentation']['id'])){
				$Documentation->delete($documentation['Documentation']['id']);
				unset($documentation);
			}
			return true;
		}
		return false;
	}

	/**
	 * Check if the host is part of a hostescalation and if it would be empty after the host would be deleted,
	 * This prevents nagios from getting problems because of empty hostescalations.
	 * @param array $host
	 */
	public function _cleanupHostEscalationDependency($host){
		if(!empty($host['HostescalationHostMembership'])){
			$Hostescalation = ClassRegistry::init('Hostescalation');
			foreach($host['HostescalationHostMembership'] as $_hostescalation){
				$hostescalation = $Hostescalation->findById($_hostescalation['hostescalation_id']);
				if(empty($hostescalation['HostescalationHostMembership']) && empty($hostescalation['HostescalationHostgroupMembership'])){
					//This eslacation is empty now, so we can delete it
					$Hostescalation->delete($hostescalation['Hostescalation']['id']);
				}
			}
		}

		if(!empty($host['HostdependencyHostMembership'])){
			$Hostdependency = ClassRegistry::init('Hostdependency');
			foreach($host['HostdependencyHostMembership'] as $_hostdependency){
				$hostdependency = $Hostdependency->findById($_hostdependency['hostdependency_id']);
				if(empty($hostdependency['HostdependencyHostMembership']) && empty($hostdependency['HostdependencyHostgroupMembership'])){
					$Hostdependency->delete($hostdependency['Hostdependency']['id']);
				}else{
					//Not the whole dependency is empty, but may be its broken
					$hosts = Hash::extract($hostdependency['HostdependencyHostMembership'], '{n}[dependent=0]');
					$dependentHosts = Hash::extract($hostdependency['HostdependencyHostMembership'], '{n}[dependent=1]');
					if(empty($hosts) || empty($dependentHosts)){
						//Data is not valid, delete!
						$Hostdependency->delete($hostdependency['Hostdependency']['id']);
					}
				}
			}
		}

		if(!empty($host['Hostgroup'])){
			$Hostgroup = ClassRegistry::init('Hostgroup');
			$Container = ClassRegistry::init('Container');
			foreach($host['Hostgroup'] as $_hostgroup){
				$hostgroup = $Hostgroup->findById($_hostgroup['id']);
				if(empty($hostgroup['Host'])){
					//Hostgroup is empty and can be deleted
					//$this->Hostgroup->delete($hostgroup['Hostgroup']['id']);
					if(isset($hostgroup['Container']['id'])){
						$Container->delete($hostgroup['Container']['id'], true);
					}
				}
			}
		}
	}
}
