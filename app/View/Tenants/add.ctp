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
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Tenants'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-home"></i> </span>
        <h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?><?php echo __('tenant'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.Container.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
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
                    <div class="form-group" ng-class="{'has-error': errors.Tenant.description}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.description">
                            <div ng-repeat="error in errors.Tenant.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('Tenant.is_active', [
                            'caption'          => __('Is active'),
                            'wrapGridClass'    => 'col col-md-1',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass'     => 'control-label',
                            'ng-model'         => 'post.Tenant.is_active'
                        ]);
                        ?>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Tenant.firstname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Firstname'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.firstname">
                            <div ng-repeat="error in errors.Tenant.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error': errors.Tenant.lastname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Lastname'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.lastname">
                            <div ng-repeat="error in errors.Tenant.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error': errors.Tenant.street}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Street'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.street">
                            <div ng-repeat="error in errors.Tenant.street">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error': errors.Tenant.zipcode}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Zipcode'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.zipcode">
                            <div ng-repeat="error in errors.Tenant.zipcode">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-class="{'has-error': errors.Tenant.city}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('City'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.city">
                            <div ng-repeat="error in errors.Tenant.city">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required hintmark_before" ng-class="{'has-error': errors.Tenant.max_users}">
                        <label class="col col-md-2 control-label hintmark_before">
                            <?php echo __('Max Users'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Tenant.max_users">
                            <div ng-repeat="error in errors.Tenant.max_users">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <span class="note hintmark_before"><?php echo __('enter 0 for infinity'); ?></span>
                    <br/ ><br/>
                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                                <a href="/tenants/index" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>