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
                        <th class="no-sort">
                            <?php echo __('Downtime comment'); ?>
                        </th>
                        <th class="no-sort">
                            <?php echo __('Acknowledgement comment'); ?>
                        </th>
                        <th class="no-sort" ng-click="orderBy('Servicestatus.output')">
                            <i class="fa" ng-class="getSortClass('Servicestatus.output')"></i>
                            <?php echo __('Service output'); ?>
                        </th>
                        <th class="no-sort text-center editItemWidth">
                            <i class="fas fa-plus-square"></i>
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
                                <a ui-sref="HostsBrowser({id: service.Host.id})" class="a-clean">
                                    {{ service.Host.hostname }}
                                </a>
                            <?php else: ?>
                                {{ service.Host.hostname }}
                            <?php endif; ?>
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
                            {{service.Downtime.commentData}}
                        </td>
                        <td>
                            {{service.Acknowledgement.comment_data}}
                        </td>
                        <td>
                            <div
                                ng-bind-html="service.Servicestatus.outputHtml | trustAsHtml"></div>
                        </td>
                        <td class="text-center">
                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                <button
                                    type="button"
                                    ng-click="loadServiceBrowserDetails(service.Service.id)"
                                    class="btn btn-xs btn-info btn-lower-padding"
                                    title="<?= __('Show more information'); ?>">
                                    <i class="fas fa-plus"></i>
                                </button>
                            <?php endif; ?>
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
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="icon-stack">
                                            <i class="icon-prepend fa fa-filter"></i>
                                            <i class="fas fa-desktop opacity-100 fa-xs text-primary cornered cornered-lr"></i>
                                        </div>
                                    </span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by host tags'); ?>"
                                           type="text"
                                           id="HostTags"
                                           ng-model="filter.Host.keywords">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="icon-stack">
                                            <i class="icon-prepend fa fa-filter"></i>
                                            <i class="fas fa-desktop opacity-100 fa-xs text-danger cornered cornered-lr"></i>
                                        </div>
                                    </span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by excluded host tags'); ?>"
                                           type="text"
                                           id="HostExcludedTags"
                                           ng-model="filter.Host.not_keywords">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="icon-stack">
                                            <i class="icon-prepend fa fa-filter"></i>
                                            <i class="fas fa-cog opacity-100 fa-xs text-primary cornered cornered-lr"></i>
                                        </div>
                                    </span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by service tags'); ?>"
                                           type="text"
                                           id="ServiceTags"
                                           ng-model="filter.Service.keywords">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6 margin-bottom-5">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="icon-stack">
                                            <i class="icon-prepend fa fa-filter"></i>
                                            <i class="fas fa-cog opacity-100 fa-xs text-danger cornered cornered-lr"></i>
                                        </div>
                                    </span>
                                </div>
                                <div class="col tagsinputFilter">
                                    <input class="form-control form-control-sm tagsinput"
                                           data-role="tagsinput"
                                           placeholder="<?php echo __('Filter by excluded service tags'); ?>"
                                           type="text"
                                           id="ServiceExcludedTags"
                                           ng-model="filter.Service.not_keywords">
                                </div>
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

