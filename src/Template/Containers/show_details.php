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
        <a ui-sref="ContainersIndex">
            <i class="fas fa-globe-americas"></i> <?php echo __('Container Map'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-eye"></i> <?php echo __('Overview'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-lg-12 margin-bottom-10">
        <select
            id="containers"
            data-placeholder="<?php echo __('Please select...'); ?>"
            class="form-control"
            chosen="{containers}"
            ng-model="post.Container.id"
            callback="loadContainers"
            ng-options="container.key as container.value for container in containers | filter: { key: '!'+<?= CT_GLOBAL; ?>}"
            ng-model-options="{debounce: 500}">
        </select>

    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Container '); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                            <li class="nav-item pointer">
                                <a class="nav-link active" data-toggle="tab" ng-click="tabName='Containers'" role="tab">
                                    <i class="fas fa-layer-group"></i>&nbsp;</i> <?php echo __('Containers'); ?>
                                </a>
                            </li>
                            <li class="nav-item pointer">
                                <a class="nav-link" data-toggle="tab" ng-click="tabName='ContainersMap'" role="tab">
                                    <i class="fas fa-sitemap"></i>&nbsp;</i> <?php echo __('Containers map'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="form-group no-margin padding-right-10">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by container name'); ?>"
                                   ng-model="filter.Hosts.address"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>
                    <div class="form-group no-margin padding-right-10">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-desktop"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm"
                                   placeholder="<?php echo __('Filter by host name'); ?>"
                                   ng-model="filter.Hosts.name"
                                   ng-model-options="{debounce: 500}">
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox padding-right-10">
                        <input type="checkbox"
                               id="showAll"
                               class="custom-control-input"
                               name="checkbox"
                               checked="checked"
                               ng-model="filter.expandAll"
                               ng-model-options="{debounce: 500}"
                               ng-true-value="false"
                               ng-false-value="true">
                        <label
                            class="custom-control-label no-margin"
                            for="showAll"> <?php echo __('Expand all'); ?></label>
                    </div>

                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="clearFilter();">
                        <i class="fas fa-undo"></i> <?php echo __('Reset'); ?>
                    </button>

                    <button class="btn btn-xs btn-success shadow-0" ng-click="toggleFullscreenMode()"
                            title="<?php echo __('Fullscreen mode'); ?>">
                        <i class="fa fa-expand-arrows-alt"></i>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <div ng-show="tabName == 'Containers'">
                            <div class="margin-top-10" ng-hide="isEmpty">
                                <table class="table m-0 table-bordered table-hover table-sm">
                                    <tr ng-repeat-start="container in containersWithChilds">
                                        <th colspan="2" class="table-dark">
                                            <h4>{{container.name}}</h4>
                                        </th>
                                    </tr>
                                    <?php foreach(['hosts', 'hosttemplates', 'contacts'] as $object): ?>
                                            <tr ng-show="container.childsElements.<?= $object; ?>">
                                                <td class="col-sm-3">
                                                    <?= $object; ?>
                                                </td>
                                                <td>
                                                    <ul class="margin-0">
                                                        <li ng-repeat="(key, name) in container.childsElements.<?= $object; ?>">
                                                            {{name}}
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                    <?php endforeach; ?>
                                    <tr ng-repeat-end="">
                                    </tr>
                              </table>
                            </div>
                            <div class="margin-top-10" ng-if="isEmpty">
                                <div class="text-center text-danger italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                        <div ng-show="tabName == 'ContainersMap'">
                            <!-- Loader -->
                            <div class="row padding-top-80" style="display:none;" id="visProgressbarLoader">
                                <div class="col-12">
                                    <div class="visloader-progressbar-center">
                                        <div class="text-center">
                                            {{nodesCount}} <?php echo __(' nodes'); ?>
                                            <span class="statusmap-progress-dots"></span>
                                        </div>
                                        <div class="progress">
                                            <div
                                                class="progress-bar progress-bar-striped bg-secondary progress-bar-animated"
                                                role="progressbar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Loader -->


                            <div class="frame-wrap">
                                <div class="margin-top-10" ng-if="isEmpty">
                                    <div class="text-center text-danger italic">
                                        <?php echo __('No entries match the selection'); ?>
                                    </div>
                                </div>
                                <div id="containermap" class="bg-color-white"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
