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
        <a ui-sref="AgentconnectorsAgent">
            <i class="fa fa-user-secret"></i> <?php echo __('openITCOCKPIT Agent'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-tools"></i> <?php echo __('Wizard'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?= __('openITCOCKPIT Agent Configuration for:'); ?>
                    <span class="fw-300">
                        <i>
                            {{host.name}} ({{host.address}})
                        </i>
                    </span>
                </h2>
            </div>

            <!-- Wizard progressbar -->
            <div class="row margin-0 text-center">
                <div class="col-xs-12 col-md-4 col-lg-2 bg-success text-white">
                    <i class="fas fa-check"></i>
                    <?= __('Select host') ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-2 bg-success text-white">
                    <i class="fas fa-check"></i>
                    <?= __('Configure Agent') ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-2 bg-success text-white">
                    <i class="fas fa-check"></i>
                    <?= __('Install Agent') ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-2 bg-success text-white">
                    <i class="fas fa-check"></i>
                    <?= __('Exchange TLS Certificate') ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-2 text-white bg-primary">
                    <?= __('Create services') ?>
                </div>
                <div class="col-xs-12 col-md-4 col-lg-2 padding-left-0 padding-right-0 bg-light-gray">
                    <div class="btn-group btn-group-xs w-100">
                        <a type="button" class="btn btn-xs btn-primary waves-effect waves-themed"
                           ui-sref="AgentconnectorsAutotls({'hostId': hostId})"
                           title="<?= __('Back') ?>"
                           style="border-radius: 0; height: 22px;">
                            <i class="fa fa-arrow-left"></i>
                        </a>

                        <button type="button" class="btn btn-xs btn-success btn-block waves-effect waves-themed"
                                style="border-radius: 0;height: 22px;"
                                ng-click="submit()">
                            <?= __('Finish') ?>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- End progressbar -->

            <div class="row">
                <div class="col-12">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="card margin-top-20 padding-bottom-20">
                                <div class="card-body">
                                    <fieldset>
                                        <legend class="fs-md fieldset-legend-border-bottom margin-top-10">
                                            <h4 class="required">
                                                <i class="fa fa-cogs"></i>
                                                <?= __('Select Services to monitor'); ?>
                                            </h4>
                                        </legend>
                                        <div>
                                            <hr class="hr-text" data-content="&#xf013; <?= __('System'); ?>">
                                            <div class="form-group col-12">
                                                <div class="custom-control custom-checkbox margin-bottom-10">
                                                    <input type="checkbox"
                                                           ng-if="services.memory"
                                                           class="custom-control-input"
                                                           id="memory"
                                                           ng-model="services.memory">
                                                    <label class="custom-control-label"
                                                           for="memory">
                                                        <?php echo __('Memory usage'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-12">
                                                <div class="custom-control custom-checkbox margin-bottom-10">
                                                    <input type="checkbox"
                                                           ng-if="services.swap"
                                                           class="custom-control-input"
                                                           id="swap"
                                                           ng-model="services.swap">
                                                    <label class="custom-control-label"
                                                           for="swap">
                                                        <?php echo __('Swap usage'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-12">
                                                <div class="custom-control custom-checkbox margin-bottom-10">
                                                    <input type="checkbox"
                                                           ng-if="services.system_load"
                                                           class="custom-control-input"
                                                           id="system_load"
                                                           ng-model="services.system_load">
                                                    <label class="custom-control-label"
                                                           for="system_load">
                                                        <?php echo __('System load'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <hr class="hr-text" data-content="&#xf0a0; <?= __('Disk information'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Disk IO'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Disks'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="hr-text" data-content="&#xf0e8; <?= __('Networking'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Net IO'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?= __('Net stats'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="hr-text-brands" data-content="&#xf395; <?= __('Docker'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Docker stats'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="hr-text-brands" data-content="&#xf17a; <?= __('Windows'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Windows services'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="hr-text-brands" data-content="&#xf179; <?= __('macOS'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Launchd services'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="hr-text-brands" data-content="&#xf17c; <?= __('Linux'); ?>">
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Processes'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12 padding-left-0 ">
                                                <label class="col-12 control-label"
                                                       for="enable_push_mode">
                                                    <?php echo __('Systemd services'); ?>
                                                </label>
                                                <div class="col-12">
                                                    <select
                                                        id="enable_push_mode"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        chosen="{}"
                                                        ng-model="config.bool.enable_push_mode">
                                                        <option ng-value="false"><?= __('Pull mode'); ?></option>
                                                        <option ng-value="true"><?= __('Push mode'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
