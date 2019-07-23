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

<!-- Deprecated -->

<span ng-if="hostBrowserMenu.isHostBrowser" data-original-title="<?php echo __('Reset check time'); ?>"
      data-placement="bottom" rel="tooltip"
      class="btn btn-default btn-sm" ng-click="rescheduleHost(getObjectsForExternalCommand())">
    <i class="fa fa-refresh fa-lg"></i>
</span>
<?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
    <span style="position:relative;">
        <a ui-sref="DocumentationsView({uuid:hostBrowserMenu.hostUuid, type:'host'})"
           data-original-title="<?php echo __('Documentation'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-book fa-lg"></i></a>

        <span ng-show="hostBrowserMenu.docuExists" class="badge bg-color-green docu-badge"><i
                    class="fa fa-check"></i></span>
    </span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('hostNotification', 'notifications')): ?>
    <a ui-sref="NotificationsHostNotification({id:hostBrowserMenu.hostId})"
       data-original-title="<?php echo __('Notifications'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-envelope  fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('index', 'hostchecks')): ?>
    <a ui-sref="HostchecksIndex({id:hostBrowserMenu.hostId})"
       data-original-title="<?php echo __('Check history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-check-square-o fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'statehistories')): ?>
    <a ui-sref="StatehistoriesHost({id:hostBrowserMenu.hostId})"
       data-original-title="<?php echo __('State history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-history fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'acknowledgements')): ?>
    <a ui-sref="AcknowledgementsHost({id:hostBrowserMenu.hostId})"
       data-original-title="<?php echo _('Acknowledgement history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-user fa-lg"></i></a>
<?php endif; ?>
<a ng-show="hostBrowserMenu.hostUrl" href="{{ hostBrowserMenu.hostUrl }}"
   data-original-title="<?php echo __('External link'); ?>"
   data-placement="bottom"
   rel="tooltip" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-external-link fa-lg"></i></a>
<?php /*endif;*/ ?>
<?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
    <a ng-if="hostBrowserMenu.allowEdit"
       ui-sref="HostsEdit({id:hostBrowserMenu.hostId})"
       data-original-title="<?php echo __('Edit host'); ?>"
       data-placement="bottom" rel="tooltip" class="btn btn-default btn-sm"><i class="fa fa-cog fa-lg"></i></a>
<?php endif; ?>
<div class="btn-group">
    <a href="javascript:void(0);" class="btn btn-default btn-sm"><?php echo __('More'); ?></a>
    <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-sm"><span
                class="caret"></span></a>
    <ul class="dropdown-menu dropdown-menu-right">
        <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
            <li>
                <a ui-sref="ServicesServiceList({id: hostBrowserMenu.hostId})"><i
                            class="fa fa-list"></i> <?php echo __('Service list'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
            <li>
                <a ui-sref="ServicetemplategroupsAllocateToHost({id: 0, hostId: hostBrowserMenu.hostId})">
                    <i class="fa fa-external-link"></i>
                    <?php echo __('Allocate service template group'); ?>
                </a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('ping', 'hosts')): ?>
            <li ng-if="hostBrowserMenu.isHostBrowser">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#pingmodal" id="pingopen"><i
                            class="fa fa-wifi"></i> <?php echo __('Ping'); ?></a>
            </li>
        <?php endif; ?>
        <?php
        if ($this->Acl->hasPermission('edit', 'hosts')):
            if (!empty($additionalLinksList)):
                echo '<li class="divider"></li>';
            endif;
            echo $this->AdditionalLinks->renderAsListItems(
                $additionalLinksList,
                '{{hostBrowserMenu.hostId}}',
                [],
                true,
                'hostBrowserMenu.allowEdit'
            );
        endif;
        ?>
    </ul>
</div>
