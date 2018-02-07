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

use itnovum\openITCOCKPIT\Core\HostcheckConditions;

class Hostcheck extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'hostchecks';
    public $primaryKey = 'hostcheck_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'host_object_id',
            'type' => 'INNER'
        ],
    ];

    /**
     * @param HostcheckConditions $HostcheckConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(HostcheckConditions $HostcheckConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'Objects.name1' => $HostcheckConditions->getHostUuid(),
                'start_time >' => date('Y-m-d H:i:s', $HostcheckConditions->getFrom()),
                'start_time <' => date('Y-m-d H:i:s', $HostcheckConditions->getTo())
            ],
            'fields' => [
                'Hostcheck.state',
                'Hostcheck.start_time',
                'Hostcheck.current_check_attempt',
                'Hostcheck.max_check_attempts',
                'Hostcheck.state_type',
                'Hostcheck.output',
            ],
            'order' => $HostcheckConditions->getOrder(),
            'limit' => $HostcheckConditions->getLimit(),
        ];

        if(!empty($HostcheckConditions->getStates())){
            $query['conditions']['state'] = $HostcheckConditions->getStates();
        }

        if(!empty($HostcheckConditions->getStateTypes())){
            $query['conditions']['state_type'] = $HostcheckConditions->getStateTypes();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($query['conditions'], $paginatorConditions);

        return $query;
    }

}