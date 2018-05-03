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

namespace itnovum\openITCOCKPIT\Core\System\Health;


class MemoryUsage {


    public function getMemoryUsage() {
        $memory = [
            'memory' => [
                'total'   => 0,
                'used'    => 0,
                'free'    => 0,
                'buffers' => 0,
                'cached'  => 0,
            ],
            'swap'   => [
                'total' => 0,
                'used'  => 0,
                'free'  => 0,
            ]
        ];


        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = [];
        foreach ($data as $line) {
            $temp = explode(":", $line);
            if (isset($temp[1])) {
                $meminfo[$temp[0]] = intval(trim(intval(substr($temp[1], 0, strpos($temp[1], "kB")))) / 1000);
            }
        }

        $percentage = $meminfo['Active'] / $meminfo['MemTotal'] * 100;
        $state = 'ok';
        if ($percentage > 80) {
            $state = 'warning';
        }
        if ($percentage > 90) {
            $state = 'critical';
        }

        $memory['memory'] = [
            'total'      => $meminfo['MemTotal'],
            'used'       => $meminfo['Active'],
            'free'       => $meminfo['MemFree'],
            'buffers'    => $meminfo['Buffers'],
            'cached'     => $meminfo['Cached'],
            'percentage' => round($percentage),
            'state'      => $state
        ];


        $swapUsed = $meminfo['SwapTotal'] - $meminfo['SwapFree'];
        $memory['swap'] = [
            'total'      => $meminfo['SwapTotal'],
            'used'       => $swapUsed,
            'free'       => $meminfo['SwapFree'],
            'percentage' => 0,
        ];
        if ($meminfo['Active'] > 0 && $swapUsed > 0) {
            $memory['swap']['percentage'] = round($swapUsed / $meminfo['SwapTotal'] * 100);
        }
        $memory['swap']['state'] = 'ok';
        if ($memory['swap']['percentage'] > 40) {
            $memory['swap']['state'] = 'warning';
        }
        if ($memory['swap']['percentage'] > 50) {
            $memory['swap']['state'] = 'critical';
        }


        return $memory;
    }

}
