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
<?php if ($isFullscreen === false): ?>
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
<?php endif; ?>

<div class="jarviswidget bg-color-white" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
        <h2>
            <?php echo __('View map:'); ?>
            {{map.Map.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <a class="btn btn-xs btn-default" href="https://dev-dziegler.oitc.itn/map_module/maps">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
            <?php if ($this->Acl->hasPermission('edit', 'mapeditors', 'mapmodule')): ?>
                <a class="btn btn-xs btn-default" ng-href="/map_module/mapeditors/edit/{{map.Map.id}}">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Edit'); ?>
                </a>
            <?php endif; ?>
            <a class="btn btn-xs btn-default" ng-href="/map_module/mapeditors_new/view/{{map.Map.id}}?fullscreen=true"
               ng-show="!fullscreen">
                <i class="fa fa-expand"></i>
                <?php echo __('Fullscreen'); ?>
            </a>

            <a class="btn btn-xs btn-default" ng-href="/map_module/mapeditors_new/view/{{map.Map.id}}?fullscreen=false"
               ng-show="fullscreen">
                <i class="fa fa-compress "></i>
                <?php echo __('Leave fullscreen'); ?>
            </a>
        </div>
    </header>
    <div id="map-editor">
        <div class="widget-body" style="overflow: auto; min-height:600px; ">
            <img ng-src="/map_module/img/backgrounds/{{map.Map.background}}" ng-if="map.Map.background"/>


            <div ng-repeat="item in map.Mapitem"
                 style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}};"
                 ng-mouseenter="showSummaryStateDelayed(item)">
                <a ng-href="{{ getHref(item) }}">
                    <map-item item="item" refresh-interval="refreshInterval"></map-item>
                </a>
            </div>

            <div ng-repeat="textItem in map.Maptext"
                 style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}};">
                <map-text item="textItem"></map-text>
            </div>

            <div ng-repeat="lineItem in map.Mapline">
                <a ng-href="{{ getHref(lineItem) }}">
                    <map-line item="lineItem" refresh-interval="refreshInterval"></map-line>
                </a>
            </div>

            <div ng-repeat="iconItem in map.Mapicon"
                 style="position:absolute; top: {{iconItem.y}}px; left: {{iconItem.x}}px;  z-index: {{iconItem.z_index}};">
                <map-icon item="iconItem"></map-icon>
            </div>

            <div ng-repeat="gadgetItem in map.Mapgadget"
                 style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}};">
                <a ng-href="{{ getHref(gadgetItem) }}">
                    <graph-item item="gadgetItem" ng-if="gadgetItem.gadget === 'RRDGraph'"
                                refresh-interval="refreshInterval"></graph-item>

                    <perfdata-text-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Text'"
                                        refresh-interval="refreshInterval"></perfdata-text-item>
                    <tacho-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Tacho'"
                                refresh-interval="refreshInterval"></tacho-item>

                    <cylinder-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Cylinder'"
                                   refresh-interval="refreshInterval"></cylinder-item>

                    <trafficlight-item item="gadgetItem"
                                       ng-if="gadgetItem.gadget === 'TrafficLight'"
                                       refresh-interval="refreshInterval"></trafficlight-item>
                </a>
            </div>

            <map-summary></map-summary>

            <div id="graph_data_tooltip"></div>

        </div>
    </div>
</div>
