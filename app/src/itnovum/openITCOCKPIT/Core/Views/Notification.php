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

use Cake\I18n\FrozenTime;

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
     * @var UserTime|null
     */
    private $UserTime;

    /**
     * Notification constructor.
     * @param $data
     * @param UserTime|null $UserTime
     */
    public function __construct($data, $UserTime = null) {

        if (isset($data['state'])) {
            $this->state = (int)$data['state'];
        }

        if (isset($data['output'])) {
            $this->output = $data['output'];
        }

        if (isset($data['Contactnotifications']['start_time'])) {
            $this->start_time = $data['Contactnotifications']['start_time'];
        }

        if (isset($data['start_time'])) {
            $this->start_time = $data['start_time'];
        }

        $this->UserTime = $UserTime;
    }

    /**
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @return int|string
     */
    public function getStartTime() {
        if (!is_numeric($this->start_time)) {
            if ($this->start_time instanceof FrozenTime) {
                $this->start_time = $this->start_time->timestamp;
            } else {
                $this->start_time = strtotime($this->start_time);
            }
        }

        return $this->start_time;
    }

    /**
     * @return array
     */
    public function toArray() {
        $arr = get_object_vars($this);
        if (isset($arr['UserTime'])) {
            unset($arr['UserTime']);
        }

        if ($this->UserTime !== null) {
            $arr['start_time'] = $this->UserTime->format($this->getStartTime());
        } else {
            $arr['start_time'] = $this->getStartTime();
        }

        return $arr;
    }

}
