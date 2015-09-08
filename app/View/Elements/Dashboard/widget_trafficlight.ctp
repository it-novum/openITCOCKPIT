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
	<?php
	echo $this->Form->create('trafficLight', array(
		'class' => 'trafficlight_form clear',
		'id' => '',
	));

	$services = $this->Html->chosenPlaceholder($service_ids_for_select);
	$options = [
		'options' => $services,
		'label' => __('Select a service to monitor'),
		'class' => 'chosen selectTrafficlight elementInput',
		'wrapInput' => 'col col-xs-8 selectTrafficlight',
	];
	echo $this->Form->input('ServiceSelect_uuid', $options);

	$options_button = array(
		'label' => 'Save',
		'class' => 'trafficlight_save btn btn-sm btn-primary',
	);
	echo $this->Form->end($options_button);
	?>
	<div class="service-title"><i class="fa fa-cog "></i></div>
	<div class="trafficlight"></div>
</div>
