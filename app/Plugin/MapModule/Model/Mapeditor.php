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

class Mapeditor extends MapModuleAppModel {
    public $useTable = false;

    /**
     * @var array used for recursive mapstatus
     */
    public $mapElements = [];

    //@TODO check this file for obsolete functions!

    public function prepareForSave($request) {
        $filtered = [];
        foreach ($request as $key => $mapObject) {
            if ($key !== 'Map') {
                switch ($key) {
                    case 'Maptext':
                        $filtered[$key] = array_filter($mapObject,
                            function ($el) {
                                return !empty(trim($el['text']));
                            }
                        );
                        break;
                    case 'Mapline':
                        $filtered[$key] = array_filter($mapObject,
                            function ($el) {
                                return (isset($el['type']));
                            }
                        );
                        break;
                    case 'Mapicon':
                        $filtered[$key] = array_filter($mapObject,
                            function ($el) {
                                return (isset($el['icon']));
                            }
                        );
                        break;
                    default:
                        $filtered[$key] = array_filter($mapObject,
                            function ($el) {
                                return (isset($el['type'], $el['object_id']) && $el['object_id'] > 0);
                            }
                        );
                        break;
                }
            }
        }

        if (empty($request['Map']['background'])) {
            $request['Map']['background'] = null;
        }

        $filtered = Hash::insert(
            Hash::filter($filtered),
            '{s}.{s}.map_id', $request['Map']['id']
        );
        $filtered = array_merge(['Map' => $request['Map']], $filtered);

        return $filtered;
    }

    /**
     * return an array with obsolete IDs which can be deleted from Database
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $oldData the old data to compare with
     * @param  Array $newData the new base data
     *
     * @return Array          Array with ids to delete
     */
    public function getObsoleteIds($oldData, $newData) {
        $idsToDelete = [];
        foreach ($oldData as $key => $data) {
            $idsToDelete[$key] = array_diff(Hash::extract($data, '{n}.id'), (!empty($newData[$key])) ? Hash::extract($newData[$key], '{s}.id') : []);
        }

        return $idsToDelete;
    }


    /**
     * return the Hoststatus for the given array of conditions
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $conditions
     * @param  Array $fields
     *
     * @return Array Hoststatus array
     */
    protected function _hoststatus($conditions, $fields = null) {
        $_conditions = ['Objects.objecttype_id' => 1];
        $conditions = Hash::merge($conditions, $_conditions);

        $_fields = ['Hoststatus.current_state', 'Objects.name1'];
        if (!empty($fields)) {
            $fields = Hash::merge($fields, $_fields);
        } else {
            $fields = $_fields;
        }
        $hoststatus = $this->Objects->find('all', [
            'conditions' => $conditions,
            'fields'     => $fields,
            'joins'      => [
                [
                    'table'      => 'nagios_hoststatus',
                    'type'       => 'INNER',
                    'alias'      => 'Hoststatus',
                    'conditions' => 'Objects.object_id = Hoststatus.host_object_id',
                ],
            ],
        ]);

        return $hoststatus;
    }

    /**
     * return the servicestatus for the given array of conditions
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $conditions
     * @param  Array $fields
     * @param  Bool $getServiceInfo set to true if you also want to get the service and servicetemplate data
     *
     * @return Array Servicestatus array
     */
    protected function _servicestatus($conditions, $fields = null, $getServiceInfo = false, $type = 'all') {
        $_conditions = ['Objects.objecttype_id' => 2];
        $conditions = Hash::merge($conditions, $_conditions);

        $_fields = ['Servicestatus.current_state', 'Objects.name1'];
        if (!empty($fields)) {
            $fields = Hash::merge($fields, $_fields);
        } else {
            $fields = $_fields;
        }

        if ($getServiceInfo) {
            $joins = [
                [
                    'table'      => 'services',
                    'alias'      => 'Service',
                    'conditions' => [
                        'Objects.name2 = Service.uuid',
                    ],
                ],
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'conditions' => [
                        'Host.uuid = Objects.name1',
                    ],
                ],
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'INNER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ];
        } else {
            $joins = [
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'INNER',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ];
        }
        $servicestatus = $this->Objects->find($type, [
            'recursive'  => -1,
            'conditions' => $conditions,
            'fields'     => $fields,
            'joins'      => $joins,
            'order'      => 'Servicestatus.current_state desc',
        ]);

        return $servicestatus;
    }

