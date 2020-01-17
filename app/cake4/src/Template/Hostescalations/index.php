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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="HostescalationsIndex">
            <i class="fa fa-bomb"></i> <?php echo __('Host Escalations'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('Overview'); ?>
    </li>
</ol>

<!-- ANGAULAR DIRECTIVES -->
<massdelete></massdelete>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Host Escalations'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('add', 'hostescalations')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="HostescalationsAdd">
                            <i class="fas fa-plus"></i> <?php echo __('New'); ?>
                        </button>
                    <?php endif; ?>

                    <button class="btn btn-xs btn-primary shadow-0" ng-click="triggerFilter()">
                        <i class="fas fa-filter"></i> <?php echo __('Filter'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <!-- Start Filter -->
                    <div class="list-filter card margin-bottom-10" ng-show="showFilter">
                        <div class="card-header">
                            <i class="fa fa-filter"></i> <?php echo __('Filter'); ?>
                        </div>
                        <div class="card-body">
                            <!-- hostname/hostgroup filter start -->
                            <div class="row">
                                <div class="col-lg-6 bordered-vertical-on-left">
                                    <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm"
                                                       placeholder="<?php echo __('Filter by host name'); ?>"
                                                       ng-model="filter.Hosts.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostFocus=true;filter.HostsExcluded.name='';hostExcludeFocus=false;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row padding-top-5 padding-bottom-5 padding-left-25">
                                        <div class="col-xs-12 help-block helptext text-info">
                                            <i class="fa fa-info-circle text-info"></i>
                                            <?php echo __('You can either search for  <b>"host"</b> OR <b>"excluded host"</b>. Opposing Field will be reset automatically'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <div class='icon-stack'>
                                                            <i class="fas fa-desktop icon-stack-3x opacity-100 "></i>
                                                            <i class="fas fa-exclamation-triangle icon-stack-2x opacity-100 text-danger"></i>
                                                        </div>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control "
                                                       placeholder="<?php echo __('Filter by excluded host group'); ?>"
                                                       ng-model="filter.HostsExcluded.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostExcludeFocus=true;filter.Hosts.name='';hostFocus=false;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 bordered-vertical-on-left">
                                    <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-sitemap"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm"
                                                       placeholder="<?php echo __('Filter by host group'); ?>"
                                                       ng-model="filter.Hostgroups.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupFocus=true;filter.HostgroupsExcluded.name='';hostgroupExcludeFocus=false;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row padding-top-5 padding-bottom-5 padding-left-25">
                                        <div class="col-xs-12 no-padding help-block helptext text-info">
                                            <i class="fa fa-info-circle text-info"></i>
                                            <?php echo __('You can either search for  <b>"host group"</b> OR <b>"excluded host group"</b>.  Opposing Field will be reset automatically'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                        <div class="form-group">
                                            <div class="input-group  input-group-sm">
                                                <div class="input-group-prepend">
                                                     <span class="input-group-text">
                                                        <div class='icon-stack'>
                                                            <i class="fas fa-sitemap icon-stack-3x opacity-100 "></i>
                                                            <i class="fas fa-exclamation-triangle icon-stack-2x opacity-100 text-danger"></i>
                                                        </div>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control"
                                                       placeholder="<?php echo __('Filter by excluded host group'); ?>"
                                                       ng-model="filter.HostgroupsExcluded.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupExcludeFocus=true;filter.Hostgroups.name='';hostgroupFocus=false;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- hostname/hostgroup filter end -->

                            <!-- notification / Escalate on filter start -->
                            <div class="row">
                                <div class="col-xs-12 col-lg-3">
                                    <fieldset>
                                        <h5><?php echo __('Notification options'); ?></h5>
                                        <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                    </div>
                                                    <input class="form-control form-control-sm"
                                                           type="number"
                                                           min="1"
                                                           step="1"
                                                           placeholder="<?php echo __('Filter by first notification'); ?>"
                                                           ng-model="filter.Hostescalations.first_notification"
                                                           ng-model-options="{debounce: 500}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                    </div>
                                                    <input class="form-control form-control-sm"
                                                           type="number"
                                                           min="0"
                                                           step="1"
                                                           placeholder="<?php echo __('Filter by last notification'); ?>"
                                                           ng-model="filter.Hostescalations.last_notification"
                                                           ng-model-options="{debounce: 500}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-lg-12 margin-bottom-10">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                                    </div>
                                                    <input class="form-control form-control-sm"
                                                           type="number"
                                                           min="0"
                                                           step="1"
                                                           placeholder="<?php echo __('Filter by notification interval'); ?>"
                                                           ng-model="filter.Hostescalations.notification_interval"
                                                           ng-model-options="{debounce: 500}">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-xs-12 col-lg-3">
                                    <fieldset>
                                        <h5><?php echo __('Escalate on ...'); ?></h5>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterUp"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hostescalations.escalate_on_recovery"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-true-value="1"
                                                       ng-false-value="">
                                                <label class="custom-control-label custom-control-label-up"
                                                       for="statusFilterUp"><?php echo __('Up'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterDown"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hostescalations.escalate_on_down"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-true-value="1"
                                                       ng-false-value="">
                                                <label class="custom-control-label custom-control-label-down"
                                                       for="statusFilterDown"><?php echo __('Down'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterUnreachable"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hostescalations.escalate_on_unreachable"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-true-value="1"
                                                       ng-false-value="">
                                                <label class="custom-control-label custom-control-label-unreachable"
                                                       for="statusFilterUnreachable"><?php echo __('Unreachable'); ?></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <!-- notification / Escalate on filter end -->
                            <div class="float-right">
                                <button type="button" ng-click="resetFilter()"
                                        class="btn btn-xs btn-danger">
                                    <?php echo __('Reset Filter'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter -->

                    <div class="frame-wrap">
                        <table class="table table-striped m-0 table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-align-center"><i class="fa fa-check-square"
                                                                 aria-hidden="true"></i></th>
                                <th><?php echo __('Hosts'); ?></th>
                                <th><?php echo __('Excluded hosts'); ?></th>
                                <th><?php echo __('Host groups'); ?></th>
                                <th><?php echo __('Excluded hosts groups'); ?></th>
                                <th><?php echo __('First'); ?></th>
                                <th><?php echo __('Last'); ?></th>
                                <th><?php echo __('Interval'); ?></th>
                                <th><?php echo __('Timeperiod'); ?></th>
                                <th><?php echo __('Contacts'); ?></th>
                                <th><?php echo __('Contact groups'); ?></th>
                                <th class="no-sort"><?php echo __('Options'); ?></th>
                                <th class="no-sort text-center width-60"><i class="fa fa-gear fa-lg"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="hostescalation in hostescalations">
                                <td class="text-center" class="width-15">
                                    <?php if ($this->Acl->hasPermission('delete', 'hostescalations')): ?>
                                        <input type="checkbox"
                                               ng-model="massChange[hostescalation.id]"
                                               ng-show="hostescalation.allowEdit">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li ng-repeat="host in hostescalation.hosts">
                                            <div class="label-group label-breadcrumb label-breadcrumb-success padding-2"
                                                 title="{{host.name}}">
                                                <label class="label label-success label-xs">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
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
                                            </div>
                                            <i ng-if="host.disabled == 1"
                                               class="fa fa-power-off text-danger"
                                               title="disabled" aria-hidden="true"></i>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li ng-repeat="host in hostescalation.hosts_excluded">
                                            <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2"
                                                 title="{{host.name}}">
                                                <label class="label label-danger label-xs">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
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
                                            </div>
                                            <i ng-if="host.disabled == 1"
                                               class="fa fa-power-off text-danger"
                                               title="disabled" aria-hidden="true"></i>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li ng-repeat="hostgroup in hostescalation.hostgroups">
                                            <div class="label-group label-breadcrumb label-breadcrumb-success padding-2"
                                                 title="{{hostgroup.container.name}}">
                                                <label class="label label-success label-xs">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
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
                                        <li ng-repeat="hostgroup in hostescalation.hostgroups_excluded">
                                            <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2"
                                                 title="{{hostgroup.container.name}}">
                                                <label class="label label-danger label-xs">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
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
                                    {{ hostescalation.first_notification }}
                                </td>
                                <td>
                                    {{ hostescalation.last_notification }}
                                </td>
                                <td>
                                    {{ hostescalation.notification_interval }}
                                </td>
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                        <a ui-sref="TimeperiodsEdit({id: hostescalation.timeperiod.id})">{{
                                            hostescalation.timeperiod.name }}</a>
                                    <?php else: ?>
                                        {{ hostescalation.timeperiod.name }}
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li ng-repeat="contact in hostescalation.contacts">
                                            <?php if ($this->Acl->hasPermission('edit', 'contacts')): ?>
                                                <a ui-sref="ContactsEdit({id: contact.id})">
                                                    {{ contact.name }}
                                                </a>
                                            <?php else: ?>
                                                {{ contact.name }}
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li ng-repeat="contactgroup in hostescalation.contactgroups">
                                            <?php if ($this->Acl->hasPermission('edit', 'contactgroups')): ?>
                                                <a ui-sref="ContactgroupsEdit({id: contactgroup.id})">
                                                    {{ contactgroup.container.name }}
                                                </a>
                                            <?php else: ?>
                                                {{ contactgroup.container.name }}
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-align-center">
                                    <div>
                                                <span class="label-forced label-success margin-right-5"
                                                      title="<?php echo __('Recovery'); ?>"
                                                      ng-show="hostescalation.escalate_on_recovery">
                                                    <?php echo __('R'); ?>
                                                </span>
                                        <span class="label-forced label-danger margin-right-5"
                                              title="<?php echo __('Down'); ?>"
                                              ng-show="hostescalation.escalate_on_down">
                                                    <?php echo __('D'); ?>
                                                </span>
                                        <span class="label-forced label-default margin-right-5"
                                              title="<?php echo __('Unreachable'); ?>"
                                              ng-show="hostescalation.escalate_on_unreachable">
                                                    <?php echo __('U'); ?>
                                                </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group smart-form">
                                        <?php if ($this->Acl->hasPermission('edit', 'hostescalations')): ?>
                                            <a ui-sref="HostescalationsEdit({id: hostescalation.id})"
                                               ng-if="hostescalation.allowEdit"
                                               class="btn btn-default">
                                                &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                            <a href="javascript:void(0);"
                                               ng-if="!hostescalation.allowEdit"
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
                                            id="menuHack-{{hostescalation.id}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'hostescalations')): ?>
                                                <li ng-if="hostescalation.allowEdit">
                                                    <a ui-sref="HostescalationsEdit({id:hostescalation.id})">
                                                        <i class="fa fa-cog"></i>
                                                        <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('delete', 'hostescalations')): ?>
                                                <li class="divider"
                                                    ng-if="hostescalation.allowEdit"></li>
                                                <li ng-if="hostescalation.allowEdit">
                                                    <a href="javascript:void(0);"
                                                       class="txt-color-red"
                                                       ng-click="confirmDelete(getObjectForDelete(hostescalation))">
                                                        <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="margin-top-10" ng-show="hostescalations.length == 0">
                            <div class="text-center text-danger italic">
                                <?php echo __('No entries match the selection'); ?>
                            </div>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fas fa-lg fa-check-square"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fas fa-lg fa-square"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fas fa-trash"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                        </div>
                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
