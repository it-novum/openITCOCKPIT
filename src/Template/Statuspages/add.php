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
        <i class="fa fa-plus"></i> <?php echo __('Add'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Create new Status page'); ?>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'statuspages')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex' class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal" ng-init="successMessage=
                        {objectName : '<?php echo __('Status page'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
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
                                ng-model="post.Statuspage.containers._ids"
                                multiple>
                            </select>
                            <div ng-show="post.Statuspage.containers._ids.length === 0" class="warning-glow">
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
                                ng-model="post.Statuspage.name">
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
                                ng-model="post.Statuspage.description">
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
                                       ng-model="post.Statuspage.public">
                                <label class="custom-control-label" for="public">
                                    <?php echo __('Public'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.show_comments}">
                            <div class="custom-control custom-checkbox margin-bottom-10"
                                 ng-class="{'has-error': errors.show_comments}">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="showComments"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.Statuspage.show_comments">
                                <label class="custom-control-label" for="showComments">
                                    <?php echo __('Show comments'); ?>
                                </label>
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
                                    callback="loadHosts"
                                    multiple
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="post.Statuspage.hosts._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.hosts">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" type="button" ng-click="toggleHostAlias()" ng-show="showHostAliasButton">Set host alias</button>
                        </div>
                        <div class="form-group" ng-show="showHostAlias">
                            <label class="control-label">
                                <?php echo __('Host alias'); ?>
                            </label>
                            <span ng-if="selectedHosts.length > 0">
                            <table class="table">
                                <thead>
                                <tr class="d-flex">
                                    <th class="col-5"><?= __('Host name'); ?></th>
                                    <th class="col-7"><?= __('Display name'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="host in selectedHosts" class="d-flex">
                                    <td class="col-5">{{host.name}}</td>
                                    <td class="col-7">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="host.display_alias">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
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
                                        callback="loadServices"
                                        multiple
                                        ng-options="(service.value.Service.id) as service.value.Host.name + '/' +service.value.Service.servicename group by service.value.Host.name for service in services"
                                        ng-model="post.Statuspage.services._ids">
                                </select>
                            </div>
                            <div ng-repeat="error in errors.services">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" type="button" ng-click="toggleServiceAlias()" ng-show="showServiceAliasButton">Set service alias</button>
                        </div>
                        <div class="form-group" ng-show="showServiceAlias">
                            <label class="control-label">
                                <?php echo __('Service alias'); ?>
                            </label>
                            <span ng-if="selectedServices.length > 0">
                            <table class="table">
                                <thead>
                                <tr class="d-flex">
                                    <th class="col-3"><?= __('Service name'); ?></th>
                                    <th class="col-2"><?= __('Host name'); ?></th>
                                    <th class="col-7"><?= __('Display name'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="service in selectedServices" class="d-flex">
                                    <td class="col-3">{{service.name}}</td>
                                    <td class="col-2">{{service.hostName}}</td>
                                    <td class="col-7">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="service.display_alias">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </span>
                        </div>

                        <div class="form-group">
                                <div class="float-right">
                                    <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?>
                                    </a>
                                    <?php if ($this->Acl->hasPermission('add', 'statuspages')): ?>
                                        <button class="btn btn-primary" type="submit">
                                            <?php echo __('Save'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
