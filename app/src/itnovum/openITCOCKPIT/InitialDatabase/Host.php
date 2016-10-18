<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\InitialDatabase;


class Host extends Importer
{
	/**
	 * @property \Host $Model
	 */

	/**
	 * @return bool
	 */
	public function import()
	{
		if ($this->isTableEmpty()) {
			$data = $this->getData();
			foreach ($data as $record) {
				$this->Model->create();

				$record['Host']['Contact'] = $record['Contact'];
				$record['Host']['Contactgroup'] = $record['Contactgroup'];

				$this->Model->saveAll($record);
			}
		}
		return true;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		$data = array (
			0 =>
				array (
					'Host' =>
						array (
							'id' => '1',
							'uuid' => 'c36b8048-93ce-4385-ac19-ab5c90574b77',
							'container_id' => '1',
							'name' => 'default host',
							'description' => NULL,
							'hosttemplate_id' => '1',
							'address' => '127.0.0.1',
							'command_id' => NULL,
							'eventhandler_command_id' => NULL,
							'timeperiod_id' => NULL,
							'check_interval' => NULL,
							'retry_interval' => NULL,
							'max_check_attempts' => NULL,
							'first_notification_delay' => NULL,
							'notification_interval' => NULL,
							'notify_on_down' => NULL,
							'notify_on_unreachable' => NULL,
							'notify_on_recovery' => NULL,
							'notify_on_flapping' => NULL,
							'notify_on_downtime' => NULL,
							'flap_detection_enabled' => NULL,
							'flap_detection_on_up' => NULL,
							'flap_detection_on_down' => NULL,
							'flap_detection_on_unreachable' => NULL,
							'low_flap_threshold' => NULL,
							'high_flap_threshold' => NULL,
							'process_performance_data' => NULL,
							'freshness_checks_enabled' => NULL,
							'freshness_threshold' => NULL,
							'passive_checks_enabled' => NULL,
							'event_handler_enabled' => NULL,
							'active_checks_enabled' => NULL,
							'retain_status_information' => NULL,
							'retain_nonstatus_information' => NULL,
							'notifications_enabled' => NULL,
							'notes' => NULL,
							'priority' => NULL,
							'check_period_id' => NULL,
							'notify_period_id' => NULL,
							'tags' => NULL,
							'own_contacts' => '0',
							'own_contactgroups' => '0',
							'own_customvariables' => '0',
							'host_url' => '',
							'satellite_id' => '0',
							'host_type' => '1',
							'disabled' => '0',
							'created' => '2015-01-15 19:26:32',
							'modified' => '2015-01-15 19:26:32',
						),
					'Container' =>
						array (
							'id' => '1',
							'containertype_id' => '1',
							'name' => 'root',
							'parent_id' => NULL,
							'lft' => '1',
							'rght' => '2',
							0 =>
								array (
									'id' => '1',
									'containertype_id' => '1',
									'name' => 'root',
									'parent_id' => NULL,
									'lft' => '1',
									'rght' => '2',
									'HostsToContainer' =>
										array (
											'id' => '1',
											'host_id' => '1',
											'container_id' => '1',
										),
								),
						),
					'HostescalationHostMembership' =>
						array (
						),
					'HostdependencyHostMembership' =>
						array (
						),
					'Customvariable' =>
						array (
						),
					'Hostcommandargumentvalue' =>
						array (
						),
					'Containers' =>
						array (
							0 =>
								array (
									'id' => '1',
									'containertype_id' => '1',
									'name' => 'root',
									'parent_id' => NULL,
									'lft' => '1',
									'rght' => '2',
									'HostsToContainer' =>
										array (
											'id' => '1',
											'host_id' => '1',
											'container_id' => '1',
										),
								),
						),
					'Contactgroup' =>
						array (
						),
					'Contact' =>
						array (
						),
					'Parenthost' =>
						array (
						),
					'Hostgroup' =>
						array (
						),
				),
		);
		return $data;
	}
}
