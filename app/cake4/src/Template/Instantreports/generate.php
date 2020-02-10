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
                <?php echo __('Instant report'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-magic"></i> </span>
        <h2><?php echo __('Create instant report'); ?></h2>
        <ul class="nav nav-tabs pull-right">
            <li ng-class="{'active': tabName=='reportConfig'}" ng-click="tabName='reportConfig'">
                <a href="javascript:void()" data-toggle="tab">
                    <i class="fa fa-pencil-square-o"></i>
                </a>
            </li>
            <li ng-class="{'active': tabName=='instantReport'}" ng-click="tabName='instantReport'"
                ng-show="reportData">
                <a href="javascript:void()" data-toggle="tab">
                    <i class="fa fa-pie-chart"></i>
                </a>
            </li>
        </ul>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="InstantreportsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="alert auto-hide alert-info" ng-if="errors.no_reportdata">
                <div ng-repeat="error in errors.no_reportdata">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>
            <section ng-show="tabName == 'reportConfig'" id="reportConfig">
                <form class="form-horizontal clear" ng-init="reportMessage=
            {successMessage : '<?php echo __('Report created successfully'); ?>' , errorMessage: '<?php echo __('Report could not be created'); ?>'}">
                    <div class="form-group required" ng-class="{'has-error': errors.instantreport_id}">
                        <label class="col col-md-1 control-label">
                            <?php echo __('Instant report'); ?>
                        </label>
                        <div class="col col-xs-10 col-lg-10">
                            <select
                                data-placeholder="<?php echo __('Please choose a instant report'); ?>"
                                class="form-control"
                                chosen="instantreports"
                                ng-options="instantreport.Instantreport.id as instantreport.Instantreport.name for instantreport in instantreports"
                                ng-model="post.instantreport_id">
                            </select>
                            <div ng-repeat="error in errors.instantreport_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
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
                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <button type="button" ng-click="createInstantReport()" class="btn btn-primary">
                                    <?php echo __('Create report'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <section ng-if="tabName == 'instantReport'" id="instantReport">
                <div class="row margin-top-10 font-md padding-bottom-10">
                    <div class="col-md-9 text-left">
                        <i class="fa fa-calendar txt-color-blueDark"></i>
                        <?php echo __('Analysis period: '); ?>
                        {{reportDetails.from}}
                        <i class="fa fa-long-arrow-right"></i>
                        {{reportDetails.to}}
                    </div>
                    <div class="col-md-3 text-left">

                    </div>
                </div>
                <div class="row" ng-if="!reportDetails.summary">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-repeat="report in reportData">
                        <div class="col-xs-12 col-md-12 col-lg-12 padding-5">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2>
                                        <i class="fa fa-desktop"></i>
                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                            <a ui-sref="HostsBrowser({id:report.Host.id})">
                                                {{report.Host.name}}
                                            </a>
                                        <?php else: ?>
                                            {{report.Host.name}}
                                        <?php endif; ?>
                                    </h2>
                                </header>
                                <div class="widget-body">
                                    <div class="row" ng-if="report.Host.reportData">
                                        <div class="col col-md-12 padding-2">
                                            <host-availability-pie-chart chart-id="report.Host.id"
                                                data="report.Host"></host-availability-pie-chart>
                                        </div>
                                    </div>
                                    <div class="row" ng-repeat="service in report.Host.Services">
                                        <div class="col col-md-12 padding-2">
                                            <i class="fa fa-cog"></i>
                                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a ui-sref="ServicesBrowser({id:service.Service.id})">
                                                    {{service.Service.name}}
                                                </a>
                                            <?php else: ?>
                                                {{service.Service.name}}
                                            <?php endif; ?>
                                        </div>
                                        <div class="col col-md-12 no-padding" ng-if="service.Service.reportData">
                                            <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                <service-availability-bar-chart chart-id="service.Service.id"
                                                    data="service.Service"></service-availability-bar-chart>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="row" ng-if="reportDetails.summary">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="col-xs-12 col-md-12 col-lg-12 padding-5" ng-if="reportDetails.summary_hosts">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2>
                                        <i class="fa fa-desktop"></i>
                                        <?php echo __('Hosts summary'); ?>
                                    </h2>
                                </header>
                                <div class="widget-body">
                                    <div class="row" ng-if="reportDetails.summary_hosts.reportData">
                                        <div class="col col-md-12 padding-2">
                                            <host-availability-pie-chart chart-id="'hostSummary'"
                                                data="reportDetails.summary_hosts"></host-availability-pie-chart>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 col-lg-12 padding-5" ng-if="reportDetails.summary_services">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2>
                                        <i class="fa fa-cog"></i>
                                        <?php echo __('Services summary'); ?>
                                    </h2>
                                </header>
                                <div class="widget-body">
                                    <div class="row" ng-if="reportDetails.summary_services.reportData">
                                        <div class="col col-md-12 no-padding"
                                             ng-if="reportDetails.summary_services.reportData">
                                            <div class="col col-lg-3 col-md-12 col-sm-12 col-xs-12 no-padding">
                                                <service-availability-pie-chart chart-id="'serviceSummary'"
                                                    data="reportDetails.summary_services"></service-availability-pie-chart>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </div>
</div>



