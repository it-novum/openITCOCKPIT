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


<div class="btn-group">
    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        <i class="fa fa-clock-o"></i>
        {{humanTimerange}} <span class="text-primary" ng-show="humanAutoRefresh">{{humanAutoRefresh}}</span>
        <span class="caret"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right" style="width: 600px;">
        <div class="row no-margin">
            <div class="col-xs-12 col-md-3">
                <fieldset>
                    <h4><?php echo __('Auto refresh'); ?></h4>
                    <hr>
                    <div ng-repeat="(urlKey, name) in timeranges.update_interval"
                         class="no-padding"
                         style="line-height: 25px;"
                         ng-class="{'text-primary': urlKey === selectedAutoRefresh}"
                         ng-click="changeAutoRefresh(urlKey, name)">
                        {{name}}
                    </div>
                </fieldset>
            </div>
            <div class="col-xs-12 col-md-3">
                <fieldset>
                    <h4><?php echo __('Long term'); ?></h4>
                    <hr>
                    <div ng-repeat="(urlKey, name) in timeranges.quick"
                         class="no-padding"
                         style="line-height: 25px;"
                         ng-class="{'text-primary': urlKey === selectedTimerange}"
                         ng-click="changeTimerange(urlKey, name)">
                        {{name}}
                    </div>
                </fieldset>
            </div>
            <div class="col-xs-12 col-md-3">
                <fieldset>
                    <h4><?php echo __('Until now'); ?></h4>
                    <hr>
                    <div ng-repeat="(urlKey, name) in timeranges.today"
                         class="no-padding"
                         style="line-height: 25px;"
                         ng-class="{'text-primary': urlKey === selectedTimerange}"
                         ng-click="changeTimerange(urlKey, name)">
                        {{name}}
                    </div>
                </fieldset>
            </div>
            <div class="col-xs-12 col-md-3">
                <fieldset>
                    <h4><?php echo __('Today'); ?></h4>
                    <hr>
                    <div ng-repeat="(urlKey, name) in timeranges.last"
                         class="no-padding"
                         style="line-height: 25px;"
                         ng-class="{'text-primary': urlKey === selectedTimerange}"
                         ng-click="changeTimerange(urlKey, name)">
                        {{name}}
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

</div>
