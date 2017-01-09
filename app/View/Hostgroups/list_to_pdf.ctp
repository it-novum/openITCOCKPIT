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
        'css/vendor/bootstrap/css/bootstrap.css',
        //'css/vendor/bootstrap/css/bootstrap-theme.css',
        'smartadmin/css/font-awesome.css',
        'smartadmin/css/smartadmin-production.css',
        'smartadmin/css/your_style.css',
        'css/app.css',
        'css/bootstrap_pdf.css',
    ];
    ?>

    <?php
    foreach ($css as $cssFile): ?>
        <!-- <link rel="stylesheet" type="text/css" href="<?php // echo WWW_ROOT.$cssFile; ?>" /> -->
    <?php endforeach; ?>

</head>
<body>
<div class="well">
    <div class="row margin-top-10 font-lg no-padding">
        <div class="col-md-9 text-left padding-left-10">
            <i class="fa fa-sitemap txt-color-blueDark padding-left-10"></i>
            <?php echo __('Hostgroups'); ?>
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
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hostgroups: '.$hostgroupCount); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hosts: '.$hostgroupHostCount); ?>
        </div>
    </div>
    <div class="padding-top-10">
        <table id="" class="table table-striped table-bordered smart-form" style="">
            <thead>
            <tr>
                <th style="font-size:x-small;"><?php echo __('Status'); ?></th>
                <th style="font-size:x-small;" class="no-sort text-center"><i class="fa fa-user fa-lg"></i></th>
                <th style="font-size:x-small;" class="no-sort text-center"><i class="fa fa-power-off fa-lg"></i></th>
                <th style="font-size:x-small;"><?php echo __('Host'); ?></th>
                <th style="font-size:x-small;"><?php echo __('Status since'); ?></th>
                <th style="font-size:x-small;"><?php echo __('Last check'); ?></th>
                <th style="font-size:x-small;"><?php echo __('Next check'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($hostgroups as $k => $hostgroup): ?>
                <!-- Hostgroup -->
                <tr>
                    <td class="bg-color-lightGray" colspan="8">
                        <span style="font-size:x-small;"><?php echo $hostgroup['Container']['name']; ?></span>
                    </td>
                </tr>
                <?php if (!empty($hostgroup['Host'])): ?>
                    <?php foreach ($hostgroup['Host'] as $key => $host): ?>
                        <tr>
                            <!-- status -->
                            <td style="font-size:x-small;" class="text-center">
                                <?php
                                if ($host['Hoststatus'][0]['Hoststatus']['is_flapping'] == 1):
                                    echo $this->Monitoring->hostFlappingIconColored($host['Hoststatus'][0]['Hoststatus']['is_flapping'], '', $host['Hoststatus'][0]['Hoststatus']['current_state']);
                                else:
                                    echo '<i class="fa fa-square '.$this->Status->HostStatusTextColor($host['Hoststatus'][0]['Hoststatus']['current_state']).'"></i>';
                                endif;
                                ?>
                            </td>
                            <!-- ACK -->
                            <td style="font-size:x-small;" class="text-center">
                                <?php if ($host['Hoststatus'][0]['Hoststatus']['problem_has_been_acknowledged'] > 0): ?>
                                    <i class="fa fa-user fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- Downtime -->
                            <td style="font-size:x-small;" class="text-center">
                                <?php if ($host['Hoststatus'][0]['Hoststatus']['scheduled_downtime_depth'] > 0): ?>
                                    <i class="fa fa-power-off fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- Host -->
                            <td style="font-size:x-small;"><?php echo $host['name']; ?></td>
                            <!-- status since -->
                            <td style="font-size:x-small;"
                                data-original-title="<?php echo h($this->Time->format($host['Hoststatus'][0]['Hoststatus']['last_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                data-placement="bottom" rel="tooltip" data-container="body">
                                <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($host['Hoststatus'][0]['Hoststatus']['last_state_change']))); ?>
                            </td>
                            <!-- last check -->
                            <td style="font-size:x-small;"><?php echo $this->Time->format($host['Hoststatus'][0]['Hoststatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                            <!-- next check -->
                            <td style="font-size:x-small;"><?php echo $this->Time->format($host['Hoststatus'][0]['Hoststatus']['next_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td style="font-size:xx-small;" class="text-center"
                            colspan="8"><?php echo __('This Hostgroup has no Hosts'); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (empty($hostgroups)): ?>
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