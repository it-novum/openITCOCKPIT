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

use \itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;

class AcknowledgedHost extends Statusengine3ModuleAppModel {
    public $useTable = 'host_acknowledgements';
    public $tablePrefix = 'statusengine_';

    /**
     * @param string $uuid
     * @return array|null
     */
    public function byHostUuid($uuid = null){
        $return = [];
        if ($uuid !== null) {
            $acknowledged = $this->find('first', [
                'conditions' => [
                    'hostname' => $uuid,
                ],
                'order' => [
                    'AcknowledgedHost.entry_time' => 'DESC',
                ],
            ]);

            return $acknowledged;

        }

        return $return;
    }

    /**
     * @param AcknowledgedHostConditions $AcknowledgedHostConditions
     * @param array $paginatorConditions
     * @return array
     */
    public function getQuery(AcknowledgedHostConditions $AcknowledgedHostConditions, $paginatorConditions = []){
        $query = [
            'conditions' => [
                'hostname' => $AcknowledgedHostConditions->getHostUuid(),
                'entry_time >' => $AcknowledgedHostConditions->getFrom(),
                'entry_time <' => $AcknowledgedHostConditions->getTo()
            ],
            'order' => $AcknowledgedHostConditions->getOrder(),
            'limit' => $AcknowledgedHostConditions->getLimit(),
        ];

        if (!empty($AcknowledgedHostConditions->getStates())) {
            $query['conditions']['state'] = $AcknowledgedHostConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }

}
