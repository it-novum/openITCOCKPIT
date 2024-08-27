<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

namespace App\itnovum\openITCOCKPIT\Filter;


use itnovum\openITCOCKPIT\Filter\Filter;

class EventlogsFilter extends Filter {

    /**
     * @return array
     */
    public function indexFilter() {
        $filters = [
            'like'   => [
                'name',
                'user_email',
            ],
            'equals' => [
                'Eventlogs.model',
                'Eventlogs.object_id',
                'Eventlogs.type',
            ]
        ];

        return $this->getConditionsByFilters($filters);
    }

    /**
     * @return false|float|int
     */
    public function getFrom() {
        if ($this->queryHasField('from')) {
            $value = strtotime($this->getQueryFieldValue('from'));
            if ($value) {
                return $value;
            }
        }
        return time() - (3600 * 24 * 30);
    }

    /**
     * @return false|float|int
     */
    public function getTo() {
        if ($this->queryHasField('to')) {
            $value = strtotime($this->getQueryFieldValue('to'));
            if ($value) {
                return $value;
            }
        }
        return time() + (3600 * 24 * 30 * 2);
    }
}
