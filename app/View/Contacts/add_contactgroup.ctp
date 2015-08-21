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
			<i class="fa fa-terminal fa-fw "></i> 
				<?php echo __('Monitoring'); ?> 
			<span>> 
				<?php echo __('Contact groups'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
		<h2><?php echo __('Add contact group'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->backButton();?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Contact', array(
					'class' => 'form-horizontal clear'
				));
				echo $this->Form->input('Container.parent_id', ['options' => $containers, 'selected' => $user_container_id, 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => __('Container')]);
				echo $this->Form->input('Container.name', ['label' => __('Contact group name')]);
				echo $this->Form->input('Contactgroup.description', ['label' => __('Description')]);
				echo $this->Form->input('Contact.id', ['options' => $contacts, 'class' => 'chosen', 'multiple' => true, 'style' => 'width:100%;', 'label' => __('Contacts'), 'data-placeholder' => __('Please choose a contact')]);
			?>
			<br />
			<br />
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>