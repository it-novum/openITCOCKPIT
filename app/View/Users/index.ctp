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
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Users'); ?>
            <span>>
                <?php echo __('Overview'); ?>
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
                        <?php if ($this->Acl->hasPermission('add', 'contacts')): ?>
                            <a ui-sref="UsersAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New local user'); ?>
                            </a>

                            <?php if ($isLdapAuth): ?>
                                <a ui-sref="UsersLdap" class="btn btn-xs btn-warning">
                                    <i class="fa fa-plus"></i>
                                    <?php echo __('Import from LDAP'); ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-user"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Users overview'); ?>
                    </h2>
                </header>

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
                                                   placeholder="<?php echo __('Filter by users ful name'); ?>"
                                                   ng-model="filter.full_name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by email'); ?>"
                                                   ng-model="filter.Users.email"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by phone'); ?>"
                                                   ng-model="filter.Users.phone"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-building"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by company'); ?>"
                                                   ng-model="filter.Users.company"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
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
                            <table id="contact_list" class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('full_name')">
                                        <i class="fa" ng-class="getSortClass('full_name')"></i>
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
                                    <th class="no-sort" ng-click="orderBy('Users.usergroup_id')">
                                        <i class="fa" ng-class="getSortClass('Users.usergroup_id')"></i>
                                        <?php echo __('User role'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="user in users">
                                    <td class="text-center" class="width-15">
                                        <input type="checkbox"
                                               ng-model="massChange[user.id]"
                                               ng-show="user.allow_edit && (user.id != myUserId)">
                                    </td>

                                    <td>{{user.full_name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.phone}}</td>
                                    <td>{{user.company}}</td>
                                    <td>
                                         <span class="label-forced label-danger"
                                               ng-hide="user.is_active">
                                            <?php echo __('Disabled'); ?>
                                        </span>
                                        <span class="label-forced label-success"
                                              ng-show="user.is_active">
                                            <?php echo __('Active'); ?>
                                        </span>
                                    </td>
                                    <td>{{user.usergroup.name}}</td>

                                    <td class="width-50">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'contacts')): ?>
                                                <a ui-sref="UsersEdit({id: user.id})"
                                                   ng-if="user.allow_edit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!user.allow_edit"
                                                   class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{user.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                                    <li ng-if="user.allow_edit">
                                                        <a ui-sref="UsersEdit({id:user.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                                    <li ng-if="!user.samaccountname && user.allow_edit">
                                                        <a ng-click="resetPassword(user.id, user.email)">
                                                            <i class="fa fa-reply-all fa-flip-horizontal"></i>
                                                            <?php echo __('Reset Password'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'users')): ?>
                                                    <li class="divider"
                                                        ng-if="user.allow_edit && (user.id != myUserId)"></li>
                                                    <li ng-if="user.allow_edit && (user.id != myUserId)">
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
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="contacts.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fa fa-lg fa-check-square-o"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fa fa-lg fa-square-o"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-4 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fa fa-lg fa-trash-o"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                        </div>
                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
