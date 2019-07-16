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
<?php
$timezones = CakeTime::listTimezones();
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Create User Container Role'); ?>
            </span>
            <div class="third_level"> <?php echo __('Add'); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo $this->action == 'edit' ? __('Edit') : __('Add') ?><?php echo __('User Container Role'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="UsercontainerrolesIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">

                <div class="form-group required" ng-class="{'has-error': errors.name}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Name'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <input
                                class="form-control"
                                type="text"
                                ng-model="post.Usercontainerrole.name">
                        <div ng-repeat="error in errors.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.containers}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                id="UsercontainerroleContainers"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                multiple
                                ng-options="container.key as container.value for container in containers"
                                ng-model="post.Usercontainerrole.containers._ids">
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Container permissions read/write -->
                    <div class="row" ng-repeat="(key, containerId) in post.Usercontainerrole.containers._ids">

                        <div class="col col-md-2"></div>
                        <div class="col col-md-10">
                            <legend class="no-padding font-sm text-primary">{{getContainerName(containerId)}}
                            </legend>
                            <input type="radio" value="1"
                                   id="{{'read_'+containerId}}"
                                   name="{{'containerPermissions1_'+containerId}}"
                                   ng-model="post.Usercontainerrole.ContainersUsercontainerrolesMemberships[containerId]" checked>
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read'); ?></label>
                            <input type="radio" value="2"
                                   id="{{'write_'+containerId}}"
                                   name="{{'containerPermissions2_'+containerId}}"
                                   ng-model="post.Usercontainerrole.ContainersUsercontainerrolesMemberships[containerId]">
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read/write'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Create new User Container Role'); ?>">
                            <a ui-sref="UsersIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>