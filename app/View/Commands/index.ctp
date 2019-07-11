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
            <?php echo __('Commands'); ?>
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
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add', 'commands')): ?>
                            <a class="btn btn-xs btn-success" ui-sref="CommandsAdd">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-terminal"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Monitoring commands'); ?></h2>
                </header>

                <div>
                    <div class="widget-body no-padding">
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-file-text-o"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by command name'); ?>"
                                                   ng-model="filter.Commands.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <fieldset>
                                        <legend style="padding-top: 0;"><?php echo __('Command types'); ?></legend>
                                        <div class="form-group smart-form">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label class="checkbox small-checkbox-label">
                                                        <input type="checkbox" name="checkbox" checked="checked"
                                                               ng-model="filter.Commands.service_checks"
                                                               ng-model-options="{debounce: 500}">
                                                        <i class="checkbox-primary"></i>
                                                        <?php echo __('Service check command'); ?>
                                                    </label>

                                                    <label class="checkbox small-checkbox-label">
                                                        <input type="checkbox" name="checkbox" checked="checked"
                                                               ng-model="filter.Commands.host_checks"
                                                               ng-model-options="{debounce: 500}">
                                                        <i class="checkbox-primary"></i>
                                                        <?php echo __('Host check command'); ?>
                                                    </label>
                                                </div>

                                                <div class="col-xs-6">
                                                    <label class="checkbox small-checkbox-label">
                                                        <input type="checkbox" name="checkbox" checked="checked"
                                                               ng-model="filter.Commands.notifications"
                                                               ng-model-options="{debounce: 500}">
                                                        <i class="checkbox-primary"></i>
                                                        <?php echo __('Notification command'); ?>
                                                    </label>

                                                    <label class="checkbox small-checkbox-label">
                                                        <input type="checkbox" name="checkbox" checked="checked"
                                                               ng-model="filter.Commands.eventhandler"
                                                               ng-model-options="{debounce: 500}">
                                                        <i class="checkbox-primary"></i>
                                                        <?php echo __('Eventhandler command'); ?>
                                                    </label>
                                                </div>
                                            </div>
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
                        <div class="mobile_table">
                            <table id="satellite_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort sorting_disabled width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Commands.name')">
                                        <i class="fa" ng-class="getSortClass('Commands.name')"></i>
                                        <?php echo __('Command name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Commands.command_type')">
                                        <i class="fa" ng-class="getSortClass('Commands.command_type')"></i>
                                        <?php echo __('Command type'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="command in commands">
                                    <td class="text-center" class="width-15">
                                        <?php if ($this->Acl->hasPermission('delete', 'commands')): ?>
                                            <input type="checkbox"
                                                   ng-model="massChange[command.Command.id]">
                                        <?php endif; ?>
                                    </td>
                                    <td>{{command.Command.name}}</td>
                                    <td>{{command.Command.type}}</td>
                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                <a ui-sref="CommandsEdit({id: command.Command.id})"
                                                   class="btn btn-default">
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
                                                id="menuHack-{{command.Command.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                    <li>
                                                        <a ui-sref="CommandsEdit({id:command.Command.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('usedBy', 'commands')): ?>
                                                    <li>
                                                        <a ui-sref="CommandsUsedBy({id: command.Command.id})">
                                                            <i class="fa fa-reply-all fa-flip-horizontal"></i>
                                                            <?php echo __('Used by'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'commands')): ?>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(command))">
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
                            <div class="row margin-top-10 margin-bottom-10" ng-show="commands.length == 0">
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
                            <div class="col-xs-12 col-md-2">
                                <a ui-sref="CommandsCopy({ids: linkForCopy()})" class="a-clean">
                                    <i class="fa fa-lg fa-files-o"></i>
                                    <?php echo __('Copy'); ?>
                                </a>
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
