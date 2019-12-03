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
 * Class DashboardTab
 * @deprecated
 */
class DashboardTab extends AppModel {


    public $hasMany = [
        'Widget' => [
            'dependent' => true,
        ],
    ];

    public $validate = [
        'name'    => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'user_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'     => 'numeric',
                'message'  => 'This field need to be numeric.',
                'required' => true,
            ],
        ],
    ];


    /**
     * @param $userId
     * @param array $options
     * @return mixed
     * @throws Exception
     * @deprecated
     */
    public function createNewTab($userId, $options = []) {
        $_options = [
            'name'              => __('Default'),
            'shared'            => 0,
            'source_tab_id'     => null,
            'check_for_updates' => 0,
            'position'          => $this->getNextPosition($userId),
        ];
        $options = Hash::merge($_options, $options);

        $this->create();
        $data = [
            'user_id' => $userId,
        ];

        $data = Hash::merge($options, $data);

        return $this->save($data);
    }

    /**
     * @param $userId
     * @return int
     * @deprecated
     */
    public function getNextPosition($userId) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'DashboardTab.user_id' => $userId,
            ],
            'order'      => [
                'DashboardTab.position' => 'DESC',
            ],
        ]);
        if (!empty($result)) {
            return (int)$result['DashboardTab']['position'] + 1;
        }

        return 1;
    }

    /**
     * @param $userId
     * @return bool
     * @deprecated
     */
    public function hasUserATab($userId) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'DashboardTab.user_id' => $userId,
            ]
        ]);

        return !empty($result);
    }

    /**
     * @param $userId
     * @return array|null
     * @deprecated
     */
    public function getAllTabsByUserId($userId) {
        $result = $this->find('all', [
            'recursive'  => -1,
            'contain'    => [],
            'conditions' => [
                'DashboardTab.user_id' => $userId,
            ],
            'order'      => [
                'DashboardTab.position' => 'ASC',
            ],
        ]);

        $forJs = [];
        foreach ($result as $row) {
            $forJs[] = [
                'id'                => (int)$row['DashboardTab']['id'],
                'position'          => (int)$row['DashboardTab']['position'],
                'name'              => $row['DashboardTab']['name'],
                'shared'            => (bool)$row['DashboardTab']['shared'],
                'source_tab_id'     => (int)$row['DashboardTab']['source_tab_id'],
                'check_for_updates' => (bool)$row['DashboardTab']['check_for_updates'],
                'last_update'       => (int)$row['DashboardTab']['last_update'],
                'locked'            => (bool)$row['DashboardTab']['locked']
            ];
        }


        return $forJs;
    }

    /**
     * @return array|null
     * @deprecated
     */
    public function getSharedTabs() {
        $result = $this->find('all', [
            'recursive'  => -1,
            'joins'      => [
                [
                    'table'      => 'users',
                    'alias'      => 'User',
                    'type'       => 'INNER',
                    'conditions' => [
                        'User.id = DashboardTab.user_id',
                    ],
                ],
            ],
            'conditions' => [
                'DashboardTab.shared' => 1,
            ],
            'fields'     => [
                'DashboardTab.*',
                'User.firstname',
                'User.lastname'
            ]
        ]);


        $forJs = [];
        foreach ($result as $row) {
            $forJs[] = [
                'id'                => (int)$row['DashboardTab']['id'],
                'position'          => (int)$row['DashboardTab']['position'],
                'name'              => sprintf(
                    '%s, %s/%s',
                    $row['User']['firstname'],
                    $row['User']['lastname'],
                    $row['DashboardTab']['name']
                ),
                'shared'            => (bool)$row['DashboardTab']['shared'],
                'source_tab_id'     => (int)$row['DashboardTab']['source_tab_id'],
                'check_for_updates' => (bool)$row['DashboardTab']['check_for_updates'],
                'last_update'       => (int)$row['DashboardTab']['last_update'],
                'locked'            => (bool)$row['DashboardTab']['locked']
            ];
        }


        return $forJs;
    }

    /**
     * @param $userId
     * @param $tabId
     * @return array|null
     * @deprecated
     */
    public function getWidgetsForTabByUserIdAndTabId($userId, $tabId) {
        $result = $this->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Widget' => [
                    'fields' => [
                        'Widget.id',
                        'Widget.dashboard_tab_id',
                        'Widget.type_id',
                        'Widget.host_id',
                        'Widget.service_id',
                        'Widget.row',
                        'Widget.col',
                        'Widget.width',
                        'Widget.height',
                        'Widget.title',
                        'Widget.color',
                        'Widget.directive',
                        'Widget.icon',
                        //'Widget.json_data', //Do not add json data here!
                        'Widget.created',
                        'Widget.modified'
                    ],
                    'order'  => [
                        'Widget.col' => 'ASC'
                    ]
                ]
            ],
            'conditions' => [
                'DashboardTab.id'      => $tabId,
                'DashboardTab.user_id' => $userId
            ],
        ]);
        return $result;
    }

}
