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
<div style="padding: 5px 10px;">
    <div class="row">
        <div class="col-sm-6">
            <div class="dataTables_info" style="line-height: 32px;">
                <?php echo __('Page'); ?>
                {{ paging.page | number }}
                <?php echo __('of'); ?>
                {{ paging.pageCount | number }},
                <?php echo __('Total'); ?>
                {{ paging.count | number }}
                <?php echo __('entries'); ?>
            </div>
        </div>
        <div class="col-sm-6 text-right">
            <div class="dataTables_paginate paging_bootstrap">
                <ul class="pagination">
                    <li ng-class="{ 'disabled': !paging.prevPage }">
                        <a href="javascript:void(0)" ng-click="changePage(1)">&lt;&lt;</a>
                    </li>
                    <li ng-class="{ 'disabled': !paging.prevPage }">
                        <a href="javascript:void(0)" ng-click="prevPage()">&lt;</a>
                    </li>


                    <li ng-repeat="i in pageNumbers() track by $index" ng-class="{'current active': i == paging.page}">
                        <a href="javascript:void(0);" ng-click="changePage(i)"> {{ i }} </a>
                    </li>


                    <li ng-class="{ 'disabled': !paging.nextPage }">
                        <a href="javascript:void(0)" ng-click="nextPage()">&gt;</a>
                    </li>
                    <li ng-class="{ 'disabled': !paging.nextPage }">
                        <a href="javascript:void(0)" ng-click="changePage(paging.pageCount)">&gt;&gt;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>