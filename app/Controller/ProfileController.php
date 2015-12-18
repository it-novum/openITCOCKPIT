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

class ProfileController extends AppController {
	public $layout = 'Admin.default';
	public $uses = ['User', 'Systemsetting'];
	
	public $components = ['Upload'];

	/*public function change_password() {
		if($this->request->is('post')) {
			if($this->User->changePassword($this->Auth->user('id'), $this->request->data)) {
				return $this->flashBack('Your password was successfully updated.', '/admin/dashboard', true);
			}else{
				$this->setFlash(__('Please check your input'), false);
			}
		}
	}*/
	
	public function edit(){
		$user = $this->User->find('first', [
			'conditions' => [
				'User.id' => $this->Auth->user('id'),
			],
			'contain' => false
		]);

		$dateformats = [
			1  => '%B %e, %Y %H:%M:%S',
			2  => '%m-%d-%Y  %H:%M:%S',
			3  => '%m-%d-%Y  %H:%M',
			4  => '%m-%d-%Y  %l:%M:%S %p',
			5  => '%H:%M:%S  %m-%d-%Y',
			
			6  => '%e %B %Y, %H:%M:%S',
			7  => '%d.%m.%Y - %H:%M:%S',
			9  => '%d.%m.%Y - %l:%M:%S %p',
			10 => '%H:%M:%S - %d.%m.%Y', //Default date format
			11 => '%H:%M - %d.%m.%Y',
		];
		
		$selectedUserTime = 10;
		
		foreach($dateformats as $key => $dateformat){
			if($dateformat == $user['User']['dateformat']){
					$selectedUserTime = $key;
			}
		}

		if($this->request->is('post') || $this->request->is('put')){
			/***** Change user data *****/
			if(isset($this->request->data['User'])){
				//Convert dateformat ID into real dateformat for MySQL database
				$this->request->data['User']['dateformat'] = $dateformats[$this->request->data['User']['dateformat']];
				
				//Fix container for validation
				$user = $this->User->findById($this->Auth->user('id'));
				$this->request->data['ContainerUserMembership'] = $user['ContainerUserMembership'];
				$this->request->data['User']['id'] = $user['User']['id'];
				$this->request->data['User']['Container'] = Hash::extract($user['ContainerUserMembership'], '{n}.id');
				$this->request->data['User']['usergroup_id'] = $user['User']['usergroup_id'];
				if($this->User->save($this->request->data)){
					$this->setFlash(__('Profile edit successfully'));
					$sessionUser = $this->Session->read('Auth');
					
					$merged = Hash::merge($sessionUser, $this->request->data);
					$this->Session->write('Auth', $merged);
					return $this->redirect(['action' => 'edit']);
				}
				$this->setFlash(__('Could not save data'), false);
				return $this->redirect(['action' => 'edit']);
			}
			
			/***** Change users profile image *****/
			if(isset($this->request->data['Picture']) && !empty($this->request->data['Picture'])){
				if(!file_exists(WWW_ROOT.'userimages')){
					mkdir(WWW_ROOT.'userimages');
				}
				$this->Upload->setPath(WWW_ROOT.'userimages'.DS);
				if(isset($this->request->data['Picture']['Image']) && isset($this->request->data['Picture']['Image']['tmp_name']) && isset($this->request->data['Picture']['Image']['name'])){
					$filename = $this->Upload->uploadUserimage($this->request->data['Picture']['Image']);
					if($filename){
						$user = $this->User->findById($this->Auth->user('id'));
						$this->User->id = $this->Auth->user('id');
						if($this->User->saveField('image', $filename)){
							$this->Session->write('Auth.User.image', $filename);
							
							//Delete old image
							if(file_exists(WWW_ROOT.'userimages'.DS.$user['User']['image']) && !is_dir(WWW_ROOT.'userimages'.DS.$user['User']['image'])){
								unlink(WWW_ROOT.'userimages'.DS.$user['User']['image']);
							}
							$this->setFlash(__('Image uploaded successfully'));
							return $this->redirect(['action' => 'edit']);
						}
					}
					$this->setFlash(__('Could not save image data, may be wrong data type. Allowd types are .png, .jpg and .gif'), false);
					return $this->redirect(['action' => 'edit']);
				}
			}
			
			/***** Change users password *****/
			if(isset($this->request->data['Password'])){
				if(Security::hash($this->request->data['Password']['current_password'], null, true) != $user['User']['password']){
					$this->setFlash(__('The entered password is not your current password'), false);
					return $this->redirect(['action' => 'edit']);
				}
				
				if(isset($this->request->data['Password']['new_password']) && isset($this->request->data['Password']['new_password_repeat'])){
					if($this->request->data['Password']['new_password'] == $this->request->data['Password']['new_password_repeat']){
						$user = $this->User->findById($this->Auth->user('id'));
						$this->User->id = $this->Auth->user('id');
						if($this->User->saveField('password', AuthComponent::password($this->request->data['Password']['new_password']))){
							$this->setFlash(__('Password changed successfully'));
							$this->redirect(['action' => 'edit']);
						}
						$this->setFlash(__('Error while saving data'), false);
						$this->redirect(['action' => 'edit']);
					}else{
						$this->setFlash(__('The entered passwords are not the same'), false);
						return $this->redirect(['action' => 'edit']);
					}
				}else{
					$this->setFlash(__('Plase enter and confirm your new password'), false);
					return $this->redirect(['action' => 'edit']);
				}
			}
			
			
		}
		$systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
		$user = $this->User->findById($this->Auth->user('id'));
		$this->set(compact('user', 'systemsettings', 'dateformats', 'selectedUserTime'));
	}
	
	public function deleteImage(){
		$user = $this->User->findById($this->Auth->user('id'));
		
		if($user['User']['image'] != null && $user['User']['image'] != ''){
			if(file_exists(WWW_ROOT.'userimages'.DS.$user['User']['image'])){
				unlink(WWW_ROOT.'userimages'.DS.$user['User']['image']);
			}
		}
		$this->redirect(['action' => 'edit']);
	}
}