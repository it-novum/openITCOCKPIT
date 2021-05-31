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
        <a ui-sref="CalendarsIndex">
            <i class="far fa-bell"></i> <?php echo __('Notification Message'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('index'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Notification messages'); ?>
                    <span class="fw-300"><i><?php echo __('Message'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('add', 'calendars')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="NotificationMessagesAdd">
                            <i class="fas fa-plus"></i> <?php echo __('Create'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <div class="frame-wrap">

                        <table class="table table-striped m-0 table-bordered table-hover table-sm">
                            <thead>
                            <tr>
                                <th class="no-sort sorting_disabled width-15">
                                    <i class="fa fa-check-square"></i>
                                </th>
                                <th class="no-sort" ng-click="orderBy('')">
                                    <i class="fa" ng-class="getSortClass('')"></i>
                                    <?php echo __('Title'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('')">
                                    <i class="fa" ng-class="getSortClass('')"></i>
                                    <?php echo __('Output'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('')">
                                    <i class="fa" ng-class="getSortClass('')"></i>
                                    <?php echo __('Date'); ?>
                                </th>
                                <th class="no-sort text-center" style="width: 10%">
                                    <i class="fa fa-trash"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="message in messages">
                                <td>

                                </td>
                                <td> {{ message.name }}</td>
                                <td> {{ message.message }}</td>
                                <td> {{ message.date }} &nbsp; {{ message.time }}</td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-danger" ng-click="deleteMessage(message.id)">
                                        <i class="fas fa-trash"></i> <?php echo __('Delete'); ?>
                                    </button>
                                </td>

                            </tr>
                            </tbody>
                        </table>

                        <div class="margin-top-10" ng-show="messages.length == 0">
                            <div class="text-center text-danger italic">
                                <?php echo __('No messages'); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
