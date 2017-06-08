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

$widgetData = $WidgetServiceStatusList[$widget['Widget']['id']];
?>
<table class="table table-bordered statusListTable"
       animation="animated <?php echo h($widgetData['Widget']['WidgetServiceStatusList']['animation']); ?>"
       data-widget-id="<?php echo h($widget['Widget']['id']); ?>">
    <thead>
    <tr>
        <th><?php echo __('State'); ?></th>
        <th class="text-center"><i class="fa fa-user fa-lg "></i></th>
        <th class="text-center"><i class="fa fa-power-off fa-lg "></i></th>
        <th><?php echo __('Host name'); ?></th>
        <th><?php echo __('Service name'); ?></th>
        <th title="<?php echo __('Hardstate'); ?>"><?php echo __('Last state change'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($widgetData['Services'] as $service): ?>
        <?php
        $serviceName = $service['Service']['name'];
        if ($serviceName === null || $serviceName === '') {
            $serviceName = $service['Servicetemplate']['name'];
        }
        ?>
        <tr>
            <td class="text-center">
                <?php
                if ($service['Servicestatus']['is_flapping'] == 1):
                    echo $this->Monitoring->serviceFlappingIconColored($service['Servicestatus']['is_flapping'], '', $service['Servicestatus']['current_state']);
                else:
                    $href = 'javascript:void(0);';
                    if ($this->Acl->hasPermission('browser')):
                        $href = '/services/browser/'.$service['Service']['id'];
                    endif;
                    echo $this->Status->humanServiceStatus($service['Service']['uuid'], $href, [$service['Service']['uuid'] => ['Servicestatus' => ['current_state' => $service['Servicestatus']['last_hard_state']]]])['html_icon'];
                endif;
                ?>
            </td>
            <td class="text-center">
                <?php if ($service['Servicestatus']['problem_has_been_acknowledged'] > 0): ?>
                    <i class="fa fa-user fa-lg "></i>
                <?php endif; ?>
            </td>
            <td class="text-center">
                <?php if ($service['Servicestatus']['scheduled_downtime_depth'] > 0): ?>
                    <i class="fa fa-power-off fa-lg "></i>
                <?php endif; ?>
            </td>
            <td>
                <?php
                if ($this->Acl->hasPermission('browser', 'hosts')):
                    echo '<a href="/hosts/browser/'.$service['Host']['id'].'">'.h($service['Host']['name']).'</a>';
                else:
                    echo h($service['Host']['name']);
                endif;
                ?>
            </td>
            <td>
                <?php
                if ($this->Acl->hasPermission('browser', 'services')):
                    echo '<a href="/services/browser/'.$service['Service']['id'].'">'.h($serviceName).'</a>';
                else:
                    echo h($serviceName);
                endif;
                ?>
            </td>
            <td data-original-title="<?php echo h($this->Time->format($service['Servicestatus']['last_hard_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                data-placement="bottom" rel="tooltip" data-container="body">
                <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($service['Servicestatus']['last_hard_state_change']))); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
