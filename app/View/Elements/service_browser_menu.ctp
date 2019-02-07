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
<span
        ng-if="serviceBrowserMenu.isServiceBrowser && canSubmitExternalCommands && mergedService.Service.allowEdit"
        ng-click="reschedule(getObjectsForExternalCommand())"
        class="btn btn-default btn-sm">
<i class="fa fa-refresh fa-lg"></i>
</span>
<?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
    <span ng-if="serviceBrowserMenu.serviceType == 1" style="position:relative;">
        <a ui-sref="DocumentationsView({uuid:serviceBrowserMenu.serviceUuid, type:'service'})"
           data-original-title="<?php echo __('Documentation'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-book fa-lg"></i></a>
        <span ng-show="serviceBrowserMenu.docuExists" class="badge bg-color-green docu-badge"><i
                    class="fa fa-check"></i></span>
    </span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('serviceNotification', 'notifications')): ?>
    <a ui-sref="NotificationsServiceNotification({id:serviceBrowserMenu.serviceId})"
       data-original-title="<?php echo _('Notifications'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-envelope fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('index', 'servicechecks')): ?>
    <a ui-sref="ServicechecksIndex({id:serviceBrowserMenu.serviceId})"
       data-original-title="<?php echo _('Check history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-check-square-o fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('service', 'statehistories')): ?>
    <a ui-sref="StatehistoriesService({id:serviceBrowserMenu.serviceId})"
       data-original-title="<?php echo _('State history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-history fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('service', 'acknowledgements')): ?>
    <a ui-sref="AcknowledgementsService({id:serviceBrowserMenu.serviceId})"
       data-original-title="<?php echo _('Acknowledgement history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-user fa-lg"></i></a>
<?php endif; ?>
<a ng-show="serviceBrowserMenu.serviceUrl" href="{{ serviceBrowserMenu.serviceUrl }}"
   data-original-title="<?php echo _('External link'); ?>" data-placement="bottom"
   rel="tooltip" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-external-link fa-lg"></i></a>
<?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
    <a ng-if="serviceBrowserMenu.allowEdit"
       href="/hosts/edit/{{ serviceBrowserMenu.hostId }}/_controller:services/_action:browser/_id:{{ serviceBrowserMenu.hostId }}/"
       data-original-title="<?php echo _('Edit host'); ?>"
       data-placement="bottom" rel="tooltip" class="btn btn-default btn-sm"><i class="fa fa-cog fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('edit', 'services')): ?>
    <a ng-if="serviceBrowserMenu.allowEdit"
       href="/services/edit/{{ serviceBrowserMenu.serviceId }}/_controller:services/_action:browser/_id:{{ serviceBrowserMenu.serviceId }}/"
       data-original-title="<?php echo _('Edit service'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-cogs fa-lg"></i></a>
<?php endif; ?>

<?php
if ($this->Acl->hasPermission('edit', 'services') && !empty($additionalLinksList)): ?>
    <div class="btn-group">
        <a href="javascript:void(0);" class="btn btn-default btn-sm"><?php echo __('More'); ?></a>
        <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-sm"><span
                    class="caret"></span></a>
        <ul class="dropdown-menu dropdown-menu-right">
            <?php
            echo $this->AdditionalLinks->renderAsListItems(
                $additionalLinksList,
                '{{serviceBrowserMenu.serviceId}}',
                [],
                true,
                'serviceBrowserMenu.allowEdit'
            );
            ?>
        </ul>
    </div>
<?php endif; ?>
