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
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Contact $Contact
 * @property User $User
 * @property ChangelogComponent $Changelog
 */
class ContactgroupsController extends AppController {

    public $uses = [
        'Contactgroup',
        'Container',
        'Contact',
        'User',
    ];
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $helpers = ['ListFilter.ListFilter'];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Container.name' => [
                    'label' => 'Name',
                    'searchType' => 'wildcard',
                ],
                'Contactgroup.description' => [
                    'label' => 'Alias',
                    'searchType' => 'wildcard',
                ],
            ],
        ],
    ];

    public function index() {
        $options = [
            'order' => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS),
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_contactgroups = $this->Contactgroup->find('all', $query);
        } else {
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $all_contactgroups = $this->Paginator->paginate();
        }

        $this->set('all_contactgroups', $all_contactgroups);
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_contactgroups']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Contactgroup->exists($id)) {
            throw new NotFoundException(__('Invalid contact group'));
        }
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }
        $contactgroup = $this->Contactgroup->findById($id);


        if (!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))) {
            throw new ForbiddenException('403 Forbidden');
        }

        $this->set('contactgroup', $contactgroup);
        $this->set('_serialize', ['contactgroup']);
    }

    public function edit($id = null) {
        $userId = $this->Auth->user('id');
        if (!$this->Contactgroup->exists($id)) {
            throw new NotFoundException(__('Invalid contactgroup'));
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }
        $contactgroup = $this->Contactgroup->findById($id);


        if (!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))) {
            $this->render403();

            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
            $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');

            $ext_data_for_changelog = [];
            if (isset($this->request->data['Contactgroup']['Contact']) && is_array($this->request->data['Contactgroup']['Contact'])) {
                foreach ($this->request->data['Contactgroup']['Contact'] as $contact_id) {
                    $_contact = $this->Contact->find('first', [
                        'recursive' => -1,
                        'fields' => [
                            'Contact.id',
                            'Contact.name',
                        ],
                        'conditions' => [
                            'Contact.id' => $contact_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Contact'][] = [
                        'id' => $contact_id,
                        'name' => $_contact['Contact']['name'],
                    ];
                }
            }
            if (isset($this->request->data['Container']['name'])) {
                $ext_data_for_changelog['Container']['name'] = $this->request->data['Container']['name'];
            }

            $this->request->data['Contact'] = $this->request->data['Contactgroup']['Contact'];
            $this->request->data['Container']['id'] = $this->request->data['Contactgroup']['container_id'];

            //Save Contact associations -> Array Format [Contact] => data
            if ($this->Contactgroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Contactgroup->id,
                    OBJECT_CONTACTGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data['Container']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog),
                    $contactgroup
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/contactgroups/edit/%s">Contact group</a> successfully saved', $this->Contactgroup->id));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Contactgroup could not be saved'), false);
            }
        } else {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($contactgroup['Container']['parent_id']);
            $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
            $contactgroup['Contactgroup']['Contact'] = Hash::combine($contactgroup['Contact'], '{n}.id', '{n}.id');
        }

        $this->request->data = Hash::merge($contactgroup, $this->request->data); // Is used in the template file
        $data = [
            'contactgroup' => $contactgroup,
            'containers' => $containers,
            'contacts' => $contacts,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function add() {
        $userId = $this->Auth->user('id');
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }

        $this->Frontend->set('data_placeholder', __('Please choose a contact'));
        $this->Frontend->set('data_placeholder_empty', __('No entries found'));

        $contacts = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Container']['parent_id'])) {
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
                $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
            }

            $this->request->data['Contactgroup']['uuid'] = UUID::v4();
            $this->request->data['Container']['containertype_id'] = CT_CONTACTGROUP;
            $ext_data_for_changelog = [];
            //Save Contact associations -> Array Format [Contact] => data
            if (isset($this->request->data['Contactgroup']['Contact']) && is_array($this->request->data['Contactgroup']['Contact'])) {
                foreach ($this->request->data['Contactgroup']['Contact'] as $contact_id) {
                    $_contact = $this->Contact->find('first', [
                        'recursive' => -1,
                        'fields' => [
                            'Contact.id',
                            'Contact.name',
                        ],
                        'conditions' => [
                            'Contact.id' => $contact_id,
                        ],
                    ]);
                    $ext_data_for_changelog['Contact'][] = [
                        'id' => $contact_id,
                        'name' => $_contact['Contact']['name'],
                    ];
                    unset($_contact);
                }
            }
            if (isset($this->request->data['Container']['name'])) {
                $ext_data_for_changelog['Container']['name'] = $this->request->data['Container']['name'];
            }
            if (isset($this->request->data['Contactgroup']['Contact'])) {
                $this->request->data['Contact'] = $this->request->data['Contactgroup']['Contact'];
            }
            if ($this->Contactgroup->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Contactgroup->id,
                    OBJECT_CONTACTGROUP,
                    $this->request->data('Container.parent_id'),
                    $userId,
                    $this->request->data['Container']['name'],
                    array_merge($this->request->data, $ext_data_for_changelog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                // for rest usage
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                }

                $this->setFlash(__('<a href="/contactgroups/edit/%s">Contact group</a> successfully saved', $this->Contactgroup->id));
                $this->redirect(['action' => 'index']);
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();

                    return;
                }

                $this->setFlash(__('Could not save data'), false);
            }
        }

        $this->set(compact(['containers', 'contacts']));
        $this->set('_serialize', ['containers', 'contacts']);
    }

    public function loadContacts($containerIds = null) {
        $this->allowOnlyAjaxRequests();

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);
        $contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
        $contacts = $this->Contact->makeItJavaScriptAble($contacts);

        $data = [
            'contacts' => $contacts,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    protected function __allowDelete($contactgroup) {
        if (is_numeric($contactgroup)) {
            $contactgroupId = $contactgroup;
        } else {
            $contactgroupId = $contactgroup['Contactgroup']['id'];
        }

        $models = [
            '__ContactgroupsToHosttemplates',
            '__ContactgroupsToHosts',
            '__ContactgroupsToServicetemplates',
            '__ContactgroupsToServices',
            '__ContactgroupsToHostescalations',
            '__ContactgroupsToServiceescalations',
        ];

        foreach ($models as $model) {
            $this->loadModel($model);
            $count = $this->{$model}->find('count', [
                'conditions' => [
                    'contactgroup_id' => $contactgroupId,
                ],
            ]);
            if ($count > 0) {
                return false;
            }
        }

        return true;
    }

    public function delete($id) {
        if (!$this->Contactgroup->exists($id)) {
            throw new NotFoundException(__('Invalid contact group'));
        }
        $userId = $this->Auth->user('id');

        $contactgroup = $this->Contactgroup->find('first', [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'conditions' => [
                'Contactgroup.id' => $id
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))) {
            $this->render403();
            return;
        }

        if ($this->__allowDelete($id)) {
            if ($this->Contactgroup->delete($id)) {
                Cache::clear(false, 'permissions');
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_CONTACTGROUP,
                    $contactgroup['Container']['parent_id'],
                    $userId,
                    $contactgroup['Container']['name'],
                    $contactgroup
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('Contactgroup deleted'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not delete contactgroup'), false);
                $this->redirect(['action' => 'index']);
            }
        } else {
            $contactgroupsCanotDelete[$contactgroup['Contactgroup']['id']] = $contactgroup['Container']['name'];
            $this->set(compact(['contactgroupsCanotDelete']));
            $this->render('mass_delete');
        }
    }

    public function mass_delete($id = null) {
        $userId = $this->Auth->user('id');
        if ($this->request->is('post') || $this->request->is('put')) {
            foreach ($this->request->data('Contactgroup.delete') as $contactgroupId) {
                if ($this->Contactgroup->exists($contactgroupId)) {
                    $contactgroup = $this->Contactgroup->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Container'
                        ],
                        'conditions' => [
                            'Contactgroup.id' => $contactgroupId
                        ]
                    ]);
                    if ($this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))) {
                        if ($this->Contactgroup->delete($contactgroupId)) {
                            $changelog_data = $this->Changelog->parseDataForChangelog(
                                $this->params['action'],
                                $this->params['controller'],
                                $contactgroupId,
                                OBJECT_CONTACTGROUP,
                                $contactgroup['Container']['parent_id'],
                                $userId,
                                $contactgroup['Container']['name'],
                                $contactgroup
                            );
                            if ($changelog_data) {
                                CakeLog::write('log', serialize($changelog_data));
                            }
                        }
                    }
                }
            }
            Cache::clear(false, 'permissions');
            $this->setFlash(__('Contact groups deleted'));
            $this->redirect(['action' => 'index']);
        }

        foreach (func_get_args() as $contactgroupId) {
            if ($this->Contactgroup->exists($contactgroupId)) {
                $contactgroup = $this->Contactgroup->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Container'
                    ],
                    'conditions' => [
                        'Contactgroup.id' => $contactgroupId
                    ]
                ]);
                if ($this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))) {
                    if ($this->__allowDelete($contactgroupId)) {
                        $contactgroupsToDelete[$contactgroupId] = $contactgroup;
                    } else {
                        debug($contactgroup['Container']['name']);
                        $contactgroupsCanotDelete[$contactgroupId] = $contactgroup['Container']['name'];
                    }
                }
            }
        }
        $count = sizeof($contactgroupsToDelete) + sizeof($contactgroupsCanotDelete);
        $this->set(compact(['contactgroupsToDelete', 'contactgroupsCanotDelete', 'count']));


    }

    public function copy($id = null) {
        $userId = $this->Auth->user('id');
        $contactgroups = $this->Contactgroup->find('all', [
            'recursive' => -1,
            'contain' => [
                'Container' => [
                    'fields' => [
                        'Container.name',
                        'Container.parent_id',
                        'Container.containertype_id',
                    ]
                ],
                'Contact' => [
                    'fields' => [
                        'Contact.id',
                        'Contact.name'
                    ]
                ]
            ],
            'fields' => [
                'Contactgroup.description'
            ],
            'conditions' => [
                'Contactgroup.id' => func_get_args(),
            ],
        ]);

        $contactgroups = Hash::combine($contactgroups, '{n}.Contactgroup.id', '{n}');
        if ($this->request->is('post') || $this->request->is('put')) {
            $datasource = $this->Contactgroup->getDataSource();
            try {
                $datasource->begin();
                foreach ($this->request->data['Contactgroup'] as $sourceContactGroupId => $newContactGroup) {
                    $newContactGroupData = [
                        'Container' => [
                            'parent_id' => $contactgroups[$sourceContactGroupId]['Container']['parent_id'],
                            'name' => $newContactGroup['name'],
                            'containertype_id' => $contactgroups[$sourceContactGroupId]['Container']['containertype_id'],
                        ],
                        'Contactgroup' => [
                            'description' => $newContactGroup['description'],
                            'uuid' => UUID::v4(),
                            'Contact' => Hash::extract($contactgroups[$sourceContactGroupId]['Contact'], '{n}.id'),
                        ],
                        'Contact' => Hash::extract($contactgroups[$sourceContactGroupId]['Contact'], '{n}.id')
                    ];
                    $this->Contactgroup->create();
                    if (!$this->Contactgroup->saveAll($newContactGroupData)) {
                        throw new Exception('Some of the Contactgroups could not be copied');
                    }
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $this->Contactgroup->id,
                        OBJECT_CONTACTGROUP,
                        $contactgroups[$sourceContactGroupId]['Container']['parent_id'],
                        $userId,
                        $newContactGroup['name'],
                        Hash::merge($contactgroups[$sourceContactGroupId], [
                            'Contactgroup' => [
                                'description' => $newContactGroup['description']
                            ],
                            'Container' => [
                                'name' => $newContactGroup['name']
                            ]
                        ])
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }
                $datasource->commit();
                Cache::clear(false, 'permissions');
                $this->setFlash(__('Contactgroups are successfully copied'));
                $this->redirect(['action' => 'index']);
            } catch (Exception $e) {
                $datasource->rollback();
                $this->setFlash(__($e->getMessage()), false);
                $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('contactgroups'));
        $this->set('back_url', $this->referer());
    }

    public function usedBy($id = null) {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Contactgroup->exists($id)) {
            throw new NotFoundException(__('Invalid contact group'));
        }

        $this->Contactgroup->bindModel([
            'hasAndBelongsToMany' => [
                'Hosttemplate' => [
                    'className' => 'Hosttemplate',
                    'joinTable' => 'contactgroups_to_hosttemplates',
                    'type' => 'INNER'
                ],
                'Host' => [
                    'className' => 'Host',
                    'joinTable' => 'contactgroups_to_hosts',
                    'type' => 'INNER'
                ],
                'Servicetemplate' => [
                    'className' => 'Servicetemplate',
                    'joinTable' => 'contactgroups_to_servicetemplates',
                    'type' => 'INNER'
                ],
                'Service' => [
                    'className' => 'Service',
                    'joinTable' => 'contactgroups_to_services',
                    'type' => 'INNER'
                ],
                'Hostescalation' => [
                    'className' => 'Hostescalation',
                    'joinTable' => 'contactgroups_to_hostescalations',
                    'type' => 'INNER'
                ],
                'Serviceescalation' => [
                    'className' => 'Serviceescalation',
                    'joinTable' => 'contactgroups_to_serviceescalations',
                    'type' => 'INNER'
                ],
            ]
        ]);

        $contactgroupWithRelations = $this->Contactgroup->find('first', [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.name'
                    ]
                ],
                'Host' => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.address'
                    ]
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Service' => [
                    'fields' => [
                        'Service.id',
                        'Service.name'
                    ],
                    'Host' => [
                        'fields' => [
                            'Host.name'
                        ]
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ]
                    ]
                ],
                'Hostescalation.id',
                'Serviceescalation.id'
            ],
            'conditions' => [
                'Contactgroup.id' => $id
            ]
        ]);

        /* Format service name for api "hostname|Service oder Service template name" */
        array_walk($contactgroupWithRelations['Service'],function(&$service){
            $serviceName = $service['name'];
            if(empty($service['name'])) {
                $serviceName = $service['Servicetemplate']['name'];
            }
            $service['name'] = sprintf('%s|%s', $service['Host']['name'], $serviceName);
        });

        //Sort host template, host, service template and service by name
        foreach(['Hosttemplate', 'Host', 'Servicetemplate', 'Service'] as $modelName){
            $contactgroupWithRelations[$modelName] = Hash::sort($contactgroupWithRelations[$modelName], '{n}.name', 'asc', [
                    'type' => 'natural',
                    'ignoreCase' => true
                ]
            );
        }
        if (!$this->allowedByContainerId(Hash::extract($contactgroupWithRelations, 'Contactgroup.container_id'), false)) {
            $this->render403();
            return;
        }
        $this->set(compact(['contactgroupWithRelations']));
        $this->set('_serialize', ['contactgroupWithRelations']);
        $this->set('back_url', $this->referer());
    }
}
