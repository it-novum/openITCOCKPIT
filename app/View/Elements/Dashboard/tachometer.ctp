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

$widgetTachoId = null;
if(isset($widgetData['WidgetTacho']['id']) && $widgetData['WidgetTacho']['id'] !== null && is_numeric($widgetData['WidgetTacho']['id'])):
	$widgetTachoId = $widgetData['WidgetTacho']['id'];
endif;

$serviceId = null;
if(!empty($widgetData['Service'])):
	$serviceId = $widgetData['Service']['Service']['id'];
	$selectedServiceName = $widgetData['Service']['Service']['name'];
	if($selectedServiceName === null || $selectedServiceName === ''):
		$selectedServiceName = $widgetData['Service']['Servicetemplate']['name'];
	endif;
endif;
?>
<div class="widget-body tacho-body" style="padding:0;">
	<div style="display:none;" class="tachoPreviewContainer">
		<div class="pull-right padding-right-20"><a href="javascript:void(0);" class="btn btn-danger close-preview" data-widget-id="<?php echo $widget['Widget']['id']; ?>"><?php echo __('Close'); ?></a></div>
		<div class="tachometerContainer" data-service-id="<?php echo $serviceId; ?>">
			<!-- canvas object will be created by javascript -->
		</div>
	</div>
	
	<div class="panel-group smart-accordion-default" id="accordion-<?php echo $widget['Widget']['id'];?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php if($serviceId === null): ?>
						<a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id'];?>" href="#collapseOne-1-<?php echo $widget['Widget']['id'];?>" aria-expanded="true" class="">
							<i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('Configuration'); ?>
						</a>
					<?php else: ?>
						<a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id'];?>" href="#collapseOne-1-<?php echo $widget['Widget']['id'];?>" aria-expanded="false" class="collapsed">
							<i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('Configuration'); ?>
						</a>
					<?php endif; ?>
				</h4>
			</div>
			<div id="collapseOne-1-<?php echo $widget['Widget']['id'];?>" class="panel-collapse collapse <?php echo ($serviceId === null)?'in':''; ?>" aria-expanded="true">
				<div class="panel-body">
					<div>
						<div class="row">
							<div class="col-xs-12">
								<?php
								echo $this->Form->create('dashboard', [
									'class' => 'clear',
									'action' => 'saveTachoConfig',
									'id' => 'TachoForm-'.$widget['Widget']['id']
								]); ?>
								<select class="chosen tachoSelectService" data-widget-id="<?php echo $widget['Widget']['id']; ?>" placeholder="<?php echo __('Please select'); ?>" name="data[dashboard][serviceId]" style="width:100%;">
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
								<br />
								<br />
							</div>
							<div class="col-xs-12">
								<div class="inputWrap">
									<?php
									if($widgetData['WidgetTacho']['data_source'] != null):
										echo $this->Form->input('ds', [
											'label' => __('Datasource'),
											'options' => [$widgetData['WidgetTacho']['data_source'] => $widgetData['WidgetTacho']['data_source']],
											'class' => 'form-control tacho-ds',
											'data-widget-id' => $widget['Widget']['id'],
											'selected' => $widgetData['WidgetTacho']['data_source'],
											'form' => 'TachoForm-'.$widget['Widget']['id'],
										]);
									else:
										echo $this->Form->input('ds', [
											'label' => __('Datasource'),
											'options' => [],
											'class' => 'form-control tacho-ds',
											'data-widget-id' => $widget['Widget']['id'],
											'form' => 'TachoForm-'.$widget['Widget']['id'],
										]);
									endif;
									echo $this->Form->input('min', [
										'label' => __('Minimum'),
										'class' => 'form-control tacho-min',
										'data-field' => 'min',
										'data-widget-id' => $widget['Widget']['id'],
										'value' => $widgetData['WidgetTacho']['min'],
										'form' => 'TachoForm-'.$widget['Widget']['id'],
									]);
									echo $this->Form->input('max', [
										'label' => __('Maximum'),
										'class' => 'form-control tacho-max',
										'data-field' => 'max',
										'data-widget-id' => $widget['Widget']['id'],
										'value' => $widgetData['WidgetTacho']['max'],
										'form' => 'TachoForm-'.$widget['Widget']['id'],
									]);
									echo $this->Form->input('warn', [
										'label' => __('Warn %'),
										'class' => 'form-control tacho-warn',
										'data-field' => 'warn',
										'data-widget-id' => $widget['Widget']['id'],
										'value' => $widgetData['WidgetTacho']['warn'],
										'form' => 'TachoForm-'.$widget['Widget']['id'],
									]);
									echo $this->Form->input('crit', [
										'label' => __('Crit %'),
										'class' => 'form-control tacho-crit',
										'data-field' => 'crit',
										'data-widget-id' => $widget['Widget']['id'],
										'value' => $widgetData['WidgetTacho']['crit'],
										'form' => 'TachoForm-'.$widget['Widget']['id'],
									]);
									?>
								</div>
								<?php
								echo $this->Form->input('tabId', [
									'type' => 'hidden',
									'value' => $widget['Widget']['dashboard_tab_id'],
									'form' => 'TachoForm-'.$widget['Widget']['id'],
								]);
								echo $this->Form->input('widgetId', [
									'type' => 'hidden',
									'value' => $widget['Widget']['id'],
									'form' => 'TachoForm-'.$widget['Widget']['id'],
								]);
								if($widgetTachoId !== null):
									echo $this->Form->input('widgetTachoId', [
										'type' => 'hidden',
										'value' => $widgetTachoId,
										'form' => 'TachoForm-'.$widget['Widget']['id'],
									]);
								endif;
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
										'form' => 'TachoForm-'.$widget['Widget']['id'],
										'div' => false,
										'value' => 1
									]); ?>
								</div>
								<?php echo $this->Form->end(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php if($serviceId === null): ?>
						<a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id'];?>" href="#collapseTwo-1-<?php echo $widget['Widget']['id'];?>" aria-expanded="false" class="collapsed">
							<i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <?php echo __('No services selected'); ?>
						</a>
					<?php else:?>
						<a data-toggle="collapse" data-parent="#accordion-<?php echo $widget['Widget']['id'];?>" href="#collapseTwo-1-<?php echo $widget['Widget']['id'];?>" aria-expanded="true" class="">
							<i class="fa fa-lg fa-angle-down pull-right"></i> <i class="fa fa-lg fa-angle-up pull-right"></i> <?php echo h($selectedServiceName); ?>
						</a>
					<?php endif;?>
				</h4>
			</div>
			<div id="collapseTwo-1-<?php echo $widget['Widget']['id'];?>" class="panel-collapse collapse <?php echo ($serviceId === null)?'':'in'; ?>" aria-expanded="false">
				<div class="panel-body" style="padding:0;">
					<?php if($serviceId === null): ?>
						<?php echo __('No service selected or selected service has been deleted');?>
					<?php else: ?>
						<center>
							<?php if($serviceId && $this->Acl->hasPermission('browser', 'services')): ?>
								<a href="/services/browser/<?php echo $serviceId; ?>">
									<canvas id="canvas-<?php echo $widget['Widget']['id'];?>" data-check-interval="<?php echo $widgetData['Service']['Servicestatus']['normal_check_interval']; ?>"></canvas>
								</a>
							<?php else: ?>
								<canvas id="canvas-<?php echo $widget['Widget']['id'];?>" data-check-interval="<?php echo $widgetData['Service']['Servicestatus']['normal_check_interval']; ?>"></canvas>
							<?php endif; ?>
						</center>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
</div>

