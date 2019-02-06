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
<?php //debug($containers); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-clock-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Time Periods'); ?>
            </span>
            <div class="third_level">
                <?php echo ucfirst($this->params['action']); ?>
            </div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-clock-o"></i> </span>
        <h2>
            <?php echo __('Add timeperiod'); ?>
        </h2>
        <div class="widget-toolbar" role="menu">
            <a class="btn btn-default" ui-sref="TimeperiodsIndex">
                <i class="fa fa-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.Container.parent_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="HostgroupParentContainer"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Container.parent_id">
                            </select>
                            <div ng-repeat="error in errors.Container.parent_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="text" ng-model="post.Timeperiod.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="text" ng-model="post.Timeperiod.description">
                        </div>
                    </div>
                    <fieldset class=" form-inline padding-10">
                        <legend class="font-sm">
                            <div <?php echo (isset($timerange_errors['check_timerange'])) ? ' has-error' : ''; ?> ">
                                <label><?php echo __('Time ranges:'); ?>  </label>
                            </div>
                            <?php if (isset($timerange_errors['check_timerange'])): ?>
                                <span class="text-danger"><?php echo (isset($timerange_errors['check_timerange'])) ? $timerange_errors['check_timerange'][0] : ''; ?></span>
                            <?php endif; ?>
                        </legend>

                        <div class="col-md-1 padding-top-10 pull-right" id="addTimerangeButton">
                            <a class="btn btn-success btn-xs addTimeRangeDivButton">
                                <i class="fa fa-plus"></i>
                                <?php echo __('Add'); ?>
                            </a>
                        </div>
                    </fieldset>
                    <fieldset class=" form-inline padding-10">
                        <legend class="font-sm">
                            <div>
                                <label><?php echo __('Link to calendar:'); ?>  </label>
                            </div>
                        </legend>
                    </fieldset>
                </div>
            </form>
        </div>
    </div>
</div>
