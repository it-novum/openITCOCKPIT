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

class ExportsController extends AppController{
	public $layout = 'Admin.default';
	
	public $components = [
		'Paginator',
		'ListFilter.ListFilter',
		'RequestHandler',
		'AdditionalLinks',
	];
	public $helpers = [
		'ListFilter.ListFilter',
	];
	
	public function index(){
		App::uses('UUID', 'Lib');
		
		$this->loadModel('Systemsetting');
		$key = $this->Systemsetting->findByKey('SUDO_SERVER.API_KEY');
		$this->Frontend->setJson('akey', $key['Systemsetting']['value']);
		$this->Frontend->setJson('websocket_url', 'wss://'.env('HTTP_HOST').'/sudo_server');
		$this->Frontend->setJson('uuidRegEx', UUID::JSregex());
	}
	
}