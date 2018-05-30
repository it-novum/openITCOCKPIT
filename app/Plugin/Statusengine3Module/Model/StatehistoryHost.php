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

use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;

class StatehistoryHost extends Statusengine3ModuleAppModel {
    public $useTable = 'host_statehistory';
    public $tablePrefix = 'statusengine_';

    public function __construct($id = false, $table = null, $ds = null, $useDynamicAssociations = true){
        parent::__construct($id, $table, $ds, $useDynamicAssociations);
        $this->virtualFields['state_type'] = 'StatehistoryHost.is_hardstate';
    }


    /**
     * @param StatehistoryHostConditions $StatehistoryHostConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(StatehistoryHostConditions $StatehistoryHostConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'hostname' => $StatehistoryHostConditions->getHostUuid(),
                'state_time >' => $StatehistoryHostConditions->getFrom(),
                'state_time <' => $StatehistoryHostConditions->getTo()
            ],
            'order' => $StatehistoryHostConditions->getOrder(),
        ];

        if ($StatehistoryHostConditions->getUseLimit()) {
            $query['limit'] = $StatehistoryHostConditions->getLimit();
        }

        if(!empty($StatehistoryHostConditions->getStates() && sizeof($StatehistoryHostConditions->getStates()) < 3)){
            $query['conditions']['state'] = $StatehistoryHostConditions->getStates();
        }

        foreach($StatehistoryHostConditions->getStateTypes() as $stateType){
            $query['conditions']['is_hardstate'] = $stateType;
        }

        if($StatehistoryHostConditions->hardStateTypeAndUpState()){
            $query['conditions']['OR'] = [
                'StatehistoryHost.is_hardstate' => 1,
                'StatehistoryHost.state' => 0
            ];
        }


        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

    /**
     * @param StatehistoryHostConditions $StatehistoryHostConditions
     * @return array
     */
    public function getLastRecord(StatehistoryHostConditions $StatehistoryHostConditions) {
        $query = [
            'conditions' => [
                'hostname' => $StatehistoryHostConditions->getHostUuid(),
                'state_time <=' => $StatehistoryHostConditions->getFrom(),
            ],
            'order'      => [
                'state_time' => 'DESC'
            ],
        ];

        return $query;
    }
}
