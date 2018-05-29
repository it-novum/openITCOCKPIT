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
        $stateLabelColors = [
            'label-success',
            'label-warning',
            'label-danger',
            'label-default',
        ];
        $additionalFilters = [
            'acknowledged' => ['has_been_acknowledged' => 1],
            'in_downtime' => ['in_downtime' => 1],
            'not_handled' => ['has_not_been_acknowledged' => 1],
            'passive' => ['passive' => 1]
        ];
        foreach ([0, 1, 2, 3] as $serviceState):?>
            <th class="text-center font-xs">
                <?php
                printf(
                    '<div class="label label-table %s">%s</div>',
                    $stateLabelColors[$serviceState],
                    strtolower($this->Status->humanSimpleServiceStatus($serviceState))
                );
                ?>
            </th>
        <?php
        endforeach;
        ?>
    </tr>
    <?php
    foreach ($serviceStateSummary

             as $key => $valueArray):
        $additionalFilter = (in_array($key, array_keys($additionalFilters), true)) ? $additionalFilters[$key] : '';
        ?>
        <tr class="font-xs">
            <?php
            if ($key !== 'total'):
                ?>
                <th><?php echo str_replace('_', ' ', $key); ?></th>
                <?php
                foreach ($valueArray

                         as $state => $count): ?>
                    <td class="text-center"><?php
                        //debug($additionalFilter);
                        if ($count > 0) :
                            $filterArray['Host.id'] = $hostId;
                            $filterArray['Servicestatus.current_state'] = [
                                $state => 1
                            ];
                            if ($this->Acl->hasPermission('index', 'services')): ?>
                                <a href="/services/index<?php echo Router::queryString([
                                    'filter' =>
                                        $filterArray
                                    ,
                                    $additionalFilter,
                                    'sort' => 'Servicestatus.last_state_change',
                                    'direction' => 'desc'
                                ]); ?>" target="_blank">
                                    <?php
                                    printf(
                                        '%s (%.0f%%)',
                                        $count,
                                        ($count / $serviceStateSummary['total'] * 100)
                                    );
                                    ?>
                                </a>
                            <?php
                            else:
                                printf(
                                    '%s (%.0f%%)',
                                    $count,
                                    ($count / $serviceStateSummary['total'] * 100)
                                );
                            endif;
                        else:
                            echo '---';
                        endif;
                        ?></td>
                <?php
                endforeach;
            else:?>
                <th colspan="5" class="text-right th-border-top">
                    <?php
                    printf('%s: %s',
                        strtoupper($key),
                        $serviceStateSummary['total']
                    ); ?>
                </th>
            <?php
            endif;
            ?>
        </tr>
    <?php
    endforeach;
    ?>
</table>
