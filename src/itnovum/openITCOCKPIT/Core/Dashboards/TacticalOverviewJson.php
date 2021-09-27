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

namespace itnovum\openITCOCKPIT\Core\Dashboards;


class TacticalOverviewJson extends DashboardJsonStandardizer {

    /**
     * @var array
     * Add new fields to this list
     * oITC will take care of the rest of the work
     */
    protected $fields = [
        'Host'         => [
            'name' => ''
        ],
        'Service'      => [
            'servicename'  => '',
            'keywords'     => '',
            'not_keywords' => ''
        ],
        'Hostgroup'    => [
            '_ids' => ''
        ],
        'Servicegroup' => [
            '_ids' => ''
        ]
    ];

    /**
     * @param array $request
     * @return array
     */
    public function standardizedData($request = []) {
        if (isset($request['Hostgroup']['_ids']) && is_array($request['Hostgroup']['_ids'])) {
            // POST request to save to database
            $request['Hostgroup']['_ids'] = implode(',', $request['Hostgroup']['_ids']);
        }
        if (isset($request['Servicegroup']['_ids']) && is_array($request['Servicegroup']['_ids'])) {
            // POST request to save to database
            $request['Servicegroup']['_ids'] = implode(',', $request['Servicegroup']['_ids']);
        }
        return $this->_standardizedData($this->fields, $request);
    }
}
