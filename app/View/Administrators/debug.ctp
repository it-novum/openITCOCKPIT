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
<div class="row">
    <div class="col-xs-12 col-lg-10">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bug fa-fw "></i>
            <?php echo __('Debugging information'); ?>
        </h1>
    </div>

    <?php if($isDebuggingMode): ?>
    <div class="col-xs-12 col-lg-2">
        <div class="pull-right">
            <a href="/Administrators/querylog" class="btn btn-default btn-xl btn-block">
                <i class="fa fa-database"></i>
                <?php echo __('Show SQL query log'); ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if (!$gearmanReachable): ?>
    <div id="error_msg">
        <div class="alert alert-danger alert-block">
            <a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i
                        class="fa fa-warning"></i> <?php echo __('Error'); ?></h5>
            <?php echo __('Could not connect to Gearman Job Server! No background tasks will be executed!'); ?>
        </div>
    </div>
<?php endif; ?>

<?php echo $this->element('repository_checker'); ?>

<div id="error_msg"></div>

<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false"  data-widget-colorbutton="false"
      data-widget-fullscreenbutton="true"  data-widget-editbutton="true"  data-widget-togglebutton="false"
      style="position: relative; opacity: 1; left: 0px; top: 0px;"  role="widget">
    <header>
        <span class="widget-icon"> <i class="fa fa-globe"></i></span>
        <h2><?php echo __('Interface information'); ?></h2>
    </header>
    <!-- widget div-->
    <div>
        <!-- end widget edit box -->
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <?php
                Configure::load('nagios');
                Configure::load('version');
                ?>

                <dt><?php echo __('System name'); ?>:</dt>
                <dd><?php echo h($systemsetting['FRONTEND']['FRONTEND.SYSTEMNAME']); ?></dd>
                <dt><?php echo __('Version'); ?>:</dt>
                <dd><?php echo h(Configure::read('version')); ?></dd>
                <dt><?php echo __('Edition'); ?>:</dt>
                <dd><?php echo ($isEnterprise) ? __('Enterprise') : __('Community'); ?></dd>
                <dt><?php echo __('Path for config'); ?>:</dt>
                <dd><?php echo h(Configure::read('nagios.export.backupSource')); ?></dd>
                <dt><?php echo __('Path for backups'); ?>:</dt>
                <dd><?php echo h(Configure::read('nagios.export.backupTarget')); ?></dd>
                <dt><?php echo __('Command interface'); ?>:</dt>
                <dd><?php echo h($systemsetting['MONITORING']['MONITORING.CMD']); ?></dd>
            </dl>
        </div>

    </div>
</div>

