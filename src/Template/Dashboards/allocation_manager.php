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
                                                   placeholder="<?php echo __('Filter by name'); ?>"
                                                   ng-model="filter.DashboardTab.name"
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
                                                   placeholder="<?php echo __('Filter by full name'); ?>"
                                                   ng-model="filter.full_name"
                                                   ng-model-options="{debounce: 500}">
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
                                <th class="no-sort" ng-click="orderBy('full_name')">
                                    <i class="fa" ng-class="getSortClass('full_name')"></i>
                                    <?php echo __('Full Name'); ?>
                                </th>
                                <th class="no-sort">
                                    <i class="fa"></i>
                                    <?php echo __('Usergroups'); ?>
                                </th>
                                <th class="no-sort">
                                    <i class="fa"></i>
                                    <?php echo __('Users'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Flags')">
                                    <i class="fa" ng-class="getSortClass('Flags')"></i>
                                    <?php echo __('Pinned'); ?>
                                </th>
                                <th class="no-sort text-center">
                                    <i class="fa fa-cog"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="dashboardTab in dashboardTabs">
                                <td class="text-center width-15">
                                    <input type="checkbox"
                                           ng-model="massChange[dashboardTab.id]">
                                </td>
                                <td>{{dashboardTab.name}}</td>
                                <td>{{dashboardTab.full_name}}</td>
                                <td>
                                    {{dashboardTab.usergroups_count}}
                                    <span class="badge badge-primary" ng-repeat="name in dashboardTab.usergroups_names">
                                            {{name}}
                                        </span>
                                </td>
                                <td>
                                    {{dashboardTab.allocated_users_count}}
                                    <span class="badge badge-primary"
                                          ng-repeat="name in dashboardTab.allocated_users_names">
                                            {{name}}
                                        </span>
                                </td>
                                <td class="width-50">
                                    <i class="fa fa-lock" ng-show="dashboardTab.isPinned"></i>
                                </td>

                                <td class="width-50">
                                    <div class="btn-group btn-group-xs" role="group">
                                        <a ui-sref="DashboardsAllocate({id: dashboardTab.id})"
                                           class="btn btn-default btn-lower-padding">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-default dropdown-toggle btn-lower-padding"
                                                data-toggle="dropdown">
                                            <i class="caret"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a ng-click="confirmDelete(getObjectForDelete(dashboardTab))"
                                               class="dropdown-item txt-color-red">
                                                <i class="fa fa-trash"></i>
                                                <?php echo __('Delete'); ?>
                                            </a>
                                        </div>
                                    </div>
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
                            <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fas fa-trash"></i>
                                    <?php echo __('Remove Allocations'); ?>
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


<!-- ANGAULAR DIRECTIVES -->
<massdelete></massdelete>
