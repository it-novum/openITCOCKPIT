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
$css = \App\itnovum\openITCOCKPIT\Core\AngularJS\PdfAssets::getCssFiles();
?>
<head>
    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?= WWW_ROOT . $cssFile; ?>"/>
    <?php
    endforeach; ?>
</head>
<body>
<div class="row">
    <div class="col-6 padding-left-15">
        <i class="fa fa-calendar"></i>
        <?php
        echo __('Analysis period: ');
        echo h($UserTime->format($fromDate)); ?>
        <i class="fas fa-long-arrow-alt-right"></i>
        <?php
        echo h($UserTime->format($toDate));
        ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>
<?php
if (!empty($error['no_downtimes']['empty'])):
    echo $error['no_downtimes']['empty'];
endif;
if (!empty($downtimeReport['hostsWithOutages'])):?>
    <div class="pdf-card">
        <div class="pdf-card-header">
            <h2>
                <span class="fa-stack">
                    <i class="fa fa-check fa-stack-1x ok"></i>
                    <i class="fa fa-ban fa-stack-2x critical opacity-50"></i>
                </span>
                <?= __('Involved in outages (Hosts):'); ?>
            </h2>
        </div>
        <div class="pdf-card-body padding-bottom-10">
            <?php
            foreach ($downtimeReport['hostsWithOutages'] as $chunkKey => $hostsWithOutages):
                $MultipleBarChart = new MultipleBarChart();
                $overview_chart = $MultipleBarChart->createBarChart(
                    Hash::combine($hostsWithOutages['hosts'], '{n}.Host.name', '{n}.Host.reportData')
                ); ?>
                <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"/>
                <?php
                foreach ($hostsWithOutages['hosts'] as $hostWithOutages): ?>
                    <div class="pdf-card">
                        <div class="pdf-card-header">
                            <h2>
                                <i class="fa fa-desktop"></i>
                                <?= h($hostWithOutages['Host']['name']); ?>
                            </h2>
                        </div>
                        <div class="pdf-card-body">
                            <div class="row">
                                <div class="col-3">
                                    <?= __('Description'); ?>
                                </div>
                                <div class="col-9">
                                    <?= h(($hostWithOutages['Host']['description']) ? $hostWithOutages['Host']['description'] : ' - '); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <?= __('IP address'); ?>
                                </div>
                                <div class="col-9">
                                    <?= h($hostWithOutages['Host']['address']); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <?= __('Status'); ?>
                                </div>
                                <?php for ($i = 0; $i < 3; $i++): ?>
                                    <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                    <div
                                        class="col-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview margin-5">
                                        <strong class="txt-color-white">
                                            <?= $hostWithOutages['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                        </strong>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    &nbsp;
                                </div>
                                <?php
                                for ($i = 0; $i < 3; $i++):?>
                                    <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                    <div
                                        class="col-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview margin-5">
                                        <strong class="txt-color-white">
                                            <?= $hostWithOutages['pieChartData']['widgetOverview'][$i]['human']; ?>
                                        </strong>
                                    </div>
                                <?php
                                endfor; ?>
                            </div>
                            <?php
                            if (isset($hostWithOutages['Services'])):
                                $servicesWithOutages =
                                    Hash::extract(
                                        $hostWithOutages['Services'],
                                        '{s}.Service.reportData[2>0]'
                                    );
                                if (!empty($servicesWithOutages)):?>
                                    <div class="row">
                                        <div class="col-9 padding-top-10 padding-bottom-10">
                                            <strong class="">
                                                <?= __('Involved in outages (Services):'); ?>
                                            </strong>
                                        </div>
                                    </div>
                                <?php
                                endif;
                                foreach ($hostWithOutages['Services'] as $uuid => $service):
                                    if ($service['Service']['reportData'][2] > 0):
                                        $serviceName = empty($service['Service']['name']) ?
                                            $service['Servicetemplate']['name'] :
                                            $service['Service']['name'];
                                        ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <i class="fa fa-cog"></i>
                                                <?= h($serviceName); ?>
                                            </div>
                                            <div class="col-3 text-right padding-right-20 text-info">
                                                <?= __('Servicetemplate'); ?>
                                            </div>
                                            <div class="col-3 text-info">
                                                <i class="fas fa-edit"></i>
                                                <?= h($service['Servicetemplate']['template_name']); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3 text-right padding-bottom-10">
                                                <?php
                                                $overview_chart = \itnovum\openITCOCKPIT\Core\Views\PieChart::createPieChartOnDisk([
                                                    $service['Service']['reportData'][0],
                                                    $service['Service']['reportData'][1],
                                                    $service['Service']['reportData'][2],
                                                    $service['Service']['reportData'][3]
                                                ]);
                                                ?>
                                                <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                     width="100"/>
                                            </div>
                                            <div class="col-9">
                                                <?php for ($i = 0; $i <= 3; $i++):
                                                    $ServicestatusIcon = new \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon($i); ?>
                                                    <div class="row">
                                                        <div class="col-4 text-right">
                                                            <em>
                                                                <?= $ServicestatusIcon->getHumanState(); ?>
                                                            </em>
                                                        </div>
                                                        <div
                                                            class="col-4 <?= $ServicestatusIcon->getBgColor(); ?> downtime-report-state-overview">
                                                            <strong class="txt-color-white">
                                                                <?= $service['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                            </strong>
                                                        </div>
                                                        <div
                                                            class="col-4 <?= $ServicestatusIcon->getBgColor(); ?> downtime-report-state-overview">
                                                            <strong class="txt-color-white">
                                                                <?= $service['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                <?php
                                                endfor; ?>
                                            </div>
                                        </div>
                                    <?php
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
<?php
endif;
if (!empty($downtimeReport['hostsWithoutOutages']['hosts'])): ?>
    <div class="pdf-card">
        <div class="pdf-card-header">
            <h2>
                <span class="fa-stack">
                    <i class="fa fa-circle-o fa-stack-2x txt-color-blueLight"></i>
                    <i class="fa fa-check fa-stack-1x ok"></i>
                </span>
                <?= __(' Hosts without outages:'); ?>
            </h2>
        </div>
        <div class="pdf-card-body">
            <?php
            foreach ($downtimeReport['hostsWithoutOutages']['hosts'] as $hostWithoutOutages): ?>
                <div class="pdf-card">
                    <div class="pdf-card-header">
                        <h2>
                            <i class="fa fa-desktop"></i>
                            <?= h($hostWithoutOutages['Host']['name']); ?>
                        </h2>
                    </div>
                    <div class="pdf-card-body">
                        <div class="row">
                            <div class="col-3">
                                <?= __('Description'); ?>
                            </div>
                            <div class="col-9">
                                <?= h(($hostWithoutOutages['Host']['description']) ? $hostWithoutOutages['Host']['description'] : ' - '); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <?= __('IP address'); ?>
                            </div>
                            <div class="col-9">
                                <?= h($hostWithoutOutages['Host']['address']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <?= __('Status'); ?>
                            </div>
                            <?php
                            for ($i = 0; $i < 3; $i++):?>
                                <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                <div
                                    class="col-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview margin-5">
                                    <strong class="txt-color-white">
                                        <strong class="txt-color-white">
                                            <?= $hostWithoutOutages['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                        </strong>
                                    </strong>
                                </div>
                            <?php
                            endfor;
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                &nbsp;
                            </div>
                            <?php
                            for ($i = 0; $i < 3; $i++):?>
                                <?php $HoststatusIcon = new \itnovum\openITCOCKPIT\Core\Views\HoststatusIcon($i); ?>
                                <div
                                    class="col-3 <?= $HoststatusIcon->getBgColor() ?> downtime-report-state-overview margin-5">
                                    <strong class="txt-color-white">
                                        <?= $hostWithoutOutages['pieChartData']['widgetOverview'][$i]['human']; ?>
                                    </strong>
                                </div>
                            <?php
                            endfor;
                            ?>
                        </div>

                        <?php
                        $servicesWithOutages = [];
                        if (isset($hostWithoutOutages['Services'])):
                            $servicesWithOutages =
                                Hash::extract(
                                    $hostWithoutOutages['Services'],
                                    '{s}.Service.reportData[2>0]'
                                );
                            if (!empty($servicesWithOutages)): ?>
                                <div class="row">
                                    <div class="col-9 padding-top-10 padding-bottom-10">
                                        <strong>
                                            <?= __('Involved in outages (Services):'); ?>
                                        </strong>
                                    </div>
                                </div>

                            <?php endif;
                            foreach ($hostWithoutOutages['Services'] as $uuid => $service):
                                if ($service['Service']['reportData'][2] > 0):
                                    $serviceName = empty($service['Service']['name']) ?
                                        $service['Servicetemplate']['name'] :
                                        $service['Service']['name'];
                                    ?>
                                    <div class="row margin-top-20">
                                        <div class="col-6">
                                            <i class="fa fa-cog"></i>
                                            <?= h($serviceName); ?>
                                        </div>
                                        <div class="col-3 text-right padding-right-20 text-info">
                                            <?= __('Servicetemplate'); ?>
                                        </div>
                                        <div class="col-3 text-info">
                                            <i class="fas fa-edit"></i>
                                            <?= h($service['Servicetemplate']['template_name']); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 text-right padding-bottom-10">
                                            <?php
                                            $overview_chart = \itnovum\openITCOCKPIT\Core\Views\PieChart::createPieChartOnDisk([
                                                $service['Service']['reportData'][0],
                                                $service['Service']['reportData'][1],
                                                $service['Service']['reportData'][2],
                                                $service['Service']['reportData'][3]
                                            ]);
                                            ?>
                                            <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>" width="100"/>
                                        </div>
                                        <div class="col-9">
                                            <?php
                                            for ($i = 0; $i <= 3; $i++):?>
                                                <?php $ServicestatusIcon = new \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon($i); ?>
                                                <div class="row">
                                                    <div class="col-4 text-right">
                                                        <em>
                                                            <?= $ServicestatusIcon->getHumanState() ?>
                                                        </em>
                                                    </div>
                                                    <div
                                                        class="col-4 <?= $ServicestatusIcon->getBgColor() ?> downtime-report-state-overview ">
                                                        <strong class="txt-color-white">
                                                            <?= $service['pieChartData']['widgetOverview'][$i]['percent']; ?>
                                                        </strong>
                                                    </div>
                                                    <div
                                                        class="col-4 <?= $ServicestatusIcon->getBgColor() ?> downtime-report-state-overview ">
                                                        <strong class="txt-color-white">
                                                            <?= $service['pieChartData']['widgetOverview'][$i]['human']; ?>
                                                        </strong>
                                                    </div>
                                                </div>
                                            <?php endfor;
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                endif;
                            endforeach;
                        endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
</body>

