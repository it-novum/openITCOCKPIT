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
class UsercontainerrolesController extends AppController {
    public $layout = 'blank';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }
        $userId = $this->Auth->User('id');

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
        $this->set('userId', $userId);
        $toJson = ['all_users', 'paging', 'userId'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_users', 'scroll', 'userId'];
        }
        $this->set('_serialize', $toJson);
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

            $this->request->data = $this->request->data('User');

            $user = $Users->get($id);
            //prevent multiple hash of password
            if (empty($this->request->data('password'))) {
                unset($user->password);
            }
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
}
