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

class Location extends AppModel{
	var $belongsTo = [
		'Container' => [
			'className' => 'Container',
			'foreignKey' => 'container_id',
			'conditions' => ['containertype_id' => CT_LOCATION]
		]
	];
	
	var $validate = [
		'latitude' => [
			'rule' => 'numeric',
			'message' => 'This value needs to be numeric',
			'allowEmpty' => true
		],
		'longitude' => [
			'rule' => 'numeric',
			'message' => 'This value needs to be numeric',
			'allowEmpty' => true
		]
	];
	
	public function __delete($location, $userId){
		if(is_numeric($location)){
			$locationId = $location;
			$location = $this->findById($location);
		}else{
			$locationId = $location['Location']['id'];
		}
		
		$Container = ClassRegistry::init('Container');
		$Devicegroup = ClassRegistry::init('Devicegroup');
		$Host = ClassRegistry::init('Host');
		//CakePHP will delete the device groups for us but we need to cleanup the hosts
		$devicegroups = $Devicegroup->find('all', [
			'conditions' => [
				'Container.parent_id' => $location['Container']['id']
			]
		]);

		$hostIds = [];
		foreach($devicegroups as $devicegroup){
			$hosts = $Host->find('all', [
				'conditions' => [
					'Host.container_id' => $devicegroup['Container']['id']
				]
			]);
			$hostIds[] = Hash::extract($hosts,'{n}.Host.id');
		}

		if($this->__allowDelete($hostIds)){
			foreach ($hostIds as $currentHostIds) {
				foreach($hosts as $host){
					$Host->__delete($host, $userId);
				}
			}
			if($Container->delete($location['Container']['id'])){
				return true;
			}
			return false;
		}
		return false;
	}

	public function __allowDelete($hostIds){
		//check if the hosts are used somwhere
		if(CakePlugin::loaded('EventcorrelationModule')){
			$notInUse = true;
			$result = [];
			$this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
			$Service = ClassRegistry::init('Service');
			foreach ($hostIds as $currentHostIds) {
				foreach ($currentHostIds as $hostId) {
					$serviceIds = Hash::extract($Service->find('all',[
						'recursive' => -1,
						'conditions' => [
							'host_id' => $hostId,
						],
						'fields' => [
							'Service.id'
						]
					]), '{n}.Service.id');
					$evcCount = $this->Eventcorrelation->find('count',[
						'conditions' => [
							'OR' => [
								'host_id' => $hostId,
								'service_id' => $serviceIds
							]
							
						]
					]);
					$result[] = $evcCount;
				}
			}

			foreach ($result as $value) {
				if($value > 0){
					$notInUse = false;
				}
			}
			return $notInUse;
		}
		return true;
	}
}
