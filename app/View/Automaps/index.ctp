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
            <i class="fa fa-magic fa-fw "></i>
            <?php echo __('Auto Maps') ?>
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
                        <?php if ($this->Acl->hasPermission('add', 'automaps')): ?>
                            <a ui-sref="AutomapsAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-magic"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Auto Maps overview'); ?>
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
                                                   placeholder="<?php echo __('Filter by auto map name'); ?>"
                                                   ng-model="filter.Automaps.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by description'); ?>"
                                                   ng-model="filter.Automaps.description"
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
                            <table id="automaps_list"
                                   class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Automaps.name')">
                                        <i class="fa" ng-class="getSortClass('Automaps.name')"></i>
                                        <?php echo __('Name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Automaps.description')">
                                        <i class="fa" ng-class="getSortClass('Automaps.description')"></i>
                                        <?php echo __('Description'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Automaps.host_regex')">
                                        <i class="fa" ng-class="getSortClass('Automaps.host_regex')"></i>
                                        <?php echo __('Host RegEx'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Automaps.service_regex')">
                                        <i class="fa" ng-class="getSortClass('Automaps.service_regex')"></i>
                                        <?php echo __('Service RegEx'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Status filters'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Recursive container'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="automap in automaps">
                                    <td class="text-center" class="width-15">
                                        <input type="checkbox"
                                               ng-model="massChange[automap.id]"
                                               ng-show="automap.allow_edit">
                                    </td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('view', 'automaps')): ?>
                                            <a ui-sref="AutomapsView({id:automap.id})">
                                                {{automap.name}}
                                            </a>
                                        <?php else: ?>
                                            {{automap.name}}
                                        <?php endif; ?>
                                    </td>
                                    <td>{{automap.description}}</td>
                                    <td>{{automap.host_regex}}</td>
                                    <td>{{automap.service_regex}}</td>

                                    <td>
                                        <span class="label-forced label-success margin-right-5"
                                              title="<?php echo __('Ok'); ?>"
                                              ng-show="automap.show_ok">
                                            <?php echo __('O'); ?>
                                        </span>
                                        <span class="label-forced label-warning margin-right-5"
                                              title="<?php echo __('Warning'); ?>"
                                              ng-show="automap.show_warning">
                                            <?php echo __('W'); ?>
                                        </span>
                                        <span class="label-forced label-danger margin-right-5"
                                              title="<?php echo __('Critical'); ?>"
                                              ng-show="automap.show_critical">
                                            <?php echo __('C'); ?>
                                        </span>
                                        <span class="label-forced label-default margin-right-5"
                                              title="<?php echo __('Unknown'); ?>"
                                              ng-show="automap.show_unknown">
                                            <?php echo __('U'); ?>
                                        </span>
                                        <span class="label-forced label-primary margin-right-5"
                                              title="<?php echo __('Acknowledged'); ?>"
                                              ng-show="automap.show_acknowledged">
                                            <i class="fa fa-user"></i>
                                        </span>
                                        <span class="label-forced label-primary"
                                              title="<?php echo __('In downtime'); ?>"
                                              ng-show="automap.show_downtime">
                                            <i class="fa fa-power-off"></i>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="label-forced label-danger"
                                              ng-hide="automap.recursive">
                                            <?php echo __('Disabled'); ?>
                                        </span>
                                        <span class="label-forced label-success"
                                              ng-show="automap.recursive">
                                            <?php echo __('Enabled'); ?>
                                        </span>
                                    </td>


                                    <td class="width-50">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'automaps')): ?>
                                                <a ui-sref="AutomapsEdit({id: automap.id})"
                                                   ng-if="automap.allow_edit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!automap.allow_edit"
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
                                                id="menuHack-{{automap.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'automaps')): ?>
                                                    <li ng-if="automap.allow_edit">
                                                        <a ui-sref="AutomapsEdit({id:automap.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('view', 'automaps')): ?>
                                                    <li>
                                                        <a ui-sref="AutomapsView({id:automap.id})">
                                                            <i class="fa fa-eye"></i>
                                                            <?php echo __('View'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'automaps')): ?>
                                                    <li class="divider" ng-if="automap.allow_edit"></li>
                                                    <li ng-if="automap.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(automap))">
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
                            <div class="row margin-top-10 margin-bottom-10" ng-show="automaps.length == 0">
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
