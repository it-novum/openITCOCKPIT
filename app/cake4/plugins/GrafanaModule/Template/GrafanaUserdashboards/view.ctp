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
            ?>
            {{ dashboards.GrafanaUserdashboard.name }}
        </h2>
        <div class="widget-toolbar">
            <?php if ($this->Acl->hasPermission('editor', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                <a ui-sref="GrafanaUserdashboardsEditor({id: dashboard.GrafanaUserdashboard.id})"
                   class="btn btn-default btn-xs" ng-if="allowEdit">
                    <i class="fa fa-cog"></i>
                    <?php echo __('Open in Editor'); ?>
                </a>
            <?php endif; ?>
            <a ui-sref="GrafanaUserdashboardsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>

        <div class="widget-toolbar">
            <grafana-timepicker callback="grafanaTimepickerCallback"></grafana-timepicker>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <iframe-directive url="iframeUrl" ng-if="dashboardFoundInGrafana"></iframe-directive>
            <div ng-if="!dashboardFoundInGrafana" class="jumbotron text-center bg-color-white">
                <div id="notFoundSvg">
                    <svg class="scaling-svg">
                        <symbol id="not-found-text">
                            <text text-anchor="middle"
                                  x="50%"
                                  y="80%"
                                  class="textline"
                                  fill="none" stroke="#a94442">
                                <?php echo __('404 Ooops...'); ?>
                            </text>
                        </symbol>
                        <g class="g-ants">
                            <use xlink:href="#not-found-text"
                                 class="text-add"></use>
                            <use xlink:href="#not-found-text"
                                 class="text-add"></use>
                            <use xlink:href="#not-found-text"
                                 class="text-add"></use>
                            <use xlink:href="#not-found-text"
                                 class="text-add"></use>
                            <use xlink:href="#not-found-text"
                                 class="text-add"></use>
                        </g>
                    </svg>
                </div>
                <h1><?php //echo __('Ooops...'); ?></h1>
                <p>
                    <?php echo __('Dashboard not found in Grafana'); ?>
                </p>
            </div>
        </div>
    </div>
</div>