<!-- Modal -->
<div id="serviceBrowserModal{{widget.id}}" class="modal z-index-2500" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span
                        class="badge border margin-right-10 border-{{serviceBrowser.servicestatus.textClass}} {{serviceBrowser.servicestatus.textClass}}"
                        ng-show="serviceBrowser.servicestatus.isInMonitoring">
                        {{ serviceBrowser.servicestatus.currentState | serviceStatusName }}
                    </span>
                    {{ serviceBrowser.host.Host.hostname }} / {{ serviceBrowser.mergedService.name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row" ng-show="serviceBrowser.servicestatus.scheduledDowntimeDepth > 0">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="fa fa-power-off"></i>
                                            <?php echo __('The service is currently in a planned maintenance period'); ?>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Downtime was set by'); ?>
                                        <b>{{serviceBrowser.downtime.authorName}}</b>
                                        <?php echo __('with an duration of'); ?>
                                        <b>{{serviceBrowser.downtime.durationHuman}}</b>.
                                    </div>
                                    <div class="padding-top-5">
                                        <small>
                                            <?php echo __('Start time:'); ?>
                                            {{ serviceBrowser.downtime.scheduledStartTime }}
                                            <?php echo __('End time:'); ?>
                                            {{ serviceBrowser.downtime.scheduledEndTime }}
                                        </small>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        {{serviceBrowser.downtime.commentData}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="serviceBrowser.servicestatus.problemHasBeenAcknowledged">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="far fa-user"
                                               ng-show="!serviceBrowser.acknowledgement.is_sticky"></i>
                                            <i class="fas fa-user"
                                               ng-show="serviceBrowser.acknowledgement.is_sticky"></i>
                                            <?php echo __('State of service is acknowledged'); ?>
                                            <span ng-show="serviceBrowser.acknowledgement.is_sticky">
                                                (<?php echo __('Sticky'); ?>)
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Acknowledgement was set by'); ?>
                                        <b>{{serviceBrowser.acknowledgement.author_name}}</b>
                                        <?php echo __('at'); ?>
                                        {{serviceBrowser.acknowledgement.entry_time}}
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        <div style="display:inline"
                                             ng-bind-html="serviceBrowser.acknowledgement.commentDataHtml | trustAsHtml"></div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="row" ng-show="serviceBrowser.servicestatus.isFlapping">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">
                            <div>
                                <h4 class="no-padding txt-color-orangeDark">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <?php echo __('The state of this service is currently flapping!'); ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="serviceBrowser.hoststatus.currentState > 0">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="alert alert-block alert-info">
                            <h4 class="alert-heading">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?php echo __('Problem with host detected!'); ?>
                            </h4>

                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                <a ui-sref="HostsBrowser({id:serviceBrowser.host.Host.id})" data-dismiss="modal">
                                    {{serviceBrowser.host.Host.hostname}}
                                </a>
                            <?php else: ?>
                                {{serviceBrowser.host.Host.hostname}}
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="serviceBrowser.hoststatus.scheduledDowntimeDepth > 0">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="fa fa-power-off"></i>
                                            <?php echo __('The host of this service is currently in a planned maintenance period'); ?>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Downtime was set by'); ?>
                                        <b>{{serviceBrowser.hostDowntime.authorName}}</b>
                                        <?php echo __('with an duration of'); ?>
                                        <b>{{serviceBrowser.hostDowntime.durationHuman}}</b>.
                                    </div>
                                    <div class="padding-top-5">
                                        <small>
                                            <?php echo __('Start time:'); ?>
                                            {{ serviceBrowser.hostDowntime.scheduledStartTime }}
                                            <?php echo __('End time:'); ?>
                                            {{ serviceBrowser.hostDowntime.scheduledEndTime }}
                                        </small>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        {{serviceBrowser.hostDowntime.commentData}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="serviceBrowser.hoststatus.problemHasBeenAcknowledged">
                    <div class="col-lg-12 margin-bottom-10">
                        <div class="browser-border padding-10" style="width: 100%;">

                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <h4 class="no-padding">
                                            <i class="far fa-user"
                                               ng-show="!serviceBrowser.hostAcknowledgement.is_sticky"></i>
                                            <i class="fas fa-user"
                                               ng-show="serviceBrowser.hostAcknowledgement.is_sticky"></i>
                                            <?php echo __('State of host is acknowledged'); ?>
                                            <span ng-show="serviceBrowser.hostAcknowledgement.is_sticky">
                                                (<?php echo __('Sticky'); ?>)
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Acknowledgement was set by'); ?>
                                        <b>{{serviceBrowser.hostAcknowledgement.author_name}}</b>
                                        <?php echo __('at'); ?>
                                        {{serviceBrowser.hostAcknowledgement.entry_time}}
                                    </div>
                                    <div class="padding-top-5">
                                        <?php echo __('Comment: '); ?>
                                        <div style="display:inline"
                                             ng-bind-html="serviceBrowser.hostAcknowledgement.commentDataHtml | trustAsHtml"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12 col-md-2">
                        <?php echo __('State'); ?>
                    </div>
                    <div class="col-xs-12 col-md-10 {{serviceBrowser.servicestatus.textClass}}">
                        {{ serviceBrowser.servicestatus.currentState | serviceStatusName }}
                    </div>
                </div>

                <div ng-show="serviceBrowser.servicestatus.isInMonitoring">
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State since'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ serviceBrowser.servicestatus.last_state_change }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Last check'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ serviceBrowser.servicestatus.lastCheck }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __(' Next check'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {{ serviceBrowser.servicestatus.nextCheck }}
                        </div>
                    </div>
                    <div class="row" ng-show="serviceBrowser.servicestatus.isHardstate">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State type'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <?php echo __('Hard state'); ?>
                            ({{serviceBrowser.servicestatus.current_check_attempt}}/{{serviceBrowser.servicestatus.max_check_attempts}})
                        </div>
                    </div>
                    <div class="row" ng-show="!serviceBrowser.servicestatus.isHardstate">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('State type'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <?php echo __('Soft state'); ?>
                            ({{serviceBrowser.servicestatus.current_check_attempt}}/{{serviceBrowser.servicestatus.max_check_attempts}})
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Output'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div
                                ng-bind-html="serviceBrowser.servicestatus.outputHtml | trustAsHtml"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-2">
                            <?php echo __('Long output'); ?>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            <div
                                ng-bind-html="serviceBrowser.servicestatus.longOutputHtml | trustAsHtml"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= __('Close'); ?>
                </button>
                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                    <a ui-sref="ServicesBrowser({id:serviceBrowser.mergedService.id})" class="btn btn-primary"
                       data-dismiss="modal">
                        <?= __('Show details'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
