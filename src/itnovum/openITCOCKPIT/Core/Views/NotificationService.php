<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

namespace itnovum\openITCOCKPIT\Core\Views;


class NotificationService extends Notification {


    /**
     * @var bool
     */
    private $is_service_notification;

    /**
     * @var integer
     */
    private $count;


    /**
     * NotificationService constructor.
     * @param $data
     * @param null $UserTime
     */
    public function __construct($data, $UserTime = null) {
        parent::__construct($data, $UserTime);
        if (isset($data['notification_type'])) {
            $this->is_service_notification = (int)$data['notification_type'] === 1;
        }
        if (isset($data['count'])) {
            //counted notifications for this.host in the configured period
            $this->count = (int)$data['count'];
        }
    }

    /**
     * @return boolean
     */
    public function isServiceNotification() {
        return $this->is_service_notification;
    }

    /**
     * @return integer
     */
    public function getCount() {
        return $this->count;
    }

}
