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
            <span>
                <?php echo __('Service Escalation'); ?>
            </span>
            <div class="third_level"> <?php echo __('Edit'); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<confirm-delete></confirm-delete>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Edit Service Escalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <button type="button" class="btn btn-danger btn-xs" ng-click="confirmDelete(post.Serviceescalation)">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <back-button fallback-state="ServiceescalationsIndex"></back-button>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: '); ?>{{ post.Serviceescalation.uuid }}
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal">
                <div class="row">

                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Serviceescalation.container_id">
                            </select>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Service}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-success"></i> <?php echo __('Services'); ?>
                        </label>
                        <div class="col col-xs-10 success">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    ng-options="service.key as service.value group by service.group disable when service.disabled for service in services"
                                    ng-model="post.Serviceescalation.Service">
                            </select>
                            <div ng-repeat="error in errors.Service">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Service_excluded}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-danger"></i> <?php echo __('Services (excluded)'); ?>
                        </label>
                        <div class="col col-xs-10 danger">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicesExcluded"
                                    ng-options="service.key as service.value group by service.group disable when service.disabled for service in servicesExcluded"
                                    ng-model="post.Serviceescalation.Service_excluded">
                            </select>
                            <div ng-repeat="error in errors.Service_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Servicegroup}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-success"></i> <?php echo __('Servicegroups'); ?>
                        </label>
                        <div class="col col-xs-10 success">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicegroups"
                                    callback="loadServicegroups"
                                    ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroups"
                                    ng-model="post.Serviceescalation.Servicegroup">
                            </select>
                            <div ng-repeat="error in errors.Servicegroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Servicegroup_excluded}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-danger"></i> <?php echo __('Servicegroups (excluded)'); ?>
                        </label>
                        <div class="col col-xs-10 danger">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicegroupsExcluded"
                                    callback="loadServicegroups"
                                    ng-options="servicegroup.key as servicegroup.value disable when servicegroup.disabled for servicegroup in servicegroupsExcluded"
                                    ng-model="post.Serviceescalation.Servicegroup_excluded">
                            </select>
                            <div ng-repeat="error in errors.Servicegroup_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.first_notification}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('First escalation notice'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input  class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    ng-model="post.Serviceescalation.first_notification">
                            <div ng-repeat="error in errors.first_notification">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.last_notification}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Last escalation notice'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input  class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    ng-model="post.Serviceescalation.last_notification">
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
                        <div class="col col-xs-10">
                            <input  class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="60"
                                    ng-model="post.Serviceescalation.notification_interval">
                            <div class="help-block"><?php echo __('Interval in minutes'); ?></div>
                            <div ng-repeat="error in errors.notification_interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.timeperiod_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Timeperiod'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                    class="form-control"
                                    chosen="timeperiods"
                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                    ng-model="post.Serviceescalation.timeperiod_id">
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
                        <div class="col col-xs-10">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose a contact'); ?>"
                                    class="form-control"
                                    chosen="contacts"
                                    callback="loadContacts"
                                    ng-options="contact.key as contact.value for contact in contacts"
                                    ng-model="post.Serviceescalation.Contact">
                            </select>
                            <div ng-repeat="error in errors.Contact">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Contactgroup}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Contactgroups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    data-placeholder="<?php echo __('Please choose a contactgroup'); ?>"
                                    class="form-control"
                                    chosen="contactgroups"
                                    callback="loadContactgroups"
                                    ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                    ng-model="post.Serviceescalation.Contactgroup">
                            </select>
                            <div ng-repeat="error in errors.Contactgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <fieldset>
                        <legend class="font-sm">
                            <label><?php echo __('Serviceescalation options'); ?></label>
                        </legend>

                        <div class="form-group" ng-class="{'has-error': errors.escalate_on_recovery}"
                             style="margin-bottom: 0px;">
                            <label class="col-xs-12 col-lg-2 control-label" for="escalate_on_recovery">
                                <i class="fa fa-square txt-color-greenLight"></i>
                                <?php echo __('Recovery'); ?>
                            </label>

                            <div class="col-xs-12 col-lg-10 smart-form">
                                <label class="checkbox small-checkbox-label no-required">
                                    <input type="checkbox" id="escalate_on_recovery" ng-true-value="1"
                                           ng-false-value="0" ng-model="post.Serviceescalation.escalate_on_recovery"
                                           class="ng-pristine ng-untouched ng-valid ng-not-empty">
                                    <i class="checkbox-success"></i>
                                </label>
                                <div ng-repeat="error in errors.escalate_on_recovery">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.escalate_on_warning}"
                             style="margin-bottom: 0px;">
                            <label class="col-xs-12 col-lg-2 control-label" for="escalate_on_warning">
                                <i class="fa fa-square txt-color-orange"></i>
                                <?php echo __('Warning'); ?>
                            </label>

                            <div class="col-xs-12 col-lg-10 smart-form">
                                <label class="checkbox small-checkbox-label no-required">
                                    <input type="checkbox" id="escalate_on_warning" ng-true-value="1" ng-false-value="0"
                                           ng-model="post.Serviceescalation.escalate_on_warning"
                                           class="ng-pristine ng-untouched ng-valid ng-not-empty">
                                    <i class="checkbox-warning"></i>
                                </label>
                                <div ng-repeat="error in errors.escalate_on_warning">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.escalate_on_critical}"
                             style="margin-bottom: 0px;">
                            <label class="col-xs-12 col-lg-2 control-label" for="escalate_on_critical">
                                <i class="fa fa-square txt-color-redLight"></i>
                                <?php echo __('Critical'); ?>
                            </label>

                            <div class="col-xs-12 col-lg-10 smart-form">
                                <label class="checkbox small-checkbox-label no-required">
                                    <input type="checkbox" id="escalate_on_critical" ng-true-value="1" ng-false-value="0"
                                           ng-model="post.Serviceescalation.escalate_on_critical"
                                           class="ng-pristine ng-untouched ng-valid ng-not-empty">
                                    <i class="checkbox-danger"></i>
                                </label>
                                <div ng-repeat="error in errors.escalate_on_critical">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.escalate_on_unknown}"
                             style="margin-bottom: 0px;">
                            <label class="col-xs-12 col-lg-2 control-label" for="escalate_on_unknown">
                                <i class="fa fa-square txt-color-blueDark"></i>
                                <?php echo __('Unknown'); ?>
                            </label>

                            <div class="col-xs-12 col-lg-10 smart-form">
                                <label class="checkbox small-checkbox-label no-required">
                                    <input type="checkbox" id="escalate_on_unknown" ng-true-value="1"
                                           ng-false-value="0" ng-model="post.Serviceescalation.escalate_on_unknown"
                                           class="ng-pristine ng-untouched ng-valid ng-not-empty">
                                    <i class="checkbox-unknown"></i>
                                </label>
                                <div ng-repeat="error in errors.escalate_on_unknown">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
            </form>

            <br/>
            <br/>
            <div class="well formactions ">
                <div class="pull-right">
                    <a ng-click="submit()" class="btn btn-primary"><?php echo __('Save'); ?></a>&nbsp;
                    <a ui-sref="ServiceescalationsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
