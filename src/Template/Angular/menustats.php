<?php
// Copyright (C) <2020>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, version 3 of the License.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//    License agreement and license key will be shipped with the order
//    confirmation.
?>
<?php

use App\View\Helper\ButtonGroupHelper;

$templateData = [];
$templateData['GroupAriaLabel'] = 'Display of storage notifications';

//    $templateData['GroupElement']['critical']['class'] = 'btn-danger';
//    $templateData['GroupElement']['critical']['data-original-title'] = __('critical messages');
//    $templateData['GroupElement']['critical']['innerHTML'] = '{{ hoststatusCount[1] }}';
//    $templateData['GroupElement']['warning']['class'] = 'btn-secondary';
//    $templateData['GroupElement']['warning']['data-original-title'] = __('warning messages');
//    $templateData['GroupElement']['warning']['innerHTML'] = '{{ hoststatusCount[2] }}';


$btnHelper = new ButtonGroupHelper($templateData);
$btnHelper->addIconButton('fa fa-hdd-o', __('host notifications'));
if ($this->Acl->hasPermission('index', 'Hosts', '')) {
    $btnHelper->addButtonWithDataAndSRef('{{ hoststatusCount[1] }}', 'btn-danger', __('critical messages'), "HostsIndex({hoststate: [1], sort: 'Hoststatus.last_state_change', direction: 'desc'})");
    $btnHelper->addButtonWithDataAndSRef('{{ hoststatusCount[2] }}', 'btn-secondary', __('warning messages'), "HostsIndex({hoststate: [2], sort: 'Hoststatus.last_state_change', direction: 'desc'})");
} else {
    $btnHelper->addButtonWithData('{{ hoststatusCount[1] }}', 'btn-danger', __('critical messages'));
    $btnHelper->addButtonWithData('{{ hoststatusCount[2] }}', 'btn-secondary', __('warning messages'));
}

$html = $btnHelper->getHtml();
echo $html;

$templateData = [];
$templateData['GroupAriaLabel'] = 'Display of service notifications';
$templateData['GroupElement']['icon']['class'] = 'btn btn-default';
$templateData['GroupElement']['icon']['data-original-title'] = __('service notifications');
$templateData['GroupElement']['icon']['innerHTML'] = '<i class="fas fa-cog"></i>';
$templateData['GroupElement']['critical']['class'] = 'btn-danger';
$templateData['GroupElement']['critical']['data-original-title'] = __('messages with filter set to criticals only');
$templateData['GroupElement']['critical']['innerHTML'] = "{{ servicestatusCount['2'] }}";
$templateData['GroupElement']['warning']['class'] = 'btn-warning';
$templateData['GroupElement']['warning']['data-original-title'] = __('messages with filter set to warings only');
$templateData['GroupElement']['warning']['innerHTML'] = "{{ servicestatusCount[1] }}";
$templateData['GroupElement']['unknown']['class'] = 'btn-secondary';
$templateData['GroupElement']['unknown']['data-original-title'] = __('messages with filter set to unknowns only');
$templateData['GroupElement']['unknown']['innerHTML'] = "{{ servicestatusCount[3] }}";

if ($this->Acl->hasPermission('index', 'services', '')) {
    $templateData['GroupElement']['icon']['ui-sref'] = 'ui-sref="ServicesIndex({sort: \'Servicestatus.last_state_change\', direction: \'desc\'})"';
    $templateData['GroupElement']['critical']['ui-sref'] = 'ui-sref="ServicesIndex({servicestate: [2], sort: \'Servicestatus.last_state_change\', direction: \'desc\'})"';
    $templateData['GroupElement']['warning']['ui-sref'] = 'ui-sref="ServicesIndex({servicestate: [1], sort: \'Servicestatus.last_state_change\', direction: \'desc\'})"';
    $templateData['GroupElement']['unknown']['ui-sref'] = 'ui-sref="ServicesIndex({servicestate: [3], sort: \'Servicestatus.last_state_change\', direction: \'desc\'})"';
}

$btnHelper = new ButtonGroupHelper($templateData);
$btnHelper->addIconButton('fas fa-cog',__('service notifications'));
$html = $btnHelper->getHtml();

echo $html;
?>
<!--<div class="btn-toolbar header-icon" role="toolbar" style="padding-right: 25px;">-->

<!--        <div class="btn-group btn-group-xs mr-2" role="group">-->
<!--            --><?php //if ($this->Acl->hasPermission('index', 'services', '')): ?>
<!--                <button class="btn btn-default"-->
<!--                        ui-sref="ServicesIndex({sort: 'Servicestatus.last_state_change', direction: 'desc'})">-->
<!--                    <i class="fa fa-cog fa-lg"></i>-->
<!--                </button>-->
<!--                <button class="btn btn-warning"-->
<!--                        ui-sref="ServicesIndex({servicestate: [1], sort: 'Servicestatus.last_state_change', direction: 'desc'})">-->
<!--                    {{ servicestatusCount['1'] }}-->
<!--                </button>-->
<!--                <button class="btn btn-danger"-->
<!--                        ui-sref="ServicesIndex({servicestate: [2], sort: 'Servicestatus.last_state_change', direction: 'desc'})">-->
<!--                    {{ servicestatusCount['2'] }}-->
<!--                </button>-->
<!--                <button class="btn btn-secondary"-->
<!--                        ui-sref="ServicesIndex({servicestate: [3], sort: 'Servicestatus.last_state_change', direction: 'desc'})">-->
<!--                    {{ servicestatusCount['3'] }}-->
<!--                </button>-->
<!--            --><?php //else: ?>
<!--                <button class="btn btn-default">-->
<!--                    <i class="fa fa-cog fa-lg"></i>-->
<!--                </button>-->
<!--                <button class="btn btn-warning">-->
<!--                    {{ servicestatusCount['1'] }}-->
<!--                </button>-->
<!--                <button class="btn btn-danger">-->
<!--                    {{ servicestatusCount['2'] }}-->
<!--                </button>-->
<!--                <button class="btn btn-secondary">-->
<!--                    {{ servicestatusCount['3'] }}-->
<!--                </button>-->
<!--            --><?php //endif; ?>
<!--        </div>-->
<!--</div>-->
