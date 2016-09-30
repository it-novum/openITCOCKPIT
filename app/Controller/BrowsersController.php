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

//App::import('Model', 'Host');
//App::import('Model', 'Container');
class BrowsersController extends AppController{

	public $layout = 'Admin.default';
	public $helpers = [
		'PieChart',
		'BrowserMisc',
		'Status',
		'Monitoring',
	];
	public $uses = [
		MONITORING_HOSTSTATUS,
		MONITORING_SERVICESTATUS,
		'Host',
		'Service',
		'Container',
		'Tenant',
		'Browser',
	];

	public $components = [
		'paginator'
	];

	function index(){
		$top_node = $this->Container->findById(ROOT_CONTAINER);
		$parents = $this->Container->getPath($top_node['Container']['parent_id']);
		$browser = Hash::extract($this->Container->children($top_node['Container']['id'], true), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_NODE.')$/]');

		$tenants = $this->__getTenants();
		$query = $this->Browser->hostsQuery(ROOT_CONTAINER);

		$this->Paginator->settings = array_merge($this->Paginator->settings, $query);
		$hosts = $this->Paginator->paginate('Host');
//		$hosts = $this->Host->find('all', $query);
		$query = $this->Browser->serviceQuery(ROOT_CONTAINER);
		$services = $this->Service->find('all', $query);
		$state_array_host = $this->Browser->countHoststate($hosts);
		$state_array_service = $this->Browser->countServicestate($services);
		$this->set(compact([
			'browser',
			'parents',
			'top_node',
			'state_array_host',
			'state_array_service',
			'tenants',
			'hosts',
		]));
	}


	function tenantBrowser($id){
		if(!$this->Container->exists($id)){
			throw new NotFoundException(__('Invalid container'));
		}
		$MY_RIGHTS_WITH_TENANT = array_merge($this->MY_RIGHTS, array_keys($this->__getTenants()));
		if(!$this->hasRootPrivileges){
			if(!in_array($id, $MY_RIGHTS_WITH_TENANT)){
				$this->render403();
				return;
			}
		}

		if($this->hasRootPrivileges === true){
			$browser = Hash::extract($this->Container->children($id, true), '{n}.Container[containertype_id=/^('.CT_GLOBAL.'|'.CT_TENANT.'|'.CT_LOCATION.'|'.CT_NODE.')$/]');
		}else{
			$containerNest = Hash::nest($this->Container->children($id));
			$browser = $this->Browser->getFirstContainers($containerNest, $this->MY_RIGHTS, [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]);

		}
		if($this->hasRootPrivileges === false){
			foreach($browser as $key => $containerRecord){
				if(!in_array($containerRecord['id'], $this->MY_RIGHTS)){
					unset($browser[$key]);
				}
			}
		}

		$hosts = $services = [];
		if(in_array($id, $this->MY_RIGHTS)){
			$query = $this->Browser->hostsQuery($id);
			$this->Paginator->settings = array_merge($this->Paginator->settings, $query);
			$hosts = $this->Paginator->paginate('Host');
			//$hosts = $this->Host->find('all', $query);
			$query = $this->Browser->serviceQuery($id);
			$services = $this->Service->find('all', $query);
		}
		$state_array_host = $this->Browser->countHoststate($hosts);
		$state_array_service = $this->Browser->countServicestate($services);
		$currentContainer = $this->Container->findById($id);
		$parents = $this->Container->getPath($currentContainer['Container']['parent_id']);
		$this->set(compact([
			'currentContainer',
			'browser',
			'parents',
			'state_array_host',
			'state_array_service',
			'hosts',
			'MY_RIGHTS_WITH_TENANT'
		]));
	}

	protected function __getTenants(){
		return $this->Tenant->tenantsByContainerId(
			array_merge(
				$this->MY_RIGHTS, array_keys(
					$this->User->getTenantIds(
						$this->Auth->user('id')
					)
				)
			),
		'list', 'container_id');
	}
}
