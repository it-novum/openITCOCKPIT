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
<?php

$this->Paginator->options(array('url' => $this->params['named']));
$filter = "/";
foreach($this->params->named as $key => $value){
    $filter.= $key.":".$value."/";
}

?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-desktop fa-fw "></i>
				<?php echo __('Hosts')?>
			<span>>
				<?php echo __('Disabled'); ?>
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
						<?php //echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search')); ?>
						<?php
						echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop);
						?>
					</div>

					<div class="jarviswidget-ctrls" role="menu">

					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Hosts'); ?> </h2>
					<ul class="nav nav-tabs pull-right" id="widget-tab-1">
						<?php if($this->Acl->hasPermission('index')):?>
							<li class="">
								<a href="/hosts/index<?php echo $filter; ?>"><i class="fa fa-stethoscope"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span> </a>
							</li>
						<?php endif;?>
						<?php if($this->Acl->hasPermission('notMonitored')):?>
							<li class="">
								<a href="/hosts/notMonitored<?php echo $filter; ?>"><i class="fa fa-user-md"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
							</li>
						<?php endif;?>
						<li class="active">
							<a href="/hosts/disabled<?php echo $filter; ?>"><i class="fa fa-power-off"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
						</li>
						<?php if($this->Acl->hasPermission('index', 'DeletedHosts')):?>
							<li>
								<a href="/deleted_hosts/index<?php echo $filter; ?>"><i class="fa fa-trash-o"></i> <span class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
							</li>
						<?php endif;?>
					</ul>
				</header>
				<div>
					<div class="widget-body no-padding">
						<?php
						$options = [ 'avoid_cut' => true];
						echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-search"></i> '.__('search'), false, false);
						?>
						<div class="mobile_table">
							<table id="host_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th><?php echo $this->Utils->getDirection($order, 'Host.name'); echo $this->Paginator->sort('Host.name', 'Hostname'); ?></th>
										<th><?php echo $this->Utils->getDirection($order, 'Hosttemplate.name'); echo $this->Paginator->sort('Hosttemplate.name', 'Hosttemplate'); ?></th>
										<th><?php echo $this->Utils->getDirection($order, 'Host.address'); echo $this->Paginator->sort('Host.address', __('Address')); ?></th>
										<th><?php echo $this->Utils->getDirection($order, 'Host.uuid'); echo $this->Paginator->sort('Host.uuid', __('UUID')); ?></th>
										<th class="editItemWidth"><?php echo __('Options'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($disabledHosts as $host): ?>
										<?php
										$hasEditPermission = false;
										if($hasRootPrivileges === true):
											$hasEditPermission = true;
										else:
											if($this->Acl->isWritableContainer(Hash::extract($host, 'Container.{n}.HostsToContainer.container_id'))):
												$hasEditPermission = true;
											endif;
										endif;
										?>
										<tr>
											<td><?php echo $host['Host']['name']; ?></td>
											<td><?php echo $host['Hosttemplate']['name']; ?></td>
											<td><?php echo $host['Host']['address']; ?></td>
											<td><?php echo $host['Host']['uuid']; ?></td>
											<td>
												<div class="btn-group">
													<?php if($this->Acl->hasPermission('edit') && $hasEditPermission):?>
														<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $host['Host']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
													<?php else:?>
														<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
													<?php endif;?>
													<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
													<ul class="dropdown-menu pull-right">
														<?php if($this->Acl->hasPermission('edit') && $hasEditPermission):?>
															<li>
																<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $host['Host']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
															</li>
														<?php endif; ?>
														<?php if($this->Acl->hasPermission('sharing') && $hasEditPermission):?>
															<li>
																<a href="/<?php echo $this->params['controller']; ?>/sharing/<?php echo $host['Host']['id']; ?>"><i class="fa fa-sitemap fa-rotate-270"></i> <?php echo __('Sharing'); ?></a>
															</li>
														<?php endif;?>
														<?php if($this->Acl->hasPermission('enable') && $hasEditPermission):?>
															<li>
																<a href="/<?php echo $this->params['controller']; ?>/enable/<?php echo $host['Host']['id']; ?>"><i class="fa fa-plug"></i> <?php echo __('Enable'); ?></a>
															</li>
														<?php endif; ?>
														<?php if($this->Acl->hasPermission('serviceList', 'services')):?>
															<li>
																<a href="/services/serviceList/<?php echo $host['Host']['id']; ?>"><i class="fa fa-list"></i> <?php echo __('Service list'); ?></a>
															</li>
														<?php endif; ?>
														<?php echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $host['Host']['id']); ?>
														<?php if($this->Acl->hasPermission('delete') && $hasEditPermission):?>
															<li class="divider"></li>
															<li>
																<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'hosts', 'action' => 'delete', $host['Host']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
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
						<?php if(empty($disabledHosts)):?>
							<div class="noMatch">
								<center>
									<span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
								</center>
							</div>
						<?php endif;?>

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
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->


	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->
