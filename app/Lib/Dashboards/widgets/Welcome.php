<?php
namespace Dashboard\Widget;
class Welcome extends Widget{
	public $isDefault = true;
	
	public function __construct(){
		$this->typeId = 1;
		$this->title = __('Welcome');
	}
	
	public function getRestoreConfig($tabId){
		$restorConfig = [
			'dashboard_tab_id' => $tabId,
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
