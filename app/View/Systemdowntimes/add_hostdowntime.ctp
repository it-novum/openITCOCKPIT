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
            <?php echo __('Host downtime'); ?>
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
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Create host downtime'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(__('Back'),$back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <?php
                    echo $this->Form->create('Systemdowntime',[
                        'class' => 'form-horizontal clear',
                    ]);
                    $hostdowntimetyps = [
                        0 => __('Individual host'),
                        1 => __('Host including services'),
                    ];

                    echo $this->CustomValidationErrors->errorHTML('downtimetype',[
                        'class' => 'text-danger',
                    ]);
                    echo $this->Form->input('objecttype_id',[
                        'type'  => 'hidden',
                        'value' => OBJECT_HOST,
                    ]);
                    echo $this->Form->input('downtimetype',[
                        'type'  => 'hidden',
                        'value' => 'host',
                    ]); ?>

                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                            <?php echo __('Host'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContainerId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="Downtime.Hostname">
                            </select>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                            <?php echo __('Maintenance period for');?>
                        </label>
                        <?php if($preselectedDowntimetype==0): ?> {{ Downtime.Type1="1";"" }} <?php endif; ?>
                        <?php if($preselectedDowntimetype==1): ?> {{ Downtime.Type2="1";"" }} <?php endif; ?>
                        <div class="col col-xs-10 col-md-10 col-lg-10">
                            <label class="padding-right-10" for="InstantreportEvaluation1">
                                <input type="radio" name="data[Instantreport][evaluation]" ng-model="Downtime.Type1" value="1">
                                <i class="fa fa-desktop"></i> <?php echo __('Individual host'); ?>
                            </label>
                            <label class="padding-right-10" for="InstantreportEvaluation2">
                                <input type="radio" name="data[Instantreport][evaluation]" ng-model="Downtime.Type2" value="1">
                                <i class="fa fa-cogs"></i> <?php echo __('Host including services'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                            <?php echo __('Comment'); ?>
                        </label>
                        <div class="col col-xs-10 col-md-10 col-lg-10">{{ Downtime.Comment="<?php echo __('In maintenance'); ?>";"" }}
                            <input class="form-control" type="text" ng-model="Downtime.Comment" >
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('DowntimeIsRecurring', [
                            'caption' => __('Recurring downtime'),
                            'wrapGridClass' => 'col col-xs-1 col-md-1 col-lg-1',
                            'captionGridClass' => 'col col-xs-1 col-md-1 col-lg-1',
                            'captionClass' => 'col col-xs-1 control-label text-right ',
                            'ng-model' => 'Downtime.Recurring.IsRecurring'
                        ]);
                        ?>
                    </div>


                    <div class="padding-10"><!-- spacer --></div>
                    <div id="recurringHost_settings" ng-style="Downtime.Recurring.Style">
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
                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Weekdays'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">{{ Downtime.Recurring.Weekdays=<?php echo json_encode($weekdays); ?>;"" }}
                                <select class="form-control" multiple chosen="Downtime.Recurring.Weekdays" name="repeatSelect" id="repeatSelect" ng-model="Downtime.Recurring.SelectedWeekdays">
                                    <option ng-repeat="weekday in Downtime.Recurring.Weekdays" value="{{weekday}}">{{weekday}}</option>
                                </select>
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Days of month'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">{{ Downtime.Recurring.DaysOfMonth="<?php echo $this->CustomValidationErrors->refill('day_of_month','') ?>";"" }}
                                <input class="form-control" type="text" ng-model="Downtime.Recurring.DaysOfMonth" placeholder="<?php echo __('1,2,3,4,5 or <blank>'); ?>">
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br/>
                    <!-- from -->
                    <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('from_date'); ?>">
                        <label class="col col-md-1 control-label" for="SystemdowntimeFromDate"><?php echo __('From'); ?>
                            :</label>
                        <div class="col col-xs-3 col-md-3" style="padding-right: 0px;">
                            {{ Downtime.FromDate="<?php echo $this->CustomValidationErrors->refill('from_date',date('d.m.Y')); ?>";"" }}
                            <input type="text" id="SystemdowntimeFromDate"
                                   class="form-control" name="data[Systemdowntime][from_date]" ng-model="Downtime.FromDate">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('from_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('from_time'); ?>"
                             style="padding-left: 0px;">
                            {{ Downtime.FromTime="<?php echo $this->CustomValidationErrors->refill('from_time',date('H:i')); ?>";"" }}
                            <input type="text" id="SystemdowntimeFromTime"
                                   class="form-control" name="data[Systemdowntime][from_time]" ng-model="Downtime.FromTime">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('from_time'); ?>
                            </div>
                        </div>
                    </div>


                    <!-- to -->
                    <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('to_date'); ?>">
                        <label class="col col-md-1 control-label" for="SystemdowntimeToDate"><?php echo __('To'); ?>
                            :</label>
                        <div class="col col-xs-3 col-md-3" style="padding-right: 0px;">
                            {{ Downtime.ToDate="<?php echo $this->CustomValidationErrors->refill('to_date',date('d.m.Y')); ?>";"" }}
                            <input type="text" id="SystemdowntimeToDate"
                                   class="form-control"
                                   name="data[Systemdowntime][to_date]" ng-model="Downtime.ToDate">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('to_time'); ?>"
                             style="padding-left: 0px;">
                            {{ Downtime.ToTime="<?php echo $this->CustomValidationErrors->refill('to_time',date('H:i',time() + 60 * 15)); ?>";"" }}
                            <input type="text" id="SystemdowntimeToTime"
                                   class="form-control" name="data[Systemdowntime][to_time]" ng-model="Downtime.ToTime">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_time'); ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- close col -->
            </div> <!-- close row-->
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->