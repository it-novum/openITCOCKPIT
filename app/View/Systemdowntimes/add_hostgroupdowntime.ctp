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
			<i class="fa fa-power-off fa-fw "></i>
				<?php echo __('Hostgroup downtime'); ?>
			<span>>
				<?php echo __('create'); ?>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-power-off"></i> </span>
		<h2 class="hidden-mobile hidden-tablet"><?php echo __('Create hostgroup downtime'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->backButton(__('Back'), $back_url);?>
		</div>
	</header>
	<div>
		<div class="widget-body">
				<div class="row">
					<div class="col-xs-12 col-md-9 col-lg-7">
							<?php
							echo $this->Form->create('Systemdowntime', [
								'class' => 'form-horizontal clear',
							]);
							$hostgroupdowntimetyps = [
								0 => __('Hosts only'),
								1 => __('Hosts including services'),
							];
							?>
							<?php echo $this->CustomValidationErrors->errorHTML('downtimetype', ['class' => 'text-danger']); ?>
							<?php echo $this->Form->input('objecttype_id', ['type' => 'hidden', 'value' => OBJECT_HOSTGROUP]);?>
							<?php echo $this->Form->input('downtimetype', ['type' => 'hidden', 'value' => 'hostgroup']);?>
							<?php echo $this->Form->input('object_id', ['options' => $hostgroups, 'selected' => $selected, 'multiple' => true, 'label' => __('Hostgroup'), 'class' => 'chosen col col-xs-12']); ?>
							<?php echo $this->Form->input('downtimetype_id', ['options' => $hostgroupdowntimetyps, 'label' => __('Maintenance period for'), 'class' => 'chosen col col-xs-12']); ?>
							<?php echo $this->Form->input('comment', ['value' => __('In maintenance'), 'label' => __('Comment')]); ?>
							
							<?php
							echo $this->Form->fancyCheckbox('is_recurring', [
								'caption' => __('Recurring downtime'),
								'captionGridClass' => 'col col-md-2',
								'captionClass' => 'control-label',
								'wrapGridClass' => 'col col-md-10',
								'class' => 'onoffswitch-checkbox notification_control',
								'checked' => (bool)$this->CustomValidationErrors->refill('is_recurring', false)
							]);
							?>
							<div class="padding-20"><!-- spacer --></div>
							<div id="recurringHost_settings" style="display:none;">
								<?php
								$weekdays = [
									1 => __('Monday'),
									2 => __('Tuesday'),
									3 => __('Wednesday'),
									4 => __('Thursday'),
									5 => __('Friday'),
									6 => __('Saturday'),
									7 => __('Sunday'),
								];
							
								echo $this->Form->input('weekdays', ['options' => $weekdays, 'multiple' => true, 'label' => __('Weekdays'), 'class' => 'chosen col col-xs-12']);
								echo $this->Form->input('day_of_month', ['placeholder' => __('1,2,3,4,5 or <blank>'), 'label' => __('Days of month')]);
								?>
							</div>
							<br />
							<!-- from -->
							<div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('from_date'); ?>">
								<label class="col col-md-2 control-label" for="SystemdowntimeFromDate"><?php echo __('From');?>:</label>
								<div class="col col-xs-4 col-md-5" style="padding-right: 0px;">
									<input type="text" id="SystemdowntimeFromDate" value="<?php echo $this->CustomValidationErrors->refill('check_interval', date('d.m.Y')); ?>" class="form-control" name="data[Systemdowntime][from_date]">
									<div>
										<?php echo $this->CustomValidationErrors->errorHTML('from_date'); ?>
									</div>
								</div>
								<div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('from_time'); ?>" style="padding-left: 0px;">
									<input type="text" id="SystemdowntimeFromTime" value="<?php echo date('H:m'); ?>" class="form-control" name="data[Systemdowntime][from_time]">
									<div>
										<?php echo $this->CustomValidationErrors->errorHTML('from_time'); ?>
									</div>
								</div>
							</div>
							

							<!-- to -->
							<div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('to_date'); ?>">
								<label class="col col-md-2 control-label" for="SystemdowntimeToDate"><?php echo __('To');?>:</label>
								<div class="col col-xs-4 col-md-5" style="padding-right: 0px;">
									<input type="text" id="SystemdowntimeToDate" value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control" name="data[Systemdowntime][to_date]">
									<div>
										<?php echo $this->CustomValidationErrors->errorHTML('to_date'); ?>
									</div>
								</div>
								<div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('to_time'); ?>" style="padding-left: 0px;">
									<input type="text" id="SystemdowntimeToTime" value="<?php echo date('H:m'); ?>" class="form-control" name="data[Systemdowntime][to_time]">
									<div>
										<?php echo $this->CustomValidationErrors->errorHTML('to_time'); ?>
									</div>
								</div>
							</div>
				</div> <!-- close col -->
			</div> <!-- close row-->
			<?php echo $this->Form->formActions(); ?>
		</div> <!-- close widget body -->
	</div>
</div> <!-- end jarviswidget -->