<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false"  data-widget-colorbutton="false"
      data-widget-fullscreenbutton="true"  data-widget-editbutton="true"  data-widget-togglebutton="false"
      style="position: relative; opacity: 1; left: 0px; top: 0px;"  role="widget">
    <header>
        <span class="widget-icon"> <i class="fa fa-cogs"></i></span>
        <h2><?php echo __('Process information'); ?></h2>
    </header>
    <!-- widget div-->
    <div>
        <!-- end widget edit box -->
        <div class="widget-body padding-10">
            <?php if ($gearmanReachable && $isGearmanWorkerRunning): ?>
                <dl class="dl-horizontal">
                    <dt><?php echo __('Monitoring engine'); ?>:</dt>
                    <dd>
                        <?php echo ($backgroundProcessStatus['isNagiosRunning']) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo h($monitoring_engine); ?>" data-placement="right"
                           rel="tooltip" class="text-info" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('Database connector'); ?>:</dt>
                    <dd>

                        <?php
                        $dbConnector = __('NDOUtils');
                        if ($isStatusengineInstalled):
                            $dbConnector = __('Statusengine');
                        endif;
                        ?>
                        <?php echo ($backgroundProcessStatus['isNdoRunning'] || $backgroundProcessStatus['isStatusengineRunning']) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo $dbConnector; ?>" data-placement="right" rel="tooltip"
                           class="text-info" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('Perfdata processor'); ?>:</dt>
                    <dd>
                        <?php
                        $perfdataProcessor = __('NPCD');
                        if ($isStatusenginePerfdataProcessor):
                            $perfdataProcessor = __('Statusengine');
                        endif;
                        ?>
                        <?php echo ($backgroundProcessStatus['isNpcdRunning'] || ($isStatusenginePerfdataProcessor && $backgroundProcessStatus['isStatusengineRunning'])) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo $perfdataProcessor; ?>" data-placement="right" rel="tooltip"
                           class="text-info" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('Queuing engine'); ?>:</dt>
                    <dd>
                        <span class="text-success"><i class="fa fa-check"></i> <?php echo __('Running'); ?></span>
                        <a data-original-title="<?php echo h('openITCOCKPIT uses the Gearman Job Server to run different background tasks'); ?>"
                           data-placement="right" rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                    class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('Gearman Worker'); ?>:</dt>
                    <dd>
                        <?php echo ($backgroundProcessStatus['isGearmanWorkerRunning']) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo __('Gearman Worker'); ?>" data-placement="right"
                           rel="tooltip" class="text-info" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('OITC Cmd'); ?>:</dt>
                    <dd>
                        <?php echo ($backgroundProcessStatus['isOitcCmdRunning']) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo __('OITC Cmd'); ?>" data-placement="right" rel="tooltip"
                           class="text-info" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                    </dd>
                    <dt><?php echo __('phpNSTA'); ?>:</dt>
                    <dd>
                        <?php echo ($backgroundProcessStatus['isPhpNstaRunning']) ? '<span class="text-success"><i class="fa fa-check"></i> '.__('Running').'</span>' : '<span class="text-danger"><i class="fa fa-close"></i> '.__('Not running!').'</span>'; ?>
                        <a data-original-title="<?php echo __('phpNSTA is only installed and running if you are using Distributed Monitoring'); ?>"
                           data-placement="right" rel="tooltip" class="text-info" href="javascript:void(0);"><i
                                    class="fa fa-info-circle"></i></a>
                    </dd>
                </dl>
            <?php endif; ?>
            <?php if ($gearmanReachable === false): ?>
                <div id="error_msg">
                    <div class="alert alert-danger alert-block">
                        <a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i
                                    class="fa fa-warning"></i> <?php echo __('Error'); ?></h5>
                        <?php echo __('As long as your Gearman-Job-Server is not running, we can not check the state of other daemons'); ?>
                        <br/>
                        <?php echo __('Please start your Gearman-Job-Server first'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isGearmanWorkerRunning === false): ?>
                <div id="error_msg">
                    <div class="alert alert-danger alert-block">
                        <a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i
                                    class="fa fa-warning"></i> <?php echo __('Error'); ?></h5>
                        <?php echo __('As long as your gearman_worker is not running, we can not check the state of other daemons'); ?>
                        <br/>
                        <?php echo __('Please start your gearman_worker first'); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>


