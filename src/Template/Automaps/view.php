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
        <a ui-sref="AutomapsIndex">
            <i class="fa fa-magic"></i> <?php echo __('Auto Maps'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-eye"></i> <?php echo __('View'); ?>
    </li>
</ol>

<query-handler-directive></query-handler-directive>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('View auto map: '); ?>
                    <span class="fw-300"><i>{{ automap.name }}</i></span>

                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'automaps')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='AutomapsIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('edit', 'automaps')): ?>
                        <button class="btn btn-xs btn-default mr-1 shadow-0" ui-sref="AutomapsEdit({id: automap.id})">
                            <i class="fas fa-cog"></i> <?php echo __('Edit'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row no-gutters">
                        <div class="col-xs-12 col-md-5">
                            <strong><?php echo __('Regular expression for hosts') ?>:</strong>
                            <code>{{ automap.host_regex }}</code>
                        </div>
                        <div class="col-xs-12 col-md-5">
                            <strong><?php echo __('Regular expression for services') ?>:</strong>
                            <code>{{ automap.service_regex }}</code>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <strong><?php echo __('Recursive') ?>:</strong>
                            <span class="badge badge-danger"
                                  ng-hide="automap.recursive">
                                <?php echo __('Disabled'); ?>
                            </span>
                            <span class="badge badge-success"
                                  ng-show="automap.recursive">
                                <?php echo __('Enabled'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <hr class="simple">
                        </div>
                    </div>

                    <div class="row no-gutters" ng-if="automap.show_label === false && automap.group_by_host === false">
                        <!-- Only status color icons -->
                        <span ng-repeat="host in servicesByHost">
                            <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                                  title="{{host.host.hostname}}/{{service.service.servicename}}"
                                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                    ng-click="showServiceStatusDetails(service.service.id)"
                                <?php endif; ?>
                                  ng-repeat="service in host.services">
                                <servicestatusicon-automap
                                    servicestatus="service.servicestatus"></servicestatusicon-automap>
                            </span>
                        </span>
                    </div>

                    <div class="row no-gutters" ng-if="automap.show_label === false && automap.group_by_host === true">
                        <!-- Status color icons with host headline -->
                        <div class="row" ng-repeat="host in servicesByHost">
                            <div class="col-lg-12">
                                <h3 class="margin-bottom-5">
                                    <i class="fa fa-desktop"></i>
                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a ui-sref="HostsBrowser({id: host.host.id})" class="a-clean">
                                            {{host.host.name}}
                                        </a>
                                    <?php else: ?>
                                        {{host.host.name}}
                                    <?php endif; ?>
                                </h3>
                            </div>

                            <div class="col-lg-12">
                                <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                                      title="{{service.host.hostname}}/{{service.service.servicename}}"
                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                        ng-click="showServiceStatusDetails(service.service.id)"
                                    <?php endif; ?>
                                      ng-repeat="service in host.services">
                                    <servicestatusicon-automap
                                        servicestatus="service.servicestatus"></servicestatusicon-automap>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters d-flex flex-row mb-3"
                         ng-if="automap.show_label === true && automap.group_by_host === false">
                        <!-- Status color icons + Hostname/Service description -->
                        <div ng-repeat-start="host in servicesByHost" ng-if="false">
                        </div>
                        <div ng-repeat="service in host.services" ng-repeat-end=""
                             class="col-lg-3 col-md-4 col-sm-6 col-xs-12 pointer ellipsis"
                             style="font-size:{{automap.font_size_html}};"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.service.id)"
                            <?php endif; ?>
                             title="{{host.host.hostname}}/{{service.service.servicename}}">
                            <servicestatusicon-automap
                                servicestatus="service.servicestatus"></servicestatusicon-automap>
                            {{host.host.hostname}}/{{service.service.servicename}}
                        </div>

                    </div>

                    <div ng-if="automap.show_label === true && automap.group_by_host === true">
                        <!-- Status color icons with host and service name and host headline -->
                        <div class="row no-gutters padding-bottom-5" ng-repeat="host in servicesByHost">
                            <div class="col-lg-12">
                                <h3 class="margin-0">
                                    <i class="fa fa-desktop"></i>
                                    <strong>
                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                            <a ui-sref="HostsBrowser({id: host.host.id})" class="a-clean">
                                                {{host.host.name}}
                                            </a>
                                        <?php else: ?>
                                            {{host.host.name}}
                                        <?php endif; ?>
                                    </strong>
                                </h3>
                            </div>

                            <div class="col-xs-12 col-md-6 col-lg-3 ellipsis" ng-repeat="service in host.services">
                                <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                        ng-click="showServiceStatusDetails(service.service.id)"
                                    <?php endif; ?>
                                      title="{{host.host.hostname}}/{{service.service.servicename}}">
                                    <servicestatusicon-automap
                                        servicestatus="service.servicestatus"></servicestatusicon-automap>
                                    {{service.service.servicename}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-top-10 margin-bottom-10" ng-if="automap.use_paginator">
                        <div class="col-lg-12">
                            <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                            <?php echo $this->element('paginator_or_scroll'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<service-status-details></service-status-details>

