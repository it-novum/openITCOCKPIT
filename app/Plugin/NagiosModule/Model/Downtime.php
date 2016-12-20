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

class Downtime extends NagiosModuleAppModel{
	public $useTable = 'downtimehistory';
	public $primaryKey = 'downtimehistory_id';
	public $tablePrefix = 'nagios_';
	public $recursive = 2;
	public $belongsTo = [
		'Objects' => [
			'className' => 'NagiosModule.Objects',
			'foreignKey' => 'object_id',
		],
	];

	//See http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf and search for "downtimehistory Table"
	public $downtime_type = '1,2';

	public function listSettings($requestData){
		$return = [
			'conditions' => [],
			'paginator' => [],
			'Listsettings' => []
		];
		if(isset($requestData['Listsettings']['view'])){
			switch($requestData['Listsettings']['view']){
				case 'hostOnly':
					$return['join'] = '1';
					$return['Listsettings']['view'] = 'hostOnly';
					break;

				case 'serviceOnly':
					$return['join'] = '2';
					$return['Listsettings']['view'] = 'serviceOnly';
					break;

				default:
					$return['join'] = $this->downtime_type;
					$return['Listsettings']['view'] = 'hostOnly';
					break;
			}
		}

		if(isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])){
			$return['paginator']['limit'] = $requestData['Listsettings']['limit'];
			$return['Listsettings']['limit'] = $return['paginator']['limit'];
		}

		if(isset($requestData['Listsettings']['hide_expired'])){
			if($requestData['Listsettings']['hide_expired'] == 1 || $requestData['Listsettings']['hide_expired'] == 'on' || $requestData['Listsettings']['hide_expired'] == 'On'){
				$return['paginator']['conditions']['Downtime.scheduled_end_time >'] = date('Y-m-d H:i:s', time() + (60 * 5));
				$return['Listsettings']['hide_expired'] = 1;
			}
		}
		return $return;

	}

	public function hostListSettings($cakeRequest, $MY_RIGHTS = [], $limit = 25){

		$requestData = $cakeRequest->data;

		if(isset($cakeRequest->params['named']['Listsettings'])){
			$requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
		}
		$requestParams = $cakeRequest->params;

		$return = [
			'paginator' => [
				'limit' => $limit,
				'order' => ['Downtime.scheduled_start_time' => 'DESC'],
			],
			'Listsettings' => [
				'limit' => $limit,
				'hide_expired' => 0
			],
			'default'=> [
				'recursive' => -1,
				'fields' => [
					'Downtime.author_name',
					'Downtime.comment_data',
					'Downtime.entry_time',
					'Downtime.scheduled_start_time',
					'Downtime.scheduled_end_time',
					'Downtime.duration',
					'Downtime.was_started',
					'Downtime.internal_downtime_id',
					'Downtime.downtimehistory_id',
					'Downtime.was_cancelled',

					'Host.id',
					'Host.uuid',
					'Host.name',

					'HostsToContainers.container_id'

				],
				'joins' => [
					[
						'table' => 'nagios_objects',
						'type' => 'INNER',
						'alias' => 'Objects',
						'conditions' => 'Objects.object_id = Downtime.object_id AND Downtime.downtime_type = 2' //Downtime.downtime_type = 2 Host downtime
					],

					[
						'table' => 'hosts',
						'type' => 'INNER',
						'alias' => 'Host',
						'conditions' => 'Host.uuid = Objects.name1'
					],
					[
						'table' => 'hosts_to_containers',
						'alias' => 'HostsToContainers',
						'type' => 'LEFT',
						'conditions' => [
							'HostsToContainers.host_id = Host.id',
						]
					]
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

		if(isset($requestData['Listsettings']['from'])){
			$time = strtotime($requestData['Listsettings']['from']);
			if($time == false || !is_numeric($time)){
				$time = strtotime('3 days ago');
			}

			$return['conditions']['Downtime.scheduled_start_time >'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['from'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Downtime.scheduled_start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
			$return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
		}

		if(isset($requestData['Listsettings']['to'])){
			$time = strtotime($requestData['Listsettings']['to']);
			if($time == false || !is_numeric($time)){
				$time = time() + (60 * 5); //Add 5 minutes to avoid missing entires in search results
			}

			$return['conditions']['Downtime.scheduled_start_time <'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['to'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Downtime.scheduled_start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
		}

		if(isset($requestData['Listsettings']['hide_expired']) && $requestData['Listsettings']['hide_expired'] == 1){
			$return['conditions']['Downtime.scheduled_end_time >'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['hide_expired'] = 1;
		}

		$return['conditions']['HostsToContainers.container_id'] = $MY_RIGHTS;

		return $return;
	}

	public function serviceListSettings($cakeRequest, $MY_RIGHTS = [], $limit = 25){
		$requestData = $cakeRequest->data;

		if(isset($cakeRequest->params['named']['Listsettings'])){
			$requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
		}
		$requestParams = $cakeRequest->params;

		$return = [
			'paginator' => [
				'limit' => $limit,
				'order' => ['Downtime.scheduled_start_time' => 'DESC'],
			],
			'Listsettings' => [
				'limit' => $limit,
				'hide_expired' => 0
			],
			'default'=> [
				'recursive' => -1,
				'fields' => [
					'Downtime.author_name',
					'Downtime.comment_data',
					'Downtime.entry_time',
					'Downtime.scheduled_start_time',
					'Downtime.scheduled_end_time',
					'Downtime.duration',
					'Downtime.was_started',
					'Downtime.internal_downtime_id',
					'Downtime.downtimehistory_id',
					'Downtime.was_cancelled',

					'Host.id',
					'Host.uuid',
					'Host.name',

					'Service.id',
					'Service.uuid',
					'Service.name',
					'Service.servicetemplate_id',

					'Servicetemplate.id',
					'Servicetemplate.name',

					'HostsToContainers.container_id'
					],
				'joins' => [
					[
						'table' => 'nagios_objects',
						'type' => 'INNER',
						'alias' => 'Objects',
						'conditions' => 'Objects.object_id = Downtime.object_id AND Downtime.downtime_type = 1' //Downtime.downtime_type = 1 Service downtime
					],

					[
						'table' => 'services',
						'type' => 'INNER',
						'alias' => 'Service',
						'conditions' => 'Service.uuid = Objects.name2'
					],
					[
						'table' => 'servicetemplates',
						'type' => 'INNER',
						'alias' => 'Servicetemplate',
						'conditions' => 'Servicetemplate.id = Service.servicetemplate_id'
					],
					[
						'table' => 'hosts',
						'type' => 'INNER',
						'alias' => 'Host',
						'conditions' => 'Host.id = Service.host_id'
					],
					[
						'table' => 'hosts_to_containers',
						'alias' => 'HostsToContainers',
						'type' => 'LEFT',
						'conditions' => [
							'HostsToContainers.host_id = Host.id',
						]
					]

				],
				'findType' => 'all',
				'group' => 'Downtime.downtimehistory_id'
			]
		];


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

			$return['conditions']['Downtime.scheduled_start_time >'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['from'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Downtime.scheduled_start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
			$return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
		}

		if(isset($requestData['Listsettings']['to'])){
			$time = strtotime($requestData['Listsettings']['to']);
			if($time == false || !is_numeric($time)){
				$time = time() + (60 * 5);
			}

			$return['conditions']['Downtime.scheduled_start_time <'] = date('Y-m-d H:i:s', $time);
			$return['Listsettings']['to'] = date('d.m.Y H:i', $time);
		}else{
			$return['conditions']['Downtime.scheduled_start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
		}

		if(isset($requestData['Listsettings']['hide_expired']) && $requestData['Listsettings']['hide_expired'] == 1){
			$return['conditions']['Downtime.scheduled_end_time >'] = date('Y-m-d H:i:s', time() + (60 * 5));
			$return['Listsettings']['hide_expired'] = 1;
		}

		$return['conditions']['HostsToContainers.container_id'] = $MY_RIGHTS;
		return $return;
	}

}
