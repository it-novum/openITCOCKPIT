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

class StatehistoryService extends NagiosModuleAppModel
{
    public $useTable = 'statehistory';
    public $primaryKey = 'statehistory_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'object_id',
            'conditions' => [
                'Objects.objecttype_id' => 2
            ],
            'type' => 'INNER'
        ],
    ];


    /**
     * @param StatehistoryServiceConditions $StatehistoryServiceConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(StatehistoryServiceConditions $StatehistoryServiceConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'Objects.name2' => $StatehistoryServiceConditions->getServiceUuid(),
                'StatehistoryService.state_time >' => date('Y-m-d H:i:s', $StatehistoryServiceConditions->getFrom()),
                'StatehistoryService.state_time <' => date('Y-m-d H:i:s', $StatehistoryServiceConditions->getTo())
            ],
            'order' => $StatehistoryServiceConditions->getOrder()
        ];

        if($StatehistoryServiceConditions->getUseLimit()){
            $query['limit'] = $StatehistoryServiceConditions->getLimit();
        }

        if(!empty($StatehistoryServiceConditions->getStates())){
            $query['conditions']['StatehistoryService.state'] = $StatehistoryServiceConditions->getStates();
        }

        if(!empty($StatehistoryServiceConditions->getStateTypes())){
            $query['conditions']['StatehistoryService.state_type'] = $StatehistoryServiceConditions->getStateTypes();
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
            'recursive'  => -1,
            'fields'     => [
                'StatehistoryService.object_id',
                'StatehistoryService.state_time',
                'StatehistoryService.state',
                'StatehistoryService.state_type',
                'StatehistoryService.last_state',
                'StatehistoryService.last_hard_state'
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = StatehistoryService.object_id AND Objects.objecttype_id =2'
                ],
            ],
            'conditions' => [
                'AND' => [
                    'Objects.name2' => $StatehistoryServiceConditions->getServiceUuid(),
                    'StatehistoryService.state_time <= "' . date('Y-m-d H:i:s', $StatehistoryServiceConditions->getFrom()) . '"'
                ],
            ],
            'order'      => [
                'StatehistoryService.state_time' => 'DESC'
            ],
        ];
        return $query;
    }
}
