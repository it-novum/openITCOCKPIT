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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="StatuspagesIndex">
            <i class="fas fa-info-circle"></i> <?php echo __('Status pages'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('View'); ?>
    </li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel">
                <div class="mt-1 mr-3">
                <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex'
                   class="btn btn-default btn-xs shadow-0 float-right">
                    <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                </a>
                </div>
            </div>
            <div class="panel mr-3 ml-3 mb-0">
                <div>
                    <div class="w-100">
                        <div class="alert w-100 bg-{{Statuspage.items[0].color}}" role="alert">
                        </div>
                        <div class="ml-2">
                            Statuspage <br>
                            <h1>{{Statuspage.statuspage.name}}</h1>
                        </div>
                        <div ng-if="Statuspage.statuspage.description != ''">
                            <p class="ml-2">{{Statuspage.statuspage.description}}</p>
                        </div>
                        <div class="alert w-100 mb-0 bg-{{Statuspage.items[0].color}}" role="alert">
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="margin-bottom-25">
                        <div class="no-padding" ng-repeat="item in Statuspage.items">
                            <div class="d-flex flex-row min-h-50 mt-2 card w-100">
                                <div class="p-2">
                                    <div class="h-100 status-line bg-{{item.color}} shadow-{{item.color}}"></div>
                                </div>
                                <div>
                                    <div class="w-100">
                                        <div class="row p-2">
                                            <!--<h3>{{item.type}}</h3>-->

                                            <h4 ng-if="item.type === 'Host'">
                                                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                <a ui-sref="HostsBrowser({id:item.id})">
                                                    {{ item.name }}
                                                    <i class="fas fa-external-link-alt padding-left-5"></i>
                                                </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'Service'">
                                                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a ui-sref="ServicesBrowser({id:item.id})">
                                                    {{ item.name }}
                                                    <i class="fas fa-external-link-alt padding-left-5"></i>
                                                </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'Hostgroup'">
                                                <?php if ($this->Acl->hasPermission('extended', 'hostgroups')): ?>
                                                <a ui-sref="HostgroupsExtended({id: item.id})">
                                                    {{ item.name }}
                                                    <i class="fas fa-external-link-alt padding-left-5"></i>
                                                </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'Servicegroup'">
                                                <?php if ($this->Acl->hasPermission('extended', 'servicegroups')): ?>
                                                <a ui-sref="ServicegroupsExtended({id: item.id})">
                                                    {{ item.name }}
                                                    <i class="fas fa-external-link-alt padding-left-5"></i>
                                                </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="p-2">
                                            <div>
                                                <div ng-if="item.currentState > 0 && !item.isAcknowledged && item.type != 'Servicegroup' && item.type != 'Hostgroup'">
                                                    <!--<h4><b><i class="far fa-user"></i> {{item.type}} <?php echo __('is not acknowledged!');?> </b></h4>-->
                                                    <h4><b><i class="far fa-user"></i><?php echo __('State is not acknowledged!');?> </b></h4>
                                                </div>
                                                <div ng-if="item.currentState > 0 && item.isAcknowledged">
                                                    <!--<h4 ng-if="item.type == 'Service'"><b><i class="fas fa-user"></i> <?php echo __('State of service is acknowledged'); ?></b></h4>
                                            <h4 ng-if="item.type == 'Host'"><b><i class="fas fa-user"></i> <?php echo __('State of host is acknowledged'); ?></b></h4>-->
                                                    <h4><b><?php echo __('State is acknowledged'); ?></b></h4>
                                                </div>
                                                <div ng-if="item.isAcknowledged">
                                                    <b ng-if="Statuspage.statuspage.showComments"><?php echo __('Comment'); ?>:
                                                        {{item.acknowledgeData.comment_data }}</b>
                                                    <b ng-if="!Statuspage.statuspage.showComments"><?php echo __('Comment'); ?>: <?php echo __('Work in progress'); ?></b>
                                                </div>
                                            </div>

                                            <div ng-if="item.isInDowntime && item.downtimeData" class="pt-1">
                                                <table class="table">
                                                    <tr>
                                                        <!--<div ng-if="item.type == 'Service'"><h4><i class="fa fa-power-off"></i> <?php echo __('The service is currently in a planned maintenance period');?></b></h4></div>
                                                    <div ng-if="item.type == 'Host'"><h4><i class="fa fa-power-off"></i> <?php echo __('The host is currently in a planned maintenance period');?></b></h4></div>-->
                                                        <h4><i class="fa fa-power-off"></i> <?php echo __(' Is currently in a planned maintenance period');?></b></h4>
                                                    </tr>
                                                    <tr>
                                                        <td><div><h5> <?php echo __('Start'); ?>: {{item.downtimeData.scheduledStartTime}}</h5></div></td>
                                                        <td><div><h5><?php echo __('End'); ?>: {{item.downtimeData.scheduledEndTime}}</h5></div></td>
                                                        <td><div><h5><?php echo __('Comment'); ?>: {{Statuspage.statuspage.showComments ? item.downtimeData.commentData : "work in progress" }}</h5></div></td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div ng-if="item.problemtext"><h4>
                                                    <b>{{item.problemtext}}</b></h4>
                                            </div>
                                            <div ng-if="item.problemtext_down"><h4>
                                                    <b>{{item.problemtext_down}}</b></h4>
                                            </div>
                                        <div class="d-flex justify-content-around">
                                            <div ng-if="item.plannedDowntimes">
                                                <table class="table table-sm w-100">
                                                    <tr class="col-12"><h5><i class="fa fa-power-off"></i><?php echo __('Planned Downtimes for the next 10 days:'); ?></h5></tr>
                                                    <tr ng-repeat="downtime in item.plannedDowntimes">
                                                        <td><h5><?php echo __('Start'); ?>:</h5></td>
                                                        <td><h5> {{downtime.scheduledStartTime}}</h5></td>
                                                        <td><h5><?php echo __('End'); ?>:</h5></td>
                                                        <td><h5> {{downtime.scheduledEndTime}}</h5></td>
                                                        <td><h5><?php echo __('Comment'); ?>:</h5></td>
                                                        <td><h5>{{Statuspage.statuspage.showComments ? downtime.commentData : "work in progress" }}</h5></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 flex-right">
                                    <div class="h-100 status-line bg-{{item.color}} shadow-{{item.color}}"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

