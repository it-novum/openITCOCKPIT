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
        <a href="<?php echo $this->webroot; ?>">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ServicesIndex">
            <i class="fa fa-cog"></i> <?php echo __('Services'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-user-md"></i> <?php echo __('Not monitored'); ?>
    </li>
</ol>
<massdelete></massdelete>
<massdeactivate></massdeactivate>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Services'); ?>
                    <span class="fw-300"><i><?php echo __('Not monitored'); ?></i></span>
                </h2>
                <div class="panel-toolbar pr-3 align-self-end">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" ui-sref="ServicesIndex" role="tab">
                                <i class="fa fa-stethoscope"></i> <?php echo __('Monitored'); ?>
                            </a>
                        </li>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'services')): ?>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" ui-sref="ServicesNotMonitored" role="tab">
                                    <i class="fa fa-user-md"></i> <?php echo __('Not monitored'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'services')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="ServicesDisabled" role="tab">
                                    <i class="fa fa-plug"></i> <?php echo __('Disabled'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="ServicesAdd">
                            <i class="fas fa-plus"></i> <?php echo __('New'); ?>
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-xs btn-primary shadow-0" ng-click="triggerFilter()">
                        <i class="fas fa-filter"></i> <?php echo __('Filter'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="service_list" class="table table-striped m-0 table-bordered">
                        <thead>
                        <tr>
                            <th colspan="2" class="no-sort width-90">
                                <?php echo __('Servicestatus'); ?>
                            </th>

                            <th class="no-sort" ng-click="orderBy('servicename')">
                                <i class="fa" ng-class="getSortClass('servicename')"></i>
                                <?php echo __('Service name'); ?>
                            </th>


                            <th class="no-sort text-center editItemWidth width-50">
                                <i class="fa fa-gear fa-lg"></i>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr ng-repeat-start="host in services">
                            <td colspan="13" class="service_table_host_header">

                                <hoststatusicon host="host"></hoststatusicon>

                                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                    <a class="padding-left-5 txt-color-blueDark"
                                       ui-sref="HostsBrowser({id:host.Host.id})">
                                        {{host.Host.hostname}} ({{host.Host.address}})
                                    </a>
                                <?php else: ?>
                                    {{host.Host.hostname}} ({{host.Host.address}})
                                <?php endif; ?>

                                <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                    <a class="pull-right txt-color-blueDark"
                                       ui-sref="ServicesServiceList({id: host.Host.id})">
                                        <i class="fa fa-list"
                                           title=" <?php echo __('Go to Service list'); ?>"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr ng-repeat="service in host.Services" ng-repeat-end="">

                            <td class="width-5">
                                <input type="checkbox"
                                       ng-model="massChange[service.id]"
                                       ng-show="service.allow_edit">
                            </td>

                            <td class="text-center width-90">
                                <servicestatusicon service="fakeServicestatus"></servicestatusicon>
                            </td>


                            <td>
                                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                    <a ui-sref="ServicesBrowser({id:service.id})">
                                        {{ service.servicename }}
                                    </a>
                                <?php else: ?>
                                    {{ service.servicename }}
                                <?php endif; ?>
                            </td>


                            <td class="width-50">
                                <div class="btn-group">
                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                        <a ui-sref="ServicesEdit({id: service.id})"
                                           ng-if="service.allow_edit"
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
                                            <li ng-if="service.allow_edit">
                                                <a ui-sref="ServicesEdit({id: service.id})">
                                                    <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                            <li ng-if="service.allow_edit">
                                                <a href="javascript:void(0);"
                                                   ng-click="confirmDeactivate(getObjectForDelete(host, service))">
                                                    <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <li ng-if="service.allow_edit">
                                                <?php echo $this->AdditionalLinks->renderAsListItems(
                                                    $additionalLinksList,
                                                    '{{service.id}}',
                                                    [],
                                                    true
                                                ); ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                            <li class="divider"></li>
                                            <li ng-if="service.allow_edit">
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
                    <div class="margin-top-10" ng-show="services.length == 0">
                        <div class="text-center text-danger italic">
                            <?php echo __('No entries match the selection'); ?>
                        </div>
                    </div>
                    <div class="row margin-top-10 margin-bottom-10">
                        <div class="col-xs-12 col-md-2 text-muted text-center">
                            <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                        </div>
                        <div class="col-xs-12 col-md-2">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fas fa-lg fa-check-square"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                        </div>
                        <div class="col-xs-12 col-md-2">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fas fa-lg fa-square"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <a ui-sref="ServicesCopy({ids: linkForCopy()})" class="a-clean">
                                <i class="fas fa-lg fa-files-o"></i>
                                <?php echo __('Copy'); ?>
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fas fa-trash"></i>
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
    </div>
</div>