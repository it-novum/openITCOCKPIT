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

class StatusMapsHelper extends AppHelper{
	
	public $hoststatus = [];
	public $servicestatus = [];
	public $servicegroupstatus = [];
	public $hostgroupstatus = [];
	
	public function beforeRender($viewFile){
		//fill Hosts
		if(isset($this->_View->viewVars['hoststatus'])){
			$hoststatus = $this->_View->viewVars['hoststatus'];
			foreach($hoststatus as $hs){
				$this->hoststatus[$hs['Objects']['name1']] = $hs['Hoststatus'];
				if(isset($hs['Host'])){
					array_push($this->hoststatus[$hs['Objects']['name1']], $hs['Host']);
				}
			}
		}
		//debug($this->hoststatus);
		//fill Services
		if(isset($this->_View->viewVars['servicestatus'])){
			$servicestatus = $this->_View->viewVars['servicestatus'];
			foreach ($servicestatus as $ss) {
				$this->servicestatus[$ss['Objects']['name2']] = $ss['Servicestatus'];
				array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Service']);
				array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Servicetemplate']);
				array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Objects']);
			}
		}

		//fill Hostgroups
		if(isset($this->_View->viewVars['hostgroup'])){
			$this->hostgroupstatus = $this->_View->viewVars['hostgroup'];
		}

		//fill Servicegroups
		if(isset($this->_View->viewVars['servicegroup'])){
			$this->servicegroupstatus = $this->_View->viewVars['servicegroup'];
		}


		if(isset($this->_View->viewVars['mapstatus'])){
			$this->mapstatus = $this->_View->viewVars['mapstatus'];
		}
	}
	
	public function hoststatus($uuid){
		if(!empty($this->hoststatus[$uuid]['Servicestatus'])){
			//take the cumulative service state if the host has at least one service
			$hostServiceStatus = $this->hoststatus[$uuid]['Servicestatus'];
			if(isset($this->servicestatus)){
				$servicestatusOriginal = $this->servicestatus;
			}
			foreach ($hostServiceStatus as $hss) {
				$this->servicestatus[$hss['Objects']['name2']] = $hss['Servicestatus'];
				array_push($this->servicestatus[$hss['Objects']['name2']], $hss['Service']);
				array_push($this->servicestatus[$hss['Objects']['name2']], $hss['Servicetemplate']);
				array_push($this->servicestatus[$hss['Objects']['name2']], $hss['Objects']);
			}

			foreach($hostServiceStatus as $key => $hss){
				$hostServiceStates[$key] = $this->servicestatus($hss['Objects']['name2']);
			}

			$cumulative_service_state['Service'] = Hash::apply($hostServiceStates, '{n}.state', 'max');
			
			$stateKey = null;
			foreach ($hostServiceStates as $key => $value) {
				if($value['state'] == $cumulative_service_state['Service']){
					$stateKey = $key;
				}
			}
			if(sizeof($stateKey)>0){
				$servicestate = $hostServiceStates[$stateKey];
			}

			return $servicestate;

		}else{
			if(isset($this->hoststatus[$uuid]['current_state'])){
				if($this->hoststatus[$uuid]['problem_has_been_acknowledged'] == 1 && $this->hoststatus[$uuid]['scheduled_downtime_depth'] > 0){
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'human_state' => __('Host state is acknowledged and the host is in scheduled downtime'), 'image' => 'downtime_ack.png'];
				}
				
				if($this->hoststatus[$uuid]['problem_has_been_acknowledged'] == 1){
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'human_state' => __('Host state is acknowledged'), 'image' => 'ack.png'];
				}
				
				if($this->hoststatus[$uuid]['scheduled_downtime_depth'] > 0){
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'human_state' => __('Host is in scheduled downtime'), 'image' => 'downtime.png'];
				}

				$state = [
					0 => [
						'human_state' => __('Host is up'),
						'image' => 'up.png'
					],
					1 => [
						'human_state' => __('Host is down'),
						'image' => 'down.png'
					],
					2 => [
						'human_state' => __('Host is unreachable'),
						'image' => 'unreachable.png'
					]
				];
				
