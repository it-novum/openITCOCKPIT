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

/**
 * @var \App\View\AppView $this
 */

?>
<aside id="pageSidebar" class="page-sidebar">
    <div class="page-logo">
        <a href="javascript:void(0);" class="page-logo-link press-scale-down d-flex align-items-center position-relative"
           data-toggle="modal" data-target="#modal-shortcut">
            <img src="/img/favicons/favicon-32x32.png" alt="logo" aria-roledescription="logo" style="width: 28px;">
            <span class="page-logo-text mr-1"><?php echo $systemname; ?></span>
            <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
            <i class="fa fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
        </a>
    </div>
    <!-- BEGIN PRIMARY NAVIGATION -->
    <nav id="js-primary-nav" class="primary-nav" role="navigation">
        <div class="nav-filter">
            <div class="position-relative">
                <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control"
                       tabindex="0">
                <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                   data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="info-card">
            <img class="profile-image rounded-circle" alt="me" src="<?= h($userImage) ?>">
            <div class="info-card-text">
                <a class="d-flex align-items-center text-white" ui-sref="ProfileEdit">
                    <span class="text-truncate text-truncate-sm d-inline-block">
                        <?= h($userFullName) ?>
                    </span>
                </a>
                <?php if ($hasRootPrivileges === true): ?>
                    <span class="d-inline-block text-truncate text-truncate-sm rootCrown">
                        <i class="fas fa-crown"
                           style="color:#FFD700; text-shadow: 0px 0px 9px rgba(255, 255, 0, 0.50); "
                           id="userRootIcon"
                           data-html="true"
                           data-original-title="<?php echo __('Administrator privileges'); ?>"
                           data-placement="right" rel="tooltip"></i>
                    </span>
                <?php endif; ?>
            </div>
            <img src="/smartadmin4/dist/img/card-backgrounds/cover-6-lg.png" class="cover" alt="cover">
            <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle"
               data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
                <i class="fa fa-angle-down"></i>
            </a>
        </div>

        <ul menu
            id="js-nav-menu"
            class="nav-menu"
            phpplugin="<?= $this->getRequest()->getParam('plugin', '') ?>"
            phpcontroller="<?= $this->getRequest()->getParam('controller', '') ?>"
            phpaction="<?= $this->getRequest()->getParam('action', '') ?>">>
        </ul>
        <div class="filter-message js-filter-message bg-success-600"></div>
    </nav>
    <!-- END PRIMARY NAVIGATION -->
    <!-- NAV FOOTER -->
    <div class="nav-footer shadow-top">
        <a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify"
           class="hidden-md-down">
            <i class="ni ni-chevron-right"></i>
            <i class="ni ni-chevron-right"></i>
        </a>
        <ul class="list-table m-auto nav-footer-buttons">
            <li>
                <a ui-sref="StatisticsIndex" data-toggle="tooltip" data-placement="top" title="<?php echo __('Anonymous statistics'); ?>">
                    <i class="fa fa-line-chart"></i>
                </a>
            </li>
            <li>
                <a ui-sref="SupportsIssue" data-toggle="tooltip" data-placement="top" title="<?php echo __('Report a Bug'); ?>">
                    <i class="fas fa-bug"></i>
                </a>
            </li>
            <li>
                <a href="https://openitcockpit.io/#Subscription" data-toggle="tooltip" data-placement="top" title="<?php echo __('Support'); ?>">
                    <i class="fa fa-life-ring"></i>
                </a>
            </li>
            <li>
                <a ui-sref="DocumentationsWiki" data-toggle="tooltip" data-placement="top" title="<?php echo __('Documentation'); ?>">
                    <i class="fa fa-book"></i>
                </a>
            </li>
        </ul>
    </div> <!-- END NAV FOOTER -->
</aside>
