<?php
// Copyright (C) <2024>  <it-novum GmbH>
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

declare(strict_types=1);

?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-puzzle-piece"></i> <?php echo __('MSTeams Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="MSTeamsSettingsIndex">
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
                    <?php echo __('MSTeams'); ?>
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
                                placeholder="https://mysite.office365.com/myteam-id/channel/mychannel-id/"
                                ng-model="post.webhook_url">
                            <div ng-repeat="error in errors.webhook_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('MSTeams Webhook URL.'); ?>
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
