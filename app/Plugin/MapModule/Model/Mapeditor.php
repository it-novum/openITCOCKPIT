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

	public function mapStatus($id){
		$Mapitem = ClassRegistry::init('Mapitem');
		$Mapline = ClassRegistry::init('Mapline');
		$Mapgadget = ClassRegistry::init('Mapgadget');
		$Host = ClassRegistry::init('Host');
		$Service = ClassRegistry::init('Service');
		$Objects = ClassRegistry::init(MONITORING_OBJECTS);

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

		$mapServices = Hash::extract($mapElements, '{s}.{n}.{s}[type=/service$/].object_id');
		$serviceUuids = $Service->find('list', [
			'recursive' => -1,
			'conditions' => [
				'Service.id' => $mapServices
			],	
			'fields' => [
				'Service.uuid'
			]
		]);

		$statusObjects['servicestatus'] = $Objects->find('all', [
			'conditions' => [
				'Objects.name2' => $serviceUuids
			]
		]);	

		$mapHosts = Hash::extract($mapElements, '{s}.{n}.{s}[type=/host$/].object_id');
		$hostUuids = $Host->find('list', [
			'recursive' => -1,
			'conditions' => [
				'Host.id' => $mapHosts
			],	
			'fields' => [
				'Host.uuid'
			]
		]);

		$statusObjects['hoststatus'] = $Objects->find('all', [
			'conditions' => [
				'AND' => [
					'Objects.objecttype_id' => 1,//2?
					'Objects.name1' => $hostUuids,
				]
			]
		]);

		$mapElements = Hash::filter($mapElements);

		debug($mapElements);
	}
}
