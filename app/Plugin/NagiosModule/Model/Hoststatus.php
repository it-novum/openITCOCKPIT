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

use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;

class Hoststatus extends NagiosModuleAppModel {
    //public $useDbConfig = 'nagios';
    public $useTable = 'hoststatus';
    public $primaryKey = 'hoststatus_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'host_object_id',
        ],
    ];

    /**
     * @param null $uuid
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array
     */
    private function byUuidMagic($uuid = null, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        if ($uuid === null || empty($uuid)) {
            return [];
        }
        $return = [];

        $options = [
            'fields' => $HoststatusFields->getFields(),
        ];

        $options['fields'][] = 'Objects.name1';

        if ($HoststatusConditions !== null) {
            if ($HoststatusConditions->hasConditions()) {
                $options['conditions'] = $HoststatusConditions->getConditions();
            }
        }
        $options['conditions']['Objects.name1'] = $uuid;
        $options['conditions']['Objects.objecttype_id'] = 1;

        $findType = 'all';
        if (!is_array($uuid)) {
            $findType = 'first';
        }


        $dbresult = $this->find($findType, $options);

        if ($findType === 'first') {
            if (empty($dbresult)) {
                return [];
            }
            return [
                'Hoststatus' => $dbresult['Hoststatus'],
            ];
        }

        $result = [];
        foreach ($dbresult as $record) {
            $result[$record['Objects']['name1']] = [
                'Hoststatus' => $record['Hoststatus'],
            ];
        }
        return $result;

    }

    /**
     * @param $uuid
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array|string
     */
    public function byUuid($uuid, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        return $this->byUuidMagic($uuid, $HoststatusFields, $HoststatusConditions);
    }

    /**
     * @param $uuids
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array
     */
    public function byUuids($uuids, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        if (!is_array($uuids)) {
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $HoststatusFields, $HoststatusConditions);
    }
}
