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
<ol class="breadcrumb">
	<li></li> <!-- leading / -->
	<?php
	$current_node = $top_node;
	if($top_node['Container']['parent_id'] != null):
		foreach($parents as $parent):
			echo '<li>'.$this->Html->link($parent['Container']['name'], 'index/'.$parent['Container']['id']).'</li>';
		endforeach;
	endif;
	?>
	<li class="active"><?php echo h($current_node['Container']['name']); ?><li>
</ol>

<div class="row">
	<article class="col-sm-2 col-md-2 col-lg-2">
		<div class="jarviswidget node-list" role="widget">
			<header>
				<span class="widget-icon"> <i class="fa fa-list-ul"></i></span>
				<h2> <?php echo __('nodes'); ?> </h2>
			</header>
			<div class="no-padding height-100" style="overflow-y:auto; overflow-x: hidden;">
				<input type="text" id="node-list-search" placeholder="<?php echo __('Search...'); ?>"/>
				<div class="padding-10">
					<div class="widget-body">
						<?php foreach($tenants as $tenantContainerId => $tenantName): ?>
							<div class="ellipsis searchContainer">
								<i class="fa fa-home"></i>
								<?php echo $this->Html->link($tenantName, ['action' => 'tenantBrowser', $tenantContainerId], ['class' => 'searchMe']); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</article>
	<article class="col-sm-5 col-md-5 col-lg-5">
			<div class="jarviswidget" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
			<header>
					<span class="widget-icon"> <i class="fa fa-pie-chart"></i></span>
					<h2><?php echo __('Hoststatus overview'); ?></h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- end widget edit box -->
					<div class="widget-body padding-10 text-center">
						<?php
						$state_total = array_sum($state_array_host);
							if($state_total > 0):
							$overview_chart =  $this->PieChart->createPieChart($state_array_host);
							echo $this->Html->image(
								'/img/charts/'.$overview_chart
							);
							$state_colors = [
								'ok',
								'critical',
								'unknown'
							];?>
							<div class="col-md-12 text-center padding-bottom-10 font-xs">
								<?php
									foreach($state_array_host as $state => $state_count):?>
										<div class="col-md-4 no-padding">
											<a href="<?php echo Router::url([
												'controller' => 'hosts',
												'action' => 'index',
												'plugin' => '',
												'Filter.Hoststatus.current_state['.$state.']' => 1,
											]); ?>">
												<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
												<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
											</a>
										</div>
								<?php endforeach; ?>
							</div>
						<?php else:?>
							<div class="text-muted padding-top-20"><?php echo __('No hosts are monitored on your system. Please create first a host'); ?></div>
						<?php endif; ?>
					</div>

				</div>
		</article>
		<article class="col-sm-5 col-md-5 col-lg-5 sortable-grid ui-sortable">
			<div class="jarviswidget jarviswidget-sortable" id="wid-id-12" data-widget-deletebutton="false" data-widget-colorbutton="false" data-widget-fullscreenbutton="true" data-widget-editbutton="true" data-widget-togglebutton="false" style="position: relative; opacity: 1; left: 0px; top: 0px;" role="widget">
			<header>
					<span class="widget-icon"> <i class="fa fa-pie-chart"></i></i></span>
					<h2><?php echo __('Servicestatus overview'); ?></h2>
				</header>
				<!-- widget div-->
				<div>
					<!-- end widget edit box -->
					<div class="widget-body padding-10 text-center">
					<?php
						$state_total = array_sum($state_array_service);
						if($state_total > 0):
							$overview_chart = $this->PieChart->createPieChart($state_array_service);

							echo $this->Html->image(
								'/img/charts/'.$overview_chart
							);
							$state_colors = [
								'ok',
								'warning',
								'critical',
								'unknown'
							];?>
							<div class="col-md-12 text-center padding-bottom-10 font-xs">
							<?php

								foreach($state_array_service as $state => $state_count):?>
									<div class="col-md-3 no-padding">
										<a href="<?php echo Router::url([
											'controller' => 'services',
											'action' => 'index',
											'plugin' => '',
											'Filter.Servicestatus.current_state['.$state.']' => 1,
										]); ?>">
											<i class="fa fa-square <?php echo $state_colors[$state]?>"></i>
											<?php
											//Fix for a system without host or services
											if($state_total == 0):
												$state_total = 1;
												if($state == 3):
													$state_count = 1;
												endif;
											endif;
											?>
											<?php echo $state_count.' ('.round($state_count/$state_total*100, 2).' %)'; ?>
										</a>
									</div>
							<?php endforeach;?>
							</div>
						<?php else:?>
							<div class="text-muted padding-top-20"><?php echo __('No services are monitored on your system. Please create first a service'); ?></div>
						<?php endif;?>
					</div>
				</div>
			</div>
		</article>
