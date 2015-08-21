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
<?php $this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $StatehistoryListsettings])]); ?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
		<h1 class="page-title <?php echo $this->Status->ServiceStatusColor($service['Service']['uuid']); ?>">
			<?php echo $this->Monitoring->serviceFlappingIcon($this->Status->sget($service['Service']['uuid'], 'is_flapping'), 'padding-left-5'); ?>
			<i class="fa fa-cog fa-fw"></i>
				<?php
				if($service['Service']['name'] !== null && $service['Service']['name'] !== ''){
					echo $service['Service']['name'];
				}else{
					echo $service['Servicetemplate']['name'];
				}
				?><span>
				&nbsp;<?php echo __('on'); ?>&nbsp;
				<a href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo $service['Host']['name']; ?> (<?php echo $service['Host']['address']; ?>)</a>
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
		<h5>
			<div class="pull-right">
				<a href="/services/browser/<?php echo $service['Service']['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Service')); ?></a>
				<?php echo $this->element('service_browser_menu'); ?>
			</div>
		</h5>
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
							echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']); 
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right color-select pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="0"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('State'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Date'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Check attempt'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Sate type'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Service output'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					 
					<div id="switch-1" class="widget-toolbar" role="menu">
						<?php
						echo $this->Form->create('statehistories', [
							'class' => 'form-horizontal clear',
							'action' => 'service/'.$service['Service']['id'] //reset the URL on submit
						]);
						
 						?>
						
						<div class="widget-toolbar pull-left" role="menu">
								<span style="line-height: 32px;" class="pull-left"><?php echo __('From:');?></span>
								<input class="form-control text-center pull-left margin-left-10" style="width: 78%;" type="text" maxlength="255" value="<?php echo $StatehistoryListsettings['from']; ?>" name="data[Listsettings][from]">
						</div>
						
						<div class="widget-toolbar pull-left" role="menu">
								<span style="line-height: 32px;" class="pull-left"><?php echo __('To:');?></span>
								<input class="form-control text-center pull-left margin-left-10" style="width: 85%;" type="text" maxlength="255" value="<?php echo $StatehistoryListsettings['to']; ?>" name="data[Listsettings][to]">
						</div>
							
							<div class="btn-group">
								<?php
									$listoptions = [
										'30' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 30,
											'human' => 30,
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
										'300' => [
											'submit_target' => '#listoptions_hidden_limit',
											'value' => 300,
											'human' => 300,
											'selector' => '#listoptions_limit'
										]
									];
								
									$selected = 30;
									if(isset($StatehistoryListsettings['limit']) && isset($listoptions[$StatehistoryListsettings['limit']]['human'])){
										$selected = $listoptions[$StatehistoryListsettings['limit']]['human'];
									}
								?>
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<span id="listoptions_limit"><?php echo $selected; ?></span> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php foreach($listoptions as $listoption): ?>
										<li>
											<a href="javascript:void(0);" class="listoptions_action" selector="<?php echo $listoption['selector']; ?>" submit_target="<?php echo $listoption['submit_target']; ?>" value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" value="<?php if(isset($StatehistoryListsettings['limit'])): echo $StatehistoryListsettings['limit']; endif; ?>" id="listoptions_hidden_limit" name="data[Listsettings][limit]" />
							</div>


							<?php
							$state_types = [
								'recovery' => __('Recovery'),
								'warning' => __('Warning'),
								'critical' => __('Critical'),
								'unknown' => __('Unknown')
							];
							?>
							
							<div class="btn-group">
								<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
									<?php echo __('State types'); ?> <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									<?php
									foreach($state_types as $state_type => $name):
										$checked = '';
										if(isset($StatehistoryListsettings['state_types'][$state_type]) && $StatehistoryListsettings['state_types'][$state_type] == 1):
											$checked = 'checked="checked"';
										endif;
										?>
										<li>
											<input type="hidden" value="0" name="data[Listsettings][state_types][<?php echo $state_type; ?>]" /> 
											<li style="width: 100%;"><a href="javascript:void(0)" class="listoptions_checkbox text-left"><input type="checkbox" name="data[Listsettings][state_types][<?php echo $state_type; ?>]" value="1" <?php echo $checked; ?>/> &nbsp; <?php echo $name; ?></a></li>
										</li>
									<?php endforeach?>
									<li class="divider"></li>
									
									<?php
									$nag_service_state_types = [
										'soft' => __('Soft'),
										'hard' => __('Hard')
									];
									
									foreach($nag_service_state_types as $state_type => $name):
										$checked = '';
										if(isset($StatehistoryListsettings['nag_state_types'][$state_type]) && $StatehistoryListsettings['nag_state_types'][$state_type] == 1):
											$checked = 'checked="checked"';
										endif;
										?>
										<li>
											<input type="hidden" value="0" name="data[Listsettings][nag_state_types][<?php echo $state_type; ?>]" /> 
											<li style="width: 100%;"><a href="javascript:void(0)" class="listoptions_checkbox text-left"><input type="checkbox" name="data[Listsettings][nag_state_types][<?php echo $state_type; ?>]" value="1" <?php echo $checked; ?>/> &nbsp; <?php echo $name; ?></a></li>
										</li>
									<?php endforeach?>
								</ul>
							</div>
							
							<button class="btn btn-xs btn-success toggle"><i class="fa fa-check"></i> <?php echo __('Apply'); ?></button>
					
						<?php
						 echo $this->Form->end();
						 ?>
 				 	</div>
					 
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon"> <i class="fa fa-history"></i> </span>
					<h2><?php echo __('State history');?> </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget content -->
					<div class="widget-body no-padding">
						<?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $StatehistoryListsettings])), 'merge' => false]], '<i class="fa fa-search"></i> '.__('Search'), false, false); ?>
						
						<table id="servicestatehistory_list" class="table table-striped table-bordered smart-form" style="">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Statehistory.state'); echo $this->Paginator->sort('Statehistory.state', __('State')); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Statehistory.state_time'); echo $this->Paginator->sort('Statehistory.state_time', __('Date')); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Statehistory.current_check_attempt'); echo $this->Paginator->sort('Statehistory.current_check_attempt', __('Check attempt')); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Statehistory.state_type'); echo $this->Paginator->sort('Statehistory.state_type', __('State type')); ?></th>
									<th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Statehistory.output'); echo $this->Paginator->sort('Statehistory.output', __('Service output')); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php //debug($all_notification); ?>
								<?php foreach($all_statehistories as $statehistory): ?>
									<tr>
										<td class="text-center"><?php echo $this->Status->humanServiceStatus($service['Service']['uuid'], 'javascript:void(0)', [$service['Service']['uuid'] => ['Servicestatus' => ['current_state' => $statehistory['Statehistory']['state']]]])['html_icon']; ?></td>
										<td><?php echo h($this->Time->format($statehistory['Statehistory']['state_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
										<td class="text-center"><?php echo h($statehistory['Statehistory']['current_check_attempt']);?>/<?php echo h($statehistory['Statehistory']['max_check_attempts']);?></td>
										<td class="text-center"><?php echo h($this->Status->humanServiceStateType($statehistory['Statehistory']['state_type'])); ?></td>
										<td><?php echo h($statehistory['Statehistory']['output']); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php if(empty($all_statehistories)):?>
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