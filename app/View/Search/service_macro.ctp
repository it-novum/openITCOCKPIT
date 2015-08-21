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
			<i class="fa fa-search fa-fw "></i>
				<?php echo __('Service macro'); ?>
			<span>>
				<?php echo __('search result'); ?>
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
						<?php echo $this->Utils->backButton(__('Back'), '/search');?>
					</div>
					<span class="widget-icon hidden-mobile"> <i class="fa fa-cog"></i> </span>
					<h2 class="hidden-mobile"><?php echo __('Services'); ?> </h2>

				</header>

				<div>
					<div class="widget-body no-padding">
						<div class="mobile_table">
							<table id="host_list" class="table table-striped table-bordered smart-form" style="">
								<thead>
									<tr>
										<th class="no-sort"><?php __('Servicename'); ?></th>
										<th class="no-sort text-center" ><i class="fa fa-gear fa-lg"></i></th>
									</tr>
								</thead>
								<tbody>
									<?php $tmp_host_name = null; ?>
									<?php foreach($all_services as $service):?>
										<?php if($tmp_host_name != $service['Host']['name']):
											$tmp_host_name = $service['Host']['name'];
										?>
											<tr>
												<td class="bg-color-lightGray" colspan="2"><a class="padding-left-5 txt-color-blueDark" href="/hosts/browser/<?php echo $service['Host']['id']; ?>"><?php echo h($service['Host']['name']); ?> (<?php echo h($service['Host']['address']); ?>)</a> <a class="pull-right txt-color-blueDark" href="/services/serviceList/<?php echo $service['Host']['id']; ?>"><i class="fa fa-list" title="<?php echo __('Go to Service list'); ?>"></i></a></td>
											</tr>

											<?php endif; ?>
											<tr>
												<td><a href="/services/browser/<?php echo $service['Service']['id']; ?>">
													<?php
													if($service['Service']['name'] !== null && $service['Service']['name'] !== ''):
														echo h($service['Service']['name']);
													else:
														echo h($service['Servicetemplate']['name']);
													endif;
													?>
												</a></td>
												<td class="width-160">
													<div class="btn-group">
														<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>" class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
														<a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></a>
														<ul class="dropdown-menu">
															<li>
																<a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>"><i class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
															</li>
															<li>
																<a href="/<?php echo $this->params['controller']; ?>/deactivate/<?php echo $service['Service']['id']; ?>"><i class="fa fa-plug"></i> <?php echo __('Disable'); ?></a>
															</li>
															<li class="divider"></li>
															<li>
																<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'services', 'action' => 'delete', $service['Service']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
															</li>
														</ul>
													</div>
												</td>
											</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php if(empty($all_services)):?>
							<div class="noMatch">
								<center>
									<span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
								</center>
							</div>
						<?php endif;?>

					</div>
				</div>
			</div>
	</div>
</section>
