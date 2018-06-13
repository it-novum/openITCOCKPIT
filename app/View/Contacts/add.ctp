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
        <h2>
            <?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?>
            <?php echo __('contact'); ?>
        </h2>
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
            <div class="row">
                <?php echo $this->Form->input('user_id', [
                    'options' => $this->Html->chosenPlaceholder($_users),
                    'class'   => 'chosen',
                    'style' => 'width: 100%',
                    'help'  => __('For browser notifications, a user needs to be assigned to the contact. User Id will be automatically available as $_CONTACTOITCUSERID$ contact macro.')
                ]); ?>
            </div>
            <br/>
            <div class="row">
                <?php $notification_settings = [
                    'host'    => [
                        'notify_host_recovery'    => '<span class="label label-success notify-label">' . __('Recovery') . '</span>',
                        'notify_host_down'        => '<span class="label label-danger notify-label">' . __('Down') . '</span>',
                        'notify_host_unreachable' => '<span class="label label-default notify-label">' . __('Unreachable') . '</span>',
                        'notify_host_flapping'    => '<span class="label label-primary notify-label">' . __('Flapping') . '</span>',
                        'notify_host_downtime'    => '<span class="label label-primary notify-label">' . __('Downtime') . '</span>',
                    ],
                    'service' => [
                        'notify_service_recovery' => '<span class="label label-success notify-label">' . __('Recovery') . '</span>',
                        'notify_service_warning'  => '<span class="label label-warning notify-label">' . __('Warning') . '</span>',
                        'notify_service_critical' => '<span class="label label-danger notify-label">' . __('Critical') . '</span>',
                        'notify_service_unknown'  => '<span class="label label-default notify-label">' . __('Unknown') . '</span>',
                        'notify_service_flapping' => '<span class="label label-primary notify-label">' . __('Flapping') . '</span>',
                        'notify_service_downtime' => '<span class="label label-primary notify-label">' . __('Downtime') . '</span>',
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
                        <div role="content" style="min-height:430px;">
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
                                        'checked'          => $this->CustomValidationErrors->refill('host_notifications_enabled', false)
                                    ]); ?>

                                </div>

                                <?php
                                $s = sprintf(
                                    '%s <i class="fa fa-info-circle text-info"
                                            data-template="<div class=\'tooltip\' role=\'tooltip\'><div class=\'tooltip-arrow tooltip-arrow-image\'></div><div class=\'tooltip-inner tooltip-inner-image\'></div></div>"
                                            rel="tooltip"
                                            data-placement="right"
                                            data-original-title="<img src=\'/img/browser_notification_bg.png\'/>"
                                            data-html="true"></i>',
                                    __('Push notifications to browser')
                                );
                                ?>


                                <div class="row">
                                    <?php echo $this->Form->fancyCheckbox('host_push_notifications_enabled', [
                                        'caption'          => $s,
                                        'captionGridClass' => 'col col-md-4 no-padding',
                                        'captionClass'     => 'control-label text-left no-padding',
                                        'checked'          => $this->CustomValidationErrors->refill('host_push_notifications_enabled', false)
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
                                                'caption' => '',
                                                'icon'    => $icon,
                                                'checked' => $this->CustomValidationErrors->refill($notification_setting, false)
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
                        <div role="content" style="min-height:430px;">
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
                                        'checked'          => $this->CustomValidationErrors->refill('service_notifications_enabled', false)
                                    ]); ?>
                                </div>
                                <div class="row">
                                    <?php
                                    $s = sprintf(
                                        '%s <i class="fa fa-info-circle text-info"
                                            data-template="<div class=\'tooltip\' role=\'tooltip\'><div class=\'tooltip-arrow tooltip-arrow-image\'></div><div class=\'tooltip-inner tooltip-inner-image\'></div></div>"
                                            rel="tooltip"
                                            data-placement="right"
                                            data-original-title="<img src=\'/img/browser_service_notification_bg.png\'/>"
                                            data-html="true"></i>',
                                        __('Push notifications to browser')
                                    );
                                    echo $this->Form->fancyCheckbox('service_push_notifications_enabled', [
                                        'caption'          => $s,
                                        'captionGridClass' => 'col col-md-4 no-padding',
                                        'captionClass'     => 'control-label text-left no-padding',
                                        'checked'          => $this->CustomValidationErrors->refill('service_push_notifications_enabled', false)
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
                                                'caption' => '',
                                                'icon'    => $icon,
                                                'checked' => $this->CustomValidationErrors->refill($notification_setting, false)
                                            ]); ?>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </article>

                <?php if ($this->Acl->hasPermission('wiki', 'documentations')): ?>
                    <article class="col-sm-12 col-md-12 col-lg-6 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Read more about browser push notification in the'); ?>
                        <a href="/documentations/wiki/additional_help/browser_push_notifications/en">
                            <?php echo __('documentation'); ?>
                        </a>
                    </article>
                <?php endif; ?>

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
