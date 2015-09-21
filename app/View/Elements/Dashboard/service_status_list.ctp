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

$widgetData = $WidgetServiceStatusList[$widget['Widget']['id']];
?>
<div class="padding-10">
	<div style="border:1px solid #c3c3c3;" class="padding-10">
		<div class="row">
			<div class="col-xs-2">
				<a href="javascript:void(0);" data-widget-id="<?php echo h($widget['Widget']['id']); ?>" class="btn btn-default btn-xs stopRotation btn-primary"><i class="fa fa-pause"></i></a>
				<a href="javascript:void(0);" data-widget-id="<?php echo h($widget['Widget']['id']); ?>" class="btn btn-default btn-xs startRotation" style="display:none;"><i class="fa fa-play"></i></a>
				<?php
				$class = '';
				if($widgetData['Widget']['WidgetServiceStatusList']['animation'] == 'fadeInRight'):
					$class = 'btn-primary';
				endif;
				?>
				<a href="javascript:void(0);" class="btn btn-default btn-xs <?php echo $class; ?> listAnimateRight" data-widget-id="<?php echo h($widget['Widget']['id']); ?>"><i class="fa fa-arrow-left"></i></a>
				<?php
				$class = '';
				if($widgetData['Widget']['WidgetServiceStatusList']['animation'] == 'fadeInUp'):
					$class = 'btn-primary';
				endif;
				?>
				<a href="javascript:void(0);" class="btn btn-default btn-xs <?php echo $class; ?> listAnimateUp" data-widget-id="<?php echo h($widget['Widget']['id']); ?>"><i class="fa fa-arrow-up"></i></a>
			</div>
			<div class="col-xs-4">
				<div class="pull-left padding-right-5">
					<?php echo __('Paging interval');?>:
				</div>
				<div class="slider-slim width-120 pull-left" data-widget-id="<?php echo h($widget['Widget']['id']); ?>">
					<input class="slider slider-primary slider-slim" data-slider-min="3" data-slider-max="30" data-slider-value="<?php echo h($widgetData['Widget']['WidgetServiceStatusList']['animation_interval']); ?>" />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-xs-6 text-right">
				<span class="listSettings">
					<?php
					$stateSettings = [
						'show_ok' => [
							'icon' => 'fa-square ok'
						],
						'show_warning' => [
							'icon' => 'fa-square warning'
						],
						'show_critical' => [
							'icon' => 'fa-square critical'
						],
						'show_unknown' => [
							'icon' => 'fa-square unknown'
						],
						'show_acknowledged' => [
							'icon' => 'fa-user'
						],
						'show_downtime' => [
							'icon' => 'fa-power-off'
						]
					];
					foreach($stateSettings as $dbField => $stateSetting): ?>
						<?php
						$checked = '';
						if($widgetData['Widget']['WidgetServiceStatusList'][$dbField] == true):
							$checked = 'checked="checked"';
						endif;
						?>
						<i class="fa <?php echo $stateSetting['icon']; ?>"></i> <input type="checkbox" <?php echo $checked; ?> data-key="<?php echo $dbField; ?>"/>
					<?php
					endforeach; ?>
				</span>
				<a href="javascript:void(0);" class="btn btn-primary btn-xs margin-left-10 saveListSettings" data-widget-id="<?php echo h($widget['Widget']['id']); ?>"><?php echo __('Save');?></a>
			</div>
		</div>
	</div>
</div>
<div class="no-padding font-xs tableContainer">
	 <?php echo $this->element('Dashboard'.DS.'service_status_list_table', ['widget' => $widget]); ?>
</div>
