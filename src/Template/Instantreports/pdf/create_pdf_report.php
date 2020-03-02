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
use itnovum\openITCOCKPIT\Core\Views\BarChart;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Core\Views\PieChart;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

/**
 * @var \App\View\AppView $this
 * @var array $instantReport
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
<div class="jarviswidget no-bordered">
    <div class="well no-bordered">
        <div class="row no-padding">
            <div class="col-md-12 text-right">
                <img src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 padding-bottom-10 text-left">
                <i class="fa fa-file-image-o txt-color-blueDark"></i>
                <?php echo __(h($instantReport['reportDetails']['name'])); ?>
            </div>
            <div class="col-md-12 padding-bottom-10 text-left">
                <i class="fa fa-calendar txt-color-blueDark"></i>
                <?php
                echo __('Analysis period: ');
                echo h($UserTime->format($fromDate)); ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php
                echo h($UserTime->format($toDate));
                ?>
            </div>
        </div>
        <?php
        if (!$instantReport['reportDetails']['summary'] && !empty($instantReport['hosts'])):
            foreach ($instantReport['hosts'] as $hostUuid => $hostData):
                $reportData = isset($hostData['Host']['reportData']) ? $hostData['Host']['reportData'] : [];
                ?>
                <section id="widget-grid" class="">
                    <div class="row">
                        <article
                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable font-md txt-color-blueDark">
                            <div class="jarviswidget no-bordered" role="widget">
                                <header role="heading">
                                    <h2 class="txt-color-blueDark" style="width:97%"><i
                                            class="fa fa-desktop txt-color-blueDark"></i> <?php echo h($hostData['Host']['name']); ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <?php
                                    // evaluation '3' => ServicesOnly
                                    if ($instantReport['reportDetails']['evaluation'] !== 3 &&
                                        isset($reportData[0], $reportData[1], $reportData[2]) &&
                                        array_sum([
                                                $reportData[0],
                                                $reportData[1],
                                                $reportData[2]
                                            ]
                                        ) > 0): ?>
                                        <div class="row margin-top-10 font-md padding-bottom-20">
                                            <div class="col-md-12 font-md padding-bottom-5">
                                                <div class="col-md-3 text-left no-padding">
                                                    <?php
                                                    $overview_chart = PieChart::createPieChartOnDisk([
                                                        $reportData[0],
                                                        $reportData[1],
                                                        $reportData[2]
                                                    ]);
                                                    ?>
                                                    <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                         width="100"/>
                                                </div>
                                                <div class="col-md-8 text-left no-padding padding-top-10">
                                                    <?php
                                                    foreach ($reportData['percentage'] as $state => $info):?>
                                                        <div class="col-md-12 text-left no-padding margin-bottom-5">
                                                            <?php
                                                            $HoststatusIcon = new HoststatusIcon($state);
                                                            echo $HoststatusIcon->getPdfIcon();
                                                            ?>
                                                            <span>
                                                                <?php echo $info; ?>
                                                            </span>
                                                        </div>
                                                    <?php endforeach;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    elseif ($instantReport['reportDetails']['evaluation'] !== 3 &&
                                        isset($reportData[0], $reportData[1], $reportData[2]) &&
                                        array_sum([$reportData[0], $reportData[1], $reportData[2]]) === 0):?>
                                        <i class="fa fa-info-circle txt-color-blueDark"></i>
                                        <?php
                                        echo __('There are no time frames defined. Time evaluation report data is not available for the selected period.');
                                    endif; ?>
                                    <div>
                                        <?php
                                        if (isset($hostData['Host']['Services'])):
                                            foreach ($hostData['Host']['Services'] as $serviceUuid => $serviceData):
                                                $reportData = isset($serviceData['Service']['reportData']) ? $serviceData['Service']['reportData'] : [];
                                                if (isset($reportData[0], $reportData[1], $reportData[2], $reportData[3]) &&
                                                    array_sum(
                                                        [$reportData[0], $reportData[1], $reportData[2], $reportData[3]]
                                                    ) > 0
                                                ):?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="font-md txt-color-blueDark">
                                                                <i class="fa fa-gear txt-color-blueDark"></i>
                                                                <?php echo h($serviceData['Service']['name']); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 font-md padding-bottom-5">
                                                            <div class="col-md-3 text-left no-padding">
                                                                <?php
                                                                $overview_chart = BarChart::createBarChartOnDisk([
                                                                    $reportData[0],
                                                                    $reportData[1],
                                                                    $reportData[2],
                                                                    $reportData[3]
                                                                ]);
                                                                ?>
                                                                <img
                                                                    src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                                    width="120"/>
                                                            </div>
                                                            <?php
                                                            foreach ($reportData['percentage'] as $state => $info):?>
                                                                <div
                                                                    class="col-md-2 text-left font-md no-padding padding-top-3">
                                                                    <?php
                                                                    $ServicestatusIcon = new ServicestatusIcon($state);
                                                                    echo $ServicestatusIcon->getPdfIcon();
                                                                    ?>
                                                                    <span>
                                                                        <?php echo $info; ?>
                                                                    </span>
                                                                </div>
                                                            <?php endforeach;
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endif;
                                            endforeach;
                                        endif;
                                        ?>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            <?php
            endforeach;
        endif;
        if ($instantReport['reportDetails']['summary']):
            if ($instantReport['reportDetails']['summary_hosts']):
                $reportData = $instantReport['reportDetails']['summary_hosts']['reportData'];
                ?>
                <section id="widget-grid" class="">
                    <div class="row">
                        <article
                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable font-md txt-color-blueDark">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2 class="txt-color-blueDark" style="width:97%">
                                        <i class="fa fa-desktop txt-color-blueDark"></i> <?= __('Hosts summary') ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <div class="row font-md">
                                        <div class="col-md-2 text-left">
                                            <?php
                                            $overview_chart = PieChart::createPieChartOnDisk([
                                                $reportData[0],
                                                $reportData[1],
                                                $reportData[2]
                                            ]);
                                            ?>
                                            <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                 width="100"/>
                                        </div>
                                        <div class="col-md-10 text-left font-md padding-top-10">
                                            <?php
                                            foreach ($reportData['percentage'] as $state => $info):?>
                                                <div class="col-md-12 text-left font-md padding-bottom-7">
                                                    <?php
                                                    $HoststatusIcon = new HoststatusIcon($state);
                                                    echo $HoststatusIcon->getPdfIcon();
                                                    ?>
                                                    <span class="">
                                                        <?php
                                                        echo $info;
                                                        ?>
                                                    </span>
                                                </div>
                                            <?php endforeach;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            <?php
            endif;
            if ($instantReport['reportDetails']['summary_services']):
                $reportData = $instantReport['reportDetails']['summary_services']['reportData']; ?>
                <section id="widget-grid" class="">
                    <div class="row">
                        <article
                            class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable font-md txt-color-blueDark">
                            <div class="jarviswidget jarviswidget-sortable" role="widget">
                                <header role="heading">
                                    <h2 class="txt-color-blueDark" style="width:97%">
                                        <i class="fa fa-cog txt-color-blueDark"></i> <?= __('Services summary') ?>
                                    </h2>
                                </header>
                                <div class="well padding-bottom-10">
                                    <div class="row margin-top-10 font-md padding-bottom-20">
                                        <div class="col-md-2 text-left">
                                            <?php
                                            $overview_chart = PieChart::createPieChartOnDisk([
                                                $reportData[0],
                                                $reportData[1],
                                                $reportData[2],
                                                $reportData[3]
                                            ]);
                                            ?>
                                            <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                                 width="100"/>
                                        </div>
                                        <div class="col-md-10 text-left font-md padding-top-15">
                                            <?php
                                            foreach ($reportData['percentage'] as $state => $info):?>
                                                <div class="col-md-12 text-left font-md padding-bottom-7">
                                                    <?php
                                                    $ServicestatusIcon = new ServicestatusIcon($state);
                                                    echo $ServicestatusIcon->getPdfIcon();
                                                    ?>
                                                    <span class="padding-5">
                                                        <?php
                                                        echo $info;
                                                        ?>
                                                    </span>
                                                </div>
                                            <?php endforeach;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            <?php
            endif;
        endif;
        ?>
    </div>
</div>
</body>
