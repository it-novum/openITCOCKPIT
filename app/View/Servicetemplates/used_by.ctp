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
            <i class="fa fa-code-fork fa-fw "></i>
            <?php echo __('Service Template'); ?>
            <span>>
                <?php echo __('used by...'); ?>
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
                        <?php echo $this->Utils->backButton(__('Back'), '/servicetemplates/index'); ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
                    <h2><?php echo __('Service Template'); ?>
                        <strong>{{ servicetemplate.Servicetemplate.name
                            }}</strong> <?php echo __('is used by the following'); ?> <?php echo __('Services'); ?>
                        ({{ total }}):</h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table id="service_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i></th>
                                <th class="no-sort"><?php echo __('Service name'); ?></th>
                            </tr>
                            </thead>
                            <tbody ng-show="serverResult">
                            <tr ng-repeat-start="host in services">
                                <td colspan="2" class="service_table_host_header">

                                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                        <a class="padding-left-5 txt-color-blueDark"
                                           href="/hosts/browser/{{host.Host.id}}">
                                            {{host.Host.hostname}} ({{host.Host.address}})
                                        </a>
                                    <?php else: ?>
                                        {{host.Host.hostname}} ({{host.Host.address}})
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                        <a class="pull-right txt-color-blueDark"
                                           href="/services/serviceList/{{host.Host.id}}">
                                            <i class="fa fa-list"
                                               title=" <?php echo __('Go to Service list'); ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr ng-repeat="service in host.Services" ng-repeat-end="">
                                <td class="width-5">
                                    <input type="checkbox"
                                           ng-model="massChange[service.Service.id]"
                                           ng-show="service.Service.allow_edit">
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
                            </tr>
                            </tbody>
                        </table>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="!serverResult">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('This service template is not used by any service'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fa fa-lg fa-check-square-o"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fa fa-lg fa-square-o"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                            </div>
                            <div class="col-xs-12 col-md-2 txt-color-red">
                                <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                    <i class="fa fa-lg fa-trash-o"></i>
                                    <?php echo __('Delete all'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>