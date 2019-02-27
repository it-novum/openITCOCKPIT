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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Manage Users'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo $this->action == 'edit' ? __('Edit') : __('Add') ?><?php echo __('User'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.containers}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="UserContainers"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    multiple
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Container._ids">
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.usergroups}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('User role'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="Usergroups"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="usergroups"
                                    ng-options="usergroup.key as usergroup.value for usergroup in usergroups"
                                    ng-model="post.User.usergroup.id">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.status}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Status'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="UserStatus"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="status"
                                    ng-options="state.key as state.value for state in status"
                                    ng-model="post.User.status">
                            </select>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Email Address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.email">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.firstname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('First name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.firstname">
                            <div ng-repeat="error in errors.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.lastname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Last name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.lastname">
                            <div ng-repeat="error in errors.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.company}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Company'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.company">
                            <div ng-repeat="error in errors.company">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.position}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Company Position'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.position">
                            <div ng-repeat="error in errors.position">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.phone}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Phone Number'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.phone">
                            <div ng-repeat="error in errors.phone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.password}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('New Password'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="password"
                                    ng-model="post.User.password">
                            <div ng-repeat="error in errors.password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.confirm_password}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Confirm new Password'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="password"
                                    ng-model="post.User.confirm_password">
                            <div ng-repeat="error in errors.confirm_password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="<?php echo __('Create new local User'); ?>">
                            <a ui-sref="UsersIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

