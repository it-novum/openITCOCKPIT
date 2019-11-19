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
                <?php echo __('Service escalation'); ?>
            </span>
            <div class="third_level"> <?php echo __('Edit'); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Edit service escalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="ServiceescalationsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Service escalation'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
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
                                        ng-model="post.Serviceescalation.container_id">
                                </select>
                                <div class="info-block-helptext">
                                    <?php echo __('Notification escalations could be used to notify a certain user group in case of an emergency.
Once a service escalated, contacts, contact group and notification options will be overwritten by the escalation.'); ?>
                                </div>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.services}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-success required">
                                    <label class="label label-success label-xs">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </label>
                                    <label class="label label-light label-xs no-border"
                                           ng-class="{'has-error': errors.services}">
                                        <?php echo __('Services'); ?>
                                    </label>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 success">
                                <select id="ServiceescalationService"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="services"
                                        callback="loadServices"
                                        ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services"
                                        ng-model="post.Serviceescalation.services._ids">
                                </select>
                                <div ng-repeat="error in errors.services">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.services_excluded}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-danger">
                                    <label class="label label-danger label-xs">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Excluded services'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 danger">
                                <select id="ServiceescalationServiceExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="services_excluded"
                                        callback="loadExcludedServices"
                                        ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services_excluded"
                                        ng-model="post.Serviceescalation.services_excluded._ids">
                                </select>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.servicegroups}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-success">
                                    <label class="label label-success label-xs">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Service groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 success">
                                <select id="ServiceescalationServicegroup"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicegroups"
                                        ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroups"
                                        ng-model="post.Serviceescalation.servicegroups._ids">
                                </select>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.servicegroups_excluded}">
                            <label class="col col-md-2 control-label">
                                <div class="label-group label-breadcrumb label-breadcrumb-danger">
                                    <label class="label label-danger label-xs">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </label>
                                    <span class="label label-light label-xs no-border">
                                        <?php echo __('Excluded service groups'); ?>
                                    </span>
                                </div>
                            </label>
                            <div class="col col-xs-12 col-lg-10 danger">
                                <select id="ServiceescalationServicegroupExcluded"
                                        multiple
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicegroups_excluded"
                                        ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroups_excluded"
                                        ng-model="post.Serviceescalation.servicegroups_excluded._ids">
                                </select>
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
                                        ng-model="post.Serviceescalation.first_notification">
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
                                        ng-model="post.Serviceescalation.last_notification">
                                <div class="info-block-helptext">
                                    <?php echo __('If number of last_notification is reached, the notification rule 
                                    will be disabled and the notification options of the service will be used again.'); ?>
                                </div>
                                <div class="info-block-helptext">
                                    <?php echo __('Service escalates after: More than '); ?>
                                    {{post.Serviceescalation.first_notification ? post.Serviceescalation.first_notification :
                                    '?'}}
                                    <?php echo __(' where send and less than '); ?>
                                    {{post.Serviceescalation.last_notification ? post.Serviceescalation.last_notification :
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
                                    interval="post.Serviceescalation.notification_interval"></interval-input-directive>
                            <div class="col-xs-12 col-lg-offset-2">
                                <div ng-repeat="error in errors.notification_interval">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col col-md-2 control-label">
                                <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                    <a ui-sref="TimeperiodsEdit({id:post.Serviceescalation.timeperiod_id})">
                                        <?php echo __('Escalation period'); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('Time period'); ?>
                                <?php endif; ?>
                            </label>
                            <div class="col col-xs-12 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                        class="form-control"
                                        chosen="timeperiods"
                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                        ng-model="post.Serviceescalation.timeperiod_id">
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
                                        ng-model="post.Serviceescalation.contacts._ids">
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
                                        data-placeholder="<?php echo __('Please choose a contactgroup'); ?>"
                                        class="form-control"
                                        chosen="contactgroups"
                                        ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                        ng-model="post.Serviceescalation.contactgroups._ids">
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
                                                       ng-model="post.Serviceescalation.escalate_on_recovery">
                                                <i class="checkbox-success"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_warning"
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
                                                       id="escalate_on_warning"
                                                       ng-model="post.Serviceescalation.escalate_on_warning">
                                                <i class="checkbox-warning"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_critical"
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
                                                       id="escalate_on_critical"
                                                       ng-model="post.Serviceescalation.escalate_on_critical">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="margin-bottom-0">
                                        <label for="escalate_on_unknown"
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
                                                       id="escalate_on_unknown"
                                                       ng-model="post.Serviceescalation.escalate_on_unknown">
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
                        <?php echo __('Save service escalation'); ?>
                    </a>&nbsp;
                    <a ui-sref="ServiceescalationsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
