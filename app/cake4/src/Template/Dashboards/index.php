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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-tachometer-alt"></i> <?php echo __('Dashboard'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Dashboard'); ?>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <li class="nav-item ui-sortable-handle" data-tab-id="{{tab.id}}" ng-repeat="tab in tabs"
                            ng-class="{'active':activeTab === tab.id}">
                            <a class="nav-link"
                               href="javascript:void(0);"
                               ng-if="activeTab !== tab.id"
                               ng-class="{'active':activeTab === tab.id}"
                               role="tab">
                                <span class="text" ng-click="loadTabContent(tab.id)"
                                      ng-class="{ 'text-primary': tab.shared === true}">
                                    {{tab.name}}
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                               class="dropdown-toggle nav-link"
                               data-toggle="dropdown"
                               aria-expanded="false"
                               ng-if="activeTab === tab.id"
                               ng-class="{ 'text-primary': tab.shared}">
                            <span class="text"
                                  ng-class="{ 'text-primary': tab.shared === true}">
                                {{tab.name}}
                            </span>
                                <b class="caret"></b>
                            </a>
                        </li>
                    </ul>


                    <button class="btn btn-xs mr-1 shadow-0 btn-primary"
                            title="<?php echo __('Setup tab rotation'); ?>"
                            data-toggle="modal" data-target="#tabRotationModal">
                        <i class="fa fa-spinner"></i>
                    </button>
                    <button class="btn btn-xs mr-1 shadow-0 btn-primary"
                            title="<?php echo __('Lock for edit'); ?>"
                            ng-click="lockOrUnlockDashboard()">
                        <i class="fa fa-lock"
                           ng-class="{ 'fa-lock': dashboardIsLocked, 'fa-unlock': !dashboardIsLocked }"></i>
                    </button>


                    <div class="btn-group btn-group-xs " ng-hide="dashboardIsLocked">
                        <button class="btn btn-success dropdown-toggle waves-effect waves-themed" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo __('Add Widget'); ?>
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start"
                             style="position: absolute; will-change: top, left; top: 37px; left: 0px;">
                            <a href="javascript:void(0);" ng-repeat="availableWidget in availableWidgets"
                               ng-click="addWidgetToTab(availableWidget.type_id)" class="dropdown-item">
                                <i class="fa {{availableWidget.icon}}"></i>&nbsp;
                                {{availableWidget.title}}
                            </a>

                            <a href="javascript:void(0);"
                               ng-click="restoreDefault()"
                               class="dropdown-item">
                                <i class="fa fa-recycle"></i>
                                <?php echo __('Restore default'); ?>
                            </a>
                        </div>
                    </div>

                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="refresh()"
                            title="<?php echo __('Refresh'); ?>">
                        <i class="fa fa-refresh"></i>
                        <?php echo __('Refresh'); ?>
                    </button>

                    <button class="btn btn-xs btn-success mr-1 shadow-0"
                            title="<?php echo __('Add tab'); ?>"
                            data-toggle="modal" data-target="#addNewTabModal"
                            ng-click="loadSharedTabs()">
                        <i class="fa fa-plus"></i>
                    </button>

                    <button class="btn btn-xs btn-success shadow-0" ng-click="toggleFullscreenMode()"
                            title="<?php echo __('Fullscreen mode'); ?>">
                        <i class="fa fa-arrows-alt"></i>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div gridster="gridsterOpts">
                        <ul>
                            <li gridster-item="widget" ng-repeat="widget in activeWidgets"
                                style="display:flex; display: -webkit-flex; flex-direction: row; -webkit-flex-direction: row; -webkit-align-content: stretch; align-content: stretch;">
                                <div class="card {{widget.color}}"
                                     data-widget-colorbutton="true"
                                     style="width:100%;" id="widget-{{widget.id}}">
                                    <div role="heading" class="ui-sortable-handle card-header  pr-3 d-flex align-items-center flex-wrap" style="cursor: move;">
                                        <div class="card-title">
                                            <i class="fa {{widget.icon}}"></i>
                                            {{widget.title}}
                                        </div>
                                        <a class="btn btn-sm btn-icon ml-auto waves-effect waves-themed"
                                           title="<?php echo __('Edit title'); ?>"
                                           ng-click="triggerRenameWidgetModal(widget.id)"
                                           ng-hide="dashboardIsLocked">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-sm btn-icon  ml-1 waves-effect waves-themed"
                                           title="<?php echo __('Remove widget'); ?>"
                                           ng-click="removeWidgetFromTab(widget.id)"
                                           ng-hide="dashboardIsLocked">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                    <!-- Loading used AngularJs directives dynamically -->
                                    <div role="content" id="widget-content-{{widget.id}}"
                                         style="height:100%; overflow: auto;" class="card-body">
                                        <ng-include
                                            src="'/dashboards/dynamicDirective?directive='+widget.directive"></ng-include>
                                    </div>
                                </div>
                                <div ng-if="$last" ng-init="$last?enableWatch():null"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Rename widget modal -->
