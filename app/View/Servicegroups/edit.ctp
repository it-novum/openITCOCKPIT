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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service Groups'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<confirm-delete></confirm-delete>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2><?php echo __('Edit Service Groups'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <button type="button" class="btn btn-danger btn-xs" ng-click="confirmDelete(servicegroup)">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <a back-button fallback-state='ServicegroupsIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID:'); ?> {{ servicegroup.Servicegroup.uuid }}
        </div>
    </header>
    <div class="widget-body">
        <form ng-submit="submit();" class="form-horizontal">
            <div class="row">
                <div class="form-group required" ng-class="{'has-error': errors.Container.parent_id}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Container'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="ServicegroupParentContainer"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Container.parent_id">
                        </select>
                        <div ng-repeat="error in errors.Container.parent_id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group required" ng-class="{'has-error': errors.Container.name}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Service group name'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input
                                class="form-control"
                                type="text"
                                ng-model="post.Container.name">
                        <div ng-repeat="error in errors.Container.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Description'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input class="form-control" type="text" ng-model="post.Servicegroup.description">
                    </div>
                </div>

                <div class="form-group" ng-class="{'has-error': errors.servicegroup_url}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Service group URL'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input class="form-control" type="text" ng-model="post.Servicegroup.servicegroup_url">
                        <div ng-repeat="error in errors.servicegroup_url">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Services'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="ServiceId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="services"
                                callback="loadServices"
                                ng-options="+(service.value.Service.id) as service.value.Host.name + '/' +((service.value.Service.name)?service.value.Service.name:service.value.Servicetemplate.name) group by service.value.Host.name for service in services"
                                ng-model="post.Servicegroup.Service">
                        </select>

                        <div ng-repeat="error in errors.Service">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Service templates'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select
                                id="ServicegroupServicetemplates"
                                multiple
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="servicetemplates"
                                callback="loadServicetemplates"
                                ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                ng-model="post.Servicegroup.Servicetemplate">
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 margin-top-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                            <a back-button fallback-state='ServicegroupsIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
