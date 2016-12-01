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


/* Notice:
 * A acontactgroup is not a stand alone object, its still a container
 * a contact belongsTo a container (many to one)
 */

class Contactgroup extends AppModel{
	//public $primaryKey = 'container_id';
//	public $primaryKeyArray = array('id','container_id');

	public $belongsTo = array(
		'Container' => [
			'className' => 'Container',
			'conditions' => ['Contactgroup.container_id = Container.id'],
			'dependent' => true,
			'foreignKey' => 'container_id',
			'associatedKey' => 'id'
		]);

	public $hasAndBelongsToMany = array(
		'Contact' => [
			'joinTable' => 'contacts_to_contactgroups',
			'foreignKey' => 'contactgroup_id',
			'unique' => true,
		]
	);

	public $recursive = 1;

	public $validate = [
		'Contact' => [
			'rule' => ['multiple', [
				'min' => 1,
			]],
			'message' => 'Please select at least 1 contact',
			'required' => true
			]
		];

	public function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);
		$this->Contact = ClassRegistry::init('Contact');
		//$this->primaryKey = 'id';
	}


	public function saveContactgroup($data = []){
		debug($data);
		if($this->saveAll($data)){
			$contactgroup = $this->findById($this->id);
			if(isset($data['Contact']) && !empty($data['Contact'])){
				foreach($data['Contact'] as $contact_id){
					$contact = $this->Contact->findById($contact_id);
					$contact['Container']['ContactsToContainer']['contact_id'] = $contact_id;
					debug($contactgroup['Contactgroup']['container_id']);
					$contact['Container']['ContactsToContainer']['container_id'] = $contactgroup['Contactgroup']['container_id'];
					if($this->Contact->saveAll($contact, ['validate' => false])){
						// $this->setFlash(__('contact group successfully saved'));
						//$this->redirect(['action' => 'index']);
						echo "jup";
					}else{
						//$this->setFlash(__('could not save data'), false);
						echo "not";
					}
				}
			}
		}
	}

	public function contactgroupsByContainerId($container_ids = [], $type = 'all', $index = 'id'){
		if(!is_array($container_ids)){
			$container_ids = [$container_ids];
		}

		//Lookup for the tenant container of $container_id
		$this->Container = ClassRegistry::init('Container');

		$tenantContainerIds = [];

		foreach($container_ids as $container_id){
			if($container_id != ROOT_CONTAINER){

				// Get container id of the tenant container
				// $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
				$path = Cache::remember('ContactGroupContactsByContainerId:'. $container_id, function() use ($container_id) {
					return $this->Container->getPath($container_id);
				}, 'migration');
				$tenantContainerIds[] = $path[1]['Container']['id'];
			}else{
				$tenantContainerIds[] = ROOT_CONTAINER;
			}
		}
		$tenantContainerIds = array_unique($tenantContainerIds);

		switch($type){
			case 'all':
				return $this->find('all', [
					'conditions' => [
						'Container.parent_id' => $tenantContainerIds,
						'Container.containertype_id' => CT_CONTACTGROUP
					],
					'recursive' => 1,
					'order' => [
						'Container.name' => 'ASC'
					]
				]);

			default:
				$return = [];
				$results = $this->find('all', [
					'conditions' => [
						'Container.parent_id' => $tenantContainerIds,
						'Container.containertype_id' => CT_CONTACTGROUP
					],
					'recursive' => 1,
					'order' => [
						'Container.name' => 'ASC'
					]
				]);
				foreach($results as $result){
					$return[$result['Contactgroup'][$index]] = $result['Container']['name'];
				}
				return $return;
		}
		return [];
	}

	public function findList(){
		$return = [];
		$results = $this->find('all', [
			'conditions' => [
				'Container.containertype_id' => CT_CONTACTGROUP
			],
			'recursive' => 1
		]);
		foreach($results as $result){
			$return[$result['Contactgroup']['id']] = $result['Container']['name'];
		}
		return $return;
	}

}
