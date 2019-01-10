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
            <i class="fa fa-bolt fa-fw "></i>
            <?php echo __('HTTP-Proxy'); ?>
            <span>>
                <?php echo __('Configuration'); ?>
            </span>
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
                    <div class="form-group required" ng-class="{'has-error': errors.ipaddress}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Proxy address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="proxy.example.org"
                                    ng-model="post.Proxy.ipaddress">
                            <div ng-repeat="error in errors.ipaddress">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.port}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Proxy port'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    placeholder="3128"
                                    ng-model="post.Proxy.port">
                            <div ng-repeat="error in errors.port">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('Proxy.enabled', [
                            'caption'          => __('Enable Proxy'),
                            'wrapGridClass'    => 'col col-md-1',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass'     => 'control-label',
                            'ng-model'         => 'post.Proxy.enabled'
                        ]);
                        ?>
                    </div>


                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>