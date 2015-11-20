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

class Mapeditor extends MapModuleAppModel{
	public $useTable = false;

	public function prepareForSave($request){
		$filtered = [];
		foreach($request as $key => $mapObject){
			if($key !== 'Map'){
				if($key === 'Maptext'){
					$filtered[$key] = array_filter($mapObject,
						function($el){
							return !empty(trim($el['text']));
						}
					);
				}else if($key === 'Mapline'){
					$filtered[$key] = array_filter($mapObject,
						function($el){
							return (isset($el['type']));
						}
					);
				}else{
					$filtered[$key] = array_filter($mapObject,
						function($el){
							return (isset($el['type'], $el['object_id']) && $el['object_id'] > 0);
						}
					);
				}
			}
		}

		$filtered =  Hash::insert(
			Hash::filter($filtered),
			'{s}.{s}.map_id', $request['Map']['id']
		);
		$filtered = array_merge(['Map' => $request['Map']], $filtered);
		return $filtered;
	}

	/**
	 * return states of all elements from a specific map 
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  $id the Id of the map
	 * @return Array the map elements
	 */
	public function mapStatus($id){
		$Mapitem = ClassRegistry::init('Mapitem');
		$Mapline = ClassRegistry::init('Mapline');
		$Mapgadget = ClassRegistry::init('Mapgadget');
		$Host = ClassRegistry::init('Host');
		$Service = ClassRegistry::init('Service');
		$Servicegroup = ClassRegistry::init('Servicegroup');
		$Hostgroup = ClassRegistry::init('Hostgroup');
		$this->Objects = ClassRegistry::init(MONITORING_OBJECTS);

		$mapElements = [];
		$statusObjects = [];
		$mapElements['items'] = $Mapitem->find('all',[
			'recursive' => -1,
			'conditions' => [
				'map_id' => $id
			],
			'fields' => [
				'Mapitem.type',
				'Mapitem.object_id'
			]
		]);
		$mapElements['lines'] = $Mapline->find('all',[
			'recursive' => -1,
			'conditions' => [
				'map_id' => $id
			],
			'fields' => [
				'Mapline.type',
				'Mapline.object_id'
			]
		]);

		$mapElements['gadgets'] = $Mapgadget->find('all',[
			'recursive' => -1,
			'conditions' => [
				'map_id' => $id
			],
			'fields' => [
				'Mapgadget.type',
				'Mapgadget.object_id'
			]
		]);

		//get the service ids
		$mapServices = Hash::extract($mapElements, '{s}.{n}.{s}[type=/service$/].object_id');
		//resolve the serviceids into uuids
		$serviceUuids = $Service->find('list', [
			'recursive' => -1,
			'conditions' => [
				'Service.id' => $mapServices
			],
			'fields' => [
				'Service.uuid'
			]
		]);
		//get the servicestatus
		$statusObjects['servicestatus'] = $this->_servicestatus(['Objects.name2' => $serviceUuids]);

		//get the host ids
		$mapHosts = Hash::extract($mapElements, '{s}.{n}.{s}[type=/host$/].object_id');
		//resolve the hostids into uuids
		$hostUuids = $Host->find('list', [
			'recursive' => -1,
			'conditions' => [
				'Host.id' => $mapHosts
			],
			'fields' => [
				'Host.uuid'
			],
		]);
		//get the hoststatus
		$statusObjects['hoststatus'] = [
			$this->_hoststatus(['Objects.name1' => $hostUuids])
		];
		//get the servicestatus for every host
		foreach($statusObjects['hoststatus'][0] as $key => $hoststatusObject){
			$statusObjects['hoststatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name1' => $hoststatusObject['Objects']['name1']]);

		}

		//get the servicegroup ids
		$mapServicegroups = Hash::extract($mapElements, '{s}.{n}.{s}[type=/servicegroup$/].object_id');

		$ServicegroupServiceUuids = $Servicegroup->find('all',[
			'recursive' => -1,
			'conditions' => [
				'Servicegroup.id' => $mapServicegroups
			],
			'contain' => [
				'Service.uuid'
			]
		]);

