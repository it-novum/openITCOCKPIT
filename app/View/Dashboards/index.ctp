<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>

<div class="row">
    <div class="col-xs-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-dashboard fa-fw "></i>
            <?php echo __('Dashboards') ?>
        </h1>
    </div>
</div>

<article class="col-xs-12">
    <div class="jarviswidget" id="widget-container" data-widget-editbutton="false">
        <header>
            <div class="tabsContainer">
                <ul class="nav nav-tabs pull-left ui-sortable">
                    <li data-tab-id="1" class="ui-sortable-handle" ng-repeat="tab in tabs"
                        ng-class="{'active':activeTab === tab.id}">
                        <a class="pointer" href="#/dashboards/index/1">
                            <span class="text ">
                                {{tab.name}}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-primary">
                    <i class="fa fa-refresh"></i>
                </button>
            </div>

            <div class="widget-toolbar" role="menu">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-success">
                        <?php echo __('Add Widget'); ?>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li ng-repeat="availableWidget in availableWidgets">
                            <a href="javascript:void(0);">
                                <i class="fa {{availableWidget.icon}}"></i>&nbsp;
                                {{availableWidget.title}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-default" ng-click="loadTabContent(activeTab)">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Refresh'); ?>
                </button>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-success">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="widget-toolbar" rile="menu">
                <button class="btn btn-xs btn-success" ng-click="toggleFullscreenMode()" title="<?php echo __('Fullscreen mode'); ?>">
                    <i class="fa fa-arrows-alt"></i>
                </button>
            </div>


        </header>
        <div>
            <div class="widget-body no-padding padding-top-10">
                <div class="padding-bottom-10">


                    <div class="grid-stack">
                        <div class="grid-stack-item" ng-repeat="activeWidget in activeWidgets.Widget"
                             id="widget-{{activeWidget.id}}"
                             data-gs-id="{{activeWidget.id}}"
                             data-gs-height="{{activeWidget.height}}"
                             data-gs-width="{{activeWidget.width}}"
                             data-gs-x="{{activeWidget.col}}"
                             data-gs-y="{{activeWidget.row}}">
                            <div class="grid-stack-item-content">
                                <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable">
                                    <header role="heading" class="ui-sortable-handle">
                                    <span class="widget-icon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                        <h2>{{activeWidget.title}}</h2>
                                    </header>
                                    <!-- Loading used AngularJs directives dynamically -->
                                    <div role="content">
                                        <ng-include
                                                src="'/dashboards/dynamicDirective?directive='+activeWidget.directive"></ng-include>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="$last" ng-init="$last?renderGrid():null"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</article>