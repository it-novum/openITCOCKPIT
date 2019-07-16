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
            <i class="fa fa-terminal fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Manage users'); ?>
            </span>
        </h1>
    </div>
</div>
<massdelete></massdelete>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>
                        <?php if ($this->Acl->hasPermission('add', 'users')): ?>
                            <a ui-sref="UsersAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Create local user'); ?>
                            </a>
                            <a ng-if="isLdapAuth" ui-sref="UsersAddFromLdap" class="btn btn-xs btn-warning">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Import from LDAP'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-terminal"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Manage users'); ?>
                    </h2>
                </header>

                <!-- widget div-->
                <div>
                    <div class="widget-body no-padding">
                        <!-- Start Filter -->
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by user full name'); ?>"
                                                   ng-model="filter.Users.full_name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by user email'); ?>"
                                                   ng-model="filter.Users.email"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by user phone'); ?>"
                                                   ng-model="filter.Users.phone"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-building"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by user company'); ?>"
                                                   ng-model="filter.Users.company"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <fieldset>
                                    <legend><?php echo __('User Role'); ?></legend>
                                    <div class="form-group smart-form">
                                        <select
                                                id="UserRoles"
                                                data-placeholder="<?php echo __('Filter by user role'); ?>"
                                                class="input-sm"
                                                chosen="usergroups"
                                                multiple
                                                ng-model="filter.Users.usergroup_id"
                                                ng-options="usergroup.key as usergroup.value for usergroup in usergroups"
                                                ng-model-options="{debounce: 500}">
                                        </select>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-right margin-top-10">
                                        <button type="button" ng-click="resetFilter()"
                                                class="btn btn-xs btn-danger">
                                            <?php echo __('Reset Filter'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Filter -->

                        <div class="mobile_table">
                            <table class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort" ng-click="orderBy('Users.full_name')">
                                        <i class="fa" ng-class="getSortClass('Users.full_name')"></i>
                                        <?php echo __('Full name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Users.email')">
                                        <i class="fa" ng-class="getSortClass('Users.email')"></i>
                                        <?php echo __('Email'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Users.phone')">
                                        <i class="fa" ng-class="getSortClass('Users.phone')"></i>
                                        <?php echo __('Phone'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Users.company')">
                                        <i class="fa" ng-class="getSortClass('Users.company')"></i>
                                        <?php echo __('Company'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Users.is_active')">
                                        <i class="fa" ng-class="getSortClass('Users.is_active')"></i>
                                        <?php echo __('Is active'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('User.usergroup.name')">
                                        <i class="fa" ng-class="getSortClass('User.usergroup.name')"></i>
                                        <?php echo __('User role'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="user in Users">
                                    <td>{{user.User.full_name}}</td>
                                    <td>{{user.User.email}}</td>
                                    <td>{{user.User.phone}}</td>
                                    <td>{{user.User.company}}</td>
                                    <td>
                                        <i class="fa fa-check" ng-if="user.User.is_active"></i>
                                        <i class="fa fa-times" ng-if="!user.User.is_active"></i>
                                        </td>
                                    <td>{{user.User.usergroup.name}}</td>
                                    <td class="width-50">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                                <a ui-sref="UsersEdit({id: user.User.id})"
                                                   ng-if="user.User.allow_edit && !user.User.samaccountname"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a ui-sref="UsersEditFromLdap({id: user.User.id})"
                                                   ng-if="user.User.allow_edit && user.User.samaccountname"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!user.User.allow_edit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{user.User.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                                    <li ng-if="user.User.allow_edit && !user.User.samaccountname">
                                                        <a ui-sref="UsersEdit({id:user.User.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                    <li ng-if="user.User.allow_edit && user.User.samaccountname">
                                                        <a ui-sref="UsersEditFromLdap({id:user.User.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit Ldap'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                                    <li ng-if="!user.User.samaccountname">
                                                        <a ng-click="resetPassword(user.User.id, user.User.email)">
                                                            <i class="fa fa-reply-all fa-flip-horizontal"></i>
                                                            <?php echo __('Reset Password'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'users')): ?>
                                                    <li class="divider" ng-if="user.User.allow_edit && (user.User.id != userId)"></li>
                                                    <li ng-if="user.User.allow_edit && (user.User.id != userId)">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(user))">
                                                            <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </article>
    </div>
</section>