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
            class="col-lg-12"
            flip="['custom:FLIP_EVENT_OUT']"
            flip-back="['custom:FLIP_EVENT_IN']"
            duration="800"
            timing-function="ease-in-out">
        <flippy-front class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="showConfig()">
                <i class="fa fa-cog fa-sm"></i>
            </a>
            <span ng-show="automap_id === null" class="text-info padding-left-20">
                <?php echo __('No element selected'); ?>
            </span>
            <automap
                automap="automap"
                services-by-host="servicesByHost"
                scroll="scroll"
                paging="paging"
                changepage="changepage"
                change-mode="changeMode">
            </automap>
            <!--end-->
        </flippy-front>
        <flippy-back class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="hideConfig()">
                <i class="fa fa-eye fa-sm"></i>
            </a>
            <div class="padding-top-10">
                <div class="form-group">
                    <div class="row">
                        <label class="col-xs-12 col-lg-12 control-label">
                            <?php echo __('Automap'); ?>
                        </label>
                        <div class="col-xs-12 col-lg-12">
                            <select data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="automaps"
                                    ng-options="available_automap.key as available_automap.value for available_automap in automaps"
                                    ng-model="automap_id">
                            </select>
                        </div>
                    </div>
                    <br/>

                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary pull-right" ng-click="saveSettings()">
                                <?php echo __('Save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </flippy-back>
    </flippy>
</div>
