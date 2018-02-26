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

use itnovum\openITCOCKPIT\Core\StatehistoryServiceConditions;

class StatehistoryService extends CrateModuleAppModel {
    public $useDbConfig = 'Crate';
    public $useTable = 'service_statehistory';
    public $tablePrefix = 'statusengine_';

    public function __construct($id = false, $table = null, $ds = null, $useDynamicAssociations = true){
        parent::__construct($id, $table, $ds, $useDynamicAssociations);
        $this->virtualFields['state_type'] = 'StatehistoryService.is_hardstate';
    }


    /**
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(StatehistoryServiceConditions $StatehistoryServiceConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'service_description' => $StatehistoryServiceConditions->getServiceUuid(),
                'state_time >' => $StatehistoryServiceConditions->getFrom(),
                'state_time <' => $StatehistoryServiceConditions->getTo()
            ],
            'order' => $StatehistoryServiceConditions->getOrder(),
        ];

        if($StatehistoryServiceConditions->getUseLimit()){
            $query['limit'] = $StatehistoryServiceConditions->getLimit();
        }

        if(!empty($StatehistoryServiceConditions->getStates()) && sizeof($StatehistoryServiceConditions->getStates()) < 4){
            $query['conditions']['state'] = $StatehistoryServiceConditions->getStates();
        }

        foreach($StatehistoryServiceConditions->getStateTypes() as $stateType){
            if($stateType === 0){
                $query['conditions']['is_hardstate'] = false;
            }

            if($stateType === 1){
                $query['conditions']['is_hardstate'] = true;
            }
        }

        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

    /**
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @return array
     */
    public function getLastRecord(StatehistoryServiceConditions $StatehistoryServiceConditions) {
        $query = [
            'conditions' => [
                'service_description' => $StatehistoryServiceConditions->getServiceUuid(),
                'state_time <=' => $StatehistoryServiceConditions->getFrom(),
            ],
            'order'      => [
                'state_time' => 'DESC'
            ],
        ];

        return $query;
    }
}
