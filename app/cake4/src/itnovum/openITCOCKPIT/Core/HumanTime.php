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

namespace itnovum\openITCOCKPIT\Core;


class HumanTime {

    /**
     * Formats a given value in seconds to a human readable string of time
     * Example 125 will return:
     * 2 minutes and 5 seconds
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author     Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since      3.0
     * @deprecated This function is deprecated and will be removed in the next version!
     */
    public static function secondsInWords($seconds) {
        //$min = (int)($seconds / 60);
        //$sec = (int)($seconds % 60);
        //return $min.' '.__('minutes').' '.__('and').' '.$sec.' '.__('seconds');
        return self::secondsInHuman($seconds);
    }

    /**
     * Formats a given value in seconds to a human readable string of time
     * Example 58536006 will return:
     * 1 years, 10 months, 8 days, 12 hours, 0 minutes and 6 seconds
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public static function secondsInHuman($duration) {
        if ($duration == '') {
            $duration = 0;
        }
        $zero = new \DateTime("@0");
        $seconds = new \DateTime("@$duration");

        $closure = function ($duration) {
            //Check how mutch "time" we need
            if ($duration >= 31536000) {
                // 1 year or more
                return '%y ' . __('years') . ', %m ' . __('months') . ', %d ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 2678400) {
                // 1 month or more
                return '%m ' . __('months') . ', %d ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 86400) {
                // 1 day or more
                return '%a ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 3600) {
                // 1 hour or more
                return '%h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 60) {
                // 1 minute or more
                return '%i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 0) {
                // 0 second or more
                return '%s ' . __('seconds');
            }
        };

        $format = $closure($duration);

        return $zero->diff($seconds)->format($format);
    }

    public static function pluralize($items, $singular, $plural) {
        if (is_array($items)) {
            if (sizeof($items) > 1) {
                return $plural;
            }

            return $singular;
        }

        if (is_numeric($items)) {
            if ($items > 1) {
                return $plural;
            }

            return $singular;
        }
    }


    /**
     * Formats a given value in seconds to a human short readable string with time units
     * Example 58536006 will return:
     * 1Y 10M 8D 12h 0m 6s
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public static function secondsInHumanShort($duration) {

        if ($duration == '') {
            $duration = 0;
        }

        $zero = new \DateTime("@0");
        $seconds = new \DateTime("@$duration");
        $closure = function ($duration) {
            //Check how much "time" we need
            if ($duration >= 31536000) {
                // 1 year or more
                return '%y' . __('Y') . ' %m' . __('M') . ' %d' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 2678400) {
                // 1 month or more
                return '%m' . __('M') . ' %d' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 86400) {
                // 1 day or more
                return '%a' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 3600) {
                // 1 hour or more
                return '%h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 60) {
                // 1 minute or more
                return '%i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 0) {
                // 0 second or more
                return '%s' . __('s');
            }
        };

        $format = $closure($duration);

        return $zero->diff($seconds)->format($format);
    }
}
