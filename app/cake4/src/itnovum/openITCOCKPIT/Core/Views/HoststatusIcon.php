<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\Views;


class HoststatusIcon {

    /**
     * @var int|null
     */
    private $state;

    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $style;

    /**
     * @var array
     */
    private $humanStates = [];

    private $stateColors = [
        0 => 'success',
        1 => 'danger',
        2 => 'default',
        3 => 'primary' //Not found in monitoring
    ];

    private $stateIcons = [
        0 => 'glyphicon glyphicon-ok',
        1 => 'fa fa-exclamation',
        2 => 'fa fa-question-circle',
        3 => 'fa fa-question-circle' //Not found in monitoring
    ];

    private $pushIcon = [
        0 => 'HostPushIconUP',
        1 => 'HostPushIconDOWN',
        2 => 'HostPushIconUNREACHABLE',
        3 => null //Not found in monitoring
    ];

    /**
     * HoststatusIcon constructor.
     * @param null $state
     * @param string $href
     * @param string $style
     */
    public function __construct($state = null, $href = 'javascript:void(0)', $style = '') {
        $this->state = $state;
        $this->href = $href;
        $this->style = $style;


        $this->humanStates = [
            0 => __('Up'),
            1 => __('Down'),
            2 => __('Unreachable')
        ];

    }

    public function getTextColor() {
        if ($this->state === null) {
            return 'txt-primary';
        }

        switch ($this->state) {
            case 0:
                return 'up';

            case 1:
                return 'down';

            default:
                return 'unreachable';
        }
    }

    /**
     * @return string
     */
    public function getHumanState() {
        if ($this->state === null) {
            return __('Not found in monitoring');
        }

        return $this->humanStates[$this->state];
    }

    /**
     * @return string
     */
    public function getHtmlIcon() {
        $template = '<a href="%s" class="btn btn-%s status-circle" style="padding:0;%s"></a>';

        $state = $this->state;
        if ($state === null) {
            $state = 3;
        }
        return sprintf($template, $this->href, $this->stateColors[$state], $this->style);
    }

    /**
     * @return string
     */
    public function getPdfIcon() {
        $template = '<i class="fa fa-square %s"></i>';
        return sprintf($template, $this->getTextColor());
    }

    /**
     * @return string
     */
    public function getIcon() {
        $state = $this->state;
        if ($state === null) {
            $state = 3;
        }

        return $this->stateIcons[$state];
    }

    /**
     * @return string
     */
    public function getNotificationIcon() {
        $icon = $this->pushIcon[$this->state];
        if ($icon === null) {
            return null;
        }

        return sprintf(
            '/img/push_notifications/wh/%s.png',
            $icon
        );
    }

    public function asArray() {
        return [
            'state'       => $this->state,
            'human_state' => $this->getHumanState(),
            'html_icon'   => $this->getHtmlIcon(),
            'icon'        => $this->getIcon()
        ];
    }

}