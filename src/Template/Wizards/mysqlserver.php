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
        <i class="fas fa-magic"></i> <?php echo __('MySQL'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration Wizard: MySQL Server'); ?>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">

                    <form ng-submit="submit();" class="form-horizontal">

                        <div class="wizard">
                            <ul class="nav nav-tabs step-anchor">
                                <li class="active">
                                    <span class="badge badge-info">
                                        <i class="fa fa-info"></i>
                                    </span>
                                    <?php echo __('MySQL Information'); ?>
                                    <span class="chevron"></span>
                                </li>
                            </ul>
                        </div>


                        <div class="step-content">
                            <div class="card margin-top-20 margin-bottom-10">
                                <div class="card-body">
                                    <fieldset class="padding-bottom-20">
                                        <legend class="fs-md fieldset-legend-border-bottom">
                                            <h4>
                                                <?= __('MySQL Server'); ?>
                                            </h4>
                                        </legend>
                                        <div class="form-group required" ng-class="{'has-error': errors.username}">
                                            <label class="control-label">
                                                <?php echo __('Username'); ?>
                                            </label>
                                            <input
                                                id="UserName"
                                                class="form-control"
                                                type="text"
                                                ng-model="post.username">
                                            <div ng-repeat="error in errors.username">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                        <div class="form-group required" ng-class="{'has-error': errors.password}">
                                            <label class="control-label">
                                                <?php echo __('Password'); ?>
                                            </label>
                                            <input
                                                id="UserName"
                                                class="form-control"
                                                type="password"
                                                ng-model="post.password">
                                            <div ng-repeat="error in errors.password">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">
                                                <?php echo __('Database'); ?>
                                            </label>
                                            <input
                                                id="UserName"
                                                class="form-control"
                                                type="text"
                                                ng-model="post.database">
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend class="fs-md fieldset-legend-border-bottom">
                                            <h4>
                                                <?= __('MySQL services'); ?>
                                            </h4>
                                        </legend>
                                        <ul class="no-padding">
                                            <ol class="padding-bottom-20 padding-left-0"
                                                ng-repeat="service in post.services">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox"
                                                               id="{{service.name}}"
                                                               class="custom-control-input"
                                                               name="checkbox"
                                                               ng-model="service.createService">
                                                        <label class="custom-control-label custom-control-label-ok"
                                                               for="{{service.name}}">
                                                            {{service.name}}
                                                            <span class="help-block italic">
                                                                {{service.description}}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group {{detectColor(commandargument.commandargument.human_name)}}"
                                                     ng-repeat="commandargument in service.servicecommandargumentvalues">
                                                    {{commandargument.commandargument.human_name}}
                                                    <input class="form-control"
                                                           type="text"
                                                           ng-disabled="!service.createService"
                                                           ng-model="commandargument.value">
                                                </div>
                                            </ol>
                                        </ul>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary"><?php echo __('Create'); ?></button>
                                    <a back-button href="javascript:void(0);" fallback-state='WizardsIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
