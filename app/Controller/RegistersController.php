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

class RegistersController extends AppController{
	public $layout = 'Admin.register';
	public $components = ['Http', 'GearmanClient'];
	public $uses = ['Register', 'Proxy'];

	public function index(){
		if($this->request->is('post')){
			$this->request->data['Register']['id'] = 1;
			if($this->Register->save($this->request->data)){
				//$this->setFlash('License added successfully');
				$this->redirect(array('action' => 'check'));
			}else{
				$this->setFlash('Could not add license', false);
			}
		}

		$license = $this->Register->find('first');

		if(!empty($license)){
			//$this->redirect(array('action' => 'check'));
		}
		$this->set('licence', $license);
	}

	public function check(){
		$license = $this->Register->find('first');
		if(empty($license)){
			$this->setFlash('Please enter a license key', false);
			$this->redirect(array('action' => 'index'));
		}
		if(ENVIRONMENT === Environments::DEVELOPMENT){
			$options = [
				'CURLOPT_SSL_VERIFYPEER' => false,
				'CURLOPT_SSL_VERIFYHOST' => false,
			];
			$http = new HttpComponent('http://172.16.2.87/licences/check/'.$license['Register']['license'].'.json', $options, $this->Proxy->getSettings());
		}else{
			$options = [];
			$http = new HttpComponent('https://packagemanager.it-novum.com/licences/check/'.$license['Register']['license'].'.json', [], $this->Proxy->getSettings());
		}

		$http->sendRequest();
		$error = $http->lastError;
		$response = json_decode($http->data);

		$isValide = false;
		$licence = null;
		if(is_object($response)){
			if(!is_array($response->licence) && property_exists($response, 'licence')){
				if(property_exists($response, 'licence')){
					if(property_exists($response->licence, 'Licence')){
						if(strtotime($response->licence->Licence->expire) > time()){
							$isValide = true;
							$licence = $response->licence->Licence;
							if($license['Register']['apt'] == 0){
								$this->GearmanClient->sendBackground('create_apt_config', ['key' => $license['Register']['license']]);
								$license['Register']['apt'] = 1;
								$this->Register->save($license);
							}
						}
					}
				}
			}
		}
		if($isValide == false){
			//The lincense is invalide, so we delete it again out of the database
			if(isset($license['Register']['id'])){
				$this->Register->delete($license['Register']['id']);
			}
		}

		$this->set(compact('isValide', 'licence', 'error'));
	}
}