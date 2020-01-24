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

/*
 *         _                    _
 *   __ _ (_) __ ___  __ __   _(_) _____      __
 *  / _` || |/ _` \ \/ / \ \ / / |/ _ \ \ /\ / /
 * | (_| || | (_| |>  <   \ V /| |  __/\ V  V /
 *  \__,_|/ |\__,_/_/\_\   \_/ |_|\___| \_/\_/
 *      |__/
*/

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
        border-top: 1px solid #ffffff !important;
    }

    /* bigBoxes */

    .bigBox {
        position: fixed;
        right: 10px;
        bottom: 10px;
        background-color: #004d60;
        padding-left: 10px;
        padding-top: 10px;
        padding-right: 10px;
        padding-bottom: 5px;
        width: 390px;
        height: 170px;
        color: white;
        z-index: 99999;
        box-sizing: content-box;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        border-left: 5px solid rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .bigBox span {
        font-size: 17px;
        font-weight: 300;
        letter-spacing: -1px;
        padding: 5px 0 !important;
        display: block;
    }

    .bigBox p {
        font-size: 13px;
        margin-top: 10px;
    }

    #divMiniIcons {
        position: fixed;
        width: 415px;
        right: 10px;
        bottom: 200px;
        z-index: 9999;
        float: right;
    }

    .bigBox .bigboxicon {
        display: none;
    }

</style>
<table class="table-no-bordered">
    <tr>
        <td></td>
        <?php
        $additionalFilters = [
            'acknowledged' => ['has_been_acknowledged' => 1],
            'in_downtime'  => ['in_downtime' => 1],
            'not_handled'  => ['has_not_been_acknowledged' => 1],
            'passive'      => ['passive' => 1]
        ];
        ?>

        <th class="text-center font-xs">
            <div class="label label-table label-success"><? __('ok'); ?></div>
        </th>
        <th class="text-center font-xs">
            <div class="label label-table label-warning"><? __('warning'); ?></div>
        </th>
        <th class="text-center font-xs">
            <div class="label label-table label-danger"><? __('critical'); ?></div>
        </th>
        <th class="text-center font-xs">
            <div class="label label-table label-default"><? __('unknown'); ?></div>
        </th>
    </tr>

        <tr class="font-xs" ng-repeat="(key, obj) in serviceStateSummary">
            <?php
            $additionalFilter = null;
            if(in_array($key, array_keys($additionalFilters), true)){
                $additionalFilter = $additionalFilters[$key];
            }

            ?>
                <th ng-if="key == 'state'" ng-repeat-start="(state, stateCount) in obj.state">{{state}}</th>
                <td ng-if="key == 'state'" ng-repeat-end="" class="text-center">

                    <div ng-if="stateCount > 0">
                        <?php
                        //$filterArray['Hosts.id'] = $hostId;

                        if ($this->Acl->hasPermission('index', 'services')): ?>
                            <a ui-sref="ServicesIndex({servicestate: [{{state}}], sort: 'Servicestatus.last_state_change', direction: 'desc'})"
                               target="_blank">
                                {{stateCount}}&nbsp;
                                ({{stateCount/serviceStateSummary.total*100}})
                            </a>
                        <?php
                        else: ?>
                            {{stateCount}}&nbsp;
                            ({{stateCount/serviceStateSummary.total*100}})
                        <?php endif; ?>
                    </div>
                    <div ng-if="stateCount <= 0">
                        ---
                    </div>

                </td>

                <th ng-if="key == 'total'" colspan="5" class="text-right th-border-top">
                    <? __('TOTAL:'); ?>&nbsp;{{obj}}
                </th>

        </tr>

</table>
