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
 * Class ConfigurationFilesController
 * @property ConfigurationFile $ConfigurationFile
 */
class ConfigurationFilesController extends AppController {

    public $uses = [
        'ConfigurationFile'
    ];

    public $layout = 'angularjs';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }
    }

    public function edit($configFile) {

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            if ($ConfigFileObject->getDbKey() === $configFile) {
                $this->set('ConfigFileObject', $ConfigFileObject);
                return;
            }
        }

        $this->redirect([
            'controller' => 'Angular',
            'action'     => 'not_found',
            'plugin'     => ''
        ]);

    }

    public function NagiosCfg() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\NagiosCfg', 'NagiosCfg');
    }

    public function AfterExport() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\AfterExport', 'AfterExport');
    }

    public function NagiosModuleConfig() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\NagiosModuleConfig', 'NagiosModuleConfig');
    }

    public function phpNSTAMaster(){
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\phpNstaMaster', 'phpNstaMaster');
    }

    public function DbBackend(){
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\DbBackend', 'DbBackend');
    }

    public function PerfdataBackend(){
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\Perfdatabackend', 'Perfdatabackend');
    }

    public function GraphingDocker(){
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\GraphingDocker', 'GraphingDocker');
    }


    /**
     * @param $ConfigurationObjectClassName
     */
    private function __sharedControllerAction($ConfigurationObjectClassName, $ShortClassName) {
        $this->layout = 'blank';
        $ConfigurationObjectClassName = new $ConfigurationObjectClassName();
        $this->set($ShortClassName, $ConfigurationObjectClassName);

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            $dbConfig = $this->ConfigurationFile->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());
            $config = $ConfigurationObjectClassName->mergeDbResultWithDefaultConfiguration($dbConfig);

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
        }

        if ($this->request->is('post')) {
            if ($ConfigurationObjectClassName->validate($this->request->data)) {
                //Save new config to database
                $configFileForDatabase = $ConfigurationObjectClassName->convertRequestForSaveAll($this->request->data);
                if ($this->ConfigurationFile->saveConfigurationValuesForConfigFile($ConfigurationObjectClassName->getDbKey(), $configFileForDatabase)) {
                    $this->setFlash(_('Config saved successfully'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);
                    return;
                } else {
                    $this->response->statusCode(400);
                    $this->set('success', false);
                    $this->set('_serialize', ['success']);
                    return;
                }

            } else {
                $this->response->statusCode(400);
                $error = $ConfigurationObjectClassName->validationErrors;
                $this->set('error', $error);
                $this->set('_serialize', ['error']);
                return;
            }
        }
    }
}
