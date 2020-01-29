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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Hosts'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2>
            <?php echo __('Edit host:'); ?>
            {{post.Host.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <a back-button fallback-state='HostsIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            UUID: {{post.Host.uuid}}
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Host'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
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
                                                        id="HostContainers"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="containers"
                                                        ng-options="container.key as container.value for container in containers"
                                                        ng-disabled="!data.isPrimaryContainerChangeable"
                                                        ng-model="post.Host.container_id">
                                                </select>

                                                <div ng-show="post.Host.container_id < 1" class="warning-glow">
                                                    <?php echo __('Please select a container.'); ?>
                                                </div>
                                                <div ng-show="post.Host.container_id === 1" class="help-block">
                                                    <?php echo __('Objects in /root can\'t be moved to other containers'); ?>
                                                </div>
                                                <div ng-repeat="error in errors.container_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($this->Acl->hasPermission('sharing', 'hosts')): ?>
                                            <div
                                                    class="form-group"
                                                    ng-show="data.allowSharing"
                                                    ng-class="{'has-error': errors.container_id}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Shared containers'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <select
                                                            id="HostSharedContainers"
                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="sharingContainers"
                                                            multiple
                                                            ng-options="container.key as container.value for container in sharingContainers"
                                                            ng-disabled="data.allowSharing === false"
                                                            ng-model="post.Host.hosts_to_containers_sharing._ids">
                                                    </select>
                                                    <div ng-repeat="error in errors.container_id">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group required"
                                             ng-class="{'has-error': errors.hosttemplate_id}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                                    <a ui-sref="HosttemplatesEdit({id:post.Host.hosttemplate_id})">
                                                        <?php echo __('Host template'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo __('Host template'); ?>
                                                <?php endif; ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <select
                                                        id="HostHosttemplateSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="hosttemplates"
                                                        ng-options="hosttemplate.key as hosttemplate.value for hosttemplate in hosttemplates"
                                                        ng-model="post.Host.hosttemplate_id">
                                                </select>
                                                <div ng-show="post.Host.hosttemplate_id < 1" class="warning-glow">
                                                    <?php echo __('Please select a host template.'); ?>
                                                </div>
                                                <div ng-repeat="error in errors.hosttemplate_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Host name'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        id="HostName"
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Host.name"
                                                        ng-blur="runDnsLookup(true)">
                                                <div ng-repeat="error in errors.name">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>

                                                <div class="text-warning" ng-show="data.dnsHostnameNotFound">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <?php echo __('Could not resolve hostname.'); ?>
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
                                                <?php echo __('Host address'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        id="HostAddress"
                                                        class="form-control"
                                                        type="text"
                                                        placeholder="<?php echo __('IPv4/IPv6 address or FQDN'); ?>"
                                                        ng-model="post.Host.address"
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

                                        <div ng-show="post.Host.hosttemplate_id">

                                            <div class="form-group" ng-class="{'has-error': errors.description}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Description'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <div class="input-group" style="width: 100%;">
                                                        <input
                                                                class="form-control"
                                                                type="text"
                                                                ng-model="post.Host.description">

                                                        <template-diff ng-show="post.Host.hosttemplate_id"
                                                                       value="post.Host.description"
                                                                       template-value="hosttemplate.Hosttemplate.description"></template-diff>
                                                    </div>
                                                    <div ng-repeat="error in errors.description">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group"
                                                 ng-class="{'has-error': errors.hostgroups}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Host groups'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <div class="input-group" style="width: 100%;">
                                                        <select
                                                                id="HostgroupsSelect"
                                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                                class="form-control"
                                                                chosen="hostgroups"
                                                                multiple
                                                                ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                                                ng-model="post.Host.hostgroups._ids">
                                                        </select>
                                                        <template-diff ng-show="post.Host.hosttemplate_id"
                                                                       value="post.Host.hostgroups._ids"
                                                                       template-value="hosttemplate.Hosttemplate.hostgroups._ids"></template-diff>
                                                    </div>
                                                    <div ng-repeat="error in errors.hostgroups">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-class="{'has-error': errors.container_id}">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <?php echo __('Parent hosts'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10">
                                                    <select
                                                            id="ParentHostsSelect"
                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="parenthosts"
                                                            callback="loadParentHosts"
                                                            multiple
                                                            ng-options="parenthost.key as parenthost.value for parenthost in parenthosts"
                                                            ng-model="post.Host.parenthosts._ids">
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
                                                                id="HostTagsInput"
                                                                class="form-control tagsinput"
                                                                type="text"
                                                                ng-model="post.Host.tags">
                                                        <template-diff ng-show="post.Host.hosttemplate_id"
                                                                       value="post.Host.tags"
                                                                       template-value="hosttemplate.Hosttemplate.tags"
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
                                                    <priority-directive priority="post.Host.priority"
                                                                        callback="setPriority"></priority-directive>
                                                    <template-diff-button ng-show="post.Host.hosttemplate_id"
                                                                          value="post.Host.priority"
                                                                          template-value="hosttemplate.Hosttemplate.priority">
                                                    </template-diff-button>
                                                </div>
                                            </div>

                                            <?php if (\Cake\Core\Plugin::isLoaded('DistributeModule')): ?>
                                                <div class="form-group" ng-class="{'has-error': errors.satellite_id}">
                                                    <label class="col-xs-12 col-lg-2 control-label">
                                                        <?php if ($this->Acl->hasPermission('edit', 'satellites', 'DistributeModule')): ?>
                                                            <a ui-sref="SatellitesEdit({id:post.Host.satellite_id})"
                                                               ng-if="post.Host.satellite_id > 0">
                                                                <?php echo __('Satellite'); ?>
                                                            </a>
                                                            <span ng-if="post.Host.satellite_id == 0"><?php echo __('Satellite'); ?></span>
                                                        <?php else: ?>
                                                            <?php echo __('Satellite'); ?>
                                                        <?php endif; ?>
                                                    </label>
                                                    <div class="col-xs-12 col-lg-10">
                                                        <select
                                                                id="SatellitesSelect"
                                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                                class="form-control"
                                                                chosen="satellites"
                                                                ng-options="satellite.key as satellite.value for satellite in satellites"
                                                                ng-model="post.Host.satellite_id">
                                                        </select>
                                                        <div ng-repeat="error in errors.satellite_id">
                                                            <div class="help-block text-danger">{{ error }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" ng-show="post.Host.hosttemplate_id">
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
                                            <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                                <a ui-sref="TimeperiodsEdit({id:post.Host.check_period_id})">
                                                    <?php echo __('Check period'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Check period'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="CheckPeriodSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="checkperiods"
                                                        ng-options="checkperiod.key as checkperiod.value for checkperiod in checkperiods"
                                                        ng-model="post.Host.check_period_id">
                                                </select>
                                                <template-diff ng-show="post.Host.hosttemplate_id"
                                                               value="post.Host.check_period_id"
                                                               template-value="hosttemplate.Hosttemplate.check_period_id"></template-diff>
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
                                                       ng-model="post.Host.active_checks_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="padding-left-20">
                                                <template-diff-button ng-show="post.Host.hosttemplate_id"
                                                                      value="post.Host.active_checks_enabled"
                                                                      template-value="hosttemplate.Hosttemplate.active_checks_enabled">
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
                                            <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                <a ui-sref="CommandsEdit({id:post.Host.command_id})">
                                                    <?php echo __('Check command'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Check command'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="HostCheckCommandSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="commands"
                                                        ng-options="command.key as command.value for command in commands"
                                                        ng-model="post.Host.command_id">
                                                </select>
                                                <template-diff ng-show="post.Host.hosttemplate_id"
                                                               value="post.Host.command_id"
                                                               template-value="hosttemplate.Hosttemplate.command_id"></template-diff>
                                            </div>
                                            <div class="help-block" ng-hide="post.Host.active_checks_enabled">
                                                <?php echo __('Due to active checking is disabled, this command will only be used as freshness check command.'); ?>
                                            </div>
                                            <div ng-repeat="error in errors.command_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.hostcommandargumentvalues}"
                                         ng-repeat="hostcommandargumentvalue in post.Host.hostcommandargumentvalues">
                                        <label class="col-xs-12 col-lg-offset-2 col-lg-2 control-label text-primary">
                                            {{hostcommandargumentvalue.commandargument.human_name}}
                                        </label>
                                        <div class="col-xs-12 col-lg-8">
                                            <div class="input-group">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="hostcommandargumentvalue.value">
                                                <template-diff
                                                        value="hostcommandargumentvalue.value"
                                                        template-value="hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues[$index].value"></template-diff>
                                            </div>
                                            <div ng-repeat="error in errors.hostcommandargumentvalues">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                            <div class="help-block">
                                                {{hostcommandargumentvalue.commandargument.name}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-show="post.Host.command_id > 0 && post.Host.hostcommandargumentvalues.length == 0">
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
                                                template-id="post.Host.hosttemplate_id"
                                                interval="post.Host.check_interval"
                                                template-value="hosttemplate.Hosttemplate.check_interval"></interval-input-with-differ-directive>
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
                                                template-id="post.Host.hosttemplate_id"
                                                interval="post.Host.retry_interval"
                                                template-value="hosttemplate.Hosttemplate.retry_interval"></interval-input-with-differ-directive>

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
                                                            ng-click="post.Host.max_check_attempts = <?php echo h($i) ?>"
                                                            ng-class="{'active': post.Host.max_check_attempts == <?php echo h($i); ?>}">
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
                                                        ng-model="post.Host.max_check_attempts">
                                                <template-diff ng-show="post.Host.hosttemplate_id"
                                                               value="post.Host.max_check_attempts"
                                                               template-value="hosttemplate.Hosttemplate.max_check_attempts"></template-diff>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-offset-2 col-lg-12">
                                            <div class="help-block">
                                                <?php echo __('Number of failed attempts before the host will switch into hard state.'); ?>
                                            </div>
                                            <div class="help-block">
                                                <?php echo __('Worst case time delay until notification command gets executed after state hits a non ok state: '); ?>
                                                <human-time-directive
                                                        seconds="(post.Host.check_interval + (post.Host.max_check_attempts -1) * post.Host.retry_interval)"></human-time-directive>
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

                <div class="row" ng-show="post.Host.hosttemplate_id">
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
                                            <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                                <a ui-sref="TimeperiodsEdit({id:post.Host.notify_period_id})">
                                                    <?php echo __('Notification period'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Notification period'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <div class="input-group" style="width: 100%;">
                                                <select
                                                        id="NotifyPeriodSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="timeperiods"
                                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                        ng-model="post.Host.notify_period_id">
                                                </select>
                                                <template-diff ng-show="post.Host.hosttemplate_id"
                                                               value="post.Host.notify_period_id"
                                                               template-value="hosttemplate.Hosttemplate.notify_period_id"></template-diff>
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
                                                template-id="post.Host.hosttemplate_id"
                                                interval="post.Host.notification_interval"
                                                template-value="hosttemplate.Hosttemplate.notification_interval"></interval-input-with-differ-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.notification_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"
                                         ng-show="data.areContactsInheritedFromHosttemplate">
                                        <label class="col-xs-12 col-lg-2 control-label" for="resetContacts">
                                            <?php echo __('Disable inheritance'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-1 smart-form">
                                            <label class="checkbox no-required no-padding no-margin label-default-off">
                                                <input type="checkbox" name="checkbox"
                                                       id="resetContacts"
                                                       ng-model="data.disableInheritance">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                        <div
                                                class="col col-xs-12 col-md-offset-2 help-block text-info"
                                                ng-class="{'strikethrough': data.disableInheritance}">
                                            <?php echo __('Contacts and contact groups got inherited from'); ?>
                                            <?php if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                                <a ui-sref="HosttemplatesEdit({id: post.Host.hosttemplate_id})">
                                                    <?php echo __('host template'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('host template'); ?>
                                            <?php endif; ?>
                                            .
                                        </div>
                                    </div>

                                    <div id="ContactBlocker">
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
                                                            ng-model="post.Host.contacts._ids">
                                                    </select>
                                                    <template-diff ng-show="post.Host.hosttemplate_id"
                                                                   value="post.Host.contacts._ids"
                                                                   template-value="hosttemplate.Hosttemplate.contacts._ids"></template-diff>
                                                </div>
                                            </div>
                                            <div ng-repeat="error in errors.contacts">
                                                <div class="help-block text-danger">{{ error }}</div>
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
                                                            ng-model="post.Host.contactgroups._ids">
                                                    </select>
                                                    <template-diff ng-show="post.Host.hosttemplate_id"
                                                                   value="post.Host.contactgroups._ids"
                                                                   template-value="hosttemplate.Hosttemplate.contactgroups._ids"></template-diff>
                                                </div>
                                                <div ng-repeat="error in errors.contactgroups">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php
                                    $hostOptions = [
                                        [
                                            'field' => 'notify_on_recovery',
                                            'class' => 'success',
                                            'text'  => __('Recovery')
                                        ],
                                        [
                                            'field' => 'notify_on_down',
                                            'class' => 'danger',
                                            'text'  => __('Down')
                                        ],
                                        [
                                            'field' => 'notify_on_unreachable',
                                            'class' => 'default',
                                            'text'  => __('Unreachable')
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
                                                    <?php echo __('Host notification options'); ?>
                                                </label>

                                                <div ng-repeat="error in errors.notify_on_recovery">
                                                    <div class="text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </legend>
                                        <ul class="config-flex-inner">
                                            <?php foreach ($hostOptions as $hostOption): ?>
                                                <li>
                                                    <div class="margin-bottom-0"
                                                         ng-class="{'has-error': errors.<?php echo $hostOption['field']; ?>}">
                                                        <label for="<?php echo $hostOption['field']; ?>"
                                                               class="col col-md-7 control-label padding-top-0">
                                                        <span class="label label-<?php echo $hostOption['class']; ?> notify-label-small">
                                                            <?php echo $hostOption['text']; ?>
                                                        </span>
                                                        </label>
                                                        <div class="col-md-2 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       ng-true-value="1"
                                                                       ng-false-value="0"
                                                                       id="<?php echo $hostOption['field']; ?>"
                                                                       ng-model="post.Host.<?php echo $hostOption['field']; ?>">
                                                                <i class="checkbox-<?php echo $hostOption['class']; ?>"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <template-diff-button ng-show="post.Host.hosttemplate_id"
                                                                          value="post.Host.<?php echo $hostOption['field']; ?>"
                                                                          template-value="hosttemplate.Hosttemplate.<?php echo $hostOption['field']; ?>">
                                                    </template-diff-button>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" ng-show="post.Host.hosttemplate_id">
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

                                        <div class="form-group" ng-class="{'has-error': errors.host_url}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Host URL'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <div class="input-group" style="width: 100%;">
                                                    <input
                                                            class="form-control"
                                                            placeholder="https://issues.example.org?host=$HOSTNAME$"
                                                            type="text"
                                                            ng-model="post.Host.host_url">
                                                    <template-diff ng-show="post.Host.hosttemplate_id"
                                                                   value="post.Host.host_url"
                                                                   template-value="hosttemplate.Hosttemplate.host_url"></template-diff>
                                                </div>
                                                <div ng-repeat="error in errors.host_url">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                                <div class="help-block">
                                                    <?php echo __('The macros $HOSTID$, $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'); ?>
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
                                                            ng-model="post.Host.notes">
                                                    <template-diff ng-show="post.Host.hosttemplate_id"
                                                                   value="post.Host.notes"
                                                                   template-value="hosttemplate.Hosttemplate.notes"></template-diff>
                                                </div>
                                                <div ng-repeat="error in errors.notes">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $hostFlapOptions = [
                                            [
                                                'field' => 'flap_detection_on_up',
                                                'class' => 'success',
                                                'text'  => __('Recovery')
                                            ],
                                            [
                                                'field' => 'flap_detection_on_down',
                                                'class' => 'danger',
                                                'text'  => __('Down')
                                            ],
                                            [
                                                'field' => 'flap_detection_on_unreachable',
                                                'class' => 'default',
                                                'text'  => __('Unreachable')
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
                                                           ng-model="post.Host.flap_detection_enabled">
                                                    <i class="checkbox-primary"></i>
                                                </label>
                                                <div class="padding-left-20">
                                                    <template-diff-button ng-show="post.Host.hosttemplate_id"
                                                                          value="post.Host.flap_detection_enabled"
                                                                          template-value="hosttemplate.Hosttemplate.flap_detection_enabled">
                                                    </template-diff-button>
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset ng-show="post.Host.flap_detection_enabled">
                                            <legend class="font-sm"
                                                    ng-class="{'has-error-no-form': errors.flap_detection_on_up}">
                                                <div ng-class="{'required':post.Host.flap_detection_enabled}">
                                                    <label>
                                                        <?php echo __('Flap detection options'); ?>
                                                    </label>

                                                    <div ng-repeat="error in errors.flap_detection_on_up">
                                                        <div class="text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </legend>
                                            <ul class="config-flex-inner">
                                                <?php foreach ($hostFlapOptions as $hostFalpOption): ?>
                                                    <li>
                                                        <div class="margin-bottom-0"
                                                             ng-class="{'has-error': errors.<?php echo $hostFalpOption['field']; ?>}">

                                                            <label for="<?php echo $hostFalpOption['field']; ?>"
                                                                   class="col col-md-7 control-label padding-top-0">
                                                                <span class="label label-<?php echo $hostFalpOption['class']; ?> notify-label-small">
                                                                    <?php echo $hostFalpOption['text']; ?>
                                                                </span>
                                                            </label>

                                                            <div class="col-md-2 smart-form">
                                                                <label class="checkbox small-checkbox-label no-required">
                                                                    <input type="checkbox" name="checkbox"
                                                                           ng-true-value="1"
                                                                           ng-false-value="0"
                                                                           ng-disabled="!post.Host.flap_detection_enabled"
                                                                           id="<?php echo $hostFalpOption['field']; ?>"
                                                                           ng-model="post.Host.<?php echo $hostFalpOption['field']; ?>">
                                                                    <i class="checkbox-<?php echo $hostFalpOption['class']; ?>"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <template-diff-button ng-show="post.Host.hosttemplate_id"
                                                                              value="post.Host.<?php echo $hostFalpOption['field']; ?>"
                                                                              template-value="hosttemplate.Hosttemplate.<?php echo $hostFalpOption['field']; ?>">
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

                    <div class="row" ng-show="post.Host.hosttemplate_id">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                            <div class="jarviswidget">
                                <header>
                                <span class="widget-icon">
                                    <i class="fa fa-usd"></i>
                                </span>
                                    <h2><?php echo __('Host macro configuration'); ?></h2>
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
                                             ng-repeat="customvariable in post.Host.customvariables">
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
                                                 ng-show="post.Host.customvariables.length > 0">
                                                <i class="fa fa-info-circle"></i>
                                                <?php echo __('Macros in green color are inherited from the host template.'); ?>
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
                                <button type="submit" class="btn btn-primary">
                                    <?php echo __('Update host'); ?>
                                </button>
                                <a back-button fallback-state='HostsIndex'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

