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

use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;

/**
 * Class AcknowledgedHost
 * @deprecated
 */
class AcknowledgedHost extends NagiosModuleAppModel {

    public $useTable = 'acknowledgements';
    public $primaryKey = 'acknowledgement_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'object_id',
            'type'       => 'INNER'
        ],
    ];

    /**
     * @param string $uuid
     * @return array|null
     * @deprecated
     */
    public function byHostUuid($uuid = null) {
        $return = [];
        if ($uuid !== null) {
            $acknowledged = $this->find('first', [
                'conditions' => [
                    'Objects.name1'         => $uuid,
                    'Objects.objecttype_id' => 1,
                ],
                'order'      => [
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
     * @deprecated
     */
    public function getQuery(AcknowledgedHostConditions $AcknowledgedHostConditions, $paginatorConditions = []) {
        $query = [
            'conditions' => [
                'Objects.name1'         => $AcknowledgedHostConditions->getHostUuid(),
                'Objects.objecttype_id' => 1,
                'entry_time >'          => date('Y-m-d H:i:s', $AcknowledgedHostConditions->getFrom()),
                'entry_time <'          => date('Y-m-d H:i:s', $AcknowledgedHostConditions->getTo())
            ],
            'order'      => $AcknowledgedHostConditions->getOrder(),
        ];

        if ($AcknowledgedHostConditions->getUseLimit()) {
            $query['limit'] = $AcknowledgedHostConditions->getLimit();
        }

        if (!empty($AcknowledgedHostConditions->getStates())) {
            $query['conditions']['state'] = $AcknowledgedHostConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }
}
