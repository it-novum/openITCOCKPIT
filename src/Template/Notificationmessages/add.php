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
        <a ui-sref="NotificationMessagesIndex">
            <i class="far fa-bell"></i> <?php echo __('Notification Messages'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('Create'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">

        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Create new Message'); ?>
                </h2>
                <div class="toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'calendars')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='NotificationMessagesIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>

                </div>

            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form class="form-horizontal" ng-submit="submit()">
                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.messages.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.message }">
                            <label class="control-label">
                                <?php echo __('Message'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.messages.message">
                            <div ng-repeat="error in errors.message">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.date}">
                            <label class="control-label">
                                <?php echo __('DateTime'); ?>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-prepend far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input
                                    id=""
                                    class="form-control col-lg-2"
                                    type="date"
                                    ng-model="post.messages.date"
                                    placeholder="<?php echo __('DD.MM.YYYY'); ?>">

                                <div class="input-group-append input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend far fa-clock"></i></span>
                                </div>
                                <input
                                    class="form-control col"
                                    ng-model="post.messages.time"
                                    ng-model-options="{timeSecondsFormat:'ss', timeStripZeroSeconds: true}"
                                    type="time"
                                    placeholder="<?php echo __('hh:mm'); ?>">

                            </div>
                            <div class="row">
                                <div class="col-lg-3" ng-repeat="error in errors.date">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="col-lg" ng-repeat="error in errors.time">
                                    <div class="help-block text-danger"> {{ error }}</div>
                                </div>
                            </div>

                        </div>

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">

                                    <button class="btn btn-primary"
                                            type="submit"><?php echo __('Create Message'); ?></button>
                                    <a back-button href="javascript:void(0);" fallback-state='NotificationMessagesIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                </div>
                            </div>
                        </div>

                    </form>


                </div>
            </div>
        </div>

    </div>

</div>
