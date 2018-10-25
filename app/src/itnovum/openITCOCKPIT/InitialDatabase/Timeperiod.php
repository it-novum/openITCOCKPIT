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


class Timeperiod extends Importer {
    /**
     * @property \Timeperiod $Model
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
                    'Timeperiod' =>
                        [
                            'id'           => '1',
                            'uuid'         => '41012866-6114-4853-9caf-6ffd19954e50',
                            'container_id' => '1',
                            'name'         => '24x7',
                            'description'  => '24x7',
                            'created'      => '2015-01-05 15:11:46',
                            'modified'     => '2015-01-05 15:11:46',
                        ],
                    'Timerange'  =>
                        [
                            0 =>
                                [
                                    'id'            => '1',
                                    'timeperiod_id' => '1',
                                    'day'           => '1',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            1 =>
                                [
                                    'id'            => '2',
                                    'timeperiod_id' => '1',
                                    'day'           => '2',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            2 =>
                                [
                                    'id'            => '3',
                                    'timeperiod_id' => '1',
                                    'day'           => '3',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            3 =>
                                [
                                    'id'            => '4',
                                    'timeperiod_id' => '1',
                                    'day'           => '4',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            4 =>
                                [
                                    'id'            => '5',
                                    'timeperiod_id' => '1',
                                    'day'           => '5',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            5 =>
                                [
                                    'id'            => '6',
                                    'timeperiod_id' => '1',
                                    'day'           => '6',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                            6 =>
                                [
                                    'id'            => '7',
                                    'timeperiod_id' => '1',
                                    'day'           => '7',
                                    'start'         => '00:00',
                                    'end'           => '24:00',
                                ],
                        ],
                ],
            1 =>
                [
                    'Timeperiod' =>
                        [
                            'id'           => '2',
                            'uuid'         => 'c5251a5e-37f1-4841-b0bd-f801ee8969d4',
                            'container_id' => '1',
                            'name'         => 'none',
                            'description'  => 'none',
                            'created'      => '2015-01-05 15:11:56',
                            'modified'     => '2015-01-05 15:11:56',
                        ],
                    'Timerange'  =>
                        [],
                ],
        ];

        return $data;
    }
}
