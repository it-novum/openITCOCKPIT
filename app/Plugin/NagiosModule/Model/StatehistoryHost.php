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

class StatehistoryHost extends NagiosModuleAppModel {
    public $useTable = 'statehistory';
    public $primaryKey = 'statehistory_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'object_id',
            'conditions' => [
                'Objects.objecttype_id' => 1
            ],
            'type'       => 'INNER'
        ],
    ];


    /**
     * @param StatehistoryHostConditions $StatehistoryHostConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(StatehistoryHostConditions $StatehistoryHostConditions, $paginatorConditions = []) {
        $query = [
            'conditions' => [
                'Objects.name1'                 => $StatehistoryHostConditions->getHostUuid(),
                'StatehistoryHost.state_time >' => date('Y-m-d H:i:s', $StatehistoryHostConditions->getFrom()),
                'StatehistoryHost.state_time <' => date('Y-m-d H:i:s', $StatehistoryHostConditions->getTo())
            ],
            'order'      => $StatehistoryHostConditions->getOrder()
        ];

        if ($StatehistoryHostConditions->getUseLimit()) {
            $query['limit'] = $StatehistoryHostConditions->getLimit();
        }

        if(!empty($StatehistoryHostConditions->getStates() && sizeof($StatehistoryHostConditions->getStates()) < 3)){
            $query['conditions']['StatehistoryHost.state'] = $StatehistoryHostConditions->getStates();
        }

        if (!empty($StatehistoryHostConditions->getStateTypes())) {
            $query['conditions']['StatehistoryHost.state_type'] = $StatehistoryHostConditions->getStateTypes();
        }

        if($StatehistoryHostConditions->hardStateTypeAndUpState()){
            $query['conditions']['OR'] = [
                'StatehistoryHost.state_type' => 1,
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
            'recursive'  => -1,
            'fields'     => [
                'StatehistoryHost.object_id',
                'StatehistoryHost.state_time',
                'StatehistoryHost.state',
                'StatehistoryHost.state_type',
                'StatehistoryHost.last_state',
                'StatehistoryHost.last_hard_state'
            ],
            'joins'      => [
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.object_id = StatehistoryHost.object_id AND Objects.objecttype_id =1'
                ],
            ],
            'conditions' => [
                'AND' => [
                    'Objects.name1' => $StatehistoryHostConditions->getHostUuid(),
                    'StatehistoryHost.state_time <= "' . date('Y-m-d H:i:s', $StatehistoryHostConditions->getFrom()) . '"'
                ],
            ],
            'order'      => [
                'StatehistoryHost.state_time' => 'DESC'
            ],
        ];
        return $query;
    }
}
