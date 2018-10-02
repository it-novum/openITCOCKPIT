<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
?>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-area-chart fa-fw "></i>
            <?php echo __('Grafana'); ?>
            <span>>
                <?php echo __('User Dashboards'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-dashboard"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php
            echo __('Dashboard:');
            echo h($dashboard['GrafanaUserdashboard']['name']);
            ?>

        </h2>
        <div class="widget-toolbar">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <iframe src="<?php echo $iframeUrl; ?>" onload="this.height=(screen.height+15);" width="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>
