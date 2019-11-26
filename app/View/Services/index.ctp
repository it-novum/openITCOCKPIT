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
        <a href="<?php echo $this->webroot; ?>">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ServicesIndex">
            <i class="fa fa-cog"></i> <?php echo __('Services'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-stethoscope"></i> <?php echo __('Monitored'); ?>
    </li>
</ol>

<!-- ANGAULAR DIRECTIVES -->
<query-handler-directive></query-handler-directive>
<massdelete></massdelete>
<massdeactivate></massdeactivate>
<?php if ($this->Acl->hasPermission('add', 'servicegroups')): ?>
    <add-services-to-servicegroup></add-services-to-servicegroup>
<?php endif; ?>

<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Services'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" ui-sref="ServicesIndex" role="tab">
                                <i class="fa fa-stethoscope"></i> <?php echo __('Monitored'); ?>
                            </a>
                        </li>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'services')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="ServicesNotMonitored" role="tab">
                                    <i class="fa fa-user-md"></i> <?php echo __('Not monitored'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'services')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="ServicesDisabled" role="tab">
                                    <i class="fa fa-plug"></i> <?php echo __('Disabled'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="ServicesAdd">
                            <i class="fas fa-plus"></i> <?php echo __('New'); ?>
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-xs btn-danger mr-1 shadow-0" ng-click="problemsOnly()">
                        <i class="fas fa-plus"></i> <?php echo __('Unhandled only'); ?>
                    </button>
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
                            <div class="row">
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by host name'); ?>"
                                                   ng-model="filter.Hosts.name"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-cog"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by service name'); ?>"
                                                   ng-model="filter.Services.name"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by output'); ?>"
                                                   ng-model="filter.Servicestatus.output"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <div class="col tagsinputFilter">
                                                <input type="text"
                                                       class="form-control form-control-sm "
                                                       data-role="tagsinput"
                                                       id="ServicesKeywordsInput"
                                                       placeholder="<?php echo __('Filter by tags'); ?>"
                                                       ng-model="filter.Services.keywords"
                                                       ng-model-options="{debounce: 500}"
                                                       style="display: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <div class="col tagsinputFilter">
                                                <input type="text" class="input-sm"
                                                       data-role="tagsinput"
                                                       id="ServicesNotKeywordsInput"
                                                       placeholder="<?php echo __('Filter by excluded tags'); ?>"
                                                       ng-model="filter.Services.not_keywords"
                                                       ng-model-options="{debounce: 500}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-xs-12 col-lg-3">
                                        <fieldset>
                                            <legend><?php echo __('Service status'); ?></legend>
                                            <div class="form-group smart-form">
                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.current_state.ok"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-success"></i>
                                                    <?php echo __('Ok'); ?>
                                                </label>

                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.current_state.warning"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-warning"></i>
                                                    <?php echo __('Warning'); ?>
                                                </label>

                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.current_state.critical"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-danger"></i>
                                                    <?php echo __('Critical'); ?>
                                                </label>

                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.current_state.unknown"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-default"></i>
                                                    <?php echo __('Unknown'); ?>
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>


                                    <div class="col-xs-12 col-lg-3">
                                        <fieldset>
                                            <legend><?php echo __('Acknowledgements'); ?></legend>
                                            <div class="form-group smart-form">
                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.acknowledged"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('Acknowledge'); ?>
                                                </label>

                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.not_acknowledged"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('Not acknowledged'); ?>
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 col-lg-3">
                                        <fieldset>
                                            <legend><?php echo __('Downtimes'); ?></legend>
                                            <div class="form-group smart-form">
                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.in_downtime"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('In downtime'); ?>
                                                </label>

                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.not_in_downtime"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('Not in downtime'); ?>
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-xs-12 col-lg-3">
                                        <fieldset>
                                            <legend><?php echo __('Check type'); ?></legend>
                                            <div class="form-group smart-form">
                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.active"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('Active service'); ?>
                                                </label>
                                            </div>
                                            <div class="form-group smart-form">
                                                <label class="checkbox small-checkbox-label">
                                                    <input type="checkbox" name="checkbox" checked="checked"
                                                           ng-model="filter.Servicestatus.passive"
                                                           ng-model-options="{debounce: 500}">
                                                    <i class="checkbox-primary"></i>
                                                    <?php echo __('Passive service'); ?>
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>

                                </div>
                            </div>
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
                        <table class="table table-striped m-0 table-bordered">
                            <thead>
                            <tr>
                                <th colspan="2" class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                                    <i class="fa" ng-class="getSortClass('Servicestatus.current_state')"></i>
                                    <?php echo __('State'); ?>
                                </th>

                                <th class="no-sort text-center">
                                    <i class="fa fa-user fa-lg" title="<?php echo __('is acknowledged'); ?>"></i>
                                </th>

                                <th class="no-sort text-center">
                                    <i class="fa fa-power-off fa-lg"
                                       title="<?php echo __('is in downtime'); ?>"></i>
                                </th>


                                <th class="no-sort text-center">
                                    <i class="fa fa fa-area-chart fa-lg" title="<?php echo __('Grapher'); ?>"></i>
                                </th>

                                <th class="no-sort text-center">
                                    <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                </th>

                                <th class="no-sort" ng-click="orderBy('servicename')">
                                    <i class="fa" ng-class="getSortClass('servicename')"></i>
                                    <?php echo __('Service name'); ?>
                                </th>


                                <th class="no-sort tableStatewidth"
                                    ng-click="orderBy('Servicestatus.last_state_change')">
                                    <i class="fa" ng-class="getSortClass('Servicestatus.last_state_change')"></i>
                                    <?php echo __('Last state change'); ?>
                                </th>

                                <th class="no-sort tableStatewidth" ng-click="orderBy('Servicestatus.last_check')">
                                    <i class="fa" ng-class="getSortClass('Servicestatus.last_check')"></i>
                                    <?php echo __('Last check'); ?>
                                </th>

                                <th class="no-sort tableStatewidth" ng-click="orderBy('Servicestatus.next_check')">
                                    <i class="fa" ng-class="getSortClass('Servicestatus.next_check')"></i>
                                    <?php echo __('Next check'); ?>
                                </th>

                                <th class="no-sort" ng-click="orderBy('Servicestatus.output')">
                                    <i class="fa" ng-class="getSortClass('Servicestatus.output')"></i>
                                    <?php echo __('Service output'); ?>
                                </th>

                                <th class="no-sort text-center editItemWidth">
                                    <i class="fa fa-gear fa-lg"></i>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr ng-repeat-start="host in services">
                                <td colspan="13" class="service_table_host_header">

                                    <hoststatusicon host="host"></hoststatusicon>

                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a class="padding-left-5 txt-color-blueDark"
                                           ui-sref="HostsBrowser({id: host.Host.id})">
                                            {{host.Host.hostname}} ({{host.Host.address}})
                                        </a>
                                    <?php else: ?>
                                        {{host.Host.hostname}} ({{host.Host.address}})
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                        <a class="pull-right txt-color-blueDark"
                                           ui-sref="ServicesServiceList({id: host.Host.id})">
                                            <i class="fa fa-list"
                                               title=" <?php echo __('Go to Service list'); ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr ng-repeat="service in host.Services" ng-repeat-end="">

                                <td class="width-5">
                                    <input type="checkbox"
                                           ng-model="massChange[service.Service.id]"
                                           ng-show="service.Service.allow_edit">
                                </td>

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
                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                        <a ui-sref="ServicesBrowser({id:service.Service.id})"
                                           class="txt-color-blueDark">
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
                                        <a ui-sref="ServicesBrowser({id:service.Service.id})">
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
                                    <span ng-if="service.Service.active_checks_enabled === false">
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


                                    <div class="btn-group btn-group-xs" role="group">
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <a ui-sref="ServicesEdit({id: service.Service.id})"
                                               ng-if="service.Service.allow_edit"
                                               class="btn btn-default btn-lower-padding">
                                                <i class="fa fa-cog"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);"
                                               class="btn btn-default btn-lower-padding">
                                                <i class="fa fa-cog"></i></a>
                                        <?php endif; ?>
                                        <button type="button"
                                                class="btn btn-default dropdown-toggle btn-lower-padding"
                                                data-toggle="dropdown">
                                            <i class="caret"></i>
                                        </button>
                                        <!-- <ul class="dropdown-menu" id="menuHack-{{service.Service.uuid}}" > -->
                                        <div class="dropdown-menu">
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <a ui-sref="ServicesEdit({id: service.Service.id})"
                                                   ng-if="service.Service.allow_edit"
                                                   class="dropdown-item">
                                                    <i class="fa fa-cog"></i>
                                                    <?php echo __('Edit'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                <a ng-if="service.Service.allow_edit"
                                                   class="dropdown-item"
                                                   href="javascript:void(0);"
                                                   ng-click="confirmDeactivate(getObjectForDelete(host, service))">
                                                    <i class="fa fa-plug"></i>
                                                    <?php echo __('Disable'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <a ng-if="service.Service.allow_edit"
                                                   class="dropdown-item">
                                                    <?php
                                                    /**
                                                     * @fixme
                                                     * as the additional links helper is deprecated fix this with the replacement !
                                                     */
                                                    echo $this->AdditionalLinks->renderAsListItems(
                                                        $additionalLinksList,
                                                        '{{service.Service.id}}',
                                                        [],
                                                        true
                                                    ); ?>
                                                </a>


                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                <a ng-click="confirmDelete(getObjectForDelete(host, service))"
                                                   ng-if="service.Service.allow_edit"
                                                   class="dropdown-item">
                                                    <i class="fa fa-trash"></i>
                                                    <?php echo __('Delete'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="margin-top-10" ng-show="services.length == 0">
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
                            <div class="col-xs-12 col-md-2">
                                <a ui-sref="ServicesCopy({ids: linkForCopy()})" class="a-clean">
                                    <i class="fas fa-lg fa-files-o"></i>
                                    <?php echo __('Copy'); ?>
                                </a>
                            </div>
                            <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fas fa-trash"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-default dropdown-toggle waves-effect waves-themed" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo __('More actions'); ?>
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start"
                                     style="position: absolute; will-change: top, left; top: 37px; left: 0px;">
                                    <a ng-href="{{ linkForPdf() }}" class="dropdown-item">
                                        <i class="fa fa-file-pdf-o"></i> <?php echo __('List as PDF'); ?>
                                    </a>
                                    <?php if ($this->Acl->hasPermission('add', 'servicegroups')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="confirmAddServicesToServicegroup(getObjectsForDelete())">
                                            <i class="fa fa-cogs"></i>
                                            <?php echo __('Add to service group'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="reschedule(getObjectsForExternalCommand())">
                                            <i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="disableNotifications(getObjectsForExternalCommand())">
                                            <i class="fa fa-envelope"></i> <?php echo __('Disable notification'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="enableNotifications(getObjectsForExternalCommand())">
                                            <i class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="serviceDowntime(getObjectsForExternalCommand())">
                                            <i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="acknowledgeService(getObjectsForExternalCommand())">
                                            <i class="fa fa-user"></i> <?php echo __('Acknowledge status'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>

            <reschedule-service></reschedule-service>
            <disable-notifications></disable-notifications>
            <enable-notifications></enable-notifications>
            <acknowledge-service author="<?php echo h($username); ?>"></acknowledge-service>
            <service-downtime author="<?php echo h($username); ?>"></service-downtime>

            <div id="serviceGraphContainer" class="popup-graph-container">
                <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;" ng-show="isLoadingGraph">
                    <i class="fa fa-refresh fa-4x fa-spin"></i>
                </div>
                <div id="serviceGraphFlot"></div>
            </div>
        </div>
    </div>
</div>