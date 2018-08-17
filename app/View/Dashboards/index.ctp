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
                    <li data-tab-id="{{tab.id}}" class="ui-sortable-handle" ng-repeat="tab in tabs"
                        ng-class="{'active':activeTab === tab.id}">
                        <a class="pointer" href="javascript:void(0);" ng-if="activeTab !== tab.id">
                            <span class="text" ng-click="loadTabContent(tab.id)">
                                {{tab.name}}
                            </span>
                        </a>

                        <a href="javascript:void(0);"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           aria-expanded="false"
                           ng-if="activeTab === tab.id">
                            {{tab.name}}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" id="menuHack-tab-{{tab.id}}">
                            <li>
                                <a href="javascript:void(0);" class="dashboard-tab-hover-fix"
                                   ng-click="triggerRenameTabModal(tab.name)">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Rename'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dashboard-tab-hover-fix">
                                    <i class="fa fa-code-fork"></i>
                                    <?php echo __('Start sharing'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="txt-color-red">
                                    <i class="fa fa-trash-o"></i>
                                    <?php echo __('Delete'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-primary"
                        title="<?php echo __('Setup tab rotation'); ?>"
                        data-toggle="modal" data-target="#tabRotationModal">
                    <i class="fa fa-spinner"></i>
                </button>
            </div>

            <div class="widget-toolbar" role="menu">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-success">
                        <?php echo __('Add Widget'); ?>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        <li ng-repeat="availableWidget in availableWidgets"
                            ng-click="addWidgetToTab(availableWidget.type_id)">
                            <a href="javascript:void(0);">
                                <i class="fa {{availableWidget.icon}}"></i>&nbsp;
                                {{availableWidget.title}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-default" ng-click="refresh()"
                        title="<?php echo __('Refresh'); ?>">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Refresh'); ?>
                </button>
            </div>

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-success"
                        title="<?php echo __('Add tab'); ?>"
                        data-toggle="modal" data-target="#addNewTabModal">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <div class="widget-toolbar" role="menu">
                <button class="btn btn-xs btn-success" ng-click="toggleFullscreenMode()"
                        title="<?php echo __('Fullscreen mode'); ?>">
                    <i class="fa fa-arrows-alt"></i>
                </button>
            </div>


        </header>
        <div>
            <div class="widget-body">


                <div gridster="gridsterOpts">
                    <ul>
                        <li gridster-item="widget" ng-repeat="widget in activeWidgets"
                            style="display:flex; display: -webkit-flex; flex-direction: row; -webkit-flex-direction: row; -webkit-align-content: stretch; align-content: stretch;">
                            <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable bg-color-blue"
                                 style="width:100%;" id="widget-{{widget.id}}">
                                <header role="heading" class="ui-sortable-handle" style="cursor: move;">
                                    <div class="col col-lg-8">
                                        <div class="smart-form no-padding" role="menu">
                                            <div>
                                                <label class="input">
                                                    <i class="icon-prepend fa fa-road txt-color-blueDark"></i>
                                                    <input type="text" placeholder="Title"
                                                           style="background: none;"
                                                           ng-model="widget.title"
                                                           ng-readonly="editMode ? false : true"
                                                           ng-model-options="{debounce: 500}"
                                                           class="input-md no-border" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-lg-4 no-padding">
                                        <div class="widget-toolbar" role="menu">
                                            <button class="btn btn-xs btn-success"
                                                    title="<?php echo __('Edit title'); ?>"
                                                    ng-click="setEditMode()">
                                                <i ng-class="editMode ? 'fa fa-floppy-o' : 'fa fa-pencil'"></i>
                                            </button>
                                            <button class="btn btn-xs btn-danger"
                                                    title="<?php echo __('Remove widget'); ?>"
                                                    ng-click="removeWidgetFromTab(widget.id)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </header>
                                <!-- Loading used AngularJs directives dynamically -->
                                <div role="content" id="widget-content-{{widget.id}}"
                                     style="height:100%; overflow: auto;">
                                    <ng-include
                                            src="'/dashboards/dynamicDirective?directive='+widget.directive"></ng-include>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</article>


<!-- Add new Tab modal -->
<div id="addNewTabModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-plus"></i>
                    <?php echo __('Create new tab'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.name}">
                            <label class="label hintmark_red"><?php echo __('Tab name'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-tag"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Tab name'); ?>"
                                       ng-model="newTabName">
                            </label>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 padding-top-10">
                        <button type="button" class="btn btn-primary pull-right" ng-click="addNewTab()">
                            <?php echo __('Create new tab'); ?>
                        </button>
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-xs-12">
                        <h4 class="modal-title">
                            <?php echo __('Create from shared tab'); ?>
                        </h4>
                    </div>
                </div>
                <br/>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tab rotation modal -->
<div id="tabRotationModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-spinner"></i>
                    <?php echo __('Tab rotation interval'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <label>
                            <?php echo __('Set tab rotation interval'); ?>
                        </label>
                        <div class="slidecontainer">
                            <input type="range" step="10" min="0" max="900" class="slider"
                                   ng-model="viewTabRotateInterval" ng-mouseup="saveTabRotateInterval()">
                            <div>
                                <div class="help-block text-muted">{{ intervalText }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rename tab modal -->
<div id="renameTabModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-pencil-square-o"></i>
                    <?php echo __('Rename tab'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.name}">
                            <label class="label hintmark_red"><?php echo __('Tab name'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-tag"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('New tab name'); ?>"
                                       ng-model="renameTabName">
                            </label>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 padding-top-10">
                        <button type="button" class="btn btn-primary pull-right" ng-click="renameTab()">
                            <?php echo __('Rename tab'); ?>
                        </button>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>




