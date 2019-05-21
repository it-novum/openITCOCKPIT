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

use itnovum\openITCOCKPIT\Core\PHPVersionChecker;
use itnovum\openITCOCKPIT\Core\Security\CSRF;
use itnovum\openITCOCKPIT\Core\Views\Logo;

class UsersController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        'User',
        'Systemsetting',
        'Tenant',
        'Usergroup',
        'ContainerUserMembership',
    ];
    public $components = [
        'ListFilter.ListFilter',
        'Ldap',
    ];

    public $helpers = [
        'ListFilter.ListFilter',
    ];


    public $listFilters = [
        'index' => [
            'fields' => [
                'User.full_name' => ['label' => 'Name', 'searchType' => 'wildcard'],
                'User.email'     => ['label' => 'Email', 'searchType' => 'wildcard'],
                'User.company'   => ['label' => 'Company', 'searchType' => 'wildcard'],
                'User.phone'     => ['label' => 'Phone', 'searchType' => 'wildcard'],
            ]
        ]
    ];


    public function index() {
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

    public function view($id = null) {
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
     * @param  int $id
     * @param  boolean $cascade
     *
     * @return bool
     */

    public function delete($id = null) {
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

    public function add($type = 'local') {
        $CSRF = new CSRF($this->Session);
        $_csrfToken = $CSRF->generateToken();
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

            $PHPVersionChecker = new PHPVersionChecker();
            if ($PHPVersionChecker->isVersionGreaterOrEquals7Dot1()) {
                $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
                require_once APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';

                $ldap = new \FreeDSx\Ldap\LdapClient([
                    'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                    'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                    'ssl_allow_self_signed' => true,
                    'ssl_validate_cert'     => false,
                    'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                    'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
                ]);
                if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                    $ldap->startTls();
                }
                $ldap->bind(
                    sprintf(
                        '%s%s',
                        $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                        $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                    ),
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
                );

                $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                    \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                    \FreeDSx\Ldap\Search\Filters::equal('sAMAccountName', $samaccountname)
                );
                if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                    $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
                    $search = FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn');
                } else {
                    $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
                    $search = FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn');
                }

                /** @var \FreeDSx\Ldap\Entry\Entries $entries */
                $entries = $ldap->search($search);
                foreach ($entries as $entry) {
                    $userDn = (string)$entry->getDn();
                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));

                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }

                    $ldapUser = [
                        'mail'           => $entry['mail'][0],
                        'givenname'      => $entry['givenname'][0],
                        'sn'             => $entry['sn'][0],
                        'samaccountname' => $entry['samaccountname'][0],
                        'dn'             => $userDn
                    ];
                }

            } else {
                $ldapUser = $this->Ldap->userInfo($samaccountname);
            }
            if (!is_null($ldapUser)) {
                // Overwrite request with LDAP data, that the user can not manipulate it with firebug ;)
                $this->request->data['User']['email'] = $ldapUser['mail'];
                $this->request->data['User']['firstname'] = $ldapUser['givenname'];
                $this->request->data['User']['lastname'] = $ldapUser['sn'];
                $this->request->data['User']['samaccountname'] = strtolower($ldapUser['samaccountname']);
                $this->request->data['User']['ldap_dn'] = $ldapUser['dn'];
            }
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $CSRF->validateCsrfToken($this);
            $CSRF->generateToken();

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
        $this->set(compact(['containers', 'usergroups', '_csrfToken']));

        $this->set('type', $type);
    }


    public function edit($id = null) {
        $CSRF = new CSRF($this->Session);
        $_csrfToken = $CSRF->generateToken();

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
            $CSRF->validateCsrfToken($this);
            $CSRF->generateToken();

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
        $options = ['conditions' => ['User.' . $this->User->primaryKey => $id]];
        $this->request->data = $this->User->find('first', $options);
        $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_USER, [], $this->hasRootPrivileges);
        $selectedContainers = ($this->request->data('Container')) ? Hash::extract($this->request->data['Container'], '{n}.id') : Hash::extract($permissionsUser['ContainerUserMembership'], '{n}.container_id');
        $this->set(compact(['containers', 'selectedContainers', 'permissionsUser']));
        $this->request->data['User']['password'] = '';

        $type = 'local';
        if (strlen($this->request->data['User']['samaccountname']) > 0) {
            $type = 'ldap';
        }
        $this->set(compact(['type', 'usergroups', '_csrfToken']));
    }

    public function addFromLdap() {
        $this->layout = 'angularjs';

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

        $PHPVersionChecker = new PHPVersionChecker();
        if ($PHPVersionChecker->isVersionGreaterOrEquals7Dot1()) {
            $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
            require_once APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';

            $ldap = new \FreeDSx\Ldap\LdapClient([
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }
            $ldap->bind(
                sprintf(
                    '%s%s',
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                ),
                $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
            );

            $filter = \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']);
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            } else {
                $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            }

            $getAll = false;
            if ($this->request->query('getAll') === 'true') {
                $getAll = true;
            }

            $usersForSelect = [];
            $paging = $ldap->paging($search, 100);
            while ($paging->hasEntries()) {
                foreach ($paging->getEntries() as $entry) {
                    $userDn = (string)$entry->getDn();
                    if (empty($userDn)) {
                        continue;
                    }

                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                    foreach ($requiredFields as $requiredField) {
                        if (!isset($entry[$requiredField])) {
                            continue 2;
                        }
                    }

                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }

                    $displayName = sprintf(
                        '%s, %s (%s)',
                        $entry['givenname'][0],
                        $entry['sn'][0],
                        $entry['samaccountname'][0]
                    );
                    $usersForSelect[$entry['samaccountname'][0]] = $displayName;
                }
                if ($getAll === false) {
                    //Only get the first few records
                    $paging->end();
                }
            }
        } else {
            $usersForSelect = $this->Ldap->findAllUser();
            $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
        }

        $usersForSelect = $this->User->makeItJavaScriptAble($usersForSelect);

        $isPhp7Dot1 = $PHPVersionChecker->isVersionGreaterOrEquals7Dot1();
        $this->set(compact(['usersForSelect', 'systemsettings', 'isPhp7Dot1', '_csrfToken']));
        $this->set('_serialize', ['usersForSelect', 'isPhp7Dot1']);
    }

    public function loadLdapUserByString() {
        $this->layout = 'blank';

        $usersForSelect = [];
        $samaccountname = $this->request->query('samaccountname');
        if (!empty($samaccountname) && strlen($samaccountname) > 2) {
            $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
            require_once APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';

            $ldap = new \FreeDSx\Ldap\LdapClient([
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }
            $ldap->bind(
                sprintf(
                    '%s%s',
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                ),
                $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
            );

            $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                \FreeDSx\Ldap\Search\Filters::contains('sAMAccountName', $samaccountname)
            );
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                    \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                    \FreeDSx\Ldap\Search\Filters::contains('uid', $samaccountname)
                );
            }
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            } else {
                $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            }

            $paging = $ldap->paging($search, 100);
            while ($paging->hasEntries()) {
                foreach ($paging->getEntries() as $entry) {
                    $userDn = (string)$entry->getDn();
                    if (empty($userDn)) {
                        continue;
                    }

                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                    foreach ($requiredFields as $requiredField) {
                        if (!isset($entry[$requiredField])) {
                            continue 2;
                        }
                    }

                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }

                    $displayName = sprintf(
                        '%s, %s (%s)',
                        $entry['givenname'][0],
                        $entry['sn'][0],
                        $entry['samaccountname'][0]
                    );
                    $usersForSelect[$entry['samaccountname'][0]] = $displayName;
                }
                $paging->end();
            }
        }

        $usersForSelect = $this->User->makeItJavaScriptAble($usersForSelect);

        $this->set('usersForSelect', $usersForSelect);
        $this->set('_serialize', ['usersForSelect']);
    }

    public function resetPassword($id = null) {
        $this->autoRender = false;
        if (!$this->User->exists($id)) {
            $this->setFlash(__('Invalid user'), false);
            $this->redirect(['action' => 'index']);

            return;
        }

        $user = $this->User->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'User.id' => $id
            ]
        ]);
        $generatePassword = function () {
            $char = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $size = (sizeof($char) - 1);
            $token = '';
            for ($i = 0; $i < 7; $i++) {
                $token .= $char[rand(0, $size)];
            }
            $token = $token . rand(0, 9);

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
        $this->User->id = $id;
        if ($this->User->saveField('password', AuthComponent::password($newPassword))) {
            $Email->send();
            $this->setFlash(__('Password reset successfully. A mail with the new password was sent to <b>' . h($user['User']['email']) . '</b>'));
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

        $containerId = (int)$this->request->query('containerId');
        $containers = [ROOT_CONTAINER];
        if (in_array($containerId, $this->MY_RIGHTS, true)) {
            $containers[] = $containerId;
        }
        $users = $this->User->find('list', [
            'recursive'  => -1,
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
            ],
            'group'      => 'User.id'
        ]);
        $users = $this->User->makeItJavaScriptAble(
            $users
        );

        $this->set(compact(['users']));
        $this->set('_serialize', ['users']);
    }
}
