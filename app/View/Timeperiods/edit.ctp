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
<?php //debug($containers); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-clock-o fa-fw "></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Timeperiods'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-clock-o"></i> </span>
		<h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?> <?php echo __('timeperiod'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php if($this->Acl->hasPermission('delete')): ?>
				<?php echo $this->Utils->deleteButton(null, $timeperiod['Timeperiod']['id']);?>
			<?php endif; ?>
			<?php echo $this->Utils->backButton() ?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Timeperiod', array(
					'class' => 'form-horizontal clear'
				));
				
				if($hasRootPrivileges):
				echo $this->Form->input('Timeperiod.container_id', array(
						'options' => $containers,
						'selected' => $timeperiod['Timeperiod']['container_id'],
//						'multiple' => true,
						'class' => 'chosen',
						'style' => 'width: 100%',
						'label' => __('Container')
					)
				);
				elseif(!$hasRootPrivileges && $timeperiod['Timeperiod']['container_id'] != ROOT_CONTAINER):
					echo $this->Form->input('Timeperiod.container_id', array(
							'options' => $containers,
							'selected' => $timeperiod['Timeperiod']['container_id'],
	//						'multiple' => true,
							'class' => 'chosen',
							'style' => 'width: 100%',
							'label' => __('Container')
						)
					);
				else:
					?>
					<div class="form-group required">
						<label class="col col-md-2 control-label" ><?php echo __('Container'); ?></label>
						<div class="col col-xs-10 required"><input type="text" value="/root" class="form-control" readonly></div>
					</div>
					<?php
					echo $this->Form->input('Container.parent_id', array(
							'value' => $timeperiod['Timeperiod']['container_id'],
							'type' => 'hidden'
						)
					);
				endif;
				echo $this->Form->input('Timeperiod.name', array('value' => $timeperiod['Timeperiod']['name']));
				echo $this->Form->input('Timeperiod.description', array('value' => $timeperiod['Timeperiod']['description']));
				echo $this->Form->input('Timeperiod.id', array('type' => 'hidden', 'value' => $timeperiod['Timeperiod']['id']));
				echo $this->Form->input('check_timerange', ['type' => 'hidden']);
				?>
			<br />
			<fieldset class=" form-inline required padding-10">
				<legend class="font-sm">
					<div class="required <?php echo (isset($timerange_errors['check_timerange']))?' has-error':'';?> ">
						<label ><?php echo __('Time ranges:'); ?>  </label>
					</div>
					<?php if(isset($timerange_errors['check_timerange'])): ?>
						<span class="text-danger"><?php echo (isset($timerange_errors['check_timerange']))?$timerange_errors['check_timerange'][0]:''; ?></span>
					<?php endif; ?>
				</legend>
				<?php if(sizeof($timeperiod['Timerange']) === 0): ?>
						<div class="col-md-10 padding-top-10 required">
						<?php
							echo $this->Form->input('Timerange.0.day', [
									'options' => $weekdays,
									'multiple' => false,
									'class' => 'chosen weekdays',
									'div' => false,

									'wrapInput' => 'col-md-2',
									'label' => ['text' => __('Day'), 'class' => 'col-md-1 no-padding text-right'],
								]
							);

							echo $this->Form->input('Timerange.0.start', [
									'class' => 'col-xs-8 no-padding',
									'placeholder' => '00:00',
									'maxlength' => 5,
									'size' => 5,
									'div' => false,
									'wrapInput' => 'col-md-2',
									'label' => ['class' => 'col-md-2 text-right'],
									'error' => array(
										'attributes' => array(
											'wrap' => 'div',
											'class' => 'text-danger',
										)
									),
								]
							);
								echo $this->Form->input('Timerange.0.end', [
									'class' => 'col-xs-8 no-padding',
									'placeholder' => '24:00',
									'maxlength' => 5,
									'size' => 5,
									'div' => false,
									'wrapInput' => 'col-md-2',
									'label' => ['class' => 'col-md-2 text-right'],
									'error' => array(
										'attributes' => array(
											'wrap' => 'div',
											'class' => 'text-danger'
										)
									),
									]
								);
							?>
							<a class="btn btn-default btn-xs txt-color-red removeTimeRangeDivButton">
								<i class="fa fa-trash-o"></i>
							</a>
						</div>
					<?php else: ?>
					<?php
					$intern_day_counter = 0;
					$tmp_day = 0;
					foreach($timeperiod['Timerange'] as $key => $timerange):
						if($tmp_day != $timerange['day']):
							$tmp_day = $timerange['day'];
							$intern_day_counter = 0;
						endif;
					?>
						<div class="col-md-10 padding-top-10 timerange required">
							<?php
							echo $this->Form->input('Timerange.'.$key.'.day', [
									'options' => $weekdays,
									'multiple' => false,
									'class' => 'chosen weekdays',
									'div' => false,
									'wrapInput' => 'col-md-2',
									'label' => ['text' => __('Day'), 'class' => 'col-md-1 no-padding text-right'],
									'default' => $timerange['day'],
								]
							);

							if(isset($timerange['id'])){
								echo $this->Form->input('Timerange.'.$key.'.id', [
									'type' => 'hidden',
									'value' => $timerange['id']
								]);
							}
							echo $this->Form->input('Timerange.'.$key.'.start', [
									'class' => ((isset($timerange_errors) && array_key_exists('Timerange.'.$timerange['day'].'.'.$intern_day_counter.'.start', $timerange_errors))?'input_error_field':' no-padding'),
									'placeholder' => '00:00',
									'maxlength' => 5,
									'size' => 5,
									'div' => false,
									'wrapInput' => 'col-md-2',
									'label' => ['class' => 'col col-md-2 text-right control-label'],
									'value' => $timerange['start'],
									'error' => array(
										'attributes' => array(
											'wrap' => 'div',
											'class' => 'text-danger'
										)
									),
									'errorClass' => 'text-danger error'
								]
							);
							echo $this->Form->input('Timerange.'.$key.'.end', [
									'class' => ((isset($timerange_errors) && array_key_exists('Timerange.'.$timerange['day'].'.'.$intern_day_counter.'.start', $timerange_errors))?'input_error_field':' no-padding'),
									'placeholder' => '24:00',
									'maxlength' => 5,
									'size' => 5,
									'div' => false,
									'wrapInput' => 'col-md-2',
									'label' => ['class' => 'col-md-2 text-right'],
									'value' => $timerange['end'],
									'error' => array(
										'attributes' => array(
											'wrap' => 'div',
											'class' => 'text-danger'
										)
									),
								]
							);
							?>
							<div class="col-md-1">
								<a class="btn btn-default btn-xs txt-color-red removeTimeRangeDivButton">
									<i class="fa fa-trash-o"></i>
								</a>
							</div>
						</div>
					<?php
						$intern_day_counter++;
					endforeach; ?>
				<?php endif; ?>
				<div class="col-md-2 padding-top-10 right" id="addTimerangeButton">
					<a class="btn btn-primary btn-xs addTimeRangeDivButton">
						<i class="fa fa-plus"></i>
						<?php echo __('Add');?>
					</a>
				</div>
				<div class="col-md-10 padding-top-10 invisible required" id="timerange_template">
				<?php
					echo $this->Form->input('template.'.sizeof($timeperiod['Timerange']).'.day', [
							'options' => $weekdays,
							'multiple' => false,
							'class' => 'weekdays',
							'div' => false,
							'wrapInput' => 'col-md-2',
							'label' => ['text' => __('Day'), 'class' => 'col-md-1 no-padding text-right']
						]
					);

					echo $this->Form->input('template.'.sizeof($timeperiod['Timerange']).'.start', [
							'class' => 'no-padding',
							'placeholder' => '00:00',
							'maxlength' => 5,
							'size' => 5,
							'div' => false,
							'wrapInput' => 'col-md-2',
							'label' => ['class' => 'col col-md-2 text-right control-label'],
							'error' => array(
								'attributes' => array(
									'wrap' => 'div',
									'class' => 'text-danger'
								)
							),
							'errorClass' => 'text-danger error'
						]
					);
					echo $this->Form->input('template.'.sizeof($timeperiod['Timerange']).'.end', [
							'class' => 'no-padding',
							'placeholder' => '24:00',
							'maxlength' => 5,
							'size' => 5,
							'div' => false,
							'wrapInput' => 'col-md-2',
							'label' => ['class' => 'col-md-2 text-right'],
							'error' => array(
								'attributes' => array(
									'wrap' => 'div',
									'class' => 'text-danger'
								)
							),
						]
					);
					?>
					<div class="col-md-1">
						<a class="btn btn-default btn-xs txt-color-red removeTimeRangeDivButton">
							<i class="fa fa-trash-o"></i>
						</a>
					</div>
				</div>
			</fieldset>
			<div class="row">
			</div>
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>