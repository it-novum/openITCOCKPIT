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

use App\Model\Table\ContainersTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsercontainerrolesTable;
use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;
use itnovum\openITCOCKPIT\Ldap\LdapClient;

/**
 * Class UsersController
 * @property AppPaginatorComponent $Paginator
 * @property DbBackend $DbBackend
 * @property AppAuthComponent $Auth
 */
class UsersController extends AppController {

    public $layout = 'blank';

    public function index() {
        /** @var $SystemsettingsTable SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            $this->set('isLdapAuth', $SystemsettingsTable->isLdapAuth());
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $UsersTable App\Model\Table\UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $UsersFilter = new UsersFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $UsersFilter->getPage());
        $all_tmp_users = $UsersTable->getUsersIndex($UsersFilter, $PaginateOMat, $this->MY_RIGHTS);


        $all_users = [];
        foreach ($all_tmp_users as $index => $_user) {
            $user = $_user->toArray();
            $user['allow_edit'] = $this->hasRootPrivileges;

            if ($this->hasRootPrivileges === false) {
                //Check permissions for non ROOT Users
                $containerWithWritePermissionByUserContainerRoles = \Cake\Utility\Hash::extract(
                    $user['usercontainerroles'],
                    '{n}.containers.{n}._joinData.container_id'
                );

                $container = \Cake\Utility\Hash::extract(
                    $user['containers'],
                    '{n}.id'
                );

                $container = array_unique(array_merge($container, $containerWithWritePermissionByUserContainerRoles));
                foreach ($container as $containerId) {
                    if ($this->isWritableContainer($containerId)) {
                        $user['allow_edit'] = true;
                        break;
                    }
                }
            }
            $all_users[] = $user;
        }

        $this->set('all_users', $all_users);
        $this->set('myUserId', $User->getId());
        $toJson = ['all_users', 'paging', 'myUserId'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_users', 'scroll', 'myUserId'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param int|null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->getUserForEdit($id);
        $containersToCheck = array_unique(array_merge(
            $user['User']['usercontainerroles_containerids']['_ids'], //Container Ids through Container Roles
            $user['User']['containers']['_ids']) //Containers defined by the user itself
        );

        if (!$this->allowedByContainerId($containersToCheck, false)) {
            $this->render403();
            return;
        }

        $this->set('user', $user['User']);
        $this->set('_serialize', ['user']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $UsersTable App\Model\Table\UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data('User');
            if (!isset($data['ContainersUsersMemberships'])) {
                $data['ContainersUsersMemberships'] = [];
            }
            $data['containers'] = $UsersTable->containerPermissionsForSave($data['ContainersUsersMemberships']);

            $user = $UsersTable->newEntity();
            $user = $UsersTable->patchEntity($user, $data);
            $UsersTable->save($user);
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


    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->getUserForEdit($id);
        $containersToCheck = array_unique(array_merge(
            $user['User']['usercontainerroles_containerids']['_ids'], //Container Ids through Container Roles
            $user['User']['containers']['_ids']) //Containers defined by the user itself
        );

        if (!$this->allowedByContainerId($containersToCheck)) {
            $this->render403();
            return;
        }

        $isLdapUser = !empty($user['User']['samaccountname']);

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $this->set('user', $user['User']);
            $this->set('isLdapUser', $isLdapUser);
            $this->set('_serialize', ['user', 'isLdapUser']);
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data('User');
            if (!isset($data['ContainersUsersMemberships'])) {
                $data['ContainersUsersMemberships'] = [];
            }
            $data['containers'] = $UsersTable->containerPermissionsForSave($data['ContainersUsersMemberships']);
            $user = $UsersTable->get($id);
            $user->setAccess('id', false);

            if ($isLdapUser) {
                $data['is_ldap'] = true;
                $user->setAccess('email', false);
                $user->setAccess('firstname', false);
                $user->setAccess('lastname', false);
                $user->setAccess('password', false);
                $user->setAccess('samaccountname', false);
                $user->setAccess('ldap_dn', false);
            }

            //prevent multiple hash of password
            if ($data['password'] === '' && $data['confirm_password'] === '') {
                unset($data['password']);
                unset($data['confirm_password']);
            }

            $user = $UsersTable->patchEntity($user, $data);
            $UsersTable->save($user);
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

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        if ($id == $User->getId()) {
            throw new RuntimeException('You cannot delete yourself!');
        }

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->getUserForEdit($id);
        $containersToCheck = array_unique(array_merge(
            $user['User']['usercontainerroles_containerids']['_ids'], //Container Ids through Container Roles
            $user['User']['containers']['_ids']) //Containers defined by the user itself
        );

        if (!$this->allowedByContainerId($containersToCheck)) {
            $this->render403();
            return;
        }

        $user = $UsersTable->get($id);
        if ($UsersTable->delete($user)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;
    }

    public function addFromLdap() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $UsersTable App\Model\Table\UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data('User');
            $data['is_ldap'] = true;
            if (!isset($data['ContainersUsersMemberships'])) {
                $data['ContainersUsersMemberships'] = [];
            }
            $data['containers'] = $UsersTable->containerPermissionsForSave($data['ContainersUsersMemberships']);

            //remove password validation when user is imported from ldap
            $UsersTable->getValidator()->remove('password');
            $UsersTable->getValidator()->remove('confirm_password');

            $user = $UsersTable->newEntity();
            $user = $UsersTable->patchEntity($user, $data);
            $UsersTable->save($user);
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


    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @param null $id
     */
    public function resetPassword($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->getUserForEdit($id);
        $containersToCheck = array_unique(array_merge(
            $user['User']['usercontainerroles_containerids']['_ids'], //Container Ids through Container Roles
            $user['User']['containers']['_ids']) //Containers defined by the user itself
        );

        if (!$this->allowedByContainerId($containersToCheck)) {
            $this->render403();
            return;
        }

        $user = $UsersTable->get($id);
        $newPassword = $UsersTable->generatePassword();

        $user->set('password', $newPassword);

        /** @var $SystemsettingsTable App\Model\Table\SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
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

        $UsersTable->save($user);
        if ($user->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $user->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $Email->send();
        $this->set('message', __('Password reset successfully. The new password was send to %s', $user->email));
        $this->set('_serialize', ['message']);
    }

    public function loadDateformats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var $UsersTable App\Model\Table\UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $dateformats = $UsersTable->getDateformats();
        $options = [];
        foreach ($dateformats as $dateformat) {
            $UserTime = new UserTime($this->Auth->user('timezone'), $dateformat);
            $options[$dateformat] = $UserTime->format(time());
        }
        $dateformats = Api::makeItJavaScriptAble($options);
        $defaultDateFormat = '%H:%M:%S - %d.%m.%Y'; // key 10

        $this->set('dateformats', $dateformats);
        $this->set('defaultDateFormat', $defaultDateFormat);
        $this->set('_serialize', ['dateformats', 'defaultDateFormat']);
    }

    /**
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    public function loadLdapUserByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $SystemsettingsTable App\Model\Table\SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $Ldap = LdapClient::fromSystemsettings($SystemsettingsTable->findAsArraySection('FRONTEND'));

        $samaccountname = (string)$this->request->query('samaccountname');
        $ldapUsers = $Ldap->getUsers($samaccountname);
        $this->set('ldapUsers', $ldapUsers);
        $this->set('_serialize', ['ldapUsers']);
    }

    public function loadUsersByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var $UsersTable App\Model\Table\UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containerId = (int)$this->request->query('containerId');
        // Due to the only filter condition is the container_id (no LIMIT or LIKE in SQL )
        // I think the selected parameter is not required
        //$selected = $this->request->query('selected');

        if($containerId === 0){
            //Missing parameter in URL
            $containerId = ROOT_CONTAINER;
        }
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $users = Api::makeItJavaScriptAble(
            $UsersTable->getUsersByContainerIds($containerIds, 'list')
        );

        $this->set('users', $users);
        $this->set('_serialize', ['users']);
    }

    public function loadUsergroups() {
        /** @var $UsergroupsTable App\Model\Table\UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');
        $usergroups = $UsergroupsTable->getUsergroupsList();

        $usergroups = Api::makeItJavaScriptAble($usergroups);

        $this->set('usergroups', $usergroups);
        $this->set('_serialize', ['usergroups']);
    }

    public function loadContainerRoles() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $UsercontainerrolesTable UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

        $ucr = Api::makeItJavaScriptAble(
            $UsercontainerrolesTable->getUsercontainerrolesAsList($this->MY_RIGHTS)
        );
        $this->set('usercontainerroles', $ucr);
        $this->set('_serialize', ['usercontainerroles']);
    }

    public function loadContainerPermissions() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $usercontainerRoleIds = $this->request->query('usercontainerRoleIds');
        if ($usercontainerRoleIds === null) {
            $usercontainerRoleIds = [];
        }
        if (!is_array($usercontainerRoleIds)) {
            $usercontainerRoleIds = [$usercontainerRoleIds];
        }

        /** @var $UsercontainerrolesTable UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');
        $containerPermissions = $UsercontainerrolesTable->getContainerPermissionsByUserContainerRoleIds($usercontainerRoleIds);

        //Merge the permissions of all user container roles together.
        //WRITE_RIGHT will overwrite a READ_RIGHT

        $permissions = [];
        foreach ($containerPermissions as $userContainerRole) {
            foreach ($userContainerRole['containers'] as $container) {
                if (isset($permissions[$container['id']])) {
                    //Container permission is already set.
                    //Only overwrite it, if it is a WRITE_RIGHT
                    if ($container['_joinData']['permission_level'] === WRITE_RIGHT) {
                        $permissions[$container['id']] = $container;
                    }
                } else {
                    //Container is not yet in permissions - add it
                    $permissions[$container['id']] = $container;
                }
            }
        }

        $this->set('userContainerRoleContainerPermissions', $permissions);
        $this->set('_serialize', ['userContainerRoleContainerPermissions']);
    }
}
