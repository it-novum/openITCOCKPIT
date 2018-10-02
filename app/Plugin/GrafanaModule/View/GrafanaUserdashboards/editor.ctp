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
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>

    <div class="col-xs-12 col-md-6">
        <div class="alert alert-info">
            <i class="fa-fw fa fa-info"></i>
            <?php echo __('Please make sure to synchronize your changes with Grafana after you have made modifications.'); ?>
        </div>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-dashboard"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php echo __('Dashboard:'); ?>
            {{name}}
        </h2>
        <div class="widget-toolbar">
            <button class="btn btn-primary btn-xs" ng-click="synchronizeWithGrafana()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Synchronize with Grafana'); ?>
            </button>
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row padding-top-10" ng-repeat="(rowId, row) in data">
                <grafana-row id="id" row="row" row-id="rowId" remove-row-callback="removeRowCallback" grafana-units="grafanaUnits"></grafana-row>
            </div>

            <hr/>

            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-success btn-xs" ng-click="addRow()">
                        <i class="fa fa-plus"></i>
                        <?php echo __('Add row'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Synchronize with Grafana Modal -->
<div id="synchronizeWithGrafanaModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Synchronize with Grafana Modal'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <div class="progress progress-sm progress-striped active">
                            <div class="progress-bar bg-color-blue" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="syncError">
                    <div class="col-xs-12">
                        <div class="alert alert-danger">
                            <i class="fa-fw fa fa-times"></i>
                            {{syncError}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
