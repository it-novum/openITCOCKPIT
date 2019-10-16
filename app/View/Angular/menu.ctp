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
    <!--<li>
        <div class="clearfix padding-10">
            <input type="text"
                   placeholder="<?php echo __('Type to search'); ?>"
                   class="form-control pull-left"
                   id="filterMainMenu"
                   title="<?php echo __('If you type the menu will be instantly searched'); ?>&#10;<?php echo __('If you press return, the system will run a host search'); ?>"
                   ng-model="menuFilter"
                   ng-keydown="navigate($event)"
            />
            <a href="/search/index" class="form-control pull-right no-padding" id="searchMainMenu">
                <i class="fa fa-search-plus"></i>
            </a>
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


    <li ng-repeat="parentNode in menu">
        <a ng-if="parentNode.isAngular != 1" ng-href="{{ parentHref(parentNode) == '#' ? '' : parentHref(parentNode) }}"
           ng-class="{'cursor-pointer': parentHref(parentNode) == '#'}" data-filter-tags="foo">

            <i class="fal fa-fw fa-{{ parentNode.icon }}"></i>
            <span class="menu-item-parent nav-link-text">{{ parentNode.title }}</span>
        </a>
        <a ng-if="parentNode.isAngular == 1" href="/ng/#!{{parentNode.url}}" data-filter-tags="{{parentNode.tags}}">

            <i class="fal fa-lg fa-fw fa-{{ parentNode.icon }}"></i>
            <span class="menu-item-parent">{{ parentNode.title }}</span>
        </a>
        <ul ng-if="parentNode.children.length > 0" style="{{ isActiveParentStyle(parentNode) }}">
            <li ng-repeat="childNode in parentNode.children">
                <a ng-if="childNode.isAngular != 1" href="{{ childNode.url }}" data-filter-tags="{{childNode.tags}}">
                    <i class="fal fa-lg fa-fw fa-{{ childNode.icon }}"></i>
                    <span class="menu-item-parent">{{ childNode.title }}</span>
                </a>
                <a ng-if="childNode.isAngular == 1" href="/ng/#!{{ childNode.url }}" data-filter-tags="{{childNode.tags}}">
                    <i class="fal fa-lg fa-fw fa-{{ childNode.icon }}"></i>
                    <span class="menu-item-parent">{{ childNode.title }}</span>
                </a>
            </li>
        </ul>
    </li>
