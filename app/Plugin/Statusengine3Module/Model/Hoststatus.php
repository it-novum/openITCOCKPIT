<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;

class Hoststatus extends Statusengine3ModuleAppModel {
    public $useTable = 'hoststatus';
    public $tablePrefix = 'statusengine_';

    /**
     * @param null $uuid
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array|bool
     */
    private function byUuidMagic($uuid = null, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        if ($uuid === null || empty($uuid)) {
            return [];
        }

        $options = [
            'fields' => $HoststatusFields->getFields(),
        ];

        if ($HoststatusConditions !== null) {
            if ($HoststatusConditions->hasConditions()) {
                $options['conditions'] = $HoststatusConditions->getConditions();
            }
        }
        $options['conditions']['Hoststatus.hostname'] = $uuid;

        $options['fields'][] = 'Hoststatus.hostname';

        $findType = 'all';
        if (!is_array($uuid)) {
            $findType = 'first';
        }

        $dbresult = $this->find($findType, $options);

        if (empty($dbresult)) {
            return false;
        }

        if ($findType === 'first') {
            return [
                'Hoststatus' => $dbresult['Hoststatus'],
            ];
        }

        $result = [];
        foreach ($dbresult as $record) {
            $result[$record['Hoststatus']['hostname']] = [
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
