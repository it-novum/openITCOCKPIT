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
        <a ui-sref="RotationsIndex">
            <i class="fa fa-retweet"></i> <?php echo __('Rotations'); ?>
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
                    <?php echo __('Create new map rotation'); ?>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'rotations', 'MapModule')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='RotationsIndex' class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Rotation'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
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
                                ng-model="post.Rotation.container_id"
                                multiple>
                            </select>
                            <div ng-show="post.Rotation.container_id < 1" class="warning-glow">
                                <?php echo __('Please select a container.'); ?>
                            </div>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.name}">
                            <label class="control-label">
                                <?php echo __('Rotation Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Rotation.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.interval}">
                            <label class="control-label">
                                <?php echo __('Rotation interval'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="number"
                                ng-model="post.Rotation.interval"
                                min="10"
                                step="5">
                            <div ng-repeat="error in errors.interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.maps}">
                            <label class="control-label" for="MapsSelect">
                                <?php echo __('Maps'); ?>
                            </label>
                            <select
                                id="MapsSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="maps"
                                ng-options="map.key as map.value for map in maps"
                                ng-model="post.Rotation.Map"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.maps">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary" type="submit">
                                        <?php echo __('Create rotation'); ?>
                                    </button>
                                    <a back-button href="javascript:void(0);" fallback-state='RotationsIndex' class="btn btn-default">
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
