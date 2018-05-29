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
 * @property \itnovum\openITCOCKPIT\Monitoring\QueryHandler $QueryHandler
 */


?>
<div id="error_msg"></div>

<?php if (!$QueryHandler->exists()): ?>
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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-sidemap fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host Groups'); ?>
            </span>
        </h1>
    </div>
</div>
<div class="row padding-bottom-10" ng-if="hostgroups.length > 0">
    <div class="col col-xs-11">
        <select
                ng-if="hostgroups.length > 0"
                class="form-control"
                chosen="hostgroups"
                ng-options="hostgroup.key as hostgroup.value for hostgroup in hostgroups"
                ng-model="post.Hostgroup.id">
        </select>
    </div>
    <div class="col col-xs-1">
        <div class="btn-group">
            <?php if ($this->Acl->hasPermission('edit')): ?>
                <a href="/hostgroups/edit/{{post.Hostgroup.id}}"
                   class="btn btn-default btn-md">&nbsp;<i class="fa fa-md fa-cog"></i>
                </a>
            <?php else: ?>
                <a href="javascript:void(0);" class="btn btn-default btn-md">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                </a>
            <?php endif; ?>
            <a href="javascript:void(0);" data-toggle="dropdown"
               class="btn btn-default btn-md dropdown-toggle" ng-if="hostgroup.Hosts.length > 0">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" ng-if="hostgroup.Hosts.length > 0">
                <?php if ($this->Acl->hasPermission('edit')): ?>
                    <li>
                        <a href="/hostgroups/edit/{{post.Hostgroup.id}}">
                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_reschedule"
                           ng-click="rescheduleHost(getObjectsForExternalCommand())">
                            <i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_schedule_downtime"
                           ng-click="hostDowntime(getObjectsForExternalCommand())">
                            <i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_ack_state"
                           ng-click="acknowledgeHost(getObjectsForExternalCommand())">
                            <i class="fa fa-user"></i> <?php echo __('Acknowledge host status'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_disable_notifications"
                           ng-click="disableHostNotifications(getObjectsForExternalCommand())">
                            <i class="fa fa-envelope-o"></i> <?php echo __('Disable notification'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_enable_notifications"
                           ng-click="enableHostNotifications(getObjectsForExternalCommand())">
                            <i class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<massdelete></massdelete>
<massdeactivate></massdeactivate>


<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">

                        <button type="button" class="btn btn-xs btn-default" ng-click="loadHostsWithStatus()"
                                ng-if="hostgroup">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <?php echo $this->Html->link(
                                __('New'), '/' . $this->params['controller'] . '/add', [
                                    'class' => 'btn btn-xs btn-success',
                                    'icon'  => 'fa fa-plus'
                                ]
                            ); ?>
                        <?php endif; ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-sidemap"></i> </span>
                    <h2 class="hidden-mobile">
                        {{(hostgroup.Container.name) && hostgroup.Container.name ||
                        '<?php echo __('Host Groups (0)'); ?>'}}
                    </h2>
                    <?php if ($this->Acl->hasPermission('extended')): ?>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li>
                                <a href="/hostgroups/index"><i class="fa fa-minus-square"></i>
                                    <span class="hidden-mobile hidden-tablet"><?php echo __('Default overview'); ?></span></a>
                            </li>
                        </ul>
                        <div class="widget-toolbar cursor-default hidden-xs hidden-sm hidden-md" ng-if="hostgroup">
                            <?php echo __('UUID: '); ?>{{hostgroup.Hostgroup.uuid}}
                        </div>
                    <?php endif; ?>
                </header>
                <div>
                    <table class="table table-striped table-hover table-bordered smart-form" ng-if="hostgroup">
                        <thead>
                        <tr>
                            <td colspan="6">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Filter by host name'); ?>"
                                               ng-model="filter.Host.name"
                                               ng-model-options="{debounce: 500}">
                                    </label>
                                </div>
                            </td>
                            <td colspan="6">
                                <div ng-repeat="(state,stateCount) in hostgroup.StatusSummary"
                                     class="col-md-4 bg-{{state}}">
                                    <div class="padding-5 pull-right">
                                        <label class="checkbox small-checkbox-label txt-color-white">
                                            <input type="checkbox" name="checkbox" checked="checked"
                                                   ng-model-options="{debounce: 500}"
                                                   ng-model="hostgroupsStateFilter[$index]"
                                                   ng-value="$index"
                                                   class="ng-pristine ng-untouched ng-valid ng-empty">
                                            <i class="checkbox-{{state}}"></i>
                                            <strong>
                                                {{stateCount}} {{state}}
                                            </strong>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="width-20 text-center">
                                <?php echo __('Status'); ?>
                            </th>
                            <th class="width-20 text-center"></th>
                            <th class="width-20 text-center">
                                <i class="fa fa-user fa-lg" title="is acknowledged"></i>
                            </th>
                            <th class="width-20 text-center">
                                <i class="fa fa-power-off fa-lg" title="is in downtime"></i>
                            </th>
                            <th class="width-20 text-center">
                                <strong title="<?php echo __('Passively transferred service'); ?>">
                                    <?php echo __('P'); ?>
                                </strong>
                            </th>
                            <th>
                                <?php echo __('Host name'); ?>
                            </th>
                            <th>
                                <?php echo __('State since'); ?>
                            </th>
                            <th>
                                <?php echo __('Last check'); ?>
                            </th>
                            <th>
                                <?php echo __('Next check'); ?>
                            </th>
                            <th>
                                <?php echo __('Service Summary '); ?>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tr ng-show="hostgroup.Hosts.length == 0">
                            <td class="no-padding text-center" colspan="14">
                                <div class="col-xs-12 text-center txt-color-red italic padding-10">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="hostgroupsStateFilter[host.Hoststatus.currentState]"
                            ng-repeat-start="host in hostgroup.Hosts">
                            <td class="width-20 text-center pointer fa-lg">
                                <i ng-class="(!showServices[host.Host.id]) ? 'fa fa-plus-square-o' : 'fa fa-minus-square-o'"
                                   ng-click="showServicesCallback(host.Host.id)"
                                ></i>
                            </td>
                            <td class="text-center">
                                <hoststatusicon host="host"></hoststatusicon>
                            </td>
                            <td class="text-center">
                                <servicecumulatedstatusicon state="host.ServicestatusSummary.cumulatedState">

                                </servicecumulatedstatusicon>
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
                            <td class="text-center">
                                <strong title="<?php echo __('Passively transferred service'); ?>"
                                        ng-show="host.Host.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                    P
                                </strong>
                            </td>
                            <td>
                                <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                    <a href="/hosts/browser/{{ host.Host.id }}">
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
                                <span ng-if="host.Host.active_checks_enabled && host.Host.is_satellite_host === false">{{ host.Hoststatus.lastCheck }}</span>
                                <span ng-if="host.Host.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                            <?php echo __('n/a'); ?>
                                        </span>
                            </td>

                            <td>
                                <span ng-if="host.Host.active_checks_enabled && host.Host.is_satellite_host === false">{{ host.Hoststatus.nextCheck }}</span>
                                <span ng-if="host.Host.active_checks_enabled === false || host.Host.is_satellite_host === true">
                                                <?php echo __('n/a'); ?>
                                            </span>
                            </td>
                            <td class="width-160">
                                <div class="btn-group btn-group-justified" role="group">
                                    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                        <a class="btn btn-success state-button"
                                           ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][0]=1">
                                            {{host.ServicestatusSummary.state['ok']}}
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-success state-button">
                                            {{host.ServicestatusSummary.state['ok']}}
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                        <a class="btn btn-warning state-button"
                                           ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][1]=1">
                                            {{host.ServicestatusSummary.state['warning']}}
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-warning state-button">
                                            {{host.ServicestatusSummary.state['warning']}}
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                        <a class="btn btn-danger state-button"
                                           ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][2]=1">
                                            {{host.ServicestatusSummary.state['critical']}}
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-danger state-button">
                                            {{host.ServicestatusSummary.state['critical']}}
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                                        <a class="btn btn-default state-button"
                                           ng-href="/services/index/?filter[Host.id]={{host.Host.id}}&filter[Servicestatus.current_state][3]=1">
                                            {{host.ServicestatusSummary.state['unknown']}}
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-default state-button">
                                            {{host.ServicestatusSummary.state['unknown']}}
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="width-50">
                                <div class="btn-group">
                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                        <a href="/hosts/edit/{{host.Host.id}}/_controller:hostgroups/_action:extended/_id:{{hostgroup.Hostgroup.id}}/"
                                           ng-if="host.Host.allow_edit"
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
                                        id="menuHack-{{hostgroup.Hostgroup.uuid}}-{{host.Host.uuid}}">
                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                            <li ng-if="host.Host.allow_edit">
                                                <a href="/hosts/edit/{{host.Host.id}}/_controller:hostgroups/_action:extended/_id:{{hostgroup.Hostgroup.id}}/">
                                                    <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('deactivate', 'hosts')): ?>
                                            <li ng-if="host.Host.allow_edit && !host.Host.disabled">
                                                <a href="javascript:void(0);"
                                                   ng-click="confirmDeactivate(getObjectForDelete(host))">
                                                    <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('enable', 'hosts')): ?>
                                            <li ng-if="host.Host.allow_edit && host.Host.disabled">
                                                <a href="javascript:void(0);"
                                                   ng-click="confirmActivate(getObjectForDelete(host))">
                                                    <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                            <li ng-if="service.Service.allow_edit">
                                                <?php echo $this->AdditionalLinks->renderAsListItems(
                                                    $additionalLinksList,
                                                    '{{host.Host.id}}',
                                                    [],
                                                    true
                                                ); ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if ($this->Acl->hasPermission('delete', 'hosts')): ?>
                                            <li class="divider"></li>
                                            <li ng-if="host.Host.allow_edit">
                                                <a href="javascript:void(0);" class="txt-color-red"
                                                   ng-click="confirmDelete(getObjectForDelete(host))">
                                                    <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="showServices[host.Host.id]" ng-repeat-end="">
                            <td colspan="12">
                                <host-service-list
                                        host-id="host.Host.id"
                                        show-services="showServices"
                                        timezone="timezone"
                                        host="host"
                                        ng-if="timezone">
                                </host-service-list>
                            </td>
                        </tr>
                    </table>
                    <div class="col-xs-12 text-center txt-color-red italic padding-10" ng-hide="hostgroup">
                        <?php echo __('No entries match the selection'); ?>
                    </div>
                    <br/>
                </div>
            </div>

            <reschedule-host callback="showFlashMsg"></reschedule-host>
            <disable-host-notifications callback="showFlashMsg"></disable-host-notifications>
            <enable-host-notifications callback="showFlashMsg"></enable-host-notifications>
            <acknowledge-host author="<?php echo h($username); ?>" callback="showFlashMsg"></acknowledge-host>
            <host-downtime author="<?php echo h($username); ?>" callback="showFlashMsg"></host-downtime>
        </article>
    </div>
</section>

