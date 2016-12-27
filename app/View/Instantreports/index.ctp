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
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Instant Report'); ?>
			</span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create Instant Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Instantreport', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('evaluationMethod', [
                'before'  => '<label class="col col-md-2 text-right">'.__('Evaluation').'</label>',
                'type'    => 'radio',
                'options' => [
                    'InstantreportHost'    => '<i class="fa fa-desktop"></i> '.__('Hosts '),
                    'InstantreportService' => '<i class="fa fa-gears"></i> '.__('Hosts and Services '),
                ],
                'class'   => 'padding-right-10',
                'default' => 'InstantreportHost',
            ]);

            echo $this->Form->input('Host', [
                'div'      => 'form-group checkbox-group multiple-select InstantreportHost',
                'options'  => Hash::combine($hosts, '{n}.Host.uuid', '{n}.Host.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;', 'label' => __('<i class="fa fa-desktop"></i> Hosts'), 'data-placeholder' => __('Please choose a host'), 'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10']]);

            echo $this->Form->input('Service', [
                'div'      => 'form-group checkbox-group multiple-select disabled InstantreportService',
                'options'  => $services,
                'class'    => 'chosen',
                'multiple' => true,
                'disabled' => true,
                'style'    => 'width:100%;',
                'label'    => [
                    'text'             => __('<i class="fa fa-gears"></i> Services'),
                    'data-placeholder' => __('Please choose a service'),
                    'wrapInput'        => [
                        'tag'   => 'div',
                        'class' => 'col col-xs-10',
                    ],
                ],
            ]);

            echo $this->Form->input('report_format', [
                    'options'          => ['pdf' => __('PDF'), 'html' => __('HTML')],
                    'data-placeholder' => __('Please select...'),
                    'class'            => 'chosen',
                    'label'            => __('Report format'),
                    'style'            => 'width:100%;',
                ]
            );

            echo $this->Form->input('timeperiod_id', ['options' => $this->Html->chosenPlaceholder($timeperiods), 'data-placeholder' => __('Please select...'), 'class' => 'chosen', 'label' => __('Timeperiod'), 'style' => 'width:100%;']);

            echo $this->Form->input('start_date', [
                'label' => __('From'),
                'type'  => 'text',
                'class' => 'form-control required',
                'value' => $this->CustomValidationErrors->refill('start_date', date('d.m.Y', strtotime('-15 days'))),
            ]);

            echo $this->Form->input('end_date', [
                'label'    => __('To'),
                'type'     => 'text',
                'class'    => 'form-control required',
                'reguired' => true,
                'value'    => $this->CustomValidationErrors->refill('end_date', date('d.m.Y', time())),
            ]);
            echo $this->Form->input('check_hard_state', [
                'options'          => [__('soft and hard state'), __('only hard state')],
                'data-placeholder' => __('Please select...'),
                'class'            => 'chosen',
                'label'            => __('Reflection state'),
                'style'            => 'width:100%;',
            ]);
            ?>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('consider_downtimes', [
                    'caption'          => __('Consider downtimes'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => $this->CustomValidationErrors->refill('consider_downtimes', false),
                ]);
                ?>
            </div>
            <?php
            echo $this->Form->formActions(__('Create'));
            ?>
        </div>
    </div>
</div>
