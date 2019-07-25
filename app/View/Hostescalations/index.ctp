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
            <i class="fa fa-bomb fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                    <?php echo __('Host Escalations'); ?>
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
                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <a ui-sref="HostescalationsAdd" class="btn btn-xs btn-success" icon="fa fa-plus">
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
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-bomb"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Host Escalations'); ?> </h2>

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
                                                       ng-focus="hostFocus=true;filter.HostsExcluded.name='';hostExcludeFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"host"</b> OR <b>"excluded host"</b>. Opposing Field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-desktop fa-stack-1x"></i>
                                            <i class="fa fa-exclamation-triangle fa-stack-1x fa-xs cornered cornered-lr text-danger"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by excluded host name'); ?>"
                                                       ng-model="filter.HostsExcluded.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostExcludeFocus=true;filter.Hosts.name='';hostFocus=false;">
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
                                                <i class="icon-prepend fa fa-sitemap"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by host group'); ?>"
                                                       ng-model="filter.Hostgroups.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupFocus=true;filter.HostgroupsExcluded.name='';hostgroupExcludeFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"host group"</b> OR <b>"excluded host group"</b>.  Opposing Field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-sitemap fa-stack-1x"></i>
                                            <i class="fa fa-exclamation-triangle fa-stack-1x fa-xs cornered cornered-lr text-danger"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by excluded host group'); ?>"
                                                       ng-model="filter.HostgroupsExcluded.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="hostgroupExcludeFocus=true;filter.Hostgroups.name='';hostgroupFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-3">
                                <fieldset>
                                    <legend><?php echo __('Notification options'); ?></legend>
                                    <div class="col-xs-12 col-md-12 padding-left-0">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                                <i class="icon-prepend fa fa-envelope-o"></i>
                                                <input class="input-sm"
                                                       type="number"
                                                       min="1"
                                                       step="1"
                                                       placeholder="<?php echo __('Filter by first notification'); ?>"
                                                       ng-model="filter.Hostescalations.first_notification"
                                                       ng-model-options="{debounce: 500}">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12 padding-left-0">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                                <i class="icon-prepend fa fa-envelope-o"></i>
                                                <input class="input-sm"
                                                       type="number"
                                                       min="0"
                                                       step="1"
                                                       placeholder="<?php echo __('Filter by last notification'); ?>"
                                                       ng-model="filter.Hostescalations.last_notification"
                                                       ng-model-options="{debounce: 500}">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12 padding-left-0">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                                <i class="icon-prepend fa fa-clock-o"></i>
                                                <input class="input-sm"
                                                       type="number"
                                                       min="0"
                                                       step="1"
                                                       placeholder="<?php echo __('Filter by notification interval'); ?>"
                                                       ng-model="filter.Hostescalations.notification_interval"
                                                       ng-model-options="{debounce: 500}">
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <fieldset>
                                    <legend><?php echo __('Escalate on ...'); ?></legend>
                                    <div class="form-group smart-form">
                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostescalations.escalate_on_recovery"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-success"></i>
                                            <?php echo __('Up'); ?>
                                        </label>


                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostescalations.escalate_on_down"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-danger"></i>
                                            <?php echo __('Down'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Hostescalations.escalate_on_unreachable"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-default"></i>
                                            <?php echo __('Unreachable'); ?>
                                        </label>
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
                         ng-init="objectName='<?php echo __('Host escalation #'); ?>'">
                        <div class="mobile_table" ng-show="hostescalations.length > 0">
                            <table id="hostescalation_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="text-align-center"><i class="fa fa-check-square-o"
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
                        </div>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="hostescalations.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10" ng-show="hostescalations.length > 0">
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
