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
            <i class="fa fa-power-off fa-fw "></i>
            <?php echo __('Create downtime'); ?>
            <span>>
                <?php echo __('Host'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-power-off"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Create new host downtime'); ?></h2>
        <div class="widget-toolbar">
            <a back-button fallback-state='DowntimesHost' class="btn btn-default btn-xs">
                <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Downtime'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-8">
                        <div class="row">
                            <div class="form-group required" ng-class="{'has-error': errors.object_id}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Host'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <select multiple
                                            id="HostId"
                                            data-placeholder="<?php echo __('Please select...'); ?>"
                                            class="form-control"
                                            chosen="hosts"
                                            callback="loadHosts"
                                            ng-options="host.key as host.value for host in hosts"
                                            ng-model="post.Systemdowntime.object_id">
                                    </select>
                                    <div ng-repeat="error in errors.object_id">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required" ng-class="{'has-error': errors.downtimetype_id}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Maintenance period for'); ?>
                                </label>
                                <div class="col col-xs-10 col-md-10 col-lg-10">
                                    <label class="padding-right-10">
                                        <input type="radio"
                                               ng-model="post.Systemdowntime.downtimetype_id" value="0">
                                        <i class="fa fa-desktop"></i> <?php echo __('Individual host'); ?>
                                    </label>
                                    <label class="padding-right-10">
                                        <input type="radio"
                                               ng-model="post.Systemdowntime.downtimetype_id" value="1">
                                        <i class="fa fa-cogs"></i> <?php echo __('Host including services'); ?>
                                    </label>
                                    <div ng-repeat="error in errors.downtimetype_id">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required" ng-class="{'has-error': errors.comment}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Comment'); ?>
                                </label>
                                <div class="col col-xs-10 col-md-10 col-lg-10">
                                    <input class="form-control" type="text" ng-model="post.Systemdowntime.comment">
                                    <div ng-repeat="error in errors.comment">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-xs-12 col-lg-2 control-label" for="DowntimeIsRecurring">
                                    <?php echo __('Recurring downtime'); ?>
                                </label>

                                <div class="col-xs-12 col-lg-1 smart-form">
                                    <label class="checkbox no-required no-padding no-margin label-default-off">
                                        <input type="checkbox" name="checkbox"
                                               id="DowntimeIsRecurring"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               ng-model="post.Systemdowntime.is_recurring">
                                        <i class="checkbox-primary"></i>
                                    </label>
                                </div>
                            </div>


                            <div id="recurringHost_settings" ng-if="post.Systemdowntime.is_recurring === 1">
                                <div class="form-group required" ng-class="{'has-error': errors.from_time}">
                                    <label class="col col-md-2 control-label"
                                           for="SystemdowntimeFromTime"><?php echo __('Start time'); ?></label>
                                    <div class="col col-xs-10 col-md-10 col-lg-10">
                                        <input type="text" class="form-control" ng-model="post.Systemdowntime.from_time"
                                               placeholder="<?php echo __('hh:mm'); ?>">
                                        <div ng-repeat="error in errors.from_time">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group required" ng-class="{'has-error': errors.duration}">
                                    <label class="col-xs-12 col-lg-2 control-label">
                                        <?php echo __('Duration'); ?>
                                    </label>
                                    <duration-input-directive
                                            duration="post.Systemdowntime.duration"></duration-input-directive>
                                    <div class="col-xs-12 col-lg-offset-2">
                                        <div ng-repeat="error in errors.duration">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $weekdays = [
                                    1 => __('Monday'),
                                    2 => __('Tuesday'),
                                    3 => __('Wednesday'),
                                    4 => __('Thursday'),
                                    5 => __('Friday'),
                                    6 => __('Saturday'),
                                    7 => __('Sunday'),
                                ];
                                ?>
                                <div class="form-group required" ng-class="{'has-error': errors.weekdays}">
                                    <label class="col col-md-2 control-label">
                                        <?php echo __('Weekdays'); ?>
                                    </label>
                                    <div class="col col-xs-10 col-md-10 col-lg-10">
                                        <select class="form-control" multiple
                                                ng-model="post.Systemdowntime.weekdays"
                                                chosen="{}">
                                            <?php
                                            foreach ($weekdays as $key => $weekday) :
                                                printf('<option value="%s">%s</option>', $key, h($weekday));
                                            endforeach;
                                            ?>

                                        </select>
                                        <div ng-repeat="error in errors.weekdays">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{'has-error': errors.day_of_month}">
                                    <label class="col col-md-2 control-label">
                                        <?php echo __('Days of month'); ?>
                                    </label>
                                    <div class="col col-xs-10 col-md-10 col-lg-10">
                                        <input class="form-control" type="text"
                                               ng-model="post.Systemdowntime.day_of_month"
                                               placeholder="<?php echo __('1,2,3,4,5 or <blank>'); ?>">
                                        <div ng-repeat="error in errors.day_of_month">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div ng-if="post.Systemdowntime.is_recurring === 0">
                                <!-- from -->
                                <div class="row">
                                    <div class="form-group required">
                                        <label class="col col-md-2 control-label"
                                               for="SystemdowntimeFromDate"><?php echo __('From'); ?></label>
                                        <div class="col col-xs-3 col-md-3" ng-class="{'has-error': errors.from_time}">
                                            <input type="text" class="form-control"
                                                   ng-model="post.Systemdowntime.from_date"
                                                   placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                            <div ng-repeat="error in errors.from_date">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                        <div class="col col-xs-2 col-md-2 no-padding"
                                             ng-class="{'has-error': errors.from_time}">
                                            <input type="text" class="form-control"
                                                   ng-model="post.Systemdowntime.from_time"
                                                   placeholder="<?php echo __('hh:mm'); ?>">
                                        </div>
                                        <div class="col-xs-12 col-md-12 col-lg-10 col-xs-offset-0 col-md-offset-0 col-lg-offset-2">
                                            <div ng-repeat="error in errors.from_time">
                                                <div class="help-block">
                                                    <span class="text-danger">{{ error }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- to -->
                                <div class="row">
                                    <div class="form-group required">
                                        <label class="col col-md-2 control-label"
                                               for="SystemdowntimeToDate"><?php echo __('To'); ?></label>
                                        <div class="col col-xs-3 col-md-3" ng-class="{'has-error': errors.to_time}">
                                            <input type="text" class="form-control"
                                                   ng-model="post.Systemdowntime.to_date"
                                                   placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                            <div ng-repeat="error in errors.to_date">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                        <div class="col col-xs-2 col-md-2 no-padding"
                                             ng-class="{'has-error': errors.to_time}">
                                            <input type="text" class="form-control"
                                                   ng-model="post.Systemdowntime.to_time"
                                                   placeholder="<?php echo __('hh:mm'); ?>">
                                        </div>
                                        <div class="col-xs-12 col-md-12 col-lg-10 col-xs-offset-0 col-md-offset-0 col-lg-offset-2">
                                            <div ng-repeat="error in errors.to_time">
                                                <div class="help-block">
                                                    <span class="text-danger">{{ error }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <label>
                                    <input type="checkbox" ng-model="data.createAnother">
                                    <?php echo _('Create another'); ?>
                                </label>

                                <input class="btn btn-primary" type="submit"
                                       value="<?php echo __('Create downtime'); ?>">

                                <a back-button fallback-state='DowntimesHost'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
