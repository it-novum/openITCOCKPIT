<?php
namespace Dashboard\Widget;
class Parentoutages extends Widget{
	public $isDefault = true;
	public $icon = 'fa-exchange';
	
	public function __construct(){
		$this->typeId = 2;
		$this->title = __('Parentoutages');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
			'row' => 5, // x
			'col' => 0, // y
			'width' => 5,
			'height' => 11,
			'title' => $this->title,
			'color' => $this->defaultColor,
		];
		return $restorConfig;
	}
	
}
