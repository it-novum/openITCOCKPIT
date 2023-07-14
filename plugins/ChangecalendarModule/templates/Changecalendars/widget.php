<?php
// Copyright (C) <2023-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
?>
<div>
    <flippy vertical
            class="col-lg-12"
            flip="['custom:FLIP_EVENT_OUT']"
            flip-back="['custom:FLIP_EVENT_IN']"
            duration="800"
            timing-function="ease-in-out">

        <flippy-front class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="showConfig()">
                <i class="fa fa-cog fa-sm"></i>
            </a>
            <span ng-show="currentChangeCalendar.id === null" class="text-info padding-left-20">
                <?php echo __('No change calendar selected'); ?>
            </span>
            <div class="no-padding">
                <a ui-sref="ChangecalendarsEdit({id:currentChangeCalendar.id})">
                    {{currentChangeCalendar.name}}
                </a>
                <div id="changecalendar-{{widget.id}}"></div>
            </div>
        </flippy-front>
        <flippy-back class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="hideConfig()">
                <i class="fa fa-eye fa-sm"></i>
            </a>
            <div class="padding-top-10">
                <div class="form-group">
                    <div class="row">
                        <label class="col-lg-12 control-label">
                            <?php echo __('Changecalendar'); ?>
                        </label>
                        <div class="col-lg-12">
                            <select data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="changeCalendars"
                                    ng-options="changeCalendar.id as changeCalendar.name for changeCalendar in changeCalendars"
                                    ng-model="currentChangeCalendar.id">
                            </select>
                        </div>
                    </div>
                    <br/>

                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary pull-right"
                                    ng-click="saveChangecalendar()">
                                <?php echo __('Save'); ?>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </flippy-back>
    </flippy>
</div>