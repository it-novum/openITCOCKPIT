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

class GraphCollection extends AppModel{

	public $hasAndBelongsToMany = [
		'GraphgenTmpl' => [
			'className' => 'GraphgenTmpl',
			'joinTable' => 'graph_tmpl_to_graph_collection',
			'foreignKey' => 'graph_collection_id',
			'associationForeignKey' => 'graphgen_tmpl_id',
		]
	];

	var $validate = [
		'name' => [
			'allowEmpty' => [
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank',
				'required' => true
			]
		]
	];

	/**
	 * Loads an Collection with it's corresponding host and service.
	 * @param $id
	 * @return array
	 */
	public function loadCollection($id){
		$collection = $this->find('first', [
			'conditions' => [
				'GraphCollection.id' => (int) $id,
			],
			'contain' => [
				'GraphgenTmpl' => [
					'GraphgenTmplConf' => [
						'Service' => [
							'fields' => [
								'Service.name',
								'Service.uuid',
								'Service.id',
							],
							'Host' => [
								'fields' => [
									'Host.name',
									'Host.uuid',
									'Host.id',
								]
							],
							'Servicetemplate' => [
								'fields' => [
									'Servicetemplate.name',
								]
							]
						],
					],
				],
			],
		]);

		return $collection;
	}
}
