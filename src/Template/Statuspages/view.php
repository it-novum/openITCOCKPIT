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
                        <div class="alert w-100 bg-{{Statuspage.statuspage.cumulatedColor}}" role="alert">
                        </div>
                        <div class="ml-2">
                            Statuspage <br>
                            <h1>{{Statuspage.statuspage.name}}</h1>
                        </div>
                        <div ng-if="Statuspage.statuspage.description != ''">
                            <p class="ml-2">{{Statuspage.statuspage.description}}</p>
                        </div>
                        <div class="alert w-100 mb-0 bg-{{Statuspage.statuspage.cumulatedColor}}" role="alert">
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="margin-bottom-25">
                        <div class="no-padding" ng-repeat="item in Statuspage.items">
                            <div class="d-flex flex-row min-h-50 card pt-2 m-0 w-100">
                                <div class="pr-2">
                                    <div class="h-100 status-line bg-{{item.cumulatedColor}} shadow-{{item.color}}"></div>
                                </div>
                                <div>
                                    <div class="w-100">
                                        <div class="row pl-2">
                                            <h4 ng-if="item.type === 'host'">
                                                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                    <a ui-sref="HostsBrowser({id:item.id})">
                                                        {{ item.name }}
                                                        <i class="fas fa-external-link-alt padding-left-5"></i>
                                                    </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'service'">
                                                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                    <a ui-sref="ServicesBrowser({id:item.id})">
                                                        {{ item.name }}
                                                        <i class="fas fa-external-link-alt padding-left-5"></i>
                                                    </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'hostgroup'">
                                                <?php if ($this->Acl->hasPermission('extended', 'hostgroups')): ?>
                                                    <a ui-sref="HostgroupsExtended({id: item.id})">
                                                        {{ item.name }}
                                                        <i class="fas fa-external-link-alt padding-left-5"></i>
                                                    </a>
                                                <?php else: ?>
                                                    {{ item.name }}
                                                <?php endif; ?>
                                            </h4>
                                            <h4 ng-if="item.type === 'servicegroup'">
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
                                    <h4 class="{{item.cumulatedColor}}">{{item.cumulatedStateName}}</h4>

                                    <div class="pr-2">
                                        <div>
                                            <div ng-if="item.acknowledgedProblemsText">
                                                <h4>
                                                    <b><i class="far fa-user"></i> {{item.acknowledgedProblemsText}}
                                                    </b></h4>
                                            </div>
                                            <div ng-if="item.acknowledgeComment">
                                                    <b><?php echo __('Comment'); ?>
                                                        : {{item.acknowledgeComment}}
                                                    </b>
                                            </div>
                                            <div ng-if="item.hostgroupHostAcknowledgementText">
                                                <h4>
                                                    <b><i class="far fa-user"></i> {{item.hostgroupHostAcknowledgementText}}
                                                    </b></h4>
                                            </div>
                                            <div ng-if="item.hostgroupServiceAcknowledgementText">
                                                <h4>
                                                    <b><i class="far fa-user"></i> {{item.hostgroupServiceAcknowledgementText}}
                                                    </b></h4>
                                            </div>
                                            <div ng-if="item.downtimeHostgroupHostText">
                                                <h4>
                                                    <b><i class="fa fa-power-off"></i> {{item.downtimeHostgroupHostText}}
                                                    </b></h4>
                                            </div>
                                            <div ng-if="item.plannedDowntimeHostgroupHostText">
                                                <h5>
                                                    <b><i class="fa fa-power-off"></i> {{item.plannedDowntimeHostgroupHostText}}
                                                    </b></h5>
                                            </div>
                                            <div ng-if="item.downtimeHostgroupServiceText">
                                                <h4>
                                                    <b><i class="fa fa-power-off"></i> {{item.downtimeHostgroupServiceText}}
                                                    </b></h4>
                                            </div>
                                            <div ng-if="item.plannedDowntimeHostgroupServiceText">
                                                <h5>
                                                    <b><i class="fa fa-power-off"></i> {{item.plannedDowntimeHostgroupServiceText}}
                                                    </b></h5>
                                            </div>
                                        </div>

                                        <div ng-if="item.isInDowntime && item.downtimeData" class="pt-1">
                                            <table class="table">
                                                <tr>
                                                    <h4>
                                                        <i class="fa fa-power-off"></i> <?php echo __(' Is currently in a planned maintenance period'); ?></b>
                                                    </h4>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div><h5> <?php echo __('Start'); ?>:
                                                                {{item.downtimeData.scheduledStartTime}}</h5></div>
                                                    </td>
                                                    <td>
                                                        <div><h5><?php echo __('End'); ?>:
                                                                {{item.downtimeData.scheduledEndTime}}</h5></div>
                                                    </td>
                                                    <td>
                                                        <div><h5><?php echo __('Comment'); ?>:
                                                                {{item.downtimeData.comment}}</h5></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-around">
                                            <div ng-if="item.plannedDowntimeData">
                                                <table class="table table-sm w-100">
                                                    <tr class="col-12"><h5><i
                                                                    class="fa fa-power-off"></i><?php echo __('Planned Downtimes for the next 10 days:'); ?>
                                                        </h5></tr>
                                                    <tr ng-repeat="downtime in item.plannedDowntimeData">
                                                        <td><h5><?php echo __('Start'); ?>:</h5></td>
                                                        <td><h5> {{downtime.scheduledStartTime}}</h5></td>
                                                        <td><h5><?php echo __('End'); ?>:</h5></td>
                                                        <td><h5> {{downtime.scheduledEndTime}}</h5></td>
                                                        <td><h5><?php echo __('Comment'); ?>:</h5></td>
                                                        <td><h5>
                                                                {{downtime.comment}}</h5></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-2 flex-right">
                                    <div class="h-100 status-line bg-{{item.cumulatedColor}} shadow-{{item.cumulatedColor}}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

