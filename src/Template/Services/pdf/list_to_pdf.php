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
<div class="well padding-20">
    <div class="row margin-top-10 font-lg no-padding">
        <div class="col-md-9 text-left">
            <i class="fa fa-cogc txt-color-blueDark"></i>
            <?php echo __('Services Overview'); ?>
        </div>
        <div class="col-md-3 text-left">
            <img src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
        </div>
    </div>
    <div class="row  margin-top-10 padding-left-20 font-sm">
        <div class="text-left ">
            <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
        </div>
    </div>
    <div class="row margin-top-10 padding-left-20 font-sm">
        <div class="text-left">
            <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Services: ' . sizeof($all_services)); ?>
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
                <th><?php echo __('Last state change'); ?></th>
                <th><?php echo __('Last check'); ?></th>
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
                        <td class="bg-color-lightGray font-md" colspan="8">
                            <?php
                            if ($Hoststatus->isFlapping()):
                                echo $Hoststatus->getHostFlappingIconColored();
                            else:
                                echo $HoststatusIcon->getPdfIcon();
                            endif;
                            ?>
                            <span class="font-md">
                                <?php printf('%s (%s)', h($service['Host']['hostname']), h($service['Host']['address'])); ?>
                            </span>
                        </td>
                    </tr>
                <?php endif; ?>
                <!-- Status -->
                <tr class="font-xs">
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
                    <td>
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
                    <td class="text-center font-xs" colspan="8"><?php echo __('No entries match the selection'); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
