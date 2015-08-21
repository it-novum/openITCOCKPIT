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
<ol class="breadcrumb">
	<?php
	$current_node = $top_node;
	if($top_node['Container']['parent_id'] != null):
		foreach($parents as $parent):
			if($parent['Container']['containertype_id'] == CT_GLOBAL){
				echo '<li>'.$this->Html->link($parent['Container']['name'], 'index/'.$parent['Container']['id']).'</li>';
			}else{
				echo '<li>'.$this->Html->link($parent['Container']['name'], $this->BrowserMisc->browserLink($parent['Container']['containertype_id']).'/'.$parent['Container']['id']).'</li>';
			}
		endforeach;
	endif;
	?>
	<li class="active"><?php echo $current_node['Container']['name']; ?><li>
</ol>

<div class="row">
	<article class="col-sm-2 col-md-2 col-lg-2">
		<div data-widget-fullscreenbutton="false" data-widget-editbutton="false" id="wid-id-1" class="jarviswidget jarviswidget-color-blueDark" style="" role="widget">
			<header role="heading">
				<span class="widget-icon"> <i class="fa fa-list-ul  txt-color-white"></i> </span>
				<h2> <?php echo __('nodes'); ?> </h2>
				<!-- <div class="widget-toolbar" role="menu"></div> -->
			</header>
			<div role="content">
				<div class="widget-body widget-hide-overflow">
						<?php foreach($browser as $b): ?>
							<?php 
								$faClass = $this->BrowserMisc->containertypeIcon($b['containertype_id']);
								$link = $this->BrowserMisc->browserLink($b['containertype_id']);
							?>
							<i class="fa <?php echo $faClass; ?>"></i>
							<?php echo $this->Html->link($b['name'], $link.'/'.$b['id']); ?>
							<br />
						<?php endforeach; ?>
				</div>
			</div>
		</div>
	</article>
	<article class="col-sm-5 col-md-5 col-lg-5 sortable-grid ui-sortable">
		<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
		<header>
				<span class="widget-icon"> <i class="fa fa-pie-chart"></i></span>
				<h2><?php echo __('Hoststatus overview'); ?></h2>
			</header>
			<!-- widget div-->
			<div>
				<!-- end widget edit box -->
				<div class="widget-body padding-10 text-center">
					<?php
					$state_total = array_sum($state_array_host);
						if($state_total > 0):
						$overview_chart =  $this->PieChart->createPieChart($state_array_host);
						echo $this->Html->image(
							'/img/charts/'.$overview_chart
						);
						$state_colors = [
							'ok',
							'critical',
							'unknown'
						];?>
						<div class="col-md-12 text-center padding-bottom-10 font-xs">
							<?php
								foreach($state_array_host as $state => $state_count):?>
									<div class="col-md-4 no-padding">
										<a href="<?php echo Router::url([
										'controller' => 'hosts',
										'action' => 'index',
										'plugin' => '',
										'Filter.Hoststatus.current_state['.$state.']' => 1,
										'BrowserContainerId' => $all_container_ids
										]); ?>">
											<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
											<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
										</a>
									</div>
							<?php endforeach; ?>
						</div>
					<?php else:?>
						<div class="text-muted padding-top-20"><?php echo __('No hosts are monitored on your system. Please create first a host'); ?></div>
					<?php endif; ?>
				</div>

			</div>
	</article>
	<article class="col-sm-5 col-md-5 col-lg-5 sortable-grid ui-sortable">
		<div class="jarviswidget jarviswidget-sortable" id="wid-id-12" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
		<header>
				<span class="widget-icon"> <i class="fa fa-pie-chart"></i></i></span>
				<h2><?php echo __('Servicestatus overview'); ?></h2>
			</header>
			<!-- widget div-->
			<div>
				<!-- end widget edit box -->
				<div class="widget-body padding-10 text-center">
				<?php
					$state_total = array_sum($state_array_service);
					if($state_total > 0):
						$overview_chart =  $this->PieChart->createPieChart($state_array_service);

						echo $this->Html->image(
							'/img/charts/'.$overview_chart
						);
						$state_colors = [
							'ok',
							'warning',
							'critical',
							'unknown'
						];?>
						<div class="col-md-12 text-center padding-bottom-10 font-xs">
						<?php

							foreach($state_array_service as $state => $state_count):?>
								<div class="col-md-3 no-padding">
									<a href="<?php echo Router::url([
									'controller' => 'services',
									'action' => 'index',
									'plugin' => '', 'Filter.Servicestatus.current_state['.$state.']' => 1,
									'BrowserContainerId' => $all_container_ids
									]); ?>">
										<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
										<?php
										//Fix for a system without host or services
										if($state_total == 0):
											$state_total = 1;
											if($state == 3):
												$state_count = 1;
											endif;
										endif;
										?>
										<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
									</a>
								</div>
						<?php endforeach;?>
						</div>
					<?php else:?>
						<div class="text-muted padding-top-20"><?php echo __('No services are monitored on your system. Please create first a service'); ?></div>
					<?php endif;?>
				</div>
			</div>
	</article>
</div>