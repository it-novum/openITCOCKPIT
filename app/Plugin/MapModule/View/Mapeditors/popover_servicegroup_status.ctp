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
$servicegroupCumulativeState = -1;
if(!empty($servicegroups['Servicestatus'])){
    $servicegroupCumulativeState = Hash::apply($servicegroups['Servicestatus'], '{s}.Servicestatus.current_state', 'max');
}
$servicegroupStatus = $this->Status->ServiceStatusColorSimple($servicegroupCumulativeState);
?>
<table class="table table-bordered popoverTable" style="padding:1px;">
    <tr>
        <th colspan="2" class="h6"><?php echo __('Servicegroup'); ?></th>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Servicegroup Name'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicegroups[0]['Container']['name']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('description'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicegroups[0]['Servicegroup']['description']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Summary State'); ?></td>
        <td class="col-md-9 col-xs-9 <?php echo $servicegroupStatus['class']; ?> "><?php echo $servicegroupStatus['human_state']; ?></td>
    </tr>
</table>