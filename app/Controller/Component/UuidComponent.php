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

class UuidComponent extends Component {
	
	public $uuidCache = [];
	
	public function initialize(Controller $controller){
		$this->controller = $controller;
	}
	
	public function getCache(){
		return $this->uuidCache;
	}
	
	public function buildCache(){
		$Models = ['Host', 'Hosttemplate', 'Timeperiod', 'Command', 'Contact', 'Contactgroup', 'Hostgroup', 'Service', 'Servicetemplate'];
		$options = [
			'Host' => [
				'recursive' => -1,
				'fields' => ['id', 'uuid', 'name']
			],
			'Hosttemplate' => [
				'recursive' => -1,
				'fields' => ['id', 'uuid', 'name']
			],
			'Timeperiod' => [
				'recursive' => -1,
				'fields' => ['id', 'uuid', 'name']
			],
			'Command' => [
				'recursive' => -1,
				'fields' => ['id', 'uuid', 'name']
			],
			'Contact' => [
				'recursive' => -1,
				'fields' => ['id', 'uuid', 'name']
			],
			'Contactgroup' => [
				'recursive' => -1,
				'fields' => ['Contactgroup.id', 'Contactgroup.uuid', 'Contactgroup.container_id'],
				'contain' => [
					'Container' => [
						'fields' => ['Container.name']
					]
				]
			],
			'Hostgroup' => [
				'recursive' => -1,
				'fields' => ['Hostgroup.id', 'Hostgroup.uuid', 'Hostgroup.container_id'],
				'contain' => [
					'Container' => [
						'fields' => ['Container.name']
					]
				]
			],
			'Service' => [
				'recursive' => -1,
				'fields' => ['Service.id', 'Service.uuid', 'Service.name'],
				'contain' => [
					'Servicetemplate' => [
						'fields' => ['Servicetemplate.name']
					]
				]
			],
			'Servicetemplate' => [
				'recursive' => -1,
				'fields' => ['Servicetemplate.id', 'Servicetemplate.uuid', 'Servicetemplate.name'],
			]
		];
		
		foreach($Models as $ModelName){
			if(!in_array($ModelName, $this->controller->uses)){
				$this->controller->loadModel($ModelName);
			}
			
			foreach($this->controller->{$ModelName}->find('all', $options[$ModelName]) as $result){
				$tmp_result = [];
				if(isset($result[$ModelName]['id'])){
					$tmp_result['id'] = $result[$ModelName]['id'];
				}
				
				if(isset($result[$ModelName]['name'])){
					$tmp_result['name'] = $result[$ModelName]['name'];
				}else{
					//in php isset() returns false, if a variable is null
					// $a = null, isset($a) will return false!!!
					if($ModelName == 'Service'){
						if($result['Service']['name'] == null || $result['Service']['name'] == ''){
							$tmp_result['name'] = $result['Servicetemplate']['name'];
						}
					}
				}
				
				if(isset($result[$ModelName]['description'])){
					$tmp_result['description'] = $result[$ModelName]['description'];
				}
				
				if(isset($result[$ModelName]['container_id'])){
					$tmp_result['container_id'] = $result[$ModelName]['container_id'];
				}
				
				if(isset($result['Container']['name'])){
					$tmp_result['container_name'] = $result['Container']['name'];
				}
				
				
				$tmp_result['ModelName'] = $ModelName;
				
				if(!isset($result[$ModelName]['uuid'])){
					debug($result[$ModelName]);
				}
				
				$this->uuidCache[$result[$ModelName]['uuid']] = $tmp_result;
				unset($tmp_result);
			}
		}
	}
}
