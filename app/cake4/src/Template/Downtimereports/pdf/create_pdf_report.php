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
use itnovum\openITCOCKPIT\Core\Views\Logo;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\Views\MultipleBarChart;

/**
 * @var \App\View\AppView $this
 * @var array $downtimeReport
 * @var int $fromDate
 * @var int $toDate
 * @var \itnovum\openITCOCKPIT\Core\Views\UserTime $UserTime
 *
 */

$Logo = new Logo();
?>
<head>
    <?php
    $css = [
        '/legacy/css/vendor/bootstrap/css/bootstrap.css',
        '/legacy/css/vendor/bootstrap/css/bootstrap-theme.css',
        '/legacy/smartadmin/css/font-awesome.css',
        '/legacy/smartadmin/css/smartadmin-production.css',
        '/legacy/smartadmin/css/your_style.css',
        '/legacy/css/app.css',
        '/legacy/css/pdf_list_style.css',
        '/legacy/css/bootstrap_pdf.css',
    ];
    ?>

    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?= WWW_ROOT . $cssFile; ?>"/>
    <?php
    endforeach; ?>
</head>
<body class="">
<div class="jarviswidget no-bordered">
    <div class="well no-bordered">
        <div class="row margin-top-10 font-md padding-bottom-10">
            <div class="col-md-9 text-left font-xl txt-color-blueDark">
                <i class="fa fa-calendar txt-color-blueDark"></i>
                <?php
                echo __('Analysis period: ');
                echo h($UserTime->format($fromDate)); ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php
                echo h($UserTime->format($toDate));
                ?>
            </div>
            <div class="col-md-3 text-left">
                <img src="<?= $Logo->getLogoPdfPath(); ?>" width="200"/>
            </div>
        </div>
        <?php
        if (!empty($error['no_downtimes']['empty'])):
            echo $error['no_downtimes']['empty'];
        endif;
        if (!empty($downtimeReport['hostsWithOutages'])):?>
            <section>
                <div class="row">
                    <article class="col-md-12 sortable-grid ui-sortable">
                        <div class="jarviswidget">
                            <header>
                                <h2 class="txt-color-blueDark text-header">
                                        <span class="fa-stack">
                                            <i class="fa fa-check fa-stack-1x ok"></i>
                                            <i class="fa fa-ban fa-stack-2x critical opacity-50"></i>
                                        </span>
                                    <?= __('Involved in outages (Hosts):'); ?>
                                </h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <?php
                                foreach ($downtimeReport['hostsWithOutages'] as $chunkKey => $hostsWithOutages):
                                    ?>
                                    <div class="widget-body">
                                        <?php
                                        $MultipleBarChart = new MultipleBarChart();

                                        $overview_chart = $MultipleBarChart->createBarChart(
                                            Hash::combine($hostsWithOutages['hosts'], '{n}.Host.name', '{n}.Host.reportData')
                                        );
                                        ?>
                                        <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"/>
                                    </div>
                                    <?php
                                    foreach ($hostsWithOutages['hosts'] as $hostWithOutages):?>
                                        <div class="jarviswidget">
                                            <header role="heading">
                                                <h2 class="bold  txt-color-blueDark">
                                                    <i class="fa fa-desktop txt-color-blueDark"></i>
                                                    <?= h($hostWithOutages['Host']['name']); ?>
                                                </h2>
                                            </header>
                                            <div class="widget-body font-md txt-color-blueDark">
                                                <div class="col-md-3 ">
                                                    <?= __('Description'); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <?= h(($hostWithOutages['Host']['description']) ? $hostWithOutages['Host']['description'] : ' - '); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= __('IP address'); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <?= h($hostWithOutages['Host']['address']); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= __('Status'); ?>
                                                </div>
                                                <?php for ($i = 0; $i < 3; $i++): ?>
                                                    <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                                    <div
                                                        class="col-md-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview font-md padding-5">
                                                        <strong class="txt-color-white">
                                                            <strong class="txt-color-white">
                                                                <?= $hostWithOutages['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                            </strong>
                                                        </strong>
                                                    </div>
                                                <?php endfor; ?>
                                                <div class="col-md-3">
                                                    &nbsp;
                                                </div>
                                                <?php
                                                for ($i = 0; $i < 3; $i++):?>
                                                    <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                                    <div
                                                        class="col-md-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview font-md padding-5">
                                                        <strong class="txt-color-white">
                                                            <?= $hostWithOutages['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                        </strong>
                                                    </div>
                                                <?php
                                                endfor;
                                                if (isset($hostWithOutages['Services'])):
                                                    $servicesWithOutages =
                                                        Hash::extract(
                                                            $hostWithOutages['Services'],
                                                            '{s}.Service.reportData[2>0]'
                                                        );
                                                    if (!empty($servicesWithOutages)):?>
                                                        <div class="col-md-9 padding-top-10 padding-bottom-10">
                                                            <strong class="txt-color-blueDark">
                                                                <?= __('Involved in outages (Services):'); ?>
                                                            </strong>
                                                        </div>
                                                    <?php endif;
                                                    foreach ($hostWithOutages['Services'] as $uuid => $service):
                                                        if ($service['Service']['reportData'][2] > 0):
                                                            $serviceName = empty($service['Service']['name']) ?
                                                                $service['Servicetemplate']['name'] :
                                                                $service['Service']['name'];
                                                            ?>
                                                            <div class="col-md-12 txt-color-blueDark">
                                                                <i class="fa fa-cog txt-color-blueDark"></i>
                                                                <?= h($serviceName);
                                                                ?>
                                                            </div>
                                                            <div class="col-md-3 text-right padding-right-20 text-info">
                                                                <?= __('Servicetemplate'); ?>
                                                            </div>
                                                            <div class="col-md-9 text-info">
                                                                <?= h($service['Servicetemplate']['template_name']);
                                                                ?>
                                                            </div>
                                                            <div class="col-md-3 text-right padding-bottom-10">
                                                                <?php
                                                                $overview_chart = \itnovum\openITCOCKPIT\Core\Views\PieChart::createPieChartOnDisk([
                                                                    $service['Service']['reportData'][0],
                                                                    $service['Service']['reportData'][1],
                                                                    $service['Service']['reportData'][2],
                                                                    $service['Service']['reportData'][3]
                                                                ]);
                                                                ?>
                                                                <img
                                                                    src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                                    width="100"/>
                                                            </div>
                                                            <?php for ($i = 0; $i <= 3; $i++): ?>
                                                            <?php $ServicestatusIcon = new \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon($i); ?>
                                                            <div class="col-md-3 text-right font-md">
                                                                <em>
                                                                    <?= $ServicestatusIcon->getHumanState(); ?>
                                                                </em>
                                                            </div>
                                                            <div
                                                                class="col-md-3 <?= $ServicestatusIcon->getBgColor(); ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?= $service['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                                </strong>
                                                            </div>
                                                            <div
                                                                class="col-md-3 <?= $ServicestatusIcon->getBgColor(); ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?= $service['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                                </strong>
                                                            </div>
                                                        <?php
                                                        endfor;
                                                        endif;
                                                    endforeach;
                                                endif;
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    endforeach;
                                endforeach; ?>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php
        endif;
        if (!empty($downtimeReport['hostsWithoutOutages']['hosts'])):?>
            <section>
                <div class="row">
                    <article class="col-md-12 sortable-grid ui-sortable">
                        <div class="jarviswidget jarviswidget-sortable" role="widget">
                            <header>
                                <h2 class="txt-color-blueDark text-header">
                                        <span class="fa-stack">
                                            <i class="fa fa-circle-o fa-stack-2x txt-color-blueLight"></i>
                                            <i class="fa fa-check fa-stack-1x ok"></i>
                                        </span>
                                    <?= __(' Hosts without outages:'); ?>
                                </h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <?php
                                foreach ($downtimeReport['hostsWithoutOutages']['hosts'] as $hostWithoutOutages):?>
                                    <div class="jarviswidget jarviswidget-color-blueLight">
                                        <header role="heading">
                                            <h2 class="bold  txt-color-blueDark">
                                                <i class="fa fa-desktop txt-color-blueDark"></i>
                                                <?= h($hostWithoutOutages['Host']['name']); ?>
                                            </h2>
                                        </header>
                                        <div class="widget-body font-md txt-color-blueDark">
                                            <div class="col-md-3 ">
                                                <?= __('Description'); ?>
                                            </div>
                                            <div class="col-md-9">
                                                <?= h(($hostWithoutOutages['Host']['description']) ? $hostWithoutOutages['Host']['description'] : ' - '); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= __('IP address'); ?>
                                            </div>
                                            <div class="col-md-9">
                                                <?= h($hostWithoutOutages['Host']['address']); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= __('Status'); ?>
                                            </div>
                                            <?php
                                            for ($i = 0; $i < 3; $i++):?>
                                                <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                                <div
                                                    class="col-md-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview font-md padding-5">
                                                    <strong class="txt-color-white">
                                                        <strong class="txt-color-white">
                                                            <?= $hostWithoutOutages['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                        </strong>
                                                    </strong>
                                                </div>
                                            <?php
                                            endfor;
                                            ?>
                                            <div class="col-md-3">
                                                &nbsp;
                                            </div>
                                            <?php
                                            for ($i = 0; $i < 3; $i++):?>
                                                <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                                <div
                                                    class="col-md-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview font-md padding-5">
                                                    <strong class="txt-color-white">
                                                        <?= $hostWithoutOutages['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                    </strong>
                                                </div>
                                            <?php
                                            endfor;
                                            $servicesWithOutages = [];
                                            if (isset($hostWithoutOutages['Services'])):
                                                $servicesWithOutages =
                                                    Hash::extract(
                                                        $hostWithoutOutages['Services'],
                                                        '{s}.Service.reportData[2>0]'
                                                    );
                                                if (!empty($servicesWithOutages)): ?>
                                                    <div class="col-md-9 padding-top-10 padding-bottom-10">
                                                        <strong class="txt-color-blueDark">
                                                            <?= __('Involved in outages (Services):'); ?>
                                                        </strong>
                                                    </div>
                                                <?php endif;
                                                foreach ($hostWithoutOutages['Services'] as $uuid => $service):
                                                    if ($service['Service']['reportData'][2] > 0):
                                                        $serviceName = empty($service['Service']['name']) ?
                                                            $service['Servicetemplate']['name'] :
                                                            $service['Service']['name'];
                                                        ?>
                                                        <div class="col-md-12 txt-color-blueDark">
                                                            <i class="fa fa-cog txt-color-blueDark"></i>
                                                            <?= h($serviceName);
                                                            ?>
                                                        </div>
                                                        <div class="col-md-3 text-right padding-right-20 text-info">
                                                            <?= __('Servicetemplate'); ?>
                                                        </div>
                                                        <div class="col-md-9 text-info">
                                                            <?= h($service['Servicetemplate']['template_name']);
                                                            ?>
                                                        </div>
                                                        <div class="col-md-3 text-right padding-bottom-10">
                                                            <?php
                                                            $overview_chart = \itnovum\openITCOCKPIT\Core\Views\PieChart::createPieChartOnDisk([
                                                                $service['Service']['reportData'][0],
                                                                $service['Service']['reportData'][1],
                                                                $service['Service']['reportData'][2],
                                                                $service['Service']['reportData'][3]
                                                            ]);
                                                            ?>
                                                            <img
                                                                src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                                width="100"/>
                                                        </div>
                                                        <?php
                                                        for ($i = 0; $i <= 3; $i++):?>
                                                            <?php $ServicestatusIcon = new \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon($i); ?>
                                                            <div class="col-md-3 text-right font-md">
                                                                <em>
                                                                    <?= $ServicestatusIcon->getHumanState() ?>
                                                                </em>
                                                            </div>
                                                            <div
                                                                class="col-md-3 <?= $ServicestatusIcon->getBgColor() ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?= $service['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                                </strong>
                                                            </div>
                                                            <div
                                                                class="col-md-3 <?= $ServicestatusIcon->getBgColor() ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?= $service['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                                </strong>
                                                            </div>
                                                        <?php endfor;
                                                    endif;
                                                endforeach;
                                            endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>
</body>

