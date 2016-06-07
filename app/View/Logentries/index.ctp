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
<?php $this->Paginator->options(array('url' => Hash::merge($this->params['named'], $ListsettingsUrlParams))); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-file-text-o fa-fw "></i>
				<?php echo __('Logentries'); ?>
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
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="0"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Date'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Type'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Logentry'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>

					<div id="switch-1" class="widget-toolbar" role="menu">
						<?php
						echo $this->Form->create('logentries', [
							'class' => 'form-horizontal clear',
							'url' => 'index' // removes everything out of the URL
						]);

						?>

							<div class="btn-group">
								<?php
									$listoptions = [
										'5' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 5,
											'human' => 5,
											'selector' => '#listoptions_limit'
										],
										'10' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 10,
											'human' => 10,
											'selector' => '#listoptions_limit'
										],
										'25' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 25,
											'human' => 25,
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
										'150' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 150,
											'human' => 150,
											'selector' => '#listoptions_limit'
										],
										'500' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 500,
											'human' => 500,
											'selector' => '#listoptions_limit'
										],
										'1000' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 1000,
											'human' => 1000,
											'selector' => '#listoptions_limit'
										]
									];
								
									$selected = $paginatorLimit;

									if(isset($ListsettingsUrlParams['Listsettings']['limit'])){
										$selected = $ListsettingsUrlParams['Listsettings']['limit'];
									}
								?>
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<span id="listoptions_limit"><?php echo $selected; ?></span> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php

									foreach($listoptions as $listoption): ?>
										<li>
											<a href="javascript:void(0);" class="listoptions_action" selector="<?php echo $listoption['selector']; ?>" submit_target="<?php echo $listoption['submit_target']; ?>" value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" value="<?php echo $selected; ?>" id="listoptions_hidden_limit" name="data[Listsettings][limit]" />
							</div>
							<div class="btn-group">
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<?php echo __('Options'); ?> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php
									foreach($logentry_types as $logentry_type => $logentry_name):
										$htmlChecked = 'checked="checked"';
										if(isset($ListsettingsUrlParams['Listsettings']['logentry_type'])):
											$htmlChecked = '';
											if($ListsettingsUrlParams['Listsettings']['logentry_type'] & $logentry_type):
												$htmlChecked = 'checked="checked"';
											endif;
										endif;
										?>
										<li>
											<input type="hidden" value="0" name="data[Listsettings][logentry_type][<?php echo $logentry_type; ?>]" />
											<li style="width: 100%;"><a href="javascript:void(0)" class="listoptions_checkbox text-left"><input type="checkbox" name="data[Listsettings][logentry_type][<?php echo $logentry_type; ?>]" value="<?php echo $logentry_type; ?>" <?php echo $htmlChecked; ?>/> &nbsp; <?php echo $logentry_name; ?></a></li>
										</li>
									<?php endforeach; ?>
									<li class="divider"></li>
										<li style="width: 100%;"><a href="javascript:void(0)" class="tick_all text-left"><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;<?php echo __('Tick all');?></a></li>
										<li style="width: 100%;"><a href="javascript:void(0)" class="untick_all text-left"><i class="fa fa-square-o"></i>&nbsp;&nbsp;<?php echo __('Untick all');?></a></li>
								</ul>
							</div>
							<button class="btn btn-xs btn-success toggle"><i class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

						<?php
						 echo $this->Form->end();
						 ?>
				 	</div>

					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-file-text-o"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Logentries');?> </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget content -->
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, array(), '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
						<div class="mobile_table">
							<table id="host_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<?php $order = $this->Paginator->param('order'); ?>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Logentry.logentry_time'); echo $this->Paginator->sort('logentry_time', __('Date')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Logentry.logentry_type'); echo $this->Paginator->sort('logentry_type', __('Type')); ?></th>
										<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Logentry.logentry_data'); echo $this->Paginator->sort('logentry_data', __('Logentry')); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($all_logentries as $logentry): ?>
										<tr>
											<td><?php echo $logentry['Logentry']['logentry_time']; ?></td>
											<td><?php
												//Bloody nagios fix -.-
												switch($logentry['Logentry']['logentry_type']):
													case 514:
														echo __('External command failed');
														break;
													case 6:
														echo __('Timeperiod transition');
														break;
													default:
														echo $logentry_types[$logentry['Logentry']['logentry_type']];
														break;
												endswitch;
												?>
											</td>
											<td><?php echo $this->Uuid->replaceUuids($logentry['Logentry']['logentry_data']); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if(empty($all_logentries)):?>
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