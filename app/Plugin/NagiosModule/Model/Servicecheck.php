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

use itnovum\openITCOCKPIT\Core\ServicechecksConditions;

class Servicecheck extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'servicechecks';
    public $primaryKey = 'servicecheck_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'service_object_id',
            'type' => 'INNER'
        ],
    ];

    /**
     * @param ServicechecksConditions $ServicecheckConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(ServicechecksConditions $ServicecheckConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'Objects.name2' => $ServicecheckConditions->getServiceUuid(),
                'start_time >' => date('Y-m-d H:i:s', $ServicecheckConditions->getFrom()),
                'start_time <' => date('Y-m-d H:i:s', $ServicecheckConditions->getTo())
            ],
            'fields' => [
                'Servicecheck.state',
                'Servicecheck.start_time',
                'Servicecheck.current_check_attempt',
                'Servicecheck.max_check_attempts',
                'Servicecheck.state_type',
                'Servicecheck.output',
                'Servicecheck.perfdata'
            ],
            'order' => $ServicecheckConditions->getOrder(),
            'limit' => $ServicecheckConditions->getLimit(),
        ];

        if(!empty($ServicecheckConditions->getStates())){
            $query['conditions']['state'] = $ServicecheckConditions->getStates();
        }

        if(!empty($ServicecheckConditions->getStateTypes())){
            $query['conditions']['state_type'] = $ServicecheckConditions->getStateTypes();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($query['conditions'], $paginatorConditions);

        return $query;
    }

}