<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false"  data-widget-colorbutton="false"
      data-widget-fullscreenbutton="true"  data-widget-editbutton="true"  data-widget-togglebutton="false"
      style="position: relative; opacity: 1; left: 0px; top: 0px;"  role="widget">
    <header>
        <span class="widget-icon"> <i class="fa fa-hdd-o"></i></span>
        <h2><?php echo __('Server information'); ?></h2>
    </header>
    <!-- widget div-->
    <div>
        <!-- end widget edit box -->
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Address'); ?>:</dt>
                <dd><?php echo h($_SERVER['SERVER_ADDR']); ?></dd>
                <dt><?php echo __('Webserver'); ?>:</dt>
                <dd><?php echo h($_SERVER['SERVER_SOFTWARE']); ?></dd>
                <dt><?php echo __('TLS'); ?>:</dt>
                <dd><?php echo h($_SERVER['HTTPS']); ?></dd>
                <dt><?php echo __('OS'); ?>:</dt>
                <dd><?php echo h($osVersion); ?></dd>
                <dt><?php echo __('Kernel'); ?>:</dt>
                <dd><?php echo h(php_uname('r')); ?></dd>
                <dt><?php echo __('Architecture'); ?>:</dt>
                <dd><?php echo h(php_uname('m')); ?></dd>
                <dt><?php echo __('PHP version'); ?>:</dt>
                <dd><?php echo h(PHP_VERSION); ?></dd>
                <dt><?php echo __('PHP Memory limit'); ?>:</dt>
                <dd><?php echo h(str_replace("M", "", get_cfg_var("memory_limit"))); ?>MB</dd>
                <dt><?php echo __('PHP Max. execution time'); ?>:</dt>
                <dd><?php echo h(ini_get("max_execution_time")); ?>s</dd>
                <dt><?php echo __('PHP Libraries'); ?>:</dt>
                <dd><?php echo implode(', ', get_loaded_extensions()); ?></dd>
            </dl>

            <b><?php echo __('Load average'); ?>:</b>
            <br/>
            <?php if (empty($load)): ?>
                <div class="well text-danger">
                    <i class="fa fa-warning"></i> <?php echo __('Could not fetch load average information'); ?>
                </div>
            <?php else: ?>
                <div class="well">
                    <div class="graph_legend" style="display:none;">
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
                    <div id="loadGraph">
                        <?php echo $load[0]; ?>, <?php echo $load[1]; ?>, <?php echo $load[2]; ?>
                    </div>
                </div>
            <?php endif; ?>
            <br/>

            <b><?php echo __('Memory usage'); ?>:</b>
            <?php if (empty($memory)): ?>
                <div class="well text-danger">
                    <i class="fa fa-warning"></i> <?php echo __('Could not fetch memory information'); ?>
                </div>
            <?php else: ?>
                <?php if (isset($memory['Memory'])): ?>
                    <div class="well">
                        <?php echo __('Total'); ?>: <?php echo $memory['Memory']['total']; ?>M,
                        <span class="txt-color-green"><?php echo __('used'); ?>
                            : <?php echo $memory['Memory']['used']; ?>M, </span>
                        <span class="txt-color-orange"><?php echo __('cached'); ?>
                            : <?php echo $memory['Memory']['cached']; ?>M,</span>
                        <span class="txt-color-blue"><?php echo __('buffers'); ?>
                            : <?php echo $memory['Memory']['buffers']; ?>M</span>

                        <div class="progress" style="margin-bottom: 0px;">
                            <div style="width: <?php echo (int)($memory['Memory']['used'] / $memory['Memory']['total'] * 100); ?>%; position: unset;"
                                 class="progress-bar bg-color-green"></div>
                            <div style="width: <?php echo (int)($memory['Memory']['cached'] / $memory['Memory']['total'] * 100); ?>%; position: unset;"
                                 class="progress-bar bg-color-orange"></div>
                            <div style="width: <?php echo (int)($memory['Memory']['buffers'] / $memory['Memory']['total'] * 100); ?>%; position: unset;"
                                 class="progress-bar bg-color-blue"></div>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($memory['Swap'])): ?>
                    <br/>
                    <b><?php echo __('Swap usage'); ?>:</b>
                    <br/>
                    <div class="well">
                        <?php echo __('Total'); ?>: <?php echo $memory['Swap']['total']; ?>M,
                        <?php echo __('used'); ?>: <?php echo $memory['Swap']['used']; ?>M
                        <?php echo $this->Html->progressbar($memory['Swap']['used'], [
                            'unit'               => '',
                            'min'                => 0,
                            'max'                => $memory['Swap']['total'],
                            'display_as_percent' => true,
                            'thresholds'         => [
                                1 => [
                                    'value'   => 5,
                                    'bgColor' => 'bg-color-orange',
                                ],
                                2 => [
                                    'value'   => 10,
                                    'bgColor' => 'bg-color-red',
                                ],
                            ],
                        ]); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <br/>
            <b><?php echo __('Disk usage'); ?>:</b>
            <?php if (empty($disks)): ?>
                <div class="well text-danger">
                    <i class="fa fa-warning"></i> <?php echo __('Could not fetch disk information'); ?>
                </div>
            <?php else: ?>
                <?php foreach ($disks as $disk): ?>
                    <div class="well">
                        <b><?php echo $disk['disk']; ?></b> (<?php echo __('size'); ?>: <?php echo $disk['size']; ?>
                        , <?php echo __('available'); ?>: <?php echo $disk['avail']; ?>
                        , <?php echo __('mount point'); ?>: <?php echo $disk['mountpoint']; ?>)
                        <?php echo $this->Html->progressbar($disk['use%']); ?>
                    </div>
                    <br/>
                <?php endforeach; ?>
            <?php endif; ?>
            <br/>
            <b><?php echo __('Queuing engine'); ?>:</b>
            <?php if ($gearmanReachable && !empty($gearmanStatus)): ?>
                <div class="well">
                    <div class="container">
                        <div class="row">
                            <div class="col col-xs-12 col-md-12 col-lg-3 bold"><?php echo __('Queue name'); ?></div>
                            <div class="col col-xs-12 col-md-12 col-lg-3 bold text-center"><?php echo __('Jobs waiting'); ?></div>
                            <div class="col col-xs-12 col-md-12 col-lg-3 bold text-center"><?php echo __('Active jobs'); ?></div>
                            <div class="col col-xs-12 col-md-12 col-lg-3 bold text-center"><?php echo __('Worker available'); ?></div>
                            <?php foreach ($gearmanStatus as $queueName => $queueStatus): ?>
                                <?php
                                $class = 'txt-color-green';
                                if ($queueStatus['jobs'] > 5):
                                    $class = 'text-primary';
                                endif;
                                if ($queueStatus['jobs'] > 50):
                                    $class = 'txt-color-orangeDark';
                                endif;
                                if ($queueStatus['jobs'] > 500):
                                    $class = 'txt-color-white bg-color-red';
                                endif;
                                if ($queueStatus['worker'] == 0):
                                    $class = 'txt-color-white bg-color-orangeDark';
                                endif;
                                ?>
                                <div class="col col-xs-12 col-md-12 col-lg-3 <?php echo $class; ?>"><?php echo h($queueName); ?></div>
                                <div class="col col-xs-12 col-md-12 col-lg-3 text-center <?php echo $class; ?>"><?php echo h($queueStatus['jobs']); ?></div>
                                <div class="col col-xs-12 col-md-12 col-lg-3 text-center <?php echo $class; ?>"><?php echo h($queueStatus['running']); ?></div>
                                <div class="col col-xs-12 col-md-12 col-lg-3 text-center <?php echo $class; ?>"><?php echo h($queueStatus['worker']); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h2 class="text-danger"><?php echo __('Error: Queuing engine not running!'); ?></h2>
            <?php endif; ?>
        </div>

    </div>
