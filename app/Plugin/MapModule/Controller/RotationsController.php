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

class RotationsController extends MapModuleAppController {
	public $layout = 'Admin.default';
		public $components = [
		'Paginator',
		'ListFilter.ListFilter',
	];
	public $helpers = [
		'ListFilter.ListFilter',
	];

	public $listFilters = [
		'index' => [
			'fields' => [
				'Rotation.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
			],
		],
	];

	public $uses = ['MapModule.Rotation', 'MapModule.Map'];


	public function index(){
		$this->Paginator->settings['limit'] = $this->PAGINATOR_LENGTH;
		$all_rotations = $this->Paginator->paginate();

		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}

		$this->set(compact(['all_rotations']));
	}

	public function add(){
		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Map'] = $this->request->data['Rotation']['Map'];
			if($this->Rotation->save($this->request->data)){
				$this->setFlash(__('Rotation saved successfully'));
				return $this->redirect(['action' => 'index']);
			}
			$this->setFlash(__('Could not save data'), false);
			debug($this->Rotation->validationErrors);
		}

		$maps = $this->Map->find('list');
		$this->set(compact('maps'));

	}

		public function edit($id = null){
		if(!$this->Rotation->exists($id)){
			throw new NotFoundException(__('Invalid map rotation'));
		}

		if($this->request->is('post') || $this->request->is('put')){
			$this->request->data['Map'] = $this->request->data['Rotation']['Map'];
			if($this->Rotation->save($this->request->data)){
				$this->setFlash(__('Rotation modifed successfully'));
				return $this->redirect(['action' => 'index']);
			}
			$this->setFlash(__('Could not save data'), false);
			debug($this->Rotation->validationErrors);
		}

		$rotation = $this->Rotation->findById($id);
		$maps = $this->Map->find('list');
		$this->set(compact('maps', 'rotation'));

	}

	public function delete($id = null){
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$this->Rotation->id = $id;
		if (!$this->Rotation->exists()){
			throw new NotFoundException(__('Invalid map rotation'));
		}

		if($this->Rotation->delete()){
			$this->setFlash(__('Map rotation deleted successfully'));
			return $this->redirect(['action' => 'index']);
		}
		$this->setFlash(__('Could not delete map rotation'), false);
		return $this->redirect(['action' => 'index']);

	}
}
