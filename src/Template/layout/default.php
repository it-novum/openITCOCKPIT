<?php
/**
 * Only ship the content of the current page to not load AngularJS in AngularJS
 *
 * @var \App\View\AppView $this
 */
?>
<?= $this->fetch('content') ?>

