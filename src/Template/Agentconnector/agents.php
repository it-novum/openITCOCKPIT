<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="AgentconnectorsAgent">
            <i class="fa fa-user-secret"></i> <?php echo __('openITCOCKPIT Agent'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <?php echo __('Agents'); ?>
    </li>
</ol>

<massdelete></massdelete>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Agents overview'); ?>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link cursor-pointer {{navSelection == 'untrustedAgents' ? 'active' : ''}}" ng-click="setNavSelection('untrustedAgents')" role="tab">
                                <i class="fa fa-times-circle">&nbsp;</i> <?php echo __('Untrusted agents'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link cursor-pointer {{navSelection == 'pullConfigurations' ? 'active' : ''}}" ng-click="setNavSelection('pullConfigurations')" role="tab">
                                <i class="fa fa-download">&nbsp;</i> <?php echo __('Pull configurations'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link cursor-pointer {{navSelection == 'pushCache' ? 'active' : ''}}" ng-click="setNavSelection('pushCache')" role="tab">
                                <i class="fa fa-upload">&nbsp;</i> <?php echo __('Push Cache'); ?>
                            </a>
                        </li>
                    </ul>

                    <button class="btn btn-xs btn-default mr-1 shadow-0" ng-click="load()">
                        <i class="fas fa-sync"></i> <?php echo __('Refresh'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('config', 'agentconnector')): ?>
                        <button class="btn btn-xs btn-success mr-1 shadow-0" ui-sref="AgentconnectorsConfig">
                            <i class="fas fa-plus"></i> <?php echo __('New'); ?>
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-xs btn-primary shadow-0" ng-click="triggerFilter()">
                        <i class="fas fa-filter"></i> <?php echo __('Filter'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <?php if ($this->Acl->hasPermission('untrustedAgents', 'agentconnector')): ?>
                    <untrusted-agents-directive ng-if="navSelection == 'untrustedAgents'" last-load-date="lastLoadDate" show-filter="showFilter"></untrusted-agents-directive>
                <?php endif; ?>
                <?php if ($this->Acl->hasPermission('pullConfigurations', 'agentconnector')): ?>
                    <pull-configurations-directive ng-if="navSelection == 'pullConfigurations'" last-load-date="lastLoadDate" show-filter="showFilter"></pull-configurations-directive>
                <?php endif; ?>
                <?php if ($this->Acl->hasPermission('pushCache', 'agentconnector')): ?>
                    <push-cache-directive ng-if="navSelection == 'pushCache'" last-load-date="lastLoadDate" show-filter="showFilter"></push-cache-directive>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
