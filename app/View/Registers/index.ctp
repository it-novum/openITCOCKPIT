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
			<i class="fa fa-check-square-o fa-fw "></i> 
				<?php echo __('System'); ?> 
			<span>> 
				<?php echo __('Registration'); ?>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
		<h2><?php echo __('Register your installation of openITCOCKPIT'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<a class="btn btn-default btn-xs" href="javascript:void(0);" name="creditos"><i class="fa fa-users"></i> <?php echo __('Credits'); ?></a>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Register', array(
					'class' => 'form-horizontal clear'
				));
				?>

				
				<?php echo $this->Form->input('license', ['label' => __('License key'), 'value' => (isset($licence['Register']['license']))?$licence['Register']['license']:'']); ?>
				<div class="form-group text-muted">
					<span class="col col-md-2 hidden-tablet hidden-mobile"><!-- spacer for nice layout --></span>
					<div class="col col-xs-10"><?php echo __('No license key?'); ?><a class="txt-color-blueDark" href="http://www.it-novum.com/en/support-openitcockpit-en.html"> <?php echo __('Please visit our homepage to get in contact.'); ?></a></div>
				</div>
				
			<?php echo $this->Form->formActions(__('Register')); ?>
		</div>
	</div>
</div>