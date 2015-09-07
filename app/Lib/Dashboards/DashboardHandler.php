<?php
namespace Dashboard;
use Dashboard\Widget;
class DashboardHandler{
	//List of all exsisting widgets classes
	private $_widgets = [
		'Welcome',
		'Parentoutages',
		'Host360',
		'Service360',
		'Hostdowntimes',
		'Servicedowntimes',
	];
	
	public function __construct(){
		require_once 'widgets' . DS . 'Widget.php';
		foreach($this->_widgets as $_widget){
			require_once 'widgets' . DS . $_widget . '.php';
			$_widget = 'Dashboard\Widget\\'. $_widget;
			$this->{$_widget} = new $_widget();
		}
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
	
}
