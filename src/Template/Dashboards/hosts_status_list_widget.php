<div>
    <flippy vertical
            class="col-lg-12"
            flip="['custom:FLIP_EVENT_OUT']"
            flip-back="['custom:FLIP_EVENT_IN']"
            duration="800"
            timing-function="ease-in-out">

        <flippy-front class="fixFlippy">
            <a href="javascript:void(0);" class="btn btn-default btn-xs txt-color-blueDark" ng-click="showConfig()">
                <i class="fa fa-cog fa-sm"></i>
            </a>
            <div class="margin-top-10">
                <table class="table table-striped m-0 table-bordered table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="no-sort" ng-click="orderBy('Hoststatus.current_state')">
                            <i class="fa" ng-class="getSortClass('Hoststatus.current_state')"></i>
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
                            <i class="far fa-user"
                               ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                               ng-if="host.Hoststatus.acknowledgement_type == 1"></i>

                            <i class="fa fa-user"
                               ng-show="host.Hoststatus.problemHasBeenAcknowledged"
                               ng-if="host.Hoststatus.acknowledgement_type == 2"
                               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                        </td>

                        <td class="text-center">
                            <i class="fa fa-power-off"
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
                            <div class="word-break"
                                 ng-bind-html="host.Hoststatus.outputHtml | trustAsHtml"></div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="margin-top-10" ng-show="hosts.length == 0">
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
            <div class="padding-10" style="border: 1px solid #c3c3c3;">
                <div class="row">
                    <div class="col-lg-1">
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

                    <div class="col-lg-8">
                        <div class="row">
                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="up_{{widget.id}}"
                                       ng-model="filter.Hoststatus.current_state.up"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label custom-control-label-up" for="up_{{widget.id}}">
                                    <?php echo __('Up'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="isDown_{{widget.id}}"
                                       ng-model="filter.Hoststatus.current_state.down"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label custom-control-label-down"
                                       for="isDown_{{widget.id}}">
                                    <?php echo __('Down'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="unreachable_{{widget.id}}"
                                       ng-model="filter.Hoststatus.current_state.unreachable"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label custom-control-label-unreachable"
                                       for="unreachable_{{widget.id}}">
                                    <?php echo __('Unreachable'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="isAck_{{widget.id}}"
                                       ng-model="filter.Hoststatus.acknowledged"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label" for="isAck_{{widget.id}}">
                                    <?php echo __('Acknowledged'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="isNotAck_{{widget.id}}"
                                       ng-model="filter.Hoststatus.not_acknowledged"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label" for="isNotAck_{{widget.id}}">
                                    <?php echo __('Not Acknowledged'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="inDowntime_{{widget.id}}"
                                       ng-model="filter.Hoststatus.in_downtime"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label" for="inDowntime_{{widget.id}}">
                                    <?php echo __('In Downtime'); ?>
                                </label>
                            </div>

                            <div class="custom-control custom-checkbox custom-control-left margin-right-10">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="notInDowntime_{{widget.id}}"
                                       ng-model="filter.Hoststatus.not_in_downtime"
                                       ng-model-options="{debounce: 500}">
                                <label class="custom-control-label" for="notInDowntime_{{widget.id}}">
                                    <?php echo __('Not in Downtime'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <span class="input-group-text"><i class="icon-prepend fa fa-filter"></i></span>
                            </div>
                            <input type="text" class="form-control"
                                   placeholder="<?php echo __('Filter by host output'); ?>"
                                   ng-model="filter.Hoststatus.output"
                                   ng-model-options="{debounce: 500}">
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
