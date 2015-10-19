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


class Widget extends AppModel{
	public $belongsTo = ['DashboardTab', 'Service'];
	public $hasOne = [
		'WidgetTacho' => [
			'dependent' => true
		],
		'WidgetServiceStatusList' => [
			'dependent' => true
		],
		'WidgetHostStatusList' => [
			'dependent' => true
		],
		//'WidgetNotice' => [
		//	'dependent' => true
		//],
		//'WidgetGraphgenerator' => [
		//	'dependent' => true
		//],
	];
	public $validate = [
		'dashboard_tab_id' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
			], 'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs a numeric value.',
			], 'notZero' => [
				'rule' => ['comparison', '>', 0],
				'message' => 'The value should be greate than zero.',
			],
		],
		'row' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs a numeric value.',
			],
		],
		'col' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs a numeric value.',
			],
		],
		'size_x' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs a numeric value.',
			],
		],
		'size_y' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field needs a numeric value.',
			],
		],
	];
	
	public function copySharedWidgets($sourceTab, $targetTab, $userId){
		$sourceWidgets = $this->find('all', [
			'conditions' => [
				'Widget.dashboard_tab_id' => $sourceTab['DashboardTab']['id']
			]
		]);
		
		foreach($sourceWidgets as $sourceWidget){
			if(isset($sourceWidget['Service'])){
				unset($sourceWidget['Service']);
			}
			if(isset($sourceWidget['Host'])){
				unset($sourceWidget['Host']);
			}
			
			$sourceWidget = Hash::remove($sourceWidget, '{s}.id');
			$sourceWidget = Hash::remove($sourceWidget, '{s}.widget_id');
			
			$sourceWidget['DashboardTab'] = [
				'id' => $targetTab['DashboardTab']['id'],
				'name' => $sourceTab['DashboardTab']['name'],
				'user_id' => $userId
			];
			$sourceWidget['Widget']['dashboard_tab_id'] = $targetTab['DashboardTab']['id'];
			//Remove all mnull keys and unused Models
			$sourceWidget = array_filter($sourceWidget, function($valuesAsArray){
				if(is_array($valuesAsArray)){
					foreach($valuesAsArray as $value){
						if($value !== null && $value !== ''){
							return true;
						}
					}
				}else{
					if($valuesAsArray !== null && $valuesAsArray !== ''){
						return true;
					}
				}
				return false;
			});
			if(!$this->saveAll($sourceWidget)){
				debug($this->validationErrors);
				$error = true;
			}else{
				$error = false;
			}
		}
		return $error;
	}
}
