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
<div id="nodeCreatedFlashMessage" class="alert alert-success" style="display:none;">
    <?php echo __('Downtime created successfully'); ?>
</div>
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
                    ?>

                    {{ post.Systemdowntime.objecttype_id="<?php echo OBJECT_HOST; ?>";""}}

                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                            <?php echo __('Host'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContainerId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="Downtime.SuggestedHosts"
                                    ng-options="host.key as host.value for host in Downtime.SuggestedHosts"
                                    ng-model="post.Systemsettings.object_id">
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
                        <div class="col col-xs-10 col-md-10 col-lg-10">
                            <input class="form-control" type="text" ng-model="post.Systemdowntime.comment" >
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
                            'ng-model' => 'post.Systemdowntime.is_recurring'
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
                            <div class="col col-xs-10 col-md-10 col-lg-10">{{ Downtime.Recurring.AllWeekdays=<?php echo json_encode($weekdays); ?>;"" }}
                                <select class="form-control" multiple chosen="Downtime.Recurring.AllWeekdays" ng-model="post.Systemdowntime.weekdays">
                                    <!--<option ng-repeat="weekday in Downtime.Recurring.AllWeekdays" value="{{weekday}}">{{weekday}}</option>-->
                                    <?php
                                    foreach($weekdays as $key=>$weekday){
                                        echo '<option value="'.$key.'">'.$weekday.'</option>';
                                    }
                                    ?>

                                </select>
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Days of month'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <input class="form-control" type="text" ng-model="post.Systemdowntime.day_of_month" placeholder="<?php echo __('1,2,3,4,5 or <blank>'); ?>">
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
                            <input type="text" class="form-control" ng-model="post.Systemdowntime.from_date">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('from_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('from_time'); ?>"
                             style="padding-left: 0px;">
                            <input type="text" class="form-control" ng-model="post.Systemdowntime.from_time">
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
                            <input type="text" class="form-control" ng-model="post.Systemdowntime.to_date">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('to_time'); ?>"
                             style="padding-left: 0px;">
                            <input type="text" class="form-control" ng-model="post.Systemdowntime.to_time">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_time'); ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- close col -->
            </div> <!-- close row-->
            <div class="well formactions ">
                <div class="pull-right">
                    <input type="button"
                           class="btn btn-primary"
                           value="<?php echo __('Save'); ?>"
                           ng-click="saveNewHostDowntime()"
                    >
                    &nbsp;
                    <a href="/downtimes/host" class="btn btn-default">
                        <?php echo __('Cancel'); ?>
                    </a>
                </div>
            </div>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->