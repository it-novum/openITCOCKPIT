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
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $uses = ['Changelog'];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Changelog.name' => [
                    'label' => 'Name',
                    'searchType' => 'wildcard',
                    'inputOptions' => [
                        'class' => 'form-control',
                        'style' => 'width:80%;'
                    ]
                ],
                'Changelog.model' => [
                    'label' => 'Object type',
                    'type' => 'checkbox',
                    'searchType' => 'nix',
                    'options' => [
                        'Command' => [
                            'name'  => 'Command',
                            'value' => 1,
                            'label' => 'Command',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Contact' => [
                            'name'  => 'Contact',
                            'value' => 1,
                            'label' => 'Contact',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Contactgroup' => [
                            'name'  => 'Contactgroup',
                            'value' => 1,
                            'label' => 'Contactgroup',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Host' => [
                            'name'  => 'Host',
                            'value' => 1,
                            'label' => 'Host',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Hostgroup' => [
                            'name'  => 'Hostgroup',
                            'value' => 1,
                            'label' => 'Hostgroup',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Hosttemplate' => [
                            'name'  => 'Hosttemplate',
                            'value' => 1,
                            'label' => 'Hosttemplate',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Service' => [
                            'name'  => 'Service',
                            'value' => 1,
                            'label' => 'Service',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Servicegroup' => [
                            'name'  => 'Servicegroup',
                            'value' => 1,
                            'label' => 'Servicegroup',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Servicetemplate' => [
                            'name'  => 'Servicetemplate',
                            'value' => 1,
                            'label' => 'Servicetemplate',
                            'data'  => 'Filter.Changelog.model',
                        ],
                        'Timeperiod' => [
                            'name'  => 'Timeperiod',
                            'value' => 1,
                            'label' => 'Timeperiod',
                            'data'  => 'Filter.Changelog.model',
                        ],
                    ],
                ],
                'Changelog.action' => [
                    'label' => 'Object type',
                    'type' => 'checkbox',
                    'searchType' => 'nix',
                    'options' => [
                        'add' => [
                            'name'  => 'add',
                            'value' => 1,
                            'label' => '<i class="fa fa-plus txt-color-greenLight"></i> add',
                            'data'  => 'Filter.Changelog.action',
                        ],
                        'copy' => [
                            'name'  => 'copy',
                            'value' => 1,
                            'label' => '<i class="fa fa-copy txt-color-blue"></i> copy',
                            'data'  => 'Filter.Changelog.action',
                        ],
                        'delete' => [
                            'name'  => 'delete',
                            'value' => 1,
                            'label' => '<i class="fa fa-trash-o txt-color-red"></i> delete',
                            'data'  => 'Filter.Changelog.action',
                        ],
                        'edit' => [
                            'name'  => 'edit',
                            'value' => 1,
                            'label' => '<i class="fa fa-pencil txt-color-blue"></i> edit',
                            'data'  => 'Filter.Changelog.action',
                        ],
                    ],
                ],
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
                    'type'       => 'LEFT',
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

        $result = $this->Systemsetting->findByKey('FRONTEND.HIDDEN_USER_IN_CHANGELOG');
        $showUser = !(bool)$result['Systemsetting']['value'];

        $this->Paginator->settings = $query;
        if ($this->isApiRequest()) {
            $this->Paginator->settings['limit'] = 250;
            $all_changes = $this->Paginator->paginate();
        } else {
            $all_changes = $this->Paginator->paginate();
        }
        
        $this->set('_serialize', ['all_changes']);

        $this->set(compact(['all_changes']));
        $this->set('showUser', $showUser);
    }
}
