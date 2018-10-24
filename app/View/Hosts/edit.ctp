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

use itnovum\openITCOCKPIT\Core\HostSharingPermissions;

$flapDetection_settings = [
    'flap_detection_on_up' => 'fa-square txt-color-greenLight',
    'flap_detection_on_down' => 'fa-square txt-color-redLight',
    'flap_detection_on_unreachable' => 'fa-square txt-color-blueDark',
];
$notification_settings = [
    'notify_on_recovery' => 'fa-square txt-color-greenLight',
    'notify_on_down' => 'fa-square txt-color-redLight',
    'notify_on_unreachable' => 'fa-square txt-color-blueDark',
    'notify_on_flapping' => 'fa-random',
    'notify_on_downtime' => 'fa-clock-o',
];
$hostSharingPermissions = new HostSharingPermissions(
    $host['Host']['container_id'],
    $hasRootPrivileges,
    Hash::extract($_host['Container'], '{n}.id'),
    $MY_RIGHTS
);
$allowSharing = $hostSharingPermissions->allowSharing();
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
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit host'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#workaroundConfirmDelete">
                    <i class="fa fa-trash-o"></i>
                    <?php echo __('Delete'); ?>
                </button>
            <?php endif; ?>
            <?php if ($this->Acl->hasPermission('browser')): ?>
                <a href="/hosts/browser/<?php echo $host['Host']['id']; ?>" class="btn btn-default btn-xs"><i
                            class="fa fa-desktop"></i> <?php echo __('Browser'); ?></a>
            <?php endif; ?>
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

        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: %s', h($host['Host']['uuid'])); ?>
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
                            echo $this->Form->input('Host.id', [
                                    'type' => 'hidden',
                                    'value' => $host['Host']['id'],
                                    'wrapInput' => 'col col-xs-8',
                                ]
                            );

                            if ($hasRootPrivileges && $host['Host']['container_id'] != ROOT_CONTAINER):
                                echo $this->Form->input('container_id', [
                                        'options' => $containers,
                                        'oldValue' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
                                        'multiple' => false,
                                        'selected' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
                                        'class' => 'chosen',
                                        'style' => 'width: 100%',
                                        'label' => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    ]
                                );
                            elseif (!$hasRootPrivileges && in_array($host['Host']['container_id'], $MY_WRITABLE_CONTAINERS)):
                                echo $this->Form->input('container_id', [
                                        'options' => $containers,
                                        'oldValue' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
                                        'multiple' => false,
                                        'selected' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
                                        'class' => 'chosen',
                                        'style' => 'width: 100%',
                                        'label' => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    ]
                                );
                            elseif (!$hasRootPrivileges && $host['Host']['container_id'] != ROOT_CONTAINER):
                                echo $this->Form->input('container_id', [
                                        'options' => $containers,
                                        'multiple' => false,
                                        'selected' => $this->Html->getParameter('Host.container_id', $host['Host']['container_id']),
                                        'class' => 'chosen',
                                        'style' => 'width: 100%',
                                        'label' => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                        'disabled' => true,
                                    ]
                                );
                                echo $this->Form->input('container_id', [
                                        'value' => $host['Host']['container_id'],
                                        'type' => 'hidden',
                                    ]
                                );
                            else:
                                ?>
                                <div class="form-group required">
                                    <label class="col col-md-1 control-label">
                                        <?php echo __('Container'); ?>
                                    </label>
                                    <div class="col col-xs-10 required">
                                        <input type="text" value="/root" class="form-control" readonly>
                                        <span class="help-block"><?php echo __("Objects in /root can't be moved to other containers"); ?></span>
                                    </div>
                                </div>
                                <?php
                                echo $this->Form->input('container_id', [
                                        'value' => $host['Host']['container_id'],
                                        'type' => 'hidden',
                                    ]
                                );
                            endif;
                            if ($this->Acl->hasPermission('sharing') && $allowSharing) {
                                if ($host['Host']['host_type'] == GENERIC_HOST) {
                                    echo $this->Form->input('shared_container', [
                                            'options' => $this->Html->chosenPlaceholder($sharingContainers),
                                            'multiple' => true,
                                            'selected' => $sharedContainers,
                                            'class' => 'chosen',
                                            'style' => 'width: 100%',
                                            'label' => ['text' => __('Shared containers'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                            'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                        ]
                                    );
                                }
                            } else {
                                echo $this->Form->input('shared_container', [
                                        'value' => serialize($sharedContainers),
                                        'type' => 'hidden',
                                    ]
                                );
                            }

                            echo $this->Form->input('hosttemplate_id', [
                                    'label' => [
                                        'text' => '<a href="/hosttemplates/edit/' . $host['Host']['hosttemplate_id'] . '"><i class="fa fa-cog"></i> </a>' . __('Hosttemplate'),
                                        'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'options' => $this->Html->chosenPlaceholder($_hosttemplates),
                                    'data-placeholder' => __('Please select...'),
                                    'class' => 'chosen',
                                    'style' => 'width:100%;',
                                    'selected' => $this->Html->getParameter('Host.hosttemplate_id', $host['Host']['hosttemplate_id']),
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            echo $this->Form->input(
                                'name',
                                [
                                    'label' => ['text' => __('Host Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => $host['Host']['name'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );

                            $value = $host['Host']['description'];
                            if ($value === null) {
                                $value = $host['Hosttemplate']['description'];
                            }
                            echo $this->Form->input('description',
                                [
                                    'label' => ['text' => __('Description'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => $value,
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            echo $this->Form->input('address',
                                [
                                    'label' => ['text' => __('Address'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => $host['Host']['address'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            echo $this->Form->input(
                                'Host.Hostgroup',
                                [
                                    'options' => $_hostgroups,
                                    'multiple' => true,
                                    'class' => 'chosen',
                                    'style' => 'width:100%;',
                                    'label' => ['text' => __('Hostgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'selected' => $this->Html->getParameter('Host.Hostgroup', $host['Hostgroup']),
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );

                            echo $this->Form->input('Host.Parenthost', [
                                    'options'          => $_parenthosts,
                                    'data-placeholder' => __('Please, start typing...'),
                                    'class'            => 'chosen,',
                                    'multiple'         => true,
                                    'style'            => 'width:100%',
                                    'label'            => ['text' => __('Parent hosts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'required'         => true,
                                    'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                    'selected' => $this->Html->getParameter('Host.Parenthost', $host['Parenthost']),
                                    'div'              => [
                                        'class' => 'form-group',
                                    ],
                                ]
                            );


                            echo $this->Form->input('notes',
                                [
                                    'label' => ['text' => __('Notes'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => $host['Host']['notes'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            echo $this->Form->input('host_url',
                                [
                                    'label' => ['text' => __('Host URL'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => $host['Host']['host_url'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    'help' => __('The macros $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'),
                                ]
                            );
                            ?>
                            <div class="form-group <?php echo (isset($validationErrors['priority'])) ? 'has-error' : '' ?>">
                                <label class="col col-md-1 control-label text-left"><?php echo __('Priority'); ?></label>
                                <div class="col col-xs-10 col-md-10 col-lg-10 smart-form">
                                    <div class="rating pull-left">
                                        <?php //The smallest priority is 1 at the moment
                                        $priority = $this->CustomValidationErrors->refill('priority', ($host['Host']['priority'] !== null) ? $host['Host']['priority'] : $host['Hosttemplate']['priority']);
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
                            <?php echo $this->Form->input(
                                'tags', [
                                    'label' => ['text' => __('Tags'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'class' => 'form-control tagsinput',
                                    'data-role' => 'tagsinput',
                                    'value' => $host['Host']['tags'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            echo $this->AdditionalLinks->renderElements($additionalElementsForm);
                            ?>

                            <div class="padding-top-10"></div>
                            <!-- notification settings -->
                            <span class="note"><?php echo __('Notification settings'); ?>:</span>
                            <?php echo $this->Form->input(
                                'Host.notify_period_id',
                                [
                                    'options' => $_timeperiods,
                                    'label' => ['text' => __('Notification Period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'class' => 'chosen col col-xs-12',
                                    'selected' => $this->Html->getParameter('Host.notify_period_id', $host['Host']['notify_period_id'] === null ? $host['Hosttemplate']['notify_period_id'] : $host['Host']['notify_period_id']),
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            ?>
                            <br/>
                            <div class="form-group form-group-slider required <?php echo $this->CustomValidationErrors->errorClass('notification_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostNotificationinterval"><?php echo __('Notification interval'); ?></label>
                                <div class="col col-md-7 hidden-mobile">
                                    <input
                                            type="text"
                                            id="HostNotificationinterval"
                                            maxlength="255"
                                            value=""
                                            class="form-control slider slider-success"
                                            name="data[Host][notification_interval]"
                                            data-slider-min="0"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('notification_interval', ($host['Host']['notification_interval'] === null) ? $host['Hosttemplate']['notification_interval'] : $host['Host']['notification_interval']); ?>"
                                            data-slider-selection="before"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#HostNotificationinterval_human">
                                </div>
                                <div class="col col-xs-8 col-md-3">
                                    <input type="number" id="_HostNotificationinterval"
                                           human="#HostNotificationinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('notification_interval', ($host['Host']['notification_interval'] === null) ? $host['Hosttemplate']['notification_interval'] : $host['Host']['notification_interval']); ?>"
                                           slider-for="HostNotificationinterval" class="form-control slider-input"
                                           name="data[Host][notification_interval]">
                                    <span class="note"
                                          id="HostNotificationinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('notification_interval', ($host['Host']['notification_interval'] === null) ? $host['Hosttemplate']['notification_interval'] : $host['Host']['notification_interval'])); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('notification_interval'); ?>
                                </div>
                            </div>
                            <div class="padding-left-20 <?php echo $this->CustomValidationErrors->errorClass('notify_on_recovery'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('notify_on_recovery', ['style' => 'margin-left: 15px;']); ?>
                                <?php
                                //NULL options are removed with Hash::filter
                                $host_notify_settings = Hash::filter(Set::classicExtract($host, 'Host.{(notify_on_).*}'));
                                $hosttemplate_notify_settings = Set::classicExtract($host, 'Hosttemplate.{(notify_on_).*}');
                                $saved_notify_settings = (empty($host_notify_settings)) ? $hosttemplate_notify_settings : $host_notify_settings;
                                ?>
                                <label class="padding-10"><?php echo __('Notification options: '); ?></label>
                                <?php
                                foreach ($notification_settings as $notification_setting => $icon):?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($notification_setting, [
                                            'caption' => ucfirst(preg_replace('/notify_on_/', '', $notification_setting)),
                                            'captionGridClass' => 'col col-xs-2 col-md-2 col-lg-2',
                                            'icon' => '<i class="fa ' . $icon . '"></i> ',
                                            'class' => 'onoffswitch-checkbox notification_control',
                                            'checked' => (boolean)$saved_notify_settings[$notification_setting],
                                            'wrapGridClass' => 'col col-xs-1',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <br/>

                            <div class="form-group padding-left-20">
                                <?php echo $this->Form->fancyCheckbox('active_checks_enabled', [
                                    'caption' => __('Enable active checks'),
                                    'wrapGridClass' => 'col col-xs-1',
                                    'captionGridClass' => 'col col-xs-2 col-md-2 col-lg-2 no-padding',
                                    'captionClass' => 'control-label text-left no-padding',
                                    'checked' => (boolean)$host['Host']['active_checks_enabled'],
                                    'icon' => '<i class="fa fa-sign-in"></i> ',
                                ]); ?>
                            </div>

                            <div class="padding-20"><!-- spacer --><br/><br/></div>

                            <?php $contactOptions = []; ?>
                            <?php $contactGroupOptions = []; ?>
                            <?php
                            if ($ContactsInherited['inherit'] === true):
                                $source = '???';
                                if ($ContactsInherited['source'] == 'Hosttemplate'):
                                    $source = __('Host') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosttemplates/edit/' . $host['Host']['hosttemplate_id'] . '">' . __('Hosttemplate') . '</a></strong>';
                                endif; ?>
                                <span class="text-info"><i
                                            class="fa fa-info-circle"></i> <?php echo __('Contacts and Contactgroups are inherited in the following order:'); ?></span>
                                <span class="text-info"> <?php echo $source; ?></span>
                                <span class="text-info"><?php echo __('Untick to disable'); ?>
                                    <input type="checkbox" id="inheritContacts" value="1" checked="checked"/></span>
                                <?php
                                $contactOptions = [
                                    'selected' => array_keys($ContactsInherited['Contact']),
                                    'readonly' => true,
                                ];

                                $contactGroupOptions = [
                                    'selected' => array_keys($ContactsInherited['Contactgroup']),
                                    'readonly' => true,
                                ];
                            endif;
                            ?>

                            <div id="hostContactSelects">
                                <?php
                                echo $this->Form->input(
                                    'Host.Contact',
                                    Hash::merge($contactOptions,
                                        [
                                            'options' => $_contacts,
                                            'multiple' => true,
                                            'class' => 'chosen',
                                            'style' => 'width:100%;',
                                            'label' => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                            'selected' => $this->Html->getParameter('Host.Contact', $host['Contact']),
                                            'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                        ]
                                    ));
                                ?>
                                <?php
                                echo $this->Form->input(
                                    'Host.Contactgroup',
                                    Hash::merge($contactGroupOptions,
                                        [
                                            'options' => $_contactgroups,
                                            'multiple' => true,
                                            'class' => 'chosen',
                                            'style' => 'width:100%;',
                                            'label' => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                            'selected' => $this->Html->getParameter('Host.Contactgroup', $host['Contactgroup']),
                                            'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                        ]
                                    ));
                                ?>
                            </div>
                        </div>

                        <div id="tab2" class="tab-pane fade">
                            <!-- check settings -->
                            <span class="note pull-left"><?php echo __('Check settings'); ?>:</span>
                            <br class="clearfix"/>
                            <?php echo $this->Form->input(
                                'Host.command_id', [
                                'options' => $this->Html->chosenPlaceholder($commands),
                                'data-placeholder' => __('Please select...'),
                                'label' => ['text' => __('Check command'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                'class' => 'chosen col col-xs-12',
                                'selected' => ($host['CheckCommand']['id'] === null) ? $host['Hosttemplate']['command_id'] : $host['Host']['command_id'],
                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                            ]);
                            ?>
                            <!-- Command arguments -->
                            <div id="CheckCommandArgs">
                                <?php
                                if (!empty($commandarguments)):
                                    $hostcommandargumentvalues = Hash::combine($host['Hostcommandargumentvalue'], '{n}.commandargument_id', '{n}');
                                    foreach ($commandarguments as $key => $commandargument):
                                        echo $this->Form->input(
                                            'Hostcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.value',
                                            [
                                                'label' => [
                                                    'class' => 'col col-xs-1 col-md-1 col-lg-1 control-label text-primary',
                                                    'text' => $commandargument['Commandargument']['human_name'],
                                                ],
                                                'value' => (array_key_exists($commandargument['Commandargument']['id'], $hostcommandargumentvalues)) ? $hostcommandargumentvalues[$commandargument['Commandargument']['id']]['value'] : '',
                                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                            ]
                                        );
                                        echo $this->Form->input(
                                            'Hostcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.commandargument_id',
                                            [
                                                'type' => 'hidden',
                                                'value' => $commandargument['Commandargument']['id'],
                                                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                            ]
                                        );
                                        if ((array_key_exists($commandargument['Commandargument']['id'], $hostcommandargumentvalues) && isset($hostcommandargumentvalues[$commandargument['Commandargument']['id']]['id']))):
                                            echo $this->Form->input(
                                                'Hostcommandargumentvalue.' . $commandargument['Commandargument']['id'] . '.id',
                                                [
                                                    'type' => 'hidden',
                                                    'value' => $hostcommandargumentvalues[$commandargument['Commandargument']['id']]['id'],
                                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                                ]
                                            );
                                        endif;
                                    endforeach;
                                else: ?>
                                    <div class="form-group">
                                        <label class="col col-md-2 control-label hidden-mobile hidden-tablet">
                                            <!-- spacer for nice layout --></label>
                                        <label class="col col-md-8 col-xs-12 text-primary"><i
                                                    class="fa fa-info-circle"></i> <?php echo __('no parameters for this command defined'); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                            echo $this->Form->input(
                                'Host.check_period_id',
                                [
                                    'options' => $_timeperiods,
                                    'label' => ['text' => __('Check period'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'class' => 'chosen col col-xs-12',
                                    'selected' => $this->Html->getParameter('Host.check_period_id', ($host['Host']['check_period_id'] === null) ? $host['Hosttemplate']['check_period_id'] : $host['Host']['check_period_id']),
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                ]
                            );
                            ?>
                            <?php echo $this->Form->input(
                                'Host.max_check_attempts',
                                [
                                    'label' => ['text' => __('Max. number of check attempts'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                    'value' => ($host['Host']['max_check_attempts'] === null) ? $host['Hosttemplate']['max_check_attempts'] : $host['Host']['max_check_attempts'],
                                    'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                                    'min' => 0,
                                ]
                            ); ?>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('check_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostCheckinterval"><?php echo __('Check interval'); ?></label>
                                <div class="col col-xs-7">
                                    <input type="text" id="HostCheckinterval" maxlength="255"
                                           value="<?php echo $this->CustomValidationErrors->refill('check_interval', ($host['Host']['check_interval'] === null) ? $host['Hosttemplate']['check_interval'] : $host['Host']['check_interval']); ?>"
                                           class="form-control slider slider-success" name="data[Host][check_interval]"
                                           data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                           data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                           data-slider-value="<?php echo $this->CustomValidationErrors->refill('check_interval', ($host['Host']['check_interval'] === null) ? $host['Hosttemplate']['check_interval'] : $host['Host']['check_interval']); ?>"
                                           data-slider-selection="before"
                                           data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                           human="#HostCheckinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostCheckinterval" human="#HostCheckinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('check_interval', ($host['Host']['check_interval'] === null) ? $host['Hosttemplate']['check_interval'] : $host['Host']['check_interval']); ?>"
                                           slider-for="HostCheckinterval" class="form-control slider-input"
                                           name="data[Host][check_interval]">
                                    <span class="note"
                                          id="HostCheckinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('check_interval', ($host['Host']['check_interval'] === null) ? $host['Hosttemplate']['check_interval'] : $host['Host']['check_interval'])); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('check_interval'); ?>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('retry_interval'); ?>">
                                <label class="col col-md-1 control-label"
                                       for="HostCheckinterval"><?php echo __('Retry interval'); ?></label>
                                <div class="col col-xs-7">
                                    <input
                                            type="text"
                                            id="HostRetryinterval"
                                            maxlength="255"
                                            value="<?php echo $this->CustomValidationErrors->refill('retry_interval', ($host['Host']['retry_interval'] === null) ? $host['Hosttemplate']['retry_interval'] : $host['Host']['retry_interval']); ?>"
                                            class="form-control slider slider-primary"
                                            name="data[Host][retry_interval]"
                                            data-slider-min="<?php echo Configure::read('NagiosModule.SLIDER_MIN'); ?>"
                                            data-slider-max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                            data-slider-value="<?php echo $this->CustomValidationErrors->refill('retry_interval', ($host['Host']['check_interval'] === null) ? $host['Hosttemplate']['retry_interval'] : $host['Hosttemplate']['retry_interval']); ?>"
                                            data-slider-selection="before"
                                            data-slider-handle="round"
                                            data-slider-step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                            human="#HostRetryinterval_human">
                                </div>
                                <div class="col col-xs-3">
                                    <input type="number" id="_HostRetryinterval" human="#HostRetryinterval_human"
                                           value="<?php echo $this->CustomValidationErrors->refill('retry_interval', ($host['Host']['retry_interval'] === null) ? $host['Hosttemplate']['retry_interval'] : $host['Host']['retry_interval']); ?>"
                                           slider-for="HostRetryinterval" class="form-control slider-input"
                                           name="data[Host][retry_interval]">
                                    <span class="note"
                                          id="HostRetryinterval_human"><?php echo $this->Utils->secondsInWords($this->CustomValidationErrors->refill('retry_interval', ($host['Host']['retry_interval'] === null) ? $host['Hosttemplate']['retry_interval'] : $host['Host']['retry_interval'])); ?></span>
                                    <?php echo $this->CustomValidationErrors->errorHTML('retry_interval'); ?>
                                </div>
                            </div>

                            <div class="padding-top-10"></div>
                            <!-- expert settings -->
                            <span class="note pull-left"><?php echo __('Expert settings'); ?>:</span>
                            <br class="clearfix"/>

                            <div class="form-group">
                                <?php
                                $flap_detection_enabled = ($host['Host']['flap_detection_enabled'] === null) ? $host['Hosttemplate']['flap_detection_enabled'] : $host['Host']['flap_detection_enabled'];
                                echo $this->Form->fancyCheckbox('flap_detection_enabled', [
                                    'caption' => __('Flap detection'),
                                    'wrapGridClass' => 'col col-xs-2',
                                    'captionGridClass' => 'col col-xs-2 text-left',
                                    'captionClass' => 'control-label',
                                    'checked' => $this->CustomValidationErrors->refill('flap_detection_enabled', ($host['Host']['flap_detection_enabled'] !== null) ? $host['Host']['flap_detection_enabled'] : $host['Hosttemplate']['flap_detection_enabled']),
                                ]); ?>
                            </div>

                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_host_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                                <?php endif; ?>
                            </legend>
                            <br/>

                            <div class="<?php echo $this->CustomValidationErrors->errorClass('flap_detection_on_up'); ?>">
                                <?php echo $this->CustomValidationErrors->errorHTML('flap_detection_on_up', ['style' => 'margin-left: 15px;']); ?>
                                <?php
                                //NULL options are removed with Hash::filter
                                $host_flapping_settings = Hash::filter(Set::classicExtract($host, 'Host.{(flap_detection_on_).*}'));
                                $saved_flapping_settings = (empty($host_flapping_settings)) ? Set::classicExtract($host, 'Hosttemplate.{(flap_detection_on_).*}') : $host_flapping_settings;
                                foreach ($flapDetection_settings as $flapDetection_setting => $icon):?>
                                    <div class="form-group no-padding">
                                        <?php echo $this->Form->fancyCheckbox($flapDetection_setting, [
                                            'caption' => ucfirst(preg_replace('/flap_detection_on_/', '', $flapDetection_setting)),
                                            'icon' => '<i class="fa ' . $icon . '"></i> ',
                                            'class' => 'onoffswitch-checkbox flapdetection_control',
                                            'checked' => (boolean)$saved_flapping_settings[$flapDetection_setting],
                                            'wrapGridClass' => 'col col-xs-2',
                                            'captionGridClass' => 'col col-xs-2',
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <br/>
                            <legend class="font-sm">
                                <!-- this legend creates the nice border  -->
                                <?php if (isset($validation_host_notification)): ?>
                                    <span class="text-danger"><?php echo $validation_host_notification; ?></span>
                                <?php endif; ?>
                            </legend>

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
                                <?php endif; ?>
                                <?php
                                $customVariableMerger = new \itnovum\openITCOCKPIT\Core\CustomVariableMerger(
                                    $host['Customvariable'],
                                    $host['Hosttemplate']['Customvariable']
                                );
                                $mergedCustomVariables = $customVariableMerger->getCustomVariablesMergedAsRepository();
                                ?>
                                <?php $this->CustomVariables->setup('HOST', OBJECT_HOST, $mergedCustomVariables->getAllCustomVariablesAsArray()); ?>
                                <?php echo $this->CustomVariables->prepare(); ?>
                                <br/>
                            </div>

                            <?php if ($mergedCustomVariables->getSize() > 0): ?>
                                <div class="col-xs-12 text-info">
                                    <i class="fa fa-info-circle"></i> <?php echo __('Macros with green color are inherited from template. You can override the value but not delete the macro itself'); ?>
                                </div>
                            <?php endif; ?>

                        </div>
                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host'); ?>

                    </div> <!-- close tab-content -->
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
                        <?php echo __('Do you really want delete this host?'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="errorOnDelete"></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="successDelete" style="display:none;">
                        <div class="alert auto-hide alert-success">
                            <?php echo __('Host deleted successfully'); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="yesDeleteHost"
                        data-host-id="<?php echo h($host['Host']['id']); ?>">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>
