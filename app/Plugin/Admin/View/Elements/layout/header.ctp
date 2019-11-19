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
<header id="header">
    <div id="logo-group">
        <span id="logo">
            <div id="logo-image"></div>
            <p id="logo-text"><?php echo $systemname; ?></p>
        </span>

        <?php if ($loggedIn): ?>
            <system-health></system-health>
        <?php endif; ?>
    </div>


    <?php if ($loggedIn): ?>
        <div class="pull-right">
            <div class="btn-header pull-right">
            <span>
                <a href="/login/logout" data-original-title="<?php echo __('Sign out'); ?>" data-placement="left"
                   rel="tooltip" data-container="body">
                    <i class="fa fa-sign-out"></i>
                </a>
            </span>
            </div>

            <div id="hide-menu" class="btn-header pull-right">
            <span>
                <a href="javascript:void(0);" data-original-title="<?php echo __('Collapse menu'); ?>"
                   data-placement="left" rel="tooltip" data-container="body">
                    <i class="fa fa-arrow-circle-left"></i>
                </a>
            </span>
            </div>

            <div class="btn-header pull-right">
                <span>
                    <?php if ($exportRunningHeaderInfo === false): ?>
                        <a href="/exports/index" sudo-server-connect=""
                           data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                           data-placement="left" rel="tooltip" data-container="body">
                            <i class="fa fa-retweet"></i>
                        </a>
                    <?php else: ?>
                        <a href="/exports/index" export-status=""
                           data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                           data-placement="left" rel="tooltip" data-container="body">
                            <i class="fa fa-retweet" ng-hide="exportRunning"></i>
                            <i class="fa fa-refresh fa-spin txt-color-red" ng-show="exportRunning"></i>
                        </a>
                    <?php endif; ?>
                </span>
            </div>
            <server-time></server-time>
            <version-check></version-check>
            <push-notifications></push-notifications>
        </div>
    <?php endif; ?>
</header>