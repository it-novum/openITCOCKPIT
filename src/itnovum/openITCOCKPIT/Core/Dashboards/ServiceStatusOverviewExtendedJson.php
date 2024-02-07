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

class ServiceStatusOverviewExtendedJson extends DashboardJsonStandardizer {

    /**
     * @var array
     * Add new fields to this list
     * oITC will take care of the rest of the work
     */
    protected $fields = [
        'Servicestatus' => [
            'current_state'         => 0,
            'acknowledged'          => false,
            'not_acknowledged'      => false,
            'in_downtime'           => false,
            'not_in_downtime'       => false,
            'state_older_than'      => null,
            'state_older_than_unit' => 'MINUTE'
        ],
        'Host'          => [
            'name'         => '',
            'name_regex'   => false,
            'keywords'     => '',
            'not_keywords' => ''
        ],
        'Service'       => [
            'servicename'       => '',
            'servicename_regex' => false,
            'keywords'          => '',
            'not_keywords'      => ''
        ],
        'Servicegroup'  => [
            '_ids' => ''
        ]
    ];

    /**
     * @param array $request
     * @return array
     */
    public function standardizedData($request = []) {
        if (isset($request['Servicegroup']['_ids']) && is_array($request['Servicegroup']['_ids'])) {
            $request['Servicegroup']['_ids'] = array_filter(
                $request['Servicegroup']['_ids'], function ($value) {
                return $value > 0;
            });
            // POST request to save to database
            $request['Servicegroup']['_ids'] = implode(',', $request['Servicegroup']['_ids']);
        }
        if (empty($request['Host']['name'])) {
            $request['Host']['name_regex'] = false;
        }
        if (empty($request['Service']['servicename'])) {
            $request['Service']['servicename_regex'] = false;
        }
        return $this->_standardizedData($this->fields, $request);
    }
}
