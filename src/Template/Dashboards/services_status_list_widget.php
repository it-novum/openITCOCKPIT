<div class="padding-10" style="border: 1px solid #c3c3c3;">

    <div class="row">
        <div class="col-xs-1">
            <a href="javascript:void(0);" ng-show="useScroll" ng-click="pauseScroll()"
               title="<?php echo __('Pause scrolling'); ?>"
               class="btn btn-default btn-xs btn-primary">
                <i class="fa fa-pause"></i>
            </a>
            <a href="javascript:void(0);" ng-show="!useScroll"
               ng-click="startScroll()" title="<?php echo __('Start scrolling'); ?>"
               class="btn btn-default btn-xs btn-primary">
                <i class="fa fa-play"></i>
            </a>
        </div>

        <div class="col-xs-3 height-45px">
            <div class="form-group form-group-slider">
                <label class="display-inline">
                    <?php echo __('Scroll interval:'); ?>
                    <span class="note" id="PagingInterval_human">
                        {{pagingTimeString}}
                    </span>
                </label>

                <div class="slidecontainer">
                    <input type="range" step="5000" min="5000" max="300000" class="slider"
                           ng-model="scroll_interval" ng-model-options="{debounce: 500}">
                </div>
            </div>
        </div>

        <div class="col-xs-8">
            <div class="row">
                <div class="form-group smart-form">
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.current_state.ok"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-success"></i>
                            <?php echo __('Ok'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.current_state.warning"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-warning"></i>
                            <?php echo __('Warning'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.acknowledged"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Acknowledged'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.in_downtime"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('In Downtime'); ?>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xs-8">
            <div class="row">
                <div class="form-group smart-form">
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.current_state.critical"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-critical"></i>
                            <?php echo __('Critical'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.current_state.unknown"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-default"></i>
                            <?php echo __('Unknown'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.not_acknowledged"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Not Acknowledged'); ?>
                        </label>
                    </div>


                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Servicestatus.not_in_downtime"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Not in Downtime'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by host name'); ?>"
                           ng-model="filter.Host.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by service name'); ?>"
                           ng-model="filter.Service.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by service output'); ?>"
                           ng-model="filter.Servicestatus.output"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
    </div>
</div>

<div class="mobile_table margin-top-10">
    <table class="table table-striped table-hover table-bordered smart-form">
        <thead>
        <tr>
            <th class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                <i class="fa" ng-class="getSortClass('Servicestatus.current_state')"></i>
                <?php echo __('State'); ?>
            </th>
            <th class="no-sort text-center">
                <i class="fa fa-user fa-lg" title="<?php echo __('is acknowledged'); ?>"></i>
            </th>

            <th class="no-sort text-center">
                <i class="fa fa-power-off fa-lg"
                   title="<?php echo __('is in downtime'); ?>"></i>
            </th>
            <th class="no-sort" ng-click="orderBy('Host.name')">
                <i class="fa" ng-class="getSortClass('Host.name')"></i>
                <?php echo __('Host name'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('Service.name')">
                <i class="fa" ng-class="getSortClass('Service.name')"></i>
                <?php echo __('Service name'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('Servicestatus.last_state_change')">
                <i class="fa" ng-class="getSortClass('Servicestatus.last_state_change')"></i>
                <?php echo __('State since'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('Servicestatus.output')">
                <i class="fa" ng-class="getSortClass('Servicestatus.output')"></i>
                <?php echo __('Service output'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="service in services">
            <td class="text-center">
                <servicestatusicon service="service"></servicestatusicon>
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
            <td class="table-color-{{(service.Hoststatus.currentState !== null)?service.Hoststatus.currentState:'disabled'}}">
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a href="/ng/#!/hosts/browser/{{ service.Host.id }}">
                        {{ service.Host.hostname }}
                    </a>
                <?php else: ?>
                    {{ service.Host.hostname }}
                <?php endif; ?>
            </td>
            <td>
                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                    <a href="/ng/#!/services/browser/{{ service.Service.id }}">
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
                {{ service.Servicestatus.output }}
            </td>
        </tr>
        </tbody>
    </table>

    <scroll scroll="scroll" click-action="changepage" only-buttons="true" ng-if="scroll"></scroll>

</div>

