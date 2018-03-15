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

//App::uses('AdminAppController', 'Admin.Controller');
//require_once APP . 'Model/User.php';

use itnovum\openITCOCKPIT\Core\Views\Logo;

class UsersController extends AppController
{
    public $layout = 'Admin.default';
    public $uses = [
        'User',
        'Systemsetting',
        'Tenant',
        'Usergroup',
        'ContainerUserMembership',
    ];
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'Ldap',
    ];

    public $helpers = [
        'ListFilter.ListFilter',
    ];


    public $listFilters = ['index' => ['fields' => [
        'User.full_name' => ['label' => 'Name', 'searchType' => 'wildcard'],
        'User.email'     => ['label' => 'Email', 'searchType' => 'wildcard'],
        'User.company'   => ['label' => 'Company', 'searchType' => 'wildcard'],
        'User.phone'     => ['label' => 'Phone', 'searchType' => 'wildcard'],
    ]]];


    public function index()
    {
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');

        $this->loadModel('Container');
        $options = [
            'recursive'  => -1,
            'order'      => [
                'User.full_name' => 'asc',
            ],
            'joins'      => [
                [
                    'table'      => 'users_to_containers',
                    'type'       => 'LEFT',
                    'alias'      => 'UsersToContainer',
                    'conditions' => 'UsersToContainer.user_id = User.id',
                ],
                [
                    'table'      => 'usergroups',
                    'type'       => 'LEFT',
                    'alias'      => 'Usergroup',
                    'conditions' => 'Usergroup.id = User.usergroup_id',
                ],
            ],
            'conditions' => [
                'UsersToContainer.container_id' => $this->MY_RIGHTS,
            ],
            'fields'     => [
                'User.id',
                'User.email',
                'User.company',
                'User.phone',
                'User.status',
                'User.full_name',
                'User.samaccountname',
                'Usergroup.id',
                'Usergroup.name',
                'UsersToContainer.container_id',
            ],
            'group'      => [
                'User.id',
            ],
        ];
        $query = Hash::merge($options, $this->Paginator->settings);

        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_users = $this->User->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_users = $this->Paginator->paginate();
        }

        //Get users container ids
        $userContainerIds = [];
        foreach ($all_users as $user) {
            $_user = $this->User->findById($user['User']['id']);
            $userContainerIds[$user['User']['id']]['Container'] = Hash::extract($_user['ContainerUserMembership'], '{n}.container_id');
        }

        $this->set('users', $all_users);
        if ($this->isApiRequest()) {
            $this->set('all_users', $all_users);
            $this->set('_serialize', ['all_users']);
        }
        $this->set('userContainerIds', $userContainerIds);
        $this->set('systemsettings', $systemsettings);
    }

    public function view($id = null)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->findById($id);
        $permissionsUser = $this->User->find('first', [
            'joins'      => [
                [
                    'table'      => 'users_to_containers',
                    'type'       => 'LEFT',
                    'alias'      => 'UsersToContainer',
                    'conditions' => 'UsersToContainer.user_id = User.id',
                ],
            ],
            'conditions' => [
                'User.id'                       => $id,
                'UsersToContainer.container_id' => $this->MY_RIGHTS,
            ],
            'fields'     => [
                'User.id',
                'User.email',
                'User.company',
                'User.status',
                'User.full_name',
                'User.samaccountname',
            ],
            'group'      => [
                'User.id',
            ],
        ]);

        if (empty($permissionsUser)) {
            $this->render403();

            return;
        }
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