				return ['state' => $this->hoststatus[$uuid]['current_state'], 'human_state' => $state[$this->hoststatus[$uuid]['current_state']]['human_state'], 'image' => $state[$this->hoststatus[$uuid]['current_state']]['image']];
			}
			
			return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
		}
	}
	

	/**
	 * retrieve single or all fields from a host
	 *
	 * Get every field as array:
	 * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'])
	 *
	 * Get a single Value from the host
	 * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'], 'output')
	 *
	 * Get a single Value from the Host or return the default value
	 * $this->Mapstatus->hoststatusField($item['Host'][0]['uuid'], 'output', 'defaultValue')
	 * 
	 * @param  String $uuid    the UUID of the host
	 * @param  String $field   the field you want (optional)
	 * @param  null $default   the default value which shall be returned when the value wasnt found
	 * @return array           Array with the Hostinformation
	 */
	public function hoststatusField($uuid, $field = null, $default = null){
		if($field === null && isset($this->hoststatus[$uuid])){
			return $this->hoststatus[$uuid];
		}
		if(isset($this->hoststatus[$uuid][$field])){
			return $this->hoststatus[$uuid][$field];
		}
		return $default;
	}


	/**
	 * Returns the current service state 
	 * @param  String $uuid 	the service UUID
	 * @return array       		Array with the current service state
	 */
	public function servicestatus($uuid){
		if(isset($this->servicestatus[$uuid]['current_state'])){
			if($this->servicestatus[$uuid]['problem_has_been_acknowledged'] == 1 && $this->servicestatus[$uuid]['scheduled_downtime_depth'] > 0){
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'human_state' => __('Service state is acknowledged and the service is in scheduled downtime'), 'image' => 'downtime_ack.png'];
			}
			
			if($this->servicestatus[$uuid]['problem_has_been_acknowledged'] == 1){
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'human_state' => __('Service state is acknowledged'), 'image' => 'ack.png'];
			}
			
			if($this->servicestatus[$uuid]['scheduled_downtime_depth'] > 0){
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'human_state' => __('Service is in scheduled downtime'), 'image' => 'downtime.png'];
			}

			$state = [
				0 => [
					'human_state' => __('Ok'),
					'image' => 'up.png',
				],
				1 => [
					'human_state' => __('Warning'),
					'image' => 'down.png',
				],
				2 => [
					'human_state' => __('Critical'),
					'image' => 'critical.png',
				],
				3 => [
					'human_state' => __('Unreachable'),
					'image' => 'unreachable.png',
				]
			];
			return [
				'state' => $this->servicestatus[$uuid]['current_state'], 
				'human_state' => $state[$this->servicestatus[$uuid]['current_state']]['human_state'], 
				'image' => $state[$this->servicestatus[$uuid]['current_state']]['image'],
				'perfdata' => $this->servicestatus[$uuid]['perfdata']
			];
		}
		
		return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
	}



	public function servicestatusField($uuid, $field = null, $default = null){
		if($field === null && isset($this->servicestatus[$uuid])){
			return $this->servicestatus[$uuid];
		}
		if(isset($this->servicestatus[$uuid][$field])){
			return $this->servicestatus[$uuid][$field];
		}
		return $default;
	}


	public function servicegroupstatus($uuid){
		$servicestate = Hash::extract($this->servicegroupstatus[0], 'Servicegroup.{n}.Servicestatus.{n}.Servicestatus');

		if(!empty($servicestate)){
			$cumulative_service_state = Hash::apply($this->servicegroupstatus[0], 'Servicegroup.{n}.Servicestatus.{n}.Servicestatus.current_state', 'max');
			return $this->ServicegroupstatusValues($cumulative_service_state);
		}
		return $this->ServicegroupstatusValues(0);
	}

	public function hostgroupstatus($uuid){

		$cumulative_service_state = false;
		$cumulative_host_state = Hash::apply($this->hostgroupstatus[0], 'Host.{n}.Hoststatus.{n}.Hoststatus.current_state', 'max');
		
		foreach ($this->hostgroupstatus[0]['Host'] as $key => $hosts) {
			$cumulative_service_states_data[] = Hash::apply($hosts, 'Servicestatus.{n}.Servicestatus.current_state', 'max');
		}
		
		$cumulative_service_state = max($cumulative_service_states_data);
		return (!$cumulative_service_state)?$this->hostgroupstatusValuesHost($cumulative_host_state):$this->hostgroupstatusValuesService($cumulative_service_state);
	}

	public function hostgroupstatusValuesHost($state){
		$states = [
				0 => [
					'human_state' => __('Hostgroup is up'),
					'image' => 'up.png',
					'state' => 0
				],
				1 => [
					'human_state' => __('Hostgroup is down'),
					'image' => 'down.png',
					'state' => 1
				],
				2 => [
					'human_state' => __('Hostgroup is unreachable'),
					'image' => 'unreachable.png',
					'state' => 2
				],
				-1 => [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				]
			];
		return $states[$state];
	}
	
	public function hostgroupstatusValuesService($state){
		$states = [
				0 => [
					'human_state' => __('Ok'),
					'image' => 'up.png',
					'state' => 0
				],
				1 => [
					'human_state' => __('Warning'),
					'image' => 'down.png',
					'state' => 1
				],
				2 => [
					'human_state' => __('Critical'),
					'image' => 'critical.png',
					'state' => 2
				],
				3 => [
					'human_state' => __('Unreachable'),
					'image' => 'unreachable.png',
					'state' => 3
				],
				-1 => [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				]
			];
		return $states[$state];
	}

	public function ServicegroupstatusValues($state){
		$states = [
				0 => [
					'human_state' => __('Ok'),
					'image' => 'up.png',
					'state' => 0
				],
				1 => [
					'human_state' => __('Warning'),
					'image' => 'down.png',
					'state' => 1
				],
				2 => [
					'human_state' => __('Critical'),
					'image' => 'critical.png',
					'state' => 2
				],
				3 => [
					'human_state' => __('Unreachable'),
					'image' => 'unreachable.png',
					'state' => 3
				],
				-1 => [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				]
			];
		return $states[$state];
	}

	public function mapstatus($id){
		//returns the summary state for a Map
		
		$mapstatus = $this->mapstatus;
		//remove the map array from the mapstatus
		$mapstatus = Hash::remove($mapstatus, 'Map');

		$cumulative_host_state = [];
		$cumulative_service_state = [];
		$cumulative_hostgroup_state = [];
		$cumulative_servicegroup_state = [];


		foreach ($mapstatus as $key => $map) {
			switch ($key) {
				case 'hoststatus':
					$hostUuids = Hash::extract($map, '{n}.Objects.name1');
					$hoststates = [];
					foreach ($hostUuids as $key => $hostUuid) {
						$hoststates[$key] = $this->hoststatus($hostUuid);
					}

					$cumulative_host_state['Host'] = Hash::apply($hoststates, '{n}.state', 'max');

					$stateKey = null;
					foreach ($hoststates as $key => $value) {
						if($value['state'] == $cumulative_host_state['Host']){
							$stateKey = $key;
						}
					}
					
					if(sizeof($stateKey)>0){
						$hoststate = $hoststates[$stateKey];
					}
					break;
				case 'servicestatus':
					$serviceUuids = Hash::extract($map, '{n}.Objects.name2');
					$servicestates = [];
					foreach ($serviceUuids as $key => $serviceUuid) {
						$servicestates[$key] = $this->servicestatus($serviceUuid);
					}

					$cumulative_service_state['Service'] = Hash::apply($servicestates, '{n}.state', 'max');
					
					$stateKey = null;
					foreach ($servicestates as $key => $value) {
						if($value['state'] == $cumulative_service_state['Service']){
							$stateKey = $key;
						}
					}
					if(sizeof($stateKey)>0){
						$servicestate = $servicestates[$stateKey];
					}
					break;
				case 'hostgroupstatus':
					$hostgroupUuids = Hash::extract($map, '{n}.Hostgroup.uuid');
					$hostgroupstates = [];
					foreach ($hostgroupUuids as $key => $hostgroupUuid) {
						$hostgroupstates[$key] = $this->servicestatus($hostgroupUuid);
					}

					$cumulative_hostgroup_state['Hostgroup'] = Hash::apply($hostgroupstates, '{n}.state', 'max');
					
					$stateKey = null;
					foreach ($hostgroupstates as $key => $value) {
						if($value['state'] == $cumulative_hostgroup_state['Hostgroup']){
							$stateKey = $key;
						}
					}
					
					if(sizeof($stateKey)>0){
						$hostgroupstate = $hostgroupstates[$stateKey];
					}
					break;
				case 'servicegroupstatus':
					$servicegroupUuids = Hash::extract($map, '{n}.Servicegroup.uuid');
					$servicegroupstates = [];
					foreach ($servicegroupUuids as $key => $servicegroupUuid) {
						$servicegroupstates[$key] = $this->servicestatus($servicegroupUuid);
					}

					$cumulative_servicegroup_state['Servicegroup'] = Hash::apply($servicegroupstates, '{n}.state', 'max');
					
					$stateKey = null;
					foreach ($servicegroupstates as $key => $value) {
						if($value['state'] == $cumulative_servicegroup_state['Servicegroup']){
							$stateKey = $key;
						}
					}
					
					if(sizeof($stateKey)>0){
						$servicegroupstate = $servicegroupstates[$stateKey];
					}
					break;
			}
		}

		$cumulative_states = Hash::merge($cumulative_host_state, $cumulative_service_state, $cumulative_hostgroup_state, $cumulative_servicegroup_state);
		//calculate whole cumulative state and determine which type it is (Host or service for the correct return state)
		$cumulative_state = Hash::apply($cumulative_states, '{s}', 'max');
		$cumulative_key = array_search($cumulative_state, $cumulative_states);

		$typeAndState = [];
		$typeAndState[$cumulative_key] = $cumulative_state;


		switch ($cumulative_key) {
			case 'Host':
				$state = $hoststate;
				break;
			case 'Service':
				$state = $servicestate;
				break;
			case 'Hostgroup':
				$state = $hostgroupstate;
				break;
			case 'Servicegroup':
				$state = $servicegroupstate;
				break;
		}
		$return = Hash::merge($state, $typeAndState);

		return $return;
	}

}