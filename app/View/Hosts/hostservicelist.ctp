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

<table class="table table-striped table-hover table-bordered smart-form">
    <tr ng-if="services.length > 0">
        <td colspan="6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by service name'); ?>"
                           ng-model="filter.Service.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </td>
        <td colspan="6">
            <div ng-repeat="(servicestate, servicecount) in host.ServicestatusSummary.state track by $index"
                 class="col-md-3 bg-{{servicestate}}">
                <div class="padding-5 pull-right">
                    <label class="checkbox small-checkbox-label txt-color-white">
                        <input type="checkbox" name="checkbox" checked="checked"
                               ng-model-options="{debounce: 500}"
                               ng-model="servicesStateFilter[$index]"
                               ng-value="$index">
                        <i class="checkbox-{{servicestate}}"></i>
                        <strong>
                            {{servicecount}} {{servicestate}}
                        </strong>
                    </label>
                </div>
            </div>
        </td>
    </tr>
    <tr ng-if="services.length > 0">
        <th></th>
        <th>
            <?php echo __('Status'); ?>
        </th>
        <th class="no-sort text-center">
            <i class="fa fa fa-area-chart fa-lg" title="<?php echo __('Grapher'); ?>"></i>
        </th>
        <th class="no-sort text-center">
            <i class="fa fa-user fa-lg" title="<?php echo __('is acknowledged'); ?>"></i>
        </th>

        <th class="no-sort text-center">
            <i class="fa fa-power-off fa-lg"
               title="<?php echo __('is in downtime'); ?>"></i>
        </th>
        <th class="no-sort text-center">
            <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
        </th>

        <th class="no-sort">
            <?php echo __('Service name'); ?>
        </th>


        <th class="no-sort tableStatewidth">
            <?php echo __('State since'); ?>
        </th>

        <th class="no-sort tableStatewidth">
            <?php echo __('Last check'); ?>
        </th>

        <th class="no-sort tableStatewidth">
            <?php echo __('Next check'); ?>
        </th>

        <th class="no-sort">
            <?php echo __('Service output'); ?>
        </th>

        <th class="no-sort text-center editItemWidth">
            <i class="fa fa-gear fa-lg"></i>
        </th>
    </tr>
    <tr ng-repeat="service in services" ng-show="servicesStateFilter[service.Servicestatus.currentState]">
        <td></td>
        <td class="text-center">
            <servicestatusicon service="service"></servicestatusicon>
        </td>
        <td class="text-center">
            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                <a href="/services/grapherSwitch/{{ service.Service.id }}" class="txt-color-blueDark">
                    <i class="fa fa-lg fa-area-chart"
                       ng-mouseenter="mouseenter($event, service.Host, service)"
                       ng-mouseleave="mouseleave()"
                       ng-if="service.Service.has_graph">
                    </i>
                </a>
            <?php else: ?>
                <i class="fa fa-lg fa-area-chart"
                   ng-mouseenter="mouseenter($event, service.Host, service)"
                   ng-mouseleave="mouseleave()"
                   ng-if="service.Service.has_graph">
                </i>
            <?php endif; ?>
        </td>
        <td class="text-center">
            <i class="fa fa-lg fa-user"
               ng-show="service.Servicestatus.problemHasBeenAcknowledged"
               ng-if="service.Servicestatus.acknowledgement_type == 1"></i>

            <i class="fa fa-lg fa-user-o"
               ng-show="service.Servicestatus.problemHasBeenAcknowledged"
               ng-if="service.Servicestatus.acknowledgement_type == 2"
               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
        </td>

        <td class="text-center">
            <i class="fa fa-lg fa-power-off"
               ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"></i>
        </td>

        <td class="text-center">
            <strong title="<?php echo __('Passively transferred service'); ?>"
                    ng-show="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
                P
            </strong>
        </td>

        <td>
            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                <a href="/services/browser/{{ service.Service.id }}">
                    {{ service.Service.servicename }}
                </a>
            <?php else: ?>
                {{ service.Service.servicename }}
            <?php endif; ?>
        </td>

        <td>
            {{ service.Servicestatus.last_state_change }}
        </td>

        <td>
            <span ng-if="service.Service.active_checks_enabled && host.Host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
            <span ng-if="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
            <?php echo __('n/a'); ?>
        </span>
        </td>

        <td>
            <span ng-if="service.Service.active_checks_enabled && host.Host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
            <span ng-if="service.Service.active_checks_enabled === false || host.Host.is_satellite_host === true">
            <?php echo __('n/a'); ?>
        </span>
        </td>

        <td>
            {{ service.Servicestatus.output }}
        </td>

        <td class="width-50">
            <div class="btn-group">
                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                    <a href="/services/edit/{{service.Service.id}}"
                       ng-if="service.Service.allow_edit"
                       class="btn btn-default">
                        &nbsp;<i class="fa fa-cog"></i>&nbsp;
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn btn-default">
                        &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                <?php endif; ?>
                <a href="javascript:void(0);" data-toggle="dropdown"
                   class="btn btn-default dropdown-toggle"><span
                            class="caret"></span></a>
                <ul class="dropdown-menu pull-right" id="menuHack-{{service.Service.uuid}}">
                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                        <li ng-if="service.Service.allow_edit">
                            <a href="/services/edit/{{service.Service.id}}">
                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                        <li ng-if="service.Service.allow_edit">
                            <?php echo $this->AdditionalLinks->renderAsListItems(
                                $additionalLinksList,
                                '{{service.Service.id}}',
                                [],
                                true
                            ); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </td>
    </tr>
</table>
<div id="serviceGraphContainer" class="popup-graph-container">
    <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;" ng-show="isLoadingGraph">
        <i class="fa fa-refresh fa-4x fa-spin"></i>
    </div>
    <div id="serviceGraphFlot"></div>
</div>
