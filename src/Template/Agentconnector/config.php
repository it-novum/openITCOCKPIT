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
    <div class="col-lg-8 margin-bottom-10">
        <div class="input-group">
            <select
                    id="AgentHost"
                    data-placeholder="<?php echo __('Please select...'); ?>"
                    class="form-control"
                    chosen="hosts"
                    ng-disabled="disableHostSelect"
                    ng-options="host.key as host.value for host in hosts"
                    ng-model="host.id">
            </select>


            <div class="input-group-append">
                <button class="btn btn-danger btn-sm waves-effect waves-themed"
                        type="button"
                        ng-if="(host.id && !servicesConfigured) || finished"
                        ng-click="resetAgentConfiguration()">
                    <i class="fas fa-undo"></i>&nbsp;
                    <?php echo __('Reset'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


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
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">
                    <div class="wizard">
                        <ul class="nav nav-tabs step-anchor">
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
                        </ul>

                        <div class="pull-right margin-right-5" style="margin-top: -39px;">
                            <div class="actions" style="position: relative; display: inline;">

                                <!-- 1st step next button -->
                                <button type="button" class="btn btn-sm btn-success"
                                        ng-if="(!pullMode && !pushMode && host.id && servicesToCreate)"
                                        data-target="#step5"
                                        ng-click="skipConfigurationGeneration()">
                                    <?= __('Next'); ?>
                                    <i class="fa fa-arrow-right"></i>
                                </button>

                                <!-- 2nd step next button -->
                                <button type="button" class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && !installed && !configured)"
                                        data-target="#step5"
                                        ng-click="continueWithAgentInstallation()">
                                    <?= __('Save configuration'); ?>
                                    <i class="fa fa-arrow-right"></i>
                                </button>

                                <!-- 3rd step next button -->
                                <button type="button" class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && !installed && configured)"
                                        data-target="#step5"
                                        ng-click="continueWithServiceConfiguration()">
                                    <?= __('I have installed the Agent'); ?>
                                    <i class="fa fa-arrow-right"></i>
                                </button>

                                <!-- 4th step next button -->
                                <button type="button" class="btn btn-sm btn-success"
                                        ng-if="((pullMode || pushMode) && installed && configured && !servicesConfigured)"
                                        data-target="#step5"
                                        ng-click="saveAgentServices()">
                                    <?= __('Create Services'); ?>
                                    <i class="fa fa-arrow-right"></i>
                                </button>

                            </div>
                        </div>

                    </div>

                    <div class="step-content">

                        <div class="row" ng-show="showLoadServicesToCreate">
                            <div class="col-12">
                                <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                    <div class="d-flex align-items-center">
                                        <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-info-600"></i>
                                                <i class="fas fa-search icon-stack-1x text-white"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <span class="h5 color-info-600">
                                                <?= __('Checking if openITCOCKPIT Monitoring Agent is already configured...'); ?>
                                            </span>
                                            <div class="progress mt-1 progress-xs">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info-600"
                                                     role="progressbar" style="width: 100%" aria-valuenow="100"
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-if="!pullMode && !pushMode && host.id && servicesToCreate && !showLoadServicesToCreate">
                            <div class="col-12">
                                <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                    <div class="d-flex align-items-center">
                                        <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-success-600"></i>
                                                <i class="fas fa-check icon-stack-1x text-white"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <span class="h5 color-success-600">
                                                <?= __('openITCOCKPIT Monitoring Agent already configured'); ?>
                                            </span>
                                            <br>
                                            <?= __('The openITCOCKPIT Agent has already been configured on this host.'); ?>
                                        </div>
                                        <button class="btn btn-outline-success btn-sm btn-w-m waves-effect waves-themed"
                                                ng-click="skipConfigurationGeneration()">
                                            <?= __('Create new services'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-if="!pullMode && !pushMode && host.id && !servicesToCreate && !showLoadServicesToCreate && servicesToCreateError != ''">
                            <div class="col-12">
                                <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                    <div class="d-flex align-items-center">
                                        <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-warning-600"></i>
                                                <i class="fas fa-warning icon-stack-1x text-white"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <span class="h5 color-warning-600">
                                                <?= __('openITCOCKPIT Monitoring Agent already configured'); ?>
                                            </span>
                                            <br>
                                            <?= __('It seems the openITCOCKPIT Agent has been configured, but it´s not possible to get current data from it.'); ?>
                                            <br>
                                            <?= __('A misconfiguration may have occurred. Please check this manually or reconfigure it.'); ?>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm btn-w-m waves-effect waves-themed"
                                                ui-sref="AgentconnectorsAgent({hostuuid: host.uuid, selection: couldBePullModeWithError ? 'pullConfigurations' : null})">
                                            <?= __('Open agent overview'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-show="remoteAgentConfig && pullMode && !installed && !configured">
                            <div class="col-12">
                                <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                    <div class="d-flex align-items-center">
                                        <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-warning-600"></i>
                                                <i class="fas fa-exclamation-triangle icon-stack-1x text-white"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <span class="h5 color-warning-600">
                                                <?= __('Please notice before changing Agent configuration'); ?>
                                            </span>
                                            <br>
                                            <?= __('If you have made some configuration changes below, you should run a remote configuration update now. Otherwise openITCOCKPIT is maybe unable to connect to the agent again through the HTTP API. In this case you have to copy the agent configuration.'); ?>
                                        </div>
                                        <button class="btn btn-outline-warning btn-sm btn-w-m waves-effect waves-themed"
                                                ng-click="runRemoteConfigUpdate()">
                                            <?= __('Execute remote configuration update'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form ng-submit="submit();" class="form-horizontal">

                            <!-- 1st Step -->
                            <div class="row padding-top-20" ng-if="!pullMode && !pushMode && host.id">

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 offset-lg-2 offset-xl-2">
                                    <div class="panel panel-default">
                                        <div class="panel-hrd padding-left-20 padding-right-20 padding-top-20">
                                            <h4>
                                                <?= __('Register Agent in pull mode'); ?>
                                            </h4>
                                            <hr/>
                                        </div>

                                        <div class="panel-container padding-left-20 padding-right-20"
                                             style="min-height: 180px;">
                                            <h5><?= __('When to use pull mode?'); ?></h5>
                                            <div class="text">
                                                <ul>
                                                    <li><?= __('In pull mode the openITCOCKPIT server will frequently connect to the agent via an HTTP/S connection to get the latest check results.'); ?></li>

                                                    <li><?= __('Use the pull mode when your openITCOCKPIT server can establish a direct connection to the target system.'); ?></li>

                                                    <li><?= __('If your openITCOCKPIT server is located in the same datacenter.'); ?></li>

                                                </ul>
                                            </div>
                                        </div>

                                        <div class="panel-footer padding-20">
                                            <div class="col-xs-12 padding-right-0">
                                                <button
                                                        type="button"
                                                        class="btn btn-outline-primary pull-right"
                                                        ng-click="continueWithPullMode()">

                                                    <?= __('Continue with pull mode'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="panel panel-default">

                                        <div class="panel-hrd padding-left-20 padding-right-20 padding-top-20">
                                            <h4>
                                                <?= __('Register Agent in push mode'); ?>
                                            </h4>
                                            <hr/>
                                        </div>

                                        <div class="panel-container padding-left-20 padding-right-20"
                                             style="min-height: 180px;">
                                            <h5><?= __('When to use push mode?'); ?></h5>
                                            <div class="text">
                                                <ul>
                                                    <li><?= __('In push mode the Agent will frequently push the latest check results to the openITCOCKPIT server via an HTTPS connection on port 443.'); ?></li>

                                                    <li><?= __('Use the push mode whenever your openITCOCKPIT server cannot establish a direct connection the the target system.'); ?></li>

                                                    <li><?= __('In a fast changing environment or when your openITCOCKPIT server is running in the cloud.'); ?></li>

                                                </ul>
                                            </div>
                                        </div>

                                        <div class="panel-footer padding-20">
                                            <div class="col-xs-12 padding-right-0">
                                                <button
                                                        type="button"
                                                        class="btn btn-outline-primary pull-right"
                                                        ng-click="continueWithPushMode()">

                                                    <?= __('Continue with push mode'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- 1st Step End -->


                            <!-- 2nd Step -->
                            <div class="row" ng-if="(pullMode || pushMode) && !installed && !configured">
                                <div class="col-12">

                                    <div class="row" ng-if="!servicesToCreate">
                                        <div class="col-12">
                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                <div class="d-flex align-items-center">
                                                    <div class="alert-icon">
                                                        <span class="icon-stack icon-stack-md">
                                                            <i class="base-7 icon-stack-3x color-info-600"></i>
                                                            <i class="fa fa-user-secret icon-stack-1x text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <span class="h5 color-info-600">
                                                            <?= __('openITCOCKPIT Monitoring Agent not installed yet?'); ?>
                                                        </span>
                                                        <br>
                                                        <?= __('No problem! You will get detailed installation instructions in the next step.'); ?>

                                                        <span ng-show="pullMode">
                                                            <br>
                                                            <br>
                                                            <b><?= __('You selected Pull Mode. If you are new to openITCOCKPIT we recommend to continue with the default settings.'); ?></b>
                                                        </span>

                                                        <span ng-show="pushMode">
                                                            <br>
                                                            <br>
                                                            <b><?= __('You selected Push Mode. It is required to set the openITCOCKPIT Server Address and an API Key.'); ?></b>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-server"></i>
                                                    <?= __('Operating system '); ?>
                                                </div>

                                                <div class="card-body">

                                                    <?= __('Which operating system do you want to monitor?'); ?>
                                                    <br>

                                                    <div class="btn-group btn-group-lg">
                                                        <button type="button"
                                                                class="btn btn-outline-primary waves-effect waves-themed"
                                                                ng-class="{'btn-primary text-white': selectedOs === 'windows'}"
                                                                ng-click="changeOs('windows')">
                                                            <i class="fab fa-windows font-size-90"></i>
                                                            <br>
                                                            <?= __('Windows'); ?>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-primary waves-effect waves-themed"
                                                                ng-class="{'btn-primary text-white': selectedOs === 'linux'}"
                                                                ng-click="changeOs('linux')">
                                                            <i class="fab fa-linux font-size-90"></i>
                                                            <br>
                                                            <?= __('Linux'); ?>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-primary waves-effect waves-themed"
                                                                ng-class="{'btn-primary text-white': selectedOs === 'macos'}"
                                                                ng-click="changeOs('macos')">
                                                            <i class="fab fa-apple font-size-90"></i>
                                                            <br>
                                                            <?= __('macOS'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                                            <form class="form-horizontal">

                                                <div class="card margin-bottom-10">
                                                    <div class="card-header">
                                                        <i class="fa fa-magic"></i>
                                                        <?= __('Basic configuration '); ?>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group col-12 padding-left-0 required"
                                                             ng-if="pushMode">
                                                            <label class="col-xs-12 col-md-9 control-label"
                                                                   for="agentconfig['oitc-url']">
                                                                <?php echo __('openITCOCKPIT Server Address'); ?>
                                                            </label>

                                                            <div class="col-xs-12 col-md-9"
                                                                 ng-init="agentconfig['oitc-url']='https://<?= h($_SERVER['SERVER_ADDR']) ?>'">
                                                                <input
                                                                        id="agentconfig['oitc-url']"
                                                                        class="form-control"
                                                                        type="text"
                                                                        placeholder="<?php echo __('External address or FQDN (example: https://{0})', $_SERVER['SERVER_ADDR']); ?>"
                                                                        ng-model="agentconfig['oitc-url']">
                                                                <div class="help-block">
                                                                    <?= __('External address of your openITCOCKPIT Server.'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0 required"
                                                             ng-if="pushMode">
                                                            <label class="col-xs-12 col-md-9 control-label"
                                                                   for="agentconfig['oitc-apikey']">
                                                                <?php echo __('openITCOCKPIT Api-Key'); ?>
                                                            </label>

                                                            <div class="col-xs-12 col-md-9">
                                                                <input
                                                                        id="agentconfig['oitc-apikey']"
                                                                        class="form-control"
                                                                        type="text"
                                                                        placeholder="b803b7fb76524e1514bed81cf3a936845cc160511a1c0d51672c..."
                                                                        ng-model="agentconfig['oitc-apikey']">
                                                                <div class="help-block">
                                                                    <?php echo __('You need to create an openITCOCKPIT user defined API key first.'); ?>
                                                                    <a href="javascript:void(0);"
                                                                       data-toggle="modal"
                                                                       data-target="#ApiKeyOverviewModal">
                                                                        <?= __('Click here for help') ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0 required">
                                                            <label class="col-xs-12 col-md-9 control-label"
                                                                   for="agentconfig.address">
                                                                <?php echo __('Agent bind address'); ?>
                                                            </label>

                                                            <div class="col-xs-12 col-md-9">
                                                                <input
                                                                        id="agentconfig.address"
                                                                        class="form-control"
                                                                        type="text"
                                                                        placeholder="<?php echo __('Address or FQDN'); ?>"
                                                                        ng-model="agentconfig.address">

                                                                <div class="help-block">
                                                                    <?= __('IP address that openITCOCKPIT Agent should bind to.'); ?>
                                                                    <?= __('Set 0.0.0.0 to bind to all interfaces.'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0 required">
                                                            <label class="col-xs-12 col-md-9 control-label"
                                                                   for="agentconfig.port">
                                                                <?php echo __('Agent bind port'); ?>
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

                                                                <div class="help-block">
                                                                    <?= __('Port number that openITCOCKPIT Agent should bind to.'); ?>
                                                                    <?= __('Default: 3333'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0 required">
                                                            <label class="col-xs-12 col-md-9 control-label"
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

                                                                <div class="help-block">
                                                                    <?= __('Determines in seconds how often the openITCOCKPIT Agent will execute all checks.'); ?>
                                                                    <?= __('Default: 30'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12" ng-show="pullMode">
                                                            <div
                                                                    class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.proxy"
                                                                       ng-model="agentconfig.proxy">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.proxy">
                                                                    <?php echo __('Use Proxy'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php
                                                                    if ($this->Acl->hasPermission('index', 'proxy', '')):
                                                                        echo __('Determine if the <a href="/#!/proxy/index">configured proxy</a> should be used.');
                                                                    else:
                                                                        echo __('Determine if the configured proxy should be used.');
                                                                    endif;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="card margin-bottom-10">
                                                    <div class="card-header">
                                                        <i class="fas fa-shield-alt"></i>
                                                        <?= __('Security configuration '); ?>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group col-12">
                                                            <div
                                                                    class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.try-autossl"
                                                                       ng-model="agentconfig['try-autossl']">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.try-autossl">
                                                                    <?php echo __('Enable Auto-TLS'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('If enabled, the Agent tries to automatically generate a TLS certificate for all incoming connections.'); ?>
                                                                    <br>
                                                                    <?php echo __('Pull mode: The certificate (including updates) will be transferred from openITCOCKPIT Server to the Agent.'); ?>
                                                                    <br>
                                                                    <?php echo __('Push mode: The certificate (including updates) will be requested from the Agent. It has to be trusted manually to get the certificate.'); ?>
                                                                </div>
                                                                <div class="help-block text-danger">
                                                                    <?= __('For security response we highly recommend to enable Auto-TLS! Otherwise the communication will be plaintext.'); ?>
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
                                                                    <?php echo __('Enable debug output'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('Print debug information on CLI'); ?>
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
                                                                    <?php echo __('Print extended debug information including stacktraces on CLI.'); ?>
                                                                </div>
                                                                <div class="help-block text-danger">
                                                                    <?= __('Warning: This setting is most likely only interesting for developers and could leak sensitive information.'); ?>
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
                                                                    <p class="text-danger">
                                                                        <?php echo __('Warning: Remote code execution is possible if the certificate was stolen or no ssl was configured.'); ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12 padding-left-0">
                                                            <label class="col-xs-12 col-md-9 control-label"
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
                                                    </div>

                                                </div>

                                                <div class="card margin-bottom-10">
                                                    <div class="card-header">
                                                        <i class="fa fa-terminal"></i>
                                                        <?= __('Check configuration '); ?>
                                                    </div>

                                                    <div class="card-body">

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
                                                             ng-show="agentconfig.customchecks">
                                                            <label class="col-xs-12 col-md-9 control-label"
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

                                                        <div class="form-group col-12">
                                                            <div
                                                                    class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.systemdservices"
                                                                       ng-model="agentconfig.systemdservices">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.systemdservices">
                                                                    <?php echo __('Enable systemd services status check'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                    class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.wineventlog"
                                                                       ng-model="agentconfig.wineventlog">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.wineventlog">
                                                                    <?php echo __('Enable windows event log check'); ?>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12">
                                                            <div
                                                                    class="custom-control custom-checkbox margin-bottom-10">
                                                                <input type="checkbox"
                                                                       class="custom-control-input"
                                                                       id="agentconfig.alfrescostats"
                                                                       ng-model="agentconfig.alfrescostats">
                                                                <label class="custom-control-label"
                                                                       for="agentconfig.alfrescostats">
                                                                    <?php echo __('Enable Alfresco status checks'); ?>
                                                                </label>
                                                                <div class="help-block">
                                                                    <?php echo __('If you have an Alfresco enterprise instance, JMX is configured and java installed on the agent host system, you can enable alfrescostats'); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-12"
                                                             style="padding-left: 1.75rem;"
                                                             ng-show="agentconfig.alfrescostats">
                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxuser']">
                                                                    <?php echo __('Alfresco JMX username'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxuser']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            placeholder="<?php echo 'monitorRole'; ?>"
                                                                            ng-model="agentconfig['alfresco-jmxuser']">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxpassword']">
                                                                    <?php echo __('Alfresco JMX password'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxpassword']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            placeholder="<?php echo 'change_asap'; ?>"
                                                                            ng-model="agentconfig['alfresco-jmxpassword']">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxaddress']">
                                                                    <?php echo __('Alfresco host address'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxaddress']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            placeholder="<?php echo '0.0.0.0'; ?>"
                                                                            ng-model="agentconfig['alfresco-jmxaddress']">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxport']">
                                                                    <?php echo __('Alfresco JMX port'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxport']"
                                                                            class="form-control"
                                                                            type="number"
                                                                            min="1"
                                                                            ng-model="agentconfig['alfresco-jmxport']">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxpath']">
                                                                    <?php echo __('Alfresco JMX path'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxpath']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            placeholder="<?php echo '/alfresco/jmxrmi'; ?>"
                                                                            ng-model="agentconfig['alfresco-jmxpath']">
                                                                    <div class="help-block">
                                                                        <?php echo __('The path behind the JMX address (service:jmx:rmi:///jndi/rmi://0.0.0.0:50500), e.g. "/alfresco/jmxrmi"'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-jmxquery']">
                                                                    <?php echo __('Alfresco JMX query'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-jmxquery']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            ng-model="agentconfig['alfresco-jmxquery']">
                                                                    <div class="help-block">
                                                                        <?php echo __('Set you custom Alfresco JMX query. Leave empty to use the default.'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-12 padding-left-0">
                                                                <label class="col-12 control-label"
                                                                       for="agentconfig['alfresco-javapath']">
                                                                    <?php echo __('Path to the java binary'); ?>
                                                                </label>

                                                                <div class="col-xs-12 col-md-9">
                                                                    <input
                                                                            id="agentconfig['alfresco-javapath']"
                                                                            class="form-control"
                                                                            type="text"
                                                                            placeholder="<?php echo '/usr/bin/java'; ?>"
                                                                            ng-model="agentconfig['alfresco-javapath']">
                                                                    <div class="help-block">
                                                                        <?php echo __('Java need to be installed on agent host system if you want to use alfrescostats.'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="far fa-file-code"></i>
                                                    <?= __('config.cnf'); ?>
                                                </div>

                                                <div class="card-body">
                                                    <textarea class="form-control"
                                                              readonly
                                                              ng-model="configTemplate"
                                                              style="min-height: 580px; width: 100%;"></textarea>
                                                </div>
                                            </div>

                                            <div class="card margin-bottom-10" ng-if="agentconfig.customchecks">
                                                <div class="card-header">
                                                    <i class="far fa-file-code"></i>
                                                    <?= __('customchecks.cnf'); ?>
                                                </div>

                                                <div class="card-body">
                                                    <textarea class="form-control"
                                                              readonly
                                                              ng-model="configTemplateCustomchecks"
                                                              style="min-height: 580px; width: 100%;"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div> <!-- row end -->


                                </div> <!-- col-12 end -->
                            </div>
                            <!-- 2nd Step End -->

                            <!-- 3rd Step -->
                            <div class="row padding-top-20" ng-if="(pullMode || pushMode) && !installed && configured">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-download"></i>
                                                    <?= __('Download and install openITCOCKPIT Monitoring Agent'); ?>
                                                </div>

                                                <div class="card-body">

                                                    <div class="row">

                                                        <div class="col-12">
                                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="alert-icon">
                                                                        <span class="icon-stack icon-stack-md">
                                                                            <i class="base-7 icon-stack-3x color-info-600"></i>
                                                                            <i class="fas fa-download icon-stack-1x text-white"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <span class="h5 color-info-600">
                                                                            <?= __('Download and install the openITCOCKPIT Monitoring Agent.'); ?>
                                                                        </span>
                                                                        <br>
                                                                        <?= __('If not already done, please {0} the openITCOCKPIT Agent now.', '<a href="https://openitcockpit.io/download_agent" target="_blank">' . __('download and install') . '</a>'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-file-code"></i>
                                                    <?= __('Copy configuration file'); ?>
                                                </div>

                                                <div class="card-body">

                                                    <div class="row">

                                                        <div class="col-12 padding-bottom-10">
                                                            <?= __('After the installation process is completed you should replace the default openITCOCKPIT Agent configuration with the recently generated configuration.'); ?>
                                                            <br>
                                                            <?= __('Copy and paste the shown configuration file to'); ?>
                                                            <code ng-show="selectedOs === 'windows'"><?= __('C:\Program Files\it-novum\openitcockpit-agent\config.cnf'); ?></code>
                                                            <code ng-show="selectedOs === 'linux'"><?= __('/etc/openitcockpit-agent/config.cnf'); ?></code>
                                                            <code ng-show="selectedOs === 'macos'"><?= __('/Applications/openitcockpit-agent/config.cnf'); ?></code>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 padding-bottom-10">
                                                            <b><?= __('config.cnf:'); ?></b>
                                                            <br>
                                                            <textarea class="form-control"
                                                                      readonly
                                                                      ng-model="configTemplate"
                                                                      style="min-height: 580px; width: 100%;"></textarea>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 padding-bottom-10"
                                                             ng-if="agentconfig.customchecks">
                                                            <b><?= __('customchecks.cnf:'); ?></b>
                                                            <br>
                                                            <textarea class="form-control"
                                                                      readonly
                                                                      ng-model="configTemplateCustomchecks"
                                                                      style="min-height: 580px; width: 100%;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-undo"></i>
                                                    <?= __('Restart openITCOCKPIT Agent'); ?>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row padding-bottom-10">
                                                        <div class="col-12 padding-bottom-10">
                                                            <?= __('To enable the new configuration a restart of the openITCOCKPIT Agent is required.'); ?>
                                                        </div>

                                                        <div class="col-12" ng-show="selectedOs === 'windows'">
                                                            <?= __('Run as administrator (via cmd.exe)'); ?>
                                                            <code><?= __('sc stop oitcAgentSvc && sc start oitcAgentSvc'); ?></code>
                                                        </div>

                                                        <div class="col-12" ng-show="selectedOs === 'linux'">
                                                            <code><?= __('sudo systemctl restart openitcockpit-agent.service'); ?></code>
                                                        </div>

                                                        <div class="col-12" ng-show="selectedOs === 'macos'">
                                                            <code><?= __('sudo /bin/launchctl stop com.it-novum.openitcockpit.agent'); ?></code>
                                                            <br>
                                                            <code><?= __('sudo /bin/launchctl start com.it-novum.openitcockpit.agent'); ?></code>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- 3rd Step End -->

                            <!-- 4th Step -->
                            <div class="row padding-top-20"
                                 ng-if="(pullMode || pushMode) && installed && configured && !servicesConfigured">
                                <div class="col-12">

                                    <div class="row" ng-hide="servicesToCreate">
                                        <div class="col-12">
                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                <div class="d-flex align-items-center">
                                                    <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-info-600"></i>
                                                <i class="fas fa-search icon-stack-1x text-white"></i>
                                            </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <span class="h5 color-info-600">
                                                            <?= __('Waiting for results...'); ?>
                                                        </span>
                                                        <br>
                                                        <?= __('If you see this message for more than 2 minutes please make sure the openITCOCKPIT Agent is running and configured.'); ?>
                                                        <br>
                                                        <div class="progress mt-1 progress-xs">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info-600"
                                                                 role="progressbar" style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"
                                         ng-hide="servicesToCreate || !servicesToCreateError || servicesToCreateError == ''">
                                        <div class="col-12">
                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                <div class="d-flex align-items-center">
                                                    <div class="alert-icon">
                                            <span class="icon-stack icon-stack-md">
                                                <i class="base-7 icon-stack-3x color-danger-600"></i>
                                                <i class="fas fa-exclamation-triangle icon-stack-1x text-white"></i>
                                            </span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <span class="h5 color-danger-600">
                                                            <?= __('An error occurred'); ?>
                                                        </span>
                                                        <br>
                                                        {{servicesToCreateError}}
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-check-square"></i>
                                                    <?= __('Select the services you like to monitor'); ?>
                                                </div>

                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-12">
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

                                                            <div ng-show="servicesToCreate.SystemdService"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.SystemdService">
                                                                    <?php echo __('Systemd services'); ?>
                                                                    ({{countObj(servicesToCreate.SystemdService)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                            id="choosenServicesToMonitor.SystemdService"
                                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                                            class="form-control"
                                                                            chosen="servicesToCreate.SystemdService"
                                                                            multiple
                                                                            ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.SystemdService"
                                                                            ng-model="choosenServicesToMonitor.SystemdService">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.WindowsEventlog"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.WindowsEventlog">
                                                                    <?php echo __('Windows event log'); ?>
                                                                    ({{countObj(servicesToCreate.WindowsEventlog)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                            id="choosenServicesToMonitor.WindowsEventlog"
                                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                                            class="form-control"
                                                                            chosen="servicesToCreate.WindowsEventlog"
                                                                            multiple
                                                                            ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.WindowsEventlog"
                                                                            ng-model="choosenServicesToMonitor.WindowsEventlog">
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div ng-show="servicesToCreate.Alfresco"
                                                                 class="form-group col-12 padding-left-0 margin-bottom-5">
                                                                <label class="control-label"
                                                                       for="choosenServicesToMonitor.Alfresco">
                                                                    <?php echo __('Alfresco checks'); ?>
                                                                    ({{countObj(servicesToCreate.Alfresco)}})
                                                                </label>
                                                                <div class="col-xs-12 col-lg-6 padding-left-0">
                                                                    <select
                                                                            id="choosenServicesToMonitor.Alfresco"
                                                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                                                            class="form-control"
                                                                            chosen="servicesToCreate.Alfresco"
                                                                            multiple
                                                                            ng-options="key as value.agent_wizard_option_description for (key, value) in servicesToCreate.Alfresco"
                                                                            ng-model="choosenServicesToMonitor.Alfresco">
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
                            </div>
                            <!-- 4th Step End -->

                            <!-- 5th Step -->
                            <div class="row padding-top-20"
                                 ng-if="(pullMode || pushMode) && installed && configured && servicesConfigured">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card margin-bottom-10">
                                                <div class="card-header">
                                                    <i class="fas fa-magic"></i>
                                                    <?= __('Agent setup done'); ?>
                                                </div>

                                                <div class="card-body">

                                                    <div class="row" ng-show="!finished">
                                                        <div class="col-12">
                                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="alert-icon">
                                                                        <span class="icon-stack icon-stack-md">
                                                                            <i class="base-7 icon-stack-3x color-info-600"></i>
                                                                            <i class="fas fa-hourglass-start icon-stack-1x text-white"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <span class="h5 color-info-600">
                                                                            <?= __('Creating new services...'); ?>
                                                                        </span>
                                                                        <br>
                                                                        <div class="progress mt-1 progress-xs">
                                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info-600"
                                                                                 role="progressbar" style="width: 100%"
                                                                                 aria-valuenow="100"
                                                                                 aria-valuemin="0"
                                                                                 aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="finished && serviceQueue.length <= 0">
                                                        <div class="col-12">
                                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="alert-icon">
                                                                        <span class="icon-stack icon-stack-md">
                                                                            <i class="base-7 icon-stack-3x color-info-600"></i>
                                                                            <i class="fas fa-info icon-stack-1x text-white"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <span class="h5 color-info-600">
                                                                            <?= __('No services where created.'); ?>
                                                                        </span>
                                                                        <br>
                                                                        <?= __('Did you choose any services?'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" ng-show="finished && serviceQueue.length > 0">
                                                        <div class="col-12">
                                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="alert-icon">
                                                                        <span class="icon-stack icon-stack-md">
                                                                            <i class="base-7 icon-stack-3x color-success-600"></i>
                                                                            <i class="fas fa-check icon-stack-1x text-white"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <span class="h5 color-success-600">
                                                                            <?= __('Services created successfully!'); ?>
                                                                        </span>
                                                                        <br>
                                                                        <?= __('To apply the changes, please refresh your monitoring configuration.'); ?>
                                                                    </div>
                                                                    <?php if ($this->Acl->hasPermission('index', 'exports')): ?>
                                                                        <a class="btn btn-outline-success btn-sm btn-w-m waves-effect waves-themed"
                                                                           ui-sref="ExportsIndex">
                                                                            <i class="fa fa-retweet"></i>
                                                                            <?= __('Go to "Refresh monitoring configuration"'); ?>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row"
                                                         ng-show="finished && pushMode && agentconfig['try-autossl']">
                                                        <div class="col-12">
                                                            <div class="alert border-faded bg-transparent text-secondary margin-top-20">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="alert-icon">
                                                                        <span class="icon-stack icon-stack-md">
                                                                            <i class="base-7 icon-stack-3x color-warning-600"></i>
                                                                            <i class="fas fa-shield-alt icon-stack-1x text-white"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <span class="h5 color-warning-600">
                                                                            <?= __('Enable automatic TLS certificate generation.'); ?>
                                                                        </span>
                                                                        <br>
                                                                        <?= __('To enable an encrypted connection you need to mark the Agent as trusted.'); ?>
                                                                    </div>
                                                                    <a class="btn btn-outline-warning btn-sm btn-w-m waves-effect waves-themed"
                                                                       ui-sref="AgentconnectorsAgent({hostuuid: host.uuid})">
                                                                        <?= __('Show untrusted Agents'); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- 5th Step End -->

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

