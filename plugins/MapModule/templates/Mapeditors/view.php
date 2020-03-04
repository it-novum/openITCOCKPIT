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
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Map Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="MapsIndex">
            <i class="fa fa-map-marker"></i> <?php echo __('Maps'); ?>
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
                    <?php echo __('View map:'); ?>
                    <span class="fw-300"><i>{{map.Map.name}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'maps', 'mapmodule')): ?>
                        <a back-button fallback-state='MapsIndex' class="btn btn-default btn-xs mr-1 shadow-0" ng-click="checkFullscreen()">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('edit', 'mapeditors', 'mapmodule')): ?>
                        <button class="btn btn-default btn-xs mr-1 shadow-0" ui-sref="MapeditorsEdit({id: map.Map.id})">
                            <i class="fa fa-edit"></i> <?php echo __('Edit'); ?>
                        </button>
                    <?php endif; ?>
                    <a class="btn btn-xs btn-default" ui-sref="MapeditorsView({id: map.Map.id, fullscreen: 'true'})"
                       ng-show="!fullscreen">
                        <i class="fa fa-expand"></i>
                        <?php echo __('Fullscreen'); ?>
                    </a>
                    <a class="btn btn-xs btn-default" ui-sref="MapeditorsView({id: map.Map.id, fullscreen: 'false'})"
                       ng-show="fullscreen">
                        <i class="fa fa-compress "></i>
                        <?php echo __('Leave fullscreen'); ?>
                    </a>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div id="map-editor">
                        <div class="widget-body"
                             style="overflow: auto;min-height:600px;"
                             mapeditor-view=""
                             map-id="map.Map.id"
                             ng-if="map"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
