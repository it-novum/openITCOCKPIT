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
<aside id="left-panel">
    <div class="login-info">
        <span>
            <a data-html="true" data-original-title="<?php echo __('Edit profile'); ?>" data-placement="right"
               rel="tooltip" ui-sref="ProfileEdit">
                <img class="online" alt="me" src="<?= h($userImage) ?>">
                <span style="max-width: 142px;">
                    <?= h($userFullName) ?>
              </span>
           </a>
            <?php if ($hasRootPrivileges === true): ?>
                <span class="text-info pull-right" style="margin-top: 11px;">
                    <i class="fa fa-lg fa-trophy"
                       style="color:#FFD700; text-shadow: 0px 0px 9px rgba(255, 255, 0, 0.50)" id="userRootIcon"
                       data-html="true" data-original-title="<?php echo __('Administrator privileges'); ?>"
                       data-placement="right" rel="tooltip"></i>
                </span>
            <?php endif; ?>
        </span>
    </div>
    <nav
            menu
            phpplugin="<?= $this->getRequest()->getParam('plugin', '') ?>"
            phpcontroller="<?= $this->getRequest()->getParam('controller', '') ?>"
            phpaction="<?= $this->getRequest()->getParam('action', '') ?>">
    </nav>
    <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
