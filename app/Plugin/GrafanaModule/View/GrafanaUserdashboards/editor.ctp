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
                <button class="btn btn-xs btn-success" ng-click="addNewRow();">Add Row</button>
                <div class="row" ng-repeat="(rowKey, row) in inputData.data">
                    <button class="btn btn-xs btn-success" ng-click="addNewPanel(rowKey);">Add Panel</button>
                    <button class="btn btn-xs btn-danger" ng-click="removeRow(rowKey);">Remove Row</button>
                    <div class="col col-lg-3" style="border: 1px solid black;"
                         ng-repeat="(panelKey, panel) in inputData.data[rowKey]">
                        <button class="btn btn-xs btn-success" ng-click="addNewMetric(rowKey, panelKey);">Add Metric
                        </button>
                        <button class="btn btn-xs btn-danger" ng-click="removePanel(rowKey, panelKey);">Remove Panel
                        </button>


                        <div class="row" style="border: 1px solid red"
                             ng-repeat="(metricKey, metric) in inputData.data[rowKey][panelKey]">
                            <div class="col col-lg-12">
                                <select
                                        id="HostContainer"
                                        data-placeholder="<?php echo __('Please choose Host'); ?>"
                                        class="form-control"
                                        chosen="inputData.hosts"
                                        ng-options="host.key as host.value for host in inputData.hosts"
                                        ng-model="inputData.data[rowKey][panelKey][metricKey].hostId"
                                        ng-change="hostSelected(inputData.data[rowKey][panelKey][metricKey].hostId, rowKey, panelKey, metricKey)"
                                >
                                </select>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                            <div class="col col-lg-12">
                                <select
                                        id="ServiceContainer"
                                        data-placeholder="<?php echo __('Please choose Service'); ?>"
                                        class="form-control"
                                        chosen="inputData.data[rowKey][panelKey][metricKey].services"
                                        ng-options="service.value.Service.id as ((service.value.Service.name)?service.value.Service.name:service.value.Servicetemplate.name) for service in inputData.data[rowKey][panelKey][metricKey].services"
                                        ng-model="inputData.data[rowKey][panelKey][metricKey].serviceId"
                                        ng-change="serviceSelected(inputData.data[rowKey][panelKey][metricKey].serviceId, rowKey, panelKey, metricKey)"
                                >
                                </select>
                            </div>

                            <div class="col col-lg-10">
                                <select
                                        id="MetricContainer"
                                        data-placeholder="<?php echo __('Please choose Metric'); ?>"
                                        class="form-control"
                                        chosen="inputData.data[rowKey][panelKey][metricKey].metrics"
                                        ng-options="metric.label as metric.name for metric in inputData.data[rowKey][panelKey][metricKey].metrics"
                                        ng-model="inputData.data[rowKey][panelKey][metricKey].metricValue"
                                        ng-change="metricSelected(rowKey, panelKey, metricKey)"
                                >
                                </select>
                            </div>
                            <div class="col col-lg-2">
                                <button class="btn btn-xs btn-danger" ng-click="removeMetric(rowKey, panelKey, metricKey)">-</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="grafanaUserdashboards"></div>
        </div>
    </div>
</div>