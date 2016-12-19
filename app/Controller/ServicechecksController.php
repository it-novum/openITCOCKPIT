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

class ServicechecksController extends AppController{
	/*
	 * Attention! In this case we load an external Model from the monitoring plugin! The Controller
	 * use this external model to fetch the required data out of the database
	 */
	public $uses = [MONITORING_SERVICECHECK, MONITORING_SERVICESTATUS, 'Host', 'Service'];


	public $components = ['Paginator', 'ListFilter.ListFilter','RequestHandler'];
	public $helpers = ['ListFilter.ListFilter', 'Status', 'Monitoring'];
	public $layout = 'Admin.default';

	public $listFilters = [
		'index' => [
			'fields' => [
				'Servicecheck.output' => ['label' => 'Output', 'searchType' => 'wildcard'],
			],
		],
	];

	public function index($id = null){
		if(!$this->Service->exists($id)){
			throw new NotFoundException(__('invalid service'));
		}

		$service = $this->Service->find('first', [
			'recursive' => -1,
			'contain' => [
				'Host' =>[
					'Container',
				],
				'Servicetemplate' => [
					'fields' => [
						'Servicetemplate.id',
						'Servicetemplate.name'
					]
				]
			],
			'conditions' => [
				'Service.id' => $id
			],

		]);

		$containerIdsToCheck = Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id');
		$containerIdsToCheck[] = $service['Host']['container_id'];

		//Check if user is permitted to see this object
		if(!$this->allowedByContainerId($containerIdsToCheck, false)){
			$this->render403();
			return;
		}

		$allowEdit = false;
		if($this->allowedByContainerId($containerIdsToCheck)){
			$allowEdit = true;
		}

		$servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], [
			'fields' => [
				'Objects.name2',
				'Servicestatus.current_state'
			]
		]);


		$requestSettings = $this->Servicecheck->listSettings($this->request, $service['Service']['uuid']);

		$this->Paginator->settings['conditions'] = Hash::merge($this->paginate['conditions'], $requestSettings['conditions']);
		$this->Paginator->settings['order'] = $requestSettings['paginator']['order'];
		$this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];

		$all_servicechecks = $this->Paginator->paginate();

		$this->set('ServicecheckListsettings', $requestSettings['Listsettings']);
		$this->set(compact(['service', 'all_servicechecks', 'servicestatus', 'allowEdit']));


		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}
}
