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

class ArchiveMigrationShell extends AppShell{
	
	public $uses = [
		'ArchiveMigrationModule.ArchiveObjects',
		'ArchiveMigrationModule.ArchiveStatehistory',
		MONITORING_OBJECTS,
		MONITORING_STATEHISTORY
	];
	
	public $cacheByObjectId = [];
	public $cacheByObjectName = [];
	
	public $simulation = true;
	public $mirateDisabledObjects = false;
	
	public function getOptionParser(){
		$parser = parent::getOptionParser();
		$parser->addOptions([
			'no-simulation' => ['help' => __d('oitc_console', 'Launch migration, don\'t simulate it')],
			'migrate-disabled-objects' => ['help' => __d('oitc_console', 'Try to migrate disabled or deleted objects')],
		]);
		return $parser;
	}
	
	public function main(){
		$this->stdout->styles('green', ['text' => 'green']);
		
		$this->parser = $this->getOptionParser();
		if(array_key_exists('no-simulation', $this->params)){
			$this->simulation = false;
		}
		
		if(array_key_exists('no-simulation', $this->params)){
			$this->mirateDisabledObjects = true;
		}
		
		
		
		$this->out('Build cacheByObjectId');
		$this->buildCacheByObjectId();
		
		$this->out('Build cacheByObjectName');
		$this->buildCacheByObjectName();
		
		$totalStatehistoryRecords = $this->ArchiveStatehistory->find('count');
		$limit = 50;
		$runs = ceil($totalStatehistoryRecords / $limit);
		
		$offset = 0;
		do{
			if($offset > $totalStatehistoryRecords){
				$offset = $totalStatehistoryRecords;
			}
			
			
			$statehistoryQuery = [
				'limit' => $limit,
				'offset' => $offset,
			];
			$results = $this->ArchiveStatehistory->find('all', $statehistoryQuery);
			
			//$this->cacheByObjectId;
			
			foreach($results as $statehistoryRecord){
				try{
					//get v2 object
					$object = $this->getObjectNameObjectId($statehistoryRecord['ArchiveStatehistory']['object_id']);
					
					//get v3 object
					try{
						if($object['hostname'] == 'localhost'){
						//	debug($object);
						}
					$v3Object = $this->getObjectIdByObjectName($object['hostname'], $object['servicename']);
					
					$data = [
						'Statehistory' => [
							'instance_id' => 1,
							'state_time' => $statehistoryRecord['ArchiveStatehistory']['state_time'],
							'state_time_usec' => strtotime($statehistoryRecord['ArchiveStatehistory']['state_time']),
							'object_id' => $v3Object['Objects']['object_id'],
							'state_change' => 1,
							'state' => $statehistoryRecord['ArchiveStatehistory']['state'],
							'state_type' => $statehistoryRecord['ArchiveStatehistory']['state_type'],
							'current_check_attempt' => $statehistoryRecord['ArchiveStatehistory']['current_check_attempt'],
							'max_check_attempts' => $statehistoryRecord['ArchiveStatehistory']['max_check_attempts'],
							'last_state' => $statehistoryRecord['ArchiveStatehistory']['last_state'],
							'last_hard_state' => $statehistoryRecord['ArchiveStatehistory']['last_hard_state'],
							'output' => $statehistoryRecord['ArchiveStatehistory']['output'],
							'long_output' => $statehistoryRecord['ArchiveStatehistory']['long_output'],
						]
					];
					
					if($this->simulation === false){
						$this->Statehistory->create();
						if($this->Statehistory->save($data)){
							if($object['servicename'] === null){
								$this->out('<green>Statehistory record migrated successfully for host: '.$object['hostname'].'</green>');
							}else{
								$this->out('<green>Statehistory record migrated successfully for service: '.$object['servicename'].' on '.$object['hostname'].'</green>');
							}
						}
					}else{
						if($object['servicename'] === null){
							$this->out('<green>Statehistory record of '.$object['hostname'].' is able to migrate</green>');
						}else{
							$this->out('<green>Statehistory record of '.$object['hostname'].' on '.$object['servicename'].' is able to migrate</green>');
						}
					}
					
					}catch(Exception $e){
						$this->out('<error>'.$e->getMessage().'</error>');
					}
					
					
				}catch(Exception $e){
					$this->out('<error>'.$e->getMessage().'</error>');
				}
			}
			
			
			$offset = $offset + $limit;
			$runs--;
			//return;
		}while($runs > 0);
		
		
	}
	
