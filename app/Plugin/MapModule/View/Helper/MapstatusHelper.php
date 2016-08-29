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

class MapstatusHelper extends AppHelper{

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

		//fill Services
		if(isset($this->_View->viewVars['servicestatus'])){
			$servicestatus = $this->_View->viewVars['servicestatus'];
			if(!empty($servicestatus)){
				foreach ($servicestatus as $ss) {
					$this->servicestatus[$ss['Objects']['name2']] = $ss['Servicestatus'];
					array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Service']);
					array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Servicetemplate']);
					array_push($this->servicestatus[$ss['Objects']['name2']], $ss['Objects']);
				}
			}
		}

		//fill Hostgroups
		if(isset($this->_View->viewVars['hostgroups'])){
			$hostgroupstatus = $this->_View->viewVars['hostgroups'];
			foreach ($hostgroupstatus as $hgs) {
				$this->hostgroupstatus[$hgs['Hostgroup']['uuid']] = $hgs['Host'];
			}
		}

		//fill Servicegroups
		if(isset($this->_View->viewVars['servicegroups'])){
			$servicegroupstatus = $this->_View->viewVars['servicegroups'];
			foreach ($servicegroupstatus as $sgs) {
				$this->servicegroupstatus[$sgs['Servicegroup']['uuid']] = $sgs['Servicegroup']['Servicestatus'];
			}
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
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host state is acknowledged and the host is in scheduled downtime'), 'image' => 'downtime_ack.png'];
				}

				if($this->hoststatus[$uuid]['problem_has_been_acknowledged'] == 1){
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host state is acknowledged'), 'image' => 'ack.png'];
				}

				if($this->hoststatus[$uuid]['scheduled_downtime_depth'] > 0){
					return ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => __('Host is in scheduled downtime'), 'image' => 'downtime.png'];
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

