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
                <?php echo __('Hostescalation'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bomb"></i> </span>
        <h2><?php echo __('Add Hostescalation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Hostescalation', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('Hostescalation.container_id', [
                'options' => $this->Html->chosenPlaceholder($containers),
                'class' => 'chosen',
                'style' => 'width: 100%;',
                'label' => __('Container'),
                'SelectionMode' => 'single',
            ]);

            echo $this->Form->input('Hostescalation.Host', [
                'options' => $hosts,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('<i class="fa fa-plus-square text-success"></i> Hosts'),
                'data-placeholder' => __('Please choose a host'),
                'wrapInput' => [
                    'tag' => 'div',
                    'class' => 'col col-xs-10 success'
                ],
                'target' => '#HostescalationHostExcluded'
            ]);

            echo $this->Form->input('Hostescalation.Host_excluded', [
                'options' => $hosts,
                'class' => 'chosen test',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('<i class="fa fa-minus-square text-danger"></i> Hosts (excluded)'),
                'data-placeholder' => __('Please choose a host'),
                'wrapInput' => [
                    'tag' => 'div',
                    'class' => 'col col-xs-10 danger'
                ],
                'target' => '#HostescalationHost'
            ]);

            echo $this->Form->input('Hostescalation.Hostgroup', [
                'options' => $hostgroups,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => '<i class="fa fa-plus-square text-success"></i> ' . __('Hostgroups'),
                'data-placeholder' => __('Please choose a hostgroup'),
                'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10 success'],
                'target' => '#HostescalationHostgroupExcluded'
            ]);

            echo $this->Form->input('Hostescalation.Hostgroup', [
                'options' => $hostgroups,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('<i class="fa fa-plus-square text-success"></i> Hostgroups'),
                'data-placeholder' => __('Please choose a hostgroup'),
                'wrapInput' => [
                    'tag' => 'div',
                    'class' => 'col col-xs-10 success'
                ],
                'target' => '#HostescalationHostgroupExcluded'
            ]);

            echo $this->Form->input('Hostescalation.first_notification', [
                'label' => __('First escalation notice'),
                'placeholder' => 0,
                'min' => 0
            ]);

            echo $this->Form->input('Hostescalation.last_notification', [
                'label' => __('Last escalation notice'),
                'placeholder' => 0,
                'min' => 0
            ]);

            echo $this->Form->input('Hostescalation.notification_interval', [
                'label' => __('Notification interval'),
                'placeholder' => 60,
                'min' => 0,
                'help' => __('Interval in minutes'),
            ]);

            echo $this->Form->input('Hostescalation.timeperiod_id', [
                'options' => $timeperiods,
                'class' => 'chosen',
                'multiple' => false,
                'style' => 'width:100%;',
                'label' => __('Timeperiod'),
                'data-placeholder' => __('Please choose a contact')
            ]);

            echo $this->Form->input('Hostescalation.Contact', [
                'options' => $contacts,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('Contacts'),
                'data-placeholder' => __('Please choose a contact')
            ]);

            echo $this->Form->input('Hostescalation.Contactgroup', [
                'options' => $contactgroups,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('Contactgroups'),
                'data-placeholder' => __('Please choose a contactgroup')
            ]);
            ?>
            <fieldset>
                <legend class="font-sm">
                    <label><?php echo __('Hostescalation options'); ?></label>
                    <?php if (isset($validation_host_notification)): ?>
                        <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                    <?php endif; ?>
                </legend>
                <?php
                $escalation_options = [
                    'escalate_on_recovery' => 'fa-square txt-color-greenLight',
                    'escalate_on_down' => 'fa-square txt-color-redLight',
                    'escalate_on_unreachable' => 'fa-square txt-color-blueDark',
                ];
                foreach ($escalation_options as $escalation_option => $icon):?>
                    <div style="border-bottom:1px solid lightGray;">
                        <?php echo $this->Form->fancyCheckbox($escalation_option, [
                            'caption' => ucfirst(preg_replace('/escalate_on_/', '', $escalation_option)),
                            'icon' => '<i class="fa ' . $icon . '"></i> ',
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
