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
        <a ui-sref="StatuspagesIndex">
            <i class="fas fa-info-circle"></i> <?php echo __('Status pages'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('Step 2'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Status page'); ?>
                    <span class="fw-300"><i><?php echo __('anonymize names'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'statuspages')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-info w-100" role="alert">
                            <h4 class="alert-heading"><?= __('Info'); ?></h4>
                            <?= __('In this optional step you can anonymize the elements to be displayed. The names entered here will be displayed on the status page instead of their real names.'); ?>
                        </div>
                    </div>

                    <form ng-submit="submit();" class="form-horizontal"
                          ng-init="successMessage=
            {objectName : '<?php echo __('Statuspage'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                        <!-- Hosts start -->
                        <span ng-if="post.Statuspages.hosts.length > 0">
                            <table class="table">
                                <thead>
                                <th class="col-5"><?= __('Host name'); ?></th>
                                <th class="col-7"><?= __('Display name'); ?></th>
                                </thead>
                                <tbody>
                                <tr ng-repeat="host in post.Statuspages.hosts">
                                    <td>{{host.name}}</td>
                                    <td>
                                        <input
                                            class="form-control"
                                            type="text"
                                            ng-model="host._joinData.display_name">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
                        <!-- Hosts end -->
                        <!-- Services start -->
                        <span ng-if="post.Statuspages.services.length > 0">
                            <hr>
                            <table class="table">
                                <thead>
                                <th class="col-3"><?= __('Service name'); ?></th>
                                <th class="col-2"><?= __('From host'); ?></th>
                                <th class="col-7"><?= __('Display name'); ?></th>
                                </thead>
                                <tbody>
                                <tr ng-repeat="service in post.Statuspages.services">
                                    <td>{{service.servicename}}</td>
                                    <td>{{service.hostname}}</td>
                                    <td>
                                        <input
                                            class="form-control"
                                            type="text"
                                            ng-model="service._joinData.display_name">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
                        <!-- Services end -->
                        <!-- Hostgroups start -->
                        <span ng-if="post.Statuspages.hostgroups.length > 0">
                            <hr>
                            <table class="table">
                                <thead>
                                <th class="col-5"><?= __('Hostgroup name'); ?></th>
                                <th class="col-7"><?= __('Display name'); ?></th>
                                </thead>
                                <tbody>
                                <tr ng-repeat="hostgroup in post.Statuspages.hostgroups">
                                    <td>{{hostgroup.Containers.name}}</td>
                                    <td>
                                        <input
                                            class="form-control"
                                            type="text"
                                            ng-model="hostgroup._joinData.display_name">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
                        <!-- Hostgroups end -->
                        <!-- Servicegroups start -->
                        <span ng-if="post.Statuspages.servicegroups.length > 0">
                            <hr>
                            <table class="table">
                                <thead>
                                <th class="col-5"><?= __('Servicegroup name'); ?></th>
                                <th class="col-7"><?= __('Display name'); ?></th>
                                </thead>
                                <tbody>
                                <tr ng-repeat="servicegroup in post.Statuspages.servicegroups">
                                    <td>{{servicegroup.Containers.name}}</td>
                                    <td>
                                        <input
                                            class="form-control"
                                            type="text"
                                            ng-model="servicegroup._joinData.display_name">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
                        <!-- Servicegroups end -->

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <?php if ($this->Acl->hasPermission('add', 'statuspages')): ?>
                                        <button class="btn btn-primary" type="submit">
                                            <?php echo __('Update status page'); ?>
                                        </button>
                                    <?php endif; ?>
                                    <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?>
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
