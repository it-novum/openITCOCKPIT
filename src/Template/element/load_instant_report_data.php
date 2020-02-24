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

$totalHostsData = [
    0 => 0,
    1 => 0,
    2 => 0
];
$totalServicesData = [
    0 => 0,
    1 => 0,
    2 => 0,
    3 => 0
];
$Logo = new Logo();
?>
<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-file-image-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __(h($instantReportDetails['name'])); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div class="well">
        <div class="row margin-top-10 font-md padding-bottom-10">
            <div class="col-md-9 text-left">
                <i class="fa fa-calendar txt-color-blueDark"></i>
                <?php
                echo __('Analysis period: ');
                echo h($this->Time->format($instantReportDetails['startDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php
                echo h($this->Time->format($instantReportDetails['endDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                ?>
            </div>
            <div class="col-md-3 text-left">
                <?php
                echo $this->Html->image($Logo->getLogoForHtmlHelper(),
                    ['width' => '200']
                ); ?>
            </div>
        </div>
        <?php
        if (!$instantReportDetails['summary'] && !empty($instantReportData)):
            foreach ($instantReportData['Hosts'] as $hostUuid => $hostData):?>
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget" role="widget">
                                <header role="heading">
                                    <h2><i class="fa fa-desktop"></i> <?php echo h($hostData['Host']['name']); ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <?php if (!$instantReportDetails['onlyServices'] && isset($hostData[0], $hostData[1], $hostData[2]) && array_sum(
                                            [$hostData[0], $hostData[1], $hostData[2]]
                                        ) > 0): ?>
                                        <div class="row margin-top-10 font-md padding-bottom-20">
                                            <div class="col-md-12 text-left">
                                                <?php
                                                $overview_chart = $this->PieChart->createPieChart([$hostData[0], $hostData[1], $hostData[2]]);

                                                echo $this->Html->image(
                                                    '/img/charts/' . $overview_chart
                                                ); ?>
                                            </div>
                                            <div class="col-md-12 text-left font-xs">
                                                <?php
                                                for ($i = 0; $i <= 2; $i++):?>

                                                    <i class="fa fa-square no-padding <?php echo $this->Status->HostStatusTextColor($i); ?> "></i>
                                                    <em class="padding-right-20">
                                                        <?php
                                                        echo round($hostData[$i] / $instantReportDetails['totalTime'] * 100, 2) . ' % (' . $this->Status->humanSimpleHostStatus($i) . ')';
                                                        ?>
                                                    </em>

                                                <?php
                                                endfor;
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    elseif (!$instantReportDetails['onlyServices'] && isset($hostData[0], $hostData[1], $hostData[2]) && array_sum(
                                            [$hostData[0], $hostData[1], $hostData[2]]
                                        ) === 0):?>
                                        <i class="fa fa-info-circle txt-color-blueDark"></i>
                                        <?php
                                        echo __('There are no time frames defined. Time evaluation report data is not available for the selected period.');
                                    endif; ?>
                                    <div>
                                        <?php
                                        if (isset($hostData['Services'])):
                                            foreach ($hostData['Services'] as $serviceUuid => $serviceData):
                                                if (isset($serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3]) &&
                                                    array_sum(
                                                        [$serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3]]
                                                    ) > 0
                                                ):?>
                                                    <div class="padding-top-10 padding-bottom-5">
                                                        <i class="fa fa-gear"></i>
                                                        <?php echo h($serviceData['Service']['name']); ?>
                                                    </div>
                                                    <div class="padding-left-20 text-left">
                                                        <?php
                                                        $percentValues = [];
                                                        for ($i = 0; $i <= 3; $i++):
                                                            $percentValues[$i] = round($serviceData[$i] / $instantReportDetails['totalTime'] * 100, 2);
                                                        endfor;
                                                        $overview_chart = $this->BarChart->createBarChart([$serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3]]);

                                                        echo $this->Html->image(
                                                            '/img/charts/' . $overview_chart
                                                        );
                                                        for ($i = 0; $i <= 3; $i++):?>
                                                            <i class="fa fa-square no-padding <?php echo $this->Status->ServiceStatusTextColor($i); ?> "></i>
                                                            <em class="padding-right-20 ">
                                                                <?php
                                                                echo round($serviceData[$i] / $instantReportDetails['totalTime'] * 100, 2) . ' % (' . $this->Status->humanSimpleServiceStatus($i) . ')';
                                                                ?>
                                                            </em>

                                                        <?php
                                                        endfor; ?>
                                                    </div>
                                                <?php endif;
                                            endforeach;
                                        else:
                                            if ($instantReportDetails['onlyHosts'] === false):
                                                echo '<i class="fa fa-info-circle txt-color-blueDark"></i> ';
                                                echo __('There are no services defined.');
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            <?php
            endforeach;
        elseif (!empty($instantReportData)):
            foreach ($instantReportData['Hosts'] as $hostUuid => $hostData):
                if (isset($hostData[0], $hostData[1], $hostData[2])):
                    $totalHostsData[0] += $hostData[0];
                    $totalHostsData[1] += $hostData[1];
                    $totalHostsData[2] += $hostData[2];
                endif;
                if (isset($hostData['Services'])):
                    foreach ($hostData['Services'] as $serviceUuid => $serviceData):
                        if (isset($serviceData[0], $serviceData[1], $serviceData[2], $serviceData[3])):
                            $totalServicesData[0] += $serviceData[0];
                            $totalServicesData[1] += $serviceData[1];
                            $totalServicesData[2] += $serviceData[2];
                            $totalServicesData[3] += $serviceData[3];
                        endif;
                    endforeach;
                endif;
            endforeach;
            $totalTimeHosts = $totalHostsData[0] + $totalHostsData[1] + $totalHostsData[2];
            $totalTimeServices = $totalServicesData[0] + $totalServicesData[1] + $totalServicesData[2] + $totalServicesData[3];
            ?>

            <?php if (!$instantReportDetails['onlyServices'] && $totalTimeHosts != 0): ?>
            <section id="widget-grid" class="">
                <div class="row">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget" role="widget">
                            <header role="heading">
                                <h2><i class="fa fa-desktop"></i> <?= __('Hosts summary') ?></h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <div class="row margin-top-10 font-md padding-bottom-20">
                                    <div class="col-md-12 text-left">
                                        <?php
                                        $overview_chart = $this->PieChart->createPieChart([$totalHostsData[0], $totalHostsData[1], $totalHostsData[2]]);

                                        echo $this->Html->image('/img/charts/' . $overview_chart); ?>
                                    </div>
                                    <div class="col-md-12 text-left font-xs">
                                        <?php
                                        for ($i = 0; $i <= 2; $i++):?>

                                            <i class="fa fa-square no-padding <?php echo $this->Status->HostStatusTextColor($i); ?> "></i>
                                            <em class="padding-right-20">
                                                <?php
                                                echo round($totalHostsData[$i] / $totalTimeHosts * 100, 2) . ' % (' . $this->Status->humanSimpleHostStatus($i) . ')';
                                                ?>
                                            </em>
                                        <?php
                                        endfor;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php
        elseif (!$instantReportDetails['onlyServices'] && $totalTimeHosts == 0):?>
            <section id="widget-grid" class="">
                <div class="row">
                    <div class="well padding-bottom-10">
                        <i class="fa fa-desktop txt-color-blueDark"></i> <?= __('Hosts summary') ?>
                        <?php
                        echo __('There are no time frames defined. Time evaluation report data is not available for the selected period.'); ?>
                    </div>
                </div>
            </section>
        <?php
        endif; ?>
            <?php if (!$instantReportDetails['onlyHosts'] && $totalTimeServices != 0): ?>
            <section id="widget-grid" class="">
                <div class="row">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget" role="widget">
                            <header role="heading">
                                <h2><i class="fa fa-gear"></i> <?= __('Services summary') ?></h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <div class="row margin-top-10 font-md padding-bottom-20">
                                    <div class="col-md-12 text-left">
                                        <?php
                                        $overview_chart = $this->PieChart->createPieChart([$totalServicesData[0], $totalServicesData[1], $totalServicesData[2], $totalServicesData[3]]);

                                        echo $this->Html->image(
                                            '/img/charts/' . $overview_chart
                                        ); ?>
                                    </div>
                                    <div class="col-md-12 text-left font-xs">
                                        <?php
                                        for ($i = 0; $i <= 3; $i++):?>
                                            <i class="fa fa-square no-padding <?php echo $this->Status->ServiceStatusTextColor($i); ?> "></i>
                                            <em class="padding-right-20 ">
                                                <?php
                                                echo round($totalServicesData[$i] / $totalTimeServices * 100, 2) . ' % (' . $this->Status->humanSimpleServiceStatus($i) . ')';
                                                ?>
                                            </em>

                                        <?php
                                        endfor;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php
        elseif (!$instantReportDetails['onlyHosts'] && $totalTimeServices == 0):?>
            <section id="widget-grid" class="">
                <div class="row">
                    <div class="well padding-bottom-10">
                        <i class="fa fa-cogs txt-color-blueDark"></i> <?= __('Services summary') ?>
                        <?php
                        echo __('There are no time frames defined. Time evaluation report data is not available for the selected period.'); ?>
                    </div>
                </div>
            </section>
        <?php
        endif;
        endif;


        $hostsNotMonitored = Hash::extract($instantReportData, 'Hosts.{s}.HostsNotMonitored.{n}');
        $servicesNotMonitored = Hash::extract($instantReportData, 'Hosts.{s}.Services.ServicesNotMonitored.{s}');
        if (!empty($hostsNotMonitored) || !empty($servicesNotMonitored)): ?>
            <section id="widget-grid" class="">
                <div class="row">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget" role="widget">
                            <header role="heading">
                                <h2>
                                    <i class="fa fa-user-md"></i> <?php echo __('Not monitored'); ?>
                                </h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <?php
                                if (!$instantReportDetails['onlyServices']):
                                    foreach ($hostsNotMonitored as $hostId => $hostName): ?>
                                        <div>
                                            <i class="fa fa-desktop"></i> <?php echo h($hostName); ?>
                                        </div>
                                    <?php
                                    endforeach;
                                endif;
                                foreach ($servicesNotMonitored as $serviceId => $serviceArray):?>
                                    <div>
                                        <i class="fa fa-gear"></i>
                                        <?php
                                        echo h((isset($serviceArray['Service']['name']) ? $serviceArray['Service']['name'] : $serviceArray['Servicetemplate']['name']));
                                        echo h(' (' . $serviceArray['Host']['name'] . ')');
                                        ?>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php endif; ?>

        <?php if (empty($instantReportData)): ?>
            <div class="row margin-bottom-10">
                <div class="col-md-12"><?php echo __('No hosts/services found'); ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
