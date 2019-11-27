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
use App\Model\Table\TenantsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use FreeDSx\Ldap\Entry\Change;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\TenantFilter;

/**
 * @property Tenant $Tenant
 * @property Changelog $Changelog
 * @property AppPaginatorComponent $Paginator
 */
class TenantsController extends AppController {

    public $layout = 'blank';

    /**
     * @deprecated
     */
    public $uses = [
        'Tenant',
        'Container',
        'Changelog'
    ];

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');
        $TenantFilter = new TenantFilter($this->request);

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $TenantFilter->getPage());
        $all_tenants = $TenantsTable->getTenantsIndex($TenantFilter, $PaginateOMat);

        foreach ($all_tenants as $key => $tenant) {
            $all_tenants[$key]['Tenant']['allowEdit'] = false;
            $tenantContainerId = $tenant['Tenant']['container_id'];
            if (isset($this->MY_RIGHTS_LEVEL[$tenantContainerId])) {
                if ((int)$this->MY_RIGHTS_LEVEL[$tenantContainerId] === WRITE_RIGHT) {
                    $all_tenants[$key]['Tenant']['allowEdit'] = true;
                }
            }
        }

        $this->set('all_tenants', $all_tenants);
        $toJson = ['all_tenants', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_tenants', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @param $id
     */
    public function view($id) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');

        if (!$TenantsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid tenant'));
        }

        $tenant = $TenantsTable->getTenantById($id);

        if (!$this->allowedByContainerId($tenant['container_id'])) {
            $this->render403();
            return;
        }

        $this->set('tenant', $tenant);
        $this->viewBuilder()->setOption('serialize', ['tenant']);
    }

    public function add() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $tenant = $TenantsTable->newEmptyEntity();
            $tenant = $TenantsTable->patchEntity($tenant, $this->request->data);

            $tenant->container->parent_id = ROOT_CONTAINER;
            $tenant->container->containertype_id = CT_TENANT;


            $TenantsTable->save($tenant);
            if ($tenant->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $tenant->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'tenants',
                    $tenant->get('id'),
                    OBJECT_TENANT,
                    [ROOT_CONTAINER],
                    $User->getId(),
                    $tenant->container->name,
                    [
                        'tenant' => $tenant->toArray()
                    ]
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($tenant); // REST API ID serialization
                    return;
                }
            }
            $this->set('tenant', $tenant);
            $this->viewBuilder()->setOption('serialize', ['tenant']);
        }
    }

    /**
     * @param null $id
     */
    public function edit($id = null) {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');

        if (!$TenantsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid tenant'));
        }

        if ($this->request->is('get')) {
            $tenant = $TenantsTable->getTenantById($id);

            if (!$this->allowedByContainerId($tenant['container_id'])) {
                $this->render403();
                return;
            }

            $this->set('tenant', $tenant);
            $this->viewBuilder()->setOption('serialize', ['tenant']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $oldTenant = $TenantsTable->get($id, [
                'contain' => ['Containers']
            ]);
            $oldTenantForChangelog = $oldTenant->toArray();
            if (!$this->allowedByContainerId($oldTenant->get('container_id'))) {
                $this->render403();
                return;
            }

            $tenant = $TenantsTable->patchEntity($oldTenant, $this->request->data);

            $tenant->container_id = $oldTenant->get('container_id');
            $tenant->container->id = $oldTenant->get('container_id');
            $tenant->container->parent_id = ROOT_CONTAINER;
            $tenant->container->containertype_id = CT_TENANT;

            $TenantsTable->save($tenant);
            if ($tenant->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $tenant->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'tenants',
                    $tenant->get('id'),
                    OBJECT_TENANT,
                    [ROOT_CONTAINER],
                    $User->getId(),
                    $tenant->container->name,
                    [
                        'tenant' => $tenant->toArray()
                    ],
                    [
                        'tenant' => $oldTenantForChangelog
                    ]
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                //@todo refactor with cake4
                Cache::clear(false, 'permissions');

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($tenant); // REST API ID serialization
                    return;
                }
            }
            $this->set('tenant', $tenant);
            $this->viewBuilder()->setOption('serialize', ['tenant']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Tenant->exists($id)) {
            throw new NotFoundException(__('Invalid tenant'));
        }

        $container = $this->Tenant->findById($id);

        /** @var $TenantsTable TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');
        $tenant = $TenantsTable->getTenantById($id);
        $tenantForChangelog = $tenant;

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.id'))) {
            $this->render403();

            return;
        }

        if ($this->Tenant->__allowDelete($container['Tenant']['container_id'])) {

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            if ($ContainersTable->delete($ContainersTable->get($container['Tenant']['container_id']))) {
                Cache::clear(false, 'permissions');

                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'delete',
                    'tenants',
                    $id,
                    OBJECT_TENANT,
                    [ROOT_CONTAINER],
                    $User->getId(),
                    $tenantForChangelog['container']['name'],
                    []
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                $this->set('message', __('Tenant deleted successfully'));
                $this->viewBuilder()->setOption('serialize', ['message']);
                return;
            }
            $this->response->statusCode(400);
            $this->set('message', __('Could not delete tenant'));
            $this->viewBuilder()->setOption('serialize', ['message']);
        }
        $this->response->statusCode(400);
        $this->set('message', __('Could not delete tenant'));
        $this->viewBuilder()->setOption('serialize', ['message']);
    }


}
