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
            <i class="fa fa-retweet fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Rotations'); ?>
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

                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <a ui-sref="RotationsAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
                    <h2><?php echo __('Map rotations'); ?></h2>
                </header>

                <div>
                    <div class="widget-body no-padding">
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-sitemap"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by Rotation name'); ?>"
                                                   ng-model="filter.rotation.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by Rotation Interval'); ?>"
                                                   ng-model="filter.rotation.interval"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
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

                        <table id="map_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <th class="no-sort sorting_disabled width-15">
                                    <i class="fa fa-check-square-o fa-lg"></i>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Rotation.name')">
                                    <i class="fa" ng-class="getSortClass('Rotation.name')"></i>
                                    <?php echo __('Rotation name'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Rotation.interval')">
                                    <i class="fa" ng-class="getSortClass('Rotation.interval')"></i>
                                    <?php echo __('Rotation interval'); ?>
                                </th>
                                <th class="no-sort text-center" style="width:60px;">
                                    <i class="fa fa-gear fa-lg"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="rotation in rotations">
                                <td class="text-center" class="width-15">
                                    <input type="checkbox"
                                           ng-model="massChange[rotation.Rotation.id]"
                                           ng-show="rotation.Rotation.allowEdit">
                                </td>
                                <td>
                                    <a ng-if="rotation.Rotation.ids.length"
                                       ui-sref="MapeditorsView({id: rotation.Rotation.first_id, rotation: rotation.Rotation.ids, interval: rotation.Rotation.interval})">
                                        {{ rotation.Rotation.name }}
                                    </a>
                                    <a ui-sref="RotationsEdit({id: rotation.Rotation.id})"
                                       ng-if="!rotation.Rotation.ids.length && rotation.Rotation.allowEdit">
                                        {{ rotation.Rotation.name }}
                                    </a>
                                </td>
                                <td>
                                    {{ rotation.Rotation.interval }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($this->Acl->hasPermission('edit')): ?>
                                            <a ui-sref="RotationsEdit({id: rotation.Rotation.id})"
                                               ng-if="rotation.Rotation.allowEdit"
                                               class="btn btn-default">&nbsp;<i class="fa fa-cog "></i>&nbsp;</a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">
                                                &nbsp;
                                                <i class="fa fa-cog"></i>
                                                &nbsp;
                                            </a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-{{rotation.Rotation.id}}">
                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                <li ng-if="rotation.Rotation.allowEdit">
                                                    <a ui-sref="RotationsEdit({id: rotation.Rotation.id})">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit Rotation'); ?>
                                                    </a>
                                                </li>
                                                <li class="divider" ng-if="map.Map.allowEdit"></li>
                                            <?php endif; ?>
                                            <li ng-if="rotation.Rotation.ids.length">
                                                <a ui-sref="MapeditorsView({id: rotation.Rotation.first_id, rotation: rotation.Rotation.ids, interval: rotation.Rotation.interval})">
                                                    <i class="fa fa-eye"></i> <?php echo __('View'); ?>
                                                </a>
                                            </li>
                                            <li ng-if="rotation.Rotation.ids.length">
                                                <a ui-sref="MapeditorsView({id: rotation.Rotation.first_id, rotation: rotation.Rotation.ids, interval: rotation.Rotation.interval, fullscreen: 'true'})">
                                                    <i class="glyphicon glyphicon-resize-full"></i> <?php echo __('View in fullscreen'); ?>
                                                </a>
                                            </li>
                                            <?php if ($this->Acl->hasPermission('delete')): ?>
                                                <li class="divider" ng-if="rotation.Rotation.allowEdit"></li>
                                                <li ng-if="rotation.Rotation.allowEdit">
                                                    <a class="txt-color-red"
                                                       href="javascript:void(0);" class="txt-color-red"
                                                       ng-click="confirmDelete(getObjectForDelete(rotation))">
                                                        <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="rotations.length == 0">
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
                            <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fa fa-lg fa-trash-o"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                        </div>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
