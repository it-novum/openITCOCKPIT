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

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-secret fa-fw "></i>
            <?php echo __('openITCOCKPIT Agent'); ?>
            <span>>
                <?php echo __('Scan'); ?>
            </span>
            <div class="third_level">> {{host.name}}</div>
        </h1>
    </div>
</div>


<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget">
                <header>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-user-secret"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Agent configuration for device:'); ?>
                        {{host.name}}
                    </h2>

                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Rerun discovery'); ?>
                        </button>
                    </div>

                    <div class="widget-toolbar" role="menu">
                        <a class="btn btn-xs btn-primary" ui-sref="AgentconfigsConfig({hostId: host.id})">
                            <i class="fa fa-cogs"></i>
                            <?php echo __('Edit agent configuration'); ?>
                        </a>
                    </div>


                </header>
                <div>
                    <div class="widget-body">
                        <form ng-submit="submit();" class="form-horizontal"
                              ng-init="successMessage=
            {objectName : '<?php echo __('Agent configuration'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                            <div class="row">


                                <div class="row" ng-show="discoveryIsRunning">
                                    <div class="col-xs-12 text-center">
                                        <i class="fa fa-cog fa-4x fa-spin"></i><br/>
                                        <?php echo __('Executing remote discovery...'); ?>
                                    </div>
                                </div>

                                <div class="row" ng-show="hasError">
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger alert-block">
                                            <h5 class="alert-heading"><i class="fa fa-warning"></i>
                                                <?php echo __('Could not connect to agent.'); ?>
                                            </h5>
                                            <div class="well text-danger">
                                                {{error}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-show="hasAgentOutput">

                                    <div class="col-xs-12 col-md-12 col-lg-8">
                                        <div class="row">
                                            <div class="form-group required"
                                                 ng-class="{'has-error': errors.servicetemplate_id}">
                                                <label class="col col-md-2 control-label">
                                                    <?php echo __('Health'); ?>
                                                </label>
                                                <div class="col col-xs-10">
                                                    <select data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="mapping.health"
                                                            multiple
                                                            ng-options="health as health.name for health in mapping.health"
                                                            ng-model="selectedHealthChecks">
                                                    </select>
                                                    <div ng-repeat="error in errors.servicetemplate_id">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group required"
                                                 ng-class="{'has-error': errors.servicetemplate_id}">
                                                <label class="col col-md-2 control-label">
                                                    <?php echo __('Processes'); ?>
                                                </label>
                                                <div class="col col-xs-10">
                                                    <select data-placeholder="<?php echo __('Please choose'); ?>"
                                                            class="form-control"
                                                            chosen="mapping.processes"
                                                            multiple
                                                            ng-options="process as process.name for process in mapping.processes"
                                                            ng-model="selectedProcessChecks">
                                                    </select>
                                                    <div ng-repeat="error in errors.servicetemplate_id">
                                                        <div class="help-block text-danger">{{ error }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 margin-top-10" ng-show="isCreatingServices">
                                    <h4><?php echo __('Creating Services...'); ?></h4>
                                </div>
                                <div class="col-xs-12 margin-top-10" ng-show="isCreatingServices">
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12 margin-top-10">
                                    <div class="well formactions ">
                                        <div class="pull-right">
                                            <input class="btn btn-primary" type="button" ng-click="createServices()"
                                                   value="<?php echo __('Create services'); ?>">
                                            <a ui-sref="HostsIndex"
                                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
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
</section>