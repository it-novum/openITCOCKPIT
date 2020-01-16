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
                <?php echo __('Deleted'); ?>
            </span>
        </h1>
    </div>
</div>

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

                        <?php if ($this->Acl->hasPermission('add', 'hosts')): ?>
                            <a ui-sref="HostsAdd" class="btn btn-xs btn-success">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                        <?php
                        /**
                         * @todo AdditionalLinks
                         */
                        /*
                        echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop);
                        */?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Deleted hosts overview'); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                            <li class="">
                                <a ui-sref="HostsIndex"><i
                                            class="fa fa-stethoscope"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'hosts')): ?>
                            <li class="">
                                <a ui-sref="HostsNotMonitored"><i
                                            class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'hosts')): ?>
                            <li>
                                <a ui-sref="HostsDisabled"><i
                                            class="fa fa-power-off"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <li class="active">
                            <a ui-sref="DeletedHostsIndex"><i
                                        class="fa fa-trash-o"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
                        </li>
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
                                                   ng-model="filter.DeletedHost.name"
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

                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort" ng-click="orderBy('DeletedHosts.name')">
                                        <i class="fa" ng-class="getSortClass('DeletedHosts.name')"></i>
                                        <?php echo __('Host name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('DeletedHosts.uuid')">
                                        <i class="fa" ng-class="getSortClass('DeletedHosts.uuid')"></i>
                                        <?php echo __('UUID'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('DeletedHosts.created')">
                                        <i class="fa" ng-class="getSortClass('DeletedHosts.created')"></i>
                                        <?php echo __('Date'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('DeletedHosts.deleted_perfdata')">
                                        <i class="fa" ng-class="getSortClass('DeletedHosts.deleted_perfdata')"></i>
                                        <?php echo __('Performance data deleted'); ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="host in hosts">
                                    <td>
                                        {{ host.DeletedHost.name }}
                                    </td>

                                    <td>
                                        {{ host.DeletedHost.uuid }}
                                    </td>

                                    <td>
                                        {{ host.DeletedHost.created }}
                                    </td>

                                    <td class="text-center">
                                        <i class="fa fa-check text-success"
                                           ng-show="host.DeletedHost.perfdataDeleted"></i>
                                        <i class="fa fa-times txt-color-red"
                                           ng-show="!host.DeletedHost.perfdataDeleted"></i>
                                    </td>
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


                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
