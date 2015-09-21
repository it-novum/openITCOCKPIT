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
<div class="widget-body trafficLight-body">
	<div class="padding-10">
		<div style="border:1px solid #c3c3c3;" class="padding-10">
			<div class="row">
				<div class="col-xs-10">
					<select class="chosen" placeholder="<?php echo __('Please select'); ?>">
						<option></option>
						<?php foreach($widgetServicesForTrafficlight as $serviceId => $serviceName):?>
							<?php
								$selected = '';
								if($widget['Widget']['service_id'] !== null && $serviceId == $widget['Widget']['service_id']):
									$selected = 'selected="selected"';
								endif;
							?>
							<option value="<?php echo $serviceId; ?>" <?php echo $selected; ?>><?php echo h($serviceName); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-xs-2 text-right">
					<a href="javascript:void(0);" class="btn btn-primary btn-xs margin-left-10 saveTrafficlight" data-widget-id="<?php echo h($widget['Widget']['id']); ?>"><?php echo __('Save');?></a>
				</div>
			</div>
		</div>
	</div>
	<center>
		<?php if($widget['Widget']['service_id'] !== null && $this->Acl->hasPermission('browser', 'services')): ?>
			<a href="/services/browser/<?php echo $widget['Widget']['service_id']; ?>">
				<div class="trafficlightContainer" data-current-state="1" data-is-flapping="0"></div>
			</a>
		<?php else: ?>
			<div class="trafficlightContainer" data-current-state="3" data-is-flapping="0"></div>
		<?php endif; ?>
	</center>
</div>

