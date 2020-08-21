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
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Mattermost Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="MattermostSettingsIndex">
            <i class="fa fa-gears"></i> <?php echo __('Configuration'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-edit"></i> <?php echo __('Edit'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Mattermost'); ?>
                    <span class="fw-300"><i><?php echo __('Configuration'); ?></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal">
                        <div class="form-group required" ng-class="{'has-error':errors.webhook_url}">
                            <label class="control-label">
                                <?php echo __('Webhook URL'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="https://mattermost.example.org/hooks/1nmqus1wsfr988e81sr8whqrte"
                                ng-model="post.webhook_url">
                            <div ng-repeat="error in errors.webhook_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Mattermost Webhook URL.'); ?>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.two_way}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.two_way}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="enableTwoWay"
                                       ng-model="post.two_way">
                                <label class="custom-control-label" for="enableTwoWay">
                                    <?php echo __('Enable Two-way integration'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.apikey}">
                            <label class="control-label">
                                <?php echo __('openITCOCKPIT API Key'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="6d132de3bfb3fa8ea5793fa3b2400a690ed72c5b0b9cfad8a0fd4f20caf4444c56057d4554eea978e5da72a7668d37ad3a50812905d958f5f47a58f6e2c5a2cff1c1ac11cdeb483504f4b80de2814c4d"
                                ng-model="post.apikey">
                            <div ng-repeat="error in errors.apikey">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ApiKeyOverviewModal">
                                    <?= __('API Key') ?>
                                </a>
                                <?= __('used by Mattermost for authentication against openITCOCKPIT.'); ?>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.use_proxy}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.use_proxy}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="use_proxy"
                                       ng-model="post.use_proxy">
                                <label class="custom-control-label" for="use_proxy">
                                    <?php echo __('Use Proxy'); ?>
                                </label>
                                <div class="help-block">
                                    <?php
                                    if ($this->Acl->hasPermission('index', 'proxy', '')):
                                        echo __('Determine if the <a ui-sref="ProxyIndex">configured proxy</a> should be used.');
                                    else:
                                        echo __('Determine if the configured proxy should be used.');
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <input class="btn btn-primary" type="submit"  value="<?= __('Save configuration') ?>">&nbsp;
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('apikey_help'); ?>