<div id="renameWidgetModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Edit widget title'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group" ng-class="{'has-error': ack.error}">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="<?php echo __('Edit widget title'); ?>"
                                   ng-model="data.renameWidgetTitle">
                        </div>
                        <div ng-repeat="error in errors.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="renameWidget()">
                    <?php echo __('Save widget Title'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>






















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

<?php echo $this->Html->css('vendor/radio_buttons.css'); ?>

<?php if ($this->Acl->hasPermission('index', 'statistics')): ?>
    <?php if (isset($askForHelp) && $askForHelp === true): ?>
        <ask-anonymous-statistics></ask-anonymous-statistics>
    <?php endif; ?>
<?php endif; ?>

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
                            <span class="text" ng-click="loadTabContent(tab.id)"
                                  ng-class="{ 'text-primary': tab.shared === true}">
                                {{tab.name}}
                            </span>
                        </a>

                        <a href="javascript:void(0);"
                           class="dropdown-toggle"
                           data-toggle="dropdown"
                           aria-expanded="false"
                           ng-if="activeTab === tab.id"
                           ng-class="{ 'text-primary': tab.shared}">
                            <span class="text"
                                  ng-class="{ 'text-primary': tab.shared === true}">
                                {{tab.name}}
                            </span>
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
                            <li ng-hide="tab.shared">
                                <a href="javascript:void(0);" class="dashboard-tab-hover-fix"
                                   ng-click="startSharing(tab.id)">
                                    <i class="fa fa-code-fork"></i>
                                    <?php echo __('Start sharing'); ?>
                                </a>
                            </li>
                            <li ng-show="tab.shared">
                                <a href="javascript:void(0);" class="dashboard-tab-hover-fix"
                                   ng-click="stopSharing(tab.id)">
                                    <i class="fa fa-code-fork"></i>
                                    <?php echo __('Stop sharing'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="txt-color-red"
                                   ng-click="deleteTab(tab.id)">
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

            <div class="widget-toolbar">
                <button class="btn btn-xs btn-primary"
                        title="<?php echo __('Lock for edit'); ?>"
                        ng-click="lockOrUnlockDashboard()">
                    <i class="fa fa-lock"
                       ng-class="{ 'fa-lock': dashboardIsLocked, 'fa-unlock': !dashboardIsLocked }"></i>
                </button>
            </div>

            <div class="widget-toolbar" role="menu" ng-hide="dashboardIsLocked">
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
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:void(0);"
                               ng-click="restoreDefault()">
                                <i class="fa fa-recycle"></i>
                                <?php echo __('Restore default'); ?>
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
                        data-toggle="modal" data-target="#addNewTabModal"
                        ng-click="loadSharedTabs()">
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
                            <div class="jarviswidget {{widget.color}} jarviswidget-sortable"
                                 data-widget-colorbutton="true"
                                 style="width:100%;" id="widget-{{widget.id}}">
                                <header role="heading" class="ui-sortable-handle" style="cursor: move;">
                                    <h2>
                                        <i class="fa {{widget.icon}}"></i>
                                        {{widget.title}}
                                    </h2>
                                    <div class="jarviswidget-ctrls" role="menu" ng-hide="dashboardIsLocked">
                                        <a class="button-icon jarviswidget-delete-btn pointer"
                                           title="<?php echo __('Edit title'); ?>"
                                           ng-click="triggerRenameWidgetModal(widget.id)">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a class="button-icon jarviswidget-delete-btn pointer"
                                           title="<?php echo __('Remove widget'); ?>"
                                           ng-click="removeWidgetFromTab(widget.id)">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                    <div class="widget-toolbar" role="menu" ng-hide="dashboardIsLocked">
                                        <a data-toggle="dropdown"
                                           class="dropdown-toggle color-box"
                                           href="javascript:void(0);">
                                        </a>
                                        <ul class="dropdown-menu arrow-box-up-right color-select pull-right padding-3">
                                            <li>
                                                <span class="bg-color-green"
                                                      data-widget-setstyle="jarviswidget-color-green"
                                                      data-toggle="tooltip"
                                                      data-placement="left"
                                                      data-original-title="<?php echo __('Green Grass'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-green'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-greenDark"
                                                      data-widget-setstyle="jarviswidget-color-greenDark"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Dark Green'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-greenDark'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-greenLight"
                                                      data-widget-setstyle="jarviswidget-color-greenLight"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Light Green'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-greenLight'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-purple"
                                                      data-widget-setstyle="jarviswidget-color-purple"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Purple'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-purple'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-magenta"
                                                      data-widget-setstyle="jarviswidget-color-magenta"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Magenta'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-magenta'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-pink"
                                                      data-widget-setstyle="jarviswidget-color-pink"
                                                      data-toggle="tooltip"
                                                      data-placement="right"
                                                      data-original-title="<?php echo __('Pink'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-pink'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-pinkDark"
                                                      data-widget-setstyle="jarviswidget-color-pinkDark"
                                                      data-toggle="tooltip"
                                                      data-placement="left"
                                                      data-original-title="<?php echo __('Fade Pink'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-pinkDark'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-blueLight"
                                                      data-widget-setstyle="jarviswidget-color-blueLight"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Light Blue'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-blueLight'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-teal"
                                                      data-widget-setstyle="jarviswidget-color-teal"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Teal'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-teal'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-blue"
                                                      data-widget-setstyle="jarviswidget-color-blue"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Ocean Blue'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-blue'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-blueDark"
                                                      data-widget-setstyle="jarviswidget-color-blueDark"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      data-original-title="<?php echo __('Night Sky'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-blueDark'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-darken"
                                                      data-widget-setstyle="jarviswidget-color-darken"
                                                      data-toggle="tooltip"
                                                      data-placement="right"
                                                      data-original-title="<?php echo __('Night'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-darken'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-yellow"
                                                      data-widget-setstyle="jarviswidget-color-yellow"
                                                      data-toggle="tooltip"
                                                      data-placement="left"
                                                      data-original-title="<?php echo __('Day Light'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-yellow'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-orange"
                                                      data-widget-setstyle="jarviswidget-color-orange"
                                                      data-toggle="tooltip"
                                                      data-placement="bottom"
                                                      data-original-title="<?php echo __('Orange'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-orange'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-orangeDark"
                                                      data-widget-setstyle="jarviswidget-color-orangeDark"
                                                      data-toggle="tooltip"
                                                      data-placement="bottom"
                                                      data-original-title="<?php echo __('Dark Orange'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-orangeDark'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-red"
                                                      data-widget-setstyle="jarviswidget-color-red"
                                                      data-toggle="tooltip"
                                                      data-placement="bottom"
                                                      data-original-title="<?php echo __('Red Rose'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-red'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-redLight"
                                                      data-widget-setstyle="jarviswidget-color-redLight"
                                                      data-toggle="tooltip"
                                                      data-placement="bottom"
                                                      data-original-title="<?php echo __('Light Red'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-redLight'">
                                                </span>
                                            </li>
                                            <li>
                                                <span class="bg-color-white"
                                                      data-widget-setstyle="jarviswidget-color-white"
                                                      data-toggle="tooltip"
                                                      data-placement="right"
                                                      data-original-title="<?php echo __('Purity'); ?>"
                                                      ng-click="widget.color='jarviswidget-color-white'">
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </header>
                                <!-- Loading used AngularJs directives dynamically -->
                                <div role="content" id="widget-content-{{widget.id}}"
                                     style="height:100%; overflow: auto;">
                                    <ng-include
                                        src="'/dashboards/dynamicDirective?directive='+widget.directive"></ng-include>
                                </div>
                            </div>
                            <div ng-if="$last" ng-init="$last?enableWatch():null"></div>
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
                                       ng-model="data.newTabName">
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

                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Select shared tab'); ?>
                    </div>
                    <div class="col-xs-12">
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="sharedTabs"
                            ng-options="sharedTab.id as sharedTab.name for sharedTab in sharedTabs"
                            ng-model="data.createTabFromSharedTabId">
                        </select>
                    </div>
                    <div class="col-xs-12 padding-top-10">
                        <button type="button" class="btn btn-primary pull-right" ng-click="addFromSharedTab()">
                            <?php echo __('Create from shared tab'); ?>
                        </button>
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
                                   ng-model="data.viewTabRotateInterval" ng-mouseup="saveTabRotateInterval()">
                            <div>
                                <div class="help-block text-muted">{{ intervalText }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-xs-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('If you only have one tab, the rotation will just refresh the data of your current tab.'); ?>
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
                                       ng-model="data.renameTabName">
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


<!-- Update available modal -->
<div id="updateAvailableModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-code-fork"></i>
                    <?php echo __('For your dashboard is an update available'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('You created this dashboard out of an "shared" dashboard. The original dashboard was updated.'); ?>
                        <br/>
                        <?php echo __('This means the original dashboard was reorder, new objects where added or existing objects gets deleted.'); ?>
                        <?php echo __('You can now choose if you want to update your dashboard or keep your current dashboard.'); ?>
                        <br/><br/>
                        <?php echo __('Warning: By updating your dashboard, local modifications will get lost.'); ?>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" ng-click="neverPerformUpdates()">
                    <?php echo __('Never perform an update for this dashboard'); ?>
                </button>

                <button type="button" class="btn btn-primary " ng-click="performUpdate()">
                    <?php echo __('Yes update'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('No thanks'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Rename widget modal -->
<div id="renameWidgetModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fas fa-edit"></i>
                    <?php echo __('Edit widget title'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': ack.error}">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('New title of widget'); ?>"
                                       ng-model="data.renameWidgetTitle">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.name">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <br/>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="renameWidget()">
                    <?php echo __('Save widget Title'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>





