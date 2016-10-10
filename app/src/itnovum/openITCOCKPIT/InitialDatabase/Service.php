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


class Service extends Importer
{
	/**
	 * @property \Service $Model
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

				$record['Service']['Contact'] = $record['Contact'];
				$record['Service']['Contactgroup'] = $record['Contactgroup'];

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
		$data = array(
			0 =>
				array(
					'Service' =>
						array(
							'id' => '1',
							'uuid' => '74fd8f59-1348-4e16-85f0-4a5c57c7dd62',
							'servicetemplate_id' => '1',
							'host_id' => '1',
							'name' => NULL,
							'description' => NULL,
							'command_id' => NULL,
							'check_command_args' => '',
							'eventhandler_command_id' => NULL,
							'notify_period_id' => NULL,
							'check_period_id' => NULL,
							'check_interval' => NULL,
							'retry_interval' => NULL,
							'max_check_attempts' => NULL,
							'first_notification_delay' => NULL,
							'notification_interval' => NULL,
							'notify_on_warning' => NULL,
							'notify_on_unknown' => NULL,
							'notify_on_critical' => NULL,
							'notify_on_recovery' => NULL,
							'notify_on_flapping' => NULL,
							'notify_on_downtime' => NULL,
							'is_volatile' => NULL,
							'flap_detection_enabled' => NULL,
							'flap_detection_on_ok' => NULL,
							'flap_detection_on_warning' => NULL,
							'flap_detection_on_unknown' => NULL,
							'flap_detection_on_critical' => NULL,
							'low_flap_threshold' => NULL,
							'high_flap_threshold' => NULL,
							'process_performance_data' => NULL,
							'freshness_checks_enabled' => NULL,
							'freshness_threshold' => NULL,
							'passive_checks_enabled' => NULL,
							'event_handler_enabled' => NULL,
							'active_checks_enabled' => NULL,
							'notifications_enabled' => NULL,
							'notes' => NULL,
							'priority' => NULL,
							'tags' => NULL,
							'own_contacts' => '0',
							'own_contactgroups' => '0',
							'own_customvariables' => '0',
							'service_url' => NULL,
							'service_type' => '1',
							'disabled' => '0',
							'created' => '2015-01-15 19:26:46',
							'modified' => '2015-01-15 19:26:46',
						),
					'Servicecommandargumentvalue' =>
						array(),
					'Serviceeventcommandargumentvalue' =>
						array(),
					'ServiceEscalationServiceMembership' =>
						array(),
					'ServicedependencyServiceMembership' =>
						array(),
					'Customvariable' =>
						array(),
					'Contactgroup' =>
						array(),
					'Contact' =>
						array(),
					'Servicegroup' =>
						array(),
				),
			1 =>
				array(
					'Service' =>
						array(
							'id' => '2',
							'uuid' => '74f14950-a58f-4f18-b6c3-5cfa9dffef4e',
							'servicetemplate_id' => '8',
							'host_id' => '1',
							'name' => NULL,
							'description' => NULL,
							'command_id' => NULL,
							'check_command_args' => '',
							'eventhandler_command_id' => NULL,
							'notify_period_id' => NULL,
							'check_period_id' => NULL,
							'check_interval' => NULL,
							'retry_interval' => NULL,
							'max_check_attempts' => NULL,
							'first_notification_delay' => NULL,
							'notification_interval' => NULL,
							'notify_on_warning' => NULL,
							'notify_on_unknown' => NULL,
							'notify_on_critical' => NULL,
							'notify_on_recovery' => NULL,
							'notify_on_flapping' => NULL,
							'notify_on_downtime' => NULL,
							'is_volatile' => NULL,
							'flap_detection_enabled' => NULL,
							'flap_detection_on_ok' => NULL,
							'flap_detection_on_warning' => NULL,
							'flap_detection_on_unknown' => NULL,
							'flap_detection_on_critical' => NULL,
							'low_flap_threshold' => NULL,
							'high_flap_threshold' => NULL,
							'process_performance_data' => NULL,
							'freshness_checks_enabled' => NULL,
							'freshness_threshold' => NULL,
							'passive_checks_enabled' => NULL,
							'event_handler_enabled' => NULL,
							'active_checks_enabled' => NULL,
							'notifications_enabled' => NULL,
							'notes' => NULL,
							'priority' => NULL,
							'tags' => NULL,
							'own_contacts' => '0',
							'own_contactgroups' => '0',
							'own_customvariables' => '0',
							'service_url' => NULL,
							'service_type' => '1',
							'disabled' => '0',
							'created' => '2015-01-16 00:46:39',
							'modified' => '2015-01-16 00:46:39',
						),
					'Servicecommandargumentvalue' =>
						array(),
					'Serviceeventcommandargumentvalue' =>
						array(),
					'ServiceEscalationServiceMembership' =>
						array(),
					'ServicedependencyServiceMembership' =>
						array(),
					'Customvariable' =>
						array(),
					'Contactgroup' =>
						array(),
					'Contact' =>
						array(),
					'Servicegroup' =>
						array(),
				),
			2 =>
				array(
					'Service' =>
						array(
							'id' => '3',
							'uuid' => '1c045407-5502-4468-aabc-7781f6cf3dec',
							'servicetemplate_id' => '9',
							'host_id' => '1',
							'name' => NULL,
							'description' => NULL,
							'command_id' => NULL,
							'check_command_args' => '',
							'eventhandler_command_id' => NULL,
							'notify_period_id' => NULL,
							'check_period_id' => NULL,
							'check_interval' => NULL,
							'retry_interval' => NULL,
							'max_check_attempts' => NULL,
							'first_notification_delay' => NULL,
							'notification_interval' => NULL,
							'notify_on_warning' => NULL,
							'notify_on_unknown' => NULL,
							'notify_on_critical' => NULL,
							'notify_on_recovery' => NULL,
							'notify_on_flapping' => NULL,
							'notify_on_downtime' => NULL,
							'is_volatile' => NULL,
							'flap_detection_enabled' => NULL,
							'flap_detection_on_ok' => NULL,
							'flap_detection_on_warning' => NULL,
							'flap_detection_on_unknown' => NULL,
							'flap_detection_on_critical' => NULL,
							'low_flap_threshold' => NULL,
							'high_flap_threshold' => NULL,
							'process_performance_data' => NULL,
							'freshness_checks_enabled' => NULL,
							'freshness_threshold' => NULL,
							'passive_checks_enabled' => NULL,
							'event_handler_enabled' => NULL,
							'active_checks_enabled' => NULL,
							'notifications_enabled' => NULL,
							'notes' => NULL,
							'priority' => NULL,
							'tags' => NULL,
							'own_contacts' => '0',
							'own_contactgroups' => '0',
							'own_customvariables' => '0',
							'service_url' => NULL,
							'service_type' => '1',
							'disabled' => '0',
							'created' => '2015-01-16 00:46:52',
							'modified' => '2015-01-16 00:46:52',
						),
					'Servicecommandargumentvalue' =>
						array(),
					'Serviceeventcommandargumentvalue' =>
						array(),
					'ServiceEscalationServiceMembership' =>
						array(),
					'ServicedependencyServiceMembership' =>
						array(),
					'Customvariable' =>
						array(),
					'Contactgroup' =>
						array(),
					'Contact' =>
						array(),
					'Servicegroup' =>
						array(),
				),
			3 =>
				array(
					'Service' =>
						array(
							'id' => '4',
							'uuid' => '7391f1aa-5e2e-447a-8a9b-b23357b9cd2a',
							'servicetemplate_id' => '13',
							'host_id' => '1',
							'name' => NULL,
							'description' => NULL,
							'command_id' => NULL,
							'check_command_args' => '',
							'eventhandler_command_id' => NULL,
							'notify_period_id' => NULL,
							'check_period_id' => NULL,
							'check_interval' => NULL,
							'retry_interval' => NULL,
							'max_check_attempts' => NULL,
							'first_notification_delay' => NULL,
							'notification_interval' => NULL,
							'notify_on_warning' => NULL,
							'notify_on_unknown' => NULL,
							'notify_on_critical' => NULL,
							'notify_on_recovery' => NULL,
							'notify_on_flapping' => NULL,
							'notify_on_downtime' => NULL,
							'is_volatile' => NULL,
							'flap_detection_enabled' => NULL,
							'flap_detection_on_ok' => NULL,
							'flap_detection_on_warning' => NULL,
							'flap_detection_on_unknown' => NULL,
							'flap_detection_on_critical' => NULL,
							'low_flap_threshold' => NULL,
							'high_flap_threshold' => NULL,
							'process_performance_data' => NULL,
							'freshness_checks_enabled' => NULL,
							'freshness_threshold' => NULL,
							'passive_checks_enabled' => NULL,
							'event_handler_enabled' => NULL,
							'active_checks_enabled' => NULL,
							'notifications_enabled' => NULL,
							'notes' => NULL,
							'priority' => NULL,
							'tags' => NULL,
							'own_contacts' => '0',
							'own_contactgroups' => '0',
							'own_customvariables' => '0',
							'service_url' => NULL,
							'service_type' => '1',
							'disabled' => '0',
							'created' => '2015-01-16 00:47:06',
							'modified' => '2015-01-16 00:47:06',
						),
					'Servicecommandargumentvalue' =>
						array(),
					'Serviceeventcommandargumentvalue' =>
						array(),
					'ServiceEscalationServiceMembership' =>
						array(),
					'ServicedependencyServiceMembership' =>
						array(),
					'Customvariable' =>
						array(),
					'Contactgroup' =>
						array(),
					'Contact' =>
						array(),
					'Servicegroup' =>
						array(),
				),
		);
		return $data;
	}
}
