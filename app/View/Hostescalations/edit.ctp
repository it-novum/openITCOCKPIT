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
                <?php echo __('Host escalation'); ?>
            </span>
            <div class="third_level"> <?php echo __('Edit'); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Edit host escalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="HostescalationsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Host escalation'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
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
                                        ng-model="post.Hostescalation.container_id">
                                </select>
                                <div class="info-block-helptext">
                                    <?php echo __('Notification escalations could be used to notify a certain user group in case of an emergency.
Once a host escalated, contacts, contact group and notification options will be overwritten by the escalation.'); ?>
                                </div>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hosts}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-success required">
                                    <label class="label label-success label-xs">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                           ng-class="{'has-error': errors.hosts}">
                                        <?php echo __('Hosts'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 success">
                                <select id="HostescalationHost"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts"
                                        ng-options="host.key as host.value disable when host.disabled for host in hosts"
                                        ng-model="post.Hostescalation.hosts._ids">
                                </select>
                                <div ng-repeat="error in errors.hosts">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hosts_excluded}">
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
                            <div class="col col-xs-12 col-lg-10 danger">
                                <select id="HostescalationHostExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts_excluded"
                                        ng-options="host.key as host.value disable when host.disabled for host in hosts_excluded"
                                        ng-model="post.Hostescalation.hosts_excluded._ids">
                                </select>
                                <div ng-repeat="error in errors.hosts_exluded">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hostgroups}">
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
                            <div class="col col-xs-12 col-lg-10 success">
                                <select id="HostescalationHostgroup"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroups"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups"
                                        ng-model="post.Hostescalation.hostgroups._ids">
                                </select>
                                <div ng-repeat="error in errors.Hostgroup">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.hostgroups_excluded}">
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
                            <div class="col col-xs-12 col-lg-10 danger">
                                <select id="HostescalationHostgroupExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hostgroups_excluded"
                                        ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups_excluded"
                                        ng-model="post.Hostescalation.hostgroups_excluded._ids">
                                </select>
                                <div ng-repeat="error in errors.hostgroups_excluded">
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
                                <div class="info-block-helptext">
                                    <?php echo __('Number of notifications that passed before the escalation rule will 
                                    overwrite notification settings.'); ?>
                                </div>
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
                                <div class="info-block-helptext">
                                    <?php echo __('If number of last_notification is reached, the notification rule 
                                    will be disabled and the notification options of the host or service will be used again.'); ?>
                                </div>
                                <div class="info-block-helptext">
                                    <?php echo __('Host escalates after: More than '); ?>
                                    {{post.Hostescalation.first_notification ? post.Hostescalation.first_notification :
                                    '?'}}
                                    <?php echo __(' where send and less than '); ?>
                                    {{post.Hostescalation.last_notification ? post.Hostescalation.last_notification :
                                    '?'}}
                                </div>
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
                            <div class="col-xs-12 col-lg-offset-2">
                                <div ng-repeat="error in errors.notification_interval">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col col-md-2 control-label">
                                <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                    <a ui-sref="TimeperiodsEdit({id:post.Hostescalation.timeperiod_id})">
                                        <?php echo __('Escalation period'); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('Escalation period'); ?>
                                <?php endif; ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose a escalation timeperiod'); ?>"
                                        class="form-control"
                                        chosen="timeperiods"
                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                        ng-model="post.Hostescalation.timeperiod_id">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.contacts}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Contacts'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        multiple
                                        data-placeholder="<?php echo __('Please choose a contact'); ?>"
                                        class="form-control"
                                        chosen="contacts"
                                        ng-options="contact.key as contact.value for contact in contacts"
                                        ng-model="post.Hostescalation.contacts._ids">
                                </select>
                                <div ng-repeat="error in errors.contacts">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.contactgroups}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Contact groups'); ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        multiple
                                        data-placeholder="<?php echo __('Please choose a contact group'); ?>"
                                        class="form-control"
                                        chosen="contactgroups"
                                        ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                        ng-model="post.Hostescalation.contactgroups._ids">
                                </select>
                                <div ng-repeat="error in errors.contactgroups">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <legend class="font-sm">
                                <div>
                                    <label>
                                        <?php echo __('Escalation options'); ?>
                                    </label>
                                </div>
                                <div ng-repeat="error in errors.escalate_on_recovery">
                                    <div class="text-danger">{{ error }}</div>
                                </div>
                            </legend>

                            <ul class="config-flex-inner">
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_recovery"
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
                                                       id="escalate_on_recovery"
                                                       ng-model="post.Hostescalation.escalate_on_recovery">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_down"
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
                                                       id="escalate_on_down"
                                                       ng-model="post.Hostescalation.escalate_on_down">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_unreachable"
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
                        <?php echo __('Save host escalation'); ?>
                    </a>&nbsp;
                    <a ui-sref="HostescalationsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
