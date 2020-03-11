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

class ButtonGroupHelper implements ButtonGroupHelperInterface {

    private $groupElements = [];
    private $buttonGroupAriaLabel = '';

    public function __construct(string $buttonGroupAriaLabel = '') {
        $this->buttonGroupAriaLabel = $buttonGroupAriaLabel;
    }

    private function  createButtonGroupWithContent(): string {
        $html = $this->buttonGroupOpens();

        $html .= $this->iterateOverManuallyAddedElements();

        $html .= $this->buttonGroupCloses();

        return $html;
    }

    private function buttonGroupOpens(): string {
        return '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="' . $this->buttonGroupAriaLabel . '">';
    }

    private function buttonGroupCloses(): string {
        return PHP_EOL . '</div>';
    }

    public function getHtml(): string {
        return $this->createButtonGroupWithContent();
    }

    public function addIconButton(string $iconCssSelector, string $dataOriginalTitle=''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn btn-default" data-original-title="' . $dataOriginalTitle . '" data-placement="bottom" rel="tooltip" data-container="body"><i class="' . $iconCssSelector . '"></i></button>';

        return $this;
    }

    public function addButton(string $innerHtml, string $cssSelector = 'btn-default'): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn ' . $cssSelector . '">' . $innerHtml . '</button>';

        return $this;
    }

    public function addButtonWithTooltip(string $innerHtml, string $cssSelector = 'btn-default', $dataOriginalTitle = ''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn ' . $cssSelector . '" data-original-title="' . $dataOriginalTitle . '" data-placement="bottom" rel="tooltip" data-container="body">' . $innerHtml . '</button>';

        return $this;
    }

    private function iterateOverManuallyAddedElements(): string {
        $html = '';

        foreach ($this->groupElements as $element) {
            $html.= PHP_EOL . $this->indent() . $element;
        }

        return $html;
    }

    public function addButtonWithTogglingMenu(string $iconCssSelector, string $dataOriginalTitle = '', string $htmlMenu = ''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<a href="javascript:void(0);" class="btn btn-default" data-toggle="dropdown" data-original-title="' . $dataOriginalTitle . '"  data-placement="bottom" rel="tooltip"><i class="' . $iconCssSelector . '"></i></a>';
        $this->groupElements[] = $this->attachDropDownMenu($htmlMenu);

        return $this;
    }

    private function attachDropDownMenu(string $html): string {
        return $html;
    }

    public function addButtonWithTooltipAndSRef(string $innerHtml, string $cssSelector = 'btn-default', $dataOriginalTitle = '', $sRef = ''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn ' . $cssSelector . '" data-original-title="' . $dataOriginalTitle . '" data-placement="bottom" rel="tooltip" data-container="body" ui-sref="' . $sRef . '">' . $innerHtml . '</button>';

        return $this;
    }

    public function addIconButtonWithSRef(string $iconCssSelector, string $dataOriginalTitle='', string $sRef = '', string $additionalHtmlAttributes = ''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn btn-default" data-original-title="' . $dataOriginalTitle . '" data-placement="bottom" rel="tooltip" data-container="body" ui-sref="' . $sRef . '" ' . $additionalHtmlAttributes . '><i class="' . $iconCssSelector . '"></i></button>';

        return $this;
    }

    public function addIconButtonWithHRef(string $iconCssSelector, string $dataOriginalTitle='', string $hRef = ''): ButtonGroupHelperInterface {
        $this->groupElements[] = '<button class="btn btn-default" data-original-title="' . $dataOriginalTitle . '" data-placement="bottom" rel="tooltip" data-container="body"><a href="' . $hRef . '"><i class="' . $iconCssSelector . '"></i></a></button>';

        return $this;
    }

    /**
     * @return string
     */
    private function indent(): string {
        return '    ';
    }

}
