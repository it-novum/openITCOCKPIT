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

class NagiosHelper extends AppHelper
{

    public function checkFlapDetection($value = 0)
    {
        if ($value == 1) {
            return ['string' => __('On'), 'html' => '<span class="label bg-color-green">'.__('On').'</span>'];
        }

        return ['string' => __('Off'), 'html' => '<span class="label bg-color-red">'.__('Off').'</span>'];
    }

    /**
     * @param $notifications
     * @return string
     * @deprecated
     */
    public function formatNotifyOnHost($notifications)
    {
        $_options = [
            'notify_on_down'        => 1,
            'notify_on_unreachable' => 0,
            'notify_on_recovery'    => 1,
            'notify_on_flapping'    => 0,
            'notify_on_downtime'    => 0,
        ];

        $notifications = Hash::merge($_options, $notifications);
        $html = '';
        foreach ($notifications as $key => $value) {
            $html .= '<dd>';
            if ($value == 1) {
                $html .= '<i class="fa fa-check txt-color-green"></i> '.__($key);
            } else {
                $html .= '<i class="fa fa-times txt-color-red"></i> '.__($key);
            }
            $html .= '</dd>';
        }

        return $html;
    }

    public function formatNotifyOnService($notifications)
    {
        $_options = [
            'notify_on_warning'  => 1,
            'notify_on_critical' => 0,
            'notify_on_unknown'  => 0,
            'notify_on_recovery' => 1,
            'notify_on_flapping' => 0,
            'notify_on_downtime' => 0,
        ];

        $notifications = Hash::merge($_options, $notifications);
        $html = '';
        foreach ($notifications as $key => $value) {
            $html .= '<dd>';
            if ($value == 1) {
                $html .= '<i class="fa fa-check txt-color-green"></i> '.__($key);
            } else {
                $html .= '<i class="fa fa-times txt-color-red"></i> '.__($key);
            }
            $html .= '</dd>';
        }

        return $html;
    }

    public function colorHostOutput($status = 2)
    {
        switch ($status) {
            case 0:
                return 'txt-color-greenDark';
            case 1:
                return 'txt-color-red';
            default:
                return 'txt-color-blueLight';
        }
    }

    public function colorServiceOutput($status = 3)
    {
        switch ($status) {
            case 0:
                return 'txt-color-greenDark';
            case 1:
                return 'warning';
            case 2:
                return 'txt-color-red';
            default:
                return 'txt-color-blueLight';
        }
    }

}