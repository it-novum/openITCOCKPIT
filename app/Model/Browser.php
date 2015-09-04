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

class Browser extends AppModel{
	var $useTable = false;
	
	public function hostsQuery($containerIds = []){
		return [
			'recursive' => -1,
			'contain' => [],
			'fields' => [
				'Host.id',
				'Host.uuid',
				'Host.name',
				'Host.address',

				'Hoststatus.current_state',
				'Hoststatus.last_check',
				'Hoststatus.next_check',
				'Hoststatus.last_hard_state_change',
				'Hoststatus.output',
				'Hoststatus.state_type',
				'Hoststatus.is_flapping',
			],
			'conditions' => [
				'HostsToContainers.container_id' => $containerIds,
				'Host.disabled' => 0,
			],
			'joins' => [
				[
					'table' => 'nagios_objects',
					'type' => 'INNER',
					'alias' => 'HostObject',
					'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
				], [
					'table' => 'nagios_hoststatus',
					'type' => 'LEFT OUTER',
					'alias' => 'Hoststatus',
					'conditions' => 'Hoststatus.host_object_id = HostObject.object_id'
				], [
					'table' => 'hosts_to_containers',
					'alias' => 'HostsToContainers',
					'type' => 'LEFT',
					'conditions' => [
						'HostsToContainers.host_id = Host.id',
					]
				]
			],
			'order' => [
				'Hoststatus.current_state' => 'DESC',
			],
			'group' => [
				'Host.id'
			]
		];
	}
	
	public function serviceQuery($containerIds = []){
		return [
			'recursive' => -1,
			'conditions' => [
				'HostsToContainers.container_id' => $containerIds,
				'Host.disabled' => 0,
				'Service.disabled' => 0,
			],
			'contain' => [],
			'fields' => [
				'Service.id',
				'Service.uuid',
				'Servicestatus.current_state'
			],
			'joins' => [[
				'table' => 'hosts',
				'type' => 'INNER',
				'alias' => 'Host',
				'conditions' => 'Service.host_id = Host.id'
			], [
				'table' => 'nagios_objects',
				'type' => 'INNER',
				'alias' => 'HostObject',
				'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1'
			], [
				'table' => 'nagios_hoststatus',
				'type' => 'INNER',
				'alias' => 'Hoststatus',
				'conditions' => 'Hoststatus.host_object_id = HostObject.object_id'
			], [
				'table' => 'nagios_objects',
				'type' => 'INNER',
				'alias' => 'ServiceObject',
				'conditions' => 'ServiceObject.name1 = Host.uuid AND Service.uuid = ServiceObject.name2 AND ServiceObject.objecttype_id = 2'
			], [
				'table' => 'nagios_servicestatus',
				'type' => 'LEFT OUTER',
				'alias' => 'Servicestatus',
				'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id'
			], [
				'table' => 'hosts_to_containers',
				'alias' => 'HostsToContainers',
				'type' => 'LEFT',
				'conditions' => [
					'HostsToContainers.host_id = Host.id',
				]
			]
			],
			'group' => [
				'Service.id'
			]
		];
	}
}
