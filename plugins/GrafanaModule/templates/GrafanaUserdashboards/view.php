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
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Grafana Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="GrafanaUserdashboardsIndex">
            <i class="fas fa-chart-area"></i> <?php echo __('User dashboards'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-eye"></i> <?php echo __('View'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Dashboard'); ?>
                    <span class="fw-300"><i>{{ dashboard.name }}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <grafana-timepicker callback="grafanaTimepickerCallback"></grafana-timepicker>
                    <?php if ($this->Acl->hasPermission('editor', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                        <button class="btn btn-xs btn-default mr-1 shadow-0 margin-left-5"
                                ui-sref="GrafanaUserdashboardsEditor({id: dashboard.id})">
                            <i class="fa fa-cog"></i> <?php echo __('Open in Editor'); ?>
                        </button>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('index', 'GrafanaUserdashboards', 'GrafanaModule')): ?>
                        <a back-button fallback-state='GrafanaUserdashboardsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <iframe-directive url="iframeUrl" ng-if="dashboardFoundInGrafana"></iframe-directive>
                    <div ng-if="!dashboardFoundInGrafana" class="jumbotron text-center bg-color-white">
                        <div id="notFoundSvg">
                            <svg class="scaling-svg">
                                <symbol id="not-found-text">
                                    <text text-anchor="middle"
                                          x="50%"
                                          y="80%"
                                          class="textline"
                                          fill="none" stroke="#a94442">
                                        <?php echo __('404 Ooops...'); ?>
                                    </text>
                                </symbol>
                                <g class="g-ants">
                                    <use xlink:href="#not-found-text"
                                         class="text-add"></use>
                                    <use xlink:href="#not-found-text"
                                         class="text-add"></use>
                                    <use xlink:href="#not-found-text"
                                         class="text-add"></use>
                                    <use xlink:href="#not-found-text"
                                         class="text-add"></use>
                                    <use xlink:href="#not-found-text"
                                         class="text-add"></use>
                                </g>
                            </svg>
                        </div>
                        <h1><?php //echo __('Ooops...'); ?></h1>
                        <p>
                            <?php echo __('Dashboard not found in Grafana'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
