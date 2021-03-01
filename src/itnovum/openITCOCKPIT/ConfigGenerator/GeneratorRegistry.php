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

namespace itnovum\openITCOCKPIT\ConfigGenerator;

use Cake\Core\Plugin;
use PrometheusModule\Lib\ConfigGenerator\PrometheusCfgs_prometheus;
use SnmpTrapModule\Lib\ConfigGenerator\SnmpTrapCfgs_snmptrapd;
use SnmpTrapModule\Lib\ConfigGenerator\SnmpTrapCfgs_snmptrapdConf;
use SnmpTrapModule\Lib\ConfigGenerator\SnmpTrapCfgs_snmpttIni;

class GeneratorRegistry {

    /**
     * @return array
     */
    public function getAllConfigFiles() {
        $configFiles = [
            new NagiosCfg(),
            new AfterExport(),
            //new NagiosModuleConfig(),
            //new phpNSTAMaster(),
            new DbBackend(),
            new PerfdataBackend(),
            new Gearman(),
            new GraphingDocker(),
            new StatusengineCfg(),
            new Statusengine3Cfg(),
            new GraphiteWeb(),
            new NSTAMaster()
        ];

        if (class_exists('SnmpTrapModule\Lib\ConfigGenerator\SnmpTrapCfgs_snmptrapd')) {
            $configFiles[] = new SnmpTrapCfgs_snmptrapd();
            $configFiles[] = new SnmpTrapCfgs_snmptrapdConf();
            $configFiles[] = new SnmpTrapCfgs_snmpttIni();
        }

        if (Plugin::isLoaded('PrometheusModule')) {
            $configFiles[] = new PrometheusCfgs_prometheus();
        }

        return $configFiles;
    }

    /**
     * @return array
     */
    public function getAllConfigFilesWithCategory() {
        $configFiles = [
            __('openITCOCKPIT Interface configuration files') => [
                new AfterExport(),
                //new NagiosModuleConfig(),
                new DbBackend(),
                new PerfdataBackend(),
                new GraphiteWeb(),
                new Gearman()
            ],
            __('Monitoring engine')                           => [
                new NagiosCfg()
            ],
            __('Statusengine')                                => [
                new StatusengineCfg(),
                new Statusengine3Cfg()
            ],
            //__('phpNSTA')                                     => [
            //    new phpNSTAMaster()
            //],
            __('NSTA')                                        => [
                new NSTAMaster()
            ],
            __('Carbon and Whisper (Graphing)')               => [
                new GraphingDocker()
            ]
        ];

        if (class_exists('SnmpTrapModule\Lib\ConfigGenerator\SnmpTrapCfgs_snmptrapd')) {
            $configFiles[__('SnmpTrapModule')] = [
                new SnmpTrapCfgs_snmptrapd(),
                new SnmpTrapCfgs_snmptrapdConf(),
                new SnmpTrapCfgs_snmpttIni()
            ];
        }

        if (Plugin::isLoaded('PrometheusModule')) {
            $configFiles[__('PrometheusModule')] = [
                new PrometheusCfgs_prometheus()
            ];
        }

        return $configFiles;
    }

}
