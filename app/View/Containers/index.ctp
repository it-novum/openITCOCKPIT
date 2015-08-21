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
			<i class="fa fa-link fa-fw "></i>
				<?php echo __('System'); ?>
			<span>>
				<?php echo __('Nodes'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-link"></i> </span>
		<h2><?php echo __('Edit containers'); ?></h2>
		<div class="widget-toolbar" role="menu"></div>
	</header>
	<div>
		<div class="widget-body">
			<?php


			echo $this->Form->input('tenats', [
				'options' => $tenants,
				'class' => 'select2 select_tenant',
				'selected' => $selected_tenant
			]); ?>
			<br />
			<div>
				<span class="ajax_loader text-center">
					<h1>
						<i class="fa fa-cog fa-lg fa-spin"></i>
					</h1>
					<br />
				</span>
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-6">
					<div class="jarviswidget" id="wid-id-0">
						<header>
							<span class="widget-icon"> <i class="fa fa-link"></i> </span>
							<h2><?php echo __('Tree'); ?></h2>
						</header>
						<div>
							<div class="widget-body">
								<div id="ajax_result"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-lg-6">
					<div class="jarviswidget" id="wid-id-0">
						<header>
							<span class="widget-icon"> <i class="fa fa-link"></i> </span>
							<h2><?php echo __('Add new node'); ?>:</h2>
						</header>
						<div>
							<div class="widget-body">
								<?php
								echo $this->Form->create('Container', [
									'class' => 'form-horizontal clear',
									'action' => 'index'
								]);
								echo $this->Form->input('selected_tenant', [
									'type' => 'hidden',
									'value' => $selected_tenant
								]);
								echo $this->Form->input('containertype_id', [
									'type' => 'hidden',
									'value' => CT_NODE
								]);
								?>
								<div id="ajax_parent_nodes"></div>
								<?php
								$options = [];
								if($validationError === false):
									$options = ['value' => false];
								endif;
								echo $this->Form->input('name', $options);
								echo $this->Form->formActions();
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>