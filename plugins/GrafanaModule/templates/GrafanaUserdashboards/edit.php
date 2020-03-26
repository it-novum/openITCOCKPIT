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
        <i class="fa fa-edit"></i> <?php echo __('Edit'); ?>
    </li>
</ol>

<div class="alert alert-danger alert-block" ng-hide="hasGrafanaConfig">
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


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Edit user defined Grafana dashboard'); ?>
                    <span class="fw-300"><i>{{post.name}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='GrafanaUserdashboardsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal"
                          ng-init="successMessage=
                            {objectName : '<?php echo __('Grafana dashboard'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                        <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                            <label class="control-label" for="ContainersSelect">
                                <?php echo __('Container'); ?>
                            </label>
                            <select
                                id="ContainersSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.container_id">
                            </select>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.name}">
                            <label class="control-label">
                                <?php echo __('User dashboard name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="My awesome Dashboard"
                                ng-model="post.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary" type="submit">
                                        <?php echo __('Update Grafana dashboard'); ?>
                                    </button>
                                    <a back-button href="javascript:void(0);" fallback-state='GrafanaUserdashboardsIndex' class="btn btn-default">
                                        <?php echo __('Cancel'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
