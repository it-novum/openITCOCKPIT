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
			<i class="fa fa-home fa-fw "></i>
				<?php echo __('System'); ?>
			<span>>
				<?php echo __('Tenants'); ?>
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
						//You need root privileges to create a new tenant
						if($this->Acl->hasPermission('add') && $hasRootPrivileges === true):
							echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus'));
							echo " "; //Fix HTML
						endif;
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-home"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Tenants'); ?></h2>

				</header>
				<div>
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<div class="tab-content">
							<div class="mobile_table">
									<table id="datatable_fixed_column" class="table table-striped table-bordered smart-form">
										<thead>
											<tr>
												<?php $order = $this->Paginator->param('order'); ?>
												<th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
												<th ><?php echo $this->Utils->getDirection($order, 'Tenant.name'); echo $this->Paginator->sort('Tenant.name', __('Tenant_name')); ?></th>
												<th><?php echo $this->Utils->getDirection($order, 'Tenant.description'); echo $this->Paginator->sort('Tenant.description', __('Description')); ?></th>
												<th><?php echo $this->Utils->getDirection($order, 'Tenant.is_active'); echo $this->Paginator->sort('Tenant.is_active', __('Is active')); ?></th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($all_tenants as $tenant):
												$allowEdit = $this->Acl->isWritableContainer($tenant['Container']['id']);
												?>
												<tr>
													<td>
														<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
															<input class="massChange" type="checkbox" name="tenant[<?php echo $tenant['Tenant']['id']; ?>]" tenantname="<?php echo h($tenant['Container']['name']); ?>" value="<?php echo $tenant['Tenant']['id']; ?>" />
														<?php endif; ?>
													</td>
													<td><?php echo $tenant['Container']['name'] ?></td>
													<td><?php echo $tenant['Tenant']['description'] ?></td>
													<td>
														<center>
															<?php if($tenant['Tenant']['is_active'] == 1): ?>
																<i class="fa fa-check fa-lg txt-color-green"></i>
															<?php else: ?>
																<i class="fa fa-power-off fa-lg txt-color-red"></i>
															<?php endif;?>
														</center>
													</td>
													<td class="text-center">
														<?php if($this->Acl->hasPermission('edit') && $allowEdit): ?>
															<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $tenant['Tenant']['id']; ?>" data-original-title="<?php echo __('edit'); ?>" data-placement="left" rel="tooltip" data-container="body"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
							</div>
									<?php if(empty($all_tenants)):?>
										<div class="noMatch">
											<center>
												<span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
											</center>
										</div>
									<?php endif;?>
								</div>
						</div>

						<?php echo $this->element('tenant_mass_changes'); ?>

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
