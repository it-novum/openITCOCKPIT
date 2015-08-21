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
			<i class="fa fa-map-marker fa-fw "></i> 
				<?php echo __('Map'); ?>
			<span>> 
				<?php echo __('Overview'); ?>
			</span>
		</h1>
	</div>
</div>

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<div class="widget-toolbar" role="menu">
						<?php echo $this->Html->link(__('New'), '/'.$this->params['plugin'].'/'.$this->params['controller'].'/add', array('class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus')); ?>
						<?php echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search')); ?>
						<?php
						if($isFilter):
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Maps </h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body no-padding">
						<?php  echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<table id="contactgroup_list" class="table table-striped table-bordered smart-form" style="">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th class="no-sort"></th>
									<th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Map.name'); echo $this->Paginator->sort('Map.name', 'Map name'); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Map.title'); echo $this->Paginator->sort('Map.title', 'Map title'); ?></th>
									<th class="no-sort">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($all_maps as $map): ?>
									<tr>
										<td class="text-center"><input class="multiselect" type="checkbox" name="map[<?php echo $map['Map']['id']; ?>]" /></td>
										<td><?php echo $map['Map']['name']; ?></td>
										<td><?php echo $map['Map']['title']?></td>
										<td>
											<div class="btn-group">
												<a href="/<?php echo $this->params['plugin'].'/'.$this->params['controller']; ?>/edit/<?php echo $map['Map']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
												<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
												<ul class="dropdown-menu">
													<li>
														<a href="/<?php echo $this->params['plugin'].'/'.$this->params['controller']; ?>/edit/<?php echo $map['Map']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
													</li>
													<li>
														<a href="/<?php echo $this->params['plugin'].'/mapeditors'; ?>/edit/<?php echo $map['Map']['id']; ?>"><i class="fa fa-edit"></i> <?php echo __('Edit in Map editor'); ?></a>
													</li>
													<li class="divider"></li>
													<li>
														<a href="/<?php echo $this->params['plugin'].'/mapeditors'; ?>/view/<?php echo $map['Map']['id']; ?>"><i class="fa fa-eye"></i> <?php echo __('View'); ?></a>
													</li>
													<li>
														<a href="<?php echo Router::url(['controller' => 'mapeditors', 'action' => 'view', 'plugin' => 'map_module', 'fullscreen' => 1, $map['Map']['id']]); ?>"><i class="glyphicon glyphicon-resize-full"></i> <?php echo __('View in fullscreen'); ?></a>
													</li>
													<li class="divider"></li>
													<li>
														<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'maps', 'action' => 'delete', $map['Map']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
													</li>
												</ul>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
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
				<!-- end widget div -->
			</div>
			<!-- end widget -->
	</div>
	<!-- end row -->
</section>
<!-- end widget grid -->