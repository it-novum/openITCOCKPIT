<?php
// Copyright (C) <2018>  <it-novum GmbH>
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
use itnovum\openITCOCKPIT\ConfigGenerator\GeneratorRegistry;

/**
 * Class ConfigGeneratorShell
 * @property ConfigurationFile $ConfigurationFile
 */
class ConfigGeneratorShell extends AppShell {

    public $uses = [
        'ConfigurationFile'
    ];

    public function main() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);

        $this->parser = $this->getOptionParser();

        debug($this->params);

        if (count($this->params) <= 3) {
            $this->out('No option given. Try --help');
            exit(1);
        }

        if (array_key_exists('migrate', $this->params)) {
            $this->migrate();
        }

        if (array_key_exists('generate', $this->params)) {
            $this->generate();
        }
    }

    public function generate() {
        $this->out('Generate all configuration files...    ');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $this->out(sprintf('Generate %s   ', $ConfigFileObject->getOutfile()), false);
            $ConfigFileObject->writeToFile($this->ConfigurationFile->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $this->out('<green>Ok</green>');
        }
    }

    public function migrate() {
        $this->out('Migrate existing configuration files to database...    ');

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $this->out(sprintf('Processing %s   ', $ConfigFileObject->getOutfile()), false);

            $dbConfig = $this->ConfigurationFile->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());
            $config = $ConfigFileObject->migrate($dbConfig);
            if (is_array($config)) {
                $configFileForDatabase = $ConfigFileObject->convertRequestForSaveAll($config);
                $this->ConfigurationFile->saveConfigurationValuesForConfigFile($ConfigFileObject->getDbKey(), $configFileForDatabase);
                $this->out('<green>Ok</green>');
            } else {
                $this->out('<blue>Skipping</blue>');
            }
        }
    }

    /**
     * @return ConsoleOptionParser
     */
    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'generate' => ['help' => "Will generate all configuration files from database"],
            'migrate'  => ['help' => 'Will migrate existing configuration files to database']
        ]);

        return $parser;
    }

    public function _welcome() {
        //Disable CakePHP welcome messages
    }

}
