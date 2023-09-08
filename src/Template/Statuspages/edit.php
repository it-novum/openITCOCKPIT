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
        <a ui-sref="StatuspagesIndex">
            <i class="fas fa-info-circle"></i> <?php echo __('Status pages'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-plus"></i> <?php echo __('Edit'); ?>
    </li>
</ol>

<div class="row">
    <form ng-submit="submit();" class="form-horizontal"
          ng-init="successMessage=
            {objectName : '<?php echo __('Statuspage'); ?>' , message: '<?php echo __('created successfully'); ?>'}">
        <!-- Hosts start -->
        <span ng-if="post.Statuspage.hosts.length > 0">
            <table class="table">
                <thead>
                <tr class="d-flex">
                    <th class="col-5"><?= __('Host name'); ?></th>
                    <th class="col-7"><?= __('Display name'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="host in post.Statuspage.hosts" class="d-flex">
                    <td class="col-5">{{host.name}}</td>
                    <td class="col-7">
                        <input
                                class="form-control"
                                type="text"
                                ng-model="host._joinData.display_alias">
                    </td>
                </tr>
                </tbody>
            </table>
        </span>

        <span ng-if="post.Statuspage.servicess.length > 0">
            <table class="table">
                <thead>
                <tr class="d-flex">
                    <th class="col-5"><?= __('Service name'); ?></th>
                    <th class="col-7"><?= __('Display name'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="service in post.Statuspage.services" class="d-flex">
                    <td class="col-5">{{service.name}}</td>
                    <td class="col-7">
                        <input
                            class="form-control"
                            type="text"
                            ng-model="service._joinData.display_alias">
                    </td>
                </tr>
                </tbody>
            </table>
        </span>
        <div class="form-group">

            <div class="float-right">
                <a back-button href="javascript:void(0);" fallback-state='StatuspagesIndex'
                   class="btn btn-default"><?php echo __('Cancel'); ?>
                </a>
                <?php if ($this->Acl->hasPermission('add', 'statuspages')): ?>
                    <button class="btn btn-primary" type="submit">
                        <?php echo __('Save'); ?>
                    </button>
                <?php endif; ?>
            </div>

        </div>
    </form>

</div>

