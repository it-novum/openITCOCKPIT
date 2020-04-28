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
        <a ui-sref="ContainersIndex">
            <i class="fas fa-globe-americas"></i> <?php echo __('Container Map'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-eye"></i> <?php echo __('Overview'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-lg-12 margin-bottom-10">
        <select
            id="containers"
            data-placeholder="<?php echo __('Please select...'); ?>"
            class="form-control"
            chosen="{containers}"
            ng-model="post.Container.id"
            callback="loadContainers"
            ng-options="container.key as container.value for container in containers"
            ng-model-options="{debounce: 500}">
        </select>

    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Container map'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <div class="form-group no-margin padding-right-10">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by container name'); ?>"
                                   ng-model="filter.Hosts.address"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>
                    <div class="form-group no-margin padding-right-10">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by host name'); ?>"
                                   ng-model="filter.Hosts.name"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox padding-right-10">
                        <input type="checkbox"
                               id="showAll"
                               class="custom-control-input"
                               name="checkbox"
                               checked="checked"
                               ng-model="filter.expandAll"
                               ng-model-options="{debounce: 500}"
                               ng-true-value="false"
                               ng-false-value="true">
                        <label
                            class="custom-control-label no-margin"
                            for="showAll"> <?php echo __('Expand all'); ?></label>
                    </div>

                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="clearFilter();">
                        <i class="fas fa-undo"></i> <?php echo __('Reset'); ?>
                    </button>

                    <button class="btn btn-xs btn-success shadow-0" ng-click="toggleFullscreenMode()"
                            title="<?php echo __('Fullscreen mode'); ?>">
                        <i class="fa fa-expand-arrows-alt"></i>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div class="margin-top-10" ng-if="isEmpty">
                            <div class="text-center text-danger italic">
                                <?php echo __('No entries match the selection'); ?>
                            </div>
                        </div>
                        <div id="containermap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="statusmap-progress-icon">
    <center>
        <div>
            <?php echo __('Loading visualization for '); ?>
        </div>
        <div>
            {{nodesCount}} <?php echo __(' nodes'); ?>
            <span class="statusmap-progress-dots"></span>
        </div>
    </center>
    <div class="progress" data-progress="0">
        <div class="progress_mask isFull">
            <div class="progress_fill"></div>
        </div>
        <div class="progress_mask">
            <div class="progress_fill"></div>
        </div>
    </div>
</div>

