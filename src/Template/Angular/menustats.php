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


$hostsButtonHelper = new ButtonGroupHelper('Display of storage notifications');

if ($this->Acl->hasPermission('index', 'Hosts', '')) {
    $hostsButtonHelper->addIconButtonWithSRef('fa fa-hdd-o', __('host notifications'), "HostsIndex({hoststate: [0,1,2], sort: 'Hoststatus.last_state_change', direction: 'desc'})");
    $hostsButtonHelper->addButtonWithTooltipAndSRef('{{ hoststatusCount[1] }}', 'btn-danger', __('critical messages'), "HostsIndex({hoststate: [1], sort: 'Hoststatus.last_state_change', direction: 'desc'})");
    $hostsButtonHelper->addButtonWithTooltipAndSRef('{{ hoststatusCount[2] }}', 'btn-secondary', __('warning messages'), "HostsIndex({hoststate: [2], sort: 'Hoststatus.last_state_change', direction: 'desc'})");
} else {
    $hostsButtonHelper->addIconButton('fa fa-hdd-o', __('host notifications'));
    $hostsButtonHelper->addButtonWithTooltip('{{ hoststatusCount[1] }}', 'btn-danger', __('critical messages'));
    $hostsButtonHelper->addButtonWithTooltip('{{ hoststatusCount[2] }}', 'btn-secondary', __('warning messages'));
}

$hostsHtml = $hostsButtonHelper->getHtml();
echo $hostsHtml;

$servicesBtnHelper = new ButtonGroupHelper('Display of service notifications');

if ($this->Acl->hasPermission('index', 'services', '')) {
    $servicesBtnHelper->addIconButtonWithSRef('fas fa-cog', __('service notifications'), "ServicesIndex({servicestate: [0,1,2,3], sort: 'Servicestatus.last_state_change', direction: 'desc'})");
    $servicesBtnHelper->addButtonWithTooltipAndSRef('{{ servicestatusCount[2] }}', 'btn-danger', __('messages with filter set to criticals only'), "ServicesIndex({servicestate: [2], sort: 'Servicestatus.last_state_change', direction: 'desc'})");
    $servicesBtnHelper->addButtonWithTooltipAndSRef('{{ servicestatusCount[1] }}', 'btn-warning', __('messages with filter set to warnings only'), "ServicesIndex({servicestate: [1], sort: 'Servicestatus.last_state_change', direction: 'desc'})");
    $servicesBtnHelper->addButtonWithTooltipAndSRef('{{ servicestatusCount[3] }}', 'btn-secondary', __('messages with filter set to unknowns only'), "ServicesIndex({servicestate: [3], sort: 'Servicestatus.last_state_change', direction: 'desc'})");
} else {
    $servicesBtnHelper->addButtonWithTooltip('{{ servicestatusCount[2] }}', 'btn-danger', __('messages with filter set to criticals only'));
    $servicesBtnHelper->addButtonWithTooltip('{{ servicestatusCount[1] }}', 'btn-warning', __('messages with filter set to warnings only'));
    $servicesBtnHelper->addButtonWithTooltip('{{ servicestatusCount[3] }}', 'btn-secondary', __('messages with filter set to unknown only'));
}

$servicesHtml = $servicesBtnHelper->getHtml();
echo $servicesHtml;
