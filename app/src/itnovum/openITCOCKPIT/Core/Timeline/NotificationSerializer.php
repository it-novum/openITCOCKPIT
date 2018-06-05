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

namespace itnovum\openITCOCKPIT\Core\Timeline;


use itnovum\openITCOCKPIT\Core\Views\UserTime;

class NotificationSerializer {

    /**
     * @var array
     */
    private $records;

    /**
     * @var UserTime
     */
    private $UserTime;


    /**
     * @var int
     */
    private $groupId;


    /**
     * NotificationSerializer constructor.
     * @param array $notificationRecords
     * @param UserTime $UserTime
     */
    public function __construct($notificationRecords = [], UserTime $UserTime) {
        $this->records = $notificationRecords;
        $this->UserTime = $UserTime;

        $TimelineGroups = new Groups();
        $this->groupId = $TimelineGroups->getNotificationsId();

    }

    public function serialize() {
        $records = [];
        $size = sizeof($this->records);

        for ($i = 0; $i < $size; $i++) {
            $title = sprintf(
                '<b>%s</b> via <b>%s</b> at %s',
                h($this->records[$i]['Contact']->getName()),
                h($this->records[$i]['Command']->getName()),
                $this->UserTime->format($this->records[$i]['NotificationHost']->getStartTime())
            );

            $records[] = [
                'start'     => $this->UserTime->customFormat('%Y-%m-%d %H:%M:%S', $this->records[$i]['NotificationHost']->getStartTime()),
                'type'      => 'box',
                'className' => 'orange',
                'content'   => '<i class="fa fa-envelope"></i>',
                'title'     => $title,
                'group'     => $this->groupId
            ];
        }

        return $records;
    }
}
