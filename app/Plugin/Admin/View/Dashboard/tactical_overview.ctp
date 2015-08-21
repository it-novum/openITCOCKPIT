<?php //debug($hoststatus);?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-cube fa-fw "></i>
				<?php echo __('Tactical Overview'); ?>
		</h1>
	</div>
</div>
<!-- widget grid -->
<section class="tab-content" id="widget-grid">
	<!-- row -->
	<div class="row">
		<!-- NEW WIDGET START -->
		<article class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" data-widget-editbutton="false">
				<header class="status_datatable_header">
					<span class="widget-icon"><i class="fa fa-pie-chart"></i></span>
					<h2>
						<?php echo __('Host state');?>
						
					</h2>
				</header>
				<div style="margin:0; padding:0;">
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th class="text-center"><i class="fa fa-square critical"></i></th>
								<th class="text-center"><i class="fa fa-square unknown"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>&nbsp;</td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state[0]' => 1]); ?>"><?php echo $hostStateCount[1]; ?></a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state[0]' => 1]); ?>"><?php echo $hostStateCount[2]; ?></a></td>
							</tr>
							<tr>
								<td><i class="fa fa-user"></i></td>
								<td>5</td>
								<td>5</td>
							</tr>
							<tr>
								<td><i class="fa fa-power-off"></i></td>
								<td><?php echo $hostsInDowntimeCrit; ?></td>
								<td><?php echo $hostsInDowntimeUnknown; ?></td>
							</tr>
						</tbody>
					</table>
					
					<table class="table table-bordered text-center" style="margin-bottom: 0px;">
						<thead>
							<tr>
								<th class="text-center"><i class="fa fa-plug"></i></th>
								<th class="text-center"><i class="fa fa-user-md"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'disabled', 'plugin' => '']);?>"><?php echo $disabledHosts; ?></a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'notMonitored', 'plugin' => '']);?>"><?php echo $hostsNotMonitored; ?></a></td>
							</tr>
						</tbody>
					</table>
					
					
				</div>
			</div>
		</article>
		
		
		<article class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" data-widget-editbutton="false">
				<header class="status_datatable_header">
					<span class="widget-icon"><i class="fa fa-pie-chart"></i></span>
					<h2>
						<?php echo __('Host state');?>
						
					</h2>
				</header>
				<div style="margin:0; padding:0;">
					<table class="table table-bordered text-center">
						<thead>
							<tr>
								<th class="text-center"><i class="fa fa-square ok"></i></th>
								<th class="text-center"><i class="fa fa-square critical"></i></th>
								<th class="text-center"><i class="fa fa-square unknown"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state[0]' => 1]); ?>">1</a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state[0]' => 1]); ?>">1</a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'index', 'plugin' => '', 'Filter.Hoststatus.current_state[0]' => 1]); ?>">1</a></td>
							</tr>
						</tbody>
					</table>
					
					<table class="table table-bordered text-center" style="margin-bottom: 0px;">
						<thead>
							<tr>
								<th class="text-center"><i class="fa fa-power-off"></i></th>
								<th class="text-center"><i class="fa fa-plug"></i></th>
								<th class="text-center"><i class="fa fa-user-md"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><a href="<?php echo Router::url(['controller' => 'downtimes', 'action' => 'host', 'plugin' => '']); ?>">1</a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'notMonitored', 'plugin' => '']); ?>">1</a></td>
								<td><a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'disabled', 'plugin' => '']); ?>">1</a></td>
							</tr>
						</tbody>
					</table>
					
				</div>
			</div>
		</article>
		
	</div>
</section>
