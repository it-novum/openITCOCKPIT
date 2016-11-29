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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-terminal fa-fw "></i>
				<?php echo __('Basic Monitoring'); ?>
			<span>>
				<?php echo __('Commands'); ?>
			</span>
		</h1>
	</div>
</div>

<section id="widget-grid" class="">

	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="widget-toolbar" role="menu">
						<?php
						if($this->Acl->hasPermission('add')):
							echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus'));
							echo " "; //Hix HTML
						endif;
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-terminal"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Commands'); ?></h2>
					<ul class="nav nav-tabs pull-left padding-left-20" id="widget-tab-1">
						<li class="active">
							<a href="<?php echo Router::url(['action' => 'index']); ?>"> <i class="fa fa-lg fa-code"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Commands'); ?></span> </a>
						</li>
						<?php if($this->Acl->hasPermission('hostchecks')): ?>
							<li class="">
								<a href="<?php echo Router::url(['action' => 'hostchecks']); ?>"> <i class="fa fa-lg fa-code"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Hostchecks'); ?></span> </a>
							</li>
						<?php endif; ?>
						<?php if($this->Acl->hasPermission('notifications')): ?>
							<li class="">
								<a href="<?php echo Router::url(['action' => 'notifications']); ?>"> <i class="fa fa-lg fa-envelope-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Notifications'); ?> </span></a>
							</li>
						<?php endif; ?>
						<?php if($this->Acl->hasPermission('handler')): ?>
							<li class="">
								<a href="<?php echo Router::url(['action' => 'handler']); ?>"> <i class="fa fa-lg fa-code-fork"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Event handler'); ?> </span></a>
							</li>
						<?php endif;?>
					</ul>
				</header>

				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
									<div class="mobile_table">
										<table id="datatable_fixed_column" class="table table-striped table-bordered smart-form">
											<thead>
												<tr>
													<?php $order = $this->Paginator->param('order'); ?>
													<th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
													<th><?php echo $this->Utils->getDirection($order, 'Command.name'); echo $this->Paginator->sort('Command.name', 'Command name'); ?></th>
													<th class="no-sort text-center" style="width:52px;"><i class="fa fa-gear fa-lg"></i></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($all_commands as $command):
													?>
													<tr>
														<td class="text-center" style="width:15px;">
															<input type="checkbox" class="massChange" commandname="<?php echo h($command['Command']['name']); ?>" value="<?php echo $command['Command']['id']; ?>">
														</td>
														<td><?php echo h($command['Command']['name']); ?></td>
														<td>
															<div class="btn-group">
																<?php if($this->Acl->hasPermission('edit')): ?>
																	<a href="<?php echo Router::url(['action' => 'edit', $command['Command']['id']]); ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
																<?php else: ?>
																	<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
																<?php endif;?>
																<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
																<ul class="dropdown-menu pull-right">
																	<?php if($this->Acl->hasPermission('edit')): ?>
																		<li>
																			<a href="<?php echo Router::url(['action' => 'edit', $command['Command']['id']]); ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
																		</li>
																	<?php endif;?>
																	<?php if($this->Acl->hasPermission('usedBy')): ?>
																		<li>
																			<a href="/<?php echo $this->params['controller']; ?>/usedBy/<?php echo $command['Command']['id']; ?>"><i class="fa fa-reply-all fa-flip-horizontal"></i> <?php echo __('Used by'); ?></a>
																		</li>
																	<?php endif; ?>
																	<?php if($this->Acl->hasPermission('delete')): ?>
																		<li class="divider"></li>
																		<li>
																			<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'commands', 'action' => 'delete', $command['Command']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
																		</li>
																	<?php endif; ?>
																</ul>
															</div>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
									<?php if(empty($all_commands)):?>
										<div class="noMatch">
											<center>
												<span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
											</center>
										</div>
									<?php endif;?>
							<div class="padding-top-10"></div>
							<?php echo $this->element('command_mass_changes'); ?>
						</div>

						<div style="padding: 5px 10px;">
							<div class="row">
								<div class="col-sm-6">
									<div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page').' {:page} '.__('of').' {:pages}, '.__('Total').' {:count} '.__('entries')); ?></div>
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
