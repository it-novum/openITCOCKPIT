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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="ConfigurationFilesIndex">
            <i class="fa fa-file-text"></i> <?php echo __('Configuration file editor'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('Overview'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration file editor'); ?>
                    <span class="fw-300"><i><?php echo __('overview'); ?></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="frame-wrap">
                        <table class="table table-striped m-0 table-bordered table-hover table-sm">
                            <thead>
                            <tr>
                                <th class="no-sort">
                                    <?php echo __('File name'); ?>
                                </th>
                                <th class="no-sort text-center width-25">
                                    <i class="fa fa-cog"></i>
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
                                    <?php if ($this->Acl->hasPermission('edit', 'configurationfiles')): ?>
                                        <a ui-sref="ConfigurationFilesEdit({configfile: '{{configFile.dbKey}}'})"
                                           class="btn btn-default btn-icon btn-sm">
                                            <i class="fa fa-cog"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="javascript:void(0);"
                                           class="btn btn-default btn-lower-padding">
                                            <i class="fa fa-cog"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
