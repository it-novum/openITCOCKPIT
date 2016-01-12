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

class Devicegroup extends AppModel{
	var $belongsTo = [
		'Container' => [
			'className' => 'Container',
			'foreignKey' => 'container_id',
			'conditions' => ['containertype_id' => CT_DEVICEGROUP]
		]
	];
	
	public function __delete($devicegroup, $userId){
		if(is_numeric($devicegroup)){
			$devicegroupId = $devicegroup;
			$devicegroup = $this->findById($devicegroupId);
		}else{
			$devicegroupId = $devicegroup['Devicegroup']['id'];
		}
		
		$Container = ClassRegistry::init('Container');
		$Host = ClassRegistry::init('Host');
		$hosts = $Host->find('all', [
			'conditions' => [
				'Host.container_id' => $devicegroup['Container']['id']
			]
		]);
		if($this->__allowDelete($hosts)){
			if($Container->delete($devicegroup['Container']['id'])){
				//Delete all hosts that are inside of this container
				foreach($hosts as $host){
					$Host->__delete($host, $userId);
				}
				return true;
			}
			return false;
		}
		return false;
	}

	public function __allowDelete($hosts){
		//check if the host is used somwhere
		if(CakePlugin::loaded('EventcorrelationModule')){
			$notInUse = true;
			$result = [];
			$this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
			foreach ($hosts as $host) {
				$evcCount = $this->Eventcorrelation->find('count',[
					'conditions' => [
						'host_id' => $host['Host']['id']
					]
				]);
				$result[] = $evcCount;
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
