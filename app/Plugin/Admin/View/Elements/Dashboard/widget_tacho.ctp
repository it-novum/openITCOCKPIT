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
<div class="widget-body tacho-body">
	<?php

	echo $this->Form->create('tachometer', array(
		'class' => 'tacho_form clear',
		'id' => '',
	));

	$services = $this->Html->chosenPlaceholder($service_ids_for_select);
	$options = [
		'options' => $services,
		'label' => __('Select a service to monitor'),
		'class' => 'chosen tacho-select-box selectTachoService elementInput',
		'wrapInput' => 'col col-xs-8 selectTachoService',
	];
	echo $this->Form->input('ServiceSelect_uuid', $options);

	$datasource = $this->Html->chosenPlaceholder();
	$options = [
		'options' => '',
		'label' => __('Select a data source'),
		'class' => 'chosen data-source-select-box selectTachoService elementInput',
		'wrapInput' => 'col col-xs-8 selectTachoService',
		'div' => [
			'class' => 'form-group data-source-select',
		]
	];
	echo $this->Form->input('DataSource_select', $options);

	echo $this->Form->inputs(array(
		'legend' => 'Scale',
		'fieldset' => 'dataSourceValues',
		'min' => [
			'label' => 'Minimum',
			'class' => 'min',
			'div' => [
				'class' => 'form-group DataSourceValuesFields',
			]
		],
		'max' => [
			'label' => 'Maximum',
			'class' => 'max',
			'div' => [
				'class' => 'form-group DataSourceValuesFields',
			]
		],
		'warning' => [
			'label' => 'Warning',
			'class' => 'warn',
			'div' => [
				'class' => 'form-group DataSourceValuesFields',
			]
		],
		'crit' => [
			'label' => 'Critical',
			'class' => 'crit',
			'div' => [
				'class' => 'form-group DataSourceValuesFields',
			]
		]
	));

	$options = [
		'options' => '',
		'class' => 'current'
	];
	echo $this->Form->hidden('DataSource_current', $options);

	$options = [
		'options' => '',
		'class' => 'unit'
	];
	echo $this->Form->hidden('DataSource_unit', $options);
	?>
	<div class="tacho_preview">Toggle<br/> Preview</div>
	<div class="tacho_preview_canvas">
		<canvas></canvas>
	</div>
	<?php
	$options_button = array(
		'label' => 'Save',
		'class' => 'tacho_save btn btn-primary',
	);
	echo $this->Form->end($options_button);

	?>
	<div class="service-title-tacho"><i class="fa fa-cog "></i></div>
	<div class="tacho">
		<canvas></canvas>
	</div>
</div>
