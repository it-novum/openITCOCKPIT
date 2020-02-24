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
<!--<ul>
    <li>
        <div class="clearfix padding-10">
            <input type="text"
                   placeholder="<?php echo __('Type to search'); ?>"
                   class="form-control pull-left"
                   id="filterMainMenu"
                   title="<?php echo __('If you type the menu will be instantly searched'); ?>&#10;<?php echo __('If you press return, the system will run a host search'); ?>"
                   ng-model="menuFilter"
                   ng-keydown="navigate($event)"
            />
        </div>
    </li>

    <li ng-repeat="menuMatche in menuMatches" ng-show="menuMatches.length > 0"
        ng-class="{'menu-search-border':$last, 'search_list_item_active':$index == menuFilterPosition}">
        <a ng-if="menuMatche.isAngular != 1" href="{{ menuMatche.url }}">
            <i class="fa fa-lg fa-fw fa-{{menuMatche.icon}}"></i>
            <span class="menu-item-parent" ng-bind-html="menuMatche.title | highlight:menuFilter"></span>
        </a>
        <a ng-if="menuMatche.isAngular == 1" href="/ng/#!{{ menuMatche.url }}">
            <i class="fa fa-lg fa-fw fa-{{menuMatche.icon}}"></i>
            <span class="menu-item-parent" ng-bind-html="menuMatche.title | highlight:menuFilter"></span>
        </a>
    </li>
-->

<span ng-repeat="headline in menu">
    <li class="nav-title">
        {{headline.alias}}
    </li>
    <li ng-repeat="item in headline.items">
        <a ng-if="!item.items" ui-sref="{{item.state}}" class=" waves-effect waves-themed"
           title="{{item.name}}" data-filter-tags="{{item.tags}}">
            <i class="{{item.icon}}"></i>
            <span class="menu-item-parent nav-link-text" >
                {{item.name}}
            </span>
        </a>

        <a ng-if="item.items.length > 0" href="javascript:void(0);" class=" waves-effect waves-themed"
           title="{{item.alias}}" data-filter-tags="{{item.tags}}">
            <i class="{{item.icon}}"></i>
            <span class="menu-item-parent nav-link-text">
                {{item.alias}}
            </span>
        </a>
        <ul ng-if="item.items.length > 0">
            <li ng-repeat="subItem in item.items">
                <a ui-sref="{{subItem.state}}" class="waves-effect waves-themed"
                   title="{{subItem.name}}" data-filter-tags="{{subItem.tags}}">
                    <i class="{{subItem.icon}}"></i>
                    <span class="nav-link-text">
                        {{subItem.name}}
                    </span>
                </a>
            </li>
        </ul>
    </li>
</span>
