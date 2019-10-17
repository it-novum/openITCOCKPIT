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
 * @property ConfigurationQueue $ConfigurationQueue
 */
class ConfigurationFilesController extends AppController {

    public $uses = [
        'ConfigurationFile',
        'ConfigurationQueue'
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

    public function phpNSTAMaster() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\phpNSTAMaster', 'phpNstaMaster');
    }

    public function DbBackend() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\DbBackend', 'DbBackend');
    }

    public function PerfdataBackend() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\PerfdataBackend', 'PerfdataBackend');
    }

    public function GraphingDocker() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\GraphingDocker', 'GraphingDocker');
    }

    public function StatusengineCfg() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\StatusengineCfg', 'StatusengineCfg');
    }

    public function GraphiteWeb() {
        $this->layout = 'blank';

        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\GraphiteWeb', 'GraphiteWeb');
    }

    public function restorDefault($configFile) {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $className = sprintf('itnovum\openITCOCKPIT\ConfigGenerator\%s', $configFile);
        if (!class_exists($className)) {
            throw new NotFoundException('Config file not found');
        }

        /** @var  $ConfigurationObjectClassName ConfigInterface */
        $ConfigurationObjectClassName = new $className();

        $currentConfig = $this->ConfigurationFile->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());
        $config = $ConfigurationObjectClassName->convertRequestForSaveAll($ConfigurationObjectClassName->getDefaults());

        if ($this->ConfigurationFile->saveConfigurationValuesForConfigFile($ConfigurationObjectClassName->getDbKey(), $config)) {
            $configHasChanged = $this->ConfigurationFile->hasChanged($currentConfig, $config);

            if ($configHasChanged) {
                //Require rewirte of configuration file on disk?
                $this->ConfigurationQueue->deleteAll([
                    'ConfigurationQueue.task' => 'ConfigGenerator',
                    'ConfigurationQueue.data' => $ConfigurationObjectClassName->getDbKey()
                ]);
                $this->ConfigurationQueue->create();
                $this->ConfigurationQueue->save([
                    'ConfigurationQueue' => [
                        'task' => 'ConfigGenerator',
                        'data' => $ConfigurationObjectClassName->getDbKey()
                    ]
                ]);
            }

            $this->setFlash(__('Config restored to default successfully'));
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;
    }

    /**
     * @param $ConfigurationObjectClassName
     * @param $ShortClassName
     * @throws Exception
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

                $currentConfig = $this->ConfigurationFile->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());

                $configHasChanged = $this->ConfigurationFile->hasChanged($currentConfig, $configFileForDatabase);

                if ($this->ConfigurationFile->saveConfigurationValuesForConfigFile($ConfigurationObjectClassName->getDbKey(), $configFileForDatabase)) {
                    $this->setFlash(__('Config saved successfully'));
                    $this->set('success', true);
                    $this->set('_serialize', ['success']);

                    if ($configHasChanged) {
                        //Require rewirte of configuration file on disk?
                        $this->ConfigurationQueue->deleteAll([
                            'ConfigurationQueue.task' => 'ConfigGenerator',
                            'ConfigurationQueue.data' => $ConfigurationObjectClassName->getDbKey()
                        ]);
                        $this->ConfigurationQueue->create();
                        $this->ConfigurationQueue->save([
                            'ConfigurationQueue' => [
                                'task' => 'ConfigGenerator',
                                'data' => $ConfigurationObjectClassName->getDbKey()
                            ]
                        ]);
                    }

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
