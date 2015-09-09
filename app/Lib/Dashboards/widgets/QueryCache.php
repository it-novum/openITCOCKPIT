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

namespace Dashboard\Widget;
class QueryCache{
	
	protected $_cache = [];
	
	//Requeres CackePHP Controller
	public function __construct(\Controller $controller){
		$this->Controller = $controller;
	}
	
	public function isCached($functionName){
		if(isset($this->_cache[$functionName])){
			return true;
		}
		return false;
	}
	
	public function getCache($functionName){
		return $this->_cache[$functionName];
	}
	
	public function setCache($functionName, $data){
		$this->_cache[$functionName] = $data;
	}
	
	public function hostStateCount(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		
		$conditions = [
			'Host.disabled' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		
		$fields = [
			'Host.id',
			'Host.uuid',

			'Hoststatus.current_state',
		];
		$query = $this->_hostBaseQuery($fields, $conditions);
		$hosts = $this->Controller->Host->find('all', $query);
		
		$hostStateArray = [
			'state' => [
				0 => 0,
				1 => 0,
				2 => 0
			],
			'total' => 0
		];
		foreach($hosts as $host){
			$hostStateArray['state'][$host['Hoststatus']['current_state']]++;
			$hostStateArray['total']++;
		}
		$this->setCache(__FUNCTION__, $hostStateArray);
		return $hostStateArray;
	}
	
	public function serviceStateCount(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		
		$conditions = [
			'Host.disabled' => 0,
			'Service.disabled' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		$query = [
			'recursive' => -1,
			'conditions' => [
				$conditions,
			],
			'contain' => [],
			'fields' => [
				'Service.id',
				'Service.uuid',
				'Servicestatus.current_state'
			],
			'joins' => [[
				'table' => 'hosts',
				'type' => 'INNER',
				'alias' => 'Host',
				'conditions' => 'Service.host_id = Host.id'
			], [
				'table' => 'nagios_objects',
				'type' => 'INNER',
				'alias' => 'HostObject',
				'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
			], [
				'table' => 'nagios_hoststatus',
				'type' => 'INNER',
				'alias' => 'Hoststatus',
				'conditions' => 'Hoststatus.host_object_id = HostObject.object_id'
			], [
				'table' => 'nagios_objects',
				'type' => 'INNER',
				'alias' => 'ServiceObject',
				'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2'
			], [
				'table' => 'nagios_servicestatus',
				'type' => 'LEFT OUTER',
				'alias' => 'Servicestatus',
				'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id'
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
				'Service.id'
			]
		];
		
		$services = $this->Controller->Service->find('all', $query);
		
		$serviceStateArray = [
			'state' => [
				0 => 0,
				1 => 0,
				2 => 0,
				3 => 0,
			],
			'total' => 0
		];
		foreach($services as $service){
			$serviceStateArray['state'][$service['Servicestatus']['current_state']]++;
			$serviceStateArray['total']++;
		}
		$this->setCache(__FUNCTION__, $serviceStateArray);
		return $serviceStateArray;
	}
	
	public function parentOutages(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		
		$conditions = [
			'Hoststatus.current_state >' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		$result = $this->Controller->Parenthost->find('all', [
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'Objects',
					'conditions' => 'Objects.object_id = Parenthost.parent_host_object_id',
				],
				[
					'table' => 'nagios_hoststatus',
					'type' => 'INNER',
					'alias' => 'Hoststatus',
					'conditions' => 'Hoststatus.host_object_id = Parenthost.parent_host_object_id',
				],
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Host.uuid = Objects.name1',
				],
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				],
			],
			'fields' => [
				'Parenthost.parent_host_object_id',
				'Hoststatus.current_state',
				'Hoststatus.output',
				'Objects.name1',
				'Host.name',
				'Host.id',
			],
			'conditions' => [
				$conditions
			],
			'group' => ['Host.uuid'],
		]);
		$this->setCache(__FUNCTION__, $result);
		return $result;
	}
	