	public function buildCacheByObjectId(){
		$query = [
			'conditions' => [
				'objecttype_id < 3', //Only hosts and services
			],
			'order' => [
				'object_id' => 'ASC'
			],
		];
		
		if($this->mirateDisabledObjects === false){
			$query['conditions']['is_active'] = 1;
		}
		
		$objects = $this->ArchiveObjects->find('all', $query);
		
		
		foreach($objects as $object){
			//Hosts
			if($object['ArchiveObjects']['objecttype_id'] == 1){
				$this->cacheByObjectId[$object['ArchiveObjects']['object_id']] = [
					'hostname' => $object['ArchiveObjects']['name1'],
					'servicename' => null
				];
			}
			
			//Services
			if($object['ArchiveObjects']['objecttype_id'] == 2){
				$this->cacheByObjectId[$object['ArchiveObjects']['object_id']] = [
					'hostname' => $object['ArchiveObjects']['name1'],
					'servicename' => $object['ArchiveObjects']['name2']
				];
			}
		}
	}
	
	public function getObjectNameObjectId($objectId){
		if(isset($this->cacheByObjectId[$objectId])){
			return $this->cacheByObjectId[$objectId];
		}
		
		throw new NotFoundException('Object Id not found in Cache');
	}
	
	public function buildCacheByObjectName(){
		//Get V3 Objects
		$hostResult = $this->Objects->find('all', [
			'conditions' => [
				'Objects.objecttype_id' => 1
			],
			'order' => [
				'Objects.object_id' => 'ASC'
			],
			'contain' => [
				'Host',
			],
			'fields' => [
				'Objects.*',
				'Host.name',
				'Host.uuid',
			],
		]);
		
		$serviceResult = $this->Objects->find('all', [
			'conditions' => [
				'Objects.objecttype_id' => 2
			],
			'order' => [
				'Objects.object_id' => 'ASC'
			],
			'contain' => [
				'Service' => [
					'Servicetemplate' => [
						'fields' => [
							'Servicetemplate.id',
							'Servicetemplate.uuid',
							'Servicetemplate.name'
						]
					],
					'Host' => [
						'fields' => [
							'Host.id',
							'Host.uuid',
							'Host.name'
						]
					]
				]
			],
			'fields' => [
				'Objects.*',
				'Service.id',
				'Service.uuid',
				'Service.name',
				'Service.host_id',
				'Service.servicetemplate_id'
			],
		]);
		
		
		foreach($hostResult as $result){
			$this->cacheByObjectName['Hosts'][md5($result['Host']['name'])] = $result;
		}
		
		foreach($serviceResult as $result){
			$serviceName = $result['Service']['name'];
			if($serviceName === null || $serviceName === ''){
				$serviceName = $result['Service']['Servicetemplate']['name'];
			}
			
			$this->cacheByObjectName['Services'][md5($result['Service']['Host']['name']).md5($serviceName)] = $result;
			
		}
	}
	
	public function getObjectIdByObjectName($name1, $name2 = null){
		$needle = md5($name1);
		if($name2 !== null){
			$needle = md5($name1).md5($name2);
			
			if(isset($this->cacheByObjectName['Services'][$needle])){
				return $this->cacheByObjectName['Services'][$needle];
			}
			throw new NotFoundException('No object found for: '.$name1.'/'.$name2.' in V3 cache, service deleted?');
		}
		
		
		if(isset($this->cacheByObjectName['Hosts'][$needle])){
			return $this->cacheByObjectName['Hosts'][$needle];
		}
		throw new NotFoundException('No object found for: '.$name1.' in V3 cache, host deleted?');
	}
	
	public function _welcome(){
		$this->out('<info>Welcome to openITCOCKPIT archiv data migration shell</info>');
		$this->hr();
	}
}