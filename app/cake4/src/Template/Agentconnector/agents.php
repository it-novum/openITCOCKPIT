<?php
// Copyright (C) <2018>  <it-novum GmbH>
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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-secret fa-fw "></i>
            <?php echo __('openITCOCKPIT Agent'); ?>
            <span>>
                <?php echo __('Agents'); ?>
            </span>
        </h1>
    </div>
</div>


<massdelete></massdelete>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add', 'agentconnector')): ?>
                            <a class="btn btn-xs btn-success" ui-sref="AgentconnectorsAdd">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-pencil-square-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Agents overview'); ?></h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by host uuid'); ?>"
                                                   ng-model="filter.hostuuid"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by agent ip'); ?>"
                                                   ng-model="filter.remote_addr"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mobile_table">
                            <table class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort sorting_disabled width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-user-secret fa-lg" title="<?php echo __('Is trusted'); ?>"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Agentconnector.hostuuid')">
                                        <i class="fa" ng-class="getSortClass('Agentconnector.hostuuid')"></i>
                                        <?php echo __('Host'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Agentconnector.remote_addr')">
                                        <i class="fa" ng-class="getSortClass('Agentconnector.remote_addr')"></i>
                                        <?php echo __('Agent IP Address'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Agentconnector.generation_date')">
                                        <i class="fa" ng-class="getSortClass('Agentconnector.generation_date')"></i>
                                        <?php echo __('Certificate generation date'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr ng-repeat="agent in agents">
                                    <td class="text-center" class="width-15">
                                        <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                                            <input type="checkbox"
                                                   ng-model="massChange[agent.Agentconnector.id]">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-lg fa-check" ng-show="agent.Agentconnector.trusted"></i>
                                        <i class="fa fa-lg fa-times-circle-o" ng-hide="agent.Agentconnector.trusted"></i>
                                    </td>
                                    <td>{{agent.Agentconnector.hostuuid}}</td>
                                    <td>{{agent.Agentconnector.http_x_forwarded_for ? agent.Agentconnector.http_x_forwarded_for :
                                        agent.Agentconnector.remote_addr}}
                                    </td>
                                    <td>{{agent.Agentconnector.generation_date}}</td>
                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                    class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{agent.Agentconnector.id}}">
                                                <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                                                    <li ng-hide="agent.Agentconnector.trusted">
                                                        <a ng-click="changetrust(agent.Agentconnector.id, 1, true)" class="cursor-pointer">
                                                            <i class="fa fa-check"></i>
                                                            <?php echo __('Trust Agent'); ?>
                                                        </a>
                                                    </li>
                                                    <li ng-show="agent.Agentconnector.trusted">
                                                        <a ng-click="changetrust(agent.Agentconnector.id, 0, true)" class="cursor-pointer">
                                                            <i class="fa fa-times-circle-o"></i>
                                                            <?php echo __('Untrust Agent'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(agent))">
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
                            <div class="row margin-top-10 margin-bottom-10" ng-show="agentchecks.length == 0">
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
                                <span ng-click="trustSelected()" class="pointer">
                                <i class="fa fa-lg fa-check"></i>
                                <?php echo __('Trust'); ?>
                            </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="untrustSelected()" class="pointer">
                                <i class="fa fa-lg fa-times-circle-o"></i>
                                <?php echo __('Untrust'); ?>
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
        </article>
    </div>
</section>



