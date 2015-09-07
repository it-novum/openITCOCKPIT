<?php
namespace Dashboard;
use Dashboard\Widget;
class DashboardHandler{
	//List of all exsisting widgets classes
	private $_widgets = ['Welcome'];
	
	public function __construct(){
		require_once 'widgets' . DS . 'Widget.php';
		foreach($this->_widgets as $_widget){
			require_once 'widgets' . DS . $_widget . '.php';
			$_widget = 'Dashboard\Widget\\'. $_widget;
			//$foo = new Widget\Welcome();
			$foo = new $_widget();
			debug($foo);
		}
	}
	
}
