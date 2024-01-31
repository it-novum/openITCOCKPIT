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
 * @var array $statuspage
 * @var int $id
 */

?>

<div class="container-fluid">
    <div class="row">

        <div class="panel w-100 m-lg-3">

            <!-- Statuspage over all status -->
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="no-padding">

                        <div class="col-12 pt-2 pb-4">
                            <h4 class="d-block l-h-n m-0 fw-500">
                                <?= h($statuspage['statuspage']['name']); ?>
                                <small class="m-0 l-h-n">
                                    <?= h($statuspage['statuspage']['description']); ?>
                                </small>
                            </h4>
                        </div>


                        <div
                            class="p-3 statuspage-bg-<?= h($statuspage['statuspage']['cumulatedColor']); ?> rounded overflow-hidden position-relative text-white">
                            <div>
                                <h2 class="d-block l-h-n m-0 fw-500">
                                    <?= h($statuspage['statuspage']['cumulatedHumanStatus']); ?>
                                </h2>
                            </div>
                            <i class="<?= h($statuspage['statuspage']['cumulatedIcon']); ?> statuspage-icon position-absolute pos-right pos-bottom opacity-15 pr-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end overall status -->


            <div class="panel-container show">
                <div class="panel-content">
                    <?php foreach ($statuspage['items'] as $item): ?>
                        <div class="no-padding margin-bottom-10">
                            <!-- Status page object card -->
                            <div class="card d-flex flex-row min-h-110 margin-bottom-10">
                                <div class="p-2">
                                    <div
                                        class="h-100 status-line bg-<?= h($item['cumulatedColor']); ?> shadow-<?= h($item['cumulatedColor']); ?>"></div>
                                </div>

                                <div class="flex-1">
                                    <div class="row p-2">
                                        <div class="col-12  h4">
                                            <?= h($item['name']); ?>
                                        </div>

                                        <!-- Handle status name -->
                                        <div class="col-12">
                                            <h4 class="<?= h($item['cumulatedColor']); ?>"><?= h($item['cumulatedStateName']); ?></h4>
                                        </div>
                                        <!-- end of status name -->

                                        <!-- Handle acknowledgement comments -->
                                        <div class="col-12 ">
                                            <div class="row">
                                                <div class="col-12 ">
                                                    <?php if (!empty($item['acknowledgedProblemsText'])): ?>
                                                        <div>
                                                            <i class="far fa-user"></i>
                                                            <?= h($item['acknowledgedProblemsText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['acknowledgeComment'])): ?>
                                                        <div>
                                                            <?php echo __('Comment'); ?>:
                                                            <?= h($item['acknowledgeComment']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['hostgroupHostAcknowledgementText'])): ?>
                                                        <div>
                                                            <i class="far fa-user"></i>
                                                            <?= h($item['hostgroupHostAcknowledgementText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['hostgroupServiceAcknowledgementText'])): ?>
                                                        <div>
                                                            <i class="far fa-user"></i>
                                                            <?= h($item['hostgroupServiceAcknowledgementText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['downtimeHostgroupHostText'])): ?>
                                                        <div>
                                                            <i class="fa fa-power-off"></i>
                                                            <?= h($item['downtimeHostgroupHostText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['plannedDowntimeHostgroupHostText'])): ?>
                                                        <div>
                                                            <i class="fa fa-power-off"></i>
                                                            <?= h($item['plannedDowntimeHostgroupHostText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['downtimeHostgroupServiceText'])): ?>
                                                        <div>
                                                            <i class="fa fa-power-off"></i>
                                                            <?= h($item['downtimeHostgroupServiceText']); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($item['plannedDowntimeHostgroupServiceText'])): ?>
                                                        <div>
                                                            <i class="fa fa-power-off"></i>
                                                            <?= h($item['plannedDowntimeHostgroupServiceText']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end of acknowledgements -->


                                        <!-- handle current downtime comments -->
                                        <?php if (!empty($item['isInDowntime']) && !empty($item['downtimeData'])): ?>
                                            <div class="col-12 ">
                                                <div class="row">
                                                    <div class="col-12 ">
                                                        <div class="pt-1">
                                                            <i class="fa fa-power-off"></i>
                                                            <?= __('Is currently in a planned maintenance period'); ?>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-3">
                                                                <?= __('Start'); ?>:
                                                                <?= h($item['downtimeData']['scheduledStartTime']); ?>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3">
                                                                <?= __('End'); ?>:
                                                                <?= h($item['downtimeData']['scheduledEndTime']); ?>
                                                            </div>
                                                            <div class="col-xs-12 col-md-3">
                                                                <?= __('Comment'); ?>:
                                                                <?= h($item['downtimeData']['comment']); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <!-- end of current downtimes -->

                                        <!-- handle plant downtime comments -->
                                        <?php if (!empty($item['plannedDowntimeData'])): ?>
                                            <div class="col-12 ">
                                                <div class="row">
                                                    <div class="col-12 ">
                                                        <div class="pt-1">
                                                            <i class="fa fa-power-off"></i>
                                                            <?= __('Planned Downtimes for the next 10 days:'); ?>
                                                        </div>
                                                        <?php foreach ($item['plannedDowntimeData'] as $downtime): ?>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-md-3">
                                                                    <?= __('Start'); ?>:
                                                                    <?= h($downtime['scheduledStartTime']); ?>
                                                                </div>
                                                                <div class="col-xs-12 col-md-3">
                                                                    <?= __('End'); ?>:
                                                                    <?= h($downtime['scheduledEndTime']); ?>
                                                                </div>
                                                                <div class="col-xs-12 col-md-3">
                                                                    <?= __('Comment'); ?>:
                                                                    <?= h($downtime['comment']); ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <!-- end of planed downtimes -->


                                    </div>
                                </div>

                                <div class="p-2 hidden-md-down">
                                    <div
                                        class="h-100 status-line bg-<?= h($item['cumulatedColor']); ?> shadow-<?= h($item['cumulatedColor']); ?>"></div>
                                </div>
                            </div>
                            <!-- end object card -->
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


        </div>


    </div>
</div>
