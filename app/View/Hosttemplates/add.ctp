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
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create new host template'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosttemplates')): ?>
                <a class="btn btn-default" ui-sref="HosttemplatesIndex">
                    <i class="fa fa-arrow-left"></i>
                    <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">

            <form ng-submit="submit();" class="form-horizontal">


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
                                                        multiple
                                                        ng-options="container.key as container.value for container in containers"
                                                        ng-model="post.Hosttemplate.container_id">
                                                </select>
                                                <div ng-repeat="error in errors.container_id">
                                                    <div class="help-block text-danger">{{ error }}</div>
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
                                            </div>
                                        </div>

                                        <div class="form-group" ng-class="{'has-error': errors.name}">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <?php echo __('Priority'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10">

                                                <priority-directive priority="post.Hosttemplate.priority"
                                                                    callback="2"></priority-directive>

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
                                         ng-class="{'has-error': errors.check_period}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check period'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="CheckPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.check_period.id">
                                            </select>
                                            <div ng-repeat="error in errors.check_period">
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
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.check_command}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check command'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="HostCheckCommandSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.check_command.id">
                                            </select>
                                            <div ng-repeat="error in errors.check_command">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required" ng-class="{'has-error': errors.check_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Check interval'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-7">
                                            <input type="range"
                                                   min="0"
                                                   max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                                   step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                                   class="form-control"
                                                   ng-model="post.Hosttemplate.check_interval">
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <input
                                                    class="form-control"
                                                    type="text"
                                                    ng-model="post.Hosttemplate.check_interval">
                                            <div class="help-block margin-bottom-0">
                                                119 minutes and 0 seconds
                                            </div>
                                        </div>
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
                                        <div class="col-xs-12 col-lg-7">
                                            <input type="range"
                                                   min="0"
                                                   max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                                   step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                                   class="form-control"
                                                   ng-model="post.Hosttemplate.retry_interval">
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <input
                                                    class="form-control"
                                                    type="text"
                                                    ng-model="post.Hosttemplate.retry_interval">
                                            <div class="help-block margin-bottom-0">
                                                119 minutes and 0 seconds
                                            </div>
                                        </div>
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
                                        <div class="col-xs-12 col-lg-10">
                                            <input
                                                    class="form-control"
                                                    type="number"
                                                    ng-model="post.Hosttemplate.max_check_attempts">
                                            <div class="help-block">
                                                <?php echo __('Number of attempts before the host will switch into a hard state.'); ?>
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
                                         ng-class="{'has-error': errors.notify_period}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notification period'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10">
                                            <select
                                                    id="NotifyPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.notify_period.id">
                                            </select>
                                            <div ng-repeat="error in errors.notify_period">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required"
                                         ng-class="{'has-error': errors.notification_interval}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <?php echo __('Notification interval'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-7">
                                            <input type="range"
                                                   min="0"
                                                   max="<?php echo Configure::read('NagiosModule.SLIDER_MAX'); ?>"
                                                   step="<?php echo Configure::read('NagiosModule.SLIDER_STEPSIZE'); ?>"
                                                   class="form-control"
                                                   ng-model="post.Hosttemplate.notification_interval">
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <input
                                                    class="form-control"
                                                    type="text"
                                                    ng-model="post.Hosttemplate.notification_interval">
                                            <div class="help-block margin-bottom-0">
                                                119 minutes and 0 seconds
                                            </div>
                                        </div>
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
                                                    chosen="timeperiods"
                                                    multiple
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.contacts._id">
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
                                                    id="NotifyPeriodSelect"
                                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                                    class="form-control"
                                                    chosen="timeperiods"
                                                    multiple
                                                    ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                                    ng-model="post.Hosttemplate.contactgroups._id">
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
                                                           ng-model="post.Hosttemplate.<?php echo $hostOption['field']; ?>">
                                                    <i class="checkbox-<?php echo $hostOption['class']; ?>"></i>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
                                        misc contacnt
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
                                    <div class="widget-body">
                                        macros und son zeugs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">
                            <a ui-sref="HosttemplatesIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
