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

$Logo = new Logo()
?>
<head>
    <?php
    $css = [
        '/css/vendor/bootstrap/css/bootstrap.css',
        '/css/vendor/bootstrap/css/bootstrap-theme.css',
        '/smartadmin/css/font-awesome.css',
        '/smartadmin/css/smartadmin-production.css',
        '/smartadmin/css/your_style.css',
        '/css/app.css',
        '/css/pdf_list_style.css',
        '/css/bootstrap_pdf.css',
    ];
    ?>

    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT . $cssFile; ?>"/>
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
                echo h($this->Time->format($downtimeReportDetails['startDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php
                echo h($this->Time->format($downtimeReportDetails['endDate'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                ?>
            </div>
            <div class="col-md-3 text-left">
                <img src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
            </div>
        </div>
        <?php
        $allHostsWithOutages = Hash::sort(Hash::extract($downtimeReportData['Hosts'], '{s}[0<' . $downtimeReportDetails['totalTime'] . ']'), '{n}.Host.0', 'ASC');
        if (!empty($allHostsWithOutages)):?>
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
                                    <?php echo __('Involved in outages (Hosts):'); ?>
                                </h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <?php
                                foreach (array_chunk($allHostsWithOutages, 10) as $hostsWithOutages):?>
                                    <div class="widget-body">
                                        <?php
                                        $overview_chart = $this->MultipleBarChart->createBarChart(
                                            Set::combine($hostsWithOutages, '{n}.Host.name', '{n}.{n}'
                                            )
                                        );
                                        ?>
                                        <img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>"/>
                                    </div>
                                    <?php
                                    foreach ($hostsWithOutages as $hostWithOutages):?>
                                        <div class="jarviswidget">
                                            <header role="heading">
                                                <h2 class="bold  txt-color-blueDark">
                                                    <i class="fa fa-desktop txt-color-blueDark"></i>
                                                    <?php echo h($hostWithOutages['Host']['name']); ?>
                                                </h2>
                                            </header>
                                            <div class="widget-body font-md txt-color-blueDark">
                                                <div class="col-md-3 ">
                                                    <?php echo __('Description'); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <?php echo h(($hostWithOutages['Host']['description']) ? $hostWithOutages['Host']['description'] : ' - '); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php echo __('IP address'); ?>
                                                </div>
                                                <div class="col-md-9">
                                                    <?php echo h($hostWithOutages['Host']['address']); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php echo __('Status'); ?>
                                                </div>
                                                <?php
                                                for ($i = 0; $i < 3; $i++):?>
                                                    <div class="col-md-3 <?php echo $this->Status->HostStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md padding-5">
                                                        <strong class="txt-color-white">
                                                            <?php
                                                            $percent_value = $hostWithOutages[$i] / $downtimeReportDetails['totalTime'] * 100;
                                                            echo (fmod($percent_value, 1) == 0) ? $percent_value : number_format($percent_value, 3); ?>
                                                            %
                                                            <?php echo '(' . $this->Utils->secondsInHumanShort($hostWithOutages[$i]) . ')'; ?>
                                                        </strong>
                                                    </div>
                                                <?php
                                                endfor;
                                                ?>
                                                <?php
                                                $servicesWithOutages = [];
                                                if (isset($hostWithOutages['Services'])):
                                                    $servicesWithOutages = Hash::sort(Hash::extract($hostWithOutages['Services'], '{s}[0<' . $downtimeReportDetails['totalTime'] . ']'), '{n}.Services.{s}.Service.name', 'ASC');
                                                    if (!empty($servicesWithOutages)):?>
                                                        <div class="col-md-9 padding-top-10 padding-bottom-10">
                                                            <strong class="txt-color-blueDark">
                                                                <?php echo __('Involved in outages (Services):'); ?>
                                                            </strong>
                                                        </div>
                                                        <?php
                                                        foreach ($servicesWithOutages as $serviceWithOutages):?>
                                                            <div class="col-md-12 txt-color-blueDark">
                                                                <i class="fa fa-cog txt-color-blueDark"></i> <?php echo h($serviceWithOutages['Service']['name']);
                                                                ?>
                                                            </div>
                                                            <div class="col-md-3 text-right padding-right-20 text-info">
                                                                <?php echo __('Servicetemplate'); ?>
                                                            </div>
                                                            <div class="col-md-9 text-info">
                                                                <?php echo h($serviceWithOutages['Service']['Servicetemplate']['name']);
                                                                ?>
                                                            </div>
                                                            <div class="col-md-3 text-right padding-bottom-10">
                                                                <?php
                                                                $overview_chart = $this->PieChart->createPieChart([$serviceWithOutages[0], $serviceWithOutages[1], $serviceWithOutages[2], $serviceWithOutages[3]]);
                                                                ?>
                                                                <img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>"
                                                                     width="100"/>
                                                            </div>
                                                            <?php
                                                            for ($i = 0; $i <= 3; $i++):?>
                                                                <div class="col-md-3 text-right font-md">
                                                                    <em>
                                                                        <?php echo $this->Status->humanSimpleServiceStatus($i); ?>
                                                                    </em>
                                                                </div>
                                                                <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                                    <strong class="txt-color-white">
                                                                        <?php
                                                                        $percent_value = $serviceWithOutages[$i] / $downtimeReportDetails['totalTime'] * 100;
                                                                        echo (fmod($percent_value, 1) == 0) ? $percent_value : number_format($percent_value, 3); ?>
                                                                        %
                                                                    </strong>
                                                                </div>
                                                                <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                                    <strong class="txt-color-white">
                                                                        <?php echo '(' . $this->Utils->secondsInHumanShort($serviceWithOutages[$i]) . ')'; ?>
                                                                    </strong>
                                                                </div>
                                                            <?php
                                                            endfor;
                                                        endforeach;
                                                    endif;
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
        $hostsWithoutOutages = Hash::sort(Hash::extract($downtimeReportData['Hosts'], '{s}[0=' . $downtimeReportDetails['totalTime'] . ']'), '{n}.Host.name', 'ASC');
        if (!empty($hostsWithoutOutages)):?>
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
                                    <?php echo __(' Hosts without outages:'); ?>
                                </h2>
                            </header>
                            <div class="well padding-bottom-10">
                                <?php
                                foreach ($hostsWithoutOutages as $hostWithoutOutages):?>
                                    <div class="jarviswidget jarviswidget-color-blueLight">
                                        <header role="heading">
                                            <h2 class="bold  txt-color-blueDark">
                                                <i class="fa fa-desktop txt-color-blueDark"></i>
                                                <?php echo h($hostWithoutOutages['Host']['name']); ?>
                                            </h2>
                                        </header>
                                        <div class="widget-body font-md txt-color-blueDark">
                                            <div class="col-md-3 ">
                                                <?php echo __('Description'); ?>
                                            </div>
                                            <div class="col-md-9">
                                                <?php echo h(($hostWithoutOutages['Host']['description']) ? $hostWithoutOutages['Host']['description'] : ' - '); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo __('IP address'); ?>
                                            </div>
                                            <div class="col-md-9">
                                                <?php echo h($hostWithoutOutages['Host']['address']); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo __('Status'); ?>
                                            </div>
                                            <?php
                                            for ($i = 0; $i < 3; $i++):?>
                                                <div class="col-md-3 <?php echo $this->Status->HostStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md padding-5">
                                                    <strong class="txt-color-white">
                                                        <?php
                                                        $percent_value = $hostWithoutOutages[$i] / $downtimeReportDetails['totalTime'] * 100;
                                                        echo (fmod($percent_value, 1) == 0) ? $percent_value : number_format($percent_value, 3); ?>
                                                        %
                                                        <?php echo '(' . $this->Utils->secondsInHumanShort($hostWithoutOutages[$i]) . ')'; ?>
                                                    </strong>
                                                </div>
                                            <?php
                                            endfor;
                                            ?>
                                            <?php
                                            $servicesWithOutages = [];
                                            if (isset($hostWithoutOutages['Services'])):
                                                $servicesWithOutages = Hash::sort(Hash::extract($hostWithoutOutages['Services'], '{s}[0<' . $downtimeReportDetails['totalTime'] . ']'), '{n}.Services.{s}.Service.name', 'ASC');
                                                if (!empty($servicesWithOutages)):?>
                                                    <div class="col-md-9 padding-top-10 padding-bottom-10">
                                                        <strong class="txt-color-blueDark">
                                                            <?php echo __('Involved in outages (Services):'); ?>
                                                        </strong>
                                                    </div>
                                                    <?php
                                                    foreach ($servicesWithOutages as $serviceWithOutages):?>
                                                        <div class="col-md-12 txt-color-blueDark">
                                                            <i class="fa fa-cog txt-color-blueDark"></i> <?php echo h($serviceWithOutages['Service']['name']);
                                                            ?>
                                                        </div>
                                                        <div class="col-md-3 text-right padding-right-20 text-info">
                                                            <?php echo __('Servicetemplate'); ?>
                                                        </div>
                                                        <div class="col-md-9 text-info">
                                                            <?php echo h($serviceWithOutages['Service']['Servicetemplate']['name']);
                                                            ?>
                                                        </div>
                                                        <div class="col-md-3 text-right padding-bottom-10">
                                                            <?php
                                                            $overview_chart = $this->PieChart->createPieChart([$serviceWithOutages[0], $serviceWithOutages[1], $serviceWithOutages[2], $serviceWithOutages[3]]);
                                                            ?>
                                                            <img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>"
                                                                 width="100"/>
                                                        </div>
                                                        <?php
                                                        for ($i = 0; $i <= 3; $i++):?>
                                                            <div class="col-md-3 text-right font-md">
                                                                <em>
                                                                    <?php echo $this->Status->humanSimpleServiceStatus($i); ?>
                                                                </em>
                                                            </div>
                                                            <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?php
                                                                    $percent_value = $serviceWithOutages[$i] / $downtimeReportDetails['totalTime'] * 100;
                                                                    echo (fmod($percent_value, 1) == 0) ? $percent_value : number_format($percent_value, 3); ?>
                                                                    %
                                                                </strong>
                                                            </div>
                                                            <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                                <strong class="txt-color-white">
                                                                    <?php echo '(' . $this->Utils->secondsInHumanShort($serviceWithOutages[$i]) . ')'; ?>
                                                                </strong>
                                                            </div>
                                                        <?php
                                                        endfor;
                                                    endforeach;
                                                endif;
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach; ?>
                            </div>
                        </div>
                    </article>
                </div>
            </section>
        <?php
        endif;
        $hostsNotMonitored = Hash::sort(
            Hash::extract(
                $downtimeReportData['Hosts'], '{s}.HostsNotMonitored'
            ),
            '{n}.Host.name', 'asc'
        );
        if (!empty($hostsNotMonitored)): ?>
        <section>
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="jarviswidget" role="widget">
                        <header role="heading">
                            <h2>
                                        <span class="fa-stack">
                                            <i class="fa fa-circle-o fa-stack-2x txt-color-blueLight"></i>
                                            <i class="fa fa-check fa-stack-1x ok"></i>
                                        </span>
                                <?php echo __('Hosts without state history records for selected time range:'); ?>
                            </h2>
                        </header>
                        <div class="well padding-bottom-10">
                            <?php
                            foreach ($hostsNotMonitored as $hostNotMonitored):?>
                                <div class="jarviswidget jarviswidget-color-blueLight">
                                    <header role="heading">
                                        <h2>
                                            <i class="fa fa-desktop"></i>
                                            <?php echo h($hostNotMonitored['Host']['name']); ?>
                                        </h2>
                                    </header>
                                    <div class="widget-body">
                                        <div class="col-md-3 ">
                                            <?php echo __('Description'); ?>
                                        </div>
                                        <div class="col-md-9">
                                            <?php echo h(($hostNotMonitored['Host']['description']) ? $hostNotMonitored['Host']['description'] : ' - '); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo __('IP address'); ?>
                                        </div>
                                        <div class="col-md-9">
                                            <?php echo h($hostNotMonitored['Host']['address']); ?>
                                        </div>
                                        <?php
                                        $services = [];
                                        if (isset($downtimeReportData['Hosts'][$hostNotMonitored['Host']['uuid']]['Services'])):
                                            $servicesWithOutages = Hash::sort(
                                                Hash::extract(
                                                    $downtimeReportData['Hosts'][$hostNotMonitored['Host']['uuid']]['Services'],
                                                    '{s}[0<'.$downtimeReportDetails['totalTime'].']'),
                                                '{n}.Services.{s}.Service.name',
                                                'ASC');
                                            if (!empty($servicesWithOutages)):?>
                                                <div class="col-md-9 padding-top-10 padding-bottom-10">
                                                    <strong>
                                                        <?php echo __('Involved in outages (Services):'); ?>
                                                    </strong>
                                                </div>
                                                <?php
                                                foreach ($servicesWithOutages as $serviceWithOutages):?>
                                                    <div class="col-md-12 txt-color-blueDark">
                                                        <i class="fa fa-cog txt-color-blueDark"></i> <?php echo h($serviceWithOutages['Service']['name']);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-3 text-right padding-right-20 text-info">
                                                        <?php echo __('Servicetemplate'); ?>
                                                    </div>
                                                    <div class="col-md-9 text-info">
                                                        <?php echo h($serviceWithOutages['Service']['Servicetemplate']['name']);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-3 text-right padding-bottom-10">
                                                        <?php
                                                        $overview_chart = $this->PieChart->createPieChart([$serviceWithOutages[0], $serviceWithOutages[1], $serviceWithOutages[2], $serviceWithOutages[3]]);
                                                        ?>
                                                        <img src="<?php echo WWW_ROOT; ?>img/charts/<?php echo $overview_chart; ?>"
                                                             width="100"/>
                                                    </div>
                                                    <?php
                                                    for ($i = 0; $i <= 3; $i++):?>
                                                        <div class="col-md-3 text-right font-md">
                                                            <em>
                                                                <?php echo $this->Status->humanSimpleServiceStatus($i); ?>
                                                            </em>
                                                        </div>
                                                        <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                            <strong class="txt-color-white">
                                                                <?php
                                                                $percent_value = $serviceWithOutages[$i] / $downtimeReportDetails['totalTime'] * 100;
                                                                echo (fmod($percent_value, 1) == 0) ? $percent_value : number_format($percent_value, 3); ?>
                                                                %
                                                            </strong>
                                                        </div>
                                                        <div class="col-md-3 <?php echo $this->Status->ServiceStatusColorSimple($i)['class']; ?> downtime-report-state-overview font-md">
                                                            <strong class="txt-color-white">
                                                                <?php echo '(' . $this->Utils->secondsInHumanShort($serviceWithOutages[$i]) . ')'; ?>
                                                            </strong>
                                                        </div>
                                                    <?php
                                                    endfor;
                                                endforeach;
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            <?php
                            endforeach; ?>
                        </div>
                    </div>
                </article>
            </div>
        </section>
        <?php
            endif;
            ?>
        </section>
    </div>
</div>
</body>
