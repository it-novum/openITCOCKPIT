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
            <i class="fa fa-gear fa-fw "></i>
            <?php echo __('Services'); ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>
</div>

<?php echo $this->Flash->render('positive'); ?>

<div id="error_msg"></div>

<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<?php if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>

<massdelete></massdelete>
<massdeactivate></massdeactivate>

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
                            <a href="/services/add" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cog"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Services'); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <li class="active">
                            <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'index'], $this->params['named'])); ?>">
                                <i class="fa fa-stethoscope"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?></span> </a>
                        </li>
                        <?php if ($this->Acl->hasPermission('notMonitored')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'notMonitored'], $this->params['named'])); ?>">
                                    <i class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'services', 'action' => 'disabled'], $this->params['named'])); ?>">
                                    <i class="fa fa-plug"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </header>
                <div>
                    <div class="widget-body no-padding">

                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by host name'); ?>"
                                                   ng-model="filter.Host.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-cog"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by service name'); ?>"
                                                   ng-model="filter.Service.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by output'); ?>"
                                                   ng-model="filter.Servicestatus.output"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <i class="icon-prepend fa fa-filter"></i>
                                        <input type="text" class="input-sm"
                                               data-role="tagsinput"
                                               placeholder="<?php echo __('Filter by tags'); ?>"
                                               ng-model="filter.Service.keywords"
                                               ng-model-options="{debounce: 500}">
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-md-3">
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


                                <div class="col-xs-12 col-md-3">
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

                                <div class="col-xs-12 col-md-3">
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

                                <div class="col-xs-12 col-md-3">
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

                        <div class="mobile_table">
                            <table id="service_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th colspan="2" class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                                        <i class="fa" ng-class="getSortClass('Servicestatus.current_state')"></i>
                                        <?php echo __('Servicestatus'); ?>
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

                                    <th class="no-sort" ng-click="orderBy('Service.servicename')">
                                        <i class="fa" ng-class="getSortClass('Service.servicename')"></i>
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
                                               href="/hosts/browser/{{host.Host.id}}">
                                                {{host.Host.hostname}} ({{host.Host.address}})
                                            </a>
                                        <?php else: ?>
                                            {{host.Host.hostname}} ({{host.Host.address}})
                                        <?php endif; ?>

                                        <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                            <a class="pull-right txt-color-blueDark"
                                               href="/services/serviceList/{{host.Host.id}}">
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
                                        <i class="fa fa-lg fa-area-chart"
                                           ng-mouseenter="mouseenter($event, host, service)"
                                           ng-mouseleave="mouseleave()"
                                           ng-if="service.Service.has_graph">
                                        </i>
                                    </td>

                                    <td class="text-center">
                                        <strong title="<?php echo __('Passively transferred service'); ?>"
                                                ng-show="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                            P
                                        </strong>
                                    </td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('browser')): ?>
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
                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                <a href="/services/edit/{{service.Service.id}}"
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
                                            <ul class="dropdown-menu pull-right" id="menuHack-{{service.Service.uuid}}">
                                                <?php if ($this->Acl->hasPermission('edit')): ?>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <a href="/services/edit/{{service.Service.id}}">
                                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('deactivate')): ?>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           ng-click="confirmDeactivate(getObjectForDelete(host, service))">
                                                            <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('edit')): ?>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <?php echo $this->AdditionalLinks->renderAsListItems(
                                                            $additionalLinksList,
                                                            '{{service.Service.id}}',
                                                            [],
                                                            true
                                                        ); ?>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete')): ?>
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

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="services.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
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
                                    <a href="javascript:void(0);" class="btn btn-default"><?php echo __('More'); ?></a>
                                    <a href="javascript:void(0);" data-toggle="dropdown"
                                       class="btn btn-default dropdown-toggle"><span
                                                class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a ng-href="{{ linkForPdf() }}" class="a-clean">
                                                <i class="fa fa-file-pdf-o"></i> <?php echo __('List as PDF'); ?>
                                            </a>
                                        </li>
                                        <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
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

        </article>
    </div>
</section>



