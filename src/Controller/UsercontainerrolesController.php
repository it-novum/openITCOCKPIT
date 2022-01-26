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

use App\Model\Table\LdapgroupsTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsercontainerrolesTable;
use Cake\Cache\Cache;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\LdapgroupFilter;
use itnovum\openITCOCKPIT\Filter\UsercontainerrolesFilter;

/**
 * Class UsercontainerrolesController
 * @package App\Controller
 */
class UsercontainerrolesController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $UsercontainerrolesFilter = new UsercontainerrolesFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $UsercontainerrolesFilter->getPage());

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');
        $all_usercontainerroles = $UsercontainerrolesTable->getUsercontainerRolesIndex($UsercontainerrolesFilter, $PaginateOMat, $this->MY_RIGHTS);

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
        $this->viewBuilder()->setOption('serialize', $toJson);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->getData('Usercontainerrole', []);
            if (!isset($data['ContainersUsercontainerrolesMemberships'])) {
                $data['ContainersUsercontainerrolesMemberships'] = [];
            }
            $data['containers'] = $UsercontainerrolesTable->containerPermissionsForSave($data['ContainersUsercontainerrolesMemberships']);

            $usercontainerrole = $UsercontainerrolesTable->newEmptyEntity();
            $usercontainerrole = $UsercontainerrolesTable->patchEntity($usercontainerrole, $data);
            $UsercontainerrolesTable->save($usercontainerrole);
            if ($usercontainerrole->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $usercontainerrole->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            Cache::clear('permissions');
            $this->set('usercontainerrole', $usercontainerrole);
            $this->viewBuilder()->setOption('serialize', ['usercontainerrole']);
        }
    }

    /**
     * @param int|null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

        if (!$UsercontainerrolesTable->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User Container Role');
        }

        $usercontainerrole = $UsercontainerrolesTable->getUserContainerRoleForEdit($id);

        if (!$this->allowedByContainerId($usercontainerrole['Usercontainerrole']['containers']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return user container roles information
            $this->set('usercontainerrole', $usercontainerrole['Usercontainerrole']);
            $this->viewBuilder()->setOption('serialize', ['usercontainerrole']);
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->getData('Usercontainerrole', []);
            if (!isset($data['ContainersUsercontainerrolesMemberships'])) {
                $data['ContainersUsercontainerrolesMemberships'] = [];
            }
            $data['containers'] = $UsercontainerrolesTable->containerPermissionsForSave($data['ContainersUsercontainerrolesMemberships']);
            $usercontainerrole = $UsercontainerrolesTable->get($id);
            $usercontainerrole->setAccess('id', false);

            $usercontainerrole = $UsercontainerrolesTable->patchEntity($usercontainerrole, $data);
            $UsercontainerrolesTable->save($usercontainerrole);
            if ($usercontainerrole->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $usercontainerrole->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            Cache::clear('permissions');
            $this->set('usercontainerrole', $usercontainerrole);
            $this->viewBuilder()->setOption('serialize', ['usercontainerrole']);
        }
    }

    /**
     * @param int|null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var UsercontainerrolesTable $UsercontainerrolesTable */
        $UsercontainerrolesTable = TableRegistry::getTableLocator()->get('Usercontainerroles');

        if (!$UsercontainerrolesTable->existsById($id)) {
            throw new MethodNotAllowedException('Invalid User Container Role');
        }

        $usercontainerrole = $UsercontainerrolesTable->getUserContainerRoleForEdit($id);

        if (!$this->allowedByContainerId($usercontainerrole['Usercontainerrole']['containers']['_ids'])) {
            $this->render403();
            return;
        }

        $usercontainerrole = $UsercontainerrolesTable->get($id);
        if ($UsercontainerrolesTable->delete($usercontainerrole)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadLdapgroupsForAngular() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $isLdapAuth = $SystemsettingsTable->isLdapAuth();
        $ldapgroups = [];

        if ($isLdapAuth === true) {
            $selected = $this->request->getQuery('selected', []);
            $LdapgroupFilter = new LdapgroupFilter($this->request);
            $where = $LdapgroupFilter->ajaxFilter();

            /** @var $LdapgroupsTable LdapgroupsTable */
            $LdapgroupsTable = TableRegistry::getTableLocator()->get('Ldapgroups');
            $ldapgroups = $LdapgroupsTable->getLdapgroupsForAngular($where, $selected);

            $ldapgroups = Api::makeItJavaScriptAble($ldapgroups);
        }

        $this->set('isLdapAuth', $isLdapAuth);
        $this->set('ldapgroups', $ldapgroups);
        $this->viewBuilder()->setOption('serialize', ['ldapgroups', 'isLdapAuth']);
    }

}
