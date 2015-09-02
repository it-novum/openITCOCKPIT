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
<?php //debug($all_serviceescalations); ?>
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-bomb fa-fw"></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Serviceescalations'); ?>
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
							//echo " "; //Fix HTML if search is implemented
						endif;
						
						//TODO: search functionallity
						//echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						
						if($isFilter):
							echo " ";
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="0"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Services'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Ext. services'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Servicegroups'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Ext. servicegroups'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('First'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="5"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Last'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="6"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Interval'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="7"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Timeframe'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="8"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Contacts'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="9"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Contact groups'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="10"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Options'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-bomb"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Serviceescalations'); ?> </h2>

				</header>
				<div>

					<!-- widget content -->
					<div class="widget-body no-padding">
						<div class="mobile_table">
							<table id="serviceescalation_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<th><?php echo __('Services'); ?></th>
										<th><?php echo __('Ext. services'); ?></th>
										<th><?php echo __('Servicegroups'); ?></th>
										<th><?php echo __('Ext. servicegroups'); ?></th>
										<th><?php echo __('First'); ?></th>
										<th><?php echo __('Last'); ?></th>
										<th><?php echo __('Interval'); ?></th>
										<th><?php echo __('Timeframe'); ?></th>
										<th><?php echo __('Contacts'); ?></th>
										<th><?php echo __('Contact groups'); ?></th>
										<th class="no-sort"><?php echo __('Options'); ?></th>
										<th class="no-sort text-center" ><i class="fa fa-gear fa-lg"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($all_serviceescalations as $serviceescalation): ?>
										<?php $allowEdit = $this->Acl->isWritableContainer($serviceescalation['Serviceescalation']['container_id']); ?>
										<tr>
											<td class="txt-color-green">
												<ul class="list-unstyled">
												<?php

													foreach(Hash::extract($serviceescalation, 'ServiceescalationServiceMembership.{n}[excluded=0]') as $service):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'hosts')):
															echo $this->Html->link(
																$service['Service']['Host']['name'],
																[
																	'controller' => 'hosts',
																	'action' => 'edit',
																	$service['Service']['Host']['id']
																],
																['class' => 'txt-color-green', 'escape' => true]
															);
														else:
															echo h($service['Service']['Host']['name']);
														endif;
														echo '/';
														if($this->Acl->hasPermission('edit', 'services')):
															echo $this->Html->link(
																($service['Service']['name'])?$service['Service']['name']:$service['Service']['Servicetemplate']['name'],
																[
																	'controller' => 'services',
																	'action' => 'edit',
																	$service['Service']['id']
																],
																['class' => 'txt-color-green', 'escape' => true]
															);
														else:
															echo h(($service['Service']['name'])?$service['Service']['name']:$service['Service']['Servicetemplate']['name']);
														endif;
														echo '</li>';
													endforeach;
											?>
												</ul>
											</td>
											<td class="txt-color-red">
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($serviceescalation, 'ServiceescalationServiceMembership.{n}[excluded=1]') as $service_exclude):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'hosts')):
															echo $this->Html->link(
																$service_exclude['Service']['Host']['name'],
																[
																	'controller' => 'hosts',
																	'action' => 'edit',
																	$service_exclude['Service']['Host']['id']
																],
																['class' => 'txt-color-red', 'escape' => true]
															);
														else:
															echo h($service_exclude['Service']['Host']['name']);
														endif;
														echo '/';
														if($this->Acl->hasPermission('edit', 'services')):
															echo $this->Html->link(
																($service_exclude['Service']['name'])?$service_exclude['Service']['name']:$service_exclude['Service']['Servicetemplate']['name'],
																[
																	'controller' => 'services',
																	'action' => 'edit',
																	$service_exclude['Service']['id']
																],
																['class' => 'txt-color-red', 'escape' => true]
															);
														else:
															echo h(($service_exclude['Service']['name'])?$service_exclude['Service']['name']:$service_exclude['Service']['Servicetemplate']['name']);
														endif;
														echo '</li>';
													endforeach;
											?>
												</ul>

											</td>
											<td class="txt-color-green">
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($serviceescalation, 'ServiceescalationServicegroupMembership.{n}[excluded=0]') as $servicegroup):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'servicegroups')):
															echo $this->Html->link(
																$servicegroup['Servicegroup']['Container']['name'],
																[
																	'controller' => 'servicegroups',
																	'action' => 'edit',
																	$servicegroup['Servicegroup']['id']
																],
																['class' => 'txt-color-green', 'escape' => true]
															);
														else:
															echo h($servicegroup['Servicegroup']['Container']['name']);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td class="txt-color-red">
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($serviceescalation, 'ServiceescalationServicegroupMembership.{n}[excluded=1]') as $servicegroup_exclude):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'servicegroups')):
															echo $this->Html->link(
																$servicegroup_exclude['Servicegroup']['Container']['name'],
																[
																	'controller' => 'servicegroups',
																	'action' => 'edit',
																	$servicegroup_exclude['Servicegroup']['id']
																],
																['class' => 'txt-color-red', 'escape' => true]
															);
														else:
															echo h($servicegroup_exclude['Servicegroup']['Container']['name']);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td><?php echo $serviceescalation['Serviceescalation']['first_notification']; ?></td>
											<td><?php echo $serviceescalation['Serviceescalation']['last_notification']; ?></td>
											<td><?php echo $serviceescalation['Serviceescalation']['notification_interval']; ?></td>
											<td><?php
												if($this->Acl->hasPermission('edit', 'timeperiods')):
													echo $this->Html->link($serviceescalation['Timeperiod']['name'],
														[
															'controller' => 'timeperiods',
															'action'	=> 'edit',
															$serviceescalation['Timeperiod']['id']
														]
													);
												else:
													echo h($serviceescalation['Timeperiod']['name']);
												endif;
											?>
											</td>
											<td>
												<?php
												foreach(Hash::extract($serviceescalation, 'Contact.{n}') as $contact):
													if($this->Acl->hasPermission('edit', 'contacts')):
														echo $this->Html->link($contact['name'],[
															'controller' => 'contacts',
															'action'	=> 'edit',
															$contact['ContactsToServiceescalation']['contact_id']
															]
														);
													else:
														echo h($contact['name']);
													endif;
													echo '<br />';
												endforeach;
												?></td>
											<td><?php
												foreach(Hash::extract($serviceescalation, 'Contactgroup.{n}') as $contactgroup):
													if($this->Acl->hasPermission('edit', 'contactgroups')):
														echo $this->Html->link($contactgroup['Container']['name'],[
															'controller' => 'contactgroups',
															'action'	 => 'edit',
															$contactgroup['ContactgroupsToServiceescalation']['contactgroup_id']
															]
														);
													else:
														echo h($contactgroup['Container']['name']);
													endif;
												echo '<br />';
												endforeach;
											?>
											</td>
											<td><?php echo __viewServiceescalationOptions($serviceescalation); ?></td>
											<td class="text-center">
												<?php if($this->Acl->hasPermission('edit') && $allowEdit):?>
													<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $serviceescalation['Serviceescalation']['id']; ?>" data-original-title="<?php echo __('edit'); ?>" data-placement="left" rel="tooltip" data-container="body"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
												<?php endif;?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if(empty($all_serviceescalations)):?>
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
				</div>
			</div>
	</div>
</section>
<?php
/**
 * This is a view function and ONLY CALLED IN THIS VIEW!
 *
 * @param array $serviceescalation from find('first')
 * @return string `<i />` HTML object with icons for each options
 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
 * @since 3.0
 *
 */
function __viewServiceescalationOptions($serviceescalation = []){
	$options = ['escalate_on_recovery' => 'txt-color-greenLight', 'escalate_on_warning' => 'txt-color-orange', 'escalate_on_critical' => 'txt-color-redLight', 'escalate_on_unknown' => 'txt-color-blueDark'];
	$class = 'fa fa-square ';
	$html = '';
	foreach($options as $option => $color){
		if(isset($serviceescalation['Serviceescalation'][$option]) && $serviceescalation['Serviceescalation'][$option] == 1){
			$html.='<i class="'.$class.$color.'"></i>&nbsp';
		}
	}
	return $html;
}
