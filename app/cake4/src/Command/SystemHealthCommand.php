<?php
declare(strict_types=1);

namespace App\Command;

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

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsetting = $SystemsettingsTable->findAsArray();
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

        if (Plugin::isLoaded('DistributeModule')) {
            $data['isDistributeModuleInstalled'] = true;
        }

        return $data;
    }

    public function saveToCache($data) {
        $data['update'] = time();
        $Redis = new \Redis();
        $Redis->connect('127.0.0.1', 6379);
        $Redis->setex('permissions_system_health', 60 * 3, serialize($data));
    }
}
