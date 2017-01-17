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
<head>

    <?php
    // PDF output
    $css = [
        'css/vendor/bootstrap/css/bootstrap.css',
        //'css/vendor/bootstrap/css/bootstrap-theme.css',
        'smartadmin/css/font-awesome.css',
        'smartadmin/css/smartadmin-production.css',
        'smartadmin/css/your_style.css',
        'css/app.css',
        'css/bootstrap_pdf.css',
        'css/pdf_list_style.css',
    ];
    ?>

    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT.$cssFile; ?>"/>
    <?php endforeach; ?>

</head>
<body>
<div class="well">
    <div class="row margin-top-10 font-lg no-padding">
        <div class="col-md-9 text-left padding-left-10">
            <i class="fa fa-sitemap txt-color-blueDark padding-left-10"></i>
            <?php echo __('Servicegroups'); ?>
        </div>
        <div class="col-md-3 text-left">
            <img src="<?php echo WWW_ROOT; ?>/img/logo.png" width="200"/>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Servicegroups: '.$servicegroupCount); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hosts: '.$hostCount); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Services: '.$serviceCount); ?>
        </div>
    </div>
    <div class="padding-top-10">
        <table id="" class="table table-striped table-bordered smart-form font-xs" style="">
            <thead>
            <tr class="font-md">
                <th><?php echo __('Status'); ?></th>
                <th class="no-sort text-center"><i class="fa fa-user fa-lg"></i></th>
                <th class="no-sort text-center"><i class="fa fa-power-off fa-lg"></i></th>
                <th><?php echo __('Servicename'); ?></th>
                <th class="width-70"><?php echo __('Status since'); ?></th>
                <th class="width-60"><?php echo __('Last check'); ?></th>
                <th class="width-60"><?php echo __('Next check'); ?></th>
                <th><?php echo __('Service output'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($servicegroupstatus)): ?>
                <?php foreach ($servicegroupstatus as $k => $servicegroup): ?>
                    <!-- Servicegroup -->
                    <tr>
                        <td class="bg-color-lightGray" colspan="8">
                            <i class="fa fa-sitemap txt-color-blueDark padding-left-10"></i>
                            <span style="font-weight:bold;"><?php echo $servicegroup['Container']['name']; ?></span>
                        </td>
                    </tr>
                    <?php foreach ($servicegroup['elements'] as $host): ?>
                        <!-- Host -->
                        <tr>
                            <td class="bg-color-lightGray" colspan="8">
                                <i class="fa fa-desktop txt-color-blueDark padding-left-20"></i>
                                <span><?php echo $host['name']; ?></span>
                            </td>
                        </tr>
                        <?php if (!empty($host['Services'])): ?>
                            <?php foreach ($host['Services'] as $key => $servicestatus): ?>
                                <!-- Status -->
                                <tr>
                                    <td class="text-center font-lg">
                                        <?php
                                        if ($servicestatus['Status']['is_flapping'] == 1):
                                            echo $this->Monitoring->serviceFlappingIconColored($servicestatus['Status']['is_flapping'], '', $servicestatus['Status']['current_state']);
                                        else:
                                            echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor($servicestatus['Status']['current_state']).'"></i>';
                                        endif;
                                        ?>
                                    </td>
                                    <!-- ACK -->
                                    <td class="text-center">
                                        <?php if ($servicestatus['Status']['problem_has_been_acknowledged'] > 0): ?>
                                            <i class="fa fa-user fa-lg"></i>
                                        <?php endif; ?>
                                    </td>
                                    <!-- downtime -->
                                    <td class="text-center">
                                        <?php if ($servicestatus['Status']['scheduled_downtime_depth'] > 0): ?>
                                            <i class="fa fa-power-off fa-lg"></i>
                                        <?php endif; ?>
                                    </td>
                                    <!-- name -->
                                    <td class="font-xs">
                                        <?php echo $servicestatus['Servicename']; ?>
                                    </td>
                                    <!-- Status Since -->
                                    <td class="font-xs"
                                        data-original-title="<?php echo h($this->Time->format($servicestatus['Status']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                        data-placement="bottom" rel="tooltip" data-container="body">
                                        <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($servicestatus['Status']['last_state_change']))); ?>
                                    </td>
                                    <!-- Last check -->
                                    <td class="font-xs"><?php echo $this->Time->format($servicestatus['Status']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                    <!-- Next check -->
                                    <td class="font-xs"><?php echo $this->Time->format($servicestatus['Status']['next_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                    <!-- Service output -->
                                    <td class="font-xs"><?php echo $servicestatus['Status']['output']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center font-xs"
                                    colspan="8"><?php echo __('This host has no Services'); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center font-xs" colspan="8"><?php echo __('No entries match the selection'); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>