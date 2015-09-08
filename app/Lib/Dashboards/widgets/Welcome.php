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
class Welcome extends Widget{
	public $isDefault = true;
	public $icon = 'fa-comment';
	public $element = 'Welcome';
	
	public function __construct(\Controller $controller, $QueryCache){
		parent::__construct($controller, $QueryCache);
		$this->typeId = 1;
		$this->title = __('Welcome');
	}
	
	public function setData(){
		//Prefix every widget variable with $widgetFoo
		$widgetHostStateArray = $this->QueryCache->hostStateCount();
		$widgetServiceStateArray = $this->QueryCache->serviceStateCount();
		$this->Controller->set(compact(['widgetHostStateArray', 'widgetServiceStateArray']));
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'type_id' => $this->typeId,
			'row' => 0,
			'col' => 0,
			'width' => 5,
			'height' => 11,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
