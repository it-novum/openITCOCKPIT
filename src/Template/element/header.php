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
<?php

use App\View\Helper\ButtonGroupHelper;

class OitcHeader {

    public function __construct($exportRunningHeaderInfo, $showstatsinmenu) {
        $this->menuHeaderOpens();

        $this->menuControl();
        $this->menuHosts();
        $this->menuSearchContentByAngularJsDirective();
        $this->menuStatistics($exportRunningHeaderInfo, $showstatsinmenu);

        $this->menuHeaderCloses();
    }

private function menuControl() {
    $html = '<div class="hidden-md-down dropdown-icon-menu position-relative">
        <a href="#" class="header-btn btn js-waves-off" data-action="toggle"
           data-class="nav-function-hidden" title="Hide Navigation">
            <i class="fas fa-bars"></i>
        </a>
        <ul>
            <li>
                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify"
                   title="Minify Navigation">
                    <i class="far fa-caret-square-left"></i>
                </a>
            </li>
            <li>
                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed"
                   title="Lock Navigation">
                    <i class="fas fa-lock"></i>
                </a>
            </li>
        </ul>
        <div class="hidden-lg-up">
            <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
            <i class="fas fa-bars"></i>
            </a>
        </div>
    </div>';

    echo $html;
}

private function menuHosts(): string {
    return '<div>Hosts</div>';
}

private function menuSearchContentByAngularJsDirective(): string {
    return '<div class="search" top-search=""></div>';
}

private function translate($singular) {
    return __($singular);
}

/**
 * @param bool $exportRunningHeaderInfo
 * @return string
 */
private function displayExportRunningNotificationIfEnabledInSettings($exportRunningHeaderInfo): string {
    if ($exportRunningHeaderInfo === false) {
        return
            '<a ui-sref="ExportsIndex" sudo-server-connect=""
                data-original-title="' . $this->translate('Refresh monitoring configuration') . '"
                data-placement="left" rel="tooltip" data-container="body" class="header-icon">
                    <i class="fa fa-retweet"></i>
             </a>';
    } else {
        return
            '<a ui-sref="ExportsIndex" export-status=""
                data-original-title="' . $this->translate('Refresh monitoring configuration') . '"
                data-placement="left" rel="tooltip" data-container="body" class="header-icon">
                    <i class="fa fa-retweet" ng-if="!exportRunning"></i>
                    <i class="fa fa-refresh fa-spin txt-color-red" ng-if="exportRunning"></i>
             </a>';
    }
}

private function displayMenuStatisticsIfEnabledInSettings($showstatsinmenu): string {
    $html = '';

    if ($showstatsinmenu) {
        $html .= '<menustats></menustats>';
    } else {

    }


    return $html;
}

private function menuStatistics($exportRunningHeaderInfo, $showstatsinmenu): void {

    $html ='<div class="ml-auto d-flex">
            <div class="header-icon">
                <span id="global_ajax_loader">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </span>
            </div>';



        $html .= '<div class="header-icon">' . $this->displayMenuStatisticsIfEnabledInSettings($showstatsinmenu) . '</div>';

        $html .= '
            <div class="header-icon">
                <system-health></system-health>
            </div>';

        $html .= $this->menuHeaderClockDisplay();

        $html .= '<div class="header-icon">
            <version-check></version-check>
        </div>';

    $html .= '<div>' . $this->displayExportRunningNotificationIfEnabledInSettings($exportRunningHeaderInfo) . '</div>';

    $html .= $this->menuHeaderSignOut();

    $html .= $this->menuNotifications();

    $html .= '</div>';

    echo $html;
}

private function surroundWithHeaderIcon(string $html): string {
    return
        '<div class="header-icon">' . PHP_EOL
        . '    ' . $html . PHP_EOL
        . '</div>';
}

private function menuHeaderClockDisplay() {
    return $this->surroundWithHeaderIcon('<server-time></server-time>');
}

private function menuHeaderSignOut(): string {

    $signOutBtnGrpHelper = new ButtonGroupHelper('');
    $signOutBtnGrpHelper->addIconButtonWithHRef('fa fa-sign-out-alt', $this->translate('Sign out'),'/users/logout');

    return $this->surroundWithHeaderIcon($signOutBtnGrpHelper->getHtml());
}

private function menuNotifications(): string {
    return '<push-notifications></push-notifications>';
}

private function menuHeaderOpens() {
    echo '<header id="header_jf" class="page-header" role="banner">';
}

private function menuHeaderCloses() {
    echo '</header>';
}
}

$header = new OitcHeader($exportRunningHeaderInfo, $showstatsinmenu);
?>
