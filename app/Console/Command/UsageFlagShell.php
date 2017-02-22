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
    protected $modules = [
        'Autoreport',
        // 'Eventcorrelation',
    ];

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

    public function main() {
        //create Object instances
        $this->Host = new Host();
        $this->Service = new Service();

        $this->createModuleInstances($this->modules);


        $this->getModuleHostAndServices($this->modules);
        $this->assignUsageFlagValue($this->moduleElements);
        debug($this->moduleElements);
        $this->saveUsageFlag($this->moduleElements);
    }

    /**
     * Creates module instances on the fly
     * @param $modules
     */
    protected function createModuleInstances($modules){
        try{
            foreach ($this->modules as $module){
                if (!class_exists($module)) {
                    throw new Exception('Class ' . $module . ' could not be found');
                }
                $this->moduleInstances[$module] = new $module();
            }
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
    }


    /**
     * save the usage_flag for every host and service which is contained in $this->moduleElements
     * if something went wrong by saving the usage_flag nothing will be written into the database
     * exception will be thrown at which module the error occoured
     * @param array $elements
     */
    protected function saveUsageFlag($elements = []){
        try{
            if(empty($elements)){
                throw new Exception('No Data To save');
            }

            //@TODO THIS IS NOT CORRECT! we must instaciate the host and service model, not the module models!
            foreach ($elements as $moduleName => $data){
                $datasource = $this->moduleInstances[$moduleName]->getDatasource();
                $datasource->begin();
                $result = [];
                foreach ($data as $modelName => $elementIds){
                    foreach ($elementIds as $elementId){
                        $this->{$modelName}->id = $elementId;
                        $result[] = $this->{$modelName}->saveField('usage_flag', $this->usageFlagMapping[$modelName][$elementId]);
                    }
                }
                //check if something could not be saved
                if(!in_array(false, $result)){
                    $datasource->commit();
                }else{
                    throw new Exception('something could not be saved while processing '.$moduleName.' keep calm - Nothing has been written into the Database!');
                    $datasource->rollback();
                }
            }
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }


    protected function assignUsageFlagValue($elements){
        //vergleiche host und service ids von $moduleElements miteinander (von den Modul keys der ersten ebene) wenn
        //übereinstimmungen dabei sind muss der wert den diese id bekommt demensprechend mit der konstante hochgerechnet werden


        /** BEGIN TEST DATA */
    /*    $testData = [
            'Eventcorrelation' => [
                'Host' => [
                    '7',
                    '1',//HIT
                    '4',//HIT
                    '43',
                    '15',
                ],
                'Service' => [
                    '10',
                    '11',
                    '21',//HIT
                    '342',
                    '23',//HIT
                    '65',
                    '324567',
                ]
            ]
        ];
        $elements = array_merge($elements, $testData); */
        /** END TEST DATA */

        foreach ($elements as $moduleName => $data){
            $tmpElements = $elements;
            foreach ($data as $modelName => $elementIds){
                foreach ($elementIds as $elementId){
                    if(isset($this->usageFlagMapping[$modelName][$elementId])){
                        $currentElement = $this->usageFlagMapping[$modelName][$elementId];
                        //has already an usage_flag but its also in use by an other module
                        //so we have to sum it with the value of this module
                        $this->usageFlagMapping[$modelName][$elementId] = $currentElement + constant(strtoupper($moduleName).'_MODULE');
                    }else{
                        //no usage_flag set yet
                        $this->usageFlagMapping[$modelName][$elementId] = constant(strtoupper($moduleName).'_MODULE');
                    }
                }
            }
        }
    }

    protected function getModuleHostAndServices($modules = []) {
        try {
            if (empty($modules)) {
                throw new Exception('There is no Module given');
            }

            foreach ($modules as $module) {
                //check if the needed classes exists
                $currentModel = $this->moduleInstances[$module];
                if (method_exists($currentModel, 'getHosts') && method_exists($currentModel, 'getServices')) {
                    $hosts = $currentModel->getHosts();
                    $services = $currentModel->getServices();
                    $this->moduleElements[$module] = [
                        'Host' => $hosts,
                        'Service' => $services
                    ];
                } else {
                    $this->out('<info>There are no getHosts() and getServices() methods found for Module ' . $module . ' -> skip</info>');
                    continue;
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

}