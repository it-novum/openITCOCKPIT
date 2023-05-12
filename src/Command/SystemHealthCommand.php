<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

declare(strict_types=1);

namespace App\Command;

use App\itnovum\openITCOCKPIT\Supervisor\Supervisorctl;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Plugin;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\System\Health\CpuLoad;
use itnovum\openITCOCKPIT\Core\System\Health\Disks;
use itnovum\openITCOCKPIT\Core\System\Health\MemoryUsage;

/**
 * SystemHealth command.
 */
class SystemHealthCommand extends Command implements CronjobInterface {
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $io->out('Fetch system health information...', 0);

        $data = $this->fetchInformation();
        $this->saveToCache($data);

        $io->success('   Ok');
        $io->hr();
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
            'isNstaRunning'                   => false,
            'isGearmanWorkerRunning'          => false,
            'isNdoInstalled'                  => false,
            'isStatusengineInstalled'         => true, //NDOUtils are not supported anymore
            'isStatusenginePerfdataProcessor' => true, //NPCD is not supported anymore
            'isDistributeModuleInstalled'     => false,
            'isPushNotificationRunning'       => false,
            'isNodeJsServerRunning'           => false
        ];

        if (IS_CONTAINER) {
            // openITCOCKPIT is running inside a container like docker
            $Supervisorctl = new Supervisorctl();
            $data = [
                'isNagiosRunning'                 => $Supervisorctl->isRunning('naemon'),
                'isNdoRunning'                    => false,
                'isStatusengineRunning'           => $Supervisorctl->isRunning('statusengine'),
                'isNpcdRunning'                   => false,
                'isOitcCmdRunning'                => $Supervisorctl->isRunning('oitc_cmd'),
                'isSudoServerRunning'             => $Supervisorctl->isRunning('sudo_server'),
                'isNstaRunning'                   => $Supervisorctl->isRunning('nsta'),
                'isGearmanWorkerRunning'          => $Supervisorctl->isRunning('gearman_worker'),
                'isNdoInstalled'                  => false,
                'isStatusengineInstalled'         => true, //NDOUtils are not supported anymore
                'isStatusenginePerfdataProcessor' => true, //NPCD is not supported anymore
                'isDistributeModuleInstalled'     => false,
                'isPushNotificationRunning'       => $Supervisorctl->isRunning('push_notification'),
                'isNodeJsServerRunning'           => $Supervisorctl->isRunning('openitcockpit-node')
            ];
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsetting = $SystemsettingsTable->findAsArray();

        /****** Cpu Load ******/
        $CpuLoad = new CpuLoad();

        $cpuLoadWarning = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.CPU.LOAD_WARNING']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.CPU.LOAD_WARNING'] : null;
        $cpuLoadCritical = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.CPU.LOAD_CRITICAL']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.CPU.LOAD_CRITICAL'] : null;

        $data['load'] = $CpuLoad->getLoadForSystemHealth($cpuLoadWarning, $cpuLoadCritical);

        /****** Disks ******/
        $Disks = new Disks();
        $diskWarningInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.DISK.WARNING_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.DISK.WARNING_IN_PERCENT'] : null;
        $diskCriticalInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.DISK.CRITICAL_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.DISK.CRITICAL_IN_PERCENT'] : null;

        $data['disk_usage'] = $Disks->getDiskUsage($diskWarningInPercent, $diskCriticalInPercent);

        /****** Memory ******/
        $MemoryUsage = new MemoryUsage();

        $memoryWarningInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.RAM.WARNING_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.RAM.WARNING_IN_PERCENT'] : null;
        $memoryCriticalInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.RAM.CRITICAL_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.RAM.CRITICAL_IN_PERCENT'] : null;
        $swapWarningInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.SWAP.WARNING_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.SWAP.WARNING_IN_PERCENT'] : null;
        $swapCriticalInPercent = !empty($systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.SWAP.CRITICAL_IN_PERCENT']) ? $systemsetting['SYSTEM_HEALTH']['SYSTEM_HEALTH.SWAP.CRITICAL_IN_PERCENT'] : null;


        $data['memory_usage'] = $MemoryUsage->getMemoryUsage(
            $memoryWarningInPercent,
            $memoryCriticalInPercent,
            $swapWarningInPercent,
            $swapCriticalInPercent
        );


        $errorRedirect = ' 2> /dev/null';

        if (IS_CONTAINER === false) {
            // Normal installation of openITCOCKPIT via apt, dnf or git
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

            exec($systemsetting['INIT']['INIT.NSTA_STATUS'] . $errorRedirect, $output, $returncode);
            if ($returncode == 0) {
                $data['isNstaRunning'] = true;
            }

            exec($systemsetting['INIT']['INIT.PUSH_NOTIFICATION'] . $errorRedirect, $output, $returncode);
            if ($returncode == 0) {
                $data['isPushNotificationRunning'] = true;
            }

            exec($systemsetting['INIT']['INIT.OPENITCOCKPIT_NODE'] . $errorRedirect, $output, $returncode);
            if ($returncode == 0) {
                $data['isNodeJsServerRunning'] = true;
            }

            if (file_exists('/opt/openitc/nagios/bin/ndo2db')) {
                $data['isNdoInstalled'] = true;
            }

            //if (file_exists('/opt/openitc/statusengine2/cakephp/app/Console/Command/StatusengineLegacyShell.php')) {
            //    $data['isStatusengineInstalled'] = true;
            //}

            //$statusengineConfig = '/opt/openitc/statusengine2/cakephp/app/Config/Statusengine.php';
            //if (file_exists($statusengineConfig)) {
            //    require_once $statusengineConfig;
            //    if (isset($config['process_perfdata'])) {
            //        if ($config['process_perfdata'] === true) {
            //            $data['isStatusenginePerfdataProcessor'] = true;
            //        }
            //    }
            //}
        }

        if (Plugin::isLoaded('DistributeModule')) {
            $data['isDistributeModuleInstalled'] = true;
        }

        return $data;
    }

    public function saveToCache($data) {
        $data['update'] = time();

        $redisHost = env('OITC_REDIS_HOST', '127.0.0.1');
        $redisPort = filter_var(env('OITC_REDIS_PORT', 6379), FILTER_VALIDATE_INT);

        $Redis = new \Redis();
        $Redis->connect($redisHost, $redisPort);
        $Redis->setex('permissions_system_health', 60 * 3, serialize($data));
    }
}
