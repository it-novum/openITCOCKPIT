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

class Serviceescalation extends AppModel
{
    var $hasAndBelongsToMany = [
        'Contactgroup' => [
            'className'             => 'Contactgroup',
            'joinTable'             => 'contactgroups_to_serviceescalations',
            'foreignKey'            => 'serviceescalation_id',
            'associationForeignKey' => 'contactgroup_id',
            'unique'                => true,
            'dependent'             => true,
        ],
        'Contact'      => [
            'className'             => 'Contact',
            'joinTable'             => 'contacts_to_serviceescalations',
            'foreignKey'            => 'serviceescalation_id',
            'associationForeignKey' => 'contact_id',
            'unique'                => true,
            'dependent'             => true,
        ],
    ];
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
        'ServiceescalationServiceMembership'      => [
            'className' => 'ServiceescalationServiceMembership',
            'dependent' => true,
        ],
        'ServiceescalationServicegroupMembership' => [
            'className' => 'ServiceescalationServicegroupMembership',
            'dependent' => true,
        ],
    ];

    var $validate = [
        'Service'               => [
            'multiple' => [
                'rule'     => ['multiple', ['min' => 1]],
                'message'  => 'Please select at least 1 service',
                'required' => true,
            ],
        ],
        'first_notification'    => [
            'numeric'                                 => [
                'rule'       => 'numeric',
                'message'    => 'This value needs to be numeric',
                'required'   => true,
                'allowEmpty' => false,
            ],
            'firstNotificationBeforeLastNotification' => [
                'rule'    => ['firstNotificationBeforeLastNotification', 'last_notification'],
                'message' => 'The first notification must be before the last notification.',
            ],
            'notNegative'                             => [
                'rule'    => ['comparison', '>=', 0],
                'message' => 'This value needs to be greate then 0',
            ],

        ],
        'last_notification'     => [
            'numeric'     => [
                'rule'       => 'numeric',
                'message'    => 'This value needs to be numeric',
                'required'   => true,
                'allowEmpty' => false,
            ],
            'notNegative' => [
                'rule'    => ['comparison', '>=', 0],
                'message' => 'This value needs to be greate then 0',
            ],

        ],
        'notification_interval' => [
            'numeric'     => [
                'rule'       => 'numeric',
                'message'    => 'This value needs to be numeric',
                'required'   => true,
                'allowEmpty' => false,
            ],
            'notNegative' => [
                'rule'    => ['comparison', '>=', 0],
                'message' => 'This value needs to be greate then 0',
            ],

        ],
        'timeperiod_id'         => [
            'notBlank' => [
                'allowEmpty' => false,
                'rule'       => 'notBlank',
                'message'    => 'This field cannot be left blank.',
                'required'   => true,
            ],
        ],

        'Contact'      => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one contact or contact group.',
                'required' => true,
            ],
        ],
        'Contactgroup' => [
            'atLeastOne' => [
                'rule'     => ['atLeastOne'],
                'message'  => 'You must specify at least one contact or contact group',
                'required' => true,
            ],
        ],
        'container_id' => [
            'multiple' => [
                'rule'    => ['multiple', ['min' => 1]],
                'message' => 'Please select at least 1 container you attend',
            ],
        ],
    ];

    /*
    Custom validation rule for contact and/or contactgroup fields
    */
    public function atLeastOne($data)
    {
        return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
    }

    /*
    Custom validation rule first_notification
    */
    public function firstNotificationBeforeLastNotification($field = [], $compare_field = null)
    {
        foreach ($field as $key => $value) {
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];
            if (($v1 > $v2) && $v2 != 0) {
                return false;
            } else {
                continue;
            }
        }

        return true;
    }

    /*
    * Parse services array for serviceescalation
    * @param Array Service-Ids
    * @param Array Service-Ids exluded
    * @return filtered array in format ['service_id' => 1..n, 'exluded' => 0/1]
    */
    public function parseServiceMembershipData($services = [], $services_exluded = [])
    {
        $service_memberships_for_serviceescalation = [];
        foreach ($services as $service_id) {
            $service_memberships_for_serviceescalation[] = ['service_id' => $service_id, 'excluded' => '0'];
        }
        foreach ($services_exluded as $service_id) {
            $service_memberships_for_serviceescalation[] = ['service_id' => $service_id, 'excluded' => '1'];
        }

        return $service_memberships_for_serviceescalation;
    }

    /*
    * Parse servicegroups array for serviceescalation
    * @param Array Servicegroup-Ids
    * @param Array Servicegroup-Ids exluded
    * @return filtered array in format ['servicegroup_id' => 1..n, 'exluded' => 0/1]
    */
    public function parseServicegroupMembershipData($servicegroups = [], $servicegroups_exluded = [])
    {
        $servicegroup_memberships_for_serviceescalation = [];
        foreach ($servicegroups as $servicegroup_id) {
            $servicegroup_memberships_for_serviceescalation[] = ['servicegroup_id' => $servicegroup_id, 'excluded' => '0'];
        }
        foreach ($servicegroups_exluded as $servicegroup_id) {
            $servicegroup_memberships_for_serviceescalation[] = ['servicegroup_id' => $servicegroup_id, 'excluded' => '1'];
        }

        return $servicegroup_memberships_for_serviceescalation;
    }
}
