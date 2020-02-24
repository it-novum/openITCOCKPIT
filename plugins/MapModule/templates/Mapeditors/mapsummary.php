<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>
<div class="map-summary-state-popover col-xs-12 no-padding animated slideInRight"
     ng-if="summaryState"
     ng-click="hideTooltip($event)"
     ng-mouseover="stopInterval()"
     ng-mouseleave="startInterval()">
    <section>
        <div class="row">
            <article ng-if="iconType == 'host'">
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-desktop fa-lg txt-color-blueDark"></i>
                            <?php echo __('Host'); ?>
                            <span class="text-danger" ng-if="summaryState.Host.disabled">
                                <?php echo __(' (DISABLED)'); ?>
                            </span>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Hostname'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="HostsBrowser({id: summaryState.Host.id})">
                                    {{summaryState.Host.hostname}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Description'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Host.description}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('State'); ?>
                            </div>
                            <div ng-show="summaryState.Hoststatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white text-capitalize bg-{{(summaryState.Hoststatus.isHardstate)?summaryState.Hoststatus.humanState:summaryState.Hoststatus.humanState+'-soft'}}">
                                <span class="padding-5">{{summaryState.Hoststatus.humanState}}</span>
                                <i ng-show="summaryState.Hoststatus.problemHasBeenAcknowledged" class="fa fa-user"></i>
                                <i ng-show="summaryState.Hoststatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="summaryState.Hoststatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                        </div>
                        <div ng-show="summaryState.Hoststatus.isInMonitoring">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Output'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Hoststatus.output}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Perfdata'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Hoststatus.perfdata}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Current attempt'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Hoststatus.current_check_attempt}}/{{summaryState.Hoststatus.max_check_attempts}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Last check'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Hoststatus.lastCheck}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Next check'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Hoststatus.nextCheck}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Summary State'); ?>
                            </div>
                            <div ng-if="summaryState.Services.length > 0"
                                 class="col-md-8 text-center txt-color-white text-capitalize bg-{{summaryState.Services[0].Servicestatus.humanState}}">
                                {{summaryState.Services[0].Servicestatus.humanState}}

                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary Output'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <?php echo __('Services: '); ?> {{summaryState.ServiceSummary.total}}
                                <div class="btn-group btn-group-justified" role="group"
                                     ng-show="summaryState.ServiceSummary.total > 0">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[0]})">
                                        {{summaryState.ServiceSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-warning state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[1]})">
                                        {{summaryState.ServiceSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[2]})">
                                        {{summaryState.ServiceSummary.state[2]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[3]})">
                                        {{summaryState.ServiceSummary.state[3]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-20" ng-show="summaryState.Services.length > 0">
                            <div class="col-md-4">
                                <?php echo __('Service'); ?>
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('State'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo __('Output'); ?>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-5" ng-repeat="service in summaryState.Services">
                            <div class="col-md-4 cropText" title="{{service.Service.servicename}}">
                                <a ui-sref="ServicesBrowser({id: service.Service.id})">
                                    {{service.Service.servicename}}
                                </a>
                            </div>
                            <div ng-show="service.Servicestatus.isInMonitoring"
                                 class="col-md-4 text-center txt-color-white text-capitalize bg-{{(service.Servicestatus.isHardstate)?service.Servicestatus.humanState:service.Servicestatus.humanState+'-soft'}}">
                                {{service.Servicestatus.humanState}}
                                <i ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                   class="fa fa-user"></i>
                                <i ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="service.Servicestatus.isInMonitoring"
                                 class="col-md-4 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                            <div class="col-md-4 cropText">
                                {{service.Servicestatus.output}}
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <article ng-if="iconType == 'service'">
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-cog fa-lg txt-color-blueDark"></i>
                            <?php echo __('Service'); ?>
                            <span class="text-danger" ng-if="summaryState.Service.disabled">
                                <?php echo __(' (DISABLED)'); ?>
                            </span>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Hostname'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="HostsBrowser({id: summaryState.Host.id})">
                                    {{summaryState.Host.hostname}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Host state'); ?>
                            </div>
                            <div ng-show="summaryState.Hoststatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white text-capitalize bg-{{(summaryState.Hoststatus.isHardstate)?summaryState.Hoststatus.humanState:summaryState.Hoststatus.humanState+'-soft'}}">
                                <span class="padding-5">{{summaryState.Hoststatus.humanState}}</span>
                                <i ng-show="summaryState.Hoststatus.problemHasBeenAcknowledged" class="fa fa-user"></i>
                                <i ng-show="summaryState.Hoststatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="summaryState.Hoststatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Service'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="ServicesBrowser({id: summaryState.Service.id})">
                                    {{summaryState.Service.servicename}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Description'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Service.description}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('State'); ?>
                            </div>
                            <div ng-show="summaryState.Servicestatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white text-capitalize bg-{{(summaryState.Servicestatus.isHardstate)?summaryState.Servicestatus.humanState:summaryState.Servicestatus.humanState+'-soft'}}">
                                <span class="padding-5">{{summaryState.Servicestatus.humanState}}</span>
                                <i ng-show="summaryState.Servicestatus.problemHasBeenAcknowledged"
                                   class="fa fa-user"></i>
                                <i ng-show="summaryState.Servicestatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="summaryState.Servicestatus.isInMonitoring"
                                 class="col-md-8 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                        </div>
                        <div ng-show="summaryState.Servicestatus.isInMonitoring">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Output'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.output}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Perfdata'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.perfdata}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Current attempt'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.current_check_attempt}}/{{summaryState.Servicestatus.max_check_attempts}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Last check'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.lastCheck}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Next check'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.nextCheck}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <?php echo __('Last state change'); ?>
                                </div>
                                <div class="col-md-8 no-padding">
                                    {{summaryState.Servicestatus.last_state_change}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <article ng-if="iconType == 'hostgroup'">
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-sitemap fa-lg txt-color-blueDark"></i>
                            <?php echo __('Host group'); ?>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Host group name'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="HostgroupsExtended({id: summaryState.Hostgroup.id})">
                                    {{summaryState.Hostgroup.name}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Description'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Hostgroup.description}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Summary state'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div
                                    class="text-center txt-color-white text-capitalize bg-{{ summaryState.CumulatedHumanState}}">
                                    {{summaryState.CumulatedHumanState}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary output'); ?>
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('Hosts: '); ?>{{summaryState.Hostgroup.HostSummary.total}}
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('Services: '); ?>{{summaryState.Hostgroup.TotalServiceSummary.total}}
                            </div>
                        </div>

                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Hosts overview'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[0]})">
                                        {{summaryState.Hostgroup.HostSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[1]})">
                                        {{summaryState.Hostgroup.HostSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[2]})">
                                        {{summaryState.Hostgroup.HostSummary.state[2]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Services overview'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[0]})">
                                        {{summaryState.Hostgroup.TotalServiceSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-warning state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[1]})">
                                        {{summaryState.Hostgroup.TotalServiceSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[2]})">
                                        {{summaryState.Hostgroup.TotalServiceSummary.state[2]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[3]})">
                                        {{summaryState.Hostgroup.TotalServiceSummary.state[3]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10" ng-repeat="host in summaryState.Hosts">
                            <div class="col-md-4 cropText">
                                <a ui-sref="HostsBrowser({id: host.Host.id})">
                                    {{host.Host.hostname}}
                                </a>
                            </div>
                            <div ng-show="host.Hoststatus.isInMonitoring"
                                 class="col-md-4 text-center txt-color-white text-capitalize bg-{{(host.Hoststatus.isHardstate)?host.Hoststatus.humanState:host.Hoststatus.humanState+'-soft'}}">
                                {{host.Hoststatus.humanState}}
                                <i ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                                   class="fa fa-user"></i>
                                <i ng-show="host.Hoststatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="host.Hoststatus.isInMonitoring"
                                 class="col-md-4 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                            <div class="col-md-4 padding-right-0">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: host.ServiceIdsGroupByState[0]})">
                                        {{host.ServiceSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-warning state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: host.ServiceIdsGroupByState[1]})">
                                        {{host.ServiceSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: host.ServiceIdsGroupByState[2]})">
                                        {{host.ServiceSummary.state[2]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: host.ServiceIdsGroupByState[3]})">
                                        {{host.ServiceSummary.state[3]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <article ng-if="iconType == 'servicegroup'">
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-cogs fa-lg txt-color-blueDark"></i>
                            <?php echo __('Service group'); ?>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Service group name'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="ServicegroupsExtended({id: summaryState.Servicegroup.id})">
                                    {{summaryState.Servicegroup.name}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Description'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Servicegroup.description}}
                            </div>
                        </div>
                        <div class="col-md-12  padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary state'); ?>
                            </div>
                            <div class="col-md-8 no-padding" ng-show="summaryState.ServiceSummary.total > 0">
                                <div
                                    class="text-center txt-color-white text-capitalize bg-{{ summaryState.CumulatedHumanState}}">
                                    {{summaryState.CumulatedHumanState}}
                                </div>
                            </div>
                            <div class="col-md-8 no-padding" ng-show="summaryState.ServiceSummary.total == 0">
                                <div class="text-center txt-color-white text-capitalize bg-primary">
                                    {{summaryState.CumulatedHumanState}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary output'); ?>
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('Services: '); ?>{{summaryState.ServiceSummary.total}}
                            </div>
                        </div>

                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Services overview'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[0]})">
                                        {{summaryState.ServiceSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-warning state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[1]})">
                                        {{summaryState.ServiceSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[2]})">
                                        {{summaryState.ServiceSummary.state[2]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[3]})">
                                        {{summaryState.ServiceSummary.state[3]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-20" ng-show="summaryState.Services.length > 0">
                            <div class="col-md-6">
                                <?php echo __('Service'); ?>
                            </div>
                            <div class="col-md-2 no-padding">
                                <?php echo __('State'); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo __('Output'); ?>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10" ng-repeat="service in summaryState.Services">
                            <div class="col-md-6 cropText"
                                 title="{{service.Host.hostname}}/{{service.Service.servicename}}">

                                <a ui-sref="ServicesBrowser({id: service.Service.id})">
                                    {{service.Host.hostname}}/{{service.Service.servicename}}
                                </a>
                            </div>
                            <div ng-show="service.Servicestatus.isInMonitoring"
                                 class="col-md-2 text-center txt-color-white text-capitalize bg-{{(service.Servicestatus.isHardstate)?service.Servicestatus.humanState:service.Servicestatus.humanState+'-soft'}}">
                                <i ng-show="service.Servicestatus.problemHasBeenAcknowledged"
                                   class="fa fa-user"></i>
                                <i ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>&nbsp;
                            </div>
                            <div ng-hide="service.Servicestatus.isInMonitoring"
                                 class="col-md-2 text-center txt-color-white bg-primary">
                                <i class="fa fa-eye-slash"></i>
                            </div>
                            <div class="col-md-4 cropText">
                                {{service.Servicestatus.output}}
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <article ng-if="iconType == 'map'">
                <div class="jarviswidget bg-color-white">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-image fa-lg txt-color-blueDark"></i>
                            <?php echo __('Map'); ?>
                        </h2>
                        <div class="col-md-12 no-padding">
                            <div class="tooltipProgressBar" style="width: {{percentValue}}%;"></div>
                        </div>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Map name'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <a ui-sref="MapeditorsView({id: summaryState.Map.object_id})">
                                    {{summaryState.Map.name}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Map title'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Map.title}}
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary state'); ?>
                            </div>
                            <div class="col-md-8 no-padding"
                                 ng-show="summaryState.HostSummary.total > 0|| summaryState.ServiceSummary.total > 0">
                                <div
                                    class="text-center txt-color-white text-capitalize bg-{{ summaryState.CumulatedHumanState}}">
                                    {{summaryState.CumulatedHumanState}}
                                </div>
                            </div>
                            <div class="col-md-8 no-padding"
                                 ng-show="summaryState.HostSummary.total == 0 && summaryState.ServiceSummary.total == 0">
                                <div class="text-center txt-color-white text-capitalize bg-primary">
                                    {{summaryState.CumulatedHumanState}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Summary output'); ?>
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('Hosts: '); ?>{{summaryState.HostSummary.total}}
                            </div>
                            <div class="col-md-4 no-padding">
                                <?php echo __('Services: '); ?>{{summaryState.ServiceSummary.total}}
                            </div>
                        </div>

                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Hosts overview'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[0]})">
                                        {{summaryState.HostSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[1]})">
                                        {{summaryState.HostSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="HostsIndex({id: summaryState.HostIdsGroupByState[2]})">
                                        {{summaryState.HostSummary.state[2]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-4">
                                <?php echo __('Services overview'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                <div class="btn-group btn-group-justified" role="group">
                                    <a class="btn btn-success state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[0]})">
                                        {{summaryState.ServiceSummary.state[0]}}
                                    </a>
                                    <a class="btn btn-warning state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[2]})">
                                        {{summaryState.ServiceSummary.state[1]}}
                                    </a>
                                    <a class="btn btn-danger state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[2]})">
                                        {{summaryState.ServiceSummary.state[2]}}
                                    </a>
                                    <a class="btn btn-default state-button-small font-sm"
                                       ui-sref="ServicesIndex({id: summaryState.ServiceIdsGroupByState[3]})">
                                        {{summaryState.ServiceSummary.state[3]}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-10">
                            <div class="col-md-12">
                                <span class="bold">
                                    <?php echo __('Host and services are not in state UP/OK'); ?>
                                </span>
                                <span class="text-info">(
                                    <i class="fa fa-info-circle"></i>
                                    <?php echo __(' maximal 20'); ?>
                                </span>)
                            </div>
                        </div>
                        <div ng-if="summaryState.NotOkHosts.length > 0 || summaryState.NotOkServices.length">
                            <div class="col-md-12 padding-top-10" ng-show="summaryState.NotOkHosts.length > 0">
                                <div class="col-md-4">
                                    <?php echo __('Host'); ?>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <?php echo __('State'); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo __('Output'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 padding-top-5" ng-repeat="notOkHost in summaryState.NotOkHosts">
                                <div class="col-md-4 cropText" title="{{notOkHost.Host.hostname}}">
                                    <a ui-sref="HostsBrowser({id: notOkHost.Host.id})">
                                        {{notOkHost.Host.hostname}}
                                    </a>
                                </div>
                                <div ng-show="notOkHost.Hoststatus.isInMonitoring"
                                     class="col-md-4 text-center txt-color-white text-capitalize bg-{{(notOkHost.Hoststatus.isHardstate)?notOkHost.Hoststatus.humanState:notOkHost.Hoststatus.humanState+'-soft'}}">
                                    {{notOkHost.Hoststatus.humanState}}
                                    <i ng-show="notOkHost.Hoststatus.problemHasBeenAcknowledged"
                                       class="fa fa-user"></i>
                                    <i ng-show="notOkHost.Hoststatus.scheduledDowntimeDepth > 0"
                                       class="fa fa-power-off"></i>
                                </div>
                                <div ng-hide="notOkHost.Hoststatus.isInMonitoring"
                                     class="col-md-4 text-center txt-color-white bg-primary">
                                    <?php echo __('Not in monitoring'); ?>
                                    <i class="fa fa-eye-slash"></i>
                                </div>
                                <div class="col-md-4 cropText" title="notOkHost.Hoststatus.output">
                                    {{notOkHost.Hoststatus.output}}
                                </div>
                            </div>
                            <div class="col-md-12 padding-top-10" ng-show="summaryState.NotOkServices.length > 0">
                                <div class="col-md-4">
                                    <?php echo __('Service'); ?>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <?php echo __('State'); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo __('Output'); ?>
                                </div>
                            </div>
                            <div class="col-md-12 padding-top-5" ng-repeat="notOkService in summaryState.NotOkServices">
                                <div class="col-md-4 cropText"
                                     title="{{notOkService.Service.hostname}}/{{notOkService.Service.hostname}}">
                                    <a ui-sref="ServicesBrowser({id: notOkService.Service.id})">
                                        {{notOkService.Service.hostname}}/{{notOkService.Service.servicename}}
                                    </a>
                                </div>
                                <div ng-show="notOkService.Servicestatus.isInMonitoring"
                                     class="col-md-4 text-center txt-color-white text-capitalize bg-{{(notOkService.Servicestatus.isHardstate)?notOkService.Servicestatus.humanState:notOkService.Servicestatus.humanState+'-soft'}}">
                                    {{notOkService.Servicestatus.humanState}}
                                    <i ng-show="notOkService.Servicestatus.problemHasBeenAcknowledged"
                                       class="fa fa-user"></i>
                                    <i ng-show="notOkService.Servicestatus.scheduledDowntimeDepth > 0"
                                       class="fa fa-power-off"></i>
                                </div>
                                <div ng-hide="notOkService.Servicestatus.isInMonitoring"
                                     class="col-md-4 text-center txt-color-white bg-primary">
                                    <?php echo __('Not in monitoring'); ?>
                                    <i class="fa fa-eye-slash"></i>
                                </div>
                                <div class="col-md-4 cropText" title="notOkService.Servicestatus.output">
                                    {{notOkService.Servicestatus.output}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
</div>
