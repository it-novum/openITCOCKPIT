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

class Acknowledged extends NagiosModuleAppModel{
	//public $useDbConfig = 'nagios';
	const ACKNOWLEDGE_HOST_PROBLEM = 33;
	const ACKNOWLEDGE_SVC_PROBLEM = 34;

	public $useTable = 'acknowledgements';
	public $primaryKey = 'acknowledgement_id';
	public $tablePrefix = 'nagios_';
	public $belongsTo = [
		'Objects' => [
			'className' => 'NagiosModule.Objects',
			'foreignKey' => 'object_id',
		]
	];
	
	public function byUuid($uuid = null){
		$return = [];
		if($uuid !== null){
			$acknowledged = $this->find('all', [
				'conditions' => [
					'Objects.name2' => $uuid,
					'Objects.objecttype_id' => 2
				],
				'order' => [
					'Acknowledged.entry_time' => 'DESC'
				]
			]);
			
			return $acknowledged;
			
		}
			
			
		/*	
			if(!empty($acknowledged)){
				foreach($acknowledged as $ack){
					$ack[$ack['Objects']['name2']] = $ack;
				}
			}
		} */
		return $return; 
	}
	
	public function byHostUuid($uuid = null){
		$return = [];
		if($uuid !== null){
			$acknowledged = $this->find('first', [
				'conditions' => [
					'Objects.name1' => $uuid,
					'Objects.objecttype_id' => 1
				],
				'order' => [
					'Acknowledged.entry_time' => 'DESC'
				]
			]);

			return $acknowledged;
			
		}
		
		return $return; 
	}
	
	public function listSettingsService($cakeRequest, $serviceUuid){
		$requestData = $cakeRequest->data;
		
		if(isset($cakeRequest->params['named']['Listsettings'])){
			$requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
		}
		
		$requestParams = $cakeRequest->params;

		$service_state_types = [
			'recovery' => 0,
			'warning' => 1,
			'critical' => 2,
			'unknown' => 3,
		];
		
		$return = [
			'conditions' => [
				'Objects.name2' => $serviceUuid,
				'Objects.objecttype_id' => 2
			],
			'paginator' => [
				'limit' => 30,
				'order' => ['Acknowledged.entry_time' => 'DESC']
			],
			'Listsettings' => [
				'limit' => 30
			]
		];

		if(isset($requestData['Listsettings']['state_types'])){
			$return['conditions']['Acknowledged.state'] = [];
			foreach($requestData['Listsettings']['state_types'] as $state_type => $value){
				if(isset($service_state_types[$state_type]) && $value == 1){
					$return['conditions']['Acknowledged.state'][] = $service_state_types[$state_type];
					$return['Listsettings']['state_types'][$state_type] = 1;
				}
			}
		}else{
			foreach($service_state_types as $state_type => $state){
				$return['Listsettings']['state_types'][$state_type] = 1;
			}
			if(isset($return['conditions']['Acknowledged.state'])){
				unset($return['conditions']['Acknowledged.state']);
			}
		}
		
		if(isset($requestParams['named']['sort']) && isset($requestParams['named']['direction'])){
			$return['paginator']['order'] = [$requestParams['named']['sort'] => $requestParams['named']['direction']];
		}
		

		if(isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])){
			$return['paginator']['limit'] = $requestData['Listsettings']['limit'];
			$return['Listsettings']['limit'] = $return['paginator']['limit'];
		}
		
		if(isset($requestData['Listsettings']['from'])){
			$time = strtotime($requestData['Listsettings']['from']);
			if($time == false || !is_numeric($time)){
				$time = strtotime('3 days ago');
			}
			
			$return['conditions']['Acknowledged.entry_time >'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['from'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Acknowledged.entry_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
			$return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
		}
		
		if(isset($requestData['Listsettings']['to'])){
			$time = strtotime($requestData['Listsettings']['to']);
			if($time == false || !is_numeric($time)){
				$time = time() + (60 * 5); //Add 5 minutes to avoid missing entires in result
			}
			
			$return['conditions']['Acknowledged.entry_time <'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['to'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Acknowledged.entry_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
		}
		
		if(isset($return['conditions']['Acknowledged.state'])){
			if(
				in_array(0, $return['conditions']['Acknowledged.state']) &&
				in_array(1, $return['conditions']['Acknowledged.state']) &&
				in_array(2, $return['conditions']['Acknowledged.state']) &&
				in_array(3, $return['conditions']['Acknowledged.state'])
			){
				//The user want every state, so lets remove this for faster SQL
				unset($return['conditions']['Acknowledged.state']);
			}
		}

		
		return $return;
	}
	
	public function listSettingsHost($cakeRequest, $hostUuid){
		$requestData = $cakeRequest->data;
		
		if(isset($cakeRequest->params['named']['Listsettings'])){
			$requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
		}
		
		$requestParams = $cakeRequest->params;

		$host_state_types = [
			'up' => 0,
			'down' => 1,
			'unreachable' => 2,
		];
		
		$return = [
			'conditions' => [
				'Objects.name1' => $hostUuid,
				'Objects.objecttype_id' => 1
			],
			'paginator' => [
				'limit' => 30,
				'order' => ['Acknowledged.entry_time' => 'DESC']
			],
			'Listsettings' => [
				'limit' => 30
			]
		];

		if(isset($requestData['Listsettings']['state_types'])){
			$return['conditions']['Acknowledged.state'] = [];
			foreach($requestData['Listsettings']['state_types'] as $state_type => $value){
				if(isset($host_state_types[$state_type]) && $value == 1){
					$return['conditions']['Acknowledged.state'][] = $host_state_types[$state_type];
					$return['Listsettings']['state_types'][$state_type] = 1;
				}
			}
		}else{
			foreach($host_state_types as $state_type => $state){
				$return['Listsettings']['state_types'][$state_type] = 1;
			}
			if(isset($return['conditions']['Acknowledged.state'])){
				unset($return['conditions']['Acknowledged.state']);
			}
		}
		
		if(isset($requestParams['named']['sort']) && isset($requestParams['named']['direction'])){
			$return['paginator']['order'] = [$requestParams['named']['sort'] => $requestParams['named']['direction']];
		}
		

		if(isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])){
			$return['paginator']['limit'] = $requestData['Listsettings']['limit'];
			$return['Listsettings']['limit'] = $return['paginator']['limit'];
		}
		
		if(isset($requestData['Listsettings']['from'])){
			$time = strtotime($requestData['Listsettings']['from']);
			if($time == false || !is_numeric($time)){
				$time = strtotime('3 days ago');
			}
			
			$return['conditions']['Acknowledged.entry_time >'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['from'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Acknowledged.entry_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
			$return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
		}
		
		if(isset($requestData['Listsettings']['to'])){
			$time = strtotime($requestData['Listsettings']['to']);
			if($time == false || !is_numeric($time)){
				$time = time() + (60 * 5); //Add 5 minutes to avoid missing entires in result
			}
			
			$return['conditions']['Acknowledged.entry_time <'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['to'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Acknowledged.entry_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
		}
		
		if(isset($return['conditions']['Acknowledged.state'])){
			if(
				in_array(0, $return['conditions']['Acknowledged.state']) &&
				in_array(1, $return['conditions']['Acknowledged.state']) &&
				in_array(2, $return['conditions']['Acknowledged.state']) &&
				in_array(3, $return['conditions']['Acknowledged.state'])
			){
				//The user want every state, so lets remove this for faster SQL
				unset($return['conditions']['Acknowledged.state']);
			}
		}

		
		return $return;
	}
}
