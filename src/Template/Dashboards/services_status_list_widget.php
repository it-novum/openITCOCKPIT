<div>
    <flippy vertical
            class="col-lg-12"
            flip="['custom:FLIP_EVENT_OUT']"
            flip-back="['custom:FLIP_EVENT_IN']"
            duration="800"
            timing-function="ease-in-out">

        <flippy-front class="fixFlippy">
            <div class="row">
                <div class="col-lg-1">
                    <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark"
                       ng-click="showConfig()">
                        <i class="fa fa-cog fa-sm"></i>
                    </a>
                </div>
                <div class="col-lg-11">
                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-1 offset-lg-8 text-right">
                            <a href="javascript:void(0);" ng-show="useScroll" ng-click="pauseScroll()"
                               title="<?php echo __('Pause scrolling'); ?>"
                               class="btn btn-xs btn-primary">
                                <i class="fa fa-pause"></i>
                            </a>
                            <a href="javascript:void(0);" ng-show="!useScroll"
                               ng-click="startScroll()" title="<?php echo __('Start scrolling'); ?>"
                               class="btn btn-xs btn-primary">
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group form-group-slider">
                                <label class="display-inline">
                                    <?php echo __('Scroll interval:'); ?>
                                    <span class="note" id="PagingInterval_human">
                                        {{pagingTimeString}}
                                    </span>
                                </label>

                                <div class="slidecontainer">
                                    <input type="range" step="5000" min="5000" max="300000" class="slider"
                                           style="width: 100%"
                                           ng-model="scroll_interval" ng-model-options="{debounce: 500}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="margin-top-10">
                <table class="table table-striped m-0 table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="no-sort" ng-click="orderBy('Servicestatus.current_state')">
                            <i class="fa" ng-class="getSortClass('Servicestatus.current_state')"></i>
                            <?php echo __('State'); ?>
                        </th>
                        <th class="no-sort text-center">
                            <i class="fa fa-user" title="<?php echo __('is acknowledged'); ?>"></i>
                        </th>

                        <th class="no-sort text-center">
                            <i class="fa fa-power-off"
                               title="<?php echo __('is in downtime'); ?>"></i>
                        </th>
                        <th class="no-sort" ng-click="orderBy('Hosts.name')">
                            <i class="fa" ng-class="getSortClass('Hosts.name')"></i>
                            <?php echo __('Host name'); ?>
                        </th>
                        <th class="no-sort" ng-click="orderBy('servicename')">
                            <i class="fa" ng-class="getSortClass('servicename')"></i>
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
                            <i class="far fa-user"
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
                            <div
                                ng-bind-html="service.Servicestatus.outputHtml | trustAsHtml"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="margin-top-10" ng-show="services.length == 0">
                    <div class="text-center text-danger italic">
                        <?php echo __('No entries match the selection'); ?>
                    </div>
                </div>

                <scroll scroll="scroll" click-action="changepage" only-buttons="true" ng-if="scroll"></scroll>
            </div>
        </flippy-front>
        <flippy-back class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark margin-bottom-10"
               ng-click="hideConfig()">
                <i class="fa fa-eye fa-sm"></i>
            </a>
            <div class="padding-10">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-desktop"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="<?php echo __('Filter by host name'); ?>"
                                       ng-model="filter.Host.name"
                                       ng-model-options="{debounce: 500}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-cog"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="<?php echo __('Filter by service name'); ?>"
                                       ng-model="filter.Service.name"
                                       ng-model-options="{debounce: 500}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-filter"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="<?php echo __('Filter by service output'); ?>"
                                       ng-model="filter.Servicestatus.output"
                                       ng-model-options="{debounce: 500}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-lg-3">
                            <fieldset>
                                <h5><?php echo __('Service status'); ?></h5>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               id="isOk_{{widget.id}}"
                                               ng-model="filter.Servicestatus.current_state.ok"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label custom-control-label-ok"
                                               for="isOk_{{widget.id}}">
                                            <?php echo __('Ok'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               id="isWarning_{{widget.id}}"
                                               ng-model="filter.Servicestatus.current_state.warning"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label custom-control-label-warning"
                                               for="isWarning_{{widget.id}}">
                                            <?php echo __('Warning'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               id="isCritical_{{widget.id}}"
                                               ng-model="filter.Servicestatus.current_state.critical"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label custom-control-label-critical"
                                               for="isCritical_{{widget.id}}">
                                            <?php echo __('Critical'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               id="isUnknown_{{widget.id}}"
                                               ng-model="filter.Servicestatus.current_state.unknown"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label custom-control-label-unknown"
                                               for="isUnknown_{{widget.id}}">
                                            <?php echo __('Unknown'); ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-xs-12 col-lg-3">
                            <fieldset>
                                <h5><?php echo __('Acknowledgements'); ?></h5>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="isAck_{{widget.id}}"
                                               ng-model="filter.Servicestatus.acknowledged"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="isAck_{{widget.id}}">
                                            <?php echo __('Acknowledged'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="isNotAck_{{widget.id}}"
                                               ng-model="filter.Servicestatus.not_acknowledged"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="isNotAck_{{widget.id}}">
                                            <?php echo __('Not Acknowledged'); ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-xs-12 col-lg-3">
                            <fieldset>
                                <h5><?php echo __('Downtimes'); ?></h5>
                                <div class="form-group smart-form">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="inDowntime_{{widget.id}}"
                                               ng-model="filter.Servicestatus.in_downtime"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="inDowntime_{{widget.id}}">
                                            <?php echo __('In Downtime'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="notInDowntime_{{widget.id}}"
                                               ng-model="filter.Servicestatus.not_in_downtime"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="notInDowntime_{{widget.id}}">
                                            <?php echo __('Not in Downtime'); ?>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-primary float-right"
                                ng-click="saveSettings()">
                            <?php echo __('Save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </flippy-back>
    </flippy>
</div>
