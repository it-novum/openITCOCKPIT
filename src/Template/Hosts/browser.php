<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * @var \App\View\AppView $this
 * @var string $masterInstanceName
 * @var string $username
 */

use Cake\Core\Plugin;

?>

<query-handler-directive></query-handler-directive>

<div ng-init="flashMshStr='<?php echo __('Command sent successfully. Refresh in 5 seconds'); ?>'"></div>


<host-browser-menu
    ng-if="hostBrowserMenuConfig"
    config="hostBrowserMenuConfig"
    last-load-date="lastLoadDate"></host-browser-menu>

<article class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" role="widget">
            <header role="heading">
                <h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Host'); ?>:</strong> {{
                    mergedHost.name }}</h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active cursor-pointer">
                        <a ng-click="selectedTab = 'tab1'; hideTimeline()" data-toggle="tab">
                            <i class="fa fa-lg fa-info"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Status information'); ?></span>
                        </a>
                    </li>

                    <li class="cursor-pointer">
                        <a ng-click="selectedTab = 'tab2'; hideTimeline()" data-toggle="tab">
                            <i class="fa fa-lg fa-hdd-o"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Device information'); ?> </span>
                        </a>
                    </li>

                    <?php if ($this->Acl->hasPermission('timeline', 'hosts')): ?>
                        <li class="cursor-pointer">
                            <a ng-click="selectedTab = 'tab3'; showTimeline()" data-toggle="tab">
                                <i class="fa fa-lg fa-clock-o"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('Timeline'); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Plugin::isLoaded('ServicenowModule')): ?>
                        <li class="cursor-pointer">
                            <a ng-click="selectedTab = 'tab4'; hideTimeline()" data-toggle="tab">
                                <i class="fa fa-lg fa-user-circle"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('ServiceNow'); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Plugin::isLoaded('GrafanaModule')): ?>
                        <li class="cursor-pointer" ng-show="GrafanaDashboardExists">
                            <a ng-click="selectedTab = 'tab5'; hideTimeline()" data-toggle="tab">
                                <i class="fa fa-lg fa-area-chart"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('Grafana'); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="widget-toolbar" role="menu">
                    <button type="button" class="btn btn-xs btn-default" ng-click="loadHost()">
                        <i class="fa fa-refresh"></i>
                        <?php echo __('Refresh'); ?>
                    </button>
                </div>
            </header>

            <div role="content">

                <div class="widget-body no-padding">
                    <div class="tab-content no-padding">
                        <div ng-show="selectedTab == 'tab1'" class="tab-pane fade active in">
                            <div class="hidden-sm hidden-md hidden-lg"
                                 ng-class="{'browser-state-green': stateIsUp(), 'browser-state-red': stateIsDown(), 'browser-state-gray': stateIsUnreachable(), 'browser-state-blue': stateIsNotInMonitoring()}"
                                 ng-if="hoststatus">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php echo __('State'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ hoststatus.currentState | hostStatusName }}
                                    </div>
                                </div>

                                <div ng-show="hoststatus.isInMonitoring">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __('State since'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            {{ hoststatus.last_state_change }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __('Last check'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            {{ hoststatus.lastCheck }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __('Next check'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <span
                                                ng-if="mergedHost.active_checks_enabled && mergedHost.is_satellite_host === false">{{ hoststatus.nextCheck }}</span>
                                            <span
                                                ng-if="mergedHost.active_checks_enabled === false || mergedHost.is_satellite_host === true">
                                                <?php echo __('n/a'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="hoststatus.isHardstate">
                                        <div class="col-xs-6">
                                            <?php echo __('State type'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo __('Hard state'); ?>
                                            ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                        </div>
                                    </div>
                                    <div class="row" ng-show="!hoststatus.isHardstate">
                                        <div class="col-xs-6">
                                            <?php echo __('State type'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo __('Soft state'); ?>
                                            ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                        </div>
                                    </div>

                                    <div class="row text-center padding-top-10 padding-bottom-10"
                                         ng-show="canSubmitExternalCommands && mergedHost.allowEdit">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="rescheduleHost(getObjectsForExternalCommand())">
                                                <i class="fa fa-refresh"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="hostDowntime(getObjectsForExternalCommand())">
                                                <i class="fa fa-power-off"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-show="hoststatus.currentState > 0"
                                                    ng-click="acknowledgeHost(getObjectsForExternalCommand())">
                                                <i class="fa fa-user"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="submitHostResult(getObjectsForExternalCommand())">
                                                <i class="fa fa-download"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="enableHostFlapDetection(getObjectsForExternalCommand())"
                                                    ng-show="!hoststatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="disableHostFlapDetection(getObjectsForExternalCommand())"
                                                    ng-show="hoststatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="enableHostNotifications(getObjectsForExternalCommand())"
                                                    ng-show="!hoststatus.notifications_enabled">
                                                <i class="fa fa-envelope"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="disableHostNotifications(getObjectsForExternalCommand())"
                                                    ng-show="hoststatus.notifications_enabled">
                                                <i class="fa fa-envelope-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="display: flex;">
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-9  padding-10">

                                    <div class="row" ng-show="mergedHost.disabled">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10 bg-warning" style="width: 100%;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-11 no-padding">
                                                        <div>
                                                            <h4 class="no-padding">
                                                                <i class="fa fa-plug"></i>
                                                                <?php echo __('This host is currently disabled!'); ?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Status overview'); ?></h3>
                                        </div>
                                    </div>

                                    <div class="row" ng-show="hoststatus.scheduledDowntimeDepth > 0">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-11 no-padding">
                                                        <div>
                                                            <h4 class="no-padding">
                                                                <i class="fa fa-power-off"></i>
                                                                <?php echo __('The host is currently in a planned maintenance period'); ?>
                                                            </h4>
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <?php echo __('Downtime was set by'); ?>
                                                            <b>{{downtime.authorName}}</b>
                                                            <?php echo __('with an duration of'); ?>
                                                            <b>{{downtime.durationHuman}}</b>.
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <small>
                                                                <?php echo __('Start time:'); ?>
                                                                {{ downtime.scheduledStartTime }}
                                                                <?php echo __('End time:'); ?>
                                                                {{ downtime.scheduledEndTime }}
                                                            </small>
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <?php echo __('Comment: '); ?>
                                                            {{downtime.commentData}}
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-1 no-padding">
                                                        <?php if ($this->Acl->hasPermission('delete', 'downtimes')): ?>
                                                            <button
                                                                class="btn btn-xs btn-danger"
                                                                ng-if="downtime.allowEdit && downtime.isCancellable"
                                                                ng-click="confirmHostDowntimeDelete(getObjectForDowntimeDelete())">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" ng-show="hoststatus.problemHasBeenAcknowledged">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">
                                                <div>
                                                    <h4 class="no-padding">
                                                        <i class="fa fa-user" ng-show="!acknowledgement.is_sticky"></i>
                                                        <i class="fa fa-user-o" ng-show="acknowledgement.is_sticky"></i>
                                                        <?php echo __('State of host is acknowledged'); ?>
                                                        <span ng-show="acknowledgement.is_sticky">
                                                            (<?php echo __('Sticky'); ?>)
                                                        </span>
                                                    </h4>
                                                </div>
                                                <div class="padding-top-5">
                                                    <?php echo __('Acknowledgement was set by'); ?>
                                                    <b>{{acknowledgement.author_name}}</b>
                                                    <?php echo __('at'); ?>
                                                    {{acknowledgement.entry_time}}
                                                </div>
                                                <div class="padding-top-5">
                                                    <?php echo __('Comment: '); ?>
                                                    <div style="display:inline"
                                                         ng-bind-html="acknowledgement.commentDataHtml | trustAsHtml"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" ng-show="hoststatus.isFlapping">
                                        <div class="col-xs-12 margin-bottom-10">
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

                                    <div class="row" ng-show="hasParentHostProblems">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">
                                                <div>
                                                    <h4 class="no-padding text-info">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                        <?php echo __('Problem with parent host detected!'); ?>
                                                    </h4>
                                                </div>
                                                <div>
                                                    <ul>
                                                        <li ng-repeat="parentHostProblem in parentHostProblems">
                                                            <a ui-sref="HostsBrowser({id:parentHostProblem.id})">
                                                                {{parentHostProblem.name}}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-9">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Check command'); ?></td>
                                                    <td>
                                                        <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                                            <a ui-sref="CommandsEdit({id: checkCommand.Command.id})">
                                                                {{ checkCommand.Command.name }}
                                                            </a>
                                                        <?php else: ?>
                                                            {{ checkCommand.Command.name }}
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Command line'); ?></td>
                                                    <td>
                                                        <code class="no-background">
                                                            {{ mergedHost.hostCommandLine }}
                                                        </code>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Output'); ?></td>
                                                    <td>
                                                        <code class="no-background" ng-class="hostStatusTextClass">
                                                            {{ hoststatus.output }}
                                                        </code>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-3">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Check period'); ?></td>
                                                    <td>
                                                        {{ checkPeriod.name }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Check interval'); ?></td>
                                                    <td>
                                                        {{ mergedHost.checkIntervalHuman }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Retry interval'); ?></td>
                                                    <td>
                                                        {{ mergedHost.retryIntervalHuman }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12" ng-show="hoststatus.longOutputHtml">
                                            <div><?php echo __('Long output'); ?></div>
                                            <div class="well">
                                                <code class="no-background">
                                                    <div ng-bind-html="hoststatus.longOutputHtml | trustAsHtml"></div>
                                                </code>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" ng-show="parenthosts.length">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Parent host overview'); ?></h3>
                                        </div>


                                        <div class="col-xs-12 col-sm-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="width-130"><?php echo __('Parent host state'); ?></th>
                                                    <th><?php echo __('Parent host name'); ?></th>
                                                    <th><?php echo __('Last state change'); ?></th>
                                                </tr>
                                                <tr ng-repeat="parenthost in parenthosts">
                                                    <td class="text-center">
                                                        <hoststatusicon
                                                            state="parentHoststatus[parenthost.uuid].currentState"></hoststatusicon>
                                                    </td>
                                                    <td>
                                                        <a ui-sref="HostsBrowser({id:parenthost.id})">
                                                            {{ parenthost.name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ parentHoststatus[parenthost.uuid].last_state_change }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Notification overview'); ?></h3>
                                        </div>

                                        <div
                                            class="col-xs-12 col-sm-12 col-md-6 text-info"
                                            ng-hide="areContactsFromHost">
                                            <?php echo __('Contacts and contact groups got inherited from'); ?>
                                            <span ng-show="areContactsInheritedFromHosttemplate" class="bold">

                                                <?php if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                                    <a ui-sref="HosttemplatesEdit({id: mergedHost.hosttemplate_id})">
                                                        <?php echo __('host template'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo __('host template'); ?>
                                                <?php endif; ?>
                                            </span>
                                            .
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr ng-show="mergedHost.contacts.length">
                                                    <td><?php echo __('Contacts'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contact in mergedHost.contacts">
                                                            <?php if ($this->Acl->hasPermission('edit', 'contacts')): ?>
                                                                <a ng-if="contact.allowEdit"
                                                                   ui-sref="ContactsEdit({id: contact.id})">
                                                                    {{contact.name}}
                                                                </a>
                                                                <span ng-if="!contact.allowEdit">{{contact.name}}</span>
                                                            <?php else: ?>
                                                                {{contact.name}}
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr ng-show="mergedHost.contactgroups.length">
                                                    <td><?php echo __('Contact groups'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contactgroup in mergedHost.contactgroups">
                                                            <?php if ($this->Acl->hasPermission('edit', 'contactgroups')): ?>
                                                                <a ng-if="contactgroup.allowEdit"
                                                                   ui-sref="ContactgroupsEdit({id: contactgroup.id})">
                                                                    {{contactgroup.container.name}}
                                                                </a>
                                                                <span ng-if="!contactgroup.allowEdit">
                                                                    {{contactgroup.container.name}}
                                                                </span>
                                                            <?php else: ?>
                                                                {{contactgroup.container.name}}
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Notification period'); ?></td>
                                                    <td>{{ notifyPeriod.name }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notification interval'); ?></td>
                                                    <td>{{ mergedHost.notificationIntervalHuman }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notifications enabled'); ?></td>
                                                    <td>
                                                        <span class="label label-success"
                                                              ng-show="hoststatus.notifications_enabled">
                                                            <?php echo __('Yes'); ?>
                                                        </span>

                                                        <span class="label label-danger"
                                                              ng-show="!hoststatus.notifications_enabled">
                                                            <?php echo __('No'); ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notify on'); ?></td>
                                                    <td>
                                                        <span class="label label-success"
                                                              ng-show="mergedHost.notify_on_recovery"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Recover'); ?>
                                                        </span>

                                                        <span class="label label-danger"
                                                              ng-show="mergedHost.notify_on_down"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Down'); ?>
                                                        </span>

                                                        <span class="label label-default"
                                                              ng-show="mergedHost.notify_on_unreachable"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Unreachable'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedHost.notify_on_flapping"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Flapping'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedHost.notify_on_downtime"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Downtime'); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6 col-md-5 col-lg-3 no-padding hidden-xs"
                                     ng-class="{'browser-state-green': stateIsUp(), 'browser-state-red': stateIsDown(), 'browser-state-gray': stateIsUnreachable(), 'browser-state-blue': stateIsNotInMonitoring()}"
                                     ng-if="hoststatus">

                                    <div class="text-center txt-color-white">
                                        <h1 class="font-size-50">
                                            {{ hoststatus.currentState | hostStatusName }}
                                        </h1>
                                    </div>

                                    <div ng-show="hoststatus.isInMonitoring">
                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('State since'); ?></div>
                                            <h3 class="margin-top-0">{{ hoststatus.last_state_change }}</h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('Last check'); ?></div>
                                            <h3 class="margin-top-0">{{ hoststatus.lastCheck }}</h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('Next check'); ?></div>
                                            <h3 class="margin-top-0">
                                                <span
                                                    ng-if="mergedHost.active_checks_enabled && mergedHost.is_satellite_host === false">
                                                    {{ hoststatus.nextCheck }}
                                                    <small style="color: #333;"
                                                           ng-show="hoststatus.latency > 1">(+ {{ hoststatus.latency }})
                                                    </small>
                                                </span>
                                                <span
                                                    ng-if="mergedHost.active_checks_enabled === false || mergedHost.is_satellite_host === true">
                                                    <?php echo __('n/a'); ?>
                                                </span>
                                            </h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('State type'); ?></div>
                                            <h3 class="margin-top-0" ng-show="hoststatus.isHardstate">
                                                <?php echo __('Hard state'); ?>
                                                ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                            </h3>

                                            <h3 class="margin-top-0" ng-show="!hoststatus.isHardstate">
                                                <?php echo __('Soft state'); ?>
                                                ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                            </h3>
                                        </div>

                                        <div ng-if="canSubmitExternalCommands && mergedHost.allowEdit">
                                            <div class="browser-action"
                                                 ng-click="rescheduleHost(getObjectsForExternalCommand())">
                                                <i class="fa fa-refresh"></i>
                                                <?php echo __('Reset check time '); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="hostDowntime(getObjectsForExternalCommand())">
                                                <i class="fa fa-power-off"></i>
                                                <?php echo __('Schedule maintenance'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-show="hoststatus.currentState > 0"
                                                 ng-click="acknowledgeHost(getObjectsForExternalCommand())">
                                                <i class="fa fa-user"></i>
                                                <?php echo __('Acknowledge host status'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="submitHostResult(getObjectsForExternalCommand())">
                                                <i class="fa fa-download"></i>
                                                <?php echo __('Passive transfer check result'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="enableHostFlapDetection(getObjectsForExternalCommand())"
                                                 ng-show="!hoststatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                                <?php echo __('Enable flap detection'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="disableHostFlapDetection(getObjectsForExternalCommand())"
                                                 ng-show="hoststatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                                <?php echo __('Disable flap detection'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="enableHostNotifications(getObjectsForExternalCommand())"
                                                 ng-show="!hoststatus.notifications_enabled">
                                                <i class="fa fa-envelope"></i>
                                                <?php echo __('Enable notifications'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="disableHostNotifications(getObjectsForExternalCommand())"
                                                 ng-show="hoststatus.notifications_enabled">
                                                <i class="fa fa-envelope-o"></i>
                                                <?php echo __('Disable notifications'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="submitHostNotification(getObjectsForExternalCommand())">
                                                <i class="fa fa-envelope"></i>
                                                <?php echo __('Send custom host notification '); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div ng-show="selectedTab == 'tab2'" class="tab-pane active fade in">
                            <div class="row">
                                <div class="col-xs-12 padding-10">
                                    <div class="row">

                                        <div class="col-xs-12">
                                            <h3 class="margin-top-0"><?php echo __('Host overview'); ?></h3>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('IP address'); ?></td>
                                                    <td>{{ mergedHost.address }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Flap detection enabled'); ?></td>
                                                    <td>
                                                        <span class="label label-danger"
                                                              ng-show="hoststatus.flap_detection_enabled">
                                                            <?php echo __('Yes'); ?>
                                                        </span>

                                                        <span class="label label-success"
                                                              ng-show="!hoststatus.flap_detection_enabled">
                                                            <?php echo __('No'); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Priority'); ?></td>
                                                    <td>
                                                        <i class="fa fa-fire ma"
                                                           ng-repeat="priority in priorities"
                                                           ng-class="{'text-primary': priority, 'text-lightGray': !priority}"
                                                           style="font-size:17px;margin-left:2px;">
                                                        </i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo __('UUID'); ?></td>
                                                    <td>
                                                        <code>{{ mergedHost.uuid }}</code>
                                                        <span
                                                            class="btn btn-default btn-xs"
                                                            onclick="$('#host-uuid-copy').show().select();document.execCommand('copy');$('#host-uuid-copy').hide();"
                                                            title="<?php echo __('Copy to clipboard'); ?>">
                                                            <i class="fa fa-copy"></i>
                                                        </span>
                                                        <input type="text" style="display:none;" id="host-uuid-copy"
                                                               value="{{ mergedHost.uuid }}"
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Container'); ?></td>

                                                    <td>
                                                        <?php if ($this->Acl->hasPermission('index', 'browsers')): ?>
                                                            <a ui-sref="BrowsersIndex({containerId: mergedHost.container_id})">
                                                                /{{mainContainer}}
                                                            </a>
                                                        <?php else: ?>
                                                            /{{mainContainer}}
                                                        <?php endif; ?>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td><?php echo __('Shared containers'); ?></td>
                                                    <td>

                                                        <?php if ($this->Acl->hasPermission('index', 'browsers')): ?>
                                                            <div ng-repeat="(key, value) in sharedContainers">
                                                                <a ui-sref="BrowsersIndex({containerId: key})">
                                                                    /{{value}}
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div ng-repeat="(key, value) in sharedContainers">
                                                                /{{value}}
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr ng-show="tags.length">
                                                    <td><?php echo __('Tags'); ?></td>
                                                    <td>
                                                        <span class="label label-primary"
                                                              ng-repeat="tag in tags"
                                                              style="margin-right: 2px;">{{tag}}</span>
                                                    </td>
                                                </tr>

                                                <tr ng-show="mergedHost.is_satellite_host">
                                                    <td><?php echo __('Satellite'); ?></td>
                                                    <td>
                                                        <satellite-name
                                                            satellite-id="mergedHost.satellite_id"
                                                            ng-if="mergedHost.is_satellite_host"
                                                        ></satellite-name>
                                                    </td>
                                                </tr>

                                                <tr ng-show="mergedHost.is_satellite_host === false">
                                                    <td><?php echo __('Instance'); ?></td>
                                                    <td>
                                                        <?php if (isset($masterInstanceName)) echo h($masterInstanceName); ?>
                                                    </td>
                                                </tr>

                                                <tr ng-show="mergedHost.notes">
                                                    <td><?php echo __('Notes'); ?></td>
                                                    <td>
                                                        {{mergedHost.notes}}
                                                    </td>
                                                </tr>
                                                <tr ng-show="mergedHost.description">
                                                    <td><?php echo __('Description'); ?></td>
                                                    <td>
                                                        {{mergedHost.description}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fade in active" ng-show="showTimelineTab && selectedTab == 'tab3'">
                            <div class="row">
                                <div class="col-xs-12 padding-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-0">
                                                <?php echo __('Outages: '); ?>
                                                <span ng-hide="failureDurationInPercent">
                                                    <i class="fa fa-refresh fa-spin txt-primary"></i>
                                                </span>
                                                <span ng-show="failureDurationInPercent">{{ (failureDurationInPercent) ? failureDurationInPercent+' %' :
                                                    '<?php echo __('No data available !'); ?>'}}
                                                </span>
                                            </h3></div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div id="visualization"></div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-12 bold"><?php echo __('Legend'); ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php echo __('State types'); ?>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-3">
                                                        <i class="fa fa-square up-soft"></i>
                                                        <?php echo __('Up soft'); ?>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3 ">
                                                        <i class="fa fa-square down-soft"></i>
                                                        <?php echo __('Down soft'); ?>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3 ">
                                                        <i class="fa fa-square unreachable-soft"></i>
                                                        <?php echo __('Unreachable soft'); ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-3">
                                                        <i class="fa fa-square up"></i>
                                                        <?php echo __('Up hard'); ?>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3 ">
                                                        <i class="fa fa-square down"></i>
                                                        <?php echo __('Down hard'); ?>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3 ">
                                                        <i class="fa fa-square unreachable"></i>
                                                        <?php echo __('Unreachable hard'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-md-3">
                                                    <i class="fa fa-square text-primary"></i>
                                                    <?php echo __('Downtime'); ?>
                                                </div>
                                                <div class="col-xs-12 col-md-3 ">
                                                    <i class="fa fa-square txt-ack"></i>
                                                    <?php echo __('Acknowledged'); ?>
                                                </div>
                                                <div class="col-xs-12 col-md-3 ">
                                                    <i class="fa fa-square txt-notification"></i>
                                                    <?php echo __('Notification'); ?>
                                                </div>
                                                <div class="col-xs-12 col-md-3 ">
                                                    <i class="fa fa-square txt-timerange"></i>
                                                    <?php echo __('Check period'); ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-md-3">
                                                    <i class="fa fa-square txt-downtime-cancelled"></i>
                                                    <?php echo __('Downtime cancelled'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fade in active" ng-show="selectedTab == 'tab4'">
                            <div class="jarviswidget margin-bottom-0 padding-10" id="wid-id-0">
                                <?php if ($this->Acl->hasPermission('host_configuration', 'elements', 'servicenowModule') && Plugin::isLoaded('ServicenowModule')): ?>
                                    <servicenow-host-element last-load="{{ lastLoadDate }}"
                                                             host-uuid="{{ mergedHost.uuid }}"
                                                             editable="<?php echo $this->Acl->hasPermission('edit', 'hosts'); ?>">
                                    </servicenow-host-element>
                                <?php else: ?>
                                    <label class="text-danger">
                                        <?php echo __('No permissions'); ?>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="fade in active" ng-show="GrafanaDashboardExists && selectedTab == 'tab5'">
                            <div class="widget-toolbar">
                                <grafana-timepicker callback="grafanaTimepickerCallback"></grafana-timepicker>
                            </div>
                            <iframe-directive url="GrafanaIframeUrl"
                                              ng-if="GrafanaDashboardExists && selectedTab == 'tab5'"></iframe-directive>
                        </div>
                    </div>

                    <div class="widget-footer text-right"></div>
                </div>
            </div>
        </div>
    </article>


    <massdelete></massdelete>
    <massdeactivate></massdeactivate>
    <massactivate></massactivate>

    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" role="widget">
            <header>
                <div class="widget-toolbar" role="menu">
                    <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                        <i class="fa fa-refresh"></i>
                        <?php echo __('Refresh'); ?>
                    </button>

                    <?php if ($this->Acl->hasPermission('add', 'services')): ?>
                        <a ui-sref="ServicesAdd({hostId: mergedHost.id})"
                           class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                            <?php echo __('Add'); ?>
                        </a>
                    <?php endif; ?>

                </div>
                <span class="widget-icon hidden-mobile"> <i class="fa fa-cogs"></i> </span>
                <h2 class="hidden-mobile"><?php echo __('Service overview'); ?></h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active cursor-pointer">
                        <a data-toggle="tab" ng-click="changeTab('active')">
                            <i class="fa fa-stethoscope"></i>
                            <span class="hidden-mobile hidden-tablet">
                                <?php echo __('Active'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="cursor-pointer">
                        <a data-toggle="tab" ng-click="changeTab('notMonitored')">
                            <i class="fa fa-user-md"></i>
                            <span class="hidden-mobile hidden-tablet">
                                <?php echo __('Not monitored'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="cursor-pointer">
                        <a data-toggle="tab" ng-click="changeTab('disabled')">
                            <i class="fa fa-plug"></i>
                            <span class="hidden-mobile hidden-tablet">
                                <?php echo __('Disabled'); ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body no-padding">
                    <div class="tab-content">

                        <div id="serviceTab1" class="tab-pane fade active in" ng-if="activeTab === 'active'">
                            <div class="mobile_table">
                                <table id="host_list"
                                       class="table table-striped table-hover table-bordered smart-form">
                                    <thead>
                                    <tr>

                                        <th class="width-120 no-sort" ng-click="orderBy('Servicestatus.current_state')">
                                            <i class="fa"
                                               ng-class="getSortClass('Servicestatus.current_state')"></i>
                                            <?php echo __('Service status'); ?>
                                        </th>

                                        <th class="no-sort text-center">
                                            <i class="fa fa-user fa-lg"
                                               title="<?php echo __('Acknowledgedment'); ?>"></i>
                                        </th>

                                        <th class="no-sort text-center">
                                            <i class="fa fa-power-off fa-lg"
                                               title="<?php echo __('in Downtime'); ?>"></i>
                                        </th>

                                        <th class="no-sort text-center">
                                            <i class="fa fa fa-area-chart fa-lg"
                                               title="<?php echo __('Grapher'); ?>"></i>
                                        </th>

                                        <th class="no-sort text-center">
                                            <strong title="<?php echo __('Passively transferred service'); ?>">
                                                P
                                            </strong>
                                        </th>

                                        <th class="no-sort" ng-click="orderBy('servicename')">
                                            <i class="fa" ng-class="getSortClass('servicename')"></i>
                                            <?php echo __('Service name'); ?>
                                        </th>

                                        <th class="no-sort tableStatewidth"
                                            ng-click="orderBy('Servicestatus.last_state_change')">
                                            <i class="fa"
                                               ng-class="getSortClass('Servicestatus.last_state_change')"></i>
                                            <?php echo __('Last state change'); ?>
                                        </th>

                                        <th class="no-sort" ng-click="orderBy('Servicestatus.output')">
                                            <i class="fa" ng-class="getSortClass('Servicestatus.output')"></i>
                                            <?php echo __('Service output'); ?>
                                        </th>

                                        <th class="no-sort text-center width-50">
                                            <i class="fa fa-gear fa-lg"></i>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">
                                            <div class="smart-form inline-group">
                                                <label class="checkbox small-checkbox-label-table">
                                                    <input name="checkbox"
                                                           ng-model="activeServiceFilter.Servicestatus.current_state.ok"
                                                           ng-model-options="{debounce: 500}"
                                                           type="checkbox">
                                                    <i class="checkbox-success"></i>
                                                    &nbsp;
                                                </label>
                                                <label class="checkbox small-checkbox-label-table">
                                                    <input name="checkbox"
                                                           ng-model="activeServiceFilter.Servicestatus.current_state.warning"
                                                           ng-model-options="{debounce: 500}"
                                                           type="checkbox">
                                                    <i class="checkbox-warning"></i>
                                                    &nbsp;
                                                </label>
                                                <label class="checkbox small-checkbox-label-table">
                                                    <input name="checkbox"
                                                           ng-model="activeServiceFilter.Servicestatus.current_state.critical"
                                                           ng-model-options="{debounce: 500}"
                                                           type="checkbox">
                                                    <i class="checkbox-danger"></i>
                                                    &nbsp;
                                                </label>
                                                <label class="checkbox small-checkbox-label-table">
                                                    <input name="checkbox"
                                                           ng-model="activeServiceFilter.Servicestatus.current_state.unknown"
                                                           ng-model-options="{debounce: 500}"
                                                           type="checkbox">
                                                    <i class="checkbox-default"></i>
                                                    &nbsp;
                                                </label>
                                            </div>
                                        </th>

                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th class="smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-cog"></i>
                                                <input class="input-sm"
                                                       placeholder="<?php echo __('Filter by service name'); ?>"
                                                       ng-model="activeServiceFilter.Service.name"
                                                       ng-model-options="{debounce: 500}"
                                                       type="text">
                                            </label>
                                        </th>

                                        <th></th>

                                        <th class="smart-form">
                                            <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                                <input class="input-sm"
                                                       placeholder="<?php echo __('Filter by output'); ?>"
                                                       ng-model="activeServiceFilter.Servicestatus.output"
                                                       ng-model-options="{debounce: 500}"
                                                       type="text">
                                            </label>
                                        </th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr ng-repeat="service in services">

                                        <td class="text-center width-90">
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

                                        <td class="text-center">
                                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a ui-sref="ServicesBrowser({id:service.Service.id})"
                                                   class="txt-color-blueDark">
                                                    <i class="fa fa-lg fa-area-chart"
                                                       ng-mouseenter="mouseenter($event, mergedHost.uuid, service)"
                                                       ng-mouseleave="mouseleave()"
                                                       ng-if="service.Service.has_graph">
                                                    </i>
                                                </a>
                                            <?php else: ?>
                                                <i class="fa fa-lg fa-area-chart"
                                                   ng-mouseenter="mouseenter($event, mergedHost.uuid, service)"
                                                   ng-mouseleave="mouseleave()"
                                                   ng-if="service.Service.has_graph">
                                                </i>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <strong title="<?php echo __('Passively transferred service'); ?>"
                                                    ng-show="service.Service.active_checks_enabled === false || mergedHost.is_satellite_host === true">
                                                P
                                            </strong>
                                        </td>

                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a ui-sref="ServicesBrowser({id:service.Service.id})">
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

                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                    <a ui-sref="ServicesEdit({id: service.Service.id})"
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
                                                <ul class="dropdown-menu pull-right"
                                                    id="menuHack-{{service.Service.uuid}}">
                                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a ui-sref="ServicesEdit({id: service.Service.id})">
                                                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a href="javascript:void(0);"
                                                               ng-click="confirmDeactivate(getObjectForDelete(mergedHost.name, service))">
                                                                <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                        <li class="divider"></li>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a href="javascript:void(0);" class="txt-color-red"
                                                               ng-click="confirmDelete(getObjectForDelete(mergedHost.name, service))">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="noMatch" ng-show="services.length === 0">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                </center>
                            </div>


                            <div id="serviceGraphContainer" class="popup-graph-container">
                                <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;"
                                     ng-show="isLoadingGraph">
                                    <i class="fa fa-refresh fa-4x fa-spin"></i>
                                </div>
                                <div id="serviceGraphFlot"></div>
                            </div>

                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>

                        </div> <!-- close tab1 -->

                        <div id="serviceTab2" class="tab-pane fade active in" ng-if="activeTab === 'notMonitored'">

                            <table class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort width-90">
                                        <?php echo __('Servicestatus'); ?>
                                    </th>


                                    <th class="no-sort">
                                        <?php echo __('Service name'); ?>
                                    </th>

                                    <th class="no-sort text-center width-50">
                                        <i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="service in services">
                                    <td class="text-center width-90">
                                        <servicestatusicon service="fakeServicestatus"></servicestatusicon>
                                    </td>

                                    <td>
                                        <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                            <a ui-sref="ServicesBrowser({id:service.Service.id})">
                                                {{ service.Service.servicename }}
                                            </a>
                                        <?php else: ?>
                                            {{ service.Service.servicename }}
                                        <?php endif; ?>
                                    </td>

                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <a ui-sref="ServicesEdit({id: service.Service.id})"
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
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{service.Service.uuid}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <a ui-sref="ServicesEdit({id: service.Service.id})">
                                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           ng-click="confirmDeactivate(getObjectForDelete(mergedHost.name, service))">
                                                            <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                    <li class="divider"></li>
                                                    <li ng-if="service.Service.allow_edit">
                                                        <a href="javascript:void(0);" class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(mergedHost.name, service))">
                                                            <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="noMatch" ng-show="services.length === 0">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                </center>
                            </div>


                            <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>

                        </div> <!-- close tab2 -->

                        <div id="serviceTab3" class="tab-pane fade active in" ng-if="activeTab === 'disabled'">

                            <div class="mobile_table">
                                <table class="table table-striped table-hover table-bordered smart-form">
                                    <thead>
                                    <tr>
                                        <th class="no-sort width-90">
                                            <?php echo __('Servicestatus'); ?>
                                        </th>


                                        <th class="no-sort">
                                            <?php echo __('Service name'); ?>
                                        </th>

                                        <th class="no-sort text-center width-50">
                                            <i class="fa fa-gear fa-lg"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="service in services">
                                        <td class="text-center width-90">
                                            <servicestatusicon service="fakeServicestatus"></servicestatusicon>
                                        </td>

                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                <a ui-sref="ServicesBrowser({id:service.Service.id})">
                                                    {{ service.Service.servicename }}
                                                </a>
                                            <?php else: ?>
                                                {{ service.Service.servicename }}
                                            <?php endif; ?>
                                        </td>

                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                    <a ui-sref="ServicesEdit({id: service.Service.id})"
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
                                                <ul class="dropdown-menu pull-right"
                                                    id="menuHack-{{service.Service.uuid}}">
                                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a ui-sref="ServicesEdit({id: service.Service.id})">
                                                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('enable', 'services')): ?>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a href="javascript:void(0);"
                                                               ng-click="confirmActivate(getObjectForDelete(mergedHost.name, service))">
                                                                <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                        <li class="divider"></li>
                                                        <li ng-if="service.Service.allow_edit">
                                                            <a href="javascript:void(0);" class="txt-color-red"
                                                               ng-click="confirmDelete(getObjectForDelete(mergedHost.name, service))">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="noMatch" ng-show="services.length === 0">
                                    <center>
                                        <span class="txt-color-red italic"><?php echo __('No services found'); ?></span>
                                    </center>
                                </div>


                                <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                            </div>

                        </div> <!-- close tab4 -->
                    </div>
                </div>
            </div>
        </div>
    </article>
</article>


<reschedule-host callback="showFlashMsg"></reschedule-host>
<disable-host-notifications callback="showFlashMsg"></disable-host-notifications>
<enable-host-notifications callback="showFlashMsg"></enable-host-notifications>
<acknowledge-host author="<?php echo h($username); ?>" callback="showFlashMsg"></acknowledge-host>
<host-downtime author="<?php echo h($username); ?>" callback="showFlashMsg"></host-downtime>
<submit-host-result max-check-attempts="{{hoststatus.max_check_attempts}}" callback="showFlashMsg"></submit-host-result>
<enable-host-flap-detection callback="showFlashMsg"></enable-host-flap-detection>
<disable-host-flap-detection callback="showFlashMsg"></disable-host-flap-detection>
<send-host-notification author="<?php echo h($username); ?>" callback="showFlashMsg"></send-host-notification>
<mass-delete-host-downtimes delete-url="/downtimes/delete/" callback="showFlashMsg"></mass-delete-host-downtimes>
