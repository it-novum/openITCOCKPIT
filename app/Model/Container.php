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
 * Class Container
 * @mixin TreeBehavior
 */
class Container extends AppModel {
    public $actsAs = ['Tree'];

    var $validate = [
        'name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule' => ['isUniqueByObject'],
                // Do not change this message without changing the Migration script as well! Otherwise it breaks it's
                // compatibility.
                'message' => 'This name already exists.',
            ],
        ],
        'parent_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'This field cannot be left blank.',
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
            'className' => 'Tenant',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],

        'Contactgroup' => [
            'className' => 'Contactgroup',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Location' => [
            'className' => 'Location',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Hosttemplate' => [
            'className' => 'Hosttemplate',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Hostgroup' => [
            'className' => 'Hostgroup',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Servicegroup' => [
            'className' => 'Servicegroup',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Servicetemplategroup' => [
            'className' => 'Servicetemplategroup',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Calendar' => [
            'className' => 'Calendar',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'ContainerUserMembership' => [
            'className' => 'ContainerUserMembership',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Host' => [
            'className' => 'Host',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],
        'Timeperiod' => [
            'className' => 'Timeperiod',
            'foreignKey' => 'container_id',
            'dependent' => true,
        ],

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
     * Returns the name and id of the tenant that is the owner of the given container_id
     * Example:
     * $id = 5
     * Return:
     * [$tenant_contaier_id => tenant name]
     *
     * @param int $containerId of the container
     *
     * @return array with all path [$tenant_contaier_id] => $tenant_name
     */
    public function getTenantByContainer($containerId) {
        $exists = Cache::remember('ContainerExists:' . $containerId, function () use ($containerId) {
            return $this->exists($containerId);
        }, 'migration');
        if (!$exists) {
            return null;
        }
        $container = Cache::remember('ContainerGetTenantByContainer:' . $containerId, function () use ($containerId) {
            return $this->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'id' => $containerId,
                ],
            ], 'migration');
        });

        $possibleContainerTypes = [CT_GLOBAL, CT_TENANT];
        while (!in_array($container['Container']['containertype_id'], $possibleContainerTypes)) {
            $container = Cache::remember('ContainerGetTenantByContainerParentNode:' . md5(json_encode($container)), function () use ($container) {
                return $this->getParentNode($container['Container']['id']);
            }, 'migration');
        }

        return [$container['Container']['id'] => $container['Container']['name']];
    }

    public function findTenantByContainer($container_id) {
        return $this->getTenantByContainer($container_id);
    }


    public function isUniqueByObject() {
        if (isset($this->data['Container']['containertype_id']) && in_array($this->data['Container']['containertype_id'], [CT_TENANT])) {
            //return $this->isUnique('name');

            $result = $this->find('count', [
                'recursive' => -1,
                'conditions' => [
                    'name' => $this->data['Container']['name'],
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
                        'recursive' => -1,
                        'conditions' => [
                            'name' => $this->data['Container']['name'],
                            'containertype_id' => [CT_TENANT],
                            'id' => $this->data['Container']['id'],
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
     * Returns an array of $type with all Contactgroups
     *
     * @param string $type for find (all, first, list,...)
     *
     * @return array with all contactgroups
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function findContactgroup($type = 'all') {
        return $this->find($type, [
            'conditions' => [
                'containertype_id' => CT_CONTACTGROUP,
            ],
        ]);
    }

    public function __delete($id) {
        return $this->delete($id, true);
    }

    public function __allowDelete($hostIds) {
        if (empty($hostIds)) {
            return true;
        }
        //check if the hosts are used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $Service = ClassRegistry::init('Service');
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            $serviceIds = Hash::extract($Service->find('all', [
                'recursive' => -1,
                'conditions' => [
                    'host_id' => $hostIds,
                ],
                'fields' => [
                    'Service.id',
                ],
            ]), '{n}.Service.id');
            $evcCount = $this->Eventcorrelation->find('count', [
                'conditions' => [
                    'OR' => [
                        'Eventcorrelation.host_id' => $hostIds,
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
