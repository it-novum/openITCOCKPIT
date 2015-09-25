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
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" >
				<header>
					<div class="tabsContainer">
						<ul class="nav nav-tabs pull-left">
							<?php foreach($tabs as $_tab): ?>
								<?php $isActive = ($_tab['DashboardTab']['id'] == $tab['DashboardTab']['id']); ?>
								<?php if($isActive): ?>
									<li class="active dropdown-toggle">
										<a class="pointer" data-toggle="dropdown" href="javascript:void(0);">
											<span class="text <?php echo ($_tab['DashboardTab']['shared'] == 1)?'text-primary':''; ?>"><?php echo h($_tab['DashboardTab']['name']); ?></span>
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a class="tab-select-menu-fix" href="javascript:void(0)" data-toggle="modal" data-target="#renameTabModal" >
													<i class="fa fa-pencil-square-o"></i>
													<?php echo __('Rename'); ?>
												</a>
											</li>
											<?php if(!$_tab['DashboardTab']['shared']): ?>
												<li>
													<a class="tab-select-menu-fix shareTab" href="javascript:void(0)">
														<i class="fa fa-share-alt"></i>
														<?php echo __('Start sharing'); ?>
													</a>
												</li>
												<?php else: ?>
													<li>
														<a class="tab-select-menu-fix stopShareTab" href="javascript:void(0)">
															<i class="fa fa-share-alt"></i>
															<?php echo __('Stop sharing'); ?>
														</a>
													</li>
											<?php endif ?>
											<?php if($_tab['DashboardTab']['source_tab_id'] !== 0 && $_tab['DashboardTab']['source_tab_id'] !== null): ?>
												<li>
													<a class="tab-select-menu-fix refreshTab" href="javascript:void(0)">
														<i class="fa fa-refresh"></i>
														<?php echo __('Get update'); ?>
													</a>
												</li>
											<?php endif ?>
											<li class="divider"></li>
											<li>
												<?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'dashboards', 'action' => 'deleteTab', $_tab['DashboardTab']['id']], ['class' => 'txt-color-red', 'escape' => false]);?>
											</li>
										</ul>
									</li>
								<?php else: ?>
									<li>
										<a class="pointer" href="<?php echo Router::url(['action' => 'index', $_tab['DashboardTab']['id']]); ?>">
											<span class="text <?php echo ($_tab['DashboardTab']['shared'] == 1)?'text-primary':''; ?>"><?php echo h($_tab['DashboardTab']['name']); ?></span>
										</a>
									</li>
								<?php endif; ?>
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
						<button class="btn btn-xs btn-success" data-toggle="modal" data-target="#addWidgetModal">
							<i class="fa fa-plus"></i>
						</button>
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

<div class="modal fade" id="addWidgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Create new Tab');?></h4>
			</div>
			<div class="modal-body">
				<div>
					<?php
					echo $this->Form->create('dashboard', [
						'class' => 'form-horizontal clear',
						'action' => 'createTab'
					]);
					echo $this->Form->input('name');
					?>
					<div style="height:35px;">
						<?php
						echo $this->Form->submit(__('Save'), [
							'class' => [
								'btn btn-primary pull-right'
							]
						]);
						echo $this->Form->end();
						?>
					</div>
				</div>
				<div>
					<hr />
					<h3><?php echo __('Select shared tab');?></h3>
					<?php echo $this->Form->input('shared_tabs', [
						'options' => ['foobar', '123', 'asd'],
						'label' => __('Shared tabs'),
						'class' => 'chosen',
						'style' => 'width:100%',
						]);?>
						<br />
						<br />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Close'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="renameTabModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo __('Rename tab');?></h4>
			</div>
			<div class="modal-body">
				<div>
					<?php
					echo $this->Form->create('dashboard', [
						'class' => 'form-horizontal clear',
						'action' => 'renameTab'
					]);
					echo $this->Form->input('name', [
						'value' => $tab['DashboardTab']['name']
					]);
					echo $this->Form->input('id', [
						'value' => $tab['DashboardTab']['id'],
						'type' => 'hidden'
					]);
					?>
					<div style="height:35px;">
						<?php
						echo $this->Form->submit(__('Save'), [
							'class' => [
								'btn btn-primary pull-right'
							]
						]);
						echo $this->Form->end();
						?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?php echo __('Close'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

