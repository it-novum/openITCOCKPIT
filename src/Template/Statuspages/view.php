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
                <div class="d-flex justify-content-center margin-top-10 margin-bottom-10">
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
                            <div class="card mt-5">
                                <div class="card-header bg-{{item.color}} txt-color-white">
                                    <h3>{{item.type}}</h3>
                                    <h4>{{item.name}}</h4>
                                </div>
                                <div class="card-body bg-color-lightGray">
                                    <div class="txt-color-blue">
                                        <div ng-if="item.currentState > 0 && !item.isAcknowledged"
                                             class="txt-color-orange">
                                            <h4><b><i class="far fa-user"></i> {{item.type}} is not acknowledged!</b>
                                            </h4>
                                        </div>
                                        <div ng-if="item.currentState > 0 && item.isAcknowledged">
                                            <h4><b><i class="fas fa-user"></i> {{item.type}} is acknowledged!</b></h4>
                                        </div>
                                        <div ng-if="item.isAcknowledged">
                                            <b ng-if="Statuspage.statuspage.showComments">Comment:
                                                {{item.acknowledgeData.comment_data }}</b>
                                            <b ng-if="!Statuspage.statuspage.showComments">Comment: Work in
                                                progress!</b>
                                        </div>
                                    </div>
                                    <div class="txt-color-red">
                                        <div ng-if="item.isInDowntime">
                                            <div>
                                                <h4><b><i class="fa fa-power-off"></i> {{item.type}} is currently in a
                                                        planned maintenance period!</b></h4>
                                            </div>
                                            <div><b> From: {{item.downtimeData.scheduledStartTime}}</b></div>
                                            <div><b> To: {{item.downtimeData.scheduledEndTime}}</b></div>
                                            <div ng-if="Statuspage.statuspage.showComments"><b>Comment:
                                                    {{item.downtimeData.commentData}}</b></div>
                                        </div>
                                    </div>
                                    <div ng-if="item.problemtext" class="txt-color-yellow"><h4>
                                            <b>{{item.problemtext}}</b></h4></div>
                                    <div ng-if="item.cumulatedState == 0 &&item.currentState == 0 && !item.isInDowntime"
                                         class="txt-color-green">
                                        <h4><b>Full operational!</b></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
