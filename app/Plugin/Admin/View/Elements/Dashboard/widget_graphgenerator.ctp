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
<div class="widget-body graphgenerator-body">
	<?php

	echo $this->Form->create('graphgeneratorForm', array(
		'class' => 'graphgenerator_form clear',
		'id' => '',
	));

	$services = $this->Html->chosenPlaceholder($service_ids_for_select);
	$options = [
		'options' => $services,
		'label' => __('Select a service to generate a graph'),
		'class' => 'chosen graphgenerator-select-box selectGraphgeneratorService elementInput',
		'wrapInput' => 'col col-xs-8 selectGraphgenerator',
	];
	echo $this->Form->input('ServiceSelect_uuid', $options);

	$options_button = array(
		'label' => 'Save',
		'class' => 'graphgenerator_save btn btn-primary',
	);
	echo $this->Form->end($options_button);

	?>
	<div class="dataSourceButtonsHeadline">Select service rule(s):<br/></div>
	<div class="dataSourceButtons"></div>
	<div class="selectTimeFrameContainer">
	<?php
		$relative_time_options = [
			1800 => __('Last 30 Minutes'),
			3600 => __('Last 1 Hour'),
			10800 => __('Last 3 Hours'),
			21600 => __('Last 6 Hours'),
			43200 => __('Last 12 Hours'),
			86400 => __('Last 24 Hours'),
			259200 => __('Last 3 Days'),
			604800 => __('Last 7 Days'),
			1209600 => __('Last 14 Days'),
			2592000 => __('Last 30 Days'),
		];
		$options = [
			'options' => $relative_time_options,
			'label' => __('Time'),
			'class' => 'chosen selectTimeFrame elementInput',
			'wrapInput' => 'col col-xs-8',
		];
		echo $this->Form->input('relative_time', $options);
	?>
	</div>
	<div class="graphContainer">
		<div class="graph_legend" style="display: none;"></div>
		<div class="graph_loader" style="display: none; text-align: center;">
			<i class="fa fa-cog fa-4x fa-spin"></i>
		</div>
		<div id="graph_data_tooltip"></div>
		<div class="graphGenerator"></div>
	</div>
</div>
