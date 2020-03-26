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
<div class="row">
    <div class="col-6 padding-left-15 font-lg">
        <i class="fa fa-file-image-o" style="font-size: 20px!important;"></i>
        <?php echo h($instantReport['reportDetails']['name']); ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>
<div class="col-12">
    <div class="text-left padding-left-5">
        <i class="fa fa-calendar "></i>
        <?php
        echo __('Analysis period: ');
        echo h($UserTime->format($fromDate)); ?>
        <i class="fas fa-long-arrow-alt-right"></i>
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
        <div class="pdf-card">
            <div class="pdf-card-header">
                <h2>
                    <i class="fa fa-desktop"></i>
                    <?php echo h($hostData['Host']['name']); ?>
                </h2>
            </div>
            <div class="pdf-card-body">
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
                    <div class="row margin-top-10 padding-bottom-20">
                        <div class="col-4">
                            <?php
                            $overview_chart = PieChart::createPieChartOnDisk([
                                $reportData[0],
                                $reportData[1],
                                $reportData[2]
                            ]);
                            ?>
                            <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>" width="300"/>
                        </div>
                        <div class="col-8 padding-top-10">
                            <?php
                            foreach ($reportData['percentage'] as $state => $info):?>
                                <div class="col-4 margin-bottom-5">
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
                <?php
                elseif ($instantReport['reportDetails']['evaluation'] !== 3 &&
                    isset($reportData[0], $reportData[1], $reportData[2]) &&
                    array_sum([$reportData[0], $reportData[1], $reportData[2]]) === 0):?>
                    <i class="fa fa-info-circle "></i>
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
                                <div class="col-12">
                                    <div>
                                        <i class="fas fa-cog"></i>
                                        <?php echo h($serviceData['Service']['name']); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 text-left">
                                        <?php
                                        $overview_chart = BarChart::createBarChartOnDisk([
                                            $reportData[0],
                                            $reportData[1],
                                            $reportData[2],
                                            $reportData[3]
                                        ]);
                                        ?>

                                        <img class="margin-5" src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>"
                                             width="100%"/>
                                    </div>
                                    <?php
                                    foreach ($reportData['percentage'] as $state => $info):?>
                                        <div class="col-2 text-left padding-top-3">
                                            <?php
                                            $ServicestatusIcon = new ServicestatusIcon($state);
                                            echo $ServicestatusIcon->getPdfIcon();
                                            ?>
                                            <span>
                                                <?= $info; ?>
                                            </span>
                                        </div>
                                    <?php endforeach;
                                    ?>
                                </div>
                            <?php endif;
                        endforeach;
                    endif;
                    ?>
                    <br/>
                </div>
            </div>
        </div>
    <?php
    endforeach;
endif;
if ($instantReport['reportDetails']['summary']):
    if ($instantReport['reportDetails']['summary_hosts']):
        $reportData = $instantReport['reportDetails']['summary_hosts']['reportData'];
        ?>
        <div class="pdf-card">
            <div class="pdf-card-header">
                <h2>
                    <i class="fa fa-desktop"></i>
                    <?= __('Hosts summary') ?>
                </h2>
            </div>
            <div class="pdf-card-body">
                <div class="row">
                    <div class="col-4 text-left">
                        <?php
                        $overview_chart = PieChart::createPieChartOnDisk([
                            $reportData[0],
                            $reportData[1],
                            $reportData[2]
                        ]);
                        ?>
                        <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>" width="300"/>
                    </div>
                    <div class="col-8 text-left padding-top-10">
                        <?php
                        foreach ($reportData['percentage'] as $state => $info):?>
                            <div class="col-12 text-left padding-bottom-7">
                                <?php
                                $HoststatusIcon = new HoststatusIcon($state);
                                echo $HoststatusIcon->getPdfIcon();
                                ?>
                                <span>
                                    <?= $info; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif;
    if ($instantReport['reportDetails']['summary_services']):
        $reportData = $instantReport['reportDetails']['summary_services']['reportData']; ?>
        <div class="pdf-card">
            <div class="pdf-card-header">
                <h2>
                    <i class="fa fa-cog"></i>
                    <?= __('Services summary') ?>
                </h2>
            </div>
            <div class="pdf-card-body">
                <div class="row">
                    <div class="col-4 text-left">
                        <?php
                        $overview_chart = PieChart::createPieChartOnDisk([
                            $reportData[0],
                            $reportData[1],
                            $reportData[2],
                            $reportData[3]
                        ]);
                        ?>
                        <img src="<?= WWW_ROOT; ?>img/charts/<?= $overview_chart; ?>" width="300"/>
                    </div>
                    <div class="col-8 text-left padding-top-15">
                        <?php
                        foreach ($reportData['percentage'] as $state => $info):?>
                            <div class="col-12 text-left padding-bottom-7">
                                <?php
                                $ServicestatusIcon = new ServicestatusIcon($state);
                                echo $ServicestatusIcon->getPdfIcon();
                                ?>
                                <span class="padding-5">
                                    <?= $info; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endif;
endif;
?>
</body>
