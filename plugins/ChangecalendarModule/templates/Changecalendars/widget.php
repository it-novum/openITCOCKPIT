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




<!-- Add Event modal -->
<div id="changecalendar-{{widget.id}}-details" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-edit"></i>
                    <?php echo __('Modify date'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="modifyEventTitle"><?php echo __('Title'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input disabled="disabled" type="text" class="form-control" placeholder="<?php echo __('Title'); ?>"
                                   ng-model="modifyEvent.title" id="modifyEventTitle">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for="modifyEventStart"><?php echo __('Start'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input disabled="disabled" type="datetime-local" class="form-control" placeholder="<?php echo __('Start'); ?>"
                                   ng-model="modifyEvent.start" id="modifyEventStart">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="modifyEventEnd"><?php echo __('End'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input disabled="disabled" type="datetime-local" class="form-control" placeholder="<?php echo __('End'); ?>"
                                   ng-model="modifyEvent.end" id="modifyEventEnd">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"><?php echo __('Description'); ?></label>
                            <textarea disabled="disabled" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div ng-repeat="contextField in modifyEvent.context" class="col-lg-12">
                        <div class="form-group">
                            <label class="text-{{contextField.class}}">{{contextField.name}}</label>
                            <textarea disabled="disabled" class="form-control bg-{{contextField.class}}" rows="3">{{contextField.value}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="modifyEventFromModal()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
            <input type="hidden" value="" ng-model="modifyEvent.id" />
        </div>
    </div>
</div>