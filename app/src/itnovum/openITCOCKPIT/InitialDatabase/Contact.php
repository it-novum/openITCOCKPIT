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


class Contact extends Importer {
    /**
     * @property \Contact $Model
     */

    /**
     * @return bool
     */
    public function import() {
        if ($this->isTableEmpty()) {
            $data = $this->getData();
            foreach ($data as $record) {
                $this->Model->create();
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
                    'Contact'   =>
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
                            'HostCommands'                  => [1],
                            'ServiceCommands'               => [2],
                        ],
                    'Container' =>
                        [
                            0 =>
                                [
                                    'id'                  => '1',
                                    'containertype_id'    => '1',
                                    'name'                => 'root',
                                    'parent_id'           => null,
                                    'lft'                 => '1',
                                    'rght'                => '2',
                                    'ContactsToContainer' =>
                                        [
                                            'id'           => '2',
                                            'contact_id'   => '1',
                                            'container_id' => '1',
                                        ],
                                ],
                        ],
                ],
        ];

        return $data;
    }
}
