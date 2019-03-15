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
            <i class="fa fa-bomb fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Hostescalation'); ?>
			</span>
            <div class="third_level"> <?php echo __('Add'); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Add Hostescalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="HostescalationsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
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
                                        ng-model="post.Hostescalation.container_id">
                                </select>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.Host}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-success">
                                    <label class="label label-success label-xs">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Hosts'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select id="HostescalationHost"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts"
                                        ng-options="host.key as host.value disable when host.disabled for host in hosts"
                                        ng-model="post.Hostescalation.Host">
                                </select>
                                <div ng-repeat="error in errors.Host">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.Host_excluded}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-danger">
                                    <label class="label label-danger label-xs">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Excluded hosts'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select id="HostescalationHostExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostsExcluded"
                                        ng-options="host.key as host.value disable when host.disabled for host in hostsExcluded"
                                        ng-model="post.Hostescalation.Host_excluded">
                                </select>
                                <div ng-repeat="error in errors.Host_excluded">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.Hostgroup}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-success">
                                    <label class="label label-success label-xs">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Host groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select id="HostescalationHostgroup"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroups"
                                        callback="loadHostgroups"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups"
                                        ng-model="post.Hostescalation.Hostgroup">
                                </select>
                                <div ng-repeat="error in errors.Hostgroup">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.Hostgroup_excluded}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-danger">
                                    <label class="label label-danger label-xs">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Excluded host groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select id="HostescalationHostgroupExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroupsExcluded"
                                        callback="loadHostgroups"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroupsExcluded"
                                        ng-model="post.Hostescalation.Hostgroup_excluded">
                                </select>
                                <div ng-repeat="error in errors.Hostgroup_excluded">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.first_notification}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('First notification'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                        ng-model="post.Hostescalation.first_notification">
                                <div ng-repeat="error in errors.first_notification">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.last_notification}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Last notification'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <input
                                        class="form-control"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                        ng-model="post.Hostescalation.last_notification">
                                <div ng-repeat="error in errors.last_notification">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required"
                             ng-class="{'has-error': errors.notification_interval}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Notification interval'); ?>
                            </label>
                            <interval-input-directive
                                    interval="post.Hostescalation.notification_interval"></interval-input-directive>
                            <div class="col col-xs-12">
                                <div ng-repeat="error in errors.notification_interval">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.timeperiod_id}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Timeperiod'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                        class="form-control"
                                        chosen="timeperiods"
                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                        ng-model="post.Hostescalation.timeperiod_id">
                                </select>
                                <div ng-repeat="error in errors.timeperiod_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.Contact}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Contacts'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        multiple
                                        data-placeholder="<?php echo __('Please choose a contact'); ?>"
                                        class="form-control"
                                        chosen="contacts"
                                        callback="loadContacts"
                                        ng-options="contact.key as contact.value for contact in contacts"
                                        ng-model="post.Hostescalation.Contact">
                                </select>
                                <div ng-repeat="error in errors.Contact">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.Contactgroup}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Contact groups'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        multiple
                                        data-placeholder="<?php echo __('Please choose a contactgroup'); ?>"
                                        class="form-control"
                                        chosen="contactgroups"
                                        callback="loadContactgroups"
                                        ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                        ng-model="post.Hostescalation.Contactgroup">
                                </select>
                                <div ng-repeat="error in errors.Contactgroup">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <legend class="font-sm">
                                <label><?php echo __('Host escalation options'); ?></label>
                            </legend>
                            <ul class="config-flex-inner">
                                <li>
                                    <div class="margin-bottom-0" ng-class="{'has-error': errors.escalate_on_recovery}">
                                        <label for="escalate_on_recovery"
                                               class="col col-md-7 control-label padding-top-0">
                                        <span class="label label-success notify-label-small">
                                            <?php echo __('Recovery'); ?>
                                        </span>
                                        </label>
                                        <div class="col-md-2 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="escalate_on_recovery"
                                                       ng-model="post.Hostescalation.escalate_on_recovery">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0" ng-class="{'has-error': errors.escalate_on_down}">
                                        <label for="escalate_on_down" class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-danger notify-label-small">
                                            <?php echo __('Down'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="escalate_on_down"
                                                       ng-model="post.Hostescalation.escalate_on_down">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0"
                                         ng-class="{'has-error': errors.escalate_on_unreachable}">
                                        <label for="escalate_on_unreachable"
                                               class="col col-md-7 control-label padding-top-0">
                                            <span class="label label-default notify-label-small">
                                                <?php echo __('Unreachable'); ?>
                                            </span>
                                        </label>
                                        <div class="col-md-2 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="escalate_on_unreachable"
                                                       ng-model="post.Hostescalation.escalate_on_unreachable">
                                                <i class="checkbox-default"></i>
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
                        <?php echo __('Create host escalation'); ?>
                    </a>&nbsp;
                    <a ui-sref="HostescalationsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
