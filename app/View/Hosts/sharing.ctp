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
			<i class="fa fa-sitemap fa-rotate-270"></i>
				<?php echo __('Monitoring'); ?>
			<span>>
				<?php echo __('Host'); ?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-sitemap fa-rotate-270"></i> </span>
		<h2 class="hidden-mobile hidden-tablet"><?php echo __('Sharing');?></h2>
		<div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
			<?php echo $this->Utils->backButton(__('Back'));?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?php
				echo $this->Form->create('Host', [
					'class' => 'form-horizontal clear'
				]); ?>
				<div class="row">
					<?php
						echo $this->Form->input('Host.id', [
								'type' => 'hidden',
								'value' => $host['Host']['id'],
								'wrapInput' => 'col col-xs-8',
							]
						);
						echo $this->Form->input('container_id', [
								'type' => 'hidden',
								'value' => $host['Host']['container_id']
							]
						);
						echo $this->Form->input('host_container_id', [
								'options' => $containers,
								'multiple' => false,
								'selected' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
								'class' => 'chosen',
								'style' => 'width: 100%',
								'label' => __('Primary container'),
								'wrapInput' => 'col col-xs-8',
								'disabled' => true
							]
						);
						echo $this->Form->input('Container', [
								'options' => $sharingContainers,
								'multiple' => true,
								'selected' => $this->Html->getParameter('Container.Container', Hash::extract($host['Container'], '{n}.id')),
								'class' => 'chosen',
								'style' => 'width: 100%',
								'label' => __('Shared containers'),
								'wrapInput' => 'col col-xs-8'
							]
						);
					?>
				</div> <!-- close col -->
			</div> <!-- close row-->
			<br />
			<?php echo $this->Form->formActions(); ?>
		</div> <!-- close widget body -->
	</div>
</div> <!-- end jarviswidget -->
