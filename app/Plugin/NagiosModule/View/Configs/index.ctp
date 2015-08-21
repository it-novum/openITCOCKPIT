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
<div class="alert auto-hide alert-success" style="display:none;" id="flashMessage"><?php echo __('config file successfully saved'); ?></div>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-link fa-fw "></i> 
				<?php echo __('Nagios'); ?>
			<span>> 
				<?php echo __('Configuration files'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-link"></i> </span>
		<h2><?php echo __('Configuration files'); ?></h2>
		<div class="widget-toolbar" role="menu"></div>
	</header>
	<div>
		<div class="widget-body">
			<?php
			echo $this->Form->create('Config', [
				'class' => 'form-horizontal clear'
			]); ?>
			<?php echo $this->Form->input('configfile', ['options' => $configFiles, 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => 'Config file']); ?>
			<div class="pull-right padding-bottom-10 "><?php echo $this->Form->submit(__('Load config file'), ['class' => 'btn btn-default']); ?></div>
			<?php echo $this->Form->end(); ?>
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
				<div class="col-sm-12">
					<div class="alert alert-block alert-danger">
						<a href="#" data-dismiss="alert" class="close">×</a>
						<h4 class="alert-heading"><i class="fa-fw fa fa-warning"></i> <?php echo __('This might void your warranty!'); ?></h4>
						<?php echo __('Changing advanced settings in configuration files can be harmful to the stability, security and performance of your system. You should only continue if you are sure of what you are doing'); ?>
					</div>
					<div class="padding-bottom-10"><?php echo __('Current configuration file:');?> <span class="text-primary"><?php echo $currentConfigFile; ?></span></div>
					<div class="config_editor" contenteditable="true">
						<?php echo $content ?>
					</div>
					<br />
					<div class="well formactions ">
						<div class="pull-right">
							<input type="button" id="saveContent" value="<?php echo __('Save'); ?>" class="btn btn-primary">&nbsp;
							<a class="btn btn-default" href="/nagios_module/configs"><?php echo __('Cancel'); ?></a>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>