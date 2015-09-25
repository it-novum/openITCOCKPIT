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
					
					<div class="widget-toolbar" role="menu">
						<div class="btn-group">
							<button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-success">
								<?php echo __('Add Widget')?> <i class="fa fa-caret-down"></i>
							</button>
							<ul class="dropdown-menu pull-right">
								<?php foreach($allWidgets as $_widget):?>
									<li>
										<a href="javascript:void(0);" class="addWidget" data-type-id="<?php echo h($_widget['typeId']); ?>">
											<i class="fa <?php echo h($_widget['icon']); ?>"></i>&nbsp;
											<?php echo h($_widget['title']);?>
										</a>
									</li>
								<?php endforeach; ?>
								<li class="divider"></li>
								<li>
									<a href="<?php echo Router::url(['controller' => 'dashboards', 'action' => 'restoreDefault', $tab['DashboardTab']['id']]); ?>">
										<i class="fa fa-recycle"></i>&nbsp;
										<?php echo __('Restore default'); ?>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="widget-toolbar" rile="menu">
						<button class="btn btn-xs btn-primary" data-toggle="dropdown" aria-expanded="false">
							<i class="fa fa-refresh"></i>
						</button>
						<ul class="dropdown-menu pull-right" id="37199141">
							<li>
								<div class="form-group form-group-slider ">
									<label class="col rotationSliderLabel" for="tabRotationInterval">Choose tab rotation interval</label>
									<div class="col rotationSlider">
										<div class="slider slider-horizontal" id=""><div class="slider-track"><div class="slider-selection" style="left: 0%; width: 0%;"></div><div class="slider-handle min-slider-handle round" tabindex="0" style="left: 0%;"></div><div class="slider-handle max-slider-handle round hide" tabindex="0" style="left: 0%;"></div></div><div class="tooltip tooltip-main top hide" style="left: 0%; margin-left: 0px;"><div class="tooltip-arrow"></div><div class="tooltip-inner">0</div></div><div class="tooltip tooltip-min top hide"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div><div class="tooltip tooltip-max top hide" style="top: -30px;"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div><input type="text" id="tabRotationInterval" maxlength="255" value="0" class="form-control slider slider-success" name="data[rotationInterval]" data-slider-min="0" data-slider-max="1200" data-slider-value="0" data-slider-selection="before" data-slider-step="30" human="#HostNotificationinterval_human" data="value: '0'" style="display: none;">
									</div>
									<div class="col rotationSliderInput">
										<input type="number" id="_tabRotationInterval" human="#HostNotificationinterval_human" value="0" slider-for="HostNotificationinterval" class="form-control slider-input" name="data[Host][notification_interval]">
										<span class="note" id="HostNotificationinterval_human">0 seconds</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="widget-toolbar" rile="menu">
						<button class="btn btn-xs btn-success" data-toggle="dropdown" aria-expanded="false">
							<i class="fa fa-plus"></i>
						</button>
						<ul class="newTabsList dropdown-menu pull-right" id="45809103">
							<li class="addNewTab"><a href="javascript:void(0);"><i class="fa fa-plus">&nbsp;</i>New Tab</a></li>
							<li class="divider"></li>
							<li class="addSharedTab">
								<form action="/" novalidate="novalidate" class="sharedTabsForm clear" id="" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div><div class="form-group"><label for="chooseSharedTabSharedTabSelect" class="col col-md-2 control-label">Select a shared Tab</label><div class="col col-xs-8 selectSharedTab">
									<select name="data[chooseSharedTab][sharedTabSelect]" class="chosen selectSharedTab elementInput" id="chooseSharedTabSharedTabSelect" style="display: none;">
										<option value="0"></option>
									</select>
									<div class="chosen-container chosen-container-single" style="width: 100%;" title="" id="chooseSharedTabSharedTabSelect_chosen">
										<a class="chosen-single chosen-default" tabindex="-1">
											<span>Please choose</span>
											<div>
												<b></b>
											</div>
										</a>
										<div class="chosen-drop">
											<div class="chosen-search">
												<input type="text" autocomplete="off">
											</div>
											<ul class="chosen-results"></ul>
										</div>
									</div>
									<div class="submit">
										<input class="sharedTabSave btn btn-primary" type="submit" value="Save">
									</div>
								</form>
							</li>
						</ul>
					</div>
					
				</header>
				<div>
					<div class="widget-body no-padding padding-top-10">
						<div class="padding-bottom-10">
							<div class="grid-stack">
								<?php
								foreach($preparedWidgets as $widget):
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