//	public function view($id = null){
//		if (!$this->User->exists($id)){
//			throw new NotFoundException(__('Invalide user'));
//		}
//		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
//		$this->set('user', $this->User->get($id));
//	}

    /**
     * delete method
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     *
     * @param int $id
     *
     * @return void
     */
    /**
     * delete overwrite for soft delete
     *
     * @param  int     $id
     * @param  boolean $cascade
     *
     * @return bool
     */

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('user'));
        }
        $user = $this->User->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($user['ContainerUserMembership'], '{n}.container_id'))) {
            $this->render403();

            return;
        }

        if ($this->User->__delete($id, $this->Auth->user('id'))) {
            $this->setFlash(__('User deleted'));
            $this->redirect(['action' => 'index']);
        } else {
            $this->setFlash(__('Could not delete user'), false);
            $this->redirect(['action' => 'index']);
        }
    }

    public function add($type = 'local')
    {
        $usergroups = $this->Usergroup->find('list');
        // Activate "Show Stats in Menu" by default for New Users
        $this->request->data['User']['showstatsinmenu'] = 0;
        if (isset($this->request->params['named']['ldap']) && $this->request->params['named']['ldap'] == true) {
            $type = 'ldap';
            $samaccountname = '';
            if (isset($this->request->params['named']['samaccountname'])) {
                $samaccountname = $this->request->params['named']['samaccountname'];
            }
        }
        if ($this->request->data('ContainerUserMembership')) {
            $this->Frontend->setJson('rights', $this->request->data('ContainerUserMembership'));
            $this->request->data['ContainerUserMembership'] = array_map(
                function ($container_id, $permission_level) {
                    return [
                        'container_id'     => $container_id,
                        'permission_level' => $permission_level,
                    ];
                },
                array_keys($this->request->data['ContainerUserMembership']),
                $this->request->data['ContainerUserMembership']
            );
        }
        if ($type == 'ldap') {
            $ldapUser = $this->Ldap->userInfo($samaccountname);
            if(!is_null($ldapUser)) {
                // Overwrite request with LDAP data, that the user can not manipulate it with firebug ;)
                $this->request->data['User']['email'] = $ldapUser['mail'];
                $this->request->data['User']['firstname'] = $ldapUser['givenname'];
                $this->request->data['User']['lastname'] = $ldapUser['sn'];
                $this->request->data['User']['samaccountname'] = strtolower($ldapUser['samaccountname']);
                $this->request->data['User']['ldap_dn'] = $ldapUser['dn'];
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->create();
            $isJsonRequest = $this->request->ext == 'json';
            if ($this->User->saveAll($this->request->data)) {
                if ($isJsonRequest) {
                    $this->serializeId();
                } else {
                    $this->setFlash('User saved successfully');
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($isJsonRequest) {
                    $this->serializeErrorMessage();
                } else {
                    $this->setFlash(__('Could not save user'), false);
                }
            }
        }

        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_USER, [], $this->hasRootPrivileges);
        $this->set(compact(['containers', 'usergroups']));

        $this->set('type', $type);
    }


    public function edit($id = null)
    {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalide user'));
        }
        $permissionsUser = $this->User->find('first', [
            'joins'      => [
                [
                    'table'      => 'users_to_containers',
                    'type'       => 'LEFT',
                    'alias'      => 'UsersToContainer',
                    'conditions' => 'UsersToContainer.user_id = User.id',
                ],
            ],
            'conditions' => [
                'User.id'                       => $id,
                'UsersToContainer.container_id' => $this->MY_RIGHTS,
            ],
            'fields'     => [
                'User.id',
                'User.email',
                'User.company',
                'User.status',
                'User.full_name',
                'User.samaccountname',
            ],
            'group'      => [
                'User.id',
            ],
        ]);

        if (isset($permissionsUser['ContainerUserMembership'])) {
            $this->Frontend->setJson('rights',
                Hash::combine(
                    $permissionsUser['ContainerUserMembership'],
                    '{n}.container_id',
                    '{n}.permission_level'
                )
            );
        }

        if (empty($permissionsUser)) {
            $this->render403();

            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data('ContainerUserMembership')) {
                $this->Frontend->setJson('rights', $this->request->data('ContainerUserMembership'));
                $this->request->data['ContainerUserMembership'] = array_map(
                    function ($container_id, $permission_level) {
                        return [
                            'container_id'     => $container_id,
                            'permission_level' => $permission_level,
                        ];
                    },
                    array_keys($this->request->data['ContainerUserMembership']),
                    $this->request->data['ContainerUserMembership']
                );
            }
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->ContainerUserMembership->deleteAll(
                    [
                        'user_id' => $id,
                    ]
                );
                if ($this->User->saveAll($this->request->data)) {
                    $this->setFlash(__('User saved successfully'));
                    Cache::clear(false, 'permissions');
                    $this->redirect(['action' => 'index']);

                    return;
                }
            } else {
                $this->setFlash(__('Could not save user'), false);
            }
        }
        $usergroups = $this->Usergroup->find('list');
        $options = ['conditions' => ['User.'.$this->User->primaryKey => $id]];
        $this->request->data = $this->User->find('first', $options);
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_USER, [], $this->hasRootPrivileges);
        $selectedContainers = ($this->request->data('Container')) ? Hash::extract($this->request->data['Container'], '{n}.id') : Hash::extract($permissionsUser['ContainerUserMembership'], '{n}.container_id');
        $this->set(compact(['containers', 'selectedContainers', 'permissionsUser']));
        $this->request->data['User']['password'] = '';

        $type = 'local';
        if (strlen($this->request->data['User']['samaccountname']) > 0) {
            $type = 'ldap';
        }
        $this->set(compact(['type', 'usergroups']));
    }

    public function addFromLdap()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->redirect([
                'controller'     => 'users',
                'action'         => 'add',
                'ldap'           => 1,
                'samaccountname' => $this->request->data('Ldap.samaccountname'),
                //Fixing usernames like jon.doe
                'fix'            => 1 // we need an / behind the username parameter otherwise cakePHP will make strange stuff with a jon.doe username (username with dot ".")
            ]);
        }

        $usersForSelect = $this->Ldap->findAllUser();
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');

        $this->set(compact(['usersForSelect', 'systemsettings']));
    }

    public function resetPassword($id = null)
    {
        $this->autoRender = false;
        if (!$this->User->exists($id)) {
            $this->setFlash(__('Invalide user'), false);
            $this->redirect(['action' => 'index']);

            return;
        }

        $user = $this->User->findById($id);
        $generatePassword = function () {
            $char = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $size = (sizeof($char) - 1);
            $token = '';
            for ($i = 0; $i < 7; $i++) {
                $token .= $char[rand(0, $size)];
            }
            $token = $token.rand(0, 9);

            return $token;
        };

        $newPassword = $generatePassword();
        $this->_systemsettings = $this->Systemsetting->findAsArray();

        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($user['User']['email']);
        $Email->subject(__('Password reset'));

        $Email->emailFormat('both');
        $Email->template('template-resetpassword', 'template-resetpassword')->viewVars(['newPassword' => $newPassword]);

        $Logo = new Logo();
        $Email->attachments([
            'logo.png' => [
                'file'      => $Logo->getSmallLogoDiskPath(),
                'mimetype'  => 'image/png',
                'contentId' => '100',
            ],
        ]);

        $user['User']['new_password'] = $newPassword;
        $user['User']['confirm_new_password'] = $newPassword;
        unset($user['User']['password']);
        unset($user['Usergroup']);
        foreach ($user['ContainerUserMembership'] as $key => $container) {
            $user['User']['Container'][] = $container['container_id'];
        }
        unset($user['ContainerUserMembership']);
        if ($this->User->saveAll($user)) {
            $Email->send();
            $this->setFlash(__('Password reset successfully. A mail with the new password was set to <b>'.$user['User']['email'].'</b>'));
            $this->redirect(['action' => 'index']);

            return;
        }
        $this->setFlash(__('Could not reset password'), false);
        $this->redirect(['action' => 'index']);

        return;
    }

    public function loadUsersByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $containers = [ROOT_CONTAINER];

        $containerId = $this->request->query('containerId');
        if(in_array($containerId, $containers, true)){
            $containers[] = $containerId;
        }
        $users = $this->User->find('list', [
            'recursive' => -1,
            'joins'      => [
                [
                    'table'      => 'users_to_containers',
                    'type'       => 'INNER',
                    'alias'      => 'UsersToContainer',
                    'conditions' => 'UsersToContainer.user_id = User.id',
                ],
            ],
            'conditions' => [
                'UsersToContainer.container_id' => $containers
            ]
        ]);
        $users = $this->User->makeItJavaScriptAble(
            $users
        );

        $this->set(compact(['users']));
        $this->set('_serialize', ['users']);
    }
}
