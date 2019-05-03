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
 * Class Servicetemplategroup
 * @deprecated
 */
class Servicetemplategroup extends AppModel {

    public $belongsTo = [
        'Container' => [
            'dependent'  => true,
            'foreignKey' => 'container_id',
            'className'  => 'Container',
            'dependent'  => true,
        ]
    ];

    public $hasAndBelongsToMany = [
        'Servicetemplate' => [
            'joinTable'  => 'servicetemplates_to_servicetemplategroups',
            'foreignKey' => 'servicetemplategroup_id',
            'unique'     => true,
            'dependent'  => true,
        ],
    ];

    public $validate = [
        'Servicetemplate' => [
            'rule'     => [
                'multiple', [
                    'min' => 1,
                ]
            ],
            'message'  => 'Please select at least 1 servicetemplate',
            'required' => true,
        ],
    ];

    /**
     * @param int $containerIds
     * @param string $type
     * @param array $options
     * @param string $id
     * @return array|null
     * @deprecated
     */
    public function byContainerId($containerIds = ROOT_CONTAINER, $type = 'all', $options = [], $id = 'id') {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $_options = [
            'hasRootPrivileges' => false,
        ];

        $options = Hash::merge($_options, $options);

        $conditions = [];
        if ($options['hasRootPrivileges'] === false) {
            $conditions['Container.' . $id] = $containerIds;
        }

        switch ($type) {
            case 'all':
                return $this->find('all', [
                    'contain'    => [
                        'Container',
                    ],
                    'conditions' => $conditions,
                    'order'      => [
                        'Container.name' => 'asc',
                    ],
                ]);
                break;

            case 'list':
                $results = $this->find('all', [
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name',
                            ],
                        ],
                    ],
                    'conditions' => $conditions,
                    'order'      => [
                        'Container.name' => 'asc',
                    ],
                    'fields'     => [
                        'Servicetemplategroup.id',
                    ],
                ]);

                $list = [];
                foreach ($results as $result) {
                    $list[$result['Servicetemplategroup']['id']] = $result['Container']['name'];
                }

                return $list;
                break;
        }
    }
}
