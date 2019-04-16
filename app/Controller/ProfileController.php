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
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\System\FileUploadSize;

/**
 * Class ProfileController
 * @property User User
 * @property SessionComponent Session
 * @property AppAuthComponent Auth
 * @property Systemsetting Systemsetting
 */
class ProfileController extends AppController {
    public $layout = 'angularjs';
    public $uses = [
        'User',
        'Systemsetting'
    ];

    public $components = ['Upload'];

    public function edit() {
        $this->layout = 'blank';
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
                /*
                                $this->set('user', $userToSave);
                                $this->set('_serialize', ['user']);
                */
            }

            /***** Change users profile image *****/
            if (isset($this->request->data['Picture']) && !empty($this->request->data['Picture'])) {
                if (!file_exists(WWW_ROOT . 'userimages')) {
                    mkdir(WWW_ROOT . 'userimages');
                }
                $this->Upload->setPath(WWW_ROOT . 'userimages' . DS);
                if (isset($this->request->data['Picture']['Image']) && isset($this->request->data['Picture']['Image']['tmp_name']) && isset($this->request->data['Picture']['Image']['name'])) {
                    $filename = $this->Upload->uploadUserimage($this->request->data['Picture']['Image']);
                    if ($filename) {
                        $oldFilename = $user->filename;
                        $user->filename = $filename;

                        $Users->save($userToSave);
                        if ($userToSave->hasErrors()) {
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

                        /* $this->set('user', $userToSave);
                         $this->set('_serialize', ['user']);
                        */
                    }
                    $this->set('error', 'Could not save image data, may be wrong data type. Allowed types are .png, .jpg and .gif');
                    $this->set('_serialize', ['error']);
                    return;
                }
            }

            /***** Change users password *****/
            if (isset($this->request->data['Password'])) {
                if ($Users->getPasswordHash($this->request->data['Password']['current_password']) != $user->password) {
                    $this->set('error', __('Current Password is incorrect'));
                    $this->set('_serialize', ['error']);
                    return;
                }

                $userToSave = $Users->patchEntity($user, $this->request->data('Password'));
debug($userToSave);
                $Users->save($userToSave);
                if ($userToSave->hasErrors()) {
                    $this->response->statusCode(400);
                    $this->set('error', $user->getErrors());
                    $this->set('_serialize', ['error']);
                    return;
                }
                //$sessionUser = $this->Session->read('Auth');

               // $merged = Hash::merge($sessionUser, $this->request->data);
               // $this->Session->write('Auth', $merged);


                /*old stuff */

                /*  if (Security::hash($this->request->data['Password']['current_password'], null, true) != $user['User']['password']) {
                      $this->setFlash(__('The entered password is not your current password'), false);

                      return $this->redirect(['action' => 'edit']);
                  }

                  if (isset($this->request->data['Password']['new_password']) && isset($this->request->data['Password']['new_password_repeat'])) {
                      if ($this->request->data['Password']['new_password'] == $this->request->data['Password']['new_password_repeat']) {
                          $user = $this->User->findById($this->Auth->user('id'));
                          $this->User->id = $this->Auth->user('id');
                          if ($this->User->saveField('password', AuthComponent::password($this->request->data['Password']['new_password']))) {
                              $this->setFlash(__('Password changed successfully'));
                              $this->redirect(['action' => 'edit']);
                          }
                          $this->setFlash(__('Error while saving data'), false);
                          $this->redirect(['action' => 'edit']);
                      } else {
                          $this->setFlash(__('The entered passwords are not the same'), false);

                          return $this->redirect(['action' => 'edit']);
                      }
                  } else {
                      $this->setFlash(__('Plase enter and confirm your new password'), false);

                      return $this->redirect(['action' => 'edit']);
                  }
  */
                /*old stuff */
            }
        }


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
        $this->layout = 'blank';
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        /** @var $ApikeysTable ApikeysTable */
        $ApikeysTable = TableRegistry::getTableLocator()->get('Apikeys');

        if ($this->request->is('get')) {
            //Generate new API key
            $apikey = $ApikeysTable->generateApiKey();
            $this->set('apikey', $apikey);
            $this->set('_serialize', ['apikey']);
            return;
        }

        if ($this->request->is('post')) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            //Save new API key
            $apikey = $this->request->data['Apikey'];
            $apikey['user_id'] = $User->getId();

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

    public function deleteImage() {
        $user = $this->User->findById($this->Auth->user('id'));

        if ($user['User']['image'] != null && $user['User']['image'] != '') {
            if (file_exists(WWW_ROOT . 'userimages' . DS . $user['User']['image'])) {
                unlink(WWW_ROOT . 'userimages' . DS . $user['User']['image']);
            }
        }
        $this->redirect(['action' => 'edit']);
    }

    public function edit_apikey() {
        $this->layout = 'blank';
        //Only ship HTML template
        return;
    }
}