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
            <i class="fas fa-info-circle"></i> <?php echo __('Statuspages'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('Edit'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Edit Statuspage'); ?>
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
                    <form ng-submit="submit();" class="form-horizontal"
                          ng-init="successMessage=
            {objectName : '<?php echo __('Statuspage'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                        <div class="form-group required" ng-class="{'has-error': errors.containers}">
                            <label class="control-label" for="ContactContainers">
                                <?php echo __('Container'); ?>
                            </label>
                            <select
                                id="ContactContainers"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                multiple
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Statuspages.containers._ids">
                            </select>
                            <div ng-show="post.Statuspages.containers._ids.length === 0" class="warning-glow">
                                <?php echo __('Please select a container.'); ?>
                            </div>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Statuspages.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.description}">
                            <label class="control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Statuspages.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.public}">
                            <div class="custom-control custom-checkbox margin-bottom-10"
                                 ng-class="{'has-error': errors.public}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="public"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.Statuspages.public">
                                <label class="custom-control-label" for="public">
                                    <?php echo __('Public'); ?>
                                </label>
                            </div>

                            <div class="col col-xs-12 col-md-offset-2 help-block">
                                <?php echo __('If enabled the statuspage will be public available without authorization'); ?>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group"
                             ng-class="{'has-error': errors.hosts}">
                            <label class="control-label">
                                <?php echo __('Hosts'); ?>
                            </label>
                            <div class="input-group" style="width: 100%;">
                                <select
                                    id="HostsSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    multiple
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="post.Statuspages.hosts._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.hosts">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-class="{'has-error': errors.services}">
                            <label class="control-label">
                                <?php echo __('Services'); ?>
                            </label>
                            <div class="input-group" style="width: 100%;">
                                <select
                                    id="ServicesSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="services"
                                    multiple
                                    ng-options="service.key as service.value.servicename group by service.value._matchingData.Hosts.name disable when service.disabled for service in services"
                                    ng-model="post.Statuspages.services._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.services">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-class="{'has-error': errors.hostgroups}">
                            <label class="control-label">
                                <?php echo __('Host groups'); ?>
                            </label>
                            <div class="input-group" style="width: 100%;">
                                <select
                                    id="HostgroupsSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hostgroups"
                                    multiple
                                    ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                    ng-model="post.Statuspages.hostgroups._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.hostgroups">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-class="{'has-error': errors.servicegroups}">
                            <label class="control-label">
                                <?php echo __('Service groups'); ?>
                            </label>
                            <div class="input-group" style="width: 100%;">
                                <select
                                    id="ServicegroupsSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicegroups"
                                    multiple
                                    ng-options="servicegroup.key as servicegroup.value for servicegroup in servicegroups"
                                    ng-model="post.Statuspages.servicegroups._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.servicegroups">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <hr>

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                        <button class="btn btn-primary" type="submit">
                                            <?php echo __('Next'); ?>
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


