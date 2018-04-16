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

App::uses('AuthComponent', 'Controller/Component');
App::uses('UserRights', 'Lib');
App::import('Component', 'Ldap');

/**
 * Class AppAuthComponent
 * @property AuthComponent Auth
 */
class AppAuthComponent extends AuthComponent {

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var UserRights
     */
    public $UserRights;

    public $components = ['Session', 'RequestHandler', 'Cookie', 'Ldap', 'Flash'];


    /**
     * Initializes AuthComponent for use in the controller
     *
     * @param Controller $controller A reference to the instantiating controller object
     *
     * @return void
     */
    public function initialize(Controller $controller) {
        Configure::load('user_rights');
        $rightsConfig = Configure::read('user_rights');
        $this->UserRights = new UserRights($rightsConfig);
        $this->_controller = $controller;
        parent::initialize($controller);
        $this->_settings();
    }

    /**
     * Sets up the AuthComponent for the Admin backend
     * @return void
     */
    protected function _settings() {
        $this->authenticate = [
            AuthComponent::ALL => [
                'userModel' => 'User',
                'fields'    => [
                    'username' => 'email',
                    'password' => 'password',
                ],
                'scope'     => [
                    //'User.login_retries <=' => 3,
                    'User.status' => Status::ACTIVE,
                ],
            ],
            'Form',
            'Api',
        ];

        $this->authError = __('action_not_allowed');
        $this->authorize = ['Controller'];

        $this->loginRedirect = [
            'controller' => 'dashboards',
            'action'     => 'index',
            'plugin'     => null,
        ];
        $this->logoutRedirect = [
            'controller' => 'login',
            'action'     => 'login',
            'plugin'     => null,
        ];
        $this->loginAction = [
            'controller' => 'login',
            'action'     => 'login',
            'plugin'     => null,
        ];

    }

    public function login($user = null, $method = null, $options = []) {
        $_options = [];
        $this->Systemsetting = ClassRegistry::init('Systemsetting');
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');

        if ($method == null) {
            $method = $systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'];
        }

        switch ($method) {
            case 'ldap':
                /* do the LDAP stuff
                 * If the login request comes from login.ctp, we use the credentials out of $_REQUEST
                 * If the request is from $this->autoLogin(), we use the credentials out of CT_USER cookie
                 */
                if (isset($user['User']['status']) && $user['User']['status'] != Status::ACTIVE) {
                    return false;
                }
                $_options = [];
                if (isset($user['User']['ldap_dn'])) {
                    $_options['dn'] = $user['User']['ldap_dn'];
                }
                if (isset($this->request->data['User']['samaccountname'])) {
                    $_options['samaccountname'] = strtolower($this->request->data['User']['samaccountname']);
                }
                if (isset($this->request->data['User']['password'])) {
                    $_options['sampassword'] = $this->request->data['User']['password'];
                }
                $options = Hash::merge($_options, $options);

                if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap' && isset($options['dn'])) {
                    $options['samaccountname'] = $options['dn'];
                }
                try {
                    $result = $this->Ldap->login($options['samaccountname'], $options['sampassword']);
                } catch (\Adldap\Exceptions\AdldapException $ex) {
                    $this->Flash->set(
                        $ex->getMessage(), [
                            'element' => 'default',
                            'params'  => [
                                'class' => 'alert alert-danger'
                            ]
                        ]
                    );
                    return false;
                }

                if (empty($user) || !isset($user['User'])) {
                    return false;
                }

                if ($result) {
                    $this->_setDefaults();
                    $this->Session->renew();
                    $this->Session->write(self::$sessionKey, $user['User']);
                }
                break;

            default:
                if (!isset($user['User']) && !empty($user)) {
                    $result = parent::login($user);
                } else {
                    $result = parent::login($user['User']);
                }
                break;
        }

