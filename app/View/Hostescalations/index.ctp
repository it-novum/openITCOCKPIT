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
?>
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bomb fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                    <?php echo __('Host Escalations'); ?>
                </span>
        </h1>
    </div>
</div>
<massdelete></massdelete>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>
                        <?php if ($this->Acl->hasPermission('add')): ?>
                            <a ui-sref="HostescalationsAdd" class="btn btn-xs btn-success" icon="fa fa-plus">
                                <i class="fa fa-plus"></i> <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-bomb"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Host Escalations'); ?> </h2>

                </header>
                <div>

                    <!-- widget content -->
                    <div class="widget-body no-padding" ng-init="objectName='<?php echo __('Host escalation #');?>'">
                        <div class="mobile_table" ng-show="hostescalations.length > 0">
                            <table id="hostescalation_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th><?php echo __('Hosts'); ?></th>
                                    <th><?php echo __('Excluded hosts'); ?></th>
                                    <th><?php echo __('Host groups'); ?></th>
                                    <th><?php echo __('Excluded hosts groups'); ?></th>
                                    <th><?php echo __('First'); ?></th>
                                    <th><?php echo __('Last'); ?></th>
                                    <th><?php echo __('Interval'); ?></th>
                                    <th><?php echo __('Timeperiod'); ?></th>
                                    <th><?php echo __('Contacts'); ?></th>
                                    <th><?php echo __('Contact groups'); ?></th>
                                    <th class="no-sort"><?php echo __('Options'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="hostescalation in hostescalations">
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="host in hostescalation.HostescalationHostMembership"
                                                ng-if="host.excluded == 0">
                                                <div class="label-group label-breadcrumb label-breadcrumb-success padding-2">
                                                    <label class="label label-success label-xs">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                        <a href="/hosts/edit/{{host.Host.id}}"
                                                           class="label label-light label-xs">
                                                            {{host.Host.name}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{host.Host.name}}
                                                        </span>
                                                    <?php endif; ?>
                                                    <i ng-if="host.Host.disabled == 1"
                                                       class="fa fa-power-off text-danger"
                                                       title="disabled" aria-hidden="true"></i>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="host in hostescalation.HostescalationHostMembership"
                                                ng-if="host.excluded == 1">
                                                <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2">
                                                    <label class="label label-danger label-xs">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                        <a href="/hosts/edit/{{host.Host.id}}"
                                                           class="label label-light label-xs">
                                                            {{host.Host.name}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{host.Host.name}}
                                                        </span>
                                                    <?php endif; ?>
                                                    <i ng-if="host.Host.disabled == 1"
                                                       class="fa fa-power-off text-danger"
                                                       title="disabled" aria-hidden="true"></i>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="hostgroup in hostescalation.HostescalationHostgroupMembership"
                                                ng-if="hostgroup.excluded == 0">
                                                <div class="label-group label-breadcrumb label-breadcrumb-success padding-2">
                                                    <label class="label label-success label-xs">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                        <a ui-sref="HostgroupsEdit({id: hostgroup.Hostgroup.id})"
                                                           class="label label-light label-xs">
                                                            {{hostgroup.Hostgroup.Container.name}}
                                                        </a>
                                                    <?php else: ?>
                                                    <span class="label label-light label-xs">
                                                        {{hostgroup.Hostgroup.Container.name}}
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="hostgroup in hostescalation.HostescalationHostgroupMembership"
                                                ng-if="hostgroup.excluded == 1">
                                                <div class="label-group label-breadcrumb label-breadcrumb-danger padding-2">
                                                    <label class="label label-danger label-xs">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </label>
                                                    <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                        <a ui-sref="HostgroupsEdit({id: hostgroup.Hostgroup.id})"
                                                           class="label label-light label-xs">
                                                            {{hostgroup.Hostgroup.Container.name}}
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="label label-light label-xs">
                                                            {{hostgroup.Hostgroup.Container.name}}
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        {{ hostescalation.Hostescalation.first_notification }}
                                    </td>
                                    <td>
                                        {{ hostescalation.Hostescalation.last_notification }}
                                    </td>
                                    <td>
                                        {{ hostescalation.Hostescalation.notification_interval }}
                                    </td>
                                    <td>
                                        <?php if ($this->Acl->hasPermission('edit', 'timeperiods')): ?>
                                            <a ui-sref="TimeperiodsEdit({id: hostescalation.Timeperiod.id})">{{
                                                hostescalation.Timeperiod.name }}</a>
                                        <?php else: ?>
                                            {{ hostescalation.Timeperiod.name }}
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="contact in hostescalation.Contact">
                                                <?php if ($this->Acl->hasPermission('edit', 'contacts')): ?>
                                                    <a ui-sref="ContactsEdit({id: contact.id})">
                                                        {{ contact.name }}
                                                    </a>
                                                <?php else: ?>
                                                    {{ contact.name }}
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li ng-repeat="contactgroup in hostescalation.Contactgroup">
                                                <?php if ($this->Acl->hasPermission('edit', 'contactgroups')): ?>
                                                    <a ui-sref="ContactgroupsEdit({id: contactgroup.id})">
                                                        {{ contactgroup.Container.name }}
                                                    </a>
                                                <?php else: ?>
                                                    {{ contactgroup.Container.name }}
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <span class="label-forced label-success margin-right-5"
                                              title="<?php echo __('Recovery'); ?>"
                                              ng-show="hostescalation.Hostescalation.escalate_on_recovery">
                                            <?php echo __('R'); ?>
                                        </span>
                                        <span class="label-forced label-danger margin-right-5"
                                              title="<?php echo __('Down'); ?>"
                                              ng-show="hostescalation.Hostescalation.escalate_on_down">
                                            <?php echo __('D'); ?>
                                        </span>
                                        <span class="label-forced label-default margin-right-5"
                                              title="<?php echo __('Unreachable'); ?>"
                                              ng-show="hostescalation.Hostescalation.escalate_on_unreachable">
                                            <?php echo __('U'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group smart-form">
                                            <?php if ($this->Acl->hasPermission('edit', 'hostescalations')): ?>
                                                <a ui-sref="HostescalationsEdit({id: hostescalation.Hostescalation.id})"
                                                   ng-if="hostescalation.Hostescalation.allowEdit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!hostescalation.Hostescalation.allowEdit"
                                                   class="btn btn-default">
                                                    <i class="fa fa-cog"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{hostescalation.Hostescalation.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'hostescalations')): ?>
                                                    <li ng-if="hostescalation.Hostescalation.allowEdit">
                                                        <a ui-sref="HostescalationsEdit({id:hostescalation.Hostescalation.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('delete', 'hostescalations')): ?>
                                                    <li class="divider"
                                                        ng-if="hostescalation.Hostescalation.allowEdit"></li>
                                                    <li ng-if="hostescalation.Hostescalation.allowEdit">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(hostescalation))">
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
                        <div class="noMatch" ng-hide="hostescalations.length > 0">
                            <center>
                                <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                            </center>
                        </div>

                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>
    </div>
</section>
