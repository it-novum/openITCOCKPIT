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


$hostInfo = $hoststatus[$uuid]['Host'];
$hostStatus = $hoststatus[$uuid]['Hoststatus'];
$stateType = '';
$cumulativeState = -1;

$serviceAmount = 0;
if (isset($hostStatus['Servicestatus'])) {
    $serviceAmount = count($hostStatus['Servicestatus']);
}

if (!isset($hostStatus['current_state'])) {
    $FakeHoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(['current_state' => -1]);
    $hostStatus = $FakeHoststatus->toArray();
    $hostStatus['current_state'] = $FakeHoststatus->currentState();
    $hostStatus['perfdata'] = null;
}

if ($hostStatus['current_state'] == 0) {
    $stateType = 'service';
    $servicestates = [];
    foreach ($hostStatus['Servicestatus'] as $servicestatus) {
        if (isset($servicestatus['Servicestatus']['current_state'])) {
            $servicestates[] = $servicestatus['Servicestatus']['current_state'];
        }
    }
    $cumulativeState = hash::apply($servicestates, '{n}', 'max');
    $summaryState = $this->Status->ServiceStatusColorSimple($cumulativeState);
} else {
    $stateType = 'host';
    $cumulativeState = $hostStatus['current_state'];
    $summaryState = $this->Status->HostStatusColorSimple($cumulativeState);
}

?>
    <table class="table table-bordered popoverTable" style="padding:1px;">
        <tr>
            <th colspan="2" class="h6"><?php echo __('Host'); ?></th>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Hostname'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($hostInfo['name']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('description'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($hostInfo['description']); ?></td>
        </tr>
        <tr>
            <?php
            $state = $this->Status->HostStatusColorSimple($hostStatus['current_state']);
            ?>
            <td class="col-md-3 col-xs-3"><?php echo __('State (State Type)'); ?></td>
            <td class="col-md-9 col-xs-9 <?php echo $state['class']; ?> "><?php echo $state['human_state']; ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Output'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($hostStatus['output']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Perfdata'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($hostStatus['perfdata']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Current attempt'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($hostStatus['current_check_attempt'] . '/' . $hostStatus['max_check_attempts']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Last Check'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($Hoststatus['lastCheck']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Next Check'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($Hoststatus['nextCheck']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Last State Change'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo h($Hoststatus['last_state_change']); ?></td>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Summary State'); ?></td>
            <?php if ($stateType == 'host'): ?>
                <?php $stateColor = $this->Status->HostStatusColorSimple($cumulativeState); ?>
                <td class="col-md-9 col-xs-9 <?php echo $summaryState['class']; ?>"> <?php echo $summaryState['human_state']; ?></td>
            <?php else:
                ?>
                <?php if ($serviceAmount > 0):
                $stateColor = $this->Status->ServiceStatusColorSimple($cumulativeState);
                ?>
                <td class="col-md-9 col-xs-9 <?php echo $summaryState['class']; ?>"> <?php echo $summaryState['human_state']; ?></td>
            <?php else: ?>
                <td class="col-md-9 col-xs-9"> <?php echo __('No Summary State possible') ?></td>
            <?php endif; ?>
            <?php endif; ?>
        </tr>
        <tr>
            <td class="col-md-3 col-xs-3"><?php echo __('Summary Output'); ?></td>
            <td class="col-md-9 col-xs-9"><?php echo $summaryState['human_state']; ?>. There
                are <?php echo $serviceAmount ?> Services
            </td>
        </tr>
    </table>

<?php if ($serviceAmount > 0) : ?>
    <table class="table table-bordered popoverListTable popoverBreakWord">
        <tr>
            <th class="col-md-3 col-xs-3 h6"><?php echo __('Service Name'); ?></th>
            <th class="col-md-2 col-xs-2 h6"><?php echo __('State'); ?></th>
            <th class="col-md-7 col-xs-7 h6"><?php echo __('Output'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($hostStatus['Servicestatus'] as $counter => $service) :
            if (empty($service['Servicestatus'])) {
                $service['Servicestatus'] = [
                    'current_state' => -1,
                    'output'        => ''
                ];
            }
            if ($i == 10): ?>
                <tr>
                    <td colspan="3"><?php echo __('Showing ' . $i . ' of ' . $serviceAmount . ' Services. Click on the icon to see all') ?></td>
                </tr>
                <?php
                break;
            endif;
            ?>
            <tr>
                <td title="<?php echo h($service[0]['ServiceName']); ?>">
                    <?php
                    echo h($service[0]['ServiceName']);
                    ?>
                </td>
                <?php $servicestate = $this->Status->ServiceStatusColorSimple($service['Servicestatus']['current_state']) ?>
                <td class="<?php echo $servicestate['class']; ?>"><?php echo $servicestate['human_state']; ?></td>
                <td title="<?php echo $service['Servicestatus']['output']; ?>"
                    class="cropText"><?php echo h($service['Servicestatus']['output']); ?></td>
            </tr>
            <?php
            $i++;
        endforeach; ?>
    </table>
<?php endif; ?>