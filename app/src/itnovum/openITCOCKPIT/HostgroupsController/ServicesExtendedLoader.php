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

namespace itnovum\openITCOCKPIT\HostgroupsController;


class ServicesExtendedLoader
{

    /**
     * @var \Hostgroup
     */
    private $Hostgroup;

    /**
     * @var array
     */
    private $containerIds = [];

    /**
     * @var null|int
     */
    private $hostgroupId = null;

    /**
     * @var \Service
     */
    private $Service = null;


    /**
     * ServicesExtendedLoader constructor.
     * @param \Hostgroup $Hostgroup
     * @param array $containerIds
     * @param int $hostgroupId
     */
    public function __construct(\Hostgroup $Hostgroup, $containerIds, $hostgroupId){
        $this->Hostgroup = $Hostgroup;
        $this->containerIds = $containerIds;
        $this->hostgroupId = $hostgroupId;
    }

    public function setServiceModel(\Service $Service){
        $this->Service = $Service;
    }

    /**
     * @param int $hostId
     * @return array
     */
    public function loadServicesWithStatusByHostId($hostId){
        $records = $this->Service->find('all', $this->getQueryWithServicestatus($hostId));
        $result = [];
        foreach($records as $record){
            if($record['Service']['name'] === null || $record['Service']['name'] === ''){
                $record['Service']['name'] = $record['Servicetemplate']['name'];
            }
            $result[] = $record;
        }

        unset($records);
        $result = \Hash::sort($result, '{n}.Service.name', 'asc');
        return $result;
    }

    public function loadServicesCumulated(){
        return $this->Hostgroup->find('all', $this->getQueryServicestatusCumulated());
    }

    private function getQueryWithServicestatus($hostId){
        $query = [
            'recursive' => -1,
            'contain' => [],
            'conditions' => [
                'Service.host_id' => $hostId
            ],
            'joins' => [
                [
                    'table' => 'servicetemplates',
                    'type' => 'INNER',
                    'alias' => 'Servicetemplate',
                    'conditions' => 'Servicetemplate.id = Service.servicetemplate_id'
                ],
                [
                    'table' => 'nagios_objects',
                    'type' => 'INNER',
                    'alias' => 'Objects',
                    'conditions' => 'Objects.name2 = Service.uuid'
                ],
                [
                    'table' => 'nagios_servicestatus',
                    'type' => 'INNER',
                    'alias' => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = Objects.object_id'
                ],
            ],
            'fields' => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.servicetemplate_id',

                'Servicetemplate.id',
                'Servicetemplate.name',

                'Objects.object_id',
                'Objects.name1',

                'Servicestatus.current_state',
                'Servicestatus.is_flapping',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.active_checks_enabled',
                'Servicestatus.last_hard_state_change',
                'Servicestatus.process_performance_data'
            ]
        ];

        return $query;
    }

    private function getQueryServicestatusCumulated(){
        $query = [
            'recursive' => -1,
            'contain' => [],
            'joins' => [
                [
                    'table' => 'containers',
                    'type' => 'LEFT',
                    'alias' => 'Container',
                    'conditions' => 'Container.id = Hostgroup.container_id'
                ],
                [
                    'table' => 'hosts_to_hostgroups',
                    'type' => 'LEFT',
                    'alias' => 'Host2Hostgroups',
                    'conditions' => 'Host2Hostgroups.hostgroup_id = Hostgroup.id'
                ],
                [
                    'table' => 'hosts',
                    'type' => 'LEFT',
                    'alias' => 'Host',
                    'conditions' => 'Host.id = Host2Hostgroups.host_id'
                ],
                [
                    'table' => 'services',
                    'type' => 'LEFT',
                    'alias' => 'Service',
                    'conditions' => 'Service.host_id = Host.id'
                ],
                [
                    'table' => 'nagios_objects',
                    'type' => 'INNER',
                    'alias' => 'Objects',
                    'conditions' => 'Objects.name2 = Service.uuid'
                ],
                [
                    'table' => 'nagios_servicestatus',
                    'type' => 'INNER',
                    'alias' => 'Servicestatus',
                    'conditions' => 'Servicestatus.service_object_id = Objects.object_id'
                ],
            ],
            'fields' => [
                'Host.id',
                'MAX(Servicestatus.current_state) as cumulated',
            ],
            'order' => [
                'Container.name' => 'asc'
            ],
            'conditions' => [
                'Container.id' => $this->containerIds,
            ],
            'group' => [
                'Host.id'
            ]
        ];

        if(!$this->hostgroupId !== null){
            $query['conditions']['Hostgroup.id'] = $this->hostgroupId;
        }

        return $query;
    }

}