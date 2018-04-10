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


$flapDetection_settings = [
    'flap_detection_on_ok'       => 'fa-square txt-color-greenLight',
    'flap_detection_on_warning'  => 'fa-square txt-color-orange',
    'flap_detection_on_unknown'  => 'fa-square txt-color-blueDark',
    'flap_detection_on_critical' => 'fa-square txt-color-redLight',
];
$notification_settings = [
    'notify_on_recovery' => 'fa-square txt-color-greenLight',
    'notify_on_warning'  => 'fa-square txt-color-orange',
    'notify_on_unknown'  => 'fa-square txt-color-blueDark',
    'notify_on_critical' => 'fa-square txt-color-redLight',
    'notify_on_flapping' => 'fa-random',
    'notify_on_downtime' => 'fa-clock-o',
];
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service'); ?>
            </span>

            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Edit Service'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#workaroundConfirmDelete">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <?php if ($this->Acl->hasPermission('browser')): ?>
                <a href="/services/browser/<?php echo $service['Service']['id']; ?>" class="btn btn-default btn-xs"><i
                            class="fa fa-cog"></i> <?php echo __('Browser'); ?></a>
            <?php endif; ?>
            <?php echo $this->Utils->backButton() ?>
        </div>

        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: %s', h($service['Service']['uuid'])); ?>
        </div>

        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
            <li class="active">
                <a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-desktop"></i> <span
                            class="hidden-mobile hidden-tablet"> <?php echo __('Basic configuration'); ?></span> </a>
            </li>
            <li class="">
                <a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-terminal"></i> <span
                            class="hidden-mobile hidden-tablet"> <?php echo __('Expert settings'); ?> </span></a>
            </li>

            <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'service', 'tabLink'); ?>
        </ul>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Service', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <!-- basic settings -->
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active in">
                            <span class="note"><?php echo __('Basic configuration'); ?>:</span>
                            <?php
                            echo $this->Form->input('Service.id', [
                                'type'      => 'hidden',
                                'value'     => $service['Service']['id'],
                                'wrapInput' => 'col col-xs-8',
                            ]);
                            /*echo $this->Form->input('Service.host_id', [
                                'options' => $this->Html->chosenPlaceholder($hosts),
                                'data-placeholder' => __('Please select...'),
                                'class' => 'chosen',
                                'label' => __('Host'),
                                'selected' => $service['Service']['host_id'],
                                'wrapInput' => 'col col-xs-8',
                                'style' => 'width: 100%',
                            ]);*/
                            ?>
                            <div class="form-group">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceName"><?php echo __('Host'); ?></label>
                                <div class="col col-xs-10 padding-top-5"><?php echo h($service['Host']['name']); ?></div>
                                <?php
                                echo $this->Form->input('Service.host_id', [
                                    'type'  => 'hidden',
                                    'value' => $service['Service']['host_id'],
                                ]); ?>
                            </div>
                            <?php
                            if ($service['Service']['service_type'] == MK_SERVICE):
                                echo $this->Form->input('Service.servicetemplate_id', [
                                    'type'  => 'hidden',
                                    'value' => $service['Service']['servicetemplate_id'],
                                ]);
                                ?>
                                <div class="form-group">
                                    <label class="col-xs-1 col-md-1 col-lg-1"
                                           for="ServiceName">
                                        <?php $allowEdit = $this->Acl->isWritableContainer($service['Servicetemplate']['container_id']); ?>
                                        <?php if ($this->Acl->hasPermission('edit', 'servicetemplates', '') && $allowEdit): ?>
                                            <a href="/servicetemplates/edit/<?php echo $service['Service']['servicetemplate_id'] . '/' . MK_SERVICE . '/'; ?>_controller:mkservicetemplates/_action:index/_plugin:mk_module"><i
                                                        class="fa fa-cog"></i> </a>
                                        <?php endif; ?>
                                        <?php echo __('Service template'); ?>
                                    </label>
                                    <div class="col col-xs-10 padding-top-5"><?php echo $service['Servicetemplate']['name']; ?></div>
                                </div>
                            <?php
                            else:
                                echo $this->Form->input('Service.servicetemplate_id', [
                                    'options'          => $this->Html->chosenPlaceholder($servicetemplates),
                                    'data-placeholder' => __('Please select...'),
                                    'class'            => 'chosen',
                                    'label'            => ['text' => '<a href="/servicetemplates/edit/' . $service['Service']['servicetemplate_id'] . '"><i class="fa fa-cog"></i> </a>' . __('Service template'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'selected'         => $service['Service']['servicetemplate_id'],
                                    'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                    'style'            => 'width: 100%',
                                ]);
                            endif;
                            if ($service['Service']['service_type'] == MK_SERVICE): ?>
                                <div class="form-group">
                                    <label class="col-xs-1 col-md-1 col-lg-1"
                                           for="ServiceName">
                                        <?php echo __('Name'); ?>
                                    </label>
                                    <div class="col col-xs-10 padding-top-5"><?php echo $service['Service']['name']; ?></div>
                                    <?php echo $this->Form->input('Service.name', ['type' => 'hidden', 'value' => $service['Service']['name']]); ?>
                                </div>
                            <?php endif;
                            if ($service['Service']['service_type'] != MK_SERVICE):
                                echo $this->Form->input('Service.name', [
                                    'label'     => ['text' => __('Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value'     => $service['Service']['name'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]);
                            endif;
                            echo $this->Form->input('Service.description', [
                                'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'value'     => $service['Service']['description'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Service.Servicegroup', [
                                'options'          => $this->Html->chosenPlaceholder($servicegroups),
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => ['text' => __('Servicegroup'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'selected'         => $service['Servicegroup'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'style'            => 'width: 100%',
                                'multiple'         => true,
                            ]);
                            echo $this->Form->input('Service.notes', [
                                'label'     => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'value'     => $service['Service']['notes'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Service.service_url', [
                                'label'     => ['text' => __('Service URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'value'     => $service['Service']['service_url'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'      => __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$, $HOSTADDRESS$, $SERVICEDESC$, $SERVICEDISPLAYNAME$ will be replaced'),
                            ]);
                            ?>
                            <div class="form-group <?php echo isset($validationErrors['priority']) ? 'has-error' : '' ?>">
                                <label class="col col-md-1 control-label text-left"><?php echo __('Priority'); ?></label>

                                <div class="col col-xs-10 col-md-10 col-lg-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php //The smallest priority is 1 at the moment
                                        $priority = $this->CustomValidationErrors->refill('priority', ($service['Service']['priority'] !== null) ? $service['Service']['priority'] : $service['Servicetemplate']['priority']);
                                        ?>
                                        <?php for ($i = 5; $i > 0; $i--): ?>
                                            <input type="radio" <?php echo ($priority == $i) ? 'checked="checked"' : '' ?>
                                                   id="Servicestars-rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
                                                   name="data[Service][priority]">
                                            <label for="Servicestars-rating-<?php echo $i; ?>"><i
                                                        class="fa fa-fire"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (isset($validationErrors['priority'])): ?>
                                        <br/><br/>
                                        <span class="help-block txt-color-red"><?php echo $validationErrors['priority']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- key words -->
                            <?php echo $this->Form->input('tags', [
                                'label'     => ['text' => __('Tags'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'     => 'form-control tagsinput',
                                'data-role' => 'tagsinput',
                                'value'     => $service['Service']['tags'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>

                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php echo $this->Form->input('Service.notify_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Notification period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen',
                                'style'            => 'width: 100%;',
                                'selected'         => $service['Service']['notify_period_id'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <br/>

                            <div
                                    class="form-group form-group-slider required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="ServiceNotificationinterval"><?php echo __('Notification interval'); ?></label>

                                <div class="col col-md-7 hidden-mobile">
                                    <input
                                            type="text"
                                            id="ServiceNotificationinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-success"
                                            name="data[Service][notification_interval]"
                                            data-slider-min="0"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $service['Service']['notification_interval']; ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceNotificationinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceNotificationinterval"
                                           human="#ServiceNotificationinterval_human"
                                           value="<?php echo $service['Service']['notification_interval']; ?>"
                                           slider-for="ServiceNotificationinterval" class="form-control slider-input"
                                           name="data[Service][notification_interval]">
                                    <span class="note"
                                          id="ServiceNotificationinterval_human"><?php echo $this->Utils->secondsInWords($service['Service']['notification_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="padding-left-20 <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <?php
                                echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', [
                                    'style' => 'margin-left: 15px;',
                                ]);

                                foreach ($notification_settings as $notification_setting => $icon): ?>
                                    <div class="form-group no-padding" style="border-bottom:1px solid lightGray;">
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption'          => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'captionGridClass' => 'col col-xs-2',
                                            'icon'             => '<i class="fa ' . $icon . '"></i> ',
                                            'class'            => 'onoffswitch-checkbox notification_control',
                                            'checked'          => $service['Service'][$notification_setting],
                                            'wrapGridClass'    => 'col col-xs-1',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <br/>

                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('process_performance_data', [
                                    'caption'          => __('Enable graph'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->CustomValidationErrors->refill('process_performance_data', $service['Service']['process_performance_data']),
                                    'icon'             => '<i class="fa fa-area-chart"></i> ',
                                ]); ?>
                            </div>

                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                    'caption'          => __('Enable active checks'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->CustomValidationErrors->refill('active_checks_enabled', $service['Service']['active_checks_enabled']),
                                    'icon'             => '<i class="fa fa-sign-in"></i> ',
                                ]); ?>
                            </div>

                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <!-- contacts & groups -->
                            <?php $contactOptions = []; ?>
                            <?php $contactGroupOptions = []; ?>
                            <?php if ($ContactsInherited['inherit'] === true):
                                switch ($ContactsInherited['source']):
                                    case 'Host':
                                        $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosts/edit/' . $service['Host']['id'] . '">' . __('Host') . '</a></strong>';
                                        break;

                                    case 'Hosttemplate':
                                        $source = __('Service') . ' <i class="fa fa-arrow-right"></i> ' . __('Servicetemplate') . ' <i class="fa fa-arrow-right"></i> ' . __('Host') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosttemplates/edit/' . $service['Host']['hosttemplate_id'] . '">' . __('Hosttemplate') . '</a></strong>';
                                        break;

                                    case 'Servicetemplate':
                                        $source = __('Service') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/servicetemplates/edit/' . $service['Service']['servicetemplate_id'] . '">' . __('Servicetemplate') . '</a></strong>';
                                        break;
                                    default:
                                        $source = '???';
                                endswitch; ?>
                                <span class="text-info"><i
                                            class="fa fa-info-circle"></i> <?php echo __('Contacts and Contactgroups are inherited in the following order:'); ?></span>
                                <span class="text-info"> <?php echo $source; ?></span>
                                <span class="text-info"><?php echo __('Untick to disable'); ?><input type="checkbox"
                                                                                                     id="inheritContacts"
                                                                                                     checked="checked"/></span>
                                <?php
                                $contactOptions = [
                                    'selected' => array_keys($ContactsInherited['Contact']),
                                ];

                                $contactGroupOptions = [
                                    'selected' => array_keys($ContactsInherited['Contactgroup']),
                                ];
                                ?>
                            <?php endif; ?>
                            <div id="serviceContactSelects">
                                <?php echo $this->Form->input('Service.Contact', Hash::merge($contactOptions, [
                                    'options'   => $contacts,
                                    'selected'  => $service['Contact'],
                                    'multiple'  => true,
                                    'class'     => 'chosen',
                                    'style'     => 'width:100%;',
                                    'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ])); ?>

                                <?php echo $this->Form->input('Service.Contactgroup', Hash::merge($contactGroupOptions, [
                                    'options'   => $contactgroups,
                                    'selected'  => $service['Contactgroup'],
                                    'multiple'  => true,
                                    'class'     => 'chosen',
                                    'style'     => 'width:100%;',
                                    'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ])); ?>
                            </div>
                        </div>
                        <!-- second page -->
                        <!-- expert settings -->
                        <div id="tab2" class="tab-pane fade">
                            <div class="form-group">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceName"><?php echo __('Host'); ?></label>
                                <div class="col col-xs-10 padding-top-5"><?php echo h($service['Host']['name']); ?></div>
                            </div>
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php echo $this->Form->input('Service.command_id', [
                                'options'          => $this->Html->chosenPlaceholder($commands),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Check command'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'selected'         => $service['Service']['command_id'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <!-- Command arguments -->
                            <div id="CheckCommandArgs">
                                <?php
                                if (!empty($commandarguments)):
                                    $servicecommandargumentvalues = Hash::combine($service['Servicecommandargumentvalue'], '{n}.commandargument_id', '{n}');
                                    foreach ($commandarguments as $key => $commandargument):
                                        echo $this->Form->input('Servicecommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.value', [
                                            'label'     => [
                                                'class' => 'col col-md-2 control-label text-primary',
                                                'text'  => $commandargument['Commandargument']['human_name'],
                                            ],
                                            'value'     => (array_key_exists($commandargument['Commandargument']['id'], $servicecommandargumentvalues)) ? $servicecommandargumentvalues[$commandargument['Commandargument']['id']]['value'] : '',
                                            'wrapInput' => 'col col-xs-8',
                                        ]);
                                        echo $this->Form->input('Servicecommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.commandargument_id', [
                                            'type'  => 'hidden',
                                            'value' => $commandargument['Commandargument']['id'],
                                        ]);
                                        if ((array_key_exists($commandargument['Commandargument']['id'], $servicecommandargumentvalues) && isset($servicecommandargumentvalues[$commandargument['Commandargument']['id']]['id']))):
                                            echo $this->Form->input('Servicecommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.id', [
                                                'type'  => 'hidden',
                                                'value' => $servicecommandargumentvalues[$commandargument['Commandargument']['id']]['id'],
                                            ]);
                                        endif;
                                    endforeach;
                                else:
                                    ?>
                                    <div class="form-group">
                                        <label class="col col-md-2 control-label hidden-mobile hidden-tablet">
                                            <!-- spacer for nice layout -->
                                        </label>
                                        <label class="col col-md-10 col-xs-12 text-primary">
                                            <i class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php echo $this->Form->input('Service.check_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen',
                                'selected'         => $service['Service']['check_period_id'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <?php echo $this->Form->input('Service.max_check_attempts', [
                                'label'     => ['text' => __('Max. number of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'value'     => $service['Service']['max_check_attempts'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Check interval'); ?></label>

                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="ServiceCheckinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-success"
                                            name="data[Service][check_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $service['Service']['check_interval']; ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input
                                            type="number"
                                            id="_ServiceCheckinterval"
                                            human="#ServiceCheckinterval_human"
                                            value="<?php echo $service['Service']['check_interval']; ?>"
                                            slider-for="ServiceCheckinterval"
                                            class="form-control slider-input"
                                            name="data[Service][check_interval]">
                                    <span class="note"
                                          id="ServiceCheckinterval_human"><?php echo $this->Utils->secondsInWords($service['Service']['check_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Retry interval'); ?></label>

                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="ServiceRetryinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-primary"
                                            name="data[Service][retry_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $service['Service']['retry_interval']; ?>"
                                            data-slider-selection="before"
                                            data-slider-handle="round"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#ServiceRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceRetryinterval" human="#ServiceRetryinterval_human"
                                           value="<?php echo $service['Service']['retry_interval']; ?>"
                                           slider-for="ServiceRetryinterval"
                                           class="form-control slider-input"
                                           name="data[Service][retry_interval]">
                                    <span class="note"
                                          id="ServiceRetryinterval_human"><?php echo $this->Utils->secondsInWords($service['Service']['retry_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>
                            <div class="form-group">
                                <?php echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                    'caption'          => __('Flap detection'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 text-left',
                                    'captionClass'     => 'control-label',
                                    'checked'          => $service['Service']['flap_detection_enabled'],
                                ]); ?>
                            </div>

                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_service_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_service_notification; ?></span>
                                <?php endif; ?>
                            </legend>

                            <br/>

                            <div class="<?php echo $this->CustomValidationErrors->errorClass('flap_detection_on_up'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('flap_detection_on_up', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($flapDetection_settings as $flapDetection_setting => $icon): ?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                            'caption'          => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                            'icon'             => '<i class="fa ' . $icon . '"></i> ',
                                            'class'            => 'onoffswitch-checkbox flapdetection_control',
                                            'checked'          => $service['Service'][$flapDetection_setting],
                                            'wrapGridClass'    => 'col col-xs-1',
                                            'captionGridClass' => 'col col-xs-2',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <br/>

                            <legend class="font-sm"></legend>

                            <div class="form-group no-padding">
                                <?php echo $this->Form->fancyCheckbox('Service.is_volatile', [
                                    'caption'          => __(ucfirst('Status volatile')),
                                    'icon'             => '<i class="fa fa-asterisk"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'checked'          => $this->CustomValidationErrors->refill('is_volatile', false),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-md-2',
                                ]); ?>
                            </div>
                            <div class="form-group no-padding">
                                <?php echo $this->Form->fancyCheckbox('Service.freshness_checks_enabled', [
                                    'caption'          => __('Freshness checks enabled'),
                                    'icon'             => '<i class="fa fa-foursquare"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'checked'          => $service['Service']['freshness_checks_enabled'],
                                    'captionGridClass' => 'col col-md-2',
                                    'wrapGridClass'    => 'col col-xs-1',
                                ]); ?>
                            </div>
                            <?php
                            echo $this->Form->input('Service.freshness_threshold', [
                                'label'     => [
                                    'text'  => __('<i class="fa fa-clock-o"></i> Freshness threshold (seconds)'),
                                    'class' => 'col col-md-2 control-label text-left',
                                ],
                                'class'     => 'col col-md-12',
                                'wrapInput' => 'col col-xs-8',
                                'value'     => $service['Service']['freshness_threshold'],
                            ]);
                            ?>
                            <br>
                            <?php echo $this->Form->input('Service.eventhandler_command_id', [
                                'options'          => $this->Html->chosenPlaceholder($eventhandlers),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Eventhandler'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen',
                                'style'            => 'width:100%;',
                                'selected'         => $service['Service']['eventhandler_command_id'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>

                            <div id="EventhandlerCommandArgs">
                                <?php
                                if (!empty($eventhandler_commandarguments)):
                                    $servicecommandargumentvalues = Hash::combine($service['Serviceeventcommandargumentvalue'], '{n}.commandargument_id', '{n}');
                                    foreach ($eventhandler_commandarguments as $key => $commandargument):
                                        echo $this->Form->input('Serviceeventcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.value', [
                                            'label'     => [
                                                'class' => 'col col-md-2 control-label text-primary',
                                                'text'  => $commandargument['Commandargument']['human_name'],
                                            ],
                                            'value'     => (array_key_exists($commandargument['Commandargument']['id'], $servicecommandargumentvalues)) ? $servicecommandargumentvalues[$commandargument['Commandargument']['id']]['value'] : '',
                                            'wrapInput' => 'col col-xs-8',
                                        ]);
                                        echo $this->Form->input('Serviceeventcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.commandargument_id', [
                                            'type'  => 'hidden',
                                            'value' => $commandargument['Commandargument']['id'],
                                        ]);
                                        if ((array_key_exists($commandargument['Commandargument']['id'], $servicecommandargumentvalues) && isset($servicecommandargumentvalues[$commandargument['Commandargument']['id']]['id']))):
                                            echo $this->Form->input('Serviceeventcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.id', [
                                                'type'  => 'hidden',
                                                'value' => $servicecommandargumentvalues[$commandargument['Commandargument']['id']]['id'],
                                            ]);
                                        endif;
                                    endforeach;
                                else:
                                    ?>
                                    <div class="form-group">
                                        <label class="col col-md-2 control-label hidden-mobile hidden-tablet">
                                            <!-- spacer for nice layout --></label>
                                        <label class="col col-md-10 col-xs-12 text-primary">
                                            <i class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <br>

                            <!-- Servicetemplate macro settings -->
                            <div class="service-macro-settings">
                                <span class="note pull-left"><?php echo __('Service macro settings'); ?>:</span>
                                <br class="clearfix"/>
                                <br/>
                                <?php if (isset($customVariableValidationError)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                                <?php endif; ?>
                                <?php if (isset($customVariableValidationErrorValue)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                                <?php endif;
                                $customVariableMerger = new \itnovum\openITCOCKPIT\Core\CustomVariableMerger(
                                    $service['Customvariable'],
                                    $service['Servicetemplate']['Customvariable']
                                );
                                $mergedCustomVariables = $customVariableMerger->getCustomVariablesMergedAsRepository();
                                ?>

                                <?php $this->CustomVariables->setup('SERVICE', OBJECT_SERVICE, $mergedCustomVariables->getAllCustomVariablesAsArray()); ?>
                                <?php echo $this->CustomVariables->prepare('SERVICE'); ?>
                                <br/>
                            </div>

                            <?php if ($mergedCustomVariables->getSize() > 0): ?>
                                <div class="col-xs-12 text-info">
                                    <i class="fa fa-info-circle"></i> <?php echo __('Macros with green color are inherited from template. You can override the value but not delete the macro itself'); ?>
                                </div>
                            <?php endif; ?>

                        </div><!-- close tab2 -->

                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'service'); ?>

                    </div>
                </div>
            </div>
            
            <br/>
            <?php echo $this->Form->formActions(); ?>

        </div>
    </div>
</div>


<div id="workaroundConfirmDelete" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-color-danger txt-color-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Attention!'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want delete this service?'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="errorOnDelete"></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="successDelete" style="display:none;">
                        <div class="alert auto-hide alert-success">
                            <?php echo __('Service deleted successfully'); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="yesDeleteService"
                        data-service-id="<?php echo h($service['Service']['id']); ?>">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>
