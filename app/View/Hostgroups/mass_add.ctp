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
				<?php echo __('Append'); ?> <?php echo $this->Utils->pluralize($hostsToAppend, __('host'), __('hosts'));?> <?php echo __('to hostgroup'); ?>
			</span>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
		<h2 class="hidden-mobile hidden-tablet"><?php echo __('Append'); ?> <?php echo $this->Utils->pluralize($hostsToAppend, __('host'), __('hosts'));?> <?php echo __('to hostgroup'); ?></h2>
		<div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
			<?php echo $this->Utils->backButton(__('Back'), $back_url);?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<div class="row">
				<div class="col-xs-12 col-md-12 col-lg-12">
					<?php
					echo $this->Form->create('Hostgroup', [
						'class' => 'form-horizontal clear'
					]);

					echo $this->Form->fancyCheckbox('Hostgroup.create', [
						'caption' => __('Create hostgroup'),
						'captionGridClass' => 'col col-xs-3',
						'checked' => false,
						'wrapGridClass' => 'col col-xs-3',
					]);
					?>
				</div>
				<div class="col-xs-12 col-md-12 col-lg-12">
					<div id="existingHostgroup" class="padding-top-10">
						<?php
						echo $this->Form->input('Hostgroup.id', [
							'options' => $this->Html->chosenPlaceholder($hostgroups),
							'data-placeholder' => __('Please select...'),
							'class' => 'chosen',
							'label' => [
								'text' => __('Hostgroup'),
							],
						]);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-md-12 col-lg-12 padding-top-10 padding-left-30">
					<div id="createHostgroup" style="display:none;">
						<?php
						echo $this->Form->input('Container.parent_id', [
							'options' => $containers,
							'selected' => $user_container_id,
							'class' => 'chosen',
							'style' => 'width: 100%;',
							'label' => [
								//'class' => 'col col-xs-1 control-label',
								'text' => __('Container'),
							],
							'SelectionMode' => 'single',
							//'wrapInput' => 'col col-xs-11',
						]);
						echo $this->Form->input('Container.name', [
							//'wrapInput' => 'col col-xs-11',
							'label' => [
								//'class' => 'col col-xs-1 control-label',
								'text' => __('Hostgroup name'),
							],
						]);
						?>
					</div>
						<i class="fa fa-plus text-success"></i> <strong><?php echo __('The following hosts will append to selected hostgroup'); ?>:</strong>
						<ul>
							<?php foreach($hostsToAppend as $hostToAppend): ?>
								<li>
									<?php echo h($hostToAppend['Host']['name']); ?>
									<?php echo $this->Form->input('Host.id.'.$hostToAppend['Host']['id'], ['value' => $hostToAppend['Host']['id'], 'type' => 'hidden']); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div> <!-- close col -->
				</div> <!-- close row-->
				<br />
			<?php echo $this->Form->formActions(); ?>
		</div> <!-- close widget body -->
	</div>
</div> <!-- end jarviswidget -->
