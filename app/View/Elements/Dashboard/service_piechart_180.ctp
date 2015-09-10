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
		<?php if($widgetServiceStateArray180['total'] > 0): ?>
		<div style="height: 140px;">
			<div class="col-xs-12 text-center chart180">
				<?php
				$overview_chart =  $this->PieChart->createHalfPieChart($widgetServiceStateArray180['state']);
				echo $this->Html->image(
					'/img/charts/'.$overview_chart
				);
				$stateColors = [
					'ok',
					'warning',
					'critical',
					'unknown'
				]; ?>
			</div>
			<div class="col-xs-12 stats180 margin-top-10" style="display:none; position: absolute; top:0px;">
				<div class="col-xs-4">
					<div class="col-xs-12 stateService_1">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[1]' => 1
						]); ?>" style="color:#FFF;">
							<?php echo __('%s warning', $widgetServiceStateArray180['state'][1]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[1]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[0]' => 1,
						]); ?>">
							<?php echo __('%s not handled', $widgetServiceStateArray180['not_handled'][1]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[1]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[1]' => 1,
						]); ?>">
							<?php echo __('%s acknowledged', $widgetServiceStateArray180['acknowledged'][1]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[1]' => 1,
							'Filter.Servicestatus.scheduled_downtime_depth[0]' => 1
						]); ?>">
							<?php echo __('%s in downtime', $widgetServiceStateArray180['in_downtime'][1]);?>
						</a>
					</div>
				</div>
			
				<div class="col-xs-4">
					<div class="col-xs-12 stateService_2">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[2]' => 1
						]); ?>" style="color:#FFF;">
							<?php echo __('%s critical', $widgetServiceStateArray180['state'][2]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[2]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[0]' => 1,
							'Filter.Servicestatus.scheduled_downtime_depth[0]' => 1
						]); ?>">
							<?php echo __('%s not handled', $widgetServiceStateArray180['not_handled'][2]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[2]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[1]' => 1,
						]); ?>">
							<?php echo __('%s acknowledged', $widgetServiceStateArray180['acknowledged'][2]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[2]' => 1,
							'Filter.Servicestatus.scheduled_downtime_depth[0]' => 1
						]); ?>">
							<?php echo __('%s in downtime', $widgetServiceStateArray180['in_downtime'][2]);?>
						</a>
					</div>
				</div>
			
				<div class="col-xs-4">
					<div class="col-xs-12 stateService_3">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[3]' => 1
						]); ?>" style="color:#FFF;">
							<?php echo __('%s unknown', $widgetServiceStateArray180['state'][3]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[3]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[0]' => 1,
							'Filter.Servicestatus.scheduled_downtime_depth[0]' => 1
						]); ?>">
							<?php echo __('%s not handled', $widgetServiceStateArray180['not_handled'][3]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[3]' => 1,
							'Filter.Servicestatus.problem_has_been_acknowledged[1]' => 1,
						]); ?>">
							<?php echo __('%s acknowledged', $widgetServiceStateArray180['acknowledged'][3]);?>
						</a>
					</div>
					<div class="col-xs-12">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state[3]' => 1,
							'Filter.Servicestatus.scheduled_downtime_depth[0]' => 1
						]); ?>">
							<?php echo __('%s in downtime', $widgetServiceStateArray180['in_downtime'][3]);?>
						</a>
					</div>
				</div>
			</div>
		</div>
			<div class="text-center font-xs">
				<div class="col-xs-12">
					<div class="toggleDetailsForPiechart"><i class="fa fa-angle-down"></i></div>
				</div>
				<?php foreach($widgetServiceStateArray180['state'] as $state => $stateCount):?>
					<div class="col-md-3 no-padding">
						<a href="<?php echo Router::url([
							'controller' => 'services',
							'action' => 'index',
							'plugin' => '',
							'Filter.Servicestatus.current_state['.$state.']' => 1
						]); ?>">
							<i class="fa fa-square <?php echo $stateColors[$state]?>"></i>
							<?php echo $stateCount .' ('.round($stateCount/$widgetServiceStateArray180['total'] * 100, 2).' %)'; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else:?>
			<div class="text-muted padding-top-80">
				<h5><?php echo __('No services are monitored on your system. Please create first a service'); ?></h5>
			</div>
		<?php endif; ?>
	</div>
</div>
