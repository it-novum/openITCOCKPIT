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
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Map Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="MapsIndex">
            <i class="fa fa-map-marker"></i> <?php echo __('Maps'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-copy"></i> <?php echo __('Copy'); ?>
    </li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Copy map/s'); ?>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'maps', 'mapmodule')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='MapsIndex' class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="card margin-bottom-10" ng-repeat="sourceMap in sourceMaps">
                        <div class="card-header">
                            <i class="fa fa-map-marker"></i>
                            <?php echo __('Source map:'); ?>
                            {{sourceMap.Source.name}}
                        </div>
                        <div class="card-body">
                            <div class="form-group required" ng-class="{'has-error': sourceMap.Error.name}">
                                <label for="Map{{$index}}Name" class="control-label">
                                    <?php echo __('Map name'); ?>
                                </label>
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceMap.Map.name"
                                    id="Map{{$index}}Name">
                                <span class="help-block">
                                <?php echo __('Name of the new map'); ?>
                                </span>
                                <div ng-repeat="error in sourceMap.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                            <div class="form-group required" ng-class="{'has-error': sourceMap.Error.title}">
                                <label for="Map{{$index}}Title" class="control-label">
                                    <?php echo __('Map title'); ?>
                                </label>
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceMap.Map.title"
                                    id="Map{{$index}}Title">
                                <span class="help-block">
                                <?php echo __('Title of the new map'); ?>
                                </span>
                                <div ng-repeat="error in sourceMap.Error.title">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                            <div class="form-group required" ng-class="{'has-error': sourceMap.Error.refresh_interval}">
                                <label for="Map{{$index}}RefreshInterval" class="control-label">
                                    <?php echo __('Refresh Interval'); ?>
                                </label>
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceMap.Map.refresh_interval"
                                    id="Map{{$index}}RefreshInterval">
                                <span class="help-block">
                                <?php echo __('Automatic maps update interval in seconds'); ?>
                                </span>
                                <div ng-repeat="error in sourceMap.Error.refresh_interval">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card margin-top-10">
                        <div class="card-body">
                            <div class="float-right">
                                <button class="btn btn-primary" ng-click="copy()">
                                    <?php echo __('Copy'); ?>
                                </button>
                                <?php if ($this->Acl->hasPermission('index', 'maps', 'MapModule')): ?>
                                    <a back-button href="javascript:void(0);" fallback-state='MapsIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
