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
        $data = [
            0 =>
                [
                    'Service'                            =>
                        [
                            'id'                         => '1',
                            'uuid'                       => '74fd8f59-1348-4e16-85f0-4a5c57c7dd62',
                            'servicetemplate_id'         => '1',
                            'host_id'                    => '1',
                            'name'                       => null,
                            'description'                => null,
                            'command_id'                 => null,
                            'check_command_args'         => '',
                            'eventhandler_command_id'    => null,
                            'notify_period_id'           => null,
                            'check_period_id'            => null,
                            'check_interval'             => null,
                            'retry_interval'             => null,
                            'max_check_attempts'         => null,
                            'first_notification_delay'   => null,
                            'notification_interval'      => null,
                            'notify_on_warning'          => null,
                            'notify_on_unknown'          => null,
                            'notify_on_critical'         => null,
                            'notify_on_recovery'         => null,
                            'notify_on_flapping'         => null,
                            'notify_on_downtime'         => null,
                            'is_volatile'                => null,
                            'flap_detection_enabled'     => null,
                            'flap_detection_on_ok'       => null,
                            'flap_detection_on_warning'  => null,
                            'flap_detection_on_unknown'  => null,
                            'flap_detection_on_critical' => null,
                            'low_flap_threshold'         => null,
                            'high_flap_threshold'        => null,
                            'process_performance_data'   => null,
                            'freshness_checks_enabled'   => null,
                            'freshness_threshold'        => null,
                            'passive_checks_enabled'     => null,
                            'event_handler_enabled'      => null,
                            'active_checks_enabled'      => null,
                            'notifications_enabled'      => null,
                            'notes'                      => null,
                            'priority'                   => null,
                            'tags'                       => null,
                            'own_contacts'               => '0',
                            'own_contactgroups'          => '0',
                            'own_customvariables'        => '0',
                            'service_url'                => null,
                            'service_type'               => '1',
                            'disabled'                   => '0',
                            'created'                    => '2015-01-15 19:26:46',
                            'modified'                   => '2015-01-15 19:26:46',
                        ],
                    'Servicecommandargumentvalue'        =>
                        [],
                    'Serviceeventcommandargumentvalue'   =>
                        [],
                    'ServiceEscalationServiceMembership' =>
                        [],
                    'ServicedependencyServiceMembership' =>
                        [],
                    'Customvariable'                     =>
                        [],
                    'Contactgroup'                       =>
                        [],
                    'Contact'                            =>
                        [],
                    'Servicegroup'                       =>
                        [],
                ],
            1 =>
                [
                    'Service'                            =>
                        [
                            'id'                         => '2',
                            'uuid'                       => '74f14950-a58f-4f18-b6c3-5cfa9dffef4e',
                            'servicetemplate_id'         => '8',
                            'host_id'                    => '1',
                            'name'                       => null,
                            'description'                => null,
                            'command_id'                 => null,
                            'check_command_args'         => '',
                            'eventhandler_command_id'    => null,
                            'notify_period_id'           => null,
                            'check_period_id'            => null,
                            'check_interval'             => null,
                            'retry_interval'             => null,
                            'max_check_attempts'         => null,
                            'first_notification_delay'   => null,
                            'notification_interval'      => null,
                            'notify_on_warning'          => null,
                            'notify_on_unknown'          => null,
                            'notify_on_critical'         => null,
                            'notify_on_recovery'         => null,
                            'notify_on_flapping'         => null,
                            'notify_on_downtime'         => null,
                            'is_volatile'                => null,
                            'flap_detection_enabled'     => null,
                            'flap_detection_on_ok'       => null,
                            'flap_detection_on_warning'  => null,
                            'flap_detection_on_unknown'  => null,
                            'flap_detection_on_critical' => null,
                            'low_flap_threshold'         => null,
                            'high_flap_threshold'        => null,
                            'process_performance_data'   => null,
                            'freshness_checks_enabled'   => null,
                            'freshness_threshold'        => null,
                            'passive_checks_enabled'     => null,
                            'event_handler_enabled'      => null,
                            'active_checks_enabled'      => null,
                            'notifications_enabled'      => null,
                            'notes'                      => null,
                            'priority'                   => null,
                            'tags'                       => null,
                            'own_contacts'               => '0',
                            'own_contactgroups'          => '0',
                            'own_customvariables'        => '0',
                            'service_url'                => null,
                            'service_type'               => '1',
                            'disabled'                   => '0',
                            'created'                    => '2015-01-16 00:46:39',
                            'modified'                   => '2015-01-16 00:46:39',
                        ],
                    'Servicecommandargumentvalue'        =>
                        [],
                    'Serviceeventcommandargumentvalue'   =>
                        [],
                    'ServiceEscalationServiceMembership' =>
                        [],
                    'ServicedependencyServiceMembership' =>
                        [],
                    'Customvariable'                     =>
                        [],
                    'Contactgroup'                       =>
                        [],
                    'Contact'                            =>
                        [],
                    'Servicegroup'                       =>
                        [],
                ],
            2 =>
                [
                    'Service'                            =>
                        [
                            'id'                         => '3',
                            'uuid'                       => '1c045407-5502-4468-aabc-7781f6cf3dec',
                            'servicetemplate_id'         => '9',
                            'host_id'                    => '1',
                            'name'                       => null,
                            'description'                => null,
                            'command_id'                 => null,
                            'check_command_args'         => '',
                            'eventhandler_command_id'    => null,
                            'notify_period_id'           => null,
                            'check_period_id'            => null,
                            'check_interval'             => null,
                            'retry_interval'             => null,
                            'max_check_attempts'         => null,
                            'first_notification_delay'   => null,
                            'notification_interval'      => null,
                            'notify_on_warning'          => null,
                            'notify_on_unknown'          => null,
                            'notify_on_critical'         => null,
                            'notify_on_recovery'         => null,
                            'notify_on_flapping'         => null,
                            'notify_on_downtime'         => null,
                            'is_volatile'                => null,
                            'flap_detection_enabled'     => null,
                            'flap_detection_on_ok'       => null,
                            'flap_detection_on_warning'  => null,
                            'flap_detection_on_unknown'  => null,
                            'flap_detection_on_critical' => null,
                            'low_flap_threshold'         => null,
                            'high_flap_threshold'        => null,
                            'process_performance_data'   => null,
                            'freshness_checks_enabled'   => null,
                            'freshness_threshold'        => null,
                            'passive_checks_enabled'     => null,
                            'event_handler_enabled'      => null,
                            'active_checks_enabled'      => null,
                            'notifications_enabled'      => null,
                            'notes'                      => null,
                            'priority'                   => null,
                            'tags'                       => null,
                            'own_contacts'               => '0',
                            'own_contactgroups'          => '0',
                            'own_customvariables'        => '0',
                            'service_url'                => null,
                            'service_type'               => '1',
                            'disabled'                   => '0',
                            'created'                    => '2015-01-16 00:46:52',
                            'modified'                   => '2015-01-16 00:46:52',
                        ],
                    'Servicecommandargumentvalue'        =>
                        [],
                    'Serviceeventcommandargumentvalue'   =>
                        [],
                    'ServiceEscalationServiceMembership' =>
                        [],
                    'ServicedependencyServiceMembership' =>
                        [],
                    'Customvariable'                     =>
                        [],
                    'Contactgroup'                       =>
                        [],
                    'Contact'                            =>
                        [],
                    'Servicegroup'                       =>
                        [],
                ],
            3 =>
                [
                    'Service'                            =>
                        [
                            'id'                         => '4',
                            'uuid'                       => '7391f1aa-5e2e-447a-8a9b-b23357b9cd2a',
                            'servicetemplate_id'         => '13',
                            'host_id'                    => '1',
                            'name'                       => null,
                            'description'                => null,
                            'command_id'                 => null,
                            'check_command_args'         => '',
                            'eventhandler_command_id'    => null,
                            'notify_period_id'           => null,
                            'check_period_id'            => null,
                            'check_interval'             => null,
                            'retry_interval'             => null,
                            'max_check_attempts'         => null,
                            'first_notification_delay'   => null,
                            'notification_interval'      => null,
                            'notify_on_warning'          => null,
                            'notify_on_unknown'          => null,
                            'notify_on_critical'         => null,
                            'notify_on_recovery'         => null,
                            'notify_on_flapping'         => null,
                            'notify_on_downtime'         => null,
                            'is_volatile'                => null,
                            'flap_detection_enabled'     => null,
                            'flap_detection_on_ok'       => null,
                            'flap_detection_on_warning'  => null,
                            'flap_detection_on_unknown'  => null,
                            'flap_detection_on_critical' => null,
                            'low_flap_threshold'         => null,
                            'high_flap_threshold'        => null,
                            'process_performance_data'   => null,
                            'freshness_checks_enabled'   => null,
                            'freshness_threshold'        => null,
                            'passive_checks_enabled'     => null,
                            'event_handler_enabled'      => null,
                            'active_checks_enabled'      => null,
                            'notifications_enabled'      => null,
                            'notes'                      => null,
                            'priority'                   => null,
                            'tags'                       => null,
                            'own_contacts'               => '0',
                            'own_contactgroups'          => '0',
                            'own_customvariables'        => '0',
                            'service_url'                => null,
                            'service_type'               => '1',
                            'disabled'                   => '0',
                            'created'                    => '2015-01-16 00:47:06',
                            'modified'                   => '2015-01-16 00:47:06',
                        ],
                    'Servicecommandargumentvalue'        =>
                        [],
                    'Serviceeventcommandargumentvalue'   =>
                        [],
                    'ServiceEscalationServiceMembership' =>
                        [],
                    'ServicedependencyServiceMembership' =>
                        [],
                    'Customvariable'                     =>
                        [],
                    'Contactgroup'                       =>
                        [],
                    'Contact'                            =>
                        [],
                    'Servicegroup'                       =>
                        [],
                ],
        ];

        return $data;
    }
}
