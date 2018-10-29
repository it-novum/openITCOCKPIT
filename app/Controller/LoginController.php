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

use itnovum\openITCOCKPIT\Core\Views\Logo;

App::uses('Validation', 'Utility');


class LoginController extends AppController {

    public $uses = ['User', 'SystemContent', 'Systemsetting', 'Container', 'Oauth2client'];
    public $components = ['Ldap'];

    public $layout = 'login';

    public function beforeFilter() {
        $this->Auth->allow();
    }

    public function index() {
        $this->redirect('/login/login');
    }

    public function login($redirectBack = 0) {
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');

        $disableLoginAnimation = false;
        if (isset($systemsettings['FRONTEND']['FRONTEND.DISABLE_LOGIN_ANIMATION'])) {
            $disableLoginAnimation = (bool)$systemsettings['FRONTEND']['FRONTEND.DISABLE_LOGIN_ANIMATION'];
        }
        $this->set('disableLoginAnimation', $disableLoginAnimation);

        if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] === 'sso' && !$this->isApiRequest()) {
            $result = $this->Oauth2client->connectToSSO();
            $errorPostMess = $this->Oauth2client->getPostErrorMessage($systemsettings['FRONTEND']['FRONTEND.SSO.LOG_OFF_LINK']);
            if (isset($result['redirect'])) {
                $this->redirect($result['redirect']);
            }
            if (($result['success'])) {
                $user = $this->User->find('first', ['conditions' => ['email' => $result['email'], 'status' => 1]]);
                if (empty($user)) {
                    echo $systemsettings['FRONTEND']['FRONTEND.SSO.NO_EMAIL_MESSAGE'] . $errorPostMess;
                    exit;
                }
                if (!$this->Auth->login($user)) {
                    echo 'Cannot log in user: ' . $result['email'] . $errorPostMess;
                    exit;
                }

                $this->Session->delete('Message.auth');
                $this->setFlash(__('login.automatically_logged_in'));
                $this->redirect($this->Auth->loginRedirect);
            } else {
                echo $result['message'] . $errorPostMess;
                exit;
            }
        }

        $displayMethod = false;
        $authMethods = [
            'ldap'    => __('LDAP'),
            'session' => __('Local'),
        ];

        $selectedMethod = 'session';
        if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap') {
            $displayMethod = true;
            $selectedMethod = 'ldap';
        }

        $this->set(compact(['authMethods', 'selectedMethod', 'displayMethod']));
        $this->Frontend->setJson('selectedMethod', $selectedMethod);

        if ($redirectBack) {
            $this->Auth->loginRedirect = $this->request->referer();
        }
        if ($this->Auth->loggedIn()) {
            $this->redirect($this->Auth->loginRedirect);

            return;
        }
        if (!empty($this->params['url']['redirectUrl'])) {
            $this->Session->write('Login.redirectUrl', $this->params['url']['redirectUrl']);
        } else if (($redirectUrl = $this->Auth->redirectUrl()) != '/') {
            $this->Session->write('Login.redirectUrl', $redirectUrl);
        }

        if ($this->request->referer(true) === '/') {
            $this->Auth->loginRedirect = [
                'controller' => 'dashboards',
                'action'     => 'index',
            ];
        }

        // Automatic login if Client SSL Certificate is sent
        if (isset($_SERVER['SSL_VERIFIED']) && $_SERVER['SSL_VERIFIED'] === 'SUCCESS' && !empty($_SERVER['SSL_DN'])) {
            $CN = $OU = '';
            $parameters = explode('/', $_SERVER['SSL_DN']);
            foreach ($parameters as $eqVal) {
                $SSLVar = explode('=', $eqVal);
                if (empty($SSLVar)) continue;
                if (isset($SSLVar[0]) && $SSLVar[0] === 'CN' && isset($SSLVar[1])) {
                    $CN = trim($SSLVar[1]);
                } else if (isset($SSLVar[0]) && $SSLVar[0] === 'OU' && isset($SSLVar[1]) && $SSLVar[1] !== 'People') {
                    $OU = strtolower(trim($SSLVar[1]));
                }
            }
            $names = explode(' ', $CN);
            $firstName = isset($names[0]) ? $names[0] : '';
            $lastName = isset($names[1]) ? $names[1] : '';
            $conditions = [];
            if ($firstName !== '' && $lastName !== '') {
                $conditions = [
                    ['firstname' => $firstName, 'lastname' => $lastName, 'status' => 1],
                ];
                if ($OU !== '') {
                    $conditions[0]['User.email LIKE'] = '%@' . $OU . '.%';
                }
            }

            if (!empty($conditions)) {
                $user = $this->User->find('first', ['conditions' => $conditions]);
                if (empty($user)) {
                    $viewerEmail = isset($systemsettings['FRONTEND']['FRONTEND.CERT.DEFAULT_USER_EMAIL']) ? $systemsettings['FRONTEND']['FRONTEND.CERT.DEFAULT_USER_EMAIL'] : '';
                    if (!empty($viewerEmail)) {
                        $user = $this->User->find('first', ['conditions' => ['User.email' => $viewerEmail, 'status' => 1]]);
                    }
                }
                if (!empty($user) && $this->Auth->login($user)) {
                    $this->Session->delete('Message.auth');
                    $this->setFlash(__('login.automatically_logged_in'));
                    $this->redirect($this->Auth->loginRedirect);
                }
            }

        }

        if ($this->request->is('post') || $this->request->is('put')) {
            //ITC-1901 workaround
            if (isset($this->request->data['LoginUser']['username'])) {
                $this->request->data['LoginUser']['email'] = $this->request->data['LoginUser']['username'];
            }

            if (isset($this->request->data['LoginUser']['auth_method']) && $this->request->data['LoginUser']['auth_method'] === 'ldap') {
                $this->request->data['LoginUser']['samaccountname'] = $this->request->data['LoginUser']['email'];
                unset($this->request->data['email']);
            }


            $this->Auth->logout();
            $this->request->data = ['User' => $this->data['LoginUser']];

            // Allow login in with nickname or email address
            if (!empty($this->data['User']['email']) && !Validation::email($this->data['User']['email'])) {
                $user = $this->User->findByEmail($this->data['User']['email']);
                if (!empty($user)) {
                    $this->request->data['User']['email'] = $user['User']['email'];
                }
            }

            $__user = null;
            if (isset($this->data['User']['auth_method']) && $this->data['User']['auth_method'] == 'ldap') {
                $__user = $this->User->findBySamaccountname(strtolower($this->data['User']['samaccountname']));
            }

            if (!isset($this->request->data['User']['auth_method'])) {
                $this->request->data['User']['auth_method'] = $systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'];
            }

            if ($this->Auth->login($__user, $this->request->data['User']['auth_method'])) {
                //MOVED TO AppController!!!
                //$_user = $this->User->findById($this->Auth->user('id'));
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

                if (isset($this->data['User']['remember_me']) && $this->data['User']['remember_me'] &&
                    $systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] != 'twofactor'
                ) {
                    $this->Auth->addRememberMeCookie();
                }
                if ($this->Session->check('Login.redirectUrl')) {
                    $this->Auth->loginRedirect = $this->Session->read('Login.redirectUrl');
                    $this->Session->delete('Login.redirectUrl');
                }


                if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'twofactor') {
                    $this->redirect('/login/onetimetoken/' . $this->Auth->user('id') . '/' . $this->data['User']['remember_me']);

                    return;
                } else {
                    if ($this->request->ext != 'json') {
                        //Only redirect for normal browser POST request, not for rest API
                        $this->setFlash(__('login.login_successful'));
                        $this->redirect($this->Auth->loginRedirect);

                        return;
                    }
                    $message = 'Login successful';
                    $this->set('message', $message);
                    $this->set('_serialize', ['message']);

                    return;
                }
            } else {
                if ($redirectBack) {
                    $this->setFlash(__('login.username_and_password_dont_match'), false);
                    $this->redirect($this->request->referer());

                    return;
                } else {
                    if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap') {
                        $sessionMessage = $this->Session->read();
                        if (isset($sessionMessage['Message']['flash']['message'])) {
                            $this->setFlash($sessionMessage['Message']['flash']['message'], false);
                        } else {
                            $this->setFlash(__('Bad username or password'), false);
                        }
                    } else {
                        $this->setFlash(__('login.username_and_password_dont_match'), false);
                    }
                }
            }
        }

        $message = 'Please login';
        $this->set('message', $message);
        $this->set('_serialize', ['message']);
    }

    public function onetimetoken($id = null, $rememberMe = false) {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('User not found'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->exists($id)) {
                $user = $this->User->findById($id);
                if ($this->request->data('Onetimetoken.onetimetoken') == $user['User']['onetimetoken']) {
                    //Restor the session
                    $this->Session->write('Auth', $this->Session->read('_Auth'));
                    $this->Session->delete('_Auth');
                    if ($rememberMe == 1 || $rememberMe === true) {
                        $this->Auth->addRememberMeCookie([
                            'email'          => $this->Session->read('Auth.User.email'),
                            'password'       => '',
                            'samaccountname' => '',
                            'sampassword'    => '',
                        ]);
                    }

                    $this->setFlash(__('login.logout_successfull'));
                    $this->redirect('/login/login');
                }
                $this->setFlash(__('Wrong One-time password'), false);
            }
        }
        //$this->layout = 'lock';
        $_user = $this->User->findById($id);
        $this->_systemsettings = $this->Systemsetting->findAsArray();

        $generateToken = function () {
            $char = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            $size = (sizeof($char) - 1);
            $token = '';
            for ($i = 0; $i < 6; $i++) {
                $token .= $char[rand(0, $size)];
            }

            return $token;
        };

        $onetimetoken = $generateToken();

        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($_user['User']['email']);
        $Email->subject(__('Your new One-time password'));

        $Email->emailFormat('both');
        $Email->template('template-onetimetoken', 'template-onetimetoken')->viewVars(['onetimetoken' => $onetimetoken]);

        $Logo = new Logo();
        $Email->attachments([
            'logo.png' => [
                'file'      => $Logo->getSmallLogoDiskPath(),
                'mimetype'  => 'image/png',
                'contentId' => '100',
            ],
        ]);

        $user = [];
        $user['User'] = $_user['User'];
        $user['User']['onetimetoken'] = $onetimetoken;
        if (isset($user['User']['id'])) {
            $this->User->id = $user['User']['id'];
            // Avoid of saving a empty user (otherwise we would may be create a user and only the onetimetoken field is filled)
            if ($this->User->saveField('onetimetoken', $onetimetoken)) {
                $Email->send();
                // Kick out the user, that he can not browser to /hosts/index for example, witout entering the one time token
                $this->Session->write('_Auth', $this->Session->read('Auth'));
                $this->Session->delete('Auth');
            }
            $this->set('user_id', $_user['User']['id']);
        } else {
            $this->setFlash('No user given or user not found', false);
        }
    }

    /**
     * Logs the user out of the system
     * @return void
     */
    public function logout() {
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
        $this->Auth->logout();
        if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] === 'sso' && !empty($systemsettings['FRONTEND']['FRONTEND.SSO.LOG_OFF_LINK'])) {
            $this->redirect($systemsettings['FRONTEND']['FRONTEND.SSO.LOG_OFF_LINK']);
        } else {
            $this->setFlash(__('login.logout_successfull'));
        }
        $this->redirect([
            'controller' => 'login',
            'action'     => 'login',
        ]);
    }


    /**
     * Dialog for auth-required actions
     * @return void
     */
    public function auth_required() {
        $redirectUrl = isset($this->params['url']['redirectUrl']) ? $this->params['url']['redirectUrl'] : null;
        $this->set(compact('redirectUrl'));
    }

    public function lock() {

        if (!$this->Auth->loggedIn()) {
            $this->redirect($this->Auth->loginRedirect);

            return;
        }

        $user = [
            'email'     => $this->Auth->user('email'),
            'full_name' => $this->Auth->user('full_name'),
            'image'     => $this->Auth->user('image'),
        ];

        $this->set('user', $user);
        $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
        $this->set('authMethod', $systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD']);

        //Multilaguage für das Frontend
        $this->layout = 'lock';
        $language = [
            'password' => __('Password'),
            'locked'   => __('Locked'),
        ];
        $this->set('language', $language);
        $this->set('title_for_layout', __('Locked'));
        $this->Auth->logout();
    }
}
