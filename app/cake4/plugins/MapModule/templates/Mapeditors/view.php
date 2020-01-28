<?php
// Copyright (C) <2019>  <it-novum GmbH>
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
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('View'); ?>
        </span>
        </h1>
    </div>
</div>

<div class="jarviswidget bg-color-white" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
        <h2>
            <?php echo __('View map:'); ?>
            {{map.Map.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <a class="btn btn-xs btn-default" ng-click="leaveFullscreen();" ui-sref="MapsIndex">
                <i class="fas fa-long-arrow-alt-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
            <?php if ($this->Acl->hasPermission('edit', 'mapeditors', 'mapmodule')): ?>
                <a class="btn btn-xs btn-default" ng-click="leaveFullscreen();"
                   ui-sref="MapeditorsEdit({id: map.Map.id})">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Edit'); ?>
                </a>
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
    </header>
    <div id="map-editor">
        <div class="widget-body"
             style="overflow: auto;
             min-height:600px;"
             mapeditor-view=""
             map-id="map.Map.id"
             ng-if="map"
        ></div>
    </div>
</div>
