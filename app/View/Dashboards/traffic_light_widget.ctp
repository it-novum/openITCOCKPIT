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
<div>
    <flippy vertical
        class="fancy"
        flip="['custom:FLIP_EVENT_OUT']"
        flip-back="['custom:FLIP_EVENT_IN']"
        duration="800"
        timing-function="ease-in-out">

    <flippy-front>
        <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="showConfig()">
            <i class="fa fa-cog fa-sm"></i>
        </a>
        <div class="padding-10" >

        </div>
    </flippy-front>
    <flippy-back>
        <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="hideConfig()">
            <i class="fa fa-eye fa-sm"></i>
        </a>
        <div class="padding-top-10">
            <div class="form-group">
                <label class="col col-md-2 control-label">
                    <?php echo __('Services'); ?>
                </label>
                <div class="col col-md-6">
                    <select data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="services"
                            ng-options="+(service.value.Service.id) as service.value.Host.name + '/' +((service.value.Service.name)?service.value.Service.name:service.value.Servicetemplate.name) group by service.value.Host.name for service in services"
                            ng-model="post.TrafficLightWidget.Service">
                    </select>

                    <div ng-repeat="error in errors.Service">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>
        </div>
    </flippy-back>
</flippy>
</div>