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

class Systemdowntime extends AppModel{
	

	var $validate = [
		'downtimetype' => [
			'rule' => ['checkDowntimeSettings'],
			'message' => 'An error occurred',
		],
		'from_date' => [
			'notEmpty' => [
				'rule'=> 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'date' => [
				'rule' => ['date', 'dmy'],
				'message' => 'Please enter a valid date'
			]
		],
		'from_time' => [
			'notEmpty' => [
				'rule'=> 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'time' => [
				'rule' => 'time',
				'message' => 'Please enter a valid time'
			]
		],
		'to_date' => [
			'notEmpty' => [
				'rule'=> 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'date' => [
				'rule' => ['date', 'dmy'],
				'message' => 'Please enter a valid date'
			]
		],
		'to_time' => [
			'notEmpty' => [
				'rule'=> 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'time' => [
				'rule' => 'time',
				'message' => 'Please enter a valid time'
			]
		],
		'comment' => [
			'notEmpty' => [
				'rule'=> 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true
			]
		],
	];
	
	public function checkDowntimeSettings(){
		if(isset($this->data['Systemdowntime']['downtimetype'])){
			//switch($this->data['Systemdowntime']['downtimetype']){
			//	case 'host':
			//	case 'hostgroup':
					if(!is_numeric($this->data['Systemdowntime']['object_id']) || $this->data['Systemdowntime']['object_id'] == '' || $this->data['Systemdowntime']['object_id'] == null){
						return false;
					}
					
					if($this->data['Systemdowntime']['is_recurring'] == 1){
						return $this->validateRecurring($this->data);
					}
					return true;
			//		break;
			//}
		}
		
		return false;
	}
	
	public function validateRecurring($request){
		//Validate days from selectbox
		if($request['Systemdowntime']['weekdays'] != ''){
			$valideDays = [1, 2, 3, 4 , 5 ,6, 7];
			$_expldoe = explode(',', $request['Systemdowntime']['weekdays']);
			foreach($_expldoe as $day){
				if($day !== '' && $day !== null && !in_array($day, $valideDays)){
					return false;
				}
			}
		}
		
		if($request['Systemdowntime']['day_of_month'] != ''){
			$valideDays = [1, 2, 3, 4 , 5 ,6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
			$_expldoe = explode(',', $request['Systemdowntime']['day_of_month']);
			foreach($_expldoe as $day){
				if($day !== '' && $day !== null && !in_array($day, $valideDays)){
					return false;
				}
			}
		}
		
		
		
		return true;
	}
	
	public function listSettings($cakeRequest){
		$requestData = $cakeRequest->data;
		
		if(isset($cakeRequest->params['named']['Listsettings'])){
			$requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
		}
		$requestParams = $cakeRequest->params;
		
		$return = [
			'paginator' => [
				'limit' => 30,
			],
			'Listsettings' => [
				'limit' => 30,
			],
			'conditions' => [],
			'default'=> [
				'recursive' => -1,
				'fields' => ['Systemdowntime.*', 'Host.*', 'Hostgroup.*', 'Service.*', 'Servicetemplate.*', 'Container.*'],
				'joins' =>  [
					[
						'table' => 'hosts',
						'type'	=> 'LEFT',
						'alias'	=> 'Host',
						'conditions' => '(Systemdowntime.objecttype_id = '.OBJECT_HOST.' AND Host.id = Systemdowntime.object_id)',
					],
			
					[
						'table' => 'services',
						'type'	=> 'LEFT',
						'alias'	=> 'Service',
						'conditions' => '(Systemdowntime.objecttype_id = '.OBJECT_SERVICE.' AND Service.id = Systemdowntime.object_id)',
					],
					
					[
						'table' => 'servicetemplates',
						'type'	=> 'LEFT OUTER',
						'alias'	=> 'Servicetemplate',
						'conditions' => 'Servicetemplate.id = Service.servicetemplate_id',
					],
					
					[
						'table' => 'hostgroups',
						'type'	=> 'LEFT',
						'alias'	=> 'Hostgroup',
						'conditions' => '(Systemdowntime.objecttype_id = '.OBJECT_HOSTGROUP.' AND Hostgroup.id = Systemdowntime.object_id)',
					],
					
					[
						'table' => 'containers',
						'type'	=> 'LEFT OUTER',
						'alias'	=> 'Container',
						'conditions' => 'Container.id = Hostgroup.container_id',
					],
				
				],
				'findType' => 'all'
			]
		];

		
		if(isset($requestParams['named']['sort']) && isset($requestParams['named']['direction'])){
			$return['paginator']['order'] = [$requestParams['named']['sort'] => $requestParams['named']['direction']];
		}
		

		if(isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])){
			$return['paginator']['limit'] = $requestData['Listsettings']['limit'];
			$return['Listsettings']['limit'] = $return['paginator']['limit'];
		}
		

		return $return;
	}
}