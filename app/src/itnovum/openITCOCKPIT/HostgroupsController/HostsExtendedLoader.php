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


class HostsExtendedLoader
{

    /**
     * @var Hostgroup
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
     * HostsExtendedLoader constructor.
     *
     * @param \Hostgroup $Hostgroup
     * @param array      $containerIds
     * @param int        $hostgroupId
     */
    public function __construct(\Hostgroup $Hostgroup, $containerIds, $hostgroupId)
    {
        $this->Hostgroup = $Hostgroup;
        $this->containerIds = $containerIds;
        $this->hostgroupId = $hostgroupId;
    }

    /**
     * @return array|null
     */
    public function loadHosts()
    {
        return $this->Hostgroup->find('all', $this->getQueryHostsOnly());
    }

    /**
     * @return array|null
     */
    public function loadHostsWithStatus()
    {
        return $this->Hostgroup->find('all', $this->getQueryWithHoststatus());

    }

    private function getQueryWithHoststatus()
    {
        $query = [
            'recursive'  => -1,
            'contain'    => [],
            'joins'      => [
                [
                    'table'      => 'containers',
                    'type'       => 'LEFT',
                    'alias'      => 'Container',
                    'conditions' => 'Container.id = Hostgroup.container_id',
                ],
                [
                    'table'      => 'hosts_to_hostgroups',
                    'type'       => 'LEFT',
                    'alias'      => 'Host2Hostgroups',
                    'conditions' => 'Host2Hostgroups.hostgroup_id = Hostgroup.id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'LEFT',
                    'alias'      => 'Host',
                    'conditions' => 'Host.id = Host2Hostgroups.host_id',
                ],
                [
                    'table'      => 'nagios_objects',
                    'type'       => 'INNER',
                    'alias'      => 'Objects',
                    'conditions' => 'Objects.name1 = Host.uuid',
                ],
                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Hoststatus.host_object_id = Objects.object_id',
                ],
            ],
            'fields'     => [
                'Hostgroup.id',
                'Hostgroup.uuid',
                'Hostgroup.container_id',
                'Hostgroup.description',
                'Hostgroup.hostgroup_url',

                'Container.id',
                'Container.parent_id',
                'Container.name',

                'Host.id',
                'Host.name',
                'Host.uuid',


                'Objects.object_id',
                'Objects.name1',

                'Hoststatus.current_state',
                'Hoststatus.is_flapping',
                'Hoststatus.problem_has_been_acknowledged',
                'Hoststatus.scheduled_downtime_depth',
                'Hoststatus.last_check',
                'Hoststatus.next_check',
                'Hoststatus.active_checks_enabled',
                'Hoststatus.last_hard_state_change',
            ],
            'order'      => [
                'Host.name' => 'asc',
            ],
            'conditions' => [
                'Container.id' => $this->containerIds,
            ],
        ];

        if (!$this->hostgroupId !== null) {
            $query['conditions']['Hostgroup.id'] = $this->hostgroupId;
        }

        return $query;
    }

    private function getQueryHostsOnly()
    {
        $query = [
            'recursive' => -1,
            'contain'   => [],
            'joins'     => [
                [
                    'table'      => 'containers',
                    'type'       => 'LEFT',
                    'alias'      => 'Container',
                    'conditions' => 'Container.id = Hostgroup.container_id',
                ],
                [
                    'table'      => 'hosts_to_hostgroups',
                    'type'       => 'LEFT',
                    'alias'      => 'Host2Hostgroups',
                    'conditions' => 'Host2Hostgroups.hostgroup_id = Hostgroup.id',
                ],
                [
                    'table'      => 'hosts',
                    'type'       => 'LEFT',
                    'alias'      => 'Host',
                    'conditions' => 'Host.id = Host2Hostgroups.host_id',
                ],
            ],
            'fields'    => [
                'Host.id',
                'Host.name',
                'Host.uuid',
            ],

            'order' => [
                'Host.name' => 'asc',
            ],

            'conditions' => [
                'Container.id' => $this->containerIds,
            ],
        ];

        if (!$this->hostgroupId !== null) {
            $query['conditions']['Hostgroup.id'] = $this->hostgroupId;
        }

        return $query;
    }

}