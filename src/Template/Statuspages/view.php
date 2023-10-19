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

use itnovum\openITCOCKPIT\Core\Views\Logo;

$logo = new Logo();
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
            <div class="panel-hdr">
                <div class="margin-top-10 margin-bottom-10">
                    <!--<div class="w-100 bg-{{Statuspage.items[0].color}} txt-color-white padding-bottom-2 margin-bottom-25"
                         style="border: 1px solid rgba(0,0,0,.125);">-->
                    <div class="w-100 padding-bottom-2 margin-bottom-25">
                        <div>
                            <!--<h5>Statuspage</h5>-->
                            <h1>{{Statuspage.statuspage.name}}</h1>
                        </div>
                        <div ng-if="Statuspage.statuspage.description != ''">
                            <!--<h5>Description</h5>-->
                            <p class="lead">{{Statuspage.statuspage.description}}</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="margin-bottom-25">
                        <div class="col no-padding" ng-repeat="item in Statuspage.items">
                            <div class="card mt-5 border-{{item.color}}">
                                <div class="card-header bg-{{item.color}} txt-color-white border-bottom-0">
                                    <h3>{{item.type}}</h3>
                                    <h4>{{item.name}}</h4>
                                </div>
                                <div class="card-body bg-{{item.color}}">
                                    <div class="txt-color-white">
                                        <div ng-if="item.currentState > 0 && !item.isAcknowledged && item.type != 'Servicegroup' && item.type != 'Hostgroup'"
                                             class="bg-{{item.color}}">
                                            <h4><b><i class="far fa-user"></i> {{item.type}} is not acknowledged!</b>
                                            </h4>
                                        </div>
                                        <div ng-if="item.currentState > 0 && item.isAcknowledged && item.type != 'Servicegroup' && item.type != 'Hostgroup'">
                                            <h4><b><i class="fas fa-user"></i> {{item.type}} is acknowledged!</b></h4>
                                        </div>
                                        <div ng-if="item.isAcknowledged">
                                            <b ng-if="Statuspage.statuspage.showComments">Comment:
                                                {{item.acknowledgeData.comment_data }}</b>
                                            <b ng-if="!Statuspage.statuspage.showComments">Comment: Work in
                                                progress!</b>
                                        </div>
                                    </div>
                                    <div class="txt-color-white">
                                        <div ng-if="item.isInDowntime && item.downtimeData" class="pt-3">
                                            <table class="table table-sm">
                                                <tr> <div><h4><i class="fa fa-power-off"></i>{{item.type}} is currently in a
                                                            planned maintenance period!</b></h4></div></tr>
                                                <tr class="txt-color-white bg-{{item.color}}">
                                                    <td><div class="txt-color-white"><h5> From: {{item.downtimeData.scheduledStartTime}}</h5></div></td>
                                                    <td><div class="txt-color-white"><h5>End: {{item.downtimeData.scheduledEndTime}}</h5></div></td>
                                                    <td><div class="txt-color-white"><h5>:Comment: {{Statuspage.statuspage.showComments ? item.downtimeData.commentData : "work in progress" }}</h5></div></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div ng-if="item.problemtext" class="txt-color-white"><h4>
                                            <b>{{item.problemtext}}</b></h4>
                                    </div>
                                    <div ng-if="item.problemtext_down" class="txt-color-white"><h4>
                                            <b>{{item.problemtext_down}}</b></h4>
                                    </div>
                                    <div ng-if="item.cumulatedState == 0 && item.currentState == 0 && !item.isInDowntime"
                                         class="txt-color-white">
                                        <h4><b>Full operational!</b></h4>
                                    </div>

                                </div>
                                <div ng-if="item.plannedDowntimes" class="card-footer table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <tr> <div><h5><i class="fa fa-power-off"></i>Planned Downtimes for the next 10 days:</h5></div></tr>
                                        <tr ng-repeat="downtime in item.plannedDowntimes">
                                            <td style="border-width:1px; border-color:lightgray;"><div><h5>Start:</h5></div><div> {{downtime.scheduledStartTime}}</div></td>
                                            <td style="border-width:1px; border-color:lightgray"><div><h5>End:</h5></div><div>{{downtime.scheduledEndTime}}</div></td>
                                            <td style="border-width:1px; border-color:lightgray;"><div><h5>:Comment:</h5></div><div> {{Statuspage.statuspage.showComments ? downtime.commentData : "work in progress" }}</div></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
