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
	<div class="col-xs-12 text-center">
	<?php
	if($widgetServiceStateArray['total'] > 0):
		$overview_chart =  $this->PieChart->createPieChart($widgetServiceStateArray['state']);
		echo $this->Html->image(
			'/img/charts/'.$overview_chart
		);
		$state_colors = [
			'ok',
			'warning',
			'critical',
			'unknown'
		];?>
		<div class="text-center font-xs">
		<?php foreach($widgetServiceStateArray['state'] as $state => $stateCount):?>
			<div class="col-md-3 no-padding">
				<a href="<?php echo Router::url([
					'controller' => 'services',
					'action' => 'index',
					'plugin' => '',
					'Filter.Servicestatus.current_state['.$state.']' => 1
				]); ?>">
					<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
					<?php echo $stateCount.' ('.round($stateCount/$widgetServiceStateArray['total'] * 100, 2).' %)'; ?>
				</a>
			</div>
		<?php endforeach;?>
		</div>
	<?php else:?>
		<div class="text-muted padding-top-80">
			<h5><?php echo __('No services are monitored on your system. Please create first a service'); ?></h5>
		</div>
	<?php endif;?>
	</div>
</div>
