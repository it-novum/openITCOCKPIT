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

use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

class Servicestatus extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'servicestatus';
    public $primaryKey = 'servicestatus_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'service_object_id',
        ],
    ];

    /**
     * @param null $uuid
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array|bool
     */
    private function byUuidMagic($uuid = null, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null)
    {
        if($uuid === null || empty($uuid)){
            return [];
        }
        $return = [];

        $options = [
            'fields' => $ServicestatusFields->getFields(),
        ];

        $options['fields'][] = 'Objects.name2';

        if ($ServicestatusConditions !== null) {
            if ($ServicestatusConditions->hasConditions()) {
                $options['conditions'] = $ServicestatusConditions->getConditions();
            }
        }
        $options['conditions']['Objects.name2'] = $uuid;
        $options['conditions']['Objects.objecttype_id'] = 2;

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
                'Servicestatus' => $dbresult['Servicestatus'],
            ];
        }

        $result = [];
        foreach($dbresult as $record){
            $result[$record['Objects']['name2']] = [
                'Servicestatus' => $record['Servicestatus'],
            ];
        }
        return $result;

    }

    /**
     * @param string $uuid
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array|bool
     */
    public function byUuid($uuid, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null){
        return $this->byUuidMagic($uuid, $ServicestatusFields, $ServicestatusConditions);
    }

    /**
     * @param array $uuids
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array|bool
     */
    public function byUuids($uuids, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null){
        if(!is_array($uuids)){
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $ServicestatusFields, $ServicestatusConditions);
    }

}
