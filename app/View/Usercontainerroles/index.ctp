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
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-terminal fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Manage user container roles'); ?>
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
                        <?php if ($this->Acl->hasPermission('add', 'usercontainerroles')): ?>
                            <a ui-sref="UsercontainerrolesAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Create container role'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-terminal"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Manage user container roles'); ?>
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
                                                   placeholder="<?php echo __('Filter by name'); ?>"
                                                   ng-model="filter.Usercontainerroles.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
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
                            <table class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort" ng-click="orderBy('Usercontainerroles.name')">
                                        <i class="fa" ng-class="getSortClass('Usercontainerroles.name')"></i>
                                        <?php echo __('Name'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="usercontainerrole in Usercontainerroles">

                                    <td>{{usercontainerrole.Usercontainerrole.name}}</td>
                                    <td class="width-50">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'usercontainerroles')): ?>
                                                <a ui-sref="UsercontainerrolesEdit({id: usercontainerrole.Usercontainerrole.id})"
                                                   ng-if="usercontainerrole.Usercontainerrole.allow_edit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{usercontainerrole.Usercontainerrole.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'usercontainerroles')): ?>
                                                    <li ng-if="usercontainerrole.Usercontainerrole.allow_edit">
                                                        <a ui-sref="UsercontainerrolesEdit({id:usercontainerrole.Usercontainerrole.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'usercontainerroles')): ?>
                                                    <li class="divider"
                                                        ng-if="usercontainerrole.Usercontainerrole.allow_edit"></li>
                                                    <li ng-if="usercontainerrole.Usercontainerrole.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(usercontainerrole))">
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
            </div>
        </article>
    </div>
</section>