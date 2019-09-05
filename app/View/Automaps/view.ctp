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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-magic fa-fw "></i>
            <?php echo __('Auto Maps'); ?>
            <span>>
                <?php echo __('View'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-magic"></i> </span>
        <h2>
            <?php echo __('View auto map:'); ?>
            {{ automap.name }}
        </h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <?php if ($this->Acl->hasPermission('index', 'automaps')): ?>
                <a back-button fallback-state='AutomapsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if ($this->Acl->hasPermission('edit', 'automaps')): ?>
            <div class="widget-toolbar" role="menu" ng-show="automap.allow_edit">
                <a ui-sref="AutomapsEdit({id: automap.id})" class="btn btn-xs btn-default">
                    <i class="fa fa-cog"></i>
                    <?php echo __('Edit'); ?>
                </a>
            </div>
        <?php endif; ?>

    </header>

    <div>
        <div class="widget-body">
            <div class="row">
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
                    <span class="label-forced label-danger"
                          ng-hide="automap.recursive">
                        <?php echo __('Disabled'); ?>
                    </span>
                    <span class="label-forced label-success"
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

            <div class="row" ng-if="automap.show_label === false && automap.group_by_host === false">
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

            <div class="row" ng-if="automap.show_label === false && automap.group_by_host === true">
                <!-- Status color icons with host headline -->
                <div class="row" ng-repeat="host in servicesByHost">
                    <div class="col-xs-12">
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

                    <div class="col-xs-12">
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

            <div class="row" ng-if="automap.show_label === true && automap.group_by_host === false">
                <!-- Status color icons + Hostname/Service description -->
                <span ng-repeat="host in servicesByHost">
                    <div class="col-xs-12 col-md-6 col-lg-3 ellipsis" ng-repeat="service in host.services">
                        <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.service.id)"
                            <?php endif; ?>
                              title="{{host.host.hostname}}/{{service.service.servicename}}">
                            <servicestatusicon-automap
                                    servicestatus="service.servicestatus"></servicestatusicon-automap>
                            {{host.host.hostname}}/{{service.service.servicename}}
                        </span>
                    </div>
                </span>
            </div>

            <div ng-if="automap.show_label === true && automap.group_by_host === true">
                <!-- Status color icons with host and service name and host headline -->
                <div class="row" ng-repeat="host in servicesByHost">
                    <div class="col-xs-12">
                        <h3 class="margin-bottom-5">
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

            <div class="row" ng-if="automap.use_paginator">
                <hr />
                <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                <?php echo $this->element('paginator_or_scroll'); ?>
            </div>

        </div>
    </div>
</div>

<service-status-details></service-status-details>