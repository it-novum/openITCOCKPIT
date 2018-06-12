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
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Contacts'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?><?php echo __('contact'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Contact', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Container', [
                    'options'  => $containers,
                    'multiple' => true,
                    'class'    => 'chosen',
                    'style'    => 'width: 100%',
                    'label'    => __('Container'),
                    'div'      => [
                        'class' => 'form-group required',
                    ],
                ]
            );
            echo $this->Form->input('Contact.name');
            echo $this->Form->input('Contact.description');

            $options = [
                'placeholder' => __('username@example.org')
            ];
            if ($isLdap === true):
                $options['readonly'] = true;
            endif;
            echo $this->Form->input('Contact.email', $options);
            echo $this->Form->input('Contact.phone', [
                'placeholder' => '0049123456789'
            ]);
            ?>
            <br/>
            <div class="row">
                <?php $notification_settings = [
                    'host'    => [
                        'notify_host_recovery'    => 'fa-square txt-color-greenLight',
                        'notify_host_down'        => 'fa-square txt-color-redLight',
                        'notify_host_unreachable' => 'fa-square txt-color-blueDark',
                        'notify_host_flapping'    => 'fa-random',
                        'notify_host_downtime'    => 'fa-clock-o',
                    ],
                    'service' => [
                        'notify_service_recovery' => 'fa-square txt-color-greenLight',
                        'notify_service_warning'  => 'fa-square txt-color-orange',
                        'notify_service_unknown'  => 'fa-square txt-color-blueDark',
                        'notify_service_critical' => 'fa-square txt-color-redLight',
                        'notify_service_flapping' => 'fa-random',
                        'notify_service_downtime' => 'fa-clock-o',
                    ],
                ];
                ?>
                <article class="col-sm-12 col-md-12 col-lg-6">
                    <div id="wid-id-1" class="jarviswidget jarviswidget-sortable" data-widget-custombutton="false"
                         data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
                        <header role="heading">
                            <span class="widget-icon">
                                <i class="fa fa-desktop"></i>
                            </span>
                            <h2><?php echo __('Notification (Host)'); ?></h2>
                        </header>
                        <div role="content" style="min-height:400px;">
                            <div class="widget-body">
                                <div>
                                    <?php echo $this->Form->input('host_timeperiod_id', ['options' => $this->Html->chosenPlaceholder($_timeperiods), 'class' => 'select2 col-xs-8 chosen', 'wrapInput' => 'col col-xs-8', 'label' => ['class' => 'col col-md-4 control-label text-left'], 'style' => 'width: 100%']); ?>
                                    <?php echo $this->Form->input('Contact.HostCommands', [
                                        'options'  => $this->Html->chosenPlaceholder($notification_commands), 'class' => 'select2 col-xs-8 chosen', 'wrapInput' => 'col col-xs-8', 'label' => ['class' => 'col col-md-4 control-label text-left'],
                                        'multiple' => true, 'style' => 'width: 100%'
                                    ]); ?>
                                    <?php echo $this->Form->fancyCheckbox('host_notifications_enabled', [
                                        'caption'          => __('Notifications enabled'),
                                        'captionGridClass' => 'col col-md-4 no-padding',
                                        'captionClass'     => 'control-label text-left no-padding',
                                    ]); ?>

                                </div>
                                <br class="clearfix"/>
                                <fieldset>
                                    <legend class="font-sm">
                                        <div class="required">
                                            <label><?php echo __('Host notification options'); ?></label>
                                        </div>
                                        <?php if (isset($validation_host_notification)): ?>
                                            <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                                        <?php endif; ?>
                                    </legend>
                                    <?php foreach ($notification_settings['host'] as $notification_setting => $icon): ?>
                                        <div style="border-bottom:1px solid lightGray;">
                                            <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                                'caption' => ucfirst(preg_replace('/notify_host_/', '', $notification_setting)),
                                                'icon'    => '<i class="fa ' . $icon . '"></i> ',
                                            ]); ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="col-sm-12 col-md-12 col-lg-6">
                    <div id="wid-id-2" class="jarviswidget jarviswidget-sortable" data-widget-custombutton="false"
                         data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
                        <header role="heading">
                            <span class="widget-icon">
                                <i class="fa fa-gear"></i>
                            </span>
                            <h2><?php echo __('Notification (Service)'); ?></h2>
                        </header>
                        <div role="content" style="min-height:400px;">
                            <div class="widget-body">
                                <div>
                                    <?php echo $this->Form->input('service_timeperiod_id', ['options' => $this->Html->chosenPlaceholder($_timeperiods), 'class' => 'select2 col-xs-8 chosen', 'wrapInput' => 'col col-xs-8', 'label' => ['class' => 'col col-md-4 control-label text-left'], 'style' => 'width: 100%']); ?>
                                    <?php echo $this->Form->input('Contact.ServiceCommands', [
                                        'options'  => $this->Html->chosenPlaceholder($notification_commands), 'class' => 'select2 col-xs-8 chosen', 'wrapInput' => 'col col-xs-8', 'label' => ['class' => 'col col-md-4 control-label text-left'],
                                        'multiple' => true, 'style' => 'width: 100%'
                                    ]); ?>
                                    <?php echo $this->Form->fancyCheckbox('service_notifications_enabled', [
                                        'caption'          => __('Notifications enabled'),
                                        'captionGridClass' => 'col col-md-4 no-padding',
                                        'captionClass'     => 'control-label text-left no-padding',
                                    ]); ?>

                                </div>
                                <br class="clearfix"/>
                                <fieldset>
                                    <legend class="font-sm">
                                        <div class="required">
                                            <label><?php echo __('Service notification options'); ?></label>
                                        </div>
                                        <?php if (isset($validation_service_notification)): ?>
                                            <span class="text-danger"><?php echo $validation_service_notification; ?></span>
                                        <?php endif; ?>
                                    </legend>
                                    <?php foreach ($notification_settings['service'] as $notification_setting => $icon): ?>
                                        <div style="border-bottom:1px solid lightGray;">
                                            <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                                'caption' => ucfirst(preg_replace('/notify_service_/', '', $notification_setting)),
                                                'icon'    => '<i class="fa ' . $icon . '"></i> ',
                                            ]); ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="col-xs-12">
                    <div id="wid-id-2" class="jarviswidget jarviswidget-sortable" data-widget-custombutton="false"
                         data-widget-editbutton="false" data-widget-colorbutton="false" role="widget">
                        <header role="heading">
                            <span class="widget-icon">
                                <i class="fa fa-gear"></i>
                            </span>
                            <h2><?php echo __('Browser push notifications'); ?></h2>
                        </header>
                        <div role="content" style="min-height:400px;">
                            <div class="widget-body">

                                <div class="row">
                                    <?php echo $this->Form->fancyCheckbox('host_push_notifications', [
                                        'caption'          => __('Enable host browser notifications'),
                                        'captionGridClass' => 'col col-xs-2 no-padding',
                                        'wrapGridClass'    => 'col col-xs-10',
                                        'captionClass'     => 'control-label text-left no-padding',
                                    ]); ?>
                                </div>

                                <div class="row">
                                    <?php echo $this->Form->fancyCheckbox('service_push_notifications', [
                                        'caption'          => __('Enable service browser notifications'),
                                        'captionGridClass' => 'col col-xs-2 no-padding',
                                        'wrapGridClass'    => 'col col-xs-10',
                                        'captionClass'     => 'control-label text-left no-padding',
                                    ]); ?>
                                </div>

                                <div class="row">
                                    <?php echo $this->Form->input('user_id', [
                                        'options'   => $this->Html->chosenPlaceholder($_timeperiods),
                                        'class'     => 'select2 col-xs-10 chosen',
                                        'wrapInput' => 'col col-xs-10',
                                        'label'     => [
                                            'class' => 'col col-xs-2 control-label text-left',
                                            'text'  => __('User')
                                        ],
                                        'style'     => 'width: 100%',
                                        'help' => __('For browser notifications, a user needs to be assigned to the contact.')
                                    ]); ?>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <p><?php echo __('Example of a browser notification:'); ?></p>
                                        <img src="/img/browser_notification.png" class="img-responsive"
                                             alt="<?php echo __('Example of a browser push notification'); ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </article>

            </div>


            <div class="row margin-bottom-10">
                <div class="col-xs-12">
                    <br/>
                    <legend class="font-sm"></legend>

                    <!-- Host macro settings -->
                    <div class="host-macro-settings">
                        <span class="note pull-left"><?php echo __('Contact macro settings'); ?>:</span>
                        <br class="clearfix"/>
                        <br/>
                        <?php if (isset($customVariableValidationError)): ?>
                            <div class="text-danger"><?php echo $customVariableValidationError; ?></div>
                        <?php endif; ?>
                        <?php if (isset($customVariableValidationErrorValue)): ?>
                            <div class="text-danger"><?php echo $customVariableValidationErrorValue; ?></div>
                        <?php endif;
                        $counter = 0;
                        $this->CustomVariables->setup($macrotype = 'CONTACT', OBJECT_CONTACT);
                        echo $this->CustomVariables->__startWrap();
                        foreach ($Customvariable as $servicemacro):
                            echo $this->CustomVariables->html($counter, [
                                'name'  => $servicemacro['name'],
                                'value' => $servicemacro['value'],
                            ]);
                            $counter++;
                        endforeach;
                        echo $this->CustomVariables->__endWrap();
                        echo $this->CustomVariables->addButton();
                        ?>
                        <br/>
                    </div>
                </div>
            </div>

            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
