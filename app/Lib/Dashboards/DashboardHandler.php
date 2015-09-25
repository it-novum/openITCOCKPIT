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

namespace Dashboard;
use Dashboard\Widget;
class DashboardHandler{
	//List of all exsisting widgets classes
	private $_widgets = [
		'Welcome',
		'Parentoutages',
		'Host360',
		'Host180',
		'Service360',
		'Service180',
		'Hostdowntimes',
		'Servicedowntimes',
		'HostStatusList',
		'ServiceStatusList',
		'Trafficlight',
		'Tachometer',
	];
	
	protected $__widgetClasses = [];
	
	public function __construct(\Controller $controller){
		$this->Controller = $controller;
		require_once 'widgets' . DS . 'Widget.php';
		
		require_once 'widgets' . DS . 'QueryCache.php';
		$QueryCache = new Widget\QueryCache($this->Controller);
		
		foreach($this->_widgets as $_widget){
			require_once 'widgets' . DS . $_widget . '.php';
			$_widget = 'Dashboard\Widget\\'. $_widget;
			$this->{$_widget} = new $_widget($this->Controller, $QueryCache);
			$this->__widgetClasses[$this->{$_widget}->typeId] = $_widget;
		}
	}
	
	public function getAllWidgets(){
		$widgets = [];
		foreach($this->_widgets as $widgetClassName){
			$widgetClassName = 'Dashboard\Widget\\' . $widgetClassName;
			$widgets[] = [
				'typeId' => $this->{$widgetClassName}->typeId,
				'title' => $this->{$widgetClassName}->title,
				'icon' => $this->{$widgetClassName}->icon,
				'element' => 'Dashboard'.DS.$this->{$widgetClassName}->element,
			];
		}
		return $widgets;
	}
	
	public function getWidgetByTypeId($typeId, $tabId = null){
		$result = [
			'Widget' => [
				'dashboard_tab_id' => $tabId,
				'typeId' => $this->{$this->__widgetClasses[$typeId]}->typeId, //backward compatible
				'type_id' => $this->{$this->__widgetClasses[$typeId]}->typeId,
				'title' => $this->{$this->__widgetClasses[$typeId]}->title,
				'icon' => $this->{$this->__widgetClasses[$typeId]}->icon,
				'element' => 'Dashboard'.DS.$this->{$this->__widgetClasses[$typeId]}->element,
				'row' => $this->{$this->__widgetClasses[$typeId]}->row,
				'col' => $this->{$this->__widgetClasses[$typeId]}->col,
				'width' => $this->{$this->__widgetClasses[$typeId]}->width,
				'height' => $this->{$this->__widgetClasses[$typeId]}->height,
				'color' => $this->{$this->__widgetClasses[$typeId]}->defaultColor,
			]
		];
		
		if($this->{$this->__widgetClasses[$typeId]}->hasInitialConfig === true){
			$result = \Hash::merge($result, $this->{$this->__widgetClasses[$typeId]}->initialConfig);
		}
		
		return $result;
	}
	
	public function getDefaultDashboards($tabId){
		$data = [];
		foreach($this->_widgets as $widgetClassName){
			$widgetClassName = 'Dashboard\Widget\\' . $widgetClassName;
			if($this->{$widgetClassName}->isDefault === true){
				$data[] = $this->{$widgetClassName}->getRestoreConfig($tabId);
			}
		}
		return $data;
	}
	
	public function prepareForRender($tab){
		$widgetData = [];
		if(isset($tab['Widget'])){
			foreach($tab['Widget'] as $widget){
				$currentWidgetData = [
					'Widget' => $widget,
					'Settings' => [
						'element' => 'Dashboard'.DS.$this->{$this->__widgetClasses[$widget['type_id']]}->element,
						'icon' => $this->{$this->__widgetClasses[$widget['type_id']]}->icon,
					]
				];
				$widgetData[] = $currentWidgetData;
				//Set data for view
				$this->{$this->__widgetClasses[$widget['type_id']]}->setData($currentWidgetData);
			}
		}
		return $widgetData;
	}
	
	public function refresh($widget){
		if(isset($widget['Widget']['type_id'])){
			$result = $this->{$this->__widgetClasses[$widget['Widget']['type_id']]}->refresh($widget);
			return $result;
		}
	}
}
