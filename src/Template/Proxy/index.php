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
        <a ui-sref="ProxyIndex">
            <i class="fa fa-bolt"></i> <?php echo __('HTTP-Proxy'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('Configuration'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('HTTP-Proxy'); ?>
                    <span class="fw-300"><i><?php echo __('Edit configuration'); ?></i></span>
                </h2>
                <div class="panel-toolbar">

                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal"
                          ng-init="successMessage=
            {objectName : '<?php echo __('Proxy configuration'); ?>' , message: '<?php echo __('updated successfully'); ?>'}">

                        <div class="form-group required">
                            <label class="control-label">
                                <?php echo __('Proxy address and Port'); ?>
                            </label>
                            <div class="input-group">
                                <input
                                    class="form-control"
                                    placeholder="proxy.local.lan"
                                    type="text"
                                    post.Proxy.ipaddress>
                                <div ng-repeat="error in errors.ipaddress">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="input-group-append input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-ethernet fs-xl"></i></span>
                                </div>
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

                        <div class="form-group" ng-class="{'has-error': errors.enabled}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.enabled}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="enabledProxy"
                                       ng-model="post.Proxy.enabled">
                                <label class="custom-control-label" for="enabledProxy">
                                    <?php echo __('Enable proxy'); ?>
                                </label>
                            </div>

                            <div class="col col-xs-12 col-md-offset-2 help-block">
                                <?php echo __('If disabled the proxy server will not be used.'); ?>
                            </div>
                        </div>

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <button class="btn btn-primary" type="submit">
                                        <?php echo __('Update Configuration'); ?>
                                    </button>
                                    <a back-button href="javascript:void(0);" fallback-state='DashboardsIndex' class="btn btn-default">
                                        <?php echo __('Cancel'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
