<?php
// Copyright (C) <2015>  <it-novum GmbH>
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
<img ng-src="/map_module/img/backgrounds/{{map.Map.background}}" ng-if="map.Map.background"/>


<div ng-repeat="item in map.Mapitems"
     style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}};"
     ng-mouseenter="showSummaryStateDelayed(item, false)"
     ng-mouseleave="cancelTimer()">
    <a ng-href="{{ getHref(item) }}">
        <map-item item="item" refresh-interval="refreshInterval"></map-item>
    </a>
</div>

<div ng-repeat="textItem in map.Maptexts"
     style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}};">
    <map-text item="textItem"></map-text>
</div>

<div ng-repeat="lineItem in map.Maplines"
     style="position: absolute; top: {{lineItem.startY}}px; left: {{lineItem.startX}}px;">
    <a ng-show="lineItem.type != 'stateless'"
       ng-href="{{ getHref(lineItem) }}"
       ng-mouseenter="showSummaryStateDelayed(lineItem, false)"
       ng-mouseleave="cancelTimer()">
        <map-line item="lineItem" refresh-interval="refreshInterval"></map-line>
    </a>
    <a ng-show="lineItem.type == 'stateless'">
        <map-line item="lineItem" refresh-interval="0"></map-line>
    </a>
</div>

<div ng-repeat="iconItem in map.Mapicons"
     style="position:absolute; top: {{iconItem.y}}px; left: {{iconItem.x}}px;  z-index: {{iconItem.z_index}};">
    <map-icon item="iconItem"></map-icon>
</div>

<div ng-repeat="gadgetItem in map.Mapgadgets"
     style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}};">
    <a ng-href="{{ getHref(gadgetItem) }}">
        <graph-item item="gadgetItem" ng-if="gadgetItem.gadget === 'RRDGraph'"
                    ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                    ng-mouseleave="cancelTimer()"
                    refresh-interval="refreshInterval"></graph-item>

        <perfdata-text-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Text'"
                            ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                            ng-mouseleave="cancelTimer()"
                            refresh-interval="refreshInterval"></perfdata-text-item>
        <tacho-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Tacho'"
                    ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                    ng-mouseleave="cancelTimer()"
                    refresh-interval="refreshInterval"></tacho-item>

        <cylinder-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Cylinder'"
                       ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                       ng-mouseleave="cancelTimer()"
                       refresh-interval="refreshInterval"></cylinder-item>

        <trafficlight-item item="gadgetItem"
                           ng-if="gadgetItem.gadget === 'TrafficLight'"
                           ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                           ng-mouseleave="cancelTimer()"
                           refresh-interval="refreshInterval"></trafficlight-item>

        <temperature-item item="gadgetItem"
                          ng-if="gadgetItem.gadget === 'Temperature'"
                          ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                          ng-mouseleave="cancelTimer()"
                          refresh-interval="refreshInterval"></temperature-item>

        <service-output-item item="gadgetItem"
                             ng-if="gadgetItem.gadget === 'ServiceOutput'"
                             ng-mouseenter="showSummaryStateDelayed(gadgetItem, false)"
                             ng-mouseleave="cancelTimer()"
                             refresh-interval="refreshInterval"></service-output-item>
    </a>
</div>

<div ng-repeat="item in map.Mapsummaryitems"
     style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}};"
     ng-mouseenter="showSummaryStateDelayed(item, true)"
     ng-mouseleave="cancelTimer()">
    <a ng-href="{{ getHref(item) }}">
        <map-summary-item item="item" details="details"
                          refresh-interval="refreshInterval"></map-summary-item>
    </a>
</div>

<map-summary></map-summary>

<div id="graph_data_tooltip"></div>
