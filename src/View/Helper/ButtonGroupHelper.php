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

class ButtonGroupHelper {

    private $templateData = [];

    public function __construct($templateData) {
        $this->templateData = $templateData;
    }

    private function clockAsButtonGroup(): string {
        $html = $this->buttonGroupOpens();
        $html .= $this->buttonGroupElementIcon();

        foreach ($this->templateData['GroupElement'] as $element) {
            $html .= $this->buttonElement($element);
        }

        return $html;
    }

    private function buttonGroupOpens(): string {
        return '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="' . $this->templateData['GroupAriaLabel'] . '">';
    }

    private function buttonGroupElementIcon(): string {
        return '<div class="' . $this->templateData['IconCode'] . ' btn btn-default"></div>';
    }

    private function buttonElement($element): string {
        return '<div class="btn btn-secondary"
                data-original-title="' . $element['i18n'] . '"
                data-placement="left"
                rel="tooltip"
                data-container="body">' . $element['innerHTML'] . '</div>';
    }

    public function getView(): void {
        echo $this->clockAsButtonGroup();
    }
}
