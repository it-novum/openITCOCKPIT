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
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Downtime report'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create downtime report'); ?></h2>
        <ul class="nav nav-tabs pull-right">
            <li ng-class="{'active': tabName=='reportConfig'}" ng-click="tabName='reportConfig'">
                <a href="javascript:void()" data-toggle="tab">
                    <i class="fa fa-pencil-square-o"></i>
                </a>
            </li>
            <li ng-class="{'active': tabName=='calendarOverview'}" ng-click="tabName='calendarOverview'"
                ng-show="reportData.downtimes">
                <a href="javascript:void()" data-toggle="tab">
                    <i class="fa fa-calendar"></i>
                </a>
            </li>
            <li ng-class="{'active': tabName=='hostsServicesOverview'}" ng-click="tabName='hostsServicesOverview'"
                ng-show="reportData.downtimes">
                <a href="javascript:void()" data-toggle="tab">
                    <i class="fa fa-pie-chart"></i>
                </a>
            </li>
        </ul>
    </header>
    <div>
        <div class="widget-body">
            <div
                    ng-init="reportMessage={successMessage : '<?php echo __('Report created successfully'); ?>' , errorMessage: '<?php echo __('Report could not be created'); ?>'}">
                <section ng-show="tabName == 'reportConfig'" id="reportConfig">
                    <?php
                    echo $this->Form->create('Downtimereport', [
                        'class' => 'form-horizontal clear',
                    ]);
                    ?>
                    <div ng-class="{'has-error': errors.services}">
                        <div class="form-group">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Evaluation'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <span>
                                    <input type="radio"
                                           id="hosts"
                                           ng-model="post.evaluation_type"
                                           ng-value="0">
                                    <label for="hosts">
                                        <i class="fa fa-desktop"></i>
                                        <?php echo __('Hosts'); ?>
                                    </label>
                                </span>
                                <span class="padding-left-10">
                                    <input type="radio"
                                           id="hostandservices"
                                           ng-model="post.evaluation_type"
                                           ng-value="1">
                                    <label for="hostandservices">
                                        <i class="fa fa-cogs"></i>
                                        <?php echo __('Hosts and Services'); ?>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Report format'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select
                                        class="form-control"
                                        ng-model="post.report_format">
                                    <option ng-value="1"><?php echo __('PDF'); ?></option>
                                    <option ng-value="2"><?php echo __('HTML'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.timeperiod_id}">
                            <label class="col col-md-1 control-label">
                                <?php echo __('Timeperiod'); ?>
                            </label>
                            <div class="col col-xs-10 col-lg-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose a timeperiod'); ?>"
                                        class="form-control"
                                        chosen="timeperiods"
                                        ng-options="timeperiod.Timeperiod.id as timeperiod.Timeperiod.name for timeperiod in timeperiods"
                                        ng-model="post.timeperiod_id">
                                </select>
                                <div ng-repeat="error in errors.timeperiod_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.from_date}">
                            <label class="col col-md-1 control-label"
                                   for="FromTime"><?php echo __('From'); ?></label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <input type="text" class="form-control" ng-model="post.from_date"
                                       placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                <div ng-repeat="error in errors.from_date">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.to_date}">
                            <label class="col col-md-1 control-label"
                                   for="ToTime"><?php echo __('To'); ?></label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <input type="text" class="form-control" ng-model="post.to_date"
                                       placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                <div ng-repeat="error in errors.to_date">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Reflection state'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select class="form-control" ng-model="post.reflection_state">
                                    <option ng-value="1"><?php echo __('soft and hard state'); ?></option>
                                    <option ng-value="2"><?php echo __('only hard state'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-lg-1 control-label" for="setColorDynamically">
                                <?php echo __('Dynamic color'); ?>
                            </label>
                            <div class="col-xs-12 col-lg-10 smart-form">
                                <label class="checkbox small-checkbox-label no-required no-padding-top">
                                    <input type="checkbox" name="checkbox"
                                           id="setColorDynamically"
                                           ng-model="setColorDynamically">
                                    <i class="checkbox-primary"></i>
                                    <?php echo __('Yes'); ?>
                                    <span class="margin-left-10 label-group-width-auto">
                                        <span class="label-small" style="background:#449d44;">100%</span>
                                        <span class="label-small" style="background:#55a03e;">90%</span>
                                        <span class="label-small" style="background:#65a339;">80%</span>
                                        <span class="label-small" style="background:#76a633;">70%</span>
                                        <span class="label-small" style="background:#86a92d;">60%</span>
                                        <span class="label-small" style="background:#97ac28;">50%</span>
                                        <span class="label-small" style="background:#d6671f;">40%</span>
                                        <span class="label-small" style="background:#d35922;">30%</span>
                                        <span class="label-small" style="background:#d04c25;">20%</span>
                                        <span class="label-small" style="background:#cc3e29;">10%</span>
                                        <span class="label-small" style="background:#c9302c;">0%</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <button type="button" ng-click="createDowntimeReport()" class="btn btn-primary">
                                    <?php echo __('Create report'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <section ng-if="tabName == 'calendarOverview'" id="calendarOverview">
                    <downtimecalendar downtimes="reportData.downtimes" from-date="post.from_date"
                                      to-date="post.to_date"></downtimecalendar>
                </section>
                <section ng-if="tabName == 'hostsServicesOverview'" id="hostsServicesOverview">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2>
                                        <span class="fa-stack">
                                            <i class="fa fa-desktop fa-lg fa-stack-1x"></i>
                                            <i class="fa fa-exclamation-triangle fa-stack-1x fa-xs cornered cornered-lr text-danger padding-bottom-2"></i>
                                        </span>
                                        <?php echo __('Involved in outages (Hosts):'); ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <div ng-repeat="(chunkIndex, hostsWithOutages) in reportData.hostsWithOutages">
                                        <hosts-bar-chart chart-id="chunkIndex"
                                                         bar-chart-data="hostsWithOutages.hostBarChartData"></hosts-bar-chart>

                                        <div class="row" ng-repeat="host in hostsWithOutages.hosts">
                                            <host-availability-overview data="host"
                                                                        evaluation-type="post.evaluation_type"
                                                                        dynamic-color="setColorDynamically"></host-availability-overview>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2>
                                        <span class="fa-stack">
                                            <i class="fa fa-desktop fa-lg fa-stack-1x"></i>
                                            <i class="fa fa-check-circle fa-stack-1x fa-xs cornered cornered-lr ok padding-bottom-2"></i>
                                        </span>
                                        <?php echo __('Hosts without outages:'); ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <div class="row" ng-repeat="host in reportData.hostsWithoutOutages.hosts">
                                        <host-availability-overview data="host" evaluation-type="post.evaluation_type"
                                                                    dynamic-color="setColorDynamically"></host-availability-overview>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

