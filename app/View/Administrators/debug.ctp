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

use itnovum\openITCOCKPIT\Core\RepositoryChecker;
use itnovum\openITCOCKPIT\Core\System\Health\LsbRelease;

/** @var RepositoryChecker $RepositoryChecker */
/** @var LsbRelease $LsbRelease */

?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bug fa-fw "></i>
            {{interfaceInformation.systemname}}
            <span>>
                <?php echo __('Debugging information'); ?>
            </span>
        </h1>
    </div>

    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5" ng-show="interfaceInformation.oitc_is_debugging_mode">
        <div class="pull-right">
            <a ui-sref="AdministratorsQuerylog" class="btn btn-default btn-xl btn-block">
                <i class="fa fa-database"></i>
                <?php echo __('Show SQL query log'); ?>
            </a>
        </div>
    </div>
</div>

<div id="error_msg" ng-hide="processInformation.gearmanReachable">
    <div class="alert alert-danger alert-block">
        <h5 class="alert-heading"><i class="fa fa-warning"></i>
            <?php echo __('Critical error!'); ?>
        </h5>
        <?php echo __('Could not connect to Gearman Job Server! No background tasks will get executed!'); ?>
    </div>
</div>


<?php echo $this->element('repository_checker'); ?>

<?php if ($LsbRelease->getCodename() === 'trusty'): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">
            <i class="fa fa-warning"></i>
            <?php echo __('Ubuntu Trusty 14.04 end of life!'); ?>
        </h4>
        <?php echo __('Official end of life of Ubuntu Trusty scheduled for April 2019.'); ?>
        <?php echo __('Therefore openITCOCKPIT 3.5 will be the last release for Ubuntu Trusty. Please update to Ubuntu Xenial to receive further updates.'); ?>
        <br/>
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support %s.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
    </div>
<?php endif; ?>

<?php if ($LsbRelease->getCodename() === 'jessie'): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">
            <i class="fa fa-warning"></i>
            <?php echo __('Debian Jessie 8 end of life!'); ?>
        </h4>
        <?php echo __('Debian Jessie is not supported by the Debian security team anymore!'); ?>
        <?php echo __('Therefore openITCOCKPIT 3.5 will be the last release for Debian Jessie. Please update to Debian Stretch to receive further updates.'); ?>
        <br/>
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support %s.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
    </div>
<?php endif; ?>

