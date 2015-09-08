<?php
namespace Dashboard\Widget;
class Host360 extends Widget{
	public $isDefault = true;
	public $icon = 'fa-pie-chart';
	
	public function __construct(){
		$this->typeId = 3;
		$this->title = __('Hosts Piechart');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'row' => 0, // x
			'col' => 11, // y
			'width' => 5,
			'height' => 13,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
