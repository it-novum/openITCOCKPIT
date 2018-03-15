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


/**
 * @property Mapeditor Mapeditor
 * @property Mapitem Mapitem
 * @property Mapline Mapline
 * @property Mapgadget Mapgadget
 * @property Mapicon Mapicon
 * @property Maptext Maptext
 * @property Host Host
 * @property Hostgroup Hostgroup
 * @property Service Service
 * @property Servicegroup Servicegroup
 * @property Background Background
 * @property Map Map
 */
class MapeditorsController extends MapModuleAppController {
    public $layout = 'Admin.default';
    public $uses = [
        'MapModule.Mapeditor',
        'MapModule.Mapitem',
        'MapModule.Mapline',
        'MapModule.Mapgadget',
        'MapModule.Mapicon',
        'MapModule.Maptext',
        'Host',
        'Hostgroup',
        'Service',
        'Servicegroup',
        'MapModule.Background',
        'MapModule.Map',
        MONITORING_OBJECTS,
        MONITORING_HOSTSTATUS,
    ];
    public $helpers = [
        'MapModule.Mapstatus',
        'Perfdata',
    ];

    public function index() {
        $this->__checkForGD();
    }

    protected function __checkForGD() {
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            throw new InternalErrorException(__('php5-gd not installed'));
        }
    }

    public function edit($id = null) {
        $this->__checkForGD();
        if (!$this->Map->exists($id)) {
            throw new NotFoundException(__('Invalid map'));
        }

        $map = $this->Map->findById($id);

        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');

        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();

            return;
        }


        $maps = $this->Map->find('all');

        $maps = Hash::remove($maps, '{n}.Container');

        $mapList = Hash::combine($maps, '{n}.Map.id', '{n}.Map.name');

        if ($this->request->is('post') || $this->request->is('put')) {
            $request = $this->Mapeditor->prepareForSave($this->request->data);
            //implement deleteObsoleteRecords() in model
            $elementIdsToDelete = $this->Mapeditor->getObsoleteIds($map, $request);

            foreach ($elementIdsToDelete as $mapElementType => $ids) {
                if (!empty($ids) && !in_array($mapElementType, ['Container', 'Rotation'])) {
                    $this->{$mapElementType}->deleteAll([
                        $mapElementType . '.map_id' => $map['Map']['id'],
                        $mapElementType . '.id'     => $ids,
                    ]);
                }
            }
            if ($this->Map->saveAll($request)) {
                if ($this->request->ext === 'json') {
                    $this->serializeId();

                    return;
                }
                $this->setFlash(__('Map modified successfully'));
                $this->redirect(['plugin' => 'map_module', 'controller' => 'maps', 'action' => 'index']);
            } else {
                if ($this->request->ext === 'json') {
                    $this->serializeErrorMessage();

                    return;
                }
                $this->setFlash(__('Data could not be saved'), false);
            }
        }
        $hosts = $this->Host->hostsByContainerId($this->MY_RIGHTS, 'list', [], 'id', 15);

        $hostgroup = $this->Hostgroup->hostgroupsByContainerId($this->MY_RIGHTS, 'list', 'id');
        $servicegroup = $this->Servicegroup->servicegroupsByContainerId($this->MY_RIGHTS, 'list');

        $backgroundThumbs = $this->Background->findBackgrounds();
        $iconSets = $this->Background->findIconsets();
        $icons = $this->Background->findIcons();

        $this->Frontend->setJson('backgroundThumbs', $backgroundThumbs);
        $this->set(compact([
            'map',
            'maps',
            'mapList',
            'servicegroup',
            'hostgroup',
            'hosts',
            'backgroundThumbs',
            'iconSets',
            'icons'
        ]));

        $this->Frontend->setJson('lang_minutes', __('minutes'));
        $this->Frontend->setJson('lang_seconds', __('seconds'));
        $this->Frontend->setJson('lang_and', __('and'));
        $this->Frontend->setJson('map_lines', Hash::Extract($map, 'Mapline.{n}'));
        $this->Frontend->setJson('map_gadgets', Hash::Extract($map, 'Mapgadget.{n}'));
    }

    public function getIconImages() {
        $this->autoRender = false;
        $iconSets = $this->Background->findIconsets();
        foreach ($iconSets['items']['iconsets'] as $iconset) {
            $path = $iconSets['items']['webPath'] . '/' . $iconset['savedName'] . '/' . 'ok.png';
            echo '<div class="col-xs-6 col-sm-6 col-md-6 backgroundContainer" title="' . $iconset['displayName'] . '">
				<div class="drag-element thumbnail thumbnailFix iconset-thumbnail">';
            if ($iconset['dimension'] < 80) {
                echo '<span class="valignHelper"></span>';
            }
            echo '<img class="iconset" src="' . $path . '" iconset-id="' . $iconset['id'] . '" iconset="' . $iconset['savedName'] . '">
				</div>
			</div>';
        }
    }

    public function getIconsetsList() {
        $this->autoRender = false;
        $iconSets = $this->Background->findIconsets();
        foreach ($iconSets['items']['iconsets'] as $name) {
            echo "<option value='{$name['savedName']}'>{$name['displayName']}</option>";
        }
    }

    public function getBackgroundImages() {
        $this->autoRender = false;
        $bgs = $this->Background->findBackgrounds();
        foreach ($bgs['files'] as $background) {
            $path = $bgs['thumbPath'] . '/thumb_' . $background['savedName'];
            $original = $bgs['webPath'] . '/' . $background['savedName'];
            echo '<div class="col-xs-6 col-sm-6 col-md-6 backgroundContainer thumbnailSize" title="' . $background['displayName'] . '">
					<div class="thumbnail backgroundThumbnailStyle background-thumbnail">
						<img class="background" src="' . $path . '" original="' . $original . '" filename="' . $background['savedName'] . '" filename-id="' . $background['id'] . '">
					</div>
				</div>';
        }
    }

    public function view($id = null) {
        $rotate = null;
        if (isset($this->request->params['named']['rotate'])) {
            $isFirst = true;
            $rotation = [];
            foreach ($this->request->params['named']['rotate'] as $rotation_map_id) {
                if ($isFirst === true) {
                    $id = $rotation_map_id;
                    $isFirst = false;
                } else {
                    $rotation[] = $rotation_map_id;
                }
            }

            //Add the current map id as the last element in rotation array, to rotate
            $rotation[] = $id;
            $this->Frontend->setJson('rotation_ids', $rotation);
            $this->Frontend->setJson('interval', $this->request->params['named']['interval']);

        } else {
            $this->Frontend->setJson('interval', 0);
        }

        $map = $this->Map->findById($id);

        $this->Frontend->setJson('refresh_interval', $map['Map']['refresh_interval']);

        if (!$this->Map->exists($id)) {
            throw new NotFoundException(__('Invalid map'));
        }

        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();

            return;
        }

        $isFullscreen = false;
        if (isset($this->request->params['named']['fullscreen'])) {
            $this->layout = 'Admin.fullscreen';
            $isFullscreen = true;
            $this->Frontend->setJson('is_fullscren', true);
        }
        $isWidget = isset($this->request->params['named']['widget']);

        $uuidsByItemType = [
            'host'         => [],
            'service'      => [],
            'servicegroup' => [],
            'hostgroup'    => [],
            'map'          => [],
        ];
        //foreach (keys Mapitem -> Mapline -> ...)
        if (!empty($map['Mapitem'])) {
            $mapitemObjectIds = Hash::combine($map['Mapitem'], '{n}.object_id', '{n}.object_id', '{n}.type');
            if (!empty($mapitemObjectIds)) {
                foreach ($mapitemObjectIds as $itemType => $objectIds) {
                    if ($itemType != 'map') {
                        $uuidsByType = Hash::extract($this->{ucfirst($itemType)}->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'id' => array_unique(array_values($objectIds)),
                            ],
                            'fields'     => [
                                'id',
                                'uuid',
                            ],
                        ]),
                            '{n}.{s}'
                        );
                        $uuidsByItemType[$itemType] = array_merge($uuidsByItemType[$itemType], $uuidsByType);
                    }
                }
            }
        }

        if (!empty($map['Mapgadget'])) {
            $mapgadgetObjectIds = Hash::combine($map['Mapgadget'], '{n}.object_id', '{n}.object_id', '{n}.type');
            if (!empty($mapgadgetObjectIds)) {
                foreach ($mapgadgetObjectIds as $gadgetType => $objectIds) {
                    $uuidsByType = Hash::extract($this->{ucfirst($gadgetType)}->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'id' => array_unique(array_values($objectIds)),
                        ],
                        'fields'     => [
                            'id',
                            'uuid',
                        ],
                    ]),
                        '{n}.{s}'
                    );
                    $uuidsByItemType[$gadgetType] = array_merge($uuidsByItemType[$gadgetType], $uuidsByType);
                }
            }
        }

        if (!empty($map['Mapline'])) {
            $maplineObjectIds = Hash::combine($map['Mapline'], '{n}.object_id', '{n}.object_id', '{n}.type');
            if (!empty($maplineObjectIds)) {
                foreach ($maplineObjectIds as $lineType => $objectIds) {
                    if ($lineType != 'stateless') {
                        $uuidsByType = Hash::extract($this->{ucfirst($lineType)}->find('all', [
                            'recursive'  => -1,
                            'conditions' => [
                                'id' => array_unique(array_values($objectIds)),
                            ],
                            'fields'     => [
                                'id',
                                'uuid',
                            ],
                        ]),
                            '{n}.{s}'
                        );
                        $uuidsByItemType[$lineType] = array_merge($uuidsByItemType[$lineType], $uuidsByType);
                    }
                }
            }
        }
        $uuidsByItemType = Hash::filter($uuidsByItemType);

        $_map = Hash::extract($map, 'Map');

        $mapElements = [];

        //get the items
        $mapitemConditions = [
            'Mapitem.map_id' => $id,
        ];

        $mapitemFields = [
            'Mapitem.*',
            'Host.id',
            'Host.uuid',
            'Hostgroup.id',
            'Hostgroup.uuid',
            'Service.id',
            'Service.uuid',
            'Servicegroup.id',
            'Servicegroup.uuid',
            'SubMap.*',
        ];
        $mapElements['map_items'] = $this->Mapeditor->getMapElements('Mapitem', $mapitemConditions, $mapitemFields);

        //get the lines
        $maplineConditions = [
            'Mapline.map_id' => $id,
        ];

        $maplineFields = [
            'Mapline.*',
            'Host.id',
            'Host.uuid',
            'Hostgroup.id',
            'Hostgroup.uuid',
            'Service.id',
            'Service.uuid',
            'Servicegroup.id',
            'Servicegroup.uuid',
        ];
        $mapElements['map_lines'] = $this->Mapeditor->getMapElements('Mapline', $maplineConditions, $maplineFields);

        //get the gadgets
        $mapgadgetConditions = [
            'Mapgadget.map_id' => $id,
        ];
        $mapgadgetFields = [
            'Mapgadget.*',
            'Host.id',
            'Host.uuid',
            'Hostgroup.id',
            'Hostgroup.uuid',
            'Service.id',
            'Service.uuid',
            'Servicegroup.id',
            'Servicegroup.uuid',
        ];
        $mapElements['map_gadgets'] = $this->Mapeditor->getMapElements('Mapgadget', $mapgadgetConditions, $mapgadgetFields);

        //get the maptexts
        $mapElements['map_texts'] = $this->Maptext->find('all', [
            'conditions' => [
                'map_id' => $id,
            ],
        ]);

        //keep the null values out
        $mapElements = Hash::filter($mapElements);
        if (!empty($mapElements['map_items'])) {
            $mapIds = Hash::extract($mapElements['map_items'], '{n}.SubMap.id');
        }


        //get the Hoststatus
        if (!empty($uuidsByItemType['host'])) {
            $hoststatusFields = [
                'Host.name',
                'Host.description',
                'Host.address',
                'Hoststatus.output',
                'Hoststatus.long_output',
                'Hoststatus.perfdata',
                'Hoststatus.last_check',
                'Hoststatus.next_check',
                'Hoststatus.last_state_change',
                'Hoststatus.problem_has_been_acknowledged',
                'Hoststatus.scheduled_downtime_depth',
                'Hoststatus.is_flapping',
                'Hoststatus.current_check_attempt',
                'Hoststatus.max_check_attempts',
            ];

            $servicestatusFields = [
                'Objects.name2',
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.is_flapping',
                'Servicestatus.perfdata',
                'Servicestatus.output',
                'Service.name', // may obsolete .. just mapstatushelper is using that
                'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
                'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
            ];
            $hostUuids = Hash::extract($uuidsByItemType['host'], '{n}.uuid');
            $hoststatus = $this->Mapeditor->getHoststatusByUuid($hostUuids, $hoststatusFields);
            foreach ($hoststatus as $key => $value) {
                $currentHostUuid = $hoststatus[$key]['Objects']['name1'];
                $hoststatus[$key]['Hoststatus']['Servicestatus'] = $this->Mapeditor->getServicestatusByHostUuid($currentHostUuid, $servicestatusFields);
            }
        }

        //get the Hostgroupstatus
        if (!empty($uuidsByItemType['hostgroup'])) {
            $hostFields = [
                'Hoststatus.current_state',
                'Hoststatus.problem_has_been_acknowledged',
                'Hoststatus.scheduled_downtime_depth',
                'Hoststatus.is_flapping',
            ];
            $serviceFields = [
                'Servicestatus.current_state',
            ];
            $hostgroups = $this->Mapeditor->getHostgroupstatusByUuid(Hash::extract($uuidsByItemType['hostgroup'], '{n}.uuid'), $hostFields, $serviceFields);
        }

        //get the Servicegroupstatus
        if (!empty($uuidsByItemType['servicegroup'])) {
            $servicegroupUuids = Hash::extract($uuidsByItemType['servicegroup'], '{n}.uuid');
            $servicegroups = $this->Mapeditor->getServicegroupstatusByUuid($servicegroupUuids);
        }

        //get the Servicestatus
        if (!empty($uuidsByItemType['service'])) {
            $fields = [
                'Objects.name2',
                'Service.name', // may obsolete .. just mapstatushelper is using that
                'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that
                'Servicestatus.problem_has_been_acknowledged',
                'Servicestatus.scheduled_downtime_depth',
                'Servicestatus.is_flapping',
                'Servicestatus.perfdata',
                'Servicestatus.output',
                'Servicestatus.long_output',
                'Servicestatus.current_check_attempt',
                'Servicestatus.max_check_attempts',
                'Servicestatus.last_check',
                'Servicestatus.next_check',
                'Servicestatus.last_state_change',
            ];
            $serviceUuids = Hash::extract($uuidsByItemType['service'], '{n}.uuid');
            $servicestatus = $this->Mapeditor->getServicestatusByUuid($serviceUuids, $fields);
        }

        if (!empty($mapElements['map_gadgets'])) {
            $serviceGadgetUuids = Hash::extract($mapElements['map_gadgets'], '{n}.Service.uuid');
            //insert the Host UUID into the servicegadgets (eg. for RRDs)
            foreach ($serviceGadgetUuids as $key => $serviceGadgetUuid) {
                $mapElements['map_gadgets'][$key]['Service']['host_uuid'] = $this->hostUuidFromServiceUuid($serviceGadgetUuid)[0];
            }
        }

        //get the icons, iconsets and background images
        $backgroundThumbs = $this->Background->findBackgrounds();
        $iconSets = $this->Background->findIconsets();
        $icons = $this->Background->findIcons();

        //set json data for javascript components
        if (!empty($mapElements['map_lines'])) {
            $this->Frontend->setJson('map_lines', Hash::Extract($mapElements['map_lines'], '{n}.Mapline'));
        }
        if (!empty($mapElements['map_gadgets'])) {
            $this->Frontend->setJson('map_gadgets', Hash::Extract($mapElements['map_gadgets'], '{n}.Mapgadget'));
        }

        if (!empty($mapIds)) {
            foreach ($mapIds as $id) {
                $mapstatus[$id] = $this->Mapeditor->mapStatus($id);
            }
        }

        $this->set(compact([
            'map',
            'mapElements',
            'backgroundThumbs',
            'iconSets',
            'mapstatus',
            'hoststatus',
            'servicestatus',
            'hostgroups',
            'servicegroups',
            'isFullscreen',
            'isWidget',
            'icons',
        ]));
        $this->set('_serialize', ['map', 'mapElements']);
    }

    public function hostUuidFromServiceUuid($serviceUuid = null) {

        $hostUuid = $this->Service->find('first', [
            'conditions' => [
                'Service.uuid' => $serviceUuid,
            ],
            'fields'     => [
                'Service.uuid',
            ],
            'contain'    => [
                'Host' => [
                    'fields' => [
                        'Host.uuid',
                    ],
                ],
            ],
        ]);

        $hostUuid = Hash::extract($hostUuid, 'Host.uuid');

        return $hostUuid;
    }

    public function fullscreen($id = null) {
        $this->layout = '';
        $this->view($id);
        $this->render('view');
    }

    public function popoverHostStatus($uuid = null) {
        $hoststatusFields = [
            'Host.name',
            'Host.description',
            'Host.address',
            'Hoststatus.output',
            'Hoststatus.long_output',
            'Hoststatus.perfdata',
            'Hoststatus.last_check',
            'Hoststatus.next_check',
            'Hoststatus.last_state_change',
            'Hoststatus.problem_has_been_acknowledged',
            'Hoststatus.scheduled_downtime_depth',
            'Hoststatus.is_flapping',
            'Hoststatus.current_check_attempt',
            'Hoststatus.max_check_attempts',
        ];
        $hoststatus = $this->Mapeditor->getHoststatusByUuid([$uuid], $hoststatusFields);

        $servicestatusFields = [
            'Objects.name2',
            'Servicestatus.problem_has_been_acknowledged',
            'Servicestatus.scheduled_downtime_depth',
            'Servicestatus.is_flapping',
            'Servicestatus.perfdata',
            'Servicestatus.output',
            'Service.name', // may obsolete .. just mapstatushelper is using that
            'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that
            'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
            'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
        ];
        $servicestatus = $this->Mapeditor->getServicestatusByHostUuid($uuid, $servicestatusFields);
        $this->set(compact(['uuid', 'hoststatus', 'servicestatus']));
    }

    public function popoverServicegroupStatus($uuid = null) {
        $fields = [];
        $servicegroups = $this->Mapeditor->getServicegroupstatusByUuid($uuid, $fields);
        $this->set(compact(['uuid', 'servicegroups']));
    }

    public function popoverHostgroupStatus($uuid = null) {
        $hostFields = [
            'Hoststatus.current_state',
            'Hoststatus.problem_has_been_acknowledged',
            'Hoststatus.scheduled_downtime_depth',
            'Hoststatus.is_flapping',
        ];
        $serviceFields = [
            'Servicestatus.current_state',
        ];
        $hostgroups = $this->Mapeditor->getHostgroupstatusByUuid($uuid, $hostFields, $serviceFields);
        $this->set(compact(['hostgroups']));
    }


    public function popoverServiceStatus($uuid = null) {
        $fields = [
            'Host.name',
            'Objects.name2',
            'Service.name', // may obsolete .. just mapstatushelper is using that
            'Servicetemplate.name', // may obsolete .. just mapstatushelper is using that
            'Servicestatus.problem_has_been_acknowledged',
            'Servicestatus.scheduled_downtime_depth',
            'Servicestatus.is_flapping',
            'Servicestatus.perfdata',
            'Servicestatus.output',
            'Servicestatus.long_output',
            'Servicestatus.current_check_attempt',
            'Servicestatus.max_check_attempts',
            'Servicestatus.last_check',
            'Servicestatus.next_check',
            'Servicestatus.last_state_change',
            'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
            'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
        ];

        $servicestatus = $this->Mapeditor->getServicestatusByUuid($uuid, $fields);
        $serviceinfo = $serviceinfo = $this->Service->find('all', [
            'conditions' => [
                'Service.uuid' => $uuid,
            ],
            'fields'     => [
                'Host.name',
                'Service.name',
                'Servicetemplate.name',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceName',
                'IF(Service.name IS NULL, Servicetemplate.description, Service.description) AS ServiceDescription',
            ],
        ]);

        $this->set(compact('uuid', 'servicestatus', 'serviceinfo'));
    }

    public function popoverMapStatus($id) {
        $mapstatus = $this->Mapeditor->mapStatus($id);
        $mapinfo = $this->Map->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Map.id' => $id,
            ],
            'fields'     => [
                'Map.id',
                'Map.name',
                'Map.title',
            ],
        ]);
        $mapstatus[$id] = $mapstatus;
        $this->set(compact('mapstatus', 'mapinfo'));
    }


    public function servicesForWizard($hostId = null) {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Host->exists($hostId)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $services = $this->Service->find('all', [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => ['Servicetemplate.name'],
                ],
                'Host'            => [
                    'fields' => ['Host.name', 'Host.uuid'],
                ],
            ],
            'fields'     => [
                'Service.id',
                'IF(Service.name IS NULL, Servicetemplate.name, Service.name) AS ServiceDescription',
            ],
            'order'      => [
                'Service.name ASC', 'Servicetemplate.name ASC',
            ],
            'conditions' => [
                'Service.disabled' => 0,
                'Host.id' => $hostId,
            ],
        ]);
        $this->set(compact(['services']));
    }

    public function hostFromService($serviceId = null) {
        //$this->autoRender = false;
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Service->exists($serviceId)) {
            throw new NotFoundException(__('Invalid service'));
        }
        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Service.id' => $serviceId,
            ],
            'contain'    => [
                'Host',
            ],
            'fields'     => [
                'Host.id',
            ],
        ]);
        $this->set('_serialize', ['service']);
        $this->set(compact('service'));
    }
}
