<!DOCTYPE html>
<html lang="en">
<head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?> - <?php echo Configure::read('general.site_name') ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->element('assets');
    ?>
</head>
<body>
<div class="container" id="main-container">
    <div class="row">
        <div class="controller <?php echo $this->name ?>_<?php echo $this->action ?>">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
    </div>
</div>
</body>
</html>
