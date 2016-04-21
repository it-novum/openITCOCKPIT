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

class DashboardTab extends AppModel{
	public $hasMany = [
		'Widget' => [
			'dependent' => true
		]
	];
	
	//public $belongsTo = ['Container'];
	public $validate = [
		'name' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
		],
		'user_id' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'message' => 'This field cannot be left blank.',
				'required' => true
			],
			'notBlank' => [
				'rule' => 'numeric',
				'message' => 'This field need to be numeric.',
				'required' => true
			],
		],
	];
	
	
	//Function name new or create are registerd by php/CakePHP
	public function createNewTab($userId, $options = []){
		$_options = [
			'name' => __('Default'),
			'shared' => 0,
			'source_tab_id' => null,
			'check_for_updates' => 0,
			'position' => $this->getNextPosition($userId),
		];
		$options = Hash::merge($_options, $options);
		
		$this->create();
		$data = [
			'user_id' => $userId,
		];
		
		$data = Hash::merge($options, $data);
		
		return $this->save($data);
	}
	
	public function getNextPosition($userId){
		$result = $this->find('first', [
			'recursive' => -1,
			'contain' => [],
			'conditions' => [
				'user_id' => $userId
			],
			'order' => [
				'position' => 'DESC'
			]
		]);
		if(!empty($result)){
			return (int)$result['DashboardTab']['position'] + 1;
		}
		return 1;
	}
	
}

