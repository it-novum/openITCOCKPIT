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

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ConfigurationFilesTable;
use App\Model\Table\ConfigurationQueueTable;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\ConfigGenerator\GeneratorRegistry;

/**
 * Class ConfigurationFilesController
 * @package App\Controller
 */
class ConfigurationFilesController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $configFilesForFrontend = [];
        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFilesWithCategory() as $categoryName => $ConfigFileObjects) {
            $category = [
                'name'        => $categoryName,
                'configFiles' => []
            ];

            foreach ($ConfigFileObjects as $ConfigFileObject) {
                /** @var ConfigInterface $ConfigFileObject */
                $category['configFiles'][] = [
                    'linkedOutfile' => $ConfigFileObject->getLinkedOutfile(),
                    'dbKey'         => $ConfigFileObject->getDbKey()
                ];
            }

            $configFilesForFrontend[] = $category;
        }

        $this->set('configFileCategories', $configFilesForFrontend);
        $this->viewBuilder()->setOption('serialize', ['configFileCategories']);
    }

    /**
     * @param null $configFile
     */
    public function edit($configFile = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            if ($ConfigFileObject->getDbKey() === $configFile) {

                $this->set('ConfigFile', $ConfigFileObject->toArray());
                $this->viewBuilder()->setOption('serialize', ['ConfigFile']);
                return;
            }
        }

        throw new NotFoundException();
    }

    /**
     * @throws \Exception
     */
    public function NagiosCfg() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\NagiosCfg', 'NagiosCfg');
    }

    /**
     * @throws \Exception
     */
    public function AfterExport() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\AfterExport', 'AfterExport');
    }

    /**
     * @throws \Exception
     */
    public function NagiosModuleConfig() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\NagiosModuleConfig', 'NagiosModuleConfig');
    }

    /**
     * @throws \Exception
     */
    public function phpNSTAMaster() {
        $this->layout = 'blank';
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\phpNSTAMaster', 'phpNstaMaster');
    }

    /**
     * @throws \Exception
     */
    public function DbBackend() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\DbBackend', 'DbBackend');
    }

    /**
     * @throws \Exception
     */
    public function PerfdataBackend() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\PerfdataBackend', 'PerfdataBackend');
    }

    /**
     * @throws \Exception
     */
    public function GraphingDocker() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\GraphingDocker', 'GraphingDocker');
    }

    /**
     * @throws \Exception
     */
    public function StatusengineCfg() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\StatusengineCfg', 'StatusengineCfg');
    }

    /**
     * @throws \Exception
     */
    public function GraphiteWeb() {
        $this->__sharedControllerAction('itnovum\openITCOCKPIT\ConfigGenerator\GraphiteWeb', 'GraphiteWeb');
    }

    /**
     * @param $configFile
     * @throws \Exception
     */
    public function restorDefault($configFile) {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $className = sprintf('itnovum\openITCOCKPIT\ConfigGenerator\%s', $configFile);
        if (!class_exists($className)) {
            throw new NotFoundException('Config file not found');
        }

        /** @var ConfigurationFilesTable $ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');
        /** @var ConfigurationQueueTable $ConfigurationQueueTable */
        $ConfigurationQueueTable = TableRegistry::getTableLocator()->get('ConfigurationQueue');

        /** @var ConfigInterface $ConfigurationObjectClassName */
        $ConfigurationObjectClassName = new $className();

        $currentConfig = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());
        $config = $ConfigurationObjectClassName->convertRequestForSaveAll($ConfigurationObjectClassName->getDefaults());

        if ($ConfigurationFilesTable->saveConfigurationValuesForConfigFile($ConfigurationObjectClassName->getDbKey(), $config)) {
            $configHasChanged = $ConfigurationFilesTable->hasChanged($currentConfig, $config);
            $configHasChanged = true;

            if ($configHasChanged) {
                //Require rewirte of configuration file on disk?
                $ConfigurationQueueTable->deleteAll([
                    'task' => 'ConfigGenerator',
                    'data' => $ConfigurationObjectClassName->getDbKey()
                ]);

                $queueEntity = $ConfigurationQueueTable->newEntity([
                    'task' => 'ConfigGenerator',
                    'data' => $ConfigurationObjectClassName->getDbKey()
                ]);

                $ConfigurationQueueTable->save($queueEntity);
            }

            $this->set('success', true);
            $this->set('message', __('Config successfully restored to default'));
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }

    /**
     * @param $ConfigurationObjectClassName
     * @param $ShortClassName
     * @throws \Exception
     */
    private function __sharedControllerAction($ConfigurationObjectClassName, $ShortClassName) {
        $ConfigurationObjectClassName = new $ConfigurationObjectClassName();
        $this->set($ShortClassName, $ConfigurationObjectClassName);

        /** @var ConfigurationFilesTable $ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            $dbConfig = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());
            $config = $ConfigurationObjectClassName->mergeDbResultWithDefaultConfiguration($dbConfig);

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
        }

        if ($this->request->is('post')) {
            /** @var ConfigurationQueueTable $ConfigurationQueueTable */
            $ConfigurationQueueTable = TableRegistry::getTableLocator()->get('ConfigurationQueue');

            if ($ConfigurationObjectClassName->validate($this->request->getData())) {
                //Save new config to database
                $configFileForDatabase = $ConfigurationObjectClassName->convertRequestForSaveAll($this->request->getData());

                $currentConfig = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigurationObjectClassName->getDbKey());

                $configHasChanged = $ConfigurationFilesTable->hasChanged($currentConfig, $configFileForDatabase);

                if ($ConfigurationFilesTable->saveConfigurationValuesForConfigFile($ConfigurationObjectClassName->getDbKey(), $configFileForDatabase)) {
                    $this->set('success', true);
                    $this->viewBuilder()->setOption('serialize', ['success']);

                    if ($configHasChanged) {
                        //Require rewirte of configuration file on disk?
                        $ConfigurationQueueTable->deleteAll([
                            'task' => 'ConfigGenerator',
                            'data' => $ConfigurationObjectClassName->getDbKey()
                        ]);

                        $queueEntity = $ConfigurationQueueTable->newEntity([
                            'task' => 'ConfigGenerator',
                            'data' => $ConfigurationObjectClassName->getDbKey()
                        ]);

                        $ConfigurationQueueTable->save($queueEntity);
                    }

                    return;
                } else {
                    $this->response = $this->response->withStatus(400);
                    $this->set('success', false);
                    $this->viewBuilder()->setOption('serialize', ['success']);
                    return;
                }

            } else {
                $this->response = $this->response->withStatus(400);
                $error = $ConfigurationObjectClassName->validationErrors;
                $this->set('error', $error);
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
        }
    }

    public function dynamicDirective() {
        $directiveName = $this->request->getQuery('directive');
        $isValidDirective = false;

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */

            if ($ConfigFileObject->getAngularDirective() === $directiveName) {
                $isValidDirective = true;
                break;
            }
        }

        if (!$isValidDirective) {
            throw new ForbiddenException();
        }

        $this->set('directiveName', $directiveName);
    }
}
