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

namespace App\View\Helper;


interface ButtonGroupHelperInterface {
    public function getHtml(): string;

    public function addIconButton(string $iconCssSelector, string $dataOriginalTitle = ''): ButtonGroupHelperInterface;

    public function addIconButtonWithSRef(string $iconCssSelector, string $dataOriginalTitle = '', string $sRef = '', string $additionalHtmlAttributes = ''): ButtonGroupHelperInterface;

    public function addIconButtonWithHRef(string $iconCssSelector, string $dataOriginalTitle = '', string $hRef = ''): ButtonGroupHelperInterface;

    public function addButton(string $innerHtml, string $cssSelector = 'btn-default'): ButtonGroupHelperInterface;

    public function addButtonWithTooltip(string $innerHtml, string $cssSelector = 'btn-default', $dataOriginalTitle = ''): ButtonGroupHelperInterface;

    public function addButtonWithTogglingMenu(string $iconCssSelector, string $dataOriginalTitle = '', string $htmlMenu = ''): ButtonGroupHelperInterface;

    public function addButtonWithTooltipAndSRef(string $innerHtml, string $cssSelector = 'btn-default', $dataOriginalTitle = '', $href = ''): ButtonGroupHelperInterface;
}
