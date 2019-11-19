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

/** @var \itnovum\openITCOCKPIT\Core\ValueObjects\User $User */

?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-exclamation-circle fa-fw "></i>
            <?php echo __('System Failure') ?>
            <span>>
                <?php echo __('Add'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-exclamation-circle"></i> </span>
        <h2><?php echo __('Create new system failure'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'systemfailures')): ?>
                <a back-button fallback-state='SystemfailuresIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('System failure'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-info alert-block">
                            <a class="close" data-dismiss="alert" href="#">×</a>
                            <h4 class="alert-heading"><?php echo __('What are "System Failures" for?'); ?></h4>
                            <?php echo __('<i>System failures</i> are outages of the openITCOCKPIT server itself. They need to be created manually.'); ?>
                            <br/>
                            <?php echo __('Timeframes defined by System failures will be ignored while report generation.'); ?>
                        </div>
                    </div>


                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col-xs-12 col-lg-2 control-label">
                            <?php echo __('Author'); ?>
                        </label>
                        <div class="col-xs-12 col-lg-10">
                            <select
                                    id="AuthorFakeSelect"
                                    class="form-control"
                                    disabled="disabled"
                                    chosen="containers">
                                <option>
                                    <?php echo h($User->getFullName()); ?>
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group required" ng-class="{'has-error': errors.comment}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Comment'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Systemfailure.comment">
                            <div ng-repeat="error in errors.comment">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.start_time}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('From'); ?>
                        </label>
                        <div class="col-md-10 no-padding">
                            <div class="row">
                                <div class="col-xs-6 col-md-3">
                                    <input
                                            id="SystemfailureFromDate"
                                            class="form-control"
                                            type="text"
                                            placeholder="DD.MM.YYYY"
                                            ng-model="post.Systemfailure.from_date">
                                </div>
                                <div class="col-xs-6 col-md-9">
                                    <input
                                            class="form-control"
                                            type="text"
                                            placeholder="hh:mm"
                                            ng-model="post.Systemfailure.from_time">
                                </div>
                            </div>

                            <div ng-repeat="error in errors.start_time" class="col-xs-12">
                                <div class="help-block">
                                    <span class="text-danger">{{ error }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.end_time}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('To'); ?>
                        </label>
                        <div class="col-md-10 no-padding">
                            <div class="row">
                                <div class="col-xs-6 col-md-3">
                                    <input
                                            id="SystemfailureToDate"
                                            class="form-control"
                                            type="text"
                                            placeholder="DD.MM.YYYY"
                                            ng-model="post.Systemfailure.to_date">
                                </div>
                                <div class="col-xs-6 col-md-9">
                                    <input
                                            class="form-control"
                                            type="text"
                                            placeholder="hh:mm"
                                            ng-model="post.Systemfailure.to_time">
                                </div>
                            </div>

                            <div ng-repeat="error in errors.end_time" class="col-xs-12">
                                <div class="help-block">
                                    <span class="text-danger">{{ error }}</span>
                                </div>
                            </div>
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
                                   value="<?php echo __('Create system failure'); ?>">

                            <a back-button fallback-state='SystemfailuresIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
