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
                                   ng-model="filter.Hoststatus.current_state.up"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-success"></i>
                            <?php echo __('Up'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Hoststatus.current_state.unreachable"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-default"></i>
                            <?php echo __('Unreachable'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Hoststatus.acknowledged"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Acknowledged'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Hoststatus.in_downtime"
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
                                   ng-model="filter.Hoststatus.current_state.down"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-danger"></i>
                            <?php echo __('Down'); ?>
                        </label>
                    </div>

                    <div class="hidden-xs hidden-md hidden-sm col-lg-3">
                        <!-- Spacer -->
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Hoststatus.not_acknowledged"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Not Acknowledged'); ?>
                        </label>
                    </div>


                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.Hoststatus.not_in_downtime"
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
                           placeholder="<?php echo __('Filter by host output'); ?>"
                           ng-model="filter.Hoststatus.output"
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
            <th class="no-sort" ng-click="orderBy('Hoststatus.current_state')">
                <i class="fa" ng-class="getSortClass('Hoststatus.current_state')"></i>
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
            <th class="no-sort" ng-click="orderBy('Hoststatus.last_state_change')">
                <i class="fa" ng-class="getSortClass('Hoststatus.last_state_change')"></i>
                <?php echo __('State since'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('Hoststatus.output')">
                <i class="fa" ng-class="getSortClass('Hoststatus.output')"></i>
                <?php echo __('Host output'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="host in hosts">
            <td class="text-center">
                <hoststatusicon host="host"></hoststatusicon>
            </td>

            <td class="text-center">
                <i class="fa fa-lg fa-user"
                   ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                   ng-if="host.Hoststatus.acknowledgement_type == 1"></i>

                <i class="fa fa-lg fa-user-o"
                   ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                   ng-if="host.Hoststatus.acknowledgement_type == 2"
                   title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
            </td>

            <td class="text-center">
                <i class="fa fa-lg fa-power-off"
                   ng-show="host.Hoststatus.scheduledDowntimeDepth > 0"></i>
            </td>
            <td>
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a href="/ng/#!/hosts/browser/{{ host.Host.id }}">
                        {{ host.Host.hostname }}
                    </a>
                <?php else: ?>
                    {{ host.Host.hostname }}
                <?php endif; ?>
            </td>
            <td>
                {{ host.Hoststatus.last_state_change }}
            </td>
            <td>
                {{ host.Hoststatus.output }}
            </td>
        </tr>
        </tbody>
    </table>

    <scroll scroll="scroll" click-action="changepage" only-buttons="true" ng-if="scroll"></scroll>

</div>

