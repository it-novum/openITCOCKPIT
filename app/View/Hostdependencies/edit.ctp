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
			<i class="fa fa-sitemap fa-fw "></i>
				<?php echo __('Monitoring');?>
			<span>>
				<?php echo __('Hostdependency'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
		<h2><?php echo __('Edit Hostdependency'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php if($this->Acl->hasPermission('delete')): ?>
				<?php echo $this->Utils->deleteButton(null, $hostdependency['Hostdependency']['id']);?>
			<?php endif; ?>
			<?php echo $this->Utils->backButton();?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Hostdependency', array(
					'class' => 'form-horizontal clear'
				));
				echo $this->Form->input('Hostdependency.id', array('type' => 'hidden', 'value' => $hostdependency['Hostdependency']['id']));

				if($hasRootPrivileges):
					echo $this->Form->input('Hostdependency.container_id', [
						'options' => $this->Html->chosenPlaceholder($containers),
						'class' => 'chosen',
						'style' => 'width: 100%;',
						'label' => __('Container'),
						'SelectionMode' => 'single',
						'selected' => $this->request->data['Hostdependency']['container_id']
					]);
				elseif(!$hasRootPrivileges && $hostdependency['Hostdependency']['container_id'] != ROOT_CONTAINER):
					echo $this->Form->input('Hostdependency.container_id', [
						'options' => $this->Html->chosenPlaceholder($containers),
						'class' => 'chosen',
						'style' => 'width: 100%;',
						'label' => __('Container'),
						'SelectionMode' => 'single',
						'selected' => $this->request->data['Hostdependency']['container_id']
					]);
				else:
					?>
					<div class="form-group required">
						<label class="col col-md-2 control-label" ><?php echo __('Container'); ?></label>
						<div class="col col-xs-10 required"><input type="text" value="/root" class="form-control" readonly></div>
					</div>
					<?php
					echo $this->Form->input('Hostdependency.container_id', array(
							'value' => $hostdependency['Hostdependency']['container_id'],
							'type' => 'hidden'
						)
					);
				endif;

				echo $this->Form->input('Hostdependency.Host', [
					'options' => $hosts,
					'class' => 'chosen',
					'multiple' => true,
					'style' => 'width:100%;',
					'label' => __('<i class="fa fa-square class-default"></i> Hosts'),
					'data-placeholder' => __('Please choose a host'),
					'wrapInput' => [
						'tag' => 'div',
						'class' => 'col col-xs-10'
					],
					'target' => '#HostdependencyHostDependent'
				]);

				echo $this->Form->input('Hostdependency.HostDependent', [
					'options' => $hosts,
					'class' => 'chosen test',
					'multiple' => true,
					'style' => 'width:100%;',
					'label' => __('<i class="fa fa-square class-info"></i> Dependent hosts'),
					'data-placeholder' => __('Please choose a host'),
					'wrapInput' => [
						'tag' => 'div',
						'class' => 'col col-xs-10 info'
					],
					'target' => '#HostdependencyHost'
				]);

				echo $this->Form->input('Hostdependency.Hostgroup', [
					'options' => $hostgroups,
					'class' => 'chosen',
					'multiple' => true,
					'style' => 'width:100%;',
					'label' => __('<i class="fa fa-square class-default"></i> Hostgroups'),
					'data-placeholder' => __('Please choose a hostgroup'),
					'wrapInput' => [
						'tag' => 'div',
						'class' => 'col col-xs-10'
					],
					'target' => '#HostdependencyHostgroupDependent'
				]);

				echo $this->Form->input('Hostdependency.HostgroupDependent', [
					'options' => $hostgroups,
					'class' => 'chosen',
					'multiple' => true,
					'style' => 'width:100%;',
					'label' => __('<i class="fa fa-square class-info"></i> Dependent Hostgroups'),
					'data-placeholder' => __('Please choose a hostgroup'),
					'wrapInput' => [
						'tag' => 'div',
						'class' => 'col col-xs-10 info'
					],
					'target' => '#HostdependencyHostgroup'
				]);

				echo $this->Form->input('Hostdependency.timeperiod_id', [
					'options' => $this->Html->chosenPlaceholder($timeperiods),
					'class' => 'chosen',
					'multiple' => false,
					'style' => 'width:100%;',
					'label' => __('Timeperiod'),
					'data-placeholder' => __('Please choose a timeperiod'),
					'selected' => $this->request->data['Hostdependency']['timeperiod_id']
				]);
				?>
				<br />
				<?php
				echo $this->Form->fancyCheckbox('inherits_parent', [
								'div' => 'form-group',
								'caption' => __('Inherits parent'),
								'wrapGridClass' => 'col col-xs-10',
								'captionGridClass' => 'col col-md-2 no-padding',
								'captionClass' => 'col col-md-2 control-label',
								'icon' => '<i class="fa fa-link"></i> ',
								'checked' => (boolean)$this->request->data['Hostdependency']['inherits_parent']
							]);
			?>
			<br class="clearfix" />
			<fieldset>
				<legend class="font-sm">
					<label ><?php echo __('Execution failure criteria'); ?></label>
					<?php if(isset($validation_host_notification)): ?>
						<span class="text-danger"><?php echo $validation_host_notification; ?></span>
					<?php endif; ?>
				</legend>
				<?php
					$dependency_options = [
									'execution_fail_on_up' => 'fa-square txt-color-greenLight',
									'execution_fail_on_down' => 'fa-square txt-color-redLight',
									'execution_fail_on_unreachable' => 'fa-square txt-color-blueDark',
									'execution_fail_on_pending' => 'fa-square-o',
									'execution_none' => 'fa-minus-square-o'
					];
				foreach($dependency_options as $dependency_option => $icon):?>
				<div style="border-bottom:1px solid lightGray;">
					<?php echo $this->Form->fancyCheckbox($dependency_option, array(
						'caption' => ucfirst(preg_replace('/(execution_|fail_on_)/','',$dependency_option)),
						'icon' => '<i class="fa '.$icon.'"></i> ',
						'checked' => (boolean)$this->request->data['Hostdependency'][$dependency_option]
					)); ?>
					<div class="clearfix"></div>
				</div>
				<?php endforeach;?>
			</fieldset>
			<br class="clearfix" />
			<fieldset>
				<legend class="font-sm">
					<label ><?php echo __('Notification failure criteria'); ?></label>
					<?php if(isset($validation_host_notification)): ?>
						<span class="text-danger"><?php echo $validation_host_notification; ?></span>
					<?php endif; ?>
				</legend>
				<?php
					$dependency_options = [
									'notification_fail_on_up' => 'fa-square txt-color-greenLight',
									'notification_fail_on_down' => 'fa-square txt-color-redLight',
									'notification_fail_on_unreachable' => 'fa-square txt-color-blueDark',
									'notification_fail_on_pending' => 'fa-square-o',
									'notification_none' => 'fa-minus-square-o '
					];
				foreach($dependency_options as $dependency_option => $icon):?>
				<div style="border-bottom:1px solid lightGray;">
					<?php echo $this->Form->fancyCheckbox($dependency_option, array(
						'caption' => ucfirst(preg_replace('/(notification_|fail_on_)/','',$dependency_option)),
						'icon' => '<i class="fa '.$icon.'"></i> ',
						'checked' => (boolean)$this->request->data['Hostdependency'][$dependency_option]
					)); ?>
					<div class="clearfix"></div>
				</div>
				<?php endforeach;?>
			</fieldset>
			<br />
			<br />
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>