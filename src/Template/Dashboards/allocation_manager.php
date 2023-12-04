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
        <a ui-sref="UsersIndex">
            <i class="fa fa-table"></i> <?php echo __('Users'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-table"></i> <?php echo __('Dashboard Allocation'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Dashboards'); ?>
                    <span class="fw-300"><i><?php echo __('Allocation'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
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
                                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by full name'); ?>"
                                                   ng-model="filter.full_name"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by email'); ?>"
                                                   ng-model="filter.Users.email"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by phone'); ?>"
                                                   ng-model="filter.Users.phone"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm"
                                                   placeholder="<?php echo __('Filter by company'); ?>"
                                                   ng-model="filter.Users.company"
                                                   ng-model-options="{debounce: 500}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-12 margin-bottom-10">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-users"></i></span>
                                            </div>
                                            <select
                                                    id="UserRoles"
                                                    data-placeholder="<?php echo __('Filter by user role'); ?>"
                                                    class="form-control"
                                                    chosen="usergroups"
                                                    multiple
                                                    ng-model="filter.Users.usergroup_id"
                                                    ng-options="usergroup.key as usergroup.value for usergroup in usergroups">
                                            </select>
                                        </div>
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
                    <!-- Filter End -->

                    <div class="frame-wrap">
                        <table class="table table-striped m-0 table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="no-sort width-15">
                                        <i class="fa fa-check-square"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('name')">
                                        <i class="fa" ng-class="getSortClass('name')"></i>
                                        <?php echo __('Name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('usergroups')">
                                        <i class="fa" ng-class="getSortClass('usergroups')"></i>
                                        <?php echo __('Usergroups'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Users')">
                                        <i class="fa" ng-class="getSortClass('Users')"></i>
                                        <?php echo __('Users'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Pinned')">
                                        <i class="fa" ng-class="getSortClass('Pinned')"></i>
                                        <?php echo __('Pinned'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="dashboardTab in dashboardTabs">
                                    <td class="text-center" class="width-15">
                                        <input type="checkbox"
                                               ng-model="massChange[dashboardTab.id]">
                                    </td>
                                    <td>{{dashboardTab.name}}</td>
                                    <td>
                                        {{dashboardTab.usergroups_count}}
                                        <span class="badge badge-primary" ng-repeat="name in dashboardTab.usergroups_names">
                                            {{name}}
                                        </span>
                                    </td>
                                    <td>
                                        {{dashboardTab.allocated_users_count}}
                                        <span class="badge badge-primary" ng-repeat="name in dashboardTab.allocated_users_names">
                                            {{name}}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fa fa-lock" ng-show="dashboardTab.locked"></i>
                                    </td>
                                    <td>
                                        <a data-did="{{dashboardTab.id}}" ng-click="manageAllocation(dashboardTab.id)"
                                           class="btn btn-sm btn-default">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="margin-top-10" ng-show="dashboardTabs.length == 0">
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
                            <?php if ($this->Acl->hasPermission('delete', 'users')): ?>
                                <div class="col-xs-12 col-md-2 txt-color-red">
                                    <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                        <i class="fas fa-trash"></i>
                                        <?php echo __('Delete selected'); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
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


<!--- Allocate Dashboard Modal --->
<div id="allocateDashboardModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-user"></i>
                    <?php echo __('Allocate Dashboard'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Select Users -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group margin-top-20 padding-bottom-10">
                            <label class="control-label">
                                <?php echo __('Allocated Users'); ?>
                            </label>
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="users"
                                    ng-options="user.key as user.value for user in users"
                                    ng-model="allocation.DashboardTab.AllocatedUsers._ids"
                                    multiple="multiple">
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Select Roles -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group margin-top-20 padding-bottom-10">
                            <label class="control-label">
                                <?php echo __('Allocated Roles'); ?>
                            </label>
                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="usergroups"
                                    ng-options="usergroup.id as usergroup.name for usergroup in usergroups"
                                    ng-model="allocation.DashboardTab.usergroups._ids"
                                    multiple="multiple">
                            </select>
                        </div>
                    </div>
                </div>

                <!-- pinDashboard -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group margin-top-20 padding-bottom-10">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="pinDashboard"
                                       ng-model="allocation.DashboardTab.flags">
                                <label class="custom-control-label" for="pinDashboard">
                                    <?php echo __('Pin Dashboard'); ?>
                                </label>
                            </div>
                            <div class="help-block"><?php echo __('If enabled, this dashboard will be pinned at the left most tab.'); ?></div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            Currently, dashboard <i>Fake 123</i> is set up as pimary. This will be removed now.
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="saveAllocation()">
                    <?php echo __('Refresh Allocation'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>