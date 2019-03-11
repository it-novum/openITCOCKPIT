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

use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\PHPVersionChecker;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;

/**
 * Class UsersController
 * @property User $User
 */
class UsersController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        'User',
        'Systemsetting',
        'Tenant',
        'Usergroup',
        //'ContainerUserMembership',
    ];
    public $components = [
        'Ldap',
    ];


    public function index() {
        $this->layout = 'blank';
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');

        $usersFilter = new UsersFilter($this->request);

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $usersFilter->getPage());
        $all_users = $Users->getUsers($this->MY_RIGHTS, $PaginateOMat);
        $isLdapAuth = false;
        if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap') {
            $isLdapAuth = true;
        }

        foreach ($all_users as $index => $user) {
            $all_users[$index]['User']['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                foreach ($user['Container'] as $key => $container) {
                    if ($this->isWritableContainer($container['id'])) {
                        $all_users[$index]['User']['allow_edit'] = $this->isWritableContainer($container['id']);
                        break;
                    }
                    $all_users[$index]['User']['allow_edit'] = false;
                }
            }
        }

        $this->set('isLdapAuth', $isLdapAuth);
        $this->set('systemsettings', $systemsettings);
        $this->set('all_users', $all_users);
        $toJson = ['all_users', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_users', 'scroll'];
        }
        $this->set('_serialize', ['toJson', 'all_users', 'systemsettings', 'isLdapAuth']);
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
            return false;
        }

        if ($this->User->__delete($id, $id)) {
            $this->setFlash(__('User deleted'));
            $this->redirect(['action' => 'index']);
        } else {
            $this->setFlash(__('Could not delete user'), false);
            $this->redirect(['action' => 'index']);
        }
    }


    public function add() {
        $this->layout = 'blank';
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        /** @var $Usergroups App\Model\Table\UsergroupsTable */
        $Usergroups = TableRegistry::getTableLocator()->get('Usergroups');
        $usergroups = $Usergroups->getUsergroupsList();

        if ($this->request->is('post') || $this->request->is('put')) {

            // save additional data to containersUsersMemberships
            if (isset($this->request->data['User']['ContainersUsersMemberships'])) {
                $containerPermissions = $Users->containerPermissionsForSave($this->request->data['User']['ContainersUsersMemberships']);
                $this->request->data['User']['containers'] = $containerPermissions;
            }

            //@TODO remove these lines as they are implemented in users add
            $this->request->data['User']['status'] = 1;

            $this->request->data = $this->request->data('User');

            $user = $Users->newEntity();
            $user = $Users->patchEntity($user, $this->request->data);

            $Users->save($user);
            if ($user->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $user->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }
            $this->set('user', $user);
            $this->set('_serialize', ['user']);
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_USER, [], $this->hasRootPrivileges);


        $this->set('containers', $containers);
        $this->set('usergroups', $usergroups);
        $this->set('_serialize', ['containers', 'usergroups']);
    }

    public function loadDateformats() {
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $dateformats = $Users->getDateformats();
        $options = [];
        foreach ($dateformats as $dateformat) {
            $ut = new UserTime($this->Auth->user('timezone'), $dateformat);
            $options[$dateformat] = $ut->format(time());
        }
        $dateformats = Api::makeItJavaScriptAble($options);
        $defaultDateFormat = '%H:%M:%S - %d.%m.%Y'; // key 10

        $this->set('dateformats', $dateformats);
        $this->set('defaultDateFormat', $defaultDateFormat);
        $this->set('_serialize', ['dateformats', 'defaultDateFormat']);
    }


    public function edit($id = null) {
        $this->layout = 'blank';
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->getUserWithContainerPermission($id, $this->MY_RIGHTS);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);

        if ($this->request->is('post') || $this->request->is('put')) {

            // save additional data to containersUsersMemberships
            if (isset($this->request->data['User']['ContainersUsersMemberships'])) {
                $containerPermissions = $Users->containerPermissionsForSave($this->request->data['User']['ContainersUsersMemberships']);
                $this->request->data['User']['containers'] = $containerPermissions;
            }

            //@TODO remove these lines as they are implemented in users edit
            $this->request->data['User']['status'] = 1;

            $this->request->data = $this->request->data('User');

            $user = $Users->get($id);
            $user = $Users->patchEntity($user, $this->request->data);

            $Users->save($user);
            if ($user->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $user->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }
            $this->set('user', $user);
            $this->set('_serialize', ['user']);
        }
    }


    public function addFromLdap() {
        $this->layout = 'angularjs';


        /**
         * LDAP Start
         */
        if (isset($this->request->params['named']['ldap']) && $this->request->params['named']['ldap'] == true) {
            $type = 'ldap';
            $samaccountname = '';
            if (isset($this->request->params['named']['samaccountname'])) {
                $samaccountname = $this->request->params['named']['samaccountname'];
            }
        }
        /**
         * LDAP END
         */

        /**
         * LDAP START
         */
        if ($type == 'ldap') {

            $PHPVersionChecker = new PHPVersionChecker();
            if ($PHPVersionChecker->isVersionGreaterOrEquals7Dot1()) {
                /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
                $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
                $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');

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

                $filter = \FreeDSx\Ldap\Search\Filters::and(
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
        /**
         * LDAP END
         */


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
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');

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
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');
        }

        $usersForSelect = Api::makeItJavaScriptAble($usersForSelect);

        $isPhp7Dot1 = $PHPVersionChecker->isVersionGreaterOrEquals7Dot1();
        $this->set(compact(['usersForSelect', 'systemsettings', 'isPhp7Dot1']));
        $this->set('_serialize', ['usersForSelect', 'isPhp7Dot1']);
    }

    public function loadLdapUserByString() {
        $this->layout = 'blank';

        $usersForSelect = [];
        $samaccountname = $this->request->query('samaccountname');
        if (!empty($samaccountname) && strlen($samaccountname) > 2) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');

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

            $filter = \FreeDSx\Ldap\Search\Filters::and(
                \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                \FreeDSx\Ldap\Search\Filters::contains('sAMAccountName', $samaccountname)
            );
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $filter = \FreeDSx\Ldap\Search\Filters::and(
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

        $usersForSelect = Api::makeItJavaScriptAble($usersForSelect);

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
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();

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
        $users = Api::makeItJavaScriptAble(
            $users
        );

        $this->set(compact(['users']));
        $this->set('_serialize', ['users']);
    }

    /**
     * get all possible states a user can have
     */
    public function loadStatus() {
        $this->layout = 'blank';
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $status = $Users->getUserStatus();
        $status = Api::makeItJavaScriptAble($status);

        $this->set('status', $status);
        $this->set('_serialize', ['status']);
    }
}
