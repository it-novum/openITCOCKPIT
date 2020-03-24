<?php
// Copyright (C) <2020>  <it-novum GmbH>
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

<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="AgentconnectorsAgent">
            <i class="fa fa-user-secret"></i> <?php echo __('openITCOCKPIT Agent'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('Configuration'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('openITCOCKPIT Agent'); ?>
                    <span class="fw-300"><i><?php echo __('Configuration'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('agents', 'agentconnector')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='AgentconnectorsAgent'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">


                    <form ng-submit="submit();" class="form-horizontal">

                        <div class="row form-group required">
                            <label class="col-xs-12 col-md-1 control-label" for="AgentHost">
                                <?php echo __('Host'); ?>
                            </label>
                            <div class="col-xs-12 col-md-5">
                                <select
                                    id="AgentHost"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="host.id">
                                </select>
                            </div>
                            <div class="col-xs-12 col-md-4"
                                 ng-show="remoteAgentConfig && pullMode && !installed && !configured">
                                <p>
                                    <?= __('If you changed some configuration, you should run the remote configuration update before you continue. Otherwise you are maybe not able to connect to the agent again threw the web interface. In that case you have to copy the configuration manually to the agent.'); ?>
                                </p>
                            </div>
                            <div class="col-xs-12 col-md-2"
                                 ng-show="remoteAgentConfig && pullMode && !installed && !configured">
                                <button
                                    type="button" style="min-height: 35px;"
                                    class="btn btn-labeled btn-primary pull-right"
                                    ng-click="runRemoteConfigUpdate()">

                                    <?= __('Run remote configuration update'); ?>
                                </button>
                            </div>

                            <div class="col-xs-12 col-md-6"
                                 ng-if="!pullMode && !pushMode && host.id && servicesToCreate">
                                <!--<p class="display-none">
                                    <?= __('We found agent check results of this host. It seems the agent is already configured.'); ?>
                                </p>-->
                                <button
                                    type="button" style="min-height: 35px;"
                                    class="btn btn-labeled btn-primary pull-right"
                                    ng-click="skipConfigurationGeneration()">

                                    <?= __('Skip configuration generation'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="row card margin-bottom-10">
                            <div class="card-header fuelux">

                                <div class="wizard">
                                    <ul class="nav nav-tabs step-anchor">
                                        <li data-target="#step0" class="nav-item reset-btn"
                                            ng-if="(host.id && !servicesConfigured) || finished"
                                            ng-click="resetAgentConfiguration()">
                                            <i class="fas fa-trash"></i>&nbsp;
                                            <span class="d-none d-lg-inline">
                                                <?php echo __('Reset'); ?>
                                            </span>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step1" class="nav-item"
                                            ng-class="(!pullMode && !pushMode) ? 'active' : ''">
                                            <span class="badge badge-info">1</span>
                                            <?php echo __('Select host and agent mode'); ?>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step2" class="nav-item"
                                            ng-class="((pullMode || pushMode) && !installed && !configured) ? 'active' : ''">
                                            <span class="badge">2</span>
                                            <?php echo __('Basic agent configuration'); ?>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step3"
                                            ng-class="((pullMode || pushMode) && !installed && configured) ? 'active' : ''">
                                            <span class="badge">3</span>
                                            <?php echo __('Installation guide'); ?>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step4"
                                            ng-class="((pullMode || pushMode) && installed && configured && !servicesConfigured) ? 'active' : ''">
                                            <span class="badge">4</span>
                                            <?php echo __('Create agent services'); ?>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step4"
                                            ng-class="((pullMode || pushMode) && installed && configured && servicesConfigured) ? 'active' : ''">
                                            <span class="badge">5</span>
                                            <?php echo __('Save changes'); ?>
                                            <span class="chevron"></span>
                                        </li>
                                        <li data-target="#step5" class="nav-item next-btn"
                                            ng-if="(!pullMode && !pushMode && host.id && servicesToCreate)"
                                            ng-click="skipConfigurationGeneration()">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <span class="d-none d-lg-inline">
                                                <?php echo __('Next'); ?>
                                            </span>
                                        </li>
                                        <li data-target="#step5" class="nav-item next-btn"
                                            ng-if="((pullMode || pushMode) && !installed && !configured)"
                                            ng-click="continueWithAgentInstallation()">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <span class="d-none d-lg-inline">
                                                <?php echo __('Next'); ?>
                                            </span>
                                        </li>
                                        <li data-target="#step5" class="nav-item next-btn"
                                            ng-if="((pullMode || pushMode) && !installed && configured)"
                                            ng-click="continueWithServiceConfiguration()">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <span class="d-none d-lg-inline">
                                                <?php echo __('Next'); ?>
                                            </span>
                                        </li>
                                        <li data-target="#step5" class="nav-item next-btn"
                                            ng-if="((pullMode || pushMode) && installed && configured && !servicesConfigured)"
                                            ng-click="saveAgentServices()">
                                            <i class="fa fa-arrow-right"></i>&nbsp;
                                            <span class="d-none d-lg-inline">
                                                <?php echo __('Next'); ?>
                                            </span>
                                        </li>
                                    </ul>

                                </div>

                            </div>
                            <div class="card-body">

                                <div class="row justify-content-md-center" ng-if="!pullMode && !pushMode && host.id">

                                    <div class="col-xs-12 col-md-6 col-lg-5">
                                        <div class="panel panel-default">

                                            <div class="panel-hrd padding-20" style="padding-bottom: 0px;">
                                                <h4>
                                                    <?= __('Register Agent in pull mode'); ?>
                                                </h4>
                                                <hr/>
                                            </div>

                                            <div class="panel-container padding-20" style="min-height: 110px;">
                                                <div class="text">
                                                    <?= __('If you configure the Agent in pull mode, it has to be reachable through the network.'); ?>
                                                    <br>
                                                    <?= __('openITCOCKPIT will try to connect to the agent using the hosts IP Address.'); ?>
                                                    <br>
                                                    <?= __('It will fetch the check results in a minutely check interval.'); ?>
                                                </div>
                                            </div>

                                            <div class="panel-footer padding-20">
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

                                    <div class="col-xs-12 col-md-6 col-lg-5 col-lg-offset-1">
                                        <div class="panel panel-default">

                                            <div class="panel-hrd padding-20" style="padding-bottom: 0px;">
                                                <h4>
                                                    <?= __('Register Agent in push mode'); ?>
                                                </h4>
                                                <hr/>
                                            </div>

                                            <div class="panel-container padding-20" style="min-height: 110px;">
                                                <div class="text">
                                                    <?= __('If you configure the agent in push mode, it needs to establish a connection to the openITCOCKPIT server in your network.'); ?>
                                                    <br>
                                                    <?= __('The agent will send the check results in a specific interval to the openITCOCKPIT server.'); ?>
                                                </div>
                                            </div>

                                            <div class="panel-footer padding-20">
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


                                <div class="row" ng-if="(pullMode || pushMode) && !installed && !configured">
                                    <div class="padding-20">
                                        <div class="row" style="border-bottom: none;">

                                            <div class="widget-body col-xs-12 col-md-6">
                                                <form class="form-horizontal">
                                                    <div class="row">

                                                        <div class="form-group col-12 padding-left-0" ng-if="pushMode">
                                                            <label class="col-xs-12 col-md-3 control-label"
                                                                   for="agentconfig['oitc-url']">
                                                                <?php echo __('openITCOCKPIT Server Address'); ?>
                                                            </label>

                                                            <div class="col-xs-12 col-md-9">
                                                                <input
                                                                    id="agentconfig['oitc-url']"
                                                                    class="form-control"
                                                                    type="text"
                                                                    placeholder="<?php echo __('External address or FQDN (example: https://demo.openitcockpit.io)'); ?>"
                                                                    ng-model="agentconfig['oitc-url']">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0" ng-if="pushMode">
                                                            <label class="col-xs-12 col-md-3 control-label"
                                                                   for="agentconfig['oitc-apikey']">
                                                                <?php echo __('openITCOCKPIT Api-Key'); ?>
                                                            </label>

                                                            <div class="col-xs-12 col-md-9">
                                                                <input
                                                                    id="agentconfig['oitc-apikey']"
                                                                    class="form-control"
                                                                    type="text"
                                                                    placeholder="<?php echo __('Api-Key'); ?>"
                                                                    ng-model="agentconfig['oitc-apikey']">
                                                                <div class="help-block">
                                                                    <?php echo __('You need to create an openITCOCKPIT user defined API key first.'); ?>
                                                                    <a href="javascript:void(0);" data-toggle="modal"
                                                                       data-target="#ApiKeyOverviewModal">
                                                                        <?= __('Click here for help') ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0">
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

                                                        <div class="form-group col-12 padding-left-0">
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

                                                        <div class="form-group col-12 padding-left-0">
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

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.try-autossl"
                                                                       ng-model="agentconfig['try-autossl']">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.try-autossl">
                                                                    <?php echo __('Try autossl mode'); ?>
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

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.verbose"
                                                                       ng-model="agentconfig.verbose">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.verbose">
                                                                    <?php echo __('Print verbose output'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Print administrator information on cli'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.stacktrace"
                                                                       ng-model="agentconfig.stacktrace">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.stacktrace">
                                                                    <?php echo __('Print stacktrace output'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Print extended administrator information (stacktraces) on cli'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12" ng-show="pullMode">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.config-update-mode"
                                                                       ng-model="agentconfig['config-update-mode']">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.config-update-mode">
                                                                    <?php echo __('Enable remote configuration update mode'); ?>
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

                                                        <div class="form-group col-12 padding-left-0">
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

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.customchecks"
                                                                       ng-model="agentconfig.customchecks">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.customchecks">
                                                                    <?php echo __('Enable custom checks'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Add custom check configuration path to default configuration and enables custom checks'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0"
                                                             ng-if="agentconfig.customchecks">
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

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.temperature-fahrenheit"
                                                                       ng-model="agentconfig['temperature-fahrenheit']">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.temperature-fahrenheit">
                                                                    <?php echo __('Use Fahrenheit'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Use Fahrenheit temperature unit instead of Celsius.'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.dockerstats"
                                                                       ng-model="agentconfig.dockerstats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.dockerstats">
                                                                    <?php echo __('Enable docker status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.qemustats"
                                                                       ng-model="agentconfig.qemustats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.qemustats">
                                                                    <?php echo __('Enable qemu status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.cpustats"
                                                                       ng-model="agentconfig.cpustats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.cpustats">
                                                                    <?php echo __('Enable cpu status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.sensorstats"
                                                                       ng-model="agentconfig.sensorstats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.sensorstats">
                                                                    <?php echo __('Enable sensor status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.processstats"
                                                                       ng-model="agentconfig.processstats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.processstats">
                                                                    <?php echo __('Enable process status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.processstats-including-child-ids"
                                                                       ng-model="agentconfig['processstats-including-child-ids']">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.processstats-including-child-ids">
                                                                    <?php echo __('Include process child ids'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Add process child ids to the default process status check (computationally intensive).'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.netstats"
                                                                       ng-model="agentconfig.netstats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.netstats">
                                                                    <?php echo __('Enable network status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.diskstats"
                                                                       ng-model="agentconfig.diskstats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.diskstats">
                                                                    <?php echo __('Enable disk status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.netio"
                                                                       ng-model="agentconfig.netio">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.netio">
                                                                    <?php echo __('Enable network I/O calculation'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.diskio"
                                                                       ng-model="agentconfig.diskio">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.diskio">
                                                                    <?php echo __('Enable disk I/O calculation'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.winservices"
                                                                       ng-model="agentconfig.winservices">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.winservices">
                                                                    <?php echo __('Enable windows services status checks'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>

                                            </div>

                                            <div class="widget-body col-xs-12 col-md-3">
                                                <p><b><?= __('agent.cnf:'); ?></b></p>
                                                <textarea readonly ng-model="configTemplate"
                                                          style="min-height: 580px; width: 100%;"></textarea>
                                            </div>
                                            <div class="widget-body col-xs-12 col-md-3" style="padding-right: 35px;"
                                                 ng-if="agentconfig.customchecks">
                                                <p><b><?= __('customchecks.cnf:'); ?></b></p>
                                                <textarea readonly ng-model="configTemplateCustomchecks"
                                                          style="min-height: 580px; width: 100%;"></textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>


                                <div class="row" ng-if="(pullMode || pushMode) && !installed && configured">
                                    <div class="padding-20">
                                        <div class="row" style="border-bottom: none;">
                                            <div class="row col-12 padding-left-25">
                                                <p>
                                                    <?= __('Download the agent installer for your system from our official openITCOCKPIT Website:'); ?>
                                                    <a href="https://openitcockpit.io/agent"
                                                       target="_blank"><?= __('Download here'); ?></a>
                                                    <br><br>
                                                    <?= __('After the installation you have to update the default configuration files with the recently generated configuration'); ?>
                                                    <br>
                                                </p>
                                            </div>

                                            <br>
                                            <div class="row col-12">
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="panel panel-default">
                                                        <div class="panel-hrd padding-20" style="padding-bottom: 0px;">
                                                            <h4>
                                                                <?= __('Config file default paths:'); ?>
                                                            </h4>
                                                            <hr/>
                                                        </div>
                                                        <div class="panel-container padding-20">
                                                            <div class="text">
                                                                <ul>
                                                                    <li>
                                                                        <?= __('Windows:'); ?>
                                                                        <code>
                                                                            <?= __('C:\Program Files\openitcockpit-agent\config.cnf'); ?>
                                                                        </code>
                                                                    </li>
                                                                    <li>
                                                                        <?= __('Linux:'); ?>
                                                                        <code>
                                                                            <?= __('/etc/openitcockpit-agent/config.cnf'); ?>
                                                                        </code>
                                                                    </li>
                                                                    <li>
                                                                        <?= __('macOS:'); ?>
                                                                        <code>
                                                                            <?= __('/Library/openitcockpit-agent/config.cnf'); ?>
                                                                        </code>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row col-12 padding-left-25">
                                                <p>
                                                    <br>
                                                    <?= __('Depending on your system, go to the configuration directory and replace the contents of the .cnf files with the following content:'); ?>
                                                    <br>
                                                    (<?= __('If the custom check configuration file is set, be sure, the file path matches the path for your specific system.'); ?>
                                                    )
                                                </p>
                                            </div>

                                            <div class="row col-12">
                                                <div class="widget-body col-xs-12 col-md-3">
                                                    <p><b><?= __('agent.cnf:'); ?></b></p>
                                                    <textarea readonly ng-model="configTemplate"
                                                              style="min-height: 580px; width: 100%;"></textarea>
                                                </div>
                                                <div class="widget-body col-xs-12 col-md-3"
                                                     ng-if="agentconfig.customchecks">
                                                    <p><b><?= __('customchecks.cnf:'); ?></b></p>
                                                    <textarea readonly ng-model="configTemplateCustomchecks"
                                                              style="min-height: 580px; width: 100%;"></textarea>
                                                </div>
                                            </div>

                                            <div class="row col-12 padding-left-25">
                                                <p>
                                                    <br>
                                                    <?= __('Restart the agent to apply the new configuration:'); ?>
                                                </p>
                                            </div>

                                            <div class="row col-12">

                                                <div class="col-xs-12 col-md-6">
                                                    <div class="panel panel-default">
                                                        <div class="panel-hrd padding-20" style="padding-bottom: 0px;">
                                                            <h4>
                                                                <?= __('Run as administrator:'); ?>
                                                            </h4>
                                                            <hr/>
                                                        </div>

                                                        <div class="panel-container padding-20">
                                                            <div class="text">
                                                                <ul>
                                                                    <li>
                                                                        <?= __('Windows CMD:'); ?>
                                                                        <code><?= __('sc stop oitcAgentSvc && sc
                                                                            start oitcAgentSvc'); ?>
                                                                        </code>
                                                                    </li>
                                                                    <li>
                                                                        <?= __('Linux:'); ?>
                                                                        <code><?= __('systemctl restart
                                                                            openitcockpit-agent'); ?>
                                                                        </code>
                                                                    </li>
                                                                    <li>
                                                                        <?= __('macOS:'); ?>
                                                                        <code><?= __('/bin/launchctl restart
                                                                            com.it-novum.openitcockpit.agent'); ?>
                                                                        </code>
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


                                <div class=" row"
                                     ng-if="(pullMode || pushMode) && installed && configured && !servicesConfigured">
                                    <div class="col-12 padding-20">
                                        <div class="margin-bottom-25">
                                            <span class="widget-icon">
                                                <i class="fa fa-magic"></i>
                                            </span>
                                            <h4 ng-hide="servicesToCreate" class="margin-bottom-10"
                                                style="display: inline;"><?php echo __('Wait get check results from the configured agent ...'); ?></h4>
                                            <h4 ng-show="servicesToCreate" class="margin-bottom-10"
                                                style="display: inline;"><?php echo __('Please choose the options you want to monitor'); ?></h4>
                                        </div>


                                        <div class="row" style="border-bottom: none;">
                                            <div class="col-12">
                                                <p ng-hide="servicesToCreate">
                                                    <?= __('Be patient, a background job is asking the openITCOCKPIT Server (every 10 seconds) for agent check results.'); ?>
                                                    <br>
                                                    <?= __('Please make sure the agent is running and right configured.'); ?>
                                                </p>

                                                <div class="row" ng-show="servicesToCreate">
                                                    <div class="col-12">
                                                        <div class="widget-body">

                                                            <div class="row margin-bottom-5"
                                                                 ng-show="servicesToCreate.CpuTotalPercentage">
                                                                <div class="form-group col-12">
                                                                    <div
                                                                        class="custom-control custom-checkbox margin-bottom-10">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="choosenServicesToMonitor.CpuTotalPercentage"
                                                                               ng-model="choosenServicesToMonitor.CpuTotalPercentage">
                                                                        <label class="custom-control-label"
                                                                               for="choosenServicesToMonitor.CpuTotalPercentage">
                                                                            <?php echo __('CPU percentage'); ?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-bottom-5"
                                                                 ng-show="servicesToCreate.SystemLoad">
                                                                <div class="form-group col-12">
                                                                    <div
                                                                        class="custom-control custom-checkbox margin-bottom-10">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="choosenServicesToMonitor.SystemLoad"
                                                                               ng-model="choosenServicesToMonitor.SystemLoad">
                                                                        <label class="custom-control-label"
                                                                               for="choosenServicesToMonitor.SystemLoad">
                                                                            <?php echo __('System load'); ?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-bottom-5"
                                                                 ng-show="servicesToCreate.MemoryUsage">
                                                                <div class="form-group col-12">
                                                                    <div
                                                                        class="custom-control custom-checkbox margin-bottom-10">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="choosenServicesToMonitor.MemoryUsage"
                                                                               ng-model="choosenServicesToMonitor.MemoryUsage">
                                                                        <label class="custom-control-label"
                                                                               for="choosenServicesToMonitor.MemoryUsage">
                                                                            <?php echo __('Memory usage'); ?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-bottom-5"
                                                                 ng-show="servicesToCreate.SwapUsage">
                                                                <div class="form-group col-12">
                                                                    <div
                                                                        class="custom-control custom-checkbox margin-bottom-10">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="choosenServicesToMonitor.SwapUsage"
                                                                               ng-model="choosenServicesToMonitor.SwapUsage">
                                                                        <label class="custom-control-label"
                                                                               for="choosenServicesToMonitor.SwapUsage">
                                                                            <?php echo __('Swap usage'); ?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.DiskIO"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.DiskIO">
                                                                    <?php echo __('Disk IO'); ?>
                                                                    ({{countObj(servicesToCreate.DiskIO)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.DiskIO"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.DiskIO"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.DiskIO"
                                                                        ng-model="choosenServicesToMonitor.DiskIO">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.DiskUsage"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.DiskUsage">
                                                                    <?php echo __('Disk usage'); ?>
                                                                    ({{countObj(servicesToCreate.DiskUsage)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.DiskUsage"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.DiskUsage"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.DiskUsage"
                                                                        ng-model="choosenServicesToMonitor.DiskUsage">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.Fan"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.Fan">
                                                                    <?php echo __('Fans'); ?>
                                                                    ({{countObj(servicesToCreate.Fan)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.Fan"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.Fan"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.Fan"
                                                                        ng-model="choosenServicesToMonitor.Fan">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.Temperature"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.Temperature">
                                                                    <?php echo __('Temperatures'); ?>
                                                                    ({{countObj(servicesToCreate.Temperature)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.Temperature"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.Temperature"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.Temperature"
                                                                        ng-model="choosenServicesToMonitor.Temperature">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-bottom-5"
                                                                 ng-show="servicesToCreate.Battery">
                                                                <div class="form-group col-12">
                                                                    <div
                                                                        class="custom-control custom-checkbox margin-bottom-10">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="choosenServicesToMonitor.Battery"
                                                                               ng-model="choosenServicesToMonitor.Battery">
                                                                        <label class="custom-control-label"
                                                                               for="choosenServicesToMonitor.Battery">
                                                                            <?php echo __('Battery'); ?>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.NetIO"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.NetIO">
                                                                    <?php echo __('Network device IO'); ?>
                                                                    ({{countObj(servicesToCreate.NetIO)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.NetIO"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.NetIO"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.NetIO"
                                                                        ng-model="choosenServicesToMonitor.NetIO">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.NetStats"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.NetStats">
                                                                    <?php echo __('Network device stats'); ?>
                                                                    ({{countObj(servicesToCreate.NetStats)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.NetStats"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.NetStats"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.NetStats"
                                                                        ng-model="choosenServicesToMonitor.NetStats">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.Process"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.Process">
                                                                    <?php echo __('Processes'); ?>
                                                                    ({{countObj(servicesToCreate.Process)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.Process"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.Process"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.Process"
                                                                        ng-model="choosenServicesToMonitor.Process">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.WindowsService"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.WindowsService">
                                                                    <?php echo __('Windows services'); ?>
                                                                    ({{countObj(servicesToCreate.WindowsService)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.WindowsService"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.WindowsService"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.WindowsService"
                                                                        ng-model="choosenServicesToMonitor.WindowsService">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.DockerContainerRunning"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.DockerContainerRunning">
                                                                    <?php echo __('Docker container running'); ?>
                                                                    ({{countObj(servicesToCreate.DockerContainerRunning)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.DockerContainerRunning"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.DockerContainerRunning"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.DockerContainerRunning"
                                                                        ng-model="choosenServicesToMonitor.DockerContainerRunning">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.DockerContainerCPU"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.DockerContainerCPU">
                                                                    <?php echo __('Docker container cpu usage'); ?>
                                                                    ({{countObj(servicesToCreate.DockerContainerCPU)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.DockerContainerCPU"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.DockerContainerCPU"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.DockerContainerCPU"
                                                                        ng-model="choosenServicesToMonitor.DockerContainerCPU">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.DockerContainerMemory"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.DockerContainerMemory">
                                                                    <?php echo __('Docker container memory usage'); ?>
                                                                    ({{countObj(servicesToCreate.DockerContainerMemory)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.DockerContainerMemory"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.DockerContainerMemory"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.DockerContainerMemory"
                                                                        ng-model="choosenServicesToMonitor.DockerContainerMemory">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.QemuVMRunning"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.QemuVMRunning">
                                                                    <?php echo __('QEMU vm running'); ?>
                                                                    ({{countObj(servicesToCreate.QemuVMRunning)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.QemuVMRunning"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.QemuVMRunning"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.QemuVMRunning"
                                                                        ng-model="choosenServicesToMonitor.QemuVMRunning">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.Customcheck"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.Customcheck">
                                                                    <?php echo __('Customchecks'); ?>
                                                                    ({{countObj(servicesToCreate.Customcheck)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                        id="choosenServicesToMonitor.Customcheck"
                                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                                        class="form-control"
                                                                        chosen="servicesToCreate.Customcheck"
                                                                        multiple
                                                                        ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.Customcheck"
                                                                        ng-model="choosenServicesToMonitor.Customcheck">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="row"
                                     ng-if="(pullMode || pushMode) && installed && configured && servicesConfigured">
                                    <div class="col-12 padding-20">
                                        <div class="margin-bottom-25">
                                            <span class="widget-icon">
                                                <i class="fa fa-magic"></i>
                                            </span>
                                            <h4 ng-show="!finished" class="margin-bottom-10"
                                                style="display: inline;"><?php echo __('Save changes'); ?></h4>
                                            <h4 ng-show="finished" class="margin-bottom-10"
                                                style="display: inline;"><?php echo __('Setup finished'); ?></h4>
                                        </div>

                                        <div class="col-12">

                                            <div class="row" style="border-bottom: none;" ng-show="!finished">
                                                <p>
                                                    <?= __('Please wait during service creation ...'); ?>
                                                </p>
                                            </div>

                                            <div class="row" style="border-bottom: none;"
                                                 ng-show="finished && serviceQueue.length > 0">
                                                <p class="col-12 padding-left-0">
                                                    <?= __('Agent services successfully created.'); ?>
                                                </p>
                                                <p class="col-12 padding-left-0">
                                                    <b><?= __('Next steps: Run an export and keep your agent running :)'); ?></b>
                                                </p>
                                            </div>
                                            <div class="row" style="border-bottom: none;"
                                                 ng-show="finished && serviceQueue.length <= 0">
                                                <p>
                                                    <?= __('No services were created.'); ?>
                                                </p>
                                            </div>
                                            <div class="row" style="border-bottom: none;"
                                                 ng-show="finished && pushMode && agentconfig['try-autossl']">
                                                <p class="col-12 padding-left-0">
                                                    <b><?= __('To activate automatic ssl certificate generation for this agent, click here to trust it: '); ?></b>
                                                    <button
                                                        type="button" style="min-height: 35px;"
                                                        class="btn btn-labeled btn-primary margin-left-10"
                                                        ui-sref="AgentconnectorsAgent({hostuuid: host.uuid})">

                                                        <?= __('Show untrusted agent'); ?>
                                                    </button>
                                                </p>
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
    </div>
</div>
</div>


<?php echo $this->element('apikey_help'); ?>

