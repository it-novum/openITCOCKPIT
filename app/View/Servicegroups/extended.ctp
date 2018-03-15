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


use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\HumanTime;

?>
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service Groups'); ?>
            </span>
        </h1>
    </div>
</div>

<massdelete></massdelete>
<massdeactivate></massdeactivate>
<massactivate></massactivate>

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
                            <?php echo $this->Html->link(
                                __('New'), '/' . $this->params['controller'] . '/add', [
                                    'class' => 'btn btn-xs btn-success',
                                    'icon'  => 'fa fa-plus'
                                ]
                            ); ?>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cogs"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Extended overview'); ?></h2>
                    <?php if ($this->Acl->hasPermission('extended')): ?>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li>
                                <a href="/servicegroups/index"><i class="fa fa-minus-square"></i>
                                    <span class="hidden-mobile hidden-tablet"><?php echo __('Default overview'); ?></span></a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </header>
                <div class="mobile_table">
                    <div class="list-filter well" ng-show="showFilter">
                        <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-cogs"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Filter by service group name'); ?>"
                                               ng-model="filter.Container.name"
                                               ng-model-options="{debounce: 500}">
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Filter by service group description'); ?>"
                                               ng-model="filter.Servicegroup.description"
                                               ng-model-options="{debounce: 500}">
                                    </label>
                                </div>
                            </div>
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
                    <table class="table table-striped table-hover table-bordered smart-form">
                        <thead>
                        <tr>
                            <th colspan="7" class="no-sort" ng-click="orderBy('Container.name')">
                                <i class="fa" ng-class="getSortClass('Container.name')"></i>
                                <?php echo __('Service group name'); ?>
                            </th>
                            <th colspan="6" class="no-sort" ng-click="orderBy('Servicegroup.description')">
                                <i class="fa" ng-class="getSortClass('Servicegroup.description')"></i>
                                <?php echo __('Service group description'); ?>
                            </th>
                        </tr>
                        </thead>
                        <tr ng-repeat-start="servicegroup in servicegroups">
                            <td colspan="7">
                                <a href="servicegroups/edit/{{servicegroup.Servicegroup.id}}"
                                   ng-if="servicegroup.Servicegroup.allowEdit">
                                    {{ servicegroup.Container.name }}
                                </a>
                                <span ng-if="!servicegroup.Servicegroup.allowEdit">
                                    {{ servicegroup.Container.name }}
                                </span>
                            </td>
                            <td colspan="6">
                                <span ng-if="servicegroup.Servicegroup.description">
                                    {{servicegroup.Servicegroup.description}}
                                </span>
                            </td>
                        </tr>
                        <tr ng-if="servicegroup.Services.length > 0">
                            <td class="no-padding text-right" colspan="13">
                                <div class="col-md-4">
                                </div>
                                <div ng-repeat="(state,stateCount) in servicegroup.StatusSummary"
                                     class="col-md-2 bg-{{state}}">
                                    <div class="padding-5 pull-right">
                                        <label class="checkbox small-checkbox-label txt-color-white">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-model="servicegroupsStateFilter[servicegroup.Servicegroup.uuid][$index]"
                                                   ng-value="$index"
                                                   class="ng-pristine ng-untouched ng-valid ng-empty">
                                            <i class="checkbox-{{state}}"></i>
                                            <strong>
                                                {{stateCount}} {{state}}
                                            </strong>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr ng-if="servicegroup.Services.length == 0">
                            <td class="no-padding text-center" colspan="13">
                                <div class="col-xs-12 text-center txt-color-red italic padding-10">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr ng-repeat-end
                            ng-show="servicegroupsStateFilter[servicegroup.Servicegroup.uuid][service.Servicestatus.currentState] || service.Servicestatus.currentState == null"
                            ng-repeat="service in servicegroup.Services">
                            <td class="text-center">
                                <servicestatusicon service="service"></servicestatusicon>
                            </td>

                            <td class="text-center">
                                <i class="fa fa-lg fa-user"
                                   ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                   ng-if="service.Servicestatus.acknowledgement_type == 1"></i>

                                <i class="fa fa-lg fa-user-o"
                                   ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                   ng-if="service.Servicestatus.acknowledgement_type == 2"
                                   title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                            </td>

                            <td class="text-center">
                                <i class="fa fa-lg fa-power-off"
                                   ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"></i>
                            </td>

                            <td class="text-center">
                                <i class="fa fa-lg fa-area-chart"
                                   ng-mouseenter="mouseenter($event, service.Host, service)"
                                   ng-mouseleave="mouseleave()"
                                   ng-if="service.Service.has_graph">
                                </i>
                            </td>

                            <td class="text-center">
                                <strong title="<?php echo __('Passively transferred service'); ?>"
                                        ng-show="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                    P
                                </strong>
                            </td>
                            <td class="table-color-{{(service.Hoststatus.currentState !== null)?service.Hoststatus.currentState:'disabled'}}">
                                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                    <a href="/hosts/browser/{{ service.Host.id }}">
                                        {{ service.Host.hostname }}
                                    </a>
                                <?php else: ?>
                                    {{ service.Host.hostname }}
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                    <a href="/services/browser/{{ service.Service.id }}">
                                        {{ service.Service.servicename }}
                                    </a>
                                <?php else: ?>
                                    {{ service.Service.servicename }}
                                <?php endif; ?>
                            </td>

                            <td>
                                {{ service.Servicestatus.last_state_change }}
                            </td>

                            <td>
                                <span ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
                                <span ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                        <?php echo __('n/a'); ?>
                                    </span>
                            </td>

                            <td>
                                <span ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
                                <span ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                        <?php echo __('n/a'); ?>
                                    </span>
                            </td>

                            <td>
                                {{ service.Servicestatus.output }}
                            </td>

                            <td class="width-50">
                                <div class="btn-group">
                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                        <a href="/services/edit/{{service.Service.id}}/_controller:servicegroups/_action:extended/_id:{{servicegroup.Servicegroup.id}}/"
                                           ng-if="service.Service.allow_edit"
                                           class="btn btn-default">
                                            &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);" class="btn btn-default">
                                            &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                    <?php endif; ?>
                                    <a href="javascript:void(0);" data-toggle="dropdown"
                                       class="btn btn-default dropdown-toggle"><span
                                                class="caret"></span></a>
                                    <ul class="dropdown-menu pull-right"
                                        id="menuHack-{{servicegroup.Servicegroup.uuid}}-{{service.Service.uuid}}">
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <li ng-if="service.Service.allow_edit">
                                                <a href="/services/edit/{{service.Service.id}}/_controller:servicegroups/_action:extended/_id:{{servicegroup.Servicegroup.id}}/">
                                                    <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                            <li ng-if="service.Service.allow_edit && !service.Service.disabled">
                                                <a href="javascript:void(0);"
                                                   ng-click="confirmDeactivate(getObjectForDelete(service.Host, service))">
                                                    <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('enable', 'services')): ?>
                                            <li ng-if="service.Service.allow_edit && service.Service.disabled">
                                                <a href="javascript:void(0);"
                                                   ng-click="confirmActivate(getObjectForDelete(service.Host, service))">
                                                    <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <li ng-if="service.Service.allow_edit">
                                                <?php echo $this->AdditionalLinks->renderAsListItems(
                                                    $additionalLinksList,
                                                    '{{service.Service.id}}',
                                                    [],
                                                    true
                                                ); ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                            <li class="divider"></li>
                                            <li ng-if="service.Service.allow_edit">
                                                <a href="javascript:void(0);" class="txt-color-red"
                                                   ng-click="confirmDelete(getObjectForDelete(service.Host, service))">
                                                    <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                </div>
            </div>
            <div id="serviceGraphContainer" class="popup-graph-container">
                <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;" ng-show="isLoadingGraph">
                    <i class="fa fa-refresh fa-4x fa-spin"></i>
                </div>
                <div id="serviceGraphFlot"></div>
            </div>
        </article>
    </div>
</section>
