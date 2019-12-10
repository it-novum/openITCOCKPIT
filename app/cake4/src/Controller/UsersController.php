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

use App\Model\Entity\User;
use App\Model\Table\ContainersTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsercontainerrolesTable;
use App\Model\Table\UsergroupsTable;
use App\Model\Table\UsersTable;
use Authentication\Authenticator\ResultInterface;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\LoginBackgrounds;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;
use itnovum\openITCOCKPIT\Ldap\LdapClient;


/**
 * Class UsersController
 * @package App\Controller
 * @property AuthenticationComponent $Authentication
 */
class UsersController extends AppController {

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login']);
    }

    public function login() {
        $this->viewBuilder()->setLayout('login');
        $LoginBackgrounds = new LoginBackgrounds();
        $images = $LoginBackgrounds->getImages();

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $disableAnimation = $SystemsettingsTable->isLoginAnimationDisabled();

        if ($this->getRequest()->getQuery('remote')) {
            $disableAnimation = true;
        }

        if ($disableAnimation) {
            $images['particles'] = 'none';
        }

        $this->set('_csrfToken', $this->request->getParam('_csrfToken'));
        $this->set('images', $images);
        $this->set('disableAnimation', $disableAnimation);

        if ($this->request->is('get')) {
            $this->viewBuilder()->setOption('serialize', ['_csrfToken', 'images']);
            return;
        }

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post')) {
            // Docs: https://book.cakephp.org/authentication/1/en/index.html

            $result = $this->Authentication->getResult();
            if ($result->getStatus() === ResultInterface::SUCCESS) {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }

            $this->RequestHandler->renderAs($this, 'json');
            $this->response = $this->response->withStatus(400);
            $errors = $result->getErrors();
            $this->set('success', false);
            $this->set('errors', $errors);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
        }
    }

    public function logout() {
        $this->Authentication->logout();
        $this->redirect([
            'action' => 'login'
        ]);
    }

    public function index() {
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            $this->set('isLdapAuth', $SystemsettingsTable->isLdapAuth());
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->getUser());

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $UsersFilter = new UsersFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $UsersFilter->getPage());
        $all_tmp_users = $UsersTable->getUsersIndex($UsersFilter, $PaginateOMat, $this->MY_RIGHTS);

        $all_users = [];
        foreach ($all_tmp_users as $index => $_user) {
            /** @var User $_user */
            $user = $_user->toArray();
            $user['allow_edit'] = $this->hasRootPrivileges;

            if ($this->hasRootPrivileges === false) {
                //Check permissions for non ROOT Users
                $containerWithWritePermissionByUserContainerRoles = Hash::extract(
                    $user['usercontainerroles'],
                    '{n}.containers.{n}._joinData.container_id'
                );

                $container = Hash::extract(
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
        $this->viewBuilder()->setOption('serialize', ['all_users', 'myUserId']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->getData('User', []);
            if (!isset($data['ContainersUsersMemberships'])) {
                $data['ContainersUsersMemberships'] = [];
            }
            $data['containers'] = $UsersTable->containerPermissionsForSave($data['ContainersUsersMemberships']);

            $user = $UsersTable->newEmptyEntity();
            $user = $UsersTable->patchEntity($user, $data);
            $UsersTable->save($user);
            if ($user->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $user->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']);
        }
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var UsersTable $UsersTable */
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
            //Return user information
            $this->set('user', $user['User']);
            $this->set('isLdapUser', $isLdapUser);
            $this->viewBuilder()->setOption('serialize', ['user', 'isLdapUser']);
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->getData('User', []);
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
                $this->response = $this->response->withStatus(400);
                $this->set('error', $user->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']);
        }
    }


    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->getUser());

        if ($id == $User->getId()) {
            throw new \RuntimeException('You cannot delete yourself!');
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
            $this->viewBuilder()->setOption('serialize', ['success']);

            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }

    public function addFromLdap() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->getData('User', []);
            $data['is_ldap'] = true;
            if (!isset($data['ContainersUsersMemberships'])) {
                $data['ContainersUsersMemberships'] = [];
            }
            $data['containers'] = $UsersTable->containerPermissionsForSave($data['ContainersUsersMemberships']);

            //remove password validation when user is imported from ldap
            $UsersTable->getValidator()->remove('password');
            $UsersTable->getValidator()->remove('confirm_password');

            $user = $UsersTable->newEmptyEntity();
            $user = $UsersTable->patchEntity($user, $data);
            $UsersTable->save($user);
            if ($user->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $user->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']);
        }
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    /**
     * @param int|null $id
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

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        $Logo = new Logo();

        $Mailer = new Mailer();
        $Mailer->setFrom($systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'], $systemsettings['MONITORING']['MONITORING.FROM_NAME']);
        $Mailer->addTo($user->get('email'));
        $Mailer->setSubject(__('Your ') . $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME']. __(' got reset!'));
        $Mailer->setEmailFormat('text');
        $Mailer->setAttachments([
            'logo.png' => [
                'file' => $Logo->getSmallLogoDiskPath(),
                'mimetype' => 'image/png',
                'contentId' => '100'
            ]
        ]);
        $Mailer->viewBuilder()
            ->setTemplate('reset_password')
            ->setVar('systemname', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])
            ->setVar('newPassword', $newPassword);

        $user->set('password', $newPassword);

        $UsersTable->save($user);
        if ($user->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $user->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $Mailer->deliver();
        $this->set('message', __('Password reset successfully. The new password was send to {0}', $user->email));
        $this->viewBuilder()->setOption('serialize', ['message']);

    }

    /**
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    public function loadLdapUserByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $Ldap = LdapClient::fromSystemsettings($SystemsettingsTable->findAsArraySection('FRONTEND'));

        $samaccountname = (string)$this->request->getQuery('samaccountname', '');
        $ldapUsers = $Ldap->getUsers($samaccountname);
        $this->set('ldapUsers', $ldapUsers);
        $this->viewBuilder()->setOption('serialize', ['ldapUsers']);
    }

    public function loadUsersByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containerId = (int)$this->request->getQuery('containerId', 0);
        // Due to the only filter condition is the container_id (no LIMIT or LIKE in SQL )
        // I think the selected parameter is not required
        //$selected = $this->request->query('selected');

        if ($containerId === 0) {
            //Missing parameter in URL
            $containerId = ROOT_CONTAINER;
        }
        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $users = Api::makeItJavaScriptAble(
            $UsersTable->getUsersByContainerIds($containerIds, 'list')
        );

        $this->set('users', $users);
        $this->viewBuilder()->setOption('serialize', ['users']);
    }

    public function loadUsergroups() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        $_usergroups = $UsergroupsTable->find('list')->toArray();

        //Rewrite hashmap to array because javascript will break the "orderd hashmap" because hashmaps have no order
        $usergroups = [];
        foreach ($_usergroups as $id => $name) {
            $usergroups[] = [
                'key'   => $id,
                'value' => $name
            ];
        }

        $this->set('usergroups', $usergroups);
        $this->viewBuilder()->setOption('serialize', ['usergroups']);
    }

    public function loadDateformats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $dateformats = $UsersTable->getDateformats();
        $options = [];

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->getUser());

        foreach ($dateformats as $dateformat) {
            $UserTime = new UserTime($User->getTimezone(), $dateformat);
            $options[$dateformat] = $UserTime->format(time());
        }
        $dateformats = Api::makeItJavaScriptAble($options);
        $defaultDateFormat = 'H:i:s - d.m.Y'; // key 10

        $this->set('dateformats', $dateformats);
        $this->set('defaultDateFormat', $defaultDateFormat);
        $this->viewBuilder()->setOption('serialize', ['dateformats', 'defaultDateFormat']);
    }

    public function loadContainerRoles() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

        $ucr = Api::makeItJavaScriptAble(
            $UsercontainerrolesTable->getUsercontainerrolesAsList($this->MY_RIGHTS)
        );
        $this->set('usercontainerroles', $ucr);
        $this->viewBuilder()->setOption('serialize', ['usercontainerroles']);
    }

    public function loadContainerPermissions() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $usercontainerRoleIds = $this->request->getQuery('usercontainerRoleIds', null);
        if ($usercontainerRoleIds === null) {
            $usercontainerRoleIds = [];
        }
        if (!is_array($usercontainerRoleIds)) {
            $usercontainerRoleIds = [$usercontainerRoleIds];
        }

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
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
        $this->viewBuilder()->setOption('serialize', ['userContainerRoleContainerPermissions']);
    }

}
