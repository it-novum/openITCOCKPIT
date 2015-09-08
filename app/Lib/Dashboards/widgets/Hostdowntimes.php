<?php
namespace Dashboard\Widget;
class Hostdowntimes extends Widget{
	public $isDefault = true;
	public $icon = 'fa-power-off';
	
	public function __construct(){
		$this->typeId = 5;
		$this->title = __('Host downtimes');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'row' => 0, // x
			'col' => 24, // y
			'width' => 5,
			'height' => 13,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
