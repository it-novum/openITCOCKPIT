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
<div class="row no-padding">
    <div class="col-xs-12">
        <?php if ($widgetServiceStateArray180['total'] > 0): ?>
            <div style="height: 140px;">
                <div class="col-xs-12 text-center chart180">
                    <?php
                    $overview_chart = $this->PieChart->createHalfPieChart($widgetServiceStateArray180['state']);
                    echo $this->Html->image(
                        '/img/charts/' . $overview_chart
                    );
                    $stateColors = [
                        'ok',
                        'warning',
                        'critical',
                        'unknown',
                    ];

                    $bgColors = [
                        1 => 'bg-color-orange',
                        2 => 'bg-color-red',
                        3 => 'bg-color-blueDark',
                    ];

                    ?>
                </div>
                <div class="col-xs-12 stats180 margin-top-10" style="display:none; position: absolute; top:0px;">
                    <?php foreach ([1, 2, 3] as $state): ?>
                        <div class="col-xs-4">
                            <div class="col-xs-12 <?php echo $bgColors[$state]; ?>">
                                <a href="/services/index<?php echo Router::queryString([
                                    'filter'    => [
                                        'Servicestatus.current_state' => [$state => 1]
                                    ],
                                    'sort'      => 'Servicestatus.last_state_change',
                                    'direction' => 'desc'
                                ]); ?>" style="color:#FFF;">
                                    <?php echo __('( %s ) ' . strtolower($this->Status->humanSimpleServiceStatus($state)), $widgetServiceStateArray180['state'][$state]); ?>
                                </a>
                            </div>
                            <div class="col-xs-12">
                                <?php if ($widgetServiceStateArray180['not_handled'][$state] > 0): ?>
                                    <a href="/services/index<?php echo Router::queryString([
                                        'filter'                    => [
                                            'Servicestatus.current_state' => [$state => 1]
                                        ],
                                        'has_not_been_acknowledged' => 1,
                                        'sort'                      => 'Servicestatus.last_state_change',
                                        'direction'                 => 'desc'
                                    ]); ?>">
                                        <?php echo __('( %s ) not handled', $widgetServiceStateArray180['not_handled'][$state]); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('( 0 ) not handled'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12">
                                <?php if ($widgetServiceStateArray180['acknowledged'][$state] > 0): ?>
                                    <a href="/services/index<?php echo Router::queryString([
                                        'filter'                => [
                                            'Servicestatus.current_state' => [$state => 1]
                                        ],
                                        'has_been_acknowledged' => 1,
                                        'sort'                  => 'Servicestatus.last_state_change',
                                        'direction'             => 'desc'
                                    ]); ?>">
                                        <?php echo __('( %s ) acknowledged', $widgetServiceStateArray180['acknowledged'][$state]); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('( 0 ) acknowledged'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12">
                                <?php if ($widgetServiceStateArray180['in_downtime'][$state] > 0): ?>
                                    <a href="/services/index<?php echo Router::queryString([
                                        'filter'      => [
                                            'Servicestatus.current_state' => [$state => 1]
                                        ],
                                        'in_downtime' => 1,
                                        'sort'        => 'Servicestatus.last_state_change',
                                        'direction'   => 'desc'
                                    ]); ?>">
                                        <?php echo __('( %s ) in downtime', $widgetServiceStateArray180['in_downtime'][$state]); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('( 0 ) in downtime'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-12">
                                <?php if ($widgetServiceStateArray180['passive'][$state] > 0): ?>
                                    <a href="/services/index<?php echo Router::queryString([
                                        'filter'      => [
                                            'Servicestatus.current_state' => [$state => 1]
                                        ],
                                        'passive' => 1,
                                        'sort'        => 'Servicestatus.last_state_change',
                                        'direction'   => 'desc'
                                    ]); ?>">
                                        <?php echo __('( %s ) passive', $widgetServiceStateArray180['passive'][$state]); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo __('( 0 ) passive'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="text-center font-xs">
                <div class="col-xs-12">
                    <div class="toggleDetailsForPiechart"><i class="fa fa-angle-down"></i></div>
                </div>
                <?php foreach ($widgetServiceStateArray180['state'] as $state => $stateCount): ?>
                    <div class="col-md-3 no-padding">
                        <a href="/services/index<?php echo Router::queryString([
                            'filter'    => [
                                'Servicestatus.current_state' => [$state => 1]
                            ],
                            'sort'      => 'Servicestatus.last_state_change',
                            'direction' => 'desc'
                        ]); ?>">
                            <i class="fa fa-square <?php echo $stateColors[$state] ?>"></i>
                            <?php echo $stateCount . ' (' . round($stateCount / $widgetServiceStateArray180['total'] * 100, 2) . ' %)'; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-muted padding-top-80">
                <h5><?php echo __('No services are monitored on your system. Please create first a service'); ?></h5>
            </div>
        <?php endif; ?>
    </div>
</div>
