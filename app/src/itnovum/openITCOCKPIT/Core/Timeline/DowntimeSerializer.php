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


use itnovum\openITCOCKPIT\Core\Views\Statehistory;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class DowntimeSerializer {

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
     * StatehistorySerializer constructor.
     * @param array $statehistoryRecords
     * @param UserTime $UserTime
     */
    public function __construct($statehistoryRecords = [], UserTime $UserTime) {
        $this->records = $statehistoryRecords;
        $this->UserTime = $UserTime;

        $TimelineGroups = new Groups();
        $this->groupId = $TimelineGroups->getDowntimesId();

    }

    public function serialize() {
        $records = [];
        $size = sizeof($this->records);

        for ($i = 0; $i < $size; $i++) {

            $records[] = [
                'start'     => $this->UserTime->customFormat('%Y-%m-%d %H:%M:%S', $this->records[$i]->getScheduledStartTime()),
                'end'       => $this->UserTime->customFormat('%Y-%m-%d %H:%M:%S', $this->records[$i]->getScheduledEndTime()),
                'type'      => 'background',
                'className' => 'bg-color-magenta',
                'content'   => sprintf('%s: %s', $this->records[$i]->getAuthorName(), $this->records[$i]->getCommentData()),
                'title'     => sprintf('%s: %s', $this->records[$i]->getAuthorName(), $this->records[$i]->getCommentData()),

                'group' => $this->groupId
            ];
        }

        return $records;
    }


//{content: 'Downtime', start: '2018-05-28 10:00:00', end: '2018-05-28 11:59:59', type: 'range', className: 'downtime', group: 13},

}
