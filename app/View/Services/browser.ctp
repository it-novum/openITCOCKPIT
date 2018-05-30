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

if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>

<div class="alert alert-success alert-block" ng-show="showFlashSuccess">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Data refresh in'); ?> {{ autoRefreshCounter }} <?php echo __('seconds...'); ?>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="status_headline" ng-class="serviceStatusTextClass">

            <span class="flapping_airport stateClass" ng-show="servicestatus.isFlapping">
                <i class="fa" ng-class="flappingState === 1 ? 'fa-circle' : 'fa-circle-o'"></i>
                <i class="fa" ng-class="flappingState === 0 ? 'fa-circle' : 'fa-circle-o'"></i>
            </span>

            <i class="fa fa-cog fa-fw"></i>
            {{ mergedService.Service.name }}
            <?php echo __('on'); ?>
            <a href="/hosts/browser/{{host.Host.id}}">
                <span>
                    {{ host.Host.name }}
                    ({{ host.Host.address }})
                </span>
            </a>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
        <h5>
            <div class="pull-right">
                <?php echo $this->element('service_browser_menu'); ?>
            </div>
        </h5>
    </div>
</div>

<article class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" role="widget">
            <header role="heading">
                <h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Service'); ?>:</strong>
                    {{ host.Host.name }} / {{ mergedService.Service.name }}
                </h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <i class="fa fa-lg fa-info"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Status information'); ?></span>
                        </a>
                    </li>

                    <li class="">
                        <a href="#tab2" data-toggle="tab">
                            <i class="fa fa-lg fa-cog"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Service information'); ?> </span>
                        </a>
                    </li>

                    <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host'); ?>
                </ul>

                <div class="widget-toolbar" role="menu">
                    <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                        <i class="fa fa-refresh"></i>
                        <?php echo __('Refresh'); ?>
                    </button>
                </div>
            </header>

            <div role="content">

                <div class="widget-body no-padding">
                    <div class="tab-content no-padding">
                        <div id="tab1" class="tab-pane fade active in">
                            <div class="hidden-sm hidden-md hidden-lg"
                                 ng-class="{'browser-state-green': stateIsOk(), 'browser-state-yellow': stateIsWarning(), 'browser-state-red': stateIsCritical(), 'browser-state-gray': stateIsUnknown(), 'browser-state-blue': stateIsNotInMonitoring()}"
                                 ng-if="servicestatus">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php echo __('State'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ servicestatus.currentState | serviceStatusName }}
                                    </div>
                                </div>

                                <div ng-show="servicestatus.isInMonitoring">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __('State since'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            {{ servicestatus.last_state_change }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __('Last check'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            {{ servicestatus.lastCheck }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <?php echo __(' Next check'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            {{ servicestatus.nextCheck }}
                                        </div>
                                    </div>
                                    <div class="row" ng-show="servicestatus.isHardstate">
                                        <div class="col-xs-6">
                                            <?php echo __('State type'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo __('Hard state'); ?>
                                            ({{servicestatus.current_check_attempt}}/{{servicestatus.max_check_attempts}})
                                        </div>
                                    </div>
                                    <div class="row" ng-show="!servicestatus.isHardstate">
                                        <div class="col-xs-6">
                                            <?php echo __('State type'); ?>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo __('Soft state'); ?>
                                            ({{servicestatus.current_check_attempt}}/{{servicestatus.max_check_attempts}})
                                        </div>
                                    </div>

                                    <div class="row text-center padding-top-10 padding-bottom-10"
                                         ng-show="canSubmitExternalCommands && mergedService.Service.allowEdit">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="reschedule(getObjectsForExternalCommand())">
                                                <i class="fa fa-refresh"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="serviceDowntime(getObjectsForExternalCommand())">
                                                <i class="fa fa-power-off"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-show="servicestatus.currentState > 0"
                                                    ng-click="acknowledgeService(getObjectsForExternalCommand())">
                                                <i class="fa fa-user"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="submitServiceResult(getObjectsForExternalCommand())">
                                                <i class="fa fa-download"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="enableServiceFlapDetection(getObjectsForExternalCommand())"
                                                    ng-show="!servicestatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="disableServiceFlapDetection(getObjectsForExternalCommand())"
                                                    ng-show="servicestatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                            </button>

                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="enableServiceNotifications(getObjectsForExternalCommand())"
                                                    ng-show="!servicestatus.notifications_enabled">
                                                <i class="fa fa-envelope"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-default"
                                                    ng-click="disableServiceNotifications(getObjectsForExternalCommand())"
                                                    ng-show="servicestatus.notifications_enabled">
                                                <i class="fa fa-envelope-o"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="display: flex;">
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-9  padding-10">

                                    <div class="row" ng-show="mergedService.Service.disabled">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10 bg-warning" style="width: 100%;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-11 no-padding">
                                                        <div>
                                                            <h4 class="no-padding">
                                                                <i class="fa fa-plug"></i>
                                                                <?php echo __('This service is currently disabled!'); ?>
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

                                    <div class="row" ng-show="servicestatus.scheduledDowntimeDepth > 0">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-11 no-padding">
                                                        <div>
                                                            <h4 class="no-padding">
                                                                <i class="fa fa-power-off"></i>
                                                                <?php echo __('The service is currently in a planned maintenance period'); ?>
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
                                                                    ng-click="confirmServiceDowntimeDelete(getObjectForDowntimeDelete())">
                                                                <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" ng-show="servicestatus.problemHasBeenAcknowledged">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">
                                                <div>
                                                    <h4 class="no-padding">
                                                        <i class="fa fa-user" ng-show="!acknowledgement.is_sticky"></i>
                                                        <i class="fa fa-user-o" ng-show="acknowledgement.is_sticky"></i>
                                                        <?php echo __('State of service is acknowledged'); ?>
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


                                    <div class="row" ng-show="servicestatus.isFlapping">
                                        <div class="col-xs-12 margin-bottom-10">
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

                                    <div class="row" ng-show="hoststatus.currentState > 0">
                                        <div class="col-xs-12 margin-bottom-10">
                                            <div class="browser-border padding-10" style="width: 100%;">
                                                <div>
                                                    <h4 class="no-padding text-info">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                        <?php echo __('Problem with host detected!'); ?>
                                                    </h4>
                                                </div>
                                                <div>
                                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                    <a href="/hosts/browser/{{host.Host.id}}">
                                                        {{host.Host.name}}
                                                    </a>
                                                    <?php else: ?>
                                                        {{host.Host.name}}
                                                    <?php endif; ?>
                                                </div>
                                            </div>
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
                                                                <?php echo __('The host of this service is currently in a planned maintenance period'); ?>
                                                            </h4>
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <?php echo __('Downtime was set by'); ?>
                                                            <b>{{hostDowntime.authorName}}</b>
                                                            <?php echo __('with an duration of'); ?>
                                                            <b>{{hostDowntime.durationHuman}}</b>.
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <small>
                                                                <?php echo __('Start time:'); ?>
                                                                {{ hostDowntime.scheduledStartTime }}
                                                                <?php echo __('End time:'); ?>
                                                                {{ hostDowntime.scheduledEndTime }}
                                                            </small>
                                                        </div>
                                                        <div class="padding-top-5">
                                                            <?php echo __('Comment: '); ?>
                                                            {{hostDowntime.commentData}}
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-1 no-padding">
                                                        <?php if ($this->Acl->hasPermission('delete', 'downtimes')): ?>
                                                            <button
                                                                    class="btn btn-xs btn-danger"
                                                                    ng-if="hostDowntime.allowEdit && hostDowntime.isCancellable"
                                                                    ng-click="confirmHostDowntimeDelete(getObjectForHostDowntimeDelete())">
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
                                                        <i class="fa fa-user" ng-show="!hostAcknowledgement.is_sticky"></i>
                                                        <i class="fa fa-user-o" ng-show="hostAcknowledgement.is_sticky"></i>
                                                        <?php echo __('State of host is acknowledged'); ?>
                                                        <span ng-show="hostAcknowledgement.is_sticky">
                                                            (<?php echo __('Sticky'); ?>)
                                                        </span>
                                                    </h4>
                                                </div>
                                                <div class="padding-top-5">
                                                    <?php echo __('Acknowledgement was set by'); ?>
                                                    <b>{{hostAcknowledgement.author_name}}</b>
                                                    <?php echo __('at'); ?>
                                                    {{hostAcknowledgement.entry_time}}
                                                </div>
                                                <div class="padding-top-5">
                                                    <?php echo __('Comment: '); ?>
                                                    <div style="display:inline"
                                                         ng-bind-html="hostAcknowledgement.commentDataHtml | trustAsHtml"></div>
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
                                                            <a href="/commands/edit/{{ mergedService.CheckCommand.id }}">
                                                                {{ mergedService.CheckCommand.name }}
                                                            </a>
                                                        <?php else: ?>
                                                            {{ mergedService.CheckCommand.name }}
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Command line'); ?></td>
                                                    <td>
                                                        <code class="no-background">
                                                            {{ mergedService.serviceCommandLine }}
                                                        </code>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Output'); ?></td>
                                                    <td>
                                                        <code class="no-background" ng-class="serviceStatusTextClass">
                                                            {{ servicestatus.output }}
                                                        </code>
                                                    </td>
                                                </tr>

                                                <tr ng-show="servicestatus.perfdata">
                                                    <td><?php echo __('Performance data'); ?></td>
                                                    <td>
                                                        <code class="no-background" ng-class="serviceStatusTextClass">
                                                            {{ servicestatus.perfdata }}
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
                                                        {{ mergedService.CheckPeriod.name }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Check interval'); ?></td>
                                                    <td>
                                                        {{ mergedService.checkIntervalHuman }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Retry interval'); ?></td>
                                                    <td>
                                                        {{ mergedService.retryIntervalHuman }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12" ng-show="servicestatus.longOutputHtml">
                                            <div><?php echo __('Long output'); ?></div>
                                            <div class="well">
                                                <code class="no-background">
                                                    <div ng-bind-html="servicestatus.longOutputHtml | trustAsHtml"></div>
                                                </code>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Host state overview'); ?></h3>
                                        </div>


                                        <div class="col-xs-12 col-sm-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th class="width-130"><?php echo __('Host state'); ?></th>
                                                    <th><?php echo __('Host name'); ?></th>
                                                    <th><?php echo __('Last state change'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <hoststatusicon
                                                                ng-if="hoststatus"
                                                                state="hoststatus.currentState"
                                                        ></hoststatusicon>
                                                    </td>
                                                    <td>
                                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                        <a href="/hosts/browser/{{host.Host.id}}">
                                                            {{ host.Host.name }}
                                                        </a>
                                                        <?php else: ?>
                                                            {{ host.Host.name }}
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        {{ hoststatus.last_state_change }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Notification overview'); ?></h3>
                                        </div>


                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="alert alert-info"
                                                 ng-show="mergedService.areContactsFromHosttemplate">
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong><?php echo __('Info'); ?>:</strong>
                                                <?php echo __('Contacts have been inherited from the host template'); ?>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="alert alert-info"
                                                 ng-show="mergedService.areContactsFromHost">
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong><?php echo __('Info'); ?>:</strong>
                                                <?php echo __('Contacts have been inherited from the host'); ?>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="alert alert-info"
                                                 ng-show="mergedService.areContactsFromServicetemplate">
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong><?php echo __('Info'); ?>:</strong>
                                                <?php echo __('Contacts have been inherited from the service template'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr ng-show="contacts.length">
                                                    <td><?php echo __('Contacts'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contact in contacts">
                                                            <?php if ($this->Acl->hasPermission('edit', 'contacts')): ?>
                                                                <a
                                                                        href="/contacts/edit/{{contact.id}}"
                                                                        ng-if="contact.allowEdit">
                                                                    {{contact.name}}
                                                                </a>
                                                                <span ng-if="!contact.allowEdit">{{contact.name}}</span>
                                                            <?php else: ?>
                                                                {{contact.name}}
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr ng-show="contactgroups.length">
                                                    <td><?php echo __('Contact groups'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contactgroup in contactgroups">
                                                            <?php if ($this->Acl->hasPermission('edit', 'contactgroups')): ?>
                                                                <a
                                                                        href="/contactgroups/edit/{{contactgroup.id}}"
                                                                        ng-if="contactgroup.allowEdit">
                                                                    {{contactgroup.Container.name}}
                                                                </a>
                                                                <span ng-if="!contactgroup.allowEdit">
                                                                    {{contactgroup.Container.name}}
                                                                </span>
                                                            <?php else: ?>
                                                                {{contactgroup.Container.name}}
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
                                                    <td>{{ mergedService.NotifyPeriod.name }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notification interval'); ?></td>
                                                    <td>{{ mergedService.notificationIntervalHuman }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notifications enabled'); ?></td>
                                                    <td>
                                                        <span class="label label-success"
                                                              ng-show="servicestatus.notifications_enabled">
                                                            <?php echo __('Yes'); ?>
                                                        </span>

                                                        <span class="label label-danger"
                                                              ng-show="!servicestatus.notifications_enabled">
                                                            <?php echo __('No'); ?>
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Notify on'); ?></td>
                                                    <td>
                                                        <span class="label label-success"
                                                              ng-show="mergedService.Service.notify_on_recovery"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Recover'); ?>
                                                        </span>

                                                        <span class="label label-warning"
                                                              ng-show="mergedService.Service.notify_on_warning"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Warning'); ?>
                                                        </span>

                                                        <span class="label label-danger"
                                                              ng-show="mergedService.Service.notify_on_critical"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Critical'); ?>
                                                        </span>

                                                        <span class="label label-default"
                                                              ng-show="mergedService.Service.notify_on_unknown"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Unknown'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedService.Service.notify_on_flapping"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Flapping'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedService.Service.notify_on_downtime"
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
                                     ng-class="{'browser-state-green': stateIsOk(), 'browser-state-yellow': stateIsWarning(), 'browser-state-red': stateIsCritical(), 'browser-state-gray': stateIsUnknown(), 'browser-state-blue': stateIsNotInMonitoring()}"
                                     ng-if="servicestatus">

                                    <div class="text-center txt-color-white">
                                        <h1 class="font-size-50">
                                            {{ servicestatus.currentState | serviceStatusName }}
                                        </h1>
                                    </div>

                                    <div ng-show="servicestatus.isInMonitoring">
                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('State since'); ?></div>
                                            <h3 class="margin-top-0">{{ servicestatus.last_state_change }}</h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('Last check'); ?></div>
                                            <h3 class="margin-top-0">{{ servicestatus.lastCheck }}</h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('Next check'); ?></div>
                                            <h3 class="margin-top-0">
                                                {{ servicestatus.nextCheck }}
                                                <small style="color: #333;"
                                                       ng-show="servicestatus.latency > 1">
                                                    (+ {{ servicestatus.latency }})
                                                </small>
                                            </h3>
                                        </div>

                                        <div class="text-center txt-color-white">
                                            <div><?php echo __('State type'); ?></div>
                                            <h3 class="margin-top-0" ng-show="servicestatus.isHardstate">
                                                <?php echo __('Hard state'); ?>
                                                ({{servicestatus.current_check_attempt}}/{{servicestatus.max_check_attempts}})
                                            </h3>

                                            <h3 class="margin-top-0" ng-show="!servicestatus.isHardstate">
                                                <?php echo __('Soft state'); ?>
                                                ({{servicestatus.current_check_attempt}}/{{servicestatus.max_check_attempts}})
                                            </h3>
                                        </div>

                                        <div ng-if="canSubmitExternalCommands && mergedService.Service.allowEdit">
                                            <div class="browser-action"
                                                 ng-click="reschedule(getObjectsForExternalCommand())">
                                                <i class="fa fa-refresh"></i>
                                                <?php echo __('Reset check time '); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="serviceDowntime(getObjectsForExternalCommand())">
                                                <i class="fa fa-power-off"></i>
                                                <?php echo __('Schedule maintenance'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-show="servicestatus.currentState > 0"
                                                 ng-click="acknowledgeService(getObjectsForExternalCommand())">
                                                <i class="fa fa-user"></i>
                                                <?php echo __('Acknowledge service status'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="submitServiceResult(getObjectsForExternalCommand())">
                                                <i class="fa fa-download"></i>
                                                <?php echo __('Passive transfer check result'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="enableServiceFlapDetection(getObjectsForExternalCommand())"
                                                 ng-show="!servicestatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                                <?php echo __('Enable flap detection'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="disableServiceFlapDetection(getObjectsForExternalCommand())"
                                                 ng-show="servicestatus.flap_detection_enabled">
                                                <i class="fa fa-adjust"></i>
                                                <?php echo __('Disable flap detection'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="enableServiceNotifications(getObjectsForExternalCommand())"
                                                 ng-show="!servicestatus.notifications_enabled">
                                                <i class="fa fa-envelope"></i>
                                                <?php echo __('Enable notifications'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="disableServiceNotifications(getObjectsForExternalCommand())"
                                                 ng-show="servicestatus.notifications_enabled">
                                                <i class="fa fa-envelope-o"></i>
                                                <?php echo __('Disable notifications'); ?>
                                            </div>

                                            <div class="browser-action margin-top-10"
                                                 ng-click="submitServiceNotification(getObjectsForExternalCommand())">
                                                <i class="fa fa-envelope"></i>
                                                <?php echo __('Send custom service notification '); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div id="tab2" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-xs-12 padding-10">
                                    <div class="row">

                                        <div class="col-xs-12">
                                            <h3 class="margin-top-0"><?php echo __('Service overview'); ?></h3>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Host ip address'); ?></td>
                                                    <td>{{ host.Host.address }}</td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo __('Flap detection enabled'); ?></td>
                                                    <td>
                                                        <span class="label label-danger"
                                                              ng-show="servicestatus.flap_detection_enabled">
                                                            <?php echo __('Yes'); ?>
                                                        </span>

                                                        <span class="label label-success"
                                                              ng-show="!servicestatus.flap_detection_enabled">
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
                                                        <code>{{ mergedService.Service.uuid }}</code>
                                                        <span
                                                                class="btn btn-default btn-xs"
                                                                onclick="$('#service-uuid-copy').show().select();document.execCommand('copy');$('#service-uuid-copy').hide();"
                                                                title="<?php echo __('Copy to clipboard'); ?>">
                                                            <i class="fa fa-copy"></i>
                                                        </span>
                                                        <input type="text" style="display:none;" id="service-uuid-copy"
                                                               value="{{ mergedService.Service.uuid }}"
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12">
                                            <table class="table table-bordered">
                                                <tr ng-show="tags.length">
                                                    <td><?php echo __('Tags'); ?></td>
                                                    <td>
                                                        <span class="label label-primary"
                                                              ng-repeat="tag in tags"
                                                              style="margin-right: 2px;">{{tag}}</span>
                                                    </td>
                                                </tr>

                                                <tr ng-show="mergedService.Service.notes">
                                                    <td><?php echo __('Notes'); ?></td>
                                                    <td>
                                                        {{mergedService.Service.notes}}
                                                    </td>
                                                </tr>
                                                <tr ng-show="mergedService.Service.description">
                                                    <td><?php echo __('Description'); ?></td>
                                                    <td>
                                                        {{mergedService.Service.description}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host'); ?>
                    </div>

                    <div class="widget-footer text-right"></div>
                </div>
            </div>
        </div>
    </article>



    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="mergedService.Service.has_graph">
        <div class="jarviswidget" role="widget">
            <header>
                <div class="widget-toolbar" role="menu">
                    <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                        <i class="fa fa-refresh" ng-class="{'fa-spin': isLoadingGraph}"></i>
                        <?php echo __('Refresh'); ?>
                    </button>
                </div>

                <div class="widget-toolbar" role="menu">
                    <div class="btn-group">
                        <button class="btn btn-xs btn-default" data-toggle="dropdown" >
                            <?php echo __('Select time range'); ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(1)">
                                    <?php echo __('1 hour'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(2)">
                                    <?php echo __('2 hours'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(4)">
                                    <?php echo __('4 hours'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(8)">
                                    <?php echo __('8 hours'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(24)">
                                    <?php echo __('1 day'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(24*2)">
                                    <?php echo __('2 days'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="changeGraphTimespan(24*5)">
                                    <?php echo __('5 days'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="widget-toolbar" role="menu">
                    <div class="btn-group">
                        <button class="btn btn-xs btn-default" data-toggle="dropdown" >
                            <?php echo __('Select data source'); ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li ng-repeat="(dsId, dsName) in dataSources">
                                <a href="javascript:void(0);" ng-click="changeDataSource(dsId)">
                                    {{dsName}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>


                <span class="widget-icon hidden-mobile"> <i class="fa fa-area-chart"></i> </span>
                <h2 class="hidden-mobile"><?php echo __('Service graphs'); ?></h2>
            </header>
            <div>
                <div class="jarviswidget-editbox"></div>
                <div class="widget-body">

                        <div id="graph_data_tooltip"></div>
                        <div id="graphCanvas" style="height: 500px;"></div>

                </div>
            </div>
        </div>
    </article>
</article>



<reschedule-service callback="showFlashMsg"></reschedule-service>
<service-downtime author="<?php echo h($username); ?>" callback="showFlashMsg"></service-downtime>
<mass-delete-service-downtimes delete-url="/downtimes/delete/" callback="showFlashMsg"></mass-delete-service-downtimes>
<acknowledge-service author="<?php echo h($username); ?>" callback="showFlashMsg"></acknowledge-service>
<submit-service-result max-check-attempts="{{mergedService.Service.max_check_attempts}}" callback="showFlashMsg"></submit-service-result>
<enable-service-flap-detection callback="showFlashMsg"></enable-service-flap-detection>
<disable-service-flap-detection callback="showFlashMsg"></disable-service-flap-detection>
<enable-service-flap-detection callback="showFlashMsg"></enable-service-flap-detection>
<disable-service-notifications callback="showFlashMsg"></disable-service-notifications>
<enable-service-notifications callback="showFlashMsg"></enable-service-notifications>
<send-service-notification author="<?php echo h($username); ?>" callback="showFlashMsg"></send-service-notification>

<mass-delete-host-downtimes delete-url="/downtimes/delete/" callback="showFlashMsg"></mass-delete-host-downtimes>


