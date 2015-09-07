<?php
namespace Dashboard\Widget;
class Welcome extends Widget{
	//Restore configuration of every widget
	public $restorConfig = [
		'foo' => 'bar'
	];
	
	public function __construct(){
		$this->typeId = 1;
		$this->title = __('Welcome');
	}
	
}
