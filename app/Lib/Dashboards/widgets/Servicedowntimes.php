<?php
namespace Dashboard\Widget;
class Servicedowntimes extends Widget{
	public $isDefault = true;
	
	public function __construct(){
		$this->typeId = 6;
		$this->title = __('Service downtimes');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'row' => 5, // x
			'col' => 24, // y
			'width' => 5,
			'height' => 13,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
