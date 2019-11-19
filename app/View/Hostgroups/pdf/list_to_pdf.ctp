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

use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\Logo;

$Logo = new Logo();

/** @var \itnovum\openITCOCKPIT\Core\ValueObjects\User $User */

$UserTime = $User->getUserTime();

?>
<head>

    <?php
    //PDF Output
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
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT . $cssFile; ?>"/>
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
            <img src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hostgroups: ' . $numberOfHostgroups); ?>
        </div>
    </div>
    <div class="row padding-left-10 margin-top-10 font-sm">
        <div class="text-left padding-left-10">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Hosts: ' . $numberOfHosts); ?>
        </div>
    </div>
    <div class="padding-top-10">
        <table id="" class="table table-striped table-bordered smart-form font-xs">
            <thead>
            <tr class="font-md">
                <th class="width-20"><?php echo __('Status'); ?></th>
                <th class="no-sort text-center width-20"><i class="fa fa-user fa-lg"></i></th>
                <th class="no-sort text-center width-20"><i class="fa fa-power-off fa-lg"></i></th>
                <th><?php echo __('Host'); ?></th>
                <th class="width-70"><?php echo __('Status since'); ?></th>
                <th class="width-60"><?php echo __('Last check'); ?></th>
                <th class="width-60"><?php echo __('Next check'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($hostgroups as $hostgroup): ?>
                <!-- Hostgroup -->
                <tr>
                    <td class="bg-color-lightGray" colspan="8">
                        <span class="font-md"><?php echo h($hostgroup['Hostgroup']['Containers']['name']); ?></span>
                    </td>
                </tr>
                <?php if (!empty($hostgroup['Hosts'])): ?>
                    <?php foreach ($hostgroup['Hosts'] as $host): ?>
                        <?php
                        if (isset($hostgroup['Hoststatus'][$host['Host']['uuid']]['Hoststatus'])):
                            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus(
                                $hostgroup['Hoststatus'][$host['Host']['uuid']]['Hoststatus']
                            );
                        else:
                            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([]);
                        endif;

                        $HoststatusIcon = new HoststatusIcon($Hoststatus->currentState());
                        $HoststatusIcon->getTextColor()
                        ?>

                        <tr>
                            <!-- status -->
                            <td class="text-center font-lg">
                                <?php
                                if ($Hoststatus->isFlapping()):
                                    $Hoststatus->getFlappingIconColored();
                                else:
                                    echo '<i class="fa fa-square ' . $HoststatusIcon->getTextColor() . '"></i>';
                                endif;
                                ?>
                            </td>
                            <!-- ACK -->
                            <td class="text-center">
                                <?php if ($Hoststatus->isAcknowledged()): ?>
                                    <i class="fa fa-user fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- Downtime -->
                            <td class="text-center">
                                <?php if ($Hoststatus->isInDowntime()): ?>
                                    <i class="fa fa-power-off fa-lg"></i>
                                <?php endif; ?>
                            </td>
                            <!-- Host -->
                            <td class="font-xs"><?php echo h($host['Host']['name']); ?></td>
                            <!-- status since -->
                            <td class="font-xs">
                                <?php echo $UserTime->format($Hoststatus->getLastStateChange()); ?>
                            </td>
                            <!-- last check -->
                            <td class="font-xs">
                                <?php echo $UserTime->format($Hoststatus->getLastCheck()); ?>
                            </td>
                            <!-- next check -->
                            <td class="font-xs"><?php echo $UserTime->format($Hoststatus->getNextCheck()); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center font-xs"
                            colspan="8"><?php echo __('This host group has no hosts'); ?></td>
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