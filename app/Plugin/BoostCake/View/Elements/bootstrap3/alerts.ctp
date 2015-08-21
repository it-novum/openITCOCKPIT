<?php
// View
echo $this->Session->flash();

// Controller
$this->Session->setFlash(__('Alert success message testing...'), 'alert', array(
	'plugin' => 'BoostCake',
	'class' => 'alert-success'
));

$this->Session->setFlash(__('Alert info message testing...'), 'alert', array(
	'plugin' => 'BoostCake',
	'class' => 'alert-info'
));

$this->Session->setFlash(__('Alert warning message testing...'), 'alert', array(
	'plugin' => 'BoostCake',
	'class' => 'alert-warning'
));

$this->Session->setFlash(__('Alert danger message testing...'), 'alert', array(
	'plugin' => 'BoostCake',
	'class' => 'alert-danger'
));
?>