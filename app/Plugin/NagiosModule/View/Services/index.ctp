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
			<i class="fa fa-gear fa-fw "></i>
				Nagios
			<span>>
				Services
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
					<div class="jarviswidget-ctrls" role="menu">
						<a data-placement="bottom" title="" rel="tooltip" class="button-icon jarviswidget-fullscreen-btn" href="javascript:void(0);" data-original-title="Fullscreen"><i class="fa fa-resize-full"></i></a>
					</div>
					<span class="widget-icon"> <i class="fa fa-gear"></i> </span>
					<h2>Services </h2>

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
						<table id="datatable_fixed_column" class="table table-striped table-bordered smart-form">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th colspan="4"><?php echo getDirection($order, 'ServiceStatus.current_state'); echo $this->Paginator->sort('ServiceStatus.current_state', 'Servicestatus'); ?></th>
									<th><?php echo getDirection($order, 'Objects.name2'); echo $this->Paginator->sort('Objects.name2', 'Service'); ?></th>
									<th><?php echo getDirection($order, 'Service.display_name'); echo $this->Paginator->sort('display_name', 'UUID'); ?></th>
									<th><?php echo getDirection($order, 'Service.service_object_id'); echo $this->Paginator->sort('host_object_id', 'service_object_id'); ?></th>
									<th><?php echo getDirection($order, 'ServiceStatus.output'); echo $this->Paginator->sort('ServiceStatus.output', 'Output'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$hostNameTmp = '';
									foreach($all_services as $service):
										//$InterfaceStateHost = $HostStatusController->getHumanState($service['HostStatus']['current_state']);
										$InterfaceStateService = $ServiceStatusController->getHumanState($service['ServiceStatus']['current_state']);
										if($hostNameTmp != $service['Objects']['name1']){
											$hostNameTmp = $service['Objects']['name1'];
									?>
									<tr>
										<th ><center><?php //echo $InterfaceStateHost['html_icon']; ?></center></th>
										<th colspan="7"><?php echo $hostNameTmp; ?></th>
									</tr>
									<?php } ?>
									<tr>
										<td style="border-right: none;">&nbsp;</td>
										<td><center><?php echo $InterfaceStateService['html_icon']; ?></center></td>
										<td><center><i class="fa fa-area-chart fa-lg "></i></center></td>
										<td><center><i class="fa fa-arrow-down  fa-lg"></i></center></td>
										<td><a href="/nagios_module/services/browser/<?php echo $service['Service']['service_object_id']; ?>"><?php echo $service['Objects']['name2']; ?></a></td>
										<td><?php echo $service['Service']['display_name']; ?></td>
										<td><?php echo $service['Service']['service_object_id']; ?></td>
										<td><?php echo $service['ServiceStatus']['output']; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php if(empty($all_services)):?>
							<center><?php echo __('search.noVal'); ?></center>
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
<?php
function getDirection($order, $key){
	if(!is_array($order))
		$order = array();

	if(array_key_exists($key, $order)):
		if($order[$key] == 'asc'):
			return '<i class="fa fa-angle-up">&nbsp;</i>';
		endif;
		return '<i class="fa fa-angle-down">&nbsp;</i>';
	endif;
}
?>
