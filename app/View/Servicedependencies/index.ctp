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
			<i class="fa fa-sitemap fa-fw"></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Servicedependencies'); ?>
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
						// TODO implement search
						//echo $this->Html->link(__('Search'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search'));
						if($isFilter):
							echo " "; //Fix HTML
							echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
						endif;
						?>
						</div>
						<div class="widget-toolbar" role="menu">
						<a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i class="fa fa-lg fa-table"></i></a>
						<ul class="dropdown-menu arrow-box-up-right pull-right">
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="0"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Services'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="1"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Dependent services'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="2"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Servicegroups'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="3"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Dependent servicegroups'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="4"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Dependency period'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="5"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Inherits parent'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="6"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Execution failure criteria'); ?></a></li>
							<li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left" class="select_datatable text-left" my-column="7"><input type="checkbox" class="pull-left" /> &nbsp; <?php echo __('Notification failure criteria'); ?></a></li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="jarviswidget-ctrls" role="menu">
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-sitemap"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Servicedependencies'); ?> </h2>

				</header>
				<div>

					<!-- widget content -->
					<div class="widget-body no-padding">
						<div class="mobile_table">
							<table id="servicedependency_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<th class="no-sort"><?php echo __('Services'); ?></th>
										<th class="no-sort"><?php echo __('Dependent services'); ?></th>
										<th class="no-sort"><?php echo __('Servicegroups'); ?></th>
										<th class="no-sort"><?php echo __('Dependent servicegroups'); ?></th>
										<th class="no-sort"><?php echo __('Dependency period'); ?></th>
										<th class="no-sort"><?php echo __('Inherits parent'); ?></th>
										<th class="no-sort"><?php echo __('Execution failure criteria'); ?></th>
										<th class="no-sort"><?php echo __('Notification failure criteria'); ?></th>
										<th class="no-sort text-center" ><i class="fa fa-gear fa-lg"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($all_servicedependencies as $servicedependency): ?>
										<?php $allowEdit = $this->Acl->isWritableContainer($servicedependency['Servicedependency']['container_id']); ?>
										<tr>
											<td>
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($servicedependency, 'ServicedependencyServiceMembership.{n}[dependent=0]') as $service):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'hosts')):
															echo $this->Html->link(
															$service['Service']['Host']['name'],
																[
																		'controller' => 'hosts',
																	'action' => 'edit',
																	$service['Service']['Host']['id']
																],
																['escape' => true]
															);
														else:
															echo h($service['Service']['Host']['name']);
														endif;
														echo '/';
														if($this->Acl->hasPermission('edit', 'services')):
															echo $this->Html->link(
																($service['Service']['name']!==null && $service['Service']['name'] !== '')?$service['Service']['name']:$service['Service']['Servicetemplate']['name'],
																[
																	'controller' => 'services',
																		'action' => 'edit',
																	$service['service_id']
																],
																['escape' => true]
															);
														else:
															echo h(($service['Service']['name']!==null && $service['Service']['name'] !== '')?$service['Service']['name']:$service['Service']['Servicetemplate']['name']);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td>
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($servicedependency, 'ServicedependencyServiceMembership.{n}[dependent=1]') as $service_dependent):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'hosts')):
															echo $this->Html->link(
																$service_dependent['Service']['Host']['name'],
																[
																	'controller' => 'hosts',
																	'action' => 'edit',
																		$service_dependent['Service']['Host']['id']
																],
																['escape' => true]
															);
														else:
															echo h($service_dependent['Service']['Host']['name']);
														endif;
														echo '/';
														if($this->Acl->hasPermission('edit', 'services')):
															echo $this->Html->link(
																($service_dependent['Service']['name'] !==null && $service_dependent['Service']['name'] !== '')?$service_dependent['Service']['name']:$service_dependent['Service']['Servicetemplate']['name'],
																[
																	'controller' => 'services',
																	'action' => 'edit',
																	$service_dependent['service_id']
																],
																['escape' => true]
															);
														else:
															echo h(($service_dependent['Service']['name'] !==null && $service_dependent['Service']['name'] !== '')?$service_dependent['Service']['name']:$service_dependent['Service']['Servicetemplate']['name']
);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td >
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($servicedependency, 'ServicedependencyServicegroupMembership.{n}[dependent=0]') as $servicegroup):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'servicegroups')):
															echo $this->Html->link(
																$servicegroup['Servicegroup']['Container']['name'],
																[
																	'controller' => 'servicegroups',
																		'action' => 'edit',
																	$servicegroup['servicegroup_id']
																],
																['escape' => true]
															);
														else:
															echo h($servicegroup['Servicegroup']['Container']['name']);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td>
												<ul class="list-unstyled">
												<?php
													foreach(Hash::extract($servicedependency, 'ServicedependencyServicegroupMembership.{n}[dependent=1]') as $servicegroup_dependent):
														echo '<li>';
														if($this->Acl->hasPermission('edit', 'servicegroups')):
															echo $this->Html->link(
																$servicegroup_dependent['Servicegroup']['Container']['name'],
																[
																	'controller' => 'servicegroups',
																	'action' => 'edit',
																	$servicegroup_dependent['servicegroup_id']
																],
																['escape' => true]
															);
														else:
															echo h($servicegroup_dependent['Servicegroup']['Container']['name']);
														endif;
														echo '</li>';
													endforeach;
												?>
												</ul>
											</td>
											<td><?php
												if($this->Acl->hasPermission('edit', 'timeperiods')):
													echo $this->Html->link($servicedependency['Timeperiod']['name'],[
														'controller' => 'timeperiods',
														'action' => 'edit',
														$servicedependency['Servicedependency']['timeperiod_id']
													]);
												else:
													echo h($servicedependency['Timeperiod']['name']);
												endif;
												?>
											</td>
											<td><?php
											echo $this->Form->fancyCheckbox('', [
												'caption' => '',
												'checked' => $servicedependency['Servicedependency']['inherits_parent'],
												'showLabel' => false,
												'disabled' => true
											]);
											?></td>

											<td><?php echo __viewDependencyOptions($servicedependency, 'execution'); ?></td>
											<td><?php echo __viewDependencyOptions($servicedependency, 'notification'); ?></td>
											<td class="text-center">
												<?php if($this->Acl->hasPermission('edit') && $allowEdit):?>
													<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $servicedependency['Servicedependency']['id']; ?>" data-original-title="<?php echo __('edit'); ?>" data-placement="left" rel="tooltip" data-container="body"><i id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if(empty($all_servicedependencies)):?>
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
				</div>
			</div>
	</div>
