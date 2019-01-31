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

<span ng-if="action == 'browser' && controller == 'hosts'" data-original-title="<?php echo __('Reset check time'); ?>"
      data-placement="bottom" rel="tooltip"
      class="btn btn-default btn-sm" ng-click="rescheduleHost(getObjectsForExternalCommand())">
    <i class="fa fa-refresh fa-lg"></i>
</span>
<?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
    <span style="position:relative;">
        <a href="/documentations/view/{{ hostUuid }}/host"
           data-original-title="<?php echo __('Documentation'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-book fa-lg"></i></a>

        <span ng-show="docuExists" class="badge bg-color-green docu-badge"><i class="fa fa-check"></i></span>
    </span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('hostNotification', 'notifications')): ?>
    <a href="/notifications/hostNotification/{{ hostId }}"
       data-original-title="<?php echo __('Notifications'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-envelope  fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('index', 'hostchecks')): ?>
    <a href="/hostchecks/index/{{ hostId }}"
       data-original-title="<?php echo __('Check history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-check-square-o fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'statehistories')): ?>
    <a href="/statehistories/host/{{ hostId }}"
       data-original-title="<?php echo __('State history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-history fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'acknowledgements')): ?>
    <a href="/acknowledgements/host/{{ hostId }}"
       data-original-title="<?php echo _('Acknowledgement history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-user fa-lg"></i></a>
<?php endif; ?>
<?php /*if ($host['Host']['host_url'] !== '' && $host['Host']['host_url'] !== null):
    $HostMacroReplacerMenu = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($host);
    $hostUrl = $HostMacroReplacerMenu->replaceBasicMacros($host['Host']['host_url']);*/
?>
<a ng-show="hostUrl" href="{{ hostUrl }}" data-original-title="<?php echo __('External link'); ?>"
   data-placement="bottom"
   rel="tooltip" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-external-link fa-lg"></i></a>
<?php /*endif;*/ ?>
<?php if ($this->Acl->hasPermission('edit')): ?>
    <a ng-if="allowEdit" href="/hosts/edit/{{ hostId }}/_controller:hosts/_action:browser/_id:{{ hostId }}/"
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
                <a href="/services/serviceList/{{ hostId }}"><i
                            class="fa fa-list"></i> <?php echo __('Service list'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
            <li>
                <a href="/hosts/allocateServiceTemplateGroup/{{ hostId }}"><i
                            class="fa fa-external-link"></i> <?php echo __('Allocate Servicetemplategroup'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('ping')): ?>
            <li ng-if="action == 'browser' && controller == 'hosts'">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#pingmodal" id="pingopen"><i
                            class="fa fa-wifi"></i> <?php echo __('Ping'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('edit')): ?>
            <li ng-if="allowEdit && additionalLinksList.length > 0" class="divider"></li>
        <?php
            echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, "{{hostId}}");
        endif; ?>
    </ul>
</div>
