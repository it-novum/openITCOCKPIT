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
            <i class="fa fa-sitemap fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                    <?php echo __('Host Dependencies'); ?>
                </span>
        </h1>
    </div>
</div>
<massdelete></massdelete>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>
                        <?php if ($this->Acl->hasPermission('add', 'hostdependencies')): ?>
                            <a ui-sref="HostdependenciesAdd" class="btn btn-xs btn-success" icon="fa fa-plus">
                                <i class="fa fa-plus"></i> <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-sitemap"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Host Dependencies'); ?> </h2>
                </header>
                <div>
                    <div class="list-filter well" ng-show="showFilter">
                        <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                        <div class="row padding-top-10">
                            <div class="col col-md-6 bordered-vertical-on-left">
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by host name'); ?>"
                                                       ng-model="filter.Hosts.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostFocus=true;filter.HostsDependent.name='';hostDependentFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"host"</b> OR <b>"dependent host"</b>. Opposing field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-desktop fa-stack-1x"></i>
                                            <i class="fa fa-sitemap fa-stack-1x fa-xs cornered cornered-lr text-primary"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by dependent host name'); ?>"
                                                       ng-model="filter.HostsDependent.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostDependentFocus=true;filter.Hosts.name='';hostFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-6 bordered-vertical-on-left">
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                                <i class="icon-prepend fa fa-server"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by host group'); ?>"
                                                       ng-model="filter.Hostgroups.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupFocus=true;filter.HostgroupsDependent.name='';hostgroupDependentFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"host group"</b> OR <b>"dependent host group"</b>.  Opposing field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-server fa-stack-1x"></i>
                                            <i class="fa fa-sitemap fa-stack-1x fa-xs cornered cornered-lr text-primary"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by dependent host group'); ?>"
                                                       ng-model="filter.HostgroupsDependent.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupDependentFocus=true;filter.Hostgroups.name='';hostgroupFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <fieldset>
                                    <legend><?php echo __('Execution fail on ...'); ?></legend>
                                    <div class="form-group smart-form">
                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.execution_fail_on_up"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-success"></i>
                                            <?php echo __('Up'); ?>
                                        </label>


                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.execution_fail_on_down"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-danger"></i>
                                            <?php echo __('Down'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.execution_fail_on_unreachable"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-default"></i>
                                            <?php echo __('Unreachable'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.execution_fail_on_pending"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-primary"></i>
                                            <?php echo __('Pending'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.execution_none"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-primary"></i>
                                            <?php echo __('Execution none'); ?>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <fieldset>
                                    <legend><?php echo __('Notification fail on ...'); ?></legend>
                                    <div class="form-group smart-form">
                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.notification_fail_on_up"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-success"></i>
                                            <?php echo __('Up'); ?>
                                        </label>


                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.notification_fail_on_down"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-danger"></i>
                                            <?php echo __('Down'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.notification_fail_on_unreachable"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-default"></i>
                                            <?php echo __('Unreachable'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.notification_fail_on_pending"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-primary"></i>
                                            <?php echo __('Pending'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostdependencies.notification_none"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-primary"></i>
                                            <?php echo __('Notification none'); ?>
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <fieldset>
                                    <legend><?php echo __('Options'); ?></legend>
                                    <div class="col-xs-12 col-md-12 padding-left-0">
                                        <div class="form-group smart-form">
                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostdependencies.inherits_parent[1]"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-true-value="true"
                                                       ng-false-value="false">
                                                <i class="checkbox-primary"></i>
                                                <?php echo __('Inherits parent'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12 padding-left-0">
                                        <div class="form-group smart-form">
                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostdependencies.inherits_parent[0]"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-true-value="true"
                                                       ng-false-value="false">
                                                <i class="checkbox-primary"></i>
                                                <?php echo __('Not inherits parent'); ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-right margin-top-10">
                                        <button type="button" ng-click="resetFilter()"
                                                class="btn btn-xs btn-danger">
                                            <?php echo __('Reset Filter'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- widget content -->
                    <div class="widget-body no-padding"
                         ng-init="objectName='<?php echo __('Host dependency #'); ?>'">
                        <div class="mobile_table">
                            <table id="hostdependency"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="text-align-center"><i class="fa fa-check-square-o"
                                                                     aria-hidden="true"></i></th>
                                    <th><?php echo __('Hosts'); ?></th>
                                    <th><?php echo __('Dependent hosts'); ?></th>
                                    <th><?php echo __('Host groups'); ?></th>
                                    <th><?php echo __('Dependent host groups'); ?></th>
                                    <th><?php echo __('Timeperiod'); ?></th>
                                    <th><?php echo __('Inherits parent'); ?></th>
                                    <th class="no-sort"><?php echo __('Execution failure criteria'); ?></th>
                                    <th class="no-sort"><?php echo __('Notification failure criteria'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="hostdependency in hostdependencies">
                                        <td class="text-center" class="width-15">
                                            <?php if ($this->Acl->hasPermission('delete', 'hostdependencies')): ?>
                                                <input type="checkbox"
                                                       ng-model="massChange[hostdependency.id]"
                                                       ng-show="hostdependency.allowEdit">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <li ng-repeat="host in hostdependency.hosts">
                                                    <div class="label-group label-breadcrumb label-breadcrumb-default padding-2">
                                                        <label class="label label-default label-xs">
                                                            <i class="fa fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                                                        </label>
                                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                            <a ui-sref="HostsEdit({id:host.id})"
                                                               class="label label-light label-xs">
                                                                {{host.name}}
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="label label-light label-xs">
                                                                {{host.name}}
                                                            </span>
                                                        <?php endif; ?>
                                                        <i ng-if="host.disabled == 1"
                                                           class="fa fa-power-off text-danger"
                                                           title="disabled" aria-hidden="true"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <li ng-repeat="host in hostdependency.hosts_dependent">
                                                    <div class="label-group label-breadcrumb label-breadcrumb-primary padding-2">
                                                        <label class="label label-primary label-xs">
                                                            <i class="fa fa-sitemap fa-rotate-90" aria-hidden="true"></i>
                                                        </label>
                                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                            <a ui-sref="HostsEdit({id:host.id})"
                                                               class="label label-light label-xs">
                                                                {{host.name}}
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="label label-light label-xs">
                                                                {{host.name}}
                                                            </span>
                                                        <?php endif; ?>
                                                        <i ng-if="host.disabled == 1"
                                                           class="fa fa-power-off text-danger"
                                                           title="disabled" aria-hidden="true"></i>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <li ng-repeat="hostgroup in hostdependency.hostgroups">
                                                    <div class="label-group label-breadcrumb label-breadcrumb-default padding-2">
                                                        <label class="label label-default label-xs">
                                                            <i class="fa fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                                                        </label>
                                                        <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                            <a ui-sref="HostgroupsEdit({id: hostgroup.id})"
                                                               class="label label-light label-xs">
                                                                {{hostgroup.container.name}}
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="label label-light label-xs">
                                                            {{hostgroup.container.name}}
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <li ng-repeat="hostgroup in hostdependency.hostgroups_dependent">
                                                    <div class="label-group label-breadcrumb label-breadcrumb-primary padding-2">
                                                        <label class="label label-primary label-xs">
                                                            <i class="fa fa-sitemap fa-rotate-90" aria-hidden="true"></i>
                                                        </label>
                                                        <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                            <a ui-sref="HostgroupsEdit({id: hostgroup.id})"
                                                               class="label label-light label-xs">
                                                                {{hostgroup.container.name}}
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="label label-light label-xs">
                                                                {{hostgroup.container.name}}
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                                <a ui-sref="TimeperiodsEdit({id: hostdependency.timeperiod.id})">{{
                                                    hostdependency.timeperiod.name }}</a>
                                            <?php else: ?>
                                                {{ hostdependency.timeperiod.name }}
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="label-forced label-success margin-right-5"
                                                  title="<?php echo __('Yes'); ?>"
                                                  ng-show="hostdependency.inherits_parent === 1">
                                                    <?php echo __('Yes'); ?>
                                            </span>
                                            <span class="label-forced label-danger margin-right-5"
                                                  title="<?php echo __('No'); ?>"
                                                  ng-show="hostdependency.inherits_parent === 0">
                                                    <?php echo __('No'); ?>
                                            </span>
                                        </td>
                                        <td class="text-align-center">
                                            <div>
                                            <span class="label-forced label-success margin-right-5"
                                                  title="<?php echo __('Up'); ?>"
                                                  ng-show="hostdependency.execution_fail_on_up">
                                                <?php echo __('O'); ?>
                                            </span>
                                                <span class="label-forced label-danger margin-right-5"
                                                      title="<?php echo __('Down'); ?>"
                                                      ng-show="hostdependency.execution_fail_on_down">
                                                    <?php echo __('D'); ?>
                                                </span>
                                                <span class="label-forced label-default margin-right-5"
                                                      title="<?php echo __('Unreachable'); ?>"
                                                      ng-show="hostdependency.execution_fail_on_unreachable">
                                                    <?php echo __('U'); ?>
                                                </span>
                                                <span class="label-forced label-primary margin-right-5"
                                                      title="<?php echo __('Pending'); ?>"
                                                      ng-show="hostdependency.execution_fail_on_pending">
                                                    <?php echo __('P'); ?>
                                                </span>
                                                <span class="label-forced label-primary margin-right-5"
                                                      title="<?php echo __('Execution none'); ?>"
                                                      ng-show="hostdependency.execution_none">
                                                    <?php echo __('N'); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-align-center">
                                            <div>
                                                <span class="label-forced label-success margin-right-5"
                                                      title="<?php echo __('Up'); ?>"
                                                      ng-show="hostdependency.notification_fail_on_up">
                                                    <?php echo __('O'); ?>
                                                </span>
                                                <span class="label-forced label-danger margin-right-5"
                                                      title="<?php echo __('Down'); ?>"
                                                      ng-show="hostdependency.notification_fail_on_down">
                                                    <?php echo __('D'); ?>
                                                </span>
                                                <span class="label-forced label-default margin-right-5"
                                                      title="<?php echo __('Unreachable'); ?>"
                                                      ng-show="hostdependency.notification_fail_on_unreachable">
                                                    <?php echo __('U'); ?>
                                                </span>
                                                <span class="label-forced label-primary margin-right-5"
                                                      title="<?php echo __('Pending'); ?>"
                                                      ng-show="hostdependency.notification_fail_on_pending">
                                                    <?php echo __('P'); ?>
                                                </span>
                                                <span class="label-forced label-primary margin-right-5"
                                                      title="<?php echo __('Notification none'); ?>"
                                                      ng-show="hostdependency.notification_none">
                                                    <?php echo __('N'); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center text-center no-padding padding-top-10">
                                            <div class="btn-group smart-form">
                                                <?php if ($this->Acl->hasPermission('edit', 'hostdependencies')): ?>
                                                    <a ui-sref="HostdependenciesEdit({id: hostdependency.id})"
                                                       ng-if="hostdependency.allowEdit"
                                                       class="btn btn-default">
                                                        &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                    </a>
                                                    <a href="javascript:void(0);"
                                                       ng-if="!hostdependency.allowEdit"
                                                       class="btn btn-default disabled">
                                                        &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                    </a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">
                                                        &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                    </a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu pull-right"
                                                    id="menuHack-{{hostdependency.id}}">
                                                    <?php if ($this->Acl->hasPermission('edit', 'hostdependencies')): ?>
                                                        <li ng-if="hostdependency.allowEdit">
                                                            <a ui-sref="HostdependenciesEdit({id:hostdependency.id})">
                                                                <i class="fa fa-cog"></i>
                                                                <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('delete', 'hostdependencies')): ?>
                                                        <li class="divider"
                                                            ng-if="hostdependency.allowEdit"></li>
                                                        <li ng-if="hostdependency.allowEdit">
                                                            <a href="javascript:void(0);"
                                                               class="txt-color-red"
                                                               ng-click="confirmDelete(getObjectForDelete(hostdependency))">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr ng-show="hostdependencies.length == 0">
                                        <td colspan="9">
                                            <div class="col-xs-12 text-center txt-color-red italic">
                                                <?php echo __('No entries match the selection'); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10" ng-show="hostdependencies.length > 0">
                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fa fa-lg fa-check-square-o"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fa fa-lg fa-square-o"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-4 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fa fa-lg fa-trash-o"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                        </div>
                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
        </article>
    </div>
</section>