</section>
<?php
/**
 * This is a view function and ONLY CALLED IN THIS VIEW!
 *
 * @param array $servicedependency from find('first')
 * @param  string [options_mode] [Dependency options mode execution|notification]
 * @return string `<i />` HTML object with icons for each options
 * @since 3.0
 *
 */
function __viewDependencyOptions($servicedependency = [], $options_mode){
	$options = [
		$options_mode.'_fail_on_ok' => [
			'color' =>  'txt-color-greenLight',
			'class' => 'fa fa-square'
		],
		$options_mode.'_fail_on_warning' => [
			'color' => 'txt-color-redLight',
			'class' => 'fa fa-square'
		],
		$options_mode.'_fail_on_critical' => [
			'color' => 'txt-color-orange',
			'class' => 'fa fa-square'
		],
		$options_mode.'_fail_on_unknown' => [
			'color' => 'txt-color-blueDark',
			'class' => 'fa fa-square'
		],
		$options_mode.'_fail_on_pending' => [
			'color' => '',
			'class' => 'fa fa-square-o'
		],
		$options_mode.'_none' => [
			'color' => '',
			'class' => 'fa fa-minus-square-o'
		]
	];
	$html = '';
	foreach($options as $option => $layout_sett){ //$layout_sett => color + icons for options
		if(isset($servicedependency['Servicedependency'][$option]) && $servicedependency['Servicedependency'][$option] == 1){
			$html.='<i class="'.$layout_sett['class'].' '.$layout_sett['color'].'" title="'.preg_replace('/('.$options_mode.'_|fail_on_)/','', $option).'"></i>&nbsp';
		}
	}
	return $html;
}
