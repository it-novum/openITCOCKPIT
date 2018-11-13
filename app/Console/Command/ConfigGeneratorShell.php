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

//require_once APP.'Vendor'.DS.'minify'.DS.'src'.DS.'Minify.php';
//require_once APP.'Vendor'.DS.'minify'.DS.'src'.DS.'JS.php';


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

        //javascript components
        $this->out('Generate all configuration files...    ');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */

            $this->out(sprintf('Generate %s   ', $ConfigFileObject->getOutfile()), false);
            $ConfigFileObject->writeToFile($this->ConfigurationFile->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $this->out('<green>Ok</green>');
        }

    }


    public function _welcome() {
        //Disable CakePHP welcome messages
    }

}
