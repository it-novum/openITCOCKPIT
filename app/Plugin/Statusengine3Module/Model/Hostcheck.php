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

class Hostcheck extends Statusengine3ModuleAppModel {
    public $useTable = 'hostchecks';
    public $tablePrefix = 'statusengine_';

    public function __construct($id = false, $table = null, $ds = null, $useDynamicAssociations = true){
        parent::__construct($id, $table, $ds, $useDynamicAssociations);
        $this->virtualFields['state_type'] = 'Hostcheck.is_hardstate';
    }

    /**
     * @param HostcheckConditions $HostcheckConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(HostcheckConditions $HostcheckConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'hostname' => $HostcheckConditions->getHostUuid(),
                'start_time >' => $HostcheckConditions->getFrom(),
                'start_time <' => $HostcheckConditions->getTo()
            ],
            'order' => $HostcheckConditions->getOrder(),
            'limit' => $HostcheckConditions->getLimit(),
        ];

        if(!empty($HostcheckConditions->getStates())){
            $query['conditions']['state'] = $HostcheckConditions->getStates();
        }

        if(!empty($HostcheckConditions->getStateTypes())){
            $query['conditions']['is_hardstate'] = (bool)$HostcheckConditions->getStateTypes()[0];
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
