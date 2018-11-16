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


use itnovum\openITCOCKPIT\ConfigGenerator\GraphingDocker;

/** @var GraphingDocker $GraphingDocker */
?>


<form ng-submit="submit();" class="form-horizontal">

    <div class="row">

        <div class="form-group required" ng-class="{'has-error': errors.Configfile.carbon_path}">
            <label class="col col-md-2 control-label">
                <?php echo __('Carbon storage path'); ?>
            </label>
            <div class="col col-xs-10">
                <input
                        class="form-control"
                        type="text"
                        ng-model="post.string.carbon_path">
                <div ng-repeat="error in errors.Configfile.carbon_path">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>
            <div class="helpText text-muted col-md-offset-2 col-md-6">
                <?php echo h($GraphingDocker->getHelpText('carbon_path')); ?>
            </div>
        </div>

        <div class="form-group required" ng-class="{'has-error': errors.Configfile.number_of_carbon_cache_instances}">
            <label class="col col-md-2 control-label">
                <?php echo __('Carbon Cache instances'); ?>
            </label>
            <div class="col col-xs-10">
                <input
                        class="form-control"
                        type="number"
                        min="1"
                        ng-model="post.int.number_of_carbon_cache_instances">
                <div ng-repeat="error in errors.Configfile.number_of_carbon_cache_instances">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>
            <div class="helpText text-muted col-md-offset-2 col-md-6">
                <?php echo h($GraphingDocker->getHelpText('number_of_carbon_cache_instances')); ?>
            </div>
        </div>

        <div class="form-group required" ng-class="{'has-error': errors.Configfile.number_of_carbon_c_relay_workers}">
            <label class="col col-md-2 control-label">
                <?php echo __('Carbon C Relay workers'); ?>
            </label>
            <div class="col col-xs-10">
                <input
                        class="form-control"
                        type="number"
                        min="1"
                        ng-model="post.int.number_of_carbon_c_relay_workers">
                <div ng-repeat="error in errors.Configfile.number_of_carbon_c_relay_workers">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>
            <div class="helpText text-muted col-md-offset-2 col-md-6">
                <?php echo h($GraphingDocker->getHelpText('number_of_carbon_c_relay_workers')); ?>
            </div>
        </div>

        <div class="form-group required" ng-class="{'has-error': errors.Configfile.carbon_storage_schema}">
            <label class="col col-md-2 control-label">
                <?php echo __('Carbon storage schema'); ?>
            </label>
            <div class="col col-xs-10">
                <input
                        class="form-control"
                        type="text"
                        ng-model="post.string.carbon_storage_schema">
                <div ng-repeat="error in errors.Configfile.carbon_storage_schema">
                    <div class="help-block text-danger">{{ error }}</div>
                </div>
            </div>
            <div class="helpText text-muted col-md-offset-2 col-md-6">
                <?php echo h($GraphingDocker->getHelpText('carbon_storage_schema')); ?>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 margin-top-10">
            <div class="well formactions ">
                <div class="pull-right">
                    <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">&nbsp;
                    <a href="/ConfigurationFiles/index" class="btn btn-default">
                        <?php echo __('Cancel'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
