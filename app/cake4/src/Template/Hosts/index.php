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
        <a ui-sref="DeletedHostsIndex">
            <i class="fa fa-desktop"></i> <?php echo __('Hosts'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-stethoscope"></i> <?php echo __('Monitored'); ?>
    </li>
</ol>


<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<query-handler-directive></query-handler-directive>
<massdelete></massdelete>
<massdeactivate></massdeactivate>
<reschedule-host></reschedule-host>
<disable-host-notifications></disable-host-notifications>
<enable-host-notifications></enable-host-notifications>
<acknowledge-host author="<?php echo h($username); ?>"></acknowledge-host>
<host-downtime author="<?php echo h($username); ?>"></host-downtime>

<?php if ($this->Acl->hasPermission('add', 'hostgroups')): ?>
    <add-hosts-to-hostgroup></add-hosts-to-hostgroup>
<?php endif; ?>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Hosts'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" ui-sref="HostsIndex" role="tab">
                                    <i class="fa fa-stethoscope">&nbsp;</i> <?php echo __('Monitored'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'hosts')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="HostsNotMonitored" role="tab">
                                    <i class="fa fa-user-md">&nbsp;</i> <?php echo __('Not monitored'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'hosts')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="HostsDisabled" role="tab">
                                    <i class="fa fa-power-off">&nbsp;</i> <?php echo __('Disabled'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('index', 'DeletedHosts')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="DeletedHostsIndex" role="tab">
                                    <i class="fa fa-trash">&nbsp;</i> <?php echo __('Deleted'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('add', 'hosts')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="HostsAdd">
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
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by IP address'); ?>"
                                                   ng-model="filter.Host.address"
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
                                                   ng-model="filter.Hoststatus.output"
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
                                                       class="form-control form-control-sm"
                                                       data-role="tagsinput"
                                                       id="ServicesKeywordsInput"
                                                       placeholder="<?php echo __('Filter by tags'); ?>"
                                                       ng-model="filter.Host.keywords"
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
                                                       ng-model="filter.Host.not_keywords"
                                                       ng-model-options="{debounce: 500}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xs-12 col-lg-3">
                                    <fieldset>
                                        <h5><?php echo __('Host status'); ?></h5>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterUp"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.current_state.up"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label custom-control-label-ok"
                                                       for="statusFilterUp"><?php echo __('Up'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterDown"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.current_state.down"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label custom-control-label-down"
                                                       for="statusFilterDown"><?php echo __('Down'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="statusFilterUnreachable"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.current_state.unreachable"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label custom-control-label-unreachable"
                                                       for="statusFilterUnreachable"><?php echo __('Unknown'); ?></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>


                                <div class="col-xs-12 col-lg-3">
                                    <fieldset>
                                        <h5><?php echo __('Acknowledgements'); ?></h5>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="ackFilterAck"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.acknowledged"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label"
                                                       for="ackFilterAck"><?php echo __('Acknowledge'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="ackFilterNotAck"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.not_acknowledged"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label"
                                                       for="ackFilterNotAck"><?php echo __('Not acknowledged'); ?></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xs-12 col-lg-3">
                                    <fieldset>
                                        <h5><?php echo __('Downtimes'); ?></h5>
                                        <div class="form-group smart-form">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="downtimwFilterInDowntime"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.in_downtime"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label"
                                                       for="downtimwFilterInDowntime"><?php echo __('In downtime'); ?></label>
                                            </div>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       id="downtimwFilterNotInDowntime"
                                                       class="custom-control-input"
                                                       name="checkbox"
                                                       checked="checked"
                                                       ng-model="filter.Hoststatus.not_in_downtime"
                                                       ng-model-options="{debounce: 500}">
                                                <label class="custom-control-label"
                                                       for="downtimwFilterNotInDowntime"><?php echo __('Not in downtime'); ?></label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <?php if (sizeof($satellites) > 1): ?>
                                    <div class="col-xs-12 col-md-3">
                                        <fieldset>
                                            <legend><?php echo __('Instance'); ?></legend>
                                            <div class="form-group smart-form">
                                                <select
                                                    id="Instance"
                                                    data-placeholder="<?php echo __('Filter by instance'); ?>"
                                                    class="form-control"
                                                    chosen="{}"
                                                    multiple
                                                    ng-model="filter.Host.satellite_id"
                                                    ng-model-options="{debounce: 500}">
                                                    <?php
                                                    foreach ($satellites as $satelliteId => $satelliteName):
                                                        printf('<option value="%s">%s</option>', h($satelliteId), h($satelliteName));
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php endif; ?>
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
                        <table class="table table-striped m-0 table-bordered table-hover table-sm">
                            <thead>
                            <tr>
                                <th colspan="2" class="no-sort width-90"
                                    ng-click="orderBy('Hoststatus.current_state')">
                                    <i class="fa" ng-class="getSortClass('Hoststatus.current_state')"></i>
                                    <?php echo __('Hoststatus'); ?>
                                </th>

                                <th class="no-sort text-center">
                                    <i class="fa fa-user" title="<?php echo __('is acknowledged'); ?>"></i>
                                </th>

                                <th class="no-sort text-center">
                                    <i class="fa fa-power-off"
                                       title="<?php echo __('is in downtime'); ?>"></i>
                                </th>

                                <th class="no-sort text-center">
                                    <i title="<?php echo __('Shared'); ?>" class="fa fa-sitemap"></i>
                                </th>

                                <th class="no-sort text-center">
                                    <strong title="<?php echo __('Passively transferred host'); ?>">P</strong>
                                </th>

                                <th class="no-sort" ng-click="orderBy('Hosts.name')">
                                    <i class="fa" ng-class="getSortClass('Hosts.name')"></i>
                                    <?php echo __('Host name'); ?>
                                </th>

                                <th class="no-sort" ng-click="orderBy('Hosts.address')">
                                    <i class="fa" ng-class="getSortClass('Hosts.address')"></i>
                                    <?php echo __('IP address'); ?>
                                </th>

                                <th class="no-sort tableStatewidth"
                                    ng-click="orderBy('Hoststatus.last_state_change')">
                                    <i class="fa" ng-class="getSortClass('Hoststatus.last_state_change')"></i>
                                    <?php echo __('Last state change'); ?>
                                </th>

                                <th class="no-sort tableStatewidth" ng-click="orderBy('Hoststatus.last_check')">
                                    <i class="fa" ng-class="getSortClass('Hoststatus.last_check')"></i>
                                    <?php echo __('Last check'); ?>
                                </th>

                                <th class="no-sort" ng-click="orderBy('Hoststatus.output')">
                                    <i class="fa" ng-class="getSortClass('Hoststatus.output')"></i>
                                    <?php echo __('Host output'); ?>
                                </th>

                                <th class="no-sort" ng-click="orderBy('Hosts.satellite_id')">
                                    <i class="fa" ng-class="getSortClass('Hosts.satellite_id')"></i>
                                    <?php echo __('Instance'); ?>
                                </th>
                                <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                    <th class="text-center">
                                        <?php echo __('Service Summary '); ?>
                                    </th>
                                <?php endif; ?>
                                <th class="no-sort text-center editItemWidth"><i class="fa fa-gear"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="host in hosts">
                                <td class="width-5">
                                    <input type="checkbox"
                                           ng-model="massChange[host.Host.id]"
                                           ng-show="host.Host.allow_edit">
                                </td>

                                <td class="text-center">
                                    <hoststatusicon host="host"></hoststatusicon>
                                </td>

                                <td class="text-center">
                                    <i class="far fa-user"
                                       ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                                       ng-if="host.Hoststatus.acknowledgement_type == 1"></i>

                                    <i class="fas fa-user"
                                       ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                                       ng-if="host.Hoststatus.acknowledgement_type == 2"
                                       title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                </td>

                                <td class="text-center">
                                    <i class="fa fa-power-off"
                                       ng-show="host.Hoststatus.scheduledDowntimeDepth > 0"></i>
                                </td>

                                <td class="text-center">

                                    <a class="txt-color-blueDark" title="<?php echo __('Shared'); ?>"
                                       ng-if="host.Host.allow_sharing === true && host.Host.containerIds.length > 1"
                                       ui-sref="HostsSharing({id:host.Host.id})">
                                        <i class="fa fa-sitemap"></i></a>

                                    <i class="fa fa-low-vision txt-color-blueLight"
                                       ng-if="host.Host.allow_sharing === false && host.Host.containerIds.length > 1"
                                       title="<?php echo __('Restricted view'); ?>"></i>
                                </td>

                                <td class="text-center">
                                    <strong title="<?php echo __('Passively transferred host'); ?>"
                                            ng-show="host.Host.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                        P
                                    </strong>
                                </td>

                                <td>
                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a ui-sref="HostsBrowser({id:host.Host.id})">
                                            {{ host.Host.hostname }}
                                        </a>
                                    <?php else: ?>
                                        {{ host.Host.hostname }}
                                    <?php endif; ?>
                                </td>

                                <td>
                                    {{ host.Host.address }}
                                </td>

                                <td>
                                    {{ host.Hoststatus.last_state_change }}
                                </td>

                                <td>
                                    {{ host.Hoststatus.lastCheck }}
                                </td>

                                <td>
                                    {{ host.Hoststatus.output }}
                                </td>

                                <td>
                                    {{ host.Host.satelliteName }}
                                </td>
                                <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                    <td class="width-160">
                                        <div class="btn-group btn-group-justified" role="group" style="width: 100%">
                                            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                                <a class="btn btn-success state-button-small"
                                                   ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][0]=1">
                                                    {{host.ServicestatusSummary.state['ok']}}
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-success state-button-small">
                                                    {{host.ServicestatusSummary.state['ok']}}
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                                <a class="btn btn-warning state-button-small"
                                                   ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][1]=1">
                                                    {{host.ServicestatusSummary.state['warning']}}
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-warning state-button">
                                                    {{host.ServicestatusSummary.state['warning']}}
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                                <a class="btn btn-danger state-button-small"
                                                   ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][2]=1">
                                                    {{host.ServicestatusSummary.state['critical']}}
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-danger state-button-small">
                                                    {{host.ServicestatusSummary.state['critical']}}
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                                <a class="btn btn-default state-button-small"
                                                   ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][3]=1">
                                                    {{host.ServicestatusSummary.state['unknown']}}
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-default state-button-small">
                                                    {{host.ServicestatusSummary.state['unknown']}}
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <td class="width-50">
                                    <div class="btn-group btn-group-xs" role="group">
                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                            <a ui-sref="HostsEdit({id:host.Host.id})"
                                               ng-if="host.Host.allow_edit"
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
                                            <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                <a ui-sref="HostsEdit({id:host.Host.id})"
                                                   ng-if="host.Host.allow_edit"
                                                   class="dropdown-item">
                                                    <i class="fa fa-cog"></i>
                                                    <?php echo __('Edit'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('sharing', 'hosts')): ?>
                                                <a ui-sref="HostsSharing({id:host.Host.id})"
                                                   ng-if="host.Host.allow_sharing"
                                                   class="dropdown-item">
                                                    <i class="fa fa-sitemap fa-rotate-270"></i>
                                                    <?php echo __('Sharing'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('deactivate', 'hosts')): ?>
                                                <a ng-if="host.Host.allow_edit"
                                                   class="dropdown-item"
                                                   href="javascript:void(0);"
                                                   ng-click="confirmDeactivate(getObjectForDelete(host))">
                                                    <i class="fa fa-plug"></i>
                                                    <?php echo __('Disable'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                                <a ui-sref="ServicesServiceList({id: host.Host.id})"
                                                   class="dropdown-item">
                                                    <i class="fa fa-sitemap fa-rotate-270"></i>
                                                    <?php echo __('Service list'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                                                <a ui-sref="ServicetemplategroupsAllocateToHost({id: 0, hostId: host.Host.id})"
                                                   class="dropdown-item">
                                                    <i class="fas fa-external-link-alt"></i>
                                                    <?php echo __('Allocate service template group'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('add', 'hostgroups', '')): ?>
                                                <a class="dropdown-item"
                                                   href="javascript:void(0);"
                                                   ng-click="confirmAddHostsToHostgroup(getObjectForDelete(host))">
                                                    <i class="fa fa-sitemap"></i>
                                                    <?php echo __('Append to host group'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('scan', 'agentconfigs', '')): ?>
                                                <a ui-sref="AgentconfigsConfig({hostId: host.Host.id})"
                                                   class="dropdown-item">
                                                    <i class="fa fa-user-secret"></i>
                                                    <?php echo __('openITCOCKPIT Agent discovery'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('delete', 'hosts')): ?>
                                                <a g-click="confirmDelete(getObjectForDelete(host))"
                                                   class="dropdown-item txt-color-red">
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

                        <div class="margin-top-10" ng-show="hosts.length == 0">
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
                                <a ui-sref="HostsCopy({ids: linkForCopy()})" class="a-clean">
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
                                    <?php if ($this->Acl->hasPermission('edit_details', 'Hosts', '')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ui-sref="HostsEditDetails({ids: linkForEditDetails()})">
                                            <i class="fa fa-cog"></i>
                                            <?php echo __('Edit details'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('deactivate', 'Hosts', '')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="confirmDeactivate(getObjectsForDelete())">
                                            <i class="fa fa-plug"></i>
                                            <?php echo __('Disable'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'hostgroups', '')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="confirmAddHostsToHostgroup(getObjectsForDelete())">
                                            <i class="fa fa-sitemap"></i>
                                            <?php echo __('Add to host group'); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="rescheduleHost(getObjectsForExternalCommand())">
                                            <i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="hostDowntime(getObjectsForExternalCommand())">
                                            <i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="acknowledgeHost(getObjectsForExternalCommand())">
                                            <i class="fa fa-user"></i> <?php echo __('Acknowledge host status'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="disableHostNotifications(getObjectsForExternalCommand())">
                                            <i class="fa fa-envelope"></i> <?php echo __('Disable notification'); ?>
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                           ng-click="enableHostNotifications(getObjectsForExternalCommand())">
                                            <i class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
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
        </div>
    </div>
</div>
