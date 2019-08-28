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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\UsercontainerrolesFilter;

/**
 * Class UsersController
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 * @property DbBackend $DbBackend
 */
class UsercontainerrolesController extends AppController {

    public $layout = 'blank';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $UsercontainerrolesFilter = new UsercontainerrolesFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $UsercontainerrolesFilter->getPage());

        /** @var $Usercontainerroles App\Model\Table\UsercontainerrolesTable */
        $Usercontainerroles = TableRegistry::getTableLocator()->get('Usercontainerroles');
        $all_usercontainerroles = $Usercontainerroles->getUsercontainerRolesIndex($UsercontainerrolesFilter, $PaginateOMat, $this->MY_RIGHTS);

        foreach ($all_usercontainerroles as $index => $usercontainerrole) {
            $all_usercontainerroles[$index]['allow_edit'] = $this->hasRootPrivileges;
            if ($this->hasRootPrivileges === false) {
                foreach ($usercontainerrole['containers'] as $key => $container) {
                    if ($this->isWritableContainer($container['id'])) {
                        $all_usercontainerroles[$index]['allow_edit'] = $this->isWritableContainer($container['id']);
                        break;
                    }
                    $all_usercontainerroles[$index]['allow_edit'] = false;
                }
            }
        }


        $this->set('all_usercontainerroles', $all_usercontainerroles);
        $toJson = ['paging', 'all_usercontainerroles'];
        if ($this->isScrollRequest()) {
            $toJson = ['scroll', 'all_usercontainerroles'];
        }
        $this->set('_serialize', $toJson);
    }


    /**
     * @param null $id
     */
    public function delete($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
        /** @var $Usercontainerroles App\Model\Table\UsercontainerrolesTable */
        $Usercontainerroles = TableRegistry::getTableLocator()->get('Usercontainerroles');
        if (!$Usercontainerroles->existsById($id)) {
            throw new MethodNotAllowedException();
        }
        $usercontainerrole = $Usercontainerroles->get($id);
        if (!$this->allowedByContainerId($usercontainerrole->id)) {
            $this->render403();
            return;
        }

        if ($Usercontainerroles->delete($usercontainerrole)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;

    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Usercontainerroles App\Model\Table\UsercontainerrolesTable */
        $Usercontainerroles = TableRegistry::getTableLocator()->get('Usercontainerroles');


        if ($this->request->is('post') || $this->request->is('put')) {

            // save additional data to containersUsersMemberships
            if (isset($this->request->data['Usercontainerrole']['ContainersUsercontainerrolesMemberships'])) {
                $containerPermissions = $Usercontainerroles->containerPermissionsForSave($this->request->data['Usercontainerrole']['ContainersUsercontainerrolesMemberships']);
                $this->request->data['Usercontainerrole']['containers'] = $containerPermissions;
            }

            $this->request->data = $this->request->data('Usercontainerrole');

            $usercontainerrole = $Usercontainerroles->newEntity();
            $usercontainerrole = $Usercontainerroles->patchEntity($usercontainerrole, $this->request->data);

            $Usercontainerroles->save($usercontainerrole);
            if ($usercontainerrole->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $usercontainerrole->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }

            $this->set('usercontainerrole', $usercontainerrole);
            $this->set('_serialize', ['usercontainerrole']);

        }
    }


    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Usercontainerroles App\Model\Table\UsercontainerrolesTable */
        $Usercontainerroles = TableRegistry::getTableLocator()->get('Usercontainerroles');

        if (!$Usercontainerroles->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User Container Role');
        }

        $usercontainerrole = $Usercontainerroles->getUsercontainerroleWithPermission($id, $this->MY_RIGHTS);

        $this->set('usercontainerrole', $usercontainerrole);
        $this->set('_serialize', ['usercontainerrole']);

        if ($this->request->is('post') || $this->request->is('put')) {

            // save additional data to containersUsersMemberships
            if (isset($this->request->data['Usercontainerrole']['ContainersUsercontainerrolesMemberships'])) {
                $containerPermissions = $Usercontainerroles->containerPermissionsForSave($this->request->data['Usercontainerrole']['ContainersUsercontainerrolesMemberships']);
                $this->request->data['Usercontainerrole']['containers'] = $containerPermissions;
            }

            $this->request->data = $this->request->data('Usercontainerrole');

            $usercontainerrole = $Usercontainerroles->get($id);

            $usercontainerrole = $Usercontainerroles->patchEntity($usercontainerrole, $this->request->data);

            $Usercontainerroles->save($usercontainerrole);
            if ($usercontainerrole->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $usercontainerrole->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }
            $this->set('usercontainerrole', $usercontainerrole);
            $this->set('_serialize', ['usercontainerrole']);
        }
    }

}
