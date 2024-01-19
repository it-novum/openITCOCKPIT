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

namespace App\itnovum\openITCOCKPIT\Core\Dashboards;


use itnovum\openITCOCKPIT\Core\Dashboards\DashboardJsonStandardizer;

class HostStatusOverviewExtendedJson extends DashboardJsonStandardizer {

    /**
     * @var array
     * Add new fields to this list
     * oITC will take care of the rest of the work
     */
    protected $fields = [
        'Host'       => [
            'name'          => '',
            'name_regex'    => false,
            'address'       => '',
            'address_regex' => false,
            'keywords'      => '',
            'not_keywords'  => ''
        ],
        'Hoststatus' => [
            'current_state'    => 0,
            'acknowledged'     => false,
            'not_acknowledged' => false,
            'in_downtime'      => false,
            'not_in_downtime'  => false,
            'state_since'      => null,
            'state_since_unit' => 'minutes'
        ],
        'Hostgroup'  => [
            '_ids' => ''
        ]
    ];

    /**
     * @param array $request
     * @return array
     */
    public function standardizedData($request = []) {
        if (isset($request['Hostgroup']['_ids']) && is_array($request['Hostgroup']['_ids'])) {
            $request['Hostgroup']['_ids'] = array_filter(
                $request['Hostgroup']['_ids'], function ($value) {
                return $value > 0;
            });

            // POST request to save to database
            $request['Hostgroup']['_ids'] = implode(',', $request['Hostgroup']['_ids']);
        }
        return $this->_standardizedData($this->fields, $request);
    }
}
