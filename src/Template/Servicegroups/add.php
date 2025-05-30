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
        <a ui-sref="ServicegroupsIndex">
            <i class="fa fa-cogs"></i> <?php echo __('Service group'); ?>
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
                    <?php echo __('Create new service group'); ?>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'servicegroups')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='ServicegroupsIndex'
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
                            {objectName : '<?php echo __('Service group'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                        <div class="form-group required" ng-class="{'has-error': errors.container}">
                            <label class="control-label" for="ContainersSelect">
                                <?php echo __('Container'); ?>
                            </label>
                            <select
                                id="ContainersSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Servicegroup.container.parent_id">
                            </select>
                            <div ng-repeat="error in errors.container.parent_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div ng-show="post.Servicegroup.container.parent_id < 1" class="warning-glow">
                                <?php echo __('Please select a container.'); ?>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.container.name}">
                            <label class="control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Servicegroup.container.name">
                            <div ng-repeat="error in errors.container.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error':errors.description}">
                            <label class="control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Servicegroup.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error':errors.servicegroup_url}">
                            <label class="control-label">
                                <?php echo __('Service group URL'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Servicegroup.servicegroup_url">
                            <div ng-repeat="error in errors.servicegroup_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.services}">
                            <label class="control-label" for="ServicegroupServicesSelect">
                                <?php echo __('Services'); ?>
                            </label>
                            <select
                                id="ServicegroupServicesSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                multiple
                                chosen="services"
                                callback="loadServices"
                                ng-options="service.key as service.value.Host.name + '/' +service.value.Service.servicename group by service.value.Host.name disable when service.value.Service.disabled for service in services"
                                ng-model="post.Servicegroup.services._ids">
                            </select>
                            <div ng-repeat="error in errors.services">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.servicetemplates}">
                            <label class="control-label" for="ServicetemplatesSelect">
                                <?php echo __('Service templates'); ?>
                            </label>
                            <select
                                id="ServicetemplatesSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                multiple
                                chosen="servicetemplates"
                                callback="loadServicetemplates"
                                ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                ng-model="post.Servicegroup.servicetemplates._ids">
                            </select>
                            <div ng-repeat="error in errors.servicetemplates">
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
                                        <?php echo __('Create service group'); ?>
                                    </button>
                                    <a back-button href="javascript:void(0);" fallback-state='ServicegroupsIndex'
                                       class="btn btn-default">
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