		$ServicegroupServiceUuids = Hash::extract($ServicegroupServiceUuids, '{n}.Service.{n}.uuid');
		foreach ($ServicegroupServiceUuids as $key => $serviceuuid) {
			$statusObjects['servicegroupstatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name2' => $serviceuuid]);
		}
		
		//get the hostgroup ids
		$mapHostgroups = Hash::extract($mapElements, '{s}.{n}.{s}[type=/hostgroup$/].object_id');

		$HostgroupHostUuids = $Hostgroup->find('all',[
			//'recursive' => -1,
			'conditions' => [
				'Hostgroup.id' => $mapHostgroups
			],
			'contain' => [
				'Host.uuid'
			]
		]);

		$HostgroupHostUuids = Hash::extract($HostgroupHostUuids, '{n}.Host.{n}.uuid');
		$statusObjects['hostgroupstatus'] = [
			$this->_hoststatus(['Objects.name1' => $HostgroupHostUuids])
		];

		foreach ($statusObjects['hostgroupstatus'][0] as $key => $hoststatusObject) {
			$statusObjects['hostgroupstatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name1' => $hoststatusObject['Objects']['name1']]);
		}
		//$mapElements = Hash::filter($mapElements);

		return $statusObjects;
	}
	/**
	 * return the Hoststatus for the given array of conditions
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $conditions
	 * @param  Array $fields
	 * @return Array Hoststatus array
	 */
	protected function _hoststatus($conditions, $fields = null){
		$_conditions = ['Objects.objecttype_id' => 1];
		$conditions = Hash::merge($conditions, $_conditions);

		$_fields = ['Hoststatus.current_state','Objects.name1'];
		if(!empty($fields)){
			$fields = Hash::merge($fields, $_fields);
		}else{
			$fields = $_fields;
		}

		$hoststatus = $this->Objects->find('all', [
			'conditions' => $conditions,
			'fields' => $fields,
			'joins' => [
				[
					'table' => 'nagios_hoststatus',
					'type' => 'LEFT',
					'alias' => 'Hoststatus',
					'conditions' => 'Objects.object_id = Hoststatus.host_object_id'
				]
			],
		]);
		return $hoststatus;
	}

	/**
	 * return the servicestatus for the given array of conditions
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $conditions
	 * @param  Array $fields 
	 * @param  Bool  $getServiceInfo set to true if you also want to get the service and servicetemplate data
	 * @return Array Servicestatus array
	 */
	protected function _servicestatus($conditions, $fields = null, $getServiceInfo = false, $type = 'all'){
		$_conditions = ['Objects.objecttype_id' => 2];
		$conditions = Hash::merge($conditions, $_conditions);

		$_fields = ['Servicestatus.current_state','Objects.name1'];
		if(!empty($fields)){
			$fields = Hash::merge($fields, $_fields);
		}else{
			$fields = $_fields;
		}

		if($getServiceInfo){
			$joins = [
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
						'Host.uuid = Objects.name1',
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
					'type' => 'LEFT',
					'alias' => 'Servicestatus',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				]
			];
		}else{
			$joins = [
				[
					'table' => 'nagios_servicestatus',
					'type' => 'LEFT',
					'alias' => 'Servicestatus',
					'conditions' => 'Objects.object_id = Servicestatus.service_object_id'
				]
			];
		}
		$servicestatus = $this->Objects->find($type, [
			'recursive' => -1,
			'conditions' => $conditions,
			'fields' => $fields,
			'joins' => $joins,
		]);
		return $servicestatus;
	}

	/**
	 * get hoststatus by uuid
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Mixed $uuid   String or array of uuids
	 * @param  Array $fields fields which should be returned
	 * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on success
	 */
	public function getHoststatusByUuid($uuid = null, $fields = null){
		if(empty($uuid)){
			return false;
		}
		$this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
		$conditions = [
			'Host.uuid' => $uuid
		];
		return $this->_hoststatus($conditions, $fields);
	}

	/**
	 * get servicestatus by HOST uuid
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Mixed $uuid   String or Array of Uuids
	 * @param  Array $fields fields which should be returned
	 * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on success
	 */
	public function getServicestatusByHostUuid($uuid = null, $fields = null){
		if(empty($uuid)){
			return false;
		}
		$this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
		$conditions = [
			'Objects.name1' => $uuid
		];
		return $this->_servicestatus($conditions, $fields, true);
	}

	/**
	 * get servcestatus by uuid
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Mixed $uuid   String or Array of Uuids
	 * @param  Array $fields fields which should be returned
	 * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on success
	 */
	public function getServicestatusByUuid($uuid = null, $fields = null){
		if(empty($uuid)){
			return false;
		}
		$this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
		$conditions = [
			'Objects.name2' => $uuid
		];
		return $this->_servicestatus($conditions, $fields, true);
	}

	public function getServicegroupstatusByUuid($uuid = null, $fields = null){
		if(empty($uuid)){
			return false;
		}
		$this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
		$this->Servicegroup = ClassRegistry::init('Servicegroup');
		$servicegroupstatus = [];
		$servicegroup = $this->Servicegroup->find('all',[
			'recursive' => -1,
			'conditions' => [
				'uuid' => $uuid,
			],
			'contain' => [
				'Container' => [
					'fields' => [
						'Container.name'
					]
				],
				'Service' => [
					'fields' => [
						'Service.uuid'
					],
				],
			],
		]);
		$servicegroupstatus = $servicegroup;
		$currentServicegroupServiceUuids = Hash::extract($servicegroup, '{n}.Service.{n}.uuid');

		foreach ($currentServicegroupServiceUuids as $key => $serviceUuid) {
			$conditions = [
				'Objects.name2' => $serviceUuid,
			];
			$servicegroupstatus[0]['Servicegroup']['Servicestatus'][$key] = $this->_servicestatus($conditions, $fields, true, 'first');
		}
		return $servicegroupstatus;
	}
}