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

class Hoststatus extends NagiosModuleAppModel
{
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
     * Return the host status as array for given uuid as string or array
     *
     * @param   string|array $uuid    UUID or array $uuid you want to get host status for
     * @param   array        $options for the find request (see cakephp's find for all options)
     *
     * @return array
     */
    private function byUuidMagic($uuid = null, $options = [])
    {
        if($uuid === null || empty($uuid)){
            return [];
        }
        $return = [];

        $_options = [
            'conditions' => [
                'Objects.name1'         => $uuid,
                'Objects.objecttype_id' => 1,
            ],
            'fields' => [
                'Hoststatus.*',
                'Objects.name1'
            ]
        ];

        $options = Hash::merge($_options, $options);

        $findType = 'all';
        if (!is_array($uuid)) {
            $findType = 'first';
        }


        $dbresult = $this->find($findType, $options);

        if($findType === 'first'){
            if(empty($dbresult)){
                return [];
            }
            return [
                'Hoststatus' => $dbresult['Hoststatus'],
            ];
        }

        $result = [];
        foreach($dbresult as $record){
            $result[$record['Objects']['name1']] = [
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
}
