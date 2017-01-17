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

class Servicedependency extends AppModel
{

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
        'ServicedependencyServiceMembership'      => [
            'className' => 'ServicedependencyServiceMembership',
            'dependent' => true,
        ],
        'ServicedependencyServicegroupMembership' => [
            'className' => 'ServicedependencyServicegroupMembership',
            'dependent' => true,
        ],
    ];

    var $validate = [
        'Service'          => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 service',
                'required' => true,
            ],
        ],
        'ServiceDependent' => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 service',
                'required' => true,
            ],
        ],
        'container_id'     => [
            'multiple' => [
                'rule'    => ['multiple', ['min' => 1]],
                'message' => 'Please select at least 1 container you attend',
            ],
        ],
    ];

    /*
    * Parse services array for servicedependency
    * @param Array Servoce-Ids
    * @param Array Dependent-Servoce-Ids
    * @return filtered array in format ['servoce_id' => 1..n, 'dependent' => 0/1]
    */
    public function parseServiceMembershipData($services = [], $dependent_services = [])
    {
        $service_memberships_for_servicedependency = [];
        foreach ($services as $service_id) {
            $service_memberships_for_servicedependency[] = ['service_id' => $service_id, 'dependent' => '0'];
        }
        foreach ($dependent_services as $service_id) {
            $service_memberships_for_servicedependency[] = ['service_id' => $service_id, 'dependent' => '1'];
        }

        return $service_memberships_for_servicedependency;
    }

    /*
    * Parse servicegroups array for servicedependency
    * @param Array Servicegroup-Ids
    * @param Array Dependent-Servicegroup-Ids
    * @return filtered array in format ['servicegroup_id' => 1..n, 'dependent' => 0/1]
    */
    public function parseServicegroupMembershipData($servicegroups = [], $dependent_servicegroups = [])
    {
        $servicegroup_memberships_for_servicedependency = [];
        foreach ($servicegroups as $servicegroup_id) {
            $servicegroup_memberships_for_servicedependency[] = ['servicegroup_id' => $servicegroup_id, 'dependent' => '0'];
        }
        foreach ($dependent_servicegroups as $servicegroup_id) {
            $servicegroup_memberships_for_servicedependency[] = ['servicegroup_id' => $servicegroup_id, 'dependent' => '1'];
        }

        return $servicegroup_memberships_for_servicedependency;
    }
}