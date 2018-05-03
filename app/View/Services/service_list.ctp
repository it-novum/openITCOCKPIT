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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Host'); ?>
            <span>>
                <?php echo __('Services'); ?>
            </span>
        </h1>
    </div>
</div>

<?php echo $this->Flash->render('positive'); ?>

<massdelete></massdelete>
<massdeactivate></massdeactivate>
<massactivate></massactivate>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


            <div class="row">
                <div class="col col-xs-8">
                    <select
                            id="ServiceListHostSelect"
                            data-placeholder="<?php echo __('Please select...'); ?>"
                            class="form-control"
                            chosen="hosts"
                            callback="loadHosts"
                            ng-options="host.key as host.value for host in hosts"
                            ng-model="hostId">
                    </select>
                </div>

                <div class="col col-xs-4" style="padding-left:0;">
                    <div class="btn-group pull-left" style="padding-top: 2px;">
                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                            <a href="/hosts/edit/{{ host.Host.id }}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/" ng-show="host.Host.allow_edit"
                               class="btn btn-default">
                                &nbsp;<i class="fa fa-cog"></i>&nbsp;
                            </a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-default">
                                &nbsp;<i class="fa fa-cog"></i>&nbsp;
                            </a>
                        <?php endif; ?>
                        <a href="javascript:void(0);" data-toggle="dropdown"
                           class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
                        <ul class="dropdown-menu" id="menuHack-host">
                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                <li>
                                    <a href="/hosts/browser/{{ host.Host.id }}">
                                        <i class="fa fa-desktop"></i> <?php echo __('Browser'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                <li>
                                    <a href="/hosts/edit/{{ host.Host.id }}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/" ng-show="host.Host.allow_edit">
                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                                <li>
                                    <a href="/hosts/allocateServiceTemplateGroup/{{ host.Host.id }}">
                                        <i class="fa fa-external-link"></i>
                                        <?php echo __('Allocate Service Template Group'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                <li ng-if="host.Host.allow_edit">
                                    <?php echo $this->AdditionalLinks->renderAsListItems(
                                        $additionalLinksList,
                                        '{{host.Host.id}}',
                                        [],
                                        true
                                    ); ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="jarviswidget jarviswidget-color-blueDark padding-top-15">
                    <header>
                        <div class="widget-toolbar" role="menu">
                            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                                <i class="fa fa-refresh"></i>
                                <?php echo __('Refresh'); ?>
                            </button>

                            <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                                <a href="/services/add/{{ host.Host.id }}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/" class="btn btn-xs btn-success">
                                    <i class="fa fa-plus"></i>
                                    <?php echo __('Add'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                <a href="/hosts/browser/{{host.Host.id}}" class="btn btn-xs btn-primary hidden-mobile">
                                    <i class="fa fa-desktop"></i>
                                    <?php echo __('Open host in browser'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                        <h2 class="hidden-mobile">{{ host.Host.name }} </h2>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab" ng-click="changeTab('active')">
                                    <i class="fa fa-stethoscope"></i>
                                    <span class="hidden-mobile hidden-tablet">
                                        <?php echo __('Active'); ?>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab" ng-click="changeTab('notMonitored')">
                                    <i class="fa fa-user-md"></i>
                                    <span class="hidden-mobile hidden-tablet">
                                        <?php echo __('Not monitored'); ?>
                                    </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab3" data-toggle="tab" ng-click="changeTab('disabled')">
                                    <i class="fa fa-plug"></i>
                                    <span class="hidden-mobile hidden-tablet">
                                        <?php echo __('Disabled'); ?>
                                    </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab4" data-toggle="tab" ng-click="changeTab('deleted')">
                                    <i class="fa fa-trash-o"></i>
                                    <span class="hidden-mobile hidden-tablet">
                                        <?php echo __('Deleted'); ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </header>
                    <div>
                        <div class="jarviswidget-editbox"></div>
                        <div class="widget-body no-padding">
                            <div class="tab-content">

                                <div id="tab1" class="tab-pane fade active in">
                                    <div class="mobile_table">
                                        <table id="host_list"
                                               class="table table-striped table-hover table-bordered smart-form"
                                               ng-if="activeTab === 'active'">
                                            <thead>
                                            <tr>

                                                <th class="no-sort text-center">
                                                    <i class="fa fa-check-square-o fa-lg"></i>
                                                </th>

                                                <th class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                                                    <i class="fa"
                                                       ng-class="getSortClass('Servicestatus.current_state')"></i>
                                                    <?php echo __('Servicestatus'); ?>
                                                </th>

                                                <th class="no-sort text-center">
                                                    <i class="fa fa-user fa-lg"
                                                       title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                </th>

                                                <th class="no-sort text-center">
                                                    <i class="fa fa-power-off fa-lg"
                                                       title="<?php echo __('in Downtime'); ?>"></i>
                                                </th>

                                                <th class="no-sort text-center">
                                                    <i class="fa fa fa-area-chart fa-lg"
                                                       title="<?php echo __('Grapher'); ?>"></i>
                                                </th>

                                                <th class="no-sort text-center">
                                                    <strong title="<?php echo __('Passively transferred service'); ?>">
                                                        P
                                                    </strong>
                                                </th>

                                                <th class="no-sort" ng-click="orderBy('Service.servicename')">
                                                    <i class="fa" ng-class="getSortClass('Service.servicename')"></i>
                                                    <?php echo __('Service name'); ?>
                                                </th>

                                                <th class="no-sort tableStatewidth"
                                                    ng-click="orderBy('Servicestatus.last_state_change')">
                                                    <i class="fa"
                                                       ng-class="getSortClass('Servicestatus.last_state_change')"></i>
                                                    <?php echo __('Last state change'); ?>
                                                </th>

                                                <th class="no-sort tableStatewidth"
                                                    ng-click="orderBy('Servicestatus.last_check')">
                                                    <i class="fa"
                                                       ng-class="getSortClass('Servicestatus.last_check')"></i>
                                                    <?php echo __('Last check'); ?>
                                                </th>

                                                <th class="no-sort tableStatewidth"
                                                    ng-click="orderBy('Servicestatus.next_check')">
                                                    <i class="fa"
                                                       ng-class="getSortClass('Servicestatus.next_check')"></i>
                                                    <?php echo __('Next check'); ?>
                                                </th>

                                                <th class="no-sort" ng-click="orderBy('Servicestatus.output')">
                                                    <i class="fa" ng-class="getSortClass('Servicestatus.output')"></i>
                                                    <?php echo __('Service output'); ?>
                                                </th>

                                                <th class="no-sort text-center width-50">
                                                    <i class="fa fa-gear fa-lg"></i>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr ng-repeat="service in services">
                                                <td class="text-center width-5">
                                                    <input type="checkbox"
                                                           ng-model="massChange[service.Service.id]"
                                                           ng-show="service.Service.allow_edit">
                                                </td>


                                                <td class="text-center width-90">
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
                                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                        <a href="/services/grapherSwitch/{{ service.Service.id }}" class="txt-color-blueDark">
                                                            <i class="fa fa-lg fa-area-chart"
                                                               ng-mouseenter="mouseenter($event, host, service)"
                                                               ng-mouseleave="mouseleave()"
                                                               ng-if="service.Service.has_graph">
                                                            </i>
                                                        </a>
                                                    <?php else: ?>
                                                        <i class="fa fa-lg fa-area-chart"
                                                           ng-mouseenter="mouseenter($event, host, service)"
                                                           ng-mouseleave="mouseleave()"
                                                           ng-if="service.Service.has_graph">
                                                        </i>
                                                    <?php endif; ?>
                                                </td>


                                                <td class="text-center">
                                                    <strong title="<?php echo __('Passively transferred service'); ?>"
                                                            ng-show="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                                        P
                                                    </strong>
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
                                                    <span ng-if="service.Service.active_checks_enabled && host.Host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
                                                    <span ng-if="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                                        <?php echo __('n/a'); ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <span ng-if="service.Service.active_checks_enabled && host.Host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
                                                    <span ng-if="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                                        <?php echo __('n/a'); ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    {{ service.Servicestatus.output }}
                                                </td>

                                                <td class="width-50">
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                            <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/"
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
                                                            id="menuHack-{{service.Service.uuid}}">
                                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/">
                                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="javascript:void(0);"
                                                                       ng-click="confirmDeactivate(getObjectForDelete(host, service))">
                                                                        <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                                <li class="divider"></li>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="javascript:void(0);" class="txt-color-red"
                                                                       ng-click="confirmDelete(getObjectForDelete(host, service))">
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

                                    <div class="noMatch" ng-show="services.length === 0">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                        </center>
                                    </div>

                                    <reschedule-service></reschedule-service>
                                    <disable-notifications></disable-notifications>
                                    <enable-notifications></enable-notifications>
                                    <acknowledge-service author="<?php echo h($username); ?>"></acknowledge-service>
                                    <service-downtime author="<?php echo h($username); ?>"></service-downtime>

                                    <div id="serviceGraphContainer" class="popup-graph-container">
                                        <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;"
                                             ng-show="isLoadingGraph">
                                            <i class="fa fa-refresh fa-4x fa-spin"></i>
                                        </div>
                                        <div id="serviceGraphFlot"></div>
                                    </div>

                                    <div class="row margin-top-10 margin-bottom-10">
                                        <div class="col-xs-12 col-md-2 text-muted text-center">
                                            <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <span ng-click="selectAll()" class="pointer">
                                                <i class="fa fa-lg fa-check-square-o"></i>
                                                <?php echo __('Select all'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <span ng-click="undoSelection()" class="pointer">
                                                <i class="fa fa-lg fa-square-o"></i>
                                                <?php echo __('Undo selection'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <a ng-href="{{ linkForCopy() }}" class="a-clean">
                                                <i class="fa fa-lg fa-files-o"></i>
                                                <?php echo __('Copy'); ?>
                                            </a>
                                        </div>
                                        <div class="col-xs-12 col-md-2 txt-color-red">
                                            <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                                <i class="fa fa-lg fa-trash-o"></i>
                                                <?php echo __('Delete'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <div class="btn-group">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-default"><?php echo __('More'); ?></a>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu" id="menuHack-1337">
                                                    <?php if ($this->Acl->hasPermission('deactivate', 'Services')): ?>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="confirmDeactivate(getObjectsForDelete())">
                                                                <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('add', 'servicegroups')): ?>
                                                        <li>
                                                            <a ng-href="{{ linkForAddToServicegroup() }}">
                                                                <i class="fa fa-cogs"></i> <?php echo __('Add to servicegroup'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="reschedule(getObjectsForExternalCommand())">
                                                                <i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="disableNotifications(getObjectsForExternalCommand())">
                                                                <i class="fa fa-envelope-o"></i> <?php echo __('Disable notification'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="enableNotifications(getObjectsForExternalCommand())">
                                                                <i class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
                                                            </a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="serviceDowntime(getObjectsForExternalCommand())">
                                                                <i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);"
                                                               ng-click="acknowledgeService(getObjectsForExternalCommand())">
                                                                <i class="fa fa-user"></i> <?php echo __('Acknowledge status'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>

                                </div> <!-- close tab1 -->

                                <div id="tab2" class="tab-pane fade">

                                    <table class="table table-striped table-hover table-bordered smart-form"
                                           ng-if="activeTab === 'notMonitored'">
                                        <thead>
                                        <tr>
                                            <th class="no-sort text-center">
                                                <i class="fa fa-check-square-o fa-lg"></i>
                                            </th>

                                            <th class="no-sort width-90">
                                                <?php echo __('Servicestatus'); ?>
                                            </th>


                                            <th class="no-sort">
                                                <?php echo __('Service name'); ?>
                                            </th>

                                            <th class="no-sort text-center width-50">
                                                <i class="fa fa-gear fa-lg"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="service in services">
                                            <td class="text-center width-5">
                                                <input type="checkbox"
                                                       ng-model="massChange[service.Service.id]"
                                                       ng-show="service.Service.allow_edit">
                                            </td>


                                            <td class="text-center width-90">
                                                <servicestatusicon service="fakeServicestatus"></servicestatusicon>
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

                                            <td class="width-50">
                                                <div class="btn-group">
                                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/"
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
                                                        id="menuHack-{{service.Service.uuid}}">
                                                        <?php if ($this->Acl->hasPermission('edit')): ?>
                                                            <li ng-if="service.Service.allow_edit">
                                                                <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/">
                                                                    <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                            <li ng-if="service.Service.allow_edit">
                                                                <a href="javascript:void(0);"
                                                                   ng-click="confirmDeactivate(getObjectForDelete(host, service))">
                                                                    <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                            <li class="divider"></li>
                                                            <li ng-if="service.Service.allow_edit">
                                                                <a href="javascript:void(0);" class="txt-color-red"
                                                                   ng-click="confirmDelete(getObjectForDelete(host, service))">
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

                                    <div class="noMatch" ng-show="services.length === 0">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                        </center>
                                    </div>

                                    <div class="row margin-top-10 margin-bottom-10">
                                        <div class="col-xs-12 col-md-2 text-muted text-center">
                                            <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <span ng-click="selectAll()" class="pointer">
                                                <i class="fa fa-lg fa-check-square-o"></i>
                                                <?php echo __('Select all'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <span ng-click="undoSelection()" class="pointer">
                                                <i class="fa fa-lg fa-square-o"></i>
                                                <?php echo __('Undo selection'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-12 col-md-2">
                                            <a ng-href="{{ linkForCopy() }}" class="a-clean">
                                                <i class="fa fa-lg fa-files-o"></i>
                                                <?php echo __('Copy'); ?>
                                            </a>
                                        </div>
                                        <div class="col-xs-12 col-md-2 txt-color-red">
                                            <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                                <i class="fa fa-lg fa-trash-o"></i>
                                                <?php echo __('Delete'); ?>
                                            </span>
                                        </div>
                                    </div>


                                    <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>

                                </div> <!-- cloase tab2 -->

                                <div id="tab3" class="tab-pane fade">

                                    <div class="mobile_table">
                                        <table class="table table-striped table-hover table-bordered smart-form"
                                               ng-if="activeTab === 'disabled'">
                                            <thead>
                                            <tr>
                                                <th class="no-sort text-center">
                                                    <i class="fa fa-check-square-o fa-lg"></i>
                                                </th>

                                                <th class="no-sort width-90">
                                                    <?php echo __('Servicestatus'); ?>
                                                </th>


                                                <th class="no-sort">
                                                    <?php echo __('Service name'); ?>
                                                </th>

                                                <th class="no-sort text-center width-50">
                                                    <i class="fa fa-gear fa-lg"></i>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr ng-repeat="service in services">
                                                <td class="text-center width-5">
                                                    <input type="checkbox"
                                                           ng-model="massChange[service.Service.id]"
                                                           ng-show="service.Service.allow_edit">
                                                </td>


                                                <td class="text-center width-90">
                                                    <servicestatusicon service="fakeServicestatus"></servicestatusicon>
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

                                                <td class="width-50">
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                            <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/"
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
                                                            id="menuHack-{{service.Service.uuid}}">
                                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="/services/edit/{{service.Service.id}}/_controller:services/_action:serviceList/_id:{{ host.Host.id }}/">
                                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('enable')): ?>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="javascript:void(0);"
                                                                       ng-click="confirmActivate(getObjectForDelete(host, service))">
                                                                        <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                                <li class="divider"></li>
                                                                <li ng-if="service.Service.allow_edit">
                                                                    <a href="javascript:void(0);" class="txt-color-red"
                                                                       ng-click="confirmDelete(getObjectForDelete(host, service))">
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

                                        <div class="noMatch" ng-show="services.length === 0">
                                            <center>
                                                <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                            </center>
                                        </div>

                                        <div class="row margin-top-10 margin-bottom-10">
                                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                            <span ng-click="selectAll()" class="pointer">
                                                <i class="fa fa-lg fa-check-square-o"></i>
                                                <?php echo __('Select all'); ?>
                                            </span>
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                            <span ng-click="undoSelection()" class="pointer">
                                                <i class="fa fa-lg fa-square-o"></i>
                                                <?php echo __('Undo selection'); ?>
                                            </span>
                                            </div>
                                            <div class="col-xs-12 col-md-2">
                                                <a ng-href="{{ linkForCopy() }}" class="a-clean">
                                                    <i class="fa fa-lg fa-files-o"></i>
                                                    <?php echo __('Copy'); ?>
                                                </a>
                                            </div>
                                            <div class="col-xs-12 col-md-2 txt-color-red">
                                            <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                                <i class="fa fa-lg fa-trash-o"></i>
                                                <?php echo __('Delete'); ?>
                                            </span>
                                            </div>
                                        </div>

                                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                                    </div>

                                </div> <!-- cloase tab4 -->

                                <div id="tab4" class="tab-pane fade">

                                    <div class="mobile_table">
                                        <table class="table table-striped table-hover table-bordered smart-form"
                                               ng-if="activeTab === 'deleted'">
                                            <thead>
                                            <tr>
                                                <th class="no-sort"><?php echo __('Service name'); ?></th>
                                                <th class="no-sort"><?php echo __('UUID'); ?></th>
                                                <th class="no-sort"><?php echo __('Date'); ?></th>
                                                <th class="no-sort"><?php echo __('Performance data deleted'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr ng-repeat="service in deletedServices">
                                                <td> {{ service.DeletedService.name }}</td>
                                                <td> {{ service.DeletedService.uuid }}</td>
                                                <td> {{ service.DeletedService.created }}</td>
                                                <td class="text-center">
                                                    <i class="fa fa-check text-success"
                                                       ng-show="service.DeletedService.perfdataDeleted"></i>
                                                    <i class="fa fa-times txt-color-red"
                                                       ng-show="!service.DeletedService.perfdataDeleted"></i>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>

                                        <div class="noMatch" ng-show="deletedServices.length === 0">
                                            <center>
                                                <span class="txt-color-red italic">
                                                    <?php echo __('No deleted services found for this host'); ?>
                                                </span>
                                            </center>
                                        </div>

                                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>

                                    </div>

                                </div> <!-- cloase tab4 -->

                            </div>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</section>
