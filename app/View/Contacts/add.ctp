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
            <?php echo __('Contacts'); ?>
            <span>>
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo __('Create new contact'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'contacts')): ?>
                <a back-button fallback-state='ContactsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Contact'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.containers}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContactContainers"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    multiple
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Contact.containers._ids">
                            </select>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Contact.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.description}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Contact.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Email'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="email"
                                    placeholder="user@example.org"
                                    ng-model="post.Contact.email">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.phone}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Phone'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="0049123456789"
                                    ng-model="post.Contact.phone">
                            <div ng-repeat="error in errors.phone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.user_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('User'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContactUser"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="users"
                                    ng-options="user.key as user.value for user in users"
                                    ng-model="post.Contact.user_id">
                            </select>
                            <div ng-repeat="error in errors.user_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('For browser notifications, a user needs to be assigned to the contact. User Id will be automatically available as $_CONTACTOITCUSERID$ contact macro.') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <br/>
                <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-sortable">
                            <header role="heading">
                                <span class="widget-icon">
                                    <i class="fa fa-desktop"></i>
                                </span>
                                <h2><?php echo __('Host notification'); ?></h2>
                            </header>
                            <div role="content" style="min-height:513px;">
                                <div class="widget-body">
                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.host_timeperiod_id}">
                                        <label class="col col-md-4 control-label">
                                            <?php echo __('Host time period'); ?>
                                        </label>
                                        <div class="col col-xs-8">
                                            <select
                                                    id="HostTimeperiodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Contact.host_timeperiod_id">
                                            </select>
                                            <div ng-repeat="error in errors.host_timeperiod_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.host_commands}">
                                        <label class="col col-md-4 control-label">
                                            <?php echo __('Host commands'); ?>
                                        </label>
                                        <div class="col col-xs-8">
                                            <select
                                                    id="HostCommands"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="commands"
                                                    multiple
                                                    ng-options="command.key as command.value for command in commands"
                                                    ng-model="post.Contact.host_commands._ids">
                                            </select>
                                            <div ng-repeat="error in errors.host_commands">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.host_notifications_enabled}">
                                        <label class="col col-md-4 control-label" for="hostNotificationsEnabled">
                                            <?php echo __('Host notifications enabled'); ?>
                                        </label>


                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       id="hostNotificationsEnabled"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Contact.host_notifications_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.host_push_notifications_enabled}">
                                        <label class="col col-md-4 control-label" for="hostPushNotificationEnabled">
                                            <?php echo __('Push notifications to browser'); ?>
                                            <i class="fa fa-info-circle text-info"
                                               data-template="<div class='tooltip' role='tooltip'><div class='tooltip-arrow tooltip-arrow-image'></div><div class='tooltip-inner tooltip-inner-image'></div></div>"
                                               rel="tooltip"
                                               data-placement="right"
                                               data-original-title="<img src='/img/browser_notification_bg.png'/>"
                                               data-html="true"></i>
                                        </label>


                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="hostPushNotificationEnabled"
                                                       ng-model="post.Contact.host_push_notifications_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>


                                    <br class="clearfix"/>

                                    <?php
                                    $hostOptions = [
                                        [
                                            'field' => 'notify_host_recovery',
                                            'class' => 'success',
                                            'text'  => __('Recovery')
                                        ],
                                        [
                                            'field' => 'notify_host_down',
                                            'class' => 'danger',
                                            'text'  => __('Down')
                                        ],
                                        [
                                            'field' => 'notify_host_unreachable',
                                            'class' => 'default',
                                            'text'  => __('Unreachable')
                                        ],
                                        [
                                            'field' => 'notify_host_flapping',
                                            'class' => 'primary',
                                            'text'  => __('Flapping')
                                        ],
                                        [
                                            'field' => 'notify_host_downtime',
                                            'class' => 'primary',
                                            'text'  => __('Downtime')
                                        ],
                                    ];
                                    ?>

                                    <fieldset>
                                        <legend class="font-sm"
                                                ng-class="{'has-error-no-form': errors.notify_host_recovery}">
                                            <div class="required">
                                                <label>
                                                    <?php echo __('Host notification options'); ?>
                                                </label>

                                                <div ng-repeat="error in errors.notify_host_recovery">
                                                    <div class="text-danger">{{ error }}</div>
                                                </div>

                                            </div>
                                        </legend>
                                        <?php foreach ($hostOptions as $hostOption): ?>
                                            <div class="form-group margin-bottom-0"
                                                 ng-class="{'has-error': errors.<?php echo $hostOption['field']; ?>}">

                                                <label for="<?php echo $hostOption['field']; ?>"
                                                       class="col col-md-4 control-label padding-top-0">
                                                    <span class="label label-<?php echo $hostOption['class']; ?> notify-label"><?php echo $hostOption['text']; ?></span>
                                                </label>

                                                <div class="col-xs-8 smart-form">
                                                    <label class="checkbox small-checkbox-label no-required">
                                                        <input type="checkbox" name="checkbox"
                                                               ng-true-value="1"
                                                               ng-false-value="0"
                                                               id="<?php echo $hostOption['field']; ?>"
                                                               ng-model="post.Contact.<?php echo $hostOption['field']; ?>">
                                                        <i class="checkbox-<?php echo $hostOption['class']; ?>"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-sortable">
                            <header role="heading">
                                <span class="widget-icon">
                                    <i class="fa fa-cog"></i>
                                </span>
                                <h2><?php echo __('Service notification'); ?></h2>
                            </header>
                            <div role="content">
                                <div class="widget-body">
                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.service_timeperiod_id}">
                                        <label class="col col-md-4 control-label">
                                            <?php echo __('Service time period'); ?>
                                        </label>
                                        <div class="col col-xs-8">
                                            <select
                                                    id="ServiceTimeperiodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Contact.service_timeperiod_id">
                                            </select>
                                            <div ng-repeat="error in errors.service_timeperiod_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.service_commands}">
                                        <label class="col col-md-4 control-label">
                                            <?php echo __('Service commands'); ?>
                                        </label>
                                        <div class="col col-xs-8">
                                            <select
                                                    id="ServiceCommands"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="commands"
                                                    multiple
                                                    ng-options="command.key as command.value for command in commands"
                                                    ng-model="post.Contact.service_commands._ids">
                                            </select>
                                            <div ng-repeat="error in errors.service_commands">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.service_notifications_enabled}">
                                        <label class="col col-md-4 control-label" for="serviceNotificationsEnabled">
                                            <?php echo __('Service notifications enabled'); ?>
                                        </label>


                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="serviceNotificationsEnabled"
                                                       ng-model="post.Contact.service_notifications_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.service_push_notifications_enabled}">
                                        <label class="col col-md-4 control-label" for="servicePushNotificationEnabled">
                                            <?php echo __('Push notifications to browser'); ?>
                                            <i class="fa fa-info-circle text-info"
                                               data-template="<div class='tooltip' role='tooltip'><div class='tooltip-arrow tooltip-arrow-image'></div><div class='tooltip-inner tooltip-inner-image'></div></div>"
                                               rel="tooltip"
                                               data-placement="right"
                                               data-original-title="<img src='/img/browser_service_notification_bg.png'/>"
                                               data-html="true"></i>
                                        </label>


                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="servicePushNotificationEnabled"
                                                       ng-model="post.Contact.service_push_notifications_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>


                                    <br class="clearfix"/>

                                    <?php
                                    $serviceOptions = [
                                        [
                                            'field' => 'notify_service_recovery',
                                            'class' => 'success',
                                            'text'  => __('Recovery')
                                        ],
                                        [
                                            'field' => 'notify_service_warning',
                                            'class' => 'warning',
                                            'text'  => __('Warning')
                                        ],
                                        [
                                            'field' => 'notify_service_critical',
                                            'class' => 'danger',
                                            'text'  => __('Critical')
                                        ],
                                        [
                                            'field' => 'notify_service_unknown',
                                            'class' => 'default',
                                            'text'  => __('Unknown')
                                        ],
                                        [
                                            'field' => 'notify_service_flapping',
                                            'class' => 'primary',
                                            'text'  => __('Flapping')
                                        ],
                                        [
                                            'field' => 'notify_service_downtime',
                                            'class' => 'primary',
                                            'text'  => __('Downtime')
                                        ],
                                    ];
                                    ?>

                                    <fieldset>
                                        <legend class="font-sm"
                                                ng-class="{'has-error-no-form': errors.notify_service_recovery}">
                                            <div class="required">
                                                <label>
                                                    <?php echo __('Service notification options'); ?>
                                                </label>

                                                <div ng-repeat="error in errors.notify_service_recovery">
                                                    <div class="text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </legend>
                                        <?php foreach ($serviceOptions as $serviceOption): ?>
                                            <div class="form-group margin-bottom-0"
                                                 ng-class="{'has-error': errors.<?php echo $serviceOption['field']; ?>}">

                                                <label for="<?php echo $serviceOption['field']; ?>"
                                                       class="col col-md-4 control-label padding-top-0">
                                                    <span class="label label-<?php echo $serviceOption['class']; ?> notify-label"><?php echo $serviceOption['text']; ?></span>
                                                </label>

                                                <div class="col-xs-8 smart-form">
                                                    <label class="checkbox small-checkbox-label no-required">
                                                        <input type="checkbox" name="checkbox"
                                                               ng-true-value="1"
                                                               ng-false-value="0"
                                                               id="<?php echo $serviceOption['field']; ?>"
                                                               ng-model="post.Contact.<?php echo $serviceOption['field']; ?>">
                                                        <i class="checkbox-<?php echo $serviceOption['class']; ?>"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <?php if ($this->Acl->hasPermission('wiki', 'documentations')): ?>
                    <div class="row padding-bottom-10">
                        <div class="col-xs-12 text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Read more about browser push notification in the'); ?>
                            <a ui-sref="DocumentationsWiki({documentation:'additional_help:browser_push_notifications'})">
                                <?php echo __('documentation'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <fieldset class="form-inline padding-10 ng-binding">
                        <legend class="font-sm" ng-class="{'has-error-no-form': errors.customvariables_unique}">
                            <div>
                                <label>
                                    <?php echo __('Define contact macros'); ?>
                                </label>

                                <div ng-repeat="error in errors.customvariables_unique">
                                    <div class="text-danger">{{ error }}</div>
                                </div>

                            </div>
                        </legend>
                        <div class="row" ng-repeat="customvariable in post.Contact.customvariables">
                            <macros-directive macro="customvariable"
                                              macro-name="'<?php echo __('CONTACT'); ?>'"
                                              index="$index"
                                              callback="deleteMacroCallback"
                                              errors="getMacroErrors($index)"
                            ></macros-directive>
                        </div>

                        <div class="row">
                            <div class="col-md-9 col-md-offset-2 padding-top-10 text-right">
                                <button type="button" class="btn btn-success btn-sm" ng-click="addMacro()">
                                    <i class="fa fa-plus"></i>
                                    <?php echo __('Add new macro'); ?>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <label>
                                <input type="checkbox" ng-model="data.createAnother">
                                <?php echo _('Create another'); ?>
                            </label>

                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Create contact'); ?>">

                            <a back-button fallback-state='ContactsIndex' class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
