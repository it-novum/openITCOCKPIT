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

class ChangelogsController extends AppController
{
    public $layout = 'Admin.default';
    public $helpers = [
        'Form',
        'Html',
        'Time',
        'ListFilter.ListFilter',
        'Changelog',
    ];
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $uses = ['Changelog'];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Changelog.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {
        $conditions = [
            'ChangelogsToContainers.container_id' => $this->MY_RIGHTS,
        ];
        $conditions = $this->ListFilter->buildConditions([], $conditions);
        $query = [
            'recursive'  => -1,
            'fields'     => [
                'DISTINCT Changelog.*', 'User.id', 'User.lastname', 'User.firstname',
            ],
            'joins'      => [
                [
                    'table'      => 'users',
                    'alias'      => 'User',
                    'type'       => 'INNER',
                    'conditions' => [
                        'User.id = Changelog.user_id',
                    ],
                ],
                [
                    'table'      => 'changelogs_to_containers',
                    'alias'      => 'ChangelogsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'ChangelogsToContainers.changelog_id = Changelog.id',
                    ],

                ],
            ],
            'conditions' => [
                $conditions,
            ],
            'order'      => [
                'Changelog.created' => 'DESC',
            ],
        ];

        $this->Paginator->settings = $query;
        if ($this->isApiRequest()) {
            $this->Paginator->settings['limit'] = 250;
            $all_changes = $this->Paginator->paginate();
        } else {
            $all_changes = $this->Paginator->paginate();
        }
        $this->set('_serialize', ['all_changes']);

        $this->set('isFilter', false);
        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        }
        $this->set(compact(['all_changes']));
    }
}
