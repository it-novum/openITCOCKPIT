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
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit configuration'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.GrafanaConfiguration.api_url}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana URL'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="metrics.example.org"
                                    ng-model="post.GrafanaConfiguration.api_url">
                            <div ng-repeat="error in errors.GrafanaConfiguration.api_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.GrafanaConfiguration.api_key}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana API Key'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="ZXhhbXBsZV9ncmFmYW5hX2FwaV9rZXk="
                                    ng-model="post.GrafanaConfiguration.api_key">
                            <div ng-repeat="error in errors.GrafanaConfiguration.api_key">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.GrafanaConfiguration.graphite_prefix}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Grafana Prefix'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="openitcockpit"
                                    ng-model="post.GrafanaConfiguration.graphite_prefix">
                            <div ng-repeat="error in errors.GrafanaConfiguration.graphite_prefix">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('GrafanaConfiguration.use_https', [
                            'caption'          => __('Use HTTPS'),
                            'wrapGridClass'    => 'col col-md-1',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass'     => 'control-label',
                            'ng-model'         => 'post.GrafanaConfiguration.use_https'
                        ]);
                        ?>
                    </div>

                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('GrafanaConfiguration.use_proxy', [
                            'caption'          => __('Use Proxy'),
                            'wrapGridClass'    => 'col col-md-1',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass'     => 'control-label',
                            'ng-model'         => 'post.GrafanaConfiguration.use_proxy'
                        ]);
                        ?>
                    </div>

                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('GrafanaConfiguration.ignore_ssl_certificate', [
                            'caption'          => __('Ignore SSL Certificate'),
                            'wrapGridClass'    => 'col col-md-1',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass'     => 'control-label',
                            'ng-model'         => 'post.GrafanaConfiguration.ignore_ssl_certificate'
                        ]);
                        ?>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.GrafanaConfiguration.dashboard_style}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Dashboard Style'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <select
                                    id="dashboard_style"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="dashboard_style"
                                    ng-model="post.GrafanaConfiguration.dashboard_style">
                                <option value="dark"><?php echo __('dark'); ?></option>
                                <option value="light"><?php echo __('light'); ?></option>
                            </select>
                            <div ng-repeat="error in errors.GrafanaConfiguration.dashboard_style">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.GrafanaConfiguration.Hostgroup}">
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
                                    ng-model="post.GrafanaConfiguration.Hostgroup"
                                    multiple>
                            </select>
                            <div ng-repeat="error in errors.GrafanaConfiguration.Hostgroup">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.GrafanaConfiguration.Hostgroup_excluded}">
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
                                    ng-model="post.GrafanaConfiguration.Hostgroup_excluded"
                                    multiple>
                            </select>
                            <div ng-repeat="error in errors.GrafanaConfiguration.Hostgroup_excluded">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <button type="button"
                                        class="btn text-center"
                                        ng-class="{'btn-success':connectionSuccessful === true, 'btn-danger': connectionSuccessful === false, 'btn-primary': connectionSuccessful == null}"
                                        ng-click="checkGrafanaConnection()">
                                    <?php echo __('Check Grafana Connection'); ?>
                                </button>
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>