				return ['state' => $this->hoststatus[$uuid]['current_state'], 'is_flapping' => $this->hoststatus[$uuid]['is_flapping'], 'human_state' => $state[$this->hoststatus[$uuid]['current_state']]['human_state'], 'image' => $state[$this->hoststatus[$uuid]['current_state']]['image']];
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
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'is_flapping' => $this->servicestatus[$uuid]['is_flapping'], 'human_state' => __('Service state is acknowledged and the service is in scheduled downtime'), 'image' => 'downtime_ack.png'];
			}

			if($this->servicestatus[$uuid]['problem_has_been_acknowledged'] == 1){
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'is_flapping' => $this->servicestatus[$uuid]['is_flapping'],'human_state' => __('Service state is acknowledged'), 'image' => 'ack.png'];
			}

			if($this->servicestatus[$uuid]['scheduled_downtime_depth'] > 0){
				return ['state' => $this->servicestatus[$uuid]['current_state'], 'is_flapping' => $this->servicestatus[$uuid]['is_flapping'],'human_state' => __('Service is in scheduled downtime'), 'image' => 'downtime.png'];
			}

			$state = [
				0 => [
					'human_state' => __('Ok'),
					'image' => 'up.png',
				],
				1 => [
					'human_state' => __('Warning'),
					'image' => 'warning.png',
				],
				2 => [
					'human_state' => __('Critical'),
					'image' => 'critical.png',
				],
				3 => [
					'human_state' => __('Unknown'),
					'image' => 'unknown.png',
				]
			];
			return [
				'state' => $this->servicestatus[$uuid]['current_state'],
				'is_flapping' => $this->servicestatus[$uuid]['is_flapping'],
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
		$servicestate = Hash::extract($this->servicegroupstatus[$uuid], '{n}.Servicestatus');
		if(!empty($servicestate)){
			$cumulative_service_state = Hash::apply($servicestate, '{n}.current_state', 'max');
			return $this->ServicegroupstatusValues($cumulative_service_state);
		}
		return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
	}

	public function hostgroupstatus($uuid){
		$cumulative_service_state = false;
		if(!empty($this->hostgroupstatus[$uuid])){
			$cumulative_host_state = Hash::apply($this->hostgroupstatus[$uuid], '{n}.Hoststatus.{n}.Hoststatus.current_state', 'max');

			foreach ($this->hostgroupstatus[$uuid] as $key => $hosts) {
				$currentStates = Hash::extract($hosts, 'Servicestatus.{n}.Servicestatus.current_state');
				if(is_array($currentStates) && !empty($currentStates)){
					$current_cumulative_service_state = Hash::apply($currentStates, '{n}', 'max');
					if(isset($current_cumulative_service_state)){
						$cumulative_service_states_data[] = $current_cumulative_service_state;
					}
				}

			}
			if(isset($cumulative_service_states_data)){
				$cumulative_service_state = max($cumulative_service_states_data);
			}
			return (!$cumulative_service_state)?$this->hostgroupstatusValuesHost($cumulative_host_state):$this->hostgroupstatusValuesService($cumulative_service_state);
		}
		return ['state' => -1, 'human_state' => __('Not found in monitoring'), 'image' => 'error.png'];
	}

	public function hostgroupstatusValuesHost($state){
		if(!isset($state)){
			$err = [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				];
			return $err;
		}
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
		if(!isset($state)){
			$err = [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				];
			return $err;
		}
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
		if(!isset($state)){
			$err = [
					'human_state' => __('Not found in monitoring'),
					'image' => 'error.png',
					'state' => -1
				];
			return $err;
		}
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
					'human_state' => __('Unknown'),
					'image' => 'unknown.png',
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
		$mapstatus = $this->mapstatus[$id];
		$cumulative_host_state = [];
		$cumulative_service_state = [];
		$cumulative_hostgroup_state = [];
		$cumulative_servicegroup_state = [];
		foreach ($mapstatus as $key => $map) {
			switch ($key) {
				case 'hoststatus':
					if(empty($mapstatus['hoststatus'][0])){
						continue;
					}
					$hoststates = Hash::extract($mapstatus['hoststatus'], '{n}.{n}.Hoststatus.current_state');
					$servicestates = Hash::extract($mapstatus['hoststatus'], '{n}.{n}.Servicestatus.{n}.Servicestatus.current_state');

					//$hostAndServiceStates = Hash::merge($hoststates, $servicestates);
					$cumulative_host_state['Host']['Host'] = Hash::apply($hoststates, '{n}', 'max');
					$cumulative_host_state['Host']['Service'] = Hash::apply($servicestates, '{n}', 'max');
					break;
				case 'servicestatus':
					if(empty($mapstatus['servicestatus'][0])){
						continue;
					}
					$servicestates = Hash::extract($mapstatus['servicestatus'],'{n}.Servicestatus.current_state');
					$cumulative_service_state['Service']['Service'] = Hash::apply($servicestates, '{n}', 'max');
					break;
				case 'hostgroupstatus':
					if(empty($mapstatus['hostgroupstatus'][0])){
						continue;
					}
					$hostgroupHoststates = Hash::extract($mapstatus['hostgroupstatus'], '{n}.{n}.Hoststatus.current_state');
					$hostgroupServicestates = Hash::extract($mapstatus['hostgroupstatus'], '{n}.{n}.Servicestatus.{n}.Servicestatus.current_state');
					//$hostAndServiceStates = Hash::merge($hostgroupHoststates, $hostgroupServicestates);

					$cumulative_hostgroup_state['Hostgroup']['Host'] = Hash::apply($hostgroupHoststates, '{n}', 'max');
					$cumulative_hostgroup_state['Hostgroup']['Service'] = Hash::apply($hostgroupServicestates, '{n}', 'max');
					break;
				case 'servicegroupstatus':
					if(empty($mapstatus['servicegroupstatus'][0])){
						continue;
					}
					$servicegroupServicestates = Hash::extract($mapstatus['servicegroupstatus'], '{n}.{n}.Servicestatus.{n}.Servicestatus.current_state');
					$cumulative_servicegroup_state['Servicegroup']['Service'] = Hash::apply($servicegroupServicestates, '{n}', 'max');
					break;
			}
		}

		$cumulative_states = Hash::merge($cumulative_host_state, $cumulative_service_state, $cumulative_hostgroup_state, $cumulative_servicegroup_state);
		//calculate whole cumulative state and determine which type it is (Host or service for the correct return state)
		$cumulative_state = -1;
		$key = null;
		if(!empty($cumulative_states)){
			$cumulative_state = Hash::apply($cumulative_states, '{s}.{s}', 'max');
			foreach ($cumulative_states as $type => $value) {
				foreach($cumulative_states[$type] as $typeKey => $state){
					if($cumulative_state == $state){
						$key = $typeKey;
						break;
					}
				}
			}
		}
		//the state wich will be displayed in the view mode
		$baseStateForView = $this->ServicegroupstatusValues($cumulative_state);
		$baseStateForView['cumulated_type_key'] = $key;

		return $baseStateForView;
	}

}