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
            <a href="/ng/#!/hostescalations/index" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.container_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Hostescalation.container_id">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.Host}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-success"></i> <?php echo __('Hosts'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select id="HostescalationHost"
                                    multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    ng-options="host.key as host.value disable when host.disabled for host in hosts"
                                    ng-model="post.Hostescalation.Host">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Host">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Hostescalation.Host_excluded}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-danger"></i> <?php echo __('Hosts (excluded)'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select id="HostescalationHostExcluded"
                                    multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hostsExcluded"
                                    ng-options="host.key as host.value disable when host.disabled for host in hostsExcluded"
                                    ng-model="post.Hostescalation.Host_excluded">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Host_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Hostescalation.Hostgroup}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-success"></i> <?php echo __('Hostgroups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select id="HostescalationHostgroup"
                                    multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hostgroups"
                                    callback="loadHostgroups"
                                    ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups"
                                    ng-model="post.Hostescalation.Hostgroup">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Hostgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Hostescalation.Hostgroup_excluded}">
                        <label class="col col-md-2 control-label">
                            <i class="fa fa-plus-square text-danger"></i> <?php echo __('Hostgroups (excluded)'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select id="HostescalationHostgroupExcluded"
                                    multiple
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hostgroupsExcluded"
                                    callback="loadHostgroups"
                                    ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroupsExcluded"
                                    ng-model="post.Hostescalation.Hostgroup_excluded">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Hostgroup_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.first_notification}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('First escalation notice'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    ng-model="post.Hostescalation.first_notification">
                            <div ng-repeat="error in errors.Hostescalation.first_notification">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.last_notification}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Last escalation notice'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    ng-model="post.Hostescalation.last_notification">
                            <div ng-repeat="error in errors.Hostescalation.last_notification">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.notification_interval}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Notification interval'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="number"
                                    min="0"
                                    placeholder="60"
                                    ng-model="post.Hostescalation.notification_interval">
                            <div class="help-block"><?php echo __('Interval in minutes'); ?></div>
                            <div ng-repeat="error in errors.Hostescalation.notification_interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.timeperiod_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Timeperiod'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                    class="form-control"
                                    chosen="timeperiods"
                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                    ng-model="post.Hostescalation.timeperiod_id">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.timeperiod_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.Contact}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Contacts'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    multiple
                                    data-placeholder="<?php echo __('Please choose a contact'); ?>"
                                    class="form-control"
                                    chosen="contacts"
                                    callback="loadContacts"
                                    ng-options="contact.key as contact.value for contact in contacts"
                                    ng-model="post.Hostescalation.Contact">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Contact">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Hostescalation.Contactgroup}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Contactgroups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    multiple
                                    data-placeholder="<?php echo __('Please choose a contactgroup'); ?>"
                                    class="form-control"
                                    chosen="contactgroups"
                                    callback="loadContactgroups"
                                    ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                    ng-model="post.Hostescalation.Contactgroup">
                            </select>
                            <div ng-repeat="error in errors.Hostescalation.Contactgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <fieldset>
                <legend class="font-sm">
                    <label><?php echo __('Hostescalation options'); ?></label>
                    <?php if (isset($validation_host_notification)): ?>
                        <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                    <?php endif; ?>
                </legend>
                <?php
                $escalation_options = [
                    'escalate_on_recovery'    => 'fa-square txt-color-greenLight',
                    'escalate_on_down'        => 'fa-square txt-color-redLight',
                    'escalate_on_unreachable' => 'fa-square txt-color-blueDark',
                ];
                foreach ($escalation_options as $escalation_option => $icon):?>
                    <div style="border-bottom:1px solid lightGray;">
                        <?php echo $this->Form->fancyCheckbox($escalation_option, [
                            'caption' => ucfirst(preg_replace('/escalate_on_/', '', $escalation_option)),
                            'icon'    => '<i class="fa ' . $icon . '"></i> ',
                        ]); ?>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
