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


class Cronjob extends Importer {
    /**
     * @property \Cronjob $Model
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
                    'Cronjob'      =>
                        [
                            'id'       => '1',
                            'task'     => 'CleanupTemp',
                            'plugin'   => 'Core',
                            'interval' => '10',
                        ],
                    'Cronschedule' =>
                        [
                            'id'         => '1',
                            'cronjob_id' => '1',
                            'is_running' => '0',
                            'start_time' => '2016-09-27 11:07:02',
                            'end_time'   => '2016-09-27 11:07:02',
                        ],
                ],
            1 =>
                [
                    'Cronjob'      =>
                        [
                            'id'       => '2',
                            'task'     => 'DatabaseCleanup',
                            'plugin'   => 'Core',
                            'interval' => '1440',
                        ],
                    'Cronschedule' =>
                        [
                            'id'         => '2',
                            'cronjob_id' => '2',
                            'is_running' => '0',
                            'start_time' => '2015-01-16 00:43:01',
                            'end_time'   => '2015-01-16 00:43:02',
                        ],
                ],
            2 =>
                [
                    'Cronjob'      =>
                        [
                            'id'       => '3',
                            'task'     => 'RecurringDowntimes',
                            'plugin'   => 'Core',
                            'interval' => '10',
                        ],
                    'Cronschedule' =>
                        [
                            'id'         => '3',
                            'cronjob_id' => '3',
                            'is_running' => '0',
                            'start_time' => '2015-01-16 00:43:02',
                            'end_time'   => '2015-01-16 00:43:02',
                        ],
                ],
            3 =>
                [
                    'Cronjob'      =>
                        [
                            'id'       => '4',
                            'task'     => 'InstantReport',
                            'plugin'   => 'Core',
                            'interval' => '1440',
                        ],
                    'Cronschedule' =>
                        [
                            'id'         => '4',
                            'cronjob_id' => '4',
                            'is_running' => '0',
                            'start_time' => '2015-01-16 00:43:02',
                            'end_time'   => '2015-01-16 00:43:02',
                        ],
                ],
        ];

        return $data;
    }
}
