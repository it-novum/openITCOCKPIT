<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

namespace App\Lib\Traits;


trait CustomValidationTrait {

    /**
     * @param string $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for contacts and or contact groups
     */
    public function checkMacroNames($value, $context) {
        if (isset($context['data']['customvariables']) && is_array($context['data']['customvariables'])) {
            $usedNames = [];

            foreach ($context['data']['customvariables'] as $macro) {
                if (in_array($macro['name'], $usedNames, true)) {
                    //Macro name not unique
                    return false;
                }
                $usedNames[] = $macro['name'];
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for contacts and or contact groups
     */
    public function checkFlapDetectionOptionsHost($value, $context) {
        $flapDetectionOptions = [
            'flap_detection_on_up',
            'flap_detection_on_down',
            'flap_detection_on_unreachable'
        ];

        if (!isset($context['data']['flap_detection_enabled']) || $context['data']['flap_detection_enabled'] == 0) {
            return true;
        }

        foreach ($flapDetectionOptions as $flapDetectionOption) {
            if (isset($context['data'][$flapDetectionOption]) && $context['data'][$flapDetectionOption] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for notify options (host)
     */
    public function checkNotificationOptionsHost($value, $context) {
        $notificationOptions = [
            'notify_on_recovery',
            'notify_on_down',
            'notify_on_unreachable',
            'notify_on_flapping',
            'notify_on_downtime'
        ];

        foreach ($notificationOptions as $notificationOption) {
            if (isset($context['data'][$notificationOption]) && $context['data'][$notificationOption] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param array $context
     * @return bool
     *
     * Custom validation rule for escalate options (host escalations)
     */
    public function checkEscalateOptionsHostEscalation($value, $context) {
        $escalateOptions = [
            'escalate_on_recovery',
            'escalate_on_down',
            'escalate_on_unreachable'
        ];

        foreach ($escalateOptions as $escalateOption) {
            if (isset($context['data'][$escalateOption]) && $context['data'][$escalateOption] == 1) {
                return true;
            }
        }
        return false;
    }
}
