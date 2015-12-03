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
 * @property Contact    $Contact
 * @property Container  $Container
 * @property Command    $Command
 * @property Timeperiod $Timeperiod
 */
class ContactsController extends AppController{
	public $uses = [
		'Contact',
		'Container',
		'Command',
		'Timeperiod',
	];
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
		'Ldap',
	];
	public $helpers = ['ListFilter.ListFilter'];

	public $listFilters = [
		'index' => [
			'fields' => [
				'Contact.name' => [
					'label' => 'Name',
					'searchType' => 'wildcard',
				],
				'Contact.email' => [
					'label' => 'Email',
					'searchType' => 'wildcard',
				],
				'Contact.phone' => [
					'label' => 'Pager',
					'searchType' => 'wildcard',
				],
			],
		],
	];

	function index(){
		$systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
		$this->Contact->unbindModel([
				'hasAndBelongsToMany' => ['HostCommands', 'ServiceCommands', 'Contactgroup'],
				'belongsTo' => ['HostTimeperiod', 'ServiceTimeperiod']
			]
		);
		$options = [
			//'recursive' => -1,
			'joins' => [
				[
					'table' => 'contacts_to_containers',
					'type' => 'INNER',
					'alias' => 'ContactsToContainer',
					'conditions' => [
						'ContactsToContainer.contact_id = Contact.id'
					]
				],
				[
					'table' => 'containers',
					'type' => 'INNER',
					'alias' => 'Container',
					'conditions' => [
						'Container.id = ContactsToContainer.container_id',
						'Container.containertype_id NOT' => CT_CONTACTGROUP
					]
				]
			],
			'conditions' => [
				'ContactsToContainer.container_id' => $this->MY_RIGHTS
			],
			'order' => ['Contact.name' => 'asc'],
			'group' => ['Contact.id']
		];

		$query = Hash::merge($options, $this->Paginator->settings);
		if($this->isApiRequest()){
			$all_contacts = $this->Contact->find('all', $query);
		}else{
			$this->Paginator->settings = $query;
			$all_contacts = $this->Paginator->paginate();
		}

		$contactsWithContainers = [];
		$MY_RIGHTS = $this->MY_RIGHTS;
		foreach($all_contacts as $key => $contact){
			$contactsWithContainers[$contact['Contact']['id']] = [];
			foreach($contact['Container'] as $container){
				$contactsWithContainers[$contact['Contact']['id']][] = $container['id'];
			}

			$all_contacts[$key]['allowEdit'] = true;
			if($this->hasRootPrivileges === false){
				if(!empty(array_diff($contactsWithContainers[$contact['Contact']['id']], $this->getWriteContainers()))){
					$all_contacts[$key]['allowEdit'] = false;
				}
			}

		}

		//$this->Paginator->settings['limit'] = 1;
		$this->set(compact(['all_contacts', 'systemsettings']));
		//Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
		$this->set('_serialize', array('all_contacts'));
		$this->set('isFilter', false);
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}
	}

	public function view($id = null){
		if(!$this->isApiRequest()){
			throw new MethodNotAllowedException();

		}
		if(!$this->Contact->exists($id)){
			throw new NotFoundException(__('Invalid contact'));
		}
		$contact = $this->Contact->findById($id);
		if(!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))){
			throw new ForbiddenException('404 Forbidden');
		}

		if(!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))){
			throw new ForbiddenException('404 Forbidden');
		}
		$this->set('contact', $contact);
		$this->set('_serialize', ['contact']);
	}

	public function edit($id = null){
		$userId = $this->Auth->user('id');
		if(!$this->Contact->exists($id)){
			throw new NotFoundException(__('Invalid contact'));
		}
		/*fixme for permissions*/

		$contact = $this->Contact->findById($id);
		if(!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))){
			$this->render403();
			return;
		}

		if(!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))){
			$this->render403();
			return;
		}

		$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
		$notification_commands = $this->Command->notificationCommands('list');
		$timeperiods = $this->Timeperiod->find('list');

		$containerIds = Hash::extract($contact, 'Container.{n}.id');

		if ($this->request->is('post') || $this->request->is('put')) {
			if(isset($this->request->data['Container']['Container'])){
				$containerIds = $this->request->data['Container']['Container'];
			}

			$ext_data_for_changelog = [
				'HostTimeperiod' => [
					'id' => $this->request->data['Contact']['host_timeperiod_id'],
					'name' => isset($timeperiods[$this->request->data['Contact']['host_timeperiod_id']])?$timeperiods[$this->request->data['Contact']['host_timeperiod_id']]:'',
				],
				'ServiceTimeperiod' => [
					'id' => $this->request->data['Contact']['service_timeperiod_id'],
					'name' => isset($timeperiods[$this->request->data['Contact']['service_timeperiod_id']])?$timeperiods[$this->request->data['Contact']['service_timeperiod_id']]:'',
				],
			];

			if(isset($this->request->data['Contact']['HostCommands']) && is_array($this->request->data['Contact']['HostCommands'])){
				foreach($this->request->data['Contact']['HostCommands'] as $command_id){
					$ext_data_for_changelog['HostCommands'][] = [
						'id' => $command_id,
						'name' => $notification_commands[$command_id]
					];
				}
			}
			if(isset($this->request->data['Contact']['ServiceCommands']) && is_array($this->request->data['Contact']['ServiceCommands'])){
				foreach($this->request->data['Contact']['ServiceCommands'] as $command_id){
					$ext_data_for_changelog['ServiceCommands'][] = [
						'id' => $command_id,
						'name' => $notification_commands[$command_id]
					];
				}
			}

			$this->Contact->id = $id;
			if ($this->Contact->save($this->request->data)) {
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$id,
					OBJECT_CONTACT,
					$containerIds,
					$userId,
					$this->request->data['Contact']['name'],
					array_merge($ext_data_for_changelog, $this->request->data),
					$contact
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('<a href="/contacts/edit/%s">Contact</a> successfully saved', $this->Contact->id));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Contact could not be saved'), false);
			}
			if(isset($this->Contact->validationErrors['notify_host_recovery'])){
				$this->set('validation_host_notification', $this->Contact->validationErrors['notify_host_recovery'][0]);
			}
			if(isset($this->Contact->validationErrors['notify_service_recovery'])){
				$this->set('validation_service_notification', $this->Contact->validationErrors['notify_service_recovery'][0]);
			}
		}

		if(!$this->request->is('post') && !$this->request->is('put')){
			$contact['Contact']['HostCommands'] = Hash::extract($contact['HostCommands'], '{n}.id');
			$contact['Contact']['ServiceCommands'] = Hash::extract($contact['ServiceCommands'], '{n}.id');
		}

		$this->request->data = Hash::merge($contact, $this->request->data);

		$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);
		$_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');

		$this->set(compact(array('contact', 'containers', 'notification_commands', 'timeperiods', '_timeperiods')));
		$this->set('_serialize', array('contact', 'notification_commands', 'timeperiods', '_timeperiods'));
	}

	public function add(){
		$userId = $this->Auth->user('id');
		if($this->hasRootPrivileges === true){
			$containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
		}else{
			$containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
		}
		$notification_commands = $this->Command->notificationCommands('list');
		$timeperiods = $this->Timeperiod->find('list');

		$_timeperiods = [];

		$isLdap = false;
		if($this->getNamedParameter('ldap', 0) == 1){
			$isLdap = true;
			$this->request->data['Contact']['email'] = $this->getNamedParameter('email', '');
			$this->request->data['Contact']['name'] = $this->getNamedParameter('samaccountname', '');
		}

		if($this->request->is('post') || $this->request->is('put')){
			$containerIds = [];
			if(isset($this->request->data['Container']['Container'])){
				$containerIds = $this->request->data['Container']['Container'];
			}
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($containerIds);
			$_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');

			$this->Contact->set($this->request->data);
			$ext_data_for_changelog = [
				'HostTimeperiod' => [
					'id' => $this->request->data['Contact']['host_timeperiod_id'],
					'name' => isset($timeperiods[$this->request->data['Contact']['host_timeperiod_id']])?$timeperiods[$this->request->data['Contact']['host_timeperiod_id']]:'',
				],
				'ServiceTimeperiod' => [
					'id' => $this->request->data['Contact']['service_timeperiod_id'],
					'name' => isset($timeperiods[$this->request->data['Contact']['service_timeperiod_id']])?$timeperiods[$this->request->data['Contact']['service_timeperiod_id']]:'',
				],
			];

			if(is_array($this->request->data['Contact']['HostCommands'])){
				foreach($this->request->data['Contact']['HostCommands'] as $command_id){
					$ext_data_for_changelog['HostCommands'][] = [
						'id' => $command_id,
						'name' => $notification_commands[$command_id]
					];
				}
			}
			if(is_array($this->request->data['Contact']['ServiceCommands'])){
				foreach($this->request->data['Contact']['ServiceCommands'] as $command_id){
					$ext_data_for_changelog['ServiceCommands'][] = [
						'id' => $command_id,
						'name' => $notification_commands[$command_id]
					];
				}
			}
			$this->Contact->set('uuid', UUID::v4());
			if($this->Contact->save($this->request->data)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$this->Contact->id,
					OBJECT_CONTACT,
					$this->request->data('Container.Container'),
					$userId,
					$this->request->data['Contact']['name'],
					array_merge($ext_data_for_changelog, $this->request->data)
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}

				if($this->request->ext === 'json'){
					$this->serializeId();

					return;
				}
				$this->setFlash(__('<a href="/contacts/edit/%s">Contact</a> successfully saved', $this->Contact->id));
				$this->redirect(array('action' => 'index'));
			}
			if($this->request->ext === 'json'){
				$this->serializeErrorMessage();

				return;
			}

			if(isset($this->Contact->validationErrors['notify_host_recovery'])){
				$this->set('validation_host_notification', $this->Contact->validationErrors['notify_host_recovery'][0]);
			}

			if(isset($this->Contact->validationErrors['notify_service_recovery'])){
				$this->set('validation_service_notification', $this->Contact->validationErrors['notify_service_recovery'][0]);
			}

			$this->setFlash(__('Contact could not be saved'), false);
		}
		$this->set(compact(['containers', '_timeperiods', 'timeperiods', 'notification_commands', 'isLdap']));
		$this->set('_serialize', ['containers', '_timeperiods', 'timeperiods', 'notification_commands']);

	}

	public function addFromLdap(){
		if($this->request->is('post') || $this->request->is('put')){
			if($this->Ldap->userExists($this->request->data('Ldap.samaccountname'))){
				$ldapUser = $this->Ldap->findUser($this->request->data('Ldap.samaccountname'));
				$this->redirect([
					'controller' => 'contacts',
					'action' => 'add',
					'ldap' => 1,
					'email' => $ldapUser['mail'],
					'samaccountname' => $ldapUser['samaccountname'],
					//Fixing usernames like jon.doe
					'fix' => 1 // we need an / behind the username parameter otherwise cakePHP will make strange stuff with a jon.doe username (username with dot ".")
				]);
			}
			$this->setFlash(__('Contact does not exists in LDAP'), false);
		}

		$users = [];
		$requiredFilds = ['samaccountname', 'mail', 'sn', 'givenname'];


		$allLdapUsers = $this->Ldap->findAllUser();
		foreach($allLdapUsers as $samAccountName){
			$ldapUser = $this->Ldap->userInfo($samAccountName);
			$ableToImport = true;
			foreach($requiredFilds as $requiredFild){
				if(!isset($ldapUser[$requiredFild])){
					$ableToImport = false;
				}
			}

			if($ableToImport === true){
				$users[] = $ldapUser;
			}
		}

		$usersForSelect = [];
		foreach($users as $user){
			$usersForSelect[$user['samaccountname']] = $user['displayname'] . ' ('.$user['samaccountname'].')';
		}

		$systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
		$this->set(compact(['usersForSelect', 'systemsettings']));
	}

	protected function __allowDelete($contact){
		if(is_numeric($contact)){
			$contactId = $contact;
		}else{
			$contactId = $contact['Contact']['id'];
		}

		$models = [
			'__ContactsToContactgroups',
			'__ContactsToHosttemplates',
			'__ContactsToHosts',
			'__ContactsToServicetemplates',
			'__ContactsToServices',
			'__ContactsToHostescalations',
			'__ContactsToServiceescalations',
		];
		foreach($models as $model){
			$this->loadModel($model);
			$count = $this->{$model}->find('count', [
				'conditions' => [
					'contact_id' => $contactId
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
		$contact = $this->Contact->findById($id);

		if(!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))){
			$this->render403();
			return;
		}

		if(!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))){
			$this->render403();
			return;
		}

		if($this->__allowDelete($id)){
			if($this->Contact->delete($id)){
				$changelog_data = $this->Changelog->parseDataForChangelog(
					$this->params['action'],
					$this->params['controller'],
					$id,
					OBJECT_CONTACT,
					Hash::extract($contact['Container'], '{n}.id'),
					$userId,
					$contact['Contact']['name'],
					$contact
				);
				if($changelog_data){
					CakeLog::write('log', serialize($changelog_data));
				}
				$this->setFlash(__('Contact deleted'));
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Could not delete contact'), false);
				$this->redirect(array('action' => 'index'));
			}
		}else{
			$contactsCanotDelete = [$contact['Contact']['name']];
			$this->set(compact(['contactsCanotDelete']));
			$this->render('mass_delete');
		}
	}

	public function mass_delete($id = null){
		$userId = $this->Auth->user('id');

		if($this->request->is('post') || $this->request->is('put')){
			foreach($this->request->data('Contact.delete') as $contactId){
				$contact = $this->Contact->findById($contactId);
				if($this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))){
					if(empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))){
						if($this->Contact->delete($contact['Contact']['id'])){
							$changelog_data = $this->Changelog->parseDataForChangelog(
								$this->params['action'],
								$this->params['controller'],
								$contact['Contact']['id'],
								OBJECT_CONTACT,
								Hash::extract($contact['Container'], '{n}.id'),
								$userId,
								$contact['Contact']['name'],
								$contact
							);
							if($changelog_data){
								CakeLog::write('log', serialize($changelog_data));
							}
						}
					}
				}
			}
			$this->setFlash(__('Contacts deleted'));
			$this->redirect(['action' => 'index']);
		}
		$contactsToDelete = [];
		$contactsCanotDelete = [];
		foreach(func_get_args() as $contactId){
			if($this->Contact->exists($contactId)){
				$contact = $this->Contact->findById($contactId);
				if($this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))){
					if(empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))){
						if($this->__allowDelete($contactId)){
							$contactsToDelete[] = $contact;
						}else{
							$contactsCanotDelete[] = $contact['Contact']['name'];
						}
					}
				}
			}
		}
		$count = sizeof($contactsToDelete) + sizeof($contactsCanotDelete);
		$this->set(compact(['contactsToDelete', 'contactsCanotDelete', 'count']));
	}

	public function loadTimeperiods(){
		$this->allowOnlyAjaxRequests();

		$timePeriods = [];
		if(isset($this->request->data['container_ids'])){
			$containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['container_ids']);
			$timePeriods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
			$timePeriods = $this->Timeperiod->makeItJavaScriptAble($timePeriods);
		}

		$data = [
			'timeperiods' => $timePeriods,
		];
		$this->set($data);
		$this->set('_serialize', array_keys($data));
	}
}
