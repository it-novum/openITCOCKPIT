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
            <?php echo __('Container downtime'); ?>
            <span>>
                <?php echo __('create'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-power-off"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Create container downtime'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(__('Back'),$back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">

                    <div class="form-group required" ng-class="{'has-error': errors.object_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select multiple
                                    id="ContainerId"
                                    data-placeholder="<?php echo __('Please select...'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    callback="loadContainers"
                                    ng-options="host.key as host.value for host in containers"
                                    ng-model="containerIds"
                            >
                            </select>
                            <div ng-repeat="error in errors.object_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="DowntimeIsRecurring" class="col col-md-2 control-label">
                            <?php echo __('Recursive container lookup'); ?>
                        </label>
                        <span class="onoffswitch margin-left-15">
                            <input class="onoffswitch-checkbox"
                                   value="1"
                                   ng-model="Downtime.is_inherit"
                                   id="DowntimeIsInherit"
                                   type="checkbox">
                            <label for="DowntimeIsInherit" class="onoffswitch-label">
                                <span data-swchoff-text="Off" data-swchon-text="On" class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </span>

                        <div class="col col-md-offset-2 col-md-10">
                            <span class="help-block"><?php echo __('Will also create a downtime for all children containers'); ?></span>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.downtimetype_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Maintenance period for'); ?>
                        </label>
                        <div class="col col-xs-10 col-md-10 col-lg-10">
                            <label class="padding-right-10">
                                <input type="radio" name="data[Downtime][downtimetype_id]" ng-model="post.Systemdowntime.downtimetype_id" value="0">
                                <i class="fa fa-desktop"></i> <?php echo __('Individual host'); ?>
                            </label>
                            <label class="padding-right-10">
                                <input type="radio" name="data[Downtime][downtimetype_id]" ng-model="post.Systemdowntime.downtimetype_id" value="1">
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
                        <label for="DowntimeIsRecurring" class="col col-md-2 control-label">
                            <?php echo __('Recurring downtime'); ?>
                        </label>
                        <span class="onoffswitch margin-left-15">
                            <input class="onoffswitch-checkbox"
                                   value="1"
                                   ng-model="Downtime.is_recurring"
                                   id="DowntimeIsRecurring"
                                   type="checkbox">
                            <label for="DowntimeIsRecurring" class="onoffswitch-label">
                                <span data-swchoff-text="Off" data-swchon-text="On" class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </span>
                    </div>

                    <div id="recurringContainer_settings" ng-if="Downtime.is_recurring === true">
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
                            <label class="col col-md-2 control-label"
                                   for="SystemdowntimeDuration"><?php echo __('Duration'); ?></label>
                            <div class="col col-xs-10 col-md-10 col-lg-10" ng-class="{'has-error': errors.duration}">
                                <input type="text" class="form-control" ng-model="post.Systemdowntime.duration"
                                       placeholder="<?php echo __('minutes'); ?>">
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
                                <input class="form-control" type="text" ng-model="post.Systemdowntime.day_of_month"
                                       placeholder="<?php echo __('1,2,3,4,5 or <blank>'); ?>">
                                <div ng-repeat="error in errors.day_of_month">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div ng-if="Downtime.is_recurring === false">
                        <!-- from -->
                        <div class="row">
                            <div class="form-group required">
                                <label class="col col-md-2 control-label"
                                       for="SystemdowntimeFromDate"><?php echo __('From'); ?></label>
                                <div class="col col-xs-3 col-md-3" ng-class="{'has-error': errors.from_date}">
                                    <input type="text" class="form-control" ng-model="post.Systemdowntime.from_date"
                                            placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                    <div ng-repeat="error in errors.from_date">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                                <div class="col col-xs-2 col-md-2 no-padding"
                                     ng-class="{'has-error': errors.from_time}">
                                    <input type="text" class="form-control" ng-model="post.Systemdowntime.from_time"
                                           placeholder="<?php echo __('hh:mm'); ?>">
                                    <div ng-repeat="error in errors.from_time">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- to -->
                        <div class="row">
                            <div class="form-group required">
                                <label class="col col-md-2 control-label"
                                       for="SystemdowntimeToDate"><?php echo __('To'); ?></label>
                                <div class="col col-xs-3 col-md-3" ng-class="{'has-error': errors.to_date}">
                                    <input type="text" class="form-control" ng-model="post.Systemdowntime.to_date"
                                           placeholder="<?php echo __('DD.MM.YYYY'); ?>">
                                    <div ng-repeat="error in errors.to_date">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                                <div class="col col-xs-2 col-md-2 no-padding" ng-class="{'has-error': errors.to_time}">
                                    <input type="text" class="form-control" ng-model="post.Systemdowntime.to_time"
                                           placeholder="<?php echo __('hh:mm'); ?>">
                                    <div ng-repeat="error in errors.to_time">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div> <!-- close row-->
        <div class="well formactions ">
            <div class="pull-right">
                <input type="button"
                       class="btn btn-primary"
                       value="<?php echo __('Save'); ?>"
                       ng-click="saveNewContainerDowntime()"
                >
                &nbsp;
                <a href="<?php echo $back_url; ?>" class="btn btn-default">
                    <?php echo __('Cancel'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
