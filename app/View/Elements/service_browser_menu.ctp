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
<?php if ($this->request->params['action'] == 'browser' && $this->request->params['controller'] == 'services'): ?>
    <span
            ng-if="canSubmitExternalCommands && mergedService.Service.allowEdit"
            ng-click="reschedule(getObjectsForExternalCommand())"
            class="btn btn-default btn-sm">
    <i class="fa fa-refresh fa-lg"></i>
</span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('view', 'documentations') && $service['Service']['service_type'] == GENERIC_SERVICE): ?>
    <span style="position:relative;">
        <a href="/documentations/view/<?php echo $service['Service']['uuid']; ?>/service"
           data-original-title="<?php echo __('Documentation'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-book fa-lg"></i></a>
        <?php if ($docuExists === true): ?>
            <span class="badge bg-color-green docu-badge"><i class="fa fa-check"></i></span>
        <?php endif; ?>
    </span>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('serviceNotification', 'notifications')): ?>
    <a href="/notifications/serviceNotification/<?php echo $service['Service']['id']; ?>"
       data-original-title="<?php echo _('Notifications'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-envelope fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('index', 'servicechecks')): ?>
    <a href="/servicechecks/index/<?php echo $service['Service']['id']; ?>"
       data-original-title="<?php echo _('Check history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-check-square-o fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('service', 'statehistories')): ?>
    <a href="/statehistories/service/<?php echo $service['Service']['id']; ?>"
       data-original-title="<?php echo _('State history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-history fa-lg"></i></a>
<?php endif; ?>
<?php if ($this->Acl->hasPermission('service', 'acknowledgements')): ?>
    <a href="/acknowledgements/service/<?php echo $service['Service']['id']; ?>"
       data-original-title="<?php echo _('Acknowledgement history'); ?>" data-placement="bottom" rel="tooltip"
       class="btn btn-default btn-sm"><i class="fa fa-user fa-lg"></i></a>
<?php endif; ?>
<?php if ($service['Service']['service_url'] !== '' && $service['Service']['service_url'] !== null):
    $serviceUrl = $service['Service']['service_url'];

    $ServiceUrlHostMacroReplacer = new \itnovum\openITCOCKPIT\Core\HostMacroReplacer($service);
    $serviceUrl = $ServiceUrlHostMacroReplacer->replaceBasicMacros($serviceUrl);
    $ServiceUrlMacroReplacer = new \itnovum\openITCOCKPIT\Core\ServiceMacroReplacer($service);
    $serviceUrl = $ServiceUrlMacroReplacer->replaceBasicMacros($serviceUrl);
    ?>
    <a href="<?php echo $serviceUrl; ?>" data-original-title="<?php echo _('External link'); ?>" data-placement="bottom"
       rel="tooltip" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-external-link fa-lg"></i></a>
<?php endif; ?>
<?php if ($allowEdit): ?>
    <?php if ($this->Acl->hasPermission('edit', 'hosts')): ?>
        <a href="/hosts/edit/<?php echo $service['Host']['id']; ?>/_controller:services/_action:browser/_id:<?php echo $service['Service']['id']; ?>/"
           data-original-title="<?php echo _('Edit host'); ?>"
           data-placement="bottom" rel="tooltip" class="btn btn-default btn-sm"><i class="fa fa-cog fa-lg"></i></a>
    <?php endif; ?>
    <?php if ($this->Acl->hasPermission('edit', 'services')): ?>
        <a href="/services/edit/<?php echo $service['Service']['id']; ?>/_controller:services/_action:browser/_id:<?php echo $service['Service']['id']; ?>/"
           data-original-title="<?php echo _('Edit service'); ?>" data-placement="bottom" rel="tooltip"
           class="btn btn-default btn-sm"><i class="fa fa-cogs fa-lg"></i></a>
    <?php endif; ?>
<?php endif; ?>

    <div class="btn-group">
        <a href="javascript:void(0);" class="btn btn-default btn-sm"><?php echo __('More'); ?></a>
        <a href="javascript:void(0);" data-toggle="dropdown" class="btn btn-default dropdown-toggle btn-sm"><span
                    class="caret"></span></a>
        <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
                <li>
                    <a href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i
                                class="fa fa-area-chart"></i> <?php echo __('Grapher'); ?></a>
                </li>
            <?php endif; ?>

            <?php if ($this->Acl->hasPermission('edit', 'services')):
                if (!empty($additionalLinksList)):
                    echo '<li class="divider"></li>';
                endif;// @TODO extend additional links mit service object
                if ($service['Service']['name'] === null || $service['Service']['name'] === ''):
                    $service['Service']['name'] = $service['Servicetemplate']['name'];
                endif;
                echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $service['Service']['id'], $service);
            endif; ?>
        </ul>
    </div>

<?php
/* old way:
<a href="/notifications/serviceNotification/<?php echo $service['Service']['id']; ?>" class="btn btn-default btn-sm"><i class="fa fa-envelope "></i> <?php echo $this->Html->underline('n', __('Notifications')); ?></a>
*/
?>