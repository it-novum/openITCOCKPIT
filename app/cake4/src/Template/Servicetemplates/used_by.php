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
            <i class="fa fa-code-fork fa-fw "></i>
            <?php echo __('Service template'); ?>
            <span>>
                <?php echo __('Used by...'); ?>
            </span>
        </h1>
    </div>
</div>

<massdelete></massdelete>


<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <a back-button fallback-state='ServicetemplatesIndex' class="btn btn-default btn-xs">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    </div>

                    <div class="widget-toolbar">
                        <div class="form-group smart-form no-padding">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       ng-model="filter.includeDisabled"
                                       ng-model-options="{debounce: 500}"
                                       ng-true-value="true"
                                       ng-false-value="false">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Include disabled services'); ?>
                            </label>
                        </div>
                    </div>

                    <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
                    <h2><?php echo __('Service template'); ?>
                        <strong>
                            »{{ servicetemplate.name }}«
                        </strong>
                        <?php echo __('is used by'); ?>
                        {{ count }}
                        <?php echo __('services.'); ?>
                    </h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table id="service_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
                                <th class="no-sort"><?php echo __('Service name'); ?></th>
                                <th class="no-sort text-center editItemWidth">
                                    <i class="fa fa-gear fa-lg"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody ng-show="hostsWithServices">
                            <tr ng-repeat-start="host in hostsWithServices">
                                <td colspan="3" class="service_table_host_header">
                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a ui-sref="HostsBrowser({id:host.id})"
                                           class="padding-left-5 txt-color-blueDark">
                                            {{ host.name }} ({{host.address}})
                                        </a>
                                    <?php else: ?>
                                        {{host.hostname}} ({{host.address}})
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                        <a ui-sref="ServicesServiceList({id:host.id})"
                                           class="pull-right txt-color-blueDark">
                                            <i class="fa fa-list"
                                               title=" <?php echo __('Go to Service list'); ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr ng-repeat="service in host.services" ng-repeat-end="">
                                <td class="width-5">
                                    <input type="checkbox"
                                           ng-model="massChange[service.id]"
                                           ng-show="host.allow_edit">
                                </td>

                                <td>
                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                        <a ui-sref="ServicesBrowser({id: service.id})">
                                            {{ service.servicename }}
                                            <span ng-show="service.disabled" title="<?php echo __('Disabled'); ?>">
                                                <i class="fa fa-plug"></i>
                                            </span>
                                        </a>
                                    <?php else: ?>
                                        {{ service.servicename }}
                                        <span ng-show="service.disabled" title="<?php echo __('Disabled'); ?>">
                                                <i class="fa fa-plug"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="width-50">
                                    <div class="btn-group">
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <a ui-sref="ServicesEdit({id: service.id})"
                                               ng-if="host.allow_edit"
                                               class="btn btn-default">
                                                &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                            </a>
                                        <?php else: ?>
                                            <a href="javascript:void(0);" class="btn btn-default">
                                                &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                           class="btn btn-default dropdown-toggle"><span
                                                    class="caret"></span></a>
                                        <ul class="dropdown-menu pull-right" id="menuHack-{{service.uuid}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <li ng-if="host.allow_edit">
                                                    <a ui-sref="ServicesEdit({id: service.id})">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                <li class="divider"></li>
                                                <li ng-if="host.allow_edit">
                                                    <a href="javascript:void(0);" class="txt-color-red"
                                                       ng-click="confirmDelete(getObjectForDelete(host, service))">
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
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="hostsWithServices.length === 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('This service template is not used by any services'); ?>
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
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
