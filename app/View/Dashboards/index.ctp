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
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-dashboard fa-fw "></i>
				<?php echo __('Dashboard')?>
		</h1>
	</div>
</div>

<section id="widget-grid" class="">
	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="tabsContainer">
						<ul class="nav pull-left nav-tabs">
							<?php foreach($tabs as $_tab): ?>
								<?php $isActive = ($_tab['DashboardTab']['id'] == $tab['DashboardTab']['id']); ?>
								<li class="<?php echo ($isActive)?'active':''; ?> dropdown-toggle dashboardTab">
									<a class="pointer" data-toggle="dropdown" href="javascript:void(0)">
										<span class="text <?php echo ($_tab['DashboardTab']['shared'] == 1)?'text-primary':''; ?>"><?php echo h($_tab['DashboardTab']['name']); ?></span>
										<?php if($isActive): ?>
											<b class="caret"></b>
										<?php endif; ?>
									</a>
									<?php if($isActive): ?>
										<ul class="dropdown-menu">
											<li>
												<a class="renameTab" href="javascript:void(0)">
													<i class="fa fa-pencil-square-o"></i>
													<?php echo __('Rename'); ?>
												</a>
												<?php if(!$_tab['DashboardTab']['shared']): ?>
													<a class="shareTab" href="javascript:void(0)">
														<i class="fa fa-share-alt"></i>
														<?php echo __('Start sharing'); ?>
													</a>
												<?php else: ?>
													<a class="stopShareTab" href="javascript:void(0)">
														<i class="fa fa-share-alt"></i>
														<?php echo __('Stop sharing'); ?>
													</a>
												<?php endif ?>
												<a class="deleteTab" href="javascript:void(0)">
													<i class="fa fa-trash-o"></i>
													<?php echo __('Delete'); ?>
												</a>
												<?php if($_tab['DashboardTab']['source_tab_id'] !== 0): ?>
													<a class="refreshTab" href="javascript:void(0)">
														<i class="fa fa-refresh"></i>
														<?php echo __('Get update'); ?>
													</a>
												<?php endif ?>
											</li>
										</ul>
									<?php endif;?>
								</li>
							<?php endforeach;?>
						</ul>
					</div>
				</header>
				<div>
					<div class="widget-body no-padding padding-top-10">
						<div class="padding-bottom-10">
							<div class="grid-stack">
								<?php
								foreach($tab['Widget'] as $widget):
									echo $this->Dashboard->render($widget);
								endforeach;
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>
