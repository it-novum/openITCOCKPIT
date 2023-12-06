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
<!-- Select Users -->
<div class="row">
    <div class="col-lg-12">
        <div class="form-group margin-top-20 padding-bottom-10">
            <label class="control-label">
                <?php echo __('Allocated Users'); ?>
            </label>
            <select
                    data-placeholder="<?php echo __('Please choose'); ?>"
                    class="form-control"
                    chosen="users"
                    ng-options="user.key as user.value for user in users"
                    ng-model="allocation.DashboardTab.AllocatedUsers._ids"
                    multiple="multiple">
            </select>
        </div>
    </div>
</div>

<!-- Select Roles -->
<div class="row">
    <div class="col-lg-12">
        <div class="form-group margin-top-20 padding-bottom-10">
            <label class="control-label">
                <?php echo __('Allocated Roles'); ?>
            </label>
            <select
                    data-placeholder="<?php echo __('Please choose'); ?>"
                    class="form-control"
                    chosen="usergroups"
                    ng-options="usergroup.id as usergroup.name for usergroup in usergroups"
                    ng-model="allocation.DashboardTab.usergroups._ids"
                    multiple="multiple">
            </select>
        </div>
    </div>
</div>

<!-- pinDashboard -->
<div class="row">
    <div class="col-lg-12">
        <div class="form-group margin-top-20 padding-bottom-10">
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                       class="custom-control-input"
                       id="pinDashboard"
                       ng-model="allocation.DashboardTab.flags">
                <label class="custom-control-label" for="pinDashboard">
                    <?php echo __('Pin Dashboard'); ?>
                </label>
            </div>
            <div class="help-block"><?php echo __('If enabled, this dashboard will be pinned at the left most tab.'); ?></div>
        </div>
        <div class="alert alert-warning" role="alert">
            Currently, dashboard <i>Fake 123</i> is set up as pimary. This will be removed now.
        </div>
    </div>
</div>

<button type="button" class="btn btn-success" ng-click="saveAllocation()">
    <?php echo __('Refresh Allocation'); ?>
</button>