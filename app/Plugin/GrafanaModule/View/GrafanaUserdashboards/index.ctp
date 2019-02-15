<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

?>
<div class="alert auto-hide alert-success" style="display:none;"
     id="flashMessage"></div>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-area-chart fa-fw "></i>
            <?php echo __('Grafana'); ?>
            <span>>
                <?php echo __('User Dashboards'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>

<massdelete></massdelete>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-dashboard"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('User defined Grafana dashboards'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>
            <?php if ($this->Acl->hasPermission('add', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                <a ui-sref="GrafanaUserdashboardsAdd" class="btn btn-xs btn-success">
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
    </header>
    <div>
        <div class="widget-body no-padding">
            <div class="list-filter well" ng-show="showFilter">
                <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-tag"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Filter by name'); ?>"
                                       ng-model="filter.GrafanaUserdashboard.name"
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

            <table id="userdashboard_list" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th class="no-sort sorting_disabled width-15">
                        <i class="fa fa-check-square-o fa-lg"></i>
                    </th>
                    <th class="no-sort" ng-click="orderBy('GrafanaUserdashboard.name')">
                        <i class="fa" ng-class="getSortClass('GrafanaUserdashboard.name')"></i>
                        <?php echo __('User dashboard name'); ?>
                    </th>
                    <th class="no-sort text-center" style="width:70px;">
                        <i class="fa fa-gear fa-lg"></i>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="userdashboard in allUserdashboards">
                    <td class="text-center width-15">
                        <input type="checkbox"
                               ng-model="massChange[userdashboard.GrafanaUserdashboard.id]"
                               ng-show="userdashboard.GrafanaUserdashboard.allowEdit">
                    </td>
                    <td>
                        <?php if ($this->Acl->hasPermission('view', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                            <a ui-sref="GrafanaUserdashboardsView({id: userdashboard.GrafanaUserdashboard.id})"
                               ng-show="userdashboard.GrafanaUserdashboard.grafana_url !== ''">
                                {{ userdashboard.GrafanaUserdashboard.name }}
                            </a>
                            <span ng-show="userdashboard.GrafanaUserdashboard.grafana_url == ''">{{ userdashboard.GrafanaUserdashboard.name }}
                            </span>
                            <span ng-show="userdashboard.GrafanaUserdashboard.grafana_url == ''"
                                  class="label label-primary font-xs">
                                <?php echo __('Not synchronized'); ?>
                            </span>
                        <?php else: ?>
                            {{ userdashboard.GrafanaUserdashboard.name }}
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group smart-form">
                            <?php if ($this->Acl->hasPermission('editor', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                                <a ui-sref="GrafanaUserdashboardsEditor({id: userdashboard.GrafanaUserdashboard.id})"
                                   ng-if="userdashboard.GrafanaUserdashboard.allowEdit"
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-cog"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-default btn-xs"
                                   ng-if="!userdashboard.GrafanaUserdashboard.allowEdit">
                                    <i class="fa fa-cog"></i>
                                </a>
                            <?php else: ?>
                                <a href="javascript:void(0);" class="btn btn-default btn-xs">
                                    <i class="fa fa-cog"></i>
                                </a>
                            <?php endif; ?>
                            <a href="javascript:void(0);" data-toggle="dropdown"
                               class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
                            <ul class="dropdown-menu pull-right"
                                id="menuHack-{{userdashboard.GrafanaUserdashboard.id}}">
                                <?php if ($this->Acl->hasPermission('editor', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                                    <li ng-if="userdashboard.GrafanaUserdashboard.allowEdit">
                                        <a ui-sref="GrafanaUserdashboardsEditor({id: userdashboard.GrafanaUserdashboard.id})">
                                            <i class="fa fa-cog"></i> <?php echo __('Open in Editor'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('edit', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                                    <li ng-if="userdashboard.GrafanaUserdashboard.allowEdit">
                                        <a ui-sref="GrafanaUserdashboardsEdit({id: userdashboard.GrafanaUserdashboard.id})">
                                            <i class="fa fa-edit"></i> <?php echo __('Edit settings'); ?>
                                        </a>
                                    </li>
                                    <li class="divider" ng-if="userdashboard.GrafanaUserdashboard.allowEdit"></li>
                                <?php endif; ?>

                                <?php if ($this->Acl->hasPermission('view', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                                    <li>
                                        <a ui-sref="GrafanaUserdashboardsView({id: userdashboard.GrafanaUserdashboard.id})">
                                            <i class="fa fa-eye"></i> <?php echo __('View'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('delete', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                                    <li class="divider" ng-if="userdashboard.GrafanaUserdashboard.allowEdit"></li>
                                    <li ng-if="userdashboard.GrafanaUserdashboard.allowEdit">
                                        <a class="txt-color-red"
                                           href="javascript:void(0);" class="txt-color-red"
                                           ng-click="confirmDelete(getObjectForDelete(userdashboard))">
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
                <div class="row margin-top-10 margin-bottom-10" ng-show="allUserdashboards.length == 0">
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
            <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
            <?php echo $this->element('paginator_or_scroll'); ?>
        </div>
    </div>
</div>
