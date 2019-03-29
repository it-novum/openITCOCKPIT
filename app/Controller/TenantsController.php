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


use App\Model\Table\ContainersTable;
use App\Model\Table\TenantsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\TenantFilter;

/**
 * @property Tenant $Tenant
 * @property Container $Container
 * @property AppPaginatorComponent $Paginator
 */
class TenantsController extends AppController {

    public $layout = 'blank';

    public $uses = [
        'Tenant',
        'Container',
    ];

    /**
     * @deprecated
     */
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
        $this->set('_serialize', $toJson);
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
        $this->set('_serialize', ['tenant']);
    }

    /**
     * @deprecated
     */
    public function add() {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['Container']['parent_id'] = ROOT_CONTAINER;
            $this->request->data['Container']['containertype_id'] = CT_TENANT;


            $isJsonRequest = $this->request->ext === 'json';
            if ($this->Tenant->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                if ($isJsonRequest) {
                    $this->serializeId();

                    return;
                } else {
                    if ($this->request->ext != 'json') {
                        $this->serializeId();
                        return;
                    }
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
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

        if (!$this->allowedByContainerId(Hash::extract($container, 'Container.id'))) {
            $this->render403();

            return;
        }

        if ($this->Tenant->__allowDelete($container['Tenant']['container_id'])) {

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            if ($ContainersTable->delete($ContainersTable->get($container['Tenant']['container_id']))) {
                Cache::clear(false, 'permissions');
                $this->set('message', __('Tenant deleted successfully'));
                $this->set('_serialize', ['message']);
                return;
            }
            $this->response->statusCode(400);
            $this->set('message', __('Could not delete tenant'));
            $this->set('_serialize', ['message']);
        }
        $this->response->statusCode(400);
        $this->set('message', __('Could not delete tenant'));
        $this->set('_serialize', ['message']);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function edit($id = null) {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Tenant->exists($id)) {
            throw new NotFoundException(__('Invalid tenant'));
        }
        $tenant = $this->Tenant->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($tenant, 'Container.id'))) {
            $this->render403();

            return;
        }

        $this->set(compact(['tenant']));
        $this->set('_serialize', ['tenant']);
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container']['containertype_id'] = CT_TENANT;
            $this->request->data['Container']['parent_id'] = ROOT_CONTAINER;
            $this->request->data['Container']['id'] = $tenant['Container']['id'];
            $this->request->data['Tenant']['id'] = $tenant['Tenant']['id'];
            if ($this->Tenant->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
        }
    }
}
