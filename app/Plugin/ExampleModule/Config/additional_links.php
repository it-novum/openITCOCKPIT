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


//This is a simple example, how to code plugins for openITCOCKPIT

$config = [];
/*
$config = [
	'additional_links' => [
		'example_1' =>[
			'positioning' => [
				'controller' => 'hosts',
				'action' => 'index',
				'viewPosition' => 'list', // The position within the controller/action
				'sorting' => 9999, // Sorting value
			],
			'link' => [
				'title' => 'Additional Link 1',
				'url' => [],
			],
		], [
			'positioning' => [
				'controller' => 'hosts',
				'action' => 'index',
				'viewPosition' => 'list', // The position within the controller/action
				'sorting' => 1000, // Sorting value
			],
			'link' => [
				'title' => 'Additional Link 2',
				'url' => [
					'controller' => 'hosts',
					'action' => 'index',
				],
			],
		],
		'example_2' =>[
			'positioning' => [
				'controller' => 'hosts',
				'action' => 'index',
				'viewPosition' => 'list', // The position within the controller/action
				'sorting' => 2000, // Sorting value
			],
			'link' => [
				'title' => 'Additional Link 3 - With Index',
				// Borrowed from MySQL: 'auto_increment' gets 'autoIndex' here
				// 'autoIndex' will be automatically replaced in list context by the right
				// index of the entry
				'url' => [
					'controller' => 'custom_hosts',
					'action' => 'edit',
					'autoIndex',
					50, // fixed value for testing purposes, shouldn't get changed!
				],
			],
		],
		'example_3' => [
			'positioning' => [
				'controller' => 'hosts',
				'action' => 'index',
				'viewPosition' => 'top', // The position within the controller/action
				'sorting' => 1000, // Sorting value
			],
			'link' => [
				'title' => '<i class="fa fa-cog"></i> ' . h(__('Custom Button')),
				'options' => [
					'class' => 'btn btn-xs btn-primary',
					'target' => '_blank',
					'escapeTitle' => false,
				],
				'url' => 'http://www.example.com',
			],
		],
	]
];
*/
// $config = [];