        return $result;
    }

    /**
     * If we have a valid cookie, log the user in.
     * @return void
     */
    public function autoLogin() {
        $this->_cookieSettings();
        $type = null;
        $options = [];
        if ($autoLoginData = $this->Cookie->read('CTUser')) {
            //pr($autoLoginData);
            if (isset($autoLoginData['email'])) {
                $user = ClassRegistry::init('User')->find('first', [
                    'conditions' => [
                        'email'  => $autoLoginData['email'],
                        'status' => Status::ACTIVE,
                    ],
                    'contain'    => false,
                ]);
                if ($user['User']['samaccountname'] != null) {
                    $type = 'ldap';
                    $options['samaccountname'] = $autoLoginData['samaccountname'];
                    $options['sampassword'] = $autoLoginData['sampassword'];
                } else {
                    $type = 'local';
                }
            } else {
                $user = [];
            }
            if (!empty($user)) {
                $this->login($user, $type, $options);

                //MOVED TO AppController!!!!
                //$this->loadmodel workaround
                //$this->User = ClassRegistry::init('User');
                //$this->Container = ClassRegistry::init('Container');
                //
                //$_user = $this->User->findById($user['User']['id']);
                //$rights = [ROOT_CONTAINER];
                //$hasRootPrivileges = false;
                //foreach($_user['Container'] as $container){
                //	$rights[] = (int)$container['id'];
                //
                //	if((int)$container['id'] === ROOT_CONTAINER){
                //		$hasRootPrivileges = true;
                //	}
                //
                //	foreach($this->Container->children($container['id'], true) as $childContainer){
                //		$rights[] = (int)$childContainer['Container']['id'];
                //	}
                //}
                //$this->Session->write('MY_RIGHTS', array_unique($rights));
                //$this->Session->write('hasRootPrivileges', $hasRootPrivileges);

                $this->Flash->set(
                    __('login.automatically_logged_in'), [
                    'element' => 'default',
                    'params'  => [
                        'class' => 'alert alert-success'
                    ]
                ]);

                return true;
            } else {
                $this->deleteRememberMeCookie();
            }
        }

        return false;
    }

    /**
     * @return void
     */
    protected function _cookieSettings() {
        $this->Cookie->name = 'CT';
        $this->Cookie->type = 'rijndael';
        $this->Cookie->key = 'xPJbcZcOP5DN1jrT7fp6%vY7voVt-f#1B!Y8!UQ!jVo_3mESDdduxtA+sXSs4HXA';
    }

    /**
     * Save a "remember me" cookie for the current user
     * @return void
     */
    public function addRememberMeCookie($options = []) {
        $this->_cookieSettings();

        $_options = [
            'email'          => $this->user('email'),
            'password'       => $this->user('password'),
            'samaccountname' => $this->request->data('User.samaccountname'),
            'sampassword'    => $this->request->data('User.password'),
        ];

        $options = Hash::merge($_options, $options);

        $this->Cookie->write('CTUser', $options, true, '2weeks');
    }

    /**
     * Deletes the remember me cookie
     * @return void
     */
    public function deleteRememberMeCookie() {
        $this->_cookieSettings();
        $this->Cookie->delete('CTUser');
    }

    /**
     * @return string
     */
    public function logout() {
        $this->Session->delete('Auth');
        $this->deleteRememberMeCookie();

        return parent::logout();
    }

    /**
     * Returns a sub-set of the currently logged in user's data for use in the frontend.
     * @return array        Returns null if the user isn't logged in.
     */
    public function getFrontendUserData() {
        $user = $this->user();
        if ($user !== null) {
            $allowedKeys = ['id', 'email', 'role', 'firstname', 'lastname'];
            foreach ($user as $k => $v) {
                if (!in_array($k, $allowedKeys)) {
                    unset($user[$k]);
                }
            }
        }

        return $user;
    }

    /**
     * Set a flash message.  Uses the Session component, and values from AuthComponent::$flash.
     * We are overriding to use our AppController's setFlash Method
     *
     * @param string $message The message to set.
     *
     * @return void
     */
    public function flash($message) {
        $this->_controller->setFlash($message, false, $this->flash['key']);
    }

    /**
     * Try to find the user for for stateless login
     * @param CakeRequest $request
     * @return bool|mixed
     */
    public function tryToGetUser(CakeRequest $request) {
        $this->constructAuthenticate();
        foreach ($this->_authenticateObjects as $auth) {
            $user = $auth->getUser($request);
            if (!empty($user) && is_array($user)) {
                return $user;
            }
        }
        return false;
    }

}

