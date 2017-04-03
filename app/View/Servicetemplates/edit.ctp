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
<?php
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
                <?php echo __('Servicetemplate'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Edit Servicetemplate'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, Hash::merge([$servicetemplate['Servicetemplate']['id']] ,$this->params['named']), [], true, __('All attached services will be deleted too.')); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>

        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: %s', h($servicetemplate['Servicetemplate']['uuid'])); ?>
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
        </ul>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Servicetemplate', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <!-- basic settings -->
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active in">
                            <span class="note"><?php echo __('Basic configuration'); ?>:</span>
                            <?php
                            if ($hasRootPrivileges || !$hasRootPrivileges && $servicetemplate['Container']['id'] != ROOT_CONTAINER):
                                echo $this->Form->input('container_id', [
                                        'options'          => $this->Html->chosenPlaceholder($containers),
                                        'data-placeholder' => __('Please select...'),
                                        'multiple'         => false,
                                        'selected'         => $this->request->data['Servicetemplate']['container_id'],
                                        'class'            => 'chosen col col-xs-12',
                                        'style'            => 'width: 100%',
                                        'label'            => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                        'help'      => count($servicetemplate['Service']) > 0 ? __('There are Services using this Service Template. Therefore the number of Containers is decreased.') : '',
                                    ]
                                );
                            else:
                                ?>
                                <div class="form-group required">
                                    <label class="col col-md-2 control-label"><?php echo __('Container'); ?></label>
                                    <div class="col col-xs-10 required"><input type="text" value="/root"
                                                                               class="form-control" readonly></div>
                                </div>
                                <?php
                                echo $this->Form->input('container_id', [
                                        'value' => $servicetemplate['Container']['id'],
                                        'type'  => 'hidden',
                                    ]
                                );
                            endif;

                            echo $this->Form->input('id', [
                                'type'  => 'hidden',
                                'value' => $servicetemplate['Servicetemplate']['id'],
                            ]);
                            echo $this->Form->input('template_name', [
                                'value'     => $this->request->data['Servicetemplate']['template_name'],
                                'label'     => ['text' => __('Template name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'help'      => __('Servicetemplate name'),
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('name', [
                                'value'     => $this->request->data['Servicetemplate']['name'],
                                'label'     => ['text' => __('Service name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'help'      => __('This is the default name for the service if you create it out of the template'),
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('description', [
                                'value'     => $this->request->data['Servicetemplate']['description'],
                                'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Servicetemplate.Servicegroup', [
                                'options'          => $this->Html->chosenPlaceholder($_servicegroups),
                                'selected'  => $this->request->data['Servicegroup'],
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'label'            => ['text' => __('Servicegroup'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'style'            => 'width: 100%',
                                'multiple'         => true,
                            ]);
                            echo $this->Form->input('notes', [
                                'value'     => $this->request->data['Servicetemplate']['notes'],
                                'label'     => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Servicetemplate.service_url', [
                                'label'     => ['text' => __('Service URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'value'     => $this->request->data['Servicetemplate']['service_url'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group <?php echo (isset($validationErrors['priority'])) ? 'has-error' : '' ?>">
                                <label class="col col-md-1 control-label text-left"><?php echo __('Priority'); ?></label>
                                <div class="col col-xs-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php
                                        // The smallest priority is 1 and the highest at the moment
                                        if ($this->request->data['Servicetemplate']['priority'] == 0):
                                            $this->request->data['Servicetemplate']['priority'] = 1;
                                        endif;
                                        ?>
                                        <?php for ($i = 5; $i > 0; $i--): ?>
                                            <input type="radio" <?php echo ($this->request->data['Servicetemplate']['priority'] == $i) ? 'checked="checked"' : '' ?>
                                                   id="stars-rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
                                                   name="data[Servicetemplate][priority]">
                                            <label for="stars-rating-<?php echo $i; ?>"><i
                                                        class="fa fa-fire"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (isset($validationErrors['priority'])): ?>
                                        <br/><br/>
                                        <span class="help-block txt-color-red"><?php echo $validationErrors['priority']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php
                            echo $this->Form->input('Servicetemplate.notify_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($_timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Notification period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'selected'         => $this->request->data['Servicetemplate']['notify_period_id'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="ServiceNotificationinterval"><?php echo __('Notificationinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="ServiceNotificationinterval" maxlength="255" value=""
                                           class="form-control slider slider-success"
                                           name="data[Servicetemplate][notification_interval]"
                                           data-slider-min="0"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->request->data['Servicetemplate']['notification_interval'] ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#ServiceNotificationinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceNotificationinterval"
                                           human="#ServiceNotificationinterval_human"
                                           value="<?php echo $this->request->data['Servicetemplate']['notification_interval'] ?>"
                                           slider-for="ServiceNotificationinterval" class="form-control slider-input"
                                           name="data[Servicetemplate][notification_interval]">
                                    <span class="note"
                                          id="ServiceNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->request->data['Servicetemplate']['notification_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', ['style' => 'margin-left: 15px;']); ?>

                                <?php foreach ($notification_settings as $notification_setting => $icon): ?>
                                    <div style="border-bottom:1px solid lightGray;">
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption'          => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'icon'             => '<i class="fa '.$icon.'"></i> ',
                                            'class'            => 'onoffswitch-checkbox notification_control',
                                            'checked'          => $this->request->data['Servicetemplate'][$notification_setting],
                                            'captionGridClass' => 'col col-xs-2',
                                            'wrapGridClass'    => 'col col-xs-1',
                                        ]); ?>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <br/>
                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('process_performance_data', [
                                    'caption'          => __('Enable graph'),
                                    'wrapGridClass'    => 'col col-md-1',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->request->data['Servicetemplate']['process_performance_data'],
                                    'icon'             => '<i class="fa fa-area-chart"></i> ',
                                ]); ?>
                            </div>
                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                    'caption'          => __('Enable active checks'),
                                    'wrapGridClass'    => 'col col-md-1',
                                    'captionGridClass' => 'col col-md-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->request->data['Servicetemplate']['active_checks_enabled'],
                                    'icon'             => '<i class="fa fa-sign-in"></i> ',
                                ]); ?>
                            </div>
                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <?php echo $this->Form->input('Servicetemplate.Contact', [
                                'options'   => $_contacts,
                                'selected'  => $this->request->data['Contact'],
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <?php echo $this->Form->input('Servicetemplate.Contactgroup', [
                                'options'   => $_contactgroups,
                                'selected'  => $this->request->data['Contactgroup'],
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                        </div>

                        <div id="tab2" class="tab-pane fade">
                            <!-- check settings -->
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php
                            echo $this->Form->input('Servicetemplate.command_id', [
                                'options'          => $this->Html->chosenPlaceholder($commands),
                                'data-placeholder' => __('Please select...'),
                                'selected'         => $this->request->data['Servicetemplate']['command_id'],
                                'label'            => ['text' => '<a href="/commands/edit/'.$this->request->data['Servicetemplate']['command_id'].'"><i class="fa fa-cog"></i> </a>'.__('Checkcommand'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'             => '<span class="text-danger">'.__('Warning: If you change the check command, all service custom arguments will be reset to service template default!').'</span>'
                            ]);
                            ?>
                            <!-- Command arguments -->
                            <div id="CheckCommandArgs">
                                <?php
                                if (!empty($commandarguments)) {
                                    $servicetemplatecommandargumentvalues = Hash::combine($servicetemplate['Servicetemplatecommandargumentvalue'], '{n}.commandargument_id', '{n}');
                                    foreach ($commandarguments as $key => $commandargument) {
                                        $value = '';
                                        if (array_key_exists($commandargument['Commandargument']['id'], $servicetemplatecommandargumentvalues)) {
                                            $value = $servicetemplatecommandargumentvalues[$commandargument['Commandargument']['id']]['value'];
                                        }

                                        echo $this->Form->input('Servicetemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.value', [
                                            'label' => [
                                                'class' => 'col col-md-2 control-label text-primary',
                                                'text'  => $commandargument['Commandargument']['human_name'],
                                            ],
                                            'value' => $value,
                                        ]);
                                        echo $this->Form->input('Servicetemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.commandargument_id', [
                                            'type'  => 'hidden',
                                            'value' => $commandargument['Commandargument']['id'],
                                        ]);

                                        if ($commandargument['Commandargument']['id'] !== null) {
                                            $value = '';
                                            if (array_key_exists($commandargument['Commandargument']['id'], $servicetemplatecommandargumentvalues)) {
                                                $value = $servicetemplatecommandargumentvalues[$commandargument['Commandargument']['id']]['id'];
                                            }

                                            echo $this->Form->input('Servicetemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.id', [
                                                'type'  => 'hidden',
                                                'value' => $value,
                                            ]);
                                        }
                                    }
                                } else { ?>
                                    <div class="form-group">
                                        <label class="col col-md-2 control-label hidden-mobile hidden-tablet">
                                            <!-- spacer for nice layout --></label>
                                        <label class="col col-md-10 col-xs-12 text-primary">
                                            <i class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                            echo $this->Form->input('Servicetemplate.check_period_id', [
                                'options'          => $this->Html->chosenPlaceholder($_timeperiods),
                                'data-placeholder' => __('Please select...'),
                                'selected'         => $this->request->data['Servicetemplate']['check_period_id'],
                                'label'            => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <?php
                            echo $this->Form->input('Servicetemplate.max_check_attempts', [
                                'value'     => $this->request->data['Servicetemplate']['max_check_attempts'],
                                'label'     => ['text' => __('Max. number of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Checkinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="ServiceCheckinterval" maxlength="255" value=""
                                           class="form-control slider slider-success"
                                           name="data[Servicetemplate][check_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $servicetemplate['Servicetemplate']['check_interval'] ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#ServiceCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceCheckinterval" human="#ServiceCheckinterval_human"
                                           value="<?php echo $servicetemplate['Servicetemplate']['check_interval'] ?>"
                                           slider-for="ServiceCheckinterval" class="form-control slider-input"
                                           name="data[Servicetemplate][check_interval]">
                                    <span class="note"
                                          id="ServiceCheckinterval_human"><?php echo $this->Utils->secondsInWords($servicetemplate['Servicetemplate']['check_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="ServiceCheckinterval"><?php echo __('Retryinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="ServiceRetryinterval" maxlength="255" value=""
                                           class="form-control slider slider-primary"
                                           name="data[Servicetemplate][retry_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->request->data['Servicetemplate']['retry_interval'] ?>"
                                           data-slider-selection="before"
                                           data-slider-handle="round"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#ServiceRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_ServiceRetryinterval" human="#ServiceRetryinterval_human"
                                           value="<?php echo $this->request->data['Servicetemplate']['retry_interval'] ?>"
                                           slider-for="ServiceRetryinterval" class="form-control slider-input"
                                           name="data[Servicetemplate][retry_interval]">
                                    <span class="note"
                                          id="ServiceRetryinterval_human"><?php echo $this->Utils->secondsInWords($this->request->data['Servicetemplate']['retry_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <!-- expert settings -->
                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>
                            <!-- key words -->
                            <?php
                            echo $this->Form->input('tags', [
                                'label'     => ['text' => __('Tags'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'     => 'form-control tagsinput',
                                'data-role' => 'tagsinput',
                                'value'     => $this->request->data['Servicetemplate']['tags'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <?php echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                'caption'          => __('Flap detection'),
                                'wrapGridClass'    => 'col col-md-1',
                                'captionGridClass' => 'col col-md-2 no-padding',
                                'captionClass'     => 'control-label text-left no-padding',
                                'checked'          => $this->request->data['Servicetemplate']['flap_detection_enabled'],
                            ]); ?>
                            <br/>
                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_service_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_service_notification; ?></span>
                                <?php endif; ?>
                            </legend>
                            <?php foreach ($flapDetection_settings as $flapDetection_setting => $icon): ?>
                                <div style="border-bottom:1px solid lightGray;">
                                    <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                        'caption'          => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                        'icon'             => '<i class="fa '.$icon.'"></i> ',
                                        'class'            => 'onoffswitch-checkbox flapdetection_control',
                                        'checked'          => $this->request->data['Servicetemplate'][$flapDetection_setting],
                                        'wrapGridClass'    => 'col col-xs-1',
                                        'captionGridClass' => 'col col-xs-2',
                                    ]); ?>
                                    <div class="clearfix"></div>
                                </div>
                            <?php endforeach; ?>
                            <br/>
                            <legend class="font-sm">
                            </legend>
                            <div style="border-bottom:1px solid lightGray;">
                                <?php echo $this->Form->fancyCheckbox('Servicetemplate.is_volatile', [
                                    'caption'          => __(ucfirst('Status volatile')),
                                    'icon'             => '<i class="fa fa-asterisk"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'checked'          => $this->request->data['Servicetemplate']['is_volatile'],
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2',
                                ]); ?>
                                <div class="clearfix"></div>
                            </div>
                            <div style="border-bottom:1px solid lightGray;">
                                <?php echo $this->Form->fancyCheckbox('Servicetemplate.freshness_checks_enabled', [
                                    'caption'          => __('Freshness checks enabled'),
                                    'icon'             => '<i class="fa fa-foursquare"></i> ',
                                    'class'            => 'onoffswitch-checkbox',
                                    'checked'          => $this->request->data['Servicetemplate']['freshness_checks_enabled'],
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2',
                                ]);
                                ?>
                                <div class="clearfix"></div>
                                <div class="padding-left-10">
                                    <?php
                                    echo $this->Form->input('freshness_threshold', [
                                        'value'     => $this->request->data['Servicetemplate']['freshness_threshold'],
                                        'label'     => [
                                            'text'  => __('<i class="fa fa-clock-o"></i> Freshness threshold (seconds)'),
                                            'class' => 'col col-md-2 control-label text-left',
                                        ],
                                        'class'     => 'col col-md-12',
                                        'wrapInput' => 'col col-xs-8',
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <br>
                            <?php echo $this->Form->input('Servicetemplate.eventhandler_command_id', [
                                'options'          => $this->Html->chosenPlaceholder($eventhandlers),
                                'data-placeholder' => __('Please select...'),
                                'selected'         => $servicetemplate['Servicetemplate']['eventhandler_command_id'],
                                'label'            => ['text' => __('Eventhandler'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'             => '<span class="text-danger">'.__('Warning: If you change the event handler command, all service custom arguments will be reset to service template default!').'</span>'
                            ]); ?>
                            <div id="EventhandlerCommandArgs"></div>
                            <br>
                            <!-- Servicetemplate macro settings -->
                            <span class="note pull-left"><?php echo __('Servicetemplate macro settings'); ?>:</span>
                            <br class="clearfix"/>
                            <br/>
                            <?php if (isset($customVariableValidationError)): ?>
                                <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                            <?php endif; ?>
                            <?php if (isset($customVariableValidationErrorValue)): ?>
                                <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                            <?php endif; ?>
                            <?php $this->CustomVariables->setup('SERVICE', OBJECT_SERVICETEMPLATE, $servicetemplate['Customvariable']); ?>
                            <?php echo $this->CustomVariables->prepare('SERVICE'); ?>
                            <br/>
                        </div> <!-- close 2nd table -->
                    </div> <!-- close tab-content -->
                </div> <!-- close col -->
            </div> <!-- close row -->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
