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

use App\Model\Table\ApikeysTable;
use App\Model\Table\UsersTable;
use Cake\Http\Session;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\System\FileUploadSize;

/**
 * Class ProfileController
 * @property AppAuthComponent $Auth
 * @property Session $Session
 */
class ProfileController extends AppController {

    public $layout = 'blank';

    public $components = ['Upload', 'Session'];

    public function edit() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($User->getId())) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->getUserForEdit($User->getId());
        $isLdapUser = !empty($user['User']['samaccountname']);

        unset($user['User']['usercontainerroles']);
        unset($user['User']['containers']);
        unset($user['User']['usercontainerroles_containerids']);
        unset($user['User']['ContainersUsersMemberships']);

        $FileUploadSize = new FileUploadSize();

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return profile information
            $this->set('user', $user['User']);
            $this->set('isLdapUser', $isLdapUser);
            $this->set('maxUploadLimit', $FileUploadSize->toArray());
            $this->viewBuilder()->setOption('serialize', ['user', 'isLdapUser', 'maxUploadLimit']);
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data('User');

            $user = $UsersTable->get($User->getId());
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
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            //Update user information in $_SESSION
            $this->Session->write('Auth', $UsersTable->getActiveUsersByIdForCake2Login($User->getId()));
            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']);
        }
    }

    public function changePassword() {
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $user = $UsersTable->get($User->getId());

        $data = $this->request->data('Password');
        if ($UsersTable->getPasswordHash($data['current_password']) !== $user->get('password')) {
            $this->response->statusCode(400);
            $this->set('error', [
                'current_password' => [
                    __('Current password is incorrect')
                ]
            ]);
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $user = $UsersTable->patchEntity($user, $data);
        $UsersTable->save($user);
        if ($user->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $user->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $this->Session->write('Auth', $UsersTable->getActiveUsersByIdForCake2Login($User->getId()));
        $this->set('message', __('Password changed successfully.'));
        $this->viewBuilder()->setOption('serialize', ['message']);
    }

    public function upload_profile_icon() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->get($this->Auth->user('id'), ['contain' => 'containers', 'users_to_containers']);

        /***** Change users profile image *****/
        if (!file_exists(WWW_ROOT . 'userimages')) {
            mkdir(WWW_ROOT . 'userimages');
        }
        $this->Upload->setPath(WWW_ROOT . 'userimages' . DS);
        if (isset($_FILES['Picture']['tmp_name']) && isset($_FILES['Picture']['name'])) {
            $filename = $this->Upload->uploadUserimage($_FILES['Picture']);

            if ($filename) {
                $oldFilename = $user->image;
                $user->image = $filename;
                //prevent multiple hash of password
                unset($user->password);
                $UsersTable->save($user);
                if ($user->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $user->getErrors());
                    $this->viewBuilder()->setOption('serialize', ['error']);
                    return;
                }
                $this->Session->write('Auth', $UsersTable->getActiveUsersByIdForCake2Login($User->getId()));

                //Delete old image
                $path = WWW_ROOT . 'userimages' . DS;
                if (!is_null($oldFilename) && file_exists($path . $oldFilename) && !is_dir($path . $oldFilename)) {
                    unlink($path . $oldFilename);
                }


                $this->response->statusCode(200);
                $this->set('success', true);
                $this->set('message', __('File Upload success!'));
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
                return;
            }
            $this->response->statusCode(400);
            $this->set('error', __('Could not save image data, may be wrong data type. Allowed types are .png, .jpg and .gif'));
            $this->set('message', __('Could not save image data, may be wrong data type. Allowed types are .png, .jpg and .gif'));
            $this->viewBuilder()->setOption('serialize', ['error', 'message']);
            return;
        }
        $this->set('user', []);
        $this->viewBuilder()->setOption('serialize', ['user']);

    }

    public function apikey() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException('Only API requests.');
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $ApikeysTable ApikeysTable */
        $ApikeysTable = TableRegistry::getTableLocator()->get('Apikeys');

        if ($this->request->is('get')) {
            $id = $this->request->getQuery('id');
            if (is_numeric($id)) {
                //Get an api key by id and user_id
                if (!$ApikeysTable->existsById($id)) {
                    throw new NotFoundException(__('Invalid API key'));
                }
                $apikey = $ApikeysTable->getApikeyByIdAndUserId(
                    $id,
                    $User->getId()
                );
                $Apikey = new \itnovum\openITCOCKPIT\Core\Views\Apikey($apikey);
                $this->set('apikey', $Apikey->toArray());
                $this->viewBuilder()->setOption('serialize', ['apikey']);
                return;
            }

            //Return all api keys of the user
            $apikeysResult = $ApikeysTable->getAllapiKeysByUserId($User->getId());

            $apikeys = [];
            foreach ($apikeysResult as $apikey) {
                $Apikey = new \itnovum\openITCOCKPIT\Core\Views\Apikey($apikey);
                $apikeys[] = $Apikey->toArray();
            }

            $this->set('apikeys', $apikeys);
            $this->viewBuilder()->setOption('serialize', ['apikeys']);
            return;
        }

        if ($this->request->is('post')) {
            //Update an api key by id
            if (isset($this->request->data['Apikey']['id'])) {
                $id = $this->request->data['Apikey']['id'];

                if (!$ApikeysTable->existsById($id)) {
                    throw new NotFoundException(__('Invalid API key'));
                }
                $apikey = $ApikeysTable->get($id);
                $apikey = $ApikeysTable->patchEntity($apikey, $this->request->data['Apikey']);

                $ApikeysTable->save($apikey);
                if ($apikey->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $apikey->getErrors());
                    $this->viewBuilder()->setOption('serialize', ['error']);
                    return;
                } else {
                    $this->set('message', __('API key updated successfully'));
                    $this->viewBuilder()->setOption('serialize', ['message']);
                    return;
                }
            }

        }
    }

    public function create_apikey() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ApikeysTable ApikeysTable */
        $ApikeysTable = TableRegistry::getTableLocator()->get('Apikeys');

        if ($this->request->is('get')) {
            //Generate new API key
            $apikey = $ApikeysTable->generateApiKey();

            $newApiKey = [
                'key'  => $apikey,
                'time' => time()
            ];
            $this->Session->write('latest_api_key', $newApiKey);

            $this->set('apikey', $apikey);
            $this->viewBuilder()->setOption('serialize', ['apikey']);
            return;
        }

        if ($this->request->is('post')) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            //Save new API key
            //Resolve ITC-2170
            $newApiKey = $this->Session->read('latest_api_key');
            $this->Session->delete('latest_api_key');
            $this->Session->delete('latest_api_key');
            if (!isset($newApiKey['key']) || !$newApiKey['time']) {
                throw new BadRequestException();
            }
            //Is API-Key older than 5 minutes?
            if ($newApiKey['time'] < (time() - 5 * 60)) {
                throw new BadRequestException('API key expired');
            }

            $apikey = [
                'apikey'      => $newApiKey['key'],
                'description' => $this->request->data('Apikey.description'),
                'user_id'     => $User->getId()
            ];

            $apikeyEntity = $ApikeysTable->newEmptyEntity();
            $apikeyEntity = $ApikeysTable->patchEntity($apikeyEntity, $apikey);

            $ApikeysTable->save($apikeyEntity);
            if ($apikeyEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $apikeyEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
            } else {
                $this->set('message', __('API key created successfully'));
                $this->viewBuilder()->setOption('serialize', ['message']);
            }
            return;
        }
    }

    public function delete_apikey($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $ApikeysTable ApikeysTable */
        $ApikeysTable = TableRegistry::getTableLocator()->get('Apikeys');

        $apikey = $ApikeysTable->getApikeyByIdAndUserId($id, $User->getId());

        if (empty($apikey)) {
            throw new NotFoundException('Invalide API key');
        }

        if ($ApikeysTable->delete($apikey)) {
            $this->set('message', __('Api key deleted successfully'));
            $this->viewBuilder()->setOption('serialize', ['message']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('message', __('Could not delete api key'));
        $this->viewBuilder()->setOption('serialize', ['message']);

    }

    public function deleteImage() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $UsersTable UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->get($this->Auth->user('id'), ['contain' => 'containers', 'users_to_containers']);
        //prevent multiple hash of password
        unset($user->password);

        if ($user->image != null && $user->image != '') {
            if (file_exists(WWW_ROOT . 'userimages' . DS . $user->image)) {
                unlink(WWW_ROOT . 'userimages' . DS . $user->image);
            }
        }

        $user->image = null;

        $UsersTable->save($user);
        if ($user->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $user->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->Session->delete('Auth.User.image');

        $this->response->statusCode(200);
        $this->set('success', true);
        $this->set('message', __('File deleted sucessfully'));
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        return;
    }

    public function edit_apikey() {
        //Only ship HTML template
        return;
    }
}
