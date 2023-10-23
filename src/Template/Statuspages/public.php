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
 * @var \App\Model\Entity\Statuspage $statuspage
 */

?>
<div class="container">
<div class="panel-container panel show m-3">

        <div class="panel-content">
            <div class="card">
                <div class="card-body">
                <p class="lead"><?= h("Statuspage") ?></p>

                <h1><?= h($Statuspage['statuspage']['name']); ?></h1>
                <p class="lead"><?= h($Statuspage['statuspage']['description']) ?></p>
                <!--<hr class="my-4">-->
                </div>
            </div>
        </div>

    <div class="panel-content">
        <div class="margin-bottom-25">
            <?php foreach ($Statuspage['items'] as $item): ?>

                <div class="card mt-5 border-<?= h($item['color']) ?>">
                    <div class="card-header bg-<?= h($item['color']) ?> txt-color-white border-bottom-0">
                        <h3><?= h($item['type']) ?></h3>
                        <h4><?= h($item['name']) ?></h4>
                    </div>
                    <div class="card-body bg-<?= h($item['color']) ?>">
                        <div class="txt-color-white">
                            <?php if ($item['currentState'] > 0 && !$item['isAcknowledged'] && $item['type'] != 'Servicegroup' && $item['type'] != 'Hostgroup'): ?>
                                <div class="bg-<?= h($item['color']) ?>">
                                    <h4><b><i class="far fa-user"></i> <?= h($item['type']) ?> is not acknowledged!</b>
                                    </h4>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['currentState'] > 0 && $item['isAcknowledged'] && $item['type'] != 'Servicegroup' && $item['type'] != 'Hostgroup'): ?>
                                <div class="bg-<?= h($item['color']) ?>">
                                    <h4><b><i class="far fa-user"></i> <?= h($item['type']) ?> not acknowledged!</b>
                                    </h4>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['isAcknowledged'] && $Statuspage['statuspage']['showComments']): ?>
                                <div>
                                    <b><?php echo __('Comment'); ?>
                                        : <?= h($item['acknowledgeData']['comment_data']) ?> </b>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['isAcknowledged'] && !$Statuspage['statuspage']['showComments']): ?>
                                <div>
                                    <b><?php echo __('Comment'); ?>: <?php echo __('Work in progress'); ?> </b>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['problemtext'])): ?>
                                <div class="txt-color-white">
                                    <h4><b><?= h($item['problemtext']) ?> </b></h4>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['problemtext_down'])): ?>
                                <div class="txt-color-white">
                                    <h4><b><?= h($item['problemtext_down']) ?> </b></h4>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php if (!empty($item['plannedDowntimes'])): ?>
                    <div class="card-footer table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <tr> <div><h5><i class="fa fa-power-off"></i><?php echo __('Planned Downtimes for the next 10 days:'); ?></h5></div></tr>
                            <?php foreach ($item['plannedDowntimes'] as $downtime): ?>
                            <tr>
                                <td style="border-width:1px; border-color:lightgray;"><div><h5><?php echo __('Start'); ?>:</h5></div><div><?= h($downtime['scheduledStartTime']) ?></div></td>
                                <td style="border-width:1px; border-color:lightgray;"><div><h5><?php echo __('End'); ?>:</h5></div><div><?= h($downtime['scheduledEndTime']) ?></div></td>
                                <td style="border-width:1px; border-color:lightgray;"><div><h5><?php echo __('Comment'); ?>:</h5></div><div><?= h($downtime['commentData']) ?></div></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>


        </div>
    </div>
</div>




