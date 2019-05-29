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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-check-square-o fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Registration'); ?>
            </span>
        </h1>
    </div>
</div>



<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
        <h2><?php echo __('Register your installation of openITCOCKPIT'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a class="btn btn-default btn-xs" href="javascript:void(0);" name="creditos"><i
                        class="fa fa-users"></i> <?php echo __('Credits'); ?></a>
        </div>
    </header>
    <div>
        <div class="widget-body resetMinHeight">
            <div ng-if="license.hasOwnProperty('id')">
                <div class="paddint-top-20">
                    <h2><?php echo __('Your license is registered to:'); ?></h2>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('First name'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        {{license.firstname}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Last name'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        {{license.lastname}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Email'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        {{license.email}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Company'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        {{license.company}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Expires'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3 text-primary">
                        {{license.expire}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Your license key'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3 text-primary">
                        {{license.licence}}
                    </div>
                </div>
            </div>
            <div ng-if="!license.hasOwnProperty('id') && !errors.error && post.Registers.license != '' && checked">
                <div class="alert alert-danger alert-block">
                    <a href="#" data-dismiss="alert" class="close">×</a>
                    <h4 class="alert-heading"><?php echo __('Error'); ?></h4>
                    <?php echo __('The entered license key is not valid'); ?>
                </div>
            </div>
            <div>
                <div ng-if="errors.error">
                    <div class="alert alert-danger alert-block">
                        <a href="#" data-dismiss="alert" class="close">×</a>
                        <h4 class="alert-heading"><?php echo __('Error No.: '); ?>{{errors.errno}}</h4>
                        {{errors.error}}
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <br>

        <form ng-submit="submit();" class="form-horizontal">
            <div class="form-group required" ng-class="{'has-error': errors.license}">
                <label class="col col-md-2 control-label">
                    <?php echo __('License key'); ?>
                </label>
                <div class="col col-xs-4">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.Registers.license"
                            ng-model-options="{debounce: 500}"
                            autocomplete="{{isProductionEnv ? 'off': 'on'}}"
                    >
                    <div ng-repeat="error in errors.license">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>
            <div class="form-group text-muted">
                <span class="col col-md-2 hidden-tablet hidden-mobile"><!-- spacer for nice layout --></span>
                <div class="col col-xs-10"><?php echo __('No license key?'); ?>
                    <a class="txt-color-blueDark"
                       href="http://openitcockpit.com"
                       target="_blank">
                        <?php echo __('Please visit our homepage to get in contact.'); ?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 margin-top-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="Register">&nbsp;
                            <a ng-click="load()" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>