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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-code-fork fa-fw "></i>
            <?php echo __('Contact groups'); ?>
            <span>>
                <?php echo __('Used by...'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php if ($this->Acl->hasPermission('index', 'contactgroups')): ?>
                            <a back-button fallback-state='ContactgroupsIndex' class="btn btn-default btn-xs">
                                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>

                    <h2><?php echo __('Contact group'); ?>
                        <strong>
                            »{{ contactgroupWithRelations.container.name }}«
                        </strong>
                        <?php echo __('is used by'); ?>
                        {{ total }}
                        <?php echo __('objects.'); ?>
                    </h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <tbody>
                            <tr ng-if="contactgroupWithRelations.hosttemplates.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Host template'); ?>
                                    ({{contactgroupWithRelations.hosttemplates.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="hosttemplate in contactgroupWithRelations.hosttemplates">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                        <a ui-sref="HosttemplatesEdit({id: hosttemplate.id})" target="_blank">
                                            {{ hosttemplate.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ hosttemplate.name }}
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr ng-if="contactgroupWithRelations.hosts.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-desktop"></i>
                                    <?php echo __('Host'); ?> ({{contactgroupWithRelations.hosts.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="host in contactgroupWithRelations.hosts">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                        <a ui-sref="HostsEdit({id:host.id})" target="_blank">
                                            {{ host.name }} ({{ host.address }})
                                        </a>
                                    <?php else: ?>
                                        {{ host.name }} ({{ host.address }})
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr ng-if="contactgroupWithRelations.servicetemplates.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Service template'); ?>
                                    ({{contactgroupWithRelations.servicetemplates.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="servicetemplate in contactgroupWithRelations.servicetemplates">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                        <a ui-sref="ServicetemplatesEdit({id: servicetemplate.id})" target="_blank">
                                            {{ servicetemplate.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ servicetemplate.name }}
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr ng-if="contactgroupWithRelations.services.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-cog"></i>
                                    <?php echo __('Service'); ?> ({{contactgroupWithRelations.services.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="service in contactgroupWithRelations.services">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                        <a ui-sref="ServicesEdit({id: service.id})" target="_blank">
                                            {{ service.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ service.name }}
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr ng-if="contactgroupWithRelations.hostescalations.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-bomb"></i>
                                    <?php echo __('Host escalation'); ?>
                                    ({{contactgroupWithRelations.hostescalations.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="hostescalation in contactgroupWithRelations.hostescalations">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hostescalations')): ?>
                                        <a ui-sref="HostescalationsEdit({id: hostescalation.id})" target="_blank">
                                            <?php echo __('Host escalation'); ?> #{{ $index +1 }}
                                        </a>
                                    <?php else: ?>
                                        <?php echo __('Host escalation'); ?> #{{ $index +1 }}
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr ng-if="contactgroupWithRelations.serviceescalations.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-bomb"></i>
                                    <?php echo __('Service escalation'); ?>
                                    ({{contactgroupWithRelations.serviceescalations.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="serviceescalation in contactgroupWithRelations.serviceescalations">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'serviceescalations')): ?>
                                        <a ui-sref="ServiceescalationsEdit({id: serviceescalation.id})" target="_blank">
                                            <?php echo __('Service escalation'); ?> #{{ $index +1 }}
                                        </a>
                                    <?php else: ?>
                                        <?php echo __('Service escalation'); ?> #{{ $index +1 }}
                                    <?php endif; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="noMatch" ng-if="total == 0">
                            <center>
                                <span
                                    class="txt-color-red italic"><?php echo __('This contact group is not used by any object'); ?></span>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
