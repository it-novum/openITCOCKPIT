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
			<i class="fa fa-pencil-square-o fa-fw "></i>
			<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo $this->Utils->pluralize($contacts, __('Contacts'), __('Contacts'));?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
		<h2 class="hidden-mobile hidden-tablet"><?php echo __('Copy'); ?> <?php echo $this->Utils->pluralize($contacts, __('contacts'), __('contacts'));?></h2>
		<div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
			<?php echo $this->Utils->backButton(__('Back'), $back_url);?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
			echo $this->Form->create('Contact', [
				'class' => 'form-horizontal clear'
			]); ?>
			<?php foreach($contacts as $key => $contact):?>
				<div class="row">
					<div class="col-xs-12 col-md-9 col-lg-7">
						<fieldset>
							<legend><?php echo $contact['Contact']['name']?></legend>
							<?php
							echo $this->Form->input('Contact.'.$key.'.name', ['value' => $contact['Contact']['name'], 'label' => __('Contactname'), 'required' => true]);
							echo $this->Form->input('Contact.'.$key.'.description', ['value' => $contact['Contact']['description'], 'label' => __('Description'), 'required' => false]);
							echo $this->Form->input('Contact.'.$key.'.email', ['value' => $contact['Contact']['email'], 'label' => __('Description'), 'required' => true]);
							echo $this->Form->input('Contact.'.$key.'.phone', ['value' => $contact['Contact']['phone'], 'label' => __('Phone'), 'required' => true]);
							echo $this->Form->input('Contact.'.$key.'.source', ['value' => $contact['Contact']['id'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.host_timeperiod_id', ['value' => $contact['Contact']['host_timeperiod_id'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.service_timeperiod_id', ['value' => $contact['Contact']['service_timeperiod_id'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.host_notifications_enabled', ['value' => $contact['Contact']['host_notifications_enabled'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.service_notifications_enabled', ['value' => $contact['Contact']['service_notifications_enabled'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_recovery', ['value' => $contact['Contact']['notify_service_recovery'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_warning', ['value' => $contact['Contact']['notify_service_warning'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_unknown', ['value' => $contact['Contact']['notify_service_unknown'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_critical', ['value' => $contact['Contact']['notify_service_critical'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_flapping', ['value' => $contact['Contact']['notify_service_flapping'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_service_downtime', ['value' => $contact['Contact']['notify_service_downtime'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_host_recovery', ['value' => $contact['Contact']['notify_host_recovery'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_host_down', ['value' => $contact['Contact']['notify_host_down'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_host_unreachable', ['value' => $contact['Contact']['notify_host_unreachable'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_host_flapping', ['value' => $contact['Contact']['notify_host_flapping'], 'type' => 'hidden']);
							echo $this->Form->input('Contact.'.$key.'.notify_host_downtime', ['value' => $contact['Contact']['notify_host_downtime'], 'type' => 'hidden']);
							?>
						</fieldset>
					</div> <!-- close col -->
				</div> <!-- close row-->
			<?php endforeach; ?>
			<br />
			<?php echo $this->Form->formActions(); ?>
		</div> <!-- close widget body -->
	</div>
</div> <!-- end jarviswidget -->