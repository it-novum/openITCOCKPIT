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
            <i class="fa fa-home fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Tenants'); ?>
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
                            <a href="/tenants/add" class="btn btn-xs btn-success">
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
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cogs"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Tenants'); ?></h2>
                </header>
                <div>
                    <div class="widget-body no-padding">

                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-cogs"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by name'); ?>"
                                                   ng-model="filter.container.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by description'); ?>"
                                                   ng-model="filter.tenant.description"
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


                        <div class="mobile_table">
                            <table id="tenant_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort sorting_disabled width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Container.name')">
                                        <i class="fa" ng-class="getSortClass('Container.name')"></i>
                                        <?php echo __('Tenant name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Tenant.description')">
                                        <i class="fa" ng-class="getSortClass('Tenant.description')"></i>
                                        <?php echo __('Description'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Tenant.is_active')">
                                        <i class="fa" ng-class="getSortClass('Tenant.is_active')"></i>
                                        <?php echo __('Is active'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="tenant in tenants">
                                    <td class="text-center" class="width-15">
                                        <input type="checkbox"
                                               ng-model="massChange[tenant.Tenant.id]"
                                               ng-show="tenant.Tenant.allowEdit">
                                    </td>
                                    <td>
                                        {{ tenant.Container.name }}
                                    </td>
                                    <td>
                                        {{ tenant.Tenant.description }}
                                    </td>
                                    <td class="text-center">
                                        <i ng-if="tenant.Tenant.is_active == 1"
                                           class="fa fa-check fa-lg txt-color-green"></i>
                                        <i ng-if="tenant.Tenant.is_active == 0"
                                           class="fa fa-power-off fa-lg txt-color-red"></i>
                                    </td>
                                    <td class="text-center">
                                        <a href="/tenants/edit/{{tenant.Tenant.id}}"
                                           ng-if="tenant.Tenant.allowEdit">
                                            <i class="fa fa-cog fa-lg txt-color-teal"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row margin-top-10 margin-bottom-10">
                                <div class="row margin-top-10 margin-bottom-10" ng-show="maps.length == 0">
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
            </div>
        </article>
    </div>
</section>