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
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('View'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
        <h2>
            <?php echo __('View map:'); ?>
            {{map.Map.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
            <?php
            if ($this->Acl->hasPermission('edit', 'mapeditors', 'map_module')):
                echo $this->Html->link(__('Edit'), '/map_module/mapeditors/edit/{{map.Map.id}}', ['class' => 'btn btn-xs btn-default', 'icon' => 'fa fa-edit']);
            endif;
            ?>
        </div>
    </header>
    <div id="map-editor">
        <div class="widget-body" style="overflow: auto;">
            <img ng-src="/map_module/img/backgrounds/{{map.Map.background}}"/>


            <div ng-repeat="item in map.Mapitem"
                 style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}};"
                 ng-mouseenter="showSummaryStateDelayed(item)">
                <map-item item="item"></map-item>
            </div>

            <div ng-repeat="textItem in map.Maptext"
                 style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}};">
                <map-text item="textItem"></map-text>
            </div>

            <div ng-repeat="lineItem in map.Mapline">
                <map-line item="lineItem"></map-line>
            </div>

            <div ng-repeat="iconItem in map.Mapicon"
                 style="position:absolute; top: {{iconItem.y}}px; left: {{iconItem.x}}px;  z-index: {{iconItem.z_index}};">
                <map-icon item="iconItem"></map-icon>
            </div>

            <div ng-repeat="gadgetItem in map.Mapgadget"
                 style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}};">
                <graph-item item="gadgetItem" ng-if="gadgetItem.gadget === 'RRDGraph'"></graph-item>
                <perfdata-text-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Text'"></perfdata-text-item>
                <tacho-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Tacho'"></tacho-item>
                <cylinder-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Cylinder'"></cylinder-item>
                <trafficlight-item item="gadgetItem" ng-if="gadgetItem.gadget === 'TrafficLight'"></trafficlight-item>
            </div>

            <map-summary></map-summary>

            <div id="graph_data_tooltip"></div>

        </div>
    </div>
</div>
