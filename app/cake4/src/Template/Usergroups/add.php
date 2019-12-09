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
            <i class="fa fa-users fa-fw "></i>
            <?php echo __('Manage User Roles'); ?>
            <span>>
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-users"></i> </span>
        <h2><?php echo __('Create new user role'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'usergroups')): ?>
                <a back-button fallback-state='UsergroupsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('User role'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Usergroup.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.description}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.Usergroup.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="row padding-top-10">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                <input type="text" class="input-sm "
                                       placeholder="<?= __('Filter by controller') ?>"
                                       ng-model="ctrlFilter"
                                       ng-model-options="{debounce: 150}">
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <div class="btn-group pull-right">
                            <a href="javascript:void(0);" class="btn btn-default"><?php echo __('Bulk actions'); ?></a>
                            <a href="javascript:void(0);" data-toggle="dropdown"
                               class="btn btn-default dropdown-toggle"><span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="tickAll('all')">
                                        <i class="fa fa-check-square-o text-primary"></i> <?php echo __('Tick all'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="untickAll('all')">
                                        <i class="fa fa-square-o"></i> <?php echo __('Untick all'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="tickAll('index')">
                                        <i class="fa fa-check-square-o txt-ack"></i> <?php echo __('Tick all: index'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="untickAll('index')">
                                        <i class="fa fa-square-o"></i> <?php echo __('Untick all: index'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="tickAll('add')">
                                        <i class="fa fa-check-square-o ok"></i> <?php echo __('Tick all: add'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="untickAll('add')">
                                        <i class="fa fa-square-o"></i> <?php echo __('Untick all: add'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="tickAll('edit')">
                                        <i class="fa fa-check-square-o warning"></i> <?php echo __('Tick all: edit'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="untickAll('edit')">
                                        <i class="fa fa-square-o"></i> <?php echo __('Untick all: edit'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="tickAll('delete')">
                                        <i class="fa fa-check-square-o down"></i> <?php echo __('Tick all: delete'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                       ng-click="untickAll('delete')">
                                        <i class="fa fa-square-o"></i> <?php echo __('Untick all: delete'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="row" ng-repeat="aco in acos">
                    <div class="col-12">
                        <div class="row padding-bottom-15" ng-repeat="controller in aco.children"
                             ng-if="controller.children.length > 0 && controller.alias.substr(-6) !== 'Module'"
                             ng-show="ctrlFilter === '' || controller.alias.toLowerCase().includes(ctrlFilter)">
                            <div class="col-xs-12">
                                <h5 ng-class="{'ok': controller.alias.substr(-6) == 'Module'}">
                                    {{controller.alias}}
                                </h5>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-xs-12 col-md-2 col-lg-4"
                                         ng-repeat="action in controller.children">
                                        <label class="form-check-label"
                                               ng-class="{'txt-ack': action.alias == 'index', 'ok': action.alias == 'add', 'warning': action.alias == 'edit', 'down': action.alias == 'delete'}">
                                            <input type="checkbox" ng-model="post.Acos[action.id]"
                                                   ng-true-value="1"
                                                   ng-false-value="0"/>
                                            {{action.alias}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row padding-bottom-15" ng-repeat="plugin in aco.children"
                             ng-if="plugin.children.length > 0 && plugin.alias.substr(-6) === 'Module'">
                            <div class="col-xs-12">
                                <h5 class="ok">
                                    <i class="fa fa-puzzle-piece"></i>
                                    {{plugin.alias}}
                                </h5>
                            </div>

                            <div class="col-xs-12" ng-repeat="controller in plugin.children">
                                <h5>
                                    {{controller.alias}}
                                </h5>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-xs-12 col-md-2 col-lg-4"
                                             ng-repeat="action in controller.children">
                                            <label class="form-check-label"
                                                   ng-class="{'txt-ack': action.alias == 'index', 'ok': action.alias == 'add', 'warning': action.alias == 'edit', 'down': action.alias == 'delete'}">
                                                <input type="checkbox" ng-model="post.Acos[action.id]"
                                                       ng-true-value="1"
                                                       ng-false-value="0"/>
                                                {{action.alias}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                            <input class="btn btn-primary" type="submit" value="<?php echo __('Create user role'); ?>">
                            <a back-button fallback-state='UsergroupsIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
