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
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Grafana Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="GrafanaConfigurationIndex">
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
                    <?php echo __('Grafana'); ?>
                    <span class="fw-300"><i><?php echo __('Configuration'); ?></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal">
                        <div class="form-group required" ng-class="{'has-error':errors.api_url}">
                            <label class="control-label">
                                <?php echo __('Grafana URL'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="metrics.example.org"
                                ng-model="post.api_url">
                            <div ng-repeat="error in errors.api_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.api_key}">
                            <label class="control-label">
                                <?php echo __('Grafana API Key'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="ZXhhbXBsZV9ncmFmYW5hX2FwaV9rZXk="
                                ng-model="post.api_key">
                            <div ng-repeat="error in errors.api_key">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.graphite_prefix}">
                            <label class="control-label">
                                <?php echo __('Grafana Prefix'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="openitcockpit"
                                ng-model="post.graphite_prefix">
                            <div ng-repeat="error in errors.graphite_prefix">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.use_https}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.use_https}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="enableHttps"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.use_https">
                                <label class="custom-control-label" for="enableHttps">
                                    <?php echo __('Connect via HTTPS'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.use_proxy}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.use_proxy}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="use_proxy"
                                       ng-true-value="1"
                                       ng-false-value="0"
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

                        <div class="form-group" ng-class="{'has-error': errors.ignore_ssl_certificate}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.ignore_ssl_certificate}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="ignore_ssl_certificate"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.ignore_ssl_certificate">
                                <label class="custom-control-label" for="ignore_ssl_certificate">
                                    <?php echo __('Ignore SSL certificate'); ?>
                                </label>
                                <div class="help-block">
                                    <?php echo __('Disable certificate validation to allow usage of self-signed certificates.'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.dashboard_style}">
                            <label class="control-label" for="dashboard_style">
                                <?php echo __('Dashboard Style'); ?>
                            </label>
                            <select
                                id="dashboard_style"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="dashboard_style"
                                ng-model="post.dashboard_style">
                                <option value="dark"><?php echo __('dark'); ?></option>
                                <option value="light"><?php echo __('light'); ?></option>
                            </select>
                            <div ng-repeat="error in errors.dashboard_style">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.Hostgroup}">
                            <label class="control-label" for="hostgroups">
                                <?php echo __('Hostgroups'); ?>
                            </label>
                            <select
                                id="hostgroups"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hostgroups"
                                ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups"
                                ng-model="post.Hostgroup"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.Hostgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Only generate dashboards for hosts in the selected host groups. If empty the system will generate a dashboard for all hosts.'); ?>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.Hostgroup_excluded}">
                            <label class="control-label" for="hostgroupsExcluded">
                                <?php echo __('Hostgroups (excluded)'); ?>
                            </label>
                            <select
                                id="hostgroupsExcluded"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hostgroups_excluded"
                                ng-options="hostgroup.key as hostgroup.value disable when hostgroup.disabled for hostgroup in hostgroups_excluded"
                                ng-model="post.Hostgroup_excluded"
                                multiple>
                            </select>
                            <div ng-repeat="error in errors.Hostgroup_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Only generate dashboards for hosts in the selected host groups. If empty the system will generate a dashboard for all hosts.'); ?>
                            </div>
                        </div>

                        <div class="alert alert-danger alert-block" ng-show="hasError">
                            <h4 class="alert-heading">{{ grafanaErrors.status }} - {{ grafanaErrors.statusText }}</h4>
                            {{ grafanaErrors.message }}
                        </div>

                        <div class="alert alert-success" ng-show="hasError === false">
                            <i class="fa-fw fa fa-check"></i>
                            <?php echo __('Connection established successfully.'); ?>
                        </div>

                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <?php if ($this->Acl->hasPermission('testGrafanaConnection', 'GrafanaConfiguration', 'GrafanaModule')): ?>
                                        <button class="btn btn-primary" ng-click="checkGrafanaConnection()">
                                            <?php echo __('Check Grafana Connection'); ?>
                                        </button>
                                    <?php endif; ?>
                                    <input class="btn btn-primary" type="submit" value="<?= __('Save') ?>">&nbsp;
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
