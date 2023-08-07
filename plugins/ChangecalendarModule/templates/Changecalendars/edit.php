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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ChangecalendarsIndex">
            <i class="fa fa-calendar"></i> <?php echo __('Changecalendars'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
    </li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Edit Changecalendar:'); ?>
                    <span class="fw-300"><i>{{post.changeCalendar.name}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'changecalendars', 'ChangecalendarsModule')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='ChangecalendarsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal"
                          ng-init="successMessage=
            {objectName : '<?php echo __('Changecalendar'); ?>' , message: '<?php echo __('created successfully'); ?>',
            'addHoliday': '<?php echo __('Add holiday '); ?>', 'deleteAllHolidays': '<?php echo __('Delete ALL holidays'); ?>',
            'deleteMonthEvents': '<?php echo __('Delete MONTH events'); ?>', 'deleteAllEvents': '<?php echo __('Delete ALL events'); ?>'}">
                        <div class="form-group required" ng-class="{'has-error': errors.containers}">
                            <label class="control-label" for="ChangecalendarContainer">
                                <?php echo __('Container'); ?>
                            </label>
                            <select
                                    id="ChangecalendarContainer"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.changeCalendar.container_id">
                            </select>
                            <div ng-show="post.changeCalendar.container_id < 1" class="warning-glow">
                                <?php echo __('Please select a container.'); ?>
                            </div>
                            <div ng-repeat="error in errors.containers">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.changeCalendar.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.description}">
                            <label class="control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.changeCalendar.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.colour}">
                            <label class="control-label">
                                <?php echo __('Colour'); ?>
                            </label>

                            <colorpicker-directive ng-model="post.changeCalendar.colour"
                                                   class="col-6"
                                                   post="post.changeCalendar"
                                                   key="'colour'"></colorpicker-directive>
                            <div ng-repeat="error in errors.colour">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="row padding-top-20">
                            <div class="col-lg-12">
                                <div id="changecalendar">
                                </div>
                            </div>
                        </div>


                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary"
                                            type="submit"><?php echo __('Update changecalendar'); ?></button>
                                    <a back-button href="javascript:void(0);" fallback-state='ChangecalendarsIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Event modal -->
<div id="addEventModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
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
                            <input type="text" class="form-control" placeholder="<?php echo __('Title'); ?>"
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
                            <input type="datetime-local" class="form-control" placeholder="<?php echo __('Start'); ?>"
                                   ng-model="modifyEvent.start" id="modifyEventStart">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="modifyEventEnd"><?php echo __('End'); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input type="datetime-local" class="form-control" placeholder="<?php echo __('End'); ?>"
                                   ng-model="modifyEvent.end" id="modifyEventEnd">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-6">
                        <label for="description"><?php echo __('Description'); ?></label>
                        <div class="panel">
                            <div class="panel-hdr">
                                <div class="panel-toolbar" style="width: 100%;">
                                    <div class="mr-auto d-flex" role="menu">

                                        <div class="dropdown">
                                            <button class="btn btn-xs btn-default dropdown-toggle" type="button"
                                                    id="docuFontSize" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="fa fa-font"></i>
                                                <?php echo __('Font size'); ?>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="docuFontSize">
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="xx-small"><?php echo __('Smallest'); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="x-small"><?php echo __('Smaller'); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="small"><?php echo __('Small'); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="large"><?php echo __('Big'); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="x-large"><?php echo __('Bigger'); ?></a>
                                                <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                                   fsize="xx-large"><?php echo __('Biggest'); ?></a>
                                            </div>
                                        </div>
                                        <span class="padding-left-10"></span>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="bold"><i class="fa fa-bold"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="italic"><i class="fa fa-italic"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="underline"><i class="fa fa-underline"></i></a>
                                        <span class="padding-left-10"></span>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="left"><i class="fa fa-align-left"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="center"><i class="fa fa-align-center"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="right"><i class="fa fa-align-right"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-xs btn-icon" wysiwyg="true"
                                           task="justify"><i class="fa fa-align-justify"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <div ng-class="{'has-error': errors.text}">
                        <textarea class="form-control"  ng-model="modifyEvent.description"
                                  style="width: 100%; height: 200px;" id="description"></textarea>
                                    </div>
                                    <div ng-repeat="error in errors.text">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <label for="preview"><?php echo __('Preview'); ?></label>
                        <div class="panel">
                            <div class="panel-hdr">
                                <div ng-bind-html="descriptionPreview | trustAsHtml">
                                    {{descriptionPreview}}
                                </div>
                            </div>
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
                <button ng-if="modifyEvent.position !== null" type="button" class="btn btn-danger" ng-click="deleteEventFromModal()">
                    <?php echo __('Delete'); ?>
                </button>
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
