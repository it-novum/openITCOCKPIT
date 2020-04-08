<!-- This is used in hostgroups extended -->

<popover-graph-directive></popover-graph-directive>
<table class="table table-striped m-0 table-bordered table-hover table-sm">
    <thead>
    <tr>
        <td colspan="8" class="no-padding">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-cog"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm"
                           placeholder="<?php echo __('Filter by service name'); ?>"
                           ng-model="filter.servicename"
                           ng-model-options="{debounce: 500}">
                </div>
            </div>
        </td>
        <td colspan="6" class="no-padding">
            <div class="row no-margin" style="height:32px; ">
                <div class="col-lg-3 bg-{{state}}" style="padding-top: 7px;"
                     ng-repeat="(state, stateCount) in host.ServicestatusSummary.state track by $index">
                    <div class="custom-control custom-checkbox txt-color-white float-right">
                        <input type="checkbox"
                               id="statusFilter{{state}}"
                               class="custom-control-input"
                               name="checkbox"
                               checked="checked"
                               ng-model-options="{debounce: 500}"
                               ng-model="servicesStateFilter[$index]"
                               ng-value="$index">
                        <label
                            class="custom-control-label custom-control-label-{{state}} no-margin"
                            for="statusFilter{{state}}">{{stateCount}} {{state}}</label>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo __('Status'); ?>
        </th>
        <th class="width-20 text-center">
            <i class="fa fa-user" title="is acknowledged"></i>
        </th>
        <th class="width-20 text-center">
            <i class="fa fa-power-off" title="is in downtime"></i>
        </th>
        <th class="width-20 text-center">
            <i class="fa fa-lg fa-area-chart" title="Grapher"></i>
        </th>
        <th class="width-20 text-center">
            <strong title="<?php echo __('Passively transferred service'); ?>">
                <?php echo __('P'); ?>
            </strong>
        </th>
        <th>
            <?php echo __('Service name'); ?>
        </th>
        <th>
            <?php echo __('State since'); ?>
        </th>
        <th>
            <?php echo __('Last check'); ?>
        </th>
        <th>
            <?php echo __('Next check'); ?>
        </th>
        <th class="width-240">
            <?php echo __('Output'); ?>
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr ng-repeat="service in services" ng-show="servicesStateFilter[service.Servicestatus.currentState]">
        <td class="text-center">
            <servicestatusicon service="service"></servicestatusicon>
        </td>
        <td class="text-center">
            <i class="fa fa-user"
               ng-show="service.Servicestatus.problemHasBeenAcknowledged"
               ng-if="service.Servicestatus.acknowledgement_type == 1"></i>

            <i class="fa fa-user"
               ng-show="service.Servicestatus.problemHasBeenAcknowledged"
               ng-if="service.Servicestatus.acknowledgement_type == 2"
               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
        </td>
        <td class="text-center">
            <i class="fa fa-power-off"
               ng-show="service.Servicestatus.scheduledDowntimeDepth > 0"></i>
        </td>
        <td class="text-center">
            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                <a ui-sref="ServicesBrowser({id: service.Service.id})"
                   class="txt-color-blueDark"
                   ng-mouseenter="mouseenter($event, service.Host.uuid, service.Service.uuid)"
                   ng-mouseleave="mouseleave()"
                   ng-if="service.Service.has_graph">
                    <i class="fa fa-lg fa-area-chart">
                    </i>
                </a>
            <?php else: ?>
                <div ng-mouseenter="mouseenter($event, service.Host.uuid, service.Service.uuid)"
                     ng-mouseleave="mouseleave()"
                     ng-if="service.Service.has_graph">
                    <i class="fa fa-lg fa-area-chart">
                    </i>
                </div>
            <?php endif; ?>
        </td>
        <td class="text-center">
            <strong title="<?php echo __('Passively transferred service'); ?>"
                    ng-show="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                P
            </strong>
        </td>
        <td>
            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                <a ui-sref="ServicesBrowser({id: service.Service.id})">
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
            <span
                ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
            <span
                ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                <?php echo __('n/a'); ?>
            </span>
        </td>
        <td>
            <span
                ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
            <span
                ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                <?php echo __('n/a'); ?>
            </span>
        </td>
        <td>
            {{ service.Servicestatus.output }}
        </td>
        <td class="width-50">
            <div class="btn-group btn-group-xs" role="group">
                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                    <a ui-sref="ServicesEdit({id: service.Service.id})"
                       ng-if="service.Service.allow_edit"
                       class="btn btn-default btn-lower-padding">
                        <i class="fa fa-cog"></i>
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0);"
                       class="btn btn-default btn-lower-padding">
                        <i class="fa fa-cog"></i></a>
                <?php endif; ?>
                <button type="button"
                        class="btn btn-default dropdown-toggle btn-lower-padding"
                        data-toggle="dropdown">
                    <i class="caret"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                        <a ui-sref="ServicesEdit({id: service.Service.id})"
                           ng-if="service.Service.allow_edit"
                           class="dropdown-item">
                            <i class="fa fa-cog"></i>
                            <?php echo __('Edit'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>

