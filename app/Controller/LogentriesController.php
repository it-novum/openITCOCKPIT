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

class LogentriesController extends AppController{
	
	/*
	 * Attention! In this case we load an external Model from the monitoring plugin! The Controller
	 * use this external model to fetch the required data out of the database
	 */
	public $uses = [MONITORING_LOGENTRY, 'Host', 'Service'];
	
	public $components = array('Paginator', 'ListFilter.ListFilter','RequestHandler', 'Uuid');
	public $helpers = array('ListFilter.ListFilter', 'Status', 'Monitoring', 'CustomValidationErrors', 'Uuid');
	public $layout = 'Admin.default';
	
	public $listFilters = [
		'index' => [
			'fields' => [
				'Logentry.logentry_data' => ['label' => 'Logentry', 'searchType' => 'wildcard']
			],
		]
	];
	
	public function index(){
		//Get Parameters out of $_GET
		if(isset($this->params['named']['Listsettings'])){
			$this->request->data['Listsettings'] = $this->params['named']['Listsettings'];
		}
		$requestSettings = $this->Logentry->listSettings($this->request->data);
		
		// Set URL Parameters to index.ctp
		$ListsettingsUrlParams = [];
		if(!empty($requestSettings['Listsettings'])){
			$ListsettingsUrlParams['Listsettings'] = $requestSettings['Listsettings'];
		}
		
		if(!is_array($this->Paginator->settings)){
			$this->Paginator->settings = [];
		}
		
		if(!isset($this->Paginator->settings['conditions'])){
			$this->Paginator->settings['conditions'] = [];
		}
		
		if(!isset($this->Paginator->settings['limit']) || $this->Paginator->settings['limit'] < 50){
			$this->Paginator->settings['limit'] = 50;
		}

		$this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['paginator']);
		$this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
		
		if(!isset($this->Paginator->settings['order'])){
			$this->Paginator->settings['order'] = ['logentry_time' => 'desc'];
		}
		
		$this->Paginator->settings['fields'] = ['logentry_time', 'logentry_type', 'logentry_data'];
		
		$this->Uuid->buildCache();
		$this->set('uuidCache', $this->Uuid->getCache());
		
		$all_logentries = $this->Paginator->paginate();
		$this->set(compact(['all_logentries', 'checked', 'ListsettingsUrlParams']));
		$this->set('logentry_types', $this->Logentry->types());
		
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}
	
}
