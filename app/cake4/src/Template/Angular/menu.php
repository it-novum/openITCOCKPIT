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
<ul>
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

    <li ng-repeat="headline in menu" >
        <!-- Category Headline -->
        <a href="javascript:void(0);">
            <span class="menu-item-parent">{{headline.alias}}</span>
            <b class="collapse-sign">
                <em class="fa fa-minus-square-o" ng-if="headline.name == 'overview'"></em>
                <em class="fa fa-plus-square-o" ng-if="headline.name != 'overview'"></em>
            </b>
        </a>

        <ul ng-style="{'display': headline.name == 'overview' ? 'block': 'none'}">
            <!-- Category items -->
            <li ng-repeat="item in headline.items">

                <!-- Just a link -->
                <a ng-if="!item.items"
                   ui-sref="{{item.state}}">
                    <i class="{{item.icon}}"></i>
                    <span class="menu-item-parent">
                        {{item.name}}
                    </span>
                </a>

                <!-- 3rd layer menu -->
                <a href="javascript:void(0);" ng-if="item.items.length > 0">
                    <i class="{{item.icon}}"></i>
                    {{item.alias}}
                    <b class="collapse-sign">
                        <em class="fa fa-plus-square-o"></em>
                    </b>
                </a>
                <ul ng-if="item.items.length > 0">
                    <li ng-repeat="subItem in item.items">
                        <a ui-sref="{{subItem.state}}">
                            <i class="{{subItem.icon}}"></i>
                            <span class="menu-item-parent">
                                {{subItem.name}}
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

</ul>

