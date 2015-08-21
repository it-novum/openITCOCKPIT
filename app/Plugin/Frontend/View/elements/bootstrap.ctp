<?php
$jsControllerFilename = Inflector::underscore($this->name . ucfirst($this->action) . 'Controller') . '.js';
if(file_exists(JS . 'controllers' . DS. $jsControllerFilename)) {
	echo $this->Html->script('controllers/' . $jsControllerFilename);
}
echo $scripts_for_layout;
if(isset($jsonVars)) {
	$codeBlock = 'window.vars = ' . $javascript->object($jsonVars) . ';';
	echo $this->Html->scriptBlock($codeBlock);
}