<div class="row">

    <div class="col-xs-12 col-md-6" style="padding-left: 0;">

        <div class="jarviswidget">
            <header>
                <span class="widget-icon"> <i class="fa fa-globe"></i></span>
                <h2><?php echo __('Interface information'); ?></h2>
            </header>
            <div>
                <div class="widget-body padding-10" style="min-height: 215px;">
                    <dl class="dl-horizontal">
                        <dt><?php echo __('System name'); ?>:</dt>
                        <dd>{{interfaceInformation.systemname}}</dd>

                        <dt><?php echo __('Version'); ?>:</dt>
                        <dd>{{interfaceInformation.version}}</dd>

                        <dt><?php echo __('Edition'); ?>:</dt>
                        <dd>{{interfaceInformation.edition}}</dd>

                        <dt><?php echo __('Monitoring Engine'); ?>:</dt>
                        <dd>{{interfaceInformation.monitoring_engine}}</dd>

                        <dt><?php echo __('Path for config'); ?>:</dt>
                        <dd>{{interfaceInformation.path_for_config}}</dd>

                        <dt><?php echo __('Path for backups'); ?>:</dt>
                        <dd>{{interfaceInformation.path_for_backups}}</dd>

                        <dt><?php echo __('Command interface'); ?>:</dt>
                        <dd>{{interfaceInformation.command_interface}}</dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xs-12 col-md-6" style="padding-right:0;">

        <div class="jarviswidget">
            <header>
                <span class="widget-icon"> <i class="fa fa-cogs"></i></span>
                <h2><?php echo __('Process information'); ?></h2>
            </header>
            <div>
                <div class="widget-body padding-10" ng-hide="processInformation.gearmanReachable" style="min-height: 215px;">
                    <div id="error_msg">
                        <div class="alert alert-danger alert-block">
                            <h5 class="alert-heading"><i class="fa fa-warning"></i>
                                <?php echo __('Error'); ?>
                            </h5>
                            <?php echo __('Gearman-Job-Server is not running, openITCOCKPIT could not check the state of background daemons'); ?>
                            <br/>
                            <?php echo __('Please start Gearman-Job-Server first'); ?>
                        </div>
                    </div>
                </div>

                <div class="widget-body padding-10" ng-hide="processInformation.isGearmanWorkerRunning" style="min-height: 215px;">
                    <div id="error_msg">
                        <div class="alert alert-danger alert-block">
                            <h5 class="alert-heading"><i class="fa fa-warning"></i>
                                <?php echo __('Error'); ?></h5>
                            <?php echo __('gearman_worker is not running, openITCOCKPIT could not check the state of background daemons'); ?>
                            <br/>
                            <?php echo __('Please start gearman_worker first'); ?>
                        </div>
                    </div>
                </div>


                <div class="widget-body padding-10"
                     ng-show="processInformation.gearmanReachable && processInformation.isGearmanWorkerRunning" style="min-height: 215px;">
                    <dl class="dl-horizontal">
                        <dt><?php echo __('Monitoring engine'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isNagiosRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isNagiosRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>
                            <a data-original-title="{{interfaceInformation.monitoring_engine}}" data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('Database connector'); ?>:</dt>
                        <dd ng-if="processInformation.isStatusengineInstalled">
                    <span ng-show="processInformation.backgroundProcesses.isStatusengineRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isStatusengineRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('Statusengine'); ?>" data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>
                        <dd ng-if="processInformation.isNdoInstalled">
                    <span ng-show="processInformation.backgroundProcesses.isNdoRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isNdoRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('NDOUtils'); ?>" data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('Perfdata processor'); ?>:</dt>
                        <dd ng-if="processInformation.isStatusengineInstalled">
                    <span ng-show="processInformation.backgroundProcesses.isStatusengineRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isStatusengineRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('Statusengine'); ?>" data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>
                        <dd ng-if="!processInformation.isStatusenginePerfdataProcessor">
                    <span ng-show="processInformation.backgroundProcesses.isNpcdRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isNpcdRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('NPCD'); ?>" data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('Queuing engine'); ?>:</dt>
                        <dd>
                            <span class="ok"><i class="fa fa-check"></i> <?php echo __('Running'); ?></span>
                            <a data-original-title="<?php echo h('openITCOCKPIT uses the Gearman Job Server to run different background tasks'); ?>"
                               data-placement="right" rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('Gearman Worker'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isGearmanWorkerRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isGearmanWorkerRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('Execute background jobs like refresh of monitoring configuration.'); ?>"
                               data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('OITC Cmd'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isOitcCmdRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isOitcCmdRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('External command interface used by Check_MK to pass check results.'); ?>"
                               data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('phpNSTA'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isPhpNstaRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isPhpNstaRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('phpNSTA is only installed and running if you are using Distributed Monitoring.'); ?>"
                               data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('Push notification service'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isPushNotificationRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isPushNotificationRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('Service required to send push notifications to your browser window.'); ?>"
                               data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>

                        <dt><?php echo __('NodeJS Server'); ?>:</dt>
                        <dd>
                    <span ng-show="processInformation.backgroundProcesses.isNodeJsServerRunning"
                          class="ok"><i class="fa fa-check"></i><?php echo __('Running'); ?></span>
                            <span ng-hide="processInformation.backgroundProcesses.isNodeJsServerRunning"
                                  class="critical"><i
                                        class="fa fa-close"></i><?php echo __('Not running!'); ?></span>

                            <a data-original-title="<?php echo __('Service required to run server side JavaScript to render charts to email notifications and PDF reports.'); ?>"
                               data-placement="right"
                               rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                        class="fa fa-info-circle"></i></a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-hdd-o"></i></span>
        <h2><?php echo __('Server information'); ?></h2>
    </header>
    <div>
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Address'); ?>:</dt>
                <dd>{{serverInformation.address}}</dd>

                <dt><?php echo __('Webserver'); ?>:</dt>
                <dd>{{serverInformation.webserver}}</dd>

                <dt><?php echo __('HTTPS / TLS'); ?>:</dt>
                <dd>{{serverInformation.tls}}</dd>

                <dt><?php echo __('OS'); ?>:</dt>
                <dd>{{serverInformation.os_version}}</dd>

                <dt><?php echo __('Kernel'); ?>:</dt>
                <dd>{{serverInformation.kernel}}</dd>

                <dt><?php echo __('Architecture'); ?>:</dt>
                <dd>{{serverInformation.architecture}}</dd>

                <dt><?php echo __('PHP version'); ?>:</dt>
                <dd>{{serverInformation.php_version}}</dd>

                <dt><?php echo __('PHP Memory limit'); ?>:</dt>
                <dd>{{serverInformation.php_memory_limit}}</dd>

                <dt><?php echo __('PHP Max. execution time'); ?>:</dt>
                <dd>{{serverInformation.php_max_execution_time}}</dd>

                <dt><?php echo __('PHP loaded extensions'); ?>:</dt>
                <dd>{{serverInformation.php_extensions.join(', ')}}</dd>

                <dt><?php echo __('CPU model'); ?>:</dt>
                <dd>{{serverInformation.cpu_processor}}</dd>

                <dt><?php echo __('Number of CPU cores'); ?>:</dt>
                <dd>{{serverInformation.cpu_cores}}</dd>
            </dl>
        </div>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-bar-chart"></i></span>
        <h2><?php echo __('CPU load'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>
        </div>

        <div class="widget-toolbar form-group smart-form" role="menu">
            <label class="checkbox small-checkbox-label display-inline margin-right-5">
                <input type="checkbox" name="checkbox" checked="checked"
                       ng-model="graph.keepHistory">
                <i class="checkbox-primary"></i>
                <?php echo __('Keep history'); ?>
            </label>
        </div>

        <div class="widget-toolbar">
            <?php echo __('Current load:'); ?> {{currentCpuLoad['1']}}, {{currentCpuLoad['5']}},
            {{currentCpuLoad['15']}}
        </div>

    </header>
    <div>
        <div class="widget-body padding-10">

            <div class="well" ng-show="!renderGraph">
                {{currentCpuLoad['1']}}, {{currentCpuLoad['5']}}, {{currentCpuLoad['15']}}
            </div>
            <div ng-show="renderGraph">
                <div class="graph_legend">
                    <table style="font-size: 11px; color:#545454">
                        <tbody>
                        <tr>
                            <td class="legendColorBox">
                                <div style="">
                                    <div style="border:2px solid #6595B4;overflow:hidden"></div>
                                </div>
                            </td>
                            <td class="legendLabel">
                                <span><?php echo __('1 Minute'); ?></span>
                            </td>
                            <td class="legendColorBox">
                                <div style="">
                                    <div style="border:2px solid #7E9D3A;overflow:hidden"></div>
                                </div>
                            </td>
                            <td class="legendLabel">
                                <span><?php echo __('5 Minutes'); ?></span>
                            </td>
                            <td class="legendColorBox">
                                <div style="">
                                    <div style="border:2px solid #E24913;overflow:hidden"></div>
                                </div>
                            </td>
                            <td class="legendLabel">
                                <span><?php echo __('15 Minutes'); ?></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id="graph_data_tooltip"></div>
                <div id="graphCanvas" style="height: 300px;"></div>
            </div>
        </div>

    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pie-chart"></i></span>
        <h2><?php echo __('Memory and disk usage'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>
        </div>

    </header>
    <div>
        <div class="widget-body padding-10">
            <b><?php echo __('Memory usage'); ?>:</b>
            <div class="well">
                <?php echo __('Total:'); ?> {{memory.memory.total}}

                <span class="txt-color-green"><?php echo __('Used:'); ?>
                    {{memory.memory.used}}MB
                </span>

                <span class="txt-color-orange"><?php echo __('Cached:'); ?>
                    {{memory.memory.cached}}MB
                </span>

                <span class="txt-color-blue"><?php echo __('Buffers:'); ?>
                    {{memory.memory.buffers}}MB
                </span>

                <div class="progress" style="margin-bottom: 0px;">
                    <div style="width: {{(memory.memory.used / memory.memory.total) * 100 }}%; position: unset;"
                         class="progress-bar bg-ok">
                        <span ng-show="memory.memory.used > 50">{{memory.memory.used}}MB</span>
                    </div>
                    <div style="width: {{(memory.memory.cached / memory.memory.total) * 100 }}%; position: unset;"
                         class="progress-bar bg-warning">
                        <span ng-show="memory.memory.cached > 10">{{memory.memory.cached}}MB</span>
                    </div>
                    <div style="width: {{(memory.memory.buffers / memory.memory.total) * 100 }}%; position: unset;"
                         class="progress-bar bg-downtime">
                        <span ng-show="memory.memory.buffers > 10">{{memory.memory.buffers}}MB</span>
                    </div>

                </div>
            </div>

            <br/>
            <b><?php echo __('Swap usage'); ?>:</b>
            <div class="well">
                <?php echo __('Total:'); ?> {{memory.swap.total}}

                <span class="txt-color-red"><?php echo __('Used:'); ?>
                    {{memory.swap.used}}MB
                </span>


                <div class="progress" style="margin-bottom: 0px;">
                    <div style="width: {{(memory.swap.used / memory.swap.total) * 100 }}%; position: unset;"
                         class="progress-bar bg-critical">
                        <span ng-show="memory.swap.used > 50">{{memory.swap.used}}MB</span>
                    </div>
                </div>
            </div>


            <br/>
            <b><?php echo __('Disk usage'); ?>:</b>

            <div class="well" ng-repeat="disk in diskUsage">
                <b>{{disk.disk}}</b>
                (
                <?php echo __('Size'); ?>: {{disk.size}}
                <?php echo __('Available'); ?>: {{disk.avail}}
                <?php echo __('Mount point'); ?>: {{disk.mountpoint}}
                )


                <div class="progress" style="margin-bottom: 0px;">
                    <div style="width: {{disk.use_percentage}}%; position: unset;"
                         class="progress-bar bg-downtime">
                        <span ng-show="disk.use_percentage > 5">{{disk.use_percentage}}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-list-ol"></i></span>
        <h2><?php echo __('Queuing engine'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>
        </div>

    </header>
    <div>
        <div class="widget-body padding-10">
            <table class="table table-striped table-hover table-bordered smart-form" style="">
                <thead>
                <tr>
                    <th class="no-sort">
                        <?php echo __('Queue name'); ?>
                    </th>
                    <th class="no-sort">
                        <?php echo __('Jobs waiting'); ?>
                    </th>
                    <th class="no-sort">
                        <?php echo __('Active jobs'); ?>
                    </th>
                    <th class="no-sort">
                        <?php echo __('Worker available'); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="(queueName, queue) in gearmanStatus"
                    class="{{getGearmanStatusClass(queue.jobs, queue.worker)}}">
                    <td>{{queueName}}</td>
                    <td>{{queue.jobs}}</td>
                    <td>{{queue.running}}</td>
                    <td>{{queue.worker}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-envelope"></i></span>
        <h2><?php echo __('Email configuration'); ?></h2>
    </header>
    <div>
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Mail server address'); ?>:</dt>
                <dd>{{emailInformation.host}}</dd>

                <dt><?php echo __('Mail server port'); ?>:</dt>
                <dd>{{emailInformation.port}}</dd>

                <dt><?php echo __('Transport protocol'); ?>:</dt>
                <dd>{{emailInformation.transport}}</dd>

                <dt><?php echo __('Username'); ?>:</dt>
                <dd>{{emailInformation.username}}</dd>

                <dt><?php echo __('Password'); ?>:</dt>
                <dd>
                    <i><?php echo __('Password hidden due to security please see the file /etc/openitcockpit/app/Config/email.php for detailed configuration information.'); ?></i>
                </dd>

                <dt>&nbsp;</dt>
                <dd>
                    <button class="btn btn-xs btn-default" ng-click="sendTestMail()">
                        <?php echo __('Send test Email to'); ?> {{emailInformation.test_mail_address}}
                    </button>
                </dd>
            </dl>
        </div>


    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-desktop"></i></span>
        <h2><?php echo __('Client information'); ?></h2>
    </header>
    <div>
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Client OS'); ?>:</dt>
                <dd>{{userInformation.user_os}}</dd>

                <dt><?php echo __('Client browser'); ?>:</dt>
                <dd>{{userInformation.user_agent}}</dd>

                <dt><?php echo __('Client IP address'); ?>:</dt>
                <dd>{{userInformation.user_remote_address}}</dd>
            </dl>
        </div>
    </div>
</div>

<!-- php info -->
<div style="margin-left: 20px;" id="phpinfo">
    <?php
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();
    $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
    echo $pinfo; ?>
</div>
