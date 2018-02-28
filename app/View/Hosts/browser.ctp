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

$Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([]);

if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="status_headline" ng-class="hostStatusTextClass">
            <hoststatusicon host="hoststateForIcon" ng-if="hoststateForIcon"></hoststatusicon>
            <i class="fa fa-desktop fa-fw"></i>
            {{ mergedHost.Host.name }}
            <span>
                ({{ mergedHost.Host.address }})
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
        <h5>
            <div class="pull-right">
                <?php echo $this->element('host_browser_menu'); ?>
            </div>
        </h5>
    </div>
</div>

<div class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" role="widget">
            <header role="heading">
                <h2 class="hidden-mobile hidden-tablet"><strong><?php echo __('Host'); ?>:</strong> {{
                    mergedHost.Host.name }}</h2>
                <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            <i class="fa fa-lg fa-info"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Status information'); ?></span>
                        </a>
                    </li>
                    <?php /*
                    <li class="">
                        <a href="#tab2" data-toggle="tab">
                            <i class="fa fa-lg fa-hdd-o"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Device information'); ?> </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab3" data-toggle="tab">
                            <i class="fa fa-lg fa-envelope-o"></i>
                            <span class="hidden-mobile hidden-tablet"> <?php echo __('Notification information'); ?> </span>
                        </a>
                    </li>
                    <?php if ($allowEdit): ?>
                        <li class="">
                            <a href="#tab4" data-toggle="tab">
                                <i class="fa fa-lg fa-desktop"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('Host commands'); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host', 'tabLink'); ?>

                    <?php if ($GrafanaDashboardExists): ?>
                        <li class="">
                            <a href="#tab5" data-toggle="tab">
                                <i class="fa fa-lg fa-area-chart"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('Grafana'); ?> </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    */ ?>
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
                            <div class="hidden-sm hidden-md hidden-lg">
                                <div class="row" style="background:#5cb85c;">
                                    <div class="col-xs-6">
                                        <?php echo __('State'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ hoststatus.currentState | hostStatusName }}
                                    </div>
                                </div>
                                <div class="row" style="background:#5cb85c;">
                                    <div class="col-xs-6">
                                        <?php echo __('State since'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ hoststatus.last_state_change }}
                                    </div>
                                </div>
                                <div class="row" style="background:#5cb85c;">
                                    <div class="col-xs-6">
                                        <?php echo __('Last check'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ hoststatus.lastCheck }}
                                    </div>
                                </div>
                                <div class="row" style="background:#5cb85c;">
                                    <div class="col-xs-6">
                                        <?php echo __(' Next check'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ hoststatus.nextCheck }}
                                    </div>
                                </div>
                                <div class="row" style="background:#5cb85c;" ng-show="hoststatus.isHardstate">
                                    <div class="col-xs-6">
                                        <?php echo __('State type'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo __('Hard state'); ?>
                                        ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                    </div>
                                </div>
                                <div class="row" style="background:#5cb85c;" ng-show="!hoststatus.isHardstate">
                                    <div class="col-xs-6">
                                        <?php echo __('State type'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo __('Soft state'); ?>
                                        ({{hoststatus.current_check_attempt}}/{{hoststatus.max_check_attempts}})
                                    </div>
                                </div>

                                <div class="row text-center padding-top-10 padding-bottom-10"
                                     style="background:#5cb85c;">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-power-off"></i>
                                        </button>
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-user"></i>
                                        </button>
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-download"></i>
                                        </button>
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-adjust"></i>
                                        </button>
                                        <button type="button" class="btn btn-default">
                                            <i class="fa fa-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="display: flex;">
                                <div class="col-xs-12 col-sm-6 col-md-7 col-lg-9  padding-10">
                                    <div class="row">

                                        <div class="col-xs-12">
                                            <h3 class="margin-top-0"><?php echo __('Host overview'); ?></h3>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('IP address'); ?></td>
                                                    <td>{{ mergedHost.Host.address }}</td>
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
                                                        <i class="fa fa-fire"
                                                           style="color:#3276B1; font-size:17px;"></i>
                                                        <i class="fa fa-fire" style="color:#CCC; font-size:17px;"></i>
                                                        <i class="fa fa-fire" style="color:#CCC; font-size:17px;"></i>
                                                        <i class="fa fa-fire" style="color:red; font-size:17px;"></i>
                                                        <i class="fa fa-fire" style="color:red; font-size:17px;"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo __('UUID'); ?></td>
                                                    <td>
                                                        <code>{{ mergedHost.Host.uuid }}</code>
                                                        <span
                                                                class="btn btn-default btn-xs"
                                                                onclick="$('#host-uuid-copy').show().select();document.execCommand('copy');$('#host-uuid-copy').hide();"
                                                                title="<?php echo __('Copy to clipboard'); ?>">
                                                            <i class="fa fa-copy"></i>
                                                        </span>
                                                        <input type="text" style="display:none;" id="host-uuid-copy"
                                                               value="{{ mergedHost.Host.uuid }}"
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-xs-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><?php echo __('Container'); ?></td>
                                                    <td>/{{mainContainer}}</td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo __('Shared containers'); ?></td>
                                                    <td>
                                                        <div ng-repeat="sharedContainer in sharedContainers">
                                                            /{{sharedContainer}}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr ng-show="mergedHost.Host.description">
                                                    <td><?php echo __('Description'); ?></td>
                                                    <td>
                                                        {{mergedHost.Host.description}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Status overview'); ?></h3>
                                        </div>
                                    </div>

                                    <div class="row" ng-show="hoststatus.isFlapping">
                                        <div class="col-xs-12">
                                            <div class="alert alert-warning">
                                                <i class="fa-fw fa fa-warning"></i>
                                                <strong><?php echo ('Warning'); ?></strong>
                                                <?php echo __('The state of this host is currently flapping!'); ?>
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
                                                            <a href="/commands/edit/{{ mergedHost.CheckCommand.id }}">
                                                                {{ mergedHost.CheckCommand.name }}
                                                            </a>
                                                        <?php else: ?>
                                                            {{ mergedHost.CheckCommand.name }}
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
                                                        {{ mergedHost.CheckPeriod.name }}
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

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h3 class="margin-top-5"><?php echo __('Notification overview'); ?></h3>
                                        </div>


                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <div class="alert alert-info"
                                                 ng-show="mergedHost.areContactsFromHosttemplate">
                                                <i class="fa-fw fa fa-info"></i>
                                                <strong><?php echo __('Info'); ?>:</strong>
                                                <?php echo __('Contacts got inherit from host template'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <table class="table table-bordered">
                                                <tr ng-show="mergedHost.Contact.length">
                                                    <td><?php echo __('Contacts'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contact in mergedHost.Contact">
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

                                                <tr ng-show="mergedHost.Contactgroup.length">
                                                    <td><?php echo __('Contact groups'); ?></td>
                                                    <td>
                                                        <div ng-repeat="contactgroup in mergedHost.Contactgroup">
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
                                                    <td>{{ mergedHost.NotifyPeriod.name }}</td>
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
                                                              ng-show="mergedHost.Host.notify_on_recovery"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Recover'); ?>
                                                        </span>

                                                        <span class="label label-danger"
                                                              ng-show="mergedHost.Host.notify_on_down"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Down'); ?>
                                                        </span>

                                                        <span class="label label-default"
                                                              ng-show="mergedHost.Host.notify_on_unreachable"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Unreachable'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedHost.Host.notify_on_flapping"
                                                              style="margin-right: 2px;">
                                                            <?php echo __('Flapping'); ?>
                                                        </span>

                                                        <span class="label label-primary"
                                                              ng-show="mergedHost.Host.notify_on_downtime"
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
                                     style="background:#5cb85c;">

                                    <div class="text-center">
                                        <h1 class="font-size-50">
                                            {{ hoststatus.currentState | hostStatusName }}
                                        </h1>
                                    </div>

                                    <div class="text-center">
                                        <div><?php echo __('State since'); ?></div>
                                        <h3 class="margin-top-0">{{ hoststatus.last_state_change }}</h3>
                                    </div>

                                    <div class="text-center">
                                        <div><?php echo __('Last check'); ?></div>
                                        <h3 class="margin-top-0">{{ hoststatus.lastCheck }}</h3>
                                    </div>

                                    <div class="text-center">
                                        <div><?php echo __('Next check'); ?></div>
                                        <h3 class="margin-top-0">
                                            {{ hoststatus.nextCheck }}
                                            <small style="color: #333;"
                                                   ng-show="hoststatus.latency > 1">
                                                (+ {{ hoststatus.latency }})
                                            </small>
                                        </h3>
                                    </div>

                                    <div class="text-center">
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

                                    <div class="browser-action">
                                        <i class="fa fa-refresh"></i>
                                        <?php echo __('Reset check time '); ?>
                                    </div>

                                    <div class="browser-action margin-top-10">
                                        <i class="fa fa-power-off"></i>
                                        <?php echo __('Set planned maintenance times'); ?>
                                    </div>

                                    <div class="browser-action margin-top-10">
                                        <i class="fa fa-user"></i>
                                        <?php echo __('Acknowledge host status'); ?>
                                    </div>

                                    <div class="browser-action margin-top-10">
                                        <i class="fa fa-download"></i>
                                        <?php echo __('Passive transfer check result'); ?>
                                    </div>

                                    <div class="browser-action margin-top-10">
                                        <i class="fa fa-adjust"></i>
                                        <?php echo __('Enables/disables flap detection'); ?>
                                    </div>

                                    <div class="browser-action margin-top-10">
                                        <i class="fa fa-envelope"></i>
                                        <?php echo __('Send custom host notification '); ?>
                                    </div>

                                </div>
                            </div>


                            <?php /*
                            {{ host.hostname }}
                            <strong>
                                <?php echo __('is %s since:', 'Unreachable') ?>

                                <?php echo $this->Time->format(
                                    $Hoststatus->getLastHardStateChange(),
                                    $this->Auth->user('dateformat'),
                                    false,
                                    $this->Auth->user('timezone')
                                ); ?>
                            </strong>
                            <br/><br/>
                            <p><?php echo __('The last system check occurred at'); ?>
                                <strong><?php echo $this->Time->format($Hoststatus->getLastCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></strong>
                                <?php
                                if ($Hoststatus->isHardState()):
                                    echo '<span class="label text-uppercase ' . $Hoststatus->HostStatusBackgroundColor() . '">' . __('hard state') . '</span>';
                                else:
                                    echo '<span class="label text-uppercase opacity-50 ' . $Hoststatus->HostStatusBackgroundColor() . '" >' . __('soft state') . '</span>';
                                endif; ?>
                            </p>

                            <?php if ($Hoststatus->isAcknowledged() && !empty($acknowledged)): ?>
                                <?php $Acknowledgement = new AcknowledgementHost($acknowledged['AcknowledgedHost']); ?>
                                <p>
                                    <span class="fa-stack fa-lg">
                                        <?php if ($Acknowledgement->isSticky()): ?>
                                            <i class="fa fa-user-o fa-stack-2x"></i>
                                        <?php else: ?>
                                            <i class="fa fa-user fa-stack-2x"></i>
                                        <?php endif; ?>
                                        <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-8"></i>
                                    </span>
                                    <?php
                                    if ($Hoststatus->getAcknowledgementType() == 1):
                                        echo __('The current status was acknowledged by');
                                    else:
                                        echo __('The current status was acknowledged (STICKY) by');
                                    endif; ?>
                                    <strong><?php echo h($Acknowledgement->getAuthorName()); ?></strong> (<i
                                            class="fa fa-clock-o"></i>
                                    <?php
                                    echo $this->Time->format($Acknowledgement->getEntryTime(),
                                        $this->Auth->user('dateformat'),
                                        false,
                                        $this->Auth->user('timezone')
                                    );
                                    ?>)
                                    <?php echo __('with the comment '); ?>
                                    "<?php
                                    $ticketDetails = [];
                                    if (!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $Acknowledgement->getCommentData(), $ticketDetails)):
                                        echo (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) ?
                                            $this->Html->link(
                                                $ticketDetails[1] . ' ' . $ticketDetails[2],
                                                $ticketSystem['Systemsetting']['value'] . $ticketDetails[3],
                                                ['target' => '_blank']) :
                                            $Acknowledgement->getCommentData();
                                    else:
                                        echo $this->Bbcode->asHtml(h($Acknowledgement->getCommentData()));
                                    endif;
                                    ?>".

                                </p>
                            <?php endif; ?>

                            <?php
                            if ($Hoststatus->isInDowntime()): ?>
                                <p>
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-power-off fa-stack-2x"></i>
                                            <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
                                        </span>
                                    <?php echo __('The host is currently in a planned maintenance period.'); ?>
                                    <br/><br/>
                                </p>
                            <?php
                            endif;
                            if (!empty($parenthosts)):
                                foreach ($parenthosts as $parenthost):
                                    if (!empty($parentHostStatus[$parenthost['Host']['uuid']])):
                                        $ParentHostStatus = new Hoststatus($parentHostStatus[$parenthost['Host']['uuid']]['Hoststatus']);
                                        ?>
                                        <p class="parentstatus padding-left-10">
                                            <?php echo __('Problem with parent host'); ?> <a
                                                    href="/hosts/browser/<?php echo $parenthost['Host']['id']; ?>"><?php echo h($parenthost['Host']['name']); ?></a> <?php echo __('detected'); ?>
                                            <br/>
                                            <?php $_state = $ParentHostStatus->getHumanHoststatus(); ?>

                                            <?php echo $_state['html_icon']; ?>
                                            <span class="padding-left-5"
                                                  style="vertical-align: middle;"><?php echo $_state['human_state']; ?></span>
                                            <code class="no-background <?php echo $ParentHostStatus->HostStatusColor(); ?>">
                                                (<?php echo h($Hoststatus->getOutput()); ?>)
                                            </code>
                                        </p>
                                        <?php


                                        if ($ParentHostStatus->isInDowntime()): ?>
                                            <p class="parentstatus">
                                                <span class="fa-stack fa-lg">
                                                    <i class="fa fa-power-off fa-stack-2x"></i>
                                                    <i class="fa fa-check fa-stack-1x txt-color-green padding-left-10 padding-top-5"></i>
                                                </span>
                                                <?php
                                                echo __('The parent host'); ?>
                                                <strong>
                                                    <?php echo h($parenthost['Host']['name']); ?>
                                                </strong>
                                                <?php
                                                echo __('is currently in a scheduled downtime'); ?>
                                            </p>
                                        <?php
                                        endif;
                                    endif;
                                endforeach;
                            endif;
                            ?>
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td><strong><?php echo __('Current state'); ?>:</strong></td>
                                    <td>
                                        <?php
                                        echo $Hoststatus->getHumanHoststatus()['html_icon'];
                                        ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Flap detection'); ?>:</strong></td>
                                    <td><?php echo $Hoststatus->compareHostFlapDetectionWithMonitoring($host['Host']['flap_detection_enabled'])['html']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Priority'); ?>:</strong></td>
                                    <td>
                                        <?php if (isset($host['Host']['priority'])): ?>
                                            <?php for ($i = 1; $i < 6; $i++): ?>
                                                <?php if ($i <= $host['Host']['priority']): ?>
                                                    <i class="fa fa-fire" style="color:#3276B1; font-size:17px;"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-fire" style="color:#CCC; font-size:17px;"></i>
                                                <?php endif;
                                            endfor; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!$Hoststatus->isNotificationsEnabled()): ?>
                                    <tr>
                                        <td><strong><?php echo __('Notifications'); ?>:</strong></td>
                                        <td>
                                            <a data-original-title="<?php echo __('Difference to configuration detected'); ?>"
                                               data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i
                                                        class="fa fa-exclamation-triangle txt-color-orange"></i></a>
                                            <span class="label bg-color-redLight"><?php echo __('Temporary off'); ?></span>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr>
                                    <td><strong><?php echo __('Check attempt'); ?>:</strong></td>
                                    <td><?php echo h($Hoststatus->getCurrentCheckAttempts()); ?>
                                        /<?php echo h($host['Host']['max_check_attempts']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Command name'); ?>:</strong></td>
                                    <td>
                                        <?php if ($this->Acl->hasPermission('edit', 'commands')): ?>
                                            <a href="/commands/edit/<?php echo $host['CheckCommand']['id']; ?>"><?php echo h($host['CheckCommand']['name']); ?></a>
                                        <?php else: ?>
                                            <?php echo h($host['CheckCommand']['name']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($this->Acl->hasPermission('checkcommand')): ?>
                                    <tr>
                                        <td><strong><?php echo __('Command line'); ?>:</strong></td>
                                        <?php
                                        $HostMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($host);
                                        $hostCommandLine = $HostMacroReplacerCommandLine->replaceBasicMacros($host['CheckCommand']['command_line']);

                                        $HostCustomMacroReplacerCommandLine = new \itnovum\openITCOCKPIT\Core\CustomMacroReplacer($host['Customvariable'], OBJECT_HOST);
                                        $hostCommandLine = $HostCustomMacroReplacerCommandLine->replaceAllMacros($hostCommandLine);
                                        ?>
                                        <td>
                                            <code class="no-background <?php echo $Hoststatus->HostStatusColor(); ?>">
                                                <?php echo $this->Monitoring->replaceCommandArguments($commandarguments, $hostCommandLine); ?>
                                            </code>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong><?php echo __('Next check in'); ?>:</strong></td>
                                    <td>
                                        <?php if ($host['Host']['active_checks_enabled'] == 1 && $host['Host']['satellite_id'] == 0 && $Hoststatus->isActiveChecksEnabled() !== null): ?>
                                            <?php echo h($this->Time->timeAgoInWords($Hoststatus->getNextCheck(), ['timezone' => $this->Auth->user('timezone')])); ?>
                                            <?php if ($Hoststatus->getLatency() > 1): ?>
                                                <span class="text-muted"
                                                      title="<?php echo __('Check latency'); ?>">
                                                    (+<?php echo $Hoststatus->getLatency(); ?>)
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php
                                            if ($Hoststatus->isInMonitoring() === false):
                                                echo __('Not found in monitoring');
                                            else:
                                                echo __('n/a due to passive check');
                                            endif;
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo __('Output'); ?>:</strong></td>
                                    <td>
                                        <code class="no-background <?php echo $Hoststatus->HostStatusColor(); ?>"><?php echo h($Hoststatus->getOutput()); ?></code>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <dl>
                                <dt><?php echo __('Long output'); ?>:</dt>
                                <?php $long_output = $Hoststatus->getLongOutput() ?>
                                <?php if (!empty($long_output)): ?>
                                    <!-- removing HTML tags, so that we can display a preview witout breaking the page -->
                                    <dd>
                                        <div id="nag_longout_preview"><?php echo $this->Bbcode->nagiosNl2br(substr(strip_tags($this->Bbcode->asHtml($long_output)), 0, 200)); ?>
                                            <a href="javascript:void(0);"
                                               id="nagShowLongOutput"><?php echo __('...read more'); ?></a></div>
                                        <div id="nag_longoutput_container" style="display:none;">
                                            <div id="nag_longoutput_loader">
                                                <span class="text-center">
                                                    <h1>
                                                        <i class="fa fa-cog fa-lg fa-spin"></i>
                                                    </h1>
                                                    <br/>
                                                </span>
                                            </div>
                                            <div id="nag_longoutput_content"><!-- content loaded by ajax --></div>
                                        </div>
                                    </dd>
                                <?php else: ?>
                                    <dd>
                                        <code class="no-background <?php echo $Hoststatus->HostStatusColor(); ?>"><?php echo __('No long output available'); ?></code>
                                    </dd>
                                <?php endif; ?>
                            </dl>
                            */ ?>

                        </div>
                        <div id="tab2" class="tab-pane fade">
                            <strong><?php echo __('Container') ?>:</strong>
                            <?php echo h($mainContainer); ?>
                            <br/>
                            <strong><?php echo __('Shared containers') ?>:</strong>
                            <?php echo empty($sharedContainers) ? '-' : h(implode(', ', $sharedContainers)); ?>
                            <br/>
                            <strong><?php echo __('Check period') ?>
                                :</strong> <?php echo h($host['CheckPeriod']['name']); ?><br/>
                            <strong><?php echo __('Check interval') ?>:</strong>
                            <?php echo $this->Utils->secondsInHumanShort($host['Host']['check_interval']); ?>
                            <br/>
                            <strong><?php echo __('Check interval in case of error'); ?>
                                :</strong> <?php echo $this->Utils->secondsInHumanShort($host['Host']['retry_interval']); ?>
                            <br/>
                            <strong><?php echo __('Active checks enabled') ?>:</strong>
                            <?php if ($host['Host']['active_checks_enabled'] == 1): ?>
                                <i class="fa fa-check text-success"></i>
                            <?php else: ?>
                                <i class="fa fa-times text-danger"></i>
                            <?php endif; ?>
                            <br/>
                            <br/>
                            <strong><?php echo __('UUID') ?>:</strong> <code><?php echo $host['Host']['uuid']; ?></code>&nbsp;
                            <span class="btn btn-default btn-xs" iconcolor="white"
                                  onclick="$('#host-uuid-copy').show().select();document.execCommand('copy');$('#host-uuid-copy').hide();"><?php echo __('Copy to clipboard'); ?></span>
                            <input style="display:none;" type="text" id="host-uuid-copy"
                                   value="<?php echo $host['Host']['uuid']; ?>"><br/>
                            <strong><?php echo __('IP address'); ?>:</strong>
                            <code><?php echo h($host['Host']['address']); ?></code><br/>
                            <?php echo $this->AdditionalLinks->renderElements($additionalElementsForm); ?>
                            <strong><?php echo __('Description'); ?>:</strong><br/>
                            <i class="txt-color-blue"><?php echo h($host['Host']['description']); ?></i>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                            <strong><?php echo __('Notification period'); ?>
                                :</strong> <?php echo h($host['NotifyPeriod']['name']); ?><br/>
                            <strong><?php echo __('Notification interval'); ?>
                                :</strong> <?php echo $this->Utils->secondsInHumanShort($host['Host']['notification_interval']); ?>
                            <br/>
                            <br/>
                            <dl>
                                <dt><?php echo __('Notification occurs in the following cases'); ?>:</dt>
                                <?php echo $this->Monitoring->formatNotifyOnHost([
                                    'notify_on_down'        => $host['Host']['notify_on_down'],
                                    'notify_on_unreachable' => $host['Host']['notify_on_unreachable'],
                                    'notify_on_recovery'    => $host['Host']['notify_on_recovery'],
                                    'notify_on_flapping'    => $host['Host']['notify_on_flapping'],
                                    'notify_on_downtime'    => $host['Host']['notify_on_downtime'],
                                ]); ?>
                            </dl>
                            <?php
                            if ($ContactsInherited['inherit'] === true):
                                if ($this->Acl->hasPermission('edit', 'hosttemplates')):
                                    $source = __('Host') . ' <i class="fa fa-arrow-right"></i> <strong><a href="/hosttemplates/edit/' . $host['Host']['hosttemplate_id'] . '">' . __('Hosttemplate') . '</a></strong>';
                                else:
                                    $source = __('Host') . ' <i class="fa fa-arrow-right"></i> <strong>' . __('Hosttemplate') . '</strong>';
                                endif;
                                ?>
                                <span class="text-info"><i
                                            class="fa fa-info-circle"></i> <?php echo __('Contacts and Contactgroups are inherited in the following order:'); ?> <?php echo $source; ?></span>
                            <?php
                            endif;
                            ?>
                            <?php if (!empty($ContactsInherited['Contact'])): ?>
                                <dl>
                                    <dt><?php echo __('The following contacts are notified'); ?>:</dt>
                                    <dd>
                                        <?php
                                        foreach ($ContactsInherited['Contact'] as $contact_id => $contact):
                                            if ($this->Acl->hasPermission('edit', 'contacts')):
                                                $_contacts[] = '<a href="/contacts/edit/' . $contact_id . '">' . h($contact) . '</a>';
                                            else:
                                                $_contacts[] = h($contact);
                                            endif;
                                        endforeach;
                                        echo implode(', ', $_contacts);
                                        unset($_contacts); ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                            <?php if (!empty($ContactsInherited['Contactgroup'])): ?>
                                <dl>
                                    <dt><?php echo __('The following contact groups are notified'); ?>:</dt>
                                    <dd>
                                        <?php
                                        foreach ($ContactsInherited['Contactgroup'] as $contactgroup_id => $contactgroup):
                                            if ($this->Acl->hasPermission('edit', 'contactgroups')):
                                                $_contactgroups[] = '<a href="/contactgroups/edit/' . $contactgroup_id . '">' . h($contactgroup) . '</a>';
                                            else:
                                                $_contactgroups[] = h($contactgroup);
                                            endif;
                                        endforeach;
                                        echo implode(', ', $_contactgroups);
                                        unset($_contactgroups); ?>
                                    </dd>
                                </dl>
                            <?php endif; ?>
                        </div>
                        <div id="tab4" class="tab-pane fade">
                            <?php if (!$Hoststatus->isInMonitoring()): ?>
                                <div class="alert alert-info alert-block">
                                    <a class="close" data-dismiss="alert" href="#">×</a>
                                    <h4 class="alert-heading"><i
                                                class="fa fa-info-circle"></i> <?php echo __('Host not found in monitoring'); ?>
                                    </h4>
                                    <?php echo __('Due to the host is not available for the monitoring engine, you can not send commands.'); ?>
                                </div>
                            <?php else: ?>
                                <h5><span class="nag_command" data-toggle="modal" data-target="#nag_command_reschedule"><i
                                                class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_passive_result"><i
                                                class="fa fa-download"></i> <?php echo __('Passive transfer of check results') ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_schedule_downtime"><i
                                                class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?> </span><br/>
                                </h5>
                                <?php if ($Hoststatus->currentState() > 0): ?>
                                    <h5><span class="nag_command" data-toggle="modal"
                                              data-target="#nag_command_ack_state"><i
                                                    class="fa fa-user"></i> <?php echo __('Acknowledge host status'); ?> </span><br/>
                                    </h5>
                                <?php endif; ?>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_flap_detection"><i
                                                class="fa fa-adjust"></i> <?php echo __('Enables/disables flap detection for a particular host'); ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_notifications"><i
                                                class="fa fa-envelope-o"></i> <?php echo __('Enables/disables notifications'); ?> </span><br/>
                                </h5>
                                <h5><span class="nag_command" data-toggle="modal"
                                          data-target="#nag_command_custom_notification"><i
                                                class="fa fa-envelope"></i> <?php echo __('Send custom host notification'); ?> </span><br/>
                                </h5>
                            <?php endif; ?>
                        </div>

                        <!-- render additional Tabs if necessary -->
                        <?php echo $this->AdditionalLinks->renderAsTabs($additionalLinksTab, null, 'host'); ?>

                        <?php if ($GrafanaDashboardExists): ?>
                            <div id="tab5" class="tab-pane fade">
                                <iframe src="<?php echo $GrafanaConfiguration->getIframeUrl(); ?>" width="100%"
                                        onload="this.height=(screen.height+15);" frameBorder="0"></iframe>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- end widget body text-->
                    <!-- widget footer -->
                    <div class="widget-footer text-right"></div>
                    <!-- end widget footer -->
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
    </article>
    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
            <div id="wid-id-0" class="jarviswidget jarviswidget-sortable" role="widget">
                <header role="heading">
                    <h2><strong><?php echo __('Services'); ?>:</strong></h2>
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-2">
                        <li class="active">
                            <a href="#tab-monitored" data-toggle="tab"> <i class="fa fa-lg fa-stethoscope"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?></span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab-disabled" data-toggle="tab"> <i class="fa fa-lg fa-plug"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                        </li>
                    </ul>
                    <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
                </header>
                <!-- widget div-->
                <div role="content">
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <!-- widget body text-->
                        <div class="tab-content">
                            <div id="tab-monitored" class="tab-pane fade active in">
                                <div role="content">
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        <!-- This area used as dropdown edit box -->
                                        <input type="text" class="form-control">
                                        <span class="note"><i
                                                    class="fa fa-check text-success"></i> <?php echo __('Change title to update and save instantly!'); ?></span>
                                    </div>
                                    <!-- end widget edit box -->
                                    <!-- widget content -->
                                    <div class="widget-body">
                                        <div class="custom-scroll">
                                            <?php if (!empty($services)): ?>
                                                <table class="table table-bordered table-hover smart-form"
                                                       id="host_browser_service_table">
                                                    <thead>
                                                    <tr>
                                                        <?php $order = $this->Paginator->param('order'); ?>
                                                        <th><?php echo __('Servicestatus'); ?></th>
                                                        <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i>
                                                        </th>
                                                        <th class="text-center"><i class="fa fa-user"
                                                                                   title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><i class="fa fa-power-off"
                                                                                   title="<?php echo __('in Downtime'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><i class="fa fa fa-area-chart fa-lg"
                                                                                   title="<?php echo __('Grapher'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><strong
                                                                    title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                        </th>
                                                        <th><?php echo __('Name'); ?></th>
                                                        <th title="<?php echo __('Hardstate'); ?>"><?php echo __('Last state change'); ?></th>
                                                        <th><?php echo __('Service output'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($services as $service):
                                                        $_servicestatus = [];
                                                        if (isset($servicestatus[$service['Service']['uuid']]['Servicestatus'])) {
                                                            $_servicestatus = $servicestatus[$service['Service']['uuid']]['Servicestatus'];
                                                        }
                                                        $Servicestatus = new Servicestatus(
                                                            $_servicestatus
                                                        );
                                                        $ServicestatusIcon = new ServicestatusIcon($Servicestatus->currentState());
                                                        if ($service['Service']['disabled'] == 0):?>
                                                            <tr>
                                                                <td class="text-center width-90"
                                                                    data-sort="<?php echo $Servicestatus->currentState(); ?>">
                                                                    <?php
                                                                    if ($Servicestatus->isFlapping()):
                                                                        echo $Servicestatus->getServiceFlappingIconColored();
                                                                    else:
                                                                        echo $ServicestatusIcon->getHtmlIcon();
                                                                    endif;
                                                                    ?>
                                                                </td>
                                                                <td class="width-50">
                                                                    <div class="btn-group">
                                                                        <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                                            <a href="<?php echo Router::url([
                                                                                'controller' => 'services',
                                                                                'action'     => 'edit',
                                                                                $service['Service']['id']
                                                                            ]); ?>" class="btn btn-default">&nbsp;<i
                                                                                        class="fa fa-cog"></i>&nbsp;
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a href="javascript:void(0);"
                                                                               class="btn btn-default">&nbsp;<i
                                                                                        class="fa fa-cog"></i>&nbsp;</a>
                                                                        <?php endif; ?>
                                                                        <a href="javascript:void(0);"
                                                                           data-toggle="dropdown"
                                                                           class="btn btn-default dropdown-toggle"><span
                                                                                    class="caret"></span></a>
                                                                        <ul class="dropdown-menu">
                                                                            <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                                                <li>
                                                                                    <a href="<?php echo Router::url([
                                                                                        'controller' => 'services',
                                                                                        'action'     => 'edit',
                                                                                        $service['Service']['id']
                                                                                    ]); ?>">
                                                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                                    </a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                            <?php if ($this->Acl->hasPermission('deactivate', 'services') && $allowEdit): ?>
                                                                                <li>
                                                                                    <a href="javascript:void(0);"
                                                                                       class="triggerWorkaroundConfirmDisable"
                                                                                       data-service-id="<?php echo h($service['Service']['id']); ?>">
                                                                                        <i class="fa fa fa-plug"></i>
                                                                                        <?php echo __('Disable'); ?>
                                                                                    </a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                            <?php if ($this->Acl->hasPermission('delete', 'services') && $allowEdit): ?>
                                                                                <li class="divider"></li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);"
                                                                                       class="txt-color-red triggerWorkaroundConfirmDelete"
                                                                                       data-service-id="<?php echo h($service['Service']['id']); ?>">
                                                                                        <i class="fa fa-trash-o"></i>
                                                                                        <?php echo __('Delete'); ?>
                                                                                    </a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($Servicestatus->isAcknowledged()): ?>
                                                                        <?php if ($Servicestatus->getAcknowledgementType() == 1): ?>
                                                                            <i class="fa fa-user"
                                                                               title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                                        <?php else: ?>
                                                                            <i class="fa fa-user-o"
                                                                               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($Servicestatus->isInDowntime()): ?>
                                                                        <i class="fa fa-power-off"></i>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
                                                                        <a class="txt-color-blueDark"
                                                                           href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i
                                                                                    class="fa fa-area-chart fa-lg popupGraph"
                                                                                    host-uuid="<?php echo $service['Host']['uuid']; ?>"
                                                                                    service-uuid="<?php echo $service['Service']['uuid']; ?>"></i></a>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($Servicestatus->isActiveChecksEnabled() == false || (isset($service['Host']['satellite_id'])) && $service['Host']['satellite_id'] > 0): ?>
                                                                        <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $serviceName = $service['Service']['name'];
                                                                    if ($serviceName === null || $serviceName === ''):
                                                                        $serviceName = $service['Servicetemplate']['name'];
                                                                    endif;
                                                                    ?>
                                                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                                        <a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo h($serviceName); ?></a>
                                                                    <?php else: ?>
                                                                        <?php echo h($serviceName); ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td data-original-title="<?php echo h($this->Time->format($Servicestatus->getLastStateChange(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                                                    data-placement="bottom" rel="tooltip"
                                                                    data-container="body"
                                                                    data-sort="<?php echo $Servicestatus->getLastStateChange(); ?>"
                                                                >
                                                                    <?php echo h($this->Utils->secondsInHumanShort(time() - $Servicestatus->getLastStateChange())); ?>
                                                                </td>
                                                                <td><?php echo h($Servicestatus->getOutput()); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="padding-top-10">
                                                    <center><span
                                                                class="txt-color-red italic"><?php echo __('No services associated with this host!'); ?></span>
                                                    </center>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- end widget content -->
                                </div>
                            </div>
                            <div id="tab-disabled" class="tab-pane fade">
                                <div role="content">
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        <!-- This area used as dropdown edit box -->
                                        <input type="text" class="form-control">
                                        <span class="note"><i
                                                    class="fa fa-check text-success"></i> <?php echo __('Change title to update and save instantly!'); ?></span>
                                    </div>
                                    <!-- end widget edit box -->
                                    <!-- widget content -->
                                    <div class="widget-body">
                                        <div class="custom-scroll">
                                            <?php if (!empty($services)):; ?>
                                                <table class="table table-bordered"
                                                       id="host_browser_disabled_service_table">
                                                    <thead>
                                                    <tr>
                                                        <?php $order = $this->Paginator->param('order'); ?>
                                                        <th><?php echo __('Servicestatus'); ?></th>
                                                        <th class="text-center"><i class="fa fa-user"
                                                                                   title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><i class="fa fa-power-off"
                                                                                   title="<?php echo __('in Downtime'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><i class="fa fa fa-area-chart fa-lg"
                                                                                   title="<?php echo __('Grapher'); ?>"></i>
                                                        </th>
                                                        <th class="text-center"><strong
                                                                    title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                        </th>
                                                        <th><?php echo __('Name'); ?></th>
                                                        <th title="<?php echo __('Hardstate'); ?>"><?php echo __('Last state change'); ?></th>
                                                        <th><?php echo __('Service output'); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $ServicestatusIconDisabled = new ServicestatusIcon(4); ?>
                                                    <?php foreach ($services as $service):
                                                        if ($service['Service']['disabled'] == 1):?>
                                                            <tr>
                                                                <td class="text-center width-90">
                                                                    <?php
                                                                    $href = 'javascript:void(0);';
                                                                    if ($this->Acl->hasPermission('browser')):
                                                                        $href = '/services/browser/' . $service['Service']['id'];
                                                                    endif;
                                                                    echo $ServicestatusIconDisabled->getHtmlIcon();
                                                                    ?>
                                                                </td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center">
                                                                    <?php if ($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
                                                                        <a class="txt-color-blueDark"
                                                                           href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i
                                                                                    class="fa fa-area-chart fa-lg popupGraph"
                                                                                    host-uuid="<?php echo $service['Host']['uuid']; ?>"
                                                                                    service-uuid="<?php echo $service['Service']['uuid']; ?>"></i></a>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($service['Service']['active_checks_enabled'] === '0' || $service['Servicetemplate']['active_checks_enabled'] === '0' || (isset($service['Host']['satellite_id'])) && $service['Host']['satellite_id'] > 0): ?>
                                                                        <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $serviceName = $service['Service']['name'];
                                                                    if ($serviceName === null || $serviceName === ''):
                                                                        $serviceName = $service['Servicetemplate']['name'];
                                                                    endif;
                                                                    ?>
                                                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                                                        <a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo h($serviceName); ?></a>
                                                                    <?php else: ?>
                                                                        <?php echo h($serviceName); ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center"><?php echo __('n/a'); ?>
                                                                </td>
                                                                <td class="text-center"><?php echo __('n/a'); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="padding-top-10">
                                                    <center><span
                                                                class="txt-color-red italic"><?php echo __('No services associated with this host!'); ?></span>
                                                    </center>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- end widget content -->
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- end widget div -->
                </div>
        </article>
    <?php endif; ?>
</div>


<div class="modal fade" id="nag_command_reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Reset check time '); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('nag_command', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('rescheduleHost', ['options' => ['hostOnly' => __('only Host'), 'hostAndServices' => __('Host and Services')], 'label' => __('Host check for') . ':']); ?>
                    <?php echo $this->Form->input('satellite_id', ['type' => 'hidden', 'value' => $host['Host']['satellite_id']]); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitRescheduleHost" data-dismiss="modal">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_passive_result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Passive transfer of check results'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitPassiveResult', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('test alert'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('status', ['options' => [0 => __('Up'), 1 => __('Down'), 2 => __('Unreachable')], 'label' => __('Status') . ':']); ?>
                    <?php echo $this->Form->fancyCheckbox('forceHardstate', ['caption' => __('Force to hard state?'), 'on' => __('true'), 'off' => __('false')]); ?>
                    <?php echo $this->Form->input('repetitions', ['type' => 'hidden', 'value' => $host['Host']['max_check_attempts']]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitCommitPassiveResult">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="txt-color-red padding-bottom-20" id="validationErrorHostDowntime" style="display:none;">
                        <i class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?></div>
                    <?php
                    echo $this->Form->create('CommitHostDowntime', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php
                    $hostdowntimetyps = [
                        0 => __('Individual host'),
                        1 => __('Host including services'),
                        2 => __('Hosts and dependent Hosts (triggered)'),
                        3 => __('Hosts and dependent Hosts (non-triggered)'),
                    ];
                    ?>
                    <?php echo $this->Form->input('type', ['options' => $hostdowntimetyps, 'selected' => $preselectedDowntimetype, 'label' => __('Maintenance period for') . ':']) ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitHostDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitHostDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostDowntimeFromTime" value="<?php echo date('H:i'); ?>"
                                   class="form-control" name="data[CommitHostDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="CommitHostDowntimeToDate"><?php echo __('To'); ?>
                            :</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostDowntimeToDate"
                                   value="<?php echo date('d.m.Y'); ?>" class="form-control"
                                   name="data[CommitHostDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostDowntimeToTime"
                                   value="<?php echo date('H:i', time() + (60 * 15)); ?>"
                                   class="form-control" name="data[CommitHostDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <a href="<?php echo Router::url([
                    'controller' => 'systemdowntimes',
                    'action'     => 'addHostdowntime',
                    $host['Host']['id']
                ]); ?>"
                   class="btn btn-primary pull-left"><i class="fa fa-cogs"></i> <?php echo __('More options'); ?></a>
                <button type="button" class="btn btn-success" id="submitCommitHostDowntime">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_flap_detection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Enables/disables flap detection'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableOrDisableHostFlapdetection', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php if ($Hoststatus->compareHostFlapDetectionWithMonitoring($host['Host']['flap_detection_enabled'])['value'] == 0): ?>
                                <?php echo __('Yes, i want temporarily <strong>enable</strong> flap detection.'); ?>
                            <?php else: ?>
                                <?php echo __('Yes, i want temporarily <strong>disable</strong> flap detection.'); ?>
                            <?php endif; ?>
                            <?php echo $this->Form->input('condition', ['type' => 'hidden', 'value' => ($Hoststatus->compareHostFlapDetectionWithMonitoring($host['Host']['flap_detection_enabled'])['value'] == 1) ? 0 : 1]); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"
                        id="submitEnableOrDisableHostFlapdetection">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="nag_command_custom_notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Send custom host notification'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitCustomHostNotification', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('test notification'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->fancyCheckbox('forced', ['caption' => __('Forced'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
                    <?php echo $this->Form->fancyCheckbox('broadcast', ['caption' => __('Broadcast'), 'on' => __('true'), 'off' => __('false'), 'checked' => 'checked']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitCustomHostNotification">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge host status'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitHoststateAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only host'), 'hostAndServices' => __('Host including services')], 'label' => 'Acknowledge for']); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" data-dismiss="modal" id="submitHoststateAck">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('isEnabled', ['type' => 'hidden', 'value' => (int)$Hoststatus->isNotificationsEnabled()]); ?>
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only host'), 'hostAndServices' => __('Host including services')], 'label' => 'Notifications']); ?>
                    <center>
                        <span class="hintmark">
                            <?php
                            if ($Hoststatus->isNotificationsEnabled() == false):
                                echo __('Yes, i want temporarily <strong>enable</strong> notifications.');
                            else:
                                echo __('Yes, i want temporarily <strong>disable</strong> notifications.');
                            endif;
                            ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="pingmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Ping remote host'); ?></h4>
            </div>
            <div class="modal-body">
                <button type="button" id="runPing" target="<?php echo h($host['Host']['address']); ?>"
                        class="btn btn-primary text-center"
                        style="width: 100%;"><?php echo __('Execute ping to:') . ' ' . h($host['Host']['address']); ?></button>
                <br/>
                <br/>
                <div id="console" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('qrmodal'); ?>

<div id="workaroundConfirmDelete" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-color-danger txt-color-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Attention!'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want delete this service?'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="errorOnDelete"></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="successDelete" style="display:none;">
                        <div class="alert auto-hide alert-success">
                            <?php echo __('Service deleted successfully'); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="yesDeleteService">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>

<div id="workaroundConfirmDisable" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-color-orange txt-color-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Attention!'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want disable this service?'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="errorOnDisable"></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 margin-top-10" id="successDisabled" style="display:none;">
                        <div class="alert auto-hide alert-success">
                            <?php echo __('Service disabled successfully'); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="yesDisableService">
                    <?php echo __('Disable'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>

