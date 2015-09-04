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

//App::import('Model', 'Host');
//App::import('Model', 'Container');
class BrowsersController extends AppController{

	public $layout = 'Admin.default';
	public $helpers = [
		'PieChart',
		'BrowserMisc',
		'Status',
		'Monitoring',
	];
	public $uses = [
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		'Host',
		'Service',
		'Container',
		'Tenant',
		'Browser',
	];

	function index($id = null){
		if($id != null){
			$top_node = $this->Container->findById($id);
		}else{
			$top_node = $this->Container->find('first');
		}
		$parents = $this->Container->getPath($top_node['Container']['parent_id']);
		$browser = Hash::extract($this->Container->children($top_node['Container']['id'], true), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/]');
		$allContainerArr = $this->Container->children($id, false, ['id', 'containertype_id']);

		$all_container_ids = Hash::merge([$id], Hash::extract($allContainerArr, '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/].id'));
		
		$tenants = $this->Tenant->tenantsByContainerId(
			array_merge(
				$this->MY_RIGHTS, array_keys(
					$this->User->getTenantIds(
						$this->Auth->user('id')
					)
				)
			),
			'list', 'container_id');
		$query = $this->Browser->hostsQuery($this->MY_RIGHTS);
		$hosts = $this->Host->find('all', $query);

		$query = $this->Browser->serviceQuery($this->MY_RIGHTS);
		$services = $this->Service->find('all', $query);
		
		$state_array_host = [
			0 => 0,
			1 => 0,
			2 => 0
		];
		$state_array_service = [
			0 => 0,
			1 => 0,
			2 => 0,
			3 => 0
		];
		
		foreach($hosts as $host){
			$state_array_host[$host['Hoststatus']['current_state']]++;
		}
		foreach($services as $service){
			$state_array_service[$service['Servicestatus']['current_state']]++;
		}

		$this->set(compact([
			'browser',
			'parents',
			'top_node',
			'state_array_host',
			'state_array_service',
			'all_container_ids',
			'tenants',
			'hosts',
		]));
	}


	function tenantBrowser($id = null){
		if($id != null){
			$top_node = $this->Container->findById($id);
		}else{
			$top_node = $this->Container->find('first');
		}

		//debug($this->Container->children($id, true));

		$browser = Hash::extract($this->Container->children($id, true), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/]');
		$all_nodes = Hash::extract($this->Container->children($id, false), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/]');

		//ids of all containers within this Tenant
		$child_node_ids = Hash::extract($all_nodes, '{n}.id');
		$all_containertype_ids = Hash::combine($all_nodes, '{n}.id', '{n}.containertype_id');

		$child_node_hosts = $this->Host->find('all',[
			'recursive' => '-1',
			'conditions' => [
				'AND' => [
					'Host.container_id' => $child_node_ids,
					'Host.disabled' => 0
				]
			],
			'fields' => [
				'Host.id',
				'Host.name',
				'Host.uuid',
				'Host.container_id'
			]
		]);
		
		$all_host_uuids = Hash::extract($child_node_hosts, '{n}.Host.uuid');
		$all_host_ids = Hash::extract($child_node_hosts, '{n}.Host.id');

		$hoststatus = $this->Hoststatus->find('all', [
			'conditions' => [
				'Objects.name1' => $all_host_uuids,
			],
			'fields' => [
				'Hoststatus.current_state'
			]
		]);

		$all_services = $this->Service->find('all',[
			'recursive' => '-1',
			'conditions' => [
				'AND' => [
					'Service.host_id' => $all_host_ids,
					'Service.disabled' => 0
				]
			],
			'fields' => [
				'Service.uuid',
				'Service.host_id'
			]
		]);

		$all_service_uuids = Hash::extract($all_services, '{n}.Service.uuid');

		$servicestatus = $this->Servicestatus->find('all', [
			'conditions' => [
				'Objects.name2' => $all_service_uuids
			],
			'fields' => [
				'Servicestatus.current_state'
			]
		]);


		$state_array_host = [0,0,0];
		$state_array_service = [0,0,0,0];
		$host_status_count = array_count_values(Hash::extract($hoststatus, '{n}.Hoststatus.current_state'));
		foreach($host_status_count as $key => $value){
			$state_array_host[$key] = $value;
		}

		$service_status_count = array_count_values(Hash::extract($servicestatus, '{n}.Servicestatus.current_state'));
		foreach($service_status_count as $key => $value){
			$state_array_service[$key] = $value;
		}

		$parents = $this->Container->getPath($top_node['Container']['parent_id']);

		$all_container_ids = Hash::merge([$id], $child_node_ids);
		
		$this->set(compact([
			'top_node',
			'browser',
			'parents',
			'state_array_host',
			'state_array_service',
			'all_container_ids',
			'hosts'
		]));
	}

