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
			<i class="fa fa-fw fa-user"></i>
				<?php echo __('Administration'); ?>
			<span>>
				<?php echo __('Manage users'); ?>
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
							echo $this->Html->link(__('Create local user'), '/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus'));
							echo " "; //Fix HTML
							if($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap'):
								echo $this->Html->link(__('Import from LDAP'), '/'.$this->params['controller'].'/addFromLdap', array('class' => 'btn btn-xs btn-warning', 'icon' => 'fa fa-plus'));
								echo " "; //Fix HTML
							endif;
						endif;

						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-terminal"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Manage users'); ?></h2>
				</header>

				<!-- widget div-->
				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<div class="tab-content">
							<!-- <form action="/nagios_module/commands/edit/" id="multiEditForm" method="post"> -->
									<div class="mobile_table">
										<table id="datatable_fixed_column" class="table table-striped table-bordered smart-form">
											<thead>
												<tr>
													<?php $order = $this->Paginator->param('order'); ?>
													<th><?php echo $this->Utils->getDirection($order, 'full_name'); echo $this->Paginator->sort('full_name', __('Full name')); ?></th>
													<th><?php echo $this->Utils->getDirection($order, 'email'); echo $this->Paginator->sort('email', __('Email')); ?></th>
													<th><?php echo $this->Utils->getDirection($order, 'company'); echo $this->Paginator->sort('company', __('Company')); ?></th>
													<th><?php echo $this->Utils->getDirection($order, 'Usergroup.name'); echo $this->Paginator->sort('Usergroup.name', __('User role')); ?></th>
													<th><?php echo $this->Utils->getDirection($order, 'status'); echo $this->Paginator->sort('status', __('Status')); ?></th>
													<th class="no-sort text-center" style="width:52px;"><i class="fa fa-gear fa-lg"></i></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($users as $user): ?>
													<?php $allowEdit = $this->Acl->isWritableContainer($userContainerIds[$user['User']['id']]['Container'])?>
													<tr>
														<td><?php echo h($user['User']['full_name']); ?></td>
														<td><?php echo $this->Html->link(h($user['User']['email']), 'mailto:' . $user['User']['email']); ?></td>
														<td><?php echo h($user['User']['company']); ?></td>
														<td><?php echo h($user['Usergroup']['name']); ?></td>
														<td><?php echo h(Status::getDescription($user['User']['status'])); ?></td>
														<?php /*<td class="text-center"><a href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $user['User']['id']; ?>" data-original-title="<?php echo __('Edit'); ?>" data-placement="left" rel="tooltip" data-container="body"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a></td>*/ ?>
														<td class="text-center">
															<div class="btn-group">
																<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
																	<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $user['User']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
																<?php else: ?>
																	<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
																<?php endif; ?>
																<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
																<ul class="dropdown-menu pull-right">
																	<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
																		<li>
																			<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $user['User']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
																		</li>
																	<?php endif; ?>
																	<?php if($user['User']['samaccountname'] == '' || $user['User']['samaccountname'] == null): ?>
																		<!-- This option is only for local users available -->
																		<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
																			<li>
																				<a href="/<?php echo $this->params['controller']; ?>/resetPassword/<?php echo $user['User']['id']; ?>"><i class="fa fa-unlock"></i> <?php echo __('Reset password'); ?></a>
																			</li>
																		<?php endif;?>
																	<?php endif;?>
																	<?php echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $user['User']['id']); ?>
																	<?php if($this->Acl->hasPermission('delete') && $allowEdit): ?>
																		<li class="divider"></li>
																		<li>
																			<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['plugin' => '', 'controller' => 'users', 'action' => 'delete', $user['User']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
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
									<?php if(empty($users)):?>
										<div class="noMatch">
											<center>
												<span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
											</center>
										</div>
									<?php endif;?>
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
