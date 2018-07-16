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
<div class="map-summary-state-popover col-xs-12">
    <section>
        <div class="row">
            <article>
                <div class="jarviswidget">
                    <header>
                        <h2 class="bold txt-color-blueDark">
                            <i class="fa fa-desktop fa-lg txt-color-blueDark"></i>
                            <?php echo __('Host'); ?>
                        </h2>
                    </header>
                    <div class="txt-color-blueDark font-xs padding-top-10 padding-bottom-10">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Hostname'); ?>
                            </div>
                            <div class="col-md-8 no-padding">
                                {{summaryState.Host.hostname}}
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
                                 class="col-md-4 text-center txt-color-white text-capitalize bg-{{(summaryState.Hoststatus.isHardstate)?summaryState.Hoststatus.humanState:summaryState.Hoststatus.humanState+'-soft'}}">
                                <span class="padding-5">{{summaryState.Hoststatus.humanState}}</span>
                                <i ng-show="summaryState.Hoststatus.problemHasBeenAcknowledged" class="fa fa-user"></i>
                                <i ng-show="summaryState.Hoststatus.scheduledDowntimeDepth > 0"
                                   class="fa fa-power-off"></i>
                            </div>
                            <div ng-hide="summaryState.Hoststatus.isInMonitoring"
                                 class="col-md-4 text-center txt-color-white bg-primary">
                                <?php echo __('Not in monitoring'); ?>
                                <i class="fa fa-eye-slash"></i>
                            </div>
                        </div>
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
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Summary State'); ?>
                            </div>
                            <div ng-if="summaryState.Services.length > 0"
                                 class="col-md-4 text-center txt-color-white text-capitalize bg-{{summaryState.Services[0].Servicestatus.humanState}}">
                                {{summaryState.Services[0].Servicestatus.humanState}}

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <?php echo __('Summary Output'); ?>
                            </div>
                            <div class="col-md-8 no-padding" ng-show="summaryState.Services.length > 0">
                                 <span ng-if="summaryState.Services.length > 0" class="text-capitalize">
                                    {{summaryState.Services[0].Servicestatus.humanState}}.
                                </span>
                                <?php echo __('There are '); ?> {{ summaryState.Services.length }}
                                <?php echo __(' services'); ?>
                            </div>
                            <div class="col-md-8 no-padding" ng-show="summaryState.Services.length == 0">
                                <?php echo __('No services found'); ?>
                            </div>
                        </div>
                        <div class="col-md-12 padding-top-20">
                            <div class="col-md-4">
                                <?php echo __('Service name'); ?>
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
                                {{service.Service.servicename}}
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
                            <div class="col-md-4 cropText" title="{{service.Servicestatus.output}}">
                                {{service.Servicestatus.output}}
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
</div>
