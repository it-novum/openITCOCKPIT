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
                    <?php echo __('Service escalations'); ?>
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
                            <a ui-sref="ServiceescalationsAdd" class="btn btn-xs btn-success" icon="fa fa-plus">
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
                    <h2 class="hidden-mobile"><?php echo __('Service Escalations'); ?> </h2>

                </header>
                <div>
                    <div class="list-filter well" ng-show="showFilter">
                        <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                        <div class="row padding-top-10">
                            <div class="col col-md-6 bordered-vertical-on-left">
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-cog"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by service name'); ?>"
                                                       ng-model="filter.Services.servicename"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="serviceFocus=true;filter.ServicesExcluded.servicename='';serviceExcludeFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"service"</b> OR <b>"excluded service"</b>. Opposing Field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-cog fa-stack-1x"></i>
                                            <i class="fa fa-exclamation-triangle fa-stack-1x fa-xs cornered cornered-lr text-danger"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by excluded service name'); ?>"
                                                       ng-model="filter.ServicesExcluded.servicename"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="serviceExcludeFocus=true;filter.Services.servicename='';serviceFocus=false;">
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
                                                <i class="icon-prepend fa fa-cogs"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by service group'); ?>"
                                                       ng-model="filter.Servicegroups.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="servicegroupFocus=true;filter.ServicegroupsExcluded.name='';servicegroupExcludeFocus=false;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row padding-top-5 padding-bottom-5">
                                    <div class="col-xs-12 no-padding help-block helptext text-info">
                                        <i class="fa fa-info-circle text-info"></i>
                                        <?php echo __('You can either search for  <b>"service group"</b> OR <b>"excluded service group"</b>.  Opposing Field will be reset automatically'); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 no-padding">
                                        <div class="form-group smart-form">
                                            <label class="input">
                                        <span class="icon-prepend fa-stack">
                                            <i class="fa fa-cogs fa-stack-1x"></i>
                                            <i class="fa fa-exclamation-triangle fa-stack-1x fa-xs cornered cornered-lr text-danger"></i>
                                        </span>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by excluded service group'); ?>"
                                                       ng-model="filter.ServicegroupsExcluded.name"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-focus="servicegroupExcludeFocus=true;filter.Servicegroups.name='';servicegroupFocus=false;">
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
                                                       ng-model="filter.Serviceescalations.first_notification"
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
                                                       ng-model="filter.Serviceescalations.last_notification"
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
                                                       ng-model="filter.Serviceescalations.notification_interval"
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
                                                   ng-model="filter.Serviceescalations.escalate_on_recovery"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-success"></i>
                                            <?php echo __('Up'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Serviceescalations.escalate_on_warning"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-warning"></i>
                                            <?php echo __('Warning'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Serviceescalations.escalate_on_critical"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-danger"></i>
                                            <?php echo __('Critical'); ?>
                                        </label>

                                        <label class="checkbox small-checkbox-label">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model="filter.Serviceescalations.escalate_on_unknown"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-true-value="1"
                                                   ng-false-value="">
                                            <i class="checkbox-default"></i>
                                            <?php echo __('Unknown'); ?>
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
                         ng-init="objectName='<?php echo __('Service escalation #'); ?>'">
                        <div class="mobile_table" ng-show="serviceescalations.length > 0">
                            <table id="serviceescalation_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="text-align-center"><i class="fa fa-check-square-o"
                                                                     aria-hidden="true"></i></th>
                                    <th><?php echo __('Services'); ?></th>
                                    <th><?php echo __('Excluded services'); ?></th>
                                    <th><?php echo __('Service groups'); ?></th>
                                    <th><?php echo __('Excluded service groups'); ?></th>
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
                                <tr ng-repeat="serviceescalation in serviceescalations">
                                    <td class="text-center" class="width-15">
                                        <?php if ($this->Acl->hasPermission('delete', 'serviceescalations')): ?>
                                            <input type="checkbox"
                                                   ng-model="massChange[serviceescalations.id]"
                                                   ng-show="serviceescalations.allowEdit">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="service in serviceescalation.services" title="{{service.servicename}}">
                                                <div class="label-group label-breadcrumb label-breadcrumb-success padding-2">
                                                    <label class="label label-success label-xs">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <a ui-sref="ServicesEdit({id: service.id})"
                                                           class="label label-light label-xs">
                                                            {{service.servicename}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{service.servicename}}
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <i ng-if="service.disabled == 1"
                                                   class="fa fa-power-off text-danger"
                                                   title="disabled" aria-hidden="true"></i>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="service in serviceescalation.services_excluded" title="{{service.servicename}}">
                                                <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2">
                                                    <label class="label label-danger label-xs">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <a ui-sref="ServicesEdit({id: service.id})"
                                                           class="label label-light label-xs">
                                                            {{service.servicename}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{service.servicename}}
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <i ng-if="service.disabled == 1"
                                                   class="fa fa-power-off text-danger"
                                                   title="disabled" aria-hidden="true"></i>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="servicegroup in serviceescalation.servicegroups">
                                                <div class="label-group label-breadcrumb label-breadcrumb-success padding-2"
                                                title="{{servicegroup.container.name}}">
                                                    <label class="label label-success label-xs">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'servicegroups')): ?>
                                                        <a ui-sref="ServicegroupsEdit({id: servicegroup.id})"
                                                           class="label label-light label-xs">
                                                            {{servicegroup.container.name}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                        {{servicegroup.container.name}}
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="servicegroup in serviceescalation.servicegroups_excluded">
                                                <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2"
                                                title="{{servicegroup.container.name}}">
                                                    <label class="label label-danger label-xs">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'servicegroups')): ?>
                                                        <a ui-sref="ServicegroupsEdit({id: servicegroup.id})"
                                                           class="label label-light label-xs">
                                                            {{servicegroup.container.name}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{servicegroup.container.name}}
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        {{ serviceescalation.first_notification }}
                                    </td>
                                    <td>
                                        {{ serviceescalation.last_notification }}
                                    </td>
                                    <td>
                                        {{ serviceescalation.notification_interval }}
                                    </td>
                                    <td>
                                        <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                            <a ui-sref="TimeperiodsEdit({id: serviceescalation.timeperiod.id})">{{
                                                serviceescalation.timeperiod.name }}</a>
                                        <?php else: ?>
                                            {{ serviceescalation.timeperiod.name }}
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="contact in serviceescalation.contacts">
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
                                            <li ng-repeat="contactgroup in serviceescalation.contactgroups">
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
                                                  ng-show="serviceescalation.escalate_on_recovery">
                                                <?php echo __('R'); ?>
                                            </span>
                                            <span class="label-forced label-warning margin-right-5"
                                                  title="<?php echo __('Warning'); ?>"
                                                  ng-show="serviceescalation.escalate_on_warning">
                                                <?php echo __('W'); ?>
                                            </span>
                                            <span class="label-forced label-danger margin-right-5"
                                                  title="<?php echo __('Critical'); ?>"
                                                  ng-show="serviceescalation.escalate_on_critical">
                                                <?php echo __('C'); ?>
                                            </span>
                                            <span class="label-forced label-default margin-right-5"
                                                  title="<?php echo __('Unknown'); ?>"
                                                  ng-show="serviceescalation.escalate_on_unknown">
                                                <?php echo __('U'); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'serviceescalations')): ?>
                                                <a ui-sref="ServiceescalationsEdit({id: serviceescalation.id})"
                                                   ng-if="serviceescalation.allowEdit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!serviceescalation.allowEdit"
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
                                                id="menuHack-{{serviceescalation.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'serviceescalations')): ?>
                                                    <li ng-if="serviceescalation.allowEdit">
                                                        <a ui-sref="ServiceescalationsEdit({id:serviceescalation.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'serviceescalations')): ?>
                                                    <li class="divider"
                                                        ng-if="serviceescalation.allowEdit"></li>
                                                    <li ng-if="serviceescalation.allowEdit">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(serviceescalation))">
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
                            <div class="row margin-top-10 margin-bottom-10" ng-show="serviceescalations.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10" ng-show="serviceescalations.length > 0">
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
