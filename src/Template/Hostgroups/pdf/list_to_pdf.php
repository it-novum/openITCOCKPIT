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

/**
 * @var \App\View\AppView $this
 * @var array $all_hostgroups
 * @var int $numberOfHostgroups
 * @var int $numberOfHosts
 * @var \itnovum\openITCOCKPIT\Core\ValueObjects\User $User
 */

$Logo = new Logo();
$css = \App\itnovum\openITCOCKPIT\Core\AngularJS\PdfAssets::getCssFiles();


$UserTime = $User->getUserTime();

?>
<head>

    <?php
    foreach ($css as $cssFile): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT . $cssFile; ?>"/>
    <?php endforeach; ?>

</head>
<body>

<div class="row">
    <div class="col-6 padding-left-15 font-lg">
        <i class="fa fa-sitemap" style="font-size: 20px!important;"></i>
        <?php echo __('Hostgroups'); ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left padding-left-10">
        <i class="fa fa-calendar"></i> <?php echo date('F d, Y H:i:s'); ?>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left padding-left-10">
        <i class="fa fa-list-ol"></i> <?php echo __('Number of Hostgroups: ' . $numberOfHostgroups); ?>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left padding-left-10">
        <i class="fa fa-list-ol"></i> <?php echo __('Number of Hosts: ' . $numberOfHosts); ?>
    </div>
</div>
<div class="padding-top-10">
    <table class="table table-striped m-0 table-bordered table-hover table-sm">
        <thead>
        <tr>
            <th class="width-20"><?php echo __('Status'); ?></th>
            <th class="no-sort text-center width-20"><i class="fa fa-user"></i></th>
            <th class="no-sort text-center width-20"><i class="fa fa-power-off"></i></th>
            <th><?php echo __('Host'); ?></th>
            <th class="width-90"><?php echo __('Status since'); ?></th>
            <th class="width-90"><?php echo __('Last check'); ?></th>
            <th class="width-90"><?php echo __('Next check'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($hostgroups as $hostgroup): ?>
            <!-- Hostgroup -->
            <tr>
                <td class="bg-color-lightGray" colspan="8">
                    <span><?php echo h($hostgroup['Hostgroup']['container']['name']); ?></span>
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
                        <td class="text-center">
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
                                <i class="fa fa-user"></i>
                            <?php endif; ?>
                        </td>
                        <!-- Downtime -->
                        <td class="text-center">
                            <?php if ($Hoststatus->isInDowntime()): ?>
                                <i class="fa fa-power-off"></i>
                            <?php endif; ?>
                        </td>
                        <!-- Host -->
                        <td><?php echo h($host['Host']['name']); ?></td>
                        <!-- status since -->
                        <td>
                            <?php echo $UserTime->format($Hoststatus->getLastStateChange()); ?>
                        </td>
                        <!-- last check -->
                        <td>
                            <?php echo $UserTime->format($Hoststatus->getLastCheck()); ?>
                        </td>
                        <!-- next check -->
                        <td><?php echo $UserTime->format($Hoststatus->getNextCheck()); ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center"
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
</body>