</div>

<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false"  data-widget-colorbutton="false"
      data-widget-fullscreenbutton="true"  data-widget-editbutton="true"  data-widget-togglebutton="false"
      style="position: relative; opacity: 1; left: 0px; top: 0px;"  role="widget">
    <header>
        <span class="widget-icon"> <i class="fa fa-envelope"></i></span>
        <h2><?php echo __('Email configuration'); ?></h2>
    </header>
    <!-- widget div-->
    <div>
        <!-- end widget edit box -->
        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Mail server address'); ?>:</dt>
                <dd><?php echo h($mailConfig['host']); ?></dd>

                <dt><?php echo __('Mail server port'); ?>:</dt>
                <dd><?php echo h($mailConfig['port']); ?></dd>

                <dt><?php echo __('Transport protocol'); ?>:</dt>
                <dd><?php echo h($mailConfig['transport']); ?></dd>

                <dt><?php echo __('Username'); ?>:</dt>
                <dd><?php echo h($mailConfig['username']); ?></dd>

                <dt><?php echo __('Password'); ?>:</dt>
                <dd>
                    <i><?php echo __('Password hidden due to security please see the file /etc/openitcockpit/app/Config/email.php for detailed configuration information.'); ?></i>
                </dd>

                <dt>&nbsp;</dt>
                <dd>
                    <form accept-charset="utf-8" method="post" class="form-horizontal clear" novalidate="novalidate"
                          action="/Administrators/testMail">
                        <input type="submit" value="<?php echo __('Send test Email to %s', h($recipientAddress)); ?>"
                               class="btn btn-xs btn-default">
                    </form>
                </dd>
            </dl>
        </div>


    </div>
