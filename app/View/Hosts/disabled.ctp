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
            <i class="fa fa-desktop fa-fw "></i>
            <?php echo __('Hosts') ?>
            <span>>
                <?php echo __('Disabled'); ?>
            </span>
        </h1>
    </div>
</div>

<massdelete></massdelete>
<massactivate></massactivate>

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
                            <a href="/hosts/add" class="btn btn-xs btn-success">
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
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hosts'); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <?php if ($this->Acl->hasPermission('index')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'index'], $this->params['named'])); ?>"><i
                                            class="fa fa-stethoscope"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('notMonitored')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'notMonitored'], $this->params['named'])); ?>"><i
                                            class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <li class="active">
                            <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'disabled'], $this->params['named'])); ?>"><i
                                        class="fa fa-power-off"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                        </li>
                        <?php if ($this->Acl->hasPermission('index', 'DeletedHosts')): ?>
                            <li>
                                <a href="<?php echo Router::url(array_merge(['controller' => 'deleted_hosts', 'action' => 'index'], $this->params['named'])); ?>"><i
                                            class="fa fa-trash-o"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </header>
                <div>
                    <div class="widget-body no-padding">

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
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by IP address'); ?>"
                                                   ng-model="filter.Host.address"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php if (sizeof($satellites) > 1): ?>
                                    <div class="col-xs-12 col-md-3">
                                        <fieldset>
                                            <legend><?php echo __('Instance'); ?></legend>
                                            <div class="form-group smart-form">
                                                <select
                                                        id="Instance"
                                                        data-placeholder="<?php echo __('Filter by instance'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        multiple
                                                        ng-model="filter.Host.satellite_id"
                                                        ng-model-options="{debounce: 500}">
                                                    <?php
                                                    foreach ($satellites as $satelliteId => $satelliteName):
                                                        printf('<option value="%s">%s</option>', h($satelliteId), h($satelliteName));
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                <?php endif; ?>

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

                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort sorting_disabled width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort"><?php echo __('Host status'); ?></th>
                                    <th class="no-sort" ng-click="orderBy('Host.name')">
                                        <i class="fa" ng-class="getSortClass('Host.name')"></i>
                                        <?php echo __('Host name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Host.address')">
                                        <i class="fa" ng-class="getSortClass('Host.address')"></i>
                                        <?php echo __('IP address'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Hosttemplate.name')">
                                        <i class="fa" ng-class="getSortClass('Hosttemplate.name')"></i>
                                        <?php echo __('Hosttemplate name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Host.uuid')">
                                        <i class="fa" ng-class="getSortClass('Host.uuid')"></i>
                                        <?php echo __('UUID'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Host.satellite_id')">
                                        <i class="fa" ng-class="getSortClass('Host.satellite_id')"></i>
                                        <?php echo __('Instance'); ?>
                                    </th>
                                    <th class="no-sort text-center editItemWidth"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="host in hosts">
                                    <td class="width-5">
                                        <input type="checkbox"
                                               ng-model="massChange[host.Host.id]"
                                               ng-show="host.Host.allow_edit">
                                    </td>

                                    <td class="text-center">
                                        <hoststatusicon host="host"></hoststatusicon>
                                    </td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('browser')): ?>
                                            <a href="/hosts/browser/{{ host.Host.id }}">
                                                {{ host.Host.hostname }}
                                            </a>
                                        <?php else: ?>
                                            {{ host.Host.hostname }}
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        {{ host.Host.address }}
                                    </td>

                                    <td>
                                        {{ host.Hosttemplate.hostname }}
                                    </td>

                                    <td>
                                        {{ host.Host.uuid }}
                                    </td>

                                    <td>
                                        {{ host.Host.satelliteName }}
                                    </td>

                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                <a href="/hosts/edit/{{host.Host.id}}/_controller:hosts/_action:disabled/"
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
                                                <?php if ($this->Acl->hasPermission('edit')): ?>
                                                    <li ng-if="host.Host.allow_edit">
                                                        <a href="/hosts/edit/{{host.Host.id}}/_controller:hosts/_action:disabled/">
                                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('sharing')): ?>
                                                    <li ng-if="host.Host.allow_sharing">
                                                        <a href="/hosts/sharing/{{host.Host.id}}">
                                                            <i class="fa fa-sitemap fa-rotate-270"></i>
                                                            <?php echo __('Sharing'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('deactivate')): ?>
                                                    <li ng-if="host.Host.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           ng-click="confirmActivate(getObjectForDelete(host))">
                                                            <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                                    <li>
                                                        <a href="/services/serviceList/{{host.Host.id}}">
                                                            <i class="fa fa-list"></i> <?php echo __('Service List'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($this->Acl->hasPermission('edit')): ?>
                                                    <li ng-if="host.Host.allow_edit">
                                                        <?php echo $this->AdditionalLinks->renderAsListItems(
                                                            $additionalLinksList,
                                                            '{{host.Host.id}}',
                                                            [],
                                                            true
                                                        ); ?>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete')): ?>
                                                    <li class="divider" ng-if="host.Host.allow_edit"></li>
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
                        </div>

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="hosts.length == 0">
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
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="confirmActivate(getObjectsForDelete())" class="pointer">
                                    <i class="fa fa-lg fa-plug"></i>
                                    <?php echo __('Enable'); ?>
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

        </article>
    </div>
</section>
