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

use itnovum\openITCOCKPIT\Core\ServiceConditions;

class Servicestatus extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'servicestatus';
    public $tablePrefix = 'statusengine_';


    /**
     * Return the service status as array for given uuid as string or array
     *
     * @param    string $uuid UUID or array $uuid you want to get service status for
     * @param    array $options for the find request (see cakephp's find for all options)
     *
     * @return array
     */
    private function byUuidMagic($uuid = null, $options = []){
        $return = [];

        $_options = [
            'conditions' => [
                'Servicestatus.service_description' => $uuid,
            ],
        ];

        $options = Hash::merge($_options, $options);
        if (isset($options['fields'])) {
            $options['fields'][] = 'Servicestatus.service_description';
        }

        $findType = 'all';
        if (!is_array($uuid)) {
            $findType = 'first';
        }

        $dbresult = $this->find($findType, $options);

        if ($findType === 'first') {
            return [
                'Servicestatus' => $dbresult['Servicestatus'],
            ];
        }

        $result = [];
        foreach ($dbresult as $record) {
            $result[$record['Servicestatus']['service_description']] = [
                'Servicestatus' => $record['Servicestatus'],
            ];
        }
        return $result;
    }

    public function byUuid($uuid, $options = []){
        return $this->byUuidMagic($uuid, $options);
    }

    public function byUuids($uuids, $options = []){
        if (!is_array($uuids)) {
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $options);
    }

    public function virtualFieldsForIndex(){
        $this->virtualFields['"Host.id"'] = 'Host.id';
        $this->virtualFields['"Host.uuid"'] = 'Host.uuid';
        $this->virtualFields['"Host.name"'] = 'Host.name';

        $this->virtualFields['"Service.id"'] =  'Service.id';
        $this->virtualFields['"Service.uuid"'] = 'Service.uuid';
        $this->virtualFields['"Service.name"'] = 'Service.name';

        $this->virtualFields['"Hoststatus.current_state"'] = 'Hoststatus.current_state';

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceIndexQuery(ServiceConditions $ServiceConditions, $conditions = []){
        if (isset($conditions['Service.keywords rlike'])) {
            $values = [];
            foreach (explode('|', $conditions['Service.keywords rlike']) as $value) {
                $values[] = sprintf('.*%s.*', $value);
            }
            unset($conditions['Service.keywords rlike']);
            $conditions['Service.tags rlike'] = implode('|', $values);
        }

        //todo CrateDB bug, check if LEFT join can be refactored with INNER join
        //https://github.com/crate/crate/issues/5747
        $query = [
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                //'Service.active_checks_enabled',
                //'Service.tags',

                'Servicestatus.current_state',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.output',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.is_hardstate',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.acknowledgement_type',
                'Servicestatus.is_flapping',


                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.address',

                'Hoststatus.current_state',
                'Hoststatus.last_hard_state_change'
            ],
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'LEFT',
                    'alias' => 'Host',
                    'conditions' => 'Host.uuid = Servicestatus.hostname',
                ],
                [
                    'table' => 'openitcockpit_services',
                    'type' => 'INNER',
                    'alias' => 'Service',
                    'conditions' => 'Service.uuid = Servicestatus.service_description',
                ],
                [
                    'table' => 'statusengine_hoststatus',
                    'type' => 'INNER',
                    'alias' => 'Hoststatus',
                    'conditions' => 'Hoststatus.hostname = Host.uuid',
                ]
            ],
            'conditions' => $conditions,
            'array_difference' => [
                'Host.container_ids' =>
                    $ServiceConditions->getContainerIds(),
            ],
            'order' => $ServiceConditions->getOrder()
        ];
        return $query;
    }

}
