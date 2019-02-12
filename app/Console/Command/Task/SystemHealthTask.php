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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\ModuleManager;
use itnovum\openITCOCKPIT\Core\System\Health\CpuLoad;
use itnovum\openITCOCKPIT\Core\System\Health\Disks;
use itnovum\openITCOCKPIT\Core\System\Health\MemoryUsage;

class SystemHealthTask extends AppShell implements CronjobInterface {
    public $uses = [
        'Systemsetting',
    ];


    function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);


        $this->out('Fetch system health information...', false);

        $data = $this->fetchInformation();
        $this->saveToCache($data);

        $this->out('<green>   Ok</green>');
        $this->hr();
    }

    /**
     * @return array
     */
    public function fetchInformation() {
        $data = [
            'isNagiosRunning'                 => false,
            'isNdoRunning'                    => false,
            'isStatusengineRunning'           => false,
            'isNpcdRunning'                   => false,
            'isOitcCmdRunning'                => false,
            'isSudoServerRunning'             => false,
            'isPhpNstaRunning'                => false,
            'isGearmanWorkerRunning'          => false,
            'isNdoInstalled'                  => false,
            'isStatusengineInstalled'         => false,
            'isStatusenginePerfdataProcessor' => false,
            'isDistributeModuleInstalled'     => false,
            'isPushNotificationRunning'       => false,
            'isNodeJsServerRunning'           => false
        ];

        $CpuLoad = new CpuLoad();


        $data['load'] = $CpuLoad->getLoadForSystemHealth();

        $Disks = new Disks();
        $data['disk_usage'] = $Disks->getDiskUsage();

        $MemoryUsage = new MemoryUsage();
        $data['memory_usage'] = $MemoryUsage->getMemoryUsage();

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsetting = $Systemsettings->findAsArray();
        $errorRedirect = ' 2> /dev/null';


        exec($systemsetting['MONITORING']['MONITORING.STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isNagiosRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.NDO_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isNdoRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.STATUSENIGNE_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isStatusengineRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.NPCD_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isNpcdRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.OITC_CMD_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isOitcCmdRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.SUDO_SERVER_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isSudoServerRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.PHPNSTA_STATUS'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isPhpNstaRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.PUSH_NOTIFICATION'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isPushNotificationRunning'] = true;
        }

        exec($systemsetting['INIT']['INIT.NODEJS_SERVER'] . $errorRedirect, $output, $returncode);
        if ($returncode == 0) {
            $data['isNodeJsServerRunning'] = true;
        }

        if (file_exists('/opt/openitc/nagios/bin/ndo2db')) {
            $data['isNdoInstalled'] = true;
        }

        if (file_exists('/opt/statusengine/cakephp/app/Console/Command/StatusengineLegacyShell.php')) {
            $data['isStatusengineInstalled'] = true;
        }

        $statusengineConfig = '/opt/statusengine/cakephp/app/Config/Statusengine.php';
        if (file_exists($statusengineConfig)) {
            require_once $statusengineConfig;
            if (isset($config['process_perfdata'])) {
                if ($config['process_perfdata'] === true) {
                    $data['isStatusenginePerfdataProcessor'] = true;
                }
            }
        }

        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $data['isDistributeModuleInstalled'] = true;
        }

        return $data;

    }

    public function saveToCache($data) {

        $data['update'] = time();
        $Redis = new Redis();
        $Redis->connect('127.0.0.1', 6379);
        $Redis->setex('permissions_system_health', 60 * 3, serialize($data));
    }


}
