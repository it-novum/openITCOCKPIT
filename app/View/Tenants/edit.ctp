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
            <i class="fa fa-home fa-fw "></i>
            <?php echo __('Tenants'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-home"></i> </span>
        <h2>
            <?php echo __('Edit tenant:'); ?>
            {{tenant.container.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'tenants')): ?>
                <a back-button fallback-state='TenantsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Tenant'); ?>' , message: '<?php echo __('updated successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="row">

                            <div class="form-group required" ng-class="{'has-error': errors.container.name}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('Name'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.container.name">
                                    </div>
                                    <div ng-repeat="error in errors.container.name">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.description}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('Description'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.description">
                                    </div>
                                    <div ng-repeat="error in errors.description">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.firstname}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('First name'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.firstname">
                                    </div>
                                    <div ng-repeat="error in errors.firstname">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.lastname}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('Last name'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.lastname">
                                    </div>
                                    <div ng-repeat="error in errors.lastname">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.street}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('Street'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.street">
                                    </div>
                                    <div ng-repeat="error in errors.street">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.zipcode}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('Zip code'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                ng-model="post.zipcode">
                                    </div>
                                    <div ng-repeat="error in errors.zipcode">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-class="{'has-error': errors.city}">
                                <label class="col-xs-12 col-lg-2 control-label">
                                    <?php echo __('City'); ?>
                                </label>
                                <div class="col-xs-12 col-lg-10">
                                    <div class="input-group" style="width: 100%;">
                                        <input
                                                class="form-control"
                                                type="text"
                                                ng-model="post.city">
                                    </div>
                                    <div ng-repeat="error in errors.city">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">

                            <button type="submit" class="btn btn-primary">
                                <?php echo __('Update tenant'); ?>
                            </button>
                            <a back-button fallback-state='TenantsIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
