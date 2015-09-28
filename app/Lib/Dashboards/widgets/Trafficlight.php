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
class Trafficlight extends Widget{
	public $isDefault = false;
	public $icon = 'fa-road';
	public $element = 'trafficlight';
	public $width = 4;
	public $height = 16;
	
	public function __construct(\Controller $controller, $QueryCache){
		parent::__construct($controller, $QueryCache);
		$this->typeId = 11;
		$this->title = __('Traffic light');
	}
	
	public function setData($widgetData){
		//Prefix every widget variable with $widgetFoo
		$widgetServicesForTrafficlight = $this->QueryCache->trafficLightServices();
		$service = [];
		if($widgetData['Widget']['service_id'] !== null){
			if($this->Controller->Service->exists($widgetData['Widget']['service_id'])){
				$query = [
					'recursive' => -1,
					'conditions' => [
						'Service.id' => $widgetData['Widget']['service_id'],
						'HostsToContainers.container_id' => $this->Controller->MY_RIGHTS,
						'Service.disabled' => 0
					],
					'contain' => [],
					'fields' => [
						'Host.id',
						'Service.id',
						'Service.uuid',
						'Servicestatus.current_state',
						'Servicestatus.is_flapping',
						'Servicestatus.normal_check_interval',
					],
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
							'conditions' => 'Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2'
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
						],
					]
				];
		
				$service = $this->Controller->Service->find('first', $query);
			}
		}
		
		$this->Controller->viewVars['widgetTrafficlights'][$widgetData['Widget']['id']] = [
			'Service' => $service,
			'Widget' => $widgetData,
		];
		$this->Controller->set('widgetServicesForTrafficlight', $widgetServicesForTrafficlight);
	}
	
	public function refresh($widget){
		$this->setData($widget);
		return [
			'element' => 'Dashboard'.DS.$this->element
		];
	}
	
}
