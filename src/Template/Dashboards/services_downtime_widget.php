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
                                   ng-model="filter.isRunning"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Is running'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.DowntimeService.was_not_cancelled"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Was not cancelled'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.DowntimeService.was_cancelled"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Was cancelled'); ?>
                        </label>
                    </div>

                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <label class="checkbox small-checkbox-label display-inline margin-right-5">
                            <input type="checkbox" name="checkbox" checked="checked"
                                   ng-model="filter.hideExpired"
                                   ng-model-options="{debounce: 500}">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Hide expired'); ?>
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by host name'); ?>"
                           ng-model="filter.Host.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-cog"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by service name'); ?>"
                           ng-model="filter.Service.name"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group smart-form">
                <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                    <input type="text" class="input-sm"
                           placeholder="<?php echo __('Filter by comment'); ?>"
                           ng-model="filter.DowntimeService.comment_data"
                           ng-model-options="{debounce: 500}">
                </label>
            </div>
        </div>
    </div>
</div>
<div class="mobile_table margin-top-10">

    <table id="hostdowntimes_list"
           class="table table-striped table-hover table-bordered smart-form" style="">
        <thead>
        <tr>
            <th class="no-sort"><?php echo __('Running'); ?></th>
            <th class="no-sort" ng-click="orderBy('Host.name')">
                <i class="fa" ng-class="getSortClass('Host.name')"></i>
                <?php echo __('Host'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('Service.name')">
                <i class="fa" ng-class="getSortClass('Service.name')"></i>
                <?php echo __('Service'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.author_name')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.author_name')"></i>
                <?php echo __('User'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.comment_data')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.comment_data')"></i>
                <?php echo __('Comment'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.entry_time')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.entry_time')"></i>
                <?php echo __('Created'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.scheduled_start_time')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.scheduled_start_time')"></i>
                <?php echo __('Start'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.scheduled_end_time')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.scheduled_end_time')"></i>
                <?php echo __('End'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.duration')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.duration')"></i>
                <?php echo __('Duration'); ?>
            </th>
            <th class="no-sort" ng-click="orderBy('DowntimeHost.was_cancelled')">
                <i class="fa" ng-class="getSortClass('DowntimeHost.was_cancelled')"></i>
                <?php echo __('Was cancelled'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="downtime in downtimes">
            <td class="text-center">
                <downtimeicon downtime="downtime.DowntimeService"></downtimeicon>
            </td>
            <td>
                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                    <a href="/ng/#!/hosts/browser/{{ downtime.Host.id }}">
                        {{ downtime.Host.hostname }}
                    </a>
                <?php else: ?>
                    {{ downtime.Host.hostname }}
                <?php endif; ?>
            </td>
            <td>
                <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                    <a href="/ng/#!/services/browser/{{ downtime.Service.id }}">
                        {{ downtime.Service.servicename }}
                    </a>
                <?php else: ?>
                    {{ downtime.Service.servicename }}
                <?php endif; ?>
            </td>
            <td>
                {{downtime.DowntimeService.authorName}}
            </td>
            <td>
                {{downtime.DowntimeService.commentData}}
            </td>
            <td>
                {{downtime.DowntimeService.entryTime}}
            </td>
            <td>
                {{downtime.DowntimeService.scheduledStartTime}}
            </td>
            <td>
                {{downtime.DowntimeService.scheduledEndTime}}
            </td>
            <td>
                {{downtime.DowntimeService.durationHuman}}
            </td>
            <td>
                <span ng-if="downtime.DowntimeService.wasCancelled"><?php echo __('Yes'); ?></span>
                <span ng-if="!downtime.DowntimeService.wasCancelled"><?php echo __('No'); ?></span>
            </td>
        </tr>

        <tr>
        </tbody>
    </table>
    <scroll scroll="scroll" click-action="changepage" only-buttons="true" ng-if="scroll"></scroll>

</div>
<div class="row margin-top-10 margin-bottom-10">
    <div class="row margin-top-10 margin-bottom-10" ng-show="downtimes.length == 0">
        <div class="col-xs-12 text-center txt-color-red italic">
            <?php echo __('No entries match the selection'); ?>
        </div>
    </div>
</div>