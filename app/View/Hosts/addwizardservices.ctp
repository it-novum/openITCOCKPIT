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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Add Services'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <div class="tab-content">
                            <div class="row">
                                <label class="col-xs-2 col-md-2 col-lg-2 control-label">
                                    <?php echo __('Hostname'); ?>
                                </label>
                                <div class="col col-xs-10 col-md-10 col-lg-10">
                                    {{ hostname }}
                                </div>
                            </div>
                            <div class="form-group required row" ng-class="{'has-error': errors.service_id}">
                                <label class="col-xs-2 col-md-2 col-lg-2 control-label">
                                    <?php echo __('Standard Service'); ?>
                                </label>
                                <div class="col col-xs-10 col-md-10 col-lg-10">
                                    <select
                                            id="Servicetemplates"
                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                            class="form-control"
                                            chosen="servicetemplates"
                                            ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                            ng-model="post.Servicetemplate.id">
                                    </select>
                                    <div ng-repeat="error in errors.service_id">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- close col -->
                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="Next">&nbsp;
                                <a href="/hosts/index" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- close row-->
            </form>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
