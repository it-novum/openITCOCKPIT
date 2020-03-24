<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-secret fa-fw "></i>
            <?php echo __('openITCOCKPIT Agent'); ?>
            <span>>
                <?php echo __('Configuration'); ?>
            </span>
            <div class="third_level">> {{host.name}}</div>
        </h1>
    </div>
</div>


<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget">
                <header>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-user-secret"></i> </span>
                    <h2 class="hidden-mobile">
                        <?php echo __('Agent configuration for device:'); ?>
                        {{host.name}}
                    </h2>

                </header>
                <div>


                    <div class="widget-body">
                        <form ng-submit="submit();" class="form-horizontal"
                              ng-init="successMessage=
            {objectName : '<?php echo __('Agent configuration'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                            <div class="row">

                                <div class="col-xs-12 col-md-12 col-lg-8">

                                    <div class="row">
                                        <div class="form-group required" ng-class="{'has-error': errors.port}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('Port number'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input
                                                        class="form-control"
                                                        type="number"
                                                        min="0"
                                                        max="65000"
                                                        ng-model="post.Agentconfig.port">
                                                <div ng-repeat="error in errors.port">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group"
                                         ng-class="{'has-error': errors.use_https}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="useHttps">
                                            <?php echo __('Use HTTPS'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-10 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       id="useHttps"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Agentconfig.use_https">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.insecure}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="insecure">
                                            <?php echo __('Enable lazy HTTPS'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-10 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       id="insecure"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Agentconfig.insecure">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="help-block">
                                                <?php echo __('Disable strict certificate check. This is less secure! (For example to use self-signed certificates)'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"
                                         ng-class="{'has-error': errors.insecure}">
                                        <label class="col-xs-12 col-lg-2 control-label" for="insecure">
                                            <?php echo __('Enable basic auth'); ?>
                                        </label>

                                        <div class="col-xs-12 col-lg-10 smart-form">
                                            <label class="checkbox small-checkbox-label no-required">
                                                <input type="checkbox" name="checkbox"
                                                       id="insecure"
                                                       ng-true-value="1"
                                                       ng-false-value="0"
                                                       ng-model="post.Agentconfig.basic_auth">
                                                <i class="checkbox-primary"></i>
                                            </label>
                                            <div class="help-block">
                                                <?php echo __('Enable HTTP basic auth.'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group required" ng-class="{'has-error': errors.username}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('Username'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input
                                                        class="form-control"
                                                        type="text"
                                                        ng-disabled="post.Agentconfig.basic_auth == 0"
                                                        ng-model="post.Agentconfig.username">
                                                <div ng-repeat="error in errors.username">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group required" ng-class="{'has-error': errors.password}">
                                            <label class="col col-md-2 control-label">
                                                <?php echo __('Password'); ?>
                                            </label>
                                            <div class="col col-xs-10">
                                                <input
                                                        class="form-control"
                                                        type="password"
                                                        ng-disabled="post.Agentconfig.basic_auth == 0"
                                                        ng-model="post.Agentconfig.password">
                                                <div ng-repeat="error in errors.password">
                                                    <div class="help-block text-danger">{{ error }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                                    <div class="well formactions ">
                                        <div class="pull-right">
                                            <input class="btn btn-primary" type="submit"
                                                   value="<?php echo __('Update agent config and execute discovery.'); ?>">
                                            <a back-button href="javascript:void(0);" fallback-state='HostsIndex'
                                               class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
