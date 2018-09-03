<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

debug($this->Acl->hasPermission('add'));
?>
<div class="alert auto-hide alert-success" style="display:none;"
     id="flashMessage"></div>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-gears fa-fw "></i>
            <?php echo __('Grafana'); ?>
            <span>>
                <?php echo __('User Dashboards'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('User Dashboard List'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <?php //if ($this->Acl->hasPermission('add')): ?>
            <a href="/grafana_module/grafana_userdashboards/add" class="btn btn-xs btn-success">
                <i class="fa fa-plus"></i>
                <?php echo __('New'); ?>
            </a>
            <?php //endif; ?>
        </div>
        <div class="jarviswidget-ctrls" role="menu">
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">


            </div>
        </div>
    </div>
</div>