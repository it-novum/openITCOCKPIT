<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
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
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('User Dashboard Add'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <button class="btn btn-success" ng-click="addNewMetric();">Add Metric</button>
                    <div class="row" ng-repeat="(key, metric) in metrics">
                        <div class="col col-lg-3">
                            <select
                                    id="HostContainer"
                                    data-placeholder="<?php echo __('Please choose Host'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="post.metrics[key].hostId"
                                    ng-change="containerSelected()"
                            >
                            </select>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="col col-lg-4">
                            <select
                                    id="ServiceContainer"
                                    data-placeholder="<?php echo __('Please choose Service'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    ng-options="service.key as service.value for service in services"
                                    ng-model="post.Container.container_id"
                                    ng-change="containerSelected()"
                            >
                            </select>
                        </div>

                        <div class="col col-lg-4">
                            <select
                                    id="MetricContainer"
                                    data-placeholder="<?php echo __('Please choose Metric'); ?>"
                                    class="form-control"
                                    chosen="metrics"
                                    ng-options="metric.key as metric.value for metric in metrics"
                                    ng-model="post.Container.container_id"
                                    ng-change="containerSelected()"
                            >
                            </select>
                        </div>
                        <div class="col col-lg-1">
                            <button class="remove" ng-show="$last" ng-click="removeChoice()">-</button>
                        </div>
                        {{metrics[key].hostId}}
                    </div>
                    {{metrics}}
                </div>
            </form>
        </div>
    </div>
</div>