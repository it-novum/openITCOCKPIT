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


class ProxyController extends AppController {
	public $layout = 'Admin.default';
	public $components = array('RequestHandler');
	
	function index(){
		$proxy = $this->Proxy->find('all');
		$this->set('proxy', $proxy);
		//_serialize wird fir das json und XML randering benötigt
		$this->set('_serialize', array('proxy'));
	}
	
	function edit(){
		$proxy = $this->Proxy->find('all');
		$this->set('proxy', $proxy);
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!isset($this->request->data['Proxy']['enabled'])){
				$this->request->data['Proxy']['enabled'] = false;
			}
			//$this->Proxy->save($this->request->data)
			if ($this->Proxy->save($this->request->data)) {
				$this->setFlash('Data saved successfully');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->setFlash(__('Proxy data invalid'), false);
			}
		}
	}
	
	function getSettings(){
		$proxy = $this->Proxy->find('all');
		$settings = array('ipaddress' => '', 'port' => 0, 'enabled' => false);
		if(isset($proxy[0]['Proxy']) && !empty($proxy[0]['proxy'])){
			$settings = array(
				'ipaddress' => $proxy[0]['Proxy']['ipaddress'],
				'port' => $proxy[0]['Proxy']['port'],
				'enabled' => $proxy[0]['Proxy']['enabled']
			);
		}
		return $settings;
	}
	
}