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
                <?php echo __('Host'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Add Host'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
        <div class="widget-toolbar" role="menu">
            <span class="onoffswitch-title" rel="tooltip" data-placement="top"
                  data-original-title="<?php echo __('auto DNS lookup'); ?>"><i class="fa fa-search"></i></span>
            <span class="onoffswitch">
					<input type="checkbox" id="autoDNSlookup" checked="checked" class="onoffswitch-checkbox"
                           name="onoffswitch">
					<label for="autoDNSlookup" class="onoffswitch-label">
						<span data-swchoff-text="<?php echo __('Off'); ?>" data-swchon-text="<?php echo __('On'); ?>"
                              class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</span>
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

            <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host', 'tabLink'); ?>
        </ul>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Host', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade active in">
                            <!-- basic settings -->
                            <span class="note"><?php echo __('Basic configuration'); ?>:</span>
                            <?php
                            echo $this->Form->input('container_id', [
                                    'options'          => $this->Html->chosenPlaceholder($containers),
                                    'data-placeholder' => __('Please select...'),
                                    'multiple'         => false,
                                    'class'            => 'chosen',
                                    'style'            => 'width: 100%',
                                    'label'            => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );

                            if ($this->Acl->hasPermission('sharing')) {
                                echo $this->Form->input('shared_container', [
                                        'options'   => $this->Html->chosenPlaceholder($sharingContainers),
                                        'multiple'  => true,
                                        'class'     => 'chosen',
                                        'style'     => 'width: 100%',
                                        'label'     => ['text' => __('Shared containers'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    ]
                                );
                            }

                            echo $this->Form->input('hosttemplate_id', [
                                'label'            => ['text' => __('Hosttemplate'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'options'          => $this->Html->chosenPlaceholder($_hosttemplates),
                                'data-placeholder' => __('Please select...'),
                                'class'            => 'chosen',
                                'style'            => 'width:100%;',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('name', [
                                'label'     => ['text' => __('Hostname'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('description', [
                                'label'     => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);

                            echo $this->Form->input('address', [
                                'label'     => ['text' => __('Address'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);

                            echo $this->Form->input('Host.Hostgroup', [
                                'options'          => $_hostgroups,
                                'data-placeholder' => __('Please select...'),
                                'multiple'         => true,
                                'class'            => 'chosen',
                                'style'            => 'width:100%;',
                                'label'            => ['text' => __('Hostgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);

                            echo $this->Form->input('Host.Parenthost', [
                                'options'          => $_parenthosts,
                                'data-placeholder' => __('Please select...'),
                                'multiple'         => true,
                                'class'            => 'chosen',
                                'style'            => 'width:100%;',
                                'label'            => ['text' => __('Parent hosts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);

                            echo $this->Form->input('notes', [
                                'label'     => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('host_url', [
                                'label'     => ['text' => __('Host URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                'help'      => __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'),
                            ]);
                            ?>
                            <div class="form-group <?php echo (isset($validationErrors['priority'])) ? 'has-error' : '' ?>">
                                <label class="col col-xs-1 col-md-1 col-lg-1 control-label text-left"><?php echo __('Priority'); ?></label>
                                <div class="col col-xs-10 col-md-10 col-lg-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php // The smallest priority is 1 at the moment
                                        $priority = $this->CustomValidationErrors->refill('priority', 1);
                                        ?>
                                        <?php for ($i = 5; $i > 0; $i--): ?>
                                            <input type="radio" <?php echo ($priority == $i) ? 'checked="checked"' : '' ?>
                                                   id="Hoststars-rating-<?php echo $i; ?>" value="<?php echo $i; ?>"
                                                   name="data[Host][priority]">
                                            <label for="Hoststars-rating-<?php echo $i; ?>"><i
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
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->AdditionalLinks->renderElements($additionalElementsForm);
                            ?>

                            <div class="padding-top-10"></div>
                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php echo $this->Form->input('Host.notify_period_id', [
                                'options'   => $_timeperiods,
                                'label'     => ['text' => __('Notification period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'     => 'chosen col col-xs-12',
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostNotificationinterval"><?php echo __('Notificationinterval'); ?></label>
                                <div class="col col-md-7 hidden-mobile">
                                    <input type="text" id="HostNotificationinterval" maxlength="255" value=""
                                           class="form-control slider slider-success"
                                           name="data[Host][notification_interval]"
                                           data-slider-min="0"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->CustomValidationErrors->refill('notification_interval', 0); ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostNotificationinterval_human">
                                </div>
                                <div class="col col-xs-8 col-md-3">
                                    <input type="number" id="_HostNotificationinterval"
                                           human="#HostNotificationinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('notification_interval', 0); ?>"
                                           slider-for="HostNotificationinterval" class="form-control slider-input"
                                           name="data[Host][notification_interval]">
                                    <span class="note"
                                          id="HostNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('notification_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="padding-left-20 <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <label class="padding-10"><?php echo __('Notification options: '); ?> </label>
                                <?php echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($notification_settings as $notification_setting => $icon): ?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption'          => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'captionGridClass' => 'col col-xs-2 col-md-2 col-lg-2',
                                            'icon'             => '<i class="fa '.$icon.'"></i> ',
                                            'class'            => 'onoffswitch-checkbox notification_control',
                                            'checked'          => $this->CustomValidationErrors->refill($notification_setting, false),
                                            'wrapGridClass'    => 'col col-xs-2',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <br/>

                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                    'caption'          => __('Enable active checks'),
                                    'wrapGridClass'    => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 col-md-2 col-lg-2 no-padding',
                                    'captionClass'     => 'control-label text-left no-padding',
                                    'checked'          => $this->CustomValidationErrors->refill('active_checks_enabled', false),
                                    'icon'             => '<i class="fa fa-sign-in"></i> ',
                                ]); ?>
                            </div>

                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <?php
                            echo $this->Form->input('Host.Contact', [
                                'options'   => $_contacts,
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Host.Contactgroup', [
                                'options'   => $_contactgroups,
                                'multiple'  => true,
                                'class'     => 'chosen',
                                'style'     => 'width:100%;',
                                'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);; ?>
                        </div>

                        <div id="tab2" class="tab-pane fade">
                            <!-- check settings -->
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php echo $this->Form->input('Host.command_id', [
                                'options'          => $this->Html->chosenPlaceholder($commands),
                                'data-placeholder' => __('Please select...'),
                                'label'            => ['text' => __('Checkcommand'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'            => 'chosen col col-xs-12',
                                'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                            ]); ?>
                            <div id="CheckCommandArgs">
                                <!-- Contact gets loaded by AJAX -->
                            </div>
                            <?php
                            echo $this->Form->input('Host.check_period_id', [
                                'options'   => $_timeperiods,
                                'label'     => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class'     => 'chosen col col-xs-12',
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            echo $this->Form->input('Host.max_check_attempts', [
                                'label'     => ['text' => __('Max. number of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostCheckinterval"><?php echo __('Checkinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="HostCheckinterval"
                                            maxlength="255"
                                            value="<?php echo $this->CustomValidationErrors->refill('check_interval', 0); ?>"
                                            class="form-control slider slider-success"
                                            name="data[Host][check_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('check_interval', 0); ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#HostCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostCheckinterval" human="#HostCheckinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('check_interval', 0); ?>"
                                           slider-for="HostCheckinterval" class="form-control slider-input"
                                           name="data[Host][check_interval]">
                                    <span class="note"
                                          id="HostCheckinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('check_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostCheckinterval"><?php echo __('Retryinterval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="HostRetryinterval" maxlength="255"
                                           value="<?php echo $this->CustomValidationErrors->refill('retry_interval', 0); ?>"
                                           class="form-control slider slider-primary" name="data[Host][retry_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->CustomValidationErrors->refill('retry_interval', 0); ?>"
                                           data-slider-selection="before"
                                           data-slider-handle="round"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostRetryinterval" human="#HostRetryinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('retry_interval', 0); ?>"
                                           slider-for="HostRetryinterval" class="form-control slider-input"
                                           name="data[Host][retry_interval]">
                                    <span class="note"
                                          id="HostRetryinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('retry_interval', 0)); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <div class="padding-top-10"></div>
                            <!-- expert settings -->
                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>

                            <div class="form-group">
                                <?php echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                    'caption'          => __('Flap detection'),
                                    'wrapGridClass'    => 'col col-xs-2',
                                    'captionGridClass' => 'col col-xs-2 text-left',
                                    'captionClass'     => 'control-label',
                                    'checked'          => $this->CustomValidationErrors->refill('flap_detection_enabled', false),
                                ]); ?>
                            </div>

                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_host_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                                <?php endif; ?>
                            </legend>
                            <div class="<?php echo $this->CustomValidationErrors->errorClass('flap_detection_on_up'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('flap_detection_on_up', ['style' => 'margin-left: 15px;']); ?>
                                <?php foreach ($flapDetection_settings as $flapDetection_setting => $icon): ?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                            'caption'          => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                            'icon'             => '<i class="fa '.$icon.'"></i> ',
                                            'class'            => 'onoffswitch-checkbox flapdetection_control',
                                            'checked'          => $this->CustomValidationErrors->refill($flapDetection_setting, false),
                                            'wrapGridClass'    => 'col col-xs-2',
                                            'captionGridClass' => 'col col-xs-2',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php /*
							<!-- remote access -->
							<span class="note pull-left"><?php echo __('Remote access'); ?>:</span>
							<br class="clearfix" />
							<?php echo $this->Form->fancyCheckbox('ssh', [
								'on' => 'SSH',
								'off' => 'SSH',
								'showLabel' => false,
								'wrapGridClass' => 'col col-md-1'
							]);?>
								<?php echo $this->Form->input('ssh', ['label' => false]); ?>
							*/ ?>
                            <!-- Host macro settings -->
                            <div class="host-macro-settings">
                                <span class="note pull-left"><?php echo __('Host macro settings'); ?>:</span>
                                <br class="clearfix"/>
                                <br/>
                                <?php if (isset($customVariableValidationError)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                                <?php endif; ?>
                                <?php if (isset($customVariableValidationErrorValue)): ?>
                                    <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                                <?php endif;

                                $counter = 0;
                                $this->CustomVariables->setup($macrotype = 'HOST', OBJECT_HOST);
                                echo $this->CustomVariables->__startWrap();
                                foreach ($Customvariable as $hostmacro):
                                    echo $this->CustomVariables->html($counter, [
                                        'name'  => $hostmacro['name'],
                                        'value' => $hostmacro['value'],
                                    ]);
                                    $counter++;
                                endforeach;
                                echo $this->CustomVariables->__endWrap();
                                echo $this->CustomVariables->addButton();
                                ?>
                            </div>
                        </div>
                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host'); ?>

                    </div> <!-- close tab-content -->
                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
