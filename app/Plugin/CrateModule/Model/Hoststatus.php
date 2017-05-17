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

class Hoststatus extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'hoststatus';
    public $tablePrefix = 'statusengine_';

    /**
     * Return the host status as array for given uuid as stirng or array
     *
     * @param          string $uuid UUID or array $uuid you want to get host status for
     * @param    array $options for the find request (see cakephp's find for all options)
     *
     * @return array
     */
    private function byUuidMagic($uuid = null, $options = []){
        $return = [];

        $_options = [
            'conditions' => [
                'Hoststatus.hostname' => $uuid,
            ],
        ];

        $options = Hash::merge($_options, $options);
        if (isset($options['fields'])) {
            $options['fields'][] = 'Hoststatus.hostname';
        }

        $findType = 'all';
        if (!is_array($uuid)) {
            $findType = 'first';
        }

        $dbresult = $this->find($findType, $options);

        if($findType === 'first'){
            return [
                'Hoststatus' => $dbresult['Hoststatus'],
            ];
        }

        $result = [];
        foreach($dbresult as $record){
            $result[$record['Hoststatus']['hostname']] = [
                'Hoststatus' => $record['Hoststatus'],
            ];
        }
        return $result;
    }

    public function byUuid($uuid, $options = []){
        return $this->byUuidMagic($uuid, $options);
    }

    public function byUuids($uuids, $options = []){
        if(!is_array($uuids)){
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $options);
    }

    /**
     * @param HostConditions $HostConditions
     * @param array $conditions
     * @return array
     */
    public function getHostIndexQuery(HostConditions $HostConditions, $conditions = []) {
        $query = [
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' => 'Host.uuid = Hoststatus.hostname',
                ]
            ],
            'conditions' => $conditions,
            'array_difference' => [
                'Host.container_ids' =>
                    $HostConditions->getContainerIds(),
            ],
            'order' => $HostConditions->getOrder()
        ];
        return $query;
    }

}
