<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * Class Hostdependency
 * @deprecated
 */
class Hostdependency extends AppModel {

    public $belongsTo = [
        'Timeperiod' => [
            'dependent'  => true,
            'foreignKey' => 'timeperiod_id',
            'className'  => 'Timeperiod',
        ],
        'Container'  => [
            'foreignKey' => 'container_id',
            'className'  => 'Container',
        ],
    ];

    var $hasMany = [
        'HostdependencyHostMembership'      => [
            'className' => 'HostdependencyHostMembership',
            'dependent' => true,
        ],
        'HostdependencyHostgroupMembership' => [
            'className' => 'HostdependencyHostgroupMembership',
            'dependent' => true,
        ],
    ];

    var $validate = [
        'Host'          => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 host',
                'required' => true,
            ],
        ],
        'HostDependent' => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 host',
                'required' => true,
            ],
        ],
        'container_id'  => [
            'multiple' => [
                'rule'    => ['multiple', ['min' => 1]],
                'message' => 'Please select at least 1 container you attend',
            ],
        ],
    ];

    /**
     * Parse hosts array for hostdependency
     * @deprecated
     * @param Array Host-Ids
     * @param Array Dependent-Host-Ids
     * @return filtered array in format ['host_id' => 1..n, 'dependent' => 0/1]
     */
    public function parseHostMembershipData($hosts = [], $dependent_hosts = []) {
        $host_memberships_for_hostdependency = [];
        foreach ($hosts as $host_id) {
            $host_memberships_for_hostdependency[] = ['host_id' => $host_id, 'dependent' => '0'];
        }
        foreach ($dependent_hosts as $host_id) {
            $host_memberships_for_hostdependency[] = ['host_id' => $host_id, 'dependent' => '1'];
        }

        return $host_memberships_for_hostdependency;
    }

    /**
     * @deprecated
     * Parse hostgroups array for hostdependency
     * @param Array Hostgroup-Ids
     * @param Array Dependent-Hostgroup-Ids
     * @return filtered array in format ['hostgroup_id' => 1..n, 'dependent' => 0/1]
     */
    public function parseHostgroupMembershipData($hostgroups = [], $dependent_hostgroups = []) {
        $hostgroup_memberships_for_hostdependency = [];
        foreach ($hostgroups as $hostgroup_id) {
            $hostgroup_memberships_for_hostdependency[] = ['hostgroup_id' => $hostgroup_id, 'dependent' => '0'];
        }
        foreach ($dependent_hostgroups as $hostgroup_id) {
            $hostgroup_memberships_for_hostdependency[] = ['hostgroup_id' => $hostgroup_id, 'dependent' => '1'];
        }

        return $hostgroup_memberships_for_hostdependency;
    }
}
