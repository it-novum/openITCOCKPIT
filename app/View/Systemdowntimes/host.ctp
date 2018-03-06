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
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-history fa-flip-horizontal fa-fw "></i>
            <?php echo __('Recurring downtimes'); ?>
            <span>>
                <?php echo __('Hosts'); ?>
                > <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-info fade in">
            <button data-dismiss="alert" class="close">×</button>
            <i class="fa fa-info-circle"></i>
            <strong>
                <?php echo __('Notice'); ?>:
            </strong>
            <?php echo __('Recurring downtimes with deleted objects will be deleted automatically by the cronjob'); ?>
        </div>
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

                        <?php echo $this->element('Downtimes/create_dropdown'); ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>


                    <div class="jarviswidget-ctrls" role="menu"></div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-history fa-flip-horizontal"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Recurring host downtimes overview'); ?> </h2>
                    <?php echo $this->element('Systemdowntimes/tabs'); ?>
                </header>

                <div>

                    <div class="widget-body no-padding">
                        <div class="mobile_table">

                            <div class="list-filter well" ng-show="showFilter">
                                <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                                <div class="row">

                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by host name'); ?>"
                                                       ng-model="filter.Host.name"
                                                       ng-model-options="{debounce: 500}">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by user'); ?>"
                                                       ng-model="filter.Systemdowntime.author"
                                                       ng-model-options="{debounce: 500}">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                                <input type="text" class="input-sm"
                                                       placeholder="<?php echo __('Filter by comment'); ?>"
                                                       ng-model="filter.Systemdowntime.comment"
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

                            <table id="recurringdowntimes_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort text-center"><i class="fa fa-check-square-o fa-lg"></i></th>

                                    <th class="no-sort" ng-click="orderBy('Host.name')">
                                        <i class="fa" ng-class="getSortClass('Host.name')"></i>
                                        <?php echo __('Host name'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Systemdowntime.author')">
                                        <i class="fa" ng-class="getSortClass('Systemdowntime.author')"></i>
                                        <?php echo __('User'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Systemdowntime.comment')">
                                        <i class="fa" ng-class="getSortClass('Systemdowntime.comment')"></i>
                                        <?php echo __('Comment'); ?>
                                    </th>

                                    <th class="no-sort"><?php echo __('Weekdays'); ?></th>

                                    <th class="no-sort"><?php echo __('Days of month'); ?></th>

                                    <th class="no-sort" ng-click="orderBy('Systemdowntime.from_time')">
                                        <i class="fa" ng-class="getSortClass('Systemdowntime.from_time')"></i>
                                        <?php echo __('Start time'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Systemdowntime.duration')">
                                        <i class="fa" ng-class="getSortClass('Systemdowntime.duration')"></i>
                                        <?php echo __('Duration'); ?>
                                    </th>

                                    <th class="no-sort"><?php echo __('Delete'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="downtime in systemdowntimes">

                                    <td class="width-5">
                                        <input type="checkbox"
                                               ng-model="massChange[downtime.Systemdowntime.id]"
                                               ng-show="downtime.Host.allow_edit">
                                    </td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                            <a href="/hosts/browser/{{ downtime.Host.id }}">
                                                {{ downtime.Host.hostname }}
                                            </a>
                                        <?php else: ?>
                                            {{ downtime.Host.hostname }}
                                        <?php endif; ?>
                                    </td>

                                    <td>{{downtime.Systemdowntime.author}}</td>

                                    <td>
                                        <span class="text-muted">
                                            AUTO[{{downtime.Systemdowntime.id}}]:
                                        </span>
                                        {{downtime.Systemdowntime.comment}}
                                    </td>

                                    <td>{{downtime.Systemdowntime.weekdaysHuman.join(', ')}}</td>

                                    <td>
                                        <span
                                                class="text-muted"
                                                ng-show="downtime.Systemdowntime.dayOfMonth.length == 0">
                                            <?php echo __('Every defined weekday'); ?></span>
                                        {{downtime.Systemdowntime.dayOfMonth.join(', ')}}
                                    </td>

                                    <td>{{downtime.Systemdowntime.startTime}}</td>

                                    <td>{{downtime.Systemdowntime.duration}}</td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('delete', 'systemdowntimes')): ?>
                                            <button
                                                    class="btn btn-xs btn-danger"
                                                    ng-if="downtime.Host.allow_edit"
                                                    ng-click="confirmDelete(getObjectForDelete(downtime))">
                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </td>

                                </tr>

                                </tbody>

                            </table>
                            <div class="row margin-top-10 margin-bottom-10">
                                <div class="row margin-top-10 margin-bottom-10" ng-show="downtimes.length == 0">
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
                                    <?php echo __('Delete'); ?>
                                </span>
                                </div>
                            </div>

                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
