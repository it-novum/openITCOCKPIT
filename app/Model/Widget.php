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
		'WidgetBrowser' => [
			'dependent' => true
		],
		'WidgetNotice' => [
			'dependent' => true
		],
		'WidgetGraphgenerator' => [
			'dependent' => true
		],
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
	
	public function restoreDefault($tabId){
		$this->create();
		$data = [
			[
				'dashboard_tab_id' => $tabId,
				'row' => 0,
				'col' => 0,
				'width' => 5,
				'height' => 11,
				'title' => __('Welcome'),
				'color' => 'jarviswidget-color-blueDark',
			],
			[
				'dashboard_tab_id' => $tabId,
				'row' => 5,
				'col' => 0,
				'width' => 5,
				'height' => 11,
				'title' => __('Parentoutages'),
				'color' => 'jarviswidget-color-blueDark',
			],
			[
				'dashboard_tab_id' => $tabId,
				'row' => 0,
				'col' => 11,
				'width' => 5,
				'height' => 13,
				'title' => __('Hosts Piechart'),
				'color' => 'jarviswidget-color-blueDark',
			],
			[
				'dashboard_tab_id' => $tabId,
				'row' => 5,
				'col' => 11,
				'width' => 0,
				'height' => 24,
				'title' => __('Services Piechart'),
				'color' => 'jarviswidget-color-blueDark',
			],
			[
				'dashboard_tab_id' => $tabId,
				'row' => 0,
				'col' => 24,
				'width' => 5,
				'height' => 13,
				'title' => __('Host downtimes'),
				'color' => 'jarviswidget-color-blueDark',
			],
			[
				'dashboard_tab_id' => $tabId,
				'row' => 5,
				'col' => 24,
				'width' => 5,
				'height' => 13,
				'title' => __('Services downtimes'),
				'color' => 'jarviswidget-color-blueDark',
			]
		];
		return $this->saveAll($data);
	}
}
