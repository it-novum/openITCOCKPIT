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


use App\Model\Table\ConfigurationFilesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\ConfigGenerator\GeneratorRegistry;

/**
 * Class ConfigGeneratorShell
 * @property ConfigGeneratorTask $ConfigGenerator
 */
class ConfigGeneratorShell extends AppShell {

    public $tasks = [
        'ConfigGenerator'
    ];

    /**
     * @var bool
     */
    private $force = false;

    public function main() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->stdout->styles('red', ['text' => 'red']);

        $this->parser = $this->getOptionParser();

        if (count($this->params) <= 3) {
            $this->out('No option given. Try --help');
            exit(1);
        }

        if (array_key_exists('migrate', $this->params)) {
            try {
                $this->migrate();
            } catch (Exception $e) {
                $this->out('<red>' . $e->getMessage() . '</red>');
            }
        }

        if (array_key_exists('generate', $this->params)) {
            if (array_key_exists('reload', $this->params)) {
                $this->generateAndReload();
            } else {
                $this->generate();
            }
        }
    }

    public function generate() {
        $this->out('Generate all configuration files...    ');
        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $this->out(sprintf('Generate %s   ', $ConfigFileObject->getLinkedOutfile()), false);
            $ConfigFileObject->writeToFile($ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $this->out('<green>Ok</green>');
        }
    }

    public function generateAndReload() {
        $this->out('Generate all configuration files...    ');

        /** @var $SystemsettingsTable App\Model\Table\SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $this->out(sprintf('Generate %s', $ConfigFileObject->getLinkedOutfile()));
            $ConfigFileObject->writeToFile($ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $this->ConfigGenerator->restartByConfigFile($ConfigFileObject->getDbKey(), $systemsettings);
        }
    }


    public function migrate() {
        $this->out('Migrate existing configuration files to database...    ');
        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $this->out(sprintf('Processing %s   ', $ConfigFileObject->getLinkedOutfile()), false);

            $dbConfig = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());
            $config = $ConfigFileObject->migrate($dbConfig);
            if (is_array($config)) {
                $configFileForDatabase = $ConfigFileObject->convertRequestForSaveAll($config);
                $ConfigurationFilesTable->saveConfigurationValuesForConfigFile($ConfigFileObject->getDbKey(), $configFileForDatabase);
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
            'reload'   => ['help' => "Reload services, where a new configuration file was generated for"],
            'migrate'  => ['help' => 'Will migrate existing configuration files to database']
        ]);

        return $parser;
    }

    public function _welcome() {
        //Disable CakePHP welcome messages
    }

}
