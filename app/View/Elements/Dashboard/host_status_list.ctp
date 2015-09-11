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

$widgetData = $widgetHoststatusList[$widget['Widget']['id']];
//debug($widgetData);
//debug($widget);
?>
<div class="padding-10">
	<div style="border:1px solid #c3c3c3;" class="padding-10">
		<div class="row">
			<div class="col-xs-2">
				<a href="javascript:void(0);" data-widget-id="<?php echo h($widget['Widget']['id']); ?>" class="btn btn-default btn-xs stopRotation btn-primary"><i class="fa fa-pause"></i></a>
				<a href="javascript:void(0);" data-widget-id="<?php echo h($widget['Widget']['id']); ?>" class="btn btn-default btn-xs startRotation" style="display:none;"><i class="fa fa-play"></i></a>
				<a href="javascript:void(0);" class="btn btn-default btn-xs"><i class="fa fa-arrow-left"></i></a>
				<a href="javascript:void(0);" class="btn btn-default btn-xs"><i class="fa fa-arrow-up"></i></a>
			</div>
			<div class="col-xs-4">
				<div class="slider-slim width-120">
					<input class="slider slider-primary slider-slim" data-slider-min="3" data-slider-max="30" data-slider-value="5" />
				</div>
			</div>
			<div class="col-xs-6 text-right">
				<?php
				$stateSettings = [
					'show_up' => [
						'icon' => 'fa-square fa-lg ok'
					],
					'show_down' => [
						'icon' => 'fa-square fa-lg critical'
					],
					'show_unreachable' => [
						'icon' => 'fa-square fa-lg unknown'
					],
					'show_acknowledged' => [
						'icon' => 'fa-user fa-lg'
					],
					'show_downtime' => [
						'icon' => 'fa-power-off fa-lg'
					]
				];
				foreach($stateSettings as $dbField => $stateSetting): ?>
					<?php
					$checked = '';
					if($widgetData['Widget']['WidgetHostStatusList'][$dbField] == true):
						$checked = 'checked="checked"';
					endif;
					?>
					<i class="fa <?php echo $stateSetting['icon']; ?>"></i> <input type="checkbox" <?php echo $checked; ?>/>
				<?php
				endforeach; ?>
				<a href="javascript:void(0);" class="btn btn-primary btn-xs margin-left-10"><?php echo __('Save');?></a>
			</div>
		</div>
	</div>
</div>
<div class="no-padding font-xs">
	<table class="table table-bordered statusListHosts" animation="animated <?php echo h($widgetData['Widget']['WidgetHostStatusList']['animation']); ?>" data-widget-id="<?php echo h($widget['Widget']['id']); ?>">
		<thead>
			<tr>
				<th><?php echo __('State'); ?></th>
				<th class="text-center"><i class="fa fa-user fa-lg "></i></th>
				<th class="text-center"><i class="fa fa-power-off fa-lg "></i></th>
				<th><?php echo __('Host name'); ?></th>
				<th><?php echo __('State since'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($widgetData['Hosts'] as $host):?>
				<tr>
					<td class="text-center">
						<?php
						if($host['Hoststatus']['is_flapping'] == 1):
							echo $this->Monitoring->hostFlappingIconColored($host['Hoststatus']['is_flapping'], '', $host['Hoststatus']['current_state']);
						else:
							$href = 'javascript:void(0);';
							if($this->Acl->hasPermission('browser')):
								$href = '/hosts/browser/'.$host['Host']['id'];
							endif;
							echo $this->Status->humanHostStatus($host['Host']['uuid'], $href, [$host['Host']['uuid'] => ['Hoststatus' => ['current_state' => $host['Hoststatus']['current_state']]]])['html_icon'];
						endif;
						?>
					</td>
					<td class="text-center">
						<?php if($host['Hoststatus']['problem_has_been_acknowledged'] > 0): ?>
							<i class="fa fa-user fa-lg "></i>
						<?php endif; ?>
					</td>
					<td class="text-center">
						<?php if($host['Hoststatus']['scheduled_downtime_depth'] > 0): ?>
							<i class="fa fa-power-off fa-lg "></i>
						<?php endif; ?>
					</td>
					<td><?php echo h($host['Host']['name']);?></td>
					<td data-original-title="<?php echo h($this->Time->format($host['Hoststatus']['last_hard_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>" data-placement="bottom" rel="tooltip" data-container="body">
						<?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($host['Hoststatus']['last_hard_state_change'])));?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>