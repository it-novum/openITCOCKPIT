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
            <i class="fa fa-calendar-plus-o fa-fw "></i>
            <?php echo __('Calendars groups'); ?>
            <span>>
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
        <h2><?php echo __('Create new calendar'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a back-button fallback-state='CalendarsIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Calendar'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="row">

                            <div class="form-group required" ng-class="{'has-error': errors.container.parent_id}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Container'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <select
                                            id="CalendarContainer"
                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                            class="form-control"
                                            chosen="containers"
                                            ng-options="container.key as container.value for container in containers"
                                            ng-model="post.Calendar.container_id">
                                    </select>

                                    <div ng-show="post.Calendar.container_id < 1" class="warning-glow">
                                        <?php echo __('Please select a container.'); ?>
                                    </div>

                                    <div ng-repeat="error in errors.calendar.container_id">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group required" ng-class="{'has-error': errors.calendar.name}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Calendar name'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <input
                                            class="form-control"
                                            type="text"
                                            ng-model="post.Calendar.name">
                                    <div ng-repeat="error in errors.calendar.name">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Description'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <input class="form-control" type="text" ng-model="post.Calendar.description">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 padding-top-20">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <label>
                                <input type="checkbox" ng-model="data.createAnother">
                                <?php echo _('Create another'); ?>
                            </label>

                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Create calendar'); ?>">

                            <a back-button fallback-state='CalendarsIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="addEventModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-user"></i>
                    <?php echo __('Add new bank holiday'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Title'); ?>"
                                       ng-model="newEvent.title">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="addEventFromModal()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="editEventModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-user"></i>
                    <?php echo __('Edit holiday'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Title'); ?>"
                                       ng-model="editEvent.title">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="editEventFromModal()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
