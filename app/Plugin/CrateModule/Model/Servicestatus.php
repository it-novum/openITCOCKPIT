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
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;

class Servicestatus extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'servicestatus';
    public $tablePrefix = 'statusengine_';


    /**
     * @param null $uuid
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array|bool
     */
    private function byUuidMagic($uuid = null, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null) {
        if($uuid === null || empty($uuid)){
            return [];
        }

        $options = [
            'fields' => $ServicestatusFields->getFields(),
        ];

        if ($ServicestatusConditions !== null) {
            if ($ServicestatusConditions->hasConditions()) {
                $options['conditions'] = $ServicestatusConditions->getConditions();
            }
        }
        $options['conditions']['Servicestatus.service_description'] = $uuid;

        $options['fields'][] = 'Servicestatus.service_description';

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

    /**
     * @param $uuid
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array
     */
    public function byUuid($uuid, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null) {
        return $this->byUuidMagic($uuid, $ServicestatusFields, $ServicestatusConditions);
    }

    /**
     * @param array $uuids
     * @param ServicestatusFields $ServicestatusFields
     * @param null|ServicestatusConditions $ServicestatusConditions
     * @return array
     */
    public function byUuids($uuids, ServicestatusFields $ServicestatusFields, $ServicestatusConditions = null) {
        if (!is_array($uuids)) {
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $ServicestatusFields, $ServicestatusConditions);
    }

    public function virtualFieldsForIndexAndServiceList() {
        $this->virtualFields['"Service.servicename"'] = 'Service.name';

    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceIndexQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        if (isset($conditions['Service.keywords rlike'])) {
            $values = [];
            foreach (explode('|', $conditions['Service.keywords rlike']) as $value) {
                $values[] = sprintf('.*%s.*', $value);
            }
            unset($conditions['Service.keywords rlike']);
            $conditions['Service.tags rlike'] = implode('|', $values);
        }

        if (isset($conditions['Servicestatus.problem_has_been_acknowledged'])) {
            $conditions['Servicestatus.problem_has_been_acknowledged'] = (bool)$conditions['Servicestatus.problem_has_been_acknowledged'];
        }

        if (isset($conditions['Servicestatus.active_checks_enabled'])) {
            $conditions['Servicestatus.active_checks_enabled'] = (bool)$conditions['Servicestatus.active_checks_enabled'];
        }


        if (isset($conditions['Service.servicename LIKE'])) {
            $serviceNameCondition = $conditions['Service.servicename LIKE'];
            unset($conditions['Service.servicename LIKE']);
            $conditions['Service.name LIKE'] = $serviceNameCondition;
        }

        //todo CrateDB bug, check if LEFT join can be refactored with INNER join
        //https://github.com/crate/crate/issues/5747
        $query = [
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.active_checks_enabled',
                'Service.tags',

                'Servicestatus.current_state',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.last_state_change',
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
                'Host.satellite_id',

                'Hoststatus.current_state',
                'Hoststatus.is_flapping',
                'Hoststatus.last_hard_state_change'

            ],
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'INNER',
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

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['Service.host_id'] = $ServiceConditions->getHostId();
        }

        return $query;
    }

    /**
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getServicestatusCount($MY_RIGHTS){
        $servicestatusCount = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];

        $this->virtualFields = [
            'count' => 'COUNT(DISTINCT Servicestatus.service_description)'
        ];
        $query = [
            'fields' => [
                'Servicestatus.current_state',
            ],
            'joins' => [
                [
                    'table' => 'openitcockpit_hosts',
                    'type' => 'INNER',
                    'alias' => 'Host',
                    'conditions' => 'Host.uuid = Servicestatus.hostname',
                ]
            ],
            'conditions' => [
                'Service.disabled'                  => false,
                'Servicestatus.current_state >'     => 0,
            ],
            'array_difference' => [
                'Host.container_ids' =>
                    $MY_RIGHTS
            ],
            'group'      => [
                'Servicestatus.current_state',
            ],
        ];

        $servicestatusCountResult = $this->find('all', $query);

        foreach ($servicestatusCountResult as $servicestatus) {
            $servicestatusCount[$servicestatus['Servicestatus']['current_state']] = (int)$servicestatus[0]['count'];
        }
        return $servicestatusCount;
    }

}
