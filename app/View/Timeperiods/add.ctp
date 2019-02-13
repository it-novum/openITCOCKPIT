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

$weekdays = [
    1 => __('Monday'),
    2 => __('Tuesday'),
    3 => __('Wednesday'),
    4 => __('Thursday'),
    5 => __('Friday'),
    6 => __('Saturday'),
    7 => __('Sunday')
];
?>
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
        <form ng-submit="submit();" class="form-horizontal">
            <div class="widget-body">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.Timeperiod.container_id}">
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
                                    ng-model="post.Timeperiod.container_id">
                            </select>
                            <div ng-repeat="error in errors.Timeperiod.container_id">
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
                <div ng-repeat="range in timeperiod.ranges">
                    <div class="col-md-10 col-md-offset-2 padding-top-5">
                        <div class="col col-md-3">
                            <label class="col col-md-2 control-label text-left">
                                <?php echo __('Day'); ?>
                            </label>
                            <div class="col col-md-8">
                                <select class="input-sm"
                                        ng-model="timeperiod.ranges[$index].day"
                                        style="width:100%;">
                                    <?php foreach ($weekdays as $day => $weekday):
                                        printf('<option value="%s">%s</option>>', $day, $weekday);
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col col-md-3">
                            <label class="col col-md-3 control-label text-left">
                                <?php echo __('Start'); ?>
                            </label>
                            <div class="form-group smart-form">
                                <label class="input"> <i class="icon-prepend fa fa-clock-o"></i>
                                    <input class="form-control input-sm" type="text"
                                           placeholder="<?php echo __('00:00'); ?>"
                                           size="5"
                                           maxlength="5"
                                           name="data[Timeperiod][{{$index}}][start]"
                                           ng-model="range.start">
                                </label>
                            </div>
                        </div>
                        <div class="col col-md-3">
                            <label class="col col-md-3 control-label text-left">
                                <?php echo __('End'); ?>
                            </label>
                            <div class="form-group smart-form">
                                <label class="input"> <i class="icon-prepend fa fa-clock-o"></i>
                                    <input class="form-control input-sm" type="text"
                                           placeholder="<?php echo __('24:00'); ?>"
                                           size="5"
                                           maxlength="5"
                                           name="data[Timeperiod][{{$index}}][start]"
                                           ng-model="range.end">
                                </label>
                            </div>
                        </div>
                        <div class="col col-md-1">
                            <a class="btn btn-default btn-sm txt-color-red"
                               href="javascript:void(0);"
                               ng-click="removeTimerange($index|number)">
                                <i class="fa fa-trash-o fa-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-md-offset-2 padding-top-5 text-right">
                    <a class="btn btn-success btn-sm" ng-click="addTimerange()">
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
            <div class="col-xs-12 margin-top-10">
                <div class="well formactions ">
                    <div class="pull-right">
                        <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">
                        <a ui-sref="TimeperiodsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
