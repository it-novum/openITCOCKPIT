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

<link rel="stylesheet" type="text/css" href="/css/lib/jquery-jvectormap-1.2.2.css?1487598921"/>
<link rel="stylesheet" type="text/css"
      href="/css/lib/jquery.imgareaselect-0.9.10/imgareaselect-animated.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/lib/jquery.svg.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap/css/bootstrap.min.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/chosen/chosen.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/chosen/chosen-bootstrap.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/list_filter.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/fineuploader/fineuploader-3.2.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/select2/select2.css?1508151423"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/select2/select2-bootstrap.css?1508151411"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap-datepicker.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap-datetimepicker.min.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/vendor/gauge/css/gauge.css?1503481963"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/font-awesome.min.css?1503481963"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/smartadmin-production.min.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/smartadmin-production-plugins.min.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/smartadmin-skins.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/demo.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/your_style.css?1525674812"/>
<link rel="stylesheet" type="text/css" href="/smartadmin/css/animate.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/lockscreen.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/base.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/app.css?1516373159"/>
<link rel="stylesheet" type="text/css" href="/css/status.css?1520583595"/>
<link rel="stylesheet" type="text/css" href="/css/lists.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/ansi.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/console.css?1487598921"/>
<link rel="stylesheet" type="text/css" href="/css/animate_new.css?1503481963"/>
<h1><?php echo __('Services in Monitoring'); ?></h1>
<table class="table table-striped">
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
            <th class="text-center">
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
    foreach ($serviceStateSummary as $key => $valueArray):
        $additionalFilter = (in_array($key, array_keys($additionalFilters), true))?$additionalFilters[$key]:'';
        ?>
        <tr>
            <?php
            if ($key !== 'total'):
                ?>
                <th><?php echo str_replace('_', ' ', $key); ?></th>
                <?php
                foreach ($valueArray as $state => $count):?>
                    <td class="text-center"><?php
                        //debug($additionalFilter);
                        if ($count > 0) :
                            $filterArray['Host.id'] = $hostId;
                            $filterArray['Servicestatus.current_stat'] = [
                                $state => 1
                            ];
                            ?>
                            <a href="/services/index<?php echo Router::queryString([
                                'filter' =>
                                    $filterArray
                                ,
                                $additionalFilter,
                                'sort' => 'Servicestatus.last_state_change',
                                'direction' => 'desc'
                            ]); ?>">
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
                            echo '---';
                        endif;
                        ?></td>
                <?php
                endforeach;
            else:?>
                <th colspan="5">
                    <?php
                    printf('%s: %d',
                        strtoupper($key),
                        $count
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
