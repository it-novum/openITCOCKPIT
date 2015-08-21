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

class DeletedHostsController extends AppController{
	public $layout = 'Admin.default';
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
		'AdditionalLinks',
	];
	public $helpers = ['ListFilter.ListFilter'];
	public $uses = ['DeletedHost', 'Host'];
	
	public $listFilters = [
		'index' => [
			'fields' => [
				'DeletedHost.name' => ['label' => 'Hostname', 'searchType' => 'wildcard'],
				'DeletedHost.uuid' => ['label' => 'UUID', 'searchType' => 'wildcard'],
			],
		],
	];
	
	public function index(){
		$this->Paginator->settings['order'] = [
			'created' => 'DESC'
		];
		$deletedHosts = $this->Paginator->paginate();

		
		$this->set(compact(['deletedHosts']));
		
		if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
			$this->set('isFilter', true);
		}else{
			$this->set('isFilter', false);
		}
	}
}