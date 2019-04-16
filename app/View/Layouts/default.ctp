<!DOCTYPE html>
<html lang="en">
<head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo $title_for_layout; ?> - <?php echo Configure::read('general.site_name') ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->element('assets');
    ?>
</head>
<body>
<?php echo $this->element('Admin.layout/header') ?>
<div class="container" id="main-container">
    <div class="row">
        <div class="col-md-12 controller <?php echo h($this->name) ?>_<?php echo h($this->action) ?>">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Flash->render('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
    </div>
</div>
<hr>
<footer>
    <div class="navbar login-copyright">
        <ul class="nav navbar-nav">
            <li>
                <a href="http://www.it-novum.com">&copy; it-novum GmbH 2005 - <?php echo date('Y'); ?></a>
            </li>
        </ul>
    </div>
</footer>
<?php echo $this->element('Admin.sql_dump'); ?>
</body>
</html>