	function locationBrowser($id = null){
		if($id != null){
			$top_node = $this->Container->findById($id);
		}else{
			$top_node = $this->Container->find('first');
		}
		$parents = $this->Container->getPath($top_node['Container']['parent_id']);

		$browser = ['There is nothing to show'];
		$this->set(compact(['top_node', 'parents', 'browser']));
	}

	function devicegroupBrowser($id = null){
		//not implemented yet
	}

	function nodeBrowser($id = null){
		if($id != null){
			$top_node = $this->Container->findById($id);
		}else{
			$top_node = $this->Container->find('first');
		}
		$parents = $this->Container->getPath($top_node['Container']['parent_id']);

		$browser = Hash::extract($this->Container->children($top_node['Container']['id'], true), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/]');
		$all_nodes = Hash::extract($this->Container->children($top_node['Container']['id'], false), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_DEVICEGROUP.'|'.CT_NODE.')$/]');

		//the child nodes of the current node 
		$child_node_ids = Hash::extract($all_nodes, '{n}.id');
		//neede for both (list and chart)
		$all_hosts = $this->Host->find('all',[
				'recursive' => '-1',
				'conditions' => [
					'AND' => [
						'Host.container_id' => $top_node['Container']['id'],
						'Host.disabled' => 0
					]
				],
				'fields' => [
					'Host.id',
					'Host.name',
					'Host.uuid',
					'Host.container_id'
				]
		]);

		$browser = array_merge($browser, $all_hosts);

		$child_node_hosts = $this->Host->find('all',[
				'recursive' => '-1',
				'conditions' => [
					'AND' => [
						'Host.container_id' => $child_node_ids,
						'Host.disabled' => 0
					]
				],
				'fields' => [
					'Host.id',
					'Host.name',
					'Host.uuid',
					'Host.container_id'
				]
		]);

		//all hosts (list hosts and recursive childs) for the chart
		$all_hosts = array_merge($all_hosts, $child_node_hosts);

		$hoststatus = $this->Hoststatus->find('all', [
			'conditions' => [
				'Objects.name1' => Hash::extract($all_hosts, '{n}.Host.uuid')
			],
			'fields' => [
				'Hoststatus.current_state'
			]
		]);
		
		$all_services = $this->Service->find('all',[
			'recursive' => '-1',
			'conditions' => [
				'AND' => [
					'Service.host_id' => Hash::extract($all_hosts, '{n}.Host.id'),
					'Service.disabled' => 0
				]
			],
			'fields' => [
				'Service.uuid',
				'Service.host_id'
			]
		]);
		$servicestatus = $this->Servicestatus->find('all', [
			'conditions' => [
				'Objects.name2' => Hash::extract($all_services, '{n}.Service.uuid')
			],
			'fields' => [
				'Servicestatus.current_state'
			]
		]);

		$state_array_host = [0,0,0];
		$state_array_service = [0,0,0,0];
		$host_status_count = array_count_values(Hash::extract($hoststatus, '{n}.Hoststatus.current_state'));
		foreach($host_status_count as $key => $value){
			$state_array_host[$key] = $value;
		}

		$service_status_count = array_count_values(Hash::extract($servicestatus, '{n}.Servicestatus.current_state'));
		foreach($service_status_count as $key => $value){
			$state_array_service[$key] = $value;
		}
		$all_container_ids = Hash::merge([$id],$child_node_ids);

		$this->set(compact(['top_node', 'parents', 'browser', 'state_array_host', 'state_array_service', 'all_container_ids']));
	}
}
