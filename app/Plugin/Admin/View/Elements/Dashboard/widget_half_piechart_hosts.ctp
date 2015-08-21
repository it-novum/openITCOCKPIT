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
<div class="widget-body pieChartHosts">
<?php
$state_total = array_sum($state_array_host);
if($state_total > 0):
	$overview_chart =  $this->PieChart->createHalfPieChart($state_array_host);
	echo $this->Html->image(
		'/img/charts/'.$overview_chart
	);
	$state_colors = [
		'ok',
		'critical',
		'unknown'
	];
	$defaultArray = array_fill(0, 3, 0);
	$counterNotHandled = $defaultArray;
	$counterAchknowledged = $defaultArray;
	$counterPlaned = $defaultArray;
	?>
	<div class="detailsForPiechart">
	<?php
	foreach($allHosts as $host):
		if($host['Hoststatus']['current_state'] > 0):
			if($host['Hoststatus']['problem_has_been_acknowledged'] === '0'):
				$counterNotHandled[$host['Hoststatus']['current_state']]++;
			else:
				$counterAchknowledged[$host['Hoststatus']['current_state']]++;
			endif;
			if($host['Hoststatus']['scheduled_downtime_depth'] > 0):
				$counterPlaned[$host['Hoststatus']['current_state']]++;
			endif;
		endif;
	endforeach;
	foreach($state_array_host as $state => $state_count):
		if($state > 0):?>
		<div class="stateColHost">
			<div class="stateHost_<?php echo $state;?>">
				<?php echo $state_count;
					if($state === 1){
						echo " down";
					}
					if($state === 2){
						echo " unreachable";
					}
				?>
			</div>
			<div class="stateColHostList">
			<?php

			if($counterNotHandled[$state] > 0):
				echo "<div class='stateColHostListItem'><a href='/hosts/index/Filter.Hoststatus.current_state[".$state."]:1/Filter.Hoststatus.problem_has_been_acknowledged[0]:1'>( ".$counterNotHandled[$state]." ) not handled</a></div>";
			else:
				echo "<div class='stateColHostListItem'>( ".$counterNotHandled[$state]." ) not handled</div>";
			endif;

			if($counterAchknowledged[$state] > 0):
				echo "<div class='stateColHostListItem'><a href='/hosts/index/Filter.Hoststatus.current_state[".$state."]:1/Filter.Hoststatus.problem_has_been_acknowledged[1]:1'>( ".$counterAchknowledged[$state]." ) acknowledged</a></div>";
			else:
				echo "<div class='stateColHostListItem'>( ".$counterAchknowledged[$state]." ) acknowledged</div>";
			endif;

			if($counterPlaned[$state] > 0):
				echo "<div class='stateColHostListItem'><a href='/hosts/index/Filter.Hoststatus.current_state[".$state."]:1/Filter.Hoststatus.scheduled_downtime_depth[0]:1'>( ".$counterPlaned[$state]." ) planned</a></div>";
			else:
				echo "<div class='stateColHostListItem'>( ".$counterPlaned[$state]." ) planned</div>";
			endif;


			?>
			</div>
		</div>
		<?php endif;
	endforeach;?>
	</div>
	<div class="toggleDetailsForPiechart"><i class="fa fa-angle-down"></i></div>
	<div class="col-md-12 text-center padding-bottom-10 font-xs">
		<?php foreach($state_array_host as $state => $state_count):?>
			<div class="col-md-4 no-padding">
				<a href="<?php echo Router::url([
					'controller' => 'hosts',
					'action' => 'index',
					'plugin' => '',
					'Filter.Hoststatus.current_state['.$state.']' => 1
				]); ?>">
					<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
					<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
<?php else:?>
	<div class="text-muted padding-top-20">
		<?php echo __('No hosts are monitored on your system. Please create first a host'); ?>
	</div>
<?php endif; ?>
</div>
