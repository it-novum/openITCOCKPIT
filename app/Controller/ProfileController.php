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
use App\Model\Table\ApikeysTable;
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

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->get($this->Auth->user('id'), ['contain' => 'containers', 'users_to_containers']);

        $userForFrontend = [
            'firstname'         => $user->firstname,
            'lastname'          => $user->lastname,
            'samaccountname'    => $user->samaccountname,
            'email'             => $user->email,
            'phone'             => $user->phone,
            'showstatsinmenu'   => $user->showstatsinmenu,
            'recursive_browser' => $user->recursive_browser,
            'paginatorlength'   => $user->paginatorlength,
            'dateformat'        => $user->dateformat,
            'timezone'          => $user->timezone,
            'image'             => $user->image
        ];

        $FileUploadSize = new FileUploadSize();
        $this->set('maxUploadLimit', $FileUploadSize->toArray());
        $this->set('user', $userForFrontend);
        $this->set('_serialize', ['user', 'maxUploadLimit']);

        if ($this->request->is('post') || $this->request->is('put')) {
            /***** Change user data *****/
            if (isset($this->request->data['User'])) {
                if ($this->request->data['User']['paginatorlength'] < '0') {
                    $this->request->data['User']['paginatorlength'] = '1';
                }
                if ($this->request->data['User']['paginatorlength'] > '1000') {
                    $this->request->data['User']['paginatorlength'] = '1000';
                }
                //prevent multiple hash of password
                unset($user->password);

                $userToSave = $Users->patchEntity($user, $this->request->data('User'));

                $Users->save($userToSave);
                if ($userToSave->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $user->getErrors());
                    $this->set('_serialize', ['error']);
                    return;
                }
                $sessionUser = $this->Session->read('Auth');

                $merged = Hash::merge($sessionUser, $this->request->data);
                $this->Session->write('Auth', $merged);
            }


            /***** Change users password *****/
            if (isset($this->request->data['Password'])) {
                if ($Users->getPasswordHash($this->request->data['Password']['current_password']) != $user->password) {
                    $this->set('error', __('Current Password is incorrect'));
                    $this->set('_serialize', ['error']);
                    return;
                }

                $userToSave = $Users->patchEntity($user, $this->request->data('Password'));

                $Users->save($userToSave);
                if ($userToSave->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $user->getErrors());
                    $this->set('_serialize', ['error']);
                    return;
                }
            }
        }
    }

    public function upload_profile_icon() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->get($this->Auth->user('id'), ['contain' => 'containers', 'users_to_containers']);

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
                $Users->save($user);
                if ($user->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $user->getErrors());
                    $this->set('_serialize', ['error']);
                    return;
                }
                $this->Session->write('Auth.User.image', $filename);

                //Delete old image
                $path = WWW_ROOT . 'userimages' . DS;
                if (!is_null($oldFilename) && file_exists($path . $oldFilename) && !is_dir($path . $oldFilename)) {
                    unlink($path . $oldFilename);
                }


                $this->response->statusCode(200);
                $this->set('success', true);
                $this->set('message', __('File Upload success!'));
                $this->set('_serialize', ['success', 'message']);
                return;
            }
            $this->response->statusCode(400);
            $this->set('error', __('Could not save image data, may be wrong data type. Allowed types are .png, .jpg and .gif'));
            $this->set('message', __('Could not save image data, may be wrong data type. Allowed types are .png, .jpg and .gif'));
            $this->set('_serialize', ['error', 'message']);
            return;
        }
        $this->set('user', []);
        $this->set('_serialize', ['user']);

    }

    public function apikey() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException('Only API requests.');
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        /** @var $ApikeysTable ApikeysTable */
        $ApikeysTable = TableRegistry::getTableLocator()->get('Apikeys');

        if ($this->request->is('get')) {
            $id = $this->request->query('id');
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
                $this->set('_serialize', ['apikey']);
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
            $this->set('_serialize', ['apikeys']);
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
                    $this->set('_serialize', ['error']);
                    return;
                } else {
                    $this->set('message', __('API key updated successfully'));
                    $this->set('_serialize', ['message']);
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
            $this->set('_serialize', ['apikey']);
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

            $apikeyEntity = $ApikeysTable->newEntity();
            $apikeyEntity = $ApikeysTable->patchEntity($apikeyEntity, $apikey);

            $ApikeysTable->save($apikeyEntity);
            if ($apikeyEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $apikeyEntity->getErrors());
                $this->set('_serialize', ['error']);
            } else {
                $this->set('message', __('API key created successfully'));
                $this->set('_serialize', ['message']);
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
            $this->set('_serialize', ['message']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('message', __('Could not delete api key'));
        $this->set('_serialize', ['message']);

    }

    public function loadUserimage(){
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->get($this->Auth->user('id'));
        $this->set('image', $user->image);
        $this->set('_serialize', ['image']);
    }

    public function deleteImage() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Users App\Model\Table\UsersTable */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $user = $Users->get($this->Auth->user('id'), ['contain' => 'containers', 'users_to_containers']);
        //prevent multiple hash of password
        unset($user->password);

        if ($user->image != null && $user->image != '') {
            if (file_exists(WWW_ROOT . 'userimages' . DS . $user->image)) {
                unlink(WWW_ROOT . 'userimages' . DS . $user->image);
            }
        }

        $user->image = null;

        $Users->save($user);
        if ($user->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $user->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }
        $this->Session->delete('Auth.User.image');

        $this->response->statusCode(200);
        $this->set('success', true);
        $this->set('message', __('File deleted sucessfully'));
        $this->set('_serialize', ['success', 'message']);
        return;
    }

    public function edit_apikey() {
        //Only ship HTML template
        return;
    }
}