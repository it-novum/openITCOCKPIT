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
<aside id="left-panel">
    <div class="login-info">
        <?php
        if ($this->Auth->user('image') != null && $this->Auth->user('image') != ''):
            if (file_exists(WWW_ROOT . 'userimages' . DS . $this->Auth->user('image'))):
                $img = '/userimages' . DS . $this->Auth->user('image');
            else:
                $img = '/img/fallback_user.png';
            endif;
        else:
            $img = '/img/fallback_user.png';
        endif;
        ?>
        <span>
            <a data-html="true" data-original-title="<?php echo __('Edit profile'); ?>" data-placement="right"
               rel="tooltip" ui-sref="ProfileEdit">
                <img class="online" alt="me" src="<?php echo $img; ?>">
                <span style="max-width: 142px;">
                    <?php echo h($this->Auth->user('full_name')); ?>
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
            phpplugin="<?php echo $this->request->params['plugin']; ?>"
            phpcontroller="<?php echo $this->request->params['controller']; ?>"
            phpaction="<?php echo $this->request->params['action']; ?>">
    </nav>
    <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
