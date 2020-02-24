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
                <?php echo __('Host dependencies'); ?>
            </span>
            <div class="third_level"> <?php echo __('Edit'); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2><?php echo __('Add host dependency'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="HostdependenciesIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Host dependency'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
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
                                        ng-model="post.Hostdependency.container_id">
                                </select>
                                <div class="info-block-helptext">
                                    <?php echo __('Host dependencies are an advanced feature that allow you to 
                                    suppress notifications for hosts based on the status of one or more other hosts.'); ?>
                                </div>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hosts}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-default required">
                                    <label class="label label-default label-xs">
                                        <i class="fa fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                          ng-class="{'has-error': errors.hosts}">
                                        <?php echo __('Hosts'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 default">
                                <select id="HostdependencyHost"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts"
                                        callback="loadHosts"
                                        ng-options="host.key as host.value disable when host.disabled for host in hosts"
                                        ng-model="post.Hostdependency.hosts._ids">
                                </select>
                                <div ng-repeat="error in errors.hosts">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hosts_dependent}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-primary required">
                                    <label class="label label-primary label-xs">
                                        <i class="fa fa-sitemap fa-rotate-90" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                          ng-class="{'has-error': errors.hosts_dependent}">
                                        <?php echo __('Dependent hosts'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 info">
                                <select id="HostdependencyHostDependent"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts_dependent"
                                        callback="loadDependentHosts"
                                        ng-options="host.key as host.value disable when host.disabled for host in hosts_dependent"
                                        ng-model="post.Hostdependency.hosts_dependent._ids">
                                </select>
                                <div ng-repeat="error in errors.hosts_dependent">
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
                                        <?php echo __('Host groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 default">
                                <select id="HostdependencyHostgroup"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroups"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups"
                                        ng-model="post.Hostdependency.hostgroups._ids">
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
                                        <?php echo __('Dependent host groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 info">
                                <select id="HostdependencyHostgroupDependent"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroups_dependent"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups_dependent"
                                        ng-model="post.Hostdependency.hostgroups_dependent._ids">
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
                                        ng-model="post.Hostdependency.timeperiod_id">
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
                                           ng-model="post.Hostdependency.inherits_parent">
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
                                        <label for="execution_fail_on_up"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-success notify-label-small">
                                            <?php echo __('Recovery'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_up"
                                                       ng-model="post.Hostdependency.execution_fail_on_up">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_down"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-danger notify-label-small">
                                            <?php echo __('Down'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_down"
                                                       ng-model="post.Hostdependency.execution_fail_on_down">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_fail_on_unreachable"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-default notify-label-small">
                                                <?php echo __('Unreachable'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_fail_on_unreachable"
                                                       ng-model="post.Hostdependency.execution_fail_on_unreachable">
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
                                                       ng-model="post.Hostdependency.execution_fail_on_pending">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="execution_none"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary label-none notify-label-small">
                                                <?php echo __('Execution none'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="execution_none"
                                                       ng-model="post.Hostdependency.execution_none">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                        <i class="fa fa-lg fa-info-circle text-info padding-top-7 helpText"
                                           id="infoButtonExecutionOptions" role="tooltip" data-toggle="tooltip"
                                           data-html="true" title="<?php echo __('\'Execution none\' option'); ?>"
                                           data-content="<div>
                                           <?php echo __('Another options will be deselected automatically!'); ?>
                                           </div>"></i>
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
                                        <label for="notification_fail_on_up"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-success notify-label-small">
                                            <?php echo __('Recovery'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_up"
                                                       ng-model="post.Hostdependency.notification_fail_on_up">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_down"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-danger notify-label-small">
                                            <?php echo __('Down'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_down"
                                                       ng-model="post.Hostdependency.notification_fail_on_down">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_fail_on_unreachable"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-default notify-label-small">
                                                <?php echo __('Unreachable'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_fail_on_unreachable"
                                                       ng-model="post.Hostdependency.notification_fail_on_unreachable">
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
                                                       ng-model="post.Hostdependency.notification_fail_on_pending">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="notification_none"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-primary label-none notify-label-small">
                                                <?php echo __('Notification none'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form padding-left-5">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="notification_none"
                                                       ng-model="post.Hostdependency.notification_none">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                        <i class="fa fa-lg fa-info-circle text-info padding-top-7 helpText"
                                           id="infoButtonNotificationOptions" role="tooltip" data-toggle="tooltip"
                                           data-html="true" title="<?php echo __('\'Notification none\' option'); ?>"
                                           data-content="<div>
                                           <?php echo __('Another options will be deselected automatically!'); ?>
                                           </div>"></i>
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
                        <?php echo __('Save host depependency'); ?>
                    </a>&nbsp;
                    <a ui-sref="HostdependenciesIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
