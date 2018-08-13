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
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\System\FileUploadSize;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use itnovum\openITCOCKPIT\Maps\MapForAngular;
use Statusengine\PerfdataParser;
use Symfony\Component\Finder\Finder;


/**
 * Class MapeditorsNewController
 * @property Map $Map
 * @property MapNew $MapNew
 * @property Mapitem $Mapitem
 * @property MapUpload $MapUpload
 * @property Mapline $Mapline
 * @property Mapgadget $Mapgadget
 * @property Maptext $Maptext
 * @property Mapicon $Mapicon
 * @property Mapsummaryitem $Mapsummaryitem
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
        'MapModule.Mapline',
        'MapModule.Mapgadget',
        'MapModule.Maptext',
        'MapModule.Mapicon',
        'MapModule.Mapsummaryitem',
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
            'recursive' => -1,
            'contain' => [
                'Container',
                'Mapitem',
                'Mapline',
                'Mapgadget',
                'Mapicon',
                'Maptext',
                'Mapsummaryitem'
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

        $MapForAngular = new MapForAngular($map);
        $map = $MapForAngular->toArray();

        $acl = [
            'hosts' => [
                'browser' => isset($this->PERMISSIONS['hosts']['browser']),
                'index' => isset($this->PERMISSIONS['hosts']['index'])
            ],
            'services' => [
                'browser' => isset($this->PERMISSIONS['services']['browser']),
                'index' => isset($this->PERMISSIONS['services']['index'])

            ],
            'hostgroups' => [
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

    /**
     * @todo Add to ACL depandencies (view/edit)
     */
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
                    'recursive' => -1,
                    'contain' => [
                        'Container'
                    ],
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name'
                    ],
                    'conditions' => [
                        'Host.id' => $objectId,
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
                    'recursive' => -1,
                    'fields' => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid'
                    ],
                    'contain' => [
                        'Host' => [
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
                        'Service.id' => $objectId,
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
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Host' => [
                            'Container',
                            'fields' => [
                                'Host.id',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ]
                        ]
                    ],
                    'fields' => [
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
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Service' => [
                            'Host' => [
                                'Container',
                                'fields' => [
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
                            'fields' => [
                                'Service.id',
                                'Service.uuid',
                                'Service.name'
                            ],
                            'conditions' => [
                                'Service.disabled' => 0
                            ]
                        ],

                    ],
                    'fields' => [
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
                    'recursive' => -1,
                    'contain' => [
                        'Container'
                    ],
                    'joins' => [
                        [
                            'table' => 'mapitems',
                            'type' => 'INNER',
                            'alias' => 'Mapitem',
                            'conditions' => 'Mapitem.map_id = Map.id',
                        ],
                    ],
                    'conditions' => [
                        'Map.id' => $mapId,
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
                        'recursive' => -1,
                        'conditions' => [
                            'Mapitem.map_id' => $map['Map']['id'],
                            'Mapitem.type' => 'map'
                        ],
                        'fields' => [
                            'Mapitem.object_id'
                        ]
                    ]);
                    if (!empty($mapItemToResolve)) {
                        $query = [
                            'recursive' => -1,
                            'joins' => [
                                [
                                    'table' => 'maps_to_containers',
                                    'type' => 'INNER',
                                    'alias' => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain' => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT' => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields' => [
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
                                'recursive' => -1,
                                'contain' => [
                                    'Container',
                                    'Service' => [
                                        'conditions' => [
                                            'Service.disabled' => 0
                                        ],
                                        'fields' => [
                                            'Service.id',
                                            'Service.uuid'
                                        ]
                                    ]
                                ],
                                'conditions' => [
                                    'Host.id' => $allDependentMapElements['hostIds'],
                                    'Host.disabled' => 0
                                ],
                                'fields' => [
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
                                        $services[$serviceData['id']] = [
                                            'Service' => $serviceData
                                        ];
                                    }
                                }
                            }
                        }
                        if (!empty($allDependentMapElements['serviceIds'])) {
                            $dependentServices = $this->Service->find('all', [
                                'recursive' => -1,
                                'contain' => [
                                    'Host' => [
                                        'Container'
                                    ]
                                ],
                                'conditions' => [
                                    'Service.id' => $allDependentMapElements['serviceIds'],
                                    'Service.disabled' => 0
                                ],
                                'fields' => [
                                    'Service.id',
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
                                foreach ($dependentServices as $service) {
                                    $services[$service['Service']['id']] = $service;
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

    /**
     * @todo Add to ACL depandencies
     */
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


    /**
     * @todo Add to ACL depandencies
     */
    public function mapline() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function mapicon() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function maptext() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function perfdatatext() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function mapsummaryitem() {
        if (!$this->isApiRequest()) {
            return;
        }
        $properties = [];
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
                    'recursive' => -1,
                    'contain' => [
                        'Container',
                        'Service' => [
                            'fields' => [
                                'Service.uuid'
                            ],
                            'conditions' => [
                                'Service.disabled' => 0
                            ]
                        ]
                    ],
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid'
                    ],
                    'conditions' => [
                        'Host.id' => $objectId,
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
                    $properties = $this->MapNew->getHostInformationForSummaryIcon(
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
                    'recursive' => -1,
                    'fields' => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid'
                    ],
                    'contain' => [
                        'Host' => [
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
                        'Service.id' => $objectId,
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
                    $properties = $this->MapNew->getServiceInformationForSummaryIcon(
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $service
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'hostgroup':
                $hostgroup = $this->Hostgroup->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Host' => [
                            'Container',
                            'fields' => [
                                'Host.id',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ],
                            'Service' => [
                                'fields' => [
                                    'Service.id',
                                    'Service.uuid'
                                ],
                                'conditions' => [
                                    'Service.disabled' => 0
                                ],
                            ]
                        ]
                    ],
                    'fields' => [
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
                    $properties = $this->MapNew->getHostgroupInformationForSummaryIcon(
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
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Service' => [
                            'Host' => [
                                'Container',
                                'fields' => [
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
                            'fields' => [
                                'Service.id',
                                'Service.uuid',
                                'Service.name'
                            ],
                            'conditions' => [
                                'Service.disabled' => 0
                            ]
                        ],

                    ],
                    'fields' => [
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
                    $properties = $this->MapNew->getServicegroupInformationForSummaryIcon(
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $servicegroup
                    );
                    break;
                }
                $allowView = false;
                break;
            case 'map':
                $map = $this->Map->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Container'
                    ],
                    'joins' => [
                        [
                            'table' => 'mapsummaryitems',
                            'type' => 'INNER',
                            'alias' => 'Mapsummaryitem',
                            'conditions' => 'Mapsummaryitem.map_id = Map.id',
                        ],
                    ],
                    'conditions' => [
                        'Mapsummaryitem.object_id' => $objectId,
                    ]
                ]);
                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'Container.{n}.MapsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }

                    //fetch all dependent map items after permissions check SUMMARY STATE ITEMS (TYPE MAP)
                    $mapSummaryItemToResolve = $this->Mapsummaryitem->find('first', [
                        'recursive' => -1,
                        'conditions' => [
                            'Mapsummaryitem.map_id' => $map['Map']['id'],
                            'Mapsummaryitem.type' => 'map'
                        ],
                        'fields' => [
                            'Mapsummaryitem.object_id'
                        ]
                    ]);

                    if (!empty($mapSummaryItemToResolve)) {
                        $query = [
                            'recursive' => -1,
                            'joins' => [
                                [
                                    'table' => 'maps_to_containers',
                                    'type' => 'INNER',
                                    'alias' => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain' => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapsummaryitem.type' => 'map',
                                'NOT' => [
                                    'Mapsummaryitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields' => [
                                'Mapsummaryitem.map_id',
                                'Mapsummaryitem.object_id'
                            ]
                        ];
                        if (!$this->hasRootPrivileges) {
                            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
                        }
                        $allVisibleItems = $this->Mapsummaryitem->find('all', $query);


                        $mapSummaryItemIdToResolve = $mapSummaryItemToResolve['Mapsummaryitem']['object_id'];
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.map_id'
                        );

                        if (isset($mapIdGroupByMapId[$mapSummaryItemIdToResolve])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $mapSummaryItemIdToResolve);
                        }
                        $dependentMapsIds[] = $mapSummaryItemIdToResolve;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapsummaryitem'] = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );
                    }

                    //fetch all dependent map items after permissions check SIMPLE STATE ITEMS (TYPE MAP)
                    $mapItemToResolve = $this->Mapitem->find('first', [
                        'recursive' => -1,
                        'conditions' => [
                            'Mapitem.map_id' => $map['Map']['id'],
                            'Mapitem.type' => 'map'
                        ],
                        'fields' => [
                            'Mapitem.object_id'
                        ]
                    ]);

                    if (!empty($mapItemToResolve)) {
                        $query = [
                            'recursive' => -1,
                            'joins' => [
                                [
                                    'table' => 'maps_to_containers',
                                    'type' => 'INNER',
                                    'alias' => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain' => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT' => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields' => [
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
                        $allDependentMapElementsFromSubMaps['mapitem'] = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );
                    }

                    //simple item (host/hostgroup/service/servicegroup)
                    $allDependentMapElementsFromSubMaps['item'] = $this->MapNew->getAllDependentMapsElements(
                        $this->Map,
                        [$map['Map']['id']],
                        $this->Hostgroup,
                        $this->Servicegroup
                    );


                    $hostIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.hostIds.{n}');
                    $serviceIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.serviceIds.{n}');

                    $hosts = [];
                    $services = [];
                    if (!empty($hostIds)) {
                        $hosts = $this->Host->find('all', [
                            'recursive' => -1,
                            'contain' => [
                                'Container',
                                'Service' => [
                                    'conditions' => [
                                        'Service.disabled' => 0
                                    ],
                                    'fields' => [
                                        'Service.id',
                                        'Service.uuid'
                                    ]
                                ]
                            ],
                            'conditions' => [
                                'Host.id' => $hostIds,
                                'Host.disabled' => 0
                            ],
                            'fields' => [
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
                                    $services[$serviceData['id']] = [
                                        'Service' => $serviceData
                                    ];
                                }
                            }
                        }
                    }
                    if (!empty($serviceIds)) {
                        $dependentServices = $this->Service->find('all', [
                            'recursive' => -1,
                            'contain' => [
                                'Host' => [
                                    'Container'
                                ]
                            ],
                            'conditions' => [
                                'Service.id' => $serviceIds,
                                'Service.disabled' => 0
                            ],
                            'fields' => [
                                'Service.id',
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
                            foreach ($dependentServices as $service) {
                                $services[$service['Service']['id']] = $service;
                            }
                        }
                    }
                    $allowView = true;
                    $properties = $this->MapNew->getMapInformationForSummaryIcon(
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $map,
                        $hosts,
                        $services
                    );
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

    /**
     * @todo Add to ACL depandencies
     */
    public function graph() {
        if (!$this->isApiRequest()) {
            return;
        }

        $serviceId = (int)$this->request->query('serviceId');
        $service = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid'
            ],
            'contain' => [
                'Host' => [
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
                'Service.id' => $serviceId,
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

    /**
     * @todo Add to ACL depandencies
     */
    public function tacho() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function cylinder() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function trafficlight() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function temperature() {
        //Only ship template
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function mapsummary() {
        if (!$this->isApiRequest()) {
            return;
        }

        $objectId = (int)$this->request->query('objectId');
        $summaryStateItem =  $this->request->query('summary');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        switch ($this->request->query('type')) {
            case 'host':
                $host = $this->Host->find('first', [
                    'recursive' => -1,
                    'contain' => [
                        'Container'
                    ],
                    'fields' => [
                        'Host.id',
                        'Host.uuid',
                        'Host.name',
                        'Host.description'
                    ],
                    'conditions' => [
                        'Host.id' => $objectId,
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
                    'recursive' => -1,
                    'fields' => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid',
                        'Service.description'
                    ],
                    'contain' => [
                        'Host' => [
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
                        'Service.id' => $objectId,
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
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Host' => [
                            'Container',
                            'fields' => [
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
                    'fields' => [
                        'Hostgroup.id',
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
                    'recursive' => -1,
                    'contain' => [
                        'Container' => [
                            'fields' => [
                                'Container.name'
                            ]
                        ],
                        'Service' => [
                            'Host' => [
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
                            'conditions' => [
                                'Service.disabled' => 0
                            ],
                            'fields' => [
                                'Service.id',
                                'Service.uuid',
                                'Service.name'
                            ]
                        ]
                    ],
                    'fields' => [
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
                if($summaryStateItem){
                    $map = $this->Map->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Container'
                        ],
                        'joins' => [
                            [
                                'table' => 'mapsummaryitems',
                                'type' => 'INNER',
                                'alias' => 'Mapsummaryitem',
                                'conditions' => 'Mapsummaryitem.map_id = Map.id',
                            ],
                        ],
                        'conditions' => [
                            'Mapsummaryitem.object_id' => $objectId
                        ],
                        'fields' => [
                            'Map.*',
                            'Mapsummaryitem.object_id'
                        ]
                    ]);
                }else{
                    $map = $this->Map->find('first', [
                        'recursive' => -1,
                        'contain' => [
                            'Container'
                        ],
                        'joins' => [
                            [
                                'table' => 'mapitems',
                                'type' => 'INNER',
                                'alias' => 'Mapitem',
                                'conditions' => 'Mapitem.map_id = Map.id',
                            ],
                        ],
                        'conditions' => [
                            'Mapitem.object_id' => $objectId
                        ],
                        'fields' => [
                            'Map.*',
                            'Mapitem.object_id'
                        ]
                    ]);
                }

                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'Container.{n}.MapsToContainer.container_id'))) {
                            $allowView = false;
                            break;
                        }
                    }
                    //fetch all dependent map items after permissions check SUMMARY STATE ITEMS (TYPE MAP)
                    $mapSummaryItemToResolve = $this->Mapsummaryitem->find('first', [
                        'recursive' => -1,
                        'conditions' => [
                            'Mapsummaryitem.map_id' => $map['Map']['id'],
                            'Mapsummaryitem.type' => 'map'
                        ],
                        'fields' => [
                            'Mapsummaryitem.object_id'
                        ]
                    ]);

                    if (!empty($mapSummaryItemToResolve)) {
                        $query = [
                            'recursive' => -1,
                            'joins' => [
                                [
                                    'table' => 'maps_to_containers',
                                    'type' => 'INNER',
                                    'alias' => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain' => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapsummaryitem.type' => 'map',
                                'NOT' => [
                                    'Mapsummaryitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields' => [
                                'Mapsummaryitem.map_id',
                                'Mapsummaryitem.object_id'
                            ]
                        ];
                        if (!$this->hasRootPrivileges) {
                            $query['conditions']['MapsToContainers.container_id'] = $this->MY_RIGHTS;
                        }
                        $allVisibleItems = $this->Mapsummaryitem->find('all', $query);


                        $mapSummaryItemIdToResolve = $mapSummaryItemToResolve['Mapsummaryitem']['object_id'];
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.map_id'
                        );

                        if (isset($mapIdGroupByMapId[$mapSummaryItemIdToResolve])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $mapSummaryItemIdToResolve);
                        }
                        $dependentMapsIds[] = $mapSummaryItemIdToResolve;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapsummaryitem'] = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );
                    }

                    //fetch all dependent map items after permissions check SIMPLE STATE ITEMS (TYPE MAP)
                    $mapItemToResolve = $this->Mapitem->find('first', [
                        'recursive' => -1,
                        'conditions' => [
                            'Mapitem.map_id' => $map['Map']['id'],
                            'Mapitem.type' => 'map'
                        ],
                        'fields' => [
                            'Mapitem.object_id'
                        ]
                    ]);

                    if (!empty($mapItemToResolve)) {
                        $query = [
                            'recursive' => -1,
                            'joins' => [
                                [
                                    'table' => 'maps_to_containers',
                                    'type' => 'INNER',
                                    'alias' => 'MapsToContainers',
                                    'conditions' => 'MapsToContainers.map_id = Map.id',
                                ],
                            ],
                            'contain' => [
                                'Map'
                            ],
                            'conditions' => [
                                'Mapitem.type' => 'map',
                                'NOT' => [
                                    'Mapitem.map_id' => $map['Map']['id']
                                ]
                            ],
                            'fields' => [
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
                        $allDependentMapElementsFromSubMaps['mapitem'] = $this->MapNew->getAllDependentMapsElements(
                            $this->Map,
                            $dependentMapsIds,
                            $this->Hostgroup,
                            $this->Servicegroup
                        );
                    }

                    //simple item (host/hostgroup/service/servicegroup)
                    $allDependentMapElementsFromSubMaps['item'] = $this->MapNew->getAllDependentMapsElements(
                        $this->Map,
                        [$map['Map']['id']],
                        $this->Hostgroup,
                        $this->Servicegroup
                    );


                    $hostIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.hostIds.{n}');
                    $serviceIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.serviceIds.{n}');

                    $hosts = [];
                    $services = [];
                    if (!empty($hostIds)) {
                        $hosts = $this->Host->find('all', [
                            'recursive' => -1,
                            'contain' => [
                                'Container',
                                'Service' => [
                                    'conditions' => [
                                        'Service.disabled' => 0
                                    ],
                                    'fields' => [
                                        'Service.uuid',
                                        'Service.id',
                                        'Service.name'
                                    ],
                                    'Servicetemplate' => [
                                        'fields' => [
                                            'Servicetemplate.name'
                                        ]
                                    ]
                                ]
                            ],
                            'conditions' => [
                                'Host.id' => $hostIds,
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
                                    $services[$serviceData['id']] = [
                                        'Service' => $serviceData,
                                        'Host' => [
                                            'name' => $host['Host']['name']
                                        ]
                                    ];
                                }
                            }
                        }
                    }

                    if (!empty($serviceIds)) {
                        $services = $this->Service->find('all', [
                            'recursive' => -1,
                            'contain' => [
                                'Host' => [
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
                                'Service.id' => $serviceIds,
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
                        $UserTime,
                        $summaryStateItem
                    );
                    $this->set('type', 'map');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['map', 'summary']);
                    return;
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

            $gadgetPreviews = [
                'RRDGraph' => 'graph_gadget.png',
                'Tacho' => 'tacho_gadget.png',
                'TrafficLight' => 'trafficlight_gadget.png',
                'Cylinder'     => 'cylinder_gadget.png',
                'Text'         => 'perfdata_gadget.png',
                'Temperature'  => 'temperature_gadget.png'
            ];
            $this->set('gadgetPreviews', $gadgetPreviews);
            $this->set('requiredIcons', $this->MapUpload->getIconsNames());
            return;
        }

        $FileUploadSize = new FileUploadSize();

        $id = (int)$id;
        if (!$this->Map->exists($id)) {
            throw new NotFoundException();
        }
        $map = $this->Map->find('first', [
            'recursive' => -1,
            'contain' => [
                'Container',
                'Mapitem',
                'Mapline',
                'Mapgadget',
                'Mapicon',
                'Maptext',
                'Mapsummaryitem'
            ],
            'conditions' => [
                'Map.id' => $id
            ]
        ]);

        $MapForAngular = new MapForAngular($map);
        $map = $MapForAngular->toArray();


        $this->set('map', $map);
        $this->set('maxUploadLimit', $FileUploadSize->toArray());
        $this->set('max_z_index', $MapForAngular->getMaxZIndex());
        $this->set('layers', $MapForAngular->getLaysers());
        $this->set('_serialize', ['map', 'maxUploadLimit', 'max_z_index', 'layers']);
    }

    /**
     * @todo Add to ACL depandencies
     */
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
                'image' => $file->getFilename(),
                'path' => sprintf('/map_module/img/backgrounds/%s', $file->getFilename()),
                'thumbnail' => sprintf('/map_module/img/backgrounds/thumb/thumb_%s', $file->getFilename()),
            ];
        }

        $this->set('backgrounds', $backgrounds);
        $this->set('_serialize', ['backgrounds']);
    }

    /**
     * @todo Add to ACL depandencies
     */
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
                'recursive' => -1,
                'conditions' => [
                    'Mapitem.id' => $this->request->data('Mapitem.id')
                ]
            ]);

            $item['Mapitem']['x'] = (int)$this->request->data('Mapitem.x');
            $item['Mapitem']['y'] = (int)$this->request->data('Mapitem.y');
            $item['Mapitem']['show_label'] = (int)$this->request->data('Mapitem.show_label');
            if ($this->Mapitem->save($item)) {
                $mapitem = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapitem($item['Mapitem']);

                $this->set('Mapitem', [
                    'Mapitem' => $mapitem->toArray()
                ]);

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

        $item = $this->request->data;
        $item['Mapitem']['show_label'] = (int)$this->request->data('Mapitem.show_label');
        if ($this->Mapitem->save($item)) {
            $mapItem = $item;
            $mapItem['Mapitem']['id'] = (int)$this->Mapitem->id;

            $mapitem = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapitem($mapItem['Mapitem']);


            $this->set('Mapitem', [
                'Mapitem' => $mapitem->toArray()
            ]);
            $this->set('_serialize', ['Mapitem']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapitem');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteItem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Mapitem.id');


        if (!$this->Mapitem->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapitem.id needs to be numeric');
        }

        if ($this->Mapitem->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveLine() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $line = $this->Mapline->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Mapline.id' => $this->request->data('Mapline.id')
                ]
            ]);

            $line['Mapline']['startX'] = (int)$this->request->data('Mapline.startX');
            $line['Mapline']['startY'] = (int)$this->request->data('Mapline.startY');
            $line['Mapline']['endX'] = (int)$this->request->data('Mapline.endX');
            $line['Mapline']['endY'] = (int)$this->request->data('Mapline.endY');
            if ($this->Mapline->save($line)) {
                $Mapline = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapline($line['Mapline']);

                $this->set('Mapline', [
                    'Mapline' => $Mapline->toArray()
                ]);

                $this->set('_serialize', ['Mapline']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapline');
            return;
        }

        //Create new item or update existing one
        if (!isset($this->request->data['Mapline']['id'])) {
            $this->Mapline->create();
        }

        $line = $this->request->data;
        $line['Mapline']['show_label'] = (int)$this->request->data('Mapline.show_label');
        if ($this->Mapline->save($line)) {
            $Mapline = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapline($line['Mapline']);
            $this->set('Mapline', [
                'Mapline' => $Mapline->toArray()
            ]);
            $this->set('_serialize', ['Mapline']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapline');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteLine() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Mapgadget.id');


        if (!$this->Mapline->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapline.id needs to be numeric');
        }

        if ($this->Mapline->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveGadget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $gadget = $this->Mapgadget->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Mapgadget.id' => $this->request->data('Mapgadget.id')
                ]
            ]);

            $gadget['Mapgadget']['x'] = (int)$this->request->data('Mapgadget.x');
            $gadget['Mapgadget']['y'] = (int)$this->request->data('Mapgadget.y');

            //NULL -> No metric selected. Cast to 0 and Gadget will use first existing metric in performance data string
            $gadget['Mapgadget']['metric'] = $gadget['Mapgadget']['metric'];
            if ($this->Mapgadget->save($gadget)) {
                $Mapgadget = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapgadget($gadget['Mapgadget']);

                $this->set('Mapgadget', [
                    'Mapgadget' => $Mapgadget->toArray()
                ]);

                $this->set('_serialize', ['Mapgadget']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapgadget');
            return;
        }

        //Save new gadget size
        if ($this->request->data('action') === 'resizestop') {
            $gadget = $this->Mapgadget->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Mapgadget.id' => $this->request->data('Mapgadget.id')
                ]
            ]);

            $gadget['Mapgadget']['size_x'] = (int)$this->request->data('Mapgadget.size_x');
            $gadget['Mapgadget']['size_y'] = (int)$this->request->data('Mapgadget.size_y');

            //NULL -> No metric selected. Cast to 0 and Gadget will use first existing metric in performance data string
            $gadget['Mapgadget']['metric'] = $gadget['Mapgadget']['metric'];
            if ($this->Mapgadget->save($gadget)) {
                $Mapgadget = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapgadget($gadget['Mapgadget']);

                $this->set('Mapgadget', [
                    'Mapgadget' => $Mapgadget->toArray()
                ]);

                $this->set('_serialize', ['Mapgadget']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapgadget');
            return;
        }


        //Create new gadget or update existing one
        if (!isset($this->request->data['Mapgadget']['id'])) {
            $this->Mapgadget->create();
        }

        $gadget = $this->request->data;

        $gadget['Mapgadget']['show_label'] = (int)$this->request->data('Mapgadget.show_label');
        $gadget['Mapgadget']['size_x'] = (int)$this->request->data('Mapgadget.size_x');
        $gadget['Mapgadget']['size_y'] = (int)$this->request->data('Mapgadget.size_y');

        if ($this->Mapgadget->save($gadget)) {
            $gadget['Mapgadget']['id'] = $this->Mapgadget->id;
            $Mapgadget = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapgadget($gadget['Mapgadget']);
            $this->set('Mapgadget', [
                'Mapgadget' => $Mapgadget->toArray()
            ]);
            $this->set('_serialize', ['Mapgadget']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapgadget');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteGadget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Mapgadget.id');


        if (!$this->Mapgadget->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapgadget.id needs to be numeric');
        }

        if ($this->Mapgadget->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }


    /**
     * @todo Add to ACL depandencies
     */
    public function getPerformanceDataMetrics($serviceId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Service->exists($serviceId)) {
            throw new NotFoundException();
        }

        $service = $this->Service->find('first', [
            'recursive' => -1,
            'fields' => [
                'Service.id',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.id' => $serviceId,
            ],
        ]);


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->perfdata();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);

        if (!empty($servicestatus)) {
            $PerfdataParser = new PerfdataParser($servicestatus['Servicestatus']['perfdata']);
            $this->set('perfdata', $PerfdataParser->parse());
            $this->set('_serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->set('_serialize', ['perfdata']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveText() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $maptext = $this->Maptext->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Maptext.id' => $this->request->data('Maptext.id')
                ]
            ]);

            $maptext['Maptext']['x'] = (int)$this->request->data('Maptext.x');
            $maptext['Maptext']['y'] = (int)$this->request->data('Maptext.y');

            if ($this->Maptext->save($maptext)) {
                $Maptext = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Maptext($maptext['Maptext']);

                $this->set('Maptext', [
                    'Maptext' => $Maptext->toArray()
                ]);

                $this->set('_serialize', ['Maptext']);
                return;
            }
            $this->serializeErrorMessageFromModel('Maptext');
            return;
        }

        //Create new text or update existing one
        if (!isset($this->request->data['Maptext']['id'])) {
            $this->Maptext->create();
        }

        $text = $this->request->data;

        $text['Maptext']['x'] = (int)$this->request->data('Maptext.x');
        $text['Maptext']['y'] = (int)$this->request->data('Maptext.y');
        if ($this->Maptext->save($text)) {
            $text['Maptext']['id'] = $this->Maptext->id;
            $Maptext = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Maptext($text['Maptext']);
            $this->set('Maptext', [
                'Maptext' => $Maptext->toArray()
            ]);
            $this->set('_serialize', ['Maptext']);
            return;
        }
        $this->serializeErrorMessageFromModel('Maptext');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteText() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Maptext.id');


        if (!$this->Maptext->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Maptext.id needs to be numeric');
        }

        if ($this->Maptext->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveIcon() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $mapicon = $this->Mapicon->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Mapicon.id' => $this->request->data('Mapicon.id')
                ]
            ]);

            $mapicon['Mapicon']['x'] = (int)$this->request->data('Mapicon.x');
            $mapicon['Mapicon']['y'] = (int)$this->request->data('Mapicon.y');

            if ($this->Mapicon->save($mapicon)) {
                $Mapicon = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapicon($mapicon['Mapicon']);

                $this->set('Mapicon', [
                    'Mapicon' => $Mapicon->toArray()
                ]);

                $this->set('_serialize', ['Mapicon']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapicon');
            return;
        }

        //Create new icon or update existing one
        if (!isset($this->request->data['Mapicon']['id'])) {
            $this->Mapicon->create();
        }

        $icon = $this->request->data;

        $icon['Mapicon']['x'] = (int)$this->request->data('Mapicon.x');
        $icon['Mapicon']['y'] = (int)$this->request->data('Mapicon.y');
        if ($this->Mapicon->save($icon)) {
            $icon['Mapicon']['id'] = $this->Mapicon->id;
            $Mapicon = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapicon($icon['Mapicon']);
            $this->set('Mapicon', [
                'Mapicon' => $Mapicon->toArray()
            ]);
            $this->set('_serialize', ['Mapicon']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapicon');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteIcon() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Mapicon.id');


        if (!$this->Maptext->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapicon.id needs to be numeric');
        }

        if ($this->Mapicon->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveBackground() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Map.id');

        if (!$this->Maptext->exists($id)) {
            throw new NotFoundException();
        }

        $map = $this->Map->find('first', [
            'recursive' => -1,
            'conditions' => [
                'Map.id' => $id
            ]
        ]);

        $map['Map']['background'] = $this->request->data('Map.background');

        if ($this->Map->save($map)) {
            $this->set('Map', [
                'Map' => $map
            ]);

            $this->set('_serialize', ['Map']);
            return;
        }
        $this->serializeErrorMessageFromModel('Map');
        return;
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function getIcons() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $icons = $this->MapUpload->getIcons();

        $this->set('icons', $icons);
        $this->set('_serialize', ['icons']);
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function saveSummaryitem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        //Save new possition after drag and drop
        if ($this->request->data('action') === 'dragstop') {
            $mapsummaryitem = $this->Mapsummaryitem->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Mapsummaryitem.id' => $this->request->data('Mapsummaryitem.id')
                ]
            ]);

            $mapsummaryitem['Mapsummaryitem']['x'] = (int)$this->request->data('Mapsummaryitem.x');
            $mapsummaryitem['Mapsummaryitem']['y'] = (int)$this->request->data('Mapsummaryitem.y');
            if ($this->Mapsummaryitem->save($mapsummaryitem)) {
                $Mapsummaryitem = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapsummaryitem($mapsummaryitem['Mapsummaryitem']);

                $this->set('Mapsummaryitem', [
                    'Mapsummaryitem' => $Mapsummaryitem->toArray()
                ]);

                $this->set('_serialize', ['Mapsummaryitem']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapsummaryitem');
            return;
        }

        //Save new gadget size
        if ($this->request->data('action') === 'resizestop') {
            $mapsummaryitem = $this->Mapsummaryitem->find('first', [
                'recursive' => -1,
                'conditions' => [
                    'Mapsummaryitem.id' => $this->request->data('Mapsummaryitem.id')
                ]
            ]);

            $mapsummaryitem['Mapsummaryitem']['size_x'] = (int)$this->request->data('Mapsummaryitem.size_x');
            $mapsummaryitem['Mapsummaryitem']['size_y'] = (int)$this->request->data('Mapsummaryitem.size_y');

            if ($this->Mapsummaryitem->save($mapsummaryitem)) {
                $Mapsummaryitem = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapsummaryitem($mapsummaryitem['Mapsummaryitem']);

                $this->set('Mapsummaryitem', [
                    'Mapsummaryitem' => $Mapsummaryitem->toArray()
                ]);

                $this->set('_serialize', ['Mapsummaryitem']);
                return;
            }
            $this->serializeErrorMessageFromModel('Mapsummaryitem');
            return;
        }


        //Create new Mapsummaryitem or update existing one
        if (!isset($this->request->data['Mapsummaryitem']['id'])) {
            $this->Mapsummaryitem->create();
        }

        $mapsummaryitem = $this->request->data;

        $mapsummaryitem['Mapsummaryitem']['show_label'] = (int)$this->request->data('Mapsummaryitem.show_label');
        $mapsummaryitem['Mapsummaryitem']['size_x'] = (int)$this->request->data('Mapsummaryitem.size_x');
        $mapsummaryitem['Mapsummaryitem']['size_y'] = (int)$this->request->data('Mapsummaryitem.size_y');

        if ($this->Mapsummaryitem->save($mapsummaryitem)) {
            $gadget['Mapsummaryitem']['id'] = $this->Mapsummaryitem->id;
            $Mapsummaryitem = new \itnovum\openITCOCKPIT\Maps\ValueObjects\Mapsummaryitem($mapsummaryitem['Mapsummaryitem']);
            $this->set('Mapsummaryitem', [
                'Mapsummaryitem' => $Mapsummaryitem->toArray()
            ]);
            $this->set('_serialize', ['Mapsummaryitem']);
            return;
        }
        $this->serializeErrorMessageFromModel('Mapsummaryitem');
    }

    /**
     * @todo Add to ACL depandencies
     */
    public function deleteSummaryitem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->data('Mapsummaryitem.id');


        if (!$this->Mapsummaryitem->exists($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapsummaryitem.id needs to be numeric');
        }

        if ($this->Mapsummaryitem->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

}
