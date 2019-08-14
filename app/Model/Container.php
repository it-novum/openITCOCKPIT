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


/**
 * Class Container
 * @deprecated
 * @mixin TreeBehavior
 */
class Container extends AppModel {
    public $actsAs = ['Tree'];

    var $validate = [
        'name'      => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => ['isUniqueByObject'],
                // Do not change this message without changing the Migration script as well! Otherwise it breaks it's
                // compatibility.
                'message' => 'This name already exists.',
            ],
        ],
        'parent_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'notZero'  => [
                'rule'     => ['comparison', '>', 0],
                'message'  => 'Invalid container.',
                'required' => true,
            ],
        ],
    ];

    var $hasMany = [
        'Tenant' => [
            'className'  => 'Tenant',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],

        'Contactgroup'            => [
            'className'  => 'Contactgroup',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Location'                => [
            'className'  => 'Location',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Hosttemplate'            => [
            'className'  => 'Hosttemplate',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Servicetemplate'         => [
            'className'  => 'Servicetemplate',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Hostgroup'               => [
            'className'  => 'Hostgroup',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Servicegroup'            => [
            'className'  => 'Servicegroup',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Servicetemplategroup'    => [
            'className'  => 'Servicetemplategroup',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Calendar'                => [
            'className'  => 'Calendar',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'ContainerUserMembership' => [
            'className'  => 'ContainerUserMembership',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Host'                    => [
            'className'  => 'Host',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
        'Timeperiod'              => [
            'className'  => 'Timeperiod',
            'foreignKey' => 'container_id',
            'dependent'  => true,
        ],
    ];

    public $hasAndBelongsToMany = [
        'Contact' => [
            'joinTable'  => 'contacts_to_containers',
            'foreignKey' => 'container_id'
        ]

    ];
    /*
        public $hasAndBelongsToMany = [
            'User' => [
                'joinTable' => 'users_to_containers',
                'foreignKey' => 'container_id',
                'unique' => true,
            ]
        ];
    */

    var $name = 'Container';

    /**
     * @return bool
     * @deprecated
     */
    public function isUniqueByObject() {
        if (isset($this->data['Container']['containertype_id']) && in_array($this->data['Container']['containertype_id'], [CT_TENANT])) {
            //return $this->isUnique('name');

            $result = $this->find('count', [
                'recursive'  => -1,
                'conditions' => [
                    'name'             => $this->data['Container']['name'],
                    'containertype_id' => [CT_TENANT],
                ],
            ]);

            // Our result is 0 so it's impossible that a tenant with the name already exists
            if ($result === 0) {
                return true;
            }

            if ($result === 1 && isset($this->data['Container']['id'])) {
                // We found a result to our conditions!!!
                // No we need to check, if this is a tenant with the same name,
                // or just our own racord that we want to update...
                if ($this->find('count', [
                        'recursive'  => -1,
                        'conditions' => [
                            'name'             => $this->data['Container']['name'],
                            'containertype_id' => [CT_TENANT],
                            'id'               => $this->data['Container']['id'],
                        ],
                    ]) === 1
                ) {
                    return true;
                }
            }

            return false;
        }

        return true;

    }


    /**
     * @param $id
     * @return bool
     * @deprecated
     */
    public function __delete($id) {
        return $this->delete($id, true);
    }

    /**
     * @param $hostIds
     * @return bool
     * @deprecated
     */
    public function __allowDelete($hostIds) {
        if (empty($hostIds)) {
            return true;
        }
        //check if the hosts are used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $Service = ClassRegistry::init('Service');
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            $serviceIds = Hash::extract($Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'host_id' => $hostIds,
                ],
                'fields'     => [
                    'Service.id',
                ],
            ]), '{n}.Service.id');
            $evcCount = $this->Eventcorrelation->find('count', [
                'conditions' => [
                    'OR' => [
                        'Eventcorrelation.host_id'    => $hostIds,
                        'Eventcorrelation.service_id' => $serviceIds,
                    ],

                ],
            ]);

            if ($evcCount > 0) {
                return false;
            }

            return true;
        }

        return true;
    }

}

