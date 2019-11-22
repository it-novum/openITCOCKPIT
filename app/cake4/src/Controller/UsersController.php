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


use App\Lib\Api\ApiPaginator;
use App\Model\Table\UsergroupsTable;
use App\Model\Table\UsersTable;
use Authentication\Authenticator\ResultInterface;
use Authentication\Controller\Component\AuthenticationComponent;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\LoginBackgrounds;

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

        if ($this->request->is('get')) {
            $this->set('_csrfToken', $this->request->getParam('_csrfToken'));
            $this->set('images', $images['winter']);
            $this->viewBuilder()->setOption('serialize', ['_csrfToken', 'images']);
            return;
        }

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if ($this->request->is('post')) {
            $user = $UsersTable->newEntity($this->request->getData());
            $this->set('user', $user);

            $result = $this->Authentication->getResult();
            if ($result->getStatus() === ResultInterface::SUCCESS) {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }

            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->viewBuilder()->setOption('serialize', ['success']);
        }
    }

    public function logout() {
        $this->Authentication->logout();
        $this->redirect([
            'action' => 'login'
        ]);
    }

    public function index() {
        if ($this->isHtmlRequest()) {
            //Only ship html template
            return;
        }

        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $ApiPaginator = new ApiPaginator($this, $this->request);

        $entities = $UsersTable->getUsersIndex($ApiPaginator);
        $myself = $this->Authentication->getIdentity();

        $this->set('users', $entities);
        $this->set('myself', $myself->get('id'));
        $this->viewBuilder()->setOption('serialize', ['users', 'myself']);
    }

    public function add() {
        if ($this->isHtmlRequest()) {
            //Only ship html template
            return;
        }

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $user = $UsersTable->newEmptyEntity();
        $user = $UsersTable->patchEntity($user, $this->request->getData());

        $UsersTable->save($user);
        if ($user->hasErrors()) {
            //This throws the body content away :(
            $this->response = $this->response->withStatus(400);
            $this->set('error', $user->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('user', $user);
        $this->viewBuilder()->setOption('serialize', ['user']);
    }

    public function edit($id = null) {
        if ($this->isHtmlRequest()) {
            //Only ship html template
            return;
        }

        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $user = $UsersTable->get($id);

        if ($this->request->is('get')) {
            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']);
            return;
        }

        if ($this->request->is('post')) {
            $user->setAccess('id', false);
            $user = $UsersTable->patchEntity($user, $this->request->getData());

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

    public function delete() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('id');

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        if (!$UsersTable->existsById($id)) {
            throw new NotFoundException(__('User not found'));
        }

        $myself = $this->Authentication->getIdentity();

        if ($id == $myself->get('id')) {
            throw new ConflictException(__('You can not delete yourself!'));
        }

        $user = $UsersTable->get($id);
        if ($UsersTable->delete($user)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }
        $this->response->statusCode(400);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function loadUsergroups() {
        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        $_usergroups = $UsergroupsTable->find('list')->toArray();

        //Rewrite hashmap to array because javascript will break the "orderd hashmap" because hashmaps have no order
        $usergroups = [];
        foreach($_usergroups as $id => $name){
            $usergroups[] = [
                'key' => $id,
                'value' => $name
            ];
        }

        $this->set('usergroups', $usergroups);
        $this->viewBuilder()->setOption('serialize', ['usergroups']);
    }
}
