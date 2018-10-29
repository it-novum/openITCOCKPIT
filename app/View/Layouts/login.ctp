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
    ?>
    <link rel="stylesheet" type="text/css"
          href="/css/vendor/bootstrap/css/bootstrap.min.css?v<?php echo Configure::read('version'); ?>"/>
    <link rel="stylesheet" type="text/css"
          href="/smartadmin/css/font-awesome.min.css?v<?php echo Configure::read('version'); ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/login.css?<?php echo time(); ?>"/>

    <script type="text/javascript"
            src="/frontend/js/lib/jquery.min.js?v<?php echo Configure::read('version'); ?>"></script>
    <script type="text/javascript" src="/js/lib/particles.min.js?v<?php echo Configure::read('version'); ?>"></script>
    <script type="text/javascript" src="/js/login.js?<?php echo time(); ?>"></script>


</head>
<body class="main">

<?php echo $content_for_layout; ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php echo $this->element('Admin.sql_dump'); ?>
        </div>
    </div>
</div>
</body>
</html>
