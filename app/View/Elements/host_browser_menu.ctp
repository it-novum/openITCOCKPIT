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
<?php if($this->request->params['action'] == 'browser' && $this->request->params['controller'] == 'hosts'): ?>
<span data-original-title="<?php echo __('Reset check time'); ?>" data-placement="bottom" rel="tooltip"
      class="btn btn-default btn-sm" ng-click="rescheduleHost(getObjectsForExternalCommand())">
    <i class="fa fa-refresh fa-lg"></i>
</span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('view', 'documentations') && $host['Host']['host_type'] == GENERIC_HOST): ?>
    <span style="position:relative;">
        <a href="/documentations/view/<?php echo $host['Host']['uuid']; ?>/host"
           data-original-title="<?php echo __('Documentation'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-book fa-lg"></i></a>
        <?php if ($docuExists === true): ?>
            <span class="badge bg-color-green docu-badge"><i class="fa fa-check"></i></span>
        <?php endif; ?>
    </span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('hostNotification', 'notifications')): ?>
    <a href="/notifications/hostNotification/<?php echo $host['Host']['id']; ?>"
       data-original-title="<?php echo __('Notifications'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-envelope  fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('index', 'hostchecks')): ?>
    <a href="/hostchecks/index/<?php echo $host['Host']['id']; ?>"
       data-original-title="<?php echo __('Check history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-check-square-o fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'statehistories')): ?>
    <a href="/statehistories/host/<?php echo $host['Host']['id']; ?>"
       data-original-title="<?php echo __('State history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-history fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('host', 'acknowledgements')): ?>
    <a href="/acknowledgements/host/<?php echo $host['Host']['id']; ?>"
       data-original-title="<?php echo _('Acknowledgement history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-user fa-lg"></i></a>
<?php endif; ?>
<?php if ($host['Host']['host_url'] !== '' && $host['Host']['host_url'] !== null):
    $HostMacroReplacerMenu = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($host);
    $hostUrl = $HostMacroReplacerMenu->replaceBasicMacros($host['Host']['host_url']);
    ?>
    <a href="<?php echo $hostUrl; ?>" data-original-title="<?php echo __('External link'); ?>" data-placement="bottom"
       rel="tooltip" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-external-link fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
    <a href="/hosts/edit/<?php echo $host['Host']['id']; ?>/_controller:hosts/_action:browser/_id:<?php echo $host['Host']['id']; ?>/" data-original-title="<?php echo __('Edit host'); ?>"
       data-placement="bottom" rel="tooltip" class="btn btn-default btn-sm"><i class="fa fa-cog fa-lg"></i></a>
<?php endif; ?>
<div class="btn-group">
    <a href="javascript:void(0);" class="btn btn-default btn-sm"><?php echo __('More'); ?></a>
    <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-sm"><span
                class="caret"></span></a>
    <ul class="dropdown-menu dropdown-menu-right">
        <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
            <li>
                <a href="/services/serviceList/<?php echo $host['Host']['id']; ?>"><i
                            class="fa fa-list"></i> <?php echo __('Service list'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
            <li>
                <a href="/hosts/allocateServiceTemplateGroup/<?php echo $host['Host']['id']; ?>"><i
                            class="fa fa-external-link"></i> <?php echo __('Allocate Servicetemplategroup'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->params['controller'] == 'hosts' && $this->params['action'] == 'browser'): ?>
            <?php if ($this->Acl->hasPermission('ping')): ?>
                <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#pingmodal" id="pingopen"><i
                                class="fa fa-wifi"></i> <?php echo __('Ping'); ?></a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <?php
        if ($this->Acl->hasPermission('edit') && $allowEdit):
            if (!empty($additionalLinksList)):
                echo '<li class="divider"></li>';
            endif;
            echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $host['Host']['id']);
        endif;
        ?>
    </ul>
</div>
