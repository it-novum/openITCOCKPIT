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

$timezones = \itnovum\openITCOCKPIT\Core\Timezone::listTimezones();
?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Users'); ?>
            <span>>
                <?php echo __('Import from LDAP'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo __('Import new user from LDAP'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'users')): ?>
                <a back-button fallback-state='UsersIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('User'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">
                    <div class="form-group" ng-class="{'has-error': errors.usercontainerroles}">
                        <label class="col col-md-2 control-label hintmark">
                            <?php echo __('Container Roles'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
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
                            <div class="help-block">
                                <?php echo _('Container Roles are handy to grant the same permissions to multiple users.'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- User Container Roles permissions read/write -->
                    <div class="row" ng-repeat="userContainerRole in userContainerRoleContainerPermissions">
                        <div class="col col-md-2"></div>
                        <div class="col col-md-10">
                            <legend class="no-padding font-sm txt-ack">
                                {{userContainerRole.path}}
                            </legend>
                            <input name="group-{{userContainerRole.id}}"
                                   type="radio"
                                   disabled="disabled"
                                   ng-checked="userContainerRole._joinData.permission_level === 1">
                            <label class="padding-10 font-sm"><?php echo __('read'); ?></label>

                            <input name="group-{{userContainerRole.id}}"
                                   type="radio"
                                   disabled="disabled"
                                   ng-checked="userContainerRole._joinData.permission_level === 2">
                            <label class="padding-10 font-sm"><?php echo __('read/write'); ?></label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.containers}">
                        <label class="col col-md-2 control-label hintmark">
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
                                    ng-model="selectedUserContainers">
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <i class="fa fa-info-circle"></i>
                                <?php echo _('Container assignments defined in the user will overwrite permissions inherited from Container Roles!'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Container permissions read/write -->
                    <div class="row" ng-repeat="userContainer in selectedUserContainerWithPermission">
                        <div class="col col-md-2"></div>
                        <div class="col col-md-10">
                            <legend class="no-padding font-sm text-primary">
                                {{userContainer.name}}
                            </legend>
                            <input name="ucgroup-{{userContainer.container_id}}"
                                   type="radio"
                                   value="1"
                                   ng-model="userContainer.permission_level"
                                   ng-disabled="userContainer.container_id === 1"
                                   ng-checked="userContainer.permission_level == 1">
                            <label class="padding-10 font-sm"><?php echo __('read'); ?></label>

                            <input name="ucgroup-{{userContainer.container_id}}"
                                   type="radio"
                                   value="2"
                                   ng-model="userContainer.permission_level"
                                   ng-disabled="userContainer.container_id === 1"
                                   ng-checked="userContainer.permission_level == 2">
                            <label class="padding-10 font-sm"><?php echo __('read/write'); ?></label>
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
                            <div ng-repeat="error in errors.usergroup_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.is_active}">
                        <label class="col col-md-2 control-label" for="userIsActive">
                            <?php echo __('Is active'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox"
                                       id="userIsActive"
                                       name="checkbox"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.User.is_active">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.samaccountname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('SAM-Account-Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContactContainers"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="ldapUsers"
                                    callback="loadLdapUsersByString"
                                    ng-options="key as ldapUser.display_name for (key, ldapUser) in ldapUsers"
                                    ng-model="data.selectedSamAccountNameIndex">
                            </select>
                            <div ng-repeat="error in errors.samaccountname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.ldap_dn}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('DN'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    disabled="disabled"
                                    readonly="readonly"
                                    ng-model="post.User.ldap_dn">
                            <div ng-repeat="error in errors.ldap_dn">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col col-md-2 text-right"><i class="fa fa-info-circle text-info"></i></div>
                        <div class="col col-xs-10 text-info">
                            <?php echo __('Connected LDAP server'); ?>:
                            <strong>{{ldapConfig.host}}</strong>
                            <br/>
                            <?php echo __('Used filter query'); ?>:
                            <strong>{{ldapConfig.query}}</strong>
                            <br/>
                            <?php echo __('Base DN'); ?>:
                            <strong>{{ldapConfig.base_dn}}</strong>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Email address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    disabled="disabled"
                                    readonly="readonly"
                                    ng-model="post.User.email">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Value imported from LDAP Server'); ?>
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
                                    disabled="disabled"
                                    readonly="readonly"
                                    ng-model="post.User.firstname">
                            <div ng-repeat="error in errors.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Value imported from LDAP Server'); ?>
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
                                    disabled="disabled"
                                    readonly="readonly"
                                    ng-model="post.User.lastname">
                            <div ng-repeat="error in errors.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Value imported from LDAP Server'); ?>
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
                            <?php echo __('Company position'); ?>
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
                            <?php echo __('Length of lists'); ?>
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
                            <?php echo __('Show status badges in menu'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="userShowstatsinmenu"
                                       ng-true-value="1"
                                       ng-false-value="0"
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
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.User.recursive_browser">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.dashboard_tab_rotation}">
                        <label class="col col-md-2 control-label" for="userDashboardTabRotation">
                            <?php echo __('Tab rotation interval'); ?>
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
                            <?php echo __('Date format'); ?>
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
                            <label>
                                <input type="checkbox" ng-model="data.createAnother">
                                <?php echo _('Create another'); ?>
                            </label>

                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Create user from LDAP'); ?>">

                            <a back-button fallback-state='UsersIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

