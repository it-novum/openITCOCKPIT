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
                <?php echo __('User Dashboards'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>

<div class="alert alert-danger alert-block" ng-hide="hasGrafanaConfig">
    <a class="close" data-dismiss="alert" href="#">×</a>
    <h4 class="alert-heading"><?php echo __('No Grafana configuration found!'); ?></h4>
    <?php
    $msg = __('Grafana Configuration');
    if ($this->Acl->hasPermission('index', 'GrafanaConfiguration', 'GrafanaModule')):
        $msg = sprintf('<a href="/grafana_module/grafana_configuration">%s</a>', $msg);
    endif;
    ?>

    <?php echo __('A valid %s is required, before this feature can be used.', $msg); ?>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Create new user defined Grafana dashboard'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a ui-sref="GrafanaUserdashboardsIndex" class="btn btn-default btn-xs" iconcolor="white">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                        <div class="col col-xs-10">
                            <label class="col col-md-2 control-label">
                                <?php echo __('Container'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <select
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="containers"
                                        ng-options="container.key as container.value for container in containers"
                                        ng-model="post.GrafanaUserdashboard.container_id">
                                </select>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group required" ng-class="{'has-error': errors.name}">
                    <div class="col col-xs-10">
                        <label class="col col-md-2 control-label">
                            <?php echo __('User dashboard name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="My awesome Dashboard"
                                    ng-model="post.GrafanaUserdashboard.name">
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">&nbsp;
                                <a ui-sref="GrafanaUserdashboardsIndex" class="btn btn-default">
                                    <?php echo __('Cancel'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>