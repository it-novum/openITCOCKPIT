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

class Tenant extends AppModel
{

    public $belongsTo = [
        'Container' => [
            'dependent'  => true,
            'foreignKey' => 'container_id',
            'className'  => 'Container',
        ]];

    public $validate = [
        'max_users'    => [
            'numeric'    => [
                'rule'    => 'numeric',
                'message' => 'This field needs to be numeric.',
            ],
            'ZeroOrMore' => [
                'rule'     => ['comparison', '>=', 0],
                'message'  => 'Invalid value.',
                'required' => true,
            ],
        ],
        'is_active'    => [
            'rule'       => 'boolean',
            'message'    => 'Incorrect value for is_active',
            'required'   => false,
            'allowEmpty' => true,
        ],
    ];

    public function hostCounter($container_id, $operator = null)
    {
        // the foreach is only needed becasue the getTenantByContainer function returns a array with one record like this
        // array($container_id => $container_name)
        foreach ($this->Container->getTenantByContainer($container_id) as $tenant_container_id => $tenant_name) {
            return $this->_counter($operator, 'number_hosts', $tenant_container_id);
        }
    }

    public function serviceCounter($container_id, $operator = null)
    {
        foreach ($this->Container->getTenantByContainer($container_id) as $tenant_container_id => $tenant_name) {
            return $this->_counter($operator, 'number_services', $tenant_container_id);
        }
    }

    public function userCounter($container_id, $operator = null)
    {
        foreach ($this->Container->getTenantByContainer($container_id) as $tenant_container_id => $tenant_name) {
            return $this->_counter($operator, 'number_users', $tenant_container_id);
        }
    }

    protected function _counter($operator, $field, $container_id)
    {
        $count = $this->find('first', [
            'conditions' => ['container_id' => $container_id],
            'fields'     => ['id', 'container_id', $field],
        ]);

        if (in_array($operator, ['+', '++'])) {
            $count['Tenant'][$field]++;
        }

        if (in_array($operator, ['-', '--'])) {
            $count['Tenant'][$field]--;
        }

        $this->save($count);

        return $count['Tenant'][$field];
    }

    public function tenantsByContainerId($container_ids = null, $type = 'all', $index = 'id')
    {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        switch ($type) {
            case 'all':
                return $this->find('all', [
                    'conditions' => [
                        'Tenant.container_id' => $container_ids,
                    ],
                ]);
                break;

            case 'list':
                $results = $this->find('all', [
                    'conditions' => [
                        'Tenant.container_id' => $container_ids,
                    ],
                    'fields'     => [
                        'Tenant.id',
                        'Tenant.container_id',
                        'Container.name',
                    ],
                ]);

                $return = [];
                if ($index == 'id') {
                    foreach ($results as $result) {
                        $return[$result['Tenant']['id']] = $result['Container']['name'];
                    }

                    return $return;
                }

                foreach ($results as $result) {
                    $return[$result['Tenant']['container_id']] = $result['Container']['name'];
                }

                return $return;
                break;
        }
    }

    public function __allowDelete($containerId)
    {
        $Container = ClassRegistry::init('Container');
        $Host = ClassRegistry::init('Host');
        $Service = ClassRegistry::init('Service');
        $children = $Container->children($containerId);

        $newContainerIds = [];
        //get rid of the locations
        foreach ($children as $key => $child) {
            if ($child['Container']['containertype_id'] != CT_LOCATION) {
                $newContainerIds[] = $child['Container']['id'];
            }
        }
        //append the containerID itself
        $newContainerIds[] = $containerId;
        //get the hosts of these containers
        $hostIds = Hash::extract($Host->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Host.container_id' => $newContainerIds,
            ],
            'fields'     => [
                'Host.id',
            ],
        ]), '{n}.Host.id');

        $serviceIds = Hash::extract($Service->find('all', [
            'conditions' => [
                'Service.host_id' => $hostIds,
            ],
            'fields'     => [
                'Service.id',
            ],
        ]), '{n}.Service.id');

        //check if the host is used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            $evcCount = $this->Eventcorrelation->find('all', [
                'conditions' => [
                    'OR' => [
                        'Eventcorrelation.host_id'    => $hostIds,
                        'Eventcorrelation.service_id' => $serviceIds,
                    ],

                ],
            ]);

            if (!empty($evcCount) && $evcCount > 0) {
                return false;
            }

            return true;
        }

        return true;
    }
}
