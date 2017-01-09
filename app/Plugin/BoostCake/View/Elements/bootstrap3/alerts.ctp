<?php
// View
echo $this->Session->flash();

// Controller
$this->Session->setFlash(__('Alert success message testing...'), 'alert', [
    'plugin' => 'BoostCake',
    'class'  => 'alert-success',
]);

$this->Session->setFlash(__('Alert info message testing...'), 'alert', [
    'plugin' => 'BoostCake',
    'class'  => 'alert-info',
]);

$this->Session->setFlash(__('Alert warning message testing...'), 'alert', [
    'plugin' => 'BoostCake',
    'class'  => 'alert-warning',
]);

$this->Session->setFlash(__('Alert danger message testing...'), 'alert', [
    'plugin' => 'BoostCake',
    'class'  => 'alert-danger',
]);
?>