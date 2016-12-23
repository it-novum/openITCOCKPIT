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
			<i class="fa fa-star fa-fw "></i>
				<?php echo __('Edit OpenID Connect');?>
			<span>>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-star"></i> </span>
		<h2><?php echo __('Edit OpenID Connect'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->backButton();?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Openid', array(
					'class' => 'form-horizontal clear'
				));
				echo $this->Form->input('id', ['type' => 'hidden', 'value' => $openID['Openid']['id']]);
				echo $this->Form->input('Openid.my_domain', ['label' => __('Domain'), 'placeholder' => 'openitcockpit.org', 'value' => $openID['Openid']['my_domain']]);
				echo $this->Form->input('returnUrl', ['label' => __('Return Url'), 'readOnly' => true, 'value' => $returnUrl]);
				echo $this->Form->input('Openid.identity', ['label' => __('Identity'), 'value' => $openID['Openid']['identity']]);
				echo $this->Form->input('Openid.client_secret', ['label' => __('Client secret'), 'value' => $openID['Openid']['client_secret']]);
			?>
			<div class="form-group">
			<?php
				echo $this->Form->fancyCheckbox('Openid.show_login_page', [
					'caption' => __('Show login page'),
					'captionGridClass' => 'col col-md-2 text-right',
					'class' => 'onoffswitch-checkbox notification_control',
					'checked' => $openID['Openid']['show_login_page'],
					'wrapGridClass' => 'col col-xs-2',
				]);
			?>
			</div>
			<?php
				echo $this->Form->input('Openid.button_text', ['label' => __('Button text'), 'value' => $openID['Openid']['button_text']]);
			?>
			<div class="form-group">
				<?php
				echo $this->Form->fancyCheckbox('Openid.active', [
					'caption' => __('Active'),
					'captionGridClass' => 'col col-md-2 text-right',
					'class' => 'onoffswitch-checkbox notification_control',
					'checked' => $openID['Openid']['active'],
					'wrapGridClass' => 'col col-xs-2',
				]);
				?>
			</div>
			<br>
			<br>
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>

