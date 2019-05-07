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
            <i class="fa fa-cog fa-fw "></i>
            <?php echo __('Services'); ?>
            <span>>
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-cog"></i> </span>
        <h2><?php echo __('Create new service'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                <a back-button fallback-state='ServicesIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Service'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-magic"></i>
                                </span>
                                <h2><?php echo __('Basic configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Container'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <select
                                                        id="ServiceContainers"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="containers"
                                                        ng-options="container.key as container.value for container in containers"
                                                        ng-model="post.Service.container_id">
                                                </select>
                                                <div ng-show="post.Service.container_id < 1" class="warning-glow">
                                                    <?php echo __('Please select a container.'); ?>
                                                </div>
                                                <div ng-repeat="error in errors.container_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="form-group required"
                                             ng-class="{'has-error': errors.servicetemplate_id}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Service template'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <select
                                                        id="ServiceServicetemplateSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="servicetemplates"
                                                        ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                                        ng-model="post.Service.servicetemplate_id">
                                                </select>
                                                <div ng-show="post.Service.servicetemplate_id < 1" class="warning-glow">
                                                    <?php echo __('Please select a service template.'); ?>
                                                </div>
                                                <div ng-repeat="error in errors.servicetemplate_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Service name'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        id="ServiceName"
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Service.name"
                                                        ng-blur="runDnsLookup(true)">
                                                <div ng-repeat="error in errors.name">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>

                                                <div class="text-warning" ng-show="data.dnsServicenameNotFound">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <?php echo __('Could not resolve servicename.'); ?>
                                                </div>

                                                <div class="smart-form">
                                                    <label class="checkbox small-checkbox-label no-required">
                                                        <input type="checkbox" checked="checked"
                                                               ng-model="data.dnsLookUp">
                                                        <i class="checkbox-primary" style="margin-top: 7px;"></i>
                                                        <?php echo __('DNS lookup'); ?>
                                                    </label>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group required" ng-class="{'has-error': errors.address}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Service address'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        id="ServiceAddress"
                                                        class="form-control"
                                                        type="text"
                                                        placeholder="<?php echo __('IPv4/IPv6 address or FQDN'); ?>"
                                                        ng-model="post.Service.address"
                                                        ng-blur="runDnsLookup(false)">
                                                <div ng-repeat="error in errors.address">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>

                                                <div class="text-warning" ng-show="data.dnsAddressNotFound">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <?php echo __('Could not resolve address.'); ?>
                                                </div>

                                            </div>
                                        </div>

                                        <div ng-show="post.Service.servicetemplate_id">

                                            <div class="form-group" ng-class="{'has-error': errors.description}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Description'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input
                                                                class="form-control"
                                                                type="text"
                                                                ng-model="post.Service.description">

                                                        <template-diff ng-show="post.Service.servicetemplate_id"
                                                                       value="post.Service.description"
                                                                       template-value="servicetemplate.Servicetemplate.description"></template-diff>
                                                    </div>
                                                    <div ng-repeat="error in errors.description">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group"
                                                 ng-class="{'has-error': errors.servicegroups}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Service groups'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <div class="input-group" style="width: 100%;">
                                                        <select
                                                                id="ServicegroupsSelect"
                                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                                class="form-control"
                                                                chosen="servicegroups"
                                                                multiple
                                                                ng-options="servicegroup.key as servicegroup.value for servicegroup in servicegroups"
                                                                ng-model="post.Service.servicegroups._ids">
                                                        </select>
                                                        <template-diff ng-show="post.Service.servicetemplate_id"
                                                                       value="post.Service.servicegroups._ids"
                                                                       template-value="servicetemplate.Servicetemplate.servicegroups._ids"></template-diff>
                                                    </div>
                                                    <div ng-repeat="error in errors.servicegroups">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-class="{'has-error': errors.container_id}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Parent services'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <select
                                                            id="ParentServicesSelect"
                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="parentservices"
                                                            callback="loadParentServices"
                                                            multiple
                                                            ng-options="parentservice.key as parentservice.value for parentservice in parentservices"
                                                            ng-model="post.Service.parentservices._ids">
                                                    </select>
                                                    <div ng-repeat="error in errors.container_id">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-class="{'has-error': errors.tags}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Tags'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input
                                                                id="ServiceTagsInput"
                                                                class="form-control tagsinput"
                                                                type="text"
                                                                ng-model="post.Service.tags">
                                                        <template-diff ng-show="post.Service.servicetemplate_id"
                                                                       value="post.Service.tags"
                                                                       template-value="servicetemplate.Servicetemplate.tags"
                                                                       callback="restoreTemplateTags"></template-diff>
                                                    </div>
                                                    <div ng-repeat="error in errors.tags">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                    <div class="help-block">
                                                        <?php echo __('Press return to separate tags'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-class="{'has-error': errors.priority}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Priority'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-2">
                                                    <priority-directive priority="post.Service.priority"
                                                                        callback="setPriority"></priority-directive>
                                                    <template-diff-button ng-show="post.Service.servicetemplate_id"
                                                                          value="post.Service.priority"
                                                                          template-value="servicetemplate.Servicetemplate.priority">
                                                    </template-diff-button>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-class="{'has-error': errors.satellite_id}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Satellite'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <select
                                                            id="SatellitesSelect"
                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="satellites"
                                                            ng-options="satellite.key as satellite.value for satellite in satellites"
                                                            ng-model="post.Service.satellite_id">
                                                    </select>
                                                    <div ng-repeat="error in errors.satellite_id">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" ng-show="post.Service.servicetemplate_id">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-terminal"></i>
                                </span>
                                <h2><?php echo __('Check configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.check_period_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check period'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="CheckPeriodSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="checkperiods"
                                                        ng-options="checkperiod.key as checkperiod.value for checkperiod in checkperiods"
                                                        ng-model="post.Service.check_period_id">
                                                </select>
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.check_period_id"
                                                               template-value="servicetemplate.Servicetemplate.check_period_id"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.check_period_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"
                                         ng-class="{'has-error': errors.active_checks_enabled}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="activeChecksEnabled">
                                            <?php echo __('Enable active checks'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-1 smart-form">
                                            <label class="checkbox no-required no-padding no-margin label-default-off">
                                                <input type="checkbox" name="checkbox"
                                                       id="activeChecksEnabled"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Service.active_checks_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="padding-left-20">
                                                <template-diff-button ng-show="post.Service.servicetemplate_id"
                                                                      value="post.Service.active_checks_enabled"
                                                                      template-value="servicetemplate.Servicetemplate.active_checks_enabled">
                                                </template-diff-button>
                                            </div>
                                        </div>
                                        <div class="col col-xs-12 col-md-offset-2 help-block">
                                            <?php echo __('If disabled the check command won\'t be executed. This is useful if an external program sends state data to openITCOCKPIT.'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.command_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check command'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="ServiceCheckCommandSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="commands"
                                                        ng-options="command.key as command.value for command in commands"
                                                        ng-model="post.Service.command_id">
                                                </select>
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.command_id"
                                                               template-value="servicetemplate.Servicetemplate.command_id"></template-diff>
                                            </div>
                                            <div class="help-block" ng-hide="post.Service.active_checks_enabled">
                                                <?php echo __('Due to active checking is disabled, this command will only be used as freshness check command.'); ?>
                                            </div>
                                            <div ng-repeat="error in errors.command_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.servicecommandargumentvalues}"
                                         ng-repeat="servicecommandargumentvalue in post.Service.servicecommandargumentvalues">
                                        <label class="col-xs-12 col-lg-offset-2 col-lg-2 control-label text-primary">
                                            {{servicecommandargumentvalue.commandargument.human_name}}
                                        </label>
                                        <div class="col-xs-12 col-lg-8">
                                            <div class="input-group">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="servicecommandargumentvalue.value">
                                                <template-diff
                                                        value="servicecommandargumentvalue.value"
                                                        template-value="servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues[$index].value"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.servicecommandargumentvalues">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                            <div class="help-block">
                                                {{servicecommandargumentvalue.commandargument.name}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-show="post.Service.command_id > 0 && post.Service.servicecommandargumentvalues.length == 0">
                                        <div class="col-xs-12 col-lg-offset-2 text-info">
                                            <i class="fa fa-info-circle"></i>
                                            <?php echo __('This command does not have any parameters.'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.check_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check interval'); ?>
                                        </label>
                                        <interval-input-with-differ-directive
                                                template-id="post.Service.servicetemplate_id"
                                                interval="post.Service.check_interval"
                                                template-value="servicetemplate.Servicetemplate.check_interval"></interval-input-with-differ-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.check_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.retry_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Retry interval'); ?>
                                        </label>
                                        <interval-input-with-differ-directive
                                                template-id="post.Service.servicetemplate_id"
                                                interval="post.Service.retry_interval"
                                                template-value="servicetemplate.Servicetemplate.retry_interval"></interval-input-with-differ-directive>

                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.retry_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.max_check_attempts}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Max. number of check attempts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-7">
                                            <div class="btn-group">
                                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                                    <button
                                                            type="button"
                                                            class="btn btn-default"
                                                            ng-click="post.Service.max_check_attempts = <?php echo h($i) ?>"
                                                            ng-class="{'active': post.Service.max_check_attempts == <?php echo h($i); ?>}">
                                                        <?php echo h($i); ?>
                                                    </button>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <div class="input-group" style="width: 100%;">
                                                <input
                                                        class="form-control"
                                                        type="number"
                                                        min="0"
                                                        ng-model="post.Service.max_check_attempts">
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.max_check_attempts"
                                                               template-value="servicetemplate.Servicetemplate.max_check_attempts"></template-diff>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-offset-2 col-lg-12">
                                            <div class="help-block">
                                                <?php echo __('Number of failed attempts before the service will switch into hard state.'); ?>
                                            </div>
                                            <div class="help-block">
                                                <?php echo __('Worst case time delay until notification command gets executed after state hits a non ok state: '); ?>
                                                <human-time-directive
                                                        seconds="(post.Service.check_interval + (post.Service.max_check_attempts -1) * post.Service.retry_interval)"></human-time-directive>
                                            </div>
                                            <div ng-repeat="error in errors.max_check_attempts">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="post.Service.servicetemplate_id">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-envelope-open-o"></i>
                                </span>
                                <h2><?php echo __('Notification configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.notify_period_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notification period'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="NotifyPeriodSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="timeperiods"
                                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                        ng-model="post.Service.notify_period_id">
                                                </select>
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.notify_period_id"
                                                               template-value="servicetemplate.Servicetemplate.notify_period_id"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.notify_period_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.notification_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notification interval'); ?>
                                        </label>
                                        <interval-input-with-differ-directive
                                                template-id="post.Service.servicetemplate_id"
                                                interval="post.Service.notification_interval"
                                                template-value="servicetemplate.Servicetemplate.notification_interval"></interval-input-with-differ-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.notification_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.contacts}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Contacts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%">
                                                <select
                                                        id="ContactsPeriodSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="contacts"
                                                        multiple
                                                        ng-options="contact.key as contact.value for contact in contacts"
                                                        ng-model="post.Service.contacts._ids">
                                                </select>
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.contacts._ids"
                                                               template-value="servicetemplate.Servicetemplate.contacts._ids"></template-diff>
                                            </div>
                                        </div>
                                        <div ng-repeat="error in errors.contacts">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group"
                                     ng-class="{'has-error': errors.contactgroups}">
                                    <label class="col-xs-12 col-lg-2 control-label">
                                        <?php echo __('Contact groups'); ?>
                                    </label>
                                    <div class="col-xs-12 col-lg-10">
                                        <div class="input-group" style="width: 100%;">
                                            <select
                                                    id="ContactgroupsSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="contactgroups"
                                                    multiple
                                                    ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                                    ng-model="post.Service.contactgroups._ids">
                                            </select>
                                            <template-diff ng-show="post.Service.servicetemplate_id"
                                                           value="post.Service.contactgroups._ids"
                                                           template-value="servicetemplate.Servicetemplate.contactgroups._ids"></template-diff>
                                        </div>
                                        <div ng-repeat="error in errors.contactgroups">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>


                                <?php
                                $serviceOptions = [
                                    [
                                        'field' => 'notify_on_recovery',
                                        'class' => 'success',
                                        'text'  => __('Recovery')
                                    ],
                                    [
                                        'field' => 'notify_on_warning',
                                        'class' => 'warning',
                                        'text'  => __('Warning')
                                    ],
                                    [
                                        'field' => 'notify_on_critical',
                                        'class' => 'danger',
                                        'text'  => __('Critical')
                                    ],
                                    [
                                        'field' => 'notify_on_unknown',
                                        'class' => 'default',
                                        'text'  => __('Unknown')
                                    ],
                                    [
                                        'field' => 'notify_on_flapping',
                                        'class' => 'primary',
                                        'text'  => __('Flapping')
                                    ],
                                    [
                                        'field' => 'notify_on_downtime',
                                        'class' => 'primary',
                                        'text'  => __('Downtime')
                                    ],
                                ];
                                ?>
                                <fieldset>
                                    <legend class="font-sm"
                                            ng-class="{'has-error-no-form': errors.notify_on_recovery}">
                                        <div class="required">
                                            <label>
                                                <?php echo __('Service notification options'); ?>
                                            </label>

                                            <div ng-repeat="error in errors.notify_on_recovery">
                                                <div class="text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </legend>
                                    <ul class="config-flex-inner">
                                        <?php foreach ($serviceOptions as $serviceOption): ?>
                                            <li>
                                                <div class="margin-bottom-0"
                                                     ng-class="{'has-error': errors.<?php echo $serviceOption['field']; ?>}">
                                                    <label for="<?php echo $serviceOption['field']; ?>"
                                                           class="col col-md-7 control-label padding-top-0">
                                                        <span class="label label-<?php echo $serviceOption['class']; ?> notify-label-small">
                                                            <?php echo $serviceOption['text']; ?>
                                                        </span>
                                                    </label>
                                                    <div class="col-md-2 smart-form">
                                                        <label class="checkbox small-checkbox-label no-required">
                                                            <input type="checkbox" name="checkbox"
                                                                   ng-true-value="1"
                                                                   ng-false-value="0"
                                                                   id="<?php echo $serviceOption['field']; ?>"
                                                                   ng-model="post.Service.<?php echo $serviceOption['field']; ?>">
                                                            <i class="checkbox-<?php echo $serviceOption['class']; ?>"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                <template-diff-button ng-show="post.Service.servicetemplate_id"
                                                                      value="post.Service.<?php echo $serviceOption['field']; ?>"
                                                                      template-value="servicetemplate.Servicetemplate.<?php echo $serviceOption['field']; ?>">
                                                </template-diff-button>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="post.Service.servicetemplate_id">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-wrench"></i>
                                </span>
                                <h2><?php echo __('Misc. configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">

                                    <div class="form-group" ng-class="{'has-error': errors.service_url}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Service URL'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <input
                                                        class="form-control"
                                                        placeholder="https://issues.example.org?host=$HOSTNAME$&service=$SERVICEDESC$"
                                                        type="text"
                                                        ng-model="post.Service.service_url">
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.service_url"
                                                               template-value="servicetemplate.Servicetemplate.service_url"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.service_url">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                            <div class="help-block">
                                                <?php echo __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$, $HOSTADDRESS$, $SERVICEDESC$, $SERVICEDISPLAYNAME$ will be replaced'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error': errors.notes}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notes'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Service.notes">
                                                <template-diff ng-show="post.Service.servicetemplate_id"
                                                               value="post.Service.notes"
                                                               template-value="servicetemplate.Servicetemplate.notes"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.notes">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $serviceFlapOptions = [
                                        [
                                            'field' => 'flap_detection_on_ok',
                                            'class' => 'success',
                                            'text'  => __('Recovery')
                                        ],
                                        [
                                            'field' => 'flap_detection_on_warning',
                                            'class' => 'warning',
                                            'text'  => __('Warning')
                                        ],
                                        [
                                            'field' => 'flap_detection_on_critical',
                                            'class' => 'danger',
                                            'text'  => __('Critical')
                                        ],
                                        [
                                            'field' => 'flap_detection_on_unknown',
                                            'class' => 'default',
                                            'text'  => __('Unknown')
                                        ]
                                    ];
                                    ?>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.flap_detection_enabled}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="flapDetectionEnabled">
                                            <?php echo __('Flap detection enabled'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-1 smart-form">
                                            <label class="checkbox no-required no-padding no-margin label-default-off">
                                                <input type="checkbox" name="checkbox"
                                                       id="flapDetectionEnabled"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Service.flap_detection_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="padding-left-20">
                                                <template-diff-button ng-show="post.Service.servicetemplate_id"
                                                                      value="post.Service.flap_detection_enabled"
                                                                      template-value="servicetemplate.Servicetemplate.flap_detection_enabled">
                                                </template-diff-button>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset ng-show="post.Service.flap_detection_enabled">
                                        <legend class="font-sm"
                                                ng-class="{'has-error-no-form': errors.flap_detection_on_up}">
                                            <div ng-class="{'required':post.Service.flap_detection_enabled}">
                                                <label>
                                                    <?php echo __('Flap detection options'); ?>
                                                </label>

                                                <div ng-repeat="error in errors.flap_detection_on_up">
                                                    <div class="text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </legend>
                                        <ul class="config-flex-inner">
                                            <?php foreach ($serviceFlapOptions as $serviceFalpOption): ?>
                                                <li>
                                                    <div class="margin-bottom-0"
                                                         ng-class="{'has-error': errors.<?php echo $serviceFalpOption['field']; ?>}">

                                                        <label for="<?php echo $serviceFalpOption['field']; ?>"
                                                               class="col col-md-7 control-label padding-top-0">
                                                                <span class="label label-<?php echo $serviceFalpOption['class']; ?> notify-label-small">
                                                                    <?php echo $serviceFalpOption['text']; ?>
                                                                </span>
                                                        </label>

                                                        <div class="col-md-2 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       ng-true-value="1"
                                                                       ng-false-value="0"
                                                                       ng-disabled="!post.Service.flap_detection_enabled"
                                                                       id="<?php echo $serviceFalpOption['field']; ?>"
                                                                       ng-model="post.Service.<?php echo $serviceFalpOption['field']; ?>">
                                                                <i class="checkbox-<?php echo $serviceFalpOption['class']; ?>"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <template-diff-button ng-show="post.Service.servicetemplate_id"
                                                                          value="post.Service.<?php echo $serviceFalpOption['field']; ?>"
                                                                          template-value="servicetemplate.Servicetemplate.<?php echo $serviceFalpOption['field']; ?>">
                                                    </template-diff-button>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </fieldset>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="post.Service.servicetemplate_id">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-usd"></i>
                                </span>
                                <h2><?php echo __('Service macro configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body"
                                     ng-class="{'has-error-no-form': errors.customvariables_unique}">

                                    <div class="row">
                                        <div ng-repeat="error in errors.customvariables_unique">
                                            <div class=" col-xs-12 text-danger">{{ error }}</div>
                                        </div>
                                    </div>

                                    <div class="row"
                                         ng-repeat="customvariable in post.Service.customvariables">
                                        <macros-directive macro="customvariable"
                                                          macro-name="'<?php echo __('HOST'); ?>'"
                                                          index="$index"
                                                          callback="deleteMacroCallback"
                                                          errors="getMacroErrors($index)"
                                        ></macros-directive>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-9 col-md-offset-2 padding-top-10 text-right">
                                            <button type="button" class="btn btn-success btn-sm"
                                                    ng-click="addMacro()">
                                                <i class="fa fa-plus"></i>
                                                <?php echo __('Add new macro'); ?>
                                            </button>
                                        </div>

                                        <div class="col-xs-12 padding-top-10 text-info"
                                             ng-show="post.Service.customvariables.length > 0">
                                            <i class="fa fa-info-circle"></i>
                                            <?php echo __('Macros in green color are inherited from the service template.'); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">

                            <label>
                                <input type="checkbox" ng-model="data.createAnother">
                                <?php echo _('Create another'); ?>
                            </label>


                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo __('Create service'); ?>
                                </button>
                                <button type="button"
                                        class="btn btn-primary dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu pull-right" id="menuHack-sdhf98j23jr">
                                    <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                                        <li>
                                            <a ui-sref="ServicesAdd">
                                                <i class="fa fa fa-gear"></i>
                                                <?php echo __('Save and create service'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a ui-sref="MkModuleMkdiscovery">
                                            <i class="fa fa fa-share-alt"></i>
                                            <?php echo __('Save and run Check_MK discovery'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <a back-button fallback-state='ServicesIndex' class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





















<!-- OLD CODE --->



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

$flapDetection_settings = [
    'flap_detection_on_ok'       => 'fa-square txt-color-greenLight',
    'flap_detection_on_warning'  => 'fa-square txt-color-orange',
    'flap_detection_on_unknown'  => 'fa-square txt-color-blueDark',
    'flap_detection_on_critical' => 'fa-square txt-color-redLight',
];
$notification_settings = [
    'notify_on_recovery' => 'fa-square txt-color-greenLight',
    'notify_on_warning'  => 'fa-square txt-color-orange',
    'notify_on_unknown'  => 'fa-square txt-color-blueDark',
    'notify_on_critical' => 'fa-square txt-color-redLight',
    'notify_on_flapping' => 'fa-random',
    'notify_on_downtime' => 'fa-clock-o',
];
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service'); ?>
            </span>

            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>

        <h2><?php echo __('Add Service'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
            <li class="active">
                <a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-desktop"></i> <span
                            class="hidden-mobile hidden-tablet"> <?php echo __('Basic configuration'); ?></span> </a>
            </li>
            <li class="">
                <a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-terminal"></i> <span
                            class="hidden-mobile hidden-tablet"> <?php echo __('Expert settings'); ?> </span></a>
            </li>

            <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'service', 'tabLink'); ?>
        </ul>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Service', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <!-- basic settings -->
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active in">
                            <span class="note"><?php echo __('Basic configuration'); ?>:</span>
                            <?php

                            echo $this->Form->input('Service.service_id', [
                                    'options'          => [],
                                    'data-placeholder' => __('Please, start typing...'),
                                    'class'            => 'chosen,',
                                    'style'            => 'width:100%',
                                    'label'            => ['text' => __('Service'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'required'         => true,
                                    'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                    'div'              => [
                                        'class' => 'form-group required',
                                    ],
                                ]
                            );


                            echo $this->Form->input('Service.servicetemplate_id', [
                                'options'          => $this->Html->chosenPlaceholder($servicetemplates),
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => ['text' => __('Servicetemplate'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'style'            => 'width: 100%',
                            ]);
                            echo $this->Form->input('Service.name', [
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'label'     => ['text' => __('Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                            ]);
                            echo $this->Form->input('Service.description', [
                                'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Service.Servicegroup', [
                                'options'          => $this->Html->chosenPlaceholder($servicegroups),
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => ['text' => __('Servicegroup'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'style'            => 'width: 100%',
                                'multiple'         => true,
                            ]);
                            echo $this->Form->input('Service.notes', [
                                'label'     => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Service.service_url', [
                                'label'     => ['text' => __('Service URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'      => __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$, $HOSTADDRESS$, $SERVICEDESC$, $SERVICEDISPLAYNAME$ will be replaced'),
                            ]);
                            ?>
                            <div class="form-group <?php echo (isset($validationErrors['priority'])) ? 'has-error' : '' ?>">
                                <label class="col col-md-1 control-label text-left"><?php echo __('Priority'); ?></label>

                                <div class="col col-xs-10 col-md-10 col-lg-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php //The smallest priority is 1 at the moment
                                        $priority = $this->CustomValidationErrors->refill('priority', 1);
                                        ?>
                                        <?php for ($i = 5; $i > 0; $i--): ?>
                                            <input type="radio" <?php echo ($priority == $i) ? 'checked="checked"' : '' ?>
                                                   id="Servicestars-rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
                                                   name="data[Service][priority]">
                                            <label for="Servicestars-rating-<?php echo $i; ?>"><i
                                                        class="fa fa-fire"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (isset($validationErrors['priority'])): ?>
                                        <br/><br/>
                                        <span class="help-block txt-color-red"><?php echo $validationErrors['priority']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- key words -->
                            <?php echo $this->Form->input('tags', [
                                'label'     => ['text' => __('Tags'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'     => 'form-control tagsinput',
                                'data-role' => 'tagsinput',
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>

                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php echo $this->Form->input('Service.notify_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Notification period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen',
                                'style'            => 'width: 100%;',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <br/>
                            <div class="form-group form-group-slider required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="ServiceNotificationinterval"><?php echo __('Notification interval'); ?></label>

                                <div class="col col-md-7 hidden-mobile">
                                    <input
                                            type="text"
                                            id="ServiceNotificationinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-success"
                                            name="data[Service][notification_interval]"
                                            data-slider-min="0"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('notification_interval', 0); ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceNotificationinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceNotificationinterval"
                                           human="#ServiceNotificationinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('notification_interval', 0); ?>"
                                           slider-for="ServiceNotificationinterval" class="form-control slider-input"
                                           name="data[Service][notification_interval]">
                                    <span class="note"
                                          id="ServiceNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('notification_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="padding-left-20 <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <?php
                                echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', [
                                    'style' => 'margin-left: 15px;',
                                ]);

                                foreach ($notification_settings as $notification_setting => $icon): ?>
                                    <div class="form-group no-padding" style="border-bottom:1px solid lightGray;">
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption'          => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'captionGridClass' => 'col col-xs-2',
                                            'icon'             => '<i class="fa ' . $icon . '"></i> ',
                                            'class'            => 'onoffswitch-checkbox notification_control',
                                            'checked'          => $this->CustomValidationErrors->refill($notification_setting, false),
                                            'wrapGridClass'    => 'col col-xs-1',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <br/>


                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('process_performance_data', [
                                    'caption'          => __('Enable graph'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->CustomValidationErrors->refill('process_performance_data', false),
                                    'icon'             => '<i class="fa fa-area-chart"></i> ',
                                ]); ?>
                            </div>

                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                    'caption'          => __('Enable active checks'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->CustomValidationErrors->refill('active_checks_enabled', false),
                                    'icon'             => '<i class="fa fa-sign-in"></i> ',
                                ]); ?>
                            </div>

                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <!-- contacts & groups -->
                            <?php echo $this->Form->input('Service.Contact', [
                                'options'   => $contacts,
                                'selected'  => $this->CustomValidationErrors->refill('Contact', [], ['Model' => 'Contact']),
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>

                            <?php echo $this->Form->input('Service.Contactgroup', [
                                'options'   => $contactgroups,
                                'selected'  => $this->CustomValidationErrors->refill('Contactgroup', [], ['Model' => 'Contactgroup']),
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                        </div>
                        <!-- second page -->
                        <!-- expert settings -->
                        <div id="tab2" class="tab-pane fade">
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php echo $this->Form->input('Service.command_id', [
                                'options'          => $this->Html->chosenPlaceholder($commands),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Check command'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <div id="CheckCommandArgs">
                                <!-- Contact gets loaded by AJAX -->
                            </div>
                            <?php echo $this->Form->input('Service.check_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <?php echo $this->Form->input('Service.max_check_attempts', [
                                'label'     => ['text' => __('Max. number of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Check interval'); ?></label>

                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="ServiceCheckinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-success"
                                            name="data[Service][check_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('check_interval', 0); ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input
                                            type="number"
                                            id="_ServiceCheckinterval"
                                            human="#ServiceCheckinterval_human"
                                            value="<?php echo $this->CustomValidationErrors->refill('check_interval', 0); ?>"
                                            slider-for="ServiceCheckinterval"
                                            class="form-control slider-input"
                                            name="data[Service][check_interval]">
                                    <span class="note"
                                          id="ServiceCheckinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('check_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Retry interval'); ?></label>

                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="ServiceRetryinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-primary"
                                            name="data[Service][retry_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('retry_interval', 0); ?>"
                                            data-slider-selection="before"
                                            data-slider-handle="round"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceRetryinterval" human="#ServiceRetryinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('retry_interval', 0); ?>"
                                           slider-for="ServiceRetryinterval"
                                           class="form-control slider-input"
                                           name="data[Service][retry_interval]">
                                    <span class="note"
                                          id="ServiceRetryinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('retry_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>

                            <div class="form-group">
                                <?php echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                    'caption'          => __('Flap detection'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 text-left',
                                    'captionClass'     => 'control-label',
                                    'checked'          => $this->CustomValidationErrors->refill('flap_detection_enabled', false),
                                ]); ?>
                            </div>

                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_service_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_service_notification; ?></span>
                                <?php endif; ?>
                            </legend>

                            <br/>

                            <div class="<?php echo $this->CustomValidationErrors->errorClass('flap_detection_on_up'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('flap_detection_on_up', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($flapDetection_settings as $flapDetection_setting => $icon): ?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                            'caption'          => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                            'icon'             => '<i class="fa ' . $icon . '"></i> ',
                                            'class'            => 'onoffswitch-checkbox flapdetection_control',
                                            'checked'          => $this->CustomValidationErrors->refill($flapDetection_setting, false),
                                            'wrapGridClass'    => 'col col-xs-1',
                                            'captionGridClass' => 'col col-xs-2',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <br/>

                            <legend class="font-sm"></legend>

                            <div class="form-group no-padding">
                                <?php echo $this->Form->fancyCheckbox('Service.is_volatile', [
                                    'caption'          => __(ucfirst('Status volatile')),
                                    'icon'             => '<i class="fa fa-asterisk"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'checked'          => $this->CustomValidationErrors->refill('is_volatile', false),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-md-2',
                                ]); ?>
                            </div>
                            <div class="form-group no-padding">
                                <?php echo $this->Form->fancyCheckbox('Service.freshness_checks_enabled', [
                                    'caption'          => __('Freshness checks enabled'),
                                    'icon'             => '<i class="fa fa-foursquare"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-md-2',
                                ]); ?>
                            </div>

                            <?php
                            echo $this->Form->input('Service.freshness_threshold', [
                                'label'     => [
                                    'text'  => __('<i class="fa fa-clock-o"></i> Freshness threshold (seconds)'),
                                    'class' => 'col col-md-2 control-label text-left',
                                ],
                                'class'     => 'col col-md-12',
                                'wrapInput' => 'col col-xs-8',
                            ]);
                            ?>
                            <!--<br>-->
                            <?php echo $this->Form->input('Service.eventhandler_command_id', [
                                'options'               => $this->Html->chosenPlaceholder($eventhandlers),
                                'data-placeholder'      => __('Please select...'),
                                'label'                 => ['text' => __('Eventhandler'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'                 => 'chosen',
                                'style'                 => 'width:100%;',
                                'wrapInput'             => 'col col-xs-10 col-md-10 col-lg-10',
                                'allow_single_deselect' => 'true',
                            ]); ?>
                            <div id="EventhandlerCommandArgs"></div>
                            <br>

                            <!-- Service macro settings -->
                            <div class="service-macro-settings">
                                <span class="note pull-left"><?php echo __('Service macro settings'); ?>:</span>
                                <br class="clearfix"/>
                                <br/>
                                <?php if (isset($customVariableValidationError)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                                <?php endif; ?>
                                <?php if (isset($customVariableValidationErrorValue)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                                <?php endif;
                                $counter = 0;
                                $this->CustomVariables->setup($macrotype = 'SERVICE', OBJECT_SERVICE);
                                echo $this->CustomVariables->__startWrap();
                                foreach ($Customvariable as $servicemacro):
                                    echo $this->CustomVariables->html($counter, [
                                        'name'  => $servicemacro['name'],
                                        'value' => $servicemacro['value'],
                                    ]);
                                    $counter++;
                                endforeach;
                                echo $this->CustomVariables->__endWrap();
                                echo $this->CustomVariables->addButton();
                                ?>
                                <br/>
                            </div>
                        </div>  <!-- close tab2 -->

                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'service'); ?>

                    </div><!-- close tab-content -->
                </div><!-- close col -->
            </div><!-- close row -->
            <br/>
            <?php echo $this->Form->formActions(); ?>

        </div><!-- close widget body -->
    </div>
</div><!-- end jarviswidget -->
