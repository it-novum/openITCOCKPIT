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
                       ng-click="showConfig()" ng-hide="readOnly">
                        <i class="fa fa-cog fa-sm"></i>
                    </a>
                </div>
                <div class="col-lg-11">
                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-1 offset-lg-6 text-right">
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
                                    <input type="range" step="5000" min="0" max="300000" class="slider"
                                           ng-disabled="readOnly"
                                           style="width: 100%"
                                           ng-model="scroll_interval" ng-model-options="{debounce: 500}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6"> <?php echo __('Top Alerts for state:'); ?>
                    <badge class="text-white pl-2 pr-2 bold rounded" ng-class="bgClass"> {{filter.state}}</badge>
                </div>
                <div class="col-lg-6">
                    <span class="pull-right" ng-show="filter.not_older_than"
                          ng-switch="filter.not_older_than_unit">
                        <?php echo __('Period: Last'); ?> {{filter.not_older_than}}
                        <span ng-switch-when="MINUTE">
                            <?= __('minute(s)'); ?>
                        </span>
                        <span ng-switch-when="HOUR">
                            <?= __('hour(s)'); ?>
                        </span>
                        <span ng-switch-when="DAY">
                            <?= __('day(s)'); ?>
                        </span>
                    </span>

                </div>

            </div>
            <div class="margin-top-10">
                <table class="table table-striped m-0 table-bordered table-hover table-sm">
                    <thead>
                    <tr class=" text-white" ng-class="bgClass">
                        <th class="text-center">
                            <?php echo __('State'); ?>
                        </th>
                        <th><?php echo __('Host'); ?></th>
                        <th><?php echo __('Last date'); ?></th>
                        <th><?php echo __('Count'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="notification in all_notifications">
                        <td class="text-center">
                            <hoststatusicon state="notification.NotificationHost.state"></hoststatusicon>
                        </td>

                        <td>
                            <?php if ($this->Acl->hasPermission('index', 'notifications')): ?>
                                <!--<a ui-sref="NotificationsHostNotification({id: notification.Host.id})">-->
                                <a class="text-primary pointer"
                                   ng-click="loadHostNotificationDetails(notification.Host.id)">
                                    {{notification.Host.name}}
                                </a>
                            <?php else: ?>
                                {{notification.Host.name}}
                            <?php endif; ?>
                        </td>
                        <td>
                            {{notification.NotificationHost.start_time}}
                        </td>
                        <td>
                            {{notification.count}}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="margin-top-10" ng-show="all_notifications.length == 0">
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
            <div class="padding-top-10">
                <div class="row">
                    <div class="col-xs-12 col-lg-6">
                        <h4><?php echo __('Host status'); ?></h4>
                        <div class="custom-control custom-radio custom-control-left margin-right-10">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="widget-radio0-{{widget.id}}"
                                   ng-value="'recovery'"
                                   ng-model="filter.state"
                                   ng-model-options="{debounce: 500}">
                            <label class="custom-control-label custom-control-label-up"
                                   for="widget-radio0-{{widget.id}}">
                                <?php echo __('Up'); ?>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-left margin-right-10">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="widget-radio1-{{widget.id}}"
                                   ng-value="'down'"
                                   ng-model="filter.state"
                                   ng-model-options="{debounce: 500}">
                            <label class="custom-control-label custom-control-label-down"
                                   for="widget-radio1-{{widget.id}}">
                                <?php echo __('Down'); ?>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-left margin-right-10">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="widget-radio2-{{widget.id}}"
                                   ng-value="'unreachable'"
                                   ng-model="filter.state"
                                   ng-model-options="{debounce: 500}">
                            <label class="custom-control-label custom-control-label-unreachable"
                                   for="widget-radio2-{{widget.id}}">
                                <?php echo __('Unreachable'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-lg-12">
                        <h5 class="pt-1">
                            <?= __('Not older than interval'); ?>
                        </h5>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text">
                                    <i class="far fa-clock fa-lg"></i>
                                </span>
                            </div>
                            <input ng-model="filter.not_older_than"
                                   placeholder="<?= __('min value 1'); ?>"
                                   class="form-control" type="number" min="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary dropdown-toggle"
                                        ng-switch="filter.not_older_than_unit"
                                        type="button" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <span ng-switch-when="MINUTE">
                                        <?= __('minutes'); ?>
                                    </span>
                                    <span ng-switch-when="HOUR">
                                        <?= __('hours'); ?>
                                    </span>
                                    <span ng-switch-when="DAY">
                                        <?= __('days'); ?>
                                    </span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       ng-click="filter.not_older_than_unit = 'MINUTE'">
                                        <?= __('minutes'); ?>
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       ng-click="filter.not_older_than_unit = 'HOUR'">
                                        <?= __('hours'); ?>
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       ng-click="filter.not_older_than_unit = 'DAY'">
                                        <?= __('days'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-danger" ng-if="error">
                    <?php echo __('Number greater 0 required'); ?>
                </div>
                <div class="row py-2">
                    <div class="col-lg-12">
                        <button class="btn btn-primary float-right" ng-click="saveHostTopAlertWidget()">
                            <?php echo __('Save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </flippy-back>
    </flippy>

</div>
