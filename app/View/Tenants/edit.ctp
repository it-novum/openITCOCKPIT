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
			<i class="fa fa-home fa-fw "></i>
				<?php echo __('System'); ?>
			<span>>
				<?php echo __('Tenants'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-home"></i> </span>
		<h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?> <?php echo __('tenant'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php if($this->Acl->hasPermission('delete')): ?>
				<a href="javascript:void(0);" id="deleteAll" class="btn btn-danger btn-xs" style="text-decoration: none;"> <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?></a>
				<?php //echo $this->Utils->deleteButton(null, $tenant['Tenant']['id']);?>
			<?php endif;?>
			<?php echo $this->Utils->backButton() ?>
		</div>
	</header>
	<input class="massChange" checked style="display: none;" type="checkbox" name="tenant[<?php echo $tenant['Tenant']['id']; ?>]" tenantname="<?php echo h($tenant['Container']['name']); ?>" value="<?php echo $tenant['Tenant']['id']; ?>" />
	<input type="hidden" id="delete_message_h1" value="<?php echo __('Attention!'); ?>" />
	<input type="hidden" id="delete_message_h2" value="<?php echo __('Do you really want delete the selected tenant? All nodes, contacts, contactgroups, locations, devicegroups, calendars, timeperiods, hosttemplates, hostgroups, hosts, servicetemplates, servicetemplategroups, servicegroups and services from this tenant will be deleted too.'); ?>" />
	<input type="hidden" id="message_yes" value="<?php echo __('Yes'); ?>" />
	<input type="hidden" id="message_no" value="<?php echo __('No'); ?>" />
	<div>
		<div class="widget-body">
			<?php
			echo $this->Form->create('Tenant', array(
				'class' => 'form-horizontal clear'
			));
			echo $this->Form->input('Tenant.id', array('type' => 'hidden'));
			echo $this->Form->input('Container.name', ['value' => $tenant['Container']['name']]);
			echo $this->Form->input('description', ['value' => $tenant['Tenant']['description']]);
			echo $this->Form->fancyCheckbox('is_active', [
				'captionGridClass' => 'col col-md-2',
				'captionClass' => 'control-label',
				'wrapGridClass' => 'col col-md-10',
				'caption' => __('is active'),
				'value' => $tenant['Tenant']['is_active'],
				'on' => __('Yes'),
				'off' => __('No')
			]);
			?>
			<br /><br/>
			<?php echo $this->Form->input('Tenant.expires', [
					'value' => (isset($tenant['Tenant']['expires']))?date(PHP_DATEFORMAT, strtotime($tenant['Tenant']['expires'])):'',
					'wrapInput' => 'col col-xs-10 col-md-3',
					'label' => __('Expiration date'),
					'type' => 'text'
				]
			); ?>
			<br />
			<?php
			echo $this->Form->input('firstname', ['value' => $tenant['Tenant']['firstname']]);
			echo $this->Form->input('lastname', ['value' => $tenant['Tenant']['lastname']]);
			echo $this->Form->input('street', ['value' => $tenant['Tenant']['street']]);
			echo $this->Form->input('zipcode', ['value' => $tenant['Tenant']['zipcode']]);
			echo $this->Form->input('city', ['value' => $tenant['Tenant']['city']]);
			?>
			<br />
			<?php
			echo $this->Form->input('max_users', ['value' => $tenant['Tenant']['max_users'], 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
			echo $this->Form->input('max_hosts', ['value' => $tenant['Tenant']['max_hosts'], 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
			echo $this->Form->input('max_services', ['value' => $tenant['Tenant']['max_services'], 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
			?>
			<span class="note hintmark_before"><?php echo __('enter 0 for infinity'); ?></span>
			<br/ ><br />
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>