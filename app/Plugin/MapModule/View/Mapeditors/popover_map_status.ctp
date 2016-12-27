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
$mapstatus = $this->Mapstatus->mapstatus($mapinfo['Map']['id']);
$statusColor = null;
if (!empty($mapstatus['cumulated_type_key'])) {
    switch ($mapstatus['cumulated_type_key']) {
        case 'Host':
            $statusColor = $this->Status->HostStatusColorSimple($mapstatus['state']);
            break;
        case 'Service':
            $statusColor = $this->Status->ServiceStatusColorSimple($mapstatus['state']);
            break;
    }
} else {
    $statusColor = $this->Status->HostStatusColorSimple(-1);
}
?>
<table class="table table-bordered popoverTable" style="padding:1px;">
    <tr>
        <th colspan="2" class="h6"><?php echo __('Map'); ?></th>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Map Name'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo $mapinfo['Map']['name']; ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Map Title'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo $mapinfo['Map']['title']; ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Summary State'); ?></td>
        <td class="col-md-9 col-xs-9 <?php echo $statusColor['class']; ?> "><?php echo $statusColor['human_state']; ?></td>
    </tr>
</table>