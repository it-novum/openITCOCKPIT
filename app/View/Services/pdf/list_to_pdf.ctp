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
    //PDF Output
    $css = [
        'css/vendor/bootstrap/css/bootstrap.css',
        'smartadmin/css/font-awesome.css',
        'smartadmin/css/smartadmin-production.css',
        'smartadmin/css/your_style.css',
        'css/app.css',
        'css/bootstrap_pdf.css',
        'css/pdf_list_style.css',
    ];
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT.$cssFile; ?>"/>
    <?php endforeach; ?>
</head>
<body>
<div class="well padding-20">
    <div class="row margin-top-10 font-lg no-padding">
        <div class="col-md-9 text-left">
            <i class="fa fa-cog txt-color-blueDark"></i>
            <?php echo __('Services'); ?>
        </div>
        <div class="col-md-3 text-left">
            <img src="<?php echo WWW_ROOT; ?>/img/logo.png" width="200"/>
        </div>
    </div>
    <div class="row  margin-top-10 padding-left-20 font-sm">
        <div class="text-left ">
            <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
        </div>
    </div>
    <div class="row margin-top-10 padding-left-20 font-sm">
        <div class="text-left">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Services: '. count($all_services)); ?>
        </div>
    </div>
    <div class="margin-top-10">
        <table class="table table-striped table-bordered font-xs">
            <thead>
            <tr class="font-md">
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
            <?php if (!empty($all_services)): ?>
                <?php foreach ($all_services as $key => $service): ?>
                    <?php if ($key == 0 || $all_services[$key-1]['Host'] != $service['Host']): ?>
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
                    <?php endif; ?>
                    <?php if (!empty($service['Service'])): ?>
                        <!-- Status -->
                        <tr class="font-xs">
                            <td class="text-center">
                                <?php
                                if ($service['Servicestatus']['is_flapping'] == 1):
                                    echo $this->Monitoring->serviceFlappingIconColored($service['Servicestatus']['is_flapping'], '', $service['Servicestatus']['current_state']);
                                else:
                                    echo '<i class="fa fa-square '.$this->Status->ServiceStatusTextColor($service['Servicestatus']['current_state']).'"></i>';
                                endif;
                                ?>
                            </td>
                            <!-- ACK -->
                            <td class="text-center">
                                <?php if ($service['Servicestatus']['problem_has_been_acknowledged'] > 0): ?>
                                    <i class="fa fa-user fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- downtime -->
                            <td class="text-center">
                                <?php if ($service['Servicestatus']['scheduled_downtime_depth'] > 0): ?>
                                    <i class="fa fa-power-off fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- name -->
                            <td>
                                <?php if (!empty($service['Service']['name'])) {
                                    echo $service['Service']['name'];
                                } else {
                                    echo $service['Servicetemplate']['name'];
                                }
                                ?>
                            </td>
                            <!-- Status Since -->
                            <td>
                                <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($service['Servicestatus']['last_state_change']))); ?>
                            </td>
                            <!-- Last check -->
                            <td><?php echo $this->Time->format($service['Servicestatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?>
                            </td>
                            <!-- Next check -->
                            <td><?php echo $this->Time->format($service['Servicestatus']['next_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?>
                            </td>
                            <td class="wrapWords"><?php echo $service['Servicestatus']['output']; ?></td>
                            <!-- klasse machen -->
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-center font-xs"
                                colspan="8"><?php echo __('This host has no Services'); ?></td>
                        </tr>
                    <?php endif; ?>
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