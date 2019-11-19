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

use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;

class Tenant extends AppModel {

    public $belongsTo = [
        'Container' => [
            'dependent'  => true,
            'foreignKey' => 'container_id',
            'className'  => 'Container',
        ]
    ];

    public $validate = [
        'max_users' => [
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
        'is_active' => [
            'rule'       => 'boolean',
            'message'    => 'Incorrect value for is_active',
            'required'   => false,
            'allowEmpty' => true,
        ],
    ];

    /**
     * @param null $container_ids
     * @param string $type
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function tenantsByContainerId($container_ids = null, $type = 'all', $index = 'id') {
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

    /**
     * @param $containerId
     * @return bool
     * @deprecated
     */
    public function __allowDelete($containerId) {
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');


        $Container = ClassRegistry::init('Container');
        $Host = ClassRegistry::init('Host');
        $Service = ClassRegistry::init('Service');
        $children = $ContainersTable->getChildren($containerId);

        $newContainerIds = [];
        //get rid of the locations
        foreach ($children as $child) {
            if ($child['containertype_id'] != CT_LOCATION) {
                $newContainerIds[] = $child['id'];
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
