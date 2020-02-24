<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

<div class="input-group input-group-sm header-search">
    <div class="input-group-btn padding-top-10" ng-init="type='<?= __('host') ?>';name='<?= __('Hosts') ?>'">
        <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown" aria-expanded="false">
            <span ng-hide="isSearching">{{name}}</span>
            <i class="fa fa-spinner fa-spin" ng-show="isSearching"></i>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li ng-class="{active: type === 'host'}">
                <a href="javascript:void(0)"
                   ng-click="setSearchType('host', '<?= __('Hosts') ?>')">
                    <i class="fa fa-check" ng-show="type === 'host'"></i>
                    <?= __('Hosts') ?>
                </a>
            </li>
            <li ng-class="{active: type === 'service'}">
                <a href="javascript:void(0)"
                   ng-click="setSearchType('service', '<?= __('Services') ?>')">
                    <i class="fa fa-check" ng-show="type === 'service'"></i>
                    <?= __('Services') ?>
                </a>
            </li>
            <li ng-class="{active: type === 'uuid'}">
                <a href="javascript:void(0)"
                   ng-click="setSearchType('uuid', '<?= __('UUID') ?>')">
                    <i class="fa fa-check" ng-show="type === 'uuid'"></i>
                    <?= __('UUID') ?>
                </a>
            </li>
        </ul>
    </div>

    <input id="search-fld"
           type="text"
           placeholder="<?= __('Type to search') ?>"
           ng-model="searchStr"
           ng-keydown="isReturnKey($event)"
           ng-disabled="isSearching">

</div>
