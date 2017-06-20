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

namespace itnovum\openITCOCKPIT\Core\Views;


abstract class Notification {

    /**
     * @var int
     */
    private $state;

    /**
     * @var string
     */
    private $output;

    /**
     * @var int|string
     */
    private $start_time;


    /**
     * Notification constructor.
     * @param array $data
     */
    public function __construct($data, $key){

        if (isset($data[$key]['state'])) {
            $this->state = (int)$data[$key]['state'];
        }

        if (isset($data[$key]['output'])) {
            $this->output = $data[$key]['output'];
        }

        if (isset($data['Contactnotification']['start_time'])) {
            $this->start_time = $data['Contactnotification']['start_time'];
        }

        if (isset($data[$key]['start_time'])) {
            $this->start_time = $data[$key]['start_time'];
        }

    }

    /**
     * @return int
     */
    public function getState(){
        return $this->state;
    }

    /**
     * @return string
     */
    public function getOutput(){
        return $this->output;
    }

    /**
     * @return int|string
     */
    public function getStartTime(){
        return $this->start_time;
    }

}
