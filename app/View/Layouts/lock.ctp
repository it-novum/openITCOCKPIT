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
        <div class="controller <?php echo h($this->name); ?>_<?php echo h($this->action); ?>">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Flash->render('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
    </div>
</div>
</body>
</html>
