<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-text-o fa-fw "></i>
            <?php echo __('Configuration file editor') ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-danger fade in">
            <button data-dismiss="alert" class="close">×</button>
            <i class="fa fa-exclamation "></i>
            <strong><?php echo __('Attention!'); ?></strong> <?php echo __("Do not change values, where you don't know what you are doing! This may break the system!"); ?>
        </div>
    </div>

</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <h2>
                        <i class="fa fa-file-text-o"></i>
                        <?php echo __('Configuration files'); ?>
                    </h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="mobile_table">
                            <table id=""
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort">
                                        <?php echo __('File name'); ?>
                                    </th>
                                    <th class="no-sort text-center width-25">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr ng-repeat-start="configFileCategory in configFileCategories">
                                    <td class="service_table_host_header" colspan="2">
                                        {{configFileCategory.name}}
                                    </td>
                                </tr>

                                <tr ng-repeat="configFile in configFileCategory.configFiles" ng-repeat-end="">
                                    <td>{{configFile.linkedOutfile}}</td>

                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit', 'configurationfiles')): ?>
                                                <a ui-sref="ConfigurationFilesEdit({configfile: '{{configFile.dbKey}}'})"
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
                                            <ul class="dropdown-menu pull-right" id="menuHack-{{configFile.dbKey}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'configurationfiles')): ?>
                                                    <li>
                                                        <a ui-sref="ConfigurationFilesEdit({configfile: '{{configFile.dbKey}}'})">
                                                            <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
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
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
