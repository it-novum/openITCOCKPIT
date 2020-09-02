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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="WizardsIndex">
            <i class="fas fa-magic"></i> <?php echo __('Wizards'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-magic"></i> <?php echo __('Linux Server'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration Wizard: Linux Server'); ?>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">

                    <form ng-submit="submit();" class="form-horizontal">

                        <div class="wizard">
                            <ul class="nav nav-tabs step-anchor">
                                <li class="active">
                                    <span class="badge badge-info">1</span><?php echo __('Linux Server Information'); ?>
                                    <span class="chevron"></span>
                                </li>
                                <li>
                                    <span class="badge">2</span><?php echo __('Contact configuration'); ?>
                                    <span class="chevron"></span>
                                </li>
                                <li>
                                    <span class="badge">3</span><?php echo __('Configuration overview'); ?>
                                    <span class="chevron"></span>
                                </li>
                            </ul>
                            <div class="pull-right margin-right-5" style="margin-top: -39px;">
                                <div class="actions" style="position: relative; display: inline;">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <?php echo __('Next'); ?><i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="step-content">
                            <div class="step-pane active padding-20" id="step1">
                                <h6 class="txt-color-blueDark"><?php echo __('Main server configuration'); ?></h6>
                                <!-- wizard form starts here -->
                                <fieldset class="col col-12">
                                    <div class="form-group">
                                        <label class="col col-2 control-label">
                                            <?php echo __('Operation Systems'); ?>
                                        </label>
                                        <div class="col col-12">
                                            <select class="form-control"
                                                    ng-model="post.Linux.operating_system"
                                                    chosen="{}">
                                                <option value="ubuntu"><?php echo __('Ubuntu'); ?></option>
                                                <option value="debian"><?php echo __('Debian'); ?></option>
                                                <option value="centos"><?php echo __('CentOS'); ?></option>
                                                <option value="redhat"><?php echo __('RedHat'); ?></option>
                                                <option value="fedora"><?php echo __('Fedora'); ?></option>
                                                <option value="opensuse"><?php echo __('openSuse'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="animate-switch-container"
                                     ng-switch on="post.Linux.operating_system">
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width ubuntu-fieldset"
                                              ng-switch-when="ubuntu">
                                        <legend class="ubuntu-text padding-10">
                                            <span class="fl-ubuntu ubuntu-text"></span>
                                            <?php echo __('Ubuntu'); ?>
                                        </legend>
                                    </fieldset>
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width debian-fieldset"
                                              ng-switch-when="debian">
                                        <legend class="debian-text padding-10">
                                            <span class="fl-debian debian-text"></span>
                                            <?php echo __('Debian'); ?>
                                        </legend>
                                    </fieldset>
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width centos-fieldset"
                                              ng-switch-when="centos">
                                        <legend class="centos-text padding-10">
                                            <span class="fl-centos centos-text"></span>
                                            <?php echo __('CentOS'); ?>
                                        </legend>
                                    </fieldset>
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width redhat-fieldset"
                                              ng-switch-when="redhat">
                                        <legend class="redhat-text padding-10">
                                            <span class="fl-redhat redhat-text"></span>
                                            <?php echo __('RedHat'); ?>
                                        </legend>
                                    </fieldset>
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width fedora-fieldset"
                                              ng-switch-when="fedora">
                                        <legend class="fedora-text padding-10">
                                            <span class="fl-fedora fedora-text"></span>
                                            <?php echo __('Fedora'); ?>
                                        </legend>
                                    </fieldset>
                                    <fieldset class="col col-12 margin-top-20 margin-left-20 wizard-fieldset-legend-auto-width opensuse-fieldset"
                                              ng-switch-when="opensuse">
                                        <legend class="opensuse-text padding-10">
                                            <span class="fl-opensuse opensuse-text"></span>
                                            <?php echo __('openSuse'); ?>
                                        </legend>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>














