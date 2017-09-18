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
            <i class="fa fa-bomb fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service Escalation'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Add Service Escalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Serviceescalation', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Serviceescalation.container_id', [
                'options'       => $this->Html->chosenPlaceholder($containers),
                'class'         => 'chosen',
                'style'         => 'width: 100%;',
                'label'         => __('Container'),
                'SelectionMode' => 'single',
            ]);

            echo $this->Form->input('Serviceescalation.Service', [
                'options'          => $services,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-plus-square text-success"></i> ' . __('Services'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10',
                ],
                'target'   => '#ServiceescalationServiceExcluded',
                'data-placeholder' => __('Please, start typing...'),
                'itn-ajax' => '/Services/ajaxGetByTerm',
                'itn-ajax-container' => '#ServiceescalationContainerId',
                'itn-ajax-onchange'=> '#ServiceescalationServiceExcluded',
            ]);

            echo $this->Form->input('Serviceescalation.Service_excluded', [
                'options'          => $servicesExcluded,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-plus-square text-danger"></i> ' . __('Services (excluded)'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10 danger',
                ],
                'target'   => '#ServiceescalationService',
                'data-placeholder' => __('Please, start typing...'),
                'itn-ajax' => '/Services/ajaxGetByTerm',
                'itn-ajax-container' => '#ServiceescalationContainerId',
                'itn-ajax-onchange'=> '#ServiceescalationService',
            ]);


            echo $this->Form->input('Serviceescalation.Servicegroup', [
                'options'          => $servicegroups,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-plus-square text-success"></i> ' . __('Service Groups'),
                'data-placeholder' => __('Please choose a servicegroup'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10 success',
                ],
                'target'           => '#ServiceescalationServicegroupExcluded',
            ]);

            echo $this->Form->input('Serviceescalation.Servicegroup_excluded', [
                'options'          => $servicegroups,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-minus-square text-danger"></i> ' . __('Service Groups (excluded)'),
                'data-placeholder' => __('Please choose a servicegroup'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10 danger',
                ],
                'target'           => '#ServiceescalationServicegroup',
            ]);

            echo $this->Form->input('Serviceescalation.first_notification', [
                'label'       => __('First escalation notice'),
                'placeholder' => 0,
                'min'         => 0,
            ]);

            echo $this->Form->input('Serviceescalation.last_notification', [
                'label'       => __('Last escalation notice'),
                'placeholder' => 0,
                'min'         => 0,
            ]);

            echo $this->Form->input('Serviceescalation.notification_interval', [
                'label'       => __('Notification interval'),
                'placeholder' => 60,
                'min'         => 0,
                'help'        => __('Interval in minutes'),
            ]);

            echo $this->Form->input('Serviceescalation.timeperiod_id', [
                'options'          => $timeperiods,
                'class'            => 'chosen',
                'multiple'         => false,
                'style'            => 'width:100%;',
                'label'            => __('Time period'),
                'data-placeholder' => __('Please choose a contact'),
            ]);

            echo $this->Form->input('Serviceescalation.Contact', [
                'options'          => $contacts,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => __('Contacts'),
                'data-placeholder' => __('Please choose a contact'),
            ]);

            echo $this->Form->input('Serviceescalation.Contactgroup', [
                'options'          => $contactgroups,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => __('Contact groups'),
                'data-placeholder' => __('Please choose a contactgroup'),
            ]);
            ?>
            <fieldset>
                <legend class="font-sm">
                    <label><?php echo __('Service escalation options'); ?></label>
                    <?php if (isset($validation_service_notification)): ?>
                        <span class="text-danger"><?php echo $validation_service_notification; ?></span>
                    <?php endif; ?>
                </legend>
                <?php
                $escalation_options = [
                    'escalate_on_recovery' => 'fa-square txt-color-greenLight',
                    'escalate_on_warning'  => 'fa-square txt-color-orange',
                    'escalate_on_critical' => 'fa-square txt-color-redLight',
                    'escalate_on_unknown'  => 'fa-square txt-color-blueDark',
                ];
                foreach ($escalation_options as $escalation_option => $icon):?>
                    <div style="border-bottom:1px solid lightGray;">
                        <?php echo $this->Form->fancyCheckbox($escalation_option, [
                            'caption' => ucfirst(preg_replace('/escalate_on_/', '', $escalation_option)),
                            'icon'    => '<i class="fa '.$icon.'"></i> ',
                            'checked' => $this->CustomValidationErrors->refill($escalation_option, false),
                        ]); ?>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
