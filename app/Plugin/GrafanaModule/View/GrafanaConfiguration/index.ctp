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
            <?php
            echo $this->Form->create('GrafanaConfiguration', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row padding-left-20">
                <div>
                    <?php
                    /*
                     * Grafana API URL
                     * Grafana API KEy
                     * Graphite Prefix
                     * Use HTTPS
                     * Ignore SSL Certificate
                     * ??? Include Hostgroups
                     * ??? Exclude Hostgroups
                     * */
                    echo $this->Form->input('GrafanaConfiguration.api_url', [
                        'label' => __('Grafana API URL'),
                        'placeholder' => __('metrics.example.org/api')
                    ]);
                    echo $this->Form->input('GrafanaConfiguration.api_key', [
                        'label' => __('Grafana API Key'),
                        'placeholder' => __('ZXhhbXBsZV9ncmFmYW5hX2FwaV9rZXk=')
                    ]);
                    echo $this->Form->input('GrafanaConfiguration.graphite_prefix', [
                        'label' => __('Graphite Prefix'),
                        'placeholder' => __('openitcockpit')
                    ]);
                    ?>
                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('GrafanaConfiguration.use_https', [
                            'caption' => __('Use HTTPS'),
                            'wrapGridClass' => 'col col-xs-10',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass' => 'control-label',
                            'checked' => $this->CustomValidationErrors->refill(
                                'use_https',
                                (isset($this->request->data['GrafanaConfiguration']['use_https'])) ? $this->request->data['GrafanaConfiguration']['use_https'] : 1
                            )
                        ]);
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo $this->Form->fancyCheckbox('GrafanaConfiguration.ignore_ssl_certificate', [
                            'caption' => __('Ignore SSL Certificate'),
                            'wrapGridClass' => 'col col-xs-10',
                            'captionGridClass' => 'col col-md-2',
                            'captionClass' => 'control-label',
                            'checked' => $this->CustomValidationErrors->refill(
                                'ignore_ssl_certificate',
                                (isset($this->request->data['GrafanaConfiguration']['ignore_ssl_certificate'])) ? $this->request->data['GrafanaConfiguration']['ignore_ssl_certificate'] : 1
                            )
                        ]);
                        ?>
                    </div>
                    <?php
                    echo $this->Form->input('GrafanaConfiguration.dashboard_style', [
                        'options' => [
                            'dark' => 'dark',
                            'light' => 'light'
                        ],
                        'class' => 'chosen',
                        'multiple' => false,
                        'style' => 'width:100%;',
                        'label' => __('<i class="fa fa-plus-square text-success"></i> Dashboard Style'),
                    ]);

                    echo $this->Form->input('GrafanaConfiguration.Hostgroup', [
                        'options' => $hostgroups,
                        'class' => 'chosen',
                        'multiple' => true,
                        'style' => 'width:100%;',
                        'label' => __('<i class="fa fa-plus-square text-success"></i> Hostgroups'),
                        'data-placeholder' => __('Please choose a hostgroup'),
                        'wrapInput' => [
                            'tag' => 'div',
                            'class' => 'col col-xs-10 success'
                        ],
                        'target' => '#GrafanaConfigurationHostgroupExcluded'
                    ]);

                    echo $this->Form->input('GrafanaConfiguration.Hostgroup_excluded', [
                        'options' => $hostgroups,
                        'class' => 'chosen',
                        'multiple' => true,
                        'style' => 'width:100%;',
                        'label' => __('<i class="fa fa-minus-square text-danger"></i> Hostgroups (excluded)'),
                        'data-placeholder' => __('Please choose a hostgroup'),
                        'wrapInput' => [
                            'tag' => 'div',
                            'class' => 'col col-xs-10 danger'
                        ],
                        'target' => '#GrafanaConfigurationHostgroup'
                    ]);
                    ?>
                    <button type="button" id="runGrafanaConnectionTest"
                            class="btn btn-primary text-center pull-right"><?php echo __('Check Grafana Connection'); ?></button>
                </div>
            </div>
        </div>

        <?php echo $this->Form->formActions(); ?>
    </div>
</div>
