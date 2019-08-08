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
            <i class="fa fa-clock-o fa-fw "></i>
            <?php echo __('Administration') ?>
            <span>>
                <?php echo __('Cron jobs'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php if ($this->Acl->hasPermission('add', 'Cronjobs')): ?>
                            <button type="button" class="btn btn-xs btn-success" ng-click="triggerAddModal()">
                                <i class="fa fa-plus"></i> <?php echo __('New'); ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>
                    </div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-clock-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Cron jobs overview'); ?> </h2>

                </header>

                <div>
                    <div class="widget-body no-padding">
                        <div class="mobile_table">
                            <table id="systemfailure_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort"><?php echo __('Task') ?></th>
                                    <th class="no-sort"><?php echo __('Plugin') ?></th>
                                    <th class="no-sort"><?php echo __('Interval'); ?></th>
                                    <th class="no-sort"><?php echo __('Last scheduled'); ?></th>
                                    <th class="no-sort"><?php echo __('Is currently running'); ?></th>
                                    <th class="no-sort"><?php echo __('Enabled'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="cronjob in cronjobs">
                                    <td>{{cronjob.Cronjob.task}}</td>
                                    <td>{{cronjob.Cronjob.plugin}}</td>
                                    <td>{{cronjob.Cronjob.interval}}</td>
                                    <td>{{cronjob.Cronschedule.last_scheduled_usertime}}</td>
                                    <td class="text-center" ng-if="cronjob.Cronschedules.is_running == 0">
                                        <?php echo __('No'); ?>
                                    </td>
                                    <td class="text-center" ng-if="cronjob.Cronschedules.is_running != 0">
                                        <?php echo __('Yes'); ?>
                                    </td>
                                    <td class="text-align-center" ng-if="cronjob.Cronjob.enabled">
                                        <i class="fa fa-check text-success"></i>
                                    </td>
                                    <td class="text-align-center" ng-if="!cronjob.Cronjob.enabled">
                                        <i class="fa fa-times text-danger"></i>
                                    </td>
                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit', 'cronjobs')): ?>
                                                <a ng-click="triggerEditModal(cronjob.Cronjob);"
                                                   href="javascript:void(0);"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{cronjob.Cronjob.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'cronjobs')): ?>
                                                    <li>
                                                        <a ng-click="triggerEditModal(cronjob.Cronjob);"
                                                           href="javascript:void(0);">
                                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
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
                        <div class="noMatch" ng-if="cronjobs.length == 0">
                            <center>
                                <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                            </center>
                        </div>
                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" style="line-height: 32px;"
                                         id="datatable_fixed_column_info"></div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>


<!-- Add cronjob modal -->
<div id="addCronjobModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-usd"></i>
                    <?php echo __('Add Cronjob'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Plugin'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="AddCronjobPluginSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availablePlugins"
                                    ng-options="value as value for (key , value) in availablePlugins"
                                    ng-model="post.Cronjob.plugin">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Task'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="AddCronjobTaskSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availableTasks"
                                    ng-options="value as value for (key , value) in availableTasks"
                                    ng-model="post.Cronjob.task">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.interval}">
                            <label class="label hintmark_red">
                                <?php echo __('Interval'); ?>
                            </label>
                            <label class="input">
                                <b class="icon-prepend">
                                    <i class="fa fa-clock-o"></i>
                                </b>
                                <input type="number" class="input-sm" min="0"
                                       ng-model="post.Cronjob.interval">
                            </label>
                            <div ng-repeat="error in errors.interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Cronjob schedule interval in minutes'); ?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 smart-form padding-top-15">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.enabled}">
                            <label class="label hintmark_red">
                                <?php echo __('Enabled'); ?>
                            </label>
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.Cronjob.enabled">
                                <i class="checkbox-primary"></i>

                                <div ng-repeat="error in errors.enabled">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="help-block">
                                    <?php echo __('Determine if this cronjob should be executed.'); ?>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveCronjob()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Edit cronjob modal -->
<div id="editCronjobModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-usd"></i>
                    <?php echo __('Edit Cronjob'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Plugin'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="EditCronjobPluginSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availablePlugins"
                                    ng-options="value as value for (key , value) in availablePlugins"
                                    ng-model="editPost.Cronjob.plugin">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Task'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="EditCronjobTaskSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availableTasks"
                                    ng-options="value as value for (key , value) in availableTasks"
                                    ng-model="editPost.Cronjob.task">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.interval}">
                            <label class="label hintmark_red">
                                <?php echo __('Interval'); ?>
                            </label>
                            <label class="input">
                                <b class="icon-prepend">
                                    <i class="fa fa-clock-o"></i>
                                </b>
                                <input type="number" class="input-sm" min="0"
                                       ng-model="editPost.Cronjob.interval">
                            </label>
                            <div ng-repeat="error in errors.interval">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Cronjob schedule interval in minutes'); ?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 smart-form padding-top-15">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.enabled}">
                            <label class="label hintmark_red">
                                <?php echo __('Enabled'); ?>
                            </label>
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       ng-model="editPost.Cronjob.enabled">
                                <i class="checkbox-primary"></i>

                                <div ng-repeat="error in errors.enabled">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="help-block">
                                    <?php echo __('Determine if this cronjob should be executed.'); ?>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteCronjob()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="editCronjob()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>