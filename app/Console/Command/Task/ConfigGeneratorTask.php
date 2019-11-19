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

use App\Model\Table\ConfigurationFilesTable;
use App\Model\Table\ConfigurationQueueTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

/**
 * Class ConfigGeneratorTask
 */
class ConfigGeneratorTask extends AppShell implements CronjobInterface {

    public function generate() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);
    }

    public function execute($quiet = false) {
        if ($quiet) {
            $this->beQuiet();
        }

        $this->out('Check for pending configuration files');

        /** @var $SystemsettingsTable App\Model\Table\SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        /** @var $ConfigurationQueueTable ConfigurationQueueTable */
        $ConfigurationQueueTable = TableRegistry::getTableLocator()->get('ConfigurationQueue');

        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $configFilesToGenerate = $ConfigurationQueueTable->getConfigFilesToGenerate();

        foreach ($configFilesToGenerate as $record) {
            $configFile = $record['data'];
            $className = sprintf('itnovum\openITCOCKPIT\ConfigGenerator\%s', $configFile);
            if (!class_exists($className)) {
                throw new NotFoundException('Config file not found');
            }

            /** @var  $ConfigFileObject ConfigInterface */
            $ConfigFileObject = new $className();
            $config = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());

            $this->out(sprintf('Generate %s   ', $ConfigFileObject->getLinkedOutfile()), false);
            $ConfigFileObject->writeToFile($config);
            $this->out('<green>Ok</green>');

            $this->restartByConfigFile($configFile, $systemsettings);

            $ConfigurationQueueTable->delete($ConfigurationQueueTable->get($record['id']));
        }
        $this->out('<green>Ok</green>');
        $this->hr();
    }


    public function beQuiet() {
        $this->params['quiet'] = true;
    }

    /**
     * @param string $configFile
     * @param array $systemsettings
     */
    public function restartByConfigFile($configFile, $systemsettings) {
        switch ($configFile) {
            case 'NagiosCfg':
                $command = $systemsettings['MONITORING']['MONITORING.RESTART'];
                $this->restartService($command, 'Restart Nagios/Naemon core');
                break;

            case 'AfterExport':
                $command = $systemsettings['INIT']['INIT.GEARMAN_WORKER_RESTART'];
                $this->restartService($command, 'Restart gearman_worker service');
                break;

            case 'phpNSTAMaster':
                $command = $systemsettings['INIT']['INIT.PHPNSTA_RESTART'];
                $this->restartService($command, 'Restart phpNSTA service');
                break;

            case 'GraphingDocker':
                $command = $systemsettings['INIT']['INIT.OITC_GRAPHING_RESTART'];
                $this->restartService($command, 'Restart and rebuild openITCOCKPIT-Graphing Docker Containers');
                break;

            case 'StatusengineCfg':
                $command = $systemsettings['INIT']['INIT.STATUSENGINE_RESTART'];
                $this->restartService($command, 'Restart Statusengine service');
                break;

            default:
                break;
        }
    }

    private function restartService($command, $outputTxt) {
        $this->out($outputTxt . '   ', false);
        exec(escapeshellcmd($command), $output, $returncode);
        if ($returncode > 0) {
            $this->out('<red>Error</red>');
        } else {
            $this->out('<green>Ok</green>');
        }
    }

}
