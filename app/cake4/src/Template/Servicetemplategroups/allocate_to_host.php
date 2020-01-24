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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ServicetemplategroupsIndex">
            <i class="fa fa-pencil-square-o"></i> <?php echo __('Allocate service template group'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('To host'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Allocate service template group'); ?>
                    <span class="fw-300"><i><?php echo __('to host group'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'servicetemplategroups')): ?>
                        <a back-button fallback-state='ServicetemplategroupsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">


                    <div class="form-group required">
                        <label class="control-label" for="ContainersSelect">
                            <?php echo __('Service template group'); ?>
                        </label>
                        <select
                            id="ContainersSelect"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="servicetemplategroups"
                            callback="loadServicetemplategroups"
                            ng-options="servicetemplategroup.key as servicetemplategroup.value for servicetemplategroup in servicetemplategroups"
                            ng-model="id">
                        </select>
                        <div ng-show="id < 1" class="warning-glow">
                            <?php echo __('Please select a service template group.'); ?>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="HostSelect">
                            <?php echo __('Host'); ?>
                        </label>
                        <select
                            id="HostSelect"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="hosts"
                            callback="loadHosts"
                            ng-options="host.key as host.value for host in hosts"
                            ng-model="hostId">
                        </select>
                        <div ng-show="hostId < 1" class="warning-glow">
                            <?php echo __('Please select a host.'); ?>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-9 col-lg-7" ng-show="hostId > 0">
                        <div class="col col-md-2 control-label">
                            <!-- Fancy layout -->
                        </div>
                        <div class="col col-xs-10">
                            <div class="text-info">
                                <i class="fa fa-info-circle"></i>
                                <?php echo __('Please notice:'); ?>
                                <?php echo __('Services which use a service template that could not be assigned to the selected host due to container permissions, will be removed automatically.'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Service deployment -->

                    <div class="col-xs-12 col-md-12 col-lg-12" ng-show="hostId > 0">
                        <hr>
                        <fieldset>
                            <h4><?php echo __('Service/s to deploy on target host:'); ?></h4>

                            <div ng-repeat="serviceToDeploy in servicesToDeploy" class="padding-bottom-5 padding-left-10">

                                <input type="checkbox" ng-model="serviceToDeploy.createServiceOnTargetHost">

                                {{serviceToDeploy.servicetemplate.name}}
                                <span class="text-info"
                                      ng-show="serviceToDeploy.servicetemplate.description">
                                    ({{serviceToDeploy.servicetemplate.description}})
                                </span>

                                <span class="text-info"
                                      ng-show="serviceToDeploy.doesServicetemplateExistsOnTargetHost"
                                      data-original-title="<?php echo __('Service already exist on selected host. Tick the box to create a duplicate.'); ?>"
                                      data-placement="right"
                                      rel="tooltip"
                                      data-container="body">
                                    <i class="fa fa-info-circle"></i>
                                </span>

                                <span ng-show="serviceToDeploy.doesServicetemplateExistsOnTargetHostAndIsDisabled"
                                      data-original-title="<?php echo __('Service already exist on selected host but is disabled. Tick the box to create a duplicate.'); ?>"
                                      data-placement="right"
                                      rel="tooltip"
                                      data-container="body">
                                    <i class="fa fa-plug"></i>
                                </span>

                            </div>

                            <div class="row">
                                <div class="col-xs-6 col-md-2 no-padding">
                                    <span ng-click="selectAll()" class="pointer">
                                        <i class="fa fa-lg fa-check-square"></i>
                                        <?php echo __('Select all'); ?>
                                    </span>
                                </div>
                                <div class="col-xs-6 col-md-2 no-padding">
                                    <span ng-click="undoSelection()" class="pointer">
                                        <i class="fa fa-lg fa-square"></i>
                                        <?php echo __('Undo selection'); ?>
                                    </span>
                                </div>
                            </div>

                        </fieldset>
                    </div>


                    <div class="card margin-top-10">
                        <div class="card-body">
                            <div class="float-right">
                                <button class="btn btn-primary" ng-click="submit()">
                                    <?php echo __('Allocate to host'); ?>
                                </button>
                                <?php if ($this->Acl->hasPermission('index', 'servicetemplategroups')): ?>
                                    <a back-button fallback-state='ServicetemplategroupsIndex' class="btn btn-default">
                                        <?php echo __('Cancel'); ?>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
