<?php
// Copyright (C) <2017>  <it-novum GmbH>
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

/**
 * Class Oauth2Controller
 *
 * @property Oauth2client $Oauth2client
 * @property Proxy $Proxy
 */
class Oauth2Controller extends Oauth2ModuleAppController
{
    public $layout = 'Admin.default';
    public $uses = ['Oauth2Module.Oauth2client', 'Proxy'];

    public function index()
    {
        $this->set('allOAuth2Clients', $this->Oauth2client->find('all'));
    }

    public function add()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Oauth2client->saveAll($this->request->data)) {
                $this->setFlash(__('OAuth2 Client successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('could not save data'), false);
            }
        }
        $this->set('returnUrl', __('Return Url will be generated after saving the OAuth2 Client'));
    }

    /**
     * @param integer $id
     */
    public function edit($id = null)
    {
        if (!$this->Oauth2client->exists($id)) {
            throw new NotFoundException(__('Invalid OAuth2 Client Connection ID'));
        }

        $oauth2Client = $this->Oauth2client->findById($id);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Oauth2client->saveAll($this->request->data)) {
                $this->setFlash(__('OAuth2 Client successfully saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('could not save data'), false);
            }
        }
        $this->set('oauth2Client', $oauth2Client);
        $this->set('returnUrl', $this->Oauth2client->getReturnUrl($id));
    }

    /**
     * @param integer $id
     */
    public function delete($id = null)
    {
        if (!$this->Oauth2client->exists($id)) {
            throw new NotFoundException(__('Invalid OAuth2Client ID'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Oauth2client->delete($id, true)) {
            $this->setFlash(__('OAuth2 Client deleted'));
            $this->redirect(['action' => 'index']);
        }

        $this->setFlash(__('Could not delete OAuth2 Client Connection'), false);
        $this->redirect(['action' => 'index']);
    }

    public function checkAndLogin()
    {
        $id = isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : null;
        $this->autoRender = false;
        try {
            $userEmail = $this->Oauth2client->getOpenIDEmail($id);
            if (is_null($userEmail['email'])) {
                throw new Exception($userEmail['message']);
            }
            $user = $this->User->find('first', ['conditions' => ['email' => $userEmail['email']]]);
            if (empty($user)) {
                throw new Exception(__('User does not exist.'));
            }
            if (!$this->Auth->login($user)) {
                throw new Exception(__('User exists but cannot log in.'));
            }

            $this->Session->delete('Message.auth');
            $this->setFlash(__('login.automatically_logged_in'));
            $this->redirect($this->Auth->loginRedirect);
        } catch (Exception $exp) {
            echo $exp->getMessage();
        }
    }

}