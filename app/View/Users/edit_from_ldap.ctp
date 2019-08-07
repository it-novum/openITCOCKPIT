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
                <?php echo __('Manage Users'); ?>
			</span>
            <div class="third_level"> <?php echo __('Edit From LDAP'); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo __('Edit LDAP User'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="UsersIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.usercontainerroles}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container Roles'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="Usercontainerroles"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="usercontainerroles"
                                    multiple
                                    ng-options="usercontainerrole.key as usercontainerrole.value for usercontainerrole in usercontainerroles"
                                    ng-model="post.User.usercontainerroles._ids">
                            </select>
                            <div ng-repeat="error in errors.usercontainerroles">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Container permissions read/write -->
                    <div class="row" ng-repeat="(containerId, value) in chosenContainerroles">
                        <div class="col col-md-2"></div>
                        <div class="col col-md-10">
                            <legend class="no-padding font-sm text-primary">{{getContainerName(containerId)}}
                            </legend>
                            <input type="radio" ng-value="1"
                                   id="{{'read_'+containerId}}"
                                   name="{{'containerrolePermissions1_'+containerId}}"
                                   ng-model="chosenContainerroles[containerId]" disabled>
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read'); ?></label>
                            <input type="radio" ng-value="2"
                                   id="{{'write_'+containerId}}"
                                   name="{{'containerrolePermissions2_'+containerId}}"
                                   ng-model="chosenContainerroles[containerId]" disabled>
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read/write'); ?></label>

                        </div>
                    </div>

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
                                    ng-model="post.User.containers._ids"
                                    ng-change="syncMemberships()">
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Container permissions read/write -->
                    <div class="row" ng-repeat="(key, containerId) in post.User.containers._ids">
                        <div class="col col-md-2"></div>
                        <div class="col col-md-10">
                            <legend class="no-padding font-sm text-primary">{{getContainerName(containerId)}}
                            </legend>
                            <input type="radio" value="1" ng-value="1" id="{{'read_'+containerId}}"
                                   name="{{'containerPermissions1_'+containerId}}"
                                   ng-model="post.User.ContainersUsersMemberships[containerId]">
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read'); ?></label>
                            <input type="radio" value="2" ng-value="2" id="{{'write_'+containerId}}"
                                   name="{{'containerPermissions2_'+containerId}}"
                                   ng-model="post.User.ContainersUsersMemberships[containerId]">
                            <label for="userPermissionButton"
                                   class="padding-10 font-sm"><?php echo __('read/write'); ?></label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.usergroup_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('User role'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select id="Usergroups"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="usergroups"
                                    ng-options="usergroup.key as usergroup.value for usergroup in usergroups"
                                    ng-model="post.User.usergroup_id">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.is_active}">
                        <label class="col col-md-2 control-label" for="userIsActive">
                            <?php echo __('Is Active'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox"
                                       id="userIsActive"
                                       name="checkbox"
                                       ng-model="post.User.is_active">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.samaccountname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Username'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.samaccountname"
                                    readonly>
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span class="col col-xs-2 text-right"><i class="fa fa-info-circle text-info"></i></span>
                        <div class="col col-xs-10 text-info">
                            <?php echo __('Contacted LDAP server'); ?>:
                            <strong>{{systemsettings.FRONTEND['FRONTEND.LDAP.ADDRESS']}}</strong>
                            <br/>
                            <?php echo __('Searched filter query'); ?>:
                            <strong>{{systemsettings.FRONTEND['FRONTEND.LDAP.QUERY']}}</strong>
                            <br/>
                            <?php echo __('Searched Base DN'); ?>:
                            <strong>{{systemsettings.FRONTEND['FRONTEND.LDAP.BASEDN']}}</strong>
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
                                    ng-model="post.User.email"
                                    readonly>
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

                    <div class="form-group" ng-class="{'has-error': errors.company}">
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

                    <div class="form-group" ng-class="{'has-error': errors.position}">
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

                    <div class="form-group" ng-class="{'has-error': errors.phone}">
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

                    <div class="form-group required" ng-class="{'has-error': errors.paginatorlength}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Listelement Length'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control"
                                   type="number"
                                   ng-model="post.User.paginatorlength">
                            <div ng-repeat="error in errors.paginatorlength">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.showstatsinmenu}">
                        <label class="col col-md-2 control-label" for="userShowstatsinmenu">
                            <?php echo __('Show status stats in menu'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="userShowstatsinmenu"
                                       ng-model="post.User.showstatsinmenu">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.recursive_browser}">
                        <label class="col col-md-2 control-label" for="userRecursiveBrowser">
                            <?php echo __('Recursive Browser'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="userRecursiveBrowser"
                                       ng-model="post.User.recursive_browser">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.dashboard_tab_rotation}">
                        <label class="col col-md-2 control-label" for="userDashboardTabRotation">
                            <?php echo __('Set tab rotation interval'); ?>
                        </label>
                        <div class="col-xs-10 smart-form slidecontainer">
                            <input type="range" step="10" min="0" max="900" class="slider"
                                   ng-model="post.User.dashboard_tab_rotation">
                            <div>
                                <div class="help-block text-muted">{{ intervalText }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.dateformat}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Date Format'); ?>
                        </label>
                        <div class="col col-xs-10">

                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="dateformats"
                                    ng-options="dateformat.key as dateformat.value for dateformat in dateformats"
                                    ng-model="post.User.dateformat">
                            </select>
                            <div ng-repeat="error in errors.User.dateformat">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group required" ng-class="{'has-error': errors.timezone}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Timezone'); ?>
                        </label>
                        <div class="col col-xs-10">

                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="{}"
                                    ng-init="post.User.timezone = post.User.timezone || 'Europe/Berlin'"
                                    ng-model="post.User.timezone">
                                <?php foreach ($timezones as $continent => $continentTimezones): ?>
                                    <optgroup label="<?php echo h($continent); ?>">
                                        <?php foreach ($continentTimezones as $timezoneKey => $timezoneName): ?>
                                            <option value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach;; ?>
                            </select>
                            <div ng-repeat="error in errors.User.timezone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="helpText text-muted col-md-offset-2 col-md-6">
                            <br/>
                            <?php echo __('Server timezone is:'); ?>
                            <strong>
                                <?php echo h(date_default_timezone_get()); ?>
                            </strong>
                            <?php echo __('Current server time:'); ?>
                            <strong>
                                <?php echo date('d.m.Y H:i:s'); ?>
                            </strong>
                        </div>
                    </div>

                </div>
                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Edit LDAP User'); ?>">
                            <a ui-sref="UsersIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>