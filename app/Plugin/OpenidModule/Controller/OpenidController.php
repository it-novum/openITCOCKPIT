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

class OpenidController extends OpenidModuleAppController {
	public $layout = 'Admin.default';

	public function index() {
		$allOpenIds = $this->Openid->find('all');
		foreach($allOpenIds as $key => $allOpenId){
			$allOpenIds[$key]['Openid']['return_url'] = $this->Openid->getReturnUrl($allOpenId['Openid']['id']);
		}
		$this->set('allOpenIds', $allOpenIds);
	}

	public function add() {
		if($this->request->is('post') || $this->request->is('put')){
			if($this->Openid->saveAll($this->request->data)){
				$this->setFlash(__('OpenID Connect successfully saved'));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('could not save data'), false);
			}
		}
		$this->set('returnUrl', __('Return Url will be generated after saving the OpenID Connect'));

	}

	public function edit($id = null) {
		if(!$this->Openid->exists($id)) {
			throw new NotFoundException(__('Invalid OpenID Connect'));
		}

		$openID = $this->Openid->findById($id);

		if($this->request->is('post') || $this->request->is('put')){
			if($this->Openid->saveAll($this->request->data)){
				$this->setFlash(__('OpenID Connect successfully saved'));
				$this->redirect(['action' => 'index']);
			}else{
				$this->setFlash(__('could not save data'), false);
			}
		}
		$this->set('openID', $openID);
		$this->set('returnUrl', $this->Openid->getReturnUrl($id));
	}

	public function delete($id = null){
		if(!$this->Openid->exists($id)) {
			throw new NotFoundException(__('Invalid Map'));
		}

		if(!$this->request->is('post')){
			throw new MethodNotAllowedException();
		}

		if($this->Openid->delete($id, true)){
			$this->setFlash(__('OpenID Connect deleted'));
			$this->redirect(['action' => 'index']);
		}

		$this->setFlash(__('Could not delete OpenID Connect'), false);
		$this->redirect(['action' => 'index']);
	}

	public function checkAndLogin(){
		$id = isset($this->request->params['named']['id']) ? $this->request->params['named']['id'] : null;
		$this->autoRender = false;
		try {
			$userEmail = $this->Openid->getOpenIDEmail($id);
			if(is_null($userEmail['email'])) {
				throw new Exception($userEmail['message']);
			}
			$user = $this->User->find('first', ['conditions' => ['email' => $userEmail['email']]]);
			if(empty($user)){
				throw new Exception(__('User does not exist.'));
			}
			if(!$this->Auth->login($user)) {
				throw new Exception(__('User exists but cannot log in.'));
			}

			$this->Session->delete('Message.auth');
			$this->setFlash(__('login.automatically_logged_in'));
			$this->redirect($this->Auth->loginRedirect);
		}catch (Exception $exp){
			echo $exp->getMessage();
		}
	}

}