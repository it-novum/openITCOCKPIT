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


use itnovum\openITCOCKPIT\Filter\TenantFilter;

App::import('Model', 'Container');


/**
 * @property Tenant $Tenant
 * @property Container $Container
 */
class TenantsController extends AppController {
    public $uses = [
        'Tenant',
        'Container',
        'CakeTime',
        'Utility',
    ];
    public $layout = 'angularjs';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $helpers = ['ListFilter.ListFilter'];
    public $listFilters = [
        'index' => [
            'fields' => [
                'Container.name'     => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Tenant.description' => ['label' => 'description', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        $TenantFilter = new TenantFilter($this->request);

        $this->Tenant->virtualFields['name'] = 'Container.name';

        $query = [
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'order'      => [
                $TenantFilter->getOrderForPaginator('Container.name', 'asc'),
            ],
            'conditions' => $TenantFilter->indexFilter(),
            'limit' => $this->Paginator->settings['limit']
        ];

        if (!$this->hasRootPrivileges) {
            $query['conditions']['Container.id'] = $this->MY_RIGHTS;
        }


        if ($this->isApiRequest()) {
            unset($query['limit']);
            $all_tenants = $this->Tenant->find('all', $query);
        } else {
            $this->Paginator->settings = $query;
            $all_tenants = $this->Paginator->paginate();
        }

        foreach ($all_tenants as $key => $tenant) {
            $all_tenants[$key]['Tenant']['allowEdit'] = false;
            $tenantContainerId = $tenant['Tenant']['container_id'];
            if (isset($this->MY_RIGHTS_LEVEL[$tenantContainerId])) {
                if ((int)$this->MY_RIGHTS_LEVEL[$tenantContainerId] === WRITE_RIGHT) {
                    $all_tenants[$key]['Tenant']['allowEdit'] = true;
                }
            }
        }
        $this->set(compact(['all_tenants']));
        $this->set('_serialize', ['all_tenants']);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Tenant->exists($id)) {
            throw new NotFoundException(__('Invalid tenant'));
        }
        $tenant = $this->Tenant->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($tenant, 'Container.id'))) {
            $this->render403();

            return;
        }

        $this->set('tenant', $tenant);
        $this->set('_serialize', ['tenant']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Container']['containertype_id'] = CT_TENANT;
            $this->request->data['Container']['parent_id'] = CT_GLOBAL;

            $isJsonRequest = $this->request->ext === 'json';
            if ($this->Tenant->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                if ($isJsonRequest) {
                    $this->serializeId();

                    return;
                } else {
                    if ($this->request->ext != 'json') {
                        if ($this->isAngularJsRequest()) {
                            $this->setFlash(__('<a href="/tenants/edit/%s">Tenant</a> successfully saved', $this->Tenant->id));
                        }
                        $this->serializeId();
                        return;
                    }
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
                $this->setFlash(__('Could not save data'), false);
            }
        }
    }


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
            if ($this->Container->delete($container['Tenant']['container_id'])) {
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

    public function edit($id = null) {
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
            $this->request->data['Container']['parent_id'] = CT_GLOBAL;
            $this->request->data['Container']['id'] = $tenant['Container']['id'];
            $this->request->data['Tenant']['id'] = $tenant['Tenant']['id'];
            if ($this->Tenant->saveAll($this->request->data)) {
                Cache::clear(false, 'permissions');
                $this->setFlash(__('<a href="/tenants/edit/%s">Tenant</a> successfully saved', $this->Tenant->id));
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                    return;
                }
            }
        }
    }
}
