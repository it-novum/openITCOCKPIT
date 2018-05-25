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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service Groups'); ?>
            </span>
        </h1>
    </div>
</div>
<div class="row padding-bottom-10" ng-if="servicegroups.length > 0">
    <div class="col col-xs-11">
        <select
                ng-if="servicegroups.length > 0"
                class="form-control"
                chosen="servicegroups"
                ng-options="servicegroup.Servicegroup.id as servicegroup.Container.name for servicegroup in servicegroups"
                ng-model="post.Servicegroup.id">
        </select>
    </div>
    <div class="col col-xs-1">
        <div class="btn-group">
            <?php if ($this->Acl->hasPermission('edit')): ?>
                <a href="/servicegroups/edit/{{post.Servicegroup.id}}"
                   class="btn btn-default btn-md">&nbsp;<i class="fa fa-md fa-cog"></i>
                </a>
            <?php else: ?>
                <a href="javascript:void(0);" class="btn btn-default btn-md">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                </a>
            <?php endif; ?>
            <a href="javascript:void(0);" data-toggle="dropdown"
               class="btn btn-default btn-md dropdown-toggle" ng-if="servicegroup.Services.length > 0">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" ng-if="servicegroup.Services.length > 0">
                <?php if ($this->Acl->hasPermission('edit')): ?>
                    <li>
                        <a href="/servicegroups/edit/{{post.Servicegroup.id}}">
                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($this->Acl->hasPermission('externalcommands', 'services')): ?>
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                            data-target="#nag_command_reschedule"
                            ng-click="reschedule(getObjectsForExternalCommand())">
                            <i class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_schedule_downtime"
                           ng-click="serviceDowntime(getObjectsForExternalCommand())">
                            <i class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_ack_state"
                           ng-click="acknowledgeService(getNotOkObjectsForExternalCommand())">
                            <i class="fa fa-user"></i> <?php echo __('Acknowledge service status'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_disable_notifications"
                           ng-click="disableNotifications(getObjectsForExternalCommand())">
                            <i class="fa fa-envelope-o"></i> <?php echo __('Disable notification'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="modal"
                           data-target="#nag_command_enable_notifications"
                           ng-click="enableNotifications(getObjectsForExternalCommand())">
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
<massactivate></massactivate>
<reschedule-service callback="showFlashMsg"></reschedule-service>
<disable-notifications callback="showFlashMsg"></disable-notifications>
<enable-notifications callback="showFlashMsg"></enable-notifications>
<acknowledge-service author="<?php echo h($username); ?>" callback="showFlashMsg"></acknowledge-service>
<service-downtime author="<?php echo h($username); ?>" callback="showFlashMsg"></service-downtime>

<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">

                        <button type="button" class="btn btn-xs btn-default" ng-click="loadServicesWithStatus()" ng-if="servicegroup">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <?php echo $this->Html->link(
                                __('New'), '/' . $this->params['controller'] . '/add', [
                                    'class' => 'btn btn-xs btn-success',
                                    'icon' => 'fa fa-plus'
                                ]
                            ); ?>
                        <?php endif; ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cogs"></i> </span>
                    <h2 class="hidden-mobile">
                        {{(servicegroup.Container.name) && servicegroup.Container.name || '<?php echo __('Service Groups (0)'); ?>'}}
                    </h2>
                    <?php if ($this->Acl->hasPermission('extended')): ?>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li>
                                <a href="/servicegroups/index"><i class="fa fa-minus-square"></i>
                                    <span class="hidden-mobile hidden-tablet"><?php echo __('Default overview'); ?></span></a>
                            </li>
                        </ul>
                        <div class="widget-toolbar cursor-default hidden-xs hidden-sm hidden-md" ng-if="servicegroup">
                            <?php echo __('UUID: '); ?>{{servicegroup.Servicegroup.uuid}}
                        </div>
                    <?php endif; ?>
                </header>
                <div>
                    <table class="table table-striped table-hover table-bordered smart-form" ng-if="servicegroup">
                        <thead>
                            <tr ng-if="servicegroup.Services.length > 0">
                                <td class="no-padding text-right" colspan="13">
                                    <div class="col-md-4">
                                    </div>
                                    <div ng-repeat="(state,stateCount) in servicegroup.StatusSummary"
                                         class="col-md-2 bg-{{state}}">
                                        <div class="padding-5 pull-right">
                                            <label class="checkbox small-checkbox-label txt-color-white">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model-options="{debounce: 500}"
                                                       ng-model="servicegroupsStateFilter[$index]"
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
                                <th>
                                    <?php echo __('Status'); ?>
                                </th>
                                <th class="width-20 text-center">
                                    <i class="fa fa-user fa-lg" title="is acknowledged"></i>
                                </th>
                                <th class="width-20 text-center">
                                    <i class="fa fa-power-off fa-lg" title="is in downtime"></i>
                                </th>
                                <th class="width-20 text-center">
                                    <i class="fa fa fa-area-chart fa-lg" title="Grapher"></i>
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
                                    <?php echo __('Service name'); ?>
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
                                <th class="width-240">
                                    <?php echo __('Output'); ?>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-show="servicegroup.Services.length == 0">
                                <td class="no-padding text-center" colspan="13">
                                    <div class="col-xs-12 text-center txt-color-red italic padding-10">
                                        <?php echo __('No entries match the selection'); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr ng-repeat-end
                                ng-show="servicegroupsStateFilter[service.Servicestatus.currentState] || service.Servicestatus.currentState == null"
                                ng-repeat="service in servicegroup.Services">
                                <td class="text-center">
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
                                        <a href="/services/grapherSwitch/{{ service.Service.id }}" class="txt-color-blueDark">
                                            <i class="fa fa-lg fa-area-chart"
                                               ng-mouseenter="mouseenter($event, service.Host, service)"
                                               ng-mouseleave="mouseleave()"
                                               ng-if="service.Service.has_graph">
                                            </i>
                                        </a>
                                    <?php else: ?>
                                        <i class="fa fa-lg fa-area-chart"
                                           ng-mouseenter="mouseenter($event, service.Host, service)"
                                           ng-mouseleave="mouseleave()"
                                           ng-if="service.Service.has_graph">
                                        </i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <strong title="<?php echo __('Passively transferred service'); ?>"
                                            ng-show="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                        P
                                    </strong>
                                </td>
                                <td class="table-color-{{(service.Hoststatus.currentState !== null)?service.Hoststatus.currentState:'disabled'}}">
                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a href="/hosts/browser/{{ service.Host.id }}">
                                            {{ service.Host.hostname }}
                                        </a>
                                    <?php else: ?>
                                        {{ service.Host.hostname }}
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($this->Acl->hasPermission('browser', 'services')): ?>
                                        <a href="/services/browser/{{ service.Service.id }}">
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
                                    <span ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.lastCheck }}</span>
                                    <span ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                            <?php echo __('n/a'); ?>
                                        </span>
                                </td>

                                <td>
                                    <span ng-if="service.Service.active_checks_enabled && service.Host.is_satellite_host === false">{{ service.Servicestatus.nextCheck }}</span>
                                    <span ng-if="service.Service.active_checks_enabled === false || service.Host.is_satellite_host === true">
                                            <?php echo __('n/a'); ?>
                                        </span>
                                </td>

                                <td>
                                    {{ service.Servicestatus.output }}
                                </td>

                                <td class="width-50">
                                    <div class="btn-group">
                                        <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                            <a href="/services/edit/{{service.Service.id}}/_controller:servicegroups/_action:extended/_id:{{servicegroup.Servicegroup.id}}/"
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
                                            id="menuHack-{{servicegroup.Servicegroup.uuid}}-{{service.Service.uuid}}">
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <li ng-if="service.Service.allow_edit">
                                                    <a href="/services/edit/{{service.Service.id}}/_controller:servicegroups/_action:extended/_id:{{servicegroup.Servicegroup.id}}/">
                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('deactivate', 'services')): ?>
                                                <li ng-if="service.Service.allow_edit && !service.Service.disabled">
                                                    <a href="javascript:void(0);"
                                                       ng-click="confirmDeactivate(getObjectForDelete(service.Host, service))">
                                                        <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('enable', 'services')): ?>
                                                <li ng-if="service.Service.allow_edit && service.Service.disabled">
                                                    <a href="javascript:void(0);"
                                                       ng-click="confirmActivate(getObjectForDelete(service.Host, service))">
                                                        <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                <li ng-if="service.Service.allow_edit">
                                                    <?php echo $this->AdditionalLinks->renderAsListItems(
                                                        $additionalLinksList,
                                                        '{{service.Service.id}}',
                                                        [],
                                                        true
                                                    ); ?>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($this->Acl->hasPermission('delete', 'services')): ?>
                                                <li class="divider"></li>
                                                <li ng-if="service.Service.allow_edit">
                                                    <a href="javascript:void(0);" class="txt-color-red"
                                                       ng-click="confirmDelete(getObjectForDelete(service.Host, service))">
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
                    <div class="col-xs-12 text-center txt-color-red italic padding-10" ng-hide="servicegroup">
                        <?php echo __('No entries match the selection'); ?>
                    </div>
                    <br />
                </div>
            </div>
            <div id="serviceGraphContainer" class="popup-graph-container">
                <div class="text-center padding-top-20 padding-bottom-20" style="width:100%;" ng-show="isLoadingGraph">
                    <i class="fa fa-refresh fa-4x fa-spin"></i>
                </div>
                <div id="serviceGraphFlot"></div>
            </div>
        </article>
    </div>
</section>
