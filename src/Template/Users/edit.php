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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="UsersIndex">
            <i class="fa fa-user"></i> <?php echo __('Users'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-edit"></i> <?php echo __('Edit'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span ng-if="!isLdapUser"><?php echo __('Edit local user:'); ?></span>
                    <span ng-if="isLdapUser"><?php echo __('Edit LDAP user:'); ?></span>
                    <span class="fw-300"><i>
                            {{post.User.firstname}},
                            {{post.User.lastname}}
                        </i></span>
                </h2>
                <div class="panel-toolbar">
                    <span class="badge border margin-right-10 {{UserType.class}} {{UserType.color}}">
                        {{UserType.title}}
                    </span>

                    <?php if ($this->Acl->hasPermission('index', 'users')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='UsersIndex'
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
            {objectName : '<?php echo __('User'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                        <div class="form-group" ng-class="{'has-error': errors.usercontainerroles}">
                            <label class="control-label hintmark" for="UserContainerroles">
                                <?php echo __('Container Roles'); ?>
                            </label>
                            <select
                                id="UserContainerroles"
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
                                <?php echo __('Container Roles are handy to grant the same permissions to multiple users.'); ?>
                            </div>
                        </div>

                        <!-- User Container Roles permissions read/write -->
                        <div class="row" ng-repeat="userContainerRole in userContainerRoleContainerPermissions">
                            <div class="col col-12 padding-left-30">
                                <legend class="no-padding font-sm txt-ack">
                                    {{userContainerRole.path}}
                                    <i class="fas fa-minus-square text-danger" ng-if="selectedUserContainers.indexOf(userContainerRole._joinData.container_id) !== -1"></i>
                                </legend>
                                <div class="d-inline-block" ng-class="{'strike' : selectedUserContainers.indexOf(userContainerRole._joinData.container_id) !== -1}">
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
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.containers}">
                            <label class="control-label hintmark" for="UserContainers">
                                <?php echo __('Container'); ?>
                            </label>
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
                                <?php echo __('Container assignments defined in the user will overwrite permissions inherited from Container Roles!'); ?>
                            </div>
                        </div>

                        <!-- Container permissions read/write -->
                        <div class="row" ng-repeat="userContainer in selectedUserContainerWithPermission">
                            <div class="col col-12 padding-left-30">
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
                            <label class="control-label" for="Usergroups">
                                <?php echo __('User role'); ?>
                            </label>
                            <select
                                id="Usergroups"
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

                        <div class="form-group" ng-class="{'has-error': errors.is_active}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.is_active}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="userIsActive"
                                       ng-model="post.User.is_active">
                                <label class="custom-control-label" for="userIsActive">
                                    <?php echo __('Is active'); ?>
                                </label>
                            </div>
                        </div>

                        <div ng-show="isLdapUser" class="form-group required"
                             ng-class="{'has-error': errors.samaccountname}">
                            <label class="control-label">
                                <?php echo __('SAM-Account-Name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-disabled="isLdapUser"
                                ng-model="post.User.samaccountname">
                            <div ng-repeat="error in errors.samaccountname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Username for the login'); ?>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.email}">
                            <label class="control-label">
                                <?php echo __('Email address'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-disabled="isLdapUser"
                                ng-model="post.User.email">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>

                        <!--
                        An oAuth user will be an oAuth user forever
                        This can be changed later the user would only need to get an password and is_oauth=0 in the database
                        -->
                        <div class="form-group" ng-class="{'has-error': errors.is_oauth}"
                             ng-show="post.User.is_oauth === true">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.is_oauth}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       checked="checked"
                                       disabled="disabled"
                                       readonly="readonly"
                                       id="userIsOAuth">
                                <label class="custom-control-label" for="userIsOAuth">
                                    <?php echo __('Enable login through oAuth2'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.firstname}">
                            <label class="control-label">
                                <?php echo __('First name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-disabled="isLdapUser"
                                ng-model="post.User.firstname">
                            <div ng-repeat="error in errors.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.lastname}">
                            <label class="control-label">
                                <?php echo __('Last name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-disabled="isLdapUser"
                                ng-model="post.User.lastname">
                            <div ng-repeat="error in errors.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.company}">
                            <label class="control-label">
                                <?php echo __('Company'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.User.company">
                            <div ng-repeat="error in errors.company">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.position}">
                            <label class="control-label">
                                <?php echo __('Company position'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.User.position">
                            <div ng-repeat="error in errors.position">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.phone}">
                            <label class="control-label">
                                <?php echo __('Phone Number'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.User.phone">
                            <div ng-repeat="error in errors.phone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.paginatorlength}">
                            <label class="control-label">
                                <?php echo __('Length of lists'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="number"
                                ng-model="post.User.paginatorlength">
                            <div ng-repeat="error in errors.paginatorlength">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.showstatsinmenu}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.showstatsinmenu}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="userShowstatsinmenu"
                                       ng-model="post.User.showstatsinmenu">
                                <label class="custom-control-label" for="userShowstatsinmenu">
                                    <?php echo __('Show status badges in menu'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.recursive_browser}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.recursive_browser}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="userRecursiveBrowser"
                                       ng-model="post.User.recursive_browser">
                                <label class="custom-control-label" for="userRecursiveBrowser">
                                    <?php echo __('Recursive Browser'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-12" ng-class="{'has-error': errors.dashboard_tab_rotation}">
                            <label>
                                <?php echo __('Tab rotation interval'); ?>
                            </label>
                            <div class="slidecontainer">
                                <input type="range" step="10" min="0" max="900" class="slider" style="width: 100%"
                                       ng-model="post.User.dashboard_tab_rotation">
                                <div>
                                    <div class="help-block text-muted">{{ intervalText }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.dateformat}">
                            <label class="control-label" for="UserDateformat">
                                <?php echo __('Date format'); ?>
                            </label>
                            <select
                                id="UserDateformat"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="dateformats"
                                ng-options="dateformat.key as dateformat.value for dateformat in dateformats"
                                ng-model="post.User.dateformat">
                            </select>
                            <div ng-repeat="error in errors.dateformat">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.timezone}">
                            <label class="control-label" for="UserDateformat">
                                <?php echo __('Timezone'); ?>
                            </label>
                            <select
                                id="UserDateformat"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="{}"
                                ng-init="post.User.timezone = post.User.timezone || 'Europe/Berlin'"
                                ng-model="post.User.timezone">
                                <?php foreach ($timezones as $continent => $continentTimezones): ?>
                                    <optgroup label="<?php echo h($continent); ?>">
                                        <?php foreach ($continentTimezones as $timezoneKey => $timezoneName): ?>
                                            <option
                                                value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach;; ?>
                            </select>
                            <div ng-repeat="error in errors.timezone">
                                <div class="help-block text-danger">{{ error }}</div>
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

                        <div class="form-group required" ng-class="{'has-error': errors.i18n}">
                            <label class="control-label" for="language">
                                <?php echo __('Language'); ?>
                            </label>
                            <select
                                id="language"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="localeOptions"
                                ng-options="value.i18n as value.name for (key, value) in localeOptions"
                                ng-model="post.User.i18n">
                            </select>
                            <div ng-repeat="error in errors.i18n">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Manual logout and login again required for changes to take effect for the current user.'); ?>
                                <br>
                                <?php echo __('These options are community translations. Feel free to extend them and open a github pull request.'); ?>
                            </div>
                        </div>


                        <!-- Prevent FireFox and Chrome from filling the users email into the timezone select box  :facepalm: -->
                        <input type="text" name="name" style="display:none">

                        <div class="form-group required" ng-class="{'has-error': errors.password}"
                             ng-if="isLdapUser === false && post.User.is_oauth === false">
                            <label class="control-label">
                                <?php echo __('New password'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="password"
                                ng-model="post.User.password"
                                autocomplete="new-password">
                            <div ng-repeat="error in errors.password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('The password must consist of 6 alphanumeric characters and must contain at least one digit'); ?>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.confirm_password}"
                             ng-if="isLdapUser === false && post.User.is_oauth === false">
                            <label class="control-label">
                                <?php echo __('Confirm new password'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="password"
                                ng-model="post.User.confirm_password"
                                autocomplete="new-password">
                            <div ng-repeat="error in errors.confirm_password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Please leave the password fields blank if you don\'t want to change the password.'); ?>
                            </div>
                        </div>
                        <!-- api key start-->

                        <fieldset>
                            <legend class="margin-0 padding-top-10">
                                <h4><?php echo __('Api keys'); ?> </h4>
                            </legend>
                            <div ng-repeat="(index,apikey) in post.User.apikeys">
                                <table class="table-default col-lg-12">
                                    <tr class="col-lg-12">
                                        <td class=""><?php echo __('Description'); ?></td>
                                        <td class="col-8"><?php echo __('Api key'); ?></td>
                                    </tr>
                                </table>
                                <!--label></label-->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control col-lg-4 mr-2"
                                           ng-model="apikey.description" maxlength="255"
                                           id="description_{{ index }}">
                                    <input ng-model="apikey.apikey"
                                           class="form-control col-lg-6"
                                           readonly
                                           maxlength="255"
                                           type="text"
                                           id="ApiKey_{{ index }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-success"
                                                ng-click="createApiKey(index)"
                                                type="button"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-key"></i>
                                            <?= __('Generate new API key'); ?>
                                        </button>
                                    </div>
                                    <button class="btn btn-danger btn-sm waves-effect waves-themed ml-2" type="button"
                                            ng-click="removeApikey(index)">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-lg-12 text-right mt-2">
                            <a href="javascript:void(0);" class="btn btn-success btn-sm" ng-click="addApikey()">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Add ApiKey'); ?>
                            </a>
                        </div>
                        <!-- api key end-->

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary"
                                            type="submit"><?php echo __('Update user'); ?></button>
                                    <a back-button href="javascript:void(0);" fallback-state='UsersIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
