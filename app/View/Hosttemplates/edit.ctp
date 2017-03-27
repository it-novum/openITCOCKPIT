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
    'flap_detection_on_up'          => 'fa-square txt-color-greenLight',
    'flap_detection_on_down'        => 'fa-square txt-color-redLight',
    'flap_detection_on_unreachable' => 'fa-square txt-color-blueDark',
];
$notification_settings = [
    'notify_on_recovery'    => 'fa-square txt-color-greenLight',
    'notify_on_down'        => 'fa-square txt-color-redLight',
    'notify_on_unreachable' => 'fa-square txt-color-blueDark',
    'notify_on_flapping'    => 'fa-random',
    'notify_on_downtime'    => 'fa-clock-o',
];
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Hosttemplate'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit Hosttemplate'); ?></h2>
        <div class="widget-toolbar pull-right" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, Hash::merge([$hosttemplate['Hosttemplate']['id']] ,$this->params['named'])); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>

        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: %s', h($hosttemplate['Hosttemplate']['uuid'])); ?>
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
            echo $this->Form->create('Hosttemplate', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <!-- basic settings -->
                        <div id="tab1" class="tab-pane fade active in">
                            <span class="note"><?php echo __('Basic configuration'); ?>:</span>
                            <?php
                            if ($hasRootPrivileges):
                                echo $this->Form->input('container_id', [
                                        'options'   => $containers,
                                        'multiple'  => false,
                                        'selected'  => $hosttemplate['Container']['id'],
                                        'class'     => 'chosen',
                                        'style'     => 'width: 100%',
                                        'label'     => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    ]
                                );
                            elseif (!$hasRootPrivileges && $hosttemplate['Container']['id'] != ROOT_CONTAINER):
                                echo $this->Form->input('container_id', [
                                        'options'   => $containers,
                                        'multiple'  => false,
                                        'selected'  => $hosttemplate['Container']['id'],
                                        'class'     => 'chosen',
                                        'style'     => 'width: 100%',
                                        'label'     => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
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
                                        'value' => $hosttemplate['Container']['id'],
                                        'type'  => 'hidden',
                                    ]
                                );
                            endif;

                            echo $this->Form->input('id', [
                                'type'  => 'hidden',
                                'value' => $this->request->data['Hosttemplate']['id'],
                            ]);
                            echo $this->Form->input('name', [
                                'value'     => $this->request->data['Hosttemplate']['name'],
                                'label'     => ['text' => __('Templatename'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('description', [
                                'value'     => $this->request->data['Hosttemplate']['description'],
                                'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Hosttemplate.Hostgroup', [
                                'options'          => $_hostgroups,
                                'data-placeholder' => __('Please select...'),
                                'multiple'         => true,
                                'class'            => 'chosen',
                                'style'            => 'width:100%;',
                                'label'            => ['text' => __('Hostgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'selected'         => $this->request->data['Hostgroup'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                             ]);

                            echo $this->Form->input('notes', [
                                'value'     => $hosttemplate['Hosttemplate']['notes'],
                                'label'     => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('host_url', [
                                'label'     => ['text' => __('Host URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'      => __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'),
                                'value'     => $this->request->data['Hosttemplate']['host_url'],
                            ]);
                            ?>
                            <div class="form-group <?php echo (isset($validationErrors['priority'])) ? 'has-error' : '' ?>">
                                <label class="col col-md-1 control-label text-left"><?php echo __('Priority'); ?></label>
                                <div class="col col-xs-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php
                                        // The smallest priority is 1 and the highest at the moment
                                        if ($this->request->data['Hosttemplate']['priority'] == 0):
                                            $this->request->data['Hosttemplate']['priority'] = 1;
                                        endif;
                                        ?>
                                        <?php for ($i = 5; $i > 0; $i--): ?>
                                            <input type="radio" <?php echo ($this->request->data['Hosttemplate']['priority'] == $i) ? 'checked="checked"' : '' ?>
                                                   id="stars-rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
                                                   name="data[Hosttemplate][priority]">
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
                            <!-- key words -->
                            <?php
                            echo $this->Form->input('tags', [
                                'label'     => ['text' => __('Tags'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'class'     => 'form-control tagsinput',
                                'data-role' => 'tagsinput',
                                'value'     => $this->request->data['Hosttemplate']['tags'],
                            ]);
                            ?>

                            <div class="padding-top-10"></div>
                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php
                            echo $this->Form->input('Hosttemplate.notify_period_id', [
                                'options'   => $_timeperiods,
                                'selected'  => $this->request->data['Hosttemplate']['notify_period_id'],
                                'class'     => 'chosen col col-xs-12',
                                'label'     => ['text' => __('Notification period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="HostNotificationinterval"><?php echo __('Notificationinterval'); ?></label>
                                <div class="col col-md-7 hidden-mobile">
                                    <input type="text" id="HostNotificationinterval" maxlength="255" value=""
                                           class="form-control slider slider-success"
                                           name="data[Hosttemplate][notification_interval]"
                                           data-slider-min="0"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->request->data['Hosttemplate']['notification_interval']; ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostNotificationinterval_human">
                                </div>
                                <div class="col col-xs-8 col-md-3">
                                    <input type="number" id="_HostNotificationinterval"
                                           human="#HostNotificationinterval_human"
                                           value="<?php echo $this->request->data['Hosttemplate']['notification_interval']; ?>"
                                           slider-for="HostNotificationinterval" class="form-control slider-input"
                                           name="data[Hosttemplate][notification_interval]">
                                    <span class="note"
                                          id="HostNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->request->data['Hosttemplate']['notification_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($notification_settings as $notification_setting => $icon): ?>
                                    <div>
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption'          => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'icon'             => '<i class="fa '.$icon.'"></i> ',
                                            'checked'          => $this->request->data['Hosttemplate'][$notification_setting],
                                            'class'            => 'onoffswitch-checkbox notification_control',
                                            'captionGridClass' => 'col col-xs-2',
                                            'wrapGridClass'    => 'col col-xs-1',
                                        ]); ?>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <br/>

                            <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                'caption'          => __('Enable active checks'),
                                'wrapGridClass'    => 'col col-md-1',
                                'captionGridClass' => 'col col-md-2 no-padding',
                                'captionClass'     => 'control-label text-left no-padding',
                                'checked'          => $this->CustomValidationErrors->refill('active_checks_enabled', (bool)$this->request->data['Hosttemplate']['active_checks_enabled']),
                                'icon'             => '<i class="fa fa-sign-in"></i> ',
                            ]); ?>
                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <?php
                            echo $this->Form->input('Hosttemplate.Contact', [
                                'options'   => $_contacts,
                                'selected'  => $this->request->data['Contact'],
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <?php
                            echo $this->Form->input('Hosttemplate.Contactgroup', [
                                'options'   => $_contactgroups,
                                'selected'  => $this->request->data['Contactgroup'],
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                        </div>


                        <div id="tab2" class="tab-pane fade">
                            <!-- check settings -->
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php
                            echo $this->Form->input('Hosttemplate.command_id', [
                                'options'   => $commands,
                                'selected'  => $this->request->data['Hosttemplate']['command_id'],
                                'class'     => 'chosen col col-xs-12',
                                'label'     => ['text' => __('Checkcommand'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'             => '<span class="text-danger">'.__('Warning: If you change the check command, all host custom arguments will be reset to host template default!').'</span>'
                            ]);
                            ?>
                            <!-- Command arguments -->
                            <div id="CheckCommandArgs">
                                <?php
                                if (!empty($commandarguments)):
                                    $hosttemplatecommandargumentvalues = Hash::combine($hosttemplate['Hosttemplatecommandargumentvalue'], '{n}.commandargument_id', '{n}');
                                    foreach ($commandarguments as $key => $commandargument):
                                        echo $this->Form->input('Hosttemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.value', [
                                            'label'     => [
                                                'class' => 'col col-md-2 control-label text-primary',
                                                'text'  => $commandargument['Commandargument']['human_name'],
                                            ],
                                            'value'     => (array_key_exists($commandargument['Commandargument']['id'], $hosttemplatecommandargumentvalues)) ? $hosttemplatecommandargumentvalues[$commandargument['Commandargument']['id']]['value'] : '',
                                            'wrapInput' => 'col col-xs-9 col-md-9 col-lg-9',
                                        ]);
                                        echo $this->Form->input('Hosttemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.commandargument_id', [
                                            'type'  => 'hidden',
                                            'value' => $commandargument['Commandargument']['id'],
                                        ]);
                                        if ($commandargument['Commandargument']['id'] !== null):
                                            echo $this->Form->input('Hosttemplatecommandargumentvalue.'.$commandargument['Commandargument']['id'].'.id', [
                                                'type'  => 'hidden',
                                                'value' => (array_key_exists($commandargument['Commandargument']['id'], $hosttemplatecommandargumentvalues)) ? $hosttemplatecommandargumentvalues[$commandargument['Commandargument']['id']]['id'] : '',
                                            ]);
                                        endif;
                                    endforeach;
                                else:
                                    ?>
                                    <div class="form-group">
                                        <label class="col col-md-2 control-label hidden-mobile hidden-tablet">
                                            <!-- spacer for nice layout --></label>
                                        <label class="col col-md-10 col-xs-12 text-primary"><i
                                                    class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                            echo $this->Form->input('Hosttemplate.check_period_id', [
                                'options'   => $_timeperiods,
                                'selected'  => $this->request->data['Hosttemplate']['check_period_id'],
                                'class'     => 'chosen col col-xs-12',
                                'label'     => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <?php
                            echo $this->Form->input('Hosttemplate.max_check_attempts', [
                                'value'     => $this->request->data['Hosttemplate']['max_check_attempts'],
                                'label'     => ['text' => __('Max. numer of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="HostCheckinterval"><?php echo __('Checkinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="HostCheckinterval" maxlength="255" value=""
                                           class="form-control slider slider-success"
                                           name="data[Hosttemplate][check_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->request->data['Hosttemplate']['check_interval']; ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostCheckinterval" human="#HostCheckinterval_human"
                                           value="<?php echo $this->request->data['Hosttemplate']['check_interval']; ?>"
                                           slider-for="HostCheckinterval" class="form-control slider-input"
                                           name="data[Hosttemplate][check_interval]">
                                    <span class="note"
                                          id="HostCheckinterval_human"><?php echo $this->Utils->secondsInWords($this->request->data['Hosttemplate']['check_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label text-left"
                                       for="HostCheckinterval"><?php echo __('Retryinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="HostRetryinterval" maxlength="255" value=""
                                           class="form-control slider slider-primary"
                                           name="data[Hosttemplate][retry_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->request->data['Hosttemplate']['retry_interval']; ?>"
                                           data-slider-selection="before"
                                           data-slider-handle="round"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostRetryinterval" human="#HostRetryinterval_human"
                                           value="<?php echo $this->request->data['Hosttemplate']['retry_interval']; ?>"
                                           slider-for="HostRetryinterval" class="form-control slider-input"
                                           name="data[Hosttemplate][retry_interval]">
                                    <span class="note"
                                          id="HostRetryinterval_human"><?php echo $this->Utils->secondsInWords($this->request->data['Hosttemplate']['retry_interval']); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <div class="padding-top-10"></div>
                            <!-- expert settings -->
                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                'caption'          => __('Flap detection'),
                                'captionGridClass' => 'col col-xs-2 text-left',
                                'wrapGridClass'    => 'col col-xs-1',
                                'captionClass'     => 'control-label',
                                'checked'          => $this->request->data['Hosttemplate']['flap_detection_enabled'],
                            ]); ?>
                            <br/>
                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_host_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                                <?php endif; ?>
                            </legend>
                            <div class="form-group <?php echo $this->CustomValidationErrors->errorClass('flap_detection_on_up'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('flap_detection_on_up', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($flapDetection_settings as $flapDetection_setting => $icon): ?>
                                    <div>
                                        <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                            'caption'          => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                            'icon'             => '<i class="fa '.$icon.'"></i> ',
                                            'checked'          => $this->request->data['Hosttemplate'][$flapDetection_setting],
                                            'class'            => 'onoffswitch-checkbox flapdetection_control',
                                            'captionGridClass' => 'col col-xs-2',
                                            'wrapGridClass'    => 'col col-xs-1',
                                        ]); ?>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Host macro settings -->
                            <span class="note pull-left"><?php echo __('Host macro settings'); ?>:</span>
                            <br class="clearfix"/>
                            <br/>
                            <?php if (isset($customVariableValidationError)): ?>
                                <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                            <?php endif; ?>
                            <?php if (isset($customVariableValidationErrorValue)): ?>
                                <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                            <?php endif; ?>
                            <?php $this->CustomVariables->setup('HOST', OBJECT_HOSTTEMPLATE, $hosttemplate['Customvariable']); ?>
                            <?php echo $this->CustomVariables->prepare(); ?>
                            <br/>
                        </div>

                    </div> <!-- close tab-content -->
                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
