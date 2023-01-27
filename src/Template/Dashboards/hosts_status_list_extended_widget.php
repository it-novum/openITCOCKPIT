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
                        <th class="no-sort">
                            <?php echo __('Downtime comment'); ?>
                        </th>
                        <th class="no-sort">
                            <?php echo __('Acknowledgement comment'); ?>
                        </th>
                        <th class="no-sort" ng-click="orderBy('Hoststatus.output')">
                            <i class="fa" ng-class="getSortClass('Hoststatus.output')"></i>
                            <?php echo __('Host output'); ?>
                        </th>
                        <th class="no-sort text-center editItemWidth">
                            <i class="fas fa-plus-square"></i>
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
                                <a ui-sref="HostsBrowser({id: host.Host.id})">
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
                            {{host.Downtime.commentData}}
                        </td>
                        <td>
                            {{host.Acknowledgement.comment_data}}
                        </td>
                        <td>
                            <div class="word-break"
                                 ng-bind-html="host.Hoststatus.outputHtml | trustAsHtml"></div>
                        </td>
                        <td class="text-center">
                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                <button
                                    type="button"
                                    ng-click="loadHostBrowserDetails(host.Host.id)"
                                    class="btn btn-xs btn-info btn-lower-padding"
                                    title="<?= __('Show more information'); ?>">
                                    <i class="fas fa-plus"></i>
                                </button>
                            <?php endif; ?>
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
                                <div class="input-group-append">
                                    <span class="input-group-text pt-0 pb-0">
                                        <regex-helper-tooltip></regex-helper-tooltip>
                                    </span>
                                </div>
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
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-filter"></i></span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by tags'); ?>"
                                           type="text"
                                           id="HostTags"
                                           ng-model="filter.Host.keywords">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-prepend fa fa-filter"></i></span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by excluded tags'); ?>"
                                           type="text"
                                           id="HostExcludedTags"
                                           ng-model="filter.Host.not_keywords">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-lg-3">
                            <fieldset>
                                <h5><?php echo __('Host status'); ?></h5>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               id="up_{{widget.id}}"
                                               ng-model="filter.Hoststatus.current_state.up"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label custom-control-label-up"
                                               for="up_{{widget.id}}">
                                            <?php echo __('Up'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
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

                                    <div class="custom-control custom-checkbox">
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
                                               ng-model="filter.Hoststatus.acknowledged"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="isAck_{{widget.id}}">
                                            <?php echo __('Acknowledged'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="isNotAck_{{widget.id}}"
                                               ng-model="filter.Hoststatus.not_acknowledged"
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
                                               ng-model="filter.Hoststatus.in_downtime"
                                               ng-model-options="{debounce: 500}">
                                        <label class="custom-control-label" for="inDowntime_{{widget.id}}">
                                            <?php echo __('In Downtime'); ?>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="notInDowntime_{{widget.id}}"
                                               ng-model="filter.Hoststatus.not_in_downtime"
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

<!-- Modal -->
<div id="hostBrowserModal{{widget.id}}" class="modal z-index-2500" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span
                        class="badge border margin-right-10 border-{{hostBrowser.hoststatus.textClass}} {{hostBrowser.hoststatus.textClass}}"
                        ng-show="hostBrowser.hoststatus.isInMonitoring">
                        {{ hostBrowser.hoststatus.currentState | hostStatusName }}
                    </span>
                    {{ hostBrowser.mergedHost.name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row" ng-show="hostBrowser.hoststatus.scheduledDowntimeDepth > 0">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="fa fa-power-off"></i>
                                            <?php echo __('The host is currently in a planned maintenance period'); ?>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Downtime was set by'); ?>
                                        <b>{{hostBrowser.downtime.authorName}}</b>
                                        <?php echo __('with an duration of'); ?>
                                        <b>{{hostBrowser.downtime.durationHuman}}</b>.
                                    </div>
                                    <div class="padding-top-5">
                                        <small>
                                            <?php echo __('Start time:'); ?>
                                            {{ hostBrowser.downtime.scheduledStartTime }}
                                            <?php echo __('End time:'); ?>
                                            {{ hostBrowser.downtime.scheduledEndTime }}
                                        </small>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        {{hostBrowser.downtime.commentData}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="hostBrowser.hoststatus.problemHasBeenAcknowledged">
                    <div class="col-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="far fa-user"
                                               ng-show="!hostBrowser.acknowledgement.is_sticky"></i>
                                            <i class="fas fa-user"
                                               ng-show="hostBrowser.acknowledgement.is_sticky"></i>
                                            <?php echo __('State of host is acknowledged'); ?>
                                            <span ng-show="hostBrowser.acknowledgement.is_sticky">
                                                        (<?php echo __('Sticky'); ?>)
                                                    </span>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Acknowledgement was set by'); ?>
                                        <b>{{hostBrowser.acknowledgement.author_name}}</b>
                                        <?php echo __('at'); ?>
                                        {{hostBrowser.acknowledgement.entry_time}}
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        <div style="display:inline"
                                             ng-bind-html="hostBrowser.acknowledgement.commentDataHtml | trustAsHtml"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row" ng-show="hostBrowser.hoststatus.isFlapping">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">
                            <div>
                                <h4 class="no-padding txt-color-orangeDark">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <?php echo __('The state of this host is currently flapping!'); ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <?php echo __('State'); ?>
                    </div>
                    <div class="col-xs-12 col-md-10 {{hostBrowser.hoststatus.textClass}}">
                        {{ hostBrowser.hoststatus.currentState | hostStatusName }}
                    </div>
                </div>

                <div ng-show="hostBrowser.hoststatus.isInMonitoring">
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State since'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ hostBrowser.hoststatus.last_state_change }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Last check'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ hostBrowser.hoststatus.lastCheck }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __(' Next check'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ hostBrowser.hoststatus.nextCheck }}
                        </div>
                    </div>
                    <div class="row" ng-show="hostBrowser.hoststatus.isHardstate">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State type'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <?php echo __('Hard state'); ?>
                            ({{hostBrowser.hoststatus.current_check_attempt}}/{{hostBrowser.hoststatus.max_check_attempts}})
                        </div>
                    </div>
                    <div class="row" ng-show="!hostBrowser.hoststatus.isHardstate">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State type'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <?php echo __('Soft state'); ?>
                            ({{hostBrowser.hoststatus.current_check_attempt}}/{{hostBrowser.hoststatus.max_check_attempts}})
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Output'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div
                                ng-bind-html="hostBrowser.hoststatus.outputHtml | trustAsHtml"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Long output'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div
                                ng-bind-html="hostBrowser.hoststatus.longOutputHtml | trustAsHtml"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= __('Close'); ?>
                </button>
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a ui-sref="HostsBrowser({id:hostBrowser.mergedHost.id})" class="btn btn-primary"
                       data-dismiss="modal">
                        <?= __('Show details'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
