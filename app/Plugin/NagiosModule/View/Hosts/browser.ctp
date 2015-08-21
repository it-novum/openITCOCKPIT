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
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
		<h1 class="page-title <?php echo $this->Status->HostStatusColor($host[0]['HostStatus']['current_state']); ?>">
			<i class="fa fa-desktop fa-fw"></i>
				<?php echo $host[0]['Objects']['name1']; ?>
			<span>
				(<?php echo $host[0]['Host']['address']; ?>)
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
		<h5>
			<span class="pull-right"><a href="/nagios_module/hosts/edit/<?php echo $host[0]['Host']['host_object_id']; ?>"><i class="fa fa-cog "></i> <span class="underline">E</span>dit host</a>&nbsp;&nbsp;&nbsp;</span>
			<span class="pull-right"><i class="fa fa-book "></i> <span class="underline">D</span>oku&nbsp;&nbsp;&nbsp;</span>
			<span class="pull-right"><i class="fa fa-refresh "></i> <span class="underline">R</span>efresh&nbsp;&nbsp;&nbsp;</span>
		</h5>
	</div>
</div>

<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
		<div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
		<header role="heading">
		<h2><strong><?php echo __('Host');?>:</strong></h2>
		<ul class="nav nav-tabs pull-right" id="widget-tab-1">
			<li class="active">
				<a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-info"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('status.information'); ?></span> </a>
			</li>
			<li class="">
				<a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-hdd-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('device.information'); ?> </span></a>
			</li>
			<li class="">
				<a href="#tab3" data-toggle="tab"> <i class="fa fa-lg fa-envelope-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('notification.information'); ?> </span></a>
			</li>
			<li class="">
				<a href="#tab4" data-toggle="tab"> <i class="fa fa-lg fa-desktop"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('host.commands'); ?> </span></a>
			</li>
		</ul>
		<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
		<!-- widget div-->
		<div role="content">
			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->
			</div>
			<!-- end widget edit box -->
			<!-- widget content -->
			<div class="widget-body no-padding">
				<!-- widget body text-->
				<div class="tab-content padding-10">
					<div id="tab1" class="tab-pane fade active in">
						<?php echo $host[0]['Objects']['name1']; ?> <strong>available since: <?php echo $this->Time->format($host[0]['HostStatus']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
						<br /><br/>
						<p>The last system check occurred at <strong><?php echo $this->Time->format($host[0]['HostStatus']['status_update_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong> according to Hard check.</p>
						<dl>
							<dt>Flap Detection:</dt>
							<dd><?php echo $this->Nagios->checkFlapDetection($host[0]['HostStatus']['flap_detection_enabled'])['html']; ?></dd>
							<dt>Check options:</dt>
							<dd>Maximum attempts per check: <?php echo $host[0]['Host']['max_check_attempts']; ?></dd>
							<dt>Check command:</dt>
							<dd>Command name: <a href="/nagios_module/commands/edit/<?php echo $command[0]['Command']['object_id']; ?>" ><?php echo $command[0]['Objects']['name1']; ?></a></dd>
							<dd>Command line: <code class="<?php echo $this->Nagios->colorHostOutput($host[0]['HostStatus']['current_state']); ?>"><?php echo $command[0]['Command']['command_line']; ?></code></dd>
							<dt>Output:</dt>
							<dd><code class="<?php echo $this->Nagios->colorHostOutput($host[0]['HostStatus']['current_state']); ?>"><?php echo $host[0]['HostStatus']['output']; ?></code></dd>
						</dl>

					</div>
					<div id="tab2" class="tab-pane fade">
						<strong>Client:</strong> it-novum<br/>
						<strong>Location:</strong> Fulda<br />
						<strong>Device group:</strong> Server
						<br />
						<br />
						<strong>IP address:</strong> <code><?php echo $host[0]['Host']['address']; ?></code><br />
						<strong>Description:</strong><br />
						<i class="txt-color-blue"><?php echo $host[0]['Host']['alias']; ?></i>
					</div>
					<div id="tab3" class="tab-pane fade">
						<strong>Notification period:</strong> 24x7<br />
						<strong>Notification interval:</strong> 2h 0m 0s<br />
						<br />
						<dl>
							<dt>Notification occurs in the following cases:</dt>
							<?php echo $this->Nagios->formatNotifyOnHost(array(
							'notify_on_down' => $host[0]['Host']['notify_on_down'],
							'notify_on_unreachable' => $host[0]['Host']['notify_on_unreachable'],
							'notify_on_recovery' => $host[0]['Host']['notify_on_recovery'],
							'notify_on_flapping' => $host[0]['Host']['notify_on_flapping'],
							'notify_on_downtime' => $host[0]['Host']['notify_on_downtime'],
							)); ?>
						</dl>
						<dl>
							<dt>The following persons are notified:</dt>
							<dd>openitcockpitSupport</dd>
						</dl>
					</div>
					<div id="tab4" class="tab-pane fade">
					 	<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i class="fa fa-refresh"></i> Reset check time </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_passive_result"><i class="fa fa-download"></i> Passive transfer of check results </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_schedule_downtime"><i class="fa fa-clock-o"></i> Set planned maintenance times </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_flap_detection"><i class="fa fa-adjust"></i> Enables/disables flap detection for a particular host </span><br /></h5>
					</div>
				</div>
				<!-- end widget body text-->
				<!-- widget footer -->
				<div class="widget-footer text-right"></div>
				<!-- end widget footer -->
			</div>
			<!-- end widget content -->
		</div>
		<!-- end widget div -->
	</div>
	</article>
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
		<div id="wid-id-0" class="jarviswidget jarviswidget-sortable" role="widget">
			<header role="heading">
			<h2><strong>Services:</strong></h2>
			<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
			<!-- widget div-->
			<div role="content">
				<!-- widget edit box -->
				<div class="jarviswidget-editbox">
					<!-- This area used as dropdown edit box -->
					<input type="text" class="form-control">
					<span class="note"><i class="fa fa-check text-success"></i> Change title to update and save instantly!</span>
				</div>
				<!-- end widget edit box -->
				<!-- widget content -->
				<div class="widget-body no-padding">
					<div class="widget-body-toolbar"></div>
					<div  class="custom-scroll table-responsive">
						<table class="table table-bordered" id="host_browser_service_table">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th>Servicestatus</th>
									<th>Servicedescription</th>
									<th>last_state_change</th>
									<th>Serviceoutput</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($services as $service): ?>
									<tr>
										<td><center><?php echo $this->Status->humanServiceStatus($service['ServiceStatus']['current_state'])['html_icon']; ?></center></td>
										<td><a href="/nagios_module/services/browser/<?php echo $service['Service']['service_object_id']; ?>"><?php echo $service['Objects']['name2']; ?></a></td>
										<td><?php echo $service['ServiceStatus']['last_state_change']; ?></td>
										<td><?php echo $service['ServiceStatus']['output']; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- end widget content -->
			</div>
			<!-- end widget div -->
		</div>
	</article>
</div>


<div class="modal fade" id="nag_command_reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('command.reschedule');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('nag_command', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input(__('Host check for').':', array('options' => array(0 => 'only Host', 1 => 'Host and Services'))); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">
					<?php echo __('send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="nag_command_passive_result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('command.passive_result');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('nag_command', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input(__('Comment').':', array('placeholder' => __('testalert'))); ?>
					<?php echo $this->Form->input(__('Status').':', array('options' => array(0 => 'Up', 1 => 'Down', 2 => 'Unreachable'))); ?>
					<?php echo $this->Form->input(__('Repetitions').':', array('value' => 1)); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">
					<?php echo __('send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('command.passive_result');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('nag_command', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<h1>Some downtime stuff</h1>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">
					<?php echo __('send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<div class="modal fade" id="nag_command_flap_detection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('command.reschedule');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('nag_command', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<center>
						<?php if($host[0]['HostStatus']['flap_detection_enabled'] == 0): ?>
							Yes, i want to <strong>enable</strong> flap detection.
						<?php else:?>
							Yes, i want to <strong>disable</strong> flap detection.
						<?php endif;?>
					</center>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">
					<?php echo __('send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>