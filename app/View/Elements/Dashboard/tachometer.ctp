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
<div class="widget-body trafficLight-body">
	<div class="padding-10">
		<div style="border:1px solid #c3c3c3;" class="padding-10">
			<div class="row">
				<div class="col-xs-12">
					<select class="chosen trafficLightSelectService" data-widget-id="<?php echo $widget['Widget']['id']; ?>" placeholder="<?php echo __('Please select'); ?>" style="width:100%;">
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
					<?php echo $this->Form->create('Tacho');?>
					<?php echo $this->Form->input('min'); ?>
					<?php echo $this->Form->input('max'); ?>
					<?php echo $this->Form->input('warn'); ?>
					<?php echo $this->Form->input('crit'); ?>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
	<center>
		<div class="tachometerWrapper">
			<?php if($serviceId && $this->Acl->hasPermission('browser', 'services')): ?>
				<a href="/services/browser/<?php echo $widget['Widget']['service_id']; ?>">
					<div class="tachometerContainer" data-check-interval="<?php echo $widgetData['Service']['Servicestatus']['normal_check_interval']; ?>" data-id-service="<?php echo $serviceId; ?>"></div>
				</a>
			<?php else: ?>
				<div class="tachometerContainer" data-id-service="0">
					<?php echo __('No service selected or selected service has been deleted');?>
				</div>
			<?php endif; ?>
		</div>
	</center>
</div>

