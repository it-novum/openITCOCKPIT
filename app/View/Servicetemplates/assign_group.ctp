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
				<?php echo $this->Utils->pluralize($servicetemplates, __('Servicetemplates'), __('Servicetemplates'));?>
			</span>
			<div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
		</h1>
	</div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
	<header>
		<span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
		<h2 class="hidden-mobile hidden-tablet"><?php echo __('Allocate to Servicetemplategroup'); ?></h2>
		<div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
			<?php echo $this->Utils->backButton(__('Back'), $back_url);?>
		</div>
	</header>
	<div>
		<div class="widget-body">
			<?= $this->Form->create('service-form', [
					'class' => 'form-horizontal clear'
				]).
				$this->Form->input('Container.parent_id', ['type' => 'hidden', 'value' => $checkedContanerId]).
				$this->Form->input('container-name', ['value' => $checkedContanerName, 'readonly'=>'readonly', 'type' => 'text', 'style' => 'width: 100%;', 'label' => __('Container')]);
			?>
			<div class="form-group">
				<div class="col col-xs-10 col-xs-offset-2">
					<?= $this->Form->checkbox('new', ['id' => 'new-stgroup']); ?>
					<?= $this->Form->label('label-for-new', __('Create new Servicetemplategroup'),['for' => 'new-stgroup']); ?>
				</div>
			</div>
			<div id="new-to-holder" style="display:none">
				<?= $this->Form->input('Container.name', ['type' => 'text', 'style' => 'width: 100%;', 'label' => __('Servicetemplategroup')]); ?>
				<?= $this->Form->input('Servicetemplategroup.description', ['label' => __('Description')]) ?>
			</div>
			<div id="assign-to-holder">
				<?= $this->Form->input('Servicetemplategroup.id', ['options' => $servicetemplateGroupList, 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => __('Servicetemplategroup')]); ?>
			</div>
			<?= $this->Form->input('Servicetemplategroup.Servicetemplate', ['options' => $allServicetemplates, 'class' => 'chosen', 'multiple' => true, 'style' => 'width:100%;', 'label' => __('Servicetemplates'), 'data-placeholder' => __('Please choose a servicetemplate'),'selected' => array_keys($myServiceTemplates)]); ?>
			<?= $this->Form->formActions(); ?>
		</div> <!-- close widget body -->
	</div>
</div> <!-- end jarviswidget -->