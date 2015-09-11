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

namespace Dashboard\Widget;
class HostStatusList extends Widget{
	public $isDefault = false;
	public $icon = 'fa-list-alt';
	public $element = 'host_status_list';
	public $width = 10;
	public $height = 21;
	public $hasInitialConfig = true;
	
	public $initialConfig = [
		'WidgetHostStatusList' => [
			'show_up' => 0,
			'show_down' => 1,
			'show_unreachable' => 1,
			'show_acknowledged' => 0,
			'show_downtime' => 0
		]
	];
	
	public function __construct(\Controller $controller, $QueryCache){
		parent::__construct($controller, $QueryCache);
		$this->typeId = 9;
		$this->title = __('Hosts status list');
	}
	
	public function setData($widgetData){
		//debug($widgetData);
		//Prefix every widget variable with $widgetFoo
		$widgetHostStateArray180 = $this->QueryCache->hostStateCount180();
		$this->Controller->set(compact(['widgetHostStateArray180']));
	}
	
}
