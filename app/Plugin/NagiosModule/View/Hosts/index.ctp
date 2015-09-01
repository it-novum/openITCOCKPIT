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
			<i class="fa fa-desktop fa-fw "></i> 
				Nagios 
			<span>> 
				Hosts
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
						<?php echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search')); ?>
						<?php
						if($isFilter):
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times')); 
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; Graph</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; Passive</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; Hostname</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; IP-Address</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="5"><input type="checkbox" class="pull-left" /> &nbsp; UUID</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="6"><input type="checkbox" class="pull-left" /> &nbsp; host_object_id</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="7"><input type="checkbox" class="pull-left" /> &nbsp; Output</a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="8"><input type="checkbox" class="pull-left" /> &nbsp; Edit</a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon"> <i class="fa fa-desktop"></i> </span>
					<h2>Hosts </h2>

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
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<table id="host_list" class="table table-striped table-bordered smart-form" style="">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'HostStatus.current_state'); echo $this->Paginator->sort('HostStatus.current_state', 'Hoststatus'); ?></th>
									<th class="no-sort text-center" ><i class="fa fa-area-chart fa-lg"></i></th>
									<th class="no-sort text-center" ><i class="fa fa-arrow-down fa-lg"></i></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Objects.name1'); echo $this->Paginator->sort('Objects.name1', 'Hostname'); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Host.address'); echo $this->Paginator->sort('address', 'IP-Address'); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Host.display_name'); echo $this->Paginator->sort('display_name', 'UUID'); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Host.host_object_id'); echo $this->Paginator->sort('host_object_id', 'host_object_id'); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'HostStatus.output'); echo $this->Paginator->sort('HostStatus.output', 'Output'); ?></th>
									<th class="no-sort text-center" ><i class="fa fa-gear fa-lg"></i></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($all_hosts as $host): ?>
									<tr>
										<td class="text-center"><?php echo $this->Status->humanHostStatus($host['HostStatus']['current_state'], '/nagios_module/hosts/browser/'.$host['Host']['host_object_id'])['html_icon']; ?></td>
										<td class="text-center"><i class="fa fa-area-chart fa-lg "></i></td>
										<td class="text-center"><i class="fa fa-arrow-down  fa-lg"></i></td>
										<td><a href="/nagios_module/hosts/browser/<?php echo $host['Host']['host_object_id']; ?>"><?php echo $host['Objects']['name1']; ?></a></td>
										<td><?php echo $host['Host']['address']; ?></td>
										<td><?php echo $host['Host']['display_name']; ?></td>
										<td><?php echo $host['Host']['host_object_id']; ?></td>
										<td><?php echo $host['HostStatus']['output']; ?></td>
										<td class="text-center"><a href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $host['Host']['host_object_id']; ?>" data-original-title="<?php echo __('edit'); ?>" data-placement="left" rel="tooltip" data-container="body"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php if(empty($all_hosts)):?>
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
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->


	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->