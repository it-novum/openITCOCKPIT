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
            <?php echo __('Host templates'); ?>
            <span>>
                <?php echo __('used by...'); ?>
            </span>
        </h1>
    </div>
</div>

<massdelete></massdelete>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
        <h2><?php echo __('Host template'); ?>
            <strong>
                »{{ hosttemplate.name }}«
            </strong>
            <?php echo __('is used by'); ?>
            {{ total }}
            <?php echo __('hosts.'); ?>
        </h2>

        <div class="widget-toolbar" role="menu">

            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <?php if ($this->Acl->hasPermission('index', 'hosttemplates')): ?>
                <a back-button fallback-state='HosttemplatesIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
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
                    <?php echo __('Include disabled hosts'); ?>
                </label>
            </div>
        </div>

    </header>
    <div>
        <div class="widget-body no-padding">
            <table class="table table-striped table-hover table-bordered smart-form"
                   style="">
                <tbody>
                <tr>
                    <th class="no-sort sorting_disabled width-15">
                        <i class="fa fa-check-square-o fa-lg"></i>
                    </th>
                    <th>
                        <?php echo __('Host name'); ?>
                    </th>
                    <th class="no-sort text-center editItemWidth">
                        <i class="fa fa-gear fa-lg"></i>
                    </th>
                </tr>
                <tr ng-repeat="host in allHosts">
                    <td class="text-center" class="width-15">
                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                            <input type="checkbox"
                                   ng-if="host.Host.allow_edit"
                                   ng-model="massChange[host.Host.id]">
                        <?php endif; ?>
                    </td>
                    <td class="">
                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                            <a ui-sref="HostsBrowser({id: host.Host.id})">
                                {{ host.Host.hostname }} ({{ host.Host.address }})

                                <span ng-show="host.Host.disabled" title="<?php echo __('Disabled'); ?>">
                                    <i class="fa fa-plug"></i>
                                </span>
                            </a>
                        <?php else: ?>
                            {{ host.Host.hostname }} ({{ host.Host.address }})
                            <span ng-show="host.Host.disabled" title="<?php echo __('Disabled'); ?>">
                                <i class="fa fa-plug"></i>
                            </span>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                            <a class="pull-right txt-color-blueDark"
                               ui-sref="ServicesServiceList({id: host.Host.id})">
                                <i class="fa fa-list" title="<?php echo __('Go to Service list'); ?>"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="width-50">
                        <div class="btn-group">
                            <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                <a ui-sref="HostsEdit({id: host.Host.id})"
                                   ng-if="host.Host.allow_edit"
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
                            <ul class="dropdown-menu pull-right" id="menuHack-{{host.Host.uuid}}">
                                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                    <li ng-if="host.Host.allow_edit">
                                        <a ui-sref="HostsEdit({id: host.Host.id})">
                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('delete', 'hosts')): ?>
                                    <li class="divider"></li>
                                    <li ng-if="host.Host.allow_edit">
                                        <a href="javascript:void(0);" class="txt-color-red"
                                           ng-click="confirmDelete(getObjectForDelete(host))">
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
                <div class="row margin-top-10 margin-bottom-10" ng-show="allHosts.length == 0">
                    <div class="col-xs-12 text-center txt-color-red italic">
                        <?php echo __('This host template is not used by any host'); ?>
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
