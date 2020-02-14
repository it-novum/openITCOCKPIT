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
            <i class="fa fa-cloud-download fa-fw "></i>
            <?= __('openITCOCKPIT Agent') ?>
            <span>>
                <?= __('Add'); ?>
            </span>
        </h1>
    </div>
</div>

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

<div class="row" ng-if="!pullMode && !pushMode && host.id">

    <div class="col-xs-12 col-mg-6 col-lg-4 col-lg-offset-1">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 200px;">

                <div class="">
                    <h4>
                        <?= __('Register Agent in pull mode'); ?>
                    </h4>
                    <hr/>
                </div>
                <div class="text">
                    <?= __('Intelligent description'); ?>
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

    <div class="col-xs-12 col-mg-6 col-lg-4 col-lg-offset-1">
        <div class="panel panel-default">

            <div class="panel-body" style="min-height: 200px;">

                <div class="">
                    <h4>
                        <?= __('Register Agent in push mode'); ?>
                    </h4>
                    <hr/>
                </div>
                <div class="text">
                    <?= __('Intelligent description'); ?>
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

<div class="row" ng-if="(pullMode || pushMode) && !installed && !configured">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.oitc_url">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.oitc_apikey">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.address">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.port">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.interval">
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
                            <label class="col col-md-3 control-label" for="agentconfig.try-autossl">
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
                            <label class="col col-md-3 control-label" for="agentconfig.verbose">
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
                            <label class="col col-md-3 control-label" for="agentconfig.stacktrace">
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
                            <label class="col col-md-3 control-label" for="agentconfig.config-update-mode">
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
                            <label class="col-xs-12 col-md-3 control-label" for="agentconfig.auth">
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
                            <label class="col col-md-3 control-label" for="agentconfig.customchecks">
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
                            <label class="col col-md-3 control-label" for="agentconfig.temperature-fahrenheit">
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
                            <label class="col col-md-3 control-label" for="agentconfig.dockerstats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.qemustats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.cpustats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.sensorstats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.processstats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.netstats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.diskstats">
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
                            <label class="col col-md-3 control-label" for="agentconfig.netio">
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
                            <label class="col col-md-3 control-label" for="agentconfig.diskio">
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
                            <label class="col col-md-3 control-label" for="agentconfig.winservices">
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
                <p><b>oitc_agent.conf:</b></p>
                <textarea readonly ng-model="configTemplate" style="min-height: 560px; width: 100%;"></textarea>
            </div>
            <div class="widget-body col-xs-12 col-md-3" ng-if="agentconfig.customchecks">
                <p><b>oitc_customchecks.conf:</b></p>
                <textarea readonly ng-model="configTemplateCustomchecks"
                          style="min-height: 560px; width: 100%;"></textarea>
            </div>

        </div>

        <div class="row">

            <div class="col-xs-12 padding-right-0">
                <button
                    type="button" style="min-height: 35px;"
                    class="btn btn-labeled btn-default pull-right margin-bottom-10"
                    ng-click="resetAgentConfiguration()">

                    <?= __('Reset'); ?>
                </button>
                <button
                    type="button" style="min-height: 35px;"
                    class="btn btn-labeled btn-primary pull-right margin-bottom-10 margin-right-5"
                    ng-click="continueWithAgentInstallation()">

                    <?= __('Continue with agent installation'); ?>
                </button>
            </div>

        </div>
    </div>
</div>

<div class="row" ng-if="(pullMode || pushMode) && !installed && configured">
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
                    <?= __('Download here install there ....'); ?>
                </p>

                <p>
                    <?= __('Copy the configuration into the given configuration files of your specific host system ....'); ?>
                </p>

                <div class="widget-body col-xs-12 col-md-3">
                    <p><b>oitc_agent.conf:</b></p>
                    <textarea readonly ng-model="configTemplate" style="min-height: 560px; width: 100%;"></textarea>
                </div>
                <div class="widget-body col-xs-12 col-md-3" ng-if="agentconfig.customchecks">
                    <p><b>oitc_customchecks.conf:</b></p>
                    <textarea readonly ng-model="configTemplateCustomchecks"
                              style="min-height: 560px; width: 100%;"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 padding-right-0">
                    <button
                        type="button" style="min-height: 35px;"
                        class="btn btn-labeled btn-default pull-right margin-bottom-10"
                        ng-click="resetAgentConfiguration()">

                        <?= __('Reset'); ?>
                    </button>
                    <button
                        type="button" style="min-height: 35px;"
                        class="btn btn-labeled btn-primary pull-right margin-top-10 margin-bottom-10"
                        ng-click="continueWithServiceConfiguration()">

                        <?= __('Configure agent services'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="(pullMode || pushMode) && installed && configured">
    <div class="jarviswidget">
        <header>
            <span class="widget-icon">
                <i class="fa fa-magic"></i>
            </span>
            <h2><?php echo __('wait to fetch information ...'); ?></h2>
        </header>
        <div class="row">
            <div class="col-xs-12 padding-right-0">
                <button
                    type="button" style="min-height: 35px;"
                    class="btn btn-labeled btn-default pull-right margin-bottom-10"
                    ng-click="resetAgentConfiguration()">

                    <?= __('Reset'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('apikey_help'); ?>

