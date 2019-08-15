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
 * A a servicegroup is not a stand alone object, its still a container
 * a servoce belongsTo a container (many to one)
 */

use itnovum\openITCOCKPIT\Core\ServicegroupConditions;

/**
 * Class Servicegroup
 * @deprecated
 */
class Servicegroup extends AppModel {

    public $belongsTo = [
        'Container' => [
            'foreignKey' => 'container_id',
            'className'  => 'Container',
        ]
    ];

    public $hasAndBelongsToMany = [
        'Service'         => [
            'joinTable'  => 'services_to_servicegroups',
            'foreignKey' => 'servicegroup_id',
            'unique'     => true,
            'dependent'  => true,
        ],
        'Servicetemplate' => [
            'joinTable'  => 'servicetemplates_to_servicegroups',
            'foreignKey' => 'servicegroup_id',
            'unique'     => true,
            'dependent'  => true,
        ],
    ];

    public $hasMany = [
        'ServiceEscalationServicegroupMembership' => [
            'className'  => 'ServiceescalationServicegroupMembership',
            'foreignKey' => 'servicegroup_id',
            'dependent'  => true,
        ],
        'ServicedependencyServicegroupMembership' => [
            'className'  => 'ServicedependencyServicegroupMembership',
            'foreignKey' => 'servicegroup_id',
            'dependent'  => true,
        ]
    ];

    public $validate = [
        'servicegroup_url' => [
            'rule'       => 'url',
            'allowEmpty' => true,
            'required'   => false,
            'message'    => 'Not a valid URL format',
        ],
    ];

    /**
     * Servicegroup constructor.
     * @param bool $id
     * @param null $table
     * @param null $ds
     * @deprecated
     */
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->Service = ClassRegistry::init('Service');
    }

    /**
     * @param array $container_ids
     * @param string $type
     * @param string $index
     * @return array|null
     * @deprecated
     */
    public function servicegroupsByContainerId($container_ids = [], $type = 'all', $index = 'id') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }
        $tenantContainerIds = [];
        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {

                // Get contaier id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = $this->Container->getPath($container_id);

                $tenantContainerIds[] = $path[1]['Container']['id'];
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);
        $container_ids = array_unique(array_merge($tenantContainerIds, $container_ids));

        if (!empty($container_ids)) {
            switch ($type) {
                case 'all':
                    return $this->find('all', [
                        'recursive' => -1,
                        'contain'   => [
                            'Container' => [
                                'conditions' => [
                                    'Container.parent_id'        => $container_ids,
                                    'Container.containertype_id' => CT_SERVICEGROUP,
                                ],

                                'order' => [
                                    'Container.name' => 'ASC',
                                ],
                            ]
                        ]
                    ]);

                default:
                    $return = [];
                    $results = $this->find('all', [
                        'recursive' => -1,
                        'contain'   => [
                            'Container' => [
                                'conditions' => [
                                    'Container.parent_id'        => $container_ids,
                                    'Container.containertype_id' => CT_SERVICEGROUP,
                                ],

                                'order' => [
                                    'Container.name' => 'ASC',
                                ],
                            ]
                        ]
                    ]);
                    foreach ($results as $result) {
                        $return[$result['Servicegroup'][$index]] = $result['Container']['name'];
                    }
                    return $return;
            }
        }
        return [];
    }

    /**
     * @param ServicegroupConditions $ServicegroupConditions
     * @param array $selected
     * @return array|null
     * @deprecated
     */
    public function getServicegroupsForAngular(ServicegroupConditions $ServicegroupConditions, $selected = []) {
        $query = [
            'recursive'  => -1,
            'fields'     => 'Container.name',
            'joins'      => [
                [
                    'table'      => 'containers',
                    'alias'      => 'Container',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Servicegroup.container_id = Container.id',
                    ],
                ],
            ],
            'conditions' => $ServicegroupConditions->getConditionsForFind(),
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
            $query['conditions']['NOT'] = ['Servicegroup.id' => $selected];
        }
        $servicegroupsWithLimit = $this->find('list', $query);
        $selectedServicegroups = [];
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
                            'Servicegroup.container_id = Container.id',
                        ],
                    ],
                ],
                'conditions' => [
                    'Servicegroup.id' => $selected
                ],
                'order'      => [
                    'Container.name' => 'ASC',
                ],
            ];
            $selectedServicegroups = $this->find('list', $query);
        }

        $servicegroups = $servicegroupsWithLimit + $selectedServicegroups;
        asort($servicegroups, SORT_FLAG_CASE | SORT_NATURAL);
        return $servicegroups;
    }
}
