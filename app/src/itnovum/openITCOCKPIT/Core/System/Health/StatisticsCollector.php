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


/**
 * Class StatisticsCollector
 * @package itnovum\openITCOCKPIT\Core\System\Health
 * @property \Host $Host
 * @property \Service $Service
 */
class StatisticsCollector {

    /**
     * @var \Host
     */
    private $Host;

    /**
     * @var \Service
     */
    private $Service;

    public function __construct(\Model $Host, \Model $Service) {
        $this->Host = $Host;
        $this->Service = $Service;
    }

    public function getData() {
        \Configure::load('version');
        $openITCOCKPITVersion = \Configure::read('version');

        $numberOfHosts = $this->Host->find('count');
        $numberOfServices = $this->Service->find('count');


        $CpuLoad = new CpuLoad();
        $Memory = new MemoryUsage();
        $MonitoringEngine = new MonitoringEngine();
        $LsbRelease = new LsbRelease();
        $SystemId = new SystemId();

        $memory = $Memory->getMemoryUsage();

        $modulePlugins = array_filter(\CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });

        $MysqlHealth = new MySQLHealth($this->Host);

        return [
            'system_id'         => $SystemId->getSystemId(),
            'cpu'               => [
                'load'            => $CpuLoad->getLoad(),
                'number_of_cores' => $CpuLoad->getNumberOfCores(),
                'model_name'      => $CpuLoad->getModel()
            ],
            'memory'            => [
                'memory' => $memory['memory'],
                'swap'   => $memory['swap'],
                'unit'   => 'mb'

            ],
            'monitoring_engine' => [
                'version_string' => $MonitoringEngine->getMonitoringEngine()
            ],
            'php'               => [
                'version_string' => phpversion()
            ],
            'oitc'              => [
                'version'            => $openITCOCKPITVersion,
                'number_of_hosts'    => $numberOfHosts,
                'number_of_services' => $numberOfServices,
                'plugins'            => array_values($modulePlugins),
            ],
            'operating_system'  => [
                'vendor'   => $LsbRelease->getVendor(),
                'version'  => $LsbRelease->getVersion(),
                'codename' => $LsbRelease->getCodename()
            ],
            'mysql'             => $MysqlHealth->getAllMetrics()
        ];
    }

}
