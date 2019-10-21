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
            <i class="fa fa-bolt fa-fw"></i>
            <?php echo __('HTTP-Proxy'); ?>
            <span>>
                <?php echo __('Configuration'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bolt"></i> </span>
        <h2><?php echo __('Edit HTTP-Proxy configuration'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Proxy configuration'); ?>' , message: '<?php echo __('updated successfully'); ?>'}">

                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.ipaddress}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Proxy address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    placeholder="proxy.local.lan"
                                    type="text"
                                    ng-model="post.Proxy.ipaddress">
                            <div ng-repeat="error in errors.ipaddress">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.port}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Description'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="number"
                                    placeholder="3128"
                                    min="0"
                                    max="65535"
                                    ng-model="post.Proxy.port">
                            <div ng-repeat="error in errors.port">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"
                         ng-class="{'has-error': errors.enabled}">
                        <label class="col col-md-2 control-label" for="enabledProxy">
                            <?php echo __('Enable Proxy'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="enabledProxy"
                                       ng-model="post.Proxy.enabled">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12 margin-top-10 margin-bottom-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit"
                                   value="<?php echo __('Update configuration'); ?>">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
