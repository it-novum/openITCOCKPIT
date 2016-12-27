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

class UsergroupsController extends AppController
{
    public $layout = 'Admin.default';
    public $components = ['Acl'];

    public $uses = ['Usergroup', 'Aro', 'Tenant'];

    //public function beforeFilter(){
    //	$this->Auth->allow();
    //	parent::beforeFilter();
    //}

    public function index()
    {
        $options = [
            'recursive' => -1,
            'order'     => [
                'Usergroup.name' => 'asc',
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);
        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_usergroups = $this->Usergroup->find('all', $query);
            $this->set('all_usergroups', $all_usergroups);
            $this->set('_serialize', ['all_usergroups']);
        } else {
            $this->Paginator->settings = Hash::merge($this->Paginator->settings, $options);
            $usergroups = $this->Paginator->paginate();
        }
        $this->set(compact(['usergroups']));
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Usergroup->exists($id)) {
            throw new NotFoundException(__('Invalid usergroup'));
        }
        $usergroup = $this->Usergroup->findById($id);

        $this->set('usergroup', $usergroup);
        $this->set('_serialize', ['usergroup']);
    }

    public function edit($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->Usergroup->exists($id)) {
            throw new NotFoundException(__('Invalid user role'));
        }
        $usergroup = $this->Usergroup->findById($id);

        $permissions = $this->Acl->Aro->Permission->find('all', [
            'conditions' => [
                'Aro.foreign_key' => $id,
            ],
        ]);

        $aros = Hash::extract($permissions, '{n}.Permission.aco_id');
        unset($permissions);
        $acos = $this->Acl->Aco->find('threaded', [
            'recursive' => -1,
        ]);
        $alwaysAllowedAcos = $this->Usergroup->getAlwaysAllowedAcos($acos);

        $usergroup = $this->Usergroup->findById($id);


        $alwaysAllowedAcos = $this->Usergroup->getAlwaysAllowedAcos($acos);
        $acoDependencies = $this->Usergroup->getAcoDependencies($acos);
        $dependenAcoIds = $this->Usergroup->getAcoDependencyIds($acoDependencies);


        if ($this->request->is('post') || $this->request->is('put')) {
            $aro = $this->Acl->Aro->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Aro.foreign_key' => $id,
                ],
                'fields'     => [
                    'Aro.id',
                ],
            ]);
            $aclData = [];
            $avoidMysqlDuplicate = [];
            foreach ($this->request->data('Usergroup.Aco') as $acoId => $value) {
                if ($value == 1) {
                    $aclData[] = [
                        'Permission' => [
                            'aro_id'  => $aro['Aro']['id'],
                            'aco_id'  => $acoId,
                            '_create' => 1,
                            '_read'   => 1,
                            '_update' => 1,
                            '_delete' => 1,
                        ],
                    ];
                    //Has dependend ACOs?
                    if (isset($acoDependencies[$acoId])) {
                        foreach (array_keys($acoDependencies[$acoId]) as $dependendAcoId) {
                            if (!isset($avoidMysqlDuplicate[$dependendAcoId])) {
                                $aclData[] = [
                                    'Permission' => [
                                        'aro_id'  => $aro['Aro']['id'],
                                        'aco_id'  => $dependendAcoId,
                                        '_create' => 1,
                                        '_read'   => 1,
                                        '_update' => 1,
                                        '_delete' => 1,
                                    ],
                                ];
                                $avoidMysqlDuplicate[$dependendAcoId] = true;
                            }
                        }
                    }
                }
            }

            //Add always allowd ACOs to request data
            foreach ($alwaysAllowedAcos as $acoId => $description) {
                $aclData[] = [
                    'Permission' => [
                        'aro_id'  => $aro['Aro']['id'],
                        'aco_id'  => $acoId,
                        '_create' => 1,
                        '_read'   => 1,
                        '_update' => 1,
                        '_delete' => 1,
                    ],
                ];
            }

            unset($this->request->data['Aco']);
            if ($this->Usergroup->save($this->request->data)) {
                //Delete old permissions
                $this->Acl->Aro->Permission->deleteAll([
                    'Aro.id' => $aro['Aro']['id'],
                ]);
                //Save new permissions
                $this->Acl->Aro->Permission->saveAll($aclData);
                $this->setFlash(__('User role successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('User role could not be saved'), false);
            }
        }

        $this->set(compact([
            'acos',
            'aros',
            'usergroup',
            'alwaysAllowedAcos',
            'acoDependencies',
            'dependenAcoIds',
        ]));
    }

    public function add()
    {
        $acos = $this->Acl->Aco->find('threaded');

        $alwaysAllowedAcos = $this->Usergroup->getAlwaysAllowedAcos($acos);
        $acoDependencies = $this->Usergroup->getAcoDependencies($acos);
        $dependenAcoIds = $this->Usergroup->getAcoDependencyIds($acoDependencies);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Usergroup->saveAll($this->request->data)) {
                $this->Aro->save([
                    'foreign_key' => $this->Usergroup->id,
                    'model'       => 'Usergroup',
                    'parent_id'   => $this->Usergroup->parentNode(),
                ]);

                //Save permissions
                $aro = $this->Acl->Aro->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Aro.foreign_key' => $this->Usergroup->id,
                    ],
                    'fields'     => [
                        'Aro.id',
                    ],
                ]);

                $aclData = [];
                $avoidMysqlDuplicate = [];
                foreach ($this->request->data('Usergroup.Aco') as $acoId => $value) {
                    if ($value == 1) {
                        $aclData[] = [
                            'Permission' => [
                                'aro_id'  => $aro['Aro']['id'],
                                'aco_id'  => $acoId,
                                '_create' => 1,
                                '_read'   => 1,
                                '_update' => 1,
                                '_delete' => 1,
                            ],
                        ];
                        //Has dependend ACOs?
                        if (isset($acoDependencies[$acoId])) {
                            foreach (array_keys($acoDependencies[$acoId]) as $dependendAcoId) {
                                if (!isset($avoidMysqlDuplicate[$dependendAcoId])) {
                                    $aclData[] = [
                                        'Permission' => [
                                            'aro_id'  => $aro['Aro']['id'],
                                            'aco_id'  => $dependendAcoId,
                                            '_create' => 1,
                                            '_read'   => 1,
                                            '_update' => 1,
                                            '_delete' => 1,
                                        ],
                                    ];
                                    $avoidMysqlDuplicate[$dependendAcoId] = true;
                                }
                            }
                        }
                    }
                }

                //Add always allowd ACOs to request data
                foreach ($alwaysAllowedAcos as $acoId => $description) {
                    $aclData[] = [
                        'Permission' => [
                            'aro_id'  => $aro['Aro']['id'],
                            'aco_id'  => $acoId,
                            '_create' => 1,
                            '_read'   => 1,
                            '_update' => 1,
                            '_delete' => 1,
                        ],
                    ];
                }

                unset($this->request->data['Aco']);
                //Save new permissions
                $this->Acl->Aro->Permission->saveAll($aclData);
                $this->setFlash(__('User role successfully saved.'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not save data'), false);
            }
        }
        $this->set(compact([
            'acos',
            'alwaysAllowedAcos',
            'acoDependencies',
            'dependenAcoIds',
        ]));
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Usergroup->exists($id)) {
            throw new NotFoundException(__('invalid_userrole'));
        }

        if ($this->Usergroup->delete($id)) {
            $this->setFlash(__('User role deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete user role'), false);
        $this->redirect(['action' => 'index']);
    }

}
