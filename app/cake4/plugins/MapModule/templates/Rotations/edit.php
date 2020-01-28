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
            <i class="fa fa-retweet fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Rotation'); ?>
            </span>
            <div class="third_level"> <?php echo __('Edit'); ?></div>
        </h1>
    </div>
</div>


<confirm-delete></confirm-delete>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
        <h2><?php echo __('Edit map rotation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete', 'rotations', 'MapModule')): ?>
                <button type="button" class="btn btn-danger btn-xs" ng-click="confirmDelete(rotation)">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <a ui-sref="RotationsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                id="RotationContainer"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Rotation.container_id"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Rotation Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Rotation.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.interval}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Rotation interval'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="number" ng-model="post.Rotation.interval" min="10"
                                   step="5">
                            <div ng-repeat="error in errors.interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.Map}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Maps'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                id="RotationMaps"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="maps"
                                ng-options="map.key as map.value for map in maps"
                                ng-model="post.Rotation.Map"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.Map">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="<?= __('Save') ?>">&nbsp;
                                <a ui-sref="RotationsIndex" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
