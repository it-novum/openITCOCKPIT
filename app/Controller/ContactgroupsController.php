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


/**
 * @property Contactgroup       $Contactgroup
 * @property Container          $Container
 * @property Contact            $Contact
 * @property User               $User
 * @property ChangelogComponent $Changelog
 */
class ContactgroupsController extends AppController{

	public $uses = [
		'Contactgroup',
		'Container',
		'Contact',
		'User',
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
				'Container.name' => [
					'label' => 'Name',
					'searchType' => 'wildcard',
				],
				'Contactgroup.description' => [
					'label' => 'Alias',
					'searchType' => 'wildcard',
				],
			],
		],
	];

	public function index(){
		$options = [
			'order' => [
				'Container.name' => 'asc',
			],
			'conditions' => [
				'Container.parent_id' => $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS),
			]
		];

		$this->Paginator->settings = Hash::merge($options, $this->Paginator->settings);

		$this->set('all_contactgroups', $this->Paginator->paginate());
		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', ['all_contactgroups']);
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}
	
	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();

		}
		if(!$this->Contactgroup->exists($id)){
			throw new NotFoundException(__('Invalid contact group'));
		}
		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}
		$contactgroup = $this->Contactgroup->findById($id);


		if(!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))){
			throw new ForbiddenException('404 Forbidden');
		}

		$this->set('contactgroup', $contactgroup);
		$this->set('_serialize', ['contactgroup']);
	}

	public function edit($id = null){
		$userId = $this->Auth->user('id');
		if(!$this->Contactgroup->exists($id)){
			throw new NotFoundException(__('Invalid contactgroup'));
		}

		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}
		$contactgroup = $this->Contactgroup->findById($id);


		if(!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}

		if($this->request->is('post') || $this->request->is('put')){
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
			$contacts = $this->Contact->contactsByContainerId($containerIds, 'list');

			$ext_data_for_changelog = [];
			if(isset($this->request->data['Contactgroup']['Contact']) && is_array($this->request->data['Contactgroup']['Contact'])){
				foreach($this->request->data['Contactgroup']['Contact'] as $contact_id){
					$_contact = $this->Contact->find('first', [
						'recursive' => -1,
						'fields' => [
							'Contact.id',
							'Contact.name'
						],
						'conditions' => [
							'Contact.id' => $contact_id
						]
					]);
					$ext_data_for_changelog['Contact'][] = [
						'id' => $contact_id,
						'name' => $_contact['Contact']['name']
					];
				}
			}
			if(isset($this->request->data['Container']['name'])){
				$ext_data_for_changelog['Container']['name'] = $this->request->data['Container']['name'];
			}

			$this->request->data['Contact'] = $this->request->data['Contactgroup']['Contact'];
			$this->request->data['Container']['id'] = $this->request->data['Contactgroup']['container_id'];

			//Save Contact associations -> Array Format [Contact] => data
			if($this->Contactgroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Contactgroup->id,
					OBJECT_CONTACTGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data['Container']['name'],
					array_merge($this->request->data, $ext_data_for_changelog),
					$contactgroup
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('<a href="/contactgroups/edit/%s">Contact group</a> successfully saved', $this->Contactgroup->id));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Contactgroup could not be saved'), false);
			}
		}else{
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($contactgroup['Container']['parent_id']);
			$contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
			$contactgroup['Contactgroup']['Contact'] = Hash::combine($contactgroup['Contact'], '{n}.id', '{n}.id');
		}

		$this->request->data = Hash::merge($contactgroup, $this->request->data); // Is used in the template file
		$data = [
			'contactgroup' => $contactgroup,
			'containers' => $containers,
			'contacts' => $contacts,
		];
		$this->set($data);
		$this->set('_serialize', array_keys($data));
	}

	public function add(){
		$userId = $this->Auth->user('id');
		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
		}

		$this->Frontend->set('data_placeholder', __('Please choose a contact'));
		$this->Frontend->set('data_placeholder_empty', __('No entries found'));

		$contacts = [];
		if($this->request->is('post') || $this->request->is('put')){
			if(isset($this->request->data['Container']['parent_id'])){
				$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['Container']['parent_id']);
				$contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
			}

			App::uses('UUID', 'Lib');
			$this->request->data['Contactgroup']['uuid'] = UUID::v4();
			$this->request->data['Container']['containertype_id'] = CT_CONTACTGROUP;
			$ext_data_for_changelog = [];
			//Save Contact associations -> Array Format [Contact] => data
			if(isset($this->request->data['Contactgroup']['Contact']) && is_array($this->request->data['Contactgroup']['Contact'])){
				foreach($this->request->data['Contactgroup']['Contact'] as $contact_id){
					$_contact = $this->Contact->find('first', [
						'recursive' => -1,
						'fields' => [
							'Contact.id',
							'Contact.name'
						],
						'conditions' => [
							'Contact.id' => $contact_id
						]
					]);
					$ext_data_for_changelog['Contact'][] = [
						'id' => $contact_id,
						'name' => $_contact['Contact']['name']
					];
					unset($_contact);
				}
			}
			if(isset($this->request->data['Container']['name'])){
				$ext_data_for_changelog['Container']['name'] = $this->request->data['Container']['name'];
			}
			if(isset($this->request->data['Contactgroup']['Contact'])){
				$this->request->data['Contact'] = $this->request->data['Contactgroup']['Contact'];
			}

			if($this->Contactgroup->saveAll($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Contactgroup->id,
					OBJECT_CONTACTGROUP,
					$this->request->data('Container.parent_id'),
					$userId,
					$this->request->data['Container']['name'],
					array_merge($this->request->data, $ext_data_for_changelog)
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				// for rest usage
				if($this->request->ext == 'json'){
					$this->serializeId();

					return;
				}

				$this->setFlash(__('<a href="/contactgroups/edit/%s">Contact group</a> successfully saved', $this->Contactgroup->id));
				$this->redirect(array('action' => 'index'));
			}else{
				if($this->request->ext == 'json'){
					$this->serializeErrorMessage();
					return;
				}

				$this->setFlash(__('Could not save data'), false);
			}
		}

		$this->set(compact(['containers', 'contacts']));
		$this->set('_serialize', ['containers', 'contacts']);
	}

	public function loadContacts($containerIds = null){
		$this->allowOnlyAjaxRequests();

		$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);
		$contacts = $this->Contact->contactsByContainerId($containerIds, 'list');
		$contacts = $this->Contact->makeItJavaScriptAble($contacts);

		$data = [
			'contacts' => $contacts,
		];
		$this->set($data);
		$this->set('_serialize', array_keys($data));
	}

	protected function __allowDelete($contactgroup){
		if(is_numeric($contactgroup)){
			$contactgroupId = $contactgroup;
		}else{
			$contactgroupId = $contactgroup['Contactgroup']['id'];
		}

		$models = [
			'__ContactgroupsToHosttemplates',
			'__ContactgroupsToHosts',
			'__ContactgroupsToServicetemplates',
			'__ContactgroupsToServices',
			'__ContactgroupsToHostescalations',
			'__ContactgroupsToServiceescalations',
		];

		foreach($models as $model){
			$this->loadModel($model);
			$count = $this->{$model}->find('count', [
				'conditions' => [
					'contactgroup_id' => $contactgroupId
				]
			]);
			if($count > 0){
				return false;
			}
		}
		return true;
	}

	public function delete($id){
		$userId = $this->Auth->user('id');
		$contactgroup = $this->Contactgroup->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))){
			$this->render403();
			return;
		}

		if($this->__allowDelete($id)){
			if($this->Contactgroup->delete($id)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$id,
					OBJECT_CONTACTGROUP,
					$contactgroup['Container']['parent_id'],
					$userId,
					$contactgroup['Container']['name'],
					$contactgroup
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('Contactgroup deleted'));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Could not delete contactgroup'), false);
				$this->redirect(['action' => 'index']);
			}
		}else{
			$contactgroupsCanotDelete = [$contactgroup['Container']['name']];
			$this->set(compact(['contactgroupsCanotDelete']));
			$this->render('mass_delete');
		}

	}

	public function mass_delete($id = null){
		$userId = $this->Auth->user('id');

		if($this->request->is('post') || $this->request->is('put')){
			foreach($this->request->data('Contactgroup.delete') as $contactgroupId){
				if($this->Contactgroup->exists($contactgroupId)){
					$contactgroup = $this->Contactgroup->findById($contactgroupId);
					if($this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))){
						if($this->Contactgroup->delete($contactgroupId)){
							$changelog_data = $this->Changelog->parseDataForChangelog(
								$this->params['action'],
								$this->params['controller'],
								$contactgroupId,
								OBJECT_CONTACTGROUP,
								$contactgroup['Container']['parent_id'],
								$userId,
								$contactgroup['Container']['name'],
								$contactgroup
							);
							if($changelog_data){
								CakeLog::write('log', serialize($changelog_data));
							}
						}
					}
				}
			}
			$this->setFlash(__('Contactgroups deleted'));
			$this->redirect(['action' => 'index']);
		}

		foreach(func_get_args() as $contactgroupId){
			if($this->Contactgroup->exists($contactgroupId)){
				$contactgroup = $this->Contactgroup->findById($contactgroupId);
				if($this->allowedByContainerId(Hash::extract($contactgroup, 'Container.parent_id'))){
					if($this->__allowDelete($contactgroupId)){
						$contactgroupsToDelete[] = $contactgroup;
					}else{
						$contactgroupsCanotDelete[] = $contactgroup['Container']['name'];
					}
				}
			}
		}
		$count = sizeof($contactgroupsToDelete) + sizeof($contactgroupsCanotDelete);
		$this->set(compact(['contactgroupsToDelete', 'contactgroupsCanotDelete', 'count']));


	}
}
