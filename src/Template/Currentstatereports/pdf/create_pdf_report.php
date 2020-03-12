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

/**
 * @var \App\View\AppView $this
 * @var array $all_services
 * @var \itnovum\openITCOCKPIT\Core\Views\UserTime $UserTime
 *
 */

use itnovum\openITCOCKPIT\Core\Views\Logo;


$css = \App\itnovum\openITCOCKPIT\Core\AngularJS\PdfAssets::getCssFiles();
$Logo = new Logo();
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
    <div class="col-6 padding-left-15">
        <i class="fa fa-calendar"></i>
        <?php
        echo __('Current state report ');
        echo h('(' . __('Date: ') . $UserTime->format(time()) . ')');
        ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>


<?php
if (sizeof($all_services) > 0):
    foreach ($all_services as $hostId => $currentStateObjectData):
        if (!empty($currentStateObjectData['Services'])):?>
            <div class="pdf-card">
                <div class="pdf-card-header">
                    <h2>
                        <strong class="<?= $currentStateObjectData['Hoststatus']['humanState']; ?>">
                            <i class="fa fa-desktop <?= $currentStateObjectData['Hoststatus']['humanState']; ?>"></i>
                            <?= h($currentStateObjectData['Host']['hostname']); ?>
                        </strong>
                    </h2>
                </div>
                <div class="pdf-card-body padding-bottom-10">
                    <div class="row">
                        <div class="col-3 ">
                            <?= __('Description'); ?>
                        </div>
                        <div class="col-9">
                            <?= h(($currentStateObjectData['Host']['description']) ? $currentStateObjectData['Host']['description'] : ' - '); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <?= __('IP address'); ?>
                        </div>
                        <div class="col-9">
                            <?= h($currentStateObjectData['Host']['address']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <?= __('Status'); ?>
                        </div>
                        <div class="col-9">
                            <?= h($currentStateObjectData['Hoststatus']['humanState']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <?= __('Status since'); ?>
                        </div>
                        <div class="col-9">
                            <?= h($currentStateObjectData['Hoststatus']['lastCheck']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <?= __('Host output'); ?>
                        </div>
                        <div class="col-9">
                            <?= h($currentStateObjectData['Hoststatus']['output']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 padding-top-20 padding-bottom-10">
                            <i class="fa fa-gears "></i>
                            <?= __('Checks'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php
                            foreach ($currentStateObjectData['Services'] as $serviceData):
                                $PerfdataParser = new \Statusengine\PerfdataParser($serviceData['Servicestatus']['perfdata']);
                                $perfdata = $PerfdataParser->parse();
                                ?>
                                <div class="row padding-top-5 no-padding" style="border-bottom: 1px solid #e1e1e1;">
                                    <div class="col-3" style="font-size: 65%!important;">
                                        <i class="fa fa-square <?= h($serviceData['Servicestatus']['textClass']); ?>"> </i>
                                        <?= h($serviceData['Service']['servicename']); ?>
                                    </div>
                                    <div class="col-2" style="font-size: 65%!important;">
                                        <?= h($serviceData['Servicestatus']['lastCheck']); ?>
                                    </div>
                                    <div class="col-5" style="font-size: 65%!important;">
                                        <?= h($serviceData['Servicestatus']['output']); ?>
                                    </div>
                                    <div class="col-2">
                                        <?php foreach ($perfdata as $label => $gauge): ?>
                                            <div class="col-12 text-center" style="font-size: 65%!important;">
                                                <?= h($label); ?>
                                            </div>
                                            <div
                                                class="col-12 text-center bordered <?= h($serviceData['Servicestatus']['cssClass']); ?>">
                                                <strong class="txt-color-white" style="font-size: 65%!important;">
                                                    <?= h($gauge['current']) . ' ' . h($gauge['unit']) ?>
                                                </strong>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endif;
    endforeach;
else:?>
    <i class="fa fa-lg fa-info-circle "></i>
    <span class="">
        <?= __('No entries match the selection'); ?>
    </span>
<?php
endif;
?>
</body>
