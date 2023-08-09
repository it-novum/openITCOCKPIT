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
        <a ui-sref="UsercontainerrolesIndex">
            <i class="fa fa-users"></i> <?php echo __('User container roles'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-copy"></i> <?php echo __('Copy'); ?>
    </li>
</ol>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('User container role'); ?>
                    <span class="fw-300"><i><?php echo __('Copy User container role/s'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <?php if ($this->Acl->hasPermission('index', 'Usercontainerroles')): ?>
                        <a back-button href="javascript:void(0);" fallback-state='UsercontainerrolesIndex'
                           class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="card margin-bottom-10" ng-repeat="sourceUsercontainerrole in sourceUsercontainerroles">
                        <div class="card-header">
                            <i class="fa fa-cog"></i>
                            <?php echo __('Source user container role:'); ?>
                            {{sourceUsercontainerrole.Source.name}}

                        </div>
                        <div class="card-body">
                            <div class="form-group required" ng-class="{'has-error': sourceUsercontainerrole.Error.name}">
                                <label for="Usercontainerrole{{$index}}Name" class="control-label required">
                                    <?php echo __('User container role name'); ?>
                                </label>
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceUsercontainerrole.Usercontainerrole.name"
                                    id="Usercontainerrole{{$index}}Name">
                                <span class="help-block">
                                <?php echo __('Name of the new user container role'); ?>
                                </span>
                                <div ng-repeat="error in sourceUsercontainerrole.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card margin-top-10">
                        <div class="card-body">
                            <div class="float-right">
                                <button class="btn btn-primary" ng-click="copy()">
                                    <?php echo __('Copy'); ?>
                                </button>
                                <?php if ($this->Acl->hasPermission('index', 'Usercontainerroles')): ?>
                                    <a back-button href="javascript:void(0);" fallback-state='UsercontainerrolesIndex'
                                       class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
