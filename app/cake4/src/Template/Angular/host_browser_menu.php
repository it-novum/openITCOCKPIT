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


<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="status_headline" ng-class="hostStatusTextClass" ng-if="config.includeHoststatus">

            <span class="flapping_airport stateClass" ng-show="hoststatus.isFlapping">
                <i class="fa" ng-class="flappingState === 1 ? 'fa-circle' : 'fa-circle-o'"></i>
                <i class="fa" ng-class="flappingState === 0 ? 'fa-circle' : 'fa-circle-o'"></i>
            </span>

            <i class="fa fa-desktop fa-fw"></i>
            {{ config.hostName }}
            <span>
                ({{ config.hostAddress }})
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6 margin-top-10">

        <div class="pull-right">
            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                <button ng-if="config.showBackButton"
                        ui-sref="HostsBrowser({id:config.hostId})"
                        class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-circle-left"></i> <?php echo __('Back to overview'); ?>
                </button>
            <?php endif; ?>

            <button ng-if="config.showReschedulingButton"
                    title="<?php echo __('Reset check time'); ?>"
                    class="btn btn-default btn-sm"
                    ng-click="config.rescheduleCallback()">
                <i class="fa fa-refresh fa-lg"></i>
            </button>

            <?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
                <div style="display: inline; position:relative;">
                    <a
                            ui-sref="DocumentationsView({uuid:config.hostUuid, type:'host'})"
                            title="<?php echo __('Object documentation'); ?>"
                            class="btn btn-default btn-sm">
                        <i class="fa fa-book fa-lg"></i>
                    </a>

                    <span ng-show="config.docuExists" class="badge bg-up docu-badge">
            <i class="fa fa-check"></i>
        </span>
                </div>
            <?php endif; ?>

            <?php if ($this->Acl->hasPermission('hostNotification', 'notifications')): ?>
                <a ui-sref="NotificationsHostNotification({id:config.hostId})"
                   title="<?php echo __('Notifications'); ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-envelope fa-lg"></i>
                </a>
            <?php endif; ?>

            <?php if ($this->Acl->hasPermission('index', 'hostchecks')): ?>
                <a ui-sref="HostchecksIndex({id:config.hostId})"
                   title="<?php echo __('Check history'); ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-check-square-o fa-lg"></i>
                </a>
            <?php endif; ?>

            <?php if ($this->Acl->hasPermission('host', 'statehistories')): ?>
                <a ui-sref="StatehistoriesHost({id:config.hostId})"
                   title="<?php echo __('State history'); ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-history fa-lg"></i>
                </a>
            <?php endif; ?>

            <?php if ($this->Acl->hasPermission('host', 'acknowledgements')): ?>
                <a ui-sref="AcknowledgementsHost({id:config.hostId})"
                   title="<?php echo __('Acknowledgement history'); ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-user fa-lg"></i>
                </a>
            <?php endif; ?>

            <a ng-show="config.hostUrl" href="{{ config.hostUrl }}"
               title="<?php echo __('External link'); ?>"
               target="_blank"
               class="btn btn-default btn-sm">
                <i class="fa fa-external-link fa-lg">
                </i>
            </a>

            <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                <a ui-sref="HostsEdit({id:config.hostId})"
                   title="<?php echo __('Edit host'); ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-cog fa-lg"></i>
                </a>
            <?php endif; ?>

            <div class="btn-group">
                <a href="javascript:void(0);" class="btn btn-default btn-sm"><?php echo __('More'); ?></a>
                <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-sm">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                        <li>
                            <a ui-sref="ServicesServiceList({id: config.hostId})">
                                <i class="fa fa-list"></i>
                                <?php echo __('Service list'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                        <li>
                            <a ui-sref="ServicetemplategroupsAllocateToHost({id: 0, hostId: config.hostId})">
                                <i class="fa fa-external-link"></i>
                                <?php echo __('Allocate service template group'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                    if ($this->Acl->hasPermission('edit', 'hosts')):
                        if (!empty($additionalLinksList)):
                            echo '<li class="divider"></li>';
                        endif;
                        echo $this->AdditionalLinks->renderAsListItems(
                            $additionalLinksList,
                            '{{config.hostId}}',
                            [],
                            true,
                            'config.allowEdit'
                        );
                    endif;
                    ?>
                </ul>
            </div>

        </div>
    </div>
</div>
