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
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
	<a href="#" data-dismiss="alert" class="close">×</a>
	<h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
	<?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<?php if(!$QueryHandler->exists()): ?>
	<div class="alert alert-danger alert-block">
		<a href="#" data-dismiss="alert" class="close">×</a>
		<h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?></h4>
		<?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
	</div>
<?php endif; ?>

<div class="alert auto-hide alert-danger" id="flashFailed" style="display:none"><?php echo __('Error while sending command'); ?></div>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
		<h1 class="page-title <?php echo $this->Status->HostStatusColor($host['Host']['uuid']); ?>">
			<?php echo $this->Monitoring->HostFlappingIcon($this->Status->get($host['Host']['uuid'], 'is_flapping')); ?>
			<i class="fa fa-desktop fa-fw"></i>
				<?php echo h($host['Host']['name']); ?>
			<span>
				(<?php echo h($host['Host']['address']); ?>)
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
		<h5>
			<div class="pull-right">
				<?php echo $this->element('host_browser_menu'); ?>
			</div>
		</h5>
	</div>
</div>

<div class="row">
	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
		<div data-widget-custombutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" id="wid-id-11" class="jarviswidget jarviswidget-sortable" role="widget">
		<header role="heading">
		<h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Host');?>:</strong></h2>
		<ul class="nav nav-tabs pull-right" id="widget-tab-1">
			<li class="active">
				<a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-info"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Status information'); ?></span> </a>
			</li>
			<li class="">
				<a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-hdd-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Device information'); ?> </span></a>
			</li>
			<li class="">
				<a href="#tab3" data-toggle="tab"> <i class="fa fa-lg fa-envelope-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Notification information'); ?> </span></a>
			</li>
			<?php if($allowEdit):?>
				<li class="">
					<a href="#tab4" data-toggle="tab"> <i class="fa fa-lg fa-desktop"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Host commands'); ?> </span></a>
				</li>
			<?php endif;?>
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
						<?php echo $host['Host']['name']; ?> <strong><?php echo __('available since:')?> <?php echo $this->Time->format($this->Status->get($host['Host']['uuid'], 'last_state_change'), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
						<br /><br/>
						<p><?php echo __('The last system check occurred at'); ?> <strong><?php echo $this->Time->format($this->Status->get($host['Host']['uuid'], 'last_check'), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
							<?php
							if($this->Status->get($host['Host']['uuid'], 'state_type') == 1):
								echo '<span class="label text-uppercase '.$this->Status->HostStatusBackgroundColor($this->Status->sget($host['Host']['uuid'],'current_state')).'">'.__('hard state').'</span>';
							else:
								echo '<span class="label text-uppercase opacity-50 '.$this->Status->HostStatusBackgroundColor($this->Status->sget($host['Host']['uuid'],'current_state')).'" >'.__('soft state').'</span>';
							endif; ?>
						</p>

						<?php if($this->Monitoring->checkForAck($this->Status->get($host['Host']['uuid'], 'problem_has_been_acknowledged')) && !empty($acknowledged)):?>
							<p>
								<span class="fa-stack fa-lg">
									<?php if($hoststatus[$host['Host']['uuid']]['Hoststatus']['acknowledgement_type'] == 1):?>
                                        <i class="fa fa-user fa-stack-2x"></i>
                                    <?php else:?>
                                        <i class="fa fa-user-o fa-stack-2x"></i>
                                    <?php endif;?>
                                    <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-8"></i>
								</span>
								<?php
                                    if($hoststatus[$host['Host']['uuid']]['Hoststatus']['acknowledgement_type'] == 1):
                                        echo __('The current status was already acknowledged by');
                                    else:
                                        echo __('The current status was already acknowledged (STICKY) by');
                                    endif;?>
                                    <strong><?php echo h($acknowledged['Acknowledged']['author_name']); ?></strong> (<i class="fa fa-clock-o"></i>
									<?php
										echo $this->Time->format($acknowledged['Acknowledged']['entry_time'],
											$this->Auth->user('dateformat'),
											false,
											$this->Auth->user('timezone')
									);
									?>)
									<?php echo __('with the comment ');?>
									"<?php
										$ticketDetails = [];
										if(!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $acknowledged['Acknowledged']['comment_data'], $ticketDetails)):
											echo (isset($ticketDetails[1],$ticketDetails[3],$ticketDetails[2]))?
												$this->Html->link(
													$ticketDetails[1].' '.$ticketDetails[2],
													$ticketSystem['Systemsetting']['value'].$ticketDetails[3],
													['target' => '_blank']):
												$acknowledged['Acknowledged']['comment_data'];
										else:
											echo h($acknowledged['Acknowledged']['comment_data']);
										endif;
										?>".

							</p>
						<?php endif;?>

						<?php if($this->Monitoring->checkForDowntime($this->Status->get($host['Host']['uuid'], 'scheduled_downtime_depth'))):?>
							<p>
								<span class="fa-stack fa-lg">
									<i class="fa fa-power-off fa-stack-2x"></i>
									<i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
								</span>
								<?php echo __('The host is currently in a planned maintenance period.'); ?><br /><br />
							</p>

						<?php endif;?>

						<?php if(!empty($parenthosts)): ?>
							<?php foreach($parenthosts as $parenthost): ?>
								<?php if($this->Status->get($parenthost['Host']['uuid'], 'current_state') > 0):?>
									<p class="parentstatus padding-left-10">
										<?php echo __('Problem with parent host');?> <a href="/hosts/browser/<?php echo $parenthost['Host']['id']; ?>"><?php echo h($parenthost['Host']['name']);?></a> <?php echo __('detected');?>
										<br />
										<?php $_state = $this->Status->humanHostStatus($parenthost['Host']['uuid'], 'javascript:void(0)', null, 'cursor:auto;'); ?>

										<?php echo $_state['html_icon']; ?>
										<span class="padding-left-5" style="vertical-align: middle;"><?php echo $_state['human_state'];?></span>
										<code class="no-background <?php echo $this->Status->HostStatusColor($parenthost['Host']['uuid']); ?>">(<?php echo h($this->Status->get($parenthost['Host']['uuid'], 'output'));?>)</code>
									</p>
								<?php endif; ?>
								<?php if($this->Monitoring->checkForDowntime($this->Status->get($parenthost['Host']['uuid'], 'scheduled_downtime_depth'))):?>
									<p class="parentstatus">
										<span class="fa-stack fa-lg">
											<i class="fa fa-power-off fa-stack-2x"></i>
											<i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
										</span>
										<?php echo __('The parent host'); ?> <strong><?php echo h($parenthost['Host']['name']);?></strong> <?php echo __('is currently in a scheduled downtime'); ?>
									</p>
								<?php endif;?>
							<?php endforeach;?>
						<?php endif;?>

						<table class="table table-bordered">
							<tbody>
								<tr>
									<td><strong><?php echo __('Current state'); ?>:</strong></td>
									<td>
										<?php
										$_state = $this->Status->humanHostStatus($host['Host']['uuid'], 'javascript:void(0)', null, 'cursor:auto;');
										echo $_state['html_icon'];
										?>

									</td>
								</tr>
								<tr>
									<td><strong><?php echo __('Flap detection'); ?>:</strong></td>
									<td><?php echo $this->Monitoring->compareHostFlapDetectionWithMonitoring($host)['html']; ?></td>
								</tr>

								<?php if($this->Status->get($host['Host']['uuid'], 'notifications_enabled') == 0):?>
									<tr>
										<td><strong><?php echo __('Notifications'); ?>:</strong></td>
										<td><a data-original-title="<?php echo __('Difference to configuration detected'); ?>" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-redLight"><?php echo __('Temporary off'); ?></span></td>
									</tr>
								<?php endif; ?>

								<tr>
									<td><strong><?php echo __('Check attempt'); ?>:</strong></td>
									<td><?php echo h($this->Status->get($host['Host']['uuid'], 'current_check_attempt')); ?>/<?php echo h($host['Host']['max_check_attempts']); ?></td>
								</tr>
								<tr>
									<td><strong><?php echo __('Command name'); ?>:</strong></td>
									<td>
										<?php if($this->Acl->hasPermission('edit', 'commands')):?>
											<a href="/commands/edit/<?php echo $host['CheckCommand']['id']; ?>" ><?php echo h($host['CheckCommand']['name']); ?></a>
										<?php else: ?>
											<?php echo h($host['CheckCommand']['name']); ?>
										<?php endif; ?>
									</td>
								</tr>
								<?php if($this->Acl->hasPermission('checkcommand')):?>
									<tr>
										<td><strong><?php echo __('Command line'); ?>:</strong></td>
										<?php
										$HostMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($host);
										$hostCommandLine = $HostMacroReplacerCommandLine->replaceBasicMacros($host['CheckCommand']['command_line']);

										$HostCustomMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\CustomMacroReplacer($host['Customvariable'], OBJECT_HOST);
										$hostCommandLine = $HostCustomMacroReplacerCommandLine->replaceAllMacros($hostCommandLine);
										?>
										<td><code class="no-background <?php echo $this->Status->HostStatusColor($host['Host']['uuid']); ?>"><?php echo $this->Monitoring->replaceCommandArguments($commandarguments, $hostCommandLine); ?></code></td>
									</tr>
								<?php endif; ?>
								<tr>
									<td><strong><?php echo __('Next check in'); ?>:</strong></td>
									<td>
										<?php if($host['Host']['active_checks_enabled'] == 1 && $host['Host']['satellite_id'] == 0 && $this->Status->get($host['Host']['uuid'], 'active_checks_enabled') !== null): ?>
											<?php echo h($this->Time->timeAgoInWords(strtotime($this->Status->get($host['Host']['uuid'], 'next_check')), ['timezone' => $this->Auth->user('timezone')])); ?>
											<?php if($this->Status->get($host['Host']['uuid'], 'latency') > 1): ?>
												<span class="text-muted" title="<?php echo __('Check latency'); ?>">(+<?php echo $this->Status->get($host['Host']['uuid'], 'latency'); ?>)</span>
											<?php endif; ?>
										<?php else:?>
												<?php
												if($this->Status->get($host['Host']['uuid'], 'active_checks_enabled') === null):
													echo __('Not found in monitoring');
												else:
													echo __('n/a due to passive check');
												endif;
												?>
										<?php endif;?>
									</td>
								</tr>
								<tr>
									<td><strong><?php echo __('Output'); ?>:</strong></td>
									<td><code class="no-background <?php echo $this->Status->HostStatusColor($host['Host']['uuid']); ?>"><?php echo h($this->Status->get($host['Host']['uuid'], 'output')); ?></code></td>
								</tr>
							</tbody>
						</table>
						<dl>
							<dt><?php echo __('Long output'); ?>:</dt>
							<?php $long_output = $this->Status->get($host['Host']['uuid'], 'long_output'); ?>
							<?php if(!empty($long_output)): ?>
								<!-- removing HTML tags, so that we can display a preview witout breaking the page -->
								<dd>
									<div id="nag_longout_preview"><?php echo $this->Bbcode->nagiosNl2br(substr(strip_tags($this->Bbcode->asHtml($long_output)), 0, 200)); ?><a href="javascript:void(0);" id="nagShowLongOutput" ><?php echo __('...read more'); ?></a></div>
									<div id="nag_longoutput_container" style="display:none;">
										<div id="nag_longoutput_loader">
											<span class="text-center">
												<h1>
													<i class="fa fa-cog fa-lg fa-spin"></i>
												</h1>
												<br />
											</span>
										</div>
										<div id="nag_longoutput_content"><!-- content loaded by ajax --></div>
									</div>
								</dd>
							<?php else:?>
								<dd> <code class="no-background <?php echo $this->Status->HostStatusColor($host['Host']['uuid']); ?>"><?php echo __('No long output available'); ?></code></dd>
							<?php endif;?>
						</dl>

					</div>
					<div id="tab2" class="tab-pane fade">
						<strong><?php echo __('Check period')?>:</strong> <?php echo h($host['CheckPeriod']['name']); ?><br />
						<strong><?php echo __('Check interval')?>:</strong>
						<?php echo $this->Utils->secondsInHumanShort($host['Host']['check_interval']); ?>
						<br />
						<strong><?php echo __('Check interval in case of error'); ?>:</strong> <?php echo $this->Utils->secondsInHumanShort($host['Host']['retry_interval']); ?>
						<br />
						<strong><?php echo __('Active checks enabled')?>:</strong>
						<?php if($host['Host']['active_checks_enabled'] == 1): ?>
							<i class="fa fa-check text-success"></i>
						<?php else: ?>
							<i class="fa fa-times text-danger"></i>
						<?php endif; ?>
						<br />
						<br />
						<strong><?php echo __('UUID')?>:</strong> <code><?php echo $host['Host']['uuid']; ?></code><br />
						<strong><?php echo __('IP address'); ?>:</strong> <code><?php echo h($host['Host']['address']); ?></code><br />
						<strong><?php echo __('Description'); ?>:</strong><br />
						<i class="txt-color-blue"><?php echo h($host['Host']['description']); ?></i>
					</div>
					<div id="tab3" class="tab-pane fade">
						<strong><?php echo __('Notification period'); ?>:</strong> <?php echo h($host['NotifyPeriod']['name']);?><br />
						<strong><?php echo __('Notification interval'); ?>:</strong> <?php echo $this->Utils->secondsInHumanShort($host['Host']['notification_interval']); ?><br />
						<br />
						<dl>
							<dt><?php echo __('Notification occurs in the following cases'); ?>:</dt>
							<?php echo $this->Monitoring->formatNotifyOnHost([
								'notify_on_down' => $host['Host']['notify_on_down'],
								'notify_on_unreachable' => $host['Host']['notify_on_unreachable'],
								'notify_on_recovery' => $host['Host']['notify_on_recovery'],
								'notify_on_flapping' => $host['Host']['notify_on_flapping'],
								'notify_on_downtime' => $host['Host']['notify_on_downtime'],
							]); ?>
						</dl>
						<?php
						if($ContactsInherited['inherit'] === true):
							if($this->Acl->hasPermission('edit', 'hosttemplates')):
								$source = __('Host').' <i class="fa fa-arrow-right"></i> <strong><a href="/hosttemplates/edit/'.$host['Host']['hosttemplate_id'].'">'.__('Hosttemplate').'</a></strong>';
							else:
								$source = __('Host').' <i class="fa fa-arrow-right"></i> <strong>'.__('Hosttemplate').'</strong>';
							endif;
							?>
							<span class="text-info"><i class="fa fa-info-circle"></i> <?php echo __('Contacts and Contactgroups are inherited in the following order:');?> <?php echo $source; ?></span>
							<?php
						endif;
						?>
						<?php if(!empty($ContactsInherited['Contact'])):?>
							<dl>
								<dt><?php echo __('The following contacts are notified'); ?>:</dt>
								<dd>
									<?php
									foreach($ContactsInherited['Contact'] as $contact_id => $contact):
										if($this->Acl->hasPermission('edit', 'contacts')):
											$_contacts[] = '<a href="/contacts/edit/'.$contact_id.'">'.h($contact).'</a>';
										else:
											$_contacts[] = h($contact);
										endif;
									endforeach;
									echo implode(', ', $_contacts); unset($_contacts); ?>
								</dd>
							</dl>
						<?php endif;?>
						<?php if(!empty($ContactsInherited['Contactgroup'])): ?>
							<dl>
								<dt><?php echo __('The following contact groups are notified'); ?>:</dt>
								<dd>
									<?php
									foreach($ContactsInherited['Contactgroup'] as $contactgroup_id => $contactgroup):
										if($this->Acl->hasPermission('edit', 'contactgroups')):
											$_contactgroups[] = '<a href="/contactgroups/edit/'.$contactgroup_id.'">'.h($contactgroup).'</a>';
										else:
											$_contactgroups[] = h($contactgroup);
										endif;
									endforeach;
									echo implode(', ', $_contactgroups); unset($_contactgroups); ?>
								</dd>
							</dl>
						<?php endif;?>
					</div>
					<div id="tab4" class="tab-pane fade">
					 	<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?> </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_passive_result"><i class="fa fa-download"></i> <?php echo __('Passive transfer of check results')?> </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_schedule_downtime"><i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?> </span><br /></h5>
						<?php if($this->Status->get($host['Host']['uuid'], 'current_state') > 0):?>
							<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_ack_state"><i class="fa fa-user"></i> <?php echo __('Acknowledge host status'); ?> </span><br /></h5>
						<?php endif;?>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_flap_detection"><i class="fa fa-adjust"></i> <?php echo __('Enables/disables flap detection for a particular host'); ?> </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_notifications"><i class="fa fa-envelope-o"></i> <?php echo __('Enables/disables notifications'); ?> </span><br /></h5>
						<h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_custom_notification"><i class="fa fa-envelope"></i> <?php echo __('Send custom host notification'); ?> </span><br /></h5>
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
	<?php if($this->Acl->hasPermission('index', 'services')):?>
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
			<div id="wid-id-0" class="jarviswidget jarviswidget-sortable" role="widget">
				<header role="heading">
				<h2><strong><?php echo __('Services'); ?>:</strong></h2>
				<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
				<!-- widget div-->
				<div role="content">
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						<input type="text" class="form-control">
						<span class="note"><i class="fa fa-check text-success"></i> <?php echo __('Change title to update and save instantly!'); ?></span>
					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body no-padding">
						<div  class="custom-scroll">
							<?php if(!empty($services)): ?>
								<table class="table table-bordered" id="host_browser_service_table">
									<thead>
										<tr>
											<?php $order = $this->Paginator->param('order'); ?>
											<th><?php echo __('Servicestatus'); ?></th>
											<th class="text-center"><i class="fa fa-user" title="<?php echo __('Acknowledgedment'); ?>"></i></th>
											<th class="text-center"><i class="fa fa-power-off" title="<?php echo __('in Downtime'); ?>"></i></th>
											<th class="text-center"><i class="fa fa fa-area-chart fa-lg" title="<?php echo __('Grapher'); ?>"></i></th>
											<th class="text-center"><strong title="<?php echo __('Passively transferred service'); ?>">P</strong></th>
											<th class="text-center" title="<?php echo __('Disabled Service'); ?>"><i class="fa fa fa-plug"></i></th>
											<th><?php echo __('Name'); ?></th>
											<th><?php echo __('Laste state change'); ?></th>
											<th><?php echo __('Service output'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($services as $service): ?>
											<tr>
												<td class="text-center width-90">
													<?php
													if($this->Status->sget($service['Service']['uuid'], 'is_flapping') == 1):
														echo $this->Monitoring->serviceFlappingIconColored($this->Status->sget($service['Service']['uuid'], 'is_flapping'), '', $this->Status->sget($service['Service']['uuid'], 'current_state'));
													else:
														$currentState = -1;
														if(isset($servicestatus[$service['Service']['uuid']]['Servicestatus']['current_state'])){
															$currentState = $servicestatus[$service['Service']['uuid']]['Servicestatus']['current_state'];
														}
														echo $this->Status->humanServiceStatus($service['Service']['uuid'], '/services/browser/'.$service['Service']['id'], null, $currentState, 'color: transparent;')['html_icon'];
													endif;
													?>
												</td>
												<td class="text-center">
													<?php if($this->Monitoring->checkForAck($this->Status->sget($service['Service']['uuid'], 'problem_has_been_acknowledged'))):?>
														<?php if($servicestatus[$service['Service']['uuid']]['Servicestatus']['acknowledgement_type'] == 1):?>
															<i class="fa fa-user" title="<?php echo __('Acknowledgedment'); ?>"></i>
														<?php else:?>
															<i class="fa fa-user-o" title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
														<?php endif;?>
													<?php endif;?>
												</td>
												<td class="text-center">
													<?php if($this->Monitoring->checkForDowntime($this->Status->sget($service['Service']['uuid'], 'scheduled_downtime_depth'))):?>
														<i class="fa fa-power-off"></i>
													<?php endif;?>
												</td>
												<td class="text-center">
													<?php if($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
															<a class="txt-color-blueDark" href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i class="fa fa-area-chart fa-lg popupGraph" host-uuid="<?php echo $service['Host']['uuid']; ?>" service-uuid="<?php echo $service['Service']['uuid']; ?>"></i></a>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<?php if(($this->Status->sget($service['Service']['uuid'], 'active_checks_enabled') == 0 && $this->Status->sget($service['Service']['uuid'], 'active_checks_enabled') !== null) || (isset($service['Host']['satellite_id'])) && $service['Host']['satellite_id'] > 0):?>
														<strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
													<?php endif;?>
												</td>
												<td class="text-center">
													<?php if($service['Service']['disabled'] == 1): ?>
														<i class="fa fa fa-plug fa-lg"></i>
													<?php endif; ?>
												</td>
												<td>
													<?php
													$serviceName = $service['Service']['name'];
													if($serviceName === null || $serviceName === ''):
														$serviceName = $service['Servicetemplate']['name'];
													endif;
													?>
													<?php if($this->Acl->hasPermission('browser', 'services')):?>
													<a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo h($serviceName); ?></a>
													<?php else: ?>
														<?php echo h($serviceName); ?>
													<?php endif; ?>
												</td>
												<td data-original-title="<?php echo h($this->Time->format($this->Status->sget($service['Service']['uuid'], 'last_state_change'), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>" data-placement="bottom" rel="tooltip" data-container="body">
													<?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($this->Status->sget($service['Service']['uuid'], 'last_state_change'))));?>
												</td>
												<td><?php echo h($this->Status->sget($service['Service']['uuid'], 'output')); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else:?>
								<div class="padding-top-10"><center><span class="txt-color-red italic"><?php echo __('No services associated with this host!'); ?></span></center></div>
							<?php endif;?>
						</div>
					</div>
					<!-- end widget content -->
				</div>
				<!-- end widget div -->
			</div>
		</article>
	<?php endif; ?>
</div>


<div class="modal fade" id="nag_command_reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Reset check time ');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('nag_command', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input('rescheduleHost', ['options' => ['hostOnly' => __('only Host'), 'hostAndServices' => __('Host and Services')], 'label' => __('Host check for').':']); ?>
					<?php echo $this->Form->input('satellite_id', ['type' => 'hidden', 'value' => $host['Host']['satellite_id']]); ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="submitRescheduleHost" data-dismiss="modal">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
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
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Passive transfer of check results');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('CommitPassiveResult', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input('comment', ['value' => __('test alert'), 'label' => __('Comment').':']); ?>
					<?php echo $this->Form->input('status', ['options' => [0 => __('Up'), 1 => __('Down'), 2 => __('Unreachable')], 'label' => __('Status').':']); ?>
					<?php echo $this->Form->fancyCheckbox('forceHardstate', ['caption' => __('Force to hard state?'), 'on' => __('true'), 'off' => __('false')]); ?>
					<?php echo $this->Form->input('repetitions', ['type' => 'hidden', 'value' => $host['Host']['max_check_attempts']]) ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submitCommitPassiveResult">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
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
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="txt-color-red padding-bottom-20" id="validationErrorHostDowntime" style="display:none;"><i class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date');?></div>
					<?php
					echo $this->Form->create('CommitHostDowntime', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php
					$hostdowntimetyps = [
						0 => __('Individual host'),
						1 => __('Host including services'),
						2 => __('Hosts and dependent Hosts (triggered)'),
						3 => __('Hosts and dependent Hosts (non-triggered)')
					];
					?>
					<?php echo $this->Form->input('type', ['options' => $hostdowntimetyps, 'label' => __('Maintenance period for').':'])?>
					<?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>
					<!-- from -->
					<div class="form-group">
						<label class="col col-md-2 control-label" for="CommitHostDowntimeFromDate"><?php echo __('From');?>:</label>
						<div class="col col-xs-5" style="padding-right: 0px;">
							<input type="text" id="CommitHostDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>" class="form-control" name="data[CommitHostDowntime][from_date]">
						</div>
						<div class="col col-xs-5" style="padding-left: 0px;">
							<input type="text" id="CommitHostDowntimeFromTime" value="<?php echo date('h:m'); ?>" class="form-control" name="data[CommitHostDowntime][from_time]">
						</div>
					</div>

					<!-- to -->
					<div class="form-group">
						<label class="col col-md-2 control-label" for="CommitHostDowntimeToDate"><?php echo __('To');?>:</label>
						<div class="col col-xs-5" style="padding-right: 0px;">
							<input type="text" id="CommitHostDowntimeToDate" value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control" name="data[CommitHostDowntime][to_date]">
						</div>
						<div class="col col-xs-5" style="padding-left: 0px;">
							<input type="text" id="CommitHostDowntimeToTime" value="<?php echo date('h:m'); ?>" class="form-control" name="data[CommitHostDowntime][to_time]">
						</div>
					</div>

					<?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
				</div>

			</div>
			<div class="modal-footer">
				<a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostdowntime', 'host_id' => $host['Host']['id']]);?>" class="btn btn-primary pull-left"><i class="fa fa-cogs"></i> <?php echo __('More options'); ?></a>
				<button type="button" class="btn btn-success" id="submitCommitHostDowntime">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
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
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Enables/disables flap detection');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('enableOrDisableHostFlapdetection', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<center>
						<span class="hintmark">
							<?php if($this->Monitoring->compareHostFlapDetectionWithMonitoring($host)['value'] == 0): ?>
								<?php echo __('Yes, i want temporarily <strong>enable</strong> flap detection.'); ?>
							<?php else:?>
								<?php echo __('Yes, i want temporarily <strong>disable</strong> flap detection.'); ?>
							<?php endif;?>
							<?php echo $this->Form->input('condition', ['type' => 'hidden', 'value' => ($this->Monitoring->compareHostFlapDetectionWithMonitoring($host)['value'] == 1)?0:1]); ?>
						</span>
					</center>

					<div class="padding-left-10 padding-top-10">
						<span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableOrDisableHostFlapdetection">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<div class="modal fade" id="nag_command_custom_notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Send custom host notification');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('CommitCustomHostNotification', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input('comment', ['value' => __('test notification'), 'label' => __('Comment').':']); ?>
					<?php echo $this->Form->fancyCheckbox('forced', ['caption' => __('Forced'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
					<?php echo $this->Form->fancyCheckbox('broadcast', ['caption' => __('Broadcast'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
					<?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submitCustomHostNotification">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge host status');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('CommitHoststateAck', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only host'), 'hostAndServices' => __('Host including services')], 'label' => 'Acknowledge for']); ?>
					<?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>
					<?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
					<?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submitHoststateAck">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="nag_command_notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications');?></h4>
			</div>
			<div class="modal-body">

				<div class="row">
					<?php
					echo $this->Form->create('enableNotifications', array(
						'class' => 'form-horizontal clear',
					)); ?>
					<?php echo $this->Form->input('isEnabled', ['type' => 'hidden', 'value' => $this->Status->get($host['Host']['uuid'], 'notifications_enabled')]); ?>
					<?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only host'), 'hostAndServices' => __('Host including services')], 'label' => 'Notifications']); ?>
					<center>
						<span class="hintmark">
							<?php
							if($this->Status->get($host['Host']['uuid'], 'notifications_enabled') == 0):
								echo __('Yes, i want temporarily <strong>enable</strong> notifications.');
							else:
								echo __('Yes, i want temporarily <strong>disable</strong> notifications.');
							endif;
							?>
						</span>
					</center>

					<div class="padding-left-10 padding-top-10">
						<span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableNotifications">
					<?php echo __('Send'); ?>
				</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Cancel'); ?>
				</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="pingmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Ping remote host');?></h4>
			</div>
			<div class="modal-body">
				<button type="button" id="runPing" target="<?php echo h($host['Host']['address']); ?>" class="btn btn-primary text-center" style="width: 100%;"><?php echo __('Execute ping to:'). ' ' .h($host['Host']['address']); ?></button>
				<br />
				<br />
				<div id="console" style="display:none;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Close'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element('qrmodal'); ?>
