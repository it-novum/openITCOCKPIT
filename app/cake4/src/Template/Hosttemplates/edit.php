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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Host templates'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>



<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2>
            <?php echo __('Edit host template:'); ?>
            {{post.Hosttemplate.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosttemplates')): ?>
                <a back-button fallback-state='HosttemplatesIndex' class="btn btn-default btn-xs">
                    <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            UUID: {{post.Hosttemplate.uuid}}
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Host template'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-magic"></i>
                                </span>
                                <h2><?php echo __('Basic configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">

                                    <div class="row">
                                        <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Container'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <select
                                                        id="ContactContainers"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="containers"
                                                        ng-options="container.key as container.value for container in containers"
                                                        ng-model="post.Hosttemplate.container_id">
                                                </select>
                                                <div ng-repeat="error in errors.container_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                                <div class="help-block" ng-show="areContainersRestricted">
                                                    <?php echo __('There are Hosts using this Host Template. Therefore the number of Containers is decreased.'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Template name'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Hosttemplate.name">
                                                <div ng-repeat="error in errors.name">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{'has-error': errors.description}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Description'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Hosttemplate.description">
                                                <div ng-repeat="error in errors.description">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group"
                                             ng-class="{'has-error': errors.hostgroups}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Host groups'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <select
                                                        id="HostgroupsSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="timeperiods"
                                                        multiple
                                                        ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                                        ng-model="post.Hosttemplate.hostgroups._ids">
                                                </select>
                                                <div ng-repeat="error in errors.hostgroups">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{'has-error': errors.tags}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Tags'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        class="form-control tagsinput"
                                                        type="text"
                                                        ng-model="post.Hosttemplate.tags">
                                                <div ng-repeat="error in errors.tags">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                                <div class="help-block">
                                                    <?php echo __('Press return to separate tags'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{'has-error': errors.priority}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Priority'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">

                                                <priority-directive priority="post.Hosttemplate.priority"
                                                                    callback="setPriority"
                                                                    ng-if="post.Hosttemplate.priority"></priority-directive>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-terminal"></i>
                                </span>
                                <h2><?php echo __('Check configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.check_period_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                                <a ui-sref="TimeperiodsEdit({id:post.Hosttemplate.check_period_id})">
                                                    <?php echo __('Check period'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Check period'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="CheckPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="checkperiods"
                                                    ng-options="checkperiod.key as checkperiod.value for checkperiod in checkperiods"
                                                    ng-model="post.Hosttemplate.check_period_id">
                                            </select>
                                            <div ng-repeat="error in errors.check_period_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.active_checks_enabled}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="activeChecksEnabled">
                                            <?php echo __('Enable active checks'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-10 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       id="activeChecksEnabled"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Hosttemplate.active_checks_enabled">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="help-block">
                                                <?php echo __('If disabled the check command won\'t be executed. This is useful if an external program sends state data to openITCOCKPIT.'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.command_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                <a ui-sref="CommandsEdit({id:post.Hosttemplate.command_id})">
                                                    <?php echo __('Check command'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Check command'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="HostCheckCommandSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="commands"
                                                    ng-options="command.key as command.value for command in commands"
                                                    ng-model="post.Hosttemplate.command_id">
                                            </select>
                                            <div class="help-block" ng-hide="post.Hosttemplate.active_checks_enabled">
                                                <?php echo __('Due to active checking is disabled, this command will only be used as freshness check command.'); ?>
                                            </div>
                                            <div ng-repeat="error in errors.command_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.hosttemplatecommandargumentvalues}"
                                         ng-repeat="hosttemplatecommandargumentvalue in post.Hosttemplate.hosttemplatecommandargumentvalues">
                                        <label class="col-xs-12 col-lg-offset-2 col-lg-2 control-label text-primary">
                                            {{hosttemplatecommandargumentvalue.commandargument.human_name}}
                                        </label>
                                        <div class="col-xs-12 col-lg-8">
                                            <input
                                                    class="form-control"
                                                    type="text"
                                                    ng-model="hosttemplatecommandargumentvalue.value">
                                            <div ng-repeat="error in errors.hosttemplatecommandargumentvalues">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                            <div class="help-block">
                                                {{hosttemplatecommandargumentvalue.commandargument.name}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-show="post.Hosttemplate.command_id > 0 && post.Hosttemplate.hosttemplatecommandargumentvalues.length == 0">
                                        <div class="col-xs-12 col-lg-offset-2 text-info">
                                            <i class="fa fa-info-circle"></i>
                                            <?php echo __('This command does not have any parameters.'); ?>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.check_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check interval'); ?>
                                        </label>
                                        <interval-input-directive
                                                interval="post.Hosttemplate.check_interval"></interval-input-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.check_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.retry_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Retry interval'); ?>
                                        </label>
                                        <interval-input-directive
                                                interval="post.Hosttemplate.retry_interval"></interval-input-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.retry_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.max_check_attempts}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Max. number of check attempts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-7">
                                            <div class="btn-group">
                                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                                    <button
                                                            type="button"
                                                            class="btn btn-default"
                                                            ng-click="post.Hosttemplate.max_check_attempts = <?php echo h($i) ?>"
                                                            ng-class="{'active': post.Hosttemplate.max_check_attempts == <?php echo h($i); ?>}">
                                                        <?php echo h($i); ?>
                                                    </button>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <input
                                                    class="form-control"
                                                    type="number"
                                                    min="0"
                                                    ng-model="post.Hosttemplate.max_check_attempts">
                                        </div>
                                        <div class="col-xs-12 col-lg-offset-2 col-lg-12">
                                            <div class="help-block">
                                                <?php echo __('Number of failed attempts before the host will switch into hard state.'); ?>
                                            </div>
                                            <div class="help-block">
                                                <?php echo __('Worst case time delay until notification command gets executed after state hits a non ok state: '); ?>
                                                <human-time-directive
                                                        seconds="(post.Hosttemplate.check_interval + (post.Hosttemplate.max_check_attempts -1) * post.Hosttemplate.retry_interval)"></human-time-directive>
                                            </div>
                                            <div ng-repeat="error in errors.max_check_attempts">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-envelope-open-o"></i>
                                </span>
                                <h2><?php echo __('Notification configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.notify_period_id}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                                <a ui-sref="TimeperiodsEdit({id:post.Hosttemplate.notify_period_id})">
                                                    <?php echo __('Notification period'); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo __('Notification period'); ?>
                                            <?php endif; ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="NotifyPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.notify_period_id">
                                            </select>
                                            <div ng-repeat="error in errors.notify_period_id">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.notification_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notification interval'); ?>
                                        </label>
                                        <interval-input-directive
                                                interval="post.Hosttemplate.notification_interval"></interval-input-directive>
                                        <div class="col-xs-12 col-lg-offset-2">
                                            <div ng-repeat="error in errors.notification_interval">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.contacts}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Contacts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="ContactsPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="contacts"
                                                    multiple
                                                    ng-options="contact.key as contact.value for contact in contacts"
                                                    ng-model="post.Hosttemplate.contacts._ids">
                                            </select>
                                            <div ng-repeat="error in errors.contacts">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.contactgroups}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Contact groups'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="ContactgroupsSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="contactgroups"
                                                    multiple
                                                    ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                                    ng-model="post.Hosttemplate.contactgroups._ids">
                                            </select>
                                            <div ng-repeat="error in errors.contactgroups">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php
                                    $hostOptions = [
                                        [
                                            'field' => 'notify_on_recovery',
                                            'class' => 'success',
                                            'text'  => __('Recovery')
                                        ],
                                        [
                                            'field' => 'notify_on_down',
                                            'class' => 'danger',
                                            'text'  => __('Down')
                                        ],
                                        [
                                            'field' => 'notify_on_unreachable',
                                            'class' => 'default',
                                            'text'  => __('Unreachable')
                                        ],
                                        [
                                            'field' => 'notify_on_flapping',
                                            'class' => 'primary',
                                            'text'  => __('Flapping')
                                        ],
                                        [
                                            'field' => 'notify_on_downtime',
                                            'class' => 'primary',
                                            'text'  => __('Downtime')
                                        ],
                                    ];
                                    ?>
                                    <fieldset>
                                        <legend class="font-sm"
                                                ng-class="{'has-error-no-form': errors.notify_on_recovery}">
                                            <div class="required">
                                                <label>
                                                    <?php echo __('Host notification options'); ?>
                                                </label>

                                                <div ng-repeat="error in errors.notify_on_recovery">
                                                    <div class="text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </legend>
                                        <ul class="config-flex-inner">
                                            <?php foreach ($hostOptions as $hostOption): ?>
                                                <li>
                                                    <div class="margin-bottom-0"
                                                         ng-class="{'has-error': errors.<?php echo $hostOption['field']; ?>}">

                                                        <label for="<?php echo $hostOption['field']; ?>"
                                                               class="col col-md-7 control-label padding-top-0">
                                                        <span class="label label-<?php echo $hostOption['class']; ?> notify-label-small">
                                                            <?php echo $hostOption['text']; ?>
                                                        </span>
                                                        </label>

                                                        <div class="col-md-2 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       ng-true-value="1"
                                                                       ng-false-value="0"
                                                                       id="<?php echo $hostOption['field']; ?>"
                                                                       ng-model="post.Hosttemplate.<?php echo $hostOption['field']; ?>">
                                                                <i class="checkbox-<?php echo $hostOption['class']; ?>"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                            <div class="jarviswidget">
                                <header>
                                <span class="widget-icon">
                                    <i class="fa fa-wrench"></i>
                                </span>
                                    <h2><?php echo __('Misc. configuration'); ?></h2>
                                </header>
                                <div>
                                    <div class="widget-body">

                                        <div class="form-group" ng-class="{'has-error': errors.host_url}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Host URL'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        class="form-control"
                                                        placeholder="https://issues.example.org?host=$HOSTNAME$"
                                                        type="text"
                                                        ng-model="post.Hosttemplate.host_url">
                                                <div ng-repeat="error in errors.host_url">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                                <div class="help-block">
                                                    <?php echo __('The macros $HOSTID$, $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{'has-error': errors.notes}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Notes'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-model="post.Hosttemplate.notes">
                                                <div ng-repeat="error in errors.notes">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $hostFlapOptions = [
                                            [
                                                'field' => 'flap_detection_on_up',
                                                'class' => 'success',
                                                'text'  => __('Recovery')
                                            ],
                                            [
                                                'field' => 'flap_detection_on_down',
                                                'class' => 'danger',
                                                'text'  => __('Down')
                                            ],
                                            [
                                                'field' => 'flap_detection_on_unreachable',
                                                'class' => 'default',
                                                'text'  => __('Unreachable')
                                            ]
                                        ];
                                        ?>

                                        <div class="form-group"
                                             ng-class="{'has-error': errors.flap_detection_enabled}">
                                            <label class="col-xs-12 col-lg-2 control-label" for="flapDetectionEnabled">
                                                <?php echo __('Flap detection enabled'); ?>
                                            </label>

                                            <div class="col-xs-12 col-lg-10 smart-form">
                                                <label class="checkbox small-checkbox-label no-required">
                                                    <input type="checkbox" name="checkbox"
                                                           id="flapDetectionEnabled"
                                                           ng-true-value="1"
                                                           ng-false-value="0"
                                                           ng-model="post.Hosttemplate.flap_detection_enabled">
                                                    <i class="checkbox-primary"></i>
                                                </label>
                                            </div>
                                        </div>

                                        <fieldset ng-show="post.Hosttemplate.flap_detection_enabled">
                                            <legend class="font-sm"
                                                    ng-class="{'has-error-no-form': errors.flap_detection_on_up}">
                                                <div class="required">
                                                    <label>
                                                        <?php echo __('Flap detection options'); ?>
                                                    </label>

                                                    <div ng-repeat="error in errors.flap_detection_on_up">
                                                        <div class="text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </legend>
                                            <ul class="config-flex-inner">
                                                <?php foreach ($hostFlapOptions as $hostFalpOption): ?>
                                                    <li>
                                                        <div class="margin-bottom-0"
                                                             ng-class="{'has-error': errors.<?php echo $hostFalpOption['field']; ?>}">

                                                            <label for="<?php echo $hostFalpOption['field']; ?>"
                                                                   class="col col-md-7 control-label padding-top-0">
                                                                <span class="label label-<?php echo $hostFalpOption['class']; ?> notify-label-small">
                                                                    <?php echo $hostFalpOption['text']; ?>
                                                                </span>
                                                            </label>

                                                            <div class="col-md-2 smart-form">
                                                                <label class="checkbox small-checkbox-label no-required">
                                                                    <input type="checkbox" name="checkbox"
                                                                           ng-true-value="1"
                                                                           ng-false-value="0"
                                                                           ng-disabled="!post.Hosttemplate.flap_detection_enabled"
                                                                           id="<?php echo $hostFalpOption['field']; ?>"
                                                                           ng-model="post.Hosttemplate.<?php echo $hostFalpOption['field']; ?>">
                                                                    <i class="checkbox-<?php echo $hostFalpOption['class']; ?>"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                            <div class="jarviswidget">
                                <header>
                                <span class="widget-icon">
                                    <i class="fa fa-usd"></i>
                                </span>
                                    <h2><?php echo __('Host macro configuration'); ?></h2>
                                </header>
                                <div>
                                    <div class="widget-body"
                                         ng-class="{'has-error-no-form': errors.customvariables_unique}">

                                        <div class="row">
                                            <div ng-repeat="error in errors.customvariables_unique">
                                                <div class=" col-xs-12 text-danger">{{ error }}</div>
                                            </div>
                                        </div>

                                        <div class="row"
                                             ng-repeat="customvariable in post.Hosttemplate.customvariables">
                                            <macros-directive macro="customvariable"
                                                              macro-name="'<?php echo __('HOST'); ?>'"
                                                              index="$index"
                                                              callback="deleteMacroCallback"
                                                              errors="getMacroErrors($index)"
                                            ></macros-directive>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9 col-md-offset-2 padding-top-10 text-right">
                                                <button type="button" class="btn btn-success btn-sm"
                                                        ng-click="addMacro()">
                                                    <i class="fa fa-plus"></i>
                                                    <?php echo __('Add new macro'); ?>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Update host template'); ?>">
                            <a back-button fallback-state='HosttemplatesIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
