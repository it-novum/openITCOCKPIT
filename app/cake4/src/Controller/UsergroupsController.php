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

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsergroupsTable;
use Cake\Cache\Cache;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\GenericFilter;

class UsergroupsController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $GenericFilter = new GenericFilter($this->request);
        $GenericFilter->setFilters([
            'like' => [
                'Usergroups.name',
                'Usergroups.description'
            ]
        ]);

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $GenericFilter->getPage());

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $allUsergroups = $UsergroupsTable->getUsergroups($PaginateOMat, $GenericFilter);

        $this->set('allUsergroups', $allUsergroups);
        $this->viewBuilder()->setOption('serialize', ['allUsergroups']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->exists($id)) {
            throw new NotFoundException(__('Invalid usergroup'));
        }
        $usergroup = $UsergroupsTable->getUsergroupById($id);

        $this->set('usergroup', $usergroup);
        $this->viewBuilder()->setOption('serialize', ['usergroup']);
    }

    public function edit($id = null) {
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->exists($id)) {
            throw new NotFoundException(__('Invalid user role'));
        }

        /*@TODO fix ACL stuff with cake 4*/
        $permissions = $this->Acl->Aro->Permission->find('all', [
            'conditions' => [
                'Aro.foreign_key' => $id,
            ],
        ]);

        $aros = Hash::extract($permissions, '{n}.Permission.aco_id');
        unset($permissions);

        /*$acos = $this->Acl->Aco->find('threaded', [
            'recursive' => -1,
        ]);
*/
        $usergroup = $UsergroupsTable->getUsergroupById($id);

        /*
                $alwaysAllowedAcos = $UsergroupsTable->getAlwaysAllowedAcos($acos);
                $acoDependencies = $UsergroupsTable->getAcoDependencies($acos);
                $dependentAcoIds = $UsergroupsTable->getAcoDependencyIds($acoDependencies);
        */
        $allAcos = $this->Acl->Aco->find('threaded', [
            'recursive' => -1,
        ]);
        $UsergroupsTable->getUsergroupAcosForAddEdit($allAcos);

        die();
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
            foreach ($this->request->getData('Usergroup.Aco') as $acoId => $value) {
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

            debug($this->request->data);
            die();
            if ($this->Usergroup->save($this->request->data)) {
                //Delete old permissions
                $this->Acl->Aro->Permission->deleteAll([
                    'Aro.id' => $aro['Aro']['id'],
                ]);
                //Save new permissions
                $this->Acl->Aro->Permission->saveAll($aclData);
                Cache::clear('permissions');
                $this->setFlash(__('User role successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('User role could not be saved'), false);
            }
        }
        $this->set('acos', $acos);
        $this->set('aros', $aros);
        $this->set('usergroup', $usergroup);
        $this->set('alwaysAllowedAcos', $alwaysAllowedAcos);
        $this->set('acoDependencies', $acoDependencies);
        $this->set('dependentAcoIds', $dependentAcoIds);
        $this->viewBuilder()->setOption('serialize', ['acos', 'aros', 'usergroup', 'alwaysAllowedAcos', 'acoDependencies', 'dependentAcoIds']);

    }

    public function add() {
        $acos = $this->Acl->Aco->find('threaded');

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        $alwaysAllowedAcos = $UsergroupsTable->getAlwaysAllowedAcos($acos);
        $acoDependencies = $UsergroupsTable->getAcoDependencies($acos);
        $dependenAcoIds = $UsergroupsTable->getAcoDependencyIds($acoDependencies);

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
                foreach ($this->request->getData('Usergroup.Aco') as $acoId => $value) {
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

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$UsergroupsTable->existsById($id)) {
            throw new NotFoundException(__('User group not found'));
        }

        $user = $this->getUser();
        if((int)$user->get('usergroup_id') === (int)$id){
            throw new \RuntimeException('You cannot delete your own user group!');
        }

        $usergroup = $UsergroupsTable->get($id);

        if ($UsergroupsTable->delete($usergroup)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(500);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function loadUsergroups() {
        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $usergroups = $UsergroupsTable->getUsergroupsList();

        $usergroups = Api::makeItJavaScriptAble($usergroups);

        $this->set('usergroups', $usergroups);
        $this->viewBuilder()->setOption('serialize', ['usergroups']);
    }

}
