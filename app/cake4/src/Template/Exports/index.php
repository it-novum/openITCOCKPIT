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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-retweet fa-fw "></i>
            <?= __('Monitoring') ?>
            <span>>
                <?= __('Apply configuration'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget jarviswidget-color-blueDark">
    <header>
        <span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
        <h2><?= __('Refresh monitoring configuration'); ?> </h2>
    </header>

    <div>
        <div class="widget-body" ng-if="!gearmanReachable">
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-block">
                        <h5 class="alert-heading">
                            <i class="fa fa-warning"></i> <?= __('Critical error!'); ?>
                        </h5>
                        <?= __('Could not connect to Gearman Job Server'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-body" ng-if="gearmanReachable">


            <div class="form-group">
                <div class="col-xs-12 smart-form">
                    <label class="checkbox no-required">
                        <input type="checkbox" name="checkbox"
                               id="createBackupCheckbox"
                               ng-true-value="1"
                               ng-false-value="0"
                               ng-model="post.create_backup">
                        <i class="checkbox-primary"></i>
                        <?= __('Create backup of current configuration'); ?>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                    <div class="jarviswidget padding-top-15" ng-show="useSingleInstanceSync">
                        <header>
                                <span class="widget-icon">
                                    <i class="fa fa-cloud"></i>
                                </span>
                            <h2><?= __('Select instances which the new configuration should get pushed.'); ?></h2>
                        </header>
                        <div>
                            <div class="widget-body">

                                <table id="host_list"
                                       class="table table-striped table-hover table-bordered smart-form"
                                       style="">
                                    <thead>
                                    <tr>
                                        <th class="no-sort width-15">
                                            <i class="fa fa-check-square-o fa-lg"></i>
                                        </th>

                                        <th class="no-sort">
                                            <?= __('Instances name'); ?>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="satellite in satellites">
                                        <td>
                                            <label class="checkbox no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-disabled="exportRunning"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="satellite.sync_instance">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </td>
                                        <td>
                                            {{ satellite.name }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="row margin-top-10 margin-bottom-10">
                                    <div class="col-xs-12 col-md-2 text-muted text-center">
                                        <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <span ng-click="selectAll()" class="pointer">
                                            <i class="fa fa-lg fa-check-square-o"></i>
                                            <?php echo __('Select all'); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <span ng-click="undoSelection()" class="pointer">
                                            <i class="fa fa-lg fa-square-o"></i>
                                            <?php echo __('Undo selection'); ?>
                                        </span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <button class="btn btn-xs btn-default"
                                                ng-click="saveInstanceConfigSyncSelection();">
                                            <?= __('Save selection'); ?>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">


                <div id="exportError" class="col-xs-12 padding-top-20" ng-show="!exportSuccessfully">
                    <div class="alert alert-danger alert-block">
                        <h4 class="alert-heading"><i class="fa fa-times"></i> <?= __('Error'); ?></h4>
                        <?= __('Error while refreshing monitoring configuration.'); ?>
                    </div>
                </div>

                <div id="verifyError" class="col-xs-12 padding-top-20" ng-show="verificationErrors.length > 0">
                    <div class="alert alert-danger alert-block">
                        <h4 class="alert-heading"><i
                                class="fa fa-times"></i> <?= __('New configuration is invalid'); ?>
                        </h4>
                        &nbsp;
                        <div class="well txt-color-blueDark" id="verifyOutput">
                            {{verificationErrors}}
                        </div>
                    </div>
                </div>

                <div id="exportInfo" ng-show="exportRunning || showLog">
                    <div class="col-xs-12">
                        <div>
                            <h4><?= __('Tasks'); ?></h4>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="well" id="exportLog">
                            <div ng-repeat="task in tasks">
                                <i class="fa fa-spin fa-refresh" ng-show="task.finished === 0"></i>
                                <i class="fa fa-check text-success"
                                   ng-show="task.finished === 1 && task.successfully === 1"></i>
                                <i class="fa fa-times text-danger"
                                   ng-show="task.finished === 1 && task.successfully === 0"></i>
                                <span class="code-font txt-color-grayDark">[{{task.modified}}]</span>
                                <span>{{task.text}}</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">

                            <label class="text-info padding-right-10" ng-show="exportRunning">
                                <i class="fa fa-info-circle"></i>
                                <?= _('Refresh in progress '); ?>
                            </label>

                            <button class="btn btn-success" type="button"
                                    ng-disabled="exportRunning" ng-click="launchExport();">
                                <i class="fa fa-rocket" ng-show="!exportRunning"></i>
                                <i class="fa fa-spin fa-spinner" ng-show="exportRunning"></i>
                                <?= __('Launch refresh'); ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
