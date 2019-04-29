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


/* Notice:
 * A a hostgroup is not a stand alone object, its still a container
 * a host belongsTo a container (many to one)
 */

use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;

/**
 * Class Hostgroup
 * @deprecated
 */
class Hostgroup extends AppModel {

    public $belongsTo = [
        'Container' => [
            'foreignKey' => 'container_id',
            'className'  => 'Container',
        ]
    ];

    public $hasAndBelongsToMany = [
        'Host'         => [
            'joinTable'  => 'hosts_to_hostgroups',
            'foreignKey' => 'hostgroup_id',
            'unique'     => true,
            'dependent'  => true,
        ],
        'Hosttemplate' => [
            'joinTable'  => 'hosttemplates_to_hostgroups',
            'foreignKey' => 'hostgroup_id',
            'unique'     => true,
            'dependent'  => true,
        ],
    ];

    public $validate = [
        'hostgroup_url' => [
            'rule'       => 'url',
            'allowEmpty' => true,
            'required'   => false,
            'message'    => 'Not a valid URL format',
        ],
    ];

    /**
     * Hostgroup constructor.
     * @param bool $id
     * @param null $table
     * @param null $ds
     * @deprecated
     */
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->Host = ClassRegistry::init('Host');
    }

    /**
     * @param array $container_ids
     * @param string $type
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function hostgroupsByContainerId($container_ids = [], $type = 'all', $index = 'container_id') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $tenantContainerIds = [];

        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {
                // Get contaier id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'HostgroupHostgroupsByContainerId');

                $tenantContainerIds[] = $path[1]['Container']['id'];
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);
        $containerIds = array_unique(array_merge($tenantContainerIds, $container_ids));

        switch ($type) {
            case 'all':
                return $this->find('all', [
                    'recursive' => -1,
                    'contain'   => [
                        'Container' => [
                            'conditions' => [
                                'Container.parent_id'        => $containerIds,
                                'Container.containertype_id' => CT_HOSTGROUP
                            ],
                        ],
                    ],
                    'order'     => [
                        'Container.name' => 'ASC',
                    ],
                ]);

            default:
                $result = $this->find('all', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'order'      => [
                        'Container.name' => 'ASC',
                    ],
                    'fields'     => [
                        'Hostgroup.id',
                        'Hostgroup.container_id',
                    ],
                    'conditions' => [
                        'Container.parent_id'        => $containerIds,
                        'Container.containertype_id' => CT_HOSTGROUP
                    ],
                ]);

                $return = [];
                foreach ($result as $hostgroup) {
                    if ($index == 'id') {
                        $return[$hostgroup['Hostgroup']['id']] = $hostgroup['Container']['name'];
                    } else {
                        $return[$hostgroup['Hostgroup']['container_id']] = $hostgroup['Container']['name'];
                    }

                }

                return $return;
        }
    }

    /**
     * @param array $container_ids
     * @param string $type
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function hostgroupsByContainerIdNoTenantLookup($container_ids = [], $type = 'all', $index = 'container_id') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }
        $container_ids = array_unique($container_ids);
        switch ($type) {
            case 'all':
                return $this->find('all', [
                    'recursive' => -1,
                    'contain'   => [
                        'Container' => [
                            'conditions' => [
                                'Container.id' => $container_ids,
                            ],
                        ],
                    ],
                    'order'     => [
                        'Container.name' => 'ASC',
                    ],
                ]);

            default:
                if ($index == 'id') {
                    $result = $this->find('all', [
                        'recursive'  => -1,
                        'contain'    => [
                            'Container' => [
                                'fields' => [
                                    'Container.name',
                                ],
                            ],
                        ],
                        'order'      => [
                            'Container.name' => 'ASC',
                        ],
                        'fields'     => [
                            'Hostgroup.id',
                            'Hostgroup.container_id',
                        ],
                        'conditions' => [
                            'Hostgroup.container_id' => $container_ids,
                        ],
                    ]);

                    $return = [];
                    foreach ($result as $hostgroup) {
                        $return[$hostgroup['Hostgroup']['id']] = $hostgroup['Container']['name'];
                    }

                    return $return;
                }
                asort($hostgroupsAsList);

                return $hostgroupsAsList;
        }

        return [];
    }


    /**
     * @param string $type
     * @param array $options
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function findHostgroups($type = 'all', $options = [], $index = 'id') {
        if ($type == 'all') {
            return $this->find('all', $options);
        }

        $return = [];
        if ($type == 'list') {
            $results = $this->find('all', $options);
            foreach ($results as $result) {
                $return[$result['Hostgroup'][$index]] = $result['Container']['name'];
            }
        }
        return $return;
    }

    /**
     * @param array $options
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function findList($options = [], $index = 'id') {
        return $this->findHostgroups('list', $options, $index);
    }

    /**
     * @param HostgroupConditions $HostgroupConditions
     * @param array $selected
     * @return array|null
     * @deprecated
     */
    public function getHostgroupsForAngular(HostgroupConditions $HostgroupConditions, $selected = []) {
        $query = [
            'recursive'  => -1,
            'fields'     => 'Container.name',
            'joins'      => [
                [
                    'table'      => 'containers',
                    'alias'      => 'Container',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Hostgroup.container_id = Container.id',
                    ],
                ],
            ],
            'conditions' => $HostgroupConditions->getConditionsForFind(),
            'order'      => [
                'Container.name' => 'ASC',
            ],
            'group'      => [
                'Container.id'
            ],
            'limit'      => self::ITN_AJAX_LIMIT
        ];
        if (is_array($selected)) {
            $selected = array_filter($selected);
        }
        if (!empty($selected)) {
            $query['conditions']['NOT'] = ['Hostgroup.id' => $selected];
        }
        $hostgroupsWithLimit = $this->find('list', $query);
        $selectedHostgroups = [];
        if (!empty($selected)) {
            $query = [
                'recursive'  => -1,
                'fields'     => 'Container.name',
                'joins'      => [
                    [
                        'table'      => 'containers',
                        'alias'      => 'Container',
                        'type'       => 'INNER',
                        'conditions' => [
                            'Hostgroup.container_id = Container.id',
                        ],
                    ],
                ],
                'conditions' => [
                    'Hostgroup.id' => $selected
                ],
                'order'      => [
                    'Container.name' => 'ASC',
                ],
            ];
            $selectedHostgroups = $this->find('list', $query);
        }

        $hostgroups = $hostgroupsWithLimit + $selectedHostgroups;
        asort($hostgroups, SORT_FLAG_CASE | SORT_NATURAL);
        return $hostgroups;
    }
}
