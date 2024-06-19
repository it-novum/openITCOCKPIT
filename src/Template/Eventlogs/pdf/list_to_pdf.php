<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

use itnovum\openITCOCKPIT\Core\Views\Logo;

/**
 * @var \App\View\AppView $this
 * @var array $all_events
 * @var array $logTypes
 */

$Logo = new Logo();
$css = \App\itnovum\openITCOCKPIT\Core\AngularJS\PdfAssets::getCssFiles();

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
        <i class="fa fa-file-text" style="font-size: 20px!important;"></i>
        <?php echo __('Event Logs'); ?>
    </div>
    <div class="col-6">
        <img class="float-right" src="<?php echo $Logo->getLogoPdfPath(); ?>" width="200"/>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left padding-left-5">
        <i class="fa fa-calendar txt-color-blueDark"></i> <?php echo date('F d, Y H:i:s'); ?>
    </div>
</div>
<div class="col-12 no-padding">
    <div class="text-left padding-left-5">
        <i class="fa fa-list-ol txt-color-blueDark"></i> <?php echo __('Number of Events: ' . sizeof($all_events)); ?>
    </div>
</div>
<div class="padding-top-10">
    <table class="table table-striped m-0 table-bordered table-hover table-sm">
        <thead>
        <tr>
            <th>
                <?php echo __('Type'); ?>
            </th>
            <th>
                <?php echo __('Name'); ?>
            </th>
            <?php if (in_array('login', $logTypes) || in_array('user_delete', $logTypes) || in_array('user_password_change', $logTypes)): ?>
                <th>
                    <?php echo __('Email'); ?>
                </th>
            <?php endif; ?>
            <th>
                <?php echo __('Date'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($all_events as $event): ?>
            <tr>
                <td>
                    <?= h($event['type']); ?>
                </td>
                <td>
                    <?php if ($event['recordExists']): ?>
                        <?= h($event['name']); ?>
                    <?php else: ?>
                        <s><?= h($event['name']); ?></s>
                    <?php endif; ?>
                </td>
                <?php if (in_array('login', $logTypes) || in_array('user_delete', $logTypes) || in_array('user_password_change', $logTypes)): ?>
                    <td>
                        <?php if ($event['recordExists']): ?>
                            <?= h($event['user_email']); ?>
                        <?php else: ?>
                            <s><?= h($event['data']['user_email']); ?></s>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?= h($event['time']); ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($all_events)): ?>
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
