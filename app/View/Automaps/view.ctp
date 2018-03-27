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
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Automaps'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-magic"></i> </span>
        <h2><?php echo __('View:'); ?> {{ automap.name }}</h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <strong><?php echo __('Regular expression for hosts') ?>:</strong>
                    {{ automap.host_regex }}
                </div>
                <div class="col-xs-12 col-md-5">
                    <strong><?php echo __('Regular expression for services') ?>:</strong>
                    {{ automap.service_regex }}
                </div>
                <div class="col-xs-12 col-md-2">
                    <strong><?php echo __('Recursive') ?>:</strong>
                    <i class="fa fa-check txt-color-greenDark" ng-show="automap.recursive"></i>
                    <i class="fa fa-times txt-color-red" ng-hide="automap.recursive"></i>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <hr class="simple">
                </div>
            </div>

            <div class="row" ng-if="automap.show_label === false && automap.group_by_host === false">
                <!-- Only status color icons -->
                <span ng-repeat="host in hostAndServices">
                        <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                              title="{{host.Host.name}}/{{service.Service.name}}"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.Service.id)"
                            <?php endif; ?>
                              ng-repeat="service in host.Services">
                            <servicestatusicon-automap
                                    servicestatus="service.Servicestatus"></servicestatusicon-automap>
                        </span>
                </span>
            </div>

            <div class="row" ng-if="automap.show_label === false && automap.group_by_host === true">
                <!-- Status color icons with host headline -->
                <div class="row" ng-repeat="host in hostAndServices">
                    <div class="col-xs-12">
                        <h3 class="margin-bottom-5">
                            <i class="fa fa-desktop"></i>
                            <strong>{{host.Host.name}}</strong>
                        </h3>
                    </div>

                    <div class="col-xs-12">
                        <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                              title="{{service.Service.name}}"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.Service.id)"
                            <?php endif; ?>
                              ng-repeat="service in host.Services">
                            <servicestatusicon-automap
                                    servicestatus="service.Servicestatus"></servicestatusicon-automap>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row" ng-if="automap.show_label === true && automap.group_by_host === false">
                <!-- Status color icons + Hostname/Service description -->
                <span ng-repeat="host in hostAndServices">
                    <div class="col-xs-12 col-md-6 col-lg-3 ellipsis" ng-repeat="service in host.Services">
                        <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.Service.id)"
                            <?php endif; ?>
                              title="{{host.Host.name}}/{{service.Service.name}}">
                            <servicestatusicon-automap
                                    servicestatus="service.Servicestatus"></servicestatusicon-automap>
                            {{host.Host.name}}/{{service.Service.name}}
                        </span>
                    </div>
                </span>
            </div>

            <div ng-if="automap.show_label === true && automap.group_by_host === true">
                <!-- Status color icons with host and service name and host headline -->
                <div class="row" ng-repeat="host in hostAndServices">
                    <div class="col-xs-12">
                        <h3 class="margin-bottom-5">
                            <i class="fa fa-desktop"></i>
                            <strong>{{host.Host.name}}</strong>
                        </h3>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3 ellipsis" ng-repeat="service in host.Services">
                        <span style="cursor:pointer;font-size:{{automap.font_size_html}};"
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                ng-click="showServiceStatusDetails(service.Service.id)"
                            <?php endif; ?>
                              title="{{service.Service.name}}">
                            <servicestatusicon-automap
                                    servicestatus="service.Servicestatus"></servicestatusicon-automap>
                            {{service.Service.name}}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<service-status-details></service-status-details>