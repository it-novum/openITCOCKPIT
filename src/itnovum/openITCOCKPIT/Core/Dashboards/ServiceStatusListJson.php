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


class ServiceStatusListJson extends DashboardJsonStandardizer {

    /**
     * @var array
     * Add new fields to this list
     * oITC will take care of the rest of the work
     */
    protected $fields = [
        'Servicestatus'   => [
            'current_state'         => [
                'ok'       => false,
                'warning'  => false,
                'critical' => false,
                'unknown'  => false,
            ],
            'acknowledged'          => false,
            'not_acknowledged'      => false,
            'in_downtime'           => false,
            'not_in_downtime'       => false,
            'output'                => '',
            'state_older_than'      => null,
            'state_older_than_unit' => 'MINUTE'
        ],
        'Host'            => [
            'name'         => '',
            'name_regex'   => false,
            'keywords'     => '',
            'not_keywords' => ''
        ],
        'Service'         => [
            'name'         => '',
            'name_regex'   => false,
            'keywords'     => '',
            'not_keywords' => ''
        ],
        'sort'            => 'Servicestatus.current_state',
        'direction'       => 'desc',
        'useScroll'       => true,
        'scroll_interval' => 30000
    ];

}