</div>
<div class="row">
	<article class="col-sm-12 col-md-12 col-lg-12">
		<div class="jarviswidget ">
			<header>
				<span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
				<h2 class="hidden-mobile"><?php echo __('Hosts'); ?></h2>
			</header>
			<div>
				<div class="widget-body no-padding">
					<div class="mobile_table">
						<table id="host-list-datatables" class="table table-striped table-bordered smart-form" style="">
							<thead>
								<tr>
									<?php $order = $this->Paginator->param('order'); ?>
									<th class="select_datatable no-sort"><?php echo __('Hoststatus'); ?></th>
									<th class="no-sort text-center" ><i class="fa fa-gear fa-lg"></i></th>
									<th class="no-sort"><?php echo __('Hostname'); ?></th>
									<th class="no-sort"><?php echo __('IP address'); ?></th>
									<th class="no-sort"><?php echo  __('State since'); ?></th>
									<th class="no-sort"><?php echo __('Last check'); ?></th>
									<th class="no-sort"><?php echo __('Output'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($hosts as $host): ?>
									<?php
									//Better performance, than run all the Hash::extracts if not necessary
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
										<td class="text-center width-75">
											<?php
											if($host['Hoststatus']['is_flapping'] == 1):
												echo $this->Monitoring->hostFlappingIconColored($host['Hoststatus']['is_flapping'], '', $host['Hoststatus']['current_state']);
											else:
												$href = 'javascript:void(0);';
												if($this->Acl->hasPermission('browser', 'hosts')):
													$href = '/hosts/browser/'.$host['Host']['id'];
												endif;
												echo $this->Status->humanHostStatus($host['Host']['uuid'], $href, [$host['Host']['uuid'] => ['Hoststatus' => ['current_state' => $host['Hoststatus']['current_state']]]])['html_icon'];
											endif;
											?>
										</td>
										<td class="width-50">
											<div class="btn-group">
												<?php if($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission):?>
													<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $host['Host']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
												<?php else: ?>
													<a href="javascript:void(0);" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
												<?php endif; ?>
												<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
												<ul class="dropdown-menu">
													<?php if($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission):?>
														<li>
															<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $host['Host']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
														</li>
													<?php endif;?>
													<?php if($this->Acl->hasPermission('serviceList', 'services')):?>
														<li>
															<a href="/services/serviceList/<?php echo $host['Host']['id']; ?>"><i class="fa fa-list"></i> <?php echo __('Service list'); ?></a>
														</li>
													<?php endif; ?>
													
													<?php
														if($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission):
															echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $host['Host']['id']);
														endif;
													?>
													<?php if($this->Acl->hasPermission('delete', 'hosts') && $hasEditPermission):?>
														<li class="divider"></li>
														<li>
															<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'hosts', 'action' => 'delete', $host['Host']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
														</li>
													<?php endif;?>
												</ul>
											</div>
										</td>

										<td>
											<?php if($this->Acl->hasPermission('browser', 'hosts')):?>
												<a href="/hosts/browser/<?php echo $host['Host']['id']; ?>"><?php echo h($host['Host']['name']); ?></a>
											<?php else:?>
												<?php echo h($host['Host']['name']); ?>
											<?php endif; ?>
										</td>
										<td><?php echo h($host['Host']['address']); ?></td>
										<td data-original-title="<?php echo h($this->Time->format($host['Hoststatus']['last_hard_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>" data-placement="bottom" rel="tooltip" data-container="body">
											<?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($host['Hoststatus']['last_hard_state_change'])));?>
										</td>
										<td><?php echo h($this->Time->format($host['Hoststatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
										<td><?php echo h($host['Hoststatus']['output']); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
