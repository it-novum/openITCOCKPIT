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
<?php $this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $DowntimeListsettings])]); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-power-off fa-fw "></i>
				<?php echo __('Downtimes'); ?>
			<span>>
				<?php echo __('Services'); ?>
			</span>
		</h1>
	</div>
</div>

<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
	<a href="#" data-dismiss="alert" class="close">×</a>
	<h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
	<?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="widget-toolbar" role="menu">
						<div class="btn-group">
							<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-success">
								<span ><i class="fa fa-plus"></i> <?php echo __('Create downtime'); ?></span> <i class="fa fa-caret-down"></i>
							</button>
							<ul class="dropdown-menu pull-right">
								<?php if($this->Acl->hasPermission('addHostdowntime', 'systemdowntimes')):?>
									<li>
										<a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostdowntime']); ?>"><?php echo __('Create host downtime'); ?></a>
									</li>
								<?php endif; ?>
								<?php if($this->Acl->hasPermission('addHostdowntime', 'systemdowntimes')):?>
									<li>
										<a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostgroupdowntime']); ?>"><?php echo __('Create hostgroup downtime'); ?></a>
									</li>
								<?php endif;?>
								<?php if($this->Acl->hasPermission('addServicedowntime', 'systemdowntimes')):?>
									<li>
										<a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addServicedowntime']); ?>"><?php echo __('Create service downtime'); ?></a>
									</li>
								<?php endif;?>
							</ul>
						</div>
						<?php echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search')); ?>
						<?php
						if($isFilter):
							echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Host'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Service'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('User'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Comment'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="5"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Created'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="6"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Start'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="7"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('End'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="8"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Duration'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="9"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Was cancelled'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>

					<div id="switch-1" class="widget-toolbar" role="menu">
						<?php
						echo $this->Form->create('downtimes', [
							'class' => 'form-horizontal clear',
							'url' => 'service' //reset the URL on submit
						]);

						?>

						<div class="widget-toolbar pull-left hidden-mobile" role="menu">
								<span style="line-height: 32px;" class="pull-left"><?php echo __('From:');?></span>
								<input class="form-control text-center pull-left margin-left-10" style="width: 78%;" type="text" maxlength="255" value="<?php echo $DowntimeListsettings['from']; ?>" name="data[Listsettings][from]">
						</div>

						<div class="widget-toolbar pull-left hidden-mobile" role="menu">
								<span style="line-height: 32px;" class="pull-left"><?php echo __('To:');?></span>
								<input class="form-control text-center pull-left margin-left-10" style="width: 85%;" type="text" maxlength="255" value="<?php echo $DowntimeListsettings['to']; ?>" name="data[Listsettings][to]">
						</div>

							<div class="btn-group">
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<span id="listoptions_view" class="hidden-mobile" ><?php echo __('Service downtimes'); ?></span> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php if($this->Acl->hasPermission('host', 'downtimes')):?>
										<li>
											<a href="<?php echo Router::url(['controller' => 'downtimes', 'action' => 'host']); ?>"><?php echo __('Host downtimes');?></a>
										</li>
									<?php endif;?>
									<?php if($this->Acl->hasPermission('service', 'downtimes')):?>
										<li>
											<a href="<?php echo Router::url(['controller' => 'downtimes', 'action' => 'service']); ?>"><?php echo __('Service downtimes');?></a>
										</li>
									<?php endif;?>
									<?php if($this->Acl->hasPermission('index', 'systemdowntimes')):?>
										<li>
											<a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'index']); ?>"><?php echo __('Recurring downtimes');?></a>
										</li>
									<?php endif;?>
								</ul>
							</div>

							<div class="btn-group hidden-mobile">
								<?php
									$listoptions = [
										'30' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 30,
											'human' => 30,
											'selector' => '#listoptions_limit'
										],
										'50' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 50,
											'human' => 50,
											'selector' => '#listoptions_limit'
										],
										'100' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 100,
											'human' => 100,
											'selector' => '#listoptions_limit'
										],
										'300' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 300,
											'human' => 300,
											'selector' => '#listoptions_limit'
										]
									];

									$selected = 30;
									if(isset($DowntimeListsettings['limit']) && isset($listoptions[$DowntimeListsettings['limit']]['human'])){
										$selected = $listoptions[$DowntimeListsettings['limit']]['human'];
									}
								?>
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<span id="listoptions_limit"><?php echo $selected; ?></span> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php foreach($listoptions as $listoption): ?>
										<li>
											<a href="javascript:void(0);" class="listoptions_action" selector="<?php echo $listoption['selector']; ?>" submit_target="<?php echo $listoption['submit_target']; ?>" value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" value="<?php if(isset($DowntimeListsettings['limit'])): echo $DowntimeListsettings['limit']; endif; ?>" id="listoptions_hidden_limit" name="data[Listsettings][limit]" />
							</div>


							<?php
							$checked = '';
							if(isset($DowntimeListsettings['hide_expired']) && $DowntimeListsettings['hide_expired'] == 1):
								$checked = 'checked="checked"';
							endif;
							?>
							<div class="btn-group hidden-mobile">
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<?php echo __('Options'); ?> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<li>
										<input type="hidden" value="0" name="data[Listsettings][hide_expired]" />
										<li style="width: 100%;"><a href="javascript:void(0)" class="listoptions_checkbox text-left"><input type="checkbox" name="data[Listsettings][hide_expired]" value="1" <?php echo $checked; ?>/> &nbsp; <?php echo __('Hide expired'); ?></a></li>
									</li>
								</ul>
							</div>

							<button class="btn btn-xs btn-success toggle hidden-mobile"><i class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

						<?php
						 echo $this->Form->end();
						 ?>
 				 	</div>

					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-power-off"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Service downtimes');?> </h2>

				</header>
				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $DowntimeListsettings])), 'merge' => false]], '<i class="fa fa-search"></i> '.__('Search'), false, false); ?>
						<div class="mobile_table">
							<table id="servicedowntimes_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th class="no-sort"><?php echo __('Running'); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Host.name'); echo $this->Paginator->sort('Host.name', __('Host')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.name'); echo $this->Paginator->sort('Service.name', __('Service')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.author_name'); echo $this->Paginator->sort('Downtime.author_name', __('User')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.comment_data'); echo $this->Paginator->sort('Downtime.comment_data', __('Comment')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.entry_time'); echo $this->Paginator->sort('Downtime.entry_time', __('Created')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.scheduled_start_time'); echo $this->Paginator->sort('Downtime.scheduled_start_time', __('Start')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.scheduled_end_time'); echo $this->Paginator->sort('Downtime.scheduled_end_time', __('End')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.duration'); echo $this->Paginator->sort('Downtime.duration', __('Duration')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Downtime.was_cancelled'); echo $this->Paginator->sort('Downtime.was_cancelled', __('Was cancelled')); ?></th>
										<th class="no-sort"><?php echo __('Delete'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($all_downtimes as $downtime): ?>
										<tr>
											<td class="text-center"><?php echo $this->Monitoring->isDowntimeRunning($downtime['Downtime']['was_started'], $downtime['Downtime']['scheduled_end_time'], $downtime['Downtime']['was_cancelled'])['html']; ?></td>
											<td>
												<?php
												if($downtime['Host']['id'] != null): ?>
													<?php if($this->Acl->hasPermission('browser', 'hosts')): ?>
														<a href="/Hosts/browser/<?php echo $downtime['Host']['id']; ?>"><?php echo $downtime['Host']['name']; ?></a>
													<?php else: ?>
														<?php echo h($downtime['Host']['name']); ?>
													<?php endif; ?>
												<?php else: ?>
													<span class="muted italic"><?php echo __('Host deleted'); ?></span>
												<?php endif; ?>
											</td>
											<td>
												<?php
												if($downtime['Service']['id'] != null):
													$serviceName = $downtime['Service']['name'];
													if($serviceName === null || $serviceName === ''):
														$serviceName = $downtime['Servicetemplate']['name'];
													endif;
													if($this->Acl->hasPermission('browser', 'services')): ?>
														<a href="/services/browser/<?php echo $downtime['Service']['id']; ?>"><?php echo h($serviceName);?></a>
													<?php else:?>
														<?php echo h($serviceName); ?>
													<?php endif;?>
												<?php else: ?>
													<span class="muted italic"><?php echo __('Host deleted'); ?></span>
												<?php endif; ?>
											</td>
											<td><?php echo $downtime['Downtime']['author_name']; ?></td>
											<td><?php echo $downtime['Downtime']['comment_data']; ?></td>
											<td><?php echo $this->Time->format($downtime['Downtime']['entry_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
											<td><?php echo $this->Time->format($downtime['Downtime']['scheduled_start_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
											<td><?php echo $this->Time->format($downtime['Downtime']['scheduled_end_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
											<td><?php echo $this->Utils->secondsInHuman($downtime['Downtime']['duration']); ?></td>
											<td>
												<?php
												if($downtime['Downtime']['was_cancelled'] == 0):
													echo __('No');
												else:
													echo __('Yes');
												endif;
												?>
											</td>
											<td class="text-center">
												<?php if(strtotime($downtime['Downtime']['scheduled_end_time']) > time() && $downtime['Downtime']['was_cancelled'] == 0): ?>
													<?php if($this->Acl->hasPermission('edit', 'services')): ?>
														<a class="btn btn-danger btn-xs delete_downtime" href="javascript:void(0)" internal-downtime-id="<?php echo h($downtime['Downtime']['internal_downtime_id']); ?>" downtimehistory-id="<?php echo h($downtime['Downtime']['downtimehistory_id']); ?>"><i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?></a>
													<?php endif;?>
												<?php endif;?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if(empty($all_downtimes)):?>
							<div class="noMatch">
								<center>
									<span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
								</center>
							</div>
						<?php endif;?>

						<div style="padding: 5px 10px;">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('paginator.showing').' {:page} '.__('of').' {:pages}, '.__('paginator.overall').' {:count} '.__('entries')); ?></div>
								</div>
								<div class="col-sm-6 text-right">
									<div class="dataTables_paginate paging_bootstrap">
										<?php echo $this->Paginator->pagination(array(
											'ul' => 'pagination'
										)); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</section>

