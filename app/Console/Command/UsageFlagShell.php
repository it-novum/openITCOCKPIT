<?php
// Copyright (C) <2017>  <it-novum GmbH>
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

/**
 * This shell set the usage flag for every module given in the $modules array
 * Every NEW module must contain in the main Model (for Autoreports the Autoreport.php,
 * Eventcorrelation -> Eventcorrelation.php) two functions named getHosts() and getServices()
 * which return the pure Service and Host ids (try Hash::extract() ;))
 */

App::import('Model', 'Host');
App::import('Model', 'Service');
App::import('Model', 'AutoreportModule.Autoreport');
App::import('Model', 'EventcorrelationModule.Eventcorrelation');

class UsageFlagShell extends AppShell {

    /**
     * contains all ids of the host and services used by the modules
     * array(
     *      'Autoreport' => array(
     *          'Host' => array(
     *              (int) 0 => '1',
     *              (int) 1 => '3',
     *              (int) 2 => '4',
     *          ),
     *          'Service' => array(
     *              (int) 0 => '1',
     *              (int) 1 => '2',
     *              (int) 2 => '3',
     *              (int) 3 => '4',
     *              (int) 4 => '14',
     *              (int) 5 => '15',
     *          )
     *      )
     *      'Eventcorrelation' => array(
     *          ...
     *      ),
     * )
     * @var array
     */
    protected $moduleElements = [];

    /**
     * This defines from which models we must get the hosts and services
     * DO NOT MODIFY IT IF YOU DONT KNOW WHAT YOU DO!
     * @var array
     */
    protected $modulesToCheck = [
        'Autoreport',
        'Eventcorrelation',
    ];

    /**
     * @var array
     * List of all loaded modules, we need to touch
     */
    private $modules = [];

    /**
     * Hold Representations of module instances
     * @var array
     */
    protected $moduleInstances = [];

    /**
     * maps the usage flag of any host and service
     * @var array
     */
    protected $usageFlagMapping = [];

    public function _welcome(){
        $this->hr();
        $this->out('Setting usage_flag for hosts and services');
        $this->hr();
    }

    public function main() {
        //create Object instances
        $this->Host = new Host();
        $this->Service = new Service();
        $this->createModuleInstances();


        $this->getModuleHostAndServices($this->modules);
        $this->assignUsageFlagValue($this->moduleElements);
        $this->saveUsageFlag($this->moduleElements);
    }


    /**
     * Creates module instances on the fly
     */
    protected function createModuleInstances() {
        try {
            if(empty($this->modulesToCheck)){
                throw new Exception('No Modules given! Exit');
            }
            foreach ($this->modulesToCheck as $module) {
                $this->out('<info>Create Instance of ' . $module . '</info>');
                if (!class_exists($module)) {
                    $this->out('<error>Class ' . $module . ' could not be found - skipping</error>');
                    continue;
                }
                $this->moduleInstances[$module] = new $module();
                $this->modules[] = $module;
                $this->out('<success>done!</success>');
            }
        } catch (Exception $e) {
            $this->out('<error>'.$e->getMessage().'</error>');
            //$this->error($e->getMessage());
        }
    }


    /**
     * save the usage_flag for every host and service which is contained in $this->moduleElements
     * if something went wrong by saving the usage_flag nothing will be written into the database
     * exception will be thrown at which module the error occured
     * @param array $elements
     */
    protected function saveUsageFlag($elements = []) {
        try {
            if (empty($elements)) {
                throw new Exception('No Data To save');
            }

            foreach ($elements as $moduleName => $data) {
                $this->out('<info>Save usage flags for ' . $moduleName . '</info>');
                foreach ($data as $modelName => $elementIds) {
                    $datasource = $this->{$modelName}->getDatasource();
                    $datasource->begin();
                    $result = [];

                    foreach ($elementIds as $elementId) {
                        $this->{$modelName}->id = $elementId;

                        if($this->{$modelName}->exists($elementId)){
                            $result[] = $this->{$modelName}->saveField('usage_flag', $this->usageFlagMapping[$modelName][$elementId]);
                        }else{
                            $this->out('<warning>'.$modelName.' with ID '.$elementId.' was not found in '.Inflector::tableize($modelName).' Table</warning>');
                        }
                    }

                    //check if something could not be saved
                    if (!in_array(false, $result)) {
                        $datasource->commit();
                        $this->out('<success>'.$modelName.' done!</success>');
                    } else {
                        $datasource->rollback();
                        throw new Exception('something could not be saved while processing ' . Inflector::pluralize($modelName) . '. Keep calm - nothing has been written into the Database for ' . Inflector::pluralize($modelName) . '!');
                    }
                }
            }
            $this->out('<success>Saving usage flags successfully finished!</success>');
        } catch (Exception $e) {
            $this->out($e->getMessage());
        }
    }


    /**
     * Map the usage flags for every host and service
     * calculates the usage flags so eg. if a service or a host is in use by two modules the bit value of the
     * constants will be summed.
     * @param $elements
     */
    protected function assignUsageFlagValue($elements) {
        try {
            foreach ($elements as $moduleName => $data) {
                $this->out('<info>Map usage flags for ' . $moduleName . '</info>');
                foreach ($data as $modelName => $elementIds) {

                    if (empty(constant(strtoupper($moduleName) . '_MODULE'))) {
                        throw new Exception('The Constant for ' . $moduleName . ' cannot not be found! Mapping cannot be processed - Exit');
                    }
                    foreach ($elementIds as $elementId) {
                        if (isset($this->usageFlagMapping[$modelName][$elementId])) {
                            $currentElement = $this->usageFlagMapping[$modelName][$elementId];
                            //has already an usage_flag but its also in use by an other module
                            //so we have to sum it with the value of this module
                            $this->usageFlagMapping[$modelName][$elementId] = $currentElement + constant(strtoupper($moduleName) . '_MODULE');
                        } else {
                            //no usage_flag set yet
                            $this->usageFlagMapping[$modelName][$elementId] = constant(strtoupper($moduleName) . '_MODULE');
                        }
                    }
                    $this->out('<success>'.$modelName.' done!</success>');
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }


    /**
     * get all host and service ids from the modules which are defined in $this->modules
     * this functions requires a getHost() and getService() Method in each committed module!
     * @param array $modules
     */
    protected function getModuleHostAndServices($modules = []) {
        try {
            if (empty($modules)) {
                throw new Exception('There are no Modules given');
            }

            foreach ($modules as $module) {
                $this->out('<info>Get host and services from model ' . $module . '</info>');
                //check if the needed classes exists
                $currentModel = $this->moduleInstances[$module];
                if (method_exists($currentModel, 'getHosts') && method_exists($currentModel, 'getServices')) {
                    $hosts = $currentModel->getHosts();
                    $services = $currentModel->getServices();
                    $this->moduleElements[$module] = [
                        'Host' => $hosts,
                        'Service' => $services
                    ];
                    $this->out('<success>done!</success>');
                } else {
                    $this->out('<info>There are no getHosts() and getServices() methods found for Module ' . $module . ' -> skip</info>');
                    continue;
                }
            }
        } catch (Exception $e) {
           $this->out('<info>'.$e->getMessage().'</info>');
        }
    }
}