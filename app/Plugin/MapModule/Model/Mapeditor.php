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

class Mapeditor extends MapModuleAppModel
{
    public $useTable = false;

    public function prepareForSave($request)
    {
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
    public function getObsoleteIds($oldData, $newData)
    {
        $idsToDelete = [];
        foreach ($oldData as $key => $data) {
            $idsToDelete[$key] = array_diff(Hash::extract($data, '{n}.id'), (!empty($newData[$key])) ? Hash::extract($newData[$key], '{s}.id') : []);
        }

        return $idsToDelete;
    }

    /**
     * return states of all elements from a specific map
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  $id the Id of the map
     *
     * @return Array the map elements
     */
    public function mapStatus($id)
    {
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
        $statusObjects['servicestatus'] = $this->_servicestatus(['Objects.name2' => $serviceUuids]);

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
            $this->_hoststatus(['Objects.name1' => $hostUuids]),
        ];
        //get the servicestatus for every host
        foreach ($statusObjects['hoststatus'][0] as $key => $hoststatusObject) {
            $statusObjects['hoststatus'][0][$key]['Servicestatus'] = $this->_servicestatus(['Objects.name1' => $hoststatusObject['Objects']['name1']]);

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

        //$mapElements = Hash::filter($mapElements);

        return $statusObjects;
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
    protected function _hoststatus($conditions, $fields = null)
    {
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
                    'type'       => 'LEFT',
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
     * @param  Bool  $getServiceInfo set to true if you also want to get the service and servicetemplate data
     *
     * @return Array Servicestatus array
     */
    protected function _servicestatus($conditions, $fields = null, $getServiceInfo = false, $type = 'all')
    {
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
                    'type'       => 'LEFT',
                    'alias'      => 'Servicestatus',
                    'conditions' => 'Objects.object_id = Servicestatus.service_object_id',
                ],
            ];
        } else {
            $joins = [
                [
                    'table'      => 'nagios_servicestatus',
                    'type'       => 'LEFT',
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
     * @param  Mixed $uuid   String or array of uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getHoststatusByUuid($uuid = [], $fields = null)
    {
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
     * @param  Mixed $uuid   String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicestatusByHostUuid($uuid = null, $fields = null)
    {
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
     * @param  Mixed $uuid   String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicestatusByUuid($uuid = null, $fields = null)
    {
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
     * @param  Mixed $uuid   String or Array of Uuids
     * @param  Array $fields fields which should be returned
     *
     * @return Mixed         false if there wasnt uuid submitted, empty array if nothing found or filled array on
     *                       success
     */
    public function getServicegroupstatusByUuid($uuid = null, $fields = null)
    {
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
            $servicegroupstatus[0]['Servicegroup']['Servicestatus'][$key] = $this->_servicestatus($conditions, $fields, true, 'first');
        }

        return $servicegroupstatus;
    }

    /**
     * get hostgroupstate by uuid
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Mixed $uuid          String or Array of Uuids
     * @param  Array $hostFields    fields of the hosts which should be returned
     * @param  Array $serviceFields fields of the services which should be returned
     *
     * @return Mixed                false if there wasnt uuid submitted, empty array if nothing found or filled array
     *                              on success
     */
    public function getHostgroupstatusByUuid($uuid = null, $hostFields = null, $serviceFields = null)
    {
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

        foreach ($HostgroupHostUuids as $key => $hostUuid) {
            $conditions = [
                'Objects.name1'     => $hostUuid,
                'Objects.is_active' => 1,
            ];
            $hostgroupstatus[0]['Host'][$key]['Hoststatus'] = $this->_hoststatus($conditions, $hostFields);

            $hostgroupstatus[0]['Host'][$key]['Servicestatus'] = $this->_servicestatus($conditions, $serviceFields);
        }

        return $hostgroupstatus;
    }

    public function getMapElements($type = 'Mapitem', $conditions = null, $fields = null)
    {
        $joins = [
            [
                'table'      => 'hosts',
                'alias'      => 'Host',
                'type'       => 'LEFT OUTER',
                'conditions' => [
                    [
                        'AND' => [
                            'Host.id = '.$type.'.object_id',
                            ''.$type.'.type' => 'host',
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
                            'Service.id = '.$type.'.object_id',
                            ''.$type.'.type' => 'service',
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
                            'Hostgroup.id = '.$type.'.object_id',
                            ''.$type.'.type' => 'hostgroup',
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
                            'Servicegroup.id = '.$type.'.object_id',
                            ''.$type.'.type' => 'servicegroup',
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
}