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


App::import('Model', 'Container');


/**
 * @property Tenant    $Tenant
 * @property Container $Container
 */
class TenantsController extends AppController{
	public $uses = [
		'Tenant',
		'Container',
		'CakeTime',
		'Utility'
	];
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		 'ListFilter.ListFilter',
		'RequestHandler',
	];
	public $helpers = ['ListFilter.ListFilter'];
	public $listFilters = [
		'index' => [
			'fields' => [
				'Container.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
				'Tenant.description' => ['label' => 'description', 'searchType' => 'wildcard'],
			]
		]
	];
	public function index(){
		$this->Tenant->virtualFields['name'] = 'Container.name';

		$options = [
			'order' => [
				'Container.name' => 'asc'
			],
			'conditions' => [
				'Container.id' => $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS),
			]
		];

		$this->Paginator->settings = Hash::merge($options, $this->Paginator->settings);

		$all_tenants = $this->Paginator->paginate();
		$this->set(compact(['all_tenants']));
		$this->set('_serialize', ['all_tenants']);
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}

	public function add(){
		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Container']['containertype_id'] = CT_TENANT;
			$this->request->data['Container']['parent_id'] = CT_GLOBAL;
			if($this->request->data('Tenant.expires')){
				$this->request->data['Tenant']['expires'] = CakeTime::format($this->request->data['Tenant']['expires'], '%Y-%m-%d');
			}

			$isJsonRequest = $this->request->ext === 'json';
			if($this->Tenant->saveAll($this->request->data)){
				if($isJsonRequest){
					$this->serializeId();
					return;
				}else{
					if($this->request->ext != 'json'){
						$this->setFlash(__('Tenant successfully saved'));
						$this->redirect(['action' => 'index']);
					}
				}
			}else{
				if($isJsonRequest){
					$this->serializeErrorMessage();
					return;
				}else{
					$this->setFlash(__('Tenant could not be saved'), false);
				}
			}
		}
	}

	public function delete($id = null){
		if (!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		if (!$this->Tenant->exists($id)){
			throw new NotFoundException(__('Invalid tenant'));
		}

		$container = $this->Tenant->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($container, 'Container.id'))){
			$this->render403();
			return;
		}

		if($this->Container->delete($container['Tenant']['container_id'])){
			$this->setFlash(__('Tenant deleted'));
			$this->redirect(['action' => 'index']);
		}
		$this->setFlash(__('Could not delete tenant'), false);
		$this->redirect(['action' => 'index']);
	}

	public function mass_delete($id = null){
		foreach(func_get_args() as $tenantId){
			if($this->Tenant->exists($tenantId)){
				$container = $this->Tenant->find('first', [
					'contain' => [
						'Container'
					],
					'conditions' => [
						'Tenant.id' => $tenantId
					]
				]);
				if($this->allowedByContainerId(Hash::extract($container, 'Container.id'))){
					$this->Container->delete($container['Tenant']['container_id']);
				}
			}
		}
		$this->setFlash(__('Tenants deleted'));
		$this->redirect(['action' => 'index']);
	}

	public function edit($id = null){
		if(!$this->Tenant->exists($id)){
			throw new NotFoundException(__('Invalid tenant'));
		}
		$tenant = $this->Tenant->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($tenant, 'Container.id'))){
			$this->render403();
			return;
		}
		$this->set(compact(['tenant']));
		$this->set('_serialize', ['tenant']);
		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Container']['containertype_id'] = CT_TENANT;
			$this->request->data['Container']['parent_id'] = CT_GLOBAL;
			$this->request->data['Container']['id'] = $tenant['Container']['id'];
			$this->request->data['Tenant']['id'] = $tenant['Tenant']['id'];
			if($this->request->data('Tenant.expires')){
				$this->request->data['Tenant']['expires'] = CakeTime::format($this->request->data['Tenant']['expires'], '%Y-%m-%d');
			}
			if($this->Tenant->saveAll($this->request->data)){
				$this->setFlash(__('Tenant successfully saved'));
				$this->redirect(['action' => 'index']);
			}
			$this->setFlash(__('Tenant could not be saved'), false);
		}
	}
}
