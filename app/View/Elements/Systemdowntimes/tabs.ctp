<?php
// Copyright (C) <2015>  <it-novum GmbH>
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
<ul class="nav nav-tabs pull-right" id="widget-tab-1">
    <?php if ($this->Acl->hasPermission('host', 'systemdowntimes')): ?>
        <li class="<?php echo ($this->action === 'host')?'active':''; ?>">
            <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'host']); ?>">
                <i class="fa fa-desktop"></i>
                <span class="hidden-mobile hidden-tablet"> <?php echo __('Host'); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if ($this->Acl->hasPermission('service', 'systemdowntimes')): ?>
        <li class="<?php echo ($this->action === 'service')?'active':''; ?>">
            <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'service']); ?>">
                <i class="fa fa-cog"></i>
                <span class="hidden-mobile hidden-tablet"> <?php echo __('Service'); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if ($this->Acl->hasPermission('hostgroup', 'systemdowntimes')): ?>
        <li class="<?php echo ($this->action === 'hostgroup')?'active':''; ?>">
            <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'hostgroup']); ?>">
                <i class="fa fa-sitemap"></i>
                <span class="hidden-mobile hidden-tablet"> <?php echo __('Hostgroup'); ?></span>
            </a>
        </li>
    <?php endif; ?>
    <?php if ($this->Acl->hasPermission('node', 'systemdowntimes')): ?>
        <li class="<?php echo ($this->action === 'node')?'active':''; ?>">
            <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'node']); ?>">
                <i class="fa fa-chain"></i>
                <span class="hidden-mobile hidden-tablet"> <?php echo __('Container'); ?></span>
            </a>
        </li>
    <?php endif; ?>
</ul>
