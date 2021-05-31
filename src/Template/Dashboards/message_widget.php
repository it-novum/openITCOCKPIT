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

<div class="row">
    <div class="col-12 mb-2">

        <b><?= "Today's Message:" ?></b>
    </div>
    <div class="col-12">
        <ul class="list-group list-group-flush" ng-repeat="message in messages">
            <li class="list-group-item p-0"> {{ message.message }}</li>
        </ul>
        <div class="margin-top-10" ng-show="messages.length == 0">
            <div class="text-left text-danger italic">
                <?php echo __('Today you have no Messages'); ?>
            </div>
        </div>

    </div>
</div>

