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
    $css = [
        'smartadmin/css/font-awesome.css',
        'smartadmin/css/smartadmin-production.css',
        'css/bootstrap_pdf.css',
//	'/css/vendor/bootstrap/css/bootstrap.css',
        //'/css/vendor/bootstrap/css/bootstrap-theme.css',
//	'/smartadmin/css/font-awesome.css',
//	'/smartadmin/css/smartadmin-production.css',
//	'/smartadmin/css/your_style.css',
//	'/css/app.css',
//	'/css/bootstrap_pdf.css'
    ];
    ?>

    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $cssFile; ?>"/>
    <?php endforeach; ?>

</head>
<body>
<div class="well">
    <div class="row margin-top-10 font-lg no-padding">
        <div class="col-md-9 text-left padding-left-10">
            <i class="fa fa-cog txt-color-blueDark padding-left-10"></i>
            <?php echo __('Services'); ?>
        </div>
        <div class="col-md-3 text-left">
            <img src="/img/logo.png" width="200"/>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Services: '.$serviceCount); ?>
        </div>
    </div>
    <div class="padding-top-10">
        <table style="width:100%!important;" class="table table-striped table-bordered font-xs">
            <thead>
            <tr class="font-sm">
                <th><?php echo __('Status'); ?></th>
                <th class="no-sort text-center"><i class="fa fa-user fa-lg"></i></th>
                <th class="no-sort text-center"><i class="fa fa-power-off fa-lg"></i></th>
                <th><?php echo __('Servicename'); ?></th>
                <th><?php echo __('Status since'); ?></th>
                <th><?php echo __('Last check'); ?></th>
                <th><?php echo __('Next check'); ?></th>
                <th><?php echo __('Service output'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($servicestatus as $k => $service): ?>
                <!-- Host -->
                <tr>
                    <td class="bg-color-lightGray font-md" colspan="8">
                        <?php
                        if (isset($service['Host']['Hoststatus'][0]['Hoststatus'])):
                            if ($service['Host']['Hoststatus'][0]['Hoststatus']['is_flapping'] == 1):
                                echo $this->Monitoring->hostFlappingIconColored($service['Host']['Hoststatus'][0]['Hoststatus']['is_flapping'], '', $service['Host']['Hoststatus'][0]['Hoststatus']['current_state']);
                            else:
                                echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor($service['Host']['Hoststatus'][0]['Hoststatus']['current_state']).'"></i>';
                            endif;
                        else:
                            echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor().'"></i>';
                        endif;
                        ?>
                        <span class="font-md"><?php echo $service['Host']['name']; ?>
                            (<?php echo $service['Host']['address']; ?>)</span>
                    </td>
                </tr>
                <?php if (!empty($service['ServiceData'])): ?>
                    <?php foreach ($service['ServiceData'] as $key => $servicedata): ?>
                        <!-- Status -->
                        <tr>
                            <td class="text-center">
                                <?php
                                if ($servicedata['Servicestatus']['is_flapping'] == 1):
                                    echo $this->Monitoring->serviceFlappingIconColored($servicedata['Servicestatus']['is_flapping'], '', $servicedata['Servicestatus']['current_state']);
                                else:
                                    echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor($servicedata['Servicestatus']['current_state']).'"></i>';
                                endif;
                                ?>
                            </td>
                            <!-- ACK -->
                            <td class="text-center">
                                <?php if ($servicedata['Servicestatus']['problem_has_been_acknowledged'] > 0): ?>
                                    <i class="fa fa-user fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- downtime -->
                            <td class="text-center">
                                <?php if ($servicedata['Servicestatus']['scheduled_downtime_depth'] > 0): ?>
                                    <i class="fa fa-power-off fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- name -->
                            <td class="font-xs">
                                <?php if (!empty($servicedata['Service']['name'])) {
                                    echo $servicedata['Service']['name'];
                                } else {
                                    echo $servicedata['Servicetemplate']['name'];
                                }
                                ?>
                            </td>
                            <!-- Status Since -->
                            <td class="font-xs"
                                data-original-title="<?php echo h($this->Time->format($servicedata['Servicestatus']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                data-placement="bottom" rel="tooltip" data-container="body">
                                <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($servicedata['Servicestatus']['last_state_change']))); ?>
                            </td>
                            <!-- Last check -->
                            <td class="font-xs"><?php echo $this->Time->format($servicedata['Servicestatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                            <!-- Next check -->
                            <td class="font-xs"><?php echo $this->Time->format($servicedata['Servicestatus']['next_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                            <!-- Service output -->
                            <td class="font-xs"><?php echo $servicedata['Servicestatus']['output']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center font-xs" colspan="8"><?php echo __('This host has no Services'); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (empty($servicedata)): ?>
                <div class="noMatch">
                    <center>
                        <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                    </center>
                </div>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>