	public function hostDowntimes(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		$conditions = [
			'Hoststatus.scheduled_downtime_depth >' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		
		$fields = [
			'Hoststatus.host_object_id',
			'Objects.name1',
			'Host.name',
			'Host.id',
			'Hoststatus.scheduled_downtime_depth',
		];
		
		$query = [
			'recursive' => -1,
			'fields' => [
				'Hoststatus.host_object_id',
				'Objects.name1',
				'Host.name',
				'Host.id',
				'Hoststatus.scheduled_downtime_depth',
			],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'Objects',
					'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
				],
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Host.uuid = Objects.name1',
				],
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				],
			],
			'conditions' => [
				$conditions,
			],
		];
		$result = $this->Controller->Hoststatus->find('all', $query);
		$this->setCache(__FUNCTION__, $result);
		return $result;
	}
	
	public function serviceDowntimes(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		$conditions = [
			'Servicestatus.scheduled_downtime_depth >' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		$query = [
			'conditions' => $conditions,
			'contain' => ['Servicetemplate'],
			'fields' => [
				'Service.id',
				'Service.name',
				'Servicestatus.scheduled_downtime_depth',
				'Servicetemplate.name',
				'Host.id',
				'Host.name',
				'HostsToContainers.container_id'
			],
			'order' => ['Host.name' => 'asc'],
			'joins' => [
				[
					'table' => 'hosts',
					'type' => 'INNER',
					'alias' => 'Host',
					'conditions' => 'Service.host_id = Host.id'
				],
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'ServiceObject',
					'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2'
				],
				[
					'table' => 'nagios_servicestatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Servicestatus',
					'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id'
				],
				[
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				]
			],
			'group' => [
				'Service.id'
			]
		];
		$result = $this->Controller->Service->find('all', $query);
		$this->setCache(__FUNCTION__, $result);
		return $result;
	}
	
	public function hostStateCount180(){
		if($this->isCached(__FUNCTION__)){
			return $this->getCache(__FUNCTION__);
		}
		
		$conditions = [
			'Host.disabled' => 0,
		];
		if($this->Controller->hasRootPrivileges === false){
			$conditions = \Hash::merge($conditions, ['HostsToContainers.container_id' => $this->Controller->MY_RIGHTS]);
		}
		
		$fields = [
			'Host.id',
			'Host.uuid',

			'Hoststatus.scheduled_downtime_depth',
			'Hoststatus.current_state',
			'Hoststatus.problem_has_been_acknowledged',
		];
		$query = $this->_hostBaseQuery($fields, $conditions);
		
		$hosts = $this->Controller->Host->find('all', $query);
		
		$hostStateArray = [
			'state' => [
				0 => 0,
				1 => 0,
				2 => 0
			],
			'acknowledged' => [
				0 => 0,
				1 => 0,
				2 => 0,
			],
			'in_downtime' => [
				0 => 0,
				1 => 0,
				2 => 0,
			],
			'not_handled' => [
				0 => 0,
				1 => 0,
				2 => 0,
			],
			'total' => 0
		];
		foreach($hosts as $host){
			$hostStateArray['state'][$host['Hoststatus']['current_state']]++;
			if($host['Hoststatus']['problem_has_been_acknowledged'] > 0 || $host['Hoststatus']['scheduled_downtime_depth'] > 0){
				if($host['Hoststatus']['problem_has_been_acknowledged'] > 0){
					$hostStateArray['acknowledged'][$host['Hoststatus']['current_state']]++;
				}
				if($host['Hoststatus']['scheduled_downtime_depth'] > 0){
					$hostStateArray['in_downtime'][$host['Hoststatus']['current_state']]++;
				}
			}else{
				if($host['Hoststatus']['current_state'] > 0){
					$hostStateArray['not_handled'][$host['Hoststatus']['current_state']]++;
				}
			}
			$hostStateArray['total']++;
		}
		$this->setCache(__FUNCTION__, $hostStateArray);
		return $hostStateArray;
	}
	
	private function _hostBaseQuery($fields = [], $conditions = []){
		return [
			'recursive' => -1,
			'contain' => [],
			'fields' => $fields,
			'conditions' => $conditions,
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
			]
		];
	}
}
