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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('openITCOCKPIT Agent'); ?>
            <span>>
                <?php echo __('Checks'); ?>
            </span>
            <div class="third_level">> <?php echo __('Add'); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
        <h2>
            <?php echo __('Create new openITCOCKPIT Agent check'); ?>
        </h2>
        <div class="widget-toolbar" role="menu">
            <button class="btn btn-default" ui-sref="AgentchecksIndex">
                <i class="fa fa-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </button>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Agent check'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
                <div class="row">

                    <div class="col-xs-12 col-md-12 col-lg-8">

                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input class="form-control" type="text" ng-model="post.Agentcheck.name">
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="help-block">
                                    <?php echo __('The name of the check is equal to the key from the agents JSON output.'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.servicetemplate_id}">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Service template'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <select data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicetemplates"
                                        ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                        ng-model="post.Agentcheck.servicetemplate_id">
                                </select>
                                <div ng-repeat="error in errors.servicetemplate_id">
                                    <div class="help-block text-danger">{{ error }}</div>
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
                                       value="<?php echo __('Create agent check'); ?>">
                                <a back-button fallback-state='AgentchecksIndex'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>

            </form>
        </div>
    </div>
</div>
