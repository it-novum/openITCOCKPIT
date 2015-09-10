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
?>
<div class="row no-padding">
	<div class="col-xs-12">
		<?php if($widgetHostStateArray180['total'] > 0): ?>
		<div class="col-xs-12 text-center chart180">
			<?php
			$overview_chart =  $this->PieChart->createHalfPieChart($widgetHostStateArray180['state']);
			echo $this->Html->image(
				'/img/charts/'.$overview_chart
			);
			$stateColors = [
				'ok',
				'critical',
				'unknown'
			]; ?>
		</div>
		<div class="col-xs-12 stats180 margin-top-10" style="display:none; padding-bottom:56px;">
			<div class="col-xs-6">
				<div class="col-xs-12 stateHost_1">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[1]' => 1
					]); ?>" style="color:#FFF;">
						<?php echo __('%s down', $widgetHostStateArray180['state'][1]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[1]' => 1,
						'Filter.Hoststatus.problem_has_been_acknowledged[0]' => 1,
					]); ?>">
						<?php echo __('%s not handled', $widgetHostStateArray180['not_handled'][1]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[1]' => 1,
						'Filter.Hoststatus.problem_has_been_acknowledged[1]' => 1,
					]); ?>">
						<?php echo __('%s acknowledged', $widgetHostStateArray180['acknowledged'][1]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[1]' => 1,
						'Filter.Hoststatus.scheduled_downtime_depth[0]' => 1
					]); ?>">
						<?php echo __('%s in downtime', $widgetHostStateArray180['in_downtime'][1]);?>
					</a>
				</div>
			</div>
			
			<div class="col-xs-6">
				<div class="col-xs-12 stateHost_2">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[2]' => 1
					]); ?>" style="color:#FFF;">
						<?php echo __('%s unreachable', $widgetHostStateArray180['state'][2]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[2]' => 1,
						'Filter.Hoststatus.problem_has_been_acknowledged[0]' => 1,
						'Filter.Hoststatus.scheduled_downtime_depth[0]' => 1
					]); ?>">
						<?php echo __('%s not handled', $widgetHostStateArray180['not_handled'][2]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[2]' => 1,
						'Filter.Hoststatus.problem_has_been_acknowledged[1]' => 1,
					]); ?>">
						<?php echo __('%s acknowledged', $widgetHostStateArray180['acknowledged'][2]);?>
					</a>
				</div>
				<div class="col-xs-12">
					<a href="<?php echo Router::url([
						'controller' => 'hosts',
						'action' => 'index',
						'plugin' => '',
						'Filter.Hoststatus.current_state[2]' => 1,
						'Filter.Hoststatus.scheduled_downtime_depth[0]' => 1
					]); ?>">
						<?php echo __('%s in downtime', $widgetHostStateArray180['in_downtime'][2]);?>
					</a>
				</div>
			</div>
		</div>
			<div class="text-center font-xs">
				<div class="col-xs-12">
					<div class="toggleDetailsForPiechart"><i class="fa fa-angle-down"></i></div>
				</div>
				<?php foreach($widgetHostStateArray180['state'] as $state => $stateCount):?>
					<div class="col-md-4 no-padding">
						<a href="<?php echo Router::url([
							'controller' => 'hosts',
							'action' => 'index',
							'plugin' => '',
							'Filter.Hoststatus.current_state['.$state.']' => 1
						]); ?>">
							<i class="fa fa-square <?php echo $stateColors[$state]?>"></i>
							<?php echo $stateCount .' ('.round($stateCount/$widgetHostStateArray180['total'] * 100, 2).' %)'; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else:?>
			<div class="text-muted padding-top-80">
				<h5><?php echo __('No hosts are monitored on your system. Please create first a host'); ?></h5>
			</div>
		<?php endif; ?>
	</div>
</div>
