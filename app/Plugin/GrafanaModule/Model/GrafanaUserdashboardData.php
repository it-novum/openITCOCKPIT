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

class GrafanaUserdashboardData extends GrafanaModuleAppModel {

    public $belongsTo = [
        'GrafanaUserdashboard' => [
            'className'  => 'GrafanaModule.GrafanaUserdashboard',
            'foreignKey' => 'userdashboard_id'
        ],
        'Host'                 => [
            'className'  => 'Host',
            'foreignKey' => 'host_id'
        ],
        'Service'               => [
            'className'  => 'Service',
            'foreignKey' => 'service_id'
        ],
    ];


    public function flattenData($data, $returnData = []) {
        foreach ($data as $contentKey => $content) {
            foreach ($content as $newKey => $newData) {
                if (is_array($newData) && !empty($newData)) {
                    $returnData = array_merge($returnData, $newData);
                    $this->flattenData($newData, $returnData);
                }
            }
        }
        return $returnData;
    }


    /**
     * Expands the GrafanaUserdashboardData data from the flat array to the multidimensional structure
     * also appends the services from the chosen host and also the available metrics from the chosen service
     * @param $dashboardData
     * @param $userContainerIds
     * @return array
     */
    public function expandData($dashboardData, $forGrafana = false, $userContainerIds = []) {
        $returnData = [];
        $hostUuids = [];
        $serviceUuids = [];
        $servicesByHostId = [];
        foreach ($dashboardData as $key => $data) {
            $data = $data['GrafanaUserdashboardData'];
            if (isset($data['row']) && isset($data['panel']) && isset($data['metric'])) {
                if (!empty($data['host_id']) && !empty($data['service_id'])) {
                    if (!$forGrafana) {
                        if (!empty($userContainerIds)) {
                            if (!isset($hostUuids[$data['host_id']])) {
                                $hostUuids[$data['host_id']] = $this->getHostUuid($data['host_id']);
                            }
                            if (!isset($serviceUuids[$data['service_id']])) {
                                $serviceUuids[$data['service_id']] = $this->getServiceUuid($data['service_id']);
                            }

                            //insert metrics
                            if (isset($hostUuids[$data['host_id']]) && isset($serviceUuids[$data['service_id']])) {
                                $data['metrics'] = $this->getPerfdataStructure($hostUuids[$data['host_id']], $serviceUuids[$data['service_id']], $userContainerIds);
                            }

                            if (!isset($servicesByHostId[$data['host_id']])) {
                                $servicesByHostId[$data['host_id']] = $this->getServicesFromHostId($data['host_id']);
                            }

                            //insert services
                            if (isset($servicesByHostId[$data['host_id']])) {
                                $data['services'] = $servicesByHostId[$data['host_id']];
                            }
                            $returnData['hosts'] = [];
                            $returnData['data'][(int)$data['row']][(int)$data['panel']][(int)$data['metric']] = $data;
                        }
                    } else {
                        $returnData[(int)$data['row']][(int)$data['panel']][(int)$data['metric']]['Host']['id'] = $data['host_id'];
                        $returnData[(int)$data['row']][(int)$data['panel']][(int)$data['metric']]['Service']['id'] = $data['service_id'];
                        $returnData[(int)$data['row']][(int)$data['panel']][(int)$data['metric']]['Service']['metric'] = $data['metric_value'];
                    }
                }
            }
        }
        return $returnData;
    }

    public function getHosts($containers) {
        if (empty($containers)) {
            return;
        }
        $hosts = $this->Host->find('list', [
            'recursive'  => -1,
            'conditions' => [
                'HostsToContainers.container_id' => $containers
            ],
            'joins'      => [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'fields'     => [
                'Host.name',
            ],
            'order'      => [
                'Host.name' => 'ASC',
            ],
            'group'      => [
                'Host.id'
            ],
            'limit'      => self::ITN_AJAX_LIMIT
        ]);


        return $this->Host->makeItJavaScriptAble($hosts);
    }


    private function getHostUuid($hostId) {
        $hostUuid = $this->Host->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Host.id' => $hostId
            ],
            'fields'     => [
                'Host.uuid'
            ]
        ]);
        if (!empty($hostUuid)) {
            return $hostUuid['Host']['uuid'];
        }
        return [];
    }

    private function getServiceUuid($serviceId) {
        $serviceUuid = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $serviceId
            ],
            'fields'     => [
                'Service.uuid'
            ]
        ]);
        if (!empty($serviceUuid)) {
            return $serviceUuid['Service']['uuid'];
        }
        return [];
    }

    private function getServicesFromHostId($hostId) {
        $this->Service->virtualFields['servicename'] = 'IF((Service.name IS NULL OR Service.name=""), Servicetemplate.name, Service.name)';
        $services = $this->Service->find('list', [
            //'recursive' => -1,
            'conditions' => [
                'Service.host_id' => $hostId
            ],
            'contain'    => [
                'Servicetemplate',
            ],
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.servicename'
            ]
        ]);
        return $this->Service->makeItJavaScriptAble($services);
    }

    public function getPerfdataStructure($host_uuid, $service_uuid, $userContainerIds) {
        $this->Rrd = ClassRegistry::init('Rrd');
        $perfdataStructure = [];
        if (!empty($host_uuid) && !empty($service_uuid)) {
            if ($this->Host->hostsByContainerId($userContainerIds, 'first', ['Host.uuid' => $host_uuid])) {
                $perfdataStructure = $this->Rrd->getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid);
            }
        }
        return $perfdataStructure;
    }


}
