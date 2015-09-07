<?php
namespace Dashboard\Widget;
class Service360 extends Widget{
	public $isDefault = true;
	
	public function __construct(){
		$this->typeId = 4;
		$this->title = __('Services Piechart');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'row' => 5, // x
			'col' => 11, // Y
			'width' => 5,
			'height' => 13,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
