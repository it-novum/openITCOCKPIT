<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="AgentconnectorsWizard">
            <i class="fa fa-user-secret"></i> <?php echo __('openITCOCKPIT Agent'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-tools"></i> <?php echo __('Configuration'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?= __('openITCOCKPIT Agent'); ?>
                    <span class="fw-300">
                        <i>
                            <?= __('Configuration'); ?>
                        </i>
                    </span>
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
                <div class="panel-content">
                    <div class="row">
                        <div class="form-group required col-lg-12">
                            <label class="control-label" for="HostsSelect">
                                <?php echo __('Host'); ?>
                            </label>
                            <select
                                id="HostsSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                chosen="hosts"
                                ng-options="host.key as host.value for host in hosts"
                                callback="loadHostsCallback"
                                ng-model="post.Host.id">
                            </select>
                        </div>
                    </div>
                    <div class="row padding-top-20">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 offset-lg-2 offset-xl-2">
                            <div class="panel panel-default agent-mode-box-pull">
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
                            <div class="panel panel-default agent-mode-box-push">
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
                </div>
            </div>
        </div>
    </div>
</div>
