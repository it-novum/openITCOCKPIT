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
<header id="header" class="page-header" role="banner">
    <menu-control></menu-control>
    <div class="search" top-search=""></div>
    <div class="ml-auto d-flex">
        <div class="header-icon padding-left-5">
            <menustats></menustats>
        </div>
        <div class="header-icon">
            <system-health></system-health>
        </div>
        <div class="header-icon">
            <span id="global_ajax_loader" style="display: none;">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">
                        <?= __('Loading...'); ?>
                    </span>
                </div>
            </span>
        </div>

        <div class="header-icon">
            <span>
                <?php if ($exportRunningHeaderInfo === false): ?>
                    <a class="btn btn-default waves-effect waves-themed"
                       ui-sref="ExportsIndex"
                       sudo-server-connect=""
                       data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                       data-placement="left" rel="tooltip" data-container="body">
                        <i class="fa fa-retweet"></i>
                    </a>
                <?php else: ?>
                    <a class="btn btn-default waves-effect waves-themed"
                       ui-sref="ExportsIndex"
                       export-status=""
                       data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                       data-placement="left" rel="tooltip" data-container="body">
                        <i class="fa fa-retweet" ng-hide="exportRunning"></i>
                        <i class="fa fa-refresh fa-spin txt-color-red" ng-show="exportRunning"></i>
                    </a>
                <?php endif; ?>
            </span>
        </div>
        <div class="header-icon padding-left-5">
            <version-check></version-check>
        </div>
        <div class="header-icon">
            <server-time></server-time>
        </div>
        <div class="header-icon">
            <div class="btn-group mr-2" role="group" aria-label="">
                <a class="btn btn-default waves-effect waves-themed" data-original-title="<?= __('Sign out'); ?>"
                   data-placement="bottom" rel="tooltip" data-container="body" href="/users/logout">
                    <i class="fa fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
        <push-notifications></push-notifications>
    </div>
</header>
