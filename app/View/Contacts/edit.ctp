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
			<i class="fa fa-user fa-fw "></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Contacts'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-user"></i> </span>
		<h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?> <?php echo __('contact'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php if($this->Acl->hasPermission('delete')): ?>
				<?php echo $this->Utils->deleteButton(null, $contact['Contact']['id']);?>
			<?php endif; ?>
			<?php echo $this->Utils->backButton() ?>
		</div>
		<div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
			<?php echo __('UUID: %s', h($contact['Contact']['uuid'])); ?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Contact', array(
					'class' => 'form-horizontal clear'
				));

				if($hasRootPrivileges):
					echo $this->Form->input('Container', array(
							'options' => $containers,
							'multiple' => true,
							'class' => 'chosen',
							'style' => 'width: 100%',
							'label' => __('Container'),
							'div' => [
								'class' => 'form-group required'
							]
						)
					);
				elseif(!$hasRootPrivileges && isset($this->request->data['Container']['Container']) && !in_array(ROOT_CONTAINER, $this->request->data['Container']['Container'])):
					echo $this->Form->input('Container', array(
							'options' => $containers,
							'multiple' => true,
							'class' => 'chosen',
							'style' => 'width: 100%',
							'label' => __('Container'),
							'div' => [
								'class' => 'form-group required'
							]
						)
					);
				elseif(!$hasRootPrivileges && !empty(Hash::extract($this->request->data, 'Container.{n}.id'))  && !in_array(ROOT_CONTAINER, Hash::extract($this->request->data, 'Container.{n}.id'))):
					echo $this->Form->input('Container', array(
							'options' => $containers,
							'multiple' => true,
							'class' => 'chosen',
							'style' => 'width: 100%',
							'label' => __('Container'),
							'div' => [
								'class' => 'form-group required'
							]
						)
					);
				else:
					?>
					<div class="form-group required">
						<label class="col col-md-2 control-label" ><?php echo __('Container'); ?></label>
						<div class="col col-xs-10 required"><input type="text" value="/root" class="form-control" readonly></div>
					</div>
					<?php
					if(isset($this->request->data['Container']['Container'])):
						foreach($this->request->data['Container']['Container'] as $container_id):
							echo '<input id="ContainerContainer" class="form-control" type="hidden" value="'.$container_id.'" name="data[Container][Container][]">';
						endforeach;
					else:
						foreach(Hash::extract($this->request->data, 'Container.{n}.id') as $container_id):
							echo '<input id="ContainerContainer" class="form-control" type="hidden" value="'.$container_id.'" name="data[Container][Container][]">';
						endforeach;
					endif;
				endif;
				

				echo $this->Form->input('name', array('value' => $this->request->data['Contact']['name']));
				echo $this->Form->input('Contact.description', array('value' => $this->request->data['Contact']['description']));
				echo $this->Form->input('Contact.email', array('value' => $this->request->data['Contact']['email']));
				echo $this->Form->input('Contact.phone', array('value' => $this->request->data['Contact']['phone']));
				echo $this->Form->input('Contact.id', array('type' => 'hidden', 'value' => $contact['Contact']['id']));
				?>
			<br />
			<div class="row">
				<?php $notification_settings = array(
												'host' => array(
													'notify_host_recovery' => 'fa-square txt-color-greenLight',
													'notify_host_down' => 'fa-square txt-color-redLight',
													'notify_host_unreachable' => 'fa-square txt-color-blueDark',
													'notify_host_flapping' => 'fa-random',
													'notify_host_downtime' => 'fa-clock-o'
												),
												'service' => array(
													'notify_service_recovery' => 'fa-square txt-color-greenLight',
													'notify_service_warning' => 'fa-square txt-color-orange',
													'notify_service_unknown' => 'fa-square txt-color-blueDark',
													'notify_service_critical' => 'fa-square txt-color-redLight',
													'notify_service_flapping' => 'fa-random',
													'notify_service_downtime' => 'fa-clock-o')
											);
				?>
				<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
					<div id="wid-id-1" class="jarviswidget jarviswidget-sortable" data-widget-custombutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
						<header role="heading">
							<span class="widget-icon">
								<i class="fa fa-desktop"></i>
							</span>
							<h2><?php echo __('Notification (Host)'); ?></h2>
						</header>
						<div role="content" style="min-height:400px;">
							<div class="widget-body">
								<div>
								<?php
								echo $this->Form->input('host_timeperiod_id', [
									'options' => $this->Html->chosenPlaceholder($_timeperiods),
									'selected' => $this->request->data['Contact']['host_timeperiod_id'],
									'class' => 'select2 col-xs-8 chosen',
									'wrapInput' => 'col col-xs-8',
									'label' => [
										'class' => 'col col-md-4 control-label text-left'
									],
									'style' => 'width: 100%'
								]);
								
								echo $this->Form->input('Contact.HostCommands', [
									'type' => 'select',
									'options' => $this->Html->chosenPlaceholder($notification_commands),
									'selected' => $this->request->data['Contact']['HostCommands'],
									'class' => 'select2 col-xs-8 chosen',
									'wrapInput' => 'col col-xs-8',
									'label' => [
										'class' => 'col col-md-4 control-label text-left'
									],
									'multiple' => true,
									'style' => 'width: 100%'
								]);
								
								echo $this->Form->fancyCheckbox('host_notifications_enabled', [
									'caption' => __('Notifications enabled'),
									'captionGridClass' => 'col col-md-4 no-padding',
									'captionClass' => 'control-label text-left no-padding',
									'checked' => (boolean)$this->request->data['Contact']['host_notifications_enabled']
								]); ?>

								</div>
								<br class="clearfix" />
								<fieldset>
									<legend class="font-sm">
										<div class="required">
											<label ><?php echo __('Host notification options'); ?></label>
										</div>
										<?php if(isset($validation_host_notification)): ?>
											<span class="text-danger"><?php echo $validation_host_notification; ?></span>
										<?php endif; ?>
									</legend>
									<?php foreach($notification_settings['host'] as $notification_setting => $icon):?>
									<div style="border-bottom:1px solid lightGray;">
										<?php echo $this->Form->fancyCheckbox($notification_setting, array(
											'caption' => ucfirst(preg_replace('/notify_host_/','',$notification_setting)),
											'icon' => '<i class="fa '.$icon.'"></i> ',
											'checked' => (boolean)$this->request->data['Contact'][$notification_setting]
										)); ?>
										<div class="clearfix"></div>
									</div>
									<?php endforeach;?>
								</fieldset>
							</div>
						</div>
					</div>
				</article>
				<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
					<div id="wid-id-1" class="jarviswidget jarviswidget-sortable" data-widget-custombutton="false" data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
						<header role="heading">
							<span class="widget-icon">
								<i class="fa fa-gear"></i>
							</span>
							<h2><?php echo __('Notification (Service)'); ?></h2>
						</header>
						<div role="content" style="min-height:400px;">
							<div class="widget-body">
								<div>
								<?php
								echo $this->Form->input('service_timeperiod_id', [
									'options' => $this->Html->chosenPlaceholder($_timeperiods),
									'selected' => $this->request->data['Contact']['service_timeperiod_id'],
									'class' => 'select2 col-xs-8 chosen',
									'wrapInput' => 'col col-xs-8',
									'label' => [
										'class' => 'col col-md-4 control-label text-left'
									],
									'style' => 'width: 100%'
								]);
								
								echo $this->Form->input('Contact.ServiceCommands', [
									'type' => 'select',
									'options' => $this->Html->chosenPlaceholder($notification_commands),
									'selected' => $this->request->data['Contact']['ServiceCommands'],
									'class' => 'select2 col-xs-8 chosen',
									'wrapInput' => 'col col-xs-8',
									'label' => [
										'class' => 'col col-md-4 control-label text-left'
									],
									'multiple' => true,
									'style' => 'width: 100%'
								]);
									
								echo $this->Form->fancyCheckbox('service_notifications_enabled', [
									'caption' => __('Notifications enabled'),
									'captionGridClass' => 'col col-md-4 no-padding',
									'captionClass' => 'control-label text-left no-padding',
									'checked' => (boolean)$this->request->data['Contact']['service_notifications_enabled']
								]); ?>

								</div>
								<br class="clearfix" />
								<fieldset>
									<legend class="font-sm">
										<div class="required">
											<label ><?php echo __('Service notification options'); ?></label>
										</div>
										<?php if(isset($validation_service_notification)): ?>
											<span class="text-danger"><?php echo $validation_service_notification; ?></span>
										<?php endif; ?>
									</legend>
									<?php foreach($notification_settings['service'] as $notification_setting => $icon):?>
										<div style="border-bottom:1px solid lightGray;">
											<?php echo $this->Form->fancyCheckbox($notification_setting, array(
												'caption' => ucfirst(preg_replace('/notify_service_/','',$notification_setting)),
												'icon' => '<i class="fa '.$icon.'"></i> ',
												'checked' => (boolean)$this->request->data['Contact'][$notification_setting]
											)); ?>
											<div class="clearfix"></div>
										</div>
									<?php endforeach;?>
								</fieldset>
							</div>
						</div>
					</div>
				</article>
			</div>
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>