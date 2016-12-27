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
$hostgroupStatus = $this->Mapstatus->hostgroupstatus($hostgroups[0]['Hostgroup']['uuid']);
$hostAmount = count($hostgroups[0]['Host']);

$serviceStateArr = [];
foreach ($hostgroups[0]['Host'] as $counter => $host) {
    //check if the servicestatus array is not empty
    if (!empty($host['Servicestatus'])) {
        foreach ($host['Servicestatus'] as $key => $value) {
            //fill the current state into the array
            //$serviceStateArr[$counter][$key] = $value['Servicestatus']['current_state'];
            $serviceStateArr[$host['uuid']][$key] = $value['Servicestatus']['current_state'];
        }
    } else {
        //set a null value into the array
        $serviceStateArr[$host['uuid']] = null;
    }

}
$serviceAmountperHost = [];
foreach ($serviceStateArr as $k => $v) {
    //check if there is an empty array
    if (!empty($v)) {
        $serviceAmountperHost[$k] = count($v);
    } else {
        //if the $v is empty it contains no services
        $serviceAmountperHost[$k] = 0;
    }
}
$serviceAmount = array_sum($serviceAmountperHost);


?>
    <table class="table table-bordered popoverTable" style="padding:1px;">
        <tr>
            <th colspan="2" class="h6"><?php echo __('Hostgroup'); ?></th>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Hostgroup Name'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo $hostgroups[0]['Container']['name']; ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Description'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo $hostgroups[0]['Hostgroup']['description']; ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Summary State'); ?></td>
            <?php if (isset($hostgroups[0]['Host']) && $hostAmount > 0): ?>
                <td class="col-md-9 col-xs-9 <?php echo $this->Status->ServiceStatusColorSimple($hostgroupStatus['state'])['class']; ?>"> <?php echo $hostgroupStatus['human_state']; ?></td>
            <?php else: ?>
                <td class="col-md-9 col-xs-9"> <?php echo __('No Summary State possible') ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Summary Output'); ?></td>
            <td class="col-md-9 col-xs-9">There are <?php echo $hostAmount; ?> Hosts and <?php echo $serviceAmount ?>
                Services
            </td>
        </tr>
    </table>

<?php if (isset($hostgroups[0]['Host']) && $hostAmount > 0) : ?>
    <table class="table table-bordered popoverListTable">
        <tr>
            <th class="col-md-4 col-xs-3 h6"><?php echo __('Host Name'); ?></th>
            <th class="col-md-1 col-xs-1 h6"><?php echo __('State'); ?></th>
            <th class="col-md-7 col-xs-8 h6"><?php echo __('Output'); ?></th>
        </tr>
        <?php
        foreach ($hostgroups[0]['Host'] as $key => $host) : ?>
            <?php $key = $host['uuid']; ?>
            <tr>
                <!-- Hostname -->
                <td title="<?php echo $host['name']; ?>">
                    <?php echo $host['name']; ?>
                </td>
                <!-- State -->
                <?php if (isset($serviceStateArr[$key])): ?>
                    <td class="<?php echo $this->Status->ServiceStatusColorSimple(max($serviceStateArr[$key]))['class']; ?>"><?php echo $this->Status->ServiceStatusColorSimple(max($serviceStateArr[$key]))['human_state']; ?></td>
                <?php else: ?>
                    <!-- there are no services for this host so display the hostdata -->
                    <?php $currentHostState = $host['Hoststatus'][0]['Hoststatus']['current_state']; ?>
                    <td class="<?php echo $this->Status->HostStatusColorSimple($currentHostState)['class']; ?>"><?php echo $this->Status->HostStatusColorSimple($currentHostState)['human_state']; ?></td>
                <?php endif; ?>
                <!-- Output -->
                <td title="<?php echo $this->Mapstatus->hoststatus($host['uuid'])['human_state']; ?>"><?php echo $this->Mapstatus->hoststatus($host['uuid'])['human_state']; ?>
                    . There are <?php echo $serviceAmountperHost[$key]; ?> Services
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>