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

class CrateService extends CrateModuleAppModel {

    public $useDbConfig = 'Crate';
    public $useTable = 'services';
    public $tablePrefix = 'openitcockpit_';

    /**
     * @param ServiceConditions $ServiceConditions
     * @param array $conditions
     * @return array
     */
    public function getServiceNotMonitoredQuery(ServiceConditions $ServiceConditions, $conditions = []) {
        //Add some duct tape
        $origConditions = $conditions;
        $conditions = [];

        foreach ($origConditions as $key => $value) {
            $key = str_replace('Service.', 'CrateService.', $key);
            $conditions[$key] = $value;
        }

        //Glue the virtual field to the real field
        if (isset($conditions['CrateService.servicename LIKE'])) {
            $serviceNameCondition = $conditions['CrateService.servicename LIKE'];
            unset($conditions['CrateService.servicename LIKE']);
            $conditions['CrateService.name LIKE'] = $serviceNameCondition;
        }

        $query = [
            'recursive'        => -1,
            'conditions'       => $conditions,
            'fields'           => [
                'CrateService.id',
                'CrateService.uuid',
                'CrateService.name',
                'CrateService.active_checks_enabled',

                'Host.name',
                'Host.id',
                'Host.uuid',
                'Host.address',
                'Host.container_ids',

                'Servicestatus.service_description'
            ],
            'order'            => $ServiceConditions->getOrder(),
            'joins'            => [
                [
                    'table'      => 'openitcockpit_hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'CrateService.host_id = Host.id',
                ],
                [
                    'table'      => 'statusengine_servicestatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_description = CrateService.uuid',
                ],
                [
                    'table'      => 'statusengine_hoststatus',
                    'type'       => 'LEFT OUTER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.hostname = Host.uuid',
                ],
            ],
            'array_difference' => [
                'Host.container_ids' =>
                    $ServiceConditions->getContainerIds()
            ],
        ];


        $query['conditions']['CrateService.disabled'] = (bool)$ServiceConditions->includeDisabled();

        if ($ServiceConditions->getHostId()) {
            $query['conditions']['CrateService.host_id'] = $ServiceConditions->getHostId();
        }

        $query['conditions'][] = 'Servicestatus.service_description IS NULL';

        return $query;
    }

    public function virtualFieldsForServicesNotMonitored() {
        $this->virtualFields['"CrateService.servicename"'] = 'CrateService.name';
    }

}
