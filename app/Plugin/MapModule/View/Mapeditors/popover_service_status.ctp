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

$_servicestatus = $this->Mapstatus->servicestatus($uuid);
$servicestatusField = $this->Mapstatus->servicestatusField($uuid);

$servicestate = $servicestatus[$uuid]['Servicestatus'];
if($servicestate['currentState'] === null){
    $servicestate['currentState'] = -1;
}
$hostName = $serviceinfo['Host']['name'];
$serviceName = $servicestatus[$uuid][0]['ServiceName'];
$serviceDescr = $servicestatus[$uuid][0]['ServiceDescription'];
$servicestatusColor = $this->Status->ServiceStatusColorSimple($servicestate['currentState'])

?>
<table class="table table-bordered popoverTable" style="padding:1px;">
    <tr>
        <th colspan="2" class="h6"><?php echo __('Service'); ?></th>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Host Name'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($hostName); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Service Name'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($serviceName); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('description'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($serviceDescr); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('State'); ?></td>
        <td class="col-md-9 col-xs-9 <?php echo $servicestatusColor['class']; ?> "><?php echo $servicestatusColor['human_state']; ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Output'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['output']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Perfdata'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['perfdata']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Current attempt'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['current_check_attempt'].'/'.$servicestate['max_check_attempts']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Last Check'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['lastCheck']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Next Check'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['nextCheck']); ?></td>
    </tr>
    <tr>
        <td class="col-md-3 col-xs-3"><?php echo __('Last State Change'); ?></td>
        <td class="col-md-9 col-xs-9"><?php echo h($servicestate['last_state_change']); ?></td>
    </tr>
</table>
