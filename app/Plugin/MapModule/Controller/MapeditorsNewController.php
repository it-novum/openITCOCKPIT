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
use itnovum\openITCOCKPIT\Core\MapConditions;
use itnovum\openITCOCKPIT\Core\System\FileUploadSize;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use Symfony\Component\Finder\Finder;


/**
 * Class MapeditorsNewController
 * @property Map $Map
 * @property MapNew $MapNew
 * @property Mapitem $Mapitem
 * @property MapUpload $MapUpload
 * @property Host $Host
 * @property Service $Service
 * @property Hoststatus $Hoststatus
 * @property Servicestatus $Servicestatus
 *
 */
class MapeditorsNewController extends MapModuleAppController {
    public $layout = 'blank';

    public $uses = [
        'MapModule.Map',
        'MapModule.MapNew',
        'MapModule.Mapitem',
        'MapModule.MapUpload',
        'Host',
        'Service',
        'Hostgroup',
        'Servicegroup',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS
    ];


    /**
     * @param int $id
     */
    ///map_module/mapeditors_new/view/20
    public function view($id) {
        if (!$this->isApiRequest()) {
            $this->layout = 'angularjs';
            $isFullscreen = false;
            if ($this->request->query('fullscreen') === 'true') {
                $this->layout = 'angularjs_fullscreen';
                $isFullscreen = true;
            }
            $this->set('isFullscreen', $isFullscreen);
            //Only ship template
            return;
        }

        $id = (int)$id;
        if (!$this->Map->exists($id)) {
            throw new NotFoundException();
        }
        $map = $this->Map->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Mapitem',
                'Mapline',
                'Mapgadget',
                'Mapicon',
                'Maptext',
            ],
            'conditions' => [
                'Map.id' => $id
            ]
        ]);


        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $acl = [
            'hosts'         => [
                'browser' => isset($this->PERMISSIONS['hosts']['browser']),
                'index' => isset($this->PERMISSIONS['hosts']['index'])
            ],
            'services'      => [
                'browser' => isset($this->PERMISSIONS['services']['browser']),
                'index' => isset($this->PERMISSIONS['services']['index'])

            ],
            'hostgroups'    => [
                'extended' => isset($this->PERMISSIONS['hostgroups']['extended'])
            ],
            'servicegroups' => [
                'extended' => isset($this->PERMISSIONS['servicegroups']['extended'])
            ]
        ];

        $this->set('map', $map);
        $this->set('ACL', $acl);

        $this->set('_serialize', ['map', 'ACL']);

    }

    public function mapitem() {
        if (!$this->isApiRequest()) {
            return;
        }
        $objectId = (int)$this->request->query('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $mapId = (int)$this->request->query('mapId');
        if ($mapId <= 0) {
            throw new RuntimeException('Invalid map id');
        }


        switch ($this->request->query('type')) {
            case 'host':
                $host = $this->Host->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container'
                    ],
                    'fields'     => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name'
                    ],
                    'conditions' => [
                        'Host.id'       => $objectId,
                        'Host.disabled' => 0
                    ]
                ]);
                if (!empty($host)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($host, 'Container.{n}.HostsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $this->MapNew->getHostInformation(
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $host
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'service':
                $service = $this->Service->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid'
                    ],
                    'contain'    => [
                        'Host'            => [
                            'fields' => [
                                'Host.id',
                                'Host.uuid',
                                'Host.name'
                            ],
                            'Container',
                        ],
                        'Servicetemplate' => [
                            'fields' => [
                                'Servicetemplate.name'
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Service.id'       => $objectId,
                        'Service.disabled' => 0
                    ],
                ]);

                if (!empty($service)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $this->MapNew->getServiceInformation($this->Servicestatus, $service);
                    break;
                }
                $allowView = false;
                break;

            case 'hostgroup':
                $hostgroup = $this->Hostgroup->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Host'      => [
                            'Container',
                            'fields'     => [
                                'Host.id',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    'fields'     => [
                        'Hostgroup.id',
                        'Hostgroup.description'
                    ],
                    'conditions' => [
                        'Hostgroup.id' => $objectId
                    ]
                ]);
                if (!empty($hostgroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($hostgroup, 'Host.{n}.Container.{n}.HostsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $this->MapNew->getHostgroupInformation(
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $hostgroup
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'servicegroup':
                $servicegroup = $this->Servicegroup->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Service'   => [
                            'Host'            => [
                                'Container',
                                'fields'     => [
                                    'Host.id',
                                    'Host.uuid'
                                ],
                                'conditions' => [
                                    'Host.disabled' => 0
                                ]
                            ],
                            'Servicetemplate' => [
                                'fields' => [
                                    'Servicetemplate.name'
                                ]
                            ],
                            'fields'          => [
                                'Service.id',
                                'Service.uuid',
                                'Service.name'
                            ],
                            'conditions'      => [
                                'Service.disabled' => 0
                            ]
                        ],

                    ],
                    'fields'     => [
                        'Servicegroup.id',
                        'Servicegroup.description'
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $objectId
                    ]
                ]);

                if (!empty($servicegroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'Host.{n}.Container.{n}.HostsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $this->MapNew->getServicegroupInformation(
                        $this->Service,
                        $this->Servicestatus,
                        $servicegroup
                    );
                    break;
                }
                $allowView = false;
                break;
            case 'map':
                $map = $this->Map->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container'
                    ],
                    'joins'      => [
                        [
                            'table'      => 'mapitems',
                            'type'       => 'INNER',
                            'alias'      => 'Mapitem',
                            'conditions' => 'Mapitem.map_id = Map.id',
                        ],
                    ],
                    'conditions' => [
                        'Map.id' => $objectId
                    ]
                ]);
                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'Container.{n}.MapsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    //fetch all dependent map items after permissions check
                    $mapItemToResolve = $this->Mapitem->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Mapitem.map_id' => $map['Map']['id'],
                            'Mapitem.type'   => 'map'
                        ],
                        'fields'     => [
                            'Mapitem.object_id'
                        ]
                    ]);

                    if (!empty($mapItemToResolve)) {
                        $query = [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'maps_to_containers',
                                    'type'       => 'INNER',
                                    'alias'      => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain'    => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT'          => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields'     => [
                                'Mapitem.map_id',
                                'Mapitem.object_id'
                            ]
                        ];
                        if (!$this->hasRootPrivileges) {
                            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
                        }
                        $allVisibleItems = $this->Mapitem->find('all', $query);


                        $mapItemIdToResolve = $mapItemToResolve['Mapitem']['object_id'];
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.Mapitem.object_id',
                            '{n}.Mapitem.object_id',
                            '{n}.Mapitem.map_id'
                        );

                        if (isset($mapIdGroupByMapId[$mapItemIdToResolve])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $mapItemIdToResolve);
                        }
                        $dependentMapsIds[] = $mapItemIdToResolve;
                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElements = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );

                        $hosts = [];
                        $services = [];
                        if (!empty($allDependentMapElements['hostIds'])) {
                            $hosts = $this->Host->find('all', [
                                'recursive'  => -1,
                                'contain'    => [
                                    'Container',
                                    'Service' => [
                                        'conditions' => [
                                            'Service.disabled' => 0
                                        ],
                                        'fields'     => [
                                            'Service.id'
                                        ]
                                    ]
                                ],
                                'conditions' => [
                                    'Host.id'       => $allDependentMapElements['hostIds'],
                                    'Host.disabled' => 0
                                ],
                                'fields'     => [
                                    'Host.uuid'
                                ]
                            ]);
                            if (!empty($hosts)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($hosts, '{n}.Container.{n}.HostsToContainer.container_id'))) {
                                        $allowView = false;
                                        break;
                                    }
                                }
                                foreach ($hosts as $host) {
                                    foreach ($host['Service'] as $serviceData) {
                                        $serviceIds[$serviceData['id']] = $serviceData['id'];
                                    }
                                }
                            }
                        }
                        if (!empty($allDependentMapElements['serviceIds'])) {
                            $services = $this->Service->find('all', [
                                'recursive'  => -1,
                                'contain'    => [
                                    'Host' => [
                                        'Container'
                                    ]
                                ],
                                'conditions' => [
                                    'Service.id'       => $allDependentMapElements['serviceIds'],
                                    'Service.disabled' => 0
                                ],
                                'fields'     => [
                                    'Service.uuid'
                                ]
                            ]);
                            if (!empty($services)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($services, '{n}.Host.Container.{n}.HostsToContainer.container_id'))) {
                                        $allowView = false;
                                        break;
                                    }
                                }
                            }
                        }
                        $allowView = true;
                        $properties = $this->MapNew->getMapInformation(
                            $this->Hoststatus,
                            $this->Servicestatus,
                            $map,
                            $hosts,
                            $services
                        );
                    }
                    break;
                }
                $allowView = false;
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

        $this->set('type', $this->request->query('type'));
        $this->set('data', $properties);
        $this->set('allowView', $allowView);
        $this->set('_serialize', ['type', 'allowView', 'data']);
    }

    public function getDependendMaps($maps, $parentMapId) {
        $allRelatedMapIdsOfParent = [];

        $childMapIds = $maps[$parentMapId];
        foreach ($childMapIds as $childMapId) {
            $allRelatedMapIdsOfParent[] = $childMapId;
            //Is the children map used as parent map in an other relation?
            if (isset($maps[$childMapId])) {
                //Rec
                $allRelatedMapIdsOfParent = array_merge(
                    $allRelatedMapIdsOfParent,
                    $this->getDependendMaps($maps, $childMapId)
                );
            }
        }

        return $allRelatedMapIdsOfParent;

    }

    public function mapline() {
        //Only ship template
        return;
    }

    public function mapicon() {
        //Only ship template
        return;
    }

    public function maptext() {
        //Only ship template
        return;
    }

    public function perfdatatext() {
        //Only ship template
        return;
    }

    public function graph() {
        if (!$this->isApiRequest()) {
            return;
        }

        $serviceId = (int)$this->request->query('serviceId');
        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid'
            ],
            'contain'    => [
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name'
                    ],
                    'Container',
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ]
                ]
            ],
            'conditions' => [
                'Service.id'       => $serviceId,
                'Service.disabled' => 0
            ],
        ]);
        if (empty($service)) {
            throw new NotFoundException('Service not found!');
        }

        if ($this->hasRootPrivileges === false) {
            if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
                $this->set('allowView', false);
                $this->set('_serialize', ['allowView']);
            }
        }

        $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
        $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service);
        $this->set('host', $Host->toArray());
        $this->set('service', $Service->toArray());
        $this->set('allowView', true);
        $this->set('_serialize', ['allowView', 'host', 'service']);
    }

    public function tacho() {
        //Only ship template
        return;
    }

    public function cylinder() {
        //Only ship template
        return;
    }

    public function trafficlight() {
        //Only ship template
        return;
    }


    public function mapsummary() {
        if (!$this->isApiRequest()) {
            return;
        }

        $objectId = (int)$this->request->query('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        switch ($this->request->query('type')) {
            case 'host':
                $host = $this->Host->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container'
                    ],
                    'fields'     => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.description'
                    ],
                    'conditions' => [
                        'Host.id'       => $objectId,
                        'Host.disabled' => 0
                    ]
                ]);
                if (!empty($host)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($host, 'Container.{n}.HostsToContainer.container_id'))) {
                            $this->render403();
                            return;
                        }
                    }
                    $summary = $this->MapNew->getHostSummary(
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $host,
                        $UserTime
                    );
                    $this->set('type', 'host');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['host', 'summary']);
                    return;
                }

                throw new NotFoundException('Host not found!');
                return;
                break;
            case 'service':
                $service = $this->Service->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Service.id',
                        'Service.uuid',
                        'Service.description'
                    ],
                    'contain'    => [
                        'Host'            => [
                            'fields' => [
                                'Host.id',
                                'Host.uuid',
                                'Host.name'
                            ],
                            'Container',
                        ],
                        'Servicetemplate' => [
                            'fields' => [
                                'Servicetemplate.name'
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Service.id'       => $objectId,
                        'Service.disabled' => 0
                    ],
                ]);

                if (!empty($service)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
                            $this->render403();
                            return;
                        }
                    }
                    $summary = $this->MapNew->getServiceSummary(
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $service,
                        $UserTime
                    );
                    $this->set('type', 'service');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['service', 'summary']);
                    return;

                }
                throw new NotFoundException('Service not found!');
                return;

                break;
            case 'hostgroup':
                $query = [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Host'      => [
                            'Container',
                            'fields'     => [
                                'Host.id',
                                'Host.uuid',
                                'Host.name',
                                'Host.description'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    'fields'     => [
                        'Hostgroup.description'
                    ],
                    'conditions' => [
                        'Hostgroup.id' => $objectId
                    ]
                ];
                if (!$this->hasRootPrivileges) {
                    $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
                }
                $hostgroup = $this->Hostgroup->find('first', $query);

                if (!empty($hostgroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($hostgroup, 'Container.{n}.HostsToContainer.container_id'))) {
                            $this->render403();
                            return;
                        }
                    }

                    $summary = $this->MapNew->getHostgroupSummary(
                        $this->Host,
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $hostgroup
                    );
                    $this->set('type', 'hostgroup');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['hostgroup', 'summary']);
                    return;
                }

                throw new NotFoundException('Host group not found!');
                return;
                break;
            case 'servicegroup':
                $query = [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Service'   => [
                            'Host'            => [
                                'Container',
                                'conditions' => [
                                    'Host.disabled' => 0
                                ]
                            ],
                            'Servicetemplate' => [
                                'fields' => [
                                    'Servicetemplate.id',
                                    'Servicetemplate.name'
                                ]
                            ],
                            'conditions'      => [
                                'Service.disabled' => 0
                            ],
                            'fields'          => [
                                'Service.id',
                                'Service.uuid',
                                'Service.name'
                            ]
                        ]
                    ],
                    'fields'     => [
                        'Servicegroup.description'
                    ],
                    'conditions' => [
                        'Servicegroup.id' => $objectId
                    ]
                ];
                if (!$this->hasRootPrivileges) {
                    $query['conditions']['Container.parent_id'] = $this->MY_RIGHTS;
                }
                $servicegroup = $this->Servicegroup->find('first', $query);

                if (!empty($servicegroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'Container.{n}.HostsToContainer.container_id'))) {
                            $this->render403();
                            return;
                        }
                    }

                    $summary = $this->MapNew->getServicegroupSummary(
                        $this->Service,
                        $this->Servicestatus,
                        $servicegroup
                    );
                    $this->set('type', 'servicegroup');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['servicegroup', 'summary']);
                    return;
                }

                throw new NotFoundException('Service group not found!');
                return;
                break;
            case 'map':
                $map = $this->Map->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container'
                    ],
                    'joins'      => [
                        [
                            'table'      => 'mapitems',
                            'type'       => 'INNER',
                            'alias'      => 'Mapitem',
                            'conditions' => 'Mapitem.map_id = Map.id',
                        ],
                    ],
                    'conditions' => [
                        'Mapitem.object_id' => $objectId
                    ]
                ]);
                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'Container.{n}.MapsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    //fetch all dependent map items after permissions check
                    $mapItemToResolve = $this->Mapitem->find('first', [
                        'recursive'  => -1,
                        'conditions' => [
                            'Mapitem.map_id' => $map['Map']['id'],
                            'Mapitem.type'   => 'map'
                        ],
                        'fields'     => [
                            'Mapitem.object_id'
                        ]
                    ]);

                    if (!empty($mapItemToResolve)) {
                        $query = [
                            'recursive'  => -1,
                            'joins'      => [
                                [
                                    'table'      => 'maps_to_containers',
                                    'type'       => 'INNER',
                                    'alias'      => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain'    => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT'          => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields'     => [
                                'Mapitem.map_id',
                                'Mapitem.object_id'
                            ]
                        ];
                        if (!$this->hasRootPrivileges) {
                            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
                        }
                        $allVisibleItems = $this->Mapitem->find('all', $query);


                        $mapItemIdToResolve = $mapItemToResolve['Mapitem']['object_id'];
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.Mapitem.object_id',
                            '{n}.Mapitem.object_id',
                            '{n}.Mapitem.map_id'
                        );
                        if (isset($mapIdGroupByMapId[$mapItemIdToResolve])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $mapItemIdToResolve);
                        }
                        $dependentMapsIds[] = $mapItemIdToResolve;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElements = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );

                        $hosts = [];
                        $services = [];
                        if (!empty($allDependentMapElements['hostIds'])) {
                            $hosts = $this->Host->find('all', [
                                'recursive'  => -1,
                                'contain'    => [
                                    'Container',
                                    'Service' => [
                                        'conditions' => [
                                            'Service.disabled' => 0
                                        ],
                                        'fields'     => [
                                            'Service.id'
                                        ]
                                    ]
                                ],
                                'conditions' => [
                                    'Host.id'       => $allDependentMapElements['hostIds'],
                                    'Host.disabled' => 0
                                ],
                                'fields' => [
                                    'Host.id',
                                    'Host.uuid',
                                    'Host.name'
                                ]
                            ]);
                            if (!empty($hosts)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($hosts, '{n}.Container.{n}.HostsToContainer.container_id'))) {
                                        break;
                                    }
                                }
                                foreach ($hosts as $host) {
                                    foreach ($host['Service'] as $serviceData) {
                                        $serviceIds[$serviceData['id']] = $serviceData['id'];
                                    }
                                }
                            }
                        }
                        if (!empty($allDependentMapElements['serviceIds'])) {
                            $services = $this->Service->find('all', [
                                'recursive'  => -1,
                                'contain'    => [
                                    'Host'            => [
                                        'Container',
                                        'fields' => [
                                            'Host.name'
                                        ]
                                    ],
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'Servicetemplate.name'
                                        ]
                                    ]
                                ],
                                'conditions' => [
                                    'Service.id'       => $allDependentMapElements['serviceIds'],
                                    'Service.disabled' => 0
                                ],
                                'fields' => [
                                    'Service.id',
                                    'Service.uuid',
                                    'Service.name'
                                ]
                            ]);
                            if (!empty($services)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($services, '{n}.Host.Container.{n}.HostsToContainer.container_id'))) {
                                        break;
                                    }
                                }
                            }
                        }
                        $summary = $this->MapNew->getMapSummary(
                            $this->Host,
                            $this->Hoststatus,
                            $this->Service,
                            $this->Servicestatus,
                            $map,
                            $hosts,
                            $services,
                            $UserTime
                        );
                        $this->set('type', 'map');
                        $this->set('summary', $summary);
                        $this->set('_serialize', ['map', 'summary']);
                        return;
                    }
                }
                throw new NotFoundException('Map not found!');
                return;
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

    }

    public function edit($id) {
        if (!$this->isApiRequest()) {
            $this->layout = 'angularjs';
            //Only ship template
            return;
        }

        $FileUploadSize = new FileUploadSize();

        $maxZIndex = 0;
        $id = (int)$id;
        if (!$this->Map->exists($id)) {
            throw new NotFoundException();
        }
        $map = $this->Map->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Mapitem',
                'Mapline',
                'Mapgadget',
                'Mapicon',
                'Maptext',
            ],
            'conditions' => [
                'Map.id' => $id
            ]
        ]);

        $layers = [];
        foreach ($map as $modelName => $records) {
            foreach ($records as $key => $data) {
                if (isset($data['z_index'])) {
                    $zIndex = (int)$data['z_index'];
                    $layers[$zIndex] = sprintf('Layer %s', $zIndex);
                    if ($zIndex > $maxZIndex) {
                        $maxZIndex = $zIndex;
                    }
                }

                //Cast strings to ints
                if (isset($data['x'])) {
                    $map[$modelName][$key]['x'] = (int)$data['x'];
                }
                if (isset($data['y'])) {
                    $map[$modelName][$key]['y'] = (int)$data['y'];
                }

                if (isset($data['object_id'])) {
                    $map[$modelName][$key]['object_id'] = (int)$data['object_id'];
                }

                if (isset($data['show_label'])) {
                    $map[$modelName][$key]['show_label'] = (int)$data['show_label'];
                }

                if (isset($data['label_possition'])) {
                    $map[$modelName][$key]['label_possition'] = (int)$data['label_possition'];
                }
            }
        };

        $this->set('map', $map);
        $this->set('maxUploadLimit', $FileUploadSize->toArray());
        $this->set('max_z_index', $maxZIndex);
        $this->set('layers', $layers);
        $this->set('_serialize', ['map', 'maxUploadLimit', 'max_z_index', 'layers']);
    }

    public function backgroundImages() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }


        $finder = new Finder();
        $res = $finder->files()->in(APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds')->exclude('thumb');

        $backgrounds = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $backgrounds[] = [
                'image'     => $file->getFilename(),
                'path'      => sprintf('/map_module/img/backgrounds/%s', $file->getFilename()),
                'thumbnail' => sprintf('/map_module/img/backgrounds/thumb/thumb_%s', $file->getFilename()),
            ];
        }

        $this->set('backgrounds', $backgrounds);
        $this->set('_serialize', ['backgrounds']);
    }

    public function getIconsets() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $iconsets = $this->MapUpload->getIconSets();

        $this->set('iconsets', $iconsets);
        $this->set('_serialize', ['iconsets']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function loadMapsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->query('selected');
        $excluded = $this->request->query('excluded');

        $MapFilter = new MapFilter($this->request);

        $MapConditions = new MapConditions($MapFilter->indexFilter());
        $MapConditions->setContainerIds($this->MY_RIGHTS);

        $maps = $this->Map->makeItJavaScriptAble(
            $this->Map->getMapsForAngular($MapConditions, $selected, $excluded)
        );

        $this->set(compact(['maps']));
        $this->set('_serialize', ['maps']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveItem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $item = $this->Mapitem->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Mapitem.id' => $this->request->data('Mapitem.id')
                ]
            ]);

            $item['Mapitem']['x'] = (int)$this->request->data('Mapitem.x');
            $item['Mapitem']['y'] = (int)$this->request->data('Mapitem.y');
            if ($this->Mapitem->save($item)) {
                $this->set('Mapitem', $item);
                $this->set('_serialize', ['Mapitem']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapitem');
            return;
        }


        //Create new item or update existing one
        if (!isset($this->request->data['MapItem']['id'])) {
            $this->Mapitem->create();
        }

        if ($this->Mapitem->save($this->request->data)) {
            $mapItem = $this->request->data;
            $mapItem['Mapitem']['id'] = $this->Mapitem->id;
            $this->set('Mapitem', $mapItem);
            $this->set('_serialize', ['Mapitem']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapitem');
    }

}
