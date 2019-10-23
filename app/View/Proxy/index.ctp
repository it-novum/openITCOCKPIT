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
<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fal fa-check-square fa-fw"></i>
        <?php echo __('HTTP-Proxy'); ?>
        <span>>
            <?php echo __('Configuration'); ?>
        </span>
    </h1>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <span class="fw-300"><i>Edit HTTP-Proxy configuration</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" ng-init="successMessage=
            {objectName : '<?php echo __('Proxy configuration'); ?>' , message: '<?php echo __('updated successfully'); ?>'}">

                        <div class="form-group" ng-class="{'has-error': errors.ipaddress}">
                            <label class="form-label required"
                                   for="proxyAddress"> <?php echo __('Proxy address'); ?></label>
                            <input type="text" class="form-control" id="proxyAddress" placeholder="proxy.local.lan"
                                   ng-model="post.Proxy.ipaddress">
                            <div ng-repeat="error in errors.ipaddress">
                                <div class="invalid-feedback">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.port}">
                            <label class="form-label required" for="proxyPort"> <?php echo __('Port'); ?></label>
                            <input class="form-control"
                                   type="number"
                                   placeholder="3128"
                                   min="0"
                                   max="65535" id="proxyPort"
                                   ng-model="post.Proxy.port">
                            <div ng-repeat="error in errors.ipaddress">
                                <div class="invalid-feedback">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.enabled}">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="enabledProxy" name="checkbox"
                                       ng-model="post.Proxy.enabled" required>
                                <label class="custom-control-label"
                                       for="enabledProxy"> <?php echo __('Enable Proxy'); ?></label>
                                <div class="help-block">
                                    <?php echo __('If disabled the proxy server will not be used.'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary ml-auto waves-effect waves-themed"
                                            type="submit"><?php echo __('Update configuration'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>