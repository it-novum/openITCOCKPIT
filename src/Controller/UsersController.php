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
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Locales;
use itnovum\openITCOCKPIT\Core\LoginBackgrounds;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsersFilter;
use itnovum\openITCOCKPIT\Ldap\LdapClient;
use itnovum\openITCOCKPIT\oAuth\oAuthClient;


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

        $isSsoEnabled = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.AUTH_METHOD')->get('value') === 'sso';
        $redirectToSsoLoginPage = $this->request->getQuery('redirect_sso', null) === 'true';

        $forceRedirectSsousersToLoginScreen = false;
        if ($isSsoEnabled === true) {
            $forceRedirectSsousersToLoginScreen = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.SSO.FORCE_USER_TO_LOGINPAGE')->get('value') === '1';
        }

        if ($redirectToSsoLoginPage === true) {
            $oAuthClient = new oAuthClient();

            $authorizationUrl = $oAuthClient->getAuthorizationUrl();
            $session = $this->request->getSession();

            //Save current state to $_SESSION to mitigate CSRF attack
            $session->write('oauth2state', $oAuthClient->getState());

            $this->redirect($authorizationUrl);
            return;
        }

        $disableAnimation = $SystemsettingsTable->isLoginAnimationDisabled();
        if ($this->getRequest()->getQuery('remote')) {
            $disableAnimation = true;
        }

        if ($disableAnimation) {
            $images['particles'] = 'none';
        }


        $hasValidSslCertificate = false;
        if (isset($_SERVER['SSL_VERIFIED']) && $_SERVER['SSL_VERIFIED'] === 'SUCCESS' && isset($_SERVER['SSL_CERT'])) {
            $hasValidSslCertificate = $this->getUser() !== null;
        }

        $isLoggedIn = $identy = $this->getUser() !== null;
        $errorMessages = [];

        if ($this->request->is('GET') && $this->request->getQuery('code', null) !== null && $isSsoEnabled) {
            // The user came back from the oAuth login page.
            // Check if we have any errors during the oAuth login
            $result = $this->Authentication->getResult();
            if ($result->getStatus() !== ResultInterface::SUCCESS) {
                if ($result->getStatus() === 'FAILURE_IDENTITY_NOT_FOUND') {

                    // The browser fires two requests.
                    // One is is GET request, when the user gets redirrected from the oAuth Login Page back to openITCOCKPIT.
                    // This is the request where the oauth Login happens.
                    //
                    // The second request is also a GET Request done by Angular to query status information.
                    // S we need to store any errors to the session that we can return errors for angularjs
                    $Session = $this->request->getSession();
                    try {
                        $oauth_error = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.SSO.NO_EMAIL_MESSAGE')->get('value');
                    } catch (RecordNotFoundException $e) {
                        // Only a fallback if the key is missing in the database
                        $oauth_error = __('E-Mail address not found in openITCOCKPIT Database.');
                    }
                    $Session->write('oauth_user_not_found', $oauth_error);
                }
            }
        }

        $this->set('_csrfToken', $this->request->getParam('_csrfToken'));
        $this->set('images', $images);
        $this->set('disableAnimation', $disableAnimation);
        $this->set('hasValidSslCertificate', $hasValidSslCertificate);
        $this->set('isSsoEnabled', $isSsoEnabled);
        $this->set('forceRedirectSsousersToLoginScreen', $forceRedirectSsousersToLoginScreen);
        $this->set('isLoggedIn', $isLoggedIn);

        if ($this->request->is('get')) {
            if ($this->isJsonRequest()) {
                $Session = $this->request->getSession();
                if ($Session->read('oauth_user_not_found', null) !== null) {
                    $errorMessages[] = $Session->read('oauth_user_not_found');
                    $Session->delete('oauth_user_not_found');
                }
            }

            $this->set('errorMessages', $errorMessages);
            $this->viewBuilder()->setOption('serialize', ['_csrfToken', 'images', 'hasValidSslCertificate', 'isLoggedIn', 'isSsoEnabled', 'forceRedirectSsousersToLoginScreen', 'errorMessages']);
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
            $errors['Password'][] = __('Invalid username or password');

            $this->set('success', false);
            $this->set('errors', $errors);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
        }
    }

    public function logout() {
        $Session = $this->request->getSession();
        $isOAuthLogin = $Session->read('is_oauth_login') === true;
        $Session->delete('is_oauth_login');
        $Session->delete('MessageOtd.showMessage');

        $this->Authentication->logout();

        if ($isOAuthLogin === true) {
            $oAuthClient = new oAuthClient();
            $this->redirect($oAuthClient->getLogoutUrl());
            return;
        }

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

        $MY_RIGHTS = $this->MY_RIGHTS;
        if($this->hasRootPrivileges){
            // root users can see all users
            $MY_RIGHTS = [];
        }
        $all_tmp_users = $UsersTable->getUsersIndex($UsersFilter, $PaginateOMat, $MY_RIGHTS);

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

            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('isOAuth2', $SystemsettingsTable->isOAuth2());
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

            if ($user->is_oauth === true) {
                //remove password validation when user is an oAuth2 user.
                $user->password = '';
                $user->confirm_password = '';
                $UsersTable->getValidator()->remove('password');
                $UsersTable->getValidator()->remove('confirm_password');
            }

            $UsersTable->save($user);
            if ($user->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $user->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            Cache::clear('permissions');
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

            if ($user->is_oauth === true) {
                $user->setAccess('is_oauth', false); //do not allow to change is_oauth
                //oAuth users has no password
                $data['password'] = '';
                $data['confirm_password'] = '';
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

            Cache::clear('permissions');
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
        $Mailer->setSubject(__('Your ') . $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'] . __(' got reset!'));
        $Mailer->setEmailFormat('text');
        $Mailer->setAttachments([
            'logo.png' => [
                'file'      => $Logo->getSmallLogoDiskPath(),
                'mimetype'  => 'image/png',
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
        //$selected = $this->request->getQuery('selected', []);

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
                $permissions[$container['id']]['user_roles'][$userContainerRole['id']] = $userContainerRole['name'];
            }
        }


        $this->set('userContainerRoleContainerPermissions', $permissions);
        $this->viewBuilder()->setOption('serialize', ['userContainerRoleContainerPermissions']);
    }

    public function getLocaleOptions() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $localesPath = Configure::read('App.paths.locales')[0];
        $definedLocalCodes = Locales::getLocalCodesFromDefinedLanguages();
        $localeOptions = [];
        $localeDirs = array_filter(glob($localesPath . '*'), 'is_dir');
        array_walk($localeDirs, function ($value, $key) use (&$localeOptions, $localesPath, $definedLocalCodes) {
            $i18n = substr($value, strlen($localesPath));
            if (in_array($i18n, $definedLocalCodes, true)) {
                $language = Locales::getLanguageByLocalCode($i18n);
                $localeOptions[] = [
                    'i18n' => $i18n,
                    'name' => $language['label']
                ];
            }
        });
        $this->set('localeOptions', $localeOptions);
        $this->viewBuilder()->setOption('serialize', ['localeOptions']);
    }

    public function loadLdapUserDetails() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $Ldap = LdapClient::fromSystemsettings($SystemsettingsTable->findAsArraySection('FRONTEND'));

        $samaccountname = (string)$this->request->getQuery('samaccountname', '');
        $ldapUser = null;
        if (!empty($samaccountname)) {
            $ldapUser = $Ldap->getUser($samaccountname, true);
            if($ldapUser){
                /** @var UsercontainerrolesTable $UsercontainerrolesTable */
                $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

                $ldapUser['userContainerRoleContainerPermissionsLdap'] = $UsercontainerrolesTable->getContainerPermissionsByLdapUserMemberOf(
                    $ldapUser['memberof']
                );

                $permissions = [];
                foreach ( $ldapUser['userContainerRoleContainerPermissionsLdap'] as $userContainerRole) {
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
                $ldapUser['userContainerRoleContainerPermissionsLdap'] = $permissions;
            }
        }
        $this->set('ldapUser', $ldapUser);
        $this->viewBuilder()->setOption('serialize', ['ldapUser']);
    }

}
