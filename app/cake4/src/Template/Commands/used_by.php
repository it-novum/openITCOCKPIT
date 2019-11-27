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
            <i class="fa fa-terminal fa-fw "></i>
            <?php echo __('Commands'); ?>
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
                        <a back-button fallback-state='CommandsIndex' class="btn btn-default btn-xs">
                            <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
                    <h2><?php echo __('Command'); ?>
                        <strong>
                            »{{ command.name }}«
                        </strong>
                        <?php echo __('is used by'); ?>
                        {{ total }}
                        <?php echo __('objects.'); ?>
                    </h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table id="usedby_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <tbody>
                            <tr ng-if="objects.Contacts.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-user"></i>
                                    <?php echo __('Contacts'); ?> ({{objects.Contacts.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="contact in objects.Contacts">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                        <a ui-sref="ContactsEdit({id: contact.id})" target="_blank">
                                            {{ contact.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ contact.name }}
                                    <?php endif; ?>
                                </td>
                            </tr>


                            <tr ng-if="objects.Hosttemplates.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Host templates'); ?> ({{objects.Hosttemplates.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="hosttemplate in objects.Hosttemplates">
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

                            <tr ng-if="objects.Servicetemplates.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-pencil-square-o"></i>
                                    <?php echo __('Service templates'); ?> ({{objects.Servicetemplates.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="servicetemplate in objects.Servicetemplates">
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

                            <tr ng-if="objects.Hosts.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-desktop"></i>
                                    <?php echo __('Hosts'); ?> ({{objects.Hosts.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="host in objects.Hosts">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                        <a ui-sref="HostsEdit({id:host.id})" target="_blank">
                                            {{ host.name }}
                                        </a>
                                    <?php else: ?>
                                        {{ host.name }}
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr ng-if="objects.Services.length > 0">
                                <th class="bg-color-lightGray">
                                    <i class="fa fa-cog"></i>
                                    <?php echo __('Services'); ?> ({{objects.Services.length}})
                                </th>
                            </tr>
                            <tr ng-repeat="service in objects.Services">
                                <td>
                                    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
                                        <a ui-sref="ServicesEdit({id:service.id})" target="_blank">
                                            {{ service._matchingData.Hosts.name }} / {{ service.servicename }}
                                        </a>
                                    <?php else: ?>
                                        {{ service._matchingData.Hosts.name }} / {{ service.servicename }}
                                    <?php endif; ?>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <div class="noMatch" ng-if="total == 0">
                            <div class="row">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('This command is not used by any object'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
