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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;
use itnovum\openITCOCKPIT\Ldap\LdapClient;

/**
 * Class UsersController
 * @property User $User
 */
class UsersController extends AppController {
    public $layout = 'blank';
    public $components = [
        'Ldap',
    ];

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        $usersFilter = new UsersFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $usersFilter->getPage());
        $all_users = $Users->getUsers($this->MY_RIGHTS, $usersFilter, $PaginateOMat);

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
        $this->set('all_users', $all_users);
        $toJson = ['all_users', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_users', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        if (!$Users->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User');
        }
        $user = $Users->getUserWithContainerPermission($id, $this->MY_RIGHTS);
        if (is_null($user)) {
            $this->render403();
            return;
        }
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }


    /**
     * @param null $id
     */
    public function delete($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        if (!$Users->existsById($id)) {
            throw new MethodNotAllowedException();
        }
        $user = $Users->get($id);
        if (!$this->allowedByContainerId($user->id)) {
            $this->render403();
            return;
        }

        if ($Users->delete($user)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

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
    }

    public function loadDateformats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
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
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        if (!$Users->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User');
        }

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
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');
        $this->set('systemsettings', $systemsettings);
        $this->set('_serialize', ['systemsettings']);


        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post') || $this->request->is('put')) {

            // save additional data to containersUsersMemberships
            if (isset($this->request->data['User']['ContainersUsersMemberships'])) {
                $containerPermissions = $Users->containerPermissionsForSave($this->request->data['User']['ContainersUsersMemberships']);
                $this->request->data['User']['containers'] = $containerPermissions;
            }

            //@TODO remove these lines as they are implemented in users add
            $this->request->data['User']['status'] = 1;

            $this->request->data = $this->request->data('User');

            //remove password validation when user is imported from ldap
            $Users->getValidator()->remove('password');
            $Users->getValidator()->remove('confirm_password');


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


    }

    public function loadLdapUserByString() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $Ldap = LdapClient::fromSystemsettings($Systemsettings->findAsArraySection('FRONTEND'));
        $samaccountname = (string)$this->request->query('samaccountname');
        $usersForSelect = $Ldap->getUsers($samaccountname);
        $this->set('usersForSelect', $usersForSelect);
        $this->set('_serialize', ['usersForSelect']);
    }

    public function resetPassword($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');

        if (!$Users->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User');
        }

        $user = $Users->get($id);
        $newPassword = $Users->generatePassword();

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();

        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($user->email);
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

        $user->password = $newPassword;

        $Users->save($user);
        if ($user->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $user->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $Email->send();
        $this->set('user', []);
        $this->set('_serialize', ['user']);
    }


    public function loadUsersByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $this->request->data['container_ids'] = 1;
        $users = [];
        if (isset($this->request->data['container_ids'])) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->request->data['container_ids']);
            $users = $this->User->usersByContainerId($containerIds, 'list');
            $users = Api::makeItJavaScriptAble($users);
        }

        $data = [
            'users' => $users,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }


    /**
     * get all possible states a user can have
     */
    public function loadStatus() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $status = $Users->getUserStatus();
        $status = Api::makeItJavaScriptAble($status);

        $this->set('status', $status);
        $this->set('_serialize', ['status']);
    }
}
