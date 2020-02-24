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
            <i class="fa fa-magic fa-fw "></i>
            <?php echo __('Auto Maps'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-magic"></i> </span>
        <h2>
            <?php echo __('Edit auto map:'); ?>
            {{post.Automap.name}}
        </h2>

        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'automaps')): ?>
                <a back-button fallback-state='AutomapsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Auto Map'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Container'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="ContainerSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="containers"
                                    ng-options="container.key as container.value for container in containers"
                                    ng-model="post.Automap.container_id">
                            </select>
                            <div ng-show="post.Automap.container_id < 1" class="warning-glow">
                                <?php echo __('Please select a container.'); ?>
                            </div>
                            <div ng-repeat="error in errors.container_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.recursive}">
                        <label class="col col-md-2 control-label" for="recursive">
                            <?php echo __('Recursive'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox"
                                       id="recursive"
                                       name="checkbox"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.Automap.recursive">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.name}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Automap.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.description}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.Automap.description">
                            <div ng-repeat="error in errors.description">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.host_regex}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Host RegEx'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model-options="{debounce: 500}"
                                    ng-model="post.Automap.host_regex">
                            <div ng-repeat="error in errors.host_regex">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block" ng-show="data.hostCount > 0">
                                <?php echo __('{0} hosts matching to regular expression.', '{{data.hostCount}}'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.service_regex}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Service RegEx'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model-options="{debounce: 500}"
                                    ng-model="post.Automap.service_regex">
                            <div ng-repeat="error in errors.service_regex">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block" ng-show="data.serviceCount > 0">
                                <?php echo __('{0} services matching to regular expression.', '{{data.serviceCount}}'); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-sortable">
                            <header role="heading">
                                <span class="widget-icon">
                                    <i class="fa fa-filter"></i>
                                </span>
                                <h2><?php echo __('Filter and display options'); ?></h2>
                            </header>
                            <div role="content" style="min-height:513px;">
                                <div class="widget-body">

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_ok}">

                                        <label for="show_ok"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-success notify-label">
                                                <?php echo __('Show Ok'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_ok"
                                                       ng-model="post.Automap.show_ok">
                                                <i class="checkbox-success"></i>
                                            </label>
                                            <div ng-repeat="error in errors.show_ok">
                                                <div class="help-block text-danger">{{ error }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_warning}">

                                        <label for="show_warning"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-warning notify-label">
                                                <?php echo __('Show Warning'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_warning"
                                                       ng-model="post.Automap.show_warning">
                                                <i class="checkbox-warning"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_critical}">

                                        <label for="show_critical"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-danger notify-label">
                                                <?php echo __('Show Critical'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_critical"
                                                       ng-model="post.Automap.show_critical">
                                                <i class="checkbox-danger"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_unknown}">

                                        <label for="show_unknown"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-default notify-label">
                                                <?php echo __('Show Unknown'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_unknown"
                                                       ng-model="post.Automap.show_unknown">
                                                <i class="checkbox-default"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_downtime}">

                                        <label for="show_downtime"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-primary notify-label">
                                                <?php echo __('Show Downtimes'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_downtime"
                                                       ng-model="post.Automap.show_downtime">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group margin-bottom-0"
                                         ng-class="{'has-error': errors.show_acknowledged}">

                                        <label for="show_acknowledged"
                                               class="col col-md-4 control-label padding-top-0">
                                            <span class="label label-primary notify-label">
                                                <?php echo __('Show Acknowledged'); ?>
                                            </span>
                                        </label>

                                        <div class="col-xs-8 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       id="show_acknowledged"
                                                       ng-model="post.Automap.show_acknowledged">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>


                                    <fieldset>
                                        <legend class="font-sm">
                                            <div class="required">
                                                <label>
                                                    <?php echo __('Display options'); ?>
                                                </label>
                                            </div>
                                        </legend>

                                        <div class="form-group margin-bottom-0"
                                             ng-class="{'has-error': errors.show_label}">

                                            <label for="show_label"
                                                   class="col col-md-4 control-label padding-top-0">
                                                <?php echo __('Show Label'); ?>
                                            </label>

                                            <div class="col-xs-8 smart-form">
                                                <label class="checkbox small-checkbox-label no-required">
                                                    <input type="checkbox" name="checkbox"
                                                           ng-true-value="1"
                                                           ng-false-value="0"
                                                           id="show_label"
                                                           ng-model="post.Automap.show_label">
                                                    <i class="checkbox-primary"></i>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group margin-bottom-0"
                                             ng-class="{'has-error': errors.group_by_host}">

                                            <label for="group_by_host"
                                                   class="col col-md-4 control-label padding-top-0">
                                                <?php echo __('Group by host'); ?>
                                            </label>

                                            <div class="col-xs-8 smart-form">
                                                <label class="checkbox small-checkbox-label no-required">
                                                    <input type="checkbox" name="checkbox"
                                                           ng-true-value="1"
                                                           ng-false-value="0"
                                                           id="group_by_host"
                                                           ng-model="post.Automap.group_by_host">
                                                    <i class="checkbox-primary"></i>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group margin-bottom-0"
                                             ng-class="{'has-error': errors.use_paginator}">

                                            <label for="use_paginator"
                                                   class="col col-md-4 control-label padding-top-0">
                                                <?php echo __('Use pagination'); ?>
                                            </label>

                                            <div class="col-xs-8 smart-form">
                                                <label class="checkbox small-checkbox-label no-required">
                                                    <input type="checkbox" name="checkbox"
                                                           ng-true-value="1"
                                                           ng-false-value="0"
                                                           id="use_paginator"
                                                           ng-model="post.Automap.use_paginator">
                                                    <i class="checkbox-primary"></i>
                                                </label>
                                                <div class="help-block">
                                                    <?php echo __('Will may decrease loading performance if disabled.'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group padding-top-10" ng-class="{'has-error': errors.font_size}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('Icon size'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <select
                                                        id="FontsizeSelect"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        ng-model="post.Automap.font_size">
                                                    <option value="1"><?php echo __('Smallest'); ?></option>
                                                    <option value="2"><?php echo __('Smaller'); ?></option>
                                                    <option value="3"><?php echo __('Small'); ?></option>
                                                    <option value="4"><?php echo __('Normal'); ?></option>
                                                    <option value="5"><?php echo __('Big'); ?></option>
                                                    <option value="6"><?php echo __('Bigger'); ?></option>
                                                    <option value="7"><?php echo __('Biggest'); ?></option>
                                                </select>
                                                <div ng-repeat="error in errors.font_size">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-6">
                        <div class="jarviswidget jarviswidget-sortable">
                            <header role="heading">
                                <span class="widget-icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <h2><?php echo __('Icon Preview'); ?></h2>
                            </header>
                            <div role="content">
                                <div class="widget-body">

                                    <div class="row"
                                         ng-if="post.Automap.show_label === 1 && post.Automap.group_by_host === 1">

                                        <div class="col-xs-12">
                                            <h3 class="margin-bottom-5">
                                                <i class="fa fa-desktop"></i>
                                                <strong>
                                                    <?php echo __('Example host'); ?>
                                                </strong>
                                            </h3>
                                        </div>

                                        <div class="col-xs-6 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square up"></i>
                                            <span
                                                    title="<?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>">
                                                <?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-6 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square critical"></i>
                                            <span
                                                    title="<?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>">
                                                <?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row"
                                         ng-if="post.Automap.show_label === 0 && post.Automap.group_by_host === 1">

                                        <div class="col-xs-12">
                                            <h3 class="margin-bottom-5">
                                                <i class="fa fa-desktop"></i>
                                                <strong>
                                                    <?php echo __('Example host'); ?>
                                                </strong>
                                            </h3>
                                        </div>

                                        <div class="col-xs-12 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square up" title="<?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>"></i>
                                            <i class="fa fa-square critical" title="<?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>"></i>
                                        </div>
                                    </div>

                                    <div class="row"
                                         ng-if="post.Automap.show_label === 0 && post.Automap.group_by_host === 0">

                                        <div class="col-xs-12 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square up" title="<?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>"></i>
                                            <i class="fa fa-square critical" title="<?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>"></i>
                                        </div>
                                    </div>

                                    <div class="row"
                                         ng-if="post.Automap.show_label === 1 && post.Automap.group_by_host === 0">

                                        <div class="col-xs-6 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square up"></i>
                                            <span
                                                    title="<?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>">
                                                <?php echo __('Example host'); ?>/<?php echo __('Service 1'); ?>
                                            </span>
                                        </div>
                                        <div class="col-xs-6 ellipsis" ng-style="getFontsize();">
                                            <i class="fa fa-square critical"></i>
                                            <span
                                                    title="<?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>">
                                                <?php echo __('Example host'); ?>/<?php echo __('Service 2'); ?>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">

                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Update Auto Map'); ?>">

                            <a back-button fallback-state='AutomapsIndex'
                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
