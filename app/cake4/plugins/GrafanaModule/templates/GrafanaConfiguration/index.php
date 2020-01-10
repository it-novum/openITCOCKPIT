<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
?>
<div class="alert auto-hide alert-success" style="display:none;"
     id="flashMessage"></div>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-gears fa-fw "></i>
            <?php echo __('Grafana'); ?>
            <span>>
                <?php echo __('Configuration'); ?>
            </span>
            <div class="third_level"> <?php echo __('Overview'); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit configuration'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.api_url}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana URL'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                placeholder="metrics.example.org"
                                ng-model="post.api_url">
                            <div ng-repeat="error in errors.api_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.api_key}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana API Key'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                placeholder="ZXhhbXBsZV9ncmFmYW5hX2FwaV9rZXk="
                                ng-model="post.api_key">
                            <div ng-repeat="error in errors.api_key">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.graphite_prefix}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana Prefix'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                placeholder="openitcockpit"
                                ng-model="post.graphite_prefix">
                            <div ng-repeat="error in errors.graphite_prefix">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="enableHttps">
                            <?php echo __('Connect via HTTPS'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="enableHttps"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.use_https">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="use_proxy">
                            <?php echo __('Use Proxy'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="use_proxy"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.use_proxy">
                                <i class="checkbox-primary"></i>
                            </label>
                            <div class="help-block">
                                <?php
                                if ($this->Acl->hasPermission('index', 'proxy', '')):
                                    echo __('Determine if the <a ui-sref="ProxyIndex">configured proxy</a> shoud be used.');
                                else:
                                    echo __('Determine if the configured proxy shoud be used.');
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="ignore_ssl_certificate">
                            <?php echo __('Ignore SSL certificate'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="ignore_ssl_certificate"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       ng-model="post.ignore_ssl_certificate">
                                <i class="checkbox-primary"></i>
                            </label>
                            <div class="help-block">

                                <?php echo __('Disable certificate validation to allow usage of self-signed certificates.'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.dashboard_style}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Dashboard Style'); ?>
                        </label>
                        <div class="col col-xs-10">
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
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.Hostgroup}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Hostgroups'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                id="dashboard_style"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hostgroups"
                                ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                ng-model="post.Hostgroup"
                                multiple>
                            </select>
                            <div class="help-block">
                                <?= __('Only generate dashboards for hosts in the selected host groups. If empty the system will generate a dashboard for all hosts.'); ?>
                            </div>
                            <div ng-repeat="error in errors.Hostgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"
                         ng-class="{'has-error': errors.Hostgroup_excluded}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Hostgroups (excluded)'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                id="MapContainer"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hostgroups"
                                ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                                ng-model="post.Hostgroup_excluded"
                                multiple>
                            </select>
                            <div class="help-block">
                                <?= __('Do not generate dashboards for hosts in the selected host groups.'); ?>
                            </div>
                            <div ng-repeat="error in errors.Hostgroup_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
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

                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <button type="button"
                                        class="btn text-center btn-primary"
                                        ng-click="checkGrafanaConnection()">
                                    <?php echo __('Check Grafana Connection'); ?>
                                </button>
                                <input class="btn btn-primary" type="submit" value="<?= __('Save') ?>">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
