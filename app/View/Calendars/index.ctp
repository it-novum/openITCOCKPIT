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
			<i class="fa fa-calendar-o"></i>
				<?php echo __('Calendar'); ?>
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
						if($this->Acl->hasPermission('edit')):
							echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', [
								'class' => 'btn btn-xs btn-success',
								'icon' => 'fa fa-plus'
							]);
						endif;
						?>
					</div>
					<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
					<h2><?php echo __('Calendars'); ?></h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<div class="tab-content">
							<div id="tab1" class="tab-pane fade active in">
								<table id="datatable_fixed_column" class="table table-striped table-bordered smart-form">
									<thead>
										<tr>
											<?php $order = $this->Paginator->param('order'); ?>
											<th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
											<th class="text-center"><i class="fa fa-gear fa-lg"></i>
											<th><?php echo $this->Utils->getDirection($order, 'name'); echo $this->Paginator->sort('name', 'Name'); ?></th>
											<th><?php echo $this->Utils->getDirection($order, 'description'); echo $this->Paginator->sort('description', 'Description'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach($calendars as $calendar):
											$allowEdit = $this->Acl->isWritableContainer($calendar['Calendar']['container_id']);
											?>
											<tr>
												<td class="text-center" style="width: 15px;">
													<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
														<input type="checkbox" class="massChange" calendarname="<?php echo h($calendar['Calendar']['name']); ?>" value="<?php echo $calendar['Calendar']['id']; ?>">
													<?php endif; ?>
												</td>
												<td class="width-50">
													<div class="btn-group">
														<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
															<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $calendar['Calendar']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
														<?php else: ?>
														<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
														<?php endif; ?>
														<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
														<ul class="dropdown-menu">
															<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
																<li>
																	<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $calendar['Calendar']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
																</li>
															<?php endif; ?>
															<?php if($this->Acl->hasPermission('delete') && $allowEdit): ?>
																<li class="divider"></li>
																<li>
																	<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => $this->params['controller'], 'action' => 'delete', $calendar['Calendar']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
																</li>
															<?php endif; ?>
														</ul>
													</div>
												</td>
												<td><?php echo h($calendar['Calendar']['name']); ?></td>
												<td><?php echo h($calendar['Calendar']['description']); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php if(empty($calendars)):?>
									<div class="noMatch">
										<center>
											<span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
										</center>
									</div>
								<?php endif;?>
							</div>
							<div class="padding-top-10"></div>
							<?php echo $this->element('calendar_mass_changes'); ?>
							<div style="padding: 5px 10px;">
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('paginator.showing').' {:page} '.__('of').' {:pages}, '.__('paginator.overall').' {:count} '.__('entries')); ?></div>
									</div>
									<div class="col-sm-6 text-right">
										<div class="dataTables_paginate paging_bootstrap">
											<?php echo $this->Paginator->pagination([
												'ul' => 'pagination'
											]); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- end widget content -->
				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->
		</div>
	</div>
	<!-- end row -->
</section>
<!-- end widget grid -->
