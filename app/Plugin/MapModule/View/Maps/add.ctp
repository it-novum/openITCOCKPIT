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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-map-marker fa-fw "></i> 
				<?php echo __('Map');?>
			<span>> 
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
		<h2><?php echo __('Add map'); ?></h2>
		<div class="widget-toolbar" role="menu">
			<?php echo $this->Utils->backButton();?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php 
				echo $this->Form->create('Map', array(
					'class' => 'form-horizontal clear'
				));
				echo $this->Form->input('Map.name', ['label' => __('Map Name')]);
				echo $this->Form->input('Map.title', ['label' => __('Map Title')]);
				echo $this->Form->input('container_id', ['options' => $tenants, 'multiple' => true, 'class' => 'chosen', 'style' => 'width:100%;', 'label' => __('Tenant')]);
			?>
			<br>
			<br>
			<?php echo $this->Form->formActions(); ?>
		</div>
	</div>
</div>

