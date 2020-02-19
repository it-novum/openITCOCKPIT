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
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-3">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-secret fa-fw"></i>
            <?= __('openITCOCKPIT Agent') ?>
            <span>>
                <?= __('Add'); ?>
            </span>
        </h1>
    </div>
</div>

<div>
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
            <div>
                <div class="widget-body fuelux">
                    <form ng-submit="submit();" class="form-horizontal">

                        <div class="wizard">
                            <ul class="steps">
                                <li data-target="#step1" ng-class="(!pullMode && !pushMode) ? 'active' : ''">
                                    <span
                                        class="badge badge-info">1</span><?php echo __('Choose Host and agent mode'); ?>
                                    <span
                                        class="chevron"></span>
                                </li>
                                <li data-target="#step2"
                                    ng-class="((pullMode || pushMode) && !installed && !configured) ? 'active' : ''">
                                    <span class="badge">2</span><?php echo __('Basic agent configuration'); ?>
                                    <span
                                        class="chevron"></span>
                                </li>
                                <li data-target="#step3"
                                    ng-class="((pullMode || pushMode) && !installed && configured) ? 'active' : ''">
                                    <span class="badge">3</span><?php echo __('Installation guide'); ?><span
                                        class="chevron"></span>
                                </li>
                                <li data-target="#step4"
                                    ng-class="((pullMode || pushMode) && installed && configured && !servicesConfigured) ? 'active' : ''">
                                    <span class="badge">4</span><?php echo __('Create agent services'); ?><span
                                        class="chevron"></span>
                                </li>
                                <li data-target="#step4"
                                    ng-class="((pullMode || pushMode) && installed && configured && servicesConfigured) ? 'active' : ''">
                                    <span class="badge">5</span><?php echo __('Save changes'); ?><span
                                        class="chevron"></span>
                                </li>
                            </ul>
                            <div class="actions" style="position: relative;">
                                <button class="btn btn-sm btn-success"
                                        ng-if="(!pullMode && !pushMode && host.id && checkdata)"
                                        ng-click="skipConfigurationGeneration()">
                                    <?php echo __('Next'); ?>&nbsp;<i class="fa fa-arrow-right"></i>
                                </button>
                                <button class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && !installed && !configured)"
                                        ng-click="continueWithAgentInstallation()">
                                    <?php echo __('Next'); ?>&nbsp;<i class="fa fa-arrow-right"></i>
                                </button>
                                <button class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && !installed && configured)"
                                        ng-click="continueWithServiceConfiguration()">
                                    <?php echo __('Next'); ?>&nbsp;<i class="fa fa-arrow-right"></i>
                                </button>
                                <button class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && installed && configured && !servicesConfigured)"
                                        ng-click="saveAgentServices()">
                                    <?php echo __('Next'); ?>&nbsp;<i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                            <div class="actions" style="position: relative;">
                                <button class="btn btn-sm btn-default" ng-if="host.id"
                                        ng-click="resetAgentConfiguration()">
                                    <i class="fa fa-arrow-left"></i>&nbsp;
                                    <?php echo __('Reset'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="step-content padding-20">

                            <div class="">

                                <div class="row margin-bottom-25">
                                    <div class="form-group required">
                                        <label class="col-xs-12 col-md-1 col-md-offset-3 control-label" for="AgentHost">
                                            <?php echo __('Host'); ?>
                                        </label>
                                        <div class="col-xs-12 col-md-5">
                                            <select
                                                id="AgentHost"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="hosts"
                                                callback="loadHosts"
                                                ng-options="host.key as host.value for host in hosts"
                                                ng-model="host.id">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-bottom-25"
                                     ng-if="!pullMode && !pushMode && host.id && checkdata">

                                    <div class="col-xs-12 col-md-6 col-md-offset-3">
                                        <p class="display-inline">
                                            <?= __('We found agent check results of this host. It seems the agent is already configured.'); ?>
                                        </p>
                                        <button
                                            type="button" style="min-height: 35px;"
                                            class="btn btn-labeled btn-primary pull-right"
                                            ng-click="skipConfigurationGeneration()">

                                            <?= __('Skip configuration generation'); ?>
                                        </button>
                                    </div>

                                </div>

                                <div class="row" ng-if="!pullMode && !pushMode && host.id">

                                    <div class="col-xs-12 col-md-6 col-lg-4 col-lg-offset-1">
                                        <div class="panel panel-default">

                                            <div class="panel-body" style="min-height: 200px;">

                                                <div class="">
                                                    <h4>
                                                        <?= __('Register Agent in pull mode'); ?>
                                                    </h4>
                                                    <hr/>
                                                </div>
                                                <div class="text">
                                                    <?= __('If you configure the Agent in pull mode, it has to be reachable threw the network.'); ?>
                                                    <br>
                                                    <?= __('openITCOCKPIT will try to connect to the agent using the hosts IP Address.'); ?>
                                                    <br>
                                                    <?= __('It will fetch the check results in a minutely check interval.'); ?>
                                                </div>

                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">

                                                    <div class="col-xs-12 padding-right-0">
                                                        <button
                                                            type="button" style="min-height: 35px;"
                                                            class="btn btn-labeled btn-primary pull-right"
                                                            ng-click="continueWithPullMode()">

                                                            <?= __('Continue with pull mode'); ?>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-6 col-lg-4 col-lg-offset-1">
                                        <div class="panel panel-default">

                                            <div class="panel-body" style="min-height: 200px;">

                                                <div class="">
                                                    <h4>
                                                        <?= __('Register Agent in push mode'); ?>
                                                    </h4>
                                                    <hr/>
                                                </div>
                                                <div class="text">
                                                    <?= __('If you configure the agent in push mode, it needs to establish a connection to the openITCOCKPIT server in your network.'); ?>
                                                    <br>
                                                    <?= __('The agent will send the check results in a specific interval to the openITCOCKPIT server.'); ?>
                                                </div>

                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">

                                                    <div class="col-xs-12 padding-right-0">
                                                        <button
                                                            type="button" style="min-height: 35px;"
                                                            class="btn btn-labeled btn-primary pull-right"
                                                            ng-click="continueWithPushMode()">

                                                            <?= __('Continue with push mode'); ?>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div class=" row" ng-if="(pullMode || pushMode) && !installed && !configured">
                                <div class="jarviswidget">
                                    <header>
                                        <span class="widget-icon">
                                            <i class="fa fa-magic"></i>
                                        </span>
                                        <h2><?php echo __('Basic agent configuration'); ?></h2>
                                    </header>
                                    <div class="row" style="border-bottom: none;">

                                        <div class="widget-body col-xs-12 col-md-6">
                                            <form class="form-horizontal">
                                                <div class="row">

                                                    <div class="form-group" ng-if="pushMode">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.oitc_url">
                                                            <?php echo __('openITCOCKPIT Server Address'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.oitc_url"
                                                                class="form-control"
                                                                type="text"
                                                                placeholder="<?php echo __('External address or FQDN (example: https://demo.openitcockpit.io)'); ?>"
                                                                ng-model="agentconfig.oitc_url">
                                                        </div>
                                                    </div>

                                                    <div class="form-group" ng-if="pushMode">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.oitc_apikey">
                                                            <?php echo __('openITCOCKPIT Api-Key'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.oitc_apikey"
                                                                class="form-control"
                                                                type="text"
                                                                placeholder="<?php echo __('Api-Key'); ?>"
                                                                ng-model="agentconfig.oitc_apikey">
                                                            <div class="help-block">
                                                                <?php echo __('You need to create an openITCOCKPIT user defined API key first.'); ?>
                                                                <a href="javascript:void(0);" data-toggle="modal"
                                                                   data-target="#ApiKeyOverviewModal">
                                                                    <?= __('Click here for help') ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.address">
                                                            <?php echo __('Agent address'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.address"
                                                                class="form-control"
                                                                type="text"
                                                                placeholder="<?php echo __('Address or FQDN'); ?>"
                                                                ng-model="agentconfig.address">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.port">
                                                            <?php echo __('Agent port'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.port"
                                                                class="form-control"
                                                                type="number"
                                                                min="1"
                                                                max="65565"
                                                                placeholder="<?php echo __('Port'); ?>"
                                                                ng-model="agentconfig.port">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.interval">
                                                            <?php echo __('Default check interval'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.interval"
                                                                class="form-control"
                                                                type="number"
                                                                min="5"
                                                                placeholder="<?php echo __('Interval in seconds'); ?>"
                                                                ng-model="agentconfig.interval">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.try-autossl">
                                                            <?php echo __('Try autossl mode'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.try-autossl"
                                                                       ng-model="agentconfig['try-autossl']">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('If enabled, the agent tries to auto generate a ssl certificate for all incoming connection.'); ?>
                                                                <br>
                                                                <?php echo __('Pull mode: The certificate (including updates) will be transferred from openITCOCKPIT to the agent.'); ?>
                                                                <br>
                                                                <?php echo __('Push mode: The certificate (including updates) will be requested from the agent. It has to be trusted manually to get the certificate.'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.verbose">
                                                            <?php echo __('Print verbose output'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.verbose"
                                                                       ng-model="agentconfig.verbose">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Print administrator information on cli'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.stacktrace">
                                                            <?php echo __('Print stacktrace output'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.stacktrace"
                                                                       ng-model="agentconfig.stacktrace">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Print extended administrator information (stacktraces) on cli'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.config-update-mode">
                                                            <?php echo __('Enable remote configuration update mode'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.config-update-mode"
                                                                       ng-model="agentconfig['config-update-mode']">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Enables the remote agent configuration update mode.'); ?>
                                                                <br>
                                                                <?php echo __('Should only be configured after an successful ssl configuration.'); ?>
                                                                <br>
                                                                <p style="color: red;">
                                                                    <?php echo __('Warning: Remote code execution is possible if the certificate was stolen or no ssl was configured.'); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfig.auth">
                                                            <?php echo __('Enable HTTP Basic Auth'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfig.auth"
                                                                class="form-control"
                                                                type="text"
                                                                placeholder="<?php echo __('username:password'); ?>"
                                                                ng-model="agentconfig.auth">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.customchecks">
                                                            <?php echo __('Enable custom checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.customchecks"
                                                                       ng-model="agentconfig.customchecks">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Add custom check configuration path to default configuration and enables custom checks'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" ng-if="agentconfig.customchecks">
                                                        <label class="col-xs-12 col-md-3 control-label"
                                                               for="agentconfigCustomchecks.max_worker_threads">
                                                            <?php echo __('Set max custom check threads'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9">
                                                            <input
                                                                id="agentconfigCustomchecks.max_worker_threads"
                                                                class="form-control"
                                                                type="number"
                                                                min="2"
                                                                ng-model="agentconfigCustomchecks['max_worker_threads']">
                                                            <div class="help-block">
                                                                <?php echo __('Set maximum amount of threads used for customchecks.'); ?>
                                                                <br>
                                                                <?php echo __('It should be increased with increasing number of custom checks, but consider: each thread needs (a bit) memory.'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.temperature-fahrenheit">
                                                            <?php echo __('Use Fahrenheit'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.temperature-fahrenheit"
                                                                       ng-model="agentconfig['temperature-fahrenheit']">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Use Fahrenheit temperature unit instead of Celsius.'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.dockerstats">
                                                            <?php echo __('Enable docker status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.dockerstats"
                                                                       ng-model="agentconfig.dockerstats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.qemustats">
                                                            <?php echo __('Enable qemu status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.qemustats"
                                                                       ng-model="agentconfig.qemustats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.cpustats">
                                                            <?php echo __('Enable cpu status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.cpustats"
                                                                       ng-model="agentconfig.cpustats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.sensorstats">
                                                            <?php echo __('Enable sensor status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.sensorstats"
                                                                       ng-model="agentconfig.sensorstats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.processstats">
                                                            <?php echo __('Enable process status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.processstats"
                                                                       ng-model="agentconfig.processstats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.processstats-including-child-ids">
                                                            <?php echo __('Include process child ids'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.processstats-including-child-ids"
                                                                       ng-model="agentconfig['processstats-including-child-ids']">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                            <div class="help-block">
                                                                <?php echo __('Add process child ids to the default process status check (computationally intensive).'); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.netstats">
                                                            <?php echo __('Enable network status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.netstats"
                                                                       ng-model="agentconfig.netstats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.diskstats">
                                                            <?php echo __('Enable disk status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.diskstats"
                                                                       ng-model="agentconfig.diskstats">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.netio">
                                                            <?php echo __('Enable network I/O calculation'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.netio"
                                                                       ng-model="agentconfig.netio">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.diskio">
                                                            <?php echo __('Enable disk I/O calculation'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.diskio"
                                                                       ng-model="agentconfig.diskio">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col col-md-3 control-label"
                                                               for="agentconfig.winservices">
                                                            <?php echo __('Enable windows services status checks'); ?>
                                                        </label>

                                                        <div class="col-xs-12 col-md-9 smart-form">
                                                            <label class="checkbox small-checkbox-label no-required">
                                                                <input type="checkbox" name="checkbox"
                                                                       id="agentconfig.winservices"
                                                                       ng-model="agentconfig.winservices">
                                                                <i class="checkbox-primary"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>

                                        </div>

                                        <div class="widget-body col-xs-12 col-md-3">
                                            <p><b>agent.cnf:</b></p>
                                            <textarea readonly ng-model="configTemplate"
                                                      style="min-height: 560px; width: 100%;"></textarea>
                                        </div>
                                        <div class="widget-body col-xs-12 col-md-3" ng-if="agentconfig.customchecks">
                                            <p><b>customchecks.cnf:</b></p>
                                            <textarea readonly ng-model="configTemplateCustomchecks"
                                                      style="min-height: 560px; width: 100%;"></textarea>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class=" row" ng-if="(pullMode || pushMode) && !installed && configured">
                                <div class="jarviswidget">
                                    <header>
                                        <span class="widget-icon">
                                            <i class="fa fa-magic"></i>
                                        </span>
                                        <h2><?php echo __('Installation guide'); ?></h2>
                                    </header>

                                    <div class="col-xs-12">

                                        <div class="row" style="border-bottom: none;">
                                            <p>
                                                <?= __('Download the agent installer for your system from our official openITCOCKPIT Website'); ?>
                                                :&nbsp;
                                                <a href="https://openitcockpit.io/download/#download"
                                                   target="_blank"><?= __('Download here'); ?></a>
                                                <br><br>
                                                <?= __('After the installation you have to update the default configuration files with the recently generated configuration'); ?>
                                                <br>
                                            </p>

                                            <br>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="panel panel-default">

                                                        <div class="panel-body">

                                                            <div class="">
                                                                <h4>
                                                                    <?= __('Config file default paths:'); ?>
                                                                </h4>
                                                                <hr/>
                                                            </div>
                                                            <div class="text">
                                                                <ul>
                                                                    <li>
                                                                        Windows: <code>C:\Program
                                                                            Files\openitcockpit-agent\config.cnf</code>
                                                                    </li>
                                                                    <li>
                                                                        Linux:
                                                                        <code>/etc/openitcockpit-agent/config.cnf</code>
                                                                    </li>
                                                                    <li>
                                                                        macOS: <code>/Library/openitcockpit-agent/config.cnf</code>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <p>
                                                    <br>
                                                    <?= __('Depending on your system, go to the configuration directory and replace the contents of the .cnf files with the following content:'); ?>
                                                </p>

                                                <div class="widget-body col-xs-12 col-md-3">
                                                    <p><b>agent.cnf:</b></p>
                                                    <textarea readonly ng-model="configTemplate"
                                                              style="min-height: 560px; width: 100%;"></textarea>
                                                </div>
                                                <div class="widget-body col-xs-12 col-md-3"
                                                     ng-if="agentconfig.customchecks">
                                                    <p><b>customchecks.cnf:</b></p>
                                                    <textarea readonly ng-model="configTemplateCustomchecks"
                                                              style="min-height: 560px; width: 100%;"></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <p>
                                                    <br>
                                                    <?= __('Restart the agent to apply the new configuration:'); ?>
                                                </p>

                                                <div class="col-xs-12 col-md-6">
                                                    <div class="panel panel-default">

                                                        <div class="panel-body">

                                                            <div class="">
                                                                <h4>
                                                                    <?= __('Run as administrator:'); ?>
                                                                </h4>
                                                                <hr/>
                                                            </div>
                                                            <div class="text">
                                                                <ul>
                                                                    <li>
                                                                        Windows CMD: <code>sc stop oitcAgentSvc && sc
                                                                            start oitcAgentSvc</code>
                                                                    </li>
                                                                    <li>
                                                                        Linux: <code>systemctl restart
                                                                            openitcockpit-agent</code>
                                                                    </li>
                                                                    <li>
                                                                        macOS: <code>/bin/launchctl restart
                                                                            com.it-novum.openitcockpit.agent</code>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class=" row" ng-if="(pullMode || pushMode) && installed && configured && !servicesConfigured">
                                <div class="jarviswidget">
                                    <header>
                                        <span class="widget-icon">
                                            <i class="fa fa-magic"></i>
                                        </span>
                                        <h2 ng-hide="checkdata"><?php echo __('Wait get check results from the configured agent ...'); ?></h2>
                                        <h2 ng-show="checkdata"><?php echo __('Please choose the options you want to monitor'); ?></h2>
                                    </header>


                                    <div class="row" style="border-bottom: none;">
                                        <div class="col-xs-12">
                                            <p ng-hide="checkdata">
                                                <?= __('Be patient, a background job is asking the openITCOCKPIT Server (every 5 seconds) for agent check results.'); ?>
                                                <br>
                                                <?= __('Please make sure the agent is running and right configured.'); ?>
                                            </p>

                                            <div class="row" ng-show="checkdata">
                                                <div class="jarviswidget">
                                                    <div class="widget-body">

                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.cpu_percentage">
                                                            <div class="form-group">
                                                                <label class="col col-md-2 control-label"
                                                                       for="choosenServicesToMonitor.cpu_percentage">
                                                                    <?php echo __('CPU percentage'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9 smart-form">
                                                                    <label
                                                                        class="checkbox small-checkbox-label no-required">
                                                                        <input type="checkbox" name="checkbox"
                                                                               id="choosenServicesToMonitor.cpu_percentage"
                                                                               ng-model="choosenServicesToMonitor.cpu_percentage">
                                                                        <i class="checkbox-primary"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.system_load">
                                                            <div class="form-group">
                                                                <label class="col col-md-2 control-label"
                                                                       for="choosenServicesToMonitor.system_load">
                                                                    <?php echo __('System load'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9 smart-form">
                                                                    <label
                                                                        class="checkbox small-checkbox-label no-required">
                                                                        <input type="checkbox" name="checkbox"
                                                                               id="choosenServicesToMonitor.system_load"
                                                                               ng-model="choosenServicesToMonitor.system_load">
                                                                        <i class="checkbox-primary"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.memory">
                                                            <div class="form-group">
                                                                <label class="col col-md-2 control-label"
                                                                       for="choosenServicesToMonitor.memory">
                                                                    <?php echo __('Memory usage'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9 smart-form">
                                                                    <label
                                                                        class="checkbox small-checkbox-label no-required">
                                                                        <input type="checkbox" name="checkbox"
                                                                               id="choosenServicesToMonitor.memory"
                                                                               ng-model="choosenServicesToMonitor.memory">
                                                                        <i class="checkbox-primary"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.swap">
                                                            <div class="form-group">
                                                                <label class="col col-md-2 control-label"
                                                                       for="choosenServicesToMonitor.swap">
                                                                    <?php echo __('Swap usage'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9 smart-form">
                                                                    <label
                                                                        class="checkbox small-checkbox-label no-required">
                                                                        <input type="checkbox" name="checkbox"
                                                                               id="choosenServicesToMonitor.swap"
                                                                               ng-model="choosenServicesToMonitor.swap">
                                                                        <i class="checkbox-primary"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.disk_io">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.disk_io">
                                                                    <?php echo __('Disk IO'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.disk_io"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.disk_io"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.disk_io"
                                                                        ng-model="choosenServicesToMonitor.disk_io">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.disk_io)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.disks">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.disks">
                                                                    <?php echo __('Disk usage'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.disks"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.disks"
                                                                        multiple
                                                                        ng-options="disk.disk.mountpoint as disk.disk.mountpoint for disk in checkdata.disks"
                                                                        ng-model="choosenServicesToMonitor.disks">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">({{countObj(checkdata.disks)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.sensors.fans">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.fans">
                                                                    <?php echo __('Fans'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.fans"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.sensors.fans"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.sensors.fans"
                                                                        ng-model="choosenServicesToMonitor.fans">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.sensors.fans)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.sensors.temperatures">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.temperatures">
                                                                    <?php echo __('Temperatures'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.temperatures"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.sensors.temperatures"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.sensors.temperatures"
                                                                        ng-model="choosenServicesToMonitor.temperatures">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.sensors.temperatures)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.sensors.battery">
                                                            <div class="form-group">
                                                                <label class="col col-md-2 control-label"
                                                                       for="choosenServicesToMonitor.battery">
                                                                    <?php echo __('Battery'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9 smart-form">
                                                                    <label
                                                                        class="checkbox small-checkbox-label no-required">
                                                                        <input type="checkbox" name="checkbox"
                                                                               id="choosenServicesToMonitor.battery"
                                                                               ng-model="choosenServicesToMonitor.battery">
                                                                        <i class="checkbox-primary"></i>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.net_io">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.net_io">
                                                                    <?php echo __('Network device IO'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.net_io"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.net_io"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.net_io"
                                                                        ng-model="choosenServicesToMonitor.net_io">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">({{countObj(checkdata.net_io)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.net_stats">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.net_stats">
                                                                    <?php echo __('Network device stats'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.net_stats"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.net_stats"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.net_stats"
                                                                        ng-model="choosenServicesToMonitor.net_stats">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.net_stats)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5" ng-show="checkdata.processes">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.processes">
                                                                    <?php echo __('Processes'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.processes"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.processes"
                                                                        multiple
                                                                        ng-options="(value.cmdline != '' ? value.cmdline : (value.exec != '' ? value.exec : value.name)) as (value.cmdline != '' ? value.cmdline : (value.exec != '' ? value.exec : value.name)) for (key, value) in checkdata.processes"
                                                                        ng-model="choosenServicesToMonitor.processes">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.processes)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.windows_services">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.windows_services">
                                                                    <?php echo __('Windows services'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.windows_services"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.windows_services"
                                                                        multiple
                                                                        ng-options="key as (value.binpath != '' ? value.binpath : (value.display_name != '' ? value.display_name : value.name)) for (key, value) in checkdata.windows_services"
                                                                        ng-model="choosenServicesToMonitor.windows_services">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.windows_services)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.dockerstats.result">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.docker_running">
                                                                    <?php echo __('Docker container running'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.docker_running"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.dockerstats.result"
                                                                        multiple
                                                                        ng-options="value.id as (value.id + ' (' + value.name + ')') for (key, value) in checkdata.dockerstats.result"
                                                                        ng-model="choosenServicesToMonitor.docker_running">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.dockerstats.result)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.dockerstats.result">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.docker_cpu">
                                                                    <?php echo __('Docker container cpu usage'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.docker_cpu"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.dockerstats.result"
                                                                        multiple
                                                                        ng-options="value.id as (value.id + ' (' + value.name + ')') for (key, value) in checkdata.dockerstats.result"
                                                                        ng-model="choosenServicesToMonitor.docker_cpu">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.dockerstats.result)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.dockerstats.result">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.docker_memory">
                                                                    <?php echo __('Docker container memory usage'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.docker_memory"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.dockerstats.result"
                                                                        multiple
                                                                        ng-options="value.id as (value.id + ' (' + value.name + ')') for (key, value) in checkdata.dockerstats.result"
                                                                        ng-model="choosenServicesToMonitor.docker_memory">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.dockerstats.result)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.qemustats.result">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.qemu_running">
                                                                    <?php echo __('QEMU vm running'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.qemu_running"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.qemustats.result"
                                                                        multiple
                                                                        ng-options="value.uuid as (value.uuid + ' (' + value.name + ')') for (key, value) in checkdata.qemustats.result"
                                                                        ng-model="choosenServicesToMonitor.qemu_running">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.qemustats.result)}})
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-bottom-5"
                                                             ng-show="checkdata.customchecks">
                                                            <div class="form-group">
                                                                <label class="col-xs-12 col-lg-2 control-label"
                                                                       for="choosenServicesToMonitor.customchecks">
                                                                    <?php echo __('Customchecks'); ?>
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6">
                                                                    <select
                                                                        id="choosenServicesToMonitor.customchecks"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="checkdata.customchecks"
                                                                        multiple
                                                                        ng-options="key as key for (key, value) in checkdata.customchecks"
                                                                        ng-model="choosenServicesToMonitor.customchecks">
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    ({{countObj(checkdata.customchecks)}})
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </article>
</div>


<?php echo $this->element('apikey_help'); ?>

