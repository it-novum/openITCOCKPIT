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

<massdelete></massdelete>

<div class="panel-content">

    <div class="list-filter card margin-bottom-10" ng-show="showFilter">
        <div class="card-header">
            <i class="fa fa-filter"></i> <?php echo __('Filter'); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by host uuid'); ?>"
                                   ng-model="filter.hostuuid"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by agent ip'); ?>"
                                   ng-model="filter.remote_addr"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="float-right margin-top-20">
                <button type="button" ng-click="resetFilter()"
                        class="btn btn-xs btn-danger">
                    <?php echo __('Reset Filter'); ?>
                </button>
            </div>
        </div>
    </div>

    <div class="frame-wrap">
        <table class="table table-striped m-0 table-bordered table-hover table-sm">
            <thead>
            <tr>
                <th class="no-sort sorting_disabled width-15">
                    <i class="fa fa-check-square fa-lg"></i>
                </th>
                <th class="no-sort text-center">
                    <i class="fa fa-user-secret fa-lg"
                       title="<?php echo __('Is trusted'); ?>"></i>
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
                    <i class="fa fa-cog"></i>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr ng-repeat="agent in unTrustedAgents">
                <td class="text-center" class="width-15">
                    <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                        <input type="checkbox"
                               ng-model="massChange[agent.Agentconnector.id]">
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <i class="fa fa-lg fa-check text-success" ng-show="agent.Agentconnector.trusted"></i>
                    <i class="fa fa-lg fa-times-circle text-danger"
                       ng-hide="agent.Agentconnector.trusted"></i>
                </td>
                <td><a ui-sref="HostsBrowser({id: agent.Host.id})">{{agent.Host.name}}
                        ({{agent.Agentconnector.hostuuid}})</a></td>
                <td>{{agent.Agentconnector.http_x_forwarded_for ?
                    agent.Agentconnector.http_x_forwarded_for :
                    agent.Agentconnector.remote_addr}}
                </td>
                <td>{{agent.Agentconnector.generation_date ? agent.Agentconnector.generation_date :
                    '<?php echo __("Certificate not yet generated") ?>'}}
                </td>
                <td class="width-50">
                    <div class="btn-group btn-group-xs" role="group">
                        <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                            <a href="javascript:void(0);"
                               class="btn btn-default btn-lower-padding">
                                <i class="fa fa-cog"></i>
                            </a>
                        <?php else: ?>
                            <a href="javascript:void(0);"
                               class="btn btn-default btn-lower-padding disabled">
                                <i class="fa fa-cog"></i>
                            </a>
                        <?php endif; ?>
                        <button type="button"
                                class="btn btn-default dropdown-toggle btn-lower-padding"
                                data-toggle="dropdown">
                            <i class="caret"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right"
                             id="menuHack-{{agent.Agentconnector.id}}">
                            <?php if ($this->Acl->hasPermission('changetrust', 'agentconnector')): ?>
                                <a ng-click="changetrust(agent.Agentconnector.id, 1, true)"
                                   ng-hide="agent.Agentconnector.trusted"
                                   href="javascript:void(0);" class="dropdown-item">
                                    <i class="fa fa-check"></i>
                                    <?php echo __('Trust Agent'); ?>
                                </a>
                                <a ng-click="changetrust(agent.Agentconnector.id, 0, true)"
                                   ng-show="agent.Agentconnector.trusted"
                                   href="javascript:void(0);" class="dropdown-item">
                                    <i class="fa fa-times-circle"></i>
                                    <?php echo __('Untrust Agent'); ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($this->Acl->hasPermission('delete', 'agentconnector')): ?>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);"
                                   class="dropdown-item txt-color-red"
                                   ng-click="confirmDelete(getObjectForDelete(agent))">
                                    <i class="fa fa-trash"></i> <?php echo __('Delete'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="col-12 margin-top-10 margin-bottom-10" ng-show="agentchecks.length == 0">
            <div class="text-center txt-color-red italic">
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
                <i class="fa fa-lg fa-check-square"></i>
                <?php echo __('Select all'); ?>
            </span>
        </div>
        <div class="col-xs-12 col-md-2">
            <span ng-click="undoSelection()" class="pointer">
                <i class="fa fa-lg fa-square"></i>
                <?php echo __('Undo selection'); ?>
            </span>
        </div>
        <div class="col-xs-12 col-md-2 text-success">
            <span ng-click="trustSelected()" class="pointer">
                <i class="fa fa-lg fa-check"></i>
                <?php echo __('Trust'); ?>
            </span>
        </div>
        <div class="col-xs-12 col-md-2 text-danger">
            <span ng-click="untrustSelected()" class="pointer">
                <i class="fa fa-lg fa-times-circle"></i>
                <?php echo __('Untrust'); ?>
            </span>
        </div>
        <?php if ($this->Acl->hasPermission('delete', 'agentconnector')): ?>
            <div class="col-xs-12 col-md-2 txt-color-red">
                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                    <i class="fa fa-lg fa-trash"></i>
                    <?php echo __('Delete selected'); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
    <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
    <?php echo $this->element('paginator_or_scroll'); ?>
</div>
