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


class Hosttemplate extends Importer {
    /**
     * @property \Hosttemplate $Model
     */

    /**
     * @return bool
     */
    public function import() {
        if ($this->isTableEmpty()) {
            $data = $this->getData();
            foreach ($data as $record) {
                $this->Model->create();

                $record['Hosttemplate']['Contact'] = $record['Contact'];
                $record['Hosttemplate']['Contactgroup'] = $record['Contactgroup'];

                $this->Model->saveAll($record);
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getData() {
        $data = [
            0 =>
                [
                    'Hosttemplate'                     =>
                        [
                            'id'                            => '1',
                            'uuid'                          => 'efbee68c-cf48-4b78-83f5-c856c56177f0',
                            'name'                          => 'default host',
                            'description'                   => 'default host',
                            'hosttemplatetype_id'           => '1',
                            'command_id'                    => '4',
                            'check_command_args'            => '',
                            'eventhandler_command_id'       => '0',
                            'timeperiod_id'                 => '0',
                            'check_interval'                => '7200',
                            'retry_interval'                => '60',
                            'max_check_attempts'            => '3',
                            'first_notification_delay'      => '0',
                            'notification_interval'         => '7200',
                            'notify_on_down'                => '1',
                            'notify_on_unreachable'         => '1',
                            'notify_on_recovery'            => '1',
                            'notify_on_flapping'            => '0',
                            'notify_on_downtime'            => '0',
                            'flap_detection_enabled'        => '0',
                            'flap_detection_on_up'          => '0',
                            'flap_detection_on_down'        => '0',
                            'flap_detection_on_unreachable' => '0',
                            'low_flap_threshold'            => '0',
                            'high_flap_threshold'           => '0',
                            'process_performance_data'      => '0',
                            'freshness_checks_enabled'      => '0',
                            'freshness_threshold'           => '0',
                            'passive_checks_enabled'        => '0',
                            'event_handler_enabled'         => '0',
                            'active_checks_enabled'         => '1',
                            'retain_status_information'     => '0',
                            'retain_nonstatus_information'  => '0',
                            'notifications_enabled'         => '0',
                            'notes'                         => '',
                            'priority'                      => '1',
                            'check_period_id'               => '1',
                            'notify_period_id'              => '1',
                            'tags'                          => '',
                            'container_id'                  => '1',
                            'host_url'                      => '',
                            'created'                       => '2015-01-05 15:22:21',
                            'modified'                      => '2015-01-05 15:22:21',
                        ],
                    'Customvariable'                   =>
                        [],
                    'Hosttemplatecommandargumentvalue' =>
                        [
                            0 =>
                                [
                                    'id'                 => '1',
                                    'commandargument_id' => '3',
                                    'hosttemplate_id'    => '1',
                                    'value'              => '3000.0,80%',
                                    'created'            => '2015-01-05 15:22:21',
                                    'modified'           => '2015-01-05 15:22:21',
                                ],
                            1 =>
                                [
                                    'id'                 => '2',
                                    'commandargument_id' => '4',
                                    'hosttemplate_id'    => '1',
                                    'value'              => '5000.0,100%',
                                    'created'            => '2015-01-05 15:22:21',
                                    'modified'           => '2015-01-05 15:22:21',
                                ],
                        ],
                    'Contactgroup'                     =>
                        [],
                    'Contact'                          =>
                        [
                            0 =>
                                [
                                    'id'                            => '1',
                                    'uuid'                          => '152aecaf-e981-4b0b-8e05-86972868547d',
                                    'name'                          => 'info',
                                    'description'                   => 'info contact',
                                    'email'                         => 'openitcockpit@localhost.local',
                                    'phone'                         => '',
                                    'host_timeperiod_id'            => '1',
                                    'service_timeperiod_id'         => '1',
                                    'host_notifications_enabled'    => '1',
                                    'service_notifications_enabled' => '1',
                                    'notify_service_recovery'       => '1',
                                    'notify_service_warning'        => '1',
                                    'notify_service_unknown'        => '1',
                                    'notify_service_critical'       => '1',
                                    'notify_service_flapping'       => '0',
                                    'notify_service_downtime'       => '0',
                                    'notify_host_recovery'          => '1',
                                    'notify_host_down'              => '1',
                                    'notify_host_unreachable'       => '1',
                                    'notify_host_flapping'          => '0',
                                    'notify_host_downtime'          => '0',
                                    'ContactsToHosttemplate'        =>
                                        [
                                            'id'              => '1',
                                            'contact_id'      => '1',
                                            'hosttemplate_id' => '1',
                                        ],
                                ],
                        ],
                ],
        ];

        return $data;
    }
}
