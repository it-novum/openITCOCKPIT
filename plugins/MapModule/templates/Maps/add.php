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
        <i class="fa fa-plus"></i> <?php echo __('Add'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Create new map'); ?>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'maps', 'mapmodule')): ?>
                        <a back-button fallback-state='MapsIndex' class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Map'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                        <div class="form-group required" ng-class="{'has-error': errors.containers}">
                            <label class="control-label" for="ContainersSelect">
                                <?php echo __('Container'); ?>
                            </label>
                            <select
                                id="ContainersSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Map.containers._ids"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.name}">
                            <label class="control-label">
                                <?php echo __('Map Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Map.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.title}">
                            <label class="control-label">
                                <?php echo __('Map Title'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Map.title">
                            <div ng-repeat="error in errors.title">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.refresh_interval}">
                            <label class="control-label">
                                <?php echo __('Refresh interval'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="number"
                                ng-model="post.Map.refresh_interval"
                                min="5"
                                max="180">
                            <div ng-repeat="error in errors.refresh_interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <label>
                                        <input type="checkbox" ng-model="data.createAnother">
                                        <?php echo __('Create another'); ?>
                                    </label>
                                    <button class="btn btn-primary" type="submit">
                                        <?php echo __('Create map'); ?>
                                    </button>
                                    <a back-button fallback-state='MapsIndex' class="btn btn-default">
                                        <?php echo __('Cancel'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
