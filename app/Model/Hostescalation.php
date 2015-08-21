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

class Hostescalation extends AppModel {

	//var $recursive = 2;

	var $hasAndBelongsToMany = [
		'Contactgroup' => [
			'className' => 'Contactgroup',
			'joinTable' => 'contactgroups_to_hostescalations',
			'foreignKey' => 'hostescalation_id',
			'associationForeignKey' => 'contactgroup_id',
			'unique' => true,
			'dependent' => true,
		],
		'Contact' => [
			'className' => 'Contact',
			'joinTable' => 'contacts_to_hostescalations',
			'foreignKey' => 'hostescalation_id',
			'associationForeignKey' => 'contact_id',
			'unique' => true,
			'dependent' => true,
		]
	];
	public $belongsTo = array(
		'Timeperiod' => [
			'dependent' => true,
			'foreignKey' => 'timeperiod_id',
			'className' => 'Timeperiod',
		],
		'Container' => [
			'foreignKey' => 'container_id',
			'className' => 'Container',
		]
	);

	var $hasMany = [
		'HostescalationHostMembership' => [
			'className' => 'HostescalationHostMembership',
			'dependent' => true,
		],
		'HostescalationHostgroupMembership' => [
			'className' => 'HostescalationHostgroupMembership',
			'dependent' => true,
		]
	];

	var $validate = [
		'Host' => [
			'multiple' => [
				'rule' => ['multiple', ['min' => 1]],
				'message' => 'Please select at least 1 host',
				'required' => true,
			]
		],
		'first_notification' => array(
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This value needs to be numeric',
				'required' => true,
				'allowEmpty' => false,
			],
			'firstNotificationBeforeLastNotification' => [
				'rule' => ['firstNotificationBeforeLastNotification', 'last_notification'],
				'message' => 'The first notification must be before the last notification.',
			],
			'notNegative' => [
				'rule' => ['comparison', '>=', 0],
				'message' => 'This value needs to be greate then 0',
			]
		),
		'last_notification' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This value needs to be numeric',
				'required' => true,
				'allowEmpty' => false,
			],
			'notNegative' => [
				'rule' => ['comparison', '>=', 0],
				'message' => 'This value needs to be greate then 0',
			]
		],
		'notification_interval' => [
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This value needs to be numeric',
				'required' => true,
				'allowEmpty' => false,
			],
			'notNegative' => [
				'rule' => ['comparison', '>=', 0],
				'message' => 'This value needs to be greate then 0',
			]
		],
		'timeperiod_id' => [
			'notEmpty' => [
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'required' => true,
			]
		],

		'Contact' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one contact or contact group.',
				'required' => true
			]
		],
		'Contactgroup' => [
			'atLeastOne' => [
				'rule' => ['atLeastOne'],
				'message' => 'You must specify at least one contact or contact group',
				'required' => true,
			]
		],
		'container_id' => array(
			'multiple' => array(
				'rule' => array('multiple', array('min' => 1)),
				'message' => 'Please select at least 1 container you attend',
			),
		),
	];

	/*
	Custom validation rule for contact and/or contactgroup fields
	*/
	public function atLeastOne($data) {
		return !empty($this->data[$this->name]['Contact']) || !empty($this->data[$this->name]['Contactgroup']);
	}

	/*
	Custom validation rule first_notification
	*/
	public function firstNotificationBeforeLastNotification($field = array(), $compare_field = null){
		foreach($field as $key => $value){
			$v1 = $value;
			$v2 = $this->data[$this->name][$compare_field];
//debug($v1.' - '.$v2.' --> '.(int)($v2 !== 0).' compare_field '.$compare_field);
			if(($v1 > $v2) && $v2 != 0){
				return false;
			}else{
				continue;
			}
		}
		return true;
	}

	/*
	* Parse hosts array for hostescalation
	* @param Array Host-Ids
	* @param Array Host-Ids exluded
	* @return filtered array in format ['host_id' => 1..n, 'exluded' => 0/1]
	*/
	public function parseHostMembershipData($hosts = [], $hosts_exluded = []){
		$host_memberships_for_hostescalation = [];
		foreach($hosts as $host_id){
			$host_memberships_for_hostescalation[] = ['host_id' => $host_id, 'excluded' => '0'];
		}
		foreach($hosts_exluded as $host_id){
			$host_memberships_for_hostescalation[] = ['host_id' => $host_id, 'excluded' => '1'];
		}
		return $host_memberships_for_hostescalation;
	}

	/*
	* Parse hostgroups array for hostescalation
	* @param Array Hostgroup-Ids
	* @param Array Hostgroup-Ids exluded
	* @return filtered array in format ['hostgroup_id' => 1..n, 'exluded' => 0/1]
	*/
	public function parseHostgroupMembershipData($hostgroups = [], $hostgroups_exluded = []){
		$hostgroup_memberships_for_hostescalation = [];
		foreach($hostgroups as $hostgroup_id){
			$hostgroup_memberships_for_hostescalation[] = ['hostgroup_id' => $hostgroup_id, 'excluded' => '0'];
		}
		foreach($hostgroups_exluded as $hostgroup_id){
			$hostgroup_memberships_for_hostescalation[] = ['hostgroup_id' => $hostgroup_id, 'excluded' => '1'];
		}
		return $hostgroup_memberships_for_hostescalation;
	}
}
