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
<style>

    .table-no-bordered {
        width: 100%;
    }

    .table-no-bordered > tbody > tr > th {
        border: none;
    }

    .table-no-bordered > tbody > tr > td > a {
        font-size: 10px;
        color: #ffffff;
    }

    th.th-border-top {
        border-top: 1px solid #c2c2c2 !important;
        padding: 3px;
    }

    tr {
        line-height: 18px;
    }

    .map-popover-scrollable {
        position: fixed;
        right: 0px;
        max-height: 95%;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .jarviswidget {
        margin-bottom: 0px !important;
    }

    .tooltipProgressBar {
        height: 5px;
        width: 100%;
        background-color: #646464;
    }

</style>

<div class="map-summary-state-popover col-xs-12 no-padding animated slideInRight map-popover-scrollable"
     ng-if="host.hostId"
     ng-click="hideTooltip($event)"
     ng-mouseover="stopInterval()"
     ng-mouseleave="startInterval()">
    <section>
        <div class="row">
            <article>
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-location-arrow fa-lg txt-color-blueDark"></i>
                            <a ng-show="hasBrowserRight" ui-sref="HostsBrowser({id: host.hostId})">{{host.title}}</a>
                            <div class="display-inline" ng-hide="hasBrowserRight">{{host.title}}</div>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="">
                        <table class="table-no-bordered">
                            <tr>
                                <td></td>

                                <th class="text-center font-xs">
                                    <div class="label label-table label-success"><?php echo __('Ok'); ?></div>
                                </th>
                                <th class="text-center font-xs">
                                    <div class="label label-table label-warning"><?php echo __('Warning'); ?></div>
                                </th>
                                <th class="text-center font-xs">
                                    <div class="label label-table label-danger"><?php echo __('Critical'); ?></div>
                                </th>
                                <th class="text-center font-xs">
                                    <div class="label label-table label-default"><?php echo __('Unknown'); ?></div>
                                </th>
                            </tr>

                            <tr class="font-xs" ng-repeat="(key, state) in serviceStateSummary" ng-if="key !== 'total'">
                                <th>
                                    {{key | underscoreless | capitalizeFirstLetter}}
                                </th>
                                <td class="text-center" ng-repeat="counter in [0,1,2,3]">

                                    <div ng-show="state[counter] > 0">
                                        <?php
                                        if ($this->Acl->hasPermission('index', 'services')): ?>
                                            <a class="cursor-pointer" ng-click="goToState(key, counter, host.hostId)">
                                                {{state[counter]}}&nbsp;
                                                ({{state[counter]/serviceStateSummary.total*100|number : 1}}%)
                                            </a>
                                        <?php
                                        else: ?>
                                            {{state[counter]}}&nbsp;
                                            ({{state[counter]/serviceStateSummary.total*100|number : 1}}%)
                                        <?php endif; ?>
                                    </div>
                                    <div ng-show="!state[counter] || state[counter] <= 0">
                                        ---
                                    </div>

                                </td>
                            </tr>

                            <tr class="font-xs">
                                <th colspan="5" class="text-right th-border-top">
                                    <?php echo __('TOTAL:'); ?>&nbsp;{{serviceStateSummary.total}}
                                </th>
                            </tr>

                        </table>
                    </div>

                </div>
            </article>
        </div>
    </section>
</div>
