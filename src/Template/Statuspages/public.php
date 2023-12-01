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
<div class="row">
    <div class="panel w-100 m-3">

        <div class="panel">
            <div class="card">
                <div class="alert w-100 bg-<?= h($Statuspage['items'][0]['color']) ?>" role="alert">
                </div>
                <div class="ml-2">


                    <p class="lead"><?= h("Statuspage") ?></p>

                    <h1><?= h($Statuspage['statuspage']['name']); ?></h1>
                    <p class="lead"><?= h($Statuspage['statuspage']['description']) ?></p>
                    <!--<hr class="my-4">-->
                </div>
                <div class="alert w-100 bt-0 bg-<?= h($Statuspage['items'][0]['color']) ?>" role="alert">
                </div>
            </div>
        </div>

        <div class="panel-content">
            <div class="margin-bottom-25">
                <?php foreach ($Statuspage['items'] as $item): ?>

                    <div class="d-flex flex-row min-h-50 mt-2 card w-100">
                        <div class="p-2">
                            <div class="h-100 status-line bg-<?= h($item['color']) ?>  shadow-<?= h($item['color']) ?>"></div>
                        </div>
                        <div>
                            <div class="w-100">
                                <div class="row p-2">
                                    <h4><?= h($item['name']) ?></h4>
                                </div>
                            </div>

                            <?php if ($item['currentState'] > 0 && !$item['isAcknowledged'] && $item['type'] != 'Servicegroup' && $item['type'] != 'Hostgroup'): ?>
                                <div>
                                    <h4><b><i class="far fa-user"></i> State is not acknowledged!</b>
                                    </h4>
                                </div>
                            <?php endif; ?>
                            <?php if ($item['currentState'] > 0 && $item['isAcknowledged'] && $item['type'] != 'Servicegroup' && $item['type'] != 'Hostgroup'): ?>
                                <div>
                                    <h4><b><i class="far fa-user"></i> State is acknowledged!</b>
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
                            <?php if (!empty($item['isInDowntime']) && !empty($item['downtimeData'])): ?>
                                <div class="pt-1">
                                    <table class="table">
                                        <tr>
                                            <!--<div ng-if="item.type == 'Service'"><h4><i class="fa fa-power-off"></i> <?php echo __('The service is currently in a planned maintenance period'); ?></b></h4></div>
                                                    <div ng-if="item.type == 'Host'"><h4><i class="fa fa-power-off"></i> <?php echo __('The host is currently in a planned maintenance period'); ?></b></h4></div>-->
                                            <h4>
                                                <i class="fa fa-power-off"></i> <?php echo __(' Is currently in a planned maintenance period'); ?></b>
                                            </h4>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><h5> <?php echo __('Start'); ?>
                                                        : <?= h($item['downtimeData']['scheduledStartTime']) ?></h5>
                                                </div>
                                            </td>
                                            <td>
                                                <div><h5><?php echo __('End'); ?>
                                                        : <?= h($item['downtimeData']['scheduledEndTime']) ?></h5></div>
                                            </td>
                                            <td>
                                                <div><h5><?php echo __('Comment'); ?>:
                                                        <?php if ($Statuspage['statuspage']['showComments']): ?>
                                                            <?= h($item['downtimeData']['commentData']) ?>
                                                        <?php else: ?>
                                                            Work in progress
                                                        <?php endif; ?>
                                                    </h5>
                                        </tr>
                                    </table>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($item['problemtext'])): ?>
                                <div>
                                    <h4><b><?= h($item['problemtext']) ?> </b></h4>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['problemtext_down'])): ?>
                                <div>
                                    <h4><b><?= h($item['problemtext_down']) ?> </b></h4>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($item['plannedDowntimes'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm w-100">
                                        <tr>
                                            <div><h5>
                                                    <i class="fa fa-power-off"></i><?php echo __('Planned Downtimes for the next 10 days:'); ?>
                                                </h5></div>
                                        </tr>
                                        <?php foreach ($item['plannedDowntimes'] as $downtime): ?>
                                            <tr>
                                                <td>
                                                    <h5><?php echo __('Start'); ?>:
                                                        <?= h($downtime['scheduledStartTime']) ?></h5>
                                                </td>
                                                <td>
                                                    <h5><?php echo __('End'); ?>:
                                                        <?= h($downtime['scheduledEndTime']) ?></h5>
                                                </td>
                                                <td>
                                                    <h5><?php echo __('Comment'); ?>:
                                                        <?php if ($Statuspage['statuspage']['showComments']): ?>
                                                            <?= h($downtime['commentData']) ?>
                                                        <?php else: ?>
                                                            Work in progress
                                                        <?php endif; ?>
                                                    </h5>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            <?php endif; ?>


                        </div>
                        <div class="p-2 flex-right">
                            <div class="h-100 status-line bg-<?= h($item['color']) ?>   shadow-<?= h($item['color']) ?>"></div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>


    </div>
</div>



