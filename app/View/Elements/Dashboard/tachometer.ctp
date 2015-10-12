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

$widgetData = $widgetTachometers[$widget['Widget']['id']];
$serviceId = null;
if(!empty($widgetData['Service'])):
	$serviceId = $widgetData['Service']['Service']['id'];
endif;
?>
<div class="widget-body tacho-body">
	<div style="display:none;" class="tachoPreviewContainer">
		<div class="pull-right padding-right-20"><a href="javascript:void(0);" class="btn btn-danger close-preview" data-widget-id="<?php echo $widget['Widget']['id']; ?>"><?php echo __('Close'); ?></a></div>
		<div class="tachometerContainer">
			<!-- canvas object will be created by javascript -->
		</div>
	</div>
	<div class="padding-10" style="position:absolute; top:0px; left:0px; z-index:1;">
		<div class="row">
			<div class="col-xs-12">
				<select class="chosen tachoSelectService" data-widget-id="<?php echo $widget['Widget']['id']; ?>" placeholder="<?php echo __('Please select'); ?>" style="width:100%;">
					<option></option>
					<?php foreach($widgetServicesForTachometer as $_serviceId => $serviceName):?>
						<?php
							$selected = '';
							if($serviceId !== null && $_serviceId == $serviceId):
								$selected = 'selected="selected"';
							endif;
						?>
						<option value="<?php echo $_serviceId; ?>" <?php echo $selected; ?>><?php echo h($serviceName); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-xs-12">
				<?php
				echo $this->Form->create('dashboard', [
					'class' => 'clear',
					'action' => 'saveTachoConfig'
				]);
				echo $this->Form->input('ds', ['label' => __('Datasource'), 'options' => [], 'class' => 'form-control tacho-ds', 'data-widget-id' => $widget['Widget']['id']]);
				echo $this->Form->input('min', ['label' => __('Minimum'), 'class' => 'form-control tacho-min', 'data-field' => 'min', 'data-widget-id' => $widget['Widget']['id']]);
				echo $this->Form->input('max', ['label' => __('Maximum'), 'class' => 'form-control tacho-max', 'data-field' => 'max', 'data-widget-id' => $widget['Widget']['id']]);
				echo $this->Form->input('warn', ['label' => __('Warn %'), 'class' => 'form-control tacho-warn', 'data-field' => 'warn', 'data-widget-id' => $widget['Widget']['id']]);
				echo $this->Form->input('crit', ['label' => __('Crit %'), 'class' => 'form-control tacho-crit', 'data-field' => 'crit', 'data-widget-id' => $widget['Widget']['id']]);
				echo $this->Form->input('tabId', ['type' => 'hidden', 'value' => $widget['Widget']['dashboard_tab_id']]);
				echo $this->Form->input('widgetId', ['type' => 'hidden', 'value' => $widget['Widget']['id']]);
				?>
			</div>
			<div class="col-xs-12">
				<div class="pull-right padding-top-10">
					<a href="javascript:void(0);" class="btn btn-default previewTacho" data-widget-id="<?php echo $widget['Widget']['id']; ?>">
						<?php echo __('Preview'); ?>
					</a>
					<?php
					echo $this->Form->submit(__('Save'), [
						'class' => [
							'btn btn-primary'
						],
						'div' => false,
						'value' => 1
					]); ?>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>

