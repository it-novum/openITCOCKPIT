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
            <i class="fa fa-line-chart fa-fw "></i>
            <?php echo __('Anonymous statistics'); ?>
        </h1>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-line-chart"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('We ask for your help'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <div class="row form-horizontal">

                <div class="alert alert-info" ng-show="settings.Systemsetting.value === 0">
                    <i class="fa-fw fa fa-times"></i>
                    <?php echo __('Sending of anonymous statistics is currently disabled.'); ?>
                    <button class="btn btn-xs btn-success pull-right" ng-click="save(1)">
                        <?php echo __('Enable'); ?>
                    </button>
                </div>

                <div class="alert alert-info" ng-show="settings.Systemsetting.value === 2">
                    <i class="fa-fw fa fa-times"></i>
                    <?php echo __('Sending of anonymous statistics is currently disabled. Waiting for your approval.'); ?>
                    <button class="btn btn-xs btn-success pull-right" ng-click="save(1)">
                        <?php echo __('Enable'); ?>
                    </button>
                </div>

                <div class="alert alert-success" ng-show="settings.Systemsetting.value === 1">
                    <i class="fa-fw fa fa-check"></i>
                    <?php echo __('Sending of anonymous statistics is currently enabled. Many thanks for your support!'); ?>
                    <button class="btn btn-xs btn-danger pull-right" ng-click="save(0)">
                        <?php echo __('Disable'); ?>
                    </button>
                </div>


                <div class="col-xs-12 margin-top-10">
                    <h4><?php echo __('What data do we collect?'); ?></h4>
                </div>
                <div class="col-xs-12">
                    <p><?php echo __('We are not interested in who you are, in which company you work or any other personal data.'); ?></p>
                    <p>
                        <?php echo __('We just ask you, to provide us system metrics like:'); ?>
                    </p>
                    <ul>
                        <li><?php echo __('CPU load, number of CPU cores and CPU model'); ?></li>
                        <li><?php echo __('Memory and swap usage'); ?></li>
                        <li><?php echo __('Version of used monitoring engine'); ?></li>
                        <li><?php echo __('Version of PHP'); ?></li>
                        <li><?php echo __('Version of openITCOCKPIT and installed openITCOCKPIT modules'); ?></li>
                        <li><?php echo __('Number of monitored hosts and services'); ?></li>
                        <li><?php echo __('Used operating system'); ?></li>
                        <li><?php echo __('MySQL usage statistics like number of select, insert and delete statements'); ?></li>
                    </ul>
                </div>

                <div class="col-xs-12 margin-top-10">
                    <h4><?php echo __('Do you track users on the interface?'); ?></h4>
                </div>

                <div class="col-xs-12">
                    <?php echo __('No. We are not interested in any user or user behavior data.'); ?>
                </div>


                <div class="col-xs-12 margin-top-10">
                    <h4><?php echo __('What records would you like to send exactly?'); ?></h4>
                </div>
                <div class="col-xs-12">
                    <p><?php echo __('To be as transparent as possible, we show you all records that will be send to us. (This data was generated on your system.)'); ?></p>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                            <textarea class="form-control" rows="15"
                                      readonly><?php echo h($statisticsAsJson); ?></textarea>
                    </div>
                </div>

                <div class="col-xs-12 margin-top-10">
                    <h5><?php echo __('What is the'); ?> <code>system_id</code>?</h5>
                </div>
                <div class="col-xs-12">
                    <?php echo __(
                        'The system_id is a unique number that was generated on your system.
                                The system_id is included in every request, to enable our database to locate your record and update it.
                        '); ?>
                    <br/>
                    <strong>
                        <?php echo __('The system_id will not be linked to any other records.'); ?>
                    </strong>
                    <br/>

                    <?php echo __('If you want to change your system_id, you can delete the file'); ?>
                    <code>/opt/openitc/etc/system-id</code>.
                    <?php echo __('openITCOCKPIT will generate a new system_id, if required.'); ?>

                </div>


                <div class="col-xs-12 margin-top-10">
                    <h4><?php echo __('Why do you ask for this data?'); ?></h4>
                </div>
                <div class="col-xs-12">
                    <p><?php echo __('
                            This information influences decisions such as which openITCOCKPIT modules should
                            get new features or which operating system is used by most of the users.

                            In addition the data will help use to optimize the database schema and improve scalability of
                            openITCOCKPIT.
                        '); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
