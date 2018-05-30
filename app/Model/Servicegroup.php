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

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        App::uses('UUID', 'Lib');
        $this->Service = ClassRegistry::init('Service');
    }

    public function servicegroupsByContainerId($container_ids = [], $type = 'all', $index = 'id') {
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
}
