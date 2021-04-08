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

<li ng-repeat-start="headline in menu" class="nav-title">
    {{headline.alias}}
</li>
<li ng-repeat-end="" ng-repeat="item in headline.items" ng-class="{'menufilterSelectable': !item.items}">
    <a ng-if="!item.items" ui-sref="{{item.state}}" class="waves-effect waves-themed"
       title="{{item.name}}" data-filter-tags="{{item.tags}}" ng-click="scrollTop();">
        <i class="{{item.icon}}"></i>
        <span class="menu-item-parent nav-link-text">
            {{item.name}}
        </span>
    </a>

    <a ng-if="item.items.length > 0" href="javascript:void(0);" class="waves-effect waves-themed"
       title="{{item.alias}}" data-filter-tags="{{item.tags}}">
        <i class="{{item.icon}}"></i>
        <span class="menu-item-parent nav-link-text">
            {{item.alias}}
        </span>
    </a>
    <ul ng-if="item.items.length > 0">
        <li ng-repeat="subItem in item.items" class="menufilterSelectable">
            <a ui-sref="{{subItem.state}}" class="waves-effect waves-themed"
               title="{{subItem.name}}" data-filter-tags="{{subItem.tags}}" ng-click="scrollTop();">
                <i class="{{subItem.icon}}"></i>
                <span class="nav-link-text">
                    {{subItem.name}}
                </span>
            </a>
        </li>
    </ul>
</li>
