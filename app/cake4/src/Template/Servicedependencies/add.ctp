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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-sitemap fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service dependencies'); ?>
            </span>
            <div class="third_level"> <?php echo __('Add'); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2><?php echo __('Add service dependency'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="ServicedependenciesIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Service dependency'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Container'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="containers"
                                        ng-options="container.key as container.value for container in containers"
                                        ng-model="post.Servicedependency.container_id">
                                </select>
                                <div class="info-block-helptext">
                                    <?php echo __('Service dependencies are an advanced feature that allow you to 
                                    suppress notifications for services based on the status of one or more other services.'); ?>
                                </div>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.services}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-default required">
                                    <label class="label label-default label-xs">
                                        <i class="fa fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                           ng-class="{'has-error': errors.services}">
                                        <?php echo __('Services'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 default">
                                <select id="ServicedependencyService"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="services"
                                        callback="loadServices"
                                        ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services"
                                        ng-model="post.Servicedependency.services._ids">
                                </select>
                                <div ng-repeat="error in errors.services">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.services_dependent}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-primary required">
                                    <label class="label label-primary label-xs">
                                        <i class="fa fa-sitemap fa-rotate-90" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                           ng-class="{'has-error': errors.services_dependent}">
                                        <?php echo __('Dependent services'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 info">
                                <select id="ServicedependencyServiceDependent"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="services_dependent"
                                        callback="loadDependentServices"
                                        ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services_dependent"
                                        ng-model="post.Servicedependency.services_dependent._ids">
                                </select>
                                <div ng-repeat="error in errors.services_dependent">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-default">
                                    <label class="label label-default label-xs">
                                        <i class="fa fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Service groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 default">
                                <select id="ServicedependencyServicegroup"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicegroups"
                                        ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroups"
                                        ng-model="post.Servicedependency.servicegroups._ids">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-primary">
                                    <label class="label label-primary label-xs">
                                        <i class="fa fa-sitemap fa-rotate-90" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Dependent service groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 info">
                                <select id="ServicedependencyServicegroupDependent"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicegroups_dependent"
                                        ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroups_dependent"
                                        ng-model="post.Servicedependency.servicegroups_dependent._ids">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Time period'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                        class="form-control"
                                        chosen="timeperiods"
                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                        ng-model="post.Servicedependency.timeperiod_id">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-2 control-label" for="inheritsParent">
                                <?php echo __('Inherits parent'); ?>
                            </label>

                            <div class="col-xs-12 col-lg-1 smart-form">
                                <label class="checkbox no-required no-padding no-margin label-default-off">
                                    <input type="checkbox" name="checkbox"
                                           id="inheritsParent"
                                           ng-true-value="1"
                                           ng-false-value="0"
                                           ng-model="post.Servicedependency.inherits_parent">
                                    <i class="checkbox-primary"></i>
                                </label>
                            </div>
                        </div>

                        <fieldset>
                            <legend class="font-sm">
                                <div>
                                    <label>
                                        <?php echo __('Execution failure criteria'); ?>
                                    </label>
                                </div>
                            </legend>
                            <ul class="config-flex-inner">
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_ok"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-success notify-label-small">
                                            <?php echo __('Ok'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_up"
                                                       ng-model="post.Servicedependency.execution_fail_on_ok">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_up"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-warning notify-label-small">
                                            <?php echo __('Warning'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_warning"
                                                       ng-model="post.Servicedependency.execution_fail_on_warning">
                                                <i class="checkbox-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_critical"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-danger notify-label-small">
                                            <?php echo __('Critical'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_down"
                                                       ng-model="post.Servicedependency.execution_fail_on_critical">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_unknown"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-default notify-label-small">
                                                <?php echo __('Unknown'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_unknown"
                                                       ng-model="post.Servicedependency.execution_fail_on_unknown">
                                                <i class="checkbox-default"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_pending"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary notify-label-small">
                                                <?php echo __('Pending'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_pending"
                                                       ng-model="post.Servicedependency.execution_fail_on_pending">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_none"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary notify-label-small">
                                                <?php echo __('Execution none'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_none"
                                                       ng-model="post.Servicedependency.execution_none">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </fieldset>
                        <fieldset>
                            <legend class="font-sm">
                                <div>
                                    <label>
                                        <?php echo __('Notification failure criteria'); ?>
                                    </label>
                                </div>
                            </legend>
                            <ul class="config-flex-inner">
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_ok"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-success notify-label-small">
                                            <?php echo __('Ok'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_ok"
                                                       ng-model="post.Servicedependency.notification_fail_on_ok">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_warning"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-warning notify-label-small">
                                            <?php echo __('Warning'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_warning"
                                                       ng-model="post.Servicedependency.notification_fail_on_warning">
                                                <i class="checkbox-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_critical"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-danger notify-label-small">
                                            <?php echo __('Critical'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_critical"
                                                       ng-model="post.Servicedependency.notification_fail_on_critical">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_unknown"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-default notify-label-small">
                                                <?php echo __('Unknown'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_unknown"
                                                       ng-model="post.Servicedependency.notification_fail_on_unknown">
                                                <i class="checkbox-default"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_pending"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary notify-label-small">
                                                <?php echo __('Pending'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_pending"
                                                       ng-model="post.Servicedependency.notification_fail_on_pending">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_none"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary notify-label-small">
                                                <?php echo __('Notification none'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_none"
                                                       ng-model="post.Servicedependency.notification_none">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </fieldset>
                    </div>
                </div>
            </form>
            <div class="well formactions ">
                <div class="pull-right">
                    <a ng-click="submit()" class="btn btn-primary">
                        <?php echo __('Save service depependency'); ?>
                    </a>&nbsp;
                    <a ui-sref="ServicedependenciesIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
