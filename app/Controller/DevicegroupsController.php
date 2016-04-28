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


class DevicegroupsController extends AppController{
	public $uses = [
		'Devicegroup',
		'Container',
		'Host',
		'Changelog',
		'Location'
	];
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler'
	];
	public $helpers = array('ListFilter.ListFilter');
	public $listFilters = [
		'index' => [
			'fields' => [
				'Container.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
				'Devicegroup.description' => ['label' => 'description', 'searchType' => 'wildcard'],
			]
		]
	];

	public function index(){
		$options = [
			'order' => [
				'Container.name' => 'asc'
			],
			'conditions' => [
				'Container.parent_id' => $this->MY_RIGHTS
			]
		];

		if($this->hasRootPrivileges === true){
			$container = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}else{
			$container = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}
		$locationIds = $this->Location->find('all',[
			'recursive' => -1,
			'conditions' => [
				'Location.container_id' => array_keys($container)
			],
			'fields' => [
				'Location.id',
				'Location.container_id'
			],
			'limit' => $this->PAGINATOR_LENGTH,
		]);
		$containerToLocation = Hash::combine($locationIds,'{n}.Location.container_id','{n}.Location.id');

		$query = Hash::merge($this->Paginator->settings, $options);

		if($this->isApiRequest()){
			unset($query['limit']);
			$all_devicegroups = $this->Devicegroup->find('all', $query);
		}else{
			$this->Paginator->settings = $query;
			$all_devicegroups = $this->Paginator->paginate();
		}
		$this->set(compact(['all_devicegroups', 'container', 'containerToLocation']));
		$this->set('_serialize', ['all_devicegroups']);
		$this->_isFilter();
	}

	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();

		}
		if(!$this->Devicegroup->exists($id)){
			throw new NotFoundException(__('Invalid devicegroup'));
		}
		$devicegroup = $this->Devicegroup->findById($id);
		if(!$this->allowedByContainerId(Hash::extract($devicegroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}

		$this->set('devicegroup', $devicegroup);
		$this->set('_serialize', ['devicegroup']);
	}

	public function add(){
		if($this->hasRootPrivileges === true){
			$container = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}else{
			$container = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}
		if($this->request->is('post') || $this->request->is('put')){
			$this->Devicegroup->create();
			$this->request->data['Container']['containertype_id'] = CT_DEVICEGROUP;
			if($this->Devicegroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Devicegroup->id,
					OBJECT_DEVICEGROUP,
					$this->request->data('Devicegroup.container_id'),
					$this->Auth->user('id'),
					$this->request->data['Container']['name'],
					$this->request->data
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				if($this->request->ext == 'json'){
					$this->serializeId();

					return;
				}else{
					$this->setFlash(__('Devicegroup successfully saved.'));
					$this->redirect(['action' => 'index']);
				}
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();

					return;
				}else{
					$this->setFlash(__('Could not save data'), false);
				}
			}
		}

		$this->set(compact(['container']));
	}

	public function edit($id = null){
		if(!$this->Devicegroup->exists($id)){
			throw new NotFoundException(__('Invalid devicegroup'));
		}

		$devicegroup = $this->Devicegroup->findById($id);
		if(!$this->allowedByContainerId(Hash::extract($devicegroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}
		if($this->hasRootPrivileges === true){
			$container = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}else{
			$container = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_DEVICEGROUP, [], $this->hasRootPrivileges);
		}
		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Devicegroup']['id'] = $id;
			$this->request->data['Container']['id'] = $devicegroup['Container']['id'];
			$this->request->data['Container']['containertype_id'] = CT_DEVICEGROUP;
			if($this->Devicegroup->saveAll($this->request->data)){
				$this->setFlash(__('Devicegroup successfully saved'));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('Could not save data'), false);
			}
		}
		$this->set(compact(['devicegroup', 'container']));
	}

	public function delete($id = null){
		if(!$this->Devicegroup->exists($id)){
			throw new NotFoundException(__('Invalid devicegroup'));
		}

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}
		$devicegroup = $this->Devicegroup->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($devicegroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}

		$userId = $this->Auth->user('id');
		if($this->Devicegroup->__delete($devicegroup, $userId)){
			$this->setFlash(__('Devicegroup successfully deleted'));
			$this->redirect(['action' => 'index']);
		}else{
			$this->setFlash(__('Could not delete data'), false);
			$this->redirect(['action' => 'index']);
		}
	}

	private function _isFilter(){
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}
}
