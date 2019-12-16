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
<div class="text-center" ng-if="allowView && (item.label_possition == 1 || item.label_possition == 2)">
    <div ng-show="item.show_label && item.label_possition == 1">{{lable}}</div>
    <img
        ng-src="/angular/getHostAndServiceStateSummaryIcon/{{item.size_x}}/{{bitMaskHostState}}/{{bitMaskServiceState}}/.png"
        onerror="this.src='/map_module/img/items/missing.png';"/>
    <div ng-show="item.show_label && item.label_possition == 2">{{lable}}</div>
</div>


<div class="text-center" ng-if="allowView && (item.label_possition == 3 || item.label_possition == 4)">
    <span ng-show="item.show_label && item.label_possition == 4">{{lable}}</span>
    <img
        ng-src="/angular/getHostAndServiceStateSummaryIcon/{{item.size_x}}/{{bitMaskHostState}}/{{bitMaskServiceState}}/.png"
        onerror="this.src='/map_module/img/items/missing.png';"/>
    <span ng-show="item.show_label && item.label_possition == 3">{{lable}}</span>
</div>
