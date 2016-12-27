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


class Usergroup extends Importer
{
    /**
     * @property \Usergroup $Model
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
                    'Usergroup' =>
                        [
                            'id'          => '1',
                            'name'        => 'Administrator',
                            'description' => '',
                            'created'     => '2015-08-19 14:57:42',
                            'modified'    => '2015-08-19 14:57:42',
                        ],
                    'User'      =>
                        [],
                ],
            1 =>
                [
                    'Usergroup' =>
                        [
                            'id'          => '2',
                            'name'        => 'Viewer',
                            'description' => '',
                            'created'     => '2015-08-19 15:00:36',
                            'modified'    => '2015-08-19 15:00:36',
                        ],
                    'User'      =>
                        [],
                ],
        ];

        return $data;
    }
}
