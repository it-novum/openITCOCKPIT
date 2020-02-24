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
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Instant Report'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Edit Instant Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="InstantreportsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div class="widget-body">
        <form ng-submit="submit();" class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Instant report'); ?>' , message: '<?php echo __('updated successfully'); ?>'}">
            <div class="row">
                <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Container'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="ContainerId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Instantreport.container_id">
                        </select>
                        <div ng-repeat="error in errors.container_id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group required" ng-class="{'has-error': errors.name}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Name'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input
                                class="form-control"
                                type="text"
                                ng-model="post.Instantreport.name">
                        <div ng-repeat="error in errors.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group required">
                    <label class="col col-md-2 control-label" for="InstantreportType">
                        <?php echo __('Type'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select ng-model="post.Instantreport.type"
                                class="form-control chosen">
                            <option ng-value="1"><?php echo __('Host groups'); ?></option>
                            <option ng-value="2"><?php echo __('Hosts'); ?></option>
                            <option ng-value="3"><?php echo __('Service groups'); ?></option>
                            <option ng-value="4"><?php echo __('Services'); ?></option>
                        </select>
                    </div>
                    <div class="col col-md-offset-2 col-md-10">
                        <div class="help-block">
                            <?php echo __('Select the object type, which should be evaluated by the report.'); ?>
                        </div>
                    </div>
                </div>
                <div ng-switch="post.Instantreport.type">
                    <div class="form-group required" ng-class="{'has-error': errors.hostgroups}"
                         ng-switch-when="1">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-server"></i>
                            <?php echo __('Host groups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="HostgroupId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hostgroups"
                                    ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                    ng-model="post.Instantreport.hostgroups._ids">
                            </select>
                            <div ng-repeat="error in errors.hostgroups">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required" ng-class="{'has-error': errors.hosts}" ng-switch-when="2">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-desktop"></i>
                            <?php echo __('Hosts'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="HostId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    callback="loadHosts"
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="post.Instantreport.hosts._ids">
                            </select>
                            <div ng-repeat="error in errors.hosts">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required" ng-class="{'has-error': errors.servicegroups}"
                         ng-switch-when="3">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-cogs"></i>
                            <?php echo __('Service groups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="ServicegroupId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicegroups"
                                    ng-options="servicegroup.key as servicegroup.value for servicegroup in servicegroups"
                                    ng-model="post.Instantreport.servicegroups._ids">
                            </select>
                            <div ng-repeat="error in errors.servicegroups">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.services}" ng-switch-when="4">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-cog"></i>
                            <?php echo __('Services'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="ServiceId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    callback="loadServices"
                                    ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services"
                                    ng-model="post.Instantreport.services._ids">
                            </select>
                            <div ng-repeat="error in errors.services">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Evaluation'); ?>
                    </label>
                    <div class="col col-md-10">
                        <div class="radio radio-primary radio-inline">
                            <input type="radio"
                                   id="InstantreportEvaluation1"
                                   ng-model="post.Instantreport.evaluation"
                                   ng-value="1">
                            <label for="InstantreportEvaluation1">
                                <i class="fa fa-desktop"></i> <?php echo __('Hosts'); ?>
                            </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio"
                                   id="InstantreportEvaluation2"
                                   ng-model="post.Instantreport.evaluation"
                                   ng-value="2">
                            <label for="InstantreportEvaluation2">
                                <i class="fa fa-cogs"></i> <?php echo __('Host and Services'); ?>
                            </label>
                        </div>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio"
                                   id="InstantreportEvaluation3"
                                   ng-model="post.Instantreport.evaluation"
                                   ng-value="3">
                            <label for="InstantreportEvaluation3">
                                <i class="fa fa-cog"></i> <?php echo __('Services'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col col-md-offset-2 col-md-10">
                        <div class="help-block">
                            <?php echo __('Choose if <b><u>only</u></b> host, host and services or <b><u>only</u></b> services should be evaluated.'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group required" ng-class="{'has-error': errors.timeperiod_id}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Timeperiod'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="TimeperiodId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="timeperiods"
                                ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                ng-model="post.Instantreport.timeperiod_id">
                        </select>
                        <div ng-repeat="error in errors.timeperiod_id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InstantreportReflection" class="col col-md-2 control-label">
                        <?php echo __('Reflection state'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select class="form-control"
                                id="InstantreportReflection"
                                chosen="states"
                                ng-model="post.Instantreport.reflection">
                            <option ng-value="1">
                                <?php echo __('soft and hard state'); ?>
                            </option>
                            <option ng-value="2">
                                <?php echo __('only hard state'); ?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InstantreportDowntimes" class="col col-md-2 control-label">
                        <?php echo __('Consider downtimes'); ?>
                    </label>
                    <div class="col-xs-12 col-lg-10 smart-form">
                        <label class="checkbox small-checkbox-label no-required no-padding-top">
                            <input type="checkbox"
                                   id="InstantreportDowntimes"
                                   ng-false-value="0"
                                   ng-true-value="1"
                                   ng-model="post.Instantreport.downtimes">
                            <i class="checkbox-primary"></i>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InstantreportSummary" class="col col-md-2 control-label">
                        <?php echo __('Summary display'); ?>
                    </label>
                    <div class="col-xs-12 col-lg-10 smart-form">
                        <label class="checkbox small-checkbox-label no-required no-padding-top">
                            <input type="checkbox"
                                   id="InstantreportSummary"
                                   ng-false-value="0"
                                   ng-true-value="1"
                                   ng-model="post.Instantreport.summary">
                            <i class="checkbox-primary"></i>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InstantreportSendEmail" class="col col-md-2 control-label">
                        <?php echo __('Send email'); ?>
                    </label>
                    <div class="col-xs-12 col-lg-10 smart-form">
                        <label class="checkbox small-checkbox-label no-required no-padding-top">
                            <input type="checkbox"
                                   id="InstantreportSendEmail"
                                   ng-false-value="0"
                                   ng-true-value="1"
                                   ng-model="post.Instantreport.send_email">
                            <i class="checkbox-primary"></i>
                        </label>
                    </div>
                </div>
                <div class="send-interval-holder" ng-if="post.Instantreport.send_email">
                    <div class="form-group {{(post.Instantreport.send_email)?'required':''}}"
                         ng-class="{'has-error': errors.send_interval}">
                        <label for="InstantreportSendInterval" class="col col-md-2 control-label">
                            <?php echo __('Send interval'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    data-placeholder="<?php __('Please select...'); ?>"
                                    class="chosen form-control"
                                    id="InstantreportSendInterval"
                                    chosen="send_interval"
                                    ng-model="post.Instantreport.send_interval">
                                <option ng-value="0" ng-if="!post.Instantreport.send_email">
                                    <?php echo __('NEVER'); ?>
                                </option>
                                <option ng-value="1">
                                    <?php echo __('DAY'); ?>
                                </option>
                                <option ng-value="2">
                                    <?php echo __('WEEK'); ?>
                                </option>
                                <option ng-value="3">
                                    <?php echo __('MONTH'); ?>
                                </option>
                                <option ng-value="4">
                                    <?php echo __('YEAR'); ?>
                                </option>
                            </select>
                            <div ng-repeat="error in errors.send_interval">
                                <div class="text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required" ng-class="{'has-error': errors.send_email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Users to send'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="UserId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="users"
                                    ng-options="user.key as user.value for user in users"
                                    ng-model="post.Instantreport.users._ids">
                            </select>
                            <div ng-repeat="error in errors.send_email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="<?= __('Save') ?>">&nbsp;
                            <a ui-sref="InstantreportsIndex" class="btn btn-default">
                                <?php echo __('Cancel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
