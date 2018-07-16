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
 * Class MapeditorsNewController
 * @property Map $Map
 * @property MapNew $MapNew
 * @property Host $Host
 * @property Service $Service
 * @property Hoststatus $Hoststatus
 * @property Servicestatus $Servicestatus
 *
 * @property BbcodeComponent $Bbcode
 */
class MapeditorsNewController extends MapModuleAppController {
    public $layout = 'blank';

    public $uses = [
        'MapModule.Map',
        'MapModule.MapNew',
        'Host',
        'Service',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS
    ];

    public $components = [
        'Bbcode'
    ];

    /**
     * @param int $id
     */
    ///map_module/mapeditors_new/view/20
    public function view($id) {
        if (!$this->isApiRequest()) {
            $this->layout = 'angularjs';
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


        foreach ($map['Maptext'] as $i => $maptext) {
            $map['Maptext'][$i]['text'] = $this->Bbcode->asHtml($maptext['text']);
        }

        $containerIdsToCheck = Hash::extract($map, 'Container.{n}.MapsToContainer.container_id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $this->set('map', $map);
        $this->set('_serialize', ['map']);

    }

    public function mapitem() {
        if (!$this->isApiRequest()) {
            return;
        }

        $objectId = (int)$this->request->query('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }

        $properties = [
            'icon'       => 'error.png',
            'color'      => 'text-primary',
            'background' => 'bg-color-blueLight',
            'perfdata'   => null
        ];
        $allowView = false;
        switch ($this->request->query('type')) {
            case 'host':
                $host = $this->Host->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Container'
                    ],
                    'fields'     => [
                        'Host.id',
                        'Host.uuid'
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
                    $properties = $this->MapNew->getHostItemImage(
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
                        'Service.uuid'
                    ],
                    'contain'    => [
                        'Host' => [
                            'fields' => [
                                'Host.id',
                            ],
                            'Container',
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
                    $properties = $this->MapNew->getServiceItemImage($this->Servicestatus, $service);
                    break;
                }
                $allowView = false;
                break;
            case 'hostgroup':
                break;
            case 'servicegroup':
                break;
            case 'map':
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

        if(!isset($properties['perfdata'])){
            $properties['perfdata'] = null;
        }

        $this->set('icon', $properties['icon']);
        $this->set('background', $properties['background']);
        $this->set('color', $properties['color']);
        $this->set('perfdata', $properties['perfdata']);
        $this->set('allowView', $allowView);
        $this->set('_serialize', ['icon', 'background', 'color', 'perfdata', 'allowView']);
    }

    public function mapline() {

    }

    public function mapgadget() {

    }

    public function mapicon() {
        return;
    }

    public function maptext() {
        return;
    }

    public function perfdatatext() {

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
        $this->graph();
    }

    public function mapsummary() {
        if (!$this->isApiRequest()) {
            return;
        }

        $objectId = (int)$this->request->query('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
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
                    //Host dinge
                    $summary = $this->MapNew->getHostSummary(
                        $this->Service,
                        $this->Hoststatus,
                        $this->Servicestatus,
                        $host
                    );
                    $this->set('type', 'host');
                    $this->set('summary', $summary);
                    $this->set('_serialize', ['host', 'summary']);
                    return;
                }

                throw new NotFoundException('Host not found!');
                return;

            case 'service':
                $service = $this->Service->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Service.uuid'
                    ],
                    'contain'    => [
                        'Host' => [
                            'fields' => [
                                'Host.id',
                            ],
                            'Container',
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
                    //Service gine tun
                }
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus([]);
                $this->set('type', 'service');
                $this->set('servicestatus', $Servicestatus->toArray());
                $this->set('_serialize', ['type', 'servicestatus']);
                break;
            case 'hostgroup':
                break;
            case 'servicegroup':
                break;
            case 'map':
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

    }

}