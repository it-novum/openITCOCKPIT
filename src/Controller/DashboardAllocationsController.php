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

use App\Model\Table\ContainersTable;
use App\Model\Table\DashboardTabAllocationsTable;
use App\Model\Table\DashboardTabsTable;
use App\Model\Table\UsergroupsTable;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\DashboardTabAllocationsFilter;
use itnovum\openITCOCKPIT\Filter\GenericFilter;


/**
 * Class DashboardAllocationsController
 * @package App\Controller
 */
class DashboardAllocationsController extends AppController {

    use LocatorAwareTrait;

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var DashboardTabAllocationsTable $DashboardTabAllocationsTable */
        $DashboardTabAllocationsTable = TableRegistry::getTableLocator()->get('DashboardTabAllocations');
        $DashboardTabAllocationsFilter = new DashboardTabAllocationsFilter($this->request);

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $DashboardTabAllocationsFilter->getPage(),);
        $all_dashboardtab_allocations = $DashboardTabAllocationsTable->getDashboardTabAllocationsIndex($DashboardTabAllocationsFilter, $PaginateOMat, $MY_RIGHTS);

        $this->set('all_dashboardtab_allocations', $all_dashboardtab_allocations);
        $this->viewBuilder()->setOption('serialize', ['all_dashboardtab_allocations']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var DashboardTabAllocationsTable $DashboardTabAllocationsTable */
            $DashboardTabAllocationsTable = TableRegistry::getTableLocator()->get('DashboardTabAllocations');

            $allocation = $DashboardTabAllocationsTable->newEmptyEntity();
            $allocation = $DashboardTabAllocationsTable->patchEntity($allocation, $this->request->getData('Allocation', []));

            // Set the author of the allocation
            $User = new User($this->getUser());
            $allocation->set('user_id', $User->getId());

            $DashboardTabAllocationsTable->save($allocation);

            if ($allocation->hasErrors()) {
                $this->set('error', $allocation->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                $this->response = $this->response->withStatus(400);
                return;
            }

            //No errors
            $this->set('allocation', $allocation);
            $this->viewBuilder()->setOption('serialize', ['allocation']);
        }
    }

    public function edit($id = null) {

    }

    public function delete($id = null) {

    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadElementsByContainerId($containerId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var UsergroupsTable $UsergroupsTable */
        $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid container'));
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);
/*
        $dashboardTabs = [
            20 => 'Default (Herbert Benutzer)'
        ];
        $dashboardTabs = Api::makeItJavaScriptAble($dashboardTabs);
*/

        $users = $UsersTable->usersByContainerId($containerIds, 'list');
        $users = Api::makeItJavaScriptAble($users);

        $usergroups = $UsergroupsTable->getUsergroupsList();
        $usergroups = Api::makeItJavaScriptAble($usergroups);

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }

        $dashboardTabs = $UsersTable->getDashboardTabsByContainerIdsAsList($containerIds, $MY_RIGHTS);
        $dashboardTabs = Api::makeItJavaScriptAble($dashboardTabs);

        $this->set('users', $users);
        $this->set('usergroups', $usergroups);
        $this->set('dashboard_tabs', $dashboardTabs);

        $this->viewBuilder()->setOption('serialize', [
            'users',
            'usergroups',
            'dashboard_tabs'
        ]);
    }

}