    /**
     * get hoststatus by uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid String or array of uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getHoststatusByUuid($uuid = [], $fields = null) {
        if (empty($uuid)) {
            return false;
        }
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
        $conditions = [
            'Host.uuid'         => $uuid,
            'Objects.is_active' => 1,
        ];

        return $this->_hoststatus($conditions, $fields);
    }

    /**
     * get servicestatus by HOST uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicestatusByHostUuid($uuid = null, $fields = null) {
        if (empty($uuid)) {
            return false;
        }
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
        $conditions = [
            'Objects.name1'     => $uuid,
            'Objects.is_active' => 1,
        ];

        return $this->_servicestatus($conditions, $fields, true);
    }

    /**
     * get servcestatus by uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicestatusByUuid($uuid = null, $fields = null) {
        if (empty($uuid)) {
            return false;
        }
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
        $conditions = [
            'Objects.name2'     => $uuid,
            'Objects.is_active' => 1,
        ];

        return $this->_servicestatus($conditions, $fields, true);
    }

    /**
     * get servicegroupstatus by uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicegroupstatusByUuid($uuid = null, $fields = null) {
        if (empty($uuid)) {
            return false;
        }
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
        $this->Servicegroup = ClassRegistry::init('Servicegroup');
        $servicegroupstatus = [];
        $servicegroup = $this->Servicegroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'uuid' => $uuid,
            ],
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.name',
                    ],
                ],
                'Service'   => [
                    'fields' => [
                        'Service.uuid',
                    ],
                ],
            ],
        ]);
        $servicegroupstatus = $servicegroup;
        $currentServicegroupServiceUuids = Hash::extract($servicegroup, '{n}.Service.{n}.uuid');

        foreach ($currentServicegroupServiceUuids as $key => $serviceUuid) {
            $conditions = [
                'Objects.name2' => $serviceUuid,
            ];
            $servicegroupstatus['Servicestatus'][$serviceUuid] = $this->_servicestatus($conditions, $fields, true, 'first');
        }

        return $servicegroupstatus;
    }

    /**
     * get hostgroupstate by uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid String or Array of Uuids
     * @param  Array $hostFields fields of the hosts which should be returned
     * @param  Array $serviceFields fields of the services which should be returned
     *
     * @return Mixed                false if there wasnt uuid submitted, empty array if nothing found or filled array
     *                              on success
     */
    public function getHostgroupstatusByUuid($uuid = null, $hostFields = null, $serviceFields = null) {
        if (empty($uuid)) {
            return false;
        }
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $hostgroupstatus = [];
        $hostgroups = $this->Hostgroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'uuid' => $uuid,
            ],
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.name',
                    ],
                ],
                'Host'      => [
                    'fields' => [
                        'Host.name',
                        'Host.uuid',
                        'Host.description',
                        'Host.address',
                    ],
                ],
            ],
            'fields'     => [
                'Hostgroup.*',
            ],
        ]);
        $hostgroupstatus = $hostgroups;

        $HostgroupHostUuids = Hash::extract($hostgroups, '{n}.Host.{n}.uuid');

        $hoststatusMapping = [];
        $servicestatusMapping = [];

        foreach ($hostgroupstatus as $hgKey => $hostgroup) {
            foreach ($HostgroupHostUuids as $key => $hostUuid) {
                $conditions = [
                    'Objects.name1'     => $hostUuid,
                    'Objects.is_active' => 1,
                ];

                if (empty($hoststatusMapping[$hostUuid])) {
                    $hoststatusMapping[$hostUuid] = $this->_hoststatus($conditions, $hostFields);
                    $hostgroupHostStatus = $hoststatusMapping[$hostUuid];
                } else {
                    $hostgroupHostStatus = $hoststatusMapping[$hostUuid];
                }

                if (empty($servicestatusMapping[$hostUuid])) {
                    $servicestatusMapping[$hostUuid] = $this->_servicestatus($conditions, $serviceFields);
                    $hostgroupServiceStatus = $servicestatusMapping[$hostUuid];
                } else {
                    $hostgroupServiceStatus = $servicestatusMapping[$hostUuid];
                }

                $hostgroupstatus[$hgKey]['Host'][$key]['Hoststatus'] = $hostgroupHostStatus;

                $hostgroupstatus[$hgKey]['Host'][$key]['Servicestatus'] = $hostgroupServiceStatus;
            }
        }

        return $hostgroupstatus;
    }

    public function getMapElements($type = 'Mapitem', $conditions = null, $fields = null) {
        $joins = [
            [
                'table'      => 'hosts',
                'alias'      => 'Host',
                'type'       => 'LEFT OUTER',
                'conditions' => [
                    [
                        'AND' => [
                            'Host.id = ' . $type . '.object_id',
                            '' . $type . '.type' => 'host',
                        ],
                    ],
                ],
            ],
            [
                'table'      => 'services',
                'alias'      => 'Service',
                'type'       => 'LEFT OUTER',
                'conditions' => [
                    [
                        'AND' => [
                            'Service.id = ' . $type . '.object_id',
                            '' . $type . '.type' => 'service',
                        ],
                    ],
                ],
            ],
            [
                'table'      => 'hostgroups',
                'alias'      => 'Hostgroup',
                'type'       => 'LEFT OUTER',
                'conditions' => [
                    [
                        'AND' => [
                            'Hostgroup.id = ' . $type . '.object_id',
                            '' . $type . '.type' => 'hostgroup',
                        ],
                    ],
                ],
            ],
            [
                'table'      => 'servicegroups',
                'alias'      => 'Servicegroup',
                'type'       => 'LEFT OUTER',
                'conditions' => [
                    [
                        'AND' => [
                            'Servicegroup.id = ' . $type . '.object_id',
                            '' . $type . '.type' => 'servicegroup',
                        ],
                    ],
                ],
            ],
        ];

        switch ($type) {
            case 'Mapitem':
                $mapJoin = [
                    'table'      => 'maps',
                    'alias'      => 'SubMap',
                    'type'       => 'LEFT OUTER',
                    'conditions' => [
                        [
                            'AND' => [
                                'SubMap.id = Mapitem.object_id',
                                'Mapitem.type' => 'map',
                            ],
                        ],
                    ],
                ];
                array_push($joins, $mapJoin);
                //$joins = Hash::merge($mapJoin, $joins);
                $this->Mapitem = ClassRegistry::init('Mapitem');
                break;
            case 'Mapline':
                $this->Mapline = ClassRegistry::init('Mapline');
                break;
            case 'Mapgadget':
                $this->Mapgadget = ClassRegistry::init('Mapgadget');
                break;
            default:
                return false;
                break;
        }
        $result = $this->$type->find('all', [
            'conditions' => $conditions,
            'fields'     => $fields,
            'joins'      => $joins,
        ]);

        return $result;
    }


    /**
     *
     *
     */


    public function getHostInfoByUuids($uuids) {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->Host = ClassRegistry::init('Host');

        $result = $this->Host->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Host.uuid' => $uuids
            ],
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.description',
                'Host.disabled'
            ]
        ]);
        return $result;
    }

    public function getServiceInfoByHostIds($hostIds) {
        if (!is_array($hostIds)) {
            $hostIds = [$hostIds];
        }

        $this->Service = ClassRegistry::init('Service');

        $result = $this->Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Service.host_id' => $hostIds
            ],
            'fields'     => [
                'Service.host_id',
                'Service.uuid',
                'Service.disabled',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
                'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
            ],
            'joins'      => [
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
            ]
        ]);
        return $result;
    }

    public function getServiceInfoByUuids($uuids) {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->Service = ClassRegistry::init('Service');

        $result = $this->Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Service.uuid' => $uuids
            ],
            'fields'     => [
                'Service.host_id',
                'Service.uuid',
                'Service.disabled',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
                'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
            ],
            'joins'      => [
                [
                    'table'      => 'servicetemplates',
                    'type'       => 'INNER',
                    'alias'      => 'Servicetemplate',
                    'conditions' => [
                        'Servicetemplate.id = Service.servicetemplate_id',
                    ],
                ],
            ]
        ]);
        return $result;
    }

    public function getHostgroupInfoByUuids($uuids) {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->Hostgroup = ClassRegistry::init('Hostgroup');

        $result = $this->Hostgroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $uuids
            ],
            'fields'     => [
                'Hostgroup.id',
                'Hostgroup.uuid',
                'Hostgroup.container_id',
                'Hostgroup.description',
                'Hostgroup.hostgroup_url',
            ],
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.name',
                    ],
                ],
                'Host'      => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid',
                        'Host.description',
                        'Host.address',
                        'Host.disabled',
                    ],
                ],
            ],
        ]);

        return $result;
    }


    public function getServicegroupInfoByUuids($uuids) {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->Servicegroup = ClassRegistry::init('Servicegroup');

        $result = $this->Servicegroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Servicegroup.uuid' => $uuids
            ],
            'fields'     => [
                'Servicegroup.id',
                'Servicegroup.uuid',
                'Servicegroup.container_id',
                'Servicegroup.description',
                'Servicegroup.servicegroup_url',
            ],
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.name'
                    ]
                ],
                'Service'   => [
                    'fields' => [
                        'Service.uuid',
                    ]
                ]
            ]
        ]);

        return $result;
    }


    /**
     * return states of all elements from a specific map
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  $id the Id of the map
     *
     * @return Array the map elements
     */
    public function mapStatus($id, $iterations = 0) {
        $Mapitem = ClassRegistry::init('Mapitem');
        $Mapline = ClassRegistry::init('Mapline');
        $Mapgadget = ClassRegistry::init('Mapgadget');
        $Host = ClassRegistry::init('Host');
        $Service = ClassRegistry::init('Service');
        $Servicegroup = ClassRegistry::init('Servicegroup');
        $Hostgroup = ClassRegistry::init('Hostgroup');
        $this->Objects = ClassRegistry::init(MONITORING_OBJECTS);

        $mapElements = [];
        $statusObjects = [];
        $mapElements['items'] = $Mapitem->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $id,
            ],
            'fields'     => [
                'Mapitem.type',
                'Mapitem.object_id',
            ],
        ]);
        $mapElements['lines'] = $Mapline->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $id,
            ],
            'fields'     => [
                'Mapline.type',
                'Mapline.object_id',
            ],
        ]);

        $mapElements['gadgets'] = $Mapgadget->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $id,
            ],
            'fields'     => [
                'Mapgadget.type',
                'Mapgadget.object_id',
            ],
        ]);

        $mapstatus = [];
        if (!empty($mapElements['items'])) {
            if ($iterations <= 1) {
                $iterations++;
                foreach ($mapElements['items'] as $item) {
                    if ($item['Mapitem']['type'] == 'map') {
                        $mapId = $item['Mapitem']['object_id'];
                        $mapstatus[] = $this->mapStatus($mapId, $iterations);
                    }
                }
            }
        }


        //get the service ids
        $mapServices = Hash::extract($mapElements, '{s}.{n}.{s}[type=/service$/].object_id');
        //resolve the serviceids into uuids
        $serviceUuids = $Service->find('list', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $mapServices,
            ],
            'fields'     => [
                'Service.uuid',
            ],
        ]);
        //get the servicestatus
        $statusObjects['servicestatus'] = $this->_servicestatus(['Objects.name2' => $serviceUuids], ['Servicestatus.problem_has_been_acknowledged']);

        //get the host ids
        $mapHosts = Hash::extract($mapElements, '{s}.{n}.{s}[type=/host$/].object_id');
        //resolve the hostids into uuids
        $hostUuids = $Host->find('list', [
            'recursive'  => -1,
            'conditions' => [
                'Host.id' => $mapHosts,
            ],
            'fields'     => [
                'Host.uuid',
            ],
        ]);
        //get the hoststatus
        $statusObjects['hoststatus'] = [
            $this->_hoststatus(['Objects.name1' => $hostUuids], ['Hoststatus.problem_has_been_acknowledged']),
        ];
        //get the servicestatus for every host
        foreach ($statusObjects['hoststatus'][0] as $key => $hoststatusObject) {
            $statusObjects['hoststatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name1' => $hoststatusObject['Objects']['name1']], ['Servicestatus.problem_has_been_acknowledged']);

        }

        //get the servicegroup ids
        $mapServicegroups = Hash::extract($mapElements, '{s}.{n}.{s}[type=/servicegroup$/].object_id');

        $ServicegroupServiceUuids = $Servicegroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Servicegroup.id' => $mapServicegroups,
            ],
            'contain'    => [
                'Service.uuid',
            ],
        ]);

        $ServicegroupServiceUuids = Hash::extract($ServicegroupServiceUuids, '{n}.Service.{n}.uuid');
        foreach ($ServicegroupServiceUuids as $key => $serviceuuid) {
            $statusObjects['servicegroupstatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name2' => $serviceuuid]);
        }

        //get the hostgroup ids
        $mapHostgroups = Hash::extract($mapElements, '{s}.{n}.{s}[type=/hostgroup$/].object_id');

        $HostgroupHostUuids = $Hostgroup->find('all', [
            //'recursive' => -1,
            'conditions' => [
                'Hostgroup.id' => $mapHostgroups,
            ],
            'contain'    => [
                'Host.uuid',
            ],
        ]);

        $HostgroupHostUuids = Hash::extract($HostgroupHostUuids, '{n}.Host.{n}.uuid');
        $statusObjects['hostgroupstatus'] = [
            $this->_hoststatus(['Objects.name1' => $HostgroupHostUuids]),
        ];

        foreach ($statusObjects['hostgroupstatus'][0] as $key => $hoststatusObject) {
            $statusObjects['hostgroupstatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name1' => $hoststatusObject['Objects']['name1']]);
        }


        if (!empty($mapstatus)) {
            foreach ($mapstatus as $mapstate) {
                $statusObjects = array_merge_recursive($statusObjects, $mapstate);
            }

            $tmpMapstatusObj = [];
            foreach ($statusObjects as $key => $statusObject) {
                if (!empty($statusObject)) {
                    foreach ($statusObject as $soKey => $obj) {
                        if (!empty($obj)) {
                            $tmpMapstatusObj[$key][] = $obj;
                        }

                    }
                }
            }
            $statusObjects = $tmpMapstatusObj;
        }
        return $statusObjects;
    }

    /**
     * @param $mapIds
     * @return array
     */
    public function getMapElementUuids($mapIds) {
        if (!is_array($mapIds)) {
            $mapIds = [$mapIds];
        }

        $mapElements = [];
        foreach ($mapIds as $mapId) {
            $mapElements[$mapId] = $this->getDeepMapElements($mapId);
        }

        $this->Host = ClassRegistry::init('Host');
        $this->Service = ClassRegistry::init('Service');
        $this->Servicegroup = ClassRegistry::init('Servicegroup');
        $this->Hostgroup = ClassRegistry::init('Hostgroup');


        $mapElementUuids = [];
        $mapElementUuidsForStatus = [
            'host'    => [],
            'service' => [],
        ];
        $services = [];
        foreach ($mapElements as $rootMapId => $linkedMaps) {
            $mapElementUuids[$rootMapId] = [];
            foreach ($linkedMaps as $linkedMapId => $types) {
                $mapElementUuids[$rootMapId][$linkedMapId] = [];
                foreach ($types as $elementType => $elements) {
                    if (empty($elementType)) {
                        continue;
                    }
                    foreach ($elements as $type => $element) {
                        switch ($element['type']) {
                            case 'service':
                                $serviceUuids = $this->Service->find('all', [
                                    'recursive'  => -1,
                                    'conditions' => [
                                        'Service.id' => $element['object_id']
                                    ],
                                    'fields'     => [
                                        'Service.uuid'
                                    ]
                                ]);
                                $serviceUuids = Hash::extract($serviceUuids, '{n}.Service.uuid');
                                $mapElementUuids[$rootMapId][$linkedMapId]['service'][]['service'] = $serviceUuids;

                                $mapElementUuidsForStatus = array_merge_recursive($mapElementUuidsForStatus, ['service' => $serviceUuids]);

                                break;
                            case 'host':
                                $hosts = $this->Host->find('all', [
                                    'recursive'  => -1,
                                    'conditions' => [
                                        'Host.id' => $element['object_id']
                                    ],
                                    'fields'     => [
                                        'Host.uuid'
                                    ]
                                ]);
                                $hostUuid = Hash::extract($hosts, '{n}.Host.uuid');
                                $mapElementUuids[$rootMapId][$linkedMapId]['host'][]['host'] = $hostUuid;
                                $serviceUuids = $this->getServiceUuidsByHostUuids($hostUuid);
                                $mapElementUuids[$rootMapId][$linkedMapId]['host'][]['service'] = $serviceUuids;

                                $mapElementUuidsForStatus = array_merge_recursive($mapElementUuidsForStatus, ['service' => $serviceUuids], ['host' => $hostUuid]);
                                break;
                            case 'servicegroup':
                                $servicegroupUuid = $this->Servicegroup->find('all', [
                                    'recursive'  => -1,
                                    'conditions' => [
                                        'Servicegroup.id' => $element['object_id']
                                    ],
                                    'fields'     => [
                                        'Servicegroup.uuid'
                                    ]
                                ]);
                                $servicegroupUuid = Hash::extract($servicegroupUuid, '{n}.Servicegroup.uuid');
                                $serviceUuids = $this->getServiceUuidsByServicegroupUuids($servicegroupUuid);
                                $mapElementUuids[$rootMapId][$linkedMapId]['servicegroup'][]['service'] = $serviceUuids;
                                $mapElementUuidsForStatus = array_merge_recursive($mapElementUuidsForStatus, ['service' => $serviceUuids]);
                                break;
                            case 'hostgroup':
                                $hostgroupUuid = $this->Hostgroup->find('all', [
                                    'recursive'  => -1,
                                    'conditions' => [
                                        'Hostgroup.id' => $element['object_id']
                                    ],
                                    'fields'     => [
                                        'Hostgroup.uuid'
                                    ]
                                ]);
                                $hostgroupUuid = Hash::extract($hostgroupUuid, '{n}.Hostgroup.uuid');
                                $hostgroupElementUuids = $this->getHostAndServiceUuidsByHostgroupuuid($hostgroupUuid);
                                $mapElementUuids[$rootMapId][$linkedMapId]['hostgroup'][] = $hostgroupElementUuids;
                                $mapElementUuidsForStatus = array_merge_recursive($mapElementUuidsForStatus, $hostgroupElementUuids);
                                break;
                        }
                    }
                }
            }
        }
        $mapElementUuidsForStatus['host'] = array_unique($mapElementUuidsForStatus['host']);
        $mapElementUuidsForStatus['service'] = array_unique($mapElementUuidsForStatus['service']);
        $mapElements = [
            'structure' => $mapElementUuids,
            'forStatus' => $mapElementUuidsForStatus
        ];

        return $mapElements;
    }

    /**
     * @param $mapId
     * @param array $iteratedMaps
     * @return array
     */
    public function getDeepMapElements($mapId, $iteratedMaps = []) {
        $this->mapElements[$mapId] = $this->getMapStatusElementsByMapId($mapId);
        $maxDepth = 2; // +1 = maximum number of map depth
        if (sizeof($iteratedMaps) < $maxDepth) {
            if (isset($this->mapElements[$mapId]['Mapitem']) && !empty($this->mapElements[$mapId]['Mapitem'])) {
                foreach ($this->mapElements[$mapId]['Mapitem'] as $element) {
                    if ($element['type'] != 'map') {
                        continue;
                    }
                    $currentMapId = $element['object_id'];
                    if (!in_array($currentMapId, $iteratedMaps)) {
                        $iteratedMaps[] = $currentMapId;
                        //next iteration
                        return $this->getDeepMapElements($currentMapId, $iteratedMaps);
                    } else {
                        continue;
                    }
                }
            }
        } else {
            //max depth reached
            return $this->mapElements;
        }
        //there are no further child maps
        return $this->mapElements;
    }

    /**
     * @param $mapId
     * @return array
     */
    public function getMapStatusElementsByMapId($mapId) {
        $Mapitem = ClassRegistry::init('Mapitem');
        $Mapline = ClassRegistry::init('Mapline');
        $Mapgadget = ClassRegistry::init('Mapgadget');

        $mapElements = [];
        $mapElements['Mapitem'] = $Mapitem->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $mapId,
            ],
            'fields'     => [
                'Mapitem.type',
                'Mapitem.object_id',
            ],
        ]);

        $mapElements['Mapline'] = $Mapline->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $mapId,
            ],
            'fields'     => [
                'Mapline.type',
                'Mapline.object_id',
            ],
        ]);

        $mapElements['Mapgadget'] = $Mapgadget->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'map_id' => $mapId,
            ],
            'fields'     => [
                'Mapgadget.type',
                'Mapgadget.object_id',
            ],
        ]);
        $newMapElements = [];
        foreach ($mapElements as $type => $data) {
            foreach ($data as $key => $element) {
                $newMapElements[$type][] = $data[$key][$type];
            }
        }

        return $newMapElements;
    }

    /**
     * @param $uuids
     * @param $hoststatusConditions
     * @param $servicestatusConditions
     * @param array $hostFields
     * @param array $serviceFields
     * @return array
     */
    public function getHoststatus($uuids, $hoststatusConditions, $servicestatusConditions, $hostFields = [], $serviceFields = []) {
        $this->Hoststatus = ClassRegistry::init(MONITORING_HOSTSTATUS);
        $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);
        $hoststatus = $this->Hoststatus->byUuids($uuids, $hostFields, $hoststatusConditions);
        $hostdata = $this->getHostInfoByUuids($uuids);
        $hostIds = Hash::extract($hostdata, '{n}.Host.id');

        $servicedata = $this->getServiceInfoByHostIds($hostIds);
        $hostServiceUuids = Hash::extract($servicedata, '{n}.Service.uuid');
        $servicestatus = $this->Servicestatus->byUuids($hostServiceUuids, $serviceFields, $servicestatusConditions);

        foreach ($servicedata as $key => $service) {
            $serviceuuid = $service['Service']['uuid'];
            if (isset($servicestatus[$serviceuuid])) {
                $servicedata[$key] = array_merge($servicedata[$key], $servicestatus[$serviceuuid]);
            }
        }

        foreach ($hostdata as $key => $host) {
            $hostuuid = $host['Host']['uuid'];
            $hostid = $host['Host']['id'];
            if (isset($hoststatus[$hostuuid])) {
                if ($host['Host']['disabled'] == 0) {
                    $hostdata[$key] = array_merge($hostdata[$key], $hoststatus[$hostuuid]);
                    if (isset($hostdata[$key]['Hoststatus'])) {
                        foreach ($servicedata as $service) {
                            if ($hostid == $service['Service']['host_id'] && $service['Service']['disabled'] == 0) {
                                $hostdata[$key]['Hoststatus']['Servicestatus'][] = $service;
                            }
                        }
                    }
                }
            }
        }
        return Hash::combine($hostdata, '{n}.Host.uuid', '{n}');
    }

    /**
     * @param $uuids
     * @param $servicestatusConditions
     * @param array $serviceFields
     * @return array
     */
    public function getServicestatus($uuids, $servicestatusConditions, $serviceFields = []) {
        $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);
        $servicestatus = $this->Servicestatus->byUuids($uuids, $serviceFields, $servicestatusConditions);
        $servicedata = $this->getServiceInfoByUuids($uuids);

        foreach ($servicedata as $key => $service) {
            $serviceuuid = $service['Service']['uuid'];
            if (isset($servicestatus[$serviceuuid])) {
                if ($service['Service']['disabled'] == 0) {
                    $servicedata[$key] = array_merge($servicedata[$key], $servicestatus[$serviceuuid]);
                }
            }
        }
        return Hash::combine($servicedata, '{n}.Service.uuid', '{n}');
    }

    /**
     * @param $uuids
     * @param $servicestatusConditions
     * @param array $serviceFields
     */
    public function getServicegroupstatus($uuids, $servicestatusConditions, $serviceFields = []) {
        $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);
        $servicegroups = $this->getServicegroupInfoByUuids($uuids);
        $serviceUuids = Hash::extract($servicegroups, '{n}.Service.{n}.uuid');
        $servicestatus = $this->Servicestatus->byUuids($serviceUuids, $serviceFields, $servicestatusConditions);
        if (!empty($servicestatus)) {
            $servicegroups['Servicestatus'] = $servicestatus;
        }
        return $servicegroups;
    }

    /**
     * @param $uuids
     * @param $hoststatusConditions
     * @param $servicestatusConditions
     * @param array $hostFields
     * @param array $serviceFields
     * @return mixed
     */
    public function getHostgroupstatus($uuids, $hoststatusConditions, $servicestatusConditions, $hostFields = [], $serviceFields = []) {
        $this->Hoststatus = ClassRegistry::init(MONITORING_HOSTSTATUS);
        $this->Servicestatus = ClassRegistry::init(MONITORING_SERVICESTATUS);

        $hostFields
            ->currentState()
            ->problemHasBeenAcknowledged()
            ->scheduledDowntimeDepth()
            ->isFlapping();

        $serviceFields->currentState();

        $hostgroupuuids = Hash::extract($uuids, '{n}.uuid');

        $hostgroups = $this->getHostgroupInfoByUuids($uuids);

        $hostids = Hash::extract($hostgroups, '{n}.Host.{n}.id');
        $hostuuids = Hash::extract($hostgroups, '{n}.Host.{n}.uuid');
        $hostgroupHostStatus = $this->Hoststatus->byUuids($hostuuids, $hostFields, $hoststatusConditions);
        $servicedata = $this->getServiceInfoByHostIds($hostids);
        $serviceUuids = Hash::extract($servicedata, '{n}.Service.uuid');
        $hostgroupServicestatus = $this->Servicestatus->byUuids($serviceUuids, $serviceFields, $servicestatusConditions);

        //we dont need the Servicedata but the mapping to the host id
        $servicestatusByHostId = [];
        foreach ($servicedata as $service) {
            $service = $service['Service'];
            $currentServiceUuid = $service['uuid'];
            $currentHostId = $service['host_id'];
            foreach ($hostgroupServicestatus as $serviceuuid => $servicestate) {
                if ($currentServiceUuid == $serviceuuid) {
                    $servicestatusByHostId[$currentHostId][] = $servicestate['Servicestatus'];
                    break;
                }
            }
        }


        foreach ($hostgroups as $key => $hostgroup) {
            foreach ($hostgroup['Host'] as $hKey => $host) {
                if ($host['disabled'] == 0) {
                    $currentHostId = $host['id'];
                    if (!isset($hostgroupHostStatus[$host['uuid']])) {
                        $hostgroupHostStatus[$host['uuid']] = [
                            'Hoststatus' => [
                                'current_state'                 => -1,
                                'problem_has_been_acknowledged' => null,
                                'scheduled_downtime_depth'      => null,
                                'is_flapping'                   => null
                            ]
                        ];
                    }

                    $hostgroups[$key]['Host'][$hKey] = array_merge($hostgroups[$key]['Host'][$hKey], $hostgroupHostStatus[$host['uuid']]);
                    if (!empty($servicestatusByHostId)) {
                        foreach ($servicedata as $service) {
                            if ($host['id'] == $service['Service']['host_id'] && $service['Service']['disabled'] == 0) {
                                $hostgroups[$key]['Host'][$hKey]['Servicestatus'] = $servicestatusByHostId[$currentHostId];
                            }
                        }
                    }
                }
            }
        }
        return $hostgroups;
    }

    /**
     * @param $hostUuids
     * @return array
     */
    public function getServiceUuidsByHostUuids($hostUuids) {
        $this->Service = ClassRegistry::init('Service');
        $serviceUuids = $this->Service->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Host.uuid' => $hostUuids
            ],
            'fields'     => [
                'Service.uuid'
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => [
                        'Host.id = Service.host_id',
                    ],
                ],
            ]
        ]);
        return Hash::extract($serviceUuids, '{n}.Service.uuid');
    }

    /**
     * @param $servicegroupUuids
     * @return array
     */
    public function getServiceUuidsByServicegroupUuids($servicegroupUuids) {
        $this->Servicegroup = ClassRegistry::init('Servicegroup');
        $serviceids = $this->Servicegroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Servicegroup.uuid' => $servicegroupUuids
            ],
            'fields'     => [
                'ServicesToServicegroups.service_id',
            ],
            'joins'      => [
                [
                    'table'      => 'services_to_servicegroups',
                    'type'       => 'INNER',
                    'alias'      => 'ServicesToServicegroups',
                    'conditions' => [
                        'ServicesToServicegroups.servicegroup_id = Servicegroup.id',
                    ],
                ],
            ]
        ]);

        $serviceids = Hash::extract($serviceids, '{n}.ServicesToServicegroups.service_id');
        $serviceids = array_unique($serviceids);

        $serviceuuids = [];
        if (!empty($serviceids)) {
            $serviceuuids = $this->Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Service.id' => $serviceids
                ],
                'fields'     => [
                    'Service.uuid'
                ]
            ]);
            $serviceuuids = Hash::extract($serviceuuids, '{n}.Service.uuid');
        }
        return $serviceuuids;
    }

    /**
     * @param $hostgroupUuids
     * @return array
     */
    public function getHostAndServiceUuidsByHostgroupuuid($hostgroupUuids) {
        $this->Hostgroup = ClassRegistry::init('Hostgroup');
        $this->Host = ClassRegistry::init('Host');
        $this->Service = ClassRegistry::init('Service');
        $hostIds = $this->Hostgroup->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Hostgroup.uuid' => $hostgroupUuids
            ],
            'fields'     => [
                'HostsToHostgroups.host_id'
            ],
            'joins'      => [
                [
                    'table'      => 'hosts_to_hostgroups',
                    'type'       => 'INNER',
                    'alias'      => 'HostsToHostgroups',
                    'conditions' => [
                        'HostsToHostgroups.hostgroup_id = Hostgroup.id',
                    ],
                ],
            ]
        ]);

        $hostIds = Hash::extract($hostIds, '{n}.HostsToHostgroups.host_id');
        $uuids = [];
        if (!empty($hostIds)) {
            $hostUuids = $this->Host->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Host.id' => $hostIds
                ],
                'fields'     => [
                    'Host.uuid'
                ]
            ]);
            $uuids['host'] = Hash::extract($hostUuids, '{n}.Host.uuid');

            $serviceUuids = $this->Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Service.host_id' => $hostIds
                ],
                'fields'     => [
                    'Service.uuid'
                ]
            ]);
            $uuids['service'] = Hash::extract($serviceUuids, '{n}.Service.uuid');
        }
        return $uuids;
    }
}