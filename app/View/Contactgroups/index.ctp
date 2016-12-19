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
<?php //debug($all_contactgroups); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-users fa-fw "></i>
				<?php echo __('Basic Monitoring'); ?>
			<span>>
				<?php echo __('Contact Groups'); ?>
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
							echo " "; //Fix HTML
						endif;
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML;
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Contactgroup name');?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Description');?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Contacts');?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-users"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Contactgroups'); ?> </h2>
				</header>
				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<div class="mobile_table">
							<table id="contactgroup_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
										<th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Container.name'); echo $this->Paginator->sort('Container.name', __('Contactgroup name')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Contacts.description'); echo $this->Paginator->sort('Contactgroup.description', __('Description')); ?></th>
										<th class="no-sort"><?php echo __('Contacts'); ?></th>
										<th class="no-sort text-center" style="width:52px"><i class="fa fa-gear fa-lg"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($all_contactgroups as $contactgroup): ?>
										<?php $allowEdit = $this->Acl->isWritableContainer($contactgroup['Container']['parent_id']); ?>
										<tr>
											<td class="text-center" style="width: 15px;">
												<?php if($this->Acl->hasPermission('edit') && $allowEdit):?>
													<input class="massChange" type="checkbox" name="contactgroup[<?php echo $contactgroup['Contactgroup']['id']; ?>]" contactgroupname="<?php echo h($contactgroup['Container']['name']); ?>" value="<?php echo $contactgroup['Contactgroup']['id']; ?>" />
												<?php endif;?>
											</td>
											<td><?php echo $contactgroup['Container']['name']; ?></td>
											<td><?php echo $contactgroup['Contactgroup']['description']; ?></td>
											<td>
												<ul class="list-unstyled">
												<?php
													foreach(Set::combine(Set::sort($contactgroup['Contact'], '{n}.name', 'asc'), '{n}.id', '{n}.name') as $contactId => $contactName):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'contacts')): ?>
														<a href="/contacts/edit/<?= $contactId ?>"><?php echo h($contactName);?></a>
														<?php
														else:
															echo h($contactName);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td>
												<div class="btn-group">
													<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
														<a href="<?php echo Router::url(['action' => 'edit', $contactgroup['Contactgroup']['id']]); ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
													<?php else: ?>
														<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
													<?php endif; ?>
													<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
													<ul class="dropdown-menu pull-right">
														<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
															<li>
																<a href="<?php echo Router::url(['action' => 'edit', $contactgroup['Contactgroup']['id']]); ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
															</li>
														<?php endif; ?>
														<?php if($this->Acl->hasPermission('delete') && $allowEdit): ?>
															<li class="divider"></li>
															<li>
																<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'contactgroups', 'action' => 'delete', $contactgroup['Contactgroup']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
															</li>
														<?php endif;?>
													</ul>
												</div>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

						<?php echo $this->element('contactgroup_mass_changes')?>

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
