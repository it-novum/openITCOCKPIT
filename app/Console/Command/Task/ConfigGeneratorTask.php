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

use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

/**
 * Class ConfigGeneratorTask
 * @property Systemsetting $Systemsetting
 * @property ConfigurationFile $ConfigurationFile
 * @property ConfigurationQueue $ConfigurationQueue
 */
class ConfigGeneratorTask extends AppShell implements CronjobInterface {

    public $uses = [
        'Systemsetting',
        'ConfigurationFile',
        'ConfigurationQueue'
    ];

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

        $systemsettings = $this->Systemsetting->findAsArray();

        $configFilesToGenerate = $this->ConfigurationQueue->getConfigFilesToGenerate();

        foreach ($configFilesToGenerate as $record) {
            $configFile = $record['ConfigurationQueue']['data'];
            $className = sprintf('itnovum\openITCOCKPIT\ConfigGenerator\%s', $configFile);
            if (!class_exists($className)) {
                throw new NotFoundException('Config file not found');
            }

            /** @var  $ConfigFileObject ConfigInterface */
            $ConfigFileObject = new $className();
            $config = $this->ConfigurationFile->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());

            $this->out(sprintf('Generate %s   ', $ConfigFileObject->getOutfile()), false);
            $ConfigFileObject->writeToFile($config);
            $this->out('<green>Ok</green>');

            switch ($configFile) {
                case 'NagiosCfg':
                    $command = $systemsettings['MONITORING']['MONITORING.RESTART'];
                    $this->restartService($command, 'Restart Nagios/Naemon core');
                    break;

                case 'AfterExport':
                    $command = $systemsettings['INIT']['INIT.OITC_GRAPHING_RESTART'];
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

                default:
                    break;
            }
            $this->ConfigurationQueue->delete($record['ConfigurationQueue']['id']);
        }
        $this->out('<green>Ok</green>');
        $this->hr();
    }


    public function beQuiet() {
        $this->params['quiet'] = true;
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
