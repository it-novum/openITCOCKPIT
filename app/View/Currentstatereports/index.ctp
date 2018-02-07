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
                <?php echo __('Current State Report'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create Current State Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Currentstatereport',[
                'class' => 'form-horizontal clear',
            ]);
            ?>
            <div class="form-group required" ng-class="{'has-error': errors.Service}">
                <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                    <?php echo __('Service'); ?>
                </label>
                <div class="col col-xs-10">
                    <select multiple
                            id="ServiceId"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="services"
                            callback="loadServices"
                            ng-options="service.value.Service.id as service.value.Host.name + '/' +((service.value.Service.name)?service.value.Service.name:service.value.Servicetemplate.name) group by service.value.Host.name for service in services"
                            ng-model="post.Currentstatereport.Service">
                    </select>
                    <div ng-repeat="error in errors.Service">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>

            <div class="form-group required" ng-class="{'has-error': errors.current_state}">
                <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                    <?php echo __('State filter'); ?>
                </label>
                <div class="col col-xs-10">
                    <label>
                        <input type="checkbox" ng-model="current_state.ok"/>
                        <?php echo __('Ok'); ?>
                    </label>

                    <label>
                        <input type="checkbox" ng-model="current_state.warning"/>
                        <?php echo __('Warning'); ?>
                    </label>

                    <label>
                        <input type="checkbox" ng-model="current_state.critical"/>
                        <?php echo __('Critical'); ?>
                    </label>

                    <label>
                        <input type="checkbox" ng-model="current_state.unknown"/>
                        <?php echo __('Unknown'); ?>
                    </label>
                </div>
                <div class="col col-xs-offset-1 col-xs-10" ng-repeat="error in errors.current_state">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>

            <div class="form-group" ng-class="{'has-error': errors.report_format}">
                <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                    <?php echo __('Report format'); ?>
                </label>
                <div class="col col-xs-10 col-md-10 col-lg-10">
                    <label class="padding-right-10">
                        <input type="radio" name="report_format" ng-model="reportformat" value="1">
                        <i class="fa fa-file-pdf-o"></i> <?php echo __('PDF'); ?>
                    </label>
                    <label class="padding-right-10">
                        <input type="radio" name="report_format" ng-model="reportformat" value="2">
                        <i class="fa fa-html5"></i> <?php echo __('HTML'); ?>
                    </label>
                    <div ng-repeat="error in errors.report_format">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="alert alert-info" ng-show="generatingReport">
                    <i class="fa fa-spin fa-refresh"></i>
                    <?php echo __('Generating report...'); ?>
                </div>
            </div>

            <div class="row">
                <div class="alert alert-info" ng-show="noDataFound">
                    {{ noDataFoundMessage }}
                </div>
            </div>

        </div>
        <div class="well formactions ">
            <div class="pull-right">
                <input type="button"
                       class="btn btn-primary"
                       value="<?php echo __('Create'); ?>"
                       ng-click="createCurrentStateReport()"
                >
                &nbsp;
                <a href="/currentstatereports" class="btn btn-default">
                    <?php echo __('Cancel'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
