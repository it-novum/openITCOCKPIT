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
 * @var array $all_services
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
        <i class="fa fa-cog" style="font-size: 20px!important;"></i>
        <?php echo __('Services Overview'); ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left">
        <i class="fa fa-calendar"></i> <?php echo date('F d, Y H:i:s'); ?>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left">
        <i class="fa fa-list-ol"></i> <?php echo __('Number of Services: ' . sizeof($all_services)); ?>
    </div>
</div>
<div class="margin-top-10">
    <table class="table table-striped m-0 table-bordered table-hover table-sm">
        <thead>
        <tr>
            <th><?php echo __('Status'); ?></th>
            <th class="no-sort text-center width-20"><i class="fa fa-user"></i></th>
            <th class="no-sort text-center width-20"><i class="fa fa-power-off"></i></th>
            <th><?php echo __('Servicename'); ?></th>
            <th class="width-160"><?php echo __('Last state change'); ?></th>
            <th class="width-160"><?php echo __('Last check'); ?></th>
            <th><?php echo __('Service output'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $tmp_host_uuid = null;
        foreach ($all_services as $service):
            /** @var \itnovum\openITCOCKPIT\Core\Servicestatus $Servicestatus */
            $Servicestatus = $service['Servicestatus'];

            /** @var \Statusengine2Module\Model\Entity\Hoststatus $Hoststatus */
            $Hoststatus = $service['Hoststatus'];

            $ServicestatusIcon = new \itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon($Servicestatus->currentState());


            if ($tmp_host_uuid != $service['Host']['uuid']):
                $tmp_host_uuid = $service['Host']['uuid'];
                $HoststatusIcon = new HoststatusIcon($Hoststatus->currentState());
                ?>
                <!-- Host -->
                <tr>
                    <td class="bg-color-lightGray" colspan="8">
                        <?php
                        if ($Hoststatus->isFlapping()):
                            echo $Hoststatus->getHostFlappingIconColored();
                        else:
                            echo $HoststatusIcon->getPdfIcon();
                        endif;
                        ?>
                        <span>
                            <?php printf('%s (%s)', h($service['Host']['hostname']), h($service['Host']['address'])); ?>
                        </span>
                    </td>
                </tr>
            <?php endif; ?>
            <!-- Status -->
            <tr>
                <td class="text-center">
                    <?php
                    if ($Servicestatus->isFlapping()):
                        echo $Servicestatus->getServiceFlappingIconColored();
                    else:
                        echo $ServicestatusIcon->getPdfIcon();
                    endif;
                    ?>
                </td>
                <!-- ACK -->
                <td class="text-center">
                    <?php if ($Servicestatus->isAcknowledged()): ?>
                        <i class="fa fa-user fa-lg"></i>
                    <?php endif; ?>
                </td>
                <!-- downtime -->
                <td class="text-center">
                    <?php if ($Servicestatus->isInDowntime()): ?>
                        <i class="fa fa-power-off fa-lg"></i>
                    <?php endif; ?>
                </td>
                <!-- name -->
                <td class="word-break">
                    <?php echo h($service['Service']['servicename']); ?>
                </td>
                <!-- Status Since -->
                <td>
                    <?php echo h($UserTime->format($Servicestatus->getLastStateChange())); ?>
                </td>
                <!-- Last check -->
                <td>
                    <?php echo h($UserTime->format($Servicestatus->getLastCheck())); ?>
                </td>
                <td class="wrapWords">
                    <?php echo h($Servicestatus->getOutput()); ?>
                </td>
            </tr>

        <?php endforeach; ?>

        <?php if (empty($all_services)): ?>
            <tr>
                <td class="text-center" colspan="8"><?php echo __('No entries match the selection'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
