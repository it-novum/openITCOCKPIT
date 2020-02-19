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
        <a ui-sref="ServicesIndex">
            <i class="fa fa-cog"></i> <?php echo __('Services'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-stethoscope"></i> <?php echo __('Services of Host'); ?>
    </li>
</ol>

<query-handler-directive></query-handler-directive>

<massdelete></massdelete>
<massdeactivate></massdeactivate>
<massactivate></massactivate>

<?php if ($this->Acl->hasPermission('add', 'servicegroups')): ?>
    <add-services-to-servicegroup></add-services-to-servicegroup>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8 margin-bottom-10">
        <div class="input-group">
            <select
                id="ServiceListHostSelect"
                data-placeholder="<?php echo __('Please select...'); ?>"
                class="form-control"
                chosen="hosts"
                callback="loadHosts"
                ng-options="host.key as host.value for host in hosts"
                ng-model="data.hostId">
            </select>


            <div class="input-group-append">
                <button class="btn btn-default btn-sm dropdown-toggle waves-effect waves-themed" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo __('Actions'); ?>
                </button>
                <div class="dropdown-menu" x-placement="bottom-start"
                     style="position: absolute; will-change: top, left; top: 37px; left: 0px;">
                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                        <a class="dropdown-item" ui-sref="HostsBrowser({id:data.hostId})">
                            <i class="fa fa-desktop"></i> <?php echo __('Browser'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                        <a class="dropdown-item" ui-sref="HostsEdit({id:data.hostId})"
                           ng-show="host.allowEdit">
                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                        <a class="dropdown-item"
                           ui-sref="ServicetemplategroupsAllocateToHostgroup({id: servicetemplategroup.Servicetemplategroup.id})">
                            <i class="fa fa-external-link-alt"></i>
                            <?php echo __('Allocate Service Template Group'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Services'); ?>
                    <span class="fw-300"><i><?php echo __('of Host'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" ng-click="changeTab('active')" role="tab">
                                <i class="fa fa-stethoscope">&nbsp;</i> <?php echo __('Active'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" ng-click="changeTab('notMonitored')" role="tab">
                                <i class="fa fa-user-md">&nbsp;</i> <?php echo __('Not monitored'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" ng-click="changeTab('disabled')" role="tab">
                                <i class="fa fa-plug">&nbsp;</i> <?php echo __('Disabled'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" ng-click="changeTab('deleted')" role="tab">
                                <i class="fa fa-trash">&nbsp;</i> <?php echo __('Deleted'); ?>
                            </a>
                        </li>
                    </ul>
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="ServicesAdd">
                            <i class="fas fa-plus"></i> <?php echo __('New'); ?>
                        </button>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                        <button class="btn btn-xs btn-primary mr-1 shadow-0" ui-sref="HostsBrowser({id:host.id})">
                            <i class="fa fa-desktop"></i>
                            <?php echo __('Open host in browser'); ?>
                        </button>
                    <?php endif; ?>


                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">

                        <!-- ACTIVE TAB START -->
                        <div ng-if="activeTab === 'active'">
                            <table class="table table-striped m-0 table-bordered table-hover table-sm">
                                <thead>
                                <tr>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-check-square"></i>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                                        <i class="fa" ng-class="getSortClass('Servicestatus.current_state')"></i>
                                        <?php echo __('State'); ?>
                                    </th>

                                    <th class="no-sort text-center">
                                        <i class="fa fa-user" title="<?php echo __('is acknowledged'); ?>"></i>
                                    </th>

                                    <th class="no-sort text-center">
                                        <i class="fa fa-power-off"
                                           title="<?php echo __('is in downtime'); ?>"></i>
                                    </th>


                                    <th class="no-sort text-center">
                                        <i class="fa fa fa-area-chart" title="<?php echo __('Grapher'); ?>"></i>
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
                                <tr ng-repeat="service in services" ng-repeat-end="">

                                    <td class="width-5">
                                        <input type="checkbox"
                                               ng-model="massChange[service.Service.id]"
                                               ng-show="service.Service.allow_edit">
                                    </td>

                                    <td class="text-center">
                                        <servicestatusicon service="service"></servicestatusicon>
                                    </td>

                                    <td class="text-center">
                                        <i class="far fa-user"
                                           ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                           ng-if="service.Servicestatus.acknowledgement_type == 1"></i>

                                        <i class="fas fa-user"
                                           ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                           ng-if="service.Servicestatus.acknowledgement_type == 2"
                                           title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                    </td>

                                    <td class="text-center">
                                        <i class="fa fa-power-off"
                                           ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"></i>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                            <a ui-sref="ServicesBrowser({id:service.Service.id})"
                                               class="txt-color-blueDark">
                                                <i class="fa fa-area-chart"
                                                   ng-mouseenter="mouseenter($event, host, service)"
                                                   ng-mouseleave="mouseleave()"
                                                   ng-if="service.Service.has_graph">
                                                </i>
                                            </a>
                                        <?php else: ?>
                                            <i class="fa fa-area-chart"
                                               ng-mouseenter="mouseenter($event, host, service)"
                                               ng-mouseleave="mouseleave()"
                                               ng-if="service.Service.has_graph">
                                            </i>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <strong title="<?php echo __('Passively transferred service'); ?>"
                                                ng-show="service.Service.active_checks_enabled === false || host.is_satellite_host === true">
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
                                        <span ng-if="service.Service.active_checks_enabled && host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
                                        <span ng-if="service.Service.active_checks_enabled === false">
                                            <?php echo __('n/a'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span ng-if="service.Service.active_checks_enabled && host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
                                        <span ng-if="service.Service.active_checks_enabled === false || host.is_satellite_host === true">
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
                                            <div class="dropdown-menu dropdown-menu-right">
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
                                                <?php
                                                $AdditionalLinks = new \App\Lib\AdditionalLinks($this);
                                                echo $AdditionalLinks->getLinksAsHtmlList('services', 'index', 'list');
                                                ?>
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
                            <reschedule-service></reschedule-service>
                            <disable-notifications></disable-notifications>
                            <enable-notifications></enable-notifications>
                            <acknowledge-service author="<?php echo h($username); ?>"></acknowledge-service>
                            <service-downtime author="<?php echo h($username); ?>"></service-downtime>

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
                                    <button class="btn btn-default dropdown-toggle waves-effect waves-themed"
                                            type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo __('More actions'); ?>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start"
                                         style="position: absolute; will-change: top, left; top: 37px; left: 0px;">
                                        <?php if ($this->Acl->hasPermission('deactivate', 'Services')): ?>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                               ng-click="confirmDeactivate(getObjectsForDelete())">
                                                <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                            </a>
                                        <?php endif; ?>
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
                        <!-- ACTIVE TAB END -->

                        <!-- NOT MONITORED TAB START -->
                        <div ng-if="activeTab === 'notMonitored'">
                            <table id="service_list" class="table table-striped m-0 table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-check-square fa-lg"></i>
                                    </th>

                                    <th class="no-sort">
                                        <?php echo __('State'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('servicename')">
                                        <i class="fa" ng-class="getSortClass('servicename')"></i>
                                        <?php echo __('Service name'); ?>
                                    </th>


                                    <th class="no-sort text-center editItemWidth width-50">
                                        <i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr ng-repeat="service in services" ng-repeat-end="">

                                    <td class="width-5">
                                        <input type="checkbox"
                                               ng-model="massChange[service.Service.id]"
                                               ng-show="service.Service.allow_edit">
                                    </td>

                                    <td class="text-center width-55">
                                        <servicestatusicon service="fakeServicestatus"></servicestatusicon>
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
                                                <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                    <a href="javascript:void(0);"
                                                       ng-click="confirmDelete(getObjectForDelete(host, service))"
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

                            </div>
                            <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                            <?php echo $this->element('paginator_or_scroll'); ?>
                        </div>
                        <!-- NOT MONITORED TAB END -->

                        <!-- DISABLED TAB START -->
                        <div ng-if="activeTab === 'disabled'">
                            <table id="service_list" class="table table-striped m-0 table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-check-square fa-lg"></i>
                                    </th>

                                    <th class="no-sort">
                                        <?php echo __('State'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('servicename')">
                                        <i class="fa" ng-class="getSortClass('servicename')"></i>
                                        <?php echo __('Service name'); ?>
                                    </th>


                                    <th class="no-sort text-center editItemWidth width-50">
                                        <i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr ng-repeat="service in services" ng-repeat-end="">

                                    <td class="width-5">
                                        <input type="checkbox"
                                               ng-model="massChange[service.Service.id]"
                                               ng-show="service.Service.allow_edit">
                                    </td>

                                    <td class="text-center width-55">
                                        <servicestatusicon service="fakeServicestatus"></servicestatusicon>
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
                                                <?php if ($this->Acl->hasPermission('enable', 'services')): ?>
                                                    <a href="javascript:void(0);"
                                                       ng-if="service.Service.allow_edit"
                                                       ng-click="confirmActivate(getObjectForDelete(host, service))"
                                                       class="dropdown-item">
                                                        <i class="fa fa-plug"></i>
                                                        <?php echo __('Enable'); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                    <a href="javascript:void(0);"
                                                       ng-click="confirmDelete(getObjectForDelete(host, service))"
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

                            </div>
                            <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                            <?php echo $this->element('paginator_or_scroll'); ?>
                        </div>
                        <!-- DISABLED TAB END -->

                        <!-- DELETED TAB START -->
                        <div ng-if="activeTab === 'deleted'">
                            <table id="service_list" class="table table-striped m-0 table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="no-sort">
                                        <?php echo __('Service Name'); ?>
                                    </th>

                                    <th class="no-sort">
                                        <?php echo __('UUID'); ?>
                                    </th>

                                    <th class="no-sort">
                                        <?php echo __('Date'); ?>
                                    </th>

                                    <th class="no-sort">
                                        <?php echo __('Performance data deleted'); ?>
                                    </th>
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
                                        <i class="fa fa-times text-danger"
                                           ng-show="!service.DeletedService.perfdataDeleted"></i>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                            <?php echo $this->element('paginator_or_scroll'); ?>
                        </div>
                        <!-- DELETED TAB END -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
