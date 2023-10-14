<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Grafana Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="GrafanaUserdashboardsIndex">
            <i class="fas fa-chart-area"></i> <?php echo __('User dashboards'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-edit"></i> <?php echo __('Editor'); ?>
    </li>
</ol>

<?php if ($hasGrafanaConfig === false): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="javascript:void(0);">×</a>
        <h4 class="alert-heading"><?php echo __('No Grafana configuration found!'); ?></h4>
        <?php
        $msg = __('Grafana Configuration');
        if ($this->Acl->hasPermission('index', 'GrafanaConfiguration', 'GrafanaModule')):
            $msg = sprintf('<a ui-sref="GrafanaConfigurationIndex">%s</a>', $msg);
        endif;
        ?>

        <?php echo __('A valid {0} is required, before this feature can be used.', $msg); ?>
    </div>
<?php endif; ?>


<div class="col-lg-12">
    <div class="alert alert-info">
        <i class="fa-fw fa fa-info"></i>
        <?php echo __('Please make sure to synchronize your changes with Grafana after you have made modifications.'); ?>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Dashboard'); ?>:
                    <span class="fw-300"><i>{{name}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('edit', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                        <button class="btn btn-xs btn-default mr-1 shadow-0" ui-sref="GrafanaUserdashboardsEdit({id: id})">
                            <i class="fas fa-edit"></i> <?php echo __('Edit settings'); ?>
                        </button>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('index', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='GrafanaUserdashboardsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-primary btn-xs mr-1 shadow-0" ng-click="synchronizeWithGrafana()">
                        <i class="fa fa-refresh"></i>
                        <?php echo __('Synchronize with Grafana'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-lg-12" ng-repeat="(rowId, row) in data">
                            <grafana-row id="id" row="row" row-id="rowId" remove-row-callback="removeRowCallback"
                                         grafana-units="grafanaUnits" container-id="containerId"></grafana-row>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-success btn-xs" ng-click="addRow()">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Add row'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Synchronize with Grafana Modal -->
<div id="synchronizeWithGrafanaModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Synchronize with Grafana Modal'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 smart-form">
                        <div class="progress progress-sm progress-striped active">
                            <div class="progress-bar bg-color-blue" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="syncError">
                    <div class="col-lg-12">
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
