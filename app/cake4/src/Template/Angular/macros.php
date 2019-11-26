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
<div class="col-xs-12">
    <div class="col-xs-12 col-lg-3 hidden-mobile text-primary"
         ng-class="{'text-success': macro.objecttype_id === 512 || macro.objecttype_id === 4096}">
        <div style="padding-top: 29px; width: 100%;">
            <!-- spacer for nice layout -->
        </div>
        <span ng-show="macro.name">
            $_{{macroName}}{{macro.name}}$
        </span>
    </div>
    <div class="col-xs-12 col-lg-4 required" ng-class="{'has-error-force': errors.name}">
        <label class="control-label">
            <?php echo __('Name'); ?>
        </label>
        <input class="form-control" style="width:100%;"
               type="text" ng-model="macro.name">
        <div ng-repeat="error in errors.name">
            <div class="text-danger">{{ error }}</div>
        </div>
    </div>
    <div class="col-xs-12 col-lg-4 required" ng-class="{'has-error-force': errors.name}">
        <label class="control-label">
            <?php echo __('Value'); ?>
        </label>
        <input class="form-control" style="width:100%" type="text" ng-model="macro.value">
        <div ng-repeat="error in errors.value">
            <div class="text-danger">{{ error }}</div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-1">
        <label><!-- just a spacer for a nice layout --> &nbsp;</label>
        <br>
        <button type="button" class="btn btn-default btn-sx txt-color-red" ng-click="callback(macro, index)"
                style="margin-top: 7px;">
            <i class="fa fa-trash-o fa-lg"></i>
        </button>
    </div>
</div>
