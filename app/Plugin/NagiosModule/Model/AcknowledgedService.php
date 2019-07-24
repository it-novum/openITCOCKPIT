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

use itnovum\openITCOCKPIT\Core\AcknowledgedServiceConditions;

/**
 * Class AcknowledgedService
 * @deprecated
 */
class AcknowledgedService extends NagiosModuleAppModel {

    public $useTable = 'acknowledgements';
    public $primaryKey = 'acknowledgement_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'object_id',
        ],
    ];

    /**
     * @param null $uuid
     * @return array|null
     * @deprecated
     */
    public function byServiceUuid($uuid = null) {
        return $this->byUuid($uuid);
    }

    /**
     * @param string $uuid
     * @return array|null
     * @deprecated
     */
    public function byUuid($uuid = null) {
        $return = [];
        if ($uuid !== null) {
            $acknowledged = $this->find('first', [
                'conditions' => [
                    'Objects.name2'         => $uuid,
                    'Objects.objecttype_id' => 2,
                ],
                'order'      => [
                    'AcknowledgedService.entry_time' => 'DESC',
                ],
            ]);

            return $acknowledged;

        }

        return $return;
    }


    /**
     * @param AcknowledgedServiceConditions $AcknowledgedServiceConditions
     * @param array $paginatorConditions
     * @return array
     * @deprecated
     */
    public function getQuery(AcknowledgedServiceConditions $AcknowledgedServiceConditions, $paginatorConditions = []) {
        $query = [
            'conditions' => [
                'Objects.name2'                    => $AcknowledgedServiceConditions->getServiceUuid(),
                'AcknowledgedService.entry_time >' => date('Y-m-d H:i:s', $AcknowledgedServiceConditions->getFrom()),
                'AcknowledgedService.entry_time <' => date('Y-m-d H:i:s', $AcknowledgedServiceConditions->getTo())
            ],
            'order'      => $AcknowledgedServiceConditions->getOrder()
        ];

        if ($AcknowledgedServiceConditions->getUseLimit()) {
            $query['limit'] = $AcknowledgedServiceConditions->getLimit();
        }

        if (!empty($AcknowledgedServiceConditions->getStates())) {
            $query['conditions']['AcknowledgedService.state'] = $AcknowledgedServiceConditions->getStates();
        }

        //Merge ListFilter conditions
        $query['conditions'] = Hash::merge($paginatorConditions, $query['conditions']);

        return $query;
    }
}