</div>

<div class="jarviswidget jarviswidget-sortable" data-widget-deletebutton="false"  data-widget-colorbutton="false"
      data-widget-fullscreenbutton="true"  data-widget-editbutton="true"  data-widget-togglebutton="false"
      style="position: relative; opacity: 1; left: 0px; top: 0px;"  role="widget">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i></span>
        <h2><?php echo __('User information'); ?></h2>
    </header>
    <!-- widget div-->
    <div>
        <!-- end widget edit box -->

        <?php
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $os = "unknown";
        if (strstr($agent, "Windows 98")) $os = "Windows 98";
        elseif (strstr($agent, "NT 4.0")) $os = "Windows NT ";
        elseif (strstr($agent, "NT 5.1")) $os = "Windows XP";
        elseif (strstr($agent, "NT 6.0")) $os = "Windows Vista";
        elseif (strstr($agent, "NT 6.1")) $os = "Windows 7";
        elseif (strstr($agent, "NT 6.2")) $os = "Windows 8";
        elseif (strstr($agent, "NT 6.3")) $os = "Windows 8.1";
        elseif (strstr($agent, "NT 6.4")) $os = "Windows 10";
        elseif (strstr($agent, "Win")) $os = "Windows";
        //Firefox
        elseif (strstr($agent, "Mac OS X 10.5")) $os = "Mac OS X - Leopard";
        elseif (strstr($agent, "Mac OS X 10.6")) $os = "Mac OS X - Snow Leopard";
        elseif (strstr($agent, "Mac OS X 10.7")) $os = "Mac OS X - Lion";
        elseif (strstr($agent, "Mac OS X 10.8")) $os = "Mac OS X - Mountain Lion";
        elseif (strstr($agent, "Mac OS X 10.9")) $os = "Mac OS X - Mavericks";
        elseif (strstr($agent, "Mac OS X 10.10")) $os = "Mac OS X - Yosemite";
        elseif (strstr($agent, "Mac OS X 10.11")) $os = "Mac OS X - El Capitan";

        elseif (strstr($agent, "Mac OS X 10.12")) $os = "macOS Sierra ";

        //Chrome
        elseif (strstr($agent, "Mac OS X 10_5")) $os = "Mac OS X - Leopard";
        elseif (strstr($agent, "Mac OS X 10_6")) $os = "Mac OS X - Snow Leopard";
        elseif (strstr($agent, "Mac OS X 10_7")) $os = "Mac OS X - Lion";
        elseif (strstr($agent, "Mac OS X 10_8")) $os = "Mac OS X - Mountain Lion";
        elseif (strstr($agent, "Mac OS X 10_9")) $os = "Mac OS X - Mavericks";
        elseif (strstr($agent, "Mac OS X 10_10")) $os = "Mac OS X - Yosemite";
        elseif (strstr($agent, "Mac OS X 10_11")) $os = "Mac OS X - El Capitan";

        elseif (strstr($agent, "Mac OS X 10_12")) $os = "macOS Sierra ";

        elseif (strstr($agent, "Mac OS")) $os = "Mac OS X";
        elseif (strstr($agent, "Linux")) $os = "Linux";
        elseif (strstr($agent, "Unix")) $os = "Unix";
        elseif (strstr($agent, "Ubuntu")) $os = "Ubuntu";
        ?>

        <div class="widget-body padding-10">
            <dl class="dl-horizontal">
                <dt><?php echo __('Your OS'); ?>:</dt>
                <dd><?php echo h($os); ?></dd>
                <dt><?php echo __('Your browser'); ?>:</dt>
                <dd><?php echo h($_SERVER['HTTP_USER_AGENT']); ?></dd>
                <dt><?php echo __('Your Address'); ?>:</dt>
                <dd><?php echo h($_SERVER['REMOTE_ADDR']); ?></dd>
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
