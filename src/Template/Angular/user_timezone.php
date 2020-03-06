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

use App\View\Helper\ButtonGroupHelper;

$templateData = [];
$templateData['GroupAriaLabel'] = 'Display of server and client times';
$templateData['IconCode'] = 'fas fa-clock';
$templateData['GroupElement']['server']['class'] = 'btn-primary';
$templateData['GroupElement']['server']['i18n'] = __("Server local time");
$templateData['GroupElement']['server']['innerHTML'] = '{{ currentServerTime }}';
$templateData['GroupElement']['client']['class'] = 'btn-secondary';
$templateData['GroupElement']['client']['i18n'] = __("Client local time");
$templateData['GroupElement']['client']['innerHTML'] = '{{ currentClientTime }}';

$btnHelper = new ButtonGroupHelper($templateData);

$btnHelper->getView();